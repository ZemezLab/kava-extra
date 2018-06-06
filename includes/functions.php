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
			add_filter( 'kava-theme/breadcrumbs/breadcrumbs-visibillity', array( $this, 'breadcrumbs_visibillity' ) );
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
