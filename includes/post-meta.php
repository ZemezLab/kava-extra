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

if ( ! class_exists( 'Kava_Extra_Post_Meta' ) ) {

	/**
	 * Define Kava_Extra_Post_Meta class
	 */
	class Kava_Extra_Post_Meta {

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
			$this->kava_extra_meta_init();
		}

		/**
		 * [kava_extra_meta_init description]
		 * @return [type] [description]
		 */
		public function kava_extra_meta_init() {
			new Cherry_X_Post_Meta( array(
				'id'            => 'template-settings',
				'title'         => esc_html__( 'Post Formats Settings', 'kava_extra' ),
				'page'          => array( 'post', 'page' ),
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => false,
				'builder_cb'    => array( $this, 'kava_extra_get_interface_builder' ),
				'fields'        => array(
					'post-formats' => array(
						'type'        => 'component-tab-horizontal'
					),
					'gallery_tab' => array(
						'type'        => 'settings',
						'parent'      => 'post-formats',
						'title'       => esc_html__( 'Gallery', 'kava_extra' ),
					),
					'gallery_images' => array(
						'type'               => 'media',
						'parent'             => 'gallery_tab',
						'title'              => esc_html__( 'Image Gallery', 'kava_extra' ),
						'description'        => esc_html__( 'Choose image(s) for the gallery. This setting is used for your gallery post formats.', 'kava_extra' ),
						'library_type'       => 'image',
						'upload_button_text' => esc_html__( 'Set Gallery Images', 'kava_extra' ),
					),
					'link_tab' => array(
						'type'        => 'settings',
						'parent'      => 'post-formats',
						'title'       => esc_html__( 'Link', 'kava_extra' ),
					),
					'link' => array(
						'type'        => 'text',
						'parent'      => 'link_tab',
						'title'       => esc_html__( 'Link URL', 'kava_extra' ),
						'description' => esc_html__( 'Enter your external url. This setting is used for your link post formats.', 'kava_extra' ),
					),
					'link_target' => array(
						'type'        => 'select',
						'parent'      => 'link_tab',
						'title'       => esc_html__( 'Link Target', 'kava_extra' ),
						'description' => esc_html__( 'Choose your target for the url. This setting is used for your link post formats.', 'kava_extra' ),
						'value'       => '_blank',
						'options'     => array(
							'_blank' => 'Blank',
							'_self'  => 'Self'
						)
					),
					'quote_tab' => array(
						'type'        => 'settings',
						'parent'      => 'post-formats',
						'title'       => esc_html__( 'Quote', 'kava_extra' ),
					),
					'quote_text' => array(
						'type'        => 'textarea',
						'parent'      => 'quote_tab',
						'title'       => esc_html__( 'Quote', 'kava_extra' ),
						'description' => esc_html__( 'Enter your quote. This setting is used for your quote post formats.', 'kava_extra' ),
					),
					'quote_cite' => array(
						'type'        => 'text',
						'parent'      => 'quote_tab',
						'title'       => esc_html__( 'Cite', 'kava_extra' ),
						'description' => esc_html__( 'Enter the quote source. This setting is used for your quote post formats.', 'kava_extra' ),
					),
					'audio_tab' => array(
						'type'        => 'settings',
						'parent'      => 'post-formats',
						'title'       => esc_html__( 'Audio', 'kava_extra' ),
					),
					'audio' => array(
						'type'               => 'media',
						'parent'             => 'audio_tab',
						'title'              => esc_html__( 'Audio', 'kava_extra' ),
						'description'        => esc_html__( 'Add audio from the media library. This setting is used for your audio post formats.', 'kava_extra' ),
						'library_type'       => 'audio',
						'multi_upload'       => false,
						'upload_button_text' => esc_html__( 'Set audio', 'kava_extra' ),
					),
					'audio_loop' => array(
						'type'        => 'switcher',
						'parent'      => 'audio_tab',
						'title'       => esc_html__( 'Loop', 'kava_extra' ),
						'description' => esc_html__( 'Allows for the looping of media.', 'kava_extra' ),
						'value'       => false,
					),
					'audio_autoplay' => array(
						'type'        => 'switcher',
						'parent'      => 'audio_tab',
						'title'       => esc_html__( 'Autoplay', 'kava_extra' ),
						'description' => esc_html__( 'Causes the media to automatically play as soon as the media file is ready.', 'kava_extra' ),
						'value'       => false,
					),
					'audio_preload' => array(
						'type'        => 'switcher',
						'parent'      => 'audio_tab',
						'title'       => esc_html__( 'Preload', 'kava_extra' ),
						'description' => esc_html__( 'Specifies if and how the audio should be loaded when the page loads.', 'kava_extra' ),
						'value'       => false,
					),
					'video_tab' => array(
						'type'        => 'settings',
						'parent'      => 'post-formats',
						'title'       => esc_html__( 'Video', 'kava_extra' ),
					),
					'video_type' => array(
						'type'        => 'radio',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'Video Source Type', 'kava_extra' ),
						'description' => esc_html__( 'Choose video source type. This setting is used for your video post formats.', 'kava_extra' ),
						'value'       => 'library',
						'options' => array(
							'library' => array(
								'label' => 'Media Library',
							),
							'external' => array(
								'label' => 'External Video',
							)
						),
					),
					'video_library' => array(
						'type'               => 'media',
						'parent'             => 'video_tab',
						'title'              => esc_html__( 'Library Video', 'kava_extra' ),
						'description'        => esc_html__( 'Add video from the media library. This setting is used for your video post formats.', 'kava_extra' ),
						'library_type'       => 'video',
						'multi_upload'       => false,
						'upload_button_text' => esc_html__( 'Set Video', 'kava_extra' ),
						'conditions'         => array(
							'video_type' => 'library',
						),
					),
					'video_external' => array(
						'type'        => 'text',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'External Video URL', 'kava_extra' ),
						'description' => esc_html__( 'Enter a URL that is compatible with WP built-in oEmbed feature. This setting is used for your video post formats.', 'kava_extra' ),
						'conditions'  => array(
							'video_type' => 'external',
						),
					),
					'video_poster' => array(
						'type'               => 'media',
						'parent'             => 'video_tab',
						'title'              => esc_html__( 'Video Poster', 'kava_extra' ),
						'description'        => esc_html__( 'Defines image to show as placeholder before the media plays.', 'kava_extra' ),
						'library_type'       => 'image',
						'multi_upload'       => false,
						'upload_button_text' => esc_html__( 'Set Poster', 'kava_extra' ),
					),
					'video_width' => array(
						'type'        => 'stepper',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'Width', 'kava_extra' ),
						'description' => esc_html__( 'Defines width of the media.', 'kava_extra' ),
						'value'       => 770,
						'max_value'   => 1200,
						'min_value'   => 100,
					),
					'video_height' => array(
						'type'        => 'stepper',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'Height', 'kava_extra' ),
						'description' => esc_html__( 'Defines height of the media.', 'kava_extra' ),
						'value'       => 480,
						'max_value'   => 1200,
						'min_value'   => 100,
					),
					'video_loop' => array(
						'type'        => 'switcher',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'Loop', 'kava_extra' ),
						'description' => esc_html__( 'Allows for the looping of media.', 'kava_extra' ),
						'value'       => false,
					),
					'video_autoplay' => array(
						'type'        => 'switcher',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'Autoplay', 'kava_extra' ),
						'description' => esc_html__( 'Causes the media to automatically play as soon as the media file is ready.', 'kava_extra' ),
						'value'       => false,
					),
					'video_preload' => array(
						'type'        => 'switcher',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'Preload', 'kava_extra' ),
						'description' => esc_html__( 'Specifies if and how the video should be loaded when the page loads.', 'kava_extra' ),
						'value'       => false,
					),
				),
			) );
		}

		public function kava_extra_get_interface_builder() {
			return new CX_Interface_Builder(
				array(
					'path' => kava_extra()->plugin_path( 'framework/modules/interface-builder/' ),
					'url'  => kava_extra()->plugin_url( 'framework/modules/interface-builder/' ),
				)
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

function kava_extra_post_meta() {
	return Kava_Extra_Post_Meta::get_instance();
}
