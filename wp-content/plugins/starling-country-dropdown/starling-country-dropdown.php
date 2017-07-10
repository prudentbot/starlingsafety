<?php
/*
  Plugin Name: Starling Country Dropdown
  Plugin URI: tylerbuilds.website
  Description: Country Sidebar Dropdown
  Version: 1.0
  Author: Tyler Smith
  Author URI: tylerbuilds.website
  License: GPL2
*/

add_action( 'wp_enqueue_scripts', 'starling_country_dropdown_enqueue_scripts');
function starling_country_dropdown_enqueue_scripts(){
  if(!is_user_logged_in())
    return;

  wp_enqueue_script('starling-country-dropdown', plugin_dir_url( __FILE__ ) . 'js/dropdown.js', array('jquery'), "0.0.1", true);

}

function starling_dropdown_array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function starling_country_dropdown_shortcode($atts, $content = null){
  if(!is_user_logged_in())
    return;

  $pages = get_pages();
  $data = array();
  foreach($pages as $p){
    $safety_score = get_post_meta($p->ID, 'danger-level', true);

    if(!$safety_score)
      continue;

    $innerArray = array('title' => get_the_title($p), 'permalink' => get_permalink($p->ID));

    array_push($data, $innerArray);
  }
  $data = starling_dropdown_array_sort($data, 'title', SORT_ASC);

  if (!$data)
    return;

  $html = '<select id="starling-sidebar-country-dropdown"><option disabled selected value>Select a Country</option>';
  for($i = 0; $i < sizeof($data); $i++){
    $html .= '<option value="' . $data[$i]['permalink'] . '">' . $data[$i]['title'] . '</option>';
  }
  $html .= '</select>';

  return $html;
}
add_shortcode( 'starling_country_dropdown', 'starling_country_dropdown_shortcode' );

?>
