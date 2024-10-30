<?php
/**
 * Dates Meta Box
 * php version 8.1.10
 *
 * @category   WordPress_Template
 * @package    Calendar_Press
 * @subpackage Metabox
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}
?>

<input type="hidden" name="dates_noncename" id="dates_noncename" value="<?php echo wp_create_nonce('calendar_press_dates'); ?>" />

<table>
    <tr>
        <th><label for="openDate"><?php _e('Open Date', 'gsCalendarPress'); ?>:</label></th>
        <td>
            <?php 
                $open_date = get_post_meta($post->ID, '_open_date_value', true);
            if ($open_date == '') {
                $open_date = time();
            }
            ?>
            <input type='text' class='widefat datepicker' id='openDate' name='event_dates[open_date]' value='<?php CP_The_Start_date($open_date, 'm/d/Y'); ?>'>
        </td>
    </tr>
    <tr>
        <th></th>
        <td>
            <?php 
                $open_date_display = get_post_meta($post->ID, '_open_date_display_value', true);
                $checked = ($open_date_display == 1) ? "checked" : "";
            ?>
            <input type='checkbox' class='widefat' id='openDateDisplay' name='event_dates[open_date_display]' value='1' <?php echo $checked; ?>>
            <label for="openDateDisplay"><?php _e('Hide Open Date?', 'gsCalendarPress'); ?></label>
        </td>
        
    </tr>
    <tr>
        <th><label for="beginDate"><?php _e('Session Date', 'gsCalendarPress'); ?>:</label></th>
        <td>
            <?php 
                $begin_date = get_post_meta($post->ID, '_begin_date_value', true); 
            if ($begin_date == '') {
                $begin_date = time();
            }
            ?>
            <input type='text' class='widefat datepicker' id='beginDate' name='event_dates[begin_date]' value='<?php CP_The_Start_date($begin_date, 'm/d/Y'); ?>'>
        </td>
    </tr>
    <tr>
        <th><label for="gsCalendarPress-event-time"><?php _e('Start Time', 'gsCalendarPress'); ?>:</label></th>
        <td>
            <?php 
                $begin_time = get_post_meta($post->ID, '_begin_time_value', true); 
            if ($begin_time == '') {
                $begin_time = time();
            }
            ?>
            <select name='event_dates[begin_time]'>
                <?php for ( $ctr = 1; $ctr <= 12; $ctr++ ) : $value = str_pad($ctr, 2, '0', STR_PAD_LEFT); ?>
                <option value='<?php echo $value; ?>' <?php echo ($value === date('h', $begin_time) ? 'selected' : ''); ?>><?php echo $value; ?></option>
                <?php endfor; ?>
            </select>
            :
            <select name='begin_time_minutes'>
                <?php for ( $ctr = 0; $ctr < 60; $ctr = $ctr + 5 ) : $value = str_pad($ctr, 2, '0', STR_PAD_LEFT);?>
                <option value='<?php echo $value ?>' <?php echo ($value === date('i', $begin_time) ? 'selected' : ''); ?>><?php echo $value; ?></option>
                <?php endfor; ?>
            </select>
            
            <select name='begin_meridiem'>
                <option value='am' <?php echo ('am' === date('a', $begin_time) ? 'selected' : ''); ?>><?php echo _e('am', 'gsCalendarPress'); ?></option>
                <option value='pm' <?php echo ('pm' === date('a', $begin_time) ? 'selected' : ''); ?>><?php echo _e('pm', 'gsCalendarPress'); ?></option>
            </select>
        </td>
    </tr>
    <tr style="display:none">
        <th><label for="endDate"><?php _e('End Date', 'gsCalendarPress'); ?>:</label></th>
        <td>
            <?php 
                $end_date = get_post_meta($post->ID, '_end_date_value', true); 
            if ($end_date == '') {
                $end_date = time();
            }
            ?>
            <input type='text' class='widefat datepicker' id='endDate' name='event_dates[end_date]' value='<?php CP_The_End_date($end_date, 'm/d/Y'); ?>'>
        </td>
    </tr>
    <tr>
        <th><label for="gsCalendarPress-event-time"><?php _e('End Time', 'gsCalendarPress'); ?>:</label></th>
        <td>
            <?php 
                $end_time = get_post_meta($post->ID, '_end_time_value', true); 
            if ($end_time == '') {
                $end_time = time();
            }
            ?>
            
            <select name='event_dates[end_time]'>
                <?php for ( $ctr = 1; $ctr <= 12; $ctr++ ) : $value = str_pad($ctr, 2, '0', STR_PAD_LEFT); ?>
                <option value='<?php echo $value; ?>' <?php echo ($value === date('h', $end_time) ? 'selected' : ''); ?>><?php echo $value; ?></option>
                <?php endfor; ?>
            </select>
            :
            <select name='end_time_minutes'>
                <?php for ( $ctr = 0; $ctr < 60; $ctr = $ctr + 5 ) : $value = str_pad($ctr, 2, '0', STR_PAD_LEFT);?>
                <option value='<?php echo $value ?>' <?php echo ($value === date('i', $end_time) ? 'selected' : ''); ?>><?php echo $value; ?></option>
                <?php endfor; ?>
            </select>
            
            <select name='end_meridiem'>
                <option value='am' <?php echo ('am' === date('a', $end_time) ? 'selected' : ''); ?>><?php echo _e('am', 'gsCalendarPress'); ?></option>
                <option value='pm' <?php echo ('pm' === date('a', $end_time) ? 'selected' : ''); ?>><?php echo _e('pm', 'gsCalendarPress'); ?></option>
            </select>
        </td>
    </tr>
</table>

<script>
    jQuery(document).ready(function() {
        jQuery('.datepicker').datepicker({
            dateFormat : 'mm/dd/yy'
        });
    });
</script>