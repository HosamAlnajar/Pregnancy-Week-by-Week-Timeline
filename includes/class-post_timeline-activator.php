<?php

/**
 * Fired during plugin activation
 *
 * @link       https://alnajar.se/
 * @since      1.0.0
 *
 * @package    Post_timeline
 * @subpackage Post_timeline/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Post_timeline
 * @subpackage Post_timeline/includes
 * @author     Hosam Alnajar <hosam@alnajar.se>
 */
class Post_timeline_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( empty( get_option( 'post_timeline_default_messages', [] ) ) ) {
			add_option( 'post_timeline_default_messages', self::post_timeline_default_messages() );
			update_site_option( 'post_timeline',  '1.0.0' );
		}
	}

	/**
	 * Default Options.
	 *
	 * @return mixed|void
	 */
	public static function post_timeline_default_messages() {
		$defaults = [
			'timeline_text'                     => [
				'description'           => __( "This text will appear in the frontend when mouse hover.", 'post_timeline' ),
				'default'    => __( "Click here", 'post_timeline' ),
			],
		];

		return apply_filters( 'post_timeline_messages', $defaults );
	}



}
