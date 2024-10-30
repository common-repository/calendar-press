<?php
/**
 * The code for simple calendar-list shortcode
 * php version 8.1.10
 *
 * @category   WordPress_Shortcode
 * @package    Calendar_Press
 * @subpackage Shortcode
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}
?>

<ul class="calendar-press-list-widget" style="">
     <?php foreach ($posts as $post) : ?>

          <li style="line-height: 25px; border-bottom: solid 1px #ddd;">
                <?php echo event_get_event_link($post); ?>
               on <?php CP_The_Start_date(); ?>
               from <?php CP_The_Start_time(); ?> to 
               <?php CP_The_End_time(); ?>
          </li>

     <?php endforeach; ?>
</ul>