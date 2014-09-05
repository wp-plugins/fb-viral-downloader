<?php
/*
Plugin Name: FB Viral Downloader
Plugin URI: http://dualcube.com/
Description: This plugin enables viral marketing of your content via Facebook sharing for each and every download from your website. It is an effective tool to increase your viewership.
Author: DualCube
Version: 1.4.1
Author URI: http://dualcube.com/
*/

require_once 'config.php';
if(!defined('ABSPATH')) exit; // Exit if accessed directly
if(!defined('DC_FB_VIRAL_DOWNLOADER_PLUGIN_TOKEN')) exit;
if(!defined('DC_FB_VIRAL_DOWNLOADER_TEXT_DOMAIN')) exit;

if(!class_exists('DC_FB_Viral_Downloader')) {

	class DC_FB_Viral_Downloader {

		public $plugin_url;

		public $plugin_path;

		public $version;

		public $token;

		public $text_domain;

		public function __construct() {

			$this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
			$this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
			$this->token = DC_FB_VIRAL_DOWNLOADER_PLUGIN_TOKEN;
			$this->text_domain = DC_FB_VIRAL_DOWNLOADER_TEXT_DOMAIN;
		  $this->version = DC_FB_VIRAL_DOWNLOADER_PLUGIN_VERSION;

			add_action('init', array( &$this, 'init'));
			add_action( 'admin_init', array( &$this, 'admin_init' ));
			add_action( 'admin_footer', array( &$this, 'footer' ));
		}

		public function init() {
			$this->register_post_types();
			add_action('add_meta_boxes', array( &$this, 'meta_boxes' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ) );
			add_action( 'save_post', array( &$this, 'save_metabox_data' ) );
			add_action('admin_menu', array( &$this, 'add_settings_menu'));

			//frontend
			if ( ! is_admin() || defined('DOING_AJAX') ) {
				$this->shortcode_init();
				add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
				add_action( 'wp_ajax_viraldownloader_share_complete', array( &$this, 'viraldownloader_share_complete_callback' ) );
				add_action( 'wp_ajax_nopriv_viraldownloader_share_complete', array( &$this, 'viraldownloader_share_complete_callback' ) );
			}

		}

		public function admin_init() {
			//add tinymce button
			if ( 'true' == get_user_option( 'rich_editing' ) ) {
  			add_filter( 'mce_buttons',          array( &$this, 'filter_mce_buttons') );
  			add_filter( 'mce_external_plugins', array( &$this, 'filter_mce_external_plugins' ) );
      }
			add_action( 'admin_notices', array( &$this, 'show_admin_messages' ) );
			add_action( 'wp_ajax_add_new_downloadable', array( &$this, 'add_new_downloadable_callback' ) );
		}

		public function register_post_types() {
			register_post_type( "downloadables",
				array(
					'labels' => array(
							'name' 					=> __( 'Downloadables', $this->text_domain ),
							'singular_name' 		=> __( 'Downloadable', $this->text_domain ),
							'menu_name'				=> _x( 'Downloadables', 'Admin menu name', $this->text_domain ),
							'add_new' 				=> __( 'Add Downloadable', $this->text_domain ),
							'add_new_item' 			=> __( 'Add New Downloadable', $this->text_domain ),
							'edit' 					=> __( 'Edit', $this->text_domain ),
							'edit_item' 			=> __( 'Edit Downloadable', $this->text_domain ),
							'new_item' 				=> __( 'New Downloadable', $this->text_domain ),
							'view' 					=> __( 'View Downloadable', $this->text_domain ),
							'view_item' 			=> __( 'View Downloadable', $this->text_domain ),
							'search_items' 			=> __( 'Search Downloadables', $this->text_domain ),
							'not_found' 			=> __( 'No Downloadables found', $this->text_domain ),
							'not_found_in_trash' 	=> __( 'No Downloadables found in trash', $this->text_domain ),
							'parent' 				=> __( 'Parent Downloadable', $this->text_domain )
						),
					'description' 			=> __( 'This is where you can add new products to your store.', $this->text_domain ),
					'public' 				=> false,
					'show_in_menu'	=> true,
					'show_ui' 				=> true,
					'publicly_queryable' 	=> false,
					'exclude_from_search' 	=> true,
					'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
					'supports' 				=> array( 'title', 'thumbnail'),
					'show_in_nav_menus' 	=> true,
					'menu_icon' => 'dashicons-download'
				)
			);
		}

		public function meta_boxes() {
			add_meta_box('fb_viral_downloader_url', __( 'Share Url', $this->text_domain ),	array(&$this, 'url_metabox'), 'downloadables');
			add_meta_box('fb_viral_downloader_caption', __( 'Share Caption', $this->text_domain ),	array(&$this, 'caption_metabox'), 'downloadables');
			add_meta_box('fb_viral_downloader_description', __( 'Share Description', $this->text_domain ),	array(&$this, 'description_metabox'), 'downloadables');
			add_meta_box('fb_viral_downloader_file', __( 'File', $this->text_domain ),	array(&$this, 'file_metabox'), 'downloadables');
			add_meta_box('fb_viral_downloader_stats', __( 'Statistics', $this->text_domain ),	array(&$this, 'stats_metabox'), 'downloadables');
			add_meta_box('fb_viral_downloader_shortcode', __( 'Shortcode', $this->text_domain ),	array(&$this, 'shortcode_metabox'), 'downloadables', 'side');
		}

		public function viraldownloader_share_complete_callback() {
			if(isset($_POST['fb_post_id']) && isset($_POST['download_id'])) {
				if(!$viraldownloader_share_data = get_post_meta((int)$_POST['download_id'], 'viraldownloader_share_data', true)) $viraldownloader_share_data = array();
				$viraldownloader_share_data[] = $_POST['fb_post_id'];
				update_post_meta((int)$_POST['download_id'], 'viraldownloader_share_data', $viraldownloader_share_data);
				echo get_post_meta((int)$_POST['download_id'], '_share_file', true);
			}
			die;
		}

		public function url_metabox() {
			global $post;
			echo '<input type="text" name="share_url" id="share_url" style="width: 98%" value="' . get_post_meta($post->ID, '_share_url', true) . '" />';
			echo '<p class="description">This url will help bring traffic to your website. The url may be a link to your ecommerce page, product campaign page, social campaign page pr any other page you want to be extensively viewed. Give your business or campaign a boost! </p>';
		}

		public function caption_metabox() {
			global $post;
			echo '<textarea name="share_caption" style="width: 98%">' . get_post_meta($post->ID, '_share_caption', true) . '</textarea>';
			echo '<p class="description">Add a Cool and Attractive caption to your url. This catchy caption will compel Facebook users to click on your url. Take, for example, ‘Last 5 Days of Summer Sale!’.</p>';
		}

		public function description_metabox() {
			global $post;
			echo '<textarea name="share_description" style="width: 98%">' . get_post_meta($post->ID, '_share_description', true) . '</textarea>';
			echo '<p class="description">This field allows you to share a smart and brief description of your products or campaign on a social media platform. An attractive description will make Facebook users curious to know what is in your url. This will increase your viewership.</p>';
		}

		public function file_metabox() {
			global $post;
			echo '<button id="upload_file" class="button-secondary">Upload File</button>&nbsp;or enter url&nbsp;';
			echo '<input type="text" name="share_file" id="share_file" style="width: 50%" value="' . get_post_meta($post->ID, '_share_file', true) . '" />';
			echo '<p class="description">Add the file you want as downloadable content from your PC. You may also provide a url. The file may be in any format of your choice.</p>';
		}

		public function shortcode_metabox() {
			global $post;
			echo '<p><code>[viraldownloader id='. $post->ID .']</code></p>';
		}

		public function stats_metabox() {
      global $post;
      $total = 0;
      if( $viraldownloader_share_data = get_post_meta( $post->ID, 'viraldownloader_share_data', true ) ) {
        $total = count($viraldownloader_share_data);
      }
      echo '<p><label>Total Share : </label>' . $total . '</p>';
      echo '<p class="description">See how many times your file is shared over Facebook. (If a single user downloads more than once the count will increase for each share).</p>';
      if( !empty( $viraldownloader_share_data ) ) {
        echo '<h4>Recently shared by</h4>';
        echo '<ul>';
        $recent_share_data = array_slice( array_reverse( $viraldownloader_share_data ), 0, 10 );
        foreach( $recent_share_data as $data ) {
          if( is_array( $data ) && isset( $data['post_id'] ) && ! empty( $data['post_id'] ) ) {
            $pieces = explode( "_", $data['post_id'] );
            $fb_id = $pieces[0];
            echo '<li style="float:left; margin-right: 5px;"><a href="https://www.facebook.com/'.$fb_id.'"><img src="http://graph.facebook.com/'.$fb_id.'/picture?type=square"/></a></li>';
          }
        }
        echo '</ul><div style="clear:both;"></div>';
      }
		}

		public function save_metabox_data( $post_id ) {
			if( isset( $_POST['post_type'] ) && $_POST['post_type'] != 'downloadables' ) return;

			$metas = array('share_url', 'share_caption', 'share_description', 'share_file');
			foreach( $metas as $meta ) {
				if( !empty( $_POST[$meta] ) ) update_post_meta( $post_id, '_' . $meta, $_POST[$meta] );
			}
		}

		public function admin_scripts() {
			$screen = get_current_screen();
			if (in_array( $screen->id, array( 'downloadables' ))) :
				wp_enqueue_media();
				wp_enqueue_script('fileupload-js', $this->plugin_url . '/assets/js/fileupload.js', array('jquery', 'media-upload', 'thickbox'), $this->version, true);
			endif;
			wp_enqueue_script('viraldownloader-insert-js', $this->plugin_url . '/assets/js/insert.js', array('jquery'), $this->version, true);
		}

		public function add_settings_menu() {
			add_options_page('FB Viral Downloader', 'FB Viral Downloader', 'manage_options', 'viraldownloader-options', array(&$this, 'plugin_option_page'));
		}

		public function plugin_option_page() {
			?>
			<div class="wrap">
				<h2>FB Viral Downloader Options</h2>
			<?php
			if(isset($_POST['submit']) && $_POST['submit'] == 'Save') {
        update_option('fbSocialClientId', $_POST['fbSocialClientId']);
        echo '<div class="updated below-h2"><p>' . __( 'Settings Updated', $this->text_domain ) . '</p></div>';
      }
			?>
			<style type="text/css">
				fieldset{
					margin:20px 0;
					border:1px solid #cecece;
					padding:15px;
				}
			</style>
				<form method="post">
					<div>
						<fieldset>
							<legend>Facebook Application ID setup</legend>
							<p>Please provide here the Facebook Application Id. If you did not yet register any application for this site, please register <a href="https://developers.facebook.com/" target="_blank">here</a>. You will get the detailed step by step guide <a href="http://help.yahoo.com/kb/index?page=content&y=PROD_YSB_MS&locale=en_US&id=SLN18861&actp=lorax&pir=ZA4iCDtibUkE5z7HYNeyKDPlxPV2mMiMJUXf5Bd3" target="_blank">here</a>.</p>
								Facebook Application ID: <input type="textbox" name="fbSocialClientId" id="fbSocialClientId" value="<?php echo(get_option('fbSocialClientId')) ?>" />
						</fieldset>
						<input type="submit" name="submit" class="button-primary" value="Save"></input>
					</div>
				</form>
			</div>
			<?php
		}

		public function shortcode_init() {
			add_shortcode( 'viraldownloader', array(&$this, 'output_viraldownloader_shortcode') );
		}

		public function output_viraldownloader_shortcode( $atts ) {
			extract( shortcode_atts( array('id' => '', 'text' => 'download'), $atts ) );
			if($id == '') return;
			$share_count = ( $data = get_post_meta( $id, 'viraldownloader_share_data', true ) ) ? count( $data ) : 0;
			$link_html = '';
			$link_html .= '<a href="#" class="viraldownloader_url viraldownloader' . $id . '"';
			$link_html .= ' data-caption="' . get_post_meta($id, '_share_caption', true) . '"';
			$link_html .= ' data-description="' . get_post_meta($id, '_share_description', true) . '"';
			$link_html .= ' data-url="' . get_post_meta($id, '_share_url', true) . '"';
			$link_html .= ' data-image="' . wp_get_attachment_url(get_post_thumbnail_id($id)) . '"';
			$link_html .= ' data-title="' . get_the_title($id) . '"';
			$link_html .= ' data-id="' . $id . '"';
			$link_html .= '>';
			$link_html .= apply_filters( 'fb_viral_downloader_html', $text, $downloadable_id, $share_count );
			$link_html .= '</a>';

			return $link_html;
		}

		public function frontend_scripts() {
			$frontend_script_path 	= $this->plugin_url . '/assets/js/frontend/';
			wp_enqueue_script( 'viraldownloader-js', $frontend_script_path . 'script.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( 'viraldownloader-js', 'viraldownloader_data', array('ajax_url' => admin_url( 'admin-ajax.php', 'relative' ), 'fb_client_id' => get_option('fbSocialClientId')) );
		}

		public function filter_mce_buttons( $buttons ) {
			array_push( $buttons, '|', 'vd_button');
			return $buttons;
		}

		public function filter_mce_external_plugins( $plugins ) {
		  if( get_bloginfo('version') < 3.9 ) {
		    $plugins['ViralDownloaderPlugin'] = $this->plugin_url . '/assets/js/tinymce/editor_plugin.js';
      } else {
        $plugins['ViralDownloaderPlugin'] = $this->plugin_url . '/assets/js/tinymce/editor_plugin_4.js';
      }
			return $plugins;
		}

		public function footer() {
			echo '<div id="vd-dialog" style="display: none;">';
			$this->output_vd_shortcode_selector();
			echo '</div>';
		}

		public function output_vd_shortcode_selector() {
			$posts = get_posts(array('posts_per_page' => -1, 'post_type' => 'downloadables', 'fields' => 'ids'));
			if(empty($posts)) {
				$posts = array();
			}
			?>
			<style>
				#TB_window {overflow: auto;}
				#TB_ajaxContent {overflow: visible; width: 94% !important;}
				select#viraldownloaderDownlaodableSelect {padding: 2px;}
			</style>
			<table class="form-table" id="fb_viral_downloader_container">
				<tr>
					<th>Select a downloadable</th>
					<td>
						<select id="viraldownloaderDownlaodableSelect" >
							<?php foreach($posts as $post_id) { ?>
								<option value="<?=$post_id ?>"><?php echo get_the_title($post_id); ?></option>
							<?php } ?>
							<option value="add-new">--Add New--</option>
						</select>
					</td>
				</tr>
				<tr class="hide add-new">
					<th>Title</th>
					<td><input class="title" type="text" /></td>
				</tr>
				<tr class="hide add-new">
					<th>Url</th>
					<td><input class="url" type="text" /></td>
				</tr>
				<tr class="hide add-new">
					<th>Caption</th>
					<td><input class="caption" type="text" /></td>
				</tr>
				<tr class="hide add-new">
					<th>Description</th>
					<td><input class="description" type="text" /></td>
				</tr>
				<tr class="hide add-new">
					<th>File</th>
					<td>
						<button class="button-secondary upload file-upload">Upload</button>
						<span>Or enter link</span>
						<input class="file" type="text" />
					</td>
				</tr>
				<tr class="hide add-new">
					<th>Image</th>
					<td>
						<button class="button-secondary upload image-upload">Upload</button>
						<img src="" class="image-preview" width="30" height="30" />
						<input class="image" type="hidden" />
					</td>
				</tr>
				<tr>
					<th>Enter link Text</th>
					<td><input type="text" id="viraldownloaderLinkText" /></td>
				</tr>
				<tr>
					<th></th>
					<td><button class="button-primary" id="viraldownloaderInsertButton">Insert Link</button></td>
				</tr>
			</table>
			<?php
		}

		public function add_new_downloadable_callback() {
			if(isset($_POST['title']) && isset($_POST['data'])) {
				$post_id = wp_insert_post(array('post_title' => $_POST['title'], 'post_status' => 'publish', 'post_type' => 'downloadables'));
				if(!is_wp_error($post_id)) {
					if(!empty($_POST['featured_image']))
						set_post_thumbnail($post_id, absint($_POST['featured_image']));
					$metas = array('share_url', 'share_caption', 'share_description', 'share_file');
					foreach($metas as $meta) {
						if(!empty($_POST['data'][$meta])) update_post_meta($post_id, '_' . $meta, $_POST['data'][$meta]);
					}
					echo $post_id;
				}
			}
			die;
		}

		public function show_admin_messages() {
			if(!get_option('fbSocialClientId')) {
				echo '<div class="error"><p><strong>FB Viral Downloader issue:</strong> Please <a href="options-general.php?page=viraldownloader-options">update Facebook settings</a> to activate Viral download. Configure the Facebook settings <a href="options-general.php?page=viraldownloader-options">here</a>.</p></div>';
			}
		}

		/**
     * Install upon activation.
     *
     * @access public
     * @return void
     */
    function activate_dc_fb_viral_downloader() {
      global $DC_Fb_Viral_Downloader;

      update_option( 'dc_fb_viral_downloader_installed', 1 );
    }

    /**
     * UnInstall upon deactivation.
     *
     * @access public
     * @return void
     */
    function deactivate_dc_fb_viral_downloader() {
      global $DC_Fb_Viral_Downloader;
      delete_option( 'dc_fb_viral_downloader_installed' );
    }

	}

	global $DC_Fb_Viral_Downloader;
	$DC_Fb_Viral_Downloader = new DC_FB_Viral_Downloader( );
	$GLOBALS['DC_Fb_Viral_Downloader'] = $DC_Fb_Viral_Downloader;

	// Activation Hooks
	register_activation_hook( __FILE__, array('DC_FB_Viral_Downloader', 'activate_dc_fb_viral_downloader') );
	register_activation_hook( __FILE__, 'flush_rewrite_rules' );

	// Deactivation Hooks
	register_deactivation_hook( __FILE__, array('DC_FB_Viral_Downloader', 'deactivate_dc_fb_viral_downloader') );
}
