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
define( 'WPCACHEHOME', '/home/grocareindia/public_html/blog/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'grocarei_blog');

/** MySQL database username */
define('DB_USER', 'grocarei_kirti');

/** MySQL database password */
define('DB_PASSWORD', 'chotukiku275');

/** MySQL hostname */
define('DB_HOST', 'localhost:3306');

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
define('AUTH_KEY',         'ni(7M%+-) Wt5v3n#,LI!J3;:.mJ+xJuHD#7~H.D0;)_5BH?;Z5tqLf&XdaK,1(K');
define('SECURE_AUTH_KEY',  'a!qA0P,W+IWLx-aLfN(T[6!_^3$e8kGS|O,3.7xn*7@gqd,*{<2fc y^E;[5osP0');
define('LOGGED_IN_KEY',    '_#{Z_,jGR5P{8/RZMg`Y K]17ZD?@X_1g(JT%nGSJx&c((6ldX~dL#+=rqF,:ta8');
define('NONCE_KEY',        '_$tHeoQD!e+A&tm-L>B8xP$hy%b4 BG hz0!7wk?4l%G{B6lDqXx`|.ST~7iq337');
define('AUTH_SALT',        'VB|q?p+DjCGwC3*w$7CDhv!_ W+C[3$O7RGisyt[U$gon8!!DJ9X~M>WhXYoXW)I');
define('SECURE_AUTH_SALT', 'tPo}gq8=}AhO6M`Rw%|AdXVV wrBap _7t&D#0whD-z3kQoC)([1I=^|cbL/+8{K');
define('LOGGED_IN_SALT',   'u<&? 2_mwc{k<swVl dLYTN.7BtE23hw7Hu7xFM5!}fu?kVLnfexH Sd_]d6p5vU');
define('NONCE_SALT',       'w`=1^5!vd3;X>mBY*azsjHWtu%*`_tT }Ka+KNV&7i`=w#@-`sg ke63[`4PqH,O');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
