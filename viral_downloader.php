<?php
/*
Plugin Name: Viral Downloader
Plugin URI: http://dualcube.com
Description: Your plugin description here
Author: Dualcube, Arim Ghosh, Sayan Saha
Version: 2.0.0
Author URI: http://dualcube.com
*/

// if ( ! class_exists( 'WC_Dependencies' ) )
// 	require_once 'includes/class-dc-dependencies.php';
require_once 'includes/fb-viral-downloader-core-functions.php';
//require_once 'includes/fb-viral-downloader-posts.php';
require_once 'config.php';
if(!defined('ABSPATH')) exit; // Exit if accessed directly
if(!defined('FB_VIRAL_DOWNLOADER_PLUGIN_TOKEN')) exit;
if(!defined('FB_VIRAL_DOWNLOADER_TEXT_DOMAIN')) exit;

if(!class_exists('FB_Viral_Downloader')) {
	require_once( 'classes/class-fb-viral-downloader.php' );

	global $FB_Viral_Downloader;
	$FB_Viral_Downloader = new FB_Viral_Downloader( __FILE__ );
	$GLOBALS['FB_Viral_Downloader'] = $FB_Viral_Downloader;
	register_activation_hook(__FILE__,'create_database');
}
function create_database(){
	global $wpdb;
  $table_name = $wpdb->prefix . "download_data";
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  $create_sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
    id bigint(9) NOT NULL AUTO_INCREMENT,
    fileid bigint(9),
    file varchar(300),
    userid varchar(100),
    useremail varchar(50),
    members bigint(9),
    redirect bigint(9),
    featured bigint(9),
    facebook bigint(9),
    google bigint(9),
    twitter bigint(9),
    ip varchar(20),
    browser_name varchar(100),
    browser_ver varchar(100),
    os varchar(100),
    time datetime,
    PRIMARY KEY ( id ));";
  dbDelta( $create_sql ); 
}
?>
