<?php
/*
  Plugin Name: Starling Tabs 2
  Plugin URI: tylerbuilds.website
  Description: Adds enhanced tab shortcode for use on country pages.
  Version: 1.0
  Author: Tyler Smith
  Author URI: tylerbuilds.website
  License: GPL2
*/

/*
  This plugin is almost completely modular.  The only thing you should
  have to do is add class tabcontent to the div containing the comments
*/

function starling_tabs_2_enqueue_scripts(){
  $post = get_post();

  if( !is_a( $post, 'WP_Post' ) || !has_shortcode( $post->post_content, 'starling_tab_2') )
    return;

  wp_enqueue_script('starling-tabs-2', plugin_dir_url( __FILE__ ) . 'js/tabs.js', array() , "1.0.1", true);
  wp_enqueue_style( 'starling-tabs-2-style', plugin_dir_url( __FILE__ ) .'css/tabs.css', array(), '1.0.1' );

}
add_action( 'wp_enqueue_scripts', 'starling_tabs_2_enqueue_scripts');

function starling_tab_2_shortcode($atts, $content = null){
  if(!$atts['tab'])
    return;

  if($atts['tab'] == 'header')
    return
      '<div class="tab row">
        <button id="defaultOpen" class="tablinks" onclick="openContent(event, \'tab-basic\')">Basic Info</button>
        <button class="tablinks" onclick="openContent(event, \'tab-security\')">Security</button>
        <button class="tablinks" onclick="openContent(event, \'tab-tni\')">Transport and Infrastructure</button>
        <button class="tablinks" onclick="openContent(event, \'tab-health\')">Health Risks</button>
        <button class="tablinks" onclick="openContent(event, \'tab-geography\')">Geography</button>
        <button class="tablinks" onclick="openContent(event, \'tab-politics\')">Politics, Economics and Society</button>
        <button class="tablinks" onclick="openContent(event, \'tab-latest\')">Latest Reports</button>
        <button class="tablinks" onclick="openContent(event, \'tab-comments\')">What the Starlings Say</button>
      </div>';

  return
    '<div id="tab-' . $atts['tab'] . '" class="tabcontent">' . do_shortcode($content) . '</div>';
}
add_shortcode( 'starling_tab_2', 'starling_tab_2_shortcode' );

?>
