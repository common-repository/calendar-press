<?php
/**
 * The taxonomies settings page
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
            <?php _e('Taxonomies', 'calendar-press'); ?>
        </h3>
        <div class="table">
            <table class="form-table cp-table">
                <tbody>
                    <?php foreach ( $this->options['taxonomies'] as $key => $taxonomy ) : ?>
                    
                        <?php $taxonomy = $this->taxDefaults($taxonomy); ?>
                    <tr align="top">
                        <td class="rp-taxonomy-header grey" colspan="2"><?php echo (isset($taxonomy['converted'])) ? _e('Converting', 'calendar-press') : ''; ?> <?php printf(__('Settings for the taxonomy "%1$s"', 'calendar-press'), $key); ?></td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="<?php echo $key; ?>_plural_name"><?php _e('Plural Name', 'calendar-press'); ?></label></th>
                        <td>
                            <input type="text" name="<?php echo $this->optionsName; ?>[taxonomies][<?php echo $key; ?>][plural]" id="<?php echo $key; ?>_plural_name" value="<?php echo $taxonomy['plural']; ?>" />
                        <?php $this->help(esc_js(sprintf(__('Plural name to use in the menus for this plugin for the taxonomy "%1$s".', 'calendar-press'), $taxonomy['plural']))); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="<?php echo $key; ?>_singular_name"><?php _e('Singular Name', 'calendar-press'); ?></label></th>
                        <td>
                            <input type="text" name="<?php echo $this->optionsName; ?>[taxonomies][<?php echo $key; ?>][singular]" id="<?php echo $key; ?>_singular_name" value="<?php echo $taxonomy['singular']; ?>" />
                        <?php $this->help(esc_js(sprintf(__('Singular name to use in the menus for this plugin for the taxonomy "%1$s".', 'calendar-press'), $taxonomy['singular']))); ?>
                        </td>
                    </tr>
                        <?php if ($taxonomy['active'] ) : ?>
                    <tr align="top">
                        <th scope="row"><label for="<?php echo $key; ?>_default"><?php _e('Default', 'calendar-press'); ?></label></th>
                        <td>
                            <?php wp_dropdown_categories(array('hierarchical' => $taxonomy['hierarchical'], 'taxonomy' => $key, 'show_option_none' => __('No Default', 'calendar-press'), 'hide_empty' => false, 'name' => $this->optionsName . '[taxonomies][' . $key . '][default]', 'id' => $key, 'orderby' => 'name', 'selected' => $taxonomy['default'])); ?>
                        </td>
                    </tr>
                        <?php endif; ?>
                    <tr align="top">
                        <th scope="row"><label for="<?php echo $key; ?>_hierarchical"><?php _e('Hierarchical', 'calendar-press'); ?></label></th>
                        <td>
                            <input type="checkbox" name="<?php echo $this->optionsName; ?>[taxonomies][<?php echo $key; ?>][hierarchical]" id="<?php echo $key; ?>_hierarchical" value="1" <?php checked($taxonomy['hierarchical'], 1); ?> />
                        <?php $this->help(esc_js(sprintf(__('Should the taxonomy "%1$s" have a hierarchical structure like post tags', 'calendar-press'), $taxonomy['singular']))); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="<?php echo $key; ?>_active"><?php _e('Activate', 'calendar-press'); ?></label></th>
                        <td>
                            <input type="checkbox" name="<?php echo $this->optionsName; ?>[taxonomies][<?php echo $key; ?>][active]" id="<?php echo $key; ?>_active" value="1" <?php checked($taxonomy['active'], 1); ?> />
                        <?php $this->help(esc_js(sprintf(__('Should the taxonomy "%1$s" have a active structure like post tags', 'calendar-press'), $taxonomy['singular']))); ?>
                        </td>
                    </tr>
                    <tr align="top">
                        <th scope="row"><label for="<?php echo $key; ?>_delete"><?php _e('Delete', 'calendar-press'); ?></label></th>
                        <td>
                            <input type="checkbox" name="<?php echo $this->optionsName; ?>[taxonomies][<?php echo $key; ?>][delete]" id="<?php echo $key; ?>_delete" value="1" onclick="confirmTaxDelete('<?php echo $taxonomy['plural']; ?>', '<?php echo $key; ?>');" />
                        <?php $this->help(esc_js(sprintf(__('Delete the taxonomy %1$s? Will not remove the data, only remove the taxonomy options.', 'calendar-press'), $taxonomy['singular']))); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div  style="width:49%; float:right">
    <div class="postbox">
        <h3 class="handl" style="margin:0;padding:3px;cursor:default;">
            <?php _e('Taxonomies Instructions', 'calendar-press'); ?>
        </h3>
        <div style="padding:8px">
            
            
            <p><?php _e('If you want to have a page that lists your taxonomies, you need to do one of two things:', 'calendar-press'); ?></p>
            <p>
                <strong><?php _e('Create Pages', 'calendar-press'); ?></strong>: <?php printf(__('Create individual pages for each taxonomy that will list the terms. These pages must have the [calendar-tax] short code on them. [%1$s]'), '<a href="https://grandslambert.com/documentation/calendar-press/shortcodes/calendar-taxonomies/" target="_blank">' . __('Documentation for shortcode', 'calendar-press') . '</a>'); ?>
            </p>
            <p>
                <strong><?php _e('Create Template File', 'calendar-press'); ?></strong>: <?php printf(__('If you create a template file named `recipe-taxonomy.php` in your theme, all taxonomies will use this template to display a list of taxonomies. [%1$s]'), '<a href="https://grandslambert.com/documentation/calendar-press/settings/taxonomy-template/" target="_blank">' . __('Documentation', 'calendar-press') . '</a>'); ?>
            </p>
            <p>
                <strong><?php _e('Warning!', 'calendar-press'); ?></strong> <?php _e('If you do not select a display page for a taxonomy and the template file does not exist, any calls to the site with the URL slug for the taxonomy will redirect to your default recipe list.', 'calendar-press'); ?>
            </p>
        </div>
    </div>
</div>
