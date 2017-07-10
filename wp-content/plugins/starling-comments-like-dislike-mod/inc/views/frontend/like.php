<div class="cld-like-wrap  cld-common-wrap">
	<?php
	$liked_ips = get_comment_meta( $comment_id, 'cld_ips', true );
	$liked_ids = get_comment_meta( $comment_id, 'cld_liked_ids', true);
	$user_ip = $this->get_user_IP();
	$user_id = get_current_user_id();
	if ( empty( $liked_ips ) ) {
		$liked_ips = array();
	}
	if ( empty( $liked_ids ) ) {
		$liked_ids = array();
	}

	// $this->print_array($liked_ips);
	$user_ip_check = (in_array( $user_ip, $liked_ips )) ? 1 : 0;
	$user_id_check = (in_array( $user_id, $liked_ids )) ? 1 : 0;

	?>
	<a href="javascript:void(0);" class="cld-like-trigger cld-like-dislike-trigger
	<?php echo ($user_ip_check == 1 || isset( $_COOKIE['cld_' . $comment_id] )) ? 'cld-prevent' : ''; ?>"
		title="<?php if($user_id_check) {echo "Unverify";} else {echo "Verify Information";}?>"
		data-comment-id="<?php echo $comment_id; ?>"
		data-trigger-type="like"
		data-restriction="<?php echo esc_attr( $cld_settings['basic_settings']['like_dislike_resistriction'] ); ?>"
		data-user-ip="<?php echo $user_ip; ?>"
		data-ip-check="<?php echo $user_ip_check; ?>"
		data-user-id="<?php echo $user_id; ?>"
		data-id-check="<?php echo $user_id_check; ?>">
		<?php
		$template = esc_attr( $cld_settings['design_settings']['template'] );
		switch ( $template ) {
			case 'template-1':
				?>
				<i class="fa fa-thumbs-up"></i>
				<?php
				break;
			case 'template-2':
				?>
				<i class="fa fa-heart"></i>
				<?php
				break;
			case 'template-3':
				?>
				<i class="fa fa-check"></i>
				<?php
				break;
			case 'template-4':
				?>
				<i class="fa fa-smile-o"></i>
				<?php
				break;
			case 'custom':
				if($user_id_check):
					?>
						<!-- verified -->
						<i class="fa fa-check"></i>
					<?php
				else:
					?>
						<i class="fa fa-check" style="color:grey"></i>
						<!-- verify -->
					<?php
				endif;
				break;
		}
		/**
		 * Fires when template is being loaded
		 * 						<!--<i class="fa fa-check" style="color:grey"></i>-->
		 * @param array $cld_settings
		 *
		 * @since 1.0.0
		 */
		do_action( 'cld_like_template', $cld_settings );
		?>
	</a>
	<span class="cld-like-count-wrap cld-count-wrap"><?php echo (empty( $like_count )) ? 0 : number_format( $like_count ); ?></span>
</div>
