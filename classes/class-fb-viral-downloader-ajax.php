<?php
class FB_Viral_Downloader_Ajax {

	//public $client_details = array();

	public function __construct() {
		//add_action('wp', array(&$this, 'demo_ajax_method'));
		add_action('init', array( &$this, 'init'));
		add_action( 'admin_init', array( &$this, 'admin_init' ));
		add_action( 'wp_ajax_generate', array( &$this, 'generate_callback' ) );
		add_action( 'wp_ajax_nopriv_generate', array( &$this, 'generate_callback' ) );
		add_action( 'wp_ajax_google_share', array( &$this, 'google_share_callback' ) );
		add_action( 'wp_ajax_nopriv_google_share', array( &$this, 'google_share_callback' ) );
		add_action( 'wp_ajax_download_csv', array( &$this, 'download_csv_callback' ) );
		add_action( 'wp_ajax_nopriv_download_csv', array( &$this, 'download_csv_callback' ) );
		add_action( 'wp_ajax_delete_logs', array( &$this, 'delete_logs_callback' ) );
		add_action( 'wp_ajax_nopriv_delete_logs', array( &$this, 'delete_logs_callback' ) );
	}

	public function init() {
	  // Do your ajx job here
	  if ( ! is_admin() || defined('DOING_AJAX') ) {
	  	add_action( 'wp_ajax_viraldownloader_share_complete', array( &$this, 'viraldownloader_share_complete_callback' ) );
			add_action( 'wp_ajax_nopriv_viraldownloader_share_complete', array( &$this, 'viraldownloader_share_complete_callback' ) );
		}
	}

	public function admin_init() {
		//add_action( 'admin_notices', array( &$this, 'show_admin_messages' ) );
		add_action( 'wp_ajax_add_new_downloadable', array( &$this, 'add_new_downloadable_callback' ) );
	}
	public function viraldownloader_share_complete_callback() {
		if(isset($_POST['download_id'])) {
			$viraldownloader_share_data = get_post_meta($_POST['download_id'], 'facebook_count', true );
			$viraldownloader_share_data++;
			update_post_meta($_POST['download_id'], 'facebook_count', $viraldownloader_share_data);
		}
		die;
	}

	public function add_new_downloadable_callback() {
		if(isset($_POST['title']) && isset($_POST['data'])) {
			$post_id = wp_insert_post(array('post_title' => $_POST['title'], 'post_status' => 'publish', 'post_type' => 'downloadables'));
			if(!is_wp_error($post_id)) {
				if(!empty($_POST['featured_image']))
					set_post_thumbnail($post_id, absint($_POST['featured_image']));
				$metas = array('share_google','share_facebook','share_twitter','featured','members','redirect','share_url', 'share_caption', 'share_description', 'share_file');
				foreach($metas as $meta) {
					if(!empty($_POST['data'][$meta])) update_post_meta($post_id, '_' . $meta, $_POST['data'][$meta]);
				}
				echo $post_id;
			}
		}
		die;
	}

	public function google_share_callback(){
		echo 1;
		die;
	}
	
	public function generate_callback(){
		require_once ('class-fb-viral-downloader-get-browser.php');
		$browser = new FB_Viral_Downloader_Get_Browser();
		$browser_details = $browser->showInfo('all');
		$ipaddress = $browser->get_ip();
		$client_details = array( 
			'id' => $_POST['id'],
			'ip' => $ipaddress,
			'agents' => $_POST['agents'],
			'browser_name' => $browser_details[2],
			'browser_ver' => $browser_details[0], 
			'os_version' => $browser_details[1]
		);
		$arr_ip = explode('|',$_POST['ip']);
		for($i=0 ; $i<count($arr_ip) ; $i++){
			$x = strcmp((string)$arr_ip[$i] , (string)$ipaddress);
			if($x == 0){
				echo 'IP Blocked';
				die;
			}
		}
		$arr_agent = explode('|',$client_details['agents']); 
		for($i=0 ; $i<count($arr_agent) ; $i++){
			$y = strcmp((string)$arr_agent[$i] , (string)$client_details['browser_name']);
			if($y == 0){
				echo 'You Are Unauthorised';
				die;
			}
		}
		if($_POST['member'] == 1){
			if(!is_user_logged_in()){
				echo 'This File is For Members Only';
				die;
			}
		}
		require_once ('class-fb-viral-downloader-download-details.php');
		$add_download = new FB_Viral_Downloader_Download_Details($client_details);
		$download_count = get_post_meta($_POST['id'], '_download_count', true);
		$download_count++;
		update_post_meta($_POST['id'], '_download_count', $download_count);
		echo get_post_meta($_POST['id'], '_share_file', true);
		die;
	}

	public function delete_logs_callback(){
		global $wpdb;
		$table_name = $wpdb->prefix . "download_data";
  	$table_name = $wpdb->prefix . "download_data";
  	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  	$sql = 'delete from '.$table_name;
  	$wpdb->query($sql);
  	echo 'OK';
  	die;
	}

	public function download_csv_callback(){
		global $wpdb;
		$social = '';
  	$table_name = $wpdb->prefix . "download_data";
  	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$list = array();
		array_push($list, 'ID', 'File', 'User', 'Members' , 'Redirect', 'Featured', 'Facebok', 'Google', 'Twitter', 'IP', 'Browser', 'Time');
		$file = fopen(ABSPATH . 'wp-content/uploads/download_logs.csv','w');
		$url = get_site_url(). '/wp-content/uploads/download_logs.csv';
		fputcsv($file,$list);
  	$logs = 'SELECT * FROM '.$table_name;
		$log_results = $wpdb->get_results($logs);
		foreach ($log_results as $log_result)
  	{
  		$line = "\n";
  		$line .= $log_result->fileid .','. $log_result->file .','. $log_result->userid .'-'. $log_result->useremail.',';
  		if($log_result->members == 1){
  			$line .= 'yes,';
  		} else {
  			$line .= 'no,';
  		}
  		if($log_result->redirect == 1){
  			$line .= 'yes,';
  		} else {
  			$line .= 'no,';
  		}
  		if($log_result->featured == 1){
  			$line .= 'yes,';
  		} else {
  			$line .= 'no,';
  		}
  		if($log_result->facebook == 1){
  			$line .= 'yes,';
  		} else {
  			$line .= 'no,';
  		}
  		if($log_result->google == 1){
  			$line .= 'yes,';
  		} else {
  			$line .= 'no,';
  		}
  		if($log_result->twitter == 1){
  			$line .= 'yes,';
  		} else {
  			$line .= 'no,';
  		}
  		$line .= $log_result->ip .','.$log_result->browser_name.'-'.$log_result->browser_ver.'/'.$log_result->os .','.$log_result->time;
  		$line_arr = explode(',',$line);
  		fputcsv($file,$line_arr);
  	}
		fclose($file);
		echo $url;
		die;
	}
}

