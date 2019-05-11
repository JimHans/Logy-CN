<?php

class Logy_Setup {

	/**
	 * # Install Options .
	 */
	function install_options() {

		// Get Available Social Networks.
		$providers = array( 'Facebook', 'Twitter', 'Google', 'LinkedIn', 'Instagram' );

		if ( ! get_option( 'logy_social_providers' ) ) {
			update_option( 'logy_social_providers', $providers );
		}

	}

	/**
	 * Build DataBase Tables.
	 */
	public function build_database_tables() {

        global $wpdb, $Logy_users_table;

        // Set Variables
        $charset_collate = $wpdb->get_charset_collate();

        // Table SQL Code.
		$users_sql = "CREATE TABLE $Logy_users_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			user_id int(11) NOT NULL,
			provider varchar(200) NOT NULL,
			identifier varchar(200) NOT NULL,
			websiteurl varchar(255) NOT NULL,
			profileurl varchar(255) NOT NULL,
			photourl varchar(255) NOT NULL,
			displayname varchar(150) NOT NULL,
			description varchar(200) NOT NULL,
			firstname varchar(150) NOT NULL,
			lastname varchar(150) NOT NULL,
			gender varchar(10) NOT NULL,
			language varchar(20) NOT NULL,
			age varchar(10) NOT NULL,
			birthday int(11) NOT NULL,
			birthmonth int(11) NOT NULL,
			birthyear int(11) NOT NULL,
			email varchar(255) NOT NULL,
			emailverified varchar(200) NOT NULL,
			phone varchar(75) NOT NULL,
			address varchar(255) NOT NULL,
			country varchar(75) NOT NULL,
			region varchar(50) NOT NULL,
			city varchar(50) NOT NULL,
			zip varchar(25) NOT NULL,
			profile_hash varchar(200) NOT NULL,
		 	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		// Include Files.
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		// Build Tables.
		dbDelta( $users_sql );
	}

	/**
	 * # Install Pages .
	 */
	function install_pages() {

		if ( current_user_can( 'manage_options' ) && ! get_option( 'logy_is_installed' ) ) {

			update_option( 'logy_is_installed', 1 );

			// Plugin Pages
			$pages = array(
				'login' 		 => array( 'title' => __( 'Login', 'logy' ) ),
				'register' 	     => array( 'title' => __( 'Register', 'logy' ) ),
			 	'lost-password'  => array( 'title' => __( 'Password Reset', 'logy' ) )
			);

			// Install Core Pages
			foreach ( $pages as $slug => $page ) {

				// Check that the page doesn't exist already
				$is_page_exists = logy_get_post_id( 'page', '_logy_core', $slug );

				if ( ! $is_page_exists ) {

					$user_page = array(
						'post_title'	 => $page['title'],
						'post_name'		 => $slug,
						'post_type' 	 => 'post',
						'post_status'	 => 'publish',
						'post_author'    =>  1,
						'comment_status' => 'closed'
					);

					$post_id = wp_insert_post( $user_page );

					wp_update_post( array('ID' => $post_id, 'post_type' => 'page' ) );

					update_post_meta( $post_id, '_logy_core', $slug );

					$logy_pages[ $slug ] = $post_id;
				}
			}

			if ( isset( $logy_pages ) ) {
				update_option( 'logy_pages', $logy_pages );
			}
		}
	}

	/**
	 * # Standard Options .
	 */
	function standard_options() {

		$default_options = array(

			// Login Form
			'logy_login_form_enable_header' 	=> 'on',
			'logy_user_after_login_redirect' 	=> 'home',
			'logy_after_logout_redirect' 		=> 'login',
			'logy_admin_after_login_redirect' 	=> 'dashboard',
			'logy_login_form_layout' 		 	=> 'logy-field-v1',
			'logy_login_icons_position'	 		=> 'logy-icons-left',
			'logy_login_actions_layout' 	 	=> 'logy-actions-v1',
			'logy_login_btn_icons_position'		=> 'logy-icons-left',
			'logy_login_btn_format' 	 		=> 'logy-border-radius',
			'logy_login_fields_format' 	 		=> 'logy-border-flat',
			'logy_login_form_title' 		 	=> __( 'Login', 'logy' ),
			'logy_login_signin_btn_title' 		=> __( 'Log In', 'logy' ),
			'logy_login_register_btn_title' 	=> __( 'Create New Account', 'logy' ),
			'logy_login_lostpswd_title' 		=> __( 'Lost password?', 'logy' ),
			'logy_login_form_subtitle' 			=> __( 'Sign in to your account', 'logy' ),

			// Social Login
			'logy_social_btns_icons_position'	=> 'logy-icons-left',
			'logy_social_btns_format'			=> 'logy-border-radius',
			'logy_social_btns_type'				=> 'logy-only-icon',
			'logy_enable_social_login'			=> 'on',

			// Lost Password Form
			'logy_lostpswd_form_enable_header'  => 'on',
			'logy_lostpswd_form_title' 			=> __( 'Forgot your password?', 'logy' ),
			'logy_lostpswd_submit_btn_title'	=> __( 'Reset Password', 'logy' ),
			'logy_lostpswd_form_subtitle' 	 	=> __( 'Reset your account password', 'logy' ),

			// Register Form
			'logy_show_terms_privacy_note' 		=> 'on',
			'logy_signup_form_enable_header' 	=> 'on',
			'logy_signup_form_layout' 			=> 'logy-field-v1',
			'logy_signup_icons_position'	 	=> 'logy-icons-left',
			'logy_signup_actions_layout' 	 	=> 'logy-regactions-v1',
			'logy_signup_btn_icons_position'	=> 'logy-icons-left',
			'logy_signup_btn_format' 	 		=> 'logy-border-radius',
			'logy_signup_fields_format' 	 	=> 'logy-border-flat',
			'logy_signup_signin_btn_title' 		=> __( 'Log In', 'logy' ),
			'logy_signup_form_title' 		 	=> __( 'Sign Up', 'logy' ),
			'logy_signup_register_btn_title' 	=> __( 'Sign Up', 'logy' ),
			'logy_signup_form_subtitle' 		=> __( 'Create New Account', 'logy' ),

			// Limit Login Settings
			'logy_long_lockout_duration' 	=> 86400,
			'logy_short_lockout_duration' 	=> 43200,
			'logy_retries_duration' 		=> 1200,
			'logy_enable_limit_login'		=> 'on',
			'logy_allowed_retries' 			=> 4,
			'logy_allowed_lockouts' 		=> 2,

			// Mail Settings
			'logy_mail_content_type' 			=> 'text/plain',
			'logy_notify_admin_on_registration' => 'on',
			'logy_mail_sender_name' 			=> get_option( 'blogname' ),
			'logy_mail_sender_email' 			=> get_option( 'admin_email' ),

			// User Confirmation Message
			'logy_user_confirmation_mail_subject'	=> 'Welcome to {site_name}!',
			'logy_user_confirmation_mail_message'	=>
				"Hi {display_name}," . "\r\n\r\n" .
				"Welcome to {site_name}!" . "\r\n\r\n" .
				"Here's your login informations :" . "\r\n\r\n" .
				"E-mail: {email}" . "\r\n\r\n" .
				"Username: {username}" . "\r\n\r\n" .
				"To set your password, visit the following address:" . "\r\n\r\n" .
				"{password_reset_url}" . "\r\n\r\n" .
				"If you have any problems, please contact us at {admin_email}.",

			// Admin Notifications
			'logy_newuser_notification_subject' => '[{site_name}] New User Registration',
			'logy_newuser_notification_message' =>
				"New user registration on your site {site_name}:" . "\r\n\r\n" .
				"Username: {username}" . "\r\n\r\n" .
				"E-mail: {email}",

			// Password Reset Email
			'logy_user_pswdreset_mail_subject' => 'Reset Your Account Password!',
			'logy_user_pswdreset_mail_message' =>
				"Hi {display_name}," . "\r\n\r\n" .
				"we got a request to reset your account password. If you made this request, visit the following link To reset your password:" . "\r\n\r\n" .
				"{password_reset_url}" . "\r\n\r\n" .
				"If this was a mistake, just ignore this email and nothing will happen." . "\r\n\r\n" .
				"if you didn't request a password reset, please contact us at {admin_email}.",

			// Password Changed Email
			'logy_user_pswdchanged_mail_subject' => 'Your Password Has Been Changed!',
			'logy_user_pswdchanged_mail_message' =>
				"Hi {display_name}," . "\r\n\r\n" .
				"your account password has been successfully changed!" . "\r\n\r\n" .
				"To login please visit the following url:" . "\r\n\r\n" .
				"{login_url}" . "\r\n\r\n" .
				"if you didn't reset your password , please contact us at {admin_email}.",

			// Welcome Email
			'logy_user_welcome_mail_subject' => 'Welcome Your Account Now is Ready !',
			'logy_user_welcome_mail_message' =>
				"Hi {display_name}," . "\r\n\r\n" .
				"Your new account is ready! Thank you for signing up." . "\r\n\r\n" .
				"To login please visit the following url:" . "\r\n\r\n" .
				"{login_url}" . "\r\n\r\n" .
				"If you have any problems, please contact us at {admin_email}.",

			// Admin Toolbar & Dashboard
			'logy_hide_subscribers_dash' => 'off',

			// Captcha.
			'logy_enable_recaptcha' => 'on',

			// Panel Options.
			'logy_panel_scheme' => 'klabs-yellow-scheme',

			// Panel Messages.
			'logy_msgbox_captcha' => 'on',
			'logy_msgbox_mail_tags' => 'off',
			'logy_msgbox_mail_content' => 'on',
			'logy_msgbox_profile_schemes' => 'on',
			'logy_msgbox_profile_schemes' => 'on',
		);

		return $default_options;
	}

}