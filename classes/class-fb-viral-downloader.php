<?php
class FB_Viral_Downloader {

	public $plugin_url;

	public $plugin_path;

	public $version;

	public $token;
	
	public $text_domain;
	
	public $library;

	public $shortcode;

	public $admin;

	public $frontend;

	public $template;

	public $ajax;

	private $file;
	
	public $settings;
	
	public $dc_wp_fields;

	public function __construct($file) {
		
		$this->file = $file;
		$this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
		$this->plugin_path = trailingslashit(dirname($file));
		$this->token = FB_VIRAL_DOWNLOADER_PLUGIN_TOKEN;
		$this->text_domain = FB_VIRAL_DOWNLOADER_TEXT_DOMAIN;
		$this->version = FB_VIRAL_DOWNLOADER_PLUGIN_VERSION;
		
		add_action('init', array(&$this, 'init'), 0);
		
		add_action( 'admin_footer', array( &$this, 'footer' ));
		add_action( 'manage_downloadables_posts_custom_column', array( $this, 'post_columns_values' ), 2 );
		add_filter( 'manage_edit-downloadables_columns', array( &$this,'post_columns' ));
		add_filter('manage_edit-downloadables_sortable_columns', array( &$this,'post_columns' ));
	}
	
	/**
	 * initilize plugin on WP init
	 */
	function init() {
		
		// Init Text Domain
		$this->load_plugin_textdomain();
		
		// Init library
		$this->load_class('library');
		$this->library = new FB_Viral_Downloader_Library();
		
		// Init ajax
		if(defined('DOING_AJAX')) {
      $this->load_class('ajax');
      $this->ajax = new  FB_Viral_Downloader_Ajax();
    }

		if (is_admin()) {
			$this->load_class('admin');
			$this->admin = new FB_Viral_Downloader_Admin();
		}

		if (!is_admin() || defined('DOING_AJAX')) {
			$this->load_class('frontend');
			$this->frontend = new FB_Viral_Downloader_Frontend();
			
			// init shortcode
      $this->load_class('shortcode');
      $this->shortcode = new FB_Viral_Downloader_Shortcode();
  
      // init templates
      $this->load_class('template');
      $this->template = new FB_Viral_Downloader_Template();
		}
		$this->downloadables();
		add_action('add_meta_boxes', array( &$this, 'meta_boxes' ), 2);
		add_action( 'save_post', array( &$this, 'save_metabox_data' ), 3 );
		$this->downloadables_taxonomies();

		// DC Wp Fields
		$this->dc_wp_fields = $this->library->load_wp_fields();
	}
	
	/**
   * Load Localisation files.
   *
   * Note: the first-loaded translation file overrides any following ones if the same translation is present
   *
   * @access public
   * @return void
   */
  public function load_plugin_textdomain() {
    $locale = apply_filters( 'plugin_locale', get_locale(), $this->token );

    load_textdomain( $this->text_domain, WP_LANG_DIR . "/fb-viral-downloader/fb-viral-downloader-$locale.mo" );
    load_textdomain( $this->text_domain, $this->plugin_path . "/languages/fb-viral-downloader-$locale.mo" );
  }

	public function load_class($class_name = '') {
		if ('' != $class_name && '' != $this->token) {
			require_once ('class-' . esc_attr($this->token) . '-' . esc_attr($class_name) . '.php');
		} // End If Statement
	}// End load_class()
	
	
	/** Cache Helpers *********************************************************/

	/**
	 * Sets a constant preventing some caching plugins from caching a page. Used on dynamic pages
	 *
	 * @access public
	 * @return void
	 */
	function nocache() {
		if (!defined('DONOTCACHEPAGE'))
			define("DONOTCACHEPAGE", "true");
		// WP Super Cache constant
	}

	function downloadables() {
		$labels = array(
			'name' 					=> __( 'Downloadables', 'post type general name' ),
			'singular_name' 		=> __( 'Downloadable', 'post type singular name' ),
			'menu_name'				=> _x( 'Downloadables', 'Admin menu name'),
			'add_new' 				=> __( 'Add Downloadable','Downloadables' ),
			'add_new_item'       => __( 'Add New Downloadable'),
			'new_item'           => __( 'New Downloadable' ),
			'edit_item'          => __( 'Edit Downloadable' ),
			'view_item'          => __( 'View Downloadable' ),
			'all_items'          => __( 'All Downloadable' ),
			'search_items'       => __( 'Search Downloadable'),
			'parent_item_colon'  => __( 'Parent Downloadable:'),
			'not_found'          => __( 'No Downloadable found.' ),
			'not_found_in_trash' => __( 'No books found in Trash.' ),
			'parent' 				=> __( 'Parent Downloadable' )
		);
		$args = array(
			'labels' => $labels,
			'description' 			=> __( 'This is where you can add new downloadables.' ),
			'public' 				=> false,
			'show_in_menu'			=> true,
			'show_ui' 				=> true,
			'publicly_queryable' 	=> false,
			'exclude_from_search' 	=> true,
			'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
			'capability_type'		=> 'post',
			'supports' 				=> array( 'title'),
			'show_in_nav_menus' 	=> true,
			'menu_icon' => 'dashicons-download'
		);
		register_post_type( 'downloadables', $args );
	}

	public function post_columns($columns) {
		$columns["id"]    		= __( "ID", 'viral-downloader' );
		$columns["file"]  		= __( "File", 'viral-downloader' );
		$columns["count"] 		= __( '<img src="'.plugins_url("/assets/images/f063.png",dirname(__FILE__)).'">');
		$columns["featured"]  = __( '<img src="'.plugins_url("/assets/images/f005.png",dirname(__FILE__)).'">');
		$columns["members"]   = __( '<img src="'.plugins_url("/assets/images/f007.png",dirname(__FILE__)).'">');
		$columns["redirect"]  = __( '<img src="'.plugins_url("/assets/images/f0c1.png",dirname(__FILE__)).'">');
		return $columns;
	}

	public function post_columns_values($columns) {
		global $post;
		$post_details = get_post($post->ID);
		$post_meta = get_post_meta($post->ID);
		switch ( $columns ) {
			case 'id' :
				echo $post_details->ID;
			break;
			case 'file' :
				$file_pos = strrpos($post_meta['_share_file'][0] , '/');
				$file_name = str_split($post_meta['_share_file'][0] , $file_pos+1);
				echo '<a href = "' . $post_meta['_share_file'][0] . '">' . $file_name[1] . '</a>';
			break;
			case 'count' :
				echo $post_meta['_download_count'][0];
			break;
			case 'featured' :
				if ( $post_meta['_featured'][0] == 1 ) {
					echo '<img src="'.plugins_url("/assets/images/f058.png",dirname(__FILE__)).'">';
				}
			break;
			case 'members' :
			if ( $post_meta['_members'][0] == 1 ) {
				echo '<img src="'.plugins_url("/assets/images/f058.png",dirname(__FILE__)).'">';
			}
			break; 
			case 'redirect' :
			if ( $post_meta['_redirect'][0] == 1 ) {
				echo '<img src="'.plugins_url("/assets/images/f058.png",dirname(__FILE__)).'">';
			}
			break; 
		}
	}

	public function meta_boxes() {
		add_meta_box('fb_viral_downloader_check', __( 'Social Options', $this->text_domain ),	array(&$this, 'check_share'), 'downloadables');
		add_meta_box('fb_viral_downloader_url', __( 'Share Url', $this->text_domain ),	array(&$this, 'url_metabox'), 'downloadables');
		add_meta_box('fb_viral_downloader_caption', __( 'Share Caption', $this->text_domain ),	array(&$this, 'caption_metabox'), 'downloadables');
		add_meta_box('fb_viral_downloader_description', __( 'Share Description', $this->text_domain ),	array(&$this, 'description_metabox'), 'downloadables');
		add_meta_box('fb_viral_downloader_file', __( 'File', $this->text_domain ),	array(&$this, 'file_metabox'), 'downloadables');
		add_meta_box('fb_viral_downloader_stats', __( 'Statistics', $this->text_domain ),	array(&$this, 'stats_metabox'), 'downloadables','side');
		add_meta_box('fb_viral_downloader_shortcode', __( 'Shortcode', $this->text_domain ),	array(&$this, 'shortcode_metabox'), 'downloadables', 'side');
		add_meta_box('fb_viral_downloader_downloads', __( 'Downloads', $this->text_domain ),	array(&$this, 'download_metabox'), 'downloadables', 'side');
		add_meta_box('fb_viral_downloader_xtra_value' , __('Special Details', $this->text_domain) , array(&$this,'special_metabox') ,'downloadables' , 'side');
	}

	public function check_share() {
		global $post;
		if(get_post_meta($post->ID, '_share_google', true) == 1){
			echo '<input type="checkbox" name="share_google" value="google" checked/>Google&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
		} else {
			echo '<input type="checkbox" name="share_google" value="google"/>Google&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
		}
		if(get_post_meta($post->ID, '_share_facebook', true) == 1){
			echo '<input type="checkbox" name="share_facebook" value="facebook" checked/>Facebook&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
		} else {
			echo '<input type="checkbox" name="share_facebook" value="facebook"/>Facebook&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
		}
		if(get_post_meta($post->ID, '_share_twitter', true) == 1){
			echo '<input type="checkbox" name="share_twitter" value="twitter" checked/>Twitter';
		} else {
			echo '<input type="checkbox" name="share_twitter" value="twitter"/>Twitter';
		}
	}
	public function url_metabox() {
		global $post;
		echo '<input type="text" name="share_url" id="share_url" style="width: 98%" value="' . get_post_meta($post->ID, '_share_url', true) . '" />';
		echo '<p class="description">This url will help bring traffic to your website. The url may be a link to your ecommerce page, product campaign page, social campaign page pr any other page you want to be extensively viewed. Give your business or campaign a boost! </p><mark>This Meta Box Should Not Kept Blank.Use "http://" or "https://"</mark>';
	}

	public function caption_metabox() {
		global $post;
		echo '<textarea name="share_caption" style="width: 98%">' . get_post_meta($post->ID, '_share_caption', true) . '</textarea>';
		echo '<p class="description">Add a Cool and Attractive caption to your url. This catchy caption will compel Facebook users to click on your url. Take, for example, ‘Last 5 Days of Summer Sale!’.</p><mark>This Meta Box Should Not Kept Blank.</mark>';
	}

	public function description_metabox() {
		global $post;
		echo '<textarea name="share_description" style="width: 98%">' . get_post_meta($post->ID, '_share_description', true) . '</textarea>';
		echo '<p class="description">This field allows you to share a smart and brief description of your products or campaign on a social media platform. An attractive description will make Facebook users curious to know what is in your url. This will increase your viewership.</p><mark>This Meta Box Should Not Kept Blank.</mark>';
	}

	public function file_metabox() {
		global $post;
		echo '<button id="upload_file" class="button-secondary">Upload File</button>&nbsp;or enter url&nbsp;';
		echo '<input type="text" name="share_file" id="share_file" style="width: 50%" value="' . get_post_meta($post->ID, '_share_file', true) . '" />';
		echo '<p class="description">Add the file you want as downloadable content from your PC. You may also provide a url. The file may be in any format of your choice.</p><mark>This Meta Box Should Not Kept Blank.</mark>';
	}

	public function shortcode_metabox() {
		global $post;
		echo '<p><code>[viraldownloader id='. $post->ID .']</code></p>';
	}

	public function download_metabox() {
		global $post;
		echo '<p>Total Downloads : <code>'.get_post_meta( $post->ID, '_download_count', true ).'</code></p>';
	}

	public function stats_metabox() {
    global $post;
    $total = 0;
    if(get_post_meta($post->ID, '_share_url', true) != ''){
    	$total = get_post_meta( $post->ID, 'facebook_count', true ) + $this->twitCount(get_post_meta($post->ID, '_share_url', true)) + $this->getPlus1(get_post_meta($post->ID, '_share_url', true));//get_post_meta( $post->ID, 'google_count', true );
    	echo '<p><label>Total Share : </label><code>' . $total . '</code></p>';
    	echo 'Facebook : <code>'.get_post_meta( $post->ID, 'facebook_count', true ).'</code><br>';
    	echo '<br>Google : <code>'.get_post_meta( $post->ID, 'google_count', true ).'</code><br>';
    	echo '<br>Twitter : <code>'.get_post_meta( $post->ID, 'twitter_count', true ).'</code><br><br>';
    } else {
    	echo '<p><label>Total Share : </label><code> 0 </code></p>';
    	echo 'Facebook : <code> 0 </code><br>';
    	echo '<br>Google : <code> 0 </code><br>';
    	echo '<br>Twitter : <code> 0 </code><br><br>';
    }
    echo '<p class="description">See how many times your file is shared over Facebook. (If a single user downloads more than once the count will increase for each share).</p>';
	}

	public function special_metabox() {
		global $post;
		if (get_post_meta( $post->ID, '_featured' , true ) == 1){
			echo '<p>Featured : <input type="checkbox" name="featured" value="featured" checked/></p>';
		} else {
			echo '<p>Featured : <input type="checkbox" name="featured" value="featured"/></p>';
		}
		if (get_post_meta( $post->ID, '_members' , true ) == 1){
			echo '<p>Members Only : <input type="checkbox" name="members" value="members" checked/></p>';
		} else {
			echo '<p>Members Only : <input type="checkbox" name="members" value="members"/></p>';
		}
		if (get_post_meta( $post->ID, '_redirect' , true ) == 1){
			echo '<p>Redirect : <input type="checkbox" name="redirect" value="redirect" checked/></p>';
		} else {
			echo '<p>Redirect : <input type="checkbox" name="redirect" value="redirect"/></p>';
		}

	}

	public function save_metabox_data( $post_id ) {
		if( isset( $_POST['post_type'] ) && $_POST['post_type'] != 'downloadables' ) return;

		if(isset($_POST['share_google'])) {
			update_post_meta( $post_id, '_share_google' , 1 );
		}	else {
			update_post_meta( $post_id, '_share_google' , 0 );
		}
		if(isset($_POST['share_facebook'])) {
			update_post_meta( $post_id, '_share_facebook' , 1 );
		} else {
			update_post_meta( $post_id, '_share_facebook' , 0 );
		}
		if(isset($_POST['share_twitter'])) {
			update_post_meta( $post_id, '_share_twitter' , 1 );
		}	else {
			update_post_meta( $post_id, '_share_twitter' , 0 );
		}
		if(isset($_POST['featured'])) {
			update_post_meta( $post_id, '_featured' , 1 );
		} else {
			update_post_meta( $post_id, '_featured' , 0 );
		}
		if(isset($_POST['members'])) {
			update_post_meta( $post_id, '_members' , 1 );
		} else {
			update_post_meta( $post_id, '_members' , 0 );
		}
		if(isset($_POST['redirect'])) {
			update_post_meta( $post_id, '_redirect' , 1 );
		} else {
			update_post_meta( $post_id, '_redirect' , 0 );
		}
		$metas = array('share_url', 'share_caption', 'share_description', 'share_file');
		foreach( $metas as $meta ) {
			if( !empty( $_POST[$meta] ) ) update_post_meta( $post_id, '_' . $meta, $_POST[$meta] );
		}
		if( !get_post_meta( $post_id, '_download_count' , true )) {
			update_post_meta( $post_id, '_download_count' , 0 );
		}
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
				<th>Check Share</th>
				<td><input class="share_google" name="google" type="checkbox" value="google"/>Google</td>
			</tr>
			<tr class="hide add-new">	
				<th></th>
				<td><input class="share_facebook" type="checkbox" value="facebook"/>Facebook</td>
			</tr>
			<tr class="hide add-new">
				<th></th>
				<td><input class="share_twitter" type="checkbox" value="twitter"/>Twitter</td>
			</tr>
			<tr class="hide add-new">
				<th>Feature List</th>
				<td><input class="featured" type="checkbox" value="featured"/>Featured</td>
			</tr>
			<tr class="hide add-new">	
				<th></th>
				<td><input class="members" type="checkbox" value="members"/>Members</td>
			</tr>
			<tr class="hide add-new">
				<th></th>
				<td><input class="redirect" type="checkbox" value="redirect"/>Redirect</td>
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
	
	function getPlus1($url) {
		global $post;
		$html =  file_get_contents( "https://plusone.google.com/_/+1/fastbutton?url=".urlencode($url));
		$doc = new DomDocument();   
		@$doc->loadHTML($html);
		$counter=$doc->getElementById('aggregateCount');
		update_post_meta($post->ID, 'google_count', $counter->nodeValue);
		return $counter->nodeValue;
	}
	
	function twitCount($url) {
		global $post;
		$json_received = file_get_contents("http://cdn.api.twitter.com/1/urls/count.json?url=" . $url);
		$json = json_decode($json_received, true);
		update_post_meta($post->ID, 'twitter_count', $json['count']);
		return $json['count'];
	}

	public function downloadables_taxonomies(){
		$labels = array(
			'name'              => _x( 'Categories', 'fb-viral-downloader' ),
			'singular_name'     => _x( 'Category', 'fb-viral-downloader' ),
			'search_items'      => __( 'Search Categories' ),
			'all_items'         => __( 'All Categories' ),
			'parent_item'       => __( 'Parent Categories' ),
			'parent_item_colon' => __( 'Parent Categories:' ),
			'edit_item'         => __( 'Edit Categories' ),
			'update_item'       => __( 'Update Categories' ),
			'add_new_item'      => __( 'Add New Categories' ),
			'new_item_name'     => __( 'New Categories Name' ),
			'menu_name'         => __( 'Categories' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'categories' ),
		);

		register_taxonomy( 'categories', 'downloadables', $args );

		$labels = array(
			'name'                       => _x( 'Tags', 'fb-viral-downloader' ),
			'singular_name'              => _x( 'Tag', 'fb-viral-downloader' ),
			'search_items'               => __( 'Search Tags' ),
			'popular_items'              => __( 'Popular Tags' ),
			'all_items'                  => __( 'All Tags' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Tag' ),
			'update_item'                => __( 'Update Tag' ),
			'add_new_item'               => __( 'Add New Tag' ),
			'new_item_name'              => __( 'New Tag Name' ),
			'separate_items_with_commas' => __( 'Separate tags with commas' ),
			'add_or_remove_items'        => __( 'Add or remove tags' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags' ),
			'not_found'                  => __( 'No tags found.' ),
			'menu_name'                  => __( 'Tags' ),
		);

		$new_args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'tags' ),
		);
		register_taxonomy( 'tags', 'downloadables', $new_args );
	}
}