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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress_3');

/** MySQL database username */
define('DB_USER', 'wordpress_7');

/** MySQL database password */
define('DB_PASSWORD', 'hOs4o8_B3H');

/** MySQL hostname */
define('DB_HOST', 'localhost:3306');

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
define('AUTH_KEY',         'cZWO4g^5Y6ZnuhtA2VOuJE9l@6g3XMe8dgV^riFk8dzwRqn5QZv&Ebq^nB97SfaY');
define('SECURE_AUTH_KEY',  '01kf5fP2IPyC3N3MRHeZ4@R)qDCFVr1M4HetL@YglJb1AmpVwV@1@3y#t679#KjH');
define('LOGGED_IN_KEY',    'j$XC4wCoQEGZoOEBIsx(Z1lrVjexf&Y*HG#SpN%Z&Lhgc%LEhV(CrBAywfl7E91*');
define('NONCE_KEY',        'J#XS$AZXZ)ERxKAo5X$6cmvPr2rA^KtnSf!pIDqyahZVA&6kEn(DCYJ^lVAc(%Cq');
define('AUTH_SALT',        'gsg!MvuVSqeXZJsp8GSUqeZSYHTab!g79wmKU2z7TQDi*^ZN)IUcpqtoI5oKbGNU');
define('SECURE_AUTH_SALT', 'E#ryUuoUsDk176qGblfP@Iaqq!nYz!$p3wAn3ywo1%)8jC$h8(FqGRy(9eKophVI');
define('LOGGED_IN_SALT',   'h8xXyhLFL#!ch8VB8cSHIIjVrn7XlJhZNmhQB0d7eVx3nK)K9m7Re68$e)NSJLlq');
define('NONCE_SALT',       'Wsei1M9gt)KiL&mU!tD8n@P$FS(favo1xoh(p9p0ZSmD2qwXspeco4N6&Eqo$odV');
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
define ('WPLANG', 'en_US');

define ('FS_METHOD', 'direct');

define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

//--- disable auto upgrade
define( 'AUTOMATIC_UPDATER_DISABLED', true );



?>
