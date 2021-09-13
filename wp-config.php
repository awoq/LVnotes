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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'cCc8+n4Sp_M,}T-4dU=jpHJs}9FCb[{v/^AciZ^w8709WEop$b#{Z_Dll%LxZ[eD');
define('SECURE_AUTH_KEY',  '0~EKgxWa6LJM17.P@5y-*4YWIJpYp/[M,<`)P(9_MCq;@{kTE@EVs8O7w#Tj;3a^');
define('LOGGED_IN_KEY',    'hLRX9-6QN<)$TPNpXeu dZ(Im]CoFl;u:=0&GQkPY$m>Nn!<A~oe@86W7;`>p$%]');
define('NONCE_KEY',        '#nS(#/-1:hAo%#Oc%I5O|;k)PxwU`QvE.!A5V-#Uuu/,$BXAz}}av#jVtceyrPb.');
define('AUTH_SALT',        '&O<w ^$4jlPeFca#ymwx}pIB6E,B#n@GF-xfA#dDlfZCc0FRklZlv|^r<W,_*6m/');
define('SECURE_AUTH_SALT', 'fIoNR8=K[}EPT$ pXFti)dW]0/_bQn|<:74&/A #x4S}).^o^(Sb8?tEWOT8n(eY');
define('LOGGED_IN_SALT',   '=~iKO)Cj[%L6eX+WO]3ra_Pu4nK8x&0N=hG:gy`Q8Vx,jbSovnTg*C}Zz$eI -zM');
define('NONCE_SALT',       '`EWEHHs_7octV3vw%0p4:kXmaAXpe=3_P5vs~&&sPQ8gd5K{}K2Gn*k[(899qc^p');

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
