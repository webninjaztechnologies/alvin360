<?php
/**
 * Theme functions and definitions.
 */
add_action( 'wp_enqueue_scripts', 'socialv_enqueue_styles' ,99);

function socialv_enqueue_styles() {

wp_enqueue_style( 'parent-style', get_stylesheet_directory_uri() . '/style.css'); 
wp_enqueue_style( 'child-style',get_stylesheet_directory_uri() . '/style.css');
}

/**
 * Set up My Child Theme's textdomain.
*
* Declare textdomain for this child theme.
* Translations can be added to the /languages/ directory.
*/
function socialv_child_theme_setup() {
load_child_theme_textdomain( 'socialv', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'socialv_child_theme_setup' );