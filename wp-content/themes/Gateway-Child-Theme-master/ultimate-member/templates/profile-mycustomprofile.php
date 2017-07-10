<?php
/* Template: Starling Profile */

//if not admin, they're not allowed here.
if(!current_user_can( "remove_users" )){
  wp_redirect( get_admin_url( ) );
  exit;
}

//force page reload every time (hack to make back button work correctly from status change page)
header("Cache-Control: no-store, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

?>
<?php if(get_user_meta(um_profile_id(), "is_approved", true)): ?>
  <h5><em> This user's profile has been approved. </em></h5>
<?php elseif(get_user_meta(um_profile_id(), "is_rejected", true)): ?>
  <h5><em> This user's profile has been rejected. </em></h5>
<?php elseif(get_user_meta(um_profile_id(), "is_suspended", true)): ?>
  <h5><em> This user's profile has been suspended. </em></h5>
<?php else: ?>
  <h5><em> This user's profile is awaiting approval. </em></h5>
<?php endif; ?>

<h6> Original Form Information </h6>
<p> Email: <?php echo um_user('user_email');?> </p>
<p> Username: <?php echo um_user('user_login');?> </p>
<p> First Name: <?php echo um_user('first_name');?> </p>
<p> Last Name: <?php echo um_user('last_name');?> </p>
<p> Telephone/Alternative Contact Details: <?php echo um_user('telephone');?> </p>
<p> Country: <?php echo um_user('country');?> </p>
<p> Area: <?php echo um_user('area');?> </p>
<p> Road Quality: <?php echo um_user('road-quality');?> </p>
<p> Driving Habits: <?php echo um_user('driving-habits');?> </p>
<p> How safe do/did they feel: <?php echo um_user('safety');?> </p>
<p> What else could they tell us about the area: <?php echo um_user('anything-else');?> </p>

<p> Allows contact:
  <?php if(um_user('can-contact')): ?>
    Yes
  <?php else: ?>
    No
  <?php endif; ?>
</p>

<?php
// var_dump(get_user_by("ID", um_profile_id()));

$comments = get_comments();
$comment_count = 0;
$user_verified = 0;
$user_was_verified = 0;
$user_was_flagged = 0;
for ($i = 0; $i < sizeof($comments); ++$i){
  if($comments[$i]->comment_author == um_user('user_login')){
    $comment_count++;

    $like_count = get_comment_meta( $comments[$i]->comment_ID, 'cld_like_count', true );
    if ( empty( $like_count ) ) {
      $like_count = 0;
    }
    $user_was_verified += $like_count;

    // doesn't account for comments that are deleted, but possibly still useful.
    // $flag_count = get_comment_meta( $comments[$i]->comment_ID, 'cld_dislike_count', true );
    // if ( empty( $flag_count ) ) {
    //   $flag_count = 0;
    // }
    // $user_was_flagged += $flag_count;

  }

  $liked_ids = get_comment_meta( $comments[$i]->comment_ID, 'cld_liked_ids', true);
  $user_liked_comment = ($liked_ids && in_array( um_profile_id(), $liked_ids )) ? 1 : 0;
  if($user_liked_comment)
    $user_verified++;
}
?>
<h6> User Activity </h6>

<p> Verifications Given Out:
  <?php echo $user_verified;?>
</p>

<p> Verifications Received:
  <?php echo $user_was_verified; ?>
</p>

<p> Flags Received:
  <?php
  $flag_count = get_user_meta(um_profile_id(), "flags_received", true);
  if ( empty( $flag_count ) ) {
    $flag_count = 0;
  }

  echo $flag_count; ?>
</p>

<p> Total Comments:
  <?php echo $comment_count; ?>
</p>

<?php if(get_user_meta(um_profile_id(), "is_approved", true)): ?>
  <h5><em> This user's profile has been approved. </em></h5>
  <div id="content">
    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
      <input type="hidden" name="id" value="<?php echo um_profile_id(); ?>">
      <input type="hidden" name="action" value="suspend_user">
      <input type="submit" value="Suspend">
    </form>
  </div>
<?php elseif(get_user_meta(um_profile_id(), "is_rejected", true)): ?>
  <h5><em> This user's profile has been rejected. </em></h5>
  <div id="content">
    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
      <input type="hidden" name="id" value="<?php echo um_profile_id(); ?>">
      <input type="hidden" name="action" value="approve_user">
      <input type="submit" value="Approve">
    </form>
  </div>
<?php elseif(get_user_meta(um_profile_id(), "is_suspended", true)): ?>
  <h5><em> This user's profile has been suspended. </em></h5>
  <div id="content">
    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
      <input type="hidden" name="id" value="<?php echo um_profile_id(); ?>">
      <input type="hidden" name="action" value="reactivate_user">
      <input type="submit" value="Unsuspend">
    </form>
  </div>
<?php else: ?>
  <h5><em> This user's profile is awaiting approval. </em></h5>
  <div id="content">
    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" style="float: left; margin-right: 10px;">
      <input type="hidden" name="id" value="<?php echo um_profile_id(); ?>">
      <input type="hidden" name="action" value="approve_user">
      <input type="submit" value="Approve">
    </form>
    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
      <input type="hidden" name="id" value="<?php echo um_profile_id(); ?>">
      <input type="hidden" name="action" value="reject_user">
      <input type="submit" value="Reject">
    </form>
  </div>
<?php endif; ?>
