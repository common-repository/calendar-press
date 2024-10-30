<?php
/**
 * The code for calendar-list shortcode
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

<ul class="calendar-press-list-widget">
     <?php foreach ($posts as $post) : ?>

          <li><a href="<?php the_permalink(); ?>" 
               target="<?php echo $atts['target']; ?>">
               <?php echo $post->post_title; ?></a> <br>
               <?php CP_The_Start_date(); ?>
               <br><?php CP_The_Start_time(); ?> - 
               <?php CP_The_End_time(); ?>
          </li>

     <?php endforeach; ?>
</ul>