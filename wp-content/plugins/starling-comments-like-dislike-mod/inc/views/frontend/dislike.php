<div class="cld-dislike-wrap  cld-common-wrap">
	<?php
	$liked_ips = get_comment_meta( $comment_id, 'cld_ips', true );
	$user_ip = $this->get_user_IP();

	$disliked_ids = get_comment_meta( $comment_id, 'cld_disliked_ids', true);
	$user_id = get_current_user_id();

	if ( empty( $liked_ips ) ) {
		$liked_ips = array();
	}
	if ( empty( $disliked_ids ) ) {
		$disliked_ids = array();
	}

//	$this->print_array($liked_ips);
	$user_ip_check = (in_array( $user_ip, $liked_ips )) ? 1 : 0;
	$user_id_check = (in_array( $user_id, $disliked_ids )) ? 1 : 0;
	?>
	<a href="javascript:void(0);"
	class="cld-dislike-trigger cld-like-dislike-trigger <?php echo ($user_ip_check == 1 || isset( $_COOKIE['cld_' . $comment_id] )) ? 'cld-prevent' : ''; ?>"
	title="<?php if($user_id_check) {echo "Unflag";} else {echo "Flag Comment for Moderation";}?>"
	data-comment-id="<?php echo $comment_id; ?>"
	data-trigger-type="dislike"
	data-user-ip="<?php echo $user_ip; ?>"
	data-ip-check="<?php echo $user_ip_check; ?>"
	data-id-check="<?php echo $user_id_check; ?>"
	data-restriction="<?php echo esc_attr( $cld_settings['basic_settings']['like_dislike_resistriction'] ); ?>">
		<?php
		$template = esc_attr( $cld_settings['design_settings']['template'] );
		switch ( $template ) {
			case 'template-1':
				?>
				<i class="fa fa-thumbs-down"></i>
				<?php
				break;
			case 'template-2':
				?>
				<i class="fa fa-heartbeat"></i>
				<?php
				break;
			case 'template-3':
				?>
				<i class="fa fa-times"></i>
				<?php
				break;
			case 'template-4':
				?>
				<i class="fa fa-frown-o"></i>
				<?php
				break;
			case 'custom':
				if($user_id_check):
					?>
					<i class="fa fa-flag"></i>
					<?php
				else:
					?>
					<i class="fa fa-flag" style="color:grey"></i>
					<?php
				endif;
				break;
		}
		/**
		 * Fires when template is being loaded
		 *
		 * @param array $cld_settings
		 *
		 * @since 1.0.0
		 */
		do_action( 'cld_dislike_template', $cld_settings );
		?>
	</a>
	<span class="cld-dislike-count-wrap cld-count-wrap"></span>
</div>
