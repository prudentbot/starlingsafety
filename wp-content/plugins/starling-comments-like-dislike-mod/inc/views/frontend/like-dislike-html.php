<?php
if(!$comment)
	return;
$comment_id = $comment->comment_ID;
$like_count = get_comment_meta( $comment_id, 'cld_like_count', true );
$dislike_count = get_comment_meta( $comment_id, 'cld_dislike_count', true );
$post_id = get_the_ID();
$cld_settings = get_option( 'cld_settings' );
/**
 * Filters like count
 *
 * @param type int $like_count
 * @param type int $comment_id
 *
 * @since 1.0.0
 */
$like_count = apply_filters( 'cld_like_count', $like_count, $comment_id );

/**
 * Filters dislike count
 *
 * @param type int $dislike_count
 * @param type int $comment_id
 *
 * @since 1.0.0
 */
$dislike_count = apply_filters( 'cld_dislike_count', $dislike_count, $comment_id );

$post = get_post();
//if this isn't a country page don't bother.
if(!$post || !get_post_meta($post->ID, 'danger-level', true))
	return;

if ( $cld_settings['basic_settings']['status'] != 1 ) {
	// if comments like dislike is disabled from backend or user isn't approved
	return;
}
//$this->print_array( $cld_settings );
?>
<div class="cld-like-dislike-wrap cld-<?php echo esc_attr( $cld_settings['design_settings']['template'] ); ?>">
	<?php
	/**
	 * Like Dislike Order
	 */

	if (get_user_meta(get_current_user_id(), "is_approved", true)){
		if ( $cld_settings['basic_settings']['display_order'] == 'like-dislike' ) {
			if ( $cld_settings['basic_settings']['like_dislike_display'] != 'dislike_only' ) {
				include(CLD_PATH . 'inc/views/frontend/like.php');
			}
			if ( $cld_settings['basic_settings']['like_dislike_display'] != 'like_only' ) {
				include(CLD_PATH . 'inc/views/frontend/dislike.php');
			}
		} else {
			/**
			 * Dislike Like Order
			 */
			if ( $cld_settings['basic_settings']['like_dislike_display'] != 'like_only' ) {
				include(CLD_PATH . 'inc/views/frontend/dislike.php');
			}
			if ( $cld_settings['basic_settings']['like_dislike_display'] != 'dislike_only' ) {
				include(CLD_PATH . 'inc/views/frontend/like.php');
			}
		}
	}
	else {

		$like_count = get_comment_meta( $comment_id, 'cld_like_count', true );
		if(empty($like_count))
			$like_count = 0;

		$verif_string = "";
		if($like_count == 1)
			$verif_string = ' Verification';
		else
			$verif_string = ' Verifications';

		?>
		<div> <span class="cld-custom-like-count"><?php echo $like_count; ?></span> <?php echo $verif_string; ?> </div>
			<?php
	}
	?>
</div>

<?php
//$this->print_array($comment);
?>
