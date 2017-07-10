<?php
/*
  Plugin Name: Starling Admin Approval
  Plugin URI: tylerbuilds.website
  Description: Custom admin approval
  Version: 1.2
  Author: Tyler Smith
  Author URI: tylerbuilds.website
  License: GPL2
*/

/*
  For the template file used on the profile page, please see the
  "ultimate-member" folder of the child theme.  It had to be done
  this way so Ultimate Member would recognize the template.
*/

add_filter( 'wp_mail_from', 'my_mail_from' );
function my_mail_from( $email ) {
    return "admin@starlingsafety.com";
}

add_filter( 'wp_mail_from_name', 'my_mail_from_name' );
function my_mail_from_name( $name ) {
    return "Starling Admin";
}

// When user is first registered...
add_action('user_register', 'add_approval_status');
function add_approval_status($user_id){

  //set to empty string, which is "false"
  add_user_meta($user_id, "is_approved", "" );
  add_user_meta($user_id, "is_rejected", "" );
  add_user_meta($user_id, "is_suspended", "");
}

add_action('admin_post_suspend_user', 'suspend_user');
function suspend_user(){
  if(!current_user_can( "remove_users" )){
    wp_redirect( get_admin_url( ) );
    exit;
  }

  if(!$_POST['id'])
    return;

  $user = get_user_by("ID", $_POST['id']);
  if(!$user)
    return;

  //"yes" is true
  update_user_meta($_POST['id'], "is_approved", "");
  update_user_meta($_POST['id'], "is_rejected", "");
  update_user_meta($_POST['id'], "is_suspended", "yes");

  $email_body =
    "Hello " . $user->display_name . ", \n\n" .
    "Unfortunately we have had to suspend your account. There a few potential different reasons why this may have occurred but it is most likely due to your activity and/or comments uploaded to the community. \n\n" .
    "If you would like to know more about this, please contact us at admin@starlingsafety.com \n\n" .
    "THE STARLING FLOCK";

  $subject = get_bloginfo("name") . ' - Account Suspended';

  wp_mail($user->user_email, $subject, $email_body);
  echo "The user's account has been suspended, and he or she has been notified.  You may now navigate away from this page.";
}


add_action('admin_post_reactivate_user', 'reactivate_user');
function reactivate_user(){
  if(!current_user_can( "remove_users" )){
    wp_redirect( get_admin_url( ) );
    exit;
  }

  if(!$_POST['id'])
    return;

  $user = get_user_by("ID", $_POST['id']);
  if(!$user)
    return;

  //"yes" is true
  update_user_meta($_POST['id'], "is_approved", "yes");
  update_user_meta($_POST['id'], "is_rejected", "");
  update_user_meta($_POST['id'], "is_suspended", "");

  $email_body =
    "Hello " . $user->display_name . ", \n\n" .
    "Your account suspension has been lifted and you can now rejoin the STARLING flock! \n\n" .
    "To login please visit the following url: http://starlingsafety.com/login \n\n" .
    "Your account e-mail: " . $user->user_email . "\n\n" .
    "If you have any problems, please contact us at admin@starlingsafety.com \n\n" .
    "Thanks, \n" .
    "THE STARLING FLOCK";

  $subject = get_bloginfo("name") . ' - Account Suspension Lifted';

  wp_mail($user->user_email, $subject, $email_body);
  echo "The user's account suspension has been lifted, and he or she has been notified.  You may now navigate away from this page.";

}

add_action('admin_post_approve_user', 'approve_user');
function approve_user(){
  if(!current_user_can( "remove_users" )){
    wp_redirect( get_admin_url( ) );
    exit;
  }

  if(!$_POST['id'])
    return;

  $user = get_user_by("ID", $_POST['id']);
  if(!$user)
    return;

  //"yes" is true
  update_user_meta($_POST['id'], "is_approved", "yes");
  update_user_meta($_POST['id'], "is_rejected", "");
  update_user_meta($_POST['id'], "is_suspended", "");

  $email_body = "

Hi {$user->display_name},

Thank you for signing up with STARLNG! Your account has been approved and is now active. This means that when you click on a country report on the site, you can select the 'What the Starlings Say' tab and start leaving advice for other users. Feel free to start leaving this advice now on places you've recently visited, or the area you are in right now. The more that everyone contributes, the better the site becomes!

To login please visit the following url:

https://starlingsafety.com/login

Your account e-mail: {$user->user_email}
Your account username: {$user->user_login}
Set your account password: https://starlingsafety.com/password-reset/

If you have any problems, please contact us at admin@starlingsafety.com

Thanks you!
THE STARLING FLOCK";

  // $email_body =
  //   "Hello again " . $user->display_name . ", \n\n" .
  //   "This is just to let you know that your application to join the STARLING flock has been approved! You can now leave comments and advice on our country reports.\n\n" .
  //   "To login please visit the following url: http://starlingsafety.com/login \n\n" .
  //   "Your account e-mail: " . $user->user_email . " \n\n" .
  //   "If you have any problems, please contact us at admin@starlingsafety.com \n\n" .
  //   "Thanks,\n" .
  //   "THE STARLING FLOCK";

  $subject = get_bloginfo("name") . ' - Account Approved';

  wp_mail($user->user_email, $subject, $email_body);
  echo "The user has been approved, and he or she has been notified.  You may now navigate away from this page.";
}

add_action('admin_post_reject_user', 'reject_user');
function reject_user(){
  if(!current_user_can( "remove_users" )){
    wp_redirect( get_admin_url( ) );
    exit;
  }

  if(!$_POST['id'])
    return;

  $user = get_user_by("ID", $_POST['id']);
  if(!$user)
    return;

  //"yes" is true
  update_user_meta($_POST['id'], "is_approved", "");
  update_user_meta($_POST['id'], "is_rejected", "yes");
  update_user_meta($_POST['id'], "is_suspended", "");


  $email_body =
  "Hello " . $user->display_name . ", \n\n" .
  "Unfortunately we are unable to approve your application to join the STARLING flock at this stage. There a few potential different reasons for this but the most likely is that you didn't supply us with enough information or advice for us to judge whether or not you would be a good contributor to our community. \n\n" .
  "If you would like to try uploading more advice you can do so here: http://starlingsafety.com/tell-us-about-an-area/ \n\n" .
  "Otherwise, if you have any problems or questions, please contact us at admin@starlingsafety.com \n\n" .
  "Thanks, \n" .
  "THE STARLING FLOCK";

  $subject = get_bloginfo("name") . ' - Account Rejected';

  wp_mail($user->user_email, $subject, $email_body);

  // wp_delete_user($_POST['id']);
  echo "User has been rejected, and he or she has been notified.  You may now navigate away from this page.";
}


?>
