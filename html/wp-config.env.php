<?php
/**
 * Setup environments
 * 
 * Set environment based on the current server hostname, this is stored
 * in the $hostname variable
 * 
 * You can define the current environment via: 
 *     define('WP_ENV', 'production');
 * 
 */


// Set environment based on hostname
switch ($hostname) {
  case 'www.hdev.online':
  case '54.86.248.194':
    define('WP_ENV', 'production');
		$_SERVER['HTTPS'] = "off";
    break;
    
    
  default:
    define('WP_ENV', 'local');
		$_SERVER['HTTPS'] = "off";
		break;
}

?>