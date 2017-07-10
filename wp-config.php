<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home/johdra14/starlingsafety.com/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'starling');

/** MySQL database username */
define('DB_USER', 'jfd');

/** MySQL database password */
define('DB_PASSWORD', 'starlingfirebeer');

/** MySQL hostname */
define('DB_HOST', 'mysql.starlingsafety.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'lCD][lepa#<+qk!@E4]mxxkY}G.0O(u&/S^>LS0>!B{ogbMcOiTP5 skc73`W,??');
define('SECURE_AUTH_KEY',  'X`G1*J4ENM %r=5h5pQW_PGlpeGqHO{9V&M(p{OK6^Vb6*6CZeD@,Ki!d:Lf}>2v');
define('LOGGED_IN_KEY',    'm0oC/YMS%YU7=rYHR^3#K0+]6Dy~p1<^{T(r9E,7`_E{r&G9WUd$s+j;E?c0OU2)');
define('NONCE_KEY',        '2v2L`e#ug!Ac}_er*|, )m&4Ud@KJpC2B6U Fi3Sd>C.3?vXVMek],}^1,9d40J.');
define('AUTH_SALT',        'OuzKVu ? KFh.t8J(t;z]FIQIxFS.*9qR_7toUxc,Tz-Jc2bM^5y=/LQ.K`e>(g)');
define('SECURE_AUTH_SALT', '66}yYwUpa{1-w=P7`wXFo3>npxAY~G[?WezO5}|zjgt[gXvXbX(^kRq@TzC]@aEj');
define('LOGGED_IN_SALT',   'xv@X|.b=Hsm]P;VMN(_9vP1_m[#`[9Q[`Iyo/;Xq~9G,eW):z)16CoUTrVbDQ_T=');
define('NONCE_SALT',       'NGf(#0UgVrgc7Kp!S!kII1p<WZYMD9Ecw^3pm!u}^P{ 7>%S`~eU3BteL5Yamux)');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpty_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);
define( 'WP_DEBUG_LOG', true );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
