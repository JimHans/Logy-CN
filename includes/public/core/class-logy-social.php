<?php

class Logy_Social {

	protected $logy;

	function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

		// Actions.
		add_action( 'init', array( $this, 'process_authentication' ) );
	}

	/**
	 * Authenticate User.
	 */
	public function process_authentication() {

	    // Get Data.
	    $mode = $this->get_mode();
	    $action = $this->get_action();
	    $provider = $this->get_provider();
	    $redirect_to = $this->get_redirect_url();

	   	// Check if Action Valid and User Not Already Connected.
	    if ( 'logy' != $action || is_user_logged_in() ) {
	        return false;
	    }

	    // Check if Social Login is Enabled.
	    if ( ! logy_is_social_login_enabled() ) {
	    	// Redirect User.
	    	$this->redirect( 'login', 'social_auth_unavailable' );
	    }

	    // Check if The Provider Allowed.
	    if ( ! in_array( $provider, logy_get_providers() ) ) {
	    	// Redirect User.
	    	$this->redirect( 'login', 'network_unavailable' );
	    }
	    
	    // Inculue Files.
	    require_once( LOGY_PATH . 'library/Hybrid/Auth.php' );
	    require_once( LOGY_PATH . 'library/Hybrid/Endpoint.php' );

	    try {

			// Create an Instance with The Config Data.
	        $hybridauth = new Hybrid_Auth( $this->get_config( $provider ) );

	        // Start the Authentication Process.
	        $adapter = $hybridauth->authenticate( $provider );

    	} catch( Exception $e ) {

    		$this->redirect( 'login', $e );

    	}

        if ( 'login' == $mode ) {

        	// Get User Data.
			$user_data = $this->get_user_data( $provider );

	        if ( ! empty( $user_data['user_id'] ) ) {
	        	// Process Login ...
	        	$this->authenticate_user( $user_data['user_id'], $redirect_to );
	        } else {

				// Check if Registration is Enabled.
				if ( ! get_option( 'users_can_register' ) ) {
	        		$this->redirect( 'login', 'registration_disabled' );
				}

				// Get User Profile Data.
				$user_profile = $user_data['user_profile'];

				// Create User.
	        	$user_id = $this->create_new_user( $user_profile );

	        	if ( $user_id > 0 ) {
	        		// Store All User Social Account Data In Database.
	        		$this->logy->query->store_user_data( $user_id, $provider, $user_profile );
	        	}

	        	// Process Login ...
	        	$this->authenticate_user( $user_id, $redirect_to );

	        }

        }

    }

	/**
	 * Create New User.
	 */
	public function create_new_user( $user ) {

		// Get Display Name.
		$display_name = $this->get_display_name( $user->displayName, $user->firstName, $user->lastName );

		// Get Unique Username.
		$user_login = $this->get_unique_username( $display_name );

		// Get Unique Email.
		$user_email = $this->get_unique_email( $user->email );

		// Init Vars.
		$required_fields = array();

		/**
		 * In case we coudn't get the user login or email we will ask the user to
		 * provide them manually.
		 */

		// Ask User to enter username.
		if ( empty( $user_login ) || username_exists( $user_login ) ) {
			$required_fields['user_login'] = true;
		}

		// Ask User to enter email.
		if ( empty( $user_email ) ) {
			$required_fields['email'] = true;
		}

		// Redirect User to Registration Page to Complete Required Fields.
		if ( ! empty( $required_fields ) ) {
			// Get Provider.
			$required_fields['provider'] = $this->get_provider();
			// Store User Data.
			logy_user_session_data( 'set', $required_fields );
			// REDIRECT User.
			$this->redirect( 'action', 'complete-registration', logy_page_url( 'register' ) );
		}

		// Prepare User Data
		$user_data = array(
			'user_login'    => $user_login,
			'user_email'    => $user_email,
			'display_name'  => $display_name,
			'first_name'    => $user->firstName,
			'last_name'     => $user->lastName,
			'description'   => $user->description,
			'user_pass'     => wp_generate_password(),
			'user_url'      => $this->get_user_url( $user->webSiteURL, $user->profileURL ),
		);

		// Insert User and Get User ID.
		$user_id = $this->logy->register->insert_new_user( $user_data );

		return $user_id;
	}

	/**
	 * Get User Data.
	 */
	public function get_user_data( $provider ) {
		
    	// Get User Profile Data.
		$user_profile = $this->get_user_profile( $provider );

		// Create Logy Session.
		$user_profile_data = logy_user_profile_data( 'get' );
		if ( empty( $user_profile_data ) && ! empty( $user_profile ) ) {
			logy_user_profile_data( 'set', $user_profile );
		}
		
		/**
		 * Check if User Exist in Logy Database
		 */

        // Get User Identifier.
        $uid = $user_profile->identifier;
        $user_email = $user_profile->emailVerified;

        // Get User ID.
        $user_id = $this->logy->query->get_user_by_provider_and_uid( $provider, $uid );
		
		/**
		 * Check if User Already Registered
		 */
		if ( ! $user_id && ! empty( $user_email ) ) {

			// Get User ID
			$user_id = email_exists( $user_email );

			// Get User ID By Verified Email.
			if ( ! $user_id ) {
				$user_id = $this->logy->query->get_user_verified_email( $user_email );
			}
		}
        
        // Get User Data.
        $user_data = array( 'user_id' => $user_id, 'user_profile' => $user_profile );

        // Return Data.
		return $user_data;
	}
	 
	/**
	 * Complete Registration.
	 */
	public function complete_registration( $username, $email ) {

		// Init Vars.
		$errors = new WP_Error();
		$error_msg = $this->logy->register;

	 	if ( ! empty( $email ) ) {

			// Check if email field is empty.
			if ( empty( $email ) ) {
				$errors->add( 'empty_fields', $error_msg->get_error_message( 'empty_fields' ) );
				return $errors;
			}

			// Check If Email is Valid
			if ( ! is_email( $email ) ) {
				$errors->add( 'email', $error_msg->get_error_message( 'email' ) );
				return $errors;
			}

			// Check if email Already Exist.
			if ( email_exists( $email ) ) {
				$errors->add( 'email_exists', $error_msg->get_error_message( 'email_exists' ) );
				return $errors;
			}

	 	}

	 	if ( ! empty( $username ) ) {
		 		
			// Check if username field is empty.
			if ( empty( $username ) ) {
				$errors->add( 'empty_fields', $error_msg->get_error_message( 'empty_fields' ) );
				return $errors;
			}

			// Check if the username already exists.
			if ( username_exists( $username ) ) {
				$errors->add( 'username_exists', $error_msg->get_error_message( 'username_exists' ) );
				return $errors;
			}

			// Check if the username is too short ( less than 4 characters ).
			if ( 4 > strlen( $username ) ) {
				$errors->add( 'username_length', $error_msg->get_error_message( 'username_length' ) );
				return $errors;
			}

			// Check if the username is valid.
			if ( ! validate_username( $username ) ) {
				$errors->add( 'username_invalid', $error_msg->get_error_message( 'username_invalid' ) );
				return $errors;
			}

	 	}

	 	// Get User Profile Data
		$user_profile_data = logy_user_profile_data( 'get' );
		
	 	$user = ! empty( $user_profile_data ) ? json_decode( $user_profile_data ) : null;

	 	if ( empty( $user ) ) {
	        // Display Error.
	        $this->redirect( 'login', 'cant_connect' );
	 	}

	 	// Get User Email & Username.
	 	$user->email = ! empty( $email ) ? $email : $user->emailVerified;
	 	$user->emailVerified = ! empty( $email ) ? $email : $user->emailVerified;
	 	$user->displayName = ! empty( $username ) ? $username : $user->displayName;

		// Prepare User Data
		$user_data = array(
			'user_email'    => $user->email,
			'user_login'    => $user->displayName,
			'display_name'  => $user->displayName,
			'first_name'    => $user->firstName,
			'last_name'     => $user->lastName,
			'description'   => $user->description,
			'user_pass'     => wp_generate_password(),
			'user_url'      => $this->get_user_url( $user->webSiteURL, $user->profileURL ),
		);

		// Insert User and Get User ID.
		$user_id = $this->logy->register->insert_new_user( $user_data );

		if ( is_wp_error( $user_id ) ) {
			// Parse errors into a string and append as parameter to redirect
			$errors = join( ',', $user_id->get_error_codes() );
			$this->redirect( 'register-errors', $errors, logy_get_complete_registration_url() );
		} else {

			// Get Required Fields.
			$get_session = json_decode( logy_user_session_data( 'get' ) );

			// Store All User Social Account Data In Database.
			$this->logy->query->store_user_data( $user_id, $get_session->provider, $user );

	    	// Process Login ...
	    	$this->authenticate_user( $user_id, $redirect_to );

		}

		return $user_id;
	}

	/**
	 * Get User Website Url.
	 */
	public function get_user_url( $website, $profile ) {

		// Get Website Url.
		if ( ! empty( $website ) ) {
			return $website;
		}

		// Get User Profile Url.
		if ( ! empty( $profile ) ) {
			return $profile;
		}

		return false;
	}

	/**
	 * Get Unique Username.
	 */
	public function get_unique_username( $display_name ) {

		// Sanitize Username.
		$username = sanitize_user( $display_name, true );

		if ( empty( $username ) ) {
			return false;
		}

		// Prepare Username By Removing Dots, Spaces. 
		$username = trim( str_replace( array( ' ', '.' ), '_', $username ) );

		if ( ! username_exists( $username ) ) {
			return $username;
		}

		return false;
	}

	/**
	 * Process Login ..
	 */
	public function authenticate_user( $user_id, $redirect_to ) {

		// Get All User Data.
		$user = get_user_by( 'id', $user_id );

		// Set WP auth cookie
		wp_set_auth_cookie( $user_id, true );

		// Add WP Login Filter.
		do_action( 'wp_login', $user->user_login, $user );

		// Clear Sessions
		$this->clear_session();

		// Redirect User.
		wp_safe_redirect( home_url() );
		
		// Die.
		die();
	}

	/**
	 * Get Provide Adapter.
	 */
	function get_user_profile( $provider ) {

		// Get Adapter
		$adapter = $this->get_adapter( $provider );

        if ( $adapter->isUserConnected() ) {
        	$profile = $adapter->getUserProfile();
        	return $profile;
        }

        // Display Can't Connect to provider Message
        $this->redirect( 'login', 'cant_connect' );
	}
	
	/**
	 * Get Provide Adapter.
	 */
	function get_adapter( $provider ) {
		// Inculde Authetification.
		if ( ! class_exists( 'Hybrid_Auth', false ) ) {
	    	require_once( LOGY_PATH . 'library/Hybrid/Auth.php' );
		}

		if ( ! class_exists( 'Hybrid_Endpoint', false ) ) {
		    require_once( LOGY_PATH . 'library/Hybrid/Endpoint.php' );
		}

		// Return Adapter
		return Hybrid_Auth::getAdapter( $provider );
	}

	/**
	 * Get Unique Email.
	 */
	public function get_unique_email( $email ) {

		if ( ! empty( $email ) && is_email( $email ) ) {
			return $email;
		}

		return false;
	}

	/**
	 * Clear Logy Session's.
	 */
	public function clear_session() {

		// Clear Sessions.
		unset( $_SESSION['HA::STORE'] );
		unset( $_SESSION['HA::CONFIG'] );
		unset( $_SESSION['logy::profile'] );
		logy_user_session_data( 'delete' );
		logy_user_profile_data( 'delete' );

	}

	/**
	 * Get User Display Name.
	 */
	function get_display_name( $display_name , $first_name, $last_name ) {

		if ( ! empty( $display_name ) ) {
			return $display_name;
		}

		// Init Display Name.
		$display_name = null;

		if ( ! empty( $first_name ) ) {
			$display_name[] = $first_name;	
		}

		if ( ! empty( $last_name ) ) {
			$display_name[] = $last_name;	
		}

    	return implode( ' ' , array_filter( $display_name ) );
	}

	/**
	 * Get Config Data.
	 */
	public function get_config( $provider ) {

		// Get Config Data.
		$config = array(
	        'base_url'   => home_url( '/' ),
	        "debug_mode" => true,
	        "debug_file" => 'debug.txt'
	    );

		// Get Provider Data.
        $provider_data = logy_get_provider_data( $provider );

        // Check if network use key or id.
        $key_or_id = $provider_data['app'];

        // Transform Provider name to lowercase
        $network = strtolower( $provider );

		// Get Network status 
		$network_status = logy_options( 'logy_' . $network . '_app_status' );

		// Check if network is enabled.
		$is_enabled = ( 'off' == $network_status ) ? false: true;

		// Get Networks Params. 
		$network_params = array(
			'enabled' => $is_enabled,
			'keys' => array(
				$key_or_id  => logy_options( 'logy_' . $network .'_app_key' ),
				'secret' 	=> logy_options( 'logy_' . $network .'_app_secret' ),
			)
		);

		if ( 'Google' == $provider ) {
			$network_params['redirect_uri'] = home_url( '/?hauth.done=Google' );
			$network_params['scope'] = "https://www.googleapis.com/auth/userinfo.profile ".
		    "https://www.googleapis.com/auth/userinfo.email";
		}

		// Get Providers Data.
		$config['providers'] = array( $provider => $network_params );

		// Return Data
		return $config;
	}

	/**
	 * Get Provider.
	 */
	public function get_provider() {
	    $provider = isset( $_REQUEST['provider'] ) ? sanitize_text_field( $_REQUEST['provider'] ) : null;	
		return $provider;
	}

	/**
	 * Get Mode.
	 */
	public function get_mode() {
	    $mode = isset( $_REQUEST['mode'] ) ? sanitize_text_field( $_REQUEST['mode'] ) : 'login';
		return $mode;
	}

	/**
	 * Get Action.
	 */
	public function get_action() {
	    $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : null;
		return $action;
	}

	/**
	 * Get Redirect Url.
	 */
	public function get_redirect_url() {
		$redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? $_REQUEST[ 'redirect_to' ] : home_url();
		return $redirect_to;
	}

	/**
	 * Redirect User.
	 */
	public function redirect( $action, $code, $redirect_to = null ) {

	    // Get Reidrect page.
	    $redirect_to = ! empty( $redirect_to ) ? $redirect_to : logy_page_url( 'login' );

	    // Get Redirect Url.
		$redirect_url = add_query_arg( $action, $code, $redirect_to );

		// Redirect User.
        wp_redirect( $redirect_url );

        // Exit.
        exit;

	}

}