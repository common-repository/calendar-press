<?php
/**
 * Calendar Press Widget Form
 * php version 8.1.10
 * 
 * @category   WordPress_Widget
 * @package    Calendar_Press
 * @subpackage Widgets
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}
?>
<p>
    <label for="<?php echo $this->get_field_id('title'); ?>">
        <?php _e('Widget Title (optional)', 'calendar-press'); ?> : 
    </label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
        name="<?php echo $this->get_field_name('title'); ?>" type="text"
        value="<?php echo $instance['title']; ?>"
    />
</p>
<p>
    <label for="rss-items-4">
        <?php _e('Events to Display', 'calendar-press'); ?> : 
    </label>
    <select name="<?php echo $this->get_field_name('items'); ?>"
        id="<?php echo $this->get_field_id('items'); ?>">
          <?php
            for ($i = 1; $i <= 20; ++ $i) {
                echo "<option value='$i' " . selected($instance['items'], $i) . ">
                    $i
                    </option>";
            }
            ?>
     </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('type'); ?>">
        <?php _e('Display Type', 'calendar-press'); ?> : 
    </label>
    <select name="<?php echo $this->get_field_name('type'); ?>"
        id="<?php echo $this->get_field_id('type'); ?>">
        <option value="next" <?php selected($instance['type'], 'next'); ?>>
            <?php _e('Upcoming Events', 'calendar-press'); ?>
        </option>
        <option value="newest" <?php selected($instance['type'], 'newest'); ?>>
            <?php _e('Newest Events', 'calendar-press'); ?>
        </option>
        <option value="featured" <?php selected($instance['type'], 'featured'); ?>>
            <?php _e('Featured', 'calendar-press'); ?>
        </option>
        <option value="updated" <?php selected($instance['type'], 'updated'); ?>>
            <?php _e('Recently Updated', 'calendar-press'); ?>
        </option>
        <option value="random" <?php selected($instance['type'], 'random'); ?>>
            <?php _e('Random', 'calendar-press'); ?>
        </option>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('category'); ?>">
        <?php _e('Category:', 'calendar-press'); ?> : 
    </label>
    <?php wp_dropdown_categories(
        array(
        'hierarchical' => true, 
        'taxonomy' => 'event-categories', 
        'show_option_none' => __('All Categories', 'calendar-press'), 
        'hide_empty' => false,
        'name' => $this->get_field_name('category'),
        'id' => $this->get_field_id('category'),
        'orderby' => 'name',
        'selected' => $instance['category']
        )
    ); ?>
</p>
<p>
    <label for="<?php echo $this->get_field_id('target'); ?>">
        <?php _e('Link Target', 'calendar-press'); ?> : 
    </label>
    <select name="<?php echo $this->get_field_name('target'); ?>"
        id="<?php echo $this->get_field_id('target'); ?>">
        <option value=""><?php _e('None', 'calendar-press'); ?></option>
        <option value="_blank" <?php selected($instance['target'], '_blank'); ?>>
            <?php _e('New Window', 'calendar-press'); ?>
        </option>
        <option value="_top" <?php selected($instance['target'], '_top'); ?>>
            <?php _e('Top Window', 'calendar-press'); ?>
        </option>
    </select>
</p>
