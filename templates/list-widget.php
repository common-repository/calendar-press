<?php
/**
 * The Template for displaying an event in a widget.
 * php version 8.1.10
 *
 * @category   WordPress_Template
 * @package    Calendar_Press
 * @subpackage Templates
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}
?>

<ul class="calendar-press-list-widget">
     <?php foreach ($posts as $post) : ?>
          <li>
              <?php echo event_get_event_link($post); ?>
              <br>
            <?php
                CP_The_Start_date(
                    get_post_meta($post->ID, '_begin_date_value', true)
                );
            ?>
            <br><?php
                CP_The_Start_time(
                    get_post_meta($post->ID, '_begin_time_value', true)
                );
                ?>
            - <?php
                CP_The_End_time(
                    get_post_meta($post->ID, '_end_time_value', true)
                );
                ?>
     </li>
     <?php endforeach; ?>
</ul>
