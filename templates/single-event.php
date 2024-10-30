<?php
/**
 * The Template for displaying an event.
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
        <div class="event-category"><?php CP_The_Event_category(); ?></div>
    </div>

    <div class="entry-content">
          <?php the_content(); ?>
     </div>

    <h3 class="event-registration-text">
        <?php _e('Registration', 'calendar-press'); ?>
    </h3>
    
    <?php
    $openDate = get_post_meta($post->ID, '_open_date_value', true);
    if ($openDate > time()) {
        echo '<p style="text-align:center">';
        if (get_post_meta($post->ID, '_open_date_display_value')) {
            _e(
                "Signups for this session are not open yet. Check back later.",
                'calender-press'
            );
        } else {
            echo "Signups for this session will open on " .
                        date('l, F jS, Y', $openDate);
        }
        echo "</p>";
    } else {
        CP_Event_registrations();
    }
    ?>

    <div class="entry-content">
        <?php wp_link_pages(
            array(
                'before' => '<div class="page-link">'
                . __('Pages:', 'calendar-press'),
                'after' => '</div>')
        ); ?>
        <?php edit_post_link(
            __('Edit Event', 'calendar-press'),
            '<span class="edit-link">', '</span>'
        ); ?>
    </div>
    <!-- .entry-content -->

</div>
<!-- #post-## -->
