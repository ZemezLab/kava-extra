<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kava_Extra_Assets' ) ) {

	/**
	 * Define Kava_Extra_Assets class
	 */
	class Kava_Extra_Assets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
		}

		/**
		 * Enqueue admin styles
		 *
		 * @return void
		 */
		public function admin_enqueue_styles() {
			$screen = get_current_screen();

			// Jet setting page check
			if ( 'elementor_page_kava-theme-settings' === $screen->base ) {}

			wp_enqueue_style(
				'kava-theme-admin-css',
				kava_extra()->plugin_url( 'assets/css/kava-extra-admin.css' ),
				false,
				kava_extra()->get_version()
			);
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

function kava_extra_assets() {
	return Kava_Extra_Assets::get_instance();
}
