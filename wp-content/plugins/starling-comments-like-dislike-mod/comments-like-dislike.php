<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please' );

/*
  Plugin Name: Starling Comments Like Dislike Mod
  Description: Modifications on the Like Dislike plugin for Starling Services
  Version:     9.9.9
  Author:      Tyler Smith/WP Happy Coders
  Author URI:  tylerbuilds.website
  License:     GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Domain Path: /languages
  Text Domain: comments-like-dislike
 */


if ( !class_exists( 'CLD_Comments_like_dislike' ) ) {

	class CLD_Comments_like_dislike {

		function __construct() {
			$this->define_constants();
			$this->includes();
		}

		/**
		 * Include all the necessary files
		 *
		 * @since 1.0.0
		 */
		function includes() {
			require_once CLD_PATH . '/inc/classes/cld-library.php';
			require_once CLD_PATH . '/inc/classes/cld-activation.php';
			require_once CLD_PATH . 'inc/classes/cld-init.php';
			require_once CLD_PATH . 'inc/classes/cld-admin.php';
			require_once CLD_PATH . 'inc/classes/cld-enqueue.php';
			require_once CLD_PATH . 'inc/classes/cld-hook.php';
			require_once CLD_PATH . 'inc/classes/cld-ajax.php';
		}

		/**
		 * Define necessary constants
		 *
		 * @since 1.0.0
		 */
		function define_constants() {
			defined( 'CLD_PATH' ) or define( 'CLD_PATH', plugin_dir_path( __FILE__ ) );
			defined( 'CLD_IMG_DIR' ) or define( 'CLD_IMG_DIR', plugin_dir_url( __FILE__ ) . 'images' );
			defined( 'CLD_CSS_DIR' ) or define( 'CLD_CSS_DIR', plugin_dir_url( __FILE__ ) . 'css' );
			defined( 'CLD_JS_DIR' ) or define( 'CLD_JS_DIR', plugin_dir_url( __FILE__ ) . 'js' );
			defined( 'CLD_VERSION' ) or define( 'CLD_VERSION', '1.0.2' );
			defined( 'CLD_TD' ) or define( 'CLD_TD', 'comments-like-dislike' );
			defined( 'CLD_BASENAME' ) or define( 'CLD_BASENAME', plugin_basename( __FILE__ ) );
		}

	}

	$cld_object = new CLD_Comments_like_dislike();
}
