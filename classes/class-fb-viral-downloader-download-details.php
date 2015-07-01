<?php
class FB_Viral_Downloader_Download_Details{
  private $id = "";
  private $ip = "";
  private $browser_name = "";
  private $browser_ver = "";
  private $os_name = "";
  private $db_version = "1.0";
  public function __construct($client_details){
    $this->id = $client_details['id'];
    $this->ip = $client_details['ip'];
    $this->browser_name = $client_details['browser_name'];
    $this->browser_ver = $client_details['browser_ver'];
    $this->os_name = $client_details['os_version'];
    $this->insert_database();
  }

  public function insert_database(){
    global $wpdb;
    $table_name = $wpdb->prefix . "download_data";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    //Getting File Name
    $post_meta = get_post_meta($this->id);
    $file_pos = strrpos($post_meta['_share_file'][0] , '/');
    $file_name = str_split($post_meta['_share_file'][0] , $file_pos+1);

    //Getting Social Details
    $facebook = $post_meta['_share_facebook'][0];
    $google = $post_meta['_share_google'][0];
    $twitter = $post_meta['_share_twitter'][0];

    //Getting File Special Permissions
    $members = $post_meta['_members'][0];
    $redirect = $post_meta['_redirect'][0];
    $featured = $post_meta['_featured'][0];

    //Getting Time
    $date = new DateTime();
    date_timestamp_set($date, strtotime("now"));
    $time = date_format($date, 'Y-m-d H:i:s');

    //Getting User Details
    $id = get_current_user_id();
    if($id == 0){
      $user_id = 'Guest';
      $user_email = 'Guest';
    } else{
      $user_data = get_userdata($id);
      $user_id = $user_data->user_login;
      $user_email = $user_data->user_email;
    }
    $insert_sql = 'INSERT INTO '.$table_name.' 
      (`fileid`, `file`, `userid`, `useremail`, `members`, `redirect`, `featured`,`facebook`, `google`, `twitter`, `ip`, `browser_name`, `browser_ver`, `os`, `time`) 
      VALUES
      ('.$this->id.',"'.$file_name[1].'","'.$user_id.'","'.$user_email.'","'.$members.'","'.$redirect.'","'.$featured.'","'.$facebook.'","'.$google.'","'.$twitter.'","'.$this->ip.'","'.$this->browser_name.'","'.$this->browser_ver.'","'.$this->os_name.'","'.$time.'");';
    dbDelta($insert_sql);
  }
}
?>