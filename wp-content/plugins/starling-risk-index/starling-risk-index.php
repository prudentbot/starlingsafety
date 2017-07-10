<?php
/*
  Plugin Name: Starling Risk Index
  Plugin URI: tylerbuilds.website
  Description: Risk Index sorting options
  Version: 1.0
  Author: Tyler Smith
  Author URI: tylerbuilds.website
  License: GPL2
*/

function starling_risk_index_enqueue_scripts(){
  $post = get_post();

  //if this isn't the risk index page
  if(!$post || $post->ID != 5037)
    return;

  wp_enqueue_script('starling-risk', plugin_dir_url( __FILE__ ) . 'js/risk.js', array() , "1.0.2", true);

}
add_action( 'wp_enqueue_scripts', 'starling_risk_index_enqueue_scripts');

function starling_risk_index_shortcode($atts, $content = null){
  return
  '<div id="starling-risk-index"><div class="row"><div class="small-12 medium-6 columns"' .
    '<label> Sort by </label>' .
    '<select id="starling-risk-index-sort" name="starling-risk-index-sort">' .
      '<option selected value="Rank">Rank</option>' .
      '<option value="Alphabetical">Alphabetical</option>' .
    '</select></div></div>' . $content .
  '</div>';
}
add_shortcode( 'starling_risk_index', 'starling_risk_index_shortcode' );

?>
