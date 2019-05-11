<?php

class Logy_Dashboard {

	/**
	 * # General Settings.
	 */
	function general_settings() {

		global $Logy_Admin, $Logy_Settings;

		// Menu Tabs List
		$tabs = array(
			'general'	=> array(
				'icon'  	=> 'cogs',
				'id' 		=> 'general',
				'function' 	=> 'logy_general_settings',
				'title' 	=> __( '通用设置', 'logy' ),
			),
			'login'	=> array(
				'id' 		=> 'login',
				'icon'  	=> 'sign-in',
				'function' 	=> 'logy_login_settings',
				'title' 	=> __( '登录设置', 'logy' ),
			),
			'register' => array(
				'icon'  	=> 'pencil',
				'id' 		=> 'register',
				'function' 	=> 'logy_register_settings',
				'title' 	=> __( '注册设置', 'logy' ),
			),
			'lost_password' => array(
				'icon'  	=> 'lock',
				'id' 		=> 'lost_password',
				'function' 	=> 'logy_lost_password_settings',
				'title' 	=> __( '忘记密码设置', 'logy' ),
			),
			'emails' => array(
				'id' 		=> 'emails',
				'icon'  	=> 'envelope-o',
				'function' 	=> 'logy_emails_settings',
				'title' 	=> __( '邮件设置', 'logy' ),
			),
			'notifications' => array(
				'icon'  	=> 'bell',
				'id' 		=> 'notifications',
				'function' 	=> 'logy_notifications_settings',
				'title' 	=> __( '通知设置', 'logy' ),
			),
			'captcha' => array(
				'id' 		=> 'captcha',
				'icon'  	=> 'user-secret',
				'function' 	=> 'logy_captcha_settings',
				'title' 	=> __( '验证码设置', 'logy' ),
			),
			'social_login' => array(
				'icon'  	=> 'share-alt',
				'id' 		=> 'social_login',
				'function' 	=> 'logy_social_login_settings',
				'title' 	=> __( '社交登录设置', 'logy' ),
			),
			'limit_login' => array(
				'icon'  	=> 'lock',
				'id' 		=> 'limit_login',
				'function' 	=> 'logy_limit_login_settings',
				'title' 	=> __( '登录保护设置', 'logy' ),
			),
			'login_styling' => array(
				'icon'  	=> 'paint-brush',
				'id' 		=> 'login_styling',
				'function' 	=> 'logy_login_styling_settings',
				'title' 	=> __( '登录样式设置', 'logy' ),
			),
			'register_styling' => array(
				'icon'  	=> 'paint-brush',
				'id' 		=> 'register_styling',
				'function' 	=> 'logy_register_styling_settings',
				'title' 	=> __( '注册样式设置', 'logy' ),
			)
		);

		// Get Current Tab.
		$current_tab = isset( $_GET['tab'] ) ? (string) $_GET['tab'] : 'general';

		// Get Tab Data.
		$tab = $tabs[ $current_tab ];

		// Append Class to the active tab.
		$tabs[ $current_tab ]['class'] = 'klabs-active-tab';

		// Get Tab Function Name.
		$settings_function = $tab['function'];

		ob_start();

		$field_args = array(
            'type'  => 'start',
            'id'    => $tab['id'],
            'icon'  => $tab['icon'],
            'title' => $tab['title'],
       	);

        $Logy_Settings->get_field( $field_args );

		// Get Settings
		$settings_function();

        $Logy_Settings->get_field( array( 'type' => 'end' ) );

		$content = ob_get_contents();

		ob_end_clean();

		// Print Panel
		$Logy_Admin->panel->admin_panel( $tabs, $content );

	}

}