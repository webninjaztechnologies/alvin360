<?php
/**
 * Elementor Widget.
 *
 * @package WP_Story_Premium
 */

namespace Wpstory\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Wpstory_Elementor_Widget
 *
 * @package Wpstory\Widgets
 */
class Wpstory_Elementor_Widget extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'wpstory';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.2.0
	 *
	 * @access public
	 */
	public function get_title() {
		return esc_html__( 'WP Story', 'wp-story-premium' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.2.0
	 *
	 * @access public
	 */
	public function get_icon() {
		return 'eicon-instagram-post';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 * @since 1.2.0
	 *
	 * @access public
	 */
	public function get_categories() {
		return array( 'general' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.2.0
	 *
	 * @access protected
	 */
	protected function register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		$this->start_controls_section(
			'box_section',
			array(
				'label' => esc_html__( 'Story Box', 'wp-story-premium' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'box',
			array(
				'label'   => esc_html__( 'Select Story Box', 'wp-story-premium' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 0,
				'options' => wpstory_premium_helpers()->get_story_boxes( true ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.2.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
			<style>
				.wp-story-preview {
					background-color: #f8f3ef;
					padding: 60px 45px;
					color: #000;
					border-radius: 3px;
					position: relative;
					overflow: hidden;
					text-align: center;
				}

				.wp-story-preview::after {
					content: '';
					position: absolute;
					background-image: url(<?php echo esc_url( WPSTORY_DIR . 'integrations/gutenberg/dist/files/instagram.svg' ); ?>);
					background-size: contain;
					bottom: -20px;
					right: -20px;
					width: 200px;
					height: 200px;
					transform: rotate(-20deg);
					opacity: 0.1;
				}

				.wp-story-preview .wp-story-shortcode {
					font-weight: 700;
					font-size: 18px;
				}
			</style>
			<div class="wp-story-preview">
				<span class="wp-story-shortcode">
					<?php if ( empty( $settings['box'] ) ) : ?>
						<?php esc_html_e( 'Select a Story Box', 'wp-story-premium' ); ?>
					<?php else : ?>
						[wp-story id="<?php echo esc_html( $settings['box'] ); ?>"]
					<?php endif; ?>
				</span>
			</div>
			<?php
		} else {
			echo do_shortcode( '[wpstory id="' . $settings['box'] . '"]' );
		}
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.2.0
	 *
	 * @access protected
	 */
	protected function content_template() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		?>
		<style>
			.wp-story-preview {
				background-color: #f8f3ef;
				padding: 60px 45px;
				color: #000;
				border-radius: 3px;
				position: relative;
				overflow: hidden;
				text-align: center;
			}

			.wp-story-preview::after {
				content: '';
				position: absolute;
				background-image: url(<?php echo esc_url( WPSTORY_DIR . 'integrations/gutenberg/dist/files/instagram.svg' ); ?>);
				background-size: contain;
				bottom: -20px;
				right: -20px;
				width: 200px;
				height: 200px;
				transform: rotate(-20deg);
				opacity: 0.1;
			}

			.wp-story-preview .wp-story-shortcode {
				font-weight: 700;
				font-size: 18px;
			}
		</style>
		<div class="wp-story-preview">
			<span class="wp-story-shortcode">
				<# if( settings.box * 1 !== 0 ) { #>
				[wp-story id="{{{ settings.box }}}"]
				<# } else { #>
				<?php esc_html_e( 'Select a Story Box', 'wp-story-premium' ); ?>
				<# } #>
			</span>
		</div>
		<?php
	}
}
