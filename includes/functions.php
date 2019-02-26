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

if ( ! class_exists( 'Kava_Extra_Functions' ) ) {

	/**
	 * Define Kava_Extra_Functions class
	 */
	class Kava_Extra_Functions {

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
			$theme_slug = kava_extra()->get_theme_slug();

			add_filter( $theme_slug . '-theme/breadcrumbs/breadcrumbs-visibillity', array( $this, 'breadcrumbs_visibillity' ) );
			add_filter( $theme_slug . '-theme/site-content/container-enabled', array( $this, 'disable_site_content_container' ) );
		}

		/**
		 * [breadcrumbs_visibillity description]
		 * @param  [type] $visibillity [description]
		 * @return [type]              [description]
		 */
		public function breadcrumbs_visibillity( $visibillity ) {
			$post_id = get_the_ID();

			$enable_breadcrumbs = get_post_meta( $post_id, 'kava_extra_enable_breadcrumbs', true );

			if ( ! filter_var( $enable_breadcrumbs, FILTER_VALIDATE_BOOLEAN ) ) {
				$visibillity = false;
			}

			return $visibillity;
		}

		/**
		 * Disable site content container
		 *
		 * @param  boolean $enabled
		 * @return boolean
		 */
		public function disable_site_content_container( $enabled = true ) {
			$disable_content_container_archive_cpt = kava_extra_settings()->get( 'disable_content_container_archive_cpt' );
			$disable_content_container_single_cpt  = kava_extra_settings()->get( 'disable_content_container_single_cpt' );

			$post_type = get_post_type();

			if ( is_archive() && isset( $disable_content_container_archive_cpt[ $post_type ] )
				&& filter_var( $disable_content_container_archive_cpt[ $post_type ], FILTER_VALIDATE_BOOLEAN )
			) {
				return false;
			}

			if ( is_search() && isset( $disable_content_container_archive_cpt['search_results'] )
				&& filter_var( $disable_content_container_archive_cpt['search_results'], FILTER_VALIDATE_BOOLEAN )
			) {
				return false;
			}

			if ( is_singular() && isset( $disable_content_container_single_cpt[ $post_type ] )
				&& filter_var( $disable_content_container_single_cpt[ $post_type ], FILTER_VALIDATE_BOOLEAN )
			) {
				return false;
			}

			if ( is_404() && isset( $disable_content_container_single_cpt['404_page'] )
				&& filter_var( $disable_content_container_single_cpt['404_page'], FILTER_VALIDATE_BOOLEAN )
			) {
				return false;
			}

			return $enabled;
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

function kava_extra_functions() {
	return Kava_Extra_Functions::get_instance();
}
