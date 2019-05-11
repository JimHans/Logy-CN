<?php

/**
 * Login Widget
 */

class logy_login_widget extends WP_Widget {

	protected $logy;

	function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

		parent::__construct(
			'logy_login_widget',
			__( 'Logy 登录表单', 'logy' ),
			array( 'description' => __( 'Logy 登录插件', 'logy' ) )
		);

	}

	/**
	 * Login Widget Content
	 */
	public function widget( $args, $instance ) {

		//  is user is logged-in hide Form.
		if ( is_user_logged_in() ) {
			return false;
		}

		// Print Form
		echo '<div class="logy-login-widget">';
		$this->logy->form->get_form( 'login' );
		echo '</div>';

	}

	/**
	 * Login Widget Backend
	 */
	public function form( $instance ) {
		echo '<p>' . __( '此插件将自动显示Logy登录框', 'logy' ) . '</p>';
	}

}

/**
 * Register Widget
 */

class logy_register_widget extends WP_Widget {

	protected $logy;

	function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

		parent::__construct(
			'logy_register_widget',
			__( 'Logy 注册表单', 'logy' ),
			array( 'description' => __( 'Logy 注册插件', 'logy' ) )
		);
	}

	/**
	 * Register Widget Content
	 */
	public function widget( $args, $instance ) {

		//  is user is logged-in hide Form.
		if ( is_user_logged_in() ) {
			return false;
		}
		
		// Print Form
		echo '<div class="logy-register-widget">';
		$this->logy->form->get_form( 'register' );
		echo '</div>';
	}

	/**
	 * Register Widget Backend
	 */
	public function form( $instance ) {
		echo '<p>' . __( '此插件将自动显示Logy注册框', 'logy' ) . '</p>';
	}

}