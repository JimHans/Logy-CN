<?php

class Logy_Mail {

	protected $logy;

	function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

		// Mail Filters
		add_filter( 'logy_mail_tags_replaces_hook', array( $this, 'replace_tags' ), 10, 2 );
		add_filter( 'wp_mail_content_type', array( $this,'set_content_type') );
		add_filter( 'wp_mail_from_name',array( $this, 'sender_name' ) );
		add_filter( 'wp_mail_from', array( $this, 'sender_email' ) );

		// Reset Password Message & Subject Filters
		add_filter( 'retrieve_password_title', array( $this, 'reset_password_subject' ), 10, 2 );
		add_filter( 'retrieve_password_message', array( $this,'reset_password_message' ), 10, 3 );

	}

	/**
	 * New user notification .
	 */
	function new_user_notification( $user_id, $notify = '' ) {

	    // Get User Data
		$user = get_userdata( $user_id );

		if ( 'user' !== $notify ) {

		    // Get Message Subject & Body
			$subject = logy_options( 'logy_newuser_notification_subject' );
			$content = logy_options( 'logy_newuser_notification_message' );

			// Convert Message Tags.
			$subject = $this->logy->mail->convert_tags( $subject, $user_id );
			$message = $this->logy->mail->convert_tags( $content, $user_id );

			// Send Message.
			wp_mail( get_option( 'admin_email' ), $subject, $message );

		}

		// if the admin is the only one should be notified then stop just here.
		if ( 'admin' === $notify ) {
			return;
		}

	    // Get Message Subject & Body
		$subject = logy_options( 'logy_user_confirmation_mail_subject' );
		$content = logy_options( 'logy_user_confirmation_mail_message' );

		// Convert Message Tags.
		$subject = $this->logy->mail->convert_tags( $subject, $user_id );
		$message = $this->logy->mail->convert_tags( $content, $user_id );

		// Send Message.
		wp_mail( $user->user_email, $subject, $message);
	}

	/**
	 * Convert Emails Tags To Values.
	 */
	public function convert_tags( $content, $user_id ) {

		$search = array(
			'{email}',
			'{site_url}',
			'{username}',
			'{login_url}',
			'{last_name}',
			'{site_name}',
			'{first_name}',
			'{display_name}',
			'{admin_email}',
			'{password_reset_url}',
		);

		$search = apply_filters( 'logy_mail_tags_patterns_hook', $search );

		$replace = array(
			'email',
			'site_url',
			'username',
			'login_url',
			'last_name',
			'site_name',
			'first_name',
			'display_name',
			'admin_email',
			'password_reset_url'
		);

		$replace = apply_filters( 'logy_mail_tags_replaces_hook', $replace, $user_id );

		$content = wp_kses_decode_entities( str_replace( $search, $replace, $content ) );

		return $content;

	}

	/**
	 * Welcome Message.
	 */
	function user_welcome_notification( $user_id ) {

	    // Get User Data
		$user = get_userdata( $user_id );

	    // Get Message Subject & Body
		$subject = logy_options( 'logy_user_welcome_mail_subject' );
		$content = logy_options( 'logy_user_welcome_mail_message' );

		// Convert Message Tags.
		$subject = $this->logy->mail->convert_tags( $subject, $user_id );
		$message = $this->logy->mail->convert_tags( $content, $user_id );

		// Send Message.
		wp_mail( $user->user_email, $subject, $message);
	}

	/**
	 * Reset Password Message Subject.
	 */
	function reset_password_subject( $title, $user_login ) {

		// Get User Data
		$user = get_user_by( 'login', $user_login );

	    // Get Message Subject
		$title = logy_options( 'logy_user_pswdreset_mail_subject', $user->ID );

		// Convert Subject Tags.
		$subject = $this->logy->mail->convert_tags( $title, $user->ID );

	    return $subject;

	}

	/**
	 * Reset Password Message Body.
	 */
	function reset_password_message( $message, $key, $user_login ) {

		// Get User Data
		$user = get_user_by( 'login', $user_login );

	    // Get Message Body
		$content = logy_options( 'logy_user_pswdreset_mail_message', $user->ID );

		// Convert Message Tags.
		$message = $this->logy->mail->convert_tags( $content, $user->ID );

	    return $message;

	}

	/**
	 * Password Changed notification .
	 */
	function password_changed_notification( $user_id ) {

	    // Get User Data
		$user = get_userdata( $user_id );

	    // Get Message Subject & Body
		$subject = logy_options( 'logy_user_pswdchanged_mail_subject' );
		$content = logy_options( 'logy_user_pswdchanged_mail_message' );

		// Convert Message Tags.
		$subject = $this->logy->mail->convert_tags( $subject, $user_id );
		$message = $this->logy->mail->convert_tags( $content, $user_id );

		// Send Message.
		wp_mail( $user->user_email, $subject, $message );
	}

	/**
	 * Replace Message Tags.
	 */
	function replace_tags( $items, $user_id ) {
		$new_replaces = array();
		foreach ( $items as $item ) {
			$item = $this->get_tag_value( $item, $user_id );
			$new_replaces[] = $item;
		}
		return $new_replaces;
	}

	/**
	 * Get Message Tags Values.
	 */
	function get_tag_value( $tag, $user_id ) {

		// Get User Data
		$user = new WP_User( $user_id );

		// Get Tag Value
		switch ( $tag ) {

			case 'site_name':
				return wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

			case 'site_url':
				return site_url();

			case 'first_name':
				return $user->first_name;

			case 'last_name':
				return $user->last_name;

			case 'display_name':
				return $user->display_name;

			case 'email':
				return $user->user_email;

			case 'username':
				return $user->user_login;

			case 'admin_email':
				return get_option( 'admin_email' );

			case 'login_url':
				return logy_page_url( 'login' );

			case 'password_reset_url':

				global $wpdb, $wp_hasher;

				// Generate something random for a password reset key.
				$key = wp_generate_password( 20, false );

				/** This action is documented in wp-login.php */
				do_action( 'retrieve_password_key', $user->user_login, $key );

				// Now insert the key, hashed, into the DB.
				if ( empty( $wp_hasher ) ) {
					$wp_hasher = new PasswordHash( 8, true );
				}

				// Get User Activation Key.
				$hashed = time() . ':' . $wp_hasher->HashPassword( $key );

				// Update User Activation Data
				$wpdb->update(
					$wpdb->users,
					array( 'user_activation_key' => $hashed ),
					array( 'user_login' => $user->user_login )
				);

				// Return Activation Link
				return network_site_url(
					"wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ),
					'login'
				) ;
		}
	}

	/**
	 * Set Message Body Content Type.
	 */
	function set_content_type( $content_type ) {
		return logy_options( 'logy_mail_content_type' );
	}

	/**
	 * Set Message Sender Name.
	 */
	function sender_name() {
		return logy_options( 'logy_mail_sender_name' );
	}

	/**
	 * Set Message Sender E-mail.
	 */
	function sender_email() {
		return logy_options( 'logy_mail_sender_email' );
	}

}