<?php

class Logy_Register {

	protected $logy;
	
	/**
	 * Init Registration Actions & Filters.
	 */
	public function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

		// Add "[logy_register]" Shortcode.
		add_shortcode( 'logy_register_page', array( $this, 'get_register_form' ) );

		// Init Register Actions & Filters.
		add_action( 'login_form_register', array( $this, 'redirect_to_logy_register_page' ) );
		add_action( 'login_form_register', array( $this, 'do_register_user' ) );

		// if captcha activated call the captcha javascript file.
		if ( $this->captcha_is_activated() ) {
			add_action( 'wp_print_footer_scripts', array( $this, 'add_captcha_js' ) );
		}

	}

	/**
	 * Redirects the user to the logy registration page instead
	 * of wp-login.php?action=register.
	 */
	public function redirect_to_logy_register_page() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			if ( is_user_logged_in() ) {
				$this->redirect_logged_in_user();
			} else {
				wp_redirect( logy_page_url( 'register' ) );
			}
			exit;
		}
	}

	/**
	 * Get Registration form.
	 */
	public function get_register_form() {
		if ( is_user_logged_in() ) {
			return false;
		} elseif ( ! get_option( 'users_can_register' ) ) {
			return __( 'Registering new users is currently not allowed.', 'logy' );
		} else {
			// Render the register form.
			return $this->logy->form->get_page( 'register' );
		}
	}

	/**
	 * Messages Attributes
	 */
	function messages_attributes() {
		// Retrieve possible errors from request parameters
		$attributes['errors'] = array();
		if ( isset( $_REQUEST['register-errors'] ) ) {
			$error_codes = explode( ',', $_REQUEST['register-errors'] );
			foreach ( $error_codes as $error_code ) {
				$attributes['errors'] []= $this->get_error_message( $error_code );
			}
		}

		// Retrieve recaptcha key
		$attributes['recaptcha_site_key'] = $this->captcha_is_activated() ? logy_options( 'logy_recaptcha_site_key' ) : null;

		return $attributes;
	}

	/**
	 * Attributes
	 */
	function attributes() {

		$attrs = $this->messages_attributes();

		// Add Form Type & Action to generate form class later.
		$attrs['form_type']   = 'signup';
		$attrs['form_action'] = 'signup';

		// Get Login Box Classes.
		$attrs['action_class'] = $this->get_actions_class();
		$attrs['form_class']   = $this->logy->form->get_form_class( $attrs );

		// Get Form Buttons Titles
		$attrs['link_title']   = logy_options( 'logy_signup_signin_btn_title' );
		$attrs['submit_title'] = logy_options( 'logy_signup_register_btn_title' );

		// Form Elements Visibilty Settings.
		$attrs['use_labels'] = ( false !== strpos( $attrs['form_class'], 'logy-with-labels' ) ) ? true : false;
		$attrs['use_icons']	 = ( false !== strpos( $attrs['form_class'], 'logy-fields-icon' ) ) ? true : false;

		// Form Actions Elements Visibilty Settings.
		$attrs['actions_icons']	= ( false !== strpos( $attrs['action_class'], 'logy-buttons-icons' ) ) ? true : false;

		return $attrs;
	}

	/**
	 * Handles the registration of a new user.
	 */
	public function do_register_user() {
		
		if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
			return false;
		}

		/**
		 * Save "Complete Registartion" Form Data.
		 */

	    // Get User Session Data.
	    $user_session_data = logy_user_session_data( 'get' );

		if ( 
			isset( $_POST['complete-registration'] )
			&&
			! empty( $user_session_data )
		) {

			// Get User Data
			$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : null ;
			$username = isset( $_POST['username'] ) ? sanitize_user( $_POST['username'] ) : null;

			// Complete Registration.
			$user_id = $this->logy->social->complete_registration( $username, $email );

			// Display Errors.
			if ( is_wp_error( $user_id ) ) {
				// Get Form Errors
				$errors = join( ',', $user_id->get_error_codes() );
				$this->redirect( 'register-errors', $errors, logy_get_complete_registration_url() );
			}

			die();

		}

		/**
		 * Process Normal Registartion.
		 */
		
		// Check if Registration is Enabled.
		if ( ! get_option( 'users_can_register' ) ) {
			$this->redirect( 'register-errors', 'closed' );
		}

		// Display Recaptcha Check Failed error.
		if ( ! $this->verify_recaptcha() && $this->captcha_is_activated() ) {
			$this->redirect( 'register-errors', 'captcha' );
		}

		// Get & Sanitize User Data.
		$email 		= sanitize_email( $_POST['email'] );
		$username 	= sanitize_user( $_POST['username'] );
		$first_name = sanitize_text_field( $_POST['first_name'] );
		$last_name 	= sanitize_text_field( $_POST['last_name'] );

		// Register User.
		$result = $this->register_user( $username, $email, $first_name, $last_name );

		if ( is_wp_error( $result ) ) {
			// Parse errors into a string and append as parameter to redirect
			$errors = join( ',', $result->get_error_codes() );
			$this->redirect( 'register-errors', $errors );
		} else {
			// Success, redirect to login page.
			$this->redirect( 'registered', $username, logy_page_url( 'login' ) );
		}
	
	}

	/**
	 * Validates and then completes the new user signup process if all went well.
	 */
	private function register_user( $username, $email, $first_name, $last_name ) {

		$errors = new WP_Error();

		// Check if there's any empty fields.
		if ( empty( $username ) || empty( $email ) || empty( $first_name ) || empty( $last_name ) ) {
			$errors->add( 'empty_fields', $this->get_error_message( 'empty_fields' ) );
			return $errors;
		}

		// Check if the username already exists.
		if ( username_exists( $username ) ) {
			$errors->add( 'username_exists', $this->get_error_message( 'username_exists' ) );
			return $errors;
		}

		// Check if the username is too short ( less than 4 characters ).
		if ( 4 > strlen( $username ) ) {
			$errors->add( 'username_length', $this->get_error_message( 'username_length' ) );
			return $errors;
		}

		// Check if the username is valid.
		if ( ! validate_username( $username ) ) {
			$errors->add( 'username_invalid', $this->get_error_message( 'username_invalid' ) );
			return $errors;
		}

		// Check if the first name is alphabetic.
		if ( ! ctype_alpha(	$first_name ) ) {
			$errors->add( 'first_name', $this->get_error_message( 'first_name' ) );
			return $errors;
		}

		// Check if the last name is alphabetic.
		if ( ! ctype_alpha( $last_name ) ) {
			$errors->add( 'last_name', $this->get_error_message( 'last_name' ) );
			return $errors;
		}

		// Email address is used as both username and email. It is also the only
		// parameter we need to validate
		if ( ! is_email( $email ) ) {
			$errors->add( 'email', $this->get_error_message( 'email' ) );
			return $errors;
		}

		if ( email_exists( $email ) ) {
			$errors->add( 'email_exists', $this->get_error_message( 'email_exists' ) );
			return $errors;
		}
	 
		// Prepare User Data
		$user_data = array(
			'user_email' => $email,
			'user_login' => $username,
			'first_name' => $first_name,
			'last_name'  => $last_name,
			'nickname'   => $first_name,
        	'user_pass'  => wp_generate_password( 12, false ),
		);

		// Insert User to database.
		$user_id = $this->insert_new_user( $user_data );

		return $user_id;
	}

	/**
	 * Insert New User.
	 */
	public function insert_new_user( $user_data ) {

		// Insert User to database.
		$user_id = wp_insert_user( $user_data );

		// Add User Meta
		add_user_meta( $user_id, '_logy_new_user', true );

		// Get Notification Value.
		$notify_admin = logy_options( 'logy_notify_admin_on_registration' );
		$notification = ( 'on' == $notify_admin ) ? 'both' : 'user';

		// Send Notification Message
		$this->logy->mail->new_user_notification( $user_id, $notification );

		return $user_id;
	}

	/**
	 * Checks that the reCAPTCHA parameter sent with the registration request is valid.
	 */
	private function verify_recaptcha() {

		// Get Captcha Response
		if ( ! isset ( $_POST['g-recaptcha-response'] ) ) {
			return false;
		}

		// This field is set by the recaptcha widget if check is successful
		$captcha_response = sanitize_text_field( $_POST['g-recaptcha-response'] );

		// Verify the captcha response from Google
		$response = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			array(
				'body' => array(
					'secret' 	=> logy_options( 'logy_recaptcha_secret_key' ),
					'response' 	=> $captcha_response
				)
			)
		);

		$success = false;
		if ( $response && is_array( $response ) ) {
			$decoded_response = json_decode( $response['body'] );
			$success = $decoded_response->success;
		}

		return $success;
	}

	/**
	 * Finds and returns a matching error message for the given error code.
	 */
	public function get_error_message( $error_code ) {
		switch ( $error_code ) {
			// Registration errors
			case 'empty_fields':
				return __( 'Required form field is missing.', 'logy' );

			case 'username_invalid':
				return __( 'Invalid username!', 'logy' );

			case 'username_exists':
				return __( 'That username already exists!', 'logy' );

			case 'username_length':
				return __( 'Username too short. At least 4 characters is required !', 'logy' );

			case 'email':
				return __( 'The email address you entered is not valid.', 'logy' );

			case 'email_exists':
				return __( 'An account exists with this email address.', 'logy' );

			case 'first_name':
				return __( 'First Name should be alphabetic !', 'logy' );

			case 'last_name':
				return __( 'Last Name should be alphabetic !', 'logy' );

			case 'closed':
				return __( 'Registering new users is currently not allowed.', 'logy' );

			case 'captcha':
				return __( 'The CAPTCHA check failed. Try Again !', 'logy' );
		}

		return __( 'An unknown error occurred. Please try again later.', 'logy' );
	}

	/**
	 * Form Actions Class
	 */
	function get_actions_class() {

		// Create New Array();
		$actions_class = array();

		// Add Form Actions Main Class
		$actions_class[] = 'logy-form-actions';

		// Get Actions Layout
		$actions_layout = logy_options( 'logy_signup_actions_layout' );

		// Get Form Options Data

		$one_button = array( 'logy-regactions-v5', 'logy-regactions-v6' );

		$use_icons	= array( 'logy-regactions-v3', 'logy-regactions-v4', 'logy-regactions-v6' );

		$full_witdh	= array( 'logy-regactions-v1', 'logy-regactions-v3', 'logy-regactions-v5', 'logy-regactions-v6' );

		$half_witdh	= array( 'logy-regactions-v2', 'logy-regactions-v4' );

		// Get One Button Class.
		$actions_class[] = in_array( $actions_layout, $one_button ) ? 'logy-one-button' : null;

		// Get Buttons icons Class.
		$actions_class[] = in_array( $actions_layout, $use_icons ) ? 'logy-buttons-icons' : null;

		// Get full Width Class.
		$actions_class[] = in_array( $actions_layout, $full_witdh ) ? 'logy-fullwidth-button' : null;

		// Get Half Width Class.
		$actions_class[] = in_array( $actions_layout, $half_witdh ) ? 'logy-halfwidth-button' : null;

		// Get Button Border Style.
		$actions_class[] = logy_options( 'logy_signup_btn_format' );

		// Get Button Icons Position.
		if ( in_array( $actions_layout, $use_icons ) ) {
			$actions_class[] = logy_options( 'logy_signup_btn_icons_position' );
		}

		// Return Action Area Classes
		return logy_generate_class( $actions_class );
	}

	/**
	 * Check If Captcha Is Activated .
	 */
	function captcha_is_activated() {

		// Get Captcha Visibility Option.
		$use_captcha = logy_options( 'logy_enable_recaptcha' );
		if ( 'off' == $use_captcha )  {
			return false;
		}

		// Get Captcha Options
		$site_key 	= logy_options( 'logy_recaptcha_site_key' );
		$secret_key = logy_options( 'logy_recaptcha_secret_key' );
		if ( empty( $site_key ) || empty( $secret_key ) ) {
			return false;
		}

		return true;
	}

	/**
	 * An action function used to include the reCAPTCHA JavaScript file at the end of the page.
	 */
	public function add_captcha_js() {
		if ( is_page( logy_page_id( 'register' ) ) ) {
			echo "<script src='https://www.google.com/recaptcha/api.js?hl=en'></script>";
		}
	}

	/**
	 * Redirect User To Specific Page..
	 */
	public function redirect( $code, $error, $redirect_to = null ) {

		// Get Redirect Url.
		$redirect_url = ! empty( $redirect_to ) ? $redirect_to : logy_page_url( 'register' );

		// Get Errors.
		$redirect_url = add_query_arg( $code, $error, $redirect_url );

		// Redirect User.
		wp_redirect( $redirect_url );

		// Exit.
		exit;
	}
}