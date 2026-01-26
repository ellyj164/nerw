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
define( 'DB_NAME', 'frenchpracticehub_wordpress' );

/** Database username */
define( 'DB_USER', 'frenchpracticehu' );

/** Database password */
define( 'DB_PASSWORD', 'RDDZgqcXX4I3eOyQ4JN9Sg94B' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          'CJXWuP4,~_3]nZLP9N~SCxL!wj/)$ Sc21z}BvLXoQSv$}-WlYv-YI`LTuy`[N3F' );
define( 'SECURE_AUTH_KEY',   'Vp+P[3d9w,d5/( 3Y_F+4!EdSguFbJ<t2ICMU%s&la!+C33f]M  ?E2A<A-] #>0' );
define( 'LOGGED_IN_KEY',     'qm5QVb1CDNC4uD75`MOCaTSJ6p>ixiy9bz)aLWoVV9(/`/~ewx4gLSXztQtR]9Ta' );
define( 'NONCE_KEY',         '0N4`ozA$6Zr.)z_|v-:`a5;Myo!,e1%gS-2mET{H0Ru&n9}&tbJ3454eGMea*:dP' );
define( 'AUTH_SALT',         '-pkr5(+sm|2IiqRP4,uEex{%wa~?Db+Aw)lzS!P4FaF@{3m%>XLEZi8*xx&g!3XS' );
define( 'SECURE_AUTH_SALT',  'h^v>Jjl+v$yK~.7S9NliMNv5/krq;5a]FGOc>1G6d,rK5LG?fxzNl(PDhg_!,/*<' );
define( 'LOGGED_IN_SALT',    'T2Ey$o<jzg?ljW}z3GQzFCd<S-]}xE6JH~|b9(]law8+~fr~.MQ=T,kGJ;1|_[cV' );
define( 'NONCE_SALT',        'mnZWMvjN-h)bB8&w%Dc}MSzL:(m|423Wg42:*99bI=x5=Sw#6Q[_Kr`Q6@@dZ/)G' );
define( 'WP_CACHE_KEY_SALT', 'RYjH>o~>CNkm5N-p^YKt>&_1P2f{]|NXw@f,W:GFr=Id UE:ib1_4RUeM=XI}w;U' );


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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
