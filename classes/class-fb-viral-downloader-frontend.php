<?php
class FB_Viral_Downloader_Frontend {

	public function __construct() {
		//enqueue scripts
		add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));
		//enqueue styles
		add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'));

		add_action( 'fb_viral_downloader_frontend_hook', array(&$this, 'fb_viral_downloader_frontend_function'), 10, 2 );

	}

	function frontend_scripts() {
		global $FB_Viral_Downloader;
		$frontend_script_path = $FB_Viral_Downloader->plugin_url . 'assets/frontend/js/';
		$frontend_script_path = str_replace( array( 'http:', 'https:' ), '', $frontend_script_path );
		$pluginURL = str_replace( array( 'http:', 'https:' ), '', $FB_Viral_Downloader->plugin_url );
		$suffix 				= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		// Enqueue your frontend javascript from here

		wp_enqueue_script( 'viraldownloader-js', $frontend_script_path . 'script.js', array( 'jquery' ), $FB_Viral_Downloader->version, true );
		wp_localize_script( 'viraldownloader-js', 'viraldownloader_data', array('ajax_url' => admin_url( 'admin-ajax.php', 'relative' ), 'fb_client_id' => get_option('fbSocialClientId')) );
	}

	function frontend_styles() {
		global $FB_Viral_Downloader;
		$frontend_style_path = $FB_Viral_Downloader->plugin_url . 'assets/frontend/css/';
		$frontend_style_path = str_replace( array( 'http:', 'https:' ), '', $frontend_style_path );
		$suffix 			 = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Enqueue your frontend stylesheet from here
		wp_enqueue_style( "style", "//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css");
		wp_enqueue_style( "frontend", $frontend_style_path.'frontend.css');
	}
	
	function dc_fb_viral_downloader_frontend_function() {
	  // Do your frontend work here
	  
	}

}
