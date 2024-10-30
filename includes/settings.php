<?php
/**
 * The settings page
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

/* Flush the rewrite rules */
global $wp_rewrite, $wp_query;
    $wp_rewrite->flush_rules();
    
if (isset($_REQUEST['tab']) ) {
    $selectedTab = sanitize_text_field($_REQUEST['tab']);
} else {
    $selectedTab = 'features';
}
    
    $tabs = array(
    'features' => __('Features', 'calendar-press'),
    'permalinks' => __('Permalinks', 'calendar-press'),
    'taxonomies' => __('Taxonomies', 'calendar-press'),
    'register' => __('Registration', 'calendar-press'),
    'display' => __('Display', 'calendar-press'),
    'widget' => __('Widget', 'calendar-press'),
    'administration' => __('Administration', 'calendar-press')
    );
    ?>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;" class="overDiv"></div>
<div class="wrap">
    <form method="post" action="options.php" id="calendar_press_settings">
        <div class="icon32" id="icon-calendar-press"><br/></div>
        <h2><?php echo $this->pluginName; ?> &raquo; <?php _e('Plugin Settings', 'calendar-press'); ?> </h2>
        <?php if (isset($_REQUEST['reset']) ) : ?>
        <div id="settings-error-calendar-press_upated" class="updated settings-error">
            <p><strong><?php _e('CalendarPress settings have been reset to defaults.', 'calendar-press'); ?></strong></p>
        </div>
        <?php elseif (isset($_REQUEST['updated']) ) : ?>
        <div id="settings-error-calendar-press_upated" class="updated settings-error">
            <p><strong><?php _e('CalendarPress Settings Saved.', 'calendar-press'); ?></strong></p>
        </div>
        <?php endif; ?>
        <?php settings_fields($this->optionsName); ?>
        <input type="hidden" name="<?php echo $this->optionsName; ?>[random-value]" value="<?php echo rand(1000, 100000); ?>" />
        <input type="hidden" name="active_tab" id="active_tab" value="<?php echo $selectedTab; ?>" />
        <ul id="calendar_press_tabs">
            <?php foreach ( $tabs as $tab => $name ) : ?>
            <li id="calendar_press_<?php echo $tab; ?>" class="calendar-press<?php echo ($selectedTab == $tab) ? '-selected' : ''; ?>" style="display: <?php echo (!$this->options['use-' . $tab]) ? 'none' : 'block'; ?>">
                <a href="#top" onclick="calendar_press_show_tab('<?php echo $tab; ?>')"><?php echo $name; ?></a>
            </li>
            <?php endforeach; ?>
            <li id="recipe_press_save" class="recipe-press-tab save-tab">
                <a href="#top" onclick="calendar_press_settings_submit()"><?php _e('Save Settings', 'recipe-press'); ?></a>
            </li>
        </ul>
        
        <?php foreach ( $tabs as $tab => $name ) : ?>
        <div id="calendar_press_box_<?php echo $tab; ?>" style="display: <?php echo ($selectedTab == $tab) ? 'block' : 'none'; ?>">
            <?php include_once 'settings/' . $tab . '.php'; ?>
        </div>
        <?php endforeach; ?>
    </form>
</div>
<?php require_once 'footer.php'; ?>
