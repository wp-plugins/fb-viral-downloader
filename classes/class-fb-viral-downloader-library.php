<?php
class FB_Viral_Downloader_Library {
  
  public $lib_path;
  
  public $lib_url;
  
  public $php_lib_path;
  
  public $php_lib_url;
  
  public $jquery_lib_path;
  
  public $jquery_lib_url;

	public function __construct() {
	  global $FB_Viral_Downloader;
	  
	  $this->lib_path = $FB_Viral_Downloader->plugin_path . 'lib/';

    $this->lib_url = $FB_Viral_Downloader->plugin_url . 'lib/';
    
    $this->php_lib_path = $this->lib_path . 'php/';
    
    $this->php_lib_url = $this->lib_url . 'php/';
    
    $this->jquery_lib_path = $this->lib_path . 'jquery/';
    
    $this->jquery_lib_url = $this->lib_url . 'jquery/';
	}
	
	/**
	 * PHP WP fields Library
	 */
	public function load_wp_fields() {
	  global $FB_Viral_Downloader;
	  if ( ! class_exists( 'DC_WP_Fields' ) )
	    require_once ($this->php_lib_path . 'class-dc-wp-fields.php');
	  $DC_WP_Fields = new DC_WP_Fields(); 
	  return $DC_WP_Fields;
	}
	
	/**
	 * Jquery qTip library
	 */
	public function load_qtip_lib() {
	  global $FB_Viral_Downloader;
	  wp_enqueue_script('qtip_js', $this->jquery_lib_url . 'qtip/qtip.js', array('jquery'), $FB_Viral_Downloader->version, true);
		wp_enqueue_style('qtip_css',  $this->jquery_lib_url . 'qtip/qtip.css', array(), $FB_Viral_Downloader->version);
	}
	
	/**
	 * WP Media library
	 */
	public function load_upload_lib() {
	  global $FB_Viral_Downloader;
	  wp_enqueue_media();
	  wp_enqueue_script('upload_js', $this->jquery_lib_url . 'upload/media-upload.js', array('jquery'), $FB_Viral_Downloader->version, true);
	  wp_enqueue_style('upload_css',  $this->jquery_lib_url . 'upload/media-upload.css', array(), $FB_Viral_Downloader->version);
	}
	
	/**
	 * WP ColorPicker library
	 */
	public function load_colorpicker_lib() {
	  global $FB_Viral_Downloader;
	  wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'colorpicker_init', $this->jquery_lib_url . 'colorpicker/colorpicker.js', array( 'jquery', 'wp-color-picker' ), $FB_Viral_Downloader->version, true );
    wp_enqueue_style( 'wp-color-picker' );
	}
	
	/**
	 * WP DatePicker library
	 */
	public function load_datepicker_lib() {
	  global $FB_Viral_Downloader;
	  wp_enqueue_script('jquery-ui-datepicker');
	  wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	}
}
