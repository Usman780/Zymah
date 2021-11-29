<?php
define( 'WP_CACHE', true );

//Begin Really Simple SSL Load balancing fix
if ((isset($_ENV["HTTPS"]) && ("on" == $_ENV["HTTPS"]))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "1") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "on") !== false))
|| (isset($_SERVER["HTTP_CF_VISITOR"]) && (strpos($_SERVER["HTTP_CF_VISITOR"], "https") !== false))
|| (isset($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_X_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_PROTO"]) && (strpos($_SERVER["HTTP_X_PROTO"], "SSL") !== false))
) {
$_SERVER["HTTPS"] = "on";
}
//END Really Simple SSL
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
define('DB_NAME', 'zyma41417816673');

/** MySQL database username */
define('DB_USER', 'zyma41417816673');

/** MySQL database password */
define('DB_PASSWORD', 'W(.01geBv@ac*');

/** MySQL hostname */
define('DB_HOST', 'zyma41417816673.db.41417816.818.hostedresource.net:3311');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '@I3F>i(SgBXOx5oJ}ZpbE%1vwE{QEW8<#aKif:K:h./vl:~7^w+lm|xaYoN|Q4PO');
define('SECURE_AUTH_KEY',  '2^TmGPeXThBe@Y*-}&b/a&=I{9<S?.r2Jky2T*^AJX2l1-77~*Ebi3Lw&I~P8Awa');
define('LOGGED_IN_KEY',    'jGWe)IM_r.t$=:k,p&Ye]cZynoy5H=w1vk*p8sfyAWDbeSY7XIpMUR=:/]ev&0z~');
define('NONCE_KEY',        'Z:1kuqF 8_Kmc=)/2QE$x~_{s%~{P!DD%p hlYp/ros9?u D{mY.lf)VL>zel:DS');
define('AUTH_SALT',        'R3.ZISgCY0SKTAR]JdC~=T/t4Y4cc*K%{$ .mb>d.dF4y@J739Md|Rpv}/=W7oor');
define('SECURE_AUTH_SALT', 'fhWUS,xDm_Kou/Q#,pBoarHYo0EYOKMB0fPR]hxrAnXWvVa!WM2&payh2yaOG/#W');
define('LOGGED_IN_SALT',   '#)/# ~VlO^yYWPh0N>XQDftmM=~!O04)I}?2MZ|GG_j5R(Lp)vR/EXD!3q+5<%16');
define('NONCE_SALT',       'ZbQS9xJPEWc7SK5K,F^2i#<.&VoILMzj:vFdWG>vaq t]yi/7Xes{PC&vy>Vr!%P');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_7a1rasbtcr_';

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
define('WP_DEBUG', false);
//define( 'WP_CACHE', true );
require_once( dirname( __FILE__ ) . '/gd-config.php' );
define( 'FS_METHOD', 'direct');
define('FS_CHMOD_DIR', (0705 & ~ umask()));
define('FS_CHMOD_FILE', (0604 & ~ umask()));


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');