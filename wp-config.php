<?php
define( 'WP_CACHE', true );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u802332889_8O93b' );

/** Database username */
define( 'DB_USER', 'u802332889_3wVLe' );

/** Database password */
define( 'DB_PASSWORD', 'qcnoVWWwEI' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'hZQriECmK!(ugKwUe9D ?TdB[2.8p[ wN5*in$]8<?Ps;U.UVxN5u~S|Bx8Mx]J!' );
define( 'SECURE_AUTH_KEY',   'y+M-/!R@#hI|{>U5%m&3|2FlCkq`j9LeWZkt{Od#AiUjMaZ#Lg(0 jlghO~S^H^r' );
define( 'LOGGED_IN_KEY',     '<vFmNinJb3CA25(t5F dI<fxs,.i2Ck(dXbl1UQo*RDv&XyG^!(*b(-G*adB2m[)' );
define( 'NONCE_KEY',         'Zuz:-gUI+550[m{4~?(@(:*qa2YvpfqmBb)Mq1&n>pqN)>#%f{%{:>dg*0^Un$%X' );
define( 'AUTH_SALT',         'H#{qaEmkVyRV/:c.r!`kO9KKH9k,![93g7/YxeU?J&~Su@|C3aUH*H9?(E/MH{}U' );
define( 'SECURE_AUTH_SALT',  'oEOzjbzTg1?rLs[0&:}GN|L$l~:V=v%tkQW=BI]`(xx}buRXn4l0X-gwi3EwFpP:' );
define( 'LOGGED_IN_SALT',    '!vAt-_X/t~SM{QCmoPut>:;A0+]KpDUp^]|<w)3<^pgo)J}mAt_J P4g!h2VRMM(' );
define( 'NONCE_SALT',        'IRza{2~ PIm|jOyE} cO7jvo%;tii+]dXS&CjpA+RB}pVNwGa3lhH?U1rwgYfI-?' );
define( 'WP_CACHE_KEY_SALT', 'p#xf~_xip2WEsM:k;dESsr`E08,Y.N/DpmO;75GhKVxm?a3dd.u7{LYA_KWv<RI5' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', true );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', '40dc58be7b8e201d5014ed7c9fc84aec' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
