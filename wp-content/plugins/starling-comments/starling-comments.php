<?php
/*
  Plugin Name: Starling Comments
  Plugin URI: tylerbuilds.website
  Description: Extra comment fields and comment filtering
  Version: 1.2
  Author: Tyler Smith
  Author URI: tylerbuilds.website
  License: GPL2
*/

add_action( 'wp_enqueue_scripts', 'starling_comments_enqueue_scripts');
function starling_comments_enqueue_scripts(){
  $post = get_post();

  //if this isn't a country page
  if(!$post || !get_post_meta($post->ID, 'danger-level', true))
    return;

  wp_enqueue_style( 'jquery-ui-style', "https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" );
  wp_enqueue_script('starling-filter', plugin_dir_url( __FILE__ ) . 'js/filter.js', array('jquery', 'jquery-ui-core', 'jquery-ui-autocomplete'), "1.1.4", true);
  wp_enqueue_script('starling-comment-form', plugin_dir_url( __FILE__ ) . 'js/form.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'), "1.0.1", true);
  wp_enqueue_script('parsley', plugin_dir_url( __FILE__ ) . 'js/parsley.min.js', "1.0.1", true);

}

// function starling_comments_default($fields){
// }
// add_filter('comment_form_default_fields', 'starling_comments_default');

function additional_fields(){

  echo '<p class="comment-form-area">'.
  '<label for="area">' . __( 'Area' ) . '* </label>'.
  '<input id="starling-comment-area" name="area" type="text" size="30"  tabindex="5" required=""/></p>';

  echo '<p class="comment-form-type">' .
  '<label for="starling-comment-type">' . __( 'Comment Type' ) . '* </label>' .
  '<select id="starling-comment-type" name="starling-comment-type" required="">' .
    '<option disabled selected value></option>' .
    '<option value="Advice">Advice</option>' .
    '<option value="Alert">Alert</option>' .
    '<option value="Question">Question</option>' .
    '<option value="Event">Event Bulletin</option>' .
    '<option value="News">News Article</option>' .
  '</select></p>';

  echo
  '<div class="row">' .
    '<div class="comment-form-start-date small-12 medium-6 columns">' .
      '<label for="starling-comment-start-date">Start Date</label>'.
      '<input id="starling-comment-start-date" name="starling-comment-start-date" type="text" size="30"  tabindex="5"/>' .
    '</div>' .
    '<div class="comment-form-end-date small-12 medium-6 columns">' .
      '<label for="starling-comment-end-date">End Date</label>'.
      '<input id="starling-comment-end-date" name="starling-comment-end-date" type="text" size="30"  tabindex="5"/>' .
    '</div>' .
  '</div>';


  echo
  '<div>' .
    '<input type="checkbox" name="anonymous" value="Post Anonymously">' .
    ' Post Anonymously<br>' .
  '</div>';

}
add_action( 'comment_form_logged_in_after', 'additional_fields' );

function save_comment_meta_data( $comment_id ) {
  $user = wp_get_current_user();
  if(!$user || !get_user_meta($user->ID, "is_approved", true))
    return;

  $comment = get_comment($comment_id);

  // if ( !( isset( $_POST['area'] ) ) || !( $_POST['area'] != '') ||
  // !( isset( $_POST['starling-comment-type'] )) || !($_POST['starling-comment-type'] != '') )
  //   return;

  $area = wp_filter_nohtml_kses($_POST['area']);
  add_comment_meta( $comment_id, 'area', $area );

  $starling_comment_type = wp_filter_nohtml_kses($_POST['starling-comment-type']);
  add_comment_meta( $comment_id, 'starling-comment-type', $starling_comment_type );

  $starling_comment_type = wp_filter_nohtml_kses($_POST['starling-comment-type']);
  add_comment_meta( $comment_id, 'starling-comment-type', $starling_comment_type );

  $starling_comment_start_date = wp_filter_nohtml_kses($_POST['starling-comment-start-date']);
  add_comment_meta( $comment_id, 'starling-comment-start-date', $starling_comment_start_date );

  $starling_comment_end_date = wp_filter_nohtml_kses($_POST['starling-comment-end-date']);
  add_comment_meta( $comment_id, 'starling-comment-end-date', $starling_comment_end_date );

  if ( ( isset( $_POST['anonymous'] ) )){
    $anonymous = wp_filter_nohtml_kses($_POST['anonymous']);
    add_comment_meta( $comment_id, 'anonymous', $anonymous );
  }
}
add_action( 'comment_post', 'save_comment_meta_data' );

// Validation
function verify_comment_meta_data( $commentdata ) {
  // if ( ! isset( $_POST['area'] ) )
  // wp_die( __( 'Error: You did not specify an area. Hit the Back button on your Web browser and resubmit your comment with an area.' ) );
  //
  // if ( ! isset( $_POST['starling-comment-type'] ) )
  // wp_die( __( 'Error: You did not specify a comment type. Hit the Back button on your Web browser and resubmit your comment with a comment type.' ) );
  //
  return $commentdata;
}
add_filter( 'preprocess_comment', 'verify_comment_meta_data' );

// function add_comment_footer($text){
//   // $area = get_comment_meta(get_comment_ID(), 'area', true);
//   // $type = get_comment_meta(get_comment_ID(), 'starling-comment-type', true);
//   // if($area && $type) {
//   //   $text = $text .
//   //   '<p class="comment-meta-header">' .
//   //     '<cite class="comment-type-starling">' . $type . '</cite>' .
//   //     '<span class="comment-area"> for ' . $area . ' area</span>' .
//   //   '</p>';
//   //
//   //   $start = get_comment_meta(get_comment_ID(), 'starling-comment-start-date', true);
//   //   $end = get_comment_meta(get_comment_ID(), 'starling-comment-end-date', true);
//   //   if($start && $end){
//   //     $text .=
//   //     '<p class="comment-meta-header">' .
//   //       '<cite class="comment-type-starling"> ' . $start . ' - ' . $end . '</cite>' .
//   //     '</p>';
//   //   }
//   // }
//   // return $text;
// }
// add_filter('comment_text', 'add_comment_footer');

?>
