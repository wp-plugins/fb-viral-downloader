<?php
class FB_Viral_Downloader_Admin {
  
  public $settings;

	public function __construct() {
		//admin script and style
		add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_script'));
		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_buttons', array( &$this, 'filter_mce_buttons') );
			add_filter( 'mce_external_plugins', array( &$this, 'filter_mce_external_plugins' ) );
		}
		add_action('fb_viral_downloader_dualcube_admin_footer', array(&$this, 'dualcube_admin_footer_for_fb_viral_downloader'));
		$this->load_class('settings');
		$this->settings = new FB_Viral_Downloader_Settings();
	}

	function load_class($class_name = '') {
	  global $FB_Viral_Downloader;
		if ('' != $class_name) {
			require_once ($FB_Viral_Downloader->plugin_path . '/admin/class-' . esc_attr($FB_Viral_Downloader->token) . '-' . esc_attr($class_name) . '.php');
		} // End If Statement
	}// End load_class()
	
	function dualcube_admin_footer_for_fb_viral_downloader() {
    global $FB_Viral_Downloader;
    ?>
    <div style="clear: both"></div>
    <div id="dc_admin_footer">
      <?php _e('Powered by', $FB_Viral_Downloader->text_domain); ?> <a href="http://dualcube.com" target="_blank"><img src="<?php echo $FB_Viral_Downloader->plugin_url.'/assets/images/dualcube.png'; ?>"></a><?php _e('Dualcube', $FB_Viral_Downloader->text_domain); ?> &copy; <?php echo date('Y');?>
    </div>
    <?php
	}

	/**
	 * Admin Scripts
	 */

	public function enqueue_admin_script() {
		global $FB_Viral_Downloader;
		$screen = get_current_screen();
		// Enqueue admin script and stylesheet from here
		if (in_array( $screen->id, array( 'toplevel_page_fb-viral-downloader-setting-admin', 'downloadables_page_downloadables_logs' ))) :   
		  $FB_Viral_Downloader->library->load_qtip_lib();
		  $FB_Viral_Downloader->library->load_upload_lib();
		  $FB_Viral_Downloader->library->load_colorpicker_lib();
		  $FB_Viral_Downloader->library->load_datepicker_lib();
		  $screen = get_current_screen();
		  wp_enqueue_style( 'fontstyle', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
		  wp_enqueue_style('admin_css',  $FB_Viral_Downloader->plugin_url.'assets/admin/css/admin.css', array(), $FB_Viral_Downloader->version);
		  wp_enqueue_style('bootstrap-css',  'http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css', array(), $FB_Viral_Downloader->version);
		  wp_enqueue_style('datatable_css',  'http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css', array(), $FB_Viral_Downloader->version);
		  
		  wp_enqueue_script('jquery-dataTables', 'http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js', array('jquery'), $FB_Viral_Downloader->version, true);
		  wp_enqueue_script('bootstrap', 'http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js', array('jquery'), $FB_Viral_Downloader->version, true);
		  wp_enqueue_script('admin_js', $FB_Viral_Downloader->plugin_url.'assets/admin/js/admin.js', array('jquery'), $FB_Viral_Downloader->version, true);

	  endif;
	  if (in_array( $screen->id, array( 'downloadables' ))) :
			wp_enqueue_media();
			wp_enqueue_script('fileupload_js', $FB_Viral_Downloader->plugin_url.'assets/admin/js/fileupload.js', array('jquery', 'media-upload', 'thickbox'), $FB_Viral_Downloader->version, true);
			wp_localize_script( 'fileupload_js', 'viraldownloader_data', array('ajax_url' => admin_url( 'admin-ajax.php', 'relative' )) );
		endif;
		wp_enqueue_script('insert_js', $FB_Viral_Downloader->plugin_url.'assets/admin/js/insert.js', array('jquery'), $FB_Viral_Downloader->version, true);
		wp_localize_script( 'insert_js', 'viraldownloader_data', array('ajax_url' => admin_url( 'admin-ajax.php', 'relative' )) );
		wp_enqueue_style('insert_css',  $FB_Viral_Downloader->plugin_url.'assets/admin/css/insert.css', array(), $FB_Viral_Downloader->version);
	}
	public function filter_mce_external_plugins( $plugins ) {
		if( get_bloginfo('version') < 3.9 ) {
			$plugins['ViralDownloaderPlugin'] = plugins_url('assets/admin/js/editor_plugin.js',dirname(__FILE__));
    } else {
      $plugins['ViralDownloaderPlugin'] = plugins_url('assets/admin/js/editor_plugin_4.js',dirname(__FILE__));
    }
    return $plugins;
	}

	public function filter_mce_buttons( $buttons ) {
		array_push( $buttons, '|', 'vd_button');
		return $buttons;
	}
}