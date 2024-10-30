<?php
/**
 * The Template Tags.
 * php version 8.1.10
 *
 * @category   WordPress_Template
 * @package    Calendar_Press
 * @subpackage Templates
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}

/* Conditionals */
if (!function_exists('CP_Use_Overflow_option')) {
    /**
     * Check if we are using the overflow option
     * 
     * @return boolean
     */
    function CP_Use_Overflow_option()
    {
        global $calendarPressOBJ;
            
        return $calendarPressOBJ->options['use-overflow'];
    }
}
    
/**
 * Get the date form.
 * 
 * @param object $post  Post object
 * @param string $field begin or end date
 * @param string $type  date or time
 * 
 * @return string
 */
function CP_Event_Get_Date_form($post = null, $field = 'begin', $type = 'date')
{
    if (is_int($post) ) {
        $post = get_post($post);
    } elseif (!is_object($post) ) {
        global $post;
    }
        
    $date = get_post_meta($post->ID, '_' . $field . '_' . $type . '_value', true);
        
    if (!$date ) {
        $date = time();
    }
        
    switch ($type) {
    case 'time':
        $output = '<input class="cp-' . $type . '-form" type="text" id="' . $field . ucfirst($type) . '" name="event_dates[' . $field . '_' . $type . ']" value="' . date('g', $date) . '" style="width:25px" /> : ';
        $output.= '<input class="cp-' . $type . '-form" type="text" id="' . $field . ucfirst($type) . '" name="' . $field . '_' . $type . '_minutes" value="' . date('i', $date) . '" style="width:25px" />';
        break;
    default:
        $output = '<input class="cp-' . $type . '-form" type="text" id="' . $field . ucfirst($type) . '" name="event_dates[' . $field . '_' . $type . ']" value="' . date('m/d/Y', $date) . '" style="width:100px" />';
        break;
    }
        
    return $output;
}
    
/**
 * Show event date form
 * 
 * @param object $post  Post Object
 * @param string $field Which date field
 * @param string $type  Date or time
 * 
 * @return null
 */
function CP_Event_Date_form($post = null, $field = 'begin', $type = 'date')
{
    print CP_Event_Get_Date_form($post, $field, $type);
}
    
/**
 * Show the date meridiem
 *
 * @param object $post  Post Object
 * @param string $field Start or end date`
 * @param string $type  am or pm
 *
 * @return null
 */
function CP_Get_Event_merdiem($post = null, $field = 'begin', $type = 'am')
{
    if (is_int($post) ) {
        $post = get_post($post);
    } elseif (!is_object($post) ) {
        global $post;
    }
        
    $date = get_post_meta($post->ID, '_' . $field . '_time_value', true);
        
    if ($date <= 0 ) {
        $date = date("U");
    }
        
    if ((date('G', $date) < 12 and $type == 'am')
        or (date('G', $date) >= 12 and $type == 'pm')
    ) {
        print ' selected="selected"';
    }
}
    
/**
 * Show the date meridiem
 * 
 * @param object $post  Post Object
 * @param string $field Start or end date`
 * @param string $type  am or pm
 * 
 * @return null
 */
function CP_Event_meridiem($post = null, $field = 'begin', $type = 'am')
{
    print CP_Get_Event_merdiem($post, $field, $type);
}
    
/**
 * Get the event calendar
 *
 * @param int $month The month
 * @param int $year  The year
 *
 * @return null
 */
function CP_Get_Event_calendar($month = null, $year = null)
{
    global $calendarPressOBJ;
        
    $output = '';
        
    if (!$month ) {
        $month = date('m', strtotime($calendarPressOBJ->currDate));
    }
        
    if (!$year ) {
        $year = date('Y', strtotime($calendarPressOBJ->currDate));
    }
        
    $day = date('d');
    $firstDay = date('w', strtotime("$year-$month-1"));
    $totalDays = date('t', strtotime("$year-$month-1"));
        
    $days = 0;
    $output.= '<div id="calendar"><dl class="cp-boxes">';
    for ( $ctr = 1 - $firstDay; $ctr <= $totalDays; ++$ctr ) {
        $output.= '<dd class="cp-month-box ';
        ++$days;
            
        if ($days > 7 ) {
            $output.= ' cp-break';
            $days = 1;
        }
            
        if ($ctr < 1 ) {
            $output.= ' cp-empty-day';
        }
            
        if ($ctr == $day and $month == date('m') and $year == date('Y') ) {
            $output.= ' cp-active-day';
        }
            
            $output.= '">';
            
        if ($ctr > 0 and $ctr <= $totalDays ) {
            $output.= '<span class="cp-month-numeral">' . $ctr . '</span>';
            $output.= '<span class="cp-month-contents">' . CP_Get_Daily_events($month, $ctr, $year) . '</span>';
        }
            
            $output.= '</dd>';
    }
        
    for ( $ctr = $days; $ctr < 7; ++$ctr ) {
        $output.= '<dd class="cp-month-box cp-empty-day"></dd>';
    }
    $output.= '</dl></div>';
        
    return $output;
}
    
/**
 * Show the event calendar
 * 
 * @param int $month The month
 * @param int $year  The year
 * 
 * @return null
 */
function CP_Event_calendar($month = null, $year = null)
{
    print CP_Get_Event_calendar($month, $year);
}
    
/**
 * Get the daily events
 *
 * @param int $month Month
 * @param int $day   Day
 * @param int $year  Year
 *
 * @return string
 */
function CP_Get_Daily_events($month = null, $day = null, $year = null)
{
    global $openEvents;
    $events = get_posts(
        array(
        'post_type' => 'event',
        'meta_key' => '_begin_date_value',
        'meta_value' => strtotime("$year-$month-$day"),
        )
    );
        
    if (is_array($openEvents) ) {
        $events = array_merge($openEvents, $events);
    }
    if (count($events) <= 0 ) {
        return;
    }
        
    $output = '';
        
    foreach ( $events as $event ) {
            
        $output.= '<span class="event-title-month" ';
            
        if (get_post_meta($event->ID, '_event_popup_value', true) ) {
             $output.= 'onmouseover="return overlib(\'' . esc_js(CP_Get_Event_popup($event)) . '\');" onmouseout="return nd();" ';
        }
            
        $output.= '><a href="' . get_permalink($event->ID) . '">' . $event->post_title . '</a></span>';
            
        $eventBeginDate = get_post_custom_values('_begin_date_value', $event->ID);
        $eventEndDate = get_post_custom_values('_end_date_value', $event->ID);
            
            
        if ((date('j-Y', $eventEndDate[0]) != date('j-Y', $eventBeginDate[0])) && date('j-Y', $eventEndDate[0]) == $day . "-" . $year ) {
            while ($openEvent = current($openEvents)) {
                if ($openEvent->ID == $event->ID ) {
                    $removeKey = key($openEvents);
                }
                    next($openEvents);
            }
            if (isset($openEvents[1]) and is_array($openEvents[1]) ) {
                  $removeEvent[$removeKey] = $openEvents[1];
            } else {
                $removeEvent[$removeKey] = array();
            }
                
                $openEvents = array_diff_key($openEvents, $removeEvent);
        } elseif ((date('j-Y', $eventEndDate[0]) != date('j-Y', $eventBeginDate[0])) && date('j-Y', $eventBeginDate[0]) == $day . "-" . $year ) {
            $openEvents[] = $event;
        }
    }
        
    return $output;
}
    
/**
 * Show the daily events
 * 
 * @param int $month Month
 * @param int $day   Day
 * @param int $year  Year
 * 
 * @return null
 */
function CP_The_Daily_events($month = null, $day = null, $year = null)
{
    print CP_Get_Daily_events($month, $day, $year);
}
    
/**
 * Get the event popup
 * 
 * @param object $event Event Object
 * 
 * @return string
 */
function CP_Get_Event_popup($event)
{
    global $calendarPressOBJ;
        
    ob_start();
    include $calendarPressOBJ->getTemplate('popup-box');
    $output = ob_get_contents();
    ob_end_clean();
        
    return $output;
}
    
/**
 * Get the event dates
 *
 * @param array  $attrs Date Attributes
 * @param object $post  Post Object
 *
 * @return null
 */
function CP_Get_Event_dates($attrs = array(), $post = null)
{
    global $calendarPressOBJ;
        
    if (is_int($post) ) {
        $post = get_post($post);
    } elseif (!is_object($post) ) {
        global $post;
    }
        
    $defaults = array(
    'date_format' => get_option('date_format'),
    'time_format' => get_option('time_format'),
    'prefix' => __('When: ', 'calendar-press'),
    'before_time' => __(' from ', 'calendar-press'),
    'after_time' => '',
    'between_time' => __(' to ', 'calendar-press'),
    'after_end_date' => __(' at ', 'calendar-press'),
    );
        
    extract(wp_parse_args($attrs, $defaults));
        
    $startDate = get_post_meta($post->ID, '_begin_date_value', true);
    $startTime = get_post_meta($post->ID, '_begin_time_value', true);
    $endDate = get_post_meta($post->ID, '_end_date_value', true);
    $endTime = get_post_meta($post->ID, '_end_time_value', true);
        
    $output = $prefix . date($date_format, $startDate);
        
    if ($startDate == $endDate ) {
        $output.= $before_time . date($time_format, $startTime) . $between_time . date($time_format, $endTime) . $after_time;
    } else {
        $output.= $before_time . date($time_format, $startTime) . $between_time . date($date_format, $endDate) . $after_end_date . date($time_format, $endTime) . $after_time;
    }
        
    return $output;
}
    
/**
 * Show the event dates
 * 
 * @param array  $attrs Date Attributes
 * @param object $post  Post Object
 * 
 * @return null
 */
function CP_The_Event_dates($attrs = array(), $post = null)
{
    print CP_Get_Event_dates($attrs, $post);
}
    
/**
 * Get the event category
 *
 * @param array  $attrs Category attributes
 * @param object $post  Post Obhct
 *
 * @return null
 */
function CP_Get_The_Event_category($attrs = array(), $post = null)
{
    global $calendarPressOBJ;
        
    if (!$calendarPressOBJ->options['use-categories'] ) {
        return false;
    }
        
    if (is_int($post) ) {
        $post = get_post($post);
    } elseif (!is_object($post) ) {
        global $post;
    }
        
    if (is_null($args['prefix']) ) {
        $args['prefix'] = __('Posted In: ', 'calendar-press');
    }
        
    if (is_null($args['divider']) ) {
        $args['divider'] = ', ';
    }
        
    if (wp_get_object_terms($post->ID, 'event-categories') ) {
        $cats = $args['prefix'] . get_the_term_list($post->ID, 'event-categories', $args['before-category'], $args['divider'], $args['after-category']) . $args['suffix'];
        return $cats;
    }
}
    
/**
 * Show the event category
 * 
 * @param array  $attrs Category attributes
 * @param object $post  Post Obhct
 * 
 * @return null
 */
function CP_The_Event_category($attrs = array(), $post = null)
{
    print CP_Get_The_Event_category($attrs, $post);
}
    
/**
 * Get the event button.
 * 
 * @param string $type  Button type.
 * @param object $post  Post Object
 * @param array  $attrs Button attributes
 * 
 * @return void|string|boolean
 */
function CP_Get_Event_button($type = 'signups', $post = null, $attrs = array())
{
    global $calendarPressOBJ, $wpdb, $current_user;
    get_currentuserinfo();
    $used = 0;
        
    if (!is_user_logged_in() ) {
        if ($type == 'signups' ) {
            return sprintf(__('You must be %1$s to register', 'calendar-press'), '<a href="' . wp_login_url(get_permalink()) . '">' . __('logged in', 'calendar-press') . '</a>');
        } else {
            return;
        }
    }
        
    if (!$calendarPressOBJ->options['registration-type'] == 'none' ) {
        print "DOH!";
        return false;
    }
        
    if (is_int($post) ) {
        $post = get_post($post);
    } elseif (!is_object($post) ) {
        global $post;
    }
        
    if ($calendarPressOBJ->options['registration-type'] == 'select' ) {
        $method = get_post_meta($post->ID, '_registration_type_value', true);
    } else {
        $method = $calendarPressOBJ->options['registration-type'];
    }
        
    switch ($method) {
    case 'signups':
        $alt = array('signups' => 'overflow', 'overflow' => 'signups');
            
        $regs = get_post_meta($post->ID, '_event_registrations_' . $type, true);
        $alt_registrations = get_post_meta($post->ID, '_event_registrations_' . $alt[$type], true);
        $available = get_post_meta($post->ID, '_event_' . $type . '_value', true);
            
        if (is_array($regs) and array_key_exists($current_user->ID, $regs) ) {
            $registered = true;
        } else {
            $registered = false;
        }
            
        if (is_array($regs) ) {
            $remaining = $available - count($regs);
        } else {
            $remaining = $available;
        }
            
        $buttonText = $calendarPressOBJ->options[$type . '-title'];
            
        if ($registered ) {
            $addButtonText = __(' - Cancel Registration', 'calendar-press');
            $clickEvent = 'onClickCancel(\'' . $type . '\', ' . $post->ID . ')';
        } elseif ($remaining > 0 ) {
            if ($registered or (is_array($alt_registrations) and array_key_exists($current_user->id, $alt_registrations)) ) {
                $addButtonText = sprintf(__('- Move (%1$s of %2$s Available)'), $remaining, $available);
                $clickEvent = 'onClickMove(\'' . $type . '\', ' . $post->ID . ')';
            } else {
                $addButtonText = sprintf(__(' (%1$s of %2$s Available)'), $remaining, $available);
                $clickEvent = 'onClickRegister(\'' . $type . '\', ' . $post->ID . ')';
            }
        } else {
            $addButtonText = __(' Full');
            $clickEvent = 'onClickWaiting(' . $post->ID . ')';
        }
            
        $buttonText.= $addButtonText;
            
        return '<input 
            id="button_' . $type . '" 
            type="button" 
            value="' . $buttonText . '" 
            onclick="' . $clickEvent . '">';
            break;
    case 'yesno':
        $regs = get_post_meta($post->ID, '_event_registrations_yesno', true);
        if (!is_array($regs)) {
            $regs = array();
        }
        $buttonText = ucfirst($type);
            
        if (array_key_exists($current_user->ID, $regs) 
            && $regs[$current_user->ID]['type'] == $type 
        ) {
            $disabled = 'disabled';
            $buttonStyle = 'event_button_selected';
        } else {
            $disabled = '';
            $buttonStyle = 'event_button_not_selected';
        }
            
        $clickEvent = 'onClickYesNo(\'' . $type . '\',' . $post->ID . ')';
        return '<input 
            class="' . $buttonStyle . '" 
            id="button_' . $type . '" 
            type="button" 
            value="' . $buttonText . '" 
            onclick="' . $clickEvent . '" ' . $disabled . '>';
            break;
    }
}
    
/**
 * Show the event signup button.
 *
 * @param object $post  Post Object
 * @param array  $attrs Button attributes
 *
 * @return null
 */
function CP_The_Event_Signup_utton($post = null, $attrs = array())
{
    print CP_Get_Event_button('signups', $post, $attrs);
}
    
/**
 * Get the event overflow button.
 *
 * @param object $post  Post Object
 * @param array  $attrs Button attributes
 *
 * @return null
 */
function CP_The_Event_Overflow_button($post = null, $attrs = array())
{
    global $calendarPressOBJ;
        
    if ($calendarPressOBJ->options['use-overflow']) {
        print CP_Get_Event_button('overflow', $post, $attrs);
    }
}
    
/**
 * Show the event yes button.
 *
 * @param object $post  Post Object
 * @param array  $attrs Button attributes
 *
 * @return null
 */
function CP_The_Event_Yes_button($post = null, $attrs = array())
{
    print CP_Get_Event_button('yes', $post, $attrs);
}
    
/**
 * Show the event no button.
 *
 * @param object $post  Post Object
 * @param array  $attrs Button attributes
 *
 * @return null
 */
function CP_The_Event_No_button($post = null, $attrs = array())
{
    print CP_Get_Event_button('no', $post, $attrs);
}
  
/**
 * Show the event maybe button.
 * 
 * @param object $post  Post Object
 * @param array  $attrs Button attributes
 * 
 * @return null
 */
function CP_The_Event_Maybe_button($post = null, $attrs = array())
{
    print CP_Get_Event_button('maybe', $post, $attrs);
}
    
/**
 * Get the event month link.
 * 
 * @param object $date Date object.
 * 
 * @return string
 */
function CP_Get_Event_Month_link($date)
{
    global $calendarPressOBJ;
        
    if ($calendarPressOBJ->in_shortcode ) {
        global $post;
        $link = get_permalink($post);
    } else {
        $link = get_option('home') . '/' . $calendarPressOBJ->options['index-slug'];
    }
    $month = date('m', strtotime($date));
    $year = date('Y', strtotime($date));
    $text = date('F, Y', strtotime($date));
    
    $linkPart = 'viewmonth=' . $month . '&viewyear=' . $year;
        
    if (get_option('permalink_structure') ) {
        return '<a href="' . $link . '?' . $linkPart . '">' . $text . '</a>';
    } else {
        return '<a href="' . $link . '&' . $linkPart . '">' . $text . '</a>';
    }
}
    
/**
 * Get last month link on the calendar.
 *
 * @return null
 */
function CP_Get_Event_Last_month()
{
    global $calendarPressOBJ;
    return CP_Get_Event_Month_link($calendarPressOBJ->lastMonth);
}
    
/**
 * Show last month link on the calendar.
 *
 * @return null
 */
function CP_The_Event_Last_month()
{
    print CP_Get_Event_Last_month();
}
    
/**
 * Get this month link on the calendar.
 *
 * @return null
 */
function CP_Get_Event_This_month()
{
    global $calendarPressOBJ;
    return date('F, Y', strtotime($calendarPressOBJ->currDate));
}

/**
 * Get this month link on the calendar.
 *
 * @return null
 */
function CP_The_Event_This_month()
{
    print CP_Get_Event_This_month();
}
    
/**
 * Get the next month link on the calendar.
 *
 * @return null
 */
function CP_Get_Event_Next_month()
{
    global $calendarPressOBJ;
    return CP_Get_Event_Month_link($calendarPressOBJ->nextMonth);
}
    
/**
 * Show the next month link on the calendar.
 * 
 * @return null
 */
function CP_The_Event_Next_month()
{
    print CP_Get_Event_Next_month();
}
    
/**
 * Get the event signups field
 *
 * @param array  $attrs Box attributes
 * @param object $post  Post object
 *
 * @return null
 */
function CP_Get_Event_signups($attrs = array(), $post = null)
{
    global $wpdb, $calendarPressOBJ;
        
    if (is_int($post) ) {
        $post = get_post($post);
    } elseif (!is_object($post) ) {
        global $post;
    }
        
    extract(
        shortcode_atts(
            array(
            'type' => 'signups',
            'divider' => '<br>',
            ), $attrs
        )
    );
        
    $signups = get_post_meta($post->ID, '_event_registrations_' . $type, true);
        
    if (is_array($signups) and count($signups) > 0 ) {
        $field = $calendarPressOBJ->options['user-display-field'];
        $prefix = '';
        $output = '';
            
        foreach ( $signups as $id => $signup ) {
             $signups[$id]['id'] = $id;
             $tempArray[$id] = &$signup['date'];
        }
        array_multisort($tempArray, $signups);
            
        foreach ( $signups as $user_id => $signup ) {
            $user = get_userdata($signup['id']);
                
            if ($field == 'full_name' and ($user->first_name or $user->last_name) ) {
                    $username = $user->first_name . ' ' . $user->last_name;
            } elseif ($field != 'display_name' and isset($user->$field) ) {
                 $username = $user->$field;
            } else {
                $username = $user->display_name;
            }
                
            if ($type != 'yesno'  
                || ($type == 'yesno' && $signup['type'] == $attrs['match']) 
            ) {
                $output.= $prefix . $username;
                    
                if ($calendarPressOBJ->options['show-signup-date'] ) {
                    $output.= ' - ' . date(
                        $calendarPressOBJ->options['signup-date-format'],
                        $signup['date']
                    );
                }
                $prefix = $divider;
            }
        }
    } else {
        $output = __('No Registrations', 'calendar-press');
    }
        
    return $output;
}
    
/**
 * Show the event signups field
 *
 * @param array  $attrs Box attributes
 * @param object $post  Post object
 *
 * @return null
 */
function CP_The_Event_signups($attrs = array(), $post = null)
{
    $attrs['type'] = 'signups';
    print CP_Get_Event_signups($attrs, $post);
}
    
/**
 * Show the event overflow field
 *
 * @param array  $attrs Box attributes
 * @param object $post  Post object
 *
 * @return null
 */
function CP_The_Event_overflow($attrs = array(), $post = null)
{
    $attrs['type'] = 'overflow';
    print CP_Get_Event_signups($attrs, $post);
}
    
/**
 * Show the event maybe field
 *
 * @param array  $attrs Box attributes
 * @param object $post  Post object
 *
 * @return null
 */
function CP_The_Event_yes($attrs = array(), $post = null)
{
    $attrs['type'] = 'yesno';
    $attrs['match'] = 'yes';
    print CP_Get_Event_signups($attrs, $post);
}
    
/**
 * Show the event no field
 *
 * @param array  $attrs Box attributes
 * @param object $post  Post object
 *
 * @return null
 */
function CP_The_Event_no($attrs = array(), $post = null)
{
    $attrs['type'] = 'yesno';
    $attrs['match'] = 'no';
    print CP_Get_Event_signups($attrs, $post);
}
    
/**
 * Show the event maybe field
 * 
 * @param array  $attrs Box attributes
 * @param object $post  Post object
 * 
 * @return null
 */
function CP_The_Event_maybe($attrs = array(), $post = null)
{
    $attrs['type'] = 'yesno';
    $attrs['match'] = 'maybe';
    print CP_Get_Event_signups($attrs, $post);
}
    
/**
 * Get the Signup Title
 * 
 * @return string
 */
function CP_Get_Signup_title()
{
    global $calendarPressOBJ;
    return $calendarPressOBJ->options['signups-title'];
}
    
/**
 * Show the signup title.
 * 
 * @return null
 */
function CP_The_Signup_title()
{
    print CP_Get_Signup_title();
}
    
/**
 * Get the overflow title.
 * 
 * @return string
 */
function CP_Get_Overflow_title()
{
    global $calendarPressOBJ;
    return $calendarPressOBJ->options['overflow-title'];
}

/**
 * Show the overflow title.
 * 
 * @return null
 */
function CP_The_Overflow_title()
{
    print CP_Get_Overflow_title();
}
    
/**
 * Check if we should show the calendar registrations
 * 
 * @param object $post Post Object
 * 
 * @return boolean
 */
function CP_Show_registrations($post = null)
{
    global $calendarPressOBJ;
        
    if (is_int($post) ) {
        $post = get_post($post);
    } elseif (!is_object($post) ) {
        global $post;
    }
        
    if ($calendarPressOBJ->options['registration-type'] == 'select' ) {
        $method = get_post_meta($post->ID, '_registration_type_value', true);
    } else {
        $method = $calendarPressOBJ->options['registration-type'];
    }
        
    switch ($method) {
    case 'none':
        return false;
            break;
    case 'signups':
        return true;
            break;
    case 'yesno':
        return true;
            break;
    default:
        return false;
    }
}
    
/**
 * Show the calendar date and time.
 *
 * @param DateTime $date   Optional timestamp
 * @param string   $format Date Format
 *
 * @return string
 */
function CP_Show_Calendar_Date_time($date, $format = null)
{
    if (!$format ) {
        $format = get_option('date_format');
    }
        
    return date($format, $date);
}
    
/**
 * Get the event start date.
 *
 * @param DateTime $date   Optional timestamp
 * @param string   $format Date Format
 *
 * @return null
 */
function CP_Get_Start_date($date = null, $format = null)
{
    if (!$date ) {
        global $post;
        $date = get_post_meta($post->ID, '_begin_date_value', true);
    }
    return CP_Show_Calendar_Date_time($date, $format);
}
    
/**
 * Show the event start date.
 *
 * @param DateTime $date   Optional timestamp
 * @param string   $format Date Format
 *
 * @return null
 */
function CP_The_Start_date($date= null, $format = null)
{
    echo CP_Get_Start_date($date, $format);
}
    
/**
 * Show the event start date.
 *
 * @param DateTime $time   Optional timestamp
 * @param string   $format Date Format
 *
 * @return null
 */
function CP_Get_Start_time($time = null, $format = null)
{
    if (!$time ) {
        global $post;
        $time = get_post_meta($post->ID, '_begin_time_value', true);
    }
    if (!$format ) {
        $format = get_option('time_format');
    }
    return CP_Show_Calendar_Date_time($time, $format);
}
    
/**
 * Show the event start date.
 *
 * @param DateTime $time   Optional timestamp
 * @param string   $format Date Format
 *
 * @return null
 */
function CP_The_Start_time($time= null, $format = null)
{
    echo CP_Get_Start_time($time, $format);
}
    
/**
 * Get the event end date.
 * 
 * @param DateTime $date   Optional date
 * @param string   $format Date Format
 * 
 * @return null
 */
function CP_Get_End_date($date = null, $format = null)
{
    if (!$date ) {
        global $post;
        $date = get_post_meta($post->ID, '_end_date_value', true);
    }
    return CP_Show_Calendar_Date_time($date, $format);
}
    
/**
 * Show the event end date.
 * 
 * @param DateTime $date   Optional date
 * @param string   $format Date Format
 * 
 * @return null
 */
function CP_The_End_date($date= null, $format = null)
{
    echo CP_Get_End_date($date, $format);
}

/**
 * Get the event end time.
 * 
 * @param DateTime $time   The end time
 * @param string   $format The time format
 * 
 * @return html
 */
function CP_Get_End_time($time = null, $format = null)
{
    if (!$time ) {
        global $post;
        $time = get_post_meta($post->ID, '_end_time_value', true);
    }
    if (!$format ) {
        $format = get_option('time_format');
    }
    return CP_Show_Calendar_Date_time($time, $format);
}
    
/**
 * Display the event end time.
 * 
 * @param DateTime $time   Datestamp for event time.
 * @param string   $format Format for the time
 * 
 * @return null
 */
function CP_The_End_time($time= null, $format = null)
{
    echo CP_Get_End_time($time, $format);
}
    
/**
 * Load the registration form based on the event options.
 *
 * @global object $post
 * @global object $calendarPressOBJ
 * 
 * @return string
 */
function CP_Event_registrations()
{
    global $post, $calendarPressOBJ;
        
    if ($calendarPressOBJ->options['registration-type'] == 'select' ) {
        switch (get_post_meta($post->ID, '_registration_type_value', true)) {
        case 'signups':
            include $calendarPressOBJ->getTemplate('registration-signups');
            break;
        case 'yesno':
            include $calendarPressOBJ->getTemplate('registration-yesno');
            break;
        default:
            /* Do nothing */
        }
    } else {
        $template = $calendarPressOBJ->getTemplate(
            'registration-' . $calendarPressOBJ->options['registration-type']
        );
            
        if (file_exists($template)) {
            include $template;
        }
    }
}    