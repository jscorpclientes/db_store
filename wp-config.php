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

define( 'WP_HOME', 'https://db.rcbtech.com.br' );
define( 'WP_SITEURL', 'https://db.rcbtech.com.br' );

define('ALLOW_UNFILTERED_UPLOADS', true);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'loja_db' );

/** MySQL database username */
define( 'DB_USER', 'loja' );

/** MySQL database password */
define( 'DB_PASSWORD', 'rmc3284k' );

/** MySQL hostname */
define( 'DB_HOST', 'rcbtech.cqbdohacyboy.us-east-1.rds.amazonaws.com' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY',         'fxiAhsF{rm#e4ZX5`S^9kFqa[{*YAb@E]&W&U%1wo4DAMA4}Bs!sj(uP&.JYFl>f');
define('SECURE_AUTH_KEY',  'F;HNoh$M{fca#ckv| -/.FE>Bq!+EQ`f>)^a.yIL$0:^i<0Kh6ZEbauA?[Mqmw4!');
define('LOGGED_IN_KEY',    'e:,&>CSwS~t@a:TZ${:C1!q]A,pHs9kQsT4Vf2+N8>yuUGn{@A@yLkUfUg-,Dh@b');
define('NONCE_KEY',        'Ro$!%Io8^<L~l$-$@LuQ==S-@=nk8jb{>[4Ql$g=*[A{ar5BeyVFGj~,Y6{B0s-z');
define('AUTH_SALT',        'QBN!Xo0q!iFh~AB;C:LqecRpKa=)FD 0n=/%uAp+?Z<7-vo$wRN>x;]u(h+,.%[+');
define('SECURE_AUTH_SALT', 'Wg_eoL$LQ&|XP{B$Q4!4<Smp:KCzbV,S]AqY,Skq7l<v:0/*:}=vs?BQ%y*m+eSP');
define('LOGGED_IN_SALT',   'ZoOhyy<%<oF}{f-X|jXes;&>Cfu#*E[qh{Hc_!<ieDPzGAe[Y3gqlsfxa%|!R{t:');
define('NONCE_SALT',       'Q{61MkzLiUB)&<WAjFL)`-0gqTh6qN@--r&$QV|fOszjZvXV2&xr+z[4TmuS|~73');

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
