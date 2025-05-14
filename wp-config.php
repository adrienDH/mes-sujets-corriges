<?php
/**
 * TEST
 * 
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keysqDSQSS
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', "wordpress" );

/** Database username */
define( 'DB_USER', "wordpress" );

/** Database password */
define( 'DB_PASSWORD', "wordpress" );

/** Database hostname */
define( 'DB_HOST', "database" );

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
define( 'AUTH_KEY',         '?JpGP%<SjN~IE`ixs~IpE4|Z,x6r?=%f{9C:E<Pfy<@~QO7VQryQFOE}Y/7Q{IWa' );
define( 'SECURE_AUTH_KEY',  'Ye~NS7|5cH2]?ptSz%Or|q|;@=U&+/Lj)_aGUulGW1J+aBG8zluvxG!:C&sn027|' );
define( 'LOGGED_IN_KEY',    ';[Fo8!n-c}pyty/vzbf-6~n)Pwp^`Si?+6F]$g6q5K!eEFJ1eS/KqdM]<y)XlE!L' );
define( 'NONCE_KEY',        '(*ggfQS$IWK0rw7y,]DNC5,bgzXY;]%nD-t=!xCO`ipba!k|2Ta+*S4Zb>1jTh~>' );
define( 'AUTH_SALT',        'r2%!bw[o~2i_9_HF#:5ijmBQ!E(b{p98kl28HqJyHo!&e}@udh?OPFd*N*jMD}!M' );
define( 'SECURE_AUTH_SALT', 'OTn% h}6m;o,J#qXrN<H8Kh6`yVcITeqd8mB)uzLzDgxM$n_Hfku J1F^:MK~n5<' );
define( 'LOGGED_IN_SALT',   '..F#ik>~g0iw^PFTH#X^)n&]lQ> Wwz%MP|-~!izR$Kn)VYTI7#$jM&`Ar4,cQs{' );
define( 'NONCE_SALT',       '4O!F.Ng)qTs{a`8%[.:zwlG@&*4o /}92YW*S|-F70-3pz&;)T~#db/8M /hT#(p' );

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
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}


/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
