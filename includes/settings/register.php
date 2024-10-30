<?php
/**
 * The registration settings page
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
            <?php _e('Editor Options', 'calendar-press'); ?>
        </h3>
        <div class="table">
            <table class="form-table cp-table">
                <tbody>
                    <tr align="top">
                        <th scope="row"><label for="calendar_press_registration"><?php _e('Registration Type', 'calendar-press'); ?></label> : </th>
                        <td colspan="3">
                            <select id="calendar_press_registration" name="<?php echo $this->optionsName; ?>[registration-type]" onchange="signup_box_click(this.selectedIndex)">
                                <option value="none" <?php selected($this->options['registration-type'], 'none'); ?>><?php _e('No Registration', 'calendar-press'); ?></option>
                                <option value="signups" <?php selected($this->options['registration-type'], 'signups'); ?>><?php _e('Limited Signups', 'calendar-press'); ?></option>
                                <option value="yesno" <?php selected($this->options['registration-type'], 'yesno'); ?>><?php _e('Yes/No/Maybe Type', 'calendar-press'); ?></option>
                                <option value="select" <?php selected($this->options['registration-type'], 'select'); ?>><?php _e('Select on Edit Screen', 'calendar-press'); ?></option>
                            </select>
                            <?php $this->help(__('Select the type of event registration you want to use. (No Registration) will show no registration information.', 'calendar-press')); ?>
                        </td>
                    </tr>
                    <tr id="signup_extra_fields" align="top" style="display: <?php echo ($this->options['registration-type'] == 'signups' || $this->options['registration-type'] == 'select') ? '' : 'none'; ?>">
                        <td colspan="4" class="cp-wide-table">
                            <table class="form-table">
                                <tr align="top">
                                    <th colspan="3" class="grey"><?php _e('Options for limited signups.', 'calendar-press'); ?></th>
                                </tr>
                                <tr align="top">
                                    <th><?php _e('Signup Type', 'calendar-press'); ?> <?php $this->help(__('Select which options you want to make available on the site. Since signups is required for this type of registration, the checkbox is disabled.', 'calendar-press')); ?></th>
                                    <th><?php _e('Title', 'calendar-press'); ?> <?php $this->help(esc_js(__('Name used for the type of signup option, if active.', 'calendar-press'))); ?></th>
                                    <th><?php _e('Default', 'calendar-press'); ?> <?php $this->help(__('Set the default number of available slots for each signup type.', 'calendar-press')); ?></th>
                                </tr>
                                <tr align="top">
                                    <td>
                                        <label><?php _e('Signups ', 'calendar-press'); ?> : <input name="<?php echo $this->optionsName; ?>[use-signups]" id="calendar_press_use_signups" disabled="disabled" type="checkbox" value="1" checked="checked" /></label>
                                    </td>
                                    <td>
                                        <input type="text" name="<?php echo $this->optionsName; ?>[signups-title]" id="calendar_press_signups_title" value="<?php echo $this->options['signups-title']; ?>" />
                                    </td>
                                    
                                    <td>
                                        <input name="<?php echo $this->optionsName; ?>[signups-default]" id="calendar_press_signups" type="input" class="input number" value="<?php echo $this->options['signups-default']; ?>" />
                                    </td>
                                </tr>
                                <tr align="top">
                                    <td>
                                        <label><?php _e('Overflow', 'calendar-press'); ?> : <input name="<?php echo $this->optionsName; ?>[use-overflow]" id="calendar_press_use_overflow" type="checkbox" value="1" <?php checked($this->options['use-overflow'], 1); ?> /></label>
                                    </td>
                                    <td>
                                        <input type="text" name="<?php echo $this->optionsName; ?>[overflow-title]" id="calendar_press_overflow_title" value="<?php echo $this->options['overflow-title']; ?>" />
                                    </td>
                                    <td>
                                        <input name="<?php echo $this->optionsName; ?>[overflow-default]" id="calendar_press_overflow" type="input" class="input number" value="<?php echo $this->options['overflow-default']; ?>" />
                                    </td>
                                </tr>
                                <tr align="top" class="no-border">
                                    <td>
                                        <label><?php _e('Waiting lists', 'calendar-press'); ?> : <input name="<?php echo $this->optionsName; ?>[use-waiting]" id="calendar_press_use_waiting" type="checkbox" value="1" <?php checked($this->options['use-waiting'], 1); ?> /></label>
                                    </td>
                                    <td>
                                        <input type="text" name="<?php echo $this->optionsName; ?>[waiting-title]" id="calendar_press_waiting_title" value="<?php echo $this->options['waiting-title']; ?>" />
                                    </td>
                                    <td>
                                        <input name="<?php echo $this->optionsName; ?>[waiting-default]" id="calendar_press_waiting_default" type="input" class="input number" value="<?php echo $this->options['waiting-default']; ?>" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr id="signup_yesno_fields" align="top" style="display: <?php echo ($this->options['registration-type'] == 'yesno' || $this->options['registration-type'] == 'select') ? '' : 'none'; ?>">
                        <td colspan="4" class="cp-wide-table">
                            <table class="form-table">
                                <tr align="top">
                                    <th colspan="4" class="grey"><?php _e('Options for yes/no/maybe signups.', 'calendar-press'); ?></th>
                                </tr>
                                <tr align="top" class="no-border"
                                <th scope="row"><label for="calendar_press_yesno_options"><?php _e('Allow which options?', 'calendar-press'); ?></label> : </th>
                                <td colspan="3">
                                    <label>
                                        <input type="checkbox" class="input checkbox" id="yes_option" name="<?php echo $this->optionsName; ?>[yes-option]" <?php checked($this->options['yes-option'], true); ?>  value="1" />
                                        <?php _e('Yes', 'calendar-press'); ?>
                                    </label>
                                    <label>
                                        <input type="checkbox" class="input checkbox" id="no_option" name="<?php echo $this->optionsName; ?>[no-option]" <?php checked($this->options['no-option'], true); ?>  value="1" />
                                        <?php _e('No', 'calendar-press'); ?>
                                        </label><label>
                                        <input type="checkbox" class="input checkbox" id="maybe_option" name="<?php echo $this->optionsName; ?>[maybe-option]" <?php checked($this->options['maybe-option'], true); ?>  value="1" />
                                        <?php _e('Maybe', 'calendar-press'); ?>
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
<div  style="width:49%; float:right">
    <div class="postbox">
        <h3 class="handl" style="margin:0;padding:3px;cursor:default;"><?php _e('Instructions', 'calendar-press'); ?></h3>
        <div style="padding:8px">
            <p><?php _e('The registration options tab allows you to select the type of registrations, if any, to use in CalendarPress.', 'calendar-press'); ?></p>
            <p><?php printf(__('If you need help configuring registration options, you should read the information on the %1$s page.', 'calendar-press'), '<a href="https://grandslambert.com/documentation/calendar-press/settings/registration/" target="_blank">' . __('Registration Documentation', 'calendar-press'). '</a>'); ?></p>
            <ul>
                <li><strong><?php _e('No Registration', 'calendar-press'); ?></strong>: <?php _e('To use CalendarPress with no registration opeions, select No Registration.', 'calendar-press'); ?></li>
                <li><strong><?php _e('Limited Sign-ups', 'calendar-press'); ?></strong>: <?php _e('Select this item to use only the Limited Sign-ups feature.', 'calendar-press'); ?></li>
                <li><strong><?php _e('Yes/No/Maybe Type', 'calendar-press'); ?></strong>: <?php _e('Select this item to only the Yes/No/Maybe type of registration.', 'calendar-press'); ?></li>
                <li><strong><?php _e('Select on Edit Screen', 'calendar-press'); ?></strong>: <?php _e('Select this item to allow the event editor to select the type of registration.', 'calendar-press'); ?></li>
            </ul>
        </div>
    </div>
    <div class="postbox">
        <h3 class="handl" style="margin:0;padding:3px;cursor:default;"><?php _e('Limited Sign-ups', 'calendar-press'); ?></h3>
        <div style="padding:8px">
            <p><?php _e('The limited sign-ups option allows you to set a maximum number of registrants for an event. There are options for overflow registrations and waiting lists. You can check the options you want to make acvaialble on the site.', 'calendar-press'); ?></p>
        </div>
        </div><div class="postbox">
        <h3 class="handl" style="margin:0;padding:3px;cursor:default;"><?php _e('Yes/No/Maybe Registration', 'calendar-press'); ?></h3>
        <div style="padding:8px">
            <p><?php _e('The Yes/No/Maybe registration option will provide you with a FaceBook style registration where users check the option for the event. You can decide here which of the three options will be available on the site.', 'calendar-press'); ?></p>
        </div>
    </div>
    </div>    