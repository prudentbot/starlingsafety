<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="tab-comments" class="comments-area tabcontent">

	<?php // You can start editing here -- including this comment! ?>
	<?php

		$args = array(
		  'id_form'           => 'commentform',
		  'id_submit'         => 'submit',
		  'title_reply'       => __( 'Share your knowledge with the flock and scroll down to see comments from other Starlings.', 'gateway' ),
		  'title_reply_to'    => __( 'Leave a Reply to %s', 'gateway' ),
		  'cancel_reply_link' => __( 'Cancel Reply', 'gateway' ),
		  'label_submit'      => __( 'Post Comment', 'gateway' ),
		  'comment_notes_after' => ''
		);
		if(get_user_meta(wp_get_current_user()->ID, "is_approved", true)){
			comment_form( $args );
		}
		else{
			echo '<h4 style="margin-top:70px;">Your account must be approved to leave comments.</h4>';
		}
	?>

	<?php if ( have_comments() ) : ?>
		<h5> Sort By</h5>
		<div class="row">
			<div class="small-12 medium-4 columns">
				<select id="starling-comment-sort-type" name="starling-comment-sort-type">
					<option disabled selected value>Comment Type</option>
					<option value="All">All</option>
					<option value="Advice">Advice</option>
					<option value="Alert">Alert</option>
					<option value="Question">Questions</option>
					<option value="Event">Event Bulletin</option>
					<option value="News">News Article</option>
				</select>
			</div>
			<div class="small-12 medium-4 columns">
				<select id="starling-comment-sort-area" name="starling-comment-sort-type">
					<option disabled selected value>Area</option>
					<option value="All">All</option>
				</select>
			</div>
			<div class="small-12 medium-4 columns">
				<select id="starling-comment-sort-other" name="starling-comment-sort-other">
					<option selected value="Verifications">Most Verified</option>
					<option value="Date">Most Recent</option>
				</select>
			</div>
		</div>
		<div class="row">
			<input id="starling-comment-search" type="text" placeholder="Search Comments..."/>
		</div>


		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'gateway' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'gateway' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'gateway' ) ); ?></div>
		</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 60,
					'callback' => 'gateway_comments',
				) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'gateway' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'gateway' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'gateway' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'gateway' ); ?></p>
	<?php endif; ?>

</div><!-- #comments -->
