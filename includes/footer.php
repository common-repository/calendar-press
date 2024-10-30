<?php
/**
 * Settings Footer
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

<div style="clear:both;">
     <div class="postbox" style="width:49%; float:left">
          <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Credits', 'calendar-press'); ?></h3>
          <div style="padding:8px;">
               <p>
                    <?php
                    printf(
                        __('Thank you for trying the %1$s plugin - I hope you find it useful. For the latest updates on this plugin, vist the %2$s. If you have problems with this plugin, please use our %3$s. For help using this plugin, visit the %4$s.', 'calendar-press'),
                        $this->pluginName,
                        '<a href="http://grandslambert.com/plugins/calendar-press" target="_blank">' . __('official site', 'calendar-press') . '</a>',
                        '<a href="https://wordpress.org/support/plugin/calendar-press/" target="_blank">' . __('Support Forum', 'calendar-press') . '</a>',
                        '<a href="http://grandslambert.com/documentation/calendar-press/" target="_blank">' . __('Documentation Page', 'calendar-press') . '</a>'
                    ); ?>
               </p>
               <p>
                    <?php
                    printf(
                        __('This plugin is &copy; %1$s by %2$s and is released under the %3$s', 'calendar-press'),
                        '2009-' . date("Y"),
                        '<a href="http://grandslambert.com" target="_blank">GrandSlambert, Inc.</a>',
                        '<a href="http://www.gnu.org/licenses/gpl.html" target="_blank">' . __('GNU General Public License', 'calendar-press') . '</a>'
                    );
                    ?>
               </p>
          </div>
     </div>
     <div class="postbox" style="width:49%; float:right">
          <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Donate', 'calendar-press'); ?></h3>
          <div style="padding:8px">
               <p>
<?php printf(__('If you find this plugin useful, please consider supporting this and our other great %1$s.', 'calendar-press'), '<a href="http://grandslambert.com/plugins" target="_blank">' . __('plugins', 'calendar-press') . '</a>'); ?>
                    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=10528216" target="_blank"><?php _e('Donate a few bucks!', 'calendar-press'); ?></a>
               </p>
          </div>
     </div>
</div>