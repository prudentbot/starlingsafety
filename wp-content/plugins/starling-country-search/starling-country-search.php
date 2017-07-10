<?php
/*
  Plugin Name: Starling Country Search
  Plugin URI: tylerbuilds.website
  Description: Responsive country search on the front page
  Version: 1.2
  Author: Tyler Smith
  Author URI: tylerbuilds.website
  License: GPL2
*/

function starling_country_search_shortcode($atts, $content = null){
  return '<input id="starling-country-search" name="starling-country-search" type="text" placeholder="Search ..." size="30"  tabindex="5" />'
  . '<div id="starling-country-search-wrap">' . do_shortcode($content) . '</div>';
}
add_shortcode( 'starling_country_search', 'starling_country_search_shortcode' );


add_action( 'wp_enqueue_scripts', 'starling_country_search_enqueue_scripts');
function starling_country_search_enqueue_scripts(){
  if(!is_front_page() || !is_user_logged_in())
    return;

  wp_enqueue_style( 'jquery-ui-style', "https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" );
  wp_enqueue_script('starling-country-search', plugin_dir_url( __FILE__ ) . 'js/search.js', array('jquery', 'jquery-ui-core', 'jquery-ui-autocomplete'), "1.1.3", true);

}

?>
