<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'wordpress' );

/** Database password */
define( 'DB_PASSWORD', 'wordpress' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'TE8lom{E>+d[twO!q?bkhC<F,Kr+*CbK$wz=6OzKWw`I+L A(q*[Q#p)#Vlo6dz]' );
define( 'SECURE_AUTH_KEY',  'b/`h.a4]>`M<j5u,GyAWb6Itjp5nwPHvN[.{I&HM2J3K#2?5)$D]gkWxPG.Yl5?D' );
define( 'LOGGED_IN_KEY',    '*AX<).9/N])y9j:s1l${Xx4n0*as,CCB#5g)`7g;$A*U+%`ou%O1[%>aQqckoBd,' );
define( 'NONCE_KEY',        '3gu<6+ kIk:7/.je~_`5g^_4+OXHxiz[_Eeuj01Gzh<%i LLWy?|g}`;%R]o><-v' );
define( 'AUTH_SALT',        'tdQhf;0MuVx@t/Fy[K!w&qph!n2#mo7[GRlo.TV{<(hit<8ot1CZJ6fL&r8xmC>y' );
define( 'SECURE_AUTH_SALT', 'B`gGaXul@V!EzI.M?a49[ibF]$lN1r$;nEC2]iSt5]Cn)+D`4L#{7@1!ym>Ydw{%' );
define( 'LOGGED_IN_SALT',   ';.c>L;6L(aPkeY_pHf`{kV:Mal+n]IkJ}ak$GD2s.3gt.3Kmfk&/.W=D6_gf:~$|' );
define( 'NONCE_SALT',       '[m7_<RK+U<A%TlpFKW+ninIG:|Ok,:JY&7:oMKZ`]IDs|9B(sV6K/-Ew!sEf]AY#' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
