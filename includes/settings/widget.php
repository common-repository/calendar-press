<?php
/**
 * The widget settings page
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
            <?php _e('Widget Defaults', 'calendar-press'); ?>
        </h3>
        <table class="form-table cp-table">
            <tr align="top">
                <th scope="row"><label for="calendar_press_widget_items"><?php _e('Default Items to Display', 'calendar-press'); ?></label></th>
                <td>
                    <select name="<?php echo $this->optionsName; ?>[widget-items]" id="calendar_press_widget_items">
                        <?php
                        for ( $i = 1; $i <= 20; ++$i ) { 
                            echo "<option value='$i' " . selected($this->options['widget-items'], $i) . ">$i</option>";
                        }
                        ?>
                    </select>
                    <?php $this->help(__('Default for new widgets.', 'calendar-press')); ?>
                </td>
            </tr>
            <tr align="top">
                <th scope="row"><label for="calendar_press_widget_type"><?php _e('Default List Widget Type', 'calendar-press'); ?></label></th>
                <td>
                    <select name="<?php echo $this->optionsName; ?>[widget-type]" id="calendar_press_widget_type">
                        <option value="next" <?php selected($this->options['widget-type'], 'next'); ?> ><?php _e('Next Events', 'calendar-press'); ?></option>
                        <option value="newest" <?php selected($this->options['widget-type'], 'newest'); ?> ><?php _e('Newest Events', 'calendar-press'); ?></option>
                        <option value="featured" <?php selected($this->options['widget-type'], 'featured'); ?> ><?php _e('Featured', 'calendar-press'); ?></option>
                        <option value="updated" <?php selected($this->options['widget-type'], 'updated'); ?> ><?php _e('Redently Updated', 'calendar-press'); ?></option>
                    </select>
                    <?php $this->help(__('Default link target when adding a new widget.', 'calendar-press')); ?>
                </td>
            </tr>
            <tr align="top" class="no-border">
                <th scope="row"><label for="calendar_press_widget_target"><?php _e('Default Link Target', 'calendar-press'); ?></label></th>
                <td>
                    <select name="<?php echo $this->optionsName; ?>[widget-target]" id="calendar_press_widget_target">
                        <option value="0">None</option>
                        <option value="_blank" <?php selected($this->options['widget-target'], '_blank'); ?>>New Window</option>
                        <option value="_top" <?php selected($this->options['widget-target'], '_top'); ?>>Top Window</option>
                    </select>
                    <?php $this->help(__('Default link target when adding a new widget.', 'calendar-press')); ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<div  style="width:49%; float:right">
    <div class="postbox">
        <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
            <?php _e('Instructions', 'calendar-press'); ?>
        </h3>
        <div style="padding:8px">
            <?php printf(__('Visit the %1$s page for insructions for this page', 'calendar-press'), '<a href="https://grandslambert.com/documentation/calendar-press/settings/widget/" target="_blank">' . __('Documentation', 'calendar-press') . '</a>'); ?>
        </div>
    </div>
</div>
