<?php

/**
 * # Register Global Scripts .
 */
function logy_global_scripts() {

    global $Logy_Translation;

    // Get Data.
    $jquery = array( 'jquery' );

    // Register Panel Styles.
    wp_register_style( 'logy-panel-style',  LOGY_AA . 'css/klabs-panel-style.css' );

    // klabs Panel Scripts
    wp_register_script( 'logy-panel', LOGY_AA . 'js/klabs-panel.js', $jquery, false, true );
    wp_localize_script( 'logy-panel', 'logy', $Logy_Translation );

    // Font Awesome.
    wp_register_style( 'logy-icons', LOGY_AA . 'fonts/css/font-awesome.min.css' );

}
add_action( 'wp_loaded', 'logy_global_scripts' );

/**
 * # Register Public Scripts .
 */
function logy_public_scripts() {
    
    // Refister Fonts.
    wp_register_style( 'logy-opensans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600' );

    // Main Css.
    wp_register_style( 'logy-style', LOGY_PA . 'css/logy.css', array( 'logy-opensans', 'logy-icons' ) );

    // Call Style
    wp_enqueue_style( 'logy-style' );
}

add_action( 'wp_enqueue_scripts', 'logy_public_scripts' );