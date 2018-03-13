<?php
/**
 * Development environment config settings
 *
 * Enter any WordPress config settings that are specific to this environment
 * in this file.
 *
 */

$db_name = getenv('DBNAME');
$db_user = getenv('DBUSER');
$db_pass = getenv('DBPASS');
$db_host = getenv('DBHOST');


// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', $db_name);
/** MySQL database username */
define('DB_USER', $db_user);
/** MySQL database password */
define('DB_PASSWORD', $db_pass);
/** MySQL hostname */
define('DB_HOST', $db_host);


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);
