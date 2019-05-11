<?php

class Logy_Login {

	protected $logy;

	/**
	 * Init Shortcode & Actions & Filters
	 */
	public function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

		// Add "[logy_login]" Shortcode.
		add_shortcode( 'logy_login_page', array( $this, 'get_login_form' ) );

		// Redirects.
		add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
		add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
		add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );

	}

	/**
	 * Returns the URL to which the user should be redirected after the successful login.
	 */
	public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {

		$redirect_url = home_url();

		if ( ! isset( $user->ID ) ) {
			return $redirect_url;
		}

		// Use the redirect_to parameter if one is set, otherwise redirect to custom page.
		if ( ! empty( $requested_redirect_to ) ) {
			$redirect_url = $redirect_to;
		} elseif ( user_can( $user, 'manage_options' ) ) {
			// Get Admin Redirect Page
			$admin_redirect_page = logy_options( 'logy_admin_after_login_redirect' );
			$redirect_url = $this->get_redirect_page( $admin_redirect_page, $user->ID );
		} else {
			// Get User Redirect Page
			$user_redirect_page  = logy_options( 'logy_user_after_login_redirect' );
			$redirect_url = $this->get_redirect_page( $user_redirect_page, $user->ID );
		}

		return wp_validate_redirect( $redirect_url, home_url() );

	}

	/**
	 * Redirect the user after authentication if there were any errors.
	 */
	public function maybe_redirect_at_authenticate( $user, $username, $password ) {
		// Check if the earlier authenticate filter (most likely,
		// the default WordPress authentication) functions have found errors
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['logy-form'] ) ) {

    		// Filters whether the given user can be authenticated with the provided $password.
			$user = apply_filters( 'wp_authenticate_user', $user, $password );
			
			if ( is_wp_error( $user ) ) {
				
				// Fires after a user login has failed.
				do_action( 'wp_login_failed', $user );

				// Get Login Page Url.
				$login_url = logy_page_url( 'login' );
        		
				// Get Redirect Url.
				if ( isset( $_REQUEST['redirect_to'] ) ) {
					$login_url = add_query_arg( 'redirect_to', $_REQUEST['redirect_to'], $login_url );
				}
				
				// Get Errors
				$error_codes = join( ',', $user->get_error_codes() );
				$login_url = add_query_arg( 'login', $error_codes, $login_url );
				wp_redirect( $login_url );
				exit;

			}
		}
		return $user;
	}

	/**
	 * A shortcode for rendering the login form.
	 */
	public function get_login_form( $attributes = null ) {

		if ( is_user_logged_in() ) {
			return false;
		}

		// Render the login form.
		return $this->logy->form->get_page( 'login', $attributes );

	}

	/**
	 * Messages Attributes
	 */
	function messages_attributes() {
		// Pass the redirect parameter to the WordPress login functionality: by default,
		// don't specify a redirect, but if a valid redirect URL has been passed as
		// request parameter, use it.
		$attributes['redirect'] = '';
		if ( isset( $_REQUEST['redirect_to'] ) ) {
			$attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
		}

		// Error messages
		$errors = array();
		if ( isset( $_REQUEST['login'] ) ) {
			$error_codes = explode( ',', $_REQUEST['login'] );
			foreach ( $error_codes as $code ) {
				$errors[] = $this->get_error_message( $code );
			}
		}

		// Filter Errors.
		$errors = apply_filters( 'logy_login_errors', $errors );

		$attributes['errors'] = $errors;

		// Check if the user just registered
		$attributes['registered'] = isset( $_REQUEST['registered'] );

		// Check if user just logged out
		$attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

		// Check if the user just requested a new password
		$attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';

		// Check if user just updated password
		$attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';

		return $attributes;
	}

	/**
	 * Attributes
	 */
	function attributes() {

		$attrs = $this->messages_attributes();

		// Add Form Type & Action to generate form class later.
		$attrs['form_type']   = 'login';
		$attrs['form_action'] = 'login';

		// Get Login Box Classes.
		$attrs['action_class'] = $this->get_actions_class();
		$attrs['form_class']   = $this->logy->form->get_form_class( $attrs );

		// Get Form Buttons Titles
		$attrs['submit_title'] = logy_options( 'logy_login_signin_btn_title' );
		$attrs['link_title']   = logy_options( 'logy_login_register_btn_title' );

		// Form Elements Visibilty Settings.
		$attrs['use_labels'] = ( false !== strpos( $attrs['form_class'], 'logy-with-labels' ) ) ? true : false;
		$attrs['use_icons']	 = ( false !== strpos( $attrs['form_class'], 'logy-fields-icon' ) ) ? true : false;

		// Form Actions Elements Visibilty Settings.
		$attrs['actions_lostpswd'] = ( false !== strpos( $attrs['action_class'], 'logy-lost-pswd' ) ) ? true : false;
		$attrs['actions_icons']	= ( false !== strpos( $attrs['action_class'], 'logy-buttons-icons' ) ) ? true : false;

		return $attrs;
	}

	/**
	 * Redirects the user to the correct page depending on whether he / she is an admin or not.
	 */
	private function redirect_logged_in_user( $redirect_to = null ) {
		$user = wp_get_current_user();
		if ( user_can( $user, 'manage_options' ) ) {
			if ( $redirect_to ) {
				wp_safe_redirect( $redirect_to );
			} else {
				wp_redirect( admin_url() );
			}
		} else {
			wp_redirect( home_url() );
		}
	}

	/**
	 * Get Redirect Page Url
	 */
	function get_redirect_page( $page, $user_id = null ) {

		switch( $page ) {
			case 'home':
				$page_url = home_url( '/' );
	        	break;
			case 'dashboard':
				$page_url = admin_url();
	        	break;
			case 'profile':
				$page_url = get_edit_user_link();
	        	break;
	        default:
				$page_url = home_url( '/' );
	        break;
        }

        return esc_url( $page_url );
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
		$actions_layout = logy_options( 'logy_login_actions_layout' );

		// Get Form Options Data

		$one_button = array(
			'logy-actions-v3', 'logy-actions-v6'
		);

		$forgot_password = array(
			'logy-actions-v2', 'logy-actions-v5', 'logy-actions-v9', 'logy-actions-v10'
		);

		$use_icons	= array(
			'logy-actions-v4', 'logy-actions-v5', 'logy-actions-v6', 'logy-actions-v7',
			'logy-actions-v10'
		);

		$full_witdh	= array(
			'logy-actions-v1', 'logy-actions-v3', 'logy-actions-v4', 'logy-actions-v6',
			'logy-actions-v9', 'logy-actions-v10'
		);

		$half_witdh	= array(
			'logy-actions-v2', 'logy-actions-v5', 'logy-actions-v7', 'logy-actions-v8'
		);

		// Get One Button Class.
		$actions_class[] = in_array( $actions_layout, $one_button ) ? 'logy-one-button' : null;

		// Get Buttons icons Class.
		$actions_class[] = in_array( $actions_layout, $use_icons ) ? 'logy-buttons-icons' : null;

		// Get full Width Class.
		$actions_class[] = in_array( $actions_layout, $full_witdh ) ? 'logy-fullwidth-button' : null;

		// Get Half Width Class.
		$actions_class[] = in_array( $actions_layout, $half_witdh ) ? 'logy-halfwidth-button' : null;

		// Get "Forgot Password" Class.
		$actions_class[] = in_array( $actions_layout, $forgot_password ) ? 'logy-lost-pswd' : null;

		// Get Button Border Style.
		$actions_class[] = logy_options( 'logy_login_btn_format' );

		// Get Button Icons Position.
		if ( in_array( $actions_layout, $use_icons ) ) {
			$actions_class[] = logy_options( 'logy_login_btn_icons_position' );
		}

		// Return Action Area Classes
		return logy_generate_class( $actions_class );

	}

	/**
	 * Redirect to custom page after the user has been logged out.
	 */
	public function redirect_after_logout() {

		// Get Redirect Page
		$redirect_to = logy_options( 'logy_after_logout_redirect' );

		// Get Redirect Url
		if ( 'login' == $redirect_to ) {
			$redirect_url = logy_page_url( 'login' ) . '?logged_out=true';
		} else {
			$redirect_url = home_url();
		}

		// Redirect User
		wp_safe_redirect( $redirect_url );
		exit;
	}

	/**
	 * Finds and returns a matching error message for the given error code.
	 */
	private function get_error_message( $error_code ) {
		switch ( $error_code ) {

			case 'empty_username':
				return __( '你有一个电子邮件地址，对吧？', 'logy' );

			case 'invalid_url':
				return __( '请求的URL无效', 'logy' );

			case 'empty_password':
				return __( '你需要输入密码才能登录。', 'logy' );

			case 'file_not_found':
				return __( '找不到登录账号信息。', 'logy' );

			case 'invalid_username':
				return __(
					"我们没有任何用户使用该电子邮件地址。也许你填错邮箱地址了。", 'logy' );

			case 'incorrect_password':
				return __( "您输入的密码不太正确。", 'logy' );

			case 'expiredkey':
			case 'invalidkey':
				return __( '您使用的密码重置链接无效。', 'logy' );

			case 'registration_disabled':
				return __( '目前不允许注册新用户。', 'logy' );
				
			case 'network_unavailable':
				return __( '所选网络不可用。', 'logy' );
				
			case 'social_auth_unavailable':
				return __( '社交账号登录不可用。', 'logy' );
				
			case 'cant_connect':
				return __( "我们无法连接到您的帐户。请再试一次！", 'logy' );
				
			case 'too_many_retries':
			return $this->logy->limit->get_lockout_msg();
			
			default:
				break;
		}
		return __( '发生未知错误。请稍后再试。', 'logy' );
	}
}