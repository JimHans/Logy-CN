<?php

class Logy_Lost_Password {

	protected $logy;

	/**
	 * Init Lost Password Actions & Filters.
	 */
	public function __construct() {

		global $Logy;

		// Init Vars.
    	$this->logy = &$Logy;

		// Redirects
		add_action( 'login_form_lostpassword', array( $this, 'redirect_to_logy_lostpassword' ) );
		add_action( 'login_form_rp', array( $this, 'redirect_to_logy_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'redirect_to_logy_password_reset' ) );
		add_action( 'template_redirect', array( &$this, 'check_reset_page_link' ) );

		// Handlers for form posting actions
		add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );
		add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );

		// Shortcodes
		add_shortcode( 'logy_lost_password_page', array( $this, 'get_password_lost_form' ) );
	}

	/**
	 * Redirects the user to the "Forgot your password?" page instead of
	 * wp-login.php?action=lostpassword.
	 */
	public function redirect_to_logy_lostpassword() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			if ( is_user_logged_in() ) {
				$this->redirect_logged_in_user();
				exit;
			}

			wp_redirect( logy_page_url( 'lost-password' ) );
			exit;
		}
	}

	/**
	 * Check Reset Password Page Link : if the key is valid or not then
	 * redirect to a custom page.
	 */
	function check_reset_page_link() {
		if ( isset( $_GET['action'] ) && 'rp' == $_GET['action'] ) {
			// Verify key / login combo
			$user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && 'expired_key' === $user->get_error_code()  ) {
					wp_redirect( add_query_arg( 'login', 'expiredkey', logy_page_url( 'login' ) ) );
				} else {
					wp_redirect( add_query_arg( 'login', 'invalidkey', logy_page_url( 'login' ) ) );
				}
				exit;
			}
		}
	}

	/**
	 * Redirects to the custom password reset page, or the login page
	 * if there are errors.
	 */
	public function redirect_to_logy_password_reset() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			// Verify key / login combo
			$user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && 'expired_key' === $user->get_error_code() ) {
					wp_redirect( add_query_arg( 'login', 'expiredkey', logy_page_url( 'login' ) ) );
				} else {
					wp_redirect( add_query_arg( 'login', 'invalidkey', logy_page_url( 'login' ) ) );
				}
				exit;
			}
			// Reset Password Page
			$redirect_url = logy_page_url( 'lost-password' );
			$redirect_url = add_query_arg( 'action', 'rp', $redirect_url );
			$redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
			$redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

			wp_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * A shortcode for rendering the form used to initiate the password reset.
	 */
	public function get_password_lost_form() {
		if ( is_user_logged_in() ) {
			return false;
		}

		// Render the Lost Password form.
		return $this->logy->form->get_page( 'lost_password' );
	}

	/**
	 * Messages Attributes
	 */
	function messages_attributes() {

		if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
			$attributes['login'] = $_REQUEST['login'];
			$attributes['key'] = $_REQUEST['key'];
		}

		// Retrieve possible errors from request parameters
		$attributes['errors'] = array();
		if ( isset( $_REQUEST['errors'] ) ) {
			$error_codes = explode( ',', $_REQUEST['errors'] );
			foreach ( $error_codes as $error_code ) {
				$attributes['errors'] []= $this->get_error_message( $error_code );
			}
		}

		return $attributes;
	}

	/**
	 * Attributes
	 */
	function attributes() {

		$attrs = $this->messages_attributes();

		// Add Form Type & Action to generate form class later.
		$attrs['form_type']   = 'login';
		$attrs['form_action'] = 'lost-password';

		// Get Login Box Classes.
		$attrs['action_class'] = $this->logy->login->get_actions_class();
		$attrs['form_class']   = $this->logy->form->get_form_class( $attrs );

		// Get Form Buttons Titles
		$attrs['submit_title'] = sanitize_text_field( logy_options( 'logy_lostpswd_submit_btn_title' ) );

		// Form Elements Visibilty Settings.
		$attrs['use_labels'] = ( false !== strpos( $attrs['form_class'], 'logy-with-labels' ) ) ? true : false;
		$attrs['use_icons']	 = ( false !== strpos( $attrs['form_class'], 'logy-fields-icon' ) ) ? true : false;

		// Form Actions Elements Visibilty Settings.
		$attrs['actions_icons']	= ( false !== strpos( $attrs['action_class'], 'logy-buttons-icons' ) ) ? true : false;

		return $attrs;
	}

	/**
	 * Initiates password reset.
	 */
	public function do_password_lost() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$errors = retrieve_password();
			if ( is_wp_error( $errors ) ) {
				// Errors found
				$redirect_url = logy_page_url( 'lost-password' );
				$redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
			} else {
				// Email sent
				$redirect_url = logy_page_url( 'login' );
				$redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
				if ( ! empty( $_REQUEST['redirect_to'] ) ) {
					$redirect_url = $_REQUEST['redirect_to'];
				}
			}
			wp_safe_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * Resets the user's password if the password reset form was submitted.
	 */
	public function do_password_reset() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			// Get Data
			$rp_key 	= $_REQUEST['rp_key'];
			$rp_login 	= $_REQUEST['rp_login'];
			$login_page = logy_page_url( 'login' );

			$user = check_password_reset_key( $rp_key, $rp_login );

			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && 'expired_key' === $user->get_error_code() ) {
					wp_redirect( add_query_arg( 'login', 'expiredkey', $login_page ) );
				} else {
					wp_redirect( add_query_arg( 'login', 'invalidkey', $login_page ) );
				}
				exit;
			}

			if ( isset( $_POST['pass1'] ) ) {

				$redirect_url = logy_page_url( 'lost-password' );
				$redirect_url = add_query_arg( 'action', 'rp', $redirect_url );
				$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
				$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );

				// Check Password Lenght
				if ( 6 > strlen( $_POST['pass1'] ) ) {
					$redirect_url = add_query_arg( 'errors', 'password_length', $redirect_url );
					wp_redirect( $redirect_url );
					exit;
				}

				// Passwords don't match
				if ( $_POST['pass1'] != $_POST['pass2'] ) {
					$redirect_url = add_query_arg( 'errors', 'password_reset_mismatch', $redirect_url );
					wp_redirect( $redirect_url );
					exit;
				}

				// Password is empty
				if ( empty( $_POST['pass1'] ) ) {
					$redirect_url = add_query_arg( 'errors', 'password_reset_empty', $redirect_url );
					wp_redirect( $redirect_url );
					exit;
				}

				// Parameter checks OK, reset password
				wp_set_password( $_POST['pass1'], $user->ID );

				// Check if user is new or not to send the right message.
				if ( get_user_meta( $user->ID, '_logy_new_user' ) ) {
					delete_user_meta( $user->ID, '_logy_new_user' );
					$this->logy->mail->user_welcome_notification( $user->ID );
				} else {
					$this->logy->mail->password_changed_notification( $user->ID );
				}

				// Redirect To login Page.
				wp_redirect( add_query_arg( 'password', 'changed', $login_page ) );

			} else {
				echo __( 'Invalid request.', 'logy' );
			}

			exit;
		}
	}

	/**
	 * Finds and returns a matching error message for the given error code.
	 */
	private function get_error_message( $error_code ) {

		switch ( $error_code ) {

			case 'empty_username':
				return __( 'You need to enter your email address to continue.', 'logy' );

			case 'invalid_email':
			case 'invalidcombo':
				return __( 'There is no user registered with this email address.', 'logy' );

			// Reset password

			case 'password_length':
				return __( 'Password length must be greater than 6 !', 'logy' );

			case 'password_reset_mismatch':
				return __( "The two passwords you entered don't match.", 'logy' );

			case 'password_reset_empty':
				return __( "Sorry, we don't accept empty passwords.", 'logy' );

			default:
				break;
		}

		return __( 'An unknown error occurred. Please try again later.', 'logy' );
	}

}