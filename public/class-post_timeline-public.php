<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://alnajar.se/
 * @since      1.0.0
 *
 * @package    Post_timeline
 * @subpackage Post_timeline/public
 */



/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Post_timeline
 * @subpackage Post_timeline/public
 * @author     Hosam Alnajar <hosam@alnajar.se>
 */
class Post_timeline_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( 'get_timeline_template', [ $this, 'get_timeline_template_func'  ] );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugins_url( '/', __FILE__ )  . 'css/post_timeline-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script('ajax-googleapis', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', array( 'jquery' ), true);

		wp_enqueue_script(
			$this->plugin_name . '-script',
			plugins_url( '/', __FILE__ )  . 'js/post_timeline-public.js',
			array( 'jquery','ajax-googleapis' ),
			$this->version, false
		);

		$namespace = 'Post_Timeline';
		wp_localize_script(
			$this->plugin_name . '-script',
			$namespace,
			[
				'TimelinePosts' => $this->get_timeline_settings(),
			]
		);
	}

	public function get_timeline_settings(){
		$options = get_option('timeline_options');
		return $options;
	}

	public function get_timeline_template_func(){
		ob_start();
		include POST_TIMELINE_DIR . '/templates/timeline-template.php' ;
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

}
