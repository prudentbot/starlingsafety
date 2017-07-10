<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class es_cls_default {
	public static function es_pluginconfig_default() {

		global $wpdb;

		$admin_email = get_option('admin_email');
		$blogname = get_option('blogname');

		if($admin_email == "") {
			$admin_email = "admin@gmail.com";
		}

		$home_url = home_url('/');
		$optinlink = $home_url . "?es=optin&db=###DBID###&email=###EMAIL###&guid=###GUID###";
		$unsublink = $home_url . "?es=unsubscribe&db=###DBID###&email=###EMAIL###&guid=###GUID###";

		$default = array();
		$default['ig_es_fromname'] = "Admin";
		$default['ig_es_fromemail'] = $admin_email;
		$default['ig_es_emailtype'] = "WP HTML MAIL";
		$default['ig_es_notifyadmin'] = "YES";
		$default['ig_es_adminemail'] = $admin_email;
		$default['ig_es_admin_new_sub_subject'] = $blogname . " New email subscription";
		$default['ig_es_admin_new_sub_content'] = "Hi Admin, \r\n\r\nWe have received a request to subscribe new email address to receive emails from our website. \r\n\r\nName : ###NAME###\r\nEmail: ###EMAIL###\r\nGroup: ###GROUP### \r\n\r\nThank You\r\n".$blogname;
		$default['ig_es_welcomeemail'] = "YES";
		$default['ig_es_welcomesubject'] = $blogname . " Welcome to our newsletter";
		$default['ig_es_welcomecontent'] = "Hi ###NAME###, \r\n\r\nWe have received a request to subscribe this email address to receive newsletter from our website in group ###GROUP###. \r\n\r\nThank You\r\n".$blogname." \r\n\r\n No longer interested in emails from ".$blogname."?. Please <a href='###LINK###'>click here</a> to unsubscribe";
		$default['ig_es_optintype'] = "Double Opt In";
		$default['ig_es_confirmsubject'] = $blogname . " confirm subscription";
		$default['ig_es_confirmcontent'] = "Hi ###NAME###, \r\n\r\nA subscription request for this email address was received. Please confirm it by <a href='###LINK###'>clicking here</a>.\r\n\r\nIf you still cannot subscribe, please click this link : \r\n ###LINK### \r\n\r\nThank You\r\n".$blogname;
		$default['ig_es_optinlink'] = $optinlink;
		$default['ig_es_unsublink'] = $unsublink;
		$default['ig_es_unsubcontent'] = "No longer interested in emails from ".$blogname."?. Please <a href='###LINK###'>click here</a> to unsubscribe";
		$default['ig_es_unsubtext'] = "Thank You, You have been successfully unsubscribed. You will no longer hear from us.";
		$default['ig_es_successmsg'] = "Thank You, You have been successfully subscribed.";
		$default['ig_es_suberror'] = "Oops.. This subscription cant be completed, sorry. The email address is blocked or already subscribed. Thank you.";
		$default['ig_es_unsuberror'] = "Oops.. We are getting some technical error. Please try again or contact admin.";

		foreach ( $default as $option_name => $option_value ) {
			update_option( $option_name, $option_value );
		}

		return true;
	}

	public static function es_subscriber_default() {

		$result = es_cls_dbquery::es_view_subscriber_count(0);
		if ($result == 0) {
			$form["es_email_mail"] = get_option('admin_email');
			$form["es_email_name"] = "Admin";
			$form["es_email_group"] = "Public";
			$form["es_email_status"] = "Confirmed";
			es_cls_dbquery::es_view_subscriber_ins($form, "insert");

			$form["es_email_mail"] = "a.example@example.com";
			$form["es_email_name"] = "Example";
			$form["es_email_group"] = "Public";
			$form["es_email_status"] = "Confirmed";
			es_cls_dbquery::es_view_subscriber_ins($form, "insert");
		}
		return true;

	}

	public static function es_template_default() {

		$result = es_cls_compose::es_template_count(0);
		if ($result == 0) {
			$form['es_templ_heading'] = 'New post published ###POSTTITLE###';
			$es_b = "Hello ###NAME###,\r\n\r\n";
			$es_b = $es_b . "We have published a new blog on our website. ###POSTTITLE###\r\n";
			$es_b = $es_b . "###POSTDESC###\r\n";
			$es_b = $es_b . "You may view the latest post at ";
			$es_b = $es_b . "###POSTLINK###\r\n";
			$es_b = $es_b . "You received this e-mail because you asked to be notified when new updates are posted.\r\n\r\n";
			$es_b = $es_b . "Thanks & Regards\r\n";
			$es_b = $es_b . "Admin";
			$form['es_templ_body'] = $es_b;
			$form['es_templ_status'] = 'Published';
			$form['es_email_type'] = 'Post Notification';
			$action = es_cls_compose::es_template_ins($form, $action = "insert");

			$form['es_templ_heading'] = 'Post notification ###POSTTITLE###';
			$es_b = "Hello ###EMAIL###,\r\n\r\n";
			$es_b = $es_b . "We have published a new blog on our website. ###POSTTITLE###\r\n";
			$es_b = $es_b . "###POSTIMAGE###\r\n";
			$es_b = $es_b . "###POSTFULL###\r\n";
			$es_b = $es_b . "You may view the latest post at ";
			$es_b = $es_b . "###POSTLINK###\r\n";
			$es_b = $es_b . "You received this e-mail because you asked to be notified when new updates are posted.\r\n\r\n";
			$es_b = $es_b . "Thanks & Regards\r\n";
			$es_b = $es_b . "Admin";
			$form['es_templ_body'] = $es_b;
			$form['es_templ_status'] = 'Published';
			$form['es_email_type'] = 'Post Notification';
			$action = es_cls_compose::es_template_ins($form, $action = "insert");

			$Sample = '<strong style="color: #990000"> Email Subscribers</strong><p>Email Subscribers plugin has options to send newsletters to subscribers. It has a separate page with HTML editor to create a HTML newsletter.';
			$Sample .= ' Also have options to send notification email to subscribers when new posts are published to your blog. Separate page available to include and exclude categories to send notifications.';
			$Sample .= ' Using plugin Import and Export options admins can easily import registered users and commenters to subscriptions list.</p>';
			$Sample .= ' <strong style="color: #990000">Plugin Features</strong><ol>';
			$Sample .= ' <li>Send notification email to subscribers when new posts are published.</li>';
			$Sample .= ' <li>Subscription box.</li><li>Double opt-in and single opt-in facility for subscriber.</li>';
			$Sample .= ' <li>Email notification to admin when user signs up (Optional).</li>';
			$Sample .= ' <li>Automatic welcome mail to subscriber (Optional).</li>';
			$Sample .= ' <li>Unsubscribe link in the mail.</li>';
			$Sample .= ' <li>Import/Export subscriber emails.</li>';
			$Sample .= ' <li>HTML editor to compose newsletter.</li>';
			$Sample .= ' </ol>';
			$Sample .= ' <p>Plugin live demo and video tutorial available on the official website. Check official website for more information.</p>';
			$Sample .= ' <strong>Thanks & Regards</strong><br>Admin';

			$form['es_templ_heading'] = 'Hello World Newsletter';
			$form['es_templ_body'] = $Sample;
			$form['es_templ_status'] = 'Published';
			$form['es_email_type'] = 'Newsletter';
			$action = es_cls_compose::es_template_ins($form, $action = "insert");
		}

		return true;
	}

	public static function es_notifications_default() {

		$result = es_cls_notification::es_notification_count(0);
		if ($result == 0) {
			$form["es_note_group"] = "Public";
			$form["es_note_status"] = "Enable";
			$form["es_note_templ"] = "1";

			$listcategory = "";
			$args = array( 'hide_empty' => 0, 'orderby' => 'name', 'order' => 'ASC' );
			$categories = get_categories($args);
			$total = count($categories);
			$i = 1;
			foreach($categories as $category) {
				$listcategory = $listcategory . " ##" . $category->cat_name . "## ";
				if($i < $total) {
					$listcategory = $listcategory .  "--";
				}
				$i = $i + 1;
			}
			$form["es_note_cat"] = $listcategory;
			es_cls_notification::es_notification_ins($form, "insert");
		}

		return true;
	}
}