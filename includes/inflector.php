<?php
/**
 * Helper file for changing the inflection of text.
 * php version 8.1.10
 *
 * @category   WordPress_Template
 * @package    Calendar_Press
 * @subpackage Helper
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}

/**
 * Class for the Calendar Press Inflector
 * 
 * @category   WordPress_Helper
 * @package    Calendar_Press
 * @subpackage Helper
 * @author     Shane Lambert <grandslamber@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
class CP_Inflector
{
    // Cached inflections
    protected static $cache = array();

    // Uncountable and irregular words
    protected static $uncountable;
    protected static $irregular;

    /**
     * Checks if a word is defined as uncountable.
     *
     * @param string $str word to check
     * 
     * @return boolean
     */
    public static function uncountable($str)
    {
        if (self::$uncountable === null) {
            // Cache uncountables
            self::$uncountable = self::cacheUncountable();

            // Make uncountables mirroed
            self::$uncountable = array_combine(self::$uncountable, self::$uncountable);
        }

        return isset(self::$uncountable[strtolower($str)]);
    }

    /**
     * A cache of uncountable words
     * 
     * @return string[]
     */
    public static function cacheUncountable()
    {
        return array (
            'access',
            'advice',
            'art',
            'baggage',
            'dances',
            'equipment',
            'fish',
            'fuel',
            'furniture',
            'food',
            'heat',
            'honey',
            'homework',
            'impatience',
            'information',
            'knowledge',
            'luggage',
            'money',
            'music',
            'news',
            'patience',
            'progress',
            'pollution',
            'research',
            'rice',
            'sand',
            'series',
            'sheep',
            'sms',
            'species',
            'toothpaste',
            'traffic',
            'understanding',
            'water',
            'weather',
            'work',
        );
    }

    /**
     * A cache of irregular words
     * 
     * @return string[]
     */
    public static function cacheIrregular()
    {
        return array (
            'child' => 'children',
            'clothes' => 'clothing',
            'man' => 'men',
            'movie' => 'movies',
            'person' => 'people',
            'woman' => 'women',
            'mouse' => 'mice',
            'goose' => 'geese',
            'ox' => 'oxen',
            'leaf' => 'leaves',
            'whole' => 'whole',
        );
    }

    /**
     * Makes a plural word singular.
     *
     * @param string  $str   word to singularize
     * @param integer $count number of things
     * 
     * @return string
     */
    public static function singular($str, $count = null)
    {
        // Remove garbage
        $str = strtolower(trim($str));

        if (is_string($count)) {
            // Convert to integer when using a digit string
            $count = (int) $count;
        }

        // Do nothing with a single count
        if ($count === 0 OR $count > 1) {
            return $str;
        }

        // Cache key name
        $key = 'singular_'.$str.$count;

        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        if (rp_inflector::uncountable($str)) {
            return self::$cache[$key] = $str;
        }

        if (empty(self::$irregular)) {
            // Cache irregular words
            self::$irregular = self::cacheIrregular();
        }

        if ($irregular = array_search($str, self::$irregular)) {
            $str = $irregular;
        } elseif (preg_match('/[sxz]es$/', $str) OR preg_match('/[^aeioudgkprt]hes$/', $str)) {
            // Remove "es"
            $str = substr($str, 0, -2);
        } elseif (preg_match('/[^aeiou]ies$/', $str)) {
            $str = substr($str, 0, -3).'y';
        } elseif (substr($str, -1) === 's' AND substr($str, -2) !== 'ss') {
            $str = substr($str, 0, -1);
        }

        return self::$cache[$key] = $str;
    }

    /**
     * Makes a singular word plural.
     *
     * @param string $str   word to pluralize
     * @param int    $count Count of string.
     * 
     * @return string
     */
    public static function plural($str, $count = null)
    {
        // Remove garbage
        $str = strtolower(trim($str));

        if (is_string($count)) {
            // Convert to integer when using a digit string
            $count = (int) $count;
        }

        // Do nothing with singular
        if ($count === 1) {
            return $str;
        }

        // Cache key name
        $key = 'plural_'.$str.$count;

        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        if (rp_inflector::uncountable($str)) {
            return self::$cache[$key] = $str;
        }

        if (empty(self::$irregular)) {
            // Cache irregular words
            self::$irregular = self::cacheIrregular();
        }

        if (isset(self::$irregular[$str])) {
            $str = self::$irregular[$str];
        } elseif (preg_match('/[sxz]$/', $str) OR preg_match('/[^aeioudgkprt]h$/', $str)) {
            $str .= 'es';
        } elseif (preg_match('/[^aeiou]y$/', $str)) {
            // Change "y" to "ies"
            $str = substr_replace($str, 'ies', -1);
        } else {
            $str .= 's';
        }

        // Set the cache and return
        return self::$cache[$key] = $str;
    }

    /**
     * Makes a phrase camel case.
     *
     * @param string $str phrase to camelize
     * 
     * @return string
     */
    public static function camelize($str)
    {
        $str = 'x'.strtolower(trim($str));
        $str = ucwords(preg_replace('/[\s_]+/', ' ', $str));

        return substr(str_replace(' ', '', $str), 1);
    }

    /**
     * Makes a phrase underscored instead of spaced.
     *
     * @param string $str phrase to underscore
     * 
     * @return string
     */
    public static function underscore($str)
    {
        return strtolower(preg_replace('/\s+/', '_', trim($str)));
    }

    /**
     * Makes an underscored or dashed phrase human-reable.
     *
     * @param string $str phrase to make human-reable
     * 
     * @return string
     */
    public static function humanize($str)
    {
        return preg_replace('/[_-]+/', ' ', trim($str));
    }

    /**
     * Helper to trim an excerpt
     * 
     * @param string $text         The text to trim.
     * @param int    $length       Length of the excerpt
     * @param string $suffix       Text for the end of the excerpt
     * @param string $allowed_tags Comma Separated list of html tags
     * 
     * @return string|mixed
     */
    public static function trimExcerpt($text, $length = null, $suffix = '...', $allowed_tags = 'p')
    {
        global $post;
        $allowed_tags_formatted = '';

        $tags = explode(',', $allowed_tags);

        foreach ($tags as $tag) {
            $allowed_tags_formatted.= '<'. $tag . '>';
        }

        if (!$length) {
            //return $text;
        }

        $text = str_replace(']]>', ']]&gt;', $text);
        $text = strip_tags($text, $allowed_tags_formatted);
        $text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
        $words = explode(' ', $text, $length + 1);
        if (count($words) > $length) {
            array_pop($words);
            array_push($words, $suffix);
            $text = implode(' ', $words);
        }

        $tags = explode(',', $allowed_tags);
        foreach ($tags as $tag) {
            $text.= '</' . $tag . '>';
        }

        return $text;
    }
} // End inflector