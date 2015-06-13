<?php
class WC_Viraldownloader_Shortcode {

	public function __construct() {

	}

	/**
	 * Output the demo shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	public static function output( $atts) {
		global $FB_Viral_Downloader;

		extract( shortcode_atts( array('id' => 'id' ,'text' => 'text' ), $atts ) );
		$common_html = '';
		$share_count = ( $data = get_post_meta( $id, 'viraldownloader_share_data', true ) ) ? count( $data ) : 0;
		$gp = $fb = $tw = 0;
		if($id == '') return;
		
		$option = get_option("dc_fb_viral_downloader_general_settings_name");
		$fbid = $option['fbSocialClientId'];
		$twid = $option['twSocialClientId'];
		$share_count = ( $data = get_post_meta( $id, 'viraldownloader_share_data', true ) ) ? count( $data ) : 0;
		$link_html = '';
		$common_html .=  'data-caption="' . get_post_meta($id, "_share_caption", true). '"';
		$common_html .= ' data-description="' . get_post_meta($id, '_share_description', true) . '"';
		$common_html .= ' data-url="' . get_post_meta($id, '_share_url', true) . '"';
		$common_html .= ' data-image="' . wp_get_attachment_url(get_post_thumbnail_id($id)) . '"';
		$common_html .= ' data-title="' . get_the_title($id) . '"';
		$common_html .= ' data-id="' . $id . '"';
		$common_html .= ' data-fbid="' . $fbid . '"></i>';
		$link_html .= '<a href="#" class="viraldownloader_url viraldownloader pop' . $id . '"';
		$link_html .= '>';
		$link_html .= apply_filters( 'fb_viral_downloader_html', $text, $id, $share_count );
		$link_html .= '</a>';
		$link_html .= '<div class="download">';
		if(get_post_meta($id, '_share_facebook', true) == 1) {
			$link_html .= '<div id="fb-root"></div><div class="adjust" ><a href="#"><i id="fb" class="fa fa-facebook-official fa-2x"' . $common_html . '</a></div>';
			$fb++;
		}
		if(get_post_meta($id, '_share_google', true) == 1) {
			$link_html .= '<div class="adjust" ><a id="google_icon" href="https://plus.google.com/share?url=' . get_post_meta($id, "_share_url", true) . '"><i id="gp" class="fa fa-google-plus-square fa-2x"></i></a></div>';
			$gp++;
		}
		if(get_post_meta($id, '_share_twitter', true) == 1) {
			$link_html .= '<div id="tw" class="adjust" ><a href="https://twitter.com/intent/tweet?url='.get_post_meta($id, "_share_url", true).'&text='. get_post_meta($id, "_share_caption", true) .'%20%7C%20'. get_post_meta($id, "_share_description", true) .'%20%7C%20&tw_p=tweetbutton&via='.$twid.'" data-url="' . get_post_meta($id, "_share_url", true) . '"><i class="fa fa-twitter-square fa-2x"></i></a></div>';
			$tw++;
		}
		?>
		<script>
		!function(d,s,id) {
			var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
			if(!d.getElementById(id)) {
				js=d.createElement(s);
				js.id=id;
				js.src=p+'://platform.twitter.com/widgets.js';
				fjs.parentNode.insertBefore(js,fjs);
			}
		}(document, 'script', 'twitter-wjs');
		</script>
		<?php
		$file = get_post_meta($id);
		$file_pos = strrpos($file['_share_file'][0] , '/');
		$file_name = str_split($file['_share_file'][0] , $file_pos+1);
		$settings = get_option("dc_fb_viral_downloader_logs_settings_name");
		if($settings['is_enable'] == 'Enable'){
			$ips_to_block = $settings['blacklisted_ips'];
			$blacklisted_agents = $settings['blacklisted_agents'];
			$link_html .= '<input type="button" id="bttn" name="gen" value="Generate" data-gp='.$gp.' data-fb='.$fb.' data-tw='.$tw.' data-id='.$id.' data-ip='.$ips_to_block.' data-agents='.$blacklisted_agents.' data-members='.get_post_meta( $id, '_members' , true).' data-feature='.get_post_meta( $id, '_featured' , true ).' data-file='.$file_name[1].'><br></div>';
			echo $link_html;
		} else {
			$link_html .= '<input type="button" id="bttn" name="gen" value="Generate" data-gp='.$gp.' data-fb='.$fb.' data-tw='.$tw.' data-id='.$id.' data-members='.get_post_meta( $id, '_members' , true).' data-feature='.get_post_meta( $id, '_featured' , true ).' data-file='.$file_name[1].'><br></div>';
			echo $link_html;
			do_action('fb-viral-downloader_template');
		}
	}
}