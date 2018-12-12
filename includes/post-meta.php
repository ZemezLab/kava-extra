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
				'id'            => 'kava-extra-post-type-settings',
				'title'         => esc_html__( 'Post Formats Settings', 'kava-extra' ),
				'page'          => array( 'post' ),
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => false,
				'builder_cb'    => array( $this, 'kava_extra_get_interface_builder' ),
				'fields'        => array(
					'post_formats' => array(
						'type'        => 'component-tab-horizontal',
					),
					'gallery_tab' => array(
						'type'        => 'settings',
						'parent'      => 'post_formats',
						'title'       => esc_html__( 'Gallery', 'kava-extra' ),
					),
					'kava_extra_gallery_images' => array(
						'type'               => 'media',
						'parent'             => 'gallery_tab',
						'title'              => esc_html__( 'Image Gallery', 'kava-extra' ),
						'description'        => esc_html__( 'Choose image(s) for the gallery. This setting is used for your gallery post formats.', 'kava-extra' ),
						'library_type'       => 'image',
						'upload_button_text' => esc_html__( 'Set Gallery Images', 'kava-extra' ),
					),
					'link_tab' => array(
						'type'        => 'settings',
						'parent'      => 'post_formats',
						'title'       => esc_html__( 'Link', 'kava-extra' ),
					),
					'kava_extra_link' => array(
						'type'        => 'text',
						'parent'      => 'link_tab',
						'title'       => esc_html__( 'Link URL', 'kava-extra' ),
						'description' => esc_html__( 'Enter your external url. This setting is used for your link post formats.', 'kava-extra' ),
					),
					'kava_extra_link_target' => array(
						'type'        => 'select',
						'parent'      => 'link_tab',
						'title'       => esc_html__( 'Link Target', 'kava-extra' ),
						'description' => esc_html__( 'Choose your target for the url. This setting is used for your link post formats.', 'kava-extra' ),
						'value'       => '_blank',
						'options'     => array(
							'_blank' => 'Blank',
							'_self'  => 'Self'
						)
					),
					'quote_tab' => array(
						'type'        => 'settings',
						'parent'      => 'post_formats',
						'title'       => esc_html__( 'Quote', 'kava-extra' ),
					),
					'kava_extra_quote_text' => array(
						'type'        => 'textarea',
						'parent'      => 'quote_tab',
						'title'       => esc_html__( 'Quote', 'kava-extra' ),
						'description' => esc_html__( 'Enter your quote. This setting is used for your quote post formats.', 'kava-extra' ),
					),
					'kava_extra_quote_cite' => array(
						'type'        => 'text',
						'parent'      => 'quote_tab',
						'title'       => esc_html__( 'Cite', 'kava-extra' ),
						'description' => esc_html__( 'Enter the quote source. This setting is used for your quote post formats.', 'kava-extra' ),
					),
					'audio_tab' => array(
						'type'        => 'settings',
						'parent'      => 'post_formats',
						'title'       => esc_html__( 'Audio', 'kava-extra' ),
					),
					'kava_extra_audio' => array(
						'type'               => 'media',
						'parent'             => 'audio_tab',
						'title'              => esc_html__( 'Audio', 'kava-extra' ),
						'description'        => esc_html__( 'Add audio from the media library. This setting is used for your audio post formats.', 'kava-extra' ),
						'library_type'       => 'audio',
						'multi_upload'       => false,
						'upload_button_text' => esc_html__( 'Set audio', 'kava-extra' ),
					),
					'kava_extra_audio_loop' => array(
						'type'        => 'switcher',
						'parent'      => 'audio_tab',
						'title'       => esc_html__( 'Loop', 'kava-extra' ),
						'description' => esc_html__( 'Allows for the looping of media.', 'kava-extra' ),
						'value'       => false,
					),
					'kava_extra_audio_autoplay' => array(
						'type'        => 'switcher',
						'parent'      => 'audio_tab',
						'title'       => esc_html__( 'Autoplay', 'kava-extra' ),
						'description' => esc_html__( 'Causes the media to automatically play as soon as the media file is ready.', 'kava-extra' ),
						'value'       => false,
					),
					'kava_extra_audio_preload' => array(
						'type'        => 'switcher',
						'parent'      => 'audio_tab',
						'title'       => esc_html__( 'Preload', 'kava-extra' ),
						'description' => esc_html__( 'Specifies if and how the audio should be loaded when the page loads.', 'kava-extra' ),
						'value'       => false,
					),
					'video_tab' => array(
						'type'        => 'settings',
						'parent'      => 'post_formats',
						'title'       => esc_html__( 'Video', 'kava-extra' ),
					),
					'kava_extra_video_type' => array(
						'type'        => 'radio',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'Video Source Type', 'kava-extra' ),
						'description' => esc_html__( 'Choose video source type. This setting is used for your video post formats.', 'kava-extra' ),
						'value'       => 'library',
						'options' => array(
							'library' => array(
								'label' => esc_html__( 'Media Library', 'kava-extra' ),
							),
							'external' => array(
								'label' => esc_html__( 'External Video', 'kava-extra' ),
							)
						),
					),
					'kava_extra_video_library' => array(
						'type'               => 'media',
						'parent'             => 'video_tab',
						'title'              => esc_html__( 'Library Video', 'kava-extra' ),
						'description'        => esc_html__( 'Add video from the media library. This setting is used for your video post formats.', 'kava-extra' ),
						'library_type'       => 'video',
						'multi_upload'       => false,
						'upload_button_text' => esc_html__( 'Set Video', 'kava-extra' ),
						'conditions'         => array(
							'kava_extra_video_type' => 'library',
						),
					),
					'kava_extra_video_external' => array(
						'type'        => 'text',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'External Video URL', 'kava-extra' ),
						'description' => esc_html__( 'Enter a URL that is compatible with WP built-in oEmbed feature. This setting is used for your video post formats.', 'kava-extra' ),
						'conditions'  => array(
							'kava_extra_video_type' => 'external',
						),
					),
					'kava_extra_video_poster' => array(
						'type'               => 'media',
						'parent'             => 'video_tab',
						'title'              => esc_html__( 'Video Poster', 'kava-extra' ),
						'description'        => esc_html__( 'Defines image to show as placeholder before the media plays.', 'kava-extra' ),
						'library_type'       => 'image',
						'multi_upload'       => false,
						'upload_button_text' => esc_html__( 'Set Poster', 'kava-extra' ),
					),
					'kava_extra_video_width' => array(
						'type'        => 'stepper',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'Width', 'kava-extra' ),
						'description' => esc_html__( 'Defines width of the media.', 'kava-extra' ),
						'value'       => 770,
						'max_value'   => 1200,
						'min_value'   => 100,
					),
					'kava_extra_video_height' => array(
						'type'        => 'stepper',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'Height', 'kava-extra' ),
						'description' => esc_html__( 'Defines height of the media.', 'kava-extra' ),
						'value'       => 480,
						'max_value'   => 1200,
						'min_value'   => 100,
					),
					'kava_extra_video_loop' => array(
						'type'        => 'switcher',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'Loop', 'kava-extra' ),
						'description' => esc_html__( 'Allows for the looping of media.', 'kava-extra' ),
						'value'       => false,
					),
					'kava_extra_video_autoplay' => array(
						'type'        => 'switcher',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'Autoplay', 'kava-extra' ),
						'description' => esc_html__( 'Causes the media to automatically play as soon as the media file is ready.', 'kava-extra' ),
						'value'       => false,
						'conditions'  => array(
							'kava_extra_video_loop' => 'true',
						),
					),
					'kava_extra_video_preload' => array(
						'type'        => 'switcher',
						'parent'      => 'video_tab',
						'title'       => esc_html__( 'Preload', 'kava-extra' ),
						'description' => esc_html__( 'Specifies if and how the video should be loaded when the page loads.', 'kava-extra' ),
						'value'       => false,
					),
				),
			) );

			new Cherry_X_Post_Meta( array(
				'id'            => 'kava-extra-page-settings',
				'title'         => esc_html__( 'Page Settings', 'kava-extra' ),
				'page'          => array( 'page', 'post' ),
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => false,
				'builder_cb'    => array( $this, 'kava_extra_get_interface_builder' ),
				'fields'        => array(
					'kava_extra_enable_breadcrumbs' => array(
						'type'        => 'switcher',
						'title'       => esc_html__( 'Use Breadcrumbs', 'kava-extra' ),
						'description' => esc_html__( 'Breadcrumbs enable global settings redefining.', 'kava-extra' ),
						'value'       => true,
					),
				),
			) );
		}

		/**
		 * [kava_extra_get_interface_builder description]
		 *
		 * @return [type] [description]
		 */
		public function kava_extra_get_interface_builder() {

			$builder_data = kava_extra()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

			return new CX_Interface_Builder(
				array(
					'path' => $builder_data['path'],
					'url'  => $builder_data['url'],
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
