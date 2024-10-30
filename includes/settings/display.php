<?php
/**
 * The display settings page
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
?>
<div style="width:49%; float:left">
    
    <div class="postbox">
        <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
            <?php _e('Display Settings', 'calendar-press'); ?>
        </h3>
        <div class="table">
            <table class="form-table cp-table">
                <tbody>
                    <tr align="top">
                        <th scope="row"><label for="calendar_press_user_display_field"><?php _e('User Data to Display', 'calendar-press'); ?></label></th>
                        <td>
                            <select  id="calendar_press_user_display_field" name="<?php echo $this->optionsName; ?>[user-display-field]">
                                <option value="full_name" <?php selected($this->options['user-display-field'], 'full_name'); ?>><?php _e('Full Name', 'calendar-press'); ?></option>
                                <option value="first_name" <?php selected($this->options['user-display-field'], 'first_name'); ?>><?php _e('First Name', 'calendar-press'); ?></option>
                                <option value="last_name" <?php selected($this->options['user-display-field'], 'last_name'); ?>><?php _e('Last Name', 'calendar-press'); ?></option>
                                <option value="display_name" <?php selected($this->options['user-display-field'], 'display_name'); ?>><?php _e('Display Name', 'calendar-press'); ?></option>
                            </select>
                            <?php $this->help(esc_js(__('What user information to display for users who are signed up.', 'calendar-press'))); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="calendar_press_show_signup_date"><?php _e('Show signup dates?', 'calendar-press'); ?></label></th>
                        <td>
                            <input type="checkbox" name="<?php echo $this->optionsName; ?>[show-signup-date]" id="calendar_pres_show_signup_date" value="1" <?php checked($this->options['show-signup-date'], 1); ?> />
                            <?php $this->help(__('Tick this checkbox if you want to display the date a person signs up on the list of registrations.', 'calendar-press')); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="calendar_press_signup_date_format"><?php _e('Signup date format', 'calendar-press'); ?></label></th>
                        <td>
                            <input type="text" name="<?php echo $this->optionsName; ?>[signup-date-format]" id="calendar_press_signup_date_format" value="<?php echo $this->options['signup-date-format']; ?>" />
                            <?php printf(__('Uses the standard <a href="%1$s" target="_blank">WordPress Date Formatting</a>.', 'calendar-press'), 'http://codex.wordpress.org/Formatting_Date_and_Time'); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="calendar_press_default_excerpt_length"><?php _e('Default Excerpt Length', 'calendar-press'); ?></label></th>
                        <td>
                            <input type="text" name="<?php echo $this->optionsName; ?>[default-excerpt-length]" id="calendar_press_default_excerpt_length" value="<?php echo $this->options['default-excerpt-length']; ?>" />
                            <?php $this->help(esc_js(__('Default length of introduction excerpt when displaying in lists.', 'calendar-press'))); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="calendar_press_cookies"><?php _e('Enable Cookies', 'calendar-press'); ?></label></th>
                        <td>
                            <input name="<?php echo $this->optionsName; ?>[use-cookies]" id="calendar_press_cookies" type="checkbox" value="1" <?php checked($this->options['use-cookies'], 1); ?> />
                            <?php $this->help(__('Click this option to use cookies to remember the month and year to display when viewing the calendar page.', 'calendar-press')); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="calendar_press_custom_css"><?php _e('Enable Plugin CSS', 'calendar-press'); ?></label></th>
                        <td>
                            <input name="<?php echo $this->optionsName; ?>[custom-css]" id="calendar_press_custom_css" type="checkbox" value="1" <?php checked($this->options['custom-css'], 1); ?> />
                            <?php $this->help(__('Click this option to include the CSS from the plugin.', 'calendar-press')); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="calendar_press_disable_filters"><?php _e('Disable Filters', 'calendar-press'); ?></label></th>
                        <td>
                            <input name="<?php echo $this->optionsName; ?>[disable-filters]" id="calendar_press_custom_css" type="checkbox" value="1" <?php checked($this->options['disable-filters'], 1); ?> />
                            <?php $this->help(__('Click this option to include events in the lists of posts by author.', 'calendar-press')); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="calendar_press_author_archives"><?php _e('Show in Author Lists', 'calendar-press'); ?></label></th>
                        <td>
                            <input name="<?php echo $this->optionsName; ?>[author-archives]" id="calendar_press_custom_css" type="checkbox" value="1" <?php checked($this->options['author-archives'], 1); ?> />
                            <?php $this->help(__('Click this option to include events in the lists of posts by author.', 'calendar-press')); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="calendar_press_box_width"><?php _e('Calendar Box Width', 'calendar-press'); ?></label></th>
                        <td>
                            <input name="<?php echo $this->optionsName; ?>[box-width]" id="calendar_press_box_width" type="text" value="<?php echo $this->options['box-width']; ?>" />px
                            <?php $this->help(__('The widget (pixels) of the date boxes on the calendar page.', 'calendar-press')); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div  style="width:49%; float:right">
    <div class="postbox">
        <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
            <?php _e('Instructions', 'calendar-press'); ?>
        </h3>
        <div style="padding:8px">
            <?php printf(__('Visit the %1$s page for insructions for this page', 'calendar-press'), '<a href="https://grandslambert.com/documentation/calendar-press/settings/display/" target="_blank">' . __('Documentation', 'calendar-press') . '</a>'); ?>
        </div>
    </div>
</div>
