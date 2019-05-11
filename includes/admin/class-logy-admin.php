<?php

class Logy_Admin {

	function __construct() {

		// Init Admin Area
		$this->init();

		// Add Plugin Admin Pages.
		add_action( 'admin_menu', array( &$this, 'init_pages' ) );

		// Load Admin Scripts & Styles .
		add_action( 'admin_print_styles', array( &$this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ) );

		// Admin Pages
	    $this->admin_pages = array(
	    	'logy-panel'
	    );

	}

	/**
	 * # Initialize Admin Panel
	 */
	function init() {

		// Init Admin Files.
		require_once LOGY_ADMIN . 'core/logy-admin-dashboard.php';
		require_once LOGY_ADMIN . 'core/logy-admin-panel.php';
		require_once LOGY_ADMIN . 'core/logy-admin-ajax.php';

		// Settings .
		require_once LOGY_ADMIN . 'core/settings/logy-settings-login.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-emails.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-general.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-captcha.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-limit-login.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-registration.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-social-login.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-lost-password.php';
        require_once LOGY_ADMIN . 'core/settings/logy-settings-notifications.php';


		// Init Administration
		$this->panel = new logy_Panel();
		$this->ajax = new logy_Admin_Ajax();
		$this->dashboard = new logy_Dashboard();
	
	}

	/**
	 * # Add Admin Pages .
	 */
	function init_pages() {

		// Show Panel to Admin's Only.
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		global $submenu;

	    // Add Plugin Admin Page.
	    add_menu_page(
	    	__( 'Logy设置', 'logy' ),
	    	__( 'Logy设置', 'logy' ),
	    	'administrator',
	    	'logy-panel',
	    	array( &$this->dashboard, 'general_settings' ),
	    	LOGY_AA . 'images/icon.png'
	    );

		// Add "General Settings" Page .
	    add_submenu_page(
	    	'logy-panel',
	    	__( 'General Settings', 'logy' ),
	    	__( 'General Settings', 'logy' ),
	    	'administrator',
	    	'logy-panel',
	    	array( &$this->dashboard, 'general_settings' ),
	    	'dashicons-admin-network'
	    );

	}

	/**
	 * # Admin Scripts.
	 */
	function admin_scripts() {

		if ( ! isset( $_GET['page'] ) || ! in_array( $_GET['page'], $this->admin_pages ) ) {
			return false;
		}

        // Load Admin Panel JS
        wp_enqueue_script( 'logy-panel' );

        // Load Color Picker
		wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

        // Uploader Scripts
        wp_enqueue_media();

	}

	/**
	 * # Panel Styles.
	 */
	function admin_styles() {

		if ( ! isset( $_GET['page'] ) ) {
			return false;
		}

		// Load Admin Panel Styles
	    if ( in_array( $_GET['page'], $this->admin_pages ) ) {

	    	// Load Panel Styles.
		    wp_enqueue_style( 'logy-panel-style' );

	        // Load Google Fonts
	        wp_enqueue_style( 'logy-opensans' );
	    	
	    	// Loading Font Awesome.
		  	wp_enqueue_style( 'logy-icons' );

	    }

	}

}