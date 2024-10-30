<?php
/**
 * Calendar Press Administration
 * php version 8.1.10
 *
 * @category   WordPress_Template
 * @package    Calendar_Press
 * @subpackage Administration
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}

/**
 * Methods for plugin administration.
 * 
 * @category   WordPress_Template
 * @package    Calendar_Press
 * @subpackage Initialize
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
class Calendar_Press_Admin extends CalendarPressCore
{
        
    static $instance;
        
    /**
     * Initialize the plugin.
     */
    function __construct()
    {
        parent::__construct();
            
        /* Add actions */
        add_action('admin_menu', array(&$this, 'addAdminPages'));
        add_action('admin_init', array(&$this, 'adminInit'));
        add_action('admin_print_styles', array(&$this, 'adminPrintStyles'));
        add_action('admin_print_scripts', array(&$this, 'adminPrintScripts'));
        add_action('save_post', array(&$this, 'saveEvent'));
        add_action('update_option_' . $this->optionsName, array(&$this, 'updateOptions'));
        add_action('dashboard_glance_items', array(&$this, 'dashboardGlanceItems'));
            
        /* Add filters */
        add_filter('plugin_action_links', array(&$this, 'addLinksOnPluginPage'), 10, 2);
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
            self::$instance = new Calendar_Press_Admin;
        }
        return self::$instance;
    }
        
    /**
     * Add the number of events to the Right Now on the Dasboard.
     * 
     * @return null
     */
    public function dashboardGlanceItems()
    {
        if (!post_type_exists('event') ) {
            return false;
        }
            
        /* Show for events */
        $num_posts = wp_count_posts('event');
        $num = number_format_i18n($num_posts->publish);
        $text = _n('Event', 'Events', intval($num_posts->publish));
        if (current_user_can('edit_posts')) {
            echo '<li class="page-count event-count"><a href="edit.php?post_type=event">' . $num . ' ' . $text . '</a></td>';
        }
    }
        
    /**
     * Add the admin page for the settings panel.
     *
     * @global string $wp_version
     * 
     * @return null
     */
    function addAdminPages()
    {
        $pages = array();
            
        $pages[] = add_submenu_page('edit.php?post_type=event', $this->pluginName . __(' Settings', 'calendar-press'), __('Settings', 'calendar-press'), 'manage_options', 'calendar-press-settings', array(&$this, 'settings'));
            
        foreach ( $pages as $page ) {
            add_action('admin_print_styles-' . $page, array(&$this, 'adminStyles'));
            add_action('admin_print_scripts-' . $page, array(&$this, 'adminScripts'));
        }
    }
        
    /**
     * Register the options
     * 
     * @return null
     */
    function adminInit()
    {
        global $wp_version;
            
        register_setting($this->optionsName, $this->optionsName);
        wp_register_style('calendar-press-admin-css', $this->pluginURL . '/includes/calendar-press-admin.css');
        wp_register_style('calendar-press-datepicker-css', $this->getTemplate('datepicker', '.css', 'url'));
        wp_register_script('calendar-press-admin-js', $this->pluginURL . '/js/calendar-press-admin.js');
    }
        
    /**
     * Print admin stylesheets while editting events.
     *
     * @global object $post
     * 
     * @return null
     */
    function adminPrintStyles()
    {
        global $post;
            
        if (is_object($post) and $post->post_type == 'event' ) {
            $this->adminStyles();
            wp_enqueue_style('calendar-press-datepicker-css');
        }
    }
        
    /**
     * Print admin javascripts while editting posts.
     *
     * @global object $post
     * 
     * @return null
     */
    function adminPrintScripts()
    {
        global $post;
            
        if (is_object($post) and $post->post_type == 'event' ) {
            $this->adminScripts();
            wp_enqueue_script('calendar-press-datepicker-js');
        }
    }
        
    /**
     * Print admin stylesheets for all plugin pages.
     * 
     * @return null
     */
    function adminStyles()
    {
        wp_enqueue_style('calendar-press-admin-css');
    }
        
    /**
     * Print admin javascripts for all plugin pages.
     * 
     * @return null
     */
    function adminScripts()
    {
        wp_enqueue_script('calendar-press-admin-js');
    }
        
    /**
     * Add a configuration link to the plugins list.
     *
     * @param array  $links Existing links
     * @param string $file  Current file.
     * 
     * @staticvar object $this_plugin
     * 
     * @return array
     */
    function addLinksOnPluginPage($links, $file)
    {
        static $this_plugin;
            
        if (!$this_plugin ) {
            $this_plugin = dirname(dirname(plugin_basename(__FILE__)));
        }
            
        if (dirname($file) == $this_plugin ) {
            $settings_link = '<a href="' . get_admin_url() . 'edit.php?post_type=event&page=calendar-press-settings">' . __('Settings', 'calendar-press') . '</a>';
            array_unshift($links, $settings_link);
        }
            
        return $links;
    }
        
    /**
     * Settings management panel.
     * 
     * @return null
     */
    function settings()
    {
        global $blog_id, $wp_version;
        include $this->pluginPath . '/includes/settings.php';
    }
        
    /**
     * Check on update option to see if we need to create any pages.
     *
     * @param array $input The options array.
     * 
     * @return null
     */
    function updateOptions($input)
    {
        if ($_REQUEST['confirm-reset-options'] ) {
            delete_option($this->optionsName);
            wp_redirect(admin_url('edit.php?post_type=event&page=calendar-press-settings&tab=' . sanitize_text_field($_POST['active_tab']) . '&reset=true'));
            exit();
        } else {
            if ($_POST['dashboard_site'] != get_site_option('dashboard_blog') ) {
                update_site_option('dashboard_blog', sanitize_text_field($_POST['dashboard_site']));
            }
                
            if ($_POST['allow_moving_events'] ) {
                    add_site_option('allow_moving_events', true);
            } else {
                  delete_site_option('allow_moving_events', true);
            }
                
            wp_redirect(admin_url('edit.php?post_type=event&page=calendar-press-settings&tab=' . sanitize_text_field($_POST['active_tab']) . '&updated=true'));
                exit();
        }
    }
        
    /**
     * Save the meta boxes for a event.
     *
     * @param int $post_id The post ID.
     * 
     * @global object $post
     * 
     * @return integer
     */
    function saveEvent($post_id)
    {
        global $post;
            
        /* Save the dates */
        if (isset($_POST['dates_noncename']) AND wp_verify_nonce($_POST['dates_noncename'], 'calendar_press_dates') ) {
                
                $defaults = array(
                    'open_date' => time(),
                    'open_date_display' => '',
                    'begin_date' => time(),
                    'end_date' => time(),
                    'end_time' => time()
                );
                $details = wp_parse_args($_POST['event_dates'], $defaults);
                
                foreach ( $details as $key => $value ) {
                    $value = sanitize_text_field($value);
                    
                    list($type, $field) = explode('_', $key);
                    
                    if ($field == 'time' ) {
                        $meridiem = sanitize_text_field($_POST[$type . '_meridiem']);
                        $minutes = sanitize_text_field($_POST[$type . '_time_minutes']);
                        if ($meridiem === 'pm' && $value != 12 ) {
                            $value = $value + 12;
                        }
                        $dateType = "{$type}Date";
                        $value = $$dateType . ' ' . $value . ':' . $minutes;
                    }
                    
                    if ($key == 'begin_date' ) {
                        $beginDate = $value;
                    }
                    
                    if ($key == 'begin_time' ) {
                        $beginTime = $value;
                    }
                    
                    if ($key == 'end_date' ) {
                        $endDate = $value;
                        $value = $beginDate;
                    }
                    
                    if ($key == 'end_time' ) {
                        $endTime = $value;
                    }
                    
                    if ($key != 'open_date_display') {
                        $value = strtotime($value);
                    }
                    $key = '_' . $key . '_value';
                    
                    if (get_post_meta($post_id, $key) == "" ) {
                        add_post_meta($post_id, $key, sanitize_text_field($value), true);
                    } elseif ($value != get_post_meta($post_id, $key . '_value', true) ) {
                        update_post_meta($post_id, $key, sanitize_text_field($value));
                    } elseif ($value == "" ) {
                        delete_post_meta($post_id, $key, get_post_meta($post_id, $key, true));
                    }
                }
                
        }
            
        /* Save the details */
        if (isset($_POST['details_noncename']) AND wp_verify_nonce($_POST['details_noncename'], 'calendar_press_details') ) {
            $input = $_POST['event_details'];
                
            $details = array('featured', 'popup');
                
            foreach ( $details as $detail ) {
                    
                    $key = '_event_' . $detail . '_value';
                    
                if (isset($input['event_' . $detail]) ) {
                    $value = sanitize_text_field($input['event_' . $detail]);
                } else {
                    $value = false;
                }
                    
                if (get_post_meta($post_id, $key) == "" ) {
                    add_post_meta($post_id, $key, $value, true);
                } elseif ($value != get_post_meta($post_id, $key, true) ) {
                    update_post_meta($post_id, $key, $value);
                } elseif ($value == false ) {
                    delete_post_meta($post_id, $key, get_post_meta($post_id, $key, true));
                }
            }
        }
            
        /* Save the signups information */
        if (isset($_POST['signups_noncename']) AND wp_verify_nonce($_POST['signups_noncename'], 'calendar_press_signups') ) {
            $input = $_POST['event_signups'];
                
            $fields = array('registration_type', 'event_signups', 'event_overflow', 'yes_option', 'no_option', 'maybe_option');
                
            foreach ( $fields as $field ) {
                    
                $key = '_' . $field . '_value';
                    
                if (isset($input[$field]) ) {
                    $value = sanitize_text_field($input[$field]);
                } else {
                    $value = false;
                }
                    
                if (get_post_meta($post_id, $key) == "" ) {
                     add_post_meta($post_id, $key, $value, true);
                } elseif ($value != get_post_meta($post_id, $key, true) ) {
                    update_post_meta($post_id, $key, $value);
                } elseif ($value == "" ) {
                    delete_post_meta($post_id, $key, get_post_meta($post_id, $key, true));
                }
            }
        }
            
        /* Flush the rewrite rules */
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
        
        return $post_id;
    }   
}    