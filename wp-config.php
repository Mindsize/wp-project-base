<?php
/**
 * Support HTTPS behind proxies
 */
if( isset( $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] ) && $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] == 'https' ) {
	$_SERVER[ 'HTTPS' ] = 'on';
}

/**
 * Composer Vendor Autoload
 */
require_once dirname( __FILE__ ) . '/wp-content/vendor/autoload.php';

/**
 * Load Environment Configuration
 */
try {
	$dotenv = new Dotenv\Dotenv( __DIR__ );
	$dotenv->load();

	$dotenv->required( array( 'WP_HOME', 'WP_DIR' ) );
	$dotenv->required( array( 'DB_NAME', 'DB_USER', 'DB_PASSWORD' ) );
	$dotenv->required( array( 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT' ) );
} catch( Exception $e ) {
	// if loading the .env file fails, add special handling here
}

/**
 * Load RDS environment configs if we are hosted on RDS and the data is already available.
 */
if( empty( getenv( 'DB_HOST' ) ) && ! empty( getenv( 'RDS_HOSTNAME' ) ) ) {
	putenv( 'DB_HOST=' . getenv( 'RDS_HOSTNAME' ) );
}

if( empty( getenv( 'DB_NAME' ) ) && ! empty( getenv( 'RDS_DB_NAME' ) ) ) {
	putenv( 'DB_NAME=' . getenv( 'RDS_DB_NAME' ) );
}

if( empty( getenv( 'DB_USER' ) ) && ! empty( getenv( 'RDS_USERNAME' ) ) ) {
	putenv( 'DB_USER=' . getenv( 'RDS_USERNAME' ) );
}

if( empty( getenv( 'DB_PASSWORD' ) ) && ! empty( getenv( 'RDS_PASSWORD' ) ) ) {
	putenv( 'DB_PASSWORD=' . getenv( 'RDS_PASSWORD' ) );
}

/**
 * Site Configs
 */

define( 'WP_HOME',         getenv( 'WP_HOME' ) );
define( 'WP_SITEURL',      getenv( 'WP_HOME' ) . '/' . getenv( 'WP_DIR' ) );

define( 'WP_CONTENT_URL',  getenv( 'WP_HOME' ) . '/wp-content' );
define( 'WP_CONTENT_DIR',  dirname( __FILE__ ) . '/wp-content' );

/**
 * MySQL
 */

define('DB_NAME',          getenv( 'DB_NAME' ) );
define('DB_USER',          getenv( 'DB_USER' ) );
define('DB_PASSWORD',      getenv( 'DB_PASSWORD' ) );
define('DB_COLLATE',       getenv( 'DB_COLLATE' ) );

define('DB_HOST',          ! empty( getenv( 'DB_HOST' ) ) ? getenv( 'DB_HOST' ) : 'localhost' );
define('DB_CHARSET',       ! empty( getenv( 'DB_CHARSET' ) ) ? getenv( 'DB_CHARSET' ) : 'utf8' );

$table_prefix  =           ! empty( getenv( 'DB_TABLE_PREFIX' ) ) ? getenv( 'DB_TABLE_PREFIX' ) : 'wp_';

/**
 * Authentication Keys and Salts
 */

define('AUTH_KEY',         getenv( 'AUTH_KEY' ) );
define('SECURE_AUTH_KEY',  getenv( 'SECURE_AUTH_KEY' ) );
define('LOGGED_IN_KEY',    getenv( 'LOGGED_IN_KEY' ) );
define('NONCE_KEY',        getenv( 'NONCE_KEY' ) );
define('AUTH_SALT',        getenv( 'AUTH_SALT' ) );
define('SECURE_AUTH_SALT', getenv( 'SECURE_AUTH_SALT' ) );
define('LOGGED_IN_SALT',   getenv( 'LOGGED_IN_SALT' ) );
define('NONCE_SALT',       getenv( 'NONCE_SALT' ) );


/**
 * Developer Flags
 */

define( 'WP_DEBUG',           filter_var( getenv('WP_DEBUG'), FILTER_VALIDATE_BOOLEAN ) );
define( 'WP_DEBUG_DISPLAY',   filter_var( getenv('WP_DEBUG_DISPLAY'), FILTER_VALIDATE_BOOLEAN ) );
define( 'WP_DEBUG_LOG',       filter_var( getenv('WP_DEBUG_LOG'), FILTER_VALIDATE_BOOLEAN ) );

define( 'DISALLOW_FILE_EDIT', true ); // If you are using this as a base, you should know better.

/**
 * Constants to define only if defined from our environment
 */

foreach( array(
     /**
      * Redis
      */
     'WP_REDIS_DISABLED',
     'WP_REDIS_CLIENT',
     'WP_REDIS_SCHEME',
     'WP_REDIS_HOST',
     'WP_REDIS_PORT',
     'WP_REDIS_PATH',
     'WP_REDIS_DATABASE',
     'WP_REDIS_SERVERS',
     'WP_REDIS_CLUSTER',
     'WP_REDIS_MAXTTL',
     'WP_REDIS_GLOBAL_GROUPS',
     'WP_REDIS_IGNORED_GROUPS',
     'WP_CACHE_KEY_SALT',

     /**
      * S3 Uploads
      */
     'S3_UPLOADS_BUCKET',
     'S3_UPLOADS_KEY',
     'S3_UPLOADS_SECRET',
     'S3_UPLOADS_REGION',
     'S3_UPLOADS_HTTP_CACHE_CONTROL',
     'S3_UPLOADS_HTTP_EXPIRES',
) as $config ) {
	if( ! empty( getenv( $config ) ) ) {
		define( $config, getenv( $config ) );
	}
}

/**
 * WordPress Setup
 */

if ( ! defined('ABSPATH') ) {
    define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';