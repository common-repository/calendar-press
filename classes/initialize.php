<?php
/**
 * Calendar Press Initialization
 * php version 8.1.10
 *
 * @category   WordPress_Template
 * @package    Calendar_Press
 * @subpackage Initialize
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}

/**
 * Class to initialize the plugin.
 * 
 * @category   WordPress_Template
 * @package    Calendar_Press
 * @subpackage Initialize
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
class Calendar_Press_Init extends CalendarPressCore
{
        
    static $instance;
        
    /**
     * Initialize the class.
     *
     * @global object $wpdb
     */
    public function __construct()
    {
        parent::__construct();
            
        add_action('init', array($this, 'setupTaxonomies'));
        add_action('init', array($this, 'createEventType'));
            
        add_filter('manage_edit-event_columns', array(&$this, 'eventEditColumns'));
        add_action("manage_posts_custom_column", array(&$this, 'eventCustomColumns'));
        add_action('admin_bar_menu', array(&$this, 'addAdminBarItem'), 500);
            
        if ($this->options['use-post-categories'] ) {
            register_taxonomy_for_object_type('post_categories', 'event');
        }
            
        if ($this->options['use-post-tags'] ) {
            register_taxonomy_for_object_type('post_tag', 'event');
        }
    }
        
    /**
     * Initialize the shortcodes.
     * 
     * @return null
     */
    static function initialize()
    {
        $instance = self::getInstance();
    }
        
    /**
     * Returns singleton instance of object
     *
     * @return object
     */
    static function getInstance()
    {
        if (is_null(self::$instance) ) {
            self::$instance = new Calendar_Press_Init();
        }
        return self::$instance;
    }
        
    /**
     * Add the Events to the Admin Bar
     * 
     * @param WP_Admin_Bar $admin_bar The admin bar object
     * 
     * @return null
     */
    function addAdminBarItem( WP_Admin_Bar $admin_bar )
    {
        if (! current_user_can('manage_options') || is_admin() ) {
            return;
        }
            
        $menu_options = array(
        'id'    => 'event-manager',
        'parent' => 'site-name',
        'group'  => null,
        'title' => __('Events', 'calendar-press'),
        'href'  => admin_url('edit.php?post_type=event')
        );
            
        $admin_bar->add_menu($menu_options);
    }
        
    /**
     * Register the post type for the plugin.
     *
     * @global object $wp_version
     * @global $wp_version $wp_rewrite
     * 
     * @return null
     */
    function createEventType()
    {
        global $wp_version;
            
        $labels = array(
        'name' => $this->options['plural-name'],
        'singular_name' => $this->options['singular-name'],
        'add_new' => __('New Event', 'calendar-press'),
        'add_new_item' => sprintf(__('Add New %1$s', 'calendar-press'), $this->options['singular-name']),
        'edit_item' => sprintf(__('Edit %1$s', 'calendar-press'), $this->options['singular-name']),
        'edit' => __('Edit', 'calendar-press'),
        'new_item' => sprintf(__('New %1$s', 'calendar-press'), $this->options['singular-name']),
        'view_item' => sprintf(__('View %1$s', 'calendar-press'), $this->options['singular-name']),
        'search_items' => sprintf(__('Search %1$s', 'calendar-press'), $this->options['singular-name']),
        'not_found' => sprintf(__('No %1$s found', 'calendar-press'), $this->options['plural-name']),
        'not_found_in_trash' => sprintf(__('No %1$s found in Trash', 'calendar-press'), $this->options['plural-name']),
        'view' => sprintf(__('View %1$s', 'calendar-press'), $this->options['singular-name']),
        'parent_item_colon' => ''
        );
        $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'description' => __('Post type created by CalendarPress for events.', 'calendar-press'),
        'menu_position' => 5,
        'menu_icon' => $this->options['menu-icon'],
        'supports' => array('title', 'editor', 'author', 'excerpt'),
        'register_meta_box_cb' => array(&$this, 'initMetaboxes'),
        );
            
        if ($this->options['use-custom-fields'] ) {
            $args['supports'][] = 'custom-fields';
        }
            
        if ($this->options['use-thumbnails'] ) {
            $args['supports'][] = 'thumbnail';
        }
            
        if ($this->options['use-comments'] ) {
            $args['supports'][] = 'comments';
        }
            
        if ($this->options['use-trackbacks'] ) {
            $args['supports'][] = 'trackbacks';
        }
            
        if ($this->options['use-revisions'] ) {
            $args['supports'][] = 'revisions';
        }
            
        if ($this->options['use-post-tags'] ) {
            $args['taxonomies'][] = 'post_tag';
        }
            
        if ($this->options['use-post-categories'] ) {
            $args['taxonomies'][] = 'category';
        }
            
        register_post_type('event', $args);
    }
        
    /**
     * Adds extra columns to the edit screen.
     *
     * @param array $columns An array of columns for the admin screen.
     * 
     * @return string
     */
    function eventEditColumns($columns)
    {
        $columns = array(
        'cb' => '<input type="checkbox" />',
        'thumbnail' => __('Image', 'calendar-press'),
        'author' => __('Owner', 'calendar-press'),
        'title' => __('Event Title', 'calendar-press'),
        'open_date' => __('Open Date', 'calendar-press'),
        'event_date' => __('Date/Time', 'calendar-press'),
        'signups' => __('Signups', 'calendar-press'),
        'overflow' => __('Overflow', 'calendar-press'),
        'featured' => __('Featured', 'calendar-press')
        );
            
        if ($this->options['use-comments'] ) {
            $columns['comments'] = '<img src="' . get_option('siteurl') . '/wp-admin/images/comment-grey-bubble.png" alt="Comments">';
        }
            
        //$columns['date'] = 'Date';
            
            
        return $columns;
    }
        
    /**
     * Handles display of custom columns
     *
     * @param string $column Name of the column.
     * 
     * @global object $post
     * 
     * @return string
     */
    function eventCustomColumns($column)
    {
        global $post;
            
        if ($post->post_type != 'event' ) {
            return;
        }
            
        switch ($column) {
        case 'thumbnail':
            if (function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
                  the_post_thumbnail(array(50, 50));
            }
            break;
        case 'open_date':
            $date = get_post_meta($post->ID, '_open_date_value', true);
            if ($date !== '') {
                 echo date("F jS, Y", $date);
            }
            break;
        case 'event_date':
            $date = get_post_meta($post->ID, '_begin_time_value', true);
            if ($date !== '') {
                 CP_The_Event_dates(array('prefix' => '', 'before_time' => '<br>'), $post->ID);
            }
            break;
        case 'intro':
            echo CP_Inflector::trimExcerpt($post->post_excerpt, 25);
            break;
        case 'featured':
            if (get_post_meta($post->ID, '_event_featured_value', true) ) {
                 _e('Yes', 'calendar-press');
            } else {
                _e('No', 'calendar-press');
            }
            break;
        case 'signups':
            $available = get_post_meta($post->ID, '_event_signups_value', true);
            $signups = get_post_meta($post->ID, '_event_registrations_signups', true);
            echo count($signups) . ' of ' . $available;
            break;
        case 'overflow':
            $available = get_post_meta($post->ID, '_event_overflow_value', true);
            $overflow = get_post_meta($post->ID, '_event_registrations_overflow', true);
            echo count($overflow) . ' of ' . $available;
            break;
        }
    }
        
    /**
     * Set up all taxonomies.
     * 
     * @return null
     */
    function setupTaxonomies()
    {
        if (!$this->options['use-taxonomies'] ) {
            return;
        }
            
        foreach ( $this->options['taxonomies'] as $key => $taxonomy ) {
                
            if (isset($taxonomy['active']) and isset($taxonomy['plural']) ) {
                    $labels = array(
                  'name' => $taxonomy['plural'],
                  'singular_name' => $taxonomy['singular'],
                  'search_items' => sprintf(__('Search %1$s', 'calendar-press'), $taxonomy['plural']),
                  'popular_items' => sprintf(__('Popular %1$s', 'calendar-press'), $taxonomy['plural']),
                  'all_items' => sprintf(__('All %1$s', 'calendar-press'), $taxonomy['plural']),
                  'parent_item' => sprintf(__('Parent %1$s', 'calendar-press'), $taxonomy['singular']),
                  'edit_item' => sprintf(__('Edit %1$s', 'calendar-press'), $taxonomy['singular']),
                  'update_item' => sprintf(__('Update %1$s', 'calendar-press'), $taxonomy['singular']),
                  'add_new_item' => sprintf(__('Add %1$s', 'calendar-press'), $taxonomy['singular']),
                  'new_item_name' => sprintf(__('New %1$s', 'calendar-press'), $taxonomy['singular']),
                  'add_or_remove_items' => sprintf(__('Add ore remove %1$s', 'calendar-press'), $taxonomy['plural']),
                  'choose_from_most_used' => sprintf(__('Choose from the most used %1$s', 'calendar-press'), $taxonomy['plural'])
                    );
                    
                    $args = array(
                    'hierarchical' => isset($taxonomy['hierarchical']),
                    'label' => $taxonomy['plural'],
                    'labels' => $labels,
                    'public' => true,
                    'show_ui' => true,
                    'rewrite' => array('slug' => $key),
                    'has_arcive' => true
                    );
                    
                    register_taxonomy($key, array('event'), $args);
            }
        }
    }
        
    /**
     * Add all of the needed meta boxes to the edit screen.
     * 
     * @return null
     */
    function initMetaboxes()
    {
        //add_meta_box('events_details', __('Details', 'calendar-press'), array(&$this, 'detailsBox'), 'event', 'side', 'high');
        add_meta_box('events_dates', __('Date and Time', 'calendar-press'), array(&$this, 'dateBox'), 'event', 'side', 'high');
            
        if ($this->options['registration-type'] != 'none' ) {
            add_meta_box('events_signup', __('Registration Settings', 'calendar-press'), array(&$this, 'signupBox'), 'event', 'side', 'high');
        }
    }
        
    /**
     * Add the details box.
     *
     * @global object $post
     * 
     * @return null
     */
    function detailsBox()
    {
        global $post;
        include $this->pluginPath . '/includes/meta-boxes/details-box.php';
    }
        
    /**
     * Add the date box.
     *
     * @global object $post
     * 
     * @return null
     */
    function dateBox()
    {
        global $post;
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', true);
            
        include $this->pluginPath . '/includes/meta-boxes/date-box.php';
    }
        
    /**
     * Add the signup settings box.
     *
     * @global object $post
     * 
     * @return null
     */
    function signupBox()
    {
        global $post;
            
        if (!$signup_type = get_post_meta($post->ID, '_registration_type_value', true) ) {
            $signup_type = $this->options['registration-type'];
        }
            
        if (!$signupCount = get_post_meta($post->ID, '_event_signups_value', true) ) {
            $signupCount = $this->options['signups-default'];
        }
            
        if (!$overflowCount = get_post_meta($post->ID, '_event_overflow_value', true) ) {
            $overflowCount = $this->options['overflow-default'];
        }
            
            include $this->pluginPath . '/includes/meta-boxes/signups-box.php';
    }
}                