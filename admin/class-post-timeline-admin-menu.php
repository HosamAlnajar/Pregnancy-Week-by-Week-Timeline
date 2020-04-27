<?php

namespace Post_Timeline\Admin;

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Timeline_Admin_Menu {

	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * @var string
	 */
	public $page_slug = 'timeline-setting-admin';

	/**
	 * @var string
	 */
	public $timeline_option_group = 'timeline_option_group';

	/**
	 * @var string
	 */
	public $weeks_section = 'timeline-weeks-section';


	/**
	 * timeline_Admin_Menu constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'timeline_add_toplevel_menu' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function timeline_add_toplevel_menu() {
		add_menu_page(
			'Pregnancy Plugin Settings',
			'Pregnancy Plugin Settings',
			'manage_options',
			$this->page_slug,
			array( $this, 'callback_create_admin_page' ),
			'dashicons-admin-generic',
			null
		);
	}

	/**
	 *
	 */
	public function callback_create_admin_page() {
		// Set class property
		$this->options = get_option( 'timeline_options' );
		?>
		<div class="wrap">
			<h1>Pregnancy Plugin Settings</h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( $this->timeline_option_group );
				do_settings_sections( $this->page_slug );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		register_setting(
			$this->timeline_option_group, // Option group
			'timeline_options', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			$this->weeks_section, // ID
			'-----', // Title
			array( $this, 'print_section_info_api' ), // Callback
			$this->page_slug // Page
		);

		add_settings_field(
			'active_timeline', // ID
			'Active Timeline', // Title
			array( $this, 'active_timeline_callback' ), // Callback
			$this->page_slug, // Page
			$this->weeks_section // Section
		);

		for ( $i = 1; $i <= 41; $i++  ){
			add_settings_field(
				'week_' . $i . '_link',
				'Week -' . $i . '- link',
				array( $this, 'week_link_callback' ),
				$this->page_slug,
				$this->weeks_section,
				$i
			);
			add_settings_field(
				'week_' . $i . '_image',
				'Week -' . $i . '- Image',
				array( $this, 'week_image_callback' ),
				$this->page_slug,
				$this->weeks_section,
				$i
			);
			add_settings_field(
				'week_' . $i . '_text',
				'Week -' . $i . '- text',
				array( $this, 'week_text_callback' ),
				$this->page_slug,
				$this->weeks_section,
				$i
			);
		}


	}

	public function sanitize( $input ) {
		$new_input = array();

		if ( isset( $input['active_timeline'] ) ) {
			$new_input['active_timeline'] = $input['active_timeline'];
		}

		for ( $i = 1; $i <= 41; $i++  ) {

			if ( isset( $input['week_' . $i . '_image'] ) ) {
				$new_input['week_' . $i . '_image'] = sanitize_text_field( $input['week_' . $i . '_image'] );
			}

			if ( isset( $input['week_' . $i . '_link'] ) ) {
				$new_input['week_' . $i . '_link'] = sanitize_text_field( $input['week_' . $i . '_link'] );
			}

			if ( isset( $input['week_' . $i . '_text'] ) ) {
				$new_input['week_' . $i . '_text'] = sanitize_text_field( $input['week_' . $i . '_text'] );
			}

//			if ( get_option( 'week_' . $i . '_text' ) === false ) // Nothing yet saved
//				error_log( print_r( get_option( 'week_' . $i . '_text' ), true ) );

			//update_option( 'week_' . $i . '_text', 'default_stuff' );
		}

		return $new_input;
	}

	/**
	 * Print the Section text api
	 */
	public function print_section_info_api() {
		print 'Enter image and link to each Week below:';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function active_timeline_callback() {

		$checked = checked( $this->options['active_timeline'], 1 );
		echo '<label class="active-pregnancy-timeline switch"><input type="checkbox" id="active-pregnancy-timeline" ' . esc_attr( $checked ) . ' name="timeline_options[active_timeline]" value="1">
						<span></span>
					</label>';

	}

	public function week_image_callback( $arg ){
		$slug = 'week_' . $arg . '_image';

		printf(
			'<input type="text" id="' . $slug . '" name="timeline_options[' . $slug . ']" value="%s" />',
			isset( $this->options[$slug] ) ? esc_attr( $this->options[$slug] ) : ''
		);

	}

	public function week_link_callback( $arg ){
		$slug = 'week_' . $arg . '_link';

		printf(
			'<input type="text" id="' . $slug . '" name="timeline_options[' . $slug . ']" value="%s" />',
			isset( $this->options[$slug] ) ? esc_attr( $this->options[$slug] ) : ''
		);

	}
	public function week_text_callback( $arg ){
		$slug = 'week_' . $arg . '_text';

		$text_field = '';
		$default_text  = get_option( 'post_timeline_default_messages',[] );
		if ($default_text){
			$text_field = $default_text['timeline_text']['default'];
		}

		printf(
			'<input type="text" id="' . $slug . '" name="timeline_options[' . $slug . ']" value="%s" />',
			! empty( $this->options[$slug] ) ? esc_attr( $this->options[$slug] ) : $text_field
		);

	}
}

