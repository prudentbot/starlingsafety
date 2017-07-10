<?php
/*
  Plugin Name: Starling Map
  Plugin URI: tylerbuilds.website
  Description: Adds the interactive map to the front page
  Version: 1.2
  Author: Tyler Smith
  Author URI: tylerbuilds.website
  License: GPL2
*/

function starling_map_enqueue_scripts(){
  if(!is_front_page())
    return;

  wp_enqueue_script('d3', plugin_dir_url( __FILE__ ) . 'js/d3.min.js');
  wp_enqueue_script('topojson', plugin_dir_url( __FILE__ ) . 'js/topojson.min.js', array('d3'));
  wp_enqueue_script('datamaps', plugin_dir_url( __FILE__ ) . 'js/datamaps.world.hires.min.js', array('d3', 'topojson'));

  wp_enqueue_script('fa', 'https://use.fontawesome.com/9663c24e77.js');

  wp_enqueue_script('starling-map', plugin_dir_url( __FILE__ ) . 'js/map.js', array('d3', 'topojson', 'datamaps', 'jquery'), "5.1.10", true);

  wp_enqueue_style('starling-map-style', plugin_dir_url( __FILE__ ) . 'css/starling-datamap.css', array(), "0.0.1");

  // This is pretty inefficient, but I thought it was the best solution from
  // an admin UI perspective, and that caching would fix it anyway?
  /* Gets meta for all pages, forwards the ones that matter to map.js */
  $pages = get_pages();
  $data = array();
  foreach($pages as $p){
    $safety_score = get_post_meta($p->ID, 'danger-level', true);
    $country_code = get_post_meta($p->ID, 'country-code', true);

    if ($country_code && $safety_score){

      $terrorism = get_post_meta($p->ID, 'terrorism', true);
      $crime = get_post_meta($p->ID, 'crime', true);
      $road_safety = get_post_meta($p->ID, 'road_safety', true);
      $health_risks = get_post_meta($p->ID, 'health_risks', true);
      $disaster_risks = get_post_meta($p->ID, 'disaster_risks', true);

      $innerArray = array("country_code" => $country_code, "safety_score" => $safety_score,
        "terrorism" => $terrorism, "crime" => $crime, "road_safety" => $road_safety,
        "health_risks" => $health_risks, "disaster_risks" => $disaster_risks );

      if(is_user_logged_in()){
        $permalink = get_permalink($p->ID);
        $innerArray['permalink'] = $permalink;
      }

      array_push($data, $innerArray);

    }
  }

	wp_localize_script('starling-map', 'starling_map_script_vars', array(
			'data' => $data
		)
	);

}
add_action( 'wp_enqueue_scripts', 'starling_map_enqueue_scripts');


function starling_map_shortcode(){

  $map_html = '';
  if(!is_user_logged_in())
    $map_html .= '<a style="display:block; color:black;" href="https://starlingsafety.com/login">';

  $map_html .= '<div class="show-for-medium-up">
  <h2 class="map-title">Travel Safety Map</h2>
  <div id="basic_choropleth" style="position: relative; width:100%"></div>
  <div>
    <div class="my-legend">
      <div class="legend-title">Safety Scores</div>
      <div class="legend-descriptions">
        <div class="legend-description-text legend-left-description"> very dangerous </div>
        <div class="legend-description-text legend-right-description"> very safe </div>
      </div>
      <div class="legend-scale">
        <ul class="legend-labels">
          <li><span style="background:#e83a29;"></span>1</li>
          <li><span style="background:#e85431;"></span>2</li>
          <li><span style="background:#ff743b;"></span>3</li>
          <li><span style="background:#ff883d;"></span>4</li>
          <li><span style="background:#ff9e4b;"></span>5</li>
          <li><span style="background:#ffd851;"></span>6</li>
          <li><span style="background:#e8d54a;"></span>7</li>
          <li><span style="background:#e8e64a;"></span>8</li>
          <li><span style="background:#b7e84e;"></span>9</li>
          <li><span style="background:#83e856;"></span>10</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="small-12 medium-6 columns">
      <button class="zoom-button" data-zoom="reset"><i class="fa fa-refresh" aria-hidden="true" style="pointer-events: none"></i></button>
      <button class="zoom-button" data-zoom="out"><i class="fa fa-minus" aria-hidden="true" style="pointer-events: none"></i></button>
      <button class="zoom-button" data-zoom="in"><i class="fa fa-plus" aria-hidden="true" style="pointer-events: none"></i></button>
    </div>';

  if(!is_user_logged_in())
    $map_html .= '</a>';


  if(is_user_logged_in()){
    $map_html .=
    '<div class="small-12 medium-6 columns">' .
      '<label for="starling-map-data-type">Map Data:</label>' .
      '<select id="starling-map-data-type" name="starling-map-data-type" style="float:right;">' .
        '<option selected value="safety_score">Overall Safety Score</option>' .
        '<option value="terrorism">Terrorism</option>' .
        '<option value="crime">Crime</option>' .
        '<option value="road_safety">Road Safety</option>' .
        '<option value="health_risks">Health Risks</option>' .
        '<option value="disaster_risks">Disaster Risks</option>' .
      '</select>' .
    '</div>';
  }
  return $map_html . "</div></div>";
}
add_shortcode( 'starling_map', 'starling_map_shortcode' );


?>
