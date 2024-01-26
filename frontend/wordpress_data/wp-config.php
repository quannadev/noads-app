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

 * @link https://wordpress.org/documentation/article/editing-wp-config-php/

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

define( 'DB_HOST', 'db:3306' );


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

define( 'AUTH_KEY',         'YF@^7+c!(#.lQm9E[q:o+H7WZaw-cjeN=+)-bC ^1y3J}#.vQ7IG<$}bRUO{&)%f' );

define( 'SECURE_AUTH_KEY',  '4U(hH:d1)=h}Dv|<c{qiriHUTtP@PHk7cQ{ cjt*GbBx~KNnDPR@U6/HEc]791SG' );

define( 'LOGGED_IN_KEY',    ';!jR[dsm=`ZH=fBPPP]1ZL(r*u{YDdgCmRu<u-Sa,NFR.UIoGe~KJ$.$`?K:;f>F' );

define( 'NONCE_KEY',        'j2m_D2<m=EfdHs;<kC*BMer]Z77RUsTR.#FDGlbOszkw?ax[C$R,sR-BImD$H Ao' );

define( 'AUTH_SALT',        '-t7EQ3G:,+1;W=!RZ^[,3~XC*gtHC(3zRr%CR;)f2gTOiE.Ow/Cv2s?luHQ29(jL' );

define( 'SECURE_AUTH_SALT', '}`,4I%_>Ly<d}iy28< E_;RU U[b,JmU|D}w/D6:#a.7_f#_Zcf2>?CKOsL}m-+e' );

define( 'LOGGED_IN_SALT',   'bK*k|r !Q),]se1ti,99c+^dQ(R>aUJNO6SWjf>k72N,X|GDrp9hcb2`A.<5cm67' );

define( 'NONCE_SALT',       '^,VxL|;NzhkxX/f:j3gM-R/wz5m@E`;IC=9w;sncX]A)KOC{:$p}k{HA9860zoea' );


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

 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/

 */

define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */




define( 'FS_METHOD', 'direct' );
/**
 * The WP_SITEURL and WP_HOME options are configured to access from any hostname or IP address.
 * If you want to access only from an specific domain, you can modify them. For example:
 *  define('WP_HOME','http://example.com');
 *  define('WP_SITEURL','http://example.com');
 *
 */
if ( defined( 'WP_CLI' ) ) {
	$_SERVER['HTTP_HOST'] = '127.0.0.1';
}

define( 'WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] . '/' );
define( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/' );
define( 'WP_AUTO_UPDATE_CORE', false );
/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */

if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', __DIR__ . '/' );

}


/** Sets up WordPress vars and included files. */

require_once ABSPATH . 'wp-settings.php';

/**
 * Disable pingback.ping xmlrpc method to prevent WordPress from participating in DDoS attacks
 * More info at: https://docs.bitnami.com/general/apps/wordpress/troubleshooting/xmlrpc-and-pingback/
 */
if ( !defined( 'WP_CLI' ) ) {
	// remove x-pingback HTTP header
	add_filter("wp_headers", function($headers) {
		unset($headers["X-Pingback"]);
		return $headers;
	});
	// disable pingbacks
	add_filter( "xmlrpc_methods", function( $methods ) {
		unset( $methods["pingback.ping"] );
		return $methods;
	});
}
