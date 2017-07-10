<?php
/*
  Plugin Name: Starling Tabs
  Plugin URI: tylerbuilds.website
  Description: Adds the tab shortcode for use on country pages.
  Version: 1.2
  Author: Tyler Smith
  Author URI: tylerbuilds.website
  License: GPL2
*/

/*
  This plugin is almost completely modular.  The only thing you should
  have to do is add class tabcontent to the div containing the comments
*/

function starling_tabs_enqueue_scripts(){
  $post = get_post();

  if( !is_a( $post, 'WP_Post' ) || !has_shortcode( $post->post_content, 'starling_tab') )
    return;

  wp_enqueue_script('starling-tabs', plugin_dir_url( __FILE__ ) . 'js/tabs.js', array() , "1.0.1", true);
  wp_enqueue_style( 'starling-tabs-style', plugin_dir_url( __FILE__ ) .'css/tabs.css', array(), '1.0.1' );

}
add_action( 'wp_enqueue_scripts', 'starling_tabs_enqueue_scripts');

function starling_tab_shortcode($atts, $content = null){
	return '<div class="tab">' .
    '<button id="defaultOpen" class="tablinks" onclick="openContent(event, \'starling-info\')">Country Information</button>' .
    '<button class="tablinks" onclick="openContent(event, \'tab-comments\')">What the Starlings say</button>' .
    '</div>' .
    '<div id="starling-info" class="tabcontent">' . do_shortcode($content) . '</div>';
}
add_shortcode( 'starling_tab', 'starling_tab_shortcode' );

?>
