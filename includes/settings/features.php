<?php
/**
 * The features settings page
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
            <?php _e('Features', 'calendar-press'); ?>
        </h3>
        <div class="table">
            <table class="form-table cp-table">
                <tbody>
                    <?php if (version_compare(get_bloginfo('version'), '3.0.999', '>') ) : ?>
                    
                    <tr align="top">
                        <th scope="row"><label for="calendar_press_use_plugin_permalinks"><?php _e('Use plugin permalinks?', 'calendar-press'); ?></label></th>
                        <td>
                            <input name="<?php echo $this->optionsName; ?>[use-plugin-permalinks]" id="calendar_press_use_plugin_permalinks" type="checkbox" value="1" <?php checked($this->options['use-plugin-permalinks'], 1); ?> onclick="calendar_press_show_permalinks(this)" />
                        <?php $this->help(__('Wordpress 3.1+ has a feature to list calendars on an index page. If you prefer to use your own permalink structure, check this box and the plugin will use the settings below.', 'calendar-press')); ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr align="top" class="no-border">
                        <td colspan="2" class="cp-wide-table">
                            <table class="form-table cp-table">
                                <tr align="top">
                                    <td><label title="<?php _e('Enable event locations included with the plugin.', 'calendar-press'); ?>"><input name="<?php echo $this->optionsName; ?>[use-locations]" id="calendar_press_use_locations" type="checkbox" value="0" disabled="disabled" <?php checked($this->options['use-locations'], 1); ?> /> <?php _e('Locations', 'calendar-press'); ?> - <strong>Coming in version 0.5</strong></label></td>
                                    <td><label title="<?php _e('Enable the custom taxonomies included with the plugin.', 'calendar-press'); ?>"><input name="<?php echo $this->optionsName; ?>[use-taxonomies]" id="calendar_press_use_taxonomies" type="checkbox" value="1" <?php checked($this->options['use-taxonomies'], 1); ?> /> <?php _e('Taxonomies', 'calendar-press'); ?></label></td>
                                    <td><label title="<?php _e('Turn on the featured images of WordPress 3.0 to enable thumbnail images for events.', 'calendar-press'); ?>"><input name="<?php echo $this->optionsName; ?>[use-thumbnails]" id="calendar_press_use_thumbnails" type="checkbox" value="1" <?php checked($this->options['use-thumbnails'], 1); ?> /> <?php _e('Post Thumbnails', 'calendar-press'); ?></label></td>
                                </tr>
                                <tr align="top">
                                    <td><label title="<?php _e('Enable the Featured Event option (used in widgets and short codes).', 'calendar-press'); ?>"><input name="<?php echo $this->optionsName; ?>[use-featured]" id="calendar_press_use_featured" type="checkbox" value="1" <?php checked($this->options['use-featured'], 1); ?> /> <?php _e('Featured Events', 'calendar-press'); ?></label></td>
                                    <td><label title="<?php _e('Enable the event popup windows.', 'calendar-press'); ?>"><input name="<?php echo $this->optionsName; ?>[use-popups]" id="calendar_press_use_custom_fields" type="checkbox" value="1" <?php checked($this->options['use-popups'], 1); ?> /> <?php _e('Pop-up Details', 'calendar-press'); ?></label></td>
                                    <td><label title="<?php _e('Enable the use of custom fields for events.', 'calendar-press'); ?>"><input name="<?php echo $this->optionsName; ?>[use-custom-fields]" id="calendar_press_use_custom_fields" type="checkbox" value="1" <?php checked($this->options['use-custom-fields'], 1); ?> /> <?php _e('Custom Fields', 'calendar-press'); ?></label></td>
                                </tr>
                                <tr align="top">
                                    <td><label title="<?php _e('Enable WordPress comments for events.', 'calendar-press'); ?>"><input name="<?php echo $this->optionsName; ?>[use-comments]" id="calendar_press_use_comments" type="checkbox" value="1" <?php checked($this->options['use-comments'], 1); ?> /> <?php _e('Comments', 'calendar-press'); ?></label></td>
                                    <td><label title="<?php _e('Enable WordPress trackbacks for events', 'calendar-press'); ?>"><input name="<?php echo $this->optionsName; ?>[use-trackbacks]" id="calendar_press_use_trackbacks" type="checkbox" value="1" <?php checked($this->options['use-trackbacks'], 1); ?> /> <?php _e('Trackbacks', 'calendar-press'); ?></label></td>
                                    <td><label title="<?php _e('Enable WordPress revisions for events.', 'calendar-press'); ?>"><input name="<?php echo $this->optionsName; ?>[use-revisions]" id="calendar_press_use_revisions" type="checkbox" value="1" <?php checked($this->options['use-revisions'], 1); ?> /> <?php _e('Revisions', 'calendar-press'); ?></label></td>
                                </tr>
                                <tr align="top" class="no-border">
                                    <th class="grey" colspan="3">
                                        <?php _e('You can enable the use of the built in WordPress taxonomies if you prefer to use these over the custom taxonomies included with the plugin.', 'calendar-press'); ?>
                                    </th>
                                </tr>
                                <tr align="top">
                                    <td><label title="<?php _e('Enable the WordPress built in categories taxonomy for events.', 'calendar-press'); ?>"><input name="<?php echo $this->optionsName; ?>[use-post-categories]" id="calendar_press_use_post_categories" type="checkbox" value="1" <?php checked($this->options['use-post-categories'], 1); ?> /> <?php _e('Post Categories', 'calendar-press'); ?></label></td>
                                    <td><label title="<?php _e('Enable the WordPress built in tags taxonomy for events.', 'calendar-press'); ?>"><input name="<?php echo $this->optionsName; ?>[use-post-tags]" id="calendar_press_use_post_tags" type="checkbox" value="1" <?php checked($this->options['use-post-tags'], 1); ?> /> <?php _e('Post Tags', 'calendar-press'); ?></label></td>
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
        <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Feature Information', 'calendar-press'); ?></h3>
        <div style="padding:8px">
            <p><?php _e('There are several features that you can add to CalendarPress. Activating some features, including locations and taxonomies, will add additional tabs to this page to allow you to configure those features more.', 'calendar-press'); ?></p>
            <p><?php printf(__('If you need help configuring CalendarPress, you should read the information on the %1$s page.', 'calendar-press'), '<a href="https://grandslambert.com/documentation/calendar-press/settings/features/" target="_blank">' . __('Features Documentation', 'calendar-press'). '</a>'); ?></p>
        </div>
    </div>
    
    <?php require 'shortcodes.php'; ?>
</div>
