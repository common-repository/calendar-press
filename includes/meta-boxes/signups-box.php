<?php
/**
 * Signups Meta Box
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

<input type="hidden" name="signups_noncename" id="signups_noncename" value="<?php echo wp_create_nonce('calendar_press_signups'); ?>" />

<div class="datebox">
    <div class="datebox-minor">
        <?php if ($this->options['registration-type'] == 'select' ) : ?>
        <div class="event-details">
            <label for="singup_type"><?php _e('Registration Type: ', 'calendar-press'); ?></label>
            <select id="calendar_press_registration" name="event_signups[registration_type]" onchange="signup_box_click(this.selectedIndex)">
                <option value="none" <?php selected($signup_type, 'none'); ?>><?php _e('No Registration', 'calendar-press'); ?></option>
                <option value="signups" <?php selected($signup_type, 'signups'); ?>><?php _e('Limited Signups', 'calendar-press'); ?></option>
                <option value="yesno" <?php selected($signup_type, 'yesno'); ?>><?php _e('Yes/No/Maybe Type', 'calendar-press'); ?></option>
            </select>
        </div>
        <?php endif; ?>
        
        <div id="signup_extra_fields" style="display: <?php echo ($signup_type == 'signups') ? 'block' : 'none'; ?>">
            <div class="event-details">
                <label for="event_signups"><?php echo $this->options['signups-title']; ?></label>
                <input type="input" class="input number" name="event_signups[event_signups]" id="event_signups" value="<?php echo $signupCount; ?>" />
            </div>
            
            <?php if ($this->options['use-overflow'] ) : ?>
            <div class="event-details no-border">
                <label for="event_overflow"><?php echo $this->options['overflow-title']; ?></label>
                <input type="input" class="input number" name="event_signups[event_overflow]" id="event_overflow" value="<?php echo $overflowCount; ?>" />
            </div>
            <?php endif; ?>
            
        </div>
        <div id="signup_yesno_fields" style="display: <?php echo ($signup_type == 'yesno') ? 'block' : 'none'; ?>">
            <?php if ($this->options['yes-option'] ) : ?>
            <div class="event-details">
                <label for="event_yes"><?php _e('Allow "Yes" option?', 'calendar-press'); ?></label>
                <input type="checkbox" class="input checkbox" name="event_signups[yes_option]" id="event_yes" value="1" <?php checked(get_post_meta($post->ID, '_yes_option_value', true)); ?> />
            </div>
            <?php endif; ?>
            
            <?php if ($this->options['no-option'] ) : ?>
            <div class="event-details">
                <label for="event_no"><?php _e('Allow "No" option?', 'calendar-press'); ?></label>
                <input type="checkbox" class="input checkbox" name="event_signups[no_option]" id="event_no" value="1" <?php checked(get_post_meta($post->ID, '_no_option_value', true)); ?> />
            </div>
            <?php endif; ?>
            
            <?php if ($this->options['maybe-option'] ) : ?>
            <div class="event-details no-border">
                <label for="event_maybe"><?php _e('Allow "Maybe" option?', 'calendar-press'); ?></label>
                <input type="checkbox" class="input checkbox" name="event_signups[maybe_option]" id="event_maybe" value="1" <?php checked(get_post_meta($post->ID, '_maybe_option_value', true)); ?> />
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>