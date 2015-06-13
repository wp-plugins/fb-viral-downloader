<?php
class FB_Viral_Downloader_Settings_Logs {
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
				"logs" => array(
					"title" =>  __(' Settings', $FB_Viral_Downloader->text_domain), // Section one
					"fields" => array(
            "is_enable" => array(
              'title' => __('Enable', $FB_Viral_Downloader->text_domain), 
              'type' => 'checkbox', 
              'id' => 'is_enable', 
              'label_for' => 'is_enable', 
              'name' => 'is_enable',
              'value' => 'Enable'
            ),
            "blacklisted_ips" => array(
              'title' => __('Blacklisted IPs', $FB_Viral_Downloader->text_domain) , 
              'type' => 'textarea', 
              'id' => 'blacklisted_ips', 
              'label_for' => 'blacklisted_ips', 
              'name' => 'blacklisted_ips', 
              'rows' => 3, 
              'cols' => 80,
              'placeholder' => __('Blacklisted IPs', $FB_Viral_Downloader->text_domain), 
              'desc' => __('Here You Provide All IPs Which You Want To Block. Use | Between Two IPs. Don\'t Use Any Space.', $FB_Viral_Downloader->text_domain)
            ),
            "blacklisted_agents" => array(
              'title' => __('Blacklisted Agents', $FB_Viral_Downloader->text_domain) , 
              'type' => 'textarea', 
              'id' => 'blacklisted_agents', 
              'label_for' => 'blacklisted_agents', 
              'name' => 'blacklisted_agents', 
              'rows' => 3, 
              'cols' => 80,
              'placeholder' => __('Blacklisted User Agents', $FB_Viral_Downloader->text_domain), 
              'desc' => __('Here You Provide All User Agents Which You Want To Block. Use | Between Two Agents. Don\'t Use Any Space After Or Before \'|\'', $FB_Viral_Downloader->text_domain)
            ),
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
  public function dc_fb_viral_downloader_logs_settings_sanitize( $input ) {
    global $FB_Viral_Downloader;
    $new_input = array();
    
    $hasError = false;
    
    if( isset( $input['is_enable'] ) ) {
      $new_input['is_enable'] = $input['is_enable'] ;
    } else {
      $new_input['is_enable'] = 'Disable';
    }

    if( isset( $input['blacklisted_ips'] ) ) {
      $new_input['blacklisted_ips'] = $input['blacklisted_ips'] ;
    }

    if( isset( $input['blacklisted_agents'] ) ) {
      $new_input['blacklisted_agents'] = sanitize_text_field($input['blacklisted_agents']);
    }

    if(!$hasError) {
      add_settings_error(
        "dc_{$this->tab}_settings_name",
        esc_attr( "dc_{$this->tab}_settings_admin_updated" ),
        __('Logs Settings updated', $FB_Viral_Downloader->text_domain),
        'updated'
      );
    }
    return $new_input;
  }

  /** 
   * Print the Section text
   */

  public function logs_info() {
    global $FB_Viral_Downloader;
    _e('<br>Enter your download log settings below', $FB_Viral_Downloader->text_domain);
  }
  
  /** 
   * Print the Section text
   */
  public function custom_settings_section_info() {
    global $FB_Viral_Downloader;
    _e('Enter your custom settings below', $FB_Viral_Downloader->text_domain);
  }
}