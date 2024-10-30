<?php
/**
 * Shortcodes
 * php version 8.1.10
 *
 * @category   WordPress_Template
 * @package    Calendar_Press
 * @subpackage Shortcodes
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}

/**
 * Calendar Press Shortcodes
 * 
 * @category   WordPress_Shortcodes
 * @package    Calendar_Press
 * @subpackage Shortcodes
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
class CP_Shortcodes extends CalendarPressCore
{

     static $instance;

     /**
      * Initialize the plugin.
      */
    function __construct()
    {
         parent::__construct();

         /* Add Shortcodes */
         add_shortcode('event-calendar', array(&$this, 'calendarShortcode'));
         add_shortcode('event-list', array(&$this, 'listShortcode'));
         add_shortcode('event-show', array(&$this, 'showShortcode'));

         /* Deprciated shortcoces */
         add_shortcode('calendarpress', array(&$this, 'calendarShortcode'));
         add_shortcode('calendar-press', array(&$this, 'calendarShortcode'));
    }

     /**
      * Initialize the administration area.
      * 
      * @return null
      */
    public static function initialize()
    {
         $instance = self::getInstance();
    }

     /**
      * Returns singleton instance of object
      *
      * @return object
      */
    protected static function getInstance()
    {
        if (is_null(self::$instance) ) {
             self::$instance = new CP_Shortcodes;
        }
         return self::$instance;
    }

     /**
      * Calendar View shortcocde.
      * 
      * @param array $atts An array of attributes.
      *
      * @global object $wp
      * @global object $calendarPressOBJ
      * @return string
      */
    function calendarShortcode($atts)
    {
         global $wp, $calendarPressOBJ;

         $this->in_shortcode = true;

         $defaults = array(
              'scope' => 'month',
              'element' => 'li',
              'title' => $this->options['calendar-title'],
              'hide-title' => false,
              'title-class' => 'calendar-press-title',
              'title-id' => 'calendar-press-title',
              'month' => date('m'),
              'year' => date('Y'),
         );

         $atts = wp_parse_args($atts, $defaults);

         if (isset($atts['type']) and $atts['type'] == 'list' ) {
              /* Make this shortcode backward compatible */
              $this->listShortcode($atts);
         } else {

             if (isset($wp->query_vars['viewmonth']) ) {
                  $atts['month'] = $wp->query_vars['viewmonth'];
             }
             if (isset($wp->query_vars['viewyear']) ) {
                  $atts['year'] = $wp->query_vars['viewyear'];
             }

             if (!$atts['hide-title'] and $atts['title'] ) {
                  $title = '<h3 id="' . $atts['title-id'] . '" class="' . $atts['title-class'] . '">' . $atts['title'] . '</h3>';
             } else {
                  $title == '';
             }

              $output = $calendarPressOBJ->showTheCalendar($atts['month'], $atts['year'], $this);
              $this->in_shortcode = false;

              return $title . $output;
         }
    }

     /**
      * Event list shortcocde.
      *
      * @param array $atts An array of shortcode attributes
      * 
      * @global object $wp               The WordPress Object
      * @global object $calendarPressOBJ The CP Object
      * 
      * @return string
      */
    function listShortcode($atts)
    {
         global $wp, $calendarPressOBJ;

         $this->in_shortcode = true;

         $defaults = array(
              'scope' => 'month',
              'element' => 'li',
              'title' => $this->options['calendar-title'],
              'hide-title' => false,
              'title-class' => 'calendar-press-title',
              'title-id' => 'calendar-press-title',
              'month' => date('m'),
              'year' => date('Y'),
         );

         $atts = wp_parse_args($atts, $defaults);

         if (isset($wp->query_vars['viewmonth']) ) {
              $atts['month'] = $wp->query_vars['viewmonth'];
         }
         if (isset($wp->query_vars['viewyear']) ) {
              $atts['year'] = $wp->query_vars['viewyear'];
         }

         if (!$atts['hide-title'] and $atts['title'] ) {
              $title = '<h3 id="' . $atts['title-id'] . '" class="' . $atts['title-class'] . '">' . $atts['title'] . '</h3>';
         } else {
              $title == '';
         }

         $output = $calendarPressOBJ->showTheList($atts);
         $this->in_shortcode = false;

         return $title . $output;
    }

    /**
     * Show the shortcode
     * 
     * @param array $atts Attributes for the shortcode
     * 
     * @return string
     */
    function showShortcode($atts)
    {
         global $wpdb, $post, $calendarPressOBJ;
         $tmp_post = $post;
         $calendarPressOBJ->in_shortcode = true;

         $defaults = array(
              'event' => null,
              'template' => 'single-event',
         );

         $atts = wp_parse_args($atts, $defaults);

         if (!$atts['event'] ) {
              return __('Sorry, no event found', 'calendar-press');
         }

         $post = get_post($wpdb->get_var('select `id` from `' . $wpdb->prefix . 'posts` where `post_name` = "' . $atts['event'] . '" and `post_status` = "publish" limit 1'));
         setup_postdata($post);

         ob_start();
         include $this->getTemplate($atts['template']);
         $output = ob_get_contents();
         ob_end_clean();

         $post = $tmp_post;
         $calendarPressOBJ->in_shortcode = false;

         return $output;
    }

}