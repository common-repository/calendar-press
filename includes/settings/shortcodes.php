<?php
/**
 * The shortcodes information block
 * php version 8.1.10
 *
 * @category   WordPress_Template
 * @package    Calendar_Press
 * @subpackage Settings
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}
?><div class="postbox">
    <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Available Shortcodes', 'calendar-press'); ?></h3>
    <div style="padding:8px">
        <p><?php _e('There are several shortcodes available in CalendarPress, but the most useful will likely be [event-list] and [event-calendar].', 'calendar-press'); ?></p>
        <ul>
            <li><strong>[event-list]</strong>: <?php printf(__('Used to display a list of events. [%1$s]'), '<a href="https://grandslambert.com/documentation/calendar-press/shortcoees/event-list" target="_blank">' . __('Documentation', 'calendar-press') . '</a>'); ?></li>
            <li><strong>[event-calendar]</strong>: <?php printf(__('Used to display a calendar of events. [%1$s]'), '<a href="https://grandslambert.com/documentation/calendar-press/shortcodes/event-calendar" target="_blank">' . __('Documentation', 'calendar-press') . '</a>'); ?></li>
            <li><strong>[event-show]</strong>: <?php printf(__('Used to display a single event on any post or page. [%1$s]'), '<a href="https://grandslambert.com/documentation/calendar-press/shortcodes/event-show" target="_blank">' . __('Documentation', 'calendar-press') . '</a>'); ?></li>
        </ul>
    </div>
</div>
