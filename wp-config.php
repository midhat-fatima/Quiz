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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'quiz' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '?l6wv)l>0>ccvi0sl0;Z@pn<s0Lw)<i.,H.%`_*kXtJQeMIa71z#WSx,-miOX#6?' );
define( 'SECURE_AUTH_KEY',  'gy<k*^pg_e_|$ &ThxCV@NWgrpx;KRUyJ-;-AWRXi.{:y>&_,[wSc(7a-.$@JwWa' );
define( 'LOGGED_IN_KEY',    'Y4%@`$#wS.t9Ax_eQqE&O(D!?kigi8ah2)q.%^=SnOhl&+5WB8E@lJA&XdtDwgT&' );
define( 'NONCE_KEY',        'W~^(=%y;MK>|q8cueGLbK|.Fp?I7p1WAexw6 n/$ve}~0zZ5Qr.?dD$1JO}q4yj3' );
define( 'AUTH_SALT',        'cE}=(+xFmLBQHodp>pRgxMWKqOd&7nAQn+|K- }b3-XYS_ctBvquPNz|;2$lTHJ4' );
define( 'SECURE_AUTH_SALT', ':&hDs>S6o0`<i9p)NV[<2Yr@_/S)Sr20aG~z[4hEydoL1}vq[YUGE$}7QSMjXRt3' );
define( 'LOGGED_IN_SALT',   'LM!7lO/7.@:}?XCuqQEcsF*]gR<QdF6;H=8L%L.4sj5Tt[suhu`;|v{vof3- N*m' );
define( 'NONCE_SALT',       '9`j(nAX=j:iHUDFDagVX+uS`S4QzN6[$kQs#+Wu>=9Z4Qm0($2Qfy&%VsKP[(JXX' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
