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

if ( ! class_exists( 'Kava_Extra_Elementor_Plugin_Ext' ) ) {

	/**
	 * Define Kava_Extra_Elementor_Plugin_Ext class
	 */
	class Kava_Extra_Elementor_Plugin_Ext {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Init page
		 */
		public function init() {

			/**
			 * Nucleo Mini Package
			 */
			if ( filter_var( kava_extra_settings()->get( 'nucleo-mini-package' ), FILTER_VALIDATE_BOOLEAN ) ) {
				$this->add_nucleo_icons_set();
			}
		}

		public function add_nucleo_icons_set() {
			add_action( 'elementor/controls/controls_registered', array( $this, 'nucleo_icons_to_icon_control' ), 20 );
			add_action( 'elementor/editor/before_enqueue_styles', array( $this, 'enqueue_icon_font' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_icon_font' ) );

			add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_nucleo_icons_tab' ) );
		}

		public function nucleo_icons_to_icon_control( $controls_manager ) {
			$default_icons = $controls_manager->get_control( 'icon' )->get_settings( 'options' );

			$nc_mini_icons_data = array(
				'icons'  => $this->get_nc_mini_icons_set(),
				'format' => 'nc-icon-outline %s',
			);

			$nc_mini_icons_array = array();

			foreach ( $nc_mini_icons_data['icons'] as $icon ) {
				$key = sprintf( $nc_mini_icons_data['format'], $icon );
				$nc_mini_icons_array[ $key ] = $icon;
			}

			$new_icons = array_merge( $default_icons, $nc_mini_icons_array );

			$controls_manager->get_control( 'icon' )->set_settings( 'options', $new_icons );
		}

		/**
		 * Get nc_mini icons set.
		 *
		 * @return array
		 */
		public function get_nc_mini_icons_set() {
			$nc_mini_icons = [];

			ob_start();

			include kava_extra()->plugin_path( 'assets/fonts/nucleo-outline-icon-font/nucleo-outline.css' );

			$result = ob_get_clean();

			preg_match_all( '/\.([-_a-zA-Z0-9]+):before[, {]/', $result, $matches );

			if ( ! is_array( $matches ) || empty( $matches[1] ) ) {
				return;
			}
			$nc_mini_icons = $matches[1];

			return $nc_mini_icons;
		}

		/**
		 * Enqueue icon font.
		 */
		public function enqueue_icon_font() {
			wp_enqueue_style(
				'kava-extra-nucleo-outline',
				kava_extra()->plugin_url( 'assets/fonts/nucleo-outline-icon-font/nucleo-outline.css' ),
				array(),
				'1.0.0'
			);
		}

		public function add_nucleo_icons_tab( $tabs = array() ) {
			$tabs['nc-icon-outline'] = array(
				'name'          => 'nc-icon-outline',
				'label'         => esc_html__( 'Nucleo Mini', 'kava-extra' ),
				'labelIcon'     => 'nc-icon-outline design_path-minus',
				'prefix'        => '',
				'displayPrefix' => 'nc-icon-outline',
				'fetchJson'     => kava_extra()->plugin_url( 'assets/fonts/nucleo-outline-icon-font/nucleo-outline.json' ),
				'ver'           => '1.0.0',
			);

			return $tabs;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
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

/**
 * Returns instance of Kava_Extra_Elementor_Plugin_Ext
 *
 * @return object
 */
function kava_extra_elementor_plugin_ext() {
	return Kava_Extra_Elementor_Plugin_Ext::get_instance();
}

kava_extra_elementor_plugin_ext()->init();
