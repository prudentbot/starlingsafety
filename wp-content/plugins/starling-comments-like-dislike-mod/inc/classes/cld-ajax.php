<?php

if ( !class_exists( 'CLD_Ajax' ) ) {

	class CLD_Ajax extends CLD_Library {

		function __construct() {
			add_action( 'wp_ajax_cld_comment_ajax_action', array( $this, 'like_dislike_action' ) );
			add_action( 'wp_ajax_nopriv_cld_comment_ajax_action', array( $this, 'like_dislike_action' ) );
		}

		function adjust_count ($user_id, $meta_tag, $delta){
			$count  = get_user_meta($user_id, $meta_tag, true);
			if ( empty( $count ) ) {
				$count = 0;
			}
			$count += $delta;
			if($count < 0)
				return;
			update_user_meta( $user_id, $meta_tag, $count );

		}

		//absolve me of my sins, spaghetti gods
		function like_dislike_action() {
			if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'cld-ajax-nonce' ) ) {
				$comment_id = sanitize_text_field( $_POST['comment_id'] );
				$type = sanitize_text_field( $_POST['type'] );
				$is_revert = sanitize_text_field( $_POST['is_revert'] );
				// $user_ip = sanitize_text_field( $_POST['user_ip'] );
				$user_id = get_current_user_id();
				$comment = get_comment($comment_id);
				$target_user_id = $comment->user_id;


				if(!$user_id || !get_user_meta($user_id, "is_approved", true)){
					$response_array = array( 'success' => false, 'latest_count' => "You must be logged in and approved to use this feature." );
					echo json_encode($response_array);
					die();
				}

				if ( $type == 'like' ) {
					$like_count = get_comment_meta( $comment_id, 'cld_like_count', true );
					if ( empty( $like_count ) ) {
						$like_count = 0;
					}

					$liked_ids = get_comment_meta( $comment_id, 'cld_liked_ids', true);
					$user_id_check = ($liked_ids && in_array( $user_id, $liked_ids )) ? 1 : 0;
					if($is_revert ){
						if(!$user_id_check || $like_count < 1 || empty($liked_ids) || !in_array( $user_id, $liked_ids)){
							$response_array = array( 'success' => false, 'latest_count' => $like_count );
						}
						else {
							$like_count = $like_count - 1;
							$check = update_comment_meta( $comment_id, 'cld_like_count', $like_count );

							if ( $check) {

								$response_array = array( 'success' => true, 'latest_count' => $like_count );
							} else {
								$response_array = array( 'success' => false, 'latest_count' => $like_count );
							}

							$index = array_search($user_id, $liked_ids);
							if($index !== FALSE){
							    unset($liked_ids[$index]);
							}
							update_comment_meta( $comment_id, 'cld_liked_ids', $liked_ids );

							$this->adjust_count($target_user_id, 'verifications_received', -1);
							$this->adjust_count($user_id, 'verifications_given', -1);
						}
					}
					else{ //is like and not revert
						if($user_id_check){
							$response_array = array( 'success' => false, 'latest_count' => $like_count );
						}
						else {
							$like_count = $like_count + 1;
							$check = update_comment_meta( $comment_id, 'cld_like_count', $like_count );

							if ( $check ) {

								$response_array = array( 'success' => true, 'latest_count' => $like_count );
							} else {
								$response_array = array( 'success' => false, 'latest_count' => $like_count );
							}

							if(empty($liked_ids)){
								$liked_ids = array();
							}
							if( ! in_array( $user_id, $liked_ids)){
								$liked_ids[] = $user_id;
							}
							update_comment_meta( $comment_id, 'cld_liked_ids', $liked_ids );

							$likes_received_count = get_user_meta($target_user_id, "likes_received_count", true);
							if ( empty( $likes_received_count ) ) {
								$likes_received_count = 0;
							}
							$likes_received_count += 1;
							update_user_meta( $target_user_id, 'likes_received_count', $likes_received_count );

							error_log("Adjusting Count");
							error_log(get_user_meta($target_user_id, 'verifications_received', true));

							$this->adjust_count($target_user_id, 'verifications_received', 1);

							error_log(get_user_meta($target_user_id, 'verifications_received', true));

							$this->adjust_count($user_id, 'verifications_given', 1);

						}
					}
				} else { //if type is 'dislike'
					$dislike_count = get_comment_meta( $comment_id, 'cld_dislike_count', true );
					if ( empty( $dislike_count ) ) {
						$dislike_count = 0;
					}

					$disliked_ids = get_comment_meta( $comment_id, 'cld_disliked_ids', true);
					$user_id_check = ($disliked_ids && in_array( $user_id, $disliked_ids )) ? 1 : 0;
					if ($is_revert){
						if(!$user_id_check || $dislike_count < 1 || empty($disliked_ids) || !in_array( $user_id, $disliked_ids)){
							$response_array = array( 'success' => false, 'latest_count' => $dislike_count );
						}
						else {
							$dislike_count = $dislike_count - 1;
							$check = update_comment_meta( $comment_id, 'cld_dislike_count', $dislike_count );

							if ( $check) {

								$response_array = array( 'success' => true, 'latest_count' => $dislike_count );
								$disliked_ids = get_comment_meta( $comment_id, 'cld_disliked_ids', true);

								$index = array_search($user_id, $disliked_ids);
								if($index !== FALSE){
										unset($disliked_ids[$index]);
								}

								update_comment_meta( $comment_id, 'cld_disliked_ids', $disliked_ids );

							} else {
								$response_array = array( 'success' => false, 'latest_count' => $dislike_count );
							}

							$this->adjust_count($target_user_id, 'flags_received', -1);
							$this->adjust_count($user_id, 'flags_given', -1);
						}
					}
					else { // if is not a revert, and is a dislike
						if($user_id_check){
							$response_array = array( 'success' => false, 'latest_count' => $like_count );
						}
						else {
							$dislike_count = $dislike_count + 1;
							$check = update_comment_meta( $comment_id, 'cld_dislike_count', $dislike_count );
							if ( $check ) {
								$response_array = array( 'success' => true, 'latest_count' => $dislike_count );
							} else {
								$response_array = array( 'success' => false, 'latest_count' => $dislike_count );
							}

							$disliked_ids = get_comment_meta($comment_id,'cld_disliked_ids',true);
							if(empty($disliked_ids)){
								$disliked_ids = array();
							}
							if( ! in_array( $user_id, $disliked_ids)){
								$disliked_ids[] = $user_id;
							}
							update_comment_meta( $comment_id, 'cld_disliked_ids', $disliked_ids );
							wp_set_comment_status( $comment_id, 'hold');

							$this->adjust_count($target_user_id, 'flags_received', 1);
							$this->adjust_count($user_id, 'flags_given', 1);

						}
					}
				}
				echo json_encode($response_array);

				//$this->print_array( $response_array );
				die();
			} else {
				die( 'No script kiddies please!' );
			}
		}

	}

	new CLD_Ajax();
}
