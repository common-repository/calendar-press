<?php
/**
 * Multi-site Support Code
 * php version 8.1.10
 *
 * @category   WordPress_Template
 * @package    Calendar_Press
 * @subpackage MultiSite
 * @author     Shane Lambert <grandslambert@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://grandslambert.com/plugins/calendar-press
 */
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}

/**
 * Returns an array of blogs for a user (all blogs if user is super-admin
 *
 * @param array $args Multisite Arguments
 * 
 * @return array
 */
function CP_Get_multisites($args = array())
{
     global $calendarPressOBJ;

    if (!$calendarPressOBJ->isNetwork) {
         return array();
    }
     
     $defaults = array(
          'user' => 1,
          'get_all' => true
     );

     $args = wp_parse_args($args, $defaults);

     return get_blogs_of_user($args['user'], $args['get_all']);
}

/**
 * Function to list all blogs for a user.
 *
 * @param array $args Arguments for site list.
 * 
 * @return string
 */
function CP_List_multisites($args = array())
{
     $defaults = array(
          'list-class' => 'multisite-list',
          'item-class' => 'multisite-list-item',
          'item-tag' => 'li',
          'echo' => true,
     );

     $args = wp_parse_args($args, $defaults);

     switch ($args['item-tag']) {
     default:
          $output = '<ul class="' . $args['list-class'] . '">';
     }

     foreach ( CP_Get_multisites($args) AS $blog ) {
          $site_id = 'multisite_' . $blog->userblog_id;

          switch ($args['item-tag']) {
         default:
              $output.= '<li><a class="' . $args['item-class'] . '" id="' . $site_id . '" href="' . $blog->siteurl . '">' . $blog->blogname . '</a></li>';
          }
     }

     switch ($args['item-tag']) {
     default:
          $output .= '</ul>';
     }

     if ($args['echo'] ) {
          echo $output;
     } else {
          return $output;
     }
}

/**
 * Get a dropdown for multi site selection
 * 
 * @param array $args Arguments for dropdown
 * 
 * @return string
 */
function CP_Dropdown_multisites($args = array())
{
     $defaults = array(
          'name' => 'site-id',
          'id' => 'site_id',
          'class' => 'multisite-dropdown',
          'selected' => false,
          'onchange' => false,
          'echo' => true,
          'value_field' => 'userblog_id',
          'show_field' => 'blogname',
     );

     $args = wp_parse_args($args, $defaults);

     $output = '<select name="' . $args['name'] . '" id = "' . $args['id'] . '" class="' . $args['class'] . '" ';

     if ($args['onchange'] ) {
          $output.= 'onchange="' . $args['onchange'] . '"';
     }
     $output.= '>';

     foreach ( CP_Get_multisites() as $blog ) {
          $output.= '<option value="' . $blog->$args['value_field'] . '" ' . selected($args['selected'], $blog->$args['value_field'], false) . '>' . $blog->$args['show_field'] . '</option>';
     }

     $output.= '</select>';

     if ($args['echo'] ) {
          echo $output;
     } else {
          return $output;
     }
}
