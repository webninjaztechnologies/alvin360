<?php
/**
 * The template for the main panel container.
 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
 *
 * @author      Redux Framework
 * @package     ReduxFramework/Templates
 * @version:    4.3.6
 */

$expanded = ( $this->parent->args['open_expanded'] ) ? ' fully-expanded' : ( ! empty( $this->parent->args['class'] ) ? ' redux-content  ' . esc_attr( $this->parent->args['class'] ) : '' );
$nonce    = wp_create_nonce( 'redux_ajax_nonce' . $this->parent->args['opt_name'] );
$actionn  = ( 'network' === $this->parent->args['database'] && $this->parent->args['network_admin'] && is_network_admin() ? './edit.php?action=redux_' . $this->parent->args['opt_name'] : './options.php' );

// Last tab?
$this->parent->options['last_tab'] = ( isset( $_GET['tab'] ) && ! isset( $this->parent->transients['last_save_mode'] ) ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification


	echo '<div class="redux-container'. esc_attr( $expanded ) .'">';

		echo '<form method="post" action="'. esc_attr( $actionn ) .'" data-nonce="'. esc_attr( $nonce ).'"
			enctype="multipart/form-data"
			class="redux-form-wrapper"
			data-opt-name="'. esc_attr( $this->parent->args['opt_name'] ) .'">';
			echo '<input
				type="hidden" id="redux-compiler-hook"
				name="'. esc_attr( $this->parent->args['opt_name'] ) .'[compiler]"
				value=""/>';
			echo '<input
				type="hidden" id="currentSection"
				name="'. esc_attr( $this->parent->args['opt_name'] ) .'[redux-section]"
				value=""/>';
			 if ( ! empty( $this->parent->options_class->no_panel ) ) { 
				echo '<input
					type="hidden"
					name="'. esc_attr( $this->parent->args['opt_name'] ).'[redux-no_panel]"
					value="'. esc_attr( implode( '|', $this->parent->options_class->no_panel ) ) .'"/>';
			 } 
			$this->init_settings_fields(); // Must run or the page won't redirect properly. 
			echo '<input
				type="hidden" id="last_tab"
				name="'. esc_attr( $this->parent->args['opt_name'] ).'[last_tab]"
				value="'. esc_attr( $this->parent->options['last_tab'] ).'"/>';
			$this->get_template( 'content.tpl.php' ); 
		echo '</form>
	</div>';

if ( isset( $this->parent->args['footer_text'] ) ) { 
	echo '<div id="redux-sub-footer">'. wp_kses_post( $this->parent->args['footer_text'] ) .'</div>';
 } ?>
