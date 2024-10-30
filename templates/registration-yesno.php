<?php
/**
 * The code for Yes/No type of registration.
 * php version 8.1.10
 *
 * @category   WordPress_Widget
 * @package    Calendar_Press
 * @subpackage Registration
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}
?>
<div id="event_buttons">
    <div class="event-registration">
        <h3 class="event-registration-text">
              <?php _e('Are you attending?', 'calendar-press'); ?>
          </h3>
        <div>
               <?php CP_The_Event_Yes_button(); ?>
               <?php CP_The_Event_No_button(); ?>
               <?php CP_The_Event_Maybe_button(); ?>
          </div>
    </div>

    <div class="event_registrations">
        <div class="event-yes">
            <h3 class="event-registration-title">
                   <?php _e('Attending', 'calendar-press'); ?>
               </h3>
               <?php CP_The_Event_yes(); ?>
          </div>

        <div class="event-maybe">
            <h3 class="event-registration-title">
                   <?php _e('Maybe Attending', 'calendar-press'); ?>
               </h3>
               <?php CP_The_Event_maybe(); ?>
          </div>
        <div class="event-no">
            <h3 class="event-registration-title">
                       <?php _e('Not Attending', 'calendar-press'); ?>
                   </h3>
               <?php CP_The_Event_no(); ?>
          </div>
    </div>
</div>
<div class="cleared"></div>