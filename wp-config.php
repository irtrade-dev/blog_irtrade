<?php
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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u555060246_blog_homologac' );

/** Database username  */
define( 'DB_USER', 'u555060246_blog_homologac' );

/** Database password */
define( 'DB_PASSWORD', 'Irtrade@2011' );

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
define( 'AUTH_KEY',         'IFgxsRx#FmWTg,7a7}mac0R&?J[ch/ebvx6nc--[r/nkZ[AEyv=*BaOnLO}A&lqZ' );
define( 'SECURE_AUTH_KEY',  'a/8zXZ`GPAty[c=64hIsAp|8om./<1p&`t>$Ah?^>aM~:8Z~_5MNX,8l*#!bJw$5' );
define( 'LOGGED_IN_KEY',    '-?>JQiBuG7iFPd<=Bh32(4Hpp2,4H9u~)WP<[_KG8rRFSu8Fjahd6lna$Ldysr&7' );
define( 'NONCE_KEY',        '`)E3m8k/D[wz09_ rl^OGw8UHDDb=^Bk4%QY!G~l2!OSY{ tjy4v9~:<im~%EyYR' );
define( 'AUTH_SALT',        'Djf@Hcb]Y`OMbUJtW~,AZ%>%){kb&aRe5cv,.c9?wo5m*q*</4AlSQN8XzYU +=C' );
define( 'SECURE_AUTH_SALT', '4>yF$E1h53U8,*w!sArHsyfiG~1uPM51u6BonqJ=WZvhg7~j^5vp40sZx6-#;^5H' );
define( 'LOGGED_IN_SALT',   '1xycHEUY!@_8Sl|Oc;CT7wn&FK_Q4lk;Ef#>#%_c{^y`=5=1JYs}&n6`VnHyc.32' );
define( 'NONCE_SALT',       'fiD-LWrR8!fItdu74C2B~hI RHMtp`%[Csb~$<]^P_@m.f9S6m?=--Ysu^tu6`Fr' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
