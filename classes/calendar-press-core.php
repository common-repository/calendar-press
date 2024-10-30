<?php
/**
 * Core functionality for the plugin.
 * php version 8.1.10
 *
 * @category   WordPress_Template
 * @package    Calendar_Press
 * @subpackage Core
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}

/**
 * Some core code for the plugin.
 *
 * @category   WordPress_Template
 * @package    Calendar_Press
 * @subpackage Core
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
class CalendarPressCore
{
    var $menuName = 'calendar-press';
    var $pluginName = 'CalendarPress';
    var $version = '0.4.3';
    var $optionsName = 'calendar-press-options';
    var $xmlURL = 'http://grandslambert.com/xml/calendar-press/';
    var $in_shortcode = false;
    var $is_network = false;
    var $is_subdomain = false;
        
    /**
     * Initialize the plugin.
     */
    protected function __construct()
    {
        /* Load Language Files */
        $langDir = dirname(plugin_basename(__FILE__)) . '/lang';
        load_plugin_textdomain('calendar-press', false, $langDir, $langDir);
            
        /* Plugin Settings */
        /* translators: The name of the plugin, should be a translation of "CalendarPress" only! */
        $this->pluginName = __('CalendarPress', 'calendar-press');
        $this->pluginPath = WP_PLUGIN_DIR . '/' . basename(dirname(dirname(__FILE__)));
        $this->pluginURL = WP_PLUGIN_URL . '/' . basename(dirname(dirname(__FILE__)));
        $this->templatesPath = WP_PLUGIN_DIR . '/' . basename(dirname(dirname(__FILE__))) . '/templates/';
        $this->templatesURL = WP_PLUGIN_URL . '/' . basename(dirname(dirname(__FILE__))) . '/templates/';
            
        /* Check if loaded as a multisite network */
        if (!function_exists('is_plugin_active_for_network') ) {
            include_once ABSPATH . '/wp-admin/includes/plugin.php';
        }
        $this->is_network = is_plugin_active_for_network('calendar-press/calendar-press.php');
            
        /* Check if loaded as a subdomain install */
        if (!function_exists('is_subdomain_install') and file_exists(ABSPATH . 'wp-admin/includes/ms-load.php') ) {
            include_once ABSPATH . 'wp-admin/includes/ms-load.php';
        }
            
        if (function_exists('is_subdomain_install') ) {
            $this->is_subdomain = is_subdomain_install('calendar-press/calendar-press.php');
        }
            
            /* Load user settings */
            $this->loadSettings();
            $this->stateNames();
    }
        
    /**
     * Load plugin settings.
     * 
     * @return null
     */
    function loadSettings()
    {
        $defaults = array(
        /* Calendar Options */
        'use-plugin-permalinks' => false,
        'index-slug' => 'events',
        'identifier' => 'event',
        'permalink' => '%identifier%' . get_option('permalink_structure'),
        'plural-name' => __('Events', 'calendar-press'),
        'singular-name' => __('Event', 'calendar-press'),
        'registration-type' => 'none',
        /* Feature Usage */
        'use-signups' => true,
        'use-features' => true,
        'use-permalinks' => true,
        'use-register' => true,
        'use-display' => true,
        'use-widget' => true,
        'use-network' => $this->is_network,
        'use-administration' => true,
        'use-overflow' => false,
        'use-waiting' => false,
        'yes-option' => false,
        'no-option' => false,
        'maybe-option' => false,
        'use-locations' => false,
        'use-cookies' => false,
        'use-taxonomies' => false,
        'use-categories' => false, /* Depricated */
        'use-cuisines' => false, /* Depricated */
        'use-thumbnails' => false,
        'use-featured' => false,
        'use-popups' => false,
        'use-comments' => false,
        'use-trackbacks' => false,
        'use-custom-fields' => false,
        'use-revisions' => false,
        'use-post-categories' => false,
        'use-post-tags' => false,
        /* Taxonomy Defaults */
        'taxonomies' => array(
        'event-category' => array('plural' => __('Event Categories', 'calendar-press'), 'singular' => __('Event Category', 'calendar-press'), 'hierarchical' => true, 'active' => true, 'default' => false),
        'event-tag' => array('plural' => __('Event Tags', 'calendar-press'), 'singular' => __('Event Tag', 'calendar-press'), 'hierarchical' => false, 'active' => true, 'default' => false)
        ),
        /* Location Defaults */
        'location-slug' => 'event-locations',
        'location-identifier' => 'event-location',
        'location-permalink' => '%identifier%' . get_option('permalink_structure'),
        'location-plural-name' => __('Event Locations', 'calendar-press'),
        'location-singular-name' => __('Event Location', 'calendar-press'),
        /* Display Settings */
        'calendar-title' => __('Calendar of Events', 'calendar-press'),
        'user-display-field' => 'display_name',
        'signups-default' => 10,
        'signups-title' => 'Openings',
        'show-signup-date' => false,
        'signup-date-format' => get_option('date_format'),
        'overflow-default' => 2,
        'overflow-title' => 'Overflow',
        'waiting-default' => 10,
        'waiting-title' => 'Waiting List',
        'default-excerpt-length' => 20,
        'use-plugin-css' => false,
        'disable-filters' => false,
        'custom-css' => '',
        'box-width' => 85,
        /* Archive Options */
        'author-archives' => false,
        /* Widget Defaults */
        'widget-items' => 10,
        'widget-type' => 'next',
        'widget-sort' => 'asc',
        'widget-target' => 'none',
        'widget-show-icon' => false,
        'widget-icon-size' => 50,
        /* Form Options */
        'form-extension' => false,
        /* Network Settings */
        'dashboard-site' => get_site_option('dashboard_blog'),
        'move-events' => get_site_option('allow_move_events'),
        'share-events' => false,
        /* Non-Configural Settings */
        'menu-icon' => $this->pluginURL . '/images/icons/small_icon.png',
        'widget_before_count' => ' ( ',
        'widget_after_count' => ' ) ',
        );
            
        $this->options = wp_parse_args(get_option($this->optionsName), $defaults);
            
        if ($this->options['use-thumbnails'] ) {
            add_theme_support('post-thumbnails');
        }
            
        /* Eliminate individual taxonomies */
        if ($this->options['use-categories'] ) {
            $this->options['use-taxonomies'] = true;
            $this->options['taxonomies']['event-categories'] = array(
            'plural' => __('Categories', 'calendar-press'),
            'singular' => __('Category', 'calendar-press'),
            'hierarchical' => true,
            'active' => true,
            'page' => $this->options['categories-page'],
            'converted' => true
            );
        }
            
        if ($this->options['use-cuisines'] ) {
            $this->options['use-taxonomies'] = true;
            $this->options['taxonomies']['event-cuisines'] = array(
            'plural' => __('Cuisines', 'calendar-press'),
            'singular' => __('Cuisine', 'calendar-press'),
            'hierarchical' => false,
            'active' => true,
            'page' => $this->options['cuisines-page'],
            'converted' => true
            );
        }
    }
        
    /**
     * Function to provide an array of state names for future support of locations.
     * 
     * @return null
     */
    function stateNames()
    {
        $this->stateList = array(
        'AL' => 'Alabama',
        'AK' => 'Alaska',
        'AZ' => 'Arizona',
        'AR' => 'Arkansas',
        'CA' => 'California',
        'CO' => 'Colorado',
        'CT' => 'Connecticut',
        'DE' => 'Delaware',
        'DC' => 'District of Columbia',
        'FL' => 'Florida',
        'GA' => 'Georgia',
        'HI' => 'Hawaii',
        'ID' => 'Idaho',
        'IL' => 'Illinois',
        'IN' => 'Indiana',
        'IA' => 'Iowa',
        'KS' => 'Kansas',
        'KY' => 'Kentucky',
        'LA' => 'Louisiana',
        'ME' => 'Maine',
        'MD' => 'Maryland',
        'MA' => 'Massachusetts',
        'MI' => 'Michigan',
        'MN' => 'Minnesota',
        'MS' => 'Mississippi',
        'MO' => 'Missouri',
        'MT' => 'Montana',
        'NE' => 'Nebraska',
        'NV' => 'Nevada',
        'NH' => 'New Hampshire',
        'NJ' => 'New Jersey',
        'NM' => 'New Mexico',
        'NY' => 'New York',
        'NC' => 'North Carolina',
        'ND' => 'North Dakota',
        'OH' => 'Ohio',
        'OK' => 'Oklahoma',
        'OR' => 'Oregon',
        'PA' => 'Pennsylvania',
        'RI' => 'Rhode Island',
        'SC' => 'South Carolina',
        'SD' => 'South Dakota',
        'TN' => 'Tennessee',
        'TX' => 'Texas',
        'UT' => 'Utah',
        'VT' => 'Vermont',
        'VA' => 'Virginia',
        'WA' => 'Washington',
        'WV' => 'West Virginia',
        'WI' => 'Wisconsin',
        'WY' => 'Wyoming',
        );
    }
        
    /**
     * Gets the default settings for taxonomies.
     *
     * @param string $tax The slug for the taxonomy.
     * 
     * @return array
     */
    function taxDefaults($tax)
    {
        $defaults = array(
        'plural' => '',
        'singular' => '',
        'default' => false,
        'hierarchical' => false,
        'active' => false,
        'delete' => false,
        );
            
        return wp_parse_args($tax, $defaults);
    }
        
    /**
     * Display the month view on a page.
     *
     * @param int $month The month to display
     * @param int $year  The year to display
     *
     * @global object $wp
     * 
     * @return string
     */
    function showTheCalendar($month = null, $year = null)
    {
        global $wp;
            
        if ($month ) {
            $this->currMonth = $month;
        } else {
            $this->currMonth = date('m');
        }
            
        if ($year ) {
            $this->currYear = $year;
        } else {
            $this->currYear = date('Y');
        }
            
            $template = $this->getTemplate('event-calendar');
            
        if (!$month && isset($wp->query_vars['viewmonth']) ) {
            $this->currMonth = $wp->query_vars['viewmonth'];
        } elseif (!$month ) {
            $this->currMonth = isset($_COOKIE['cp_view_month']) 
                ? sanitize_text_field($_COOKIE['cp_view_month'])
                : date('m');
        }
            
        if (isset($wp->query_vars['viewyear']) ) {
                $this->currYear = $wp->query_vars['viewyear'];
        } elseif (!$year ) {
                $this->currYear = isset($_COOKIE['cp_view_year'])
                    ? sanitize_text_field($_COOKIE['cp_view_year'])
                    : date('Y');
        }
            
            /* Calculate for last month */
            $lastmonth = $this->currMonth - 1;
            $lastyear = $this->currYear;
        if ($lastmonth <= 0 ) {
                $lastmonth = 12;
                --$lastyear;
        }
            
            /* Calculate for next month */
            $nextmonth = $this->currMonth + 1;
            $nextyear = $this->currYear;
        if ($nextmonth > 12 ) {
                $nextmonth = 1;
                ++$nextyear;
        }
            
            /* Format dates */
            $today = min(date('d'), date('t', strtotime($this->currYear . '-' . $this->currMonth . '-' . 1)));
            $this->currDate = date('Y-m-d', strtotime($this->currYear . '-' . $this->currMonth . '-' . $today));
            $this->lastMonth = date('Y-m-d', strtotime($lastyear . '-' . $lastmonth . '-' . 1));
            $this->nextMonth = date('Y-m-d', strtotime($nextyear . '-' . $nextmonth . '-' . 1));
            
            ob_start();
            include $template;
            $content = ob_get_contents();
            ob_end_clean();
            
            return $content;
    }
        
    /**
     * Displays a list of events. Used by shortcodes.
     *
     * @param array $atts An array of attributes for the shortcode
     * 
     * @global object $calendarPressOBJ
     * @global array $customFields
     * @global integer $cp_widget_order
     * @global object $post
     * 
     * @return string
     */
    function showTheList($atts)
    {
        global $calendarPressOBJ, $customFields, $cp_widget_order, $post;
        $defaults = array(
        'posts_per_page' => 10,
        'template' => 'list-shortcode',
        'target' => ''
        );
            
        $atts = wp_parse_args($atts, $defaults);      
            
        /* Grab the posts for the widget */
        $posts = get_posts(
            array(
            'posts_per_page' => $atts['items'],
            'numberposts' => $atts['items'],
            'post_type' => 'event'
            )
        );
            
        $template = $calendarPressOBJ->getTemplate($atts['template']);
        ob_start();
        include $template;
        $content = ob_get_contents();
        ob_end_clean();
            
        //$post = $originalPost;
        return $content;
    }
        
    /**
     * Retrieve a template file from either the theme or the plugin directory.
     *
     * As of version 0.5.0 all files must be in a folder named 'calendar-press' within the theme.
     *
     * @param string $template The name of the template.
     * @param string $ext      File name extension
     * @param string $type     A URL or Path
     * 
     * @return string The full path to the template file.
     */
    function getTemplate($template = null, $ext = '.php', $type = 'path')
    {
        if ($template == null ) {
            return false;
        }
            
        /* Looks for template files in theme root - to be deprecated after version 0.5.0 */
        $themeFile = get_stylesheet_directory() . '/' . $template . $ext;
            
        /* Added in version 0.4.2 */
        if (!file_exists($themeFile) ) {
            $themeFile = get_stylesheet_directory() . '/calendar-press/' . $template . $ext;
        }
            
        if (file_exists($themeFile) and !$this->in_shortcode ) {
            if ($type == 'url' ) {
                    $file = get_bloginfo('template_url') . '/' . $template . $ext;
            } else {
                  $file = get_stylesheet_directory() . '/' . $template . $ext;
            }
        } elseif ($type == 'url' ) {
            $file = $this->templatesURL . $template . $ext;
        } else {
                $file = $this->templatesPath . $template . $ext;
        }
            
            return $file;
    }
        
    /**
     * Create a help icon on the administration pages.
     *
     * @param string $text Text to display for help.
     * 
     * @return null
     */
    function help($text)
    {
        //echo '<img src="' . $this->pluginURL . '/images/icons/help.jpg" align="absmiddle"onmouseover="return overlib(\'' . $text . '\');" onmouseout="return nd();" />';
    }
        
    /**
     * Displayes any data sent in textareas.
     *
     * @param array $input Items to display.
     * 
     * @return null
     */
    function debug($input)
    {
        $contents = func_get_args();
            
        foreach ( $contents as $content ) {
            print '<textarea style="width:49%; height:250px; float: left;">';
            print_r($content);
            print '</textarea>';
        }
            
        echo '<div style="clear: both"></div>';
    }
}