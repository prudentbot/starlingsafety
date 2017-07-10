<?php
/*
  Plugin Name: Starling Users Table
  Plugin URI: tylerbuilds.website
  Description: Changes the columns on the Users page.
  Version: 1.0
  Author: Tyler Smith
  Author URI: tylerbuilds.website
  License: GPL2
*/

function starling_user_admin_enqueue($hook) {
    if ( 'users.php' != $hook ) {
        return;
    }
    wp_enqueue_script( 'starling_user_admin', plugin_dir_url( __FILE__ ) . 'js/admin.js' );
}
add_action( 'admin_enqueue_scripts', 'starling_user_admin_enqueue' );


function starling_count_user_comments( $user_id) {
  global $wpdb, $post, $current_user;

  $where = 'WHERE comment_approved = 1 AND user_id = ' . $user_id ;
  $comment_count = $wpdb->get_var("SELECT COUNT( * ) AS total
                                   FROM {$wpdb->comments}
                                   {$where}");
  return $comment_count;
}

function starling_get_approval_status ($user_id){
  if(get_user_meta($user_id, 'is_approved', true))
    return "Approved";
  if(get_user_meta($user_id, 'is_suspended', true))
    return "Suspended";
  if(get_user_meta($user_id, 'is_rejected', true))
    return "Rejected";

  return "Pending";
}

function starling_get_approval_controls ($user_id) {

  $approve = '<button type="button" onclick="post(\'' . esc_url(admin_url("admin-post.php")) .
    '\', {id:\'' . $user_id . '\', action:\'approve_user\'})"> Approve </button>';

  $reject = '<button type="button" onclick="post(\'' . esc_url(admin_url("admin-post.php")) .
    '\', {id:\'' . $user_id . '\', action:\'reject_user\'})"> Reject </button>';

  $suspend = '<button type="button" onclick="post(\'' . esc_url(admin_url("admin-post.php")) .
    '\', {id:\'' . $user_id . '\', action:\'suspend_user\'})"> Suspend </button>';

  $unsuspend = '<button type="button" onclick="post(\'' . esc_url(admin_url("admin-post.php")) .
    '\', {id:\'' . $user_id . '\', action:\'reactivate_user\'})"> Unsuspend </button>';

  if(get_user_meta($user_id, 'is_approved', true))
    return $suspend;
  if(get_user_meta($user_id, 'is_rejected', true))
    return $approve;
  if(get_user_meta($user_id, 'is_suspended', true))
    return $unsuspend;

  return $approve . $reject;
}

function starling_get_count($user_id, $meta_tag){
  $count = get_user_meta($user_id, $meta_tag, true);
  if(empty($count))
    $count = 0;
  return $count;
}

add_action('manage_users_custom_column', 'starling_user_column_content', 10, 3);
function starling_user_column_content($value, $column_name, $user_id) {
  // $user = get_userdata( $user_id );
  if ( $column_name == 'num_comments' ) {
    return starling_count_user_comments ($user_id);
  }
  if ($column_name == 'approval_status'){
    return starling_get_approval_status($user_id);
  }
  if ($column_name == 'approval_controls'){
    return starling_get_approval_controls($user_id);
  }
  if ($column_name == 'verifications_given'){
    return starling_get_count($user_id, 'verifications_given');
  }
  if ($column_name == 'flags_given'){
    return starling_get_count($user_id, 'flags_given');
  }
  if ($column_name == 'verifications_received'){
    return starling_get_count($user_id, 'verifications_received');
  }
  if ($column_name == 'flags_received'){
    return starling_get_count($user_id, 'flags_received');
  }

  return $value;
}
add_filter( 'manage_users_sortable_columns', 'starling_user_sortable_columns' );

function starling_user_sortable_columns( $sortable_columns ) {
  //  $sortable_columns['num_comments']   = 'num_comments';
   $sortable_columns['verifications_given']   = 'verifications_given';
   $sortable_columns['flags_given']   = 'flags_given';
   $sortable_columns['verifications_received']   = 'verifications_received';
   $sortable_columns['flags_received']   = 'flags_received';
   return $sortable_columns;
}

add_action('pre_user_query', 'user_column_orderby');
function user_column_orderby($user_search) {
  global $wpdb, $current_screen;

	if (! isset($current_screen->id) || 'users' != $current_screen->id) {
	    return;
	}

  $vars = $user_search->query_vars;

  if('verifications_given' == $vars['orderby']) {
      $user_search->query_from .= " INNER JOIN {$wpdb->usermeta} m1 ON {$wpdb->users}.ID=m1.user_id AND (m1.meta_key='verifications_given')";
      $user_search->query_orderby = ' ORDER BY UPPER(m1.meta_value) '. $vars['order'];
  } elseif ('flags_given' == $vars['orderby']) {
      $user_search->query_from .= " INNER JOIN {$wpdb->usermeta} m1 ON {$wpdb->users}.ID=m1.user_id AND (m1.meta_key='flags_given')";
      $user_search->query_orderby = ' ORDER BY UPPER(m1.meta_value) '. $vars['order'];
 	} elseif ('verifications_received' == $vars['orderby']) {
      $user_search->query_from .= " INNER JOIN {$wpdb->usermeta} m1 ON {$wpdb->users}.ID=m1.user_id AND (m1.meta_key='verifications_received')";
      $user_search->query_orderby = ' ORDER BY UPPER(m1.meta_value) '. $vars['order'];
  } elseif ('flags_received' == $vars['orderby']) {
      $user_search->query_from .= " INNER JOIN {$wpdb->usermeta} m1 ON {$wpdb->users}.ID=m1.user_id AND (m1.meta_key='flags_received')";
      $user_search->query_orderby = ' ORDER BY UPPER(m1.meta_value) '. $vars['order'];
  }
}

// function starling_comments_column_orderby( $vars ) {
//   error_log('orderby check');
//
//     if ( isset( $vars['orderby'] ) && 'num_comments' == $vars['orderby'] ) {
//       error_log('orderby num_comments');
//         $vars = array_merge( $vars, array(
//             'meta_key' => 'num_comments',
//             'orderby' => 'meta_value_num',
//             'order' => 'ASC'
//         ) );
//     }
//
//     return $vars;
// }
// add_filter( 'request', 'starling_comments_column_orderby' );


add_action('manage_users_columns','starling_modify_user_columns');
function starling_modify_user_columns($column_headers) {
  error_log("sanity check");
  unset($column_headers['posts']);
  unset($column_headers['role']);
  unset($column_headers['name']);
  $column_headers['num_comments'] = 'Comments';
  $column_headers['approval_status'] = 'Status';
  $column_headers['approval_controls'] = 'Controls';
  $column_headers['verifications_given'] = 'Verifications Given';
  $column_headers['flags_given'] = 'Flags Given';
  $column_headers['verifications_received'] = 'Verifications Receieved';
  $column_headers['flags_received'] = 'Flags Received';

  return $column_headers;
}

add_action('admin_head', 'starling_custom_admin_css');
function starling_custom_admin_css() {
  echo '<style>
  .column-username {width: 16%}
  </style>';
}


?>
