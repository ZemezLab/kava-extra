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

if ( ! class_exists( 'Kava_Extra_Settings' ) ) {

	/**
	 * Define Kava_Extra_Settings class
	 */
	class Kava_Extra_Settings {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * [$key description]
		 * @var string
		 */
		public $key = 'kava-extra-settings';

		/**
		 * [$builder description]
		 * @var null
		 */
		public $interface_builder  = null;

		/**
		 * [$settings description]
		 * @var null
		 */
		public $settings = null;

		/**
		 * Init page
		 */
		public function init() {

			add_action( 'admin_enqueue_scripts', array( $this, 'init_interface_builder' ), 0 );

			add_action( 'admin_menu', array( $this, 'register_page' ), 99 );
			add_action( 'init', array( $this, 'save' ), 40 );
			add_action( 'admin_notices', array( $this, 'saved_notice' ) );
		}

		/**
		 * [init_interface_builder description]
		 *
		 * @return [type] [description]
		 */
		public function init_interface_builder() {

			if ( isset( $_REQUEST['page'] ) && $this->key === $_REQUEST['page'] ) {

				$builder_data = kava_extra()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

				$this->interface_builder = new CX_Interface_Builder(
					array(
						'path' => $builder_data['path'],
						'url'  => $builder_data['url'],
					)
				);
			}

		}

		/**
		 * Show saved notice
		 *
		 * @return bool
		 */
		public function saved_notice() {

			if ( ! isset( $_GET['settings-saved'] ) ) {
				return false;
			}

			$message = esc_html__( 'Settings saved', 'kava-extra' );

			printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', $message );

			return true;

		}

		/**
		 * Save settings
		 *
		 * @return void
		 */
		public function save() {

			if ( ! isset( $_REQUEST['page'] ) || $this->key !== $_REQUEST['page'] ) {
				return;
			}

			if ( ! isset( $_REQUEST['action'] ) || 'save-settings' !== $_REQUEST['action'] ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$current = get_option( $this->key, array() );
			$data    = $_REQUEST;

			unset( $data['action'] );

			foreach ( $data as $key => $value ) {
				$current[ $key ] = is_array( $value ) ? $value : esc_attr( $value );
			}

			update_option( $this->key, $current );

			$redirect = add_query_arg(
				array( 'dialog-saved' => true ),
				$this->get_settings_page_link()
			);

			wp_redirect( $redirect );
			die();

		}

		/**
		 * Return settings page URL
		 *
		 * @return string
		 */
		public function get_settings_page_link() {

			return add_query_arg(
				array(
					'page' => $this->key,
				),
				esc_url( admin_url( 'admin.php' ) )
			);

		}

		/**
		 * [get description]
		 * @param  [type]  $setting [description]
		 * @param  boolean $default [description]
		 * @return [type]           [description]
		 */
		public function get( $setting, $default = false ) {

			if ( null === $this->settings ) {
				$this->settings = get_option( $this->key, array() );
			}

			return isset( $this->settings[ $setting ] ) ? $this->settings[ $setting ] : $default;
		}

		/**
		 * Register add/edit page
		 *
		 * @return void
		 */
		public function register_page() {

			add_menu_page(
				esc_html__( 'Theme', 'kava-extra' ),
				esc_html__( 'Theme', 'kava-extra' ),
				'manage_options',
				$this->key,
				array( $this, 'render_page' ),
				kava_extra()->plugin_url('assets/images/') . 'kava-theme-icon.svg',
				100
			);
		}

		/**
		 * Render settings page
		 *
		 * @return void
		 */
		public function render_page() {

			$this->interface_builder->register_section(
				array(
					'kava_extra_settings' => array(
						'type'   => 'section',
						'scroll' => false,
						'title'  => esc_html__( 'Kava Theme Settings', 'kava-extra' ),
					),
				)
			);

			$this->interface_builder->register_form(
				array(
					'kava_extra_settings_form' => array(
						'type'   => 'form',
						'parent' => 'kava_extra_settings',
						'action' => add_query_arg(
							array( 'page' => $this->key, 'action' => 'save-settings' ),
							esc_url( admin_url( 'admin.php' ) )
						),
					),
				)
			);

			$this->interface_builder->register_settings(
				array(
					'settings_top' => array(
						'type'   => 'settings',
						'parent' => 'kava_extra_settings_form',
					),
					'settings_bottom' => array(
						'type'   => 'settings',
						'parent' => 'kava_extra_settings_form',
					),
				)
			);

			$this->interface_builder->register_component(
				array(
					'kava_extra_tab_vertical' => array(
						'type'   => 'component-tab-vertical',
						'parent' => 'settings_top',
					),
				)
			);

			$this->interface_builder->register_settings(
				array(
					'general_tab' => array(
						'parent'      => 'kava_extra_tab_vertical',
						'title'       => esc_html__( 'General', 'kava-extra' ),
					),
					/*'advanced_tab' => array(
						'parent'      => 'kava_extra_tab_vertical',
						'title'       => esc_html__( 'Advanced', 'kava-extra' ),
					),*/
				)
			);

			$controls = apply_filters( 'kava-extra/settings-page/controls-list',
				array(
					'nucleo-mini-package' => array(
						'type'        => 'switcher',
						'parent'      => 'general_tab',
						'title'       => esc_html__( 'use nucleo-mini icon package', 'kava-extra' ),
						'description' => esc_html__( 'Add nucleo-mini icon package to Elementor icon picker control', 'kava-extra' ),
						'value'       => $this->get( 'nucleo-mini-package' ),
						'toggle'      => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
						),
					),
				)
			);

			$this->interface_builder->register_control( $controls );

			$this->interface_builder->register_html(
				array(
					'save_button' => array(
						'type'   => 'html',
						'parent' => 'settings_bottom',
						'class'  => 'cherry-control dialog-save',
						'html'   => '<button type="submit" class="cx-button cx-button-primary-style">' . esc_html__( 'Save', 'kava-extra' ) . '</button>',
					),
				)
			);

			echo '<div class="kava-extra-settings-page">';
				$this->interface_builder->render();
			echo '</div>';
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
 * Returns instance of Jet_Elements_Settings
 *
 * @return object
 */
function kava_extra_settings() {
	return Kava_Extra_Settings::get_instance();
}

kava_extra_settings()->init();
