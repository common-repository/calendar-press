<?php
/**
 * The code for limited type of registration.
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
        <div>
                 <?php CP_The_Event_Signup_utton(); ?>
                 <?php CP_The_Event_Overflow_button(); ?>
            </div>
    </div>

    <div class="event-signups">
        <h3 class="event-registration-title">
            <?php CP_The_Signup_title(); ?>
        </h3>
        <?php CP_The_Event_signups(); ?>
    </div>

     <?php if (CP_Use_Overflow_option() ) : ?>
        <div class="event-overflow">
        <h3 class="event-registration-title">
                <?php CP_The_Overflow_title(); ?>
            </h3>
            <?php CP_The_Event_overflow(); ?>
        </div>
     <?php endif; ?>
</div>