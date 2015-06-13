<?php
class FB_Viral_Downloader_Shortcode {

	public $list_product;

	public function __construct() {
		//shortcodes
		//add_shortcode('demo_shortcode', array(&$this, 'demo_shortcode'));
		add_shortcode( 'viraldownloader', array(&$this, 'output_viraldownloader_shortcode') );
	}

	//public function demo_shortcode($attr) {
	public function output_viraldownloader_shortcode($attr) {
		global $FB_Viral_Downloader;
		$this->load_class('viraldownloader-shortcode');
		return $this->shortcode_wrapper(array('WC_Viraldownloader_Shortcode', 'output'), $attr);
	}

	/**
	 * Helper Functions
	 */

	/**
	 * Shortcode Wrapper
	 *
	 * @access public
	 * @param mixed $function
	 * @param array $atts (default: array())
	 * @return string
	 */
	public function shortcode_wrapper($function, $atts = array()) {
		ob_start();
		call_user_func($function, $atts);
		return ob_get_clean();
	}

	/**
	 * Shortcode CLass Loader
	 *
	 * @access public
	 * @param mixed $class_name
	 * @return void
	 */
	public function load_class($class_name = '') {
		global $FB_Viral_Downloader;
		if ('' != $class_name && '' != $FB_Viral_Downloader->token) {
			require_once ('shortcode/class-' . esc_attr($FB_Viral_Downloader->token) . '-shortcode-' . esc_attr($class_name) . '.php');
		}
	}

}
?>
