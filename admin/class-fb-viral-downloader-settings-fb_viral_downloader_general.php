<?php
class FB_Viral_Downloader_Settings_General {
  /**
   * Holds the values to be used in the fields callbacks
   */
  private $options;
  
  private $tab;

  /**
   * Start up
   */
  public function __construct($tab) {
    $this->tab = $tab;
    $this->options = get_option( "dc_{$this->tab}_settings_name" );
    $this->settings_page_init();
  }
  
  /**
   * Register and add settings
   */
  public function settings_page_init() {
    global $FB_Viral_Downloader;
    
    $settings_tab_options = array(
    	"tab" => "{$this->tab}",
			"ref" => &$this,
			"sections" => array(
				"default_settings_section" => array(
					"title" =>  __(' Settings', $FB_Viral_Downloader->text_domain), // Section one
					"fields" => array(
						"facebook" => array(
							'title' => __('Facebook Application ID', $FB_Viral_Downloader->text_domain), 
							'type' => 'text', 
							'id' => 'fbid', 
							'label_for' => 'fbid',
							'name' => 'fbSocialClientId', 
							'hints' => __('Enter Facebook App ID here.', $FB_Viral_Downloader->text_domain), 
							'desc' => __('It will represent your facebook app identification.', $FB_Viral_Downloader->text_domain)
						),
						"google" => array(
							'title' => __('Google+ Application ID', $FB_Viral_Downloader->text_domain), 
							'type' => 'text', 
							'id' => 'gpid', 
							'label_for' => 'gpid',
							'name' => 'gpSocialClientId', 
							'hints' => __('Enter Google+ App ID here.', $FB_Viral_Downloader->text_domain), 
							'desc' => __('It will represent your google plus app identification.', $FB_Viral_Downloader->text_domain)
						), 
						"twitter" => array(
							'title' => __('Twitter Application ID', $FB_Viral_Downloader->text_domain), 
							'type' => 'text', 
							'id' => 'twid', 
							'label_for' => 'twid',
							'name' => 'twSocialClientId', 
							'hints' => __('Enter Twitter App ID here.', $FB_Viral_Downloader->text_domain), 
							'desc' => __('It will represent your twitter app identification.', $FB_Viral_Downloader->text_domain)
						),// Text
					)
				), 
			)
		);
    $FB_Viral_Downloader->admin->settings->settings_field_init(apply_filters("settings_{$this->tab}_tab_options", $settings_tab_options));
  }

  /**
   * Sanitize each setting field as needed
   *
   * @param array $input Contains all settings fields as array keys
   */
  public function dc_fb_viral_downloader_general_settings_sanitize( $input ) {
    global $FB_Viral_Downloader;
    $new_input = array();
    
    $hasError = false;
    
    if( isset( $input['fbSocialClientId'] ) ) {
      $new_input['fbSocialClientId'] =  $input['fbSocialClientId'] ;
    }
    if( isset( $input['gpSocialClientId'] ) ) {
      $new_input['gpSocialClientId'] =  $input['gpSocialClientId'] ;
    }
    if( isset( $input['twSocialClientId'] ) ) {
      $new_input['twSocialClientId'] =  $input['twSocialClientId'] ;
    }
    if(!$hasError) {
      add_settings_error(
        "dc_{$this->tab}_settings_name",
        esc_attr( "dc_{$this->tab}_settings_admin_updated" ),
        __('General settings updated', $FB_Viral_Downloader->text_domain),
        'updated'
      );
    }
    return $new_input;
  }

  /** 
   * Print the Section text
   */

  public function default_settings_section_info() {
    global $FB_Viral_Downloader;
    _e('<br>Enter your social settings below', $FB_Viral_Downloader->text_domain);
  }
  
  /** 
   * Print the Section text
   */
  public function custom_settings_section_info() {
    global $FB_Viral_Downloader;
    _e('Enter your custom settings below', $FB_Viral_Downloader->text_domain);
  }
}