<?php
/**
 * The Template for displaying an event loop.
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
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="event-meta">
        <div class="event-dates"><?php CP_The_Event_dates(); ?></div>
    </div>

    <div class="entry-content">
        <?php the_content(); ?>
    </div>
</div><!-- #post-## -->
