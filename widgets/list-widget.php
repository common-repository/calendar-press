<?php 
/**
 * Calendar Press Widget
 * php version 8.1.10
 * 
 * @category   WordPress_Widget
 * @package    Calendar_Press
 * @subpackage Widgets
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}

/**
 * Class for List Events Widget
 * 
 * @category   WordPress_Widget
 * @package    Calendar_Press
 * @subpackage Widgets
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
class Calendar_Press_Widget_List_Events extends WP_Widget
{

    var $options = array();

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct(
            'calendar-press-list-widget',
            __('CalendarPress &raquo; List', ' better-rss-widget'),
            array(
                'description' => __('Event List Widget', 'better-rss-widget')
            )
        );

        $this->pluginPath = WP_CONTENT_DIR . '/plugins/' . 
            plugin_basename(dirname(__FILE__));
        $this->options = get_option('calendar-press-options');
    }

    /**
     * Widget Code
     * 
     * @param array $args     Widget Arguments
     * @param array $instance Widget Instance
     * 
     * @return array
     */
    function widget($args, $instance)
    {
        global $calendarPressOBJ;

        if (isset($instance['error']) && $instance['error']) {
            return;
        }

        $instance = wp_parse_args($instance, $this->defaults());

        $posts = new WP_Query();
        $posts->set('post_type', 'event');
        $posts->set('posts_per_age', $instance['items']); /* Does not seem to work */
        $posts->set('showposts', $instance['items']);
        $customFields = array(
            '_begin_time_value' => time()
        );

        switch ($instance['type']) {
        case 'next':
            $posts->set('order', 'ASC');
            $cp_widget_order = '_begin_time_value ASC';
            add_filter(
                'posts_orderby', array(
                $calendarPressOBJ,
                'get_custom_fields_posts_orderby'
                )
            );
            break;
        case 'newest':
            $posts->set('ordeby', 'date');
            break;
        case 'featured':
            $customFields['_event_featured_value'] = true;
            add_filter(
                'posts_orderby', array(
                $calendarPressOBJ,
                'get_custom_fields_posts_orderby'
                )
            );
            break;
        case 'updated':
            $posts->set('orderby', 'modified');
            break;
        case 'random':
            $posts->set('orderby', 'rand');
            break;
        default:
            break;
        }

        /* Grab the posts for the widget */
        add_filter(
            'posts_fields', array(
            &$calendarPressOBJ,
            'get_custom_fields_posts_select'
            )
        );
        add_filter(
            'posts_join', array(
            &$calendarPressOBJ,
            'get_custom_field_posts_join'
            )
        );
        add_filter(
            'posts_groupby', array(
            $calendarPressOBJ,
            'get_custom_field_posts_group'
            )
        );
        $posts->get_posts();
        remove_filter(
            'posts_fields', array(
            &$calendarPressOBJ,
            'get_custom_fields_posts_select'
            )
        );
        remove_filter(
            'posts_join', array(
            &$calendarPressOBJ,
            'get_custom_field_posts_join'
            )
        );

        remove_filter(
            'posts_groupby', array(
            $calendarPressOBJ,
            'get_custom_field_posts_group'
            )
        );

        remove_filter(
            'posts_orderby', array(
            $calendarPressOBJ,
            'get_custom_fields_posts_orderby'
            )
        );

        /* Output Widget */
        echo $args['before_widget'];
        if ($instance['title']) {
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
        }

        $template = $calendarPressOBJ->getTemplate('list-widget');
        include $template;

        echo $args['after_widget'];
    }

    /**
     * Build the form for the widget.
     *
     * @param array $instance Instance Data
     *            
     * @return mixed
     */
    function form($instance)
    {
        $instance = wp_parse_args($instance, $this->defaults());

        if ($instance['items'] < 1 || 20 < $instance['type']) {
            $instance['type'] = $this->options['widget-items'];
        }

        include $this->pluginPath . '/list-form.php';
        return $instance;
    }

    /**
     * Set up the default widget instace.
     *
     * @return boolean[]|NULL[]
     */
    function defaults()
    {
        global $calendarPressOBJ;
        return array(
            'title' => false,
            'items' => $calendarPressOBJ->options['widget-items'],
            'type' => $calendarPressOBJ->options['widget-type'],
            'linktarget' => $calendarPressOBJ->options['widget-target'],
            'showicon' => $calendarPressOBJ->options['widget-show-icon'],
            'iconsize' => $calendarPressOBJ->options['widget-icon-size']
        );
    }
}

/**
 * Function to register the List Widget.
 *
 * @return null
 */
function Calendar_Press_Register_List_widget()
{
    register_widget('Calendar_Press_Widget_List_Events');
    return null;
}
add_action('widgets_init', 'Calendar_Press_Register_List_widget');
