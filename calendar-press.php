<?php
/**
 * Calendar Press Plugin
 * 
 * @category Plugins
 * @package  CalendarPress
 * @author   Shane Lambert <grandslambert@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://grandslambert.com/plugins/calendar-press
 * 
 * Plugin Name: CalendarPress
 * Plugin URI: http://grandslambert.com/plugins/calendar-press
 * Description: Add an event calendar with details, directions, RSVP system and more.
 * Version: 1.0.0
 * Author: grandslambert
 * Author URI: http://grandslambert.com/
 * License: GPL-2.0+
 */
require_once 'classes/calendar-press-core.php';
require_once 'classes/initialize.php';
require_once 'includes/template_tags.php';
require_once 'includes/inflector.php';
require_once 'includes/multisite-support.php';

/**
 * Class for Calendar Press Plugin
 * 
 * @category   WordPress_Widget
 * @package    Calendar_Press
 * @subpackage Class
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
class Calendar_Press extends CalendarPressCore
{

    /**
     * Initialize the plugin.
     */
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('America/Chicago');

        /* Add actions */
        add_action('send_headers', array( &$this, 'cookies' ));
        add_action('wp_loaded', array( &$this, 'onWPLoad' ));
        add_action('wp_print_styles', array( &$this, 'cpPrintStyles' ));
        add_action('wp_print_scripts', array( &$this, 'cpPrintScripts' ));
        add_action('templateRedirect', array( &$this, 'templateRedirect' ));
        add_action('preGetEvents', array( &$this, 'preGetEvents' ));

        /* Add filters */
        add_filter('the_content', array( &$this, 'theContentFilter' ));
        add_filter('the_excerpt', array( &$this, 'theContentFilter' ));
        add_filter('query_vars', array( &$this, 'addQueryVars' ));

        /* Setup AJAX */
        add_action('wp_ajax_event_registration', array( &$this, 'registrations' ));
        add_action('wp_ajax_nopriv_event_registration', array( &$this, 'registrations' ));

        if (is_admin()) {
            include_once 'classes/administration.php';
            Calendar_Press_Admin::initialize();
        } else {
            include_once 'classes/shortcodes.php';
            CP_Shortcodes::initialize();
        }

        Calendar_Press_Init::initialize();
    }

    /**
     * Add cookies for calendar display.
     *
     * @global <type> $wp_query
     * 
     * @return null
     */
    function cookies()
    {
        global $wp_query;

        if ($this->options['use-cookies'] and (isset($_REQUEST['viewmonth']) or isset($_REQUEST['viewyear']))) {
            $url = parse_url(get_option('home'));
            if (! isset($url['path'])) {
                $url['path'] = '';
            }
            setcookie('cp_view_month', sanitize_text_field($_REQUEST['viewmonth']), time() + 3600, $url['path'] . DIRECTORY_SEPARATOR, $url['host']);
            setcookie('cp_view_year', sanitize_text_field($_REQUEST['viewyear']), time() + 3600, $url['path'] . DIRECTORY_SEPARATOR, $url['host']);
        }
    }

    /**
     * Method to be called when WordPress loads successfully
     * 
     * @return null
     */
    function onWPLoad()
    {
        /* Add CSS stylesheets */
        wp_register_style(
            'calendar-press-style', 
            $this->getTemplate('calendar-press', '.css', 'url')
        );
    }

    /**
     * Filter for adding styles.
     * 
     * @return null;
     */
    function cpPrintStyles()
    {
        wp_enqueue_style('calendar-press-style');
        ?>
        <style type="text/css" media="screen">
            .cp-box-width, dd.cp-month-box {
            width: <?php echo $this->options['box-width']; ?>px;
            }
        </style>
        <?php
    }

    /**
     * Filter for adding scripts
     * 
     * @return null
     */
    function cpPrintScripts()
    {
        wp_enqueue_script('sack');
        
        wp_enqueue_script(
            'calendar-press-script',
            plugins_url('js/calendar-press.js', __FILE__),
            array(),
            filemtime(plugin_dir_path(__FILE__) . 'js/calendar-press.js'),
        );
        
        wp_localize_script(
            'calendar-press-script', 
            'CPAJAX', array(
                'ajaxurl' => admin_url('admin-ajax.php')
            )
        );
        wp_enqueue_script(
            'calendar-press-encode',
            plugins_url('js/encode.js', __FILE__),
            array(),
            filemtime(plugin_dir_path(__FILE__) . 'js/encode.js'),
        );
    }

    /**
     * Adding query variables for the calendar.
     * 
     * @param array $qvars Existing allowed query variables.$this
     * 
     * @return array
     */
    function addQueryVars($qvars)
    {
        $qvars[] = 'viewmonth';
        $qvars[] = 'viewyear';
        return $qvars;
    }

    /**
     * Function called before calling events query.
     * 
     * @param object $query The WP Query object
     * 
     * @return object
     */
    public function preGetEvents($query)
    {
        if (! is_admin()) {
            if (array_key_exists('post_type', $query->query) && $query->query['post_type'] === 'event') {
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'ASC');
                $query->set('meta_key', '_begin_time_value');

                if (! $query->is_single()) {
                    if (sizeof($query->query_vars['meta_query']) < 2) {
                        $meta_query = array_merge(
                            (array) $query->query_vars['meta_query'], array(
                            array(
                                'key' => '_begin_date_value',
                                'value' => strtotime('-1 day'),
                                'type' => 'string',
                                'compare' => '>='
                            )
                            )
                        );
                        $query->set('meta_query', $meta_query);
                    }
                }
            }
        }
        return $query;
    }

    /**
     * Template redirect
     * 
     * @return null;
     */
    function templateRedirect()
    {
        global $post, $wp;

        if (isset($wp->query_vars['post_type']) and $wp->query_vars['post_type'] == 'event' and ! is_single()) {
            $template = $this->getTemplate('index-event');
            include $template;
            exit();
        }
    }

    /**
     * Filter the contents
     * 
     * @param string $content The content to filter
     * 
     * @return string
     */
    function theContentFilter($content)
    {
        global $post, $wp, $current_user;
        get_currentuserinfo();

        if ($this->in_shortcode) {
            return $content;
        }

        $files = get_theme(get_option('current_theme'));

        if (is_single()) {
            $template_file = get_stylesheet_directory() . '/single-event.php';
        } elseif (is_archive()) {
            $template_file = get_stylesheet_directory() . '/archive-event.php';
        } else {
            $template_file = get_stylesheet_directory() . '/index-event.php';
        }
        if ($post->post_type != 'event' or in_array($template_file, $files['Template Files']) or $this->in_shortcode) {
            return $content;
        }

        remove_filter(
            'the_content', array(
            &$this,
            'theContentFilter'
            )
        );

        if (is_archive()) {
            $template = $this->getTemplate('loop-event');
        } elseif (is_single()) {
            $template = $this->getTemplate('single-event');
        } elseif ($post->post_type == 'event' and in_the_loop()) {
            $template = $this->getTemplate('loop-event');
        }

        ob_start();
        include $template;
        $content = ob_get_contents();
        ob_end_clean();

        add_filter(
            'the_content', array(
            &$this,
            'theContentFilter'
            )
        );

        return $content;
    }

    /**
     * Ajax method for handling registrations.
     *
     * @global object $current_user
     * 
     * @return null
     */
    function registrations()
    {
        global $current_user, $post;
        get_currentuserinfo();
        $type = sanitize_text_field($_POST['type']);
        $id = sanitize_text_field($_POST['id']);
        $post = get_post($id);
        $action = sanitize_text_field($_POST['click_action']);
        $event = get_post($id);
        $meta_prefix = '_event_registrations_';

        switch ($action) {
        case 'yesno':
            $responses = array(
                'yes' => __('are attending', 'Calendar_Press'),
                'no' => __('are not attending', 'Calendar_Press'),
                'maybe' => __('might attend', 'calendar_presss')
            );

            $registrations = get_post_meta($id, $meta_prefix . 'yesno', true);

            if (! is_array($registrations)) {
                $registrations = array();
            }

            if (array_key_exists($current_user->ID, $registrations)) {
                if ($registrations[$current_user->ID]['type'] == $type) {
                    $message = sprintf(__('You have already indicated that you %1$s this event.', 'calendar-press'), $responses[$type]);
                    $status = 'Duplicate';
                } else {
                    $oldType = $registrations[$current_user->ID]['type'];
                    $registrations[$current_user->ID]['type'] = $type;
                    $message = sprintf(__('You have changed your response from %1$s to %2$s this event.', 'calendar-press'), $responses[$oldType], $responses[$type]);
                    $status = 'Duplicate';
                }
            } else {
                $registrations[$current_user->ID] = array(
                    'type' => $type,
                    'date' => current_time('timestamp')
                );
                $message = sprintf(__('You have indicated that you %1$s this event.', 'calendar-press'), $responses[$type]);
                $status = 'Registered';
            }

            $results = update_post_meta($id, $meta_prefix . 'yesno', $registrations);

            break;
        case 'delete':
            $action = 'signups';
            $registrations = get_post_meta($id, $meta_prefix . $type, true);

            if (array_key_exists($current_user->ID, $registrations)) {
                unset($registrations[$current_user->ID]);
            }

            $results = update_post_meta($id, $meta_prefix . $type, $registrations);

            if ($results) {
                $message = __('Your registration for ' . $event->post_title . ' has been canceled', 'calendar-press');
                $status = 'Cancelled';
            } else {
                $status = 'Error';
                $message = __('Sorry, I was unable to remove your registration for ' . $event->post_title . '. Pleae try again later.', 'calendar-press');
            }
            break;
        case 'move':
            $action = 'signups';
            $alt = array(
                'signups' => 'overflow',
                'overflow' => 'signups'
            );

            $registrations = get_post_meta($id, $meta_prefix . $alt[$type], true);

            if (array_key_exists($current_user->ID, $registrations)) {
                $original_date = $registrations[$current_user->ID]['date'];
                unset($registrations[$current_user->ID]);
                if (count($registrations) < 1) {
                    delete_post_meta($id, $meta_prefix . $alt[$type]);
                } else {
                    update_post_meta($id, $meta_prefix . $alt[$type], $registrations);
                }
            }

            /* Add new registration */
            $registrations = get_post_meta($id, $meta_prefix . $type, true);
            $registrations[$current_user->ID] = array(
                'date' => ($original_date) ? $original_date : current_time('timestamp')
            );

            $results = update_post_meta($id, $meta_prefix . $type, $registrations);

            if ($results) {
                $message = __('Your registration for ' . $event->post_title . ' has been moved to the ' . $this->options[$type . '-title'], 'calendar-press');
                $status = 'Moved';
            } else {
                $status = 'Error';
                $message = __('Sorry, I was unable to remove your registrationr for ' . $event->post_title . '. Pleae try again later.', 'calendar-press');
            }
            break;
        default:
            $action = 'signups';
            $registrations = get_post_meta($id, $meta_prefix . $type, true);

            if (! is_array($registrations)) {
                $registrations = array(
                    $registrations
                );
            }

            if (array_key_exists($current_user->id, $registrations)) {
                $message = __('You are already registered on the ' . $this->options[$type . '-title'] . '  for the ' . $event->post_title . '.', 'calendar-press');
                $status = 'Registered';
            } else {
                $registrations[$current_user->ID] = array(
                    'date' => current_time('timestamp')
                );
                unset($registrations[0]);
                $results = update_post_meta($id, $meta_prefix . $type, $registrations);
                $message = __('You are now registered on the ' . $this->options[$type . '-title'] . ' for the ' . $event->post_title . '.', 'calendar-press');
                $status = 'Registered';
            }
        }

        ob_start();
        include $this->getTemplate('registration-' . $action);
        $results = ob_get_contents();
        ob_end_clean();

        die('onSackSuccess("' . $status . '","' . $type . '", ' . $id . ', "' . esc_js($results) . '")');
    }
}

/* Instantiate the Plugin */
$calendarPressOBJ = new Calendar_Press();

/* Add Widgets */
require_once 'widgets/list-widget.php';
// require_once('widgets/category-widget.php');

/* Activation Hook */
register_activation_hook(__FILE__, 'Calendar_Press_activation');

/**
 * Function to be called when plugin is activated
 * 
 * @return null
 */
function Calendar_Press_activation()
{
    global $wpdb;

    /* Set old posts to singular post type name */
    if (! post_type_exists('events')) {
        $wpdb->update(
            $wpdb->prefix . 'posts', array(
            'post_type' => 'event'
            ), array(
            'post_type' => 'events'
            )
        );
    }

    /* Rename the built in taxonomies to be singular names */
    $wpdb->update(
        $wpdb->prefix . 'term_taxonomy', array(
        'taxonomy' => 'event-category'
        ), array(
        'taxonomy' => 'event-categories'
        )
    );

    $wpdb->update(
        $wpdb->prefix . 'term_taxonomy', array(
        'taxonomy' => 'event-tag'
        ), array(
        'taxonomy' => 'event-tags'
        )
    );
}        
