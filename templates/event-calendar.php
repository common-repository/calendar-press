<?php
/**
 * The Template for displaying an event calendar.
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

<div 
    id="overDiv" 
    style="position:absolute;
    visibility:hidden; z-index:1000;"
    class="overDiv">
</div>

<div class="cp-navigation">
    <div class="cp-prev-month"><?php CP_The_Event_Last_month(); ?></div>
    <div class="cp-curr-month"><?php CP_The_Event_This_month(); ?></div>
    <div class="cp-next-month"><?php CP_The_Event_Next_month(); ?></div>
</div>
<dl class="cp-list-dow">
    <dt class="cp-box-width"><?php _e('Sunday'); ?></dt>
    <dt class="cp-box-width"><?php _e('Monday'); ?></dt>
    <dt class="cp-box-width"><?php _e('Tuesday'); ?></dt>
    <dt class="cp-box-width"><?php _e('Wednesday'); ?></dt>
    <dt class="cp-box-width"><?php _e('Thursday'); ?></dt>
    <dt class="cp-box-width"><?php _e('Friday'); ?></dt>
    <dt class="cp-box-width"><?php _e('Saturday'); ?></dt>
</dl>
<div class="cleared" style="clear:both"></div>

<?php CP_Event_calendar($this->currMonth, $this->currYear); ?>

<div class="cleared" style="clear:both"></div>
