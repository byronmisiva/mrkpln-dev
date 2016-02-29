<?php
/** 
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information by
 * visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
define('FS_METHOD','direct');
define('WP_MEMORY_LIMIT', '64M');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress_c');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1:3306');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link http://api.wordpress.org/secret-key/1.1/ WordPress.org secret-key service}
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'CQos1YSKxJAIWPt#U3^H14vWTEhevIz2(78Y&Klo%fxc8MiArDBeX!4%YySGOn1u');
define('SECURE_AUTH_KEY',  '*GU)O^N4!&iv38Py(I%Ln18bgphvhjzl#cIw0lDbbc0vT7cz^09n#XM)H9rMZSxW');
define('LOGGED_IN_KEY',    's2hxRS2dtB5Oy$i%N%TdaxOdOCaf(btVyNUvXJyTx2NbPl%wH7qgIujHnrB0XW4V');
define('NONCE_KEY',        'Awk#%F$XM9nMm^7cKmah(%dSEb)OC3OjE^DmQ(9n5n(c%E*E@vwj#KmuOQfsyHJL');
define('AUTH_SALT',        'V*JJ%DibF7HTjAh%w$D&e6(ihU!kTv$m7HpmB3q8hk($QATCC)nS@NckNDCdI6AS');
define('SECURE_AUTH_SALT', 'i2iGL)$WbQ7y3DV!WGoW@%^IM5Uhf5JyqRuiL0WVhDizSp@buS12PssXmMeMW#gV');
define('LOGGED_IN_SALT',   'xpKF%MNt@r(pU^UyI@Di!DjIMnaQAUibm7S(Z%wgNZbnNwHf(TD923riO96!LpUF');
define('NONCE_SALT',       '%VgGW0EPM@Gq4O8*Us)qj*vDE4PX^uZUktghr))6Cucj7^A&I)h%IOFnge!dB10z');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', 'es_ES');

define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);

/* That's all, stop editing! Happy blogging. */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

//--- disable auto upgrade
define( 'AUTOMATIC_UPDATER_DISABLED', true );
?>