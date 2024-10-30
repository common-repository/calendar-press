<?php
/**
 * Details Meta Box
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

<input type="hidden" name="details_noncename" id="details_noncename" value="<?php echo wp_create_nonce('calendar_press_details'); ?>" />

<div class="detailsbox">
    <div class="details-minor">
        
        <?php if ($this->options['use-featured'] ) : ?>
        <div class="event-details event-details-featured">
            <label for="event_featured"><?php _e('Featured Event:', 'calendar-press'); ?></label>
            <input type="checkbox" class="checkbox" name="event_details[event_featured]" id="event_featured" value="1" <?php checked(get_post_meta($post->ID, '_event_featured_value', true), 1); ?> />
        </div>
        <?php endif; ?>
        
        <?php if ($this->options['use-popups'] ) : ?>
        <div class="event-details event-details-popup no-border">
            <label for="event_popup"><?php _e('Enable popup:', 'calendar-press'); ?></label>
            <input type="checkbox" class="checkbox" name="event_details[event_popup]" id="event_popup" value="1" <?php checked(get_post_meta($post->ID, '_event_popup_value', true), 1); ?> />
        </div>
        <?php endif; ?>
    </div>
</div>