<?php
if(!function_exists('get_viral_downloader_settings')) {
  function get_viral_downloader_settings($name = '', $tab = '') {
    if(empty($tab) && empty($name)) return '';
    if(empty($tab)) return get_option($name);
    if(empty($name)) return get_option("dc_{$tab}_settings_name");
    $settings = get_option("dc_{$tab}_settings_name");
    if(!isset($settings[$name])) return '';
    return $settings[$name];
  }
}

add_action('admin_menu', 'downloadables_log_page');

function downloadables_log_page() {
 	add_submenu_page('edit.php?post_type=downloadables', 'Downloadables Logs', 'Downloadables Logs', 'read', 'downloadables_logs','downloadables_logs_callback');
}

function downloadables_logs_callback() {
	global $wpdb;
	$social = '';
	$featured = $redirect = 0;
  $table_name = $wpdb->prefix . "download_data";
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	?>
	<div class="wrap">
		<div id="icon-edit" class="icon32 icon32-posts-dlm_download"><br/></div>
		<h2><?php _e( 'Downloadables Logs', 'fb-viral-downloader' ); ?><input type="button" id="export" class="add-new-h2" value="Export CSV"><input type="button" id="delete" class="add-new-h2" value="Delete Logs"></h2><br/>
			<div id="download-logs">
			<p>
			<table class="table table-striped table-bordered dataTable no-footer" id="download_log_table">	<!--class="table table-striped table-bordered dataTable no-footer"-->
				<thead>
					<tr>
						<th>ID</th>
						<th>File</th>
						<th>User</th>
						<th>Feature</th>
						<th>Social</th>
						<th>IP</th>
						<th>Browser</th>
						<th>Date</th>
					</tr>
				</thead>
			<tbody>
			<?php
				$logs = 'SELECT * FROM '.$table_name;
				$log_results = $wpdb->get_results($logs);
				for($i = 0 ; $i < count($log_results) ; $i++){ ?>
					<tr>
					<td><?php echo $log_results[$i]->fileid; ?></td>
					<td><?php echo $log_results[$i]->file; ?></td>
					<td><?php echo $log_results[$i]->userid.' - '.$log_results[$i]->useremail; ?></td>
					<td><?php 
						$spcl = '';
						if($log_results[$i]->featured == 1){
							$spcl = '<img src="'.plugins_url("/assets/images/arrow-242.png",dirname(__FILE__)).'"><div class="hide">featured</div>';
						} else {
							$spcl = '<img src="'.plugins_url("/assets/images/arrow-3.png",dirname(__FILE__)).'"><div class="hide">redirect</div>';
						}
						echo $spcl;
					?></td>
					<td><?php 
						$social = '';
						if ($log_results[$i]->facebook == 1){
							$social .= '<img src="'.plugins_url("/assets/images/facebook.png",dirname(__FILE__)).'"><div class="hide">facebook</div>&nbsp';
						}
						if($log_results[$i]->google == 1){
							$social .= '<img src="'.plugins_url("/assets/images/google-plus.png",dirname(__FILE__)).'"><div class="hide">google</div>&nbsp';
						}
						if($log_results[$i]->twitter == 1){
							$social .= '<img src="'.plugins_url("/assets/images/twitter.png",dirname(__FILE__)).'"><div class="hide">twitter</div>&nbsp';
						}
						echo $social; 
						?>
					</td>
					<td><?php echo $log_results[$i]->ip; ?></td>
					<td><?php echo $log_results[$i]->browser_name.'-'.$log_results[$i]->browser_ver.'/'.$log_results[$i]->os; ?></td>
					<td><?php echo $log_results[$i]->time; ?></td>
					</tr>
					<?php 
				}
				?>
			</tbody>
		</table>
		</p>
		</div>
	<?php
}

function receive_month($month){
	switch($month){
		case 1: return 'Jan'; break;
		case 2: return 'Feb'; break;
		case 3: return 'Mar'; break;
		case 4: return 'Apr'; break;
		case 5: return 'May'; break;
		case 6: return 'June'; break;
		case 7: return 'July'; break;
		case 8: return 'Aug'; break;
		case 9: return 'Sept'; break;
		case 10: return 'Oct'; break;
		case 11: return 'Nov'; break;
		case 12: return 'Dec'; break;
		default: return 'Wrong Input'; break;
	}
}
?>