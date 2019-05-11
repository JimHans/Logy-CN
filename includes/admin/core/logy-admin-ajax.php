<?php

class Logy_Admin_Ajax {

	function __construct() {

		// Save Settings
		add_action( 'wp_ajax_logy_admin_save_data',  array( &$this, 'save_settings' ) );

		// Reset Settings
		add_action( 'wp_ajax_logy_reset_settings',  array( &$this, 'reset_settings' ) );

	}

	/**
	 * # Save Settings With Ajax.
	 */
	function save_settings() {

		check_ajax_referer( 'logy-settings-data', 'security' );

		// Get Data.
		$data = $_POST;

		unset( $data['security'], $data['action'] );

	    // Panel options
	    $options = $data['logy_options'];
	    $logy_pages = $data['logy_pages'];

	    // Save Options
	    if ( $options ) {
		    foreach ( $options as $option => $value ) {
		    	// Get Option Value
		        if ( ! is_array( $value ) ) {
		        	$the_value = stripslashes( $value );
		        } else {
		        	$the_value = $value;
		        }
		        // Save Option or Delete Option if Empty
		        if ( isset( $option ) ) {
		        	update_option( $option, $the_value );
		        } else {
		        	delete_option( $option );
		        }
		    }
	    }

		// Save Registration Value
		$register_opts = 'users_can_register';
        if ( isset( $options[ $register_opts ] ) ) {
	    	if ( 'on' == $options[ $register_opts ] ) {
	    		update_option( $register_opts, 1 );
	    	} else {
	    		update_option( $register_opts, 0 );
	    	}
	    }

	    // If User want to save Plugin Pages.
	    if ( isset( $logy_pages ) ) {
		    $this->save_pages( $logy_pages );
	    }

	   	die( '1' );

	}
	
	/**
	 * # Save Pages.
	 */
	function save_pages( $logy_pages ) {

		// Get How much time page is repeated.
		$page_counts = array_count_values( $logy_pages );

		// if page is already used show error messsage.
		foreach ( $page_counts as $id => $nbr ) {
			if ( $nbr > 1 ) {
				die( __( 'You are using same page more than ones.', 'logy' ) );
			}
		}

		// Update Pages in Database.
		$update_pages = update_option( 'logy_pages', $logy_pages );

		if ( $update_pages ) {
			foreach ( $logy_pages as $page => $id ) {
				// Update Option ID
				update_option( $page, $id );
			}
		}
	}

	/**
	 * Reset Settings
	 */
	function reset_settings() {

		// Get Reset Type.
		$reset_type = sanitize_text_field( $_POST['reset_type'] );

	    if ( 'tab' == $reset_type ) {
			check_ajax_referer( 'logy-settings-data', 'security' );
	    	$result  = $this->reset_tab_settings( $_POST['logy_options'] );
	    } elseif ( 'all' == $reset_type ) {
	    	$result  = $this->reset_all_settings();
	    }

	}

	/**
	 * Reset All Settings.
	 */
	function reset_all_settings() {
		
		global $Logy;

		// Get Default Options.
		$default_options = $Logy->setup->standard_options();

		// Reset Options
		foreach ( $default_options as $option => $value ) {
			if ( get_option( $option ) ) {
				delete_option( $option );
			}
		}

		// Reset Styling Input's
        foreach ( $Logy->styling->styles_data() as $key ) {
			if ( get_option( $key['id'] ) ) {
				delete_option( $key['id'] );
			}
        }

		// Get Providers.
		$providers = logy_get_providers();

		// Reset Social Provider Input's.
        foreach ( $providers as $provider ) {

        	// Transform Provider Name to lower case.
        	$provider = strtolower( $provider );

        	// Reset Provider Status's
			if ( get_option( 'logy_' . $provider . '_app_status' ) ) {
				delete_option( 'logy_' . $provider . '_app_status' );
			}

        	// Reset Provider Keys.
			if ( get_option( 'logy_' . $provider . '_app_key' ) ) {
				delete_option( 'logy_' . $provider . '_app_key' );
			}

        	// Reset Provider Secret Keys.
			if ( get_option( 'logy_' . $provider . '_app_secret' ) ) {
				delete_option( 'logy_' . $provider . '_app_secret' );
			}

        	// Reset Provider Notes.
			if ( get_option( 'logy_' . $provider .'_setup_steps' ) ) {
				delete_option( 'logy_' . $provider .'_setup_steps' );
			}

        }

		// Specific Options
		$specific_options = array(
			// Upload Fields
			'logy_login_cover',
			'logy_signup_cover',
			'logy_lostpswd_cover'
		);

		// Reset Specific Options
		foreach ( $specific_options as $option ) {
			if ( get_option( $option ) ) {
				delete_option( $option );
			}
		}

		die( '1' );
	}

	/**
	 * Reset Current Tab Settings.
	 */
	function reset_tab_settings( $tab_options ) {

		if ( empty( $tab_options ) ) {
			return false;
		}

		// Reset Tab Options
		foreach ( $tab_options as $option => $value ) {
			if ( get_option( $option ) ) {
				delete_option( $option );
			}
		}

		die( '1' );
	}	
}