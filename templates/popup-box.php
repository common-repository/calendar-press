<?php
/**
 * The Template for displaying an popup box.
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
?>
<div class="popup-contents">
    <div class="event_times_popup">
        <?php echo date(
            'g:i a', 
            get_post_meta($event->ID, '_begin_time_value', true)
        ); ?>
    </div>
    <div class="event_content_popup">
        <?php echo $event->post_content; ?>
    </div>
</div>