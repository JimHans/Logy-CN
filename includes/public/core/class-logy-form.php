<?php

class Logy_Form {

	protected $logy;

	function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

	}

	/**
	 * Form Fields
	 */
	function get_page( $form, $attributes = null ) {

		do_action( 'logy_before_' . $form . '_form' );

		echo '<div class="logy logy-page-box">';
		$this->get_form( $form, $attributes );
		echo '</div>';

		do_action( 'logy_after_' . $form . '_form' );

	}

	/**
	 * Form Fields
	 */
	function get_form( $form, $shortcode_attrs = null ) {

		// Get Form Attributes
		$attributes = $this->logy->$form->attributes();
		$elements 	= $this->get_form_elements( $form );

		// Get Action Link
		if ( 'login' == $form ) {
			$action = wp_login_url();
		} elseif ( 'register' == $form ) {
			$action = wp_registration_url();
		} elseif ( 'lost_password' == $form && isset( $_GET['action'] ) && 'rp' == $_GET['action'] ) {
			$action = site_url( 'wp-login.php?action=resetpass' );
		} elseif ( 'lost_password' == $form ) {
			$action = wp_lostpassword_url();
		}

		?>
		
		<div class="<?php echo $attributes['form_class']; ?>">

			<?php $this->get_form_header( $form ); ?>
			<?php $this->get_form_messages( $attributes ); ?>

			<form method="post" action="<?php echo $action; ?>">

				<?php $this->generate_form_fields( $elements['fields'], $attributes ); ?>
				<?php $this->generate_form_actions( $elements['actions'], $attributes ); ?>

				<!-- After Form Buttons -->
				<?php do_action( 'logy_after_' . $form . '_buttons', $shortcode_attrs ); ?>

				<input type="hidden" name="logy-form" value="1" />

			</form>

		</div>

		<?php
	}

	/**
	 * Form Header
	 */
	function get_form_header( $form ) {

		// Get Form Title.
		if ( 'lost_password' == $form ) {
			$form_title = logy_options( 'logy_lostpswd_form_title' );
			$form_subtitle = logy_options( 'logy_lostpswd_form_subtitle' );
		} elseif ( 'register' == $form ) {
			
			$form_title = logy_options( 'logy_signup_form_title' );
			$form_subtitle = logy_options( 'logy_signup_form_subtitle' );

			if ( is_registration_incomplete() ) {
				$form_title = __( 'Complete Registration', 'logy' ) ;
			}

		} else {
			$form_title = logy_options( 'logy_login_form_title' );
			$form_subtitle 	= logy_options( 'logy_login_form_subtitle' );
		}

		// Sanitize Form Title & Subtitle
		$form_title = sanitize_text_field( $form_title );
		$form_subtitle = sanitize_text_field( $form_subtitle );

		// Get Form Options
		if ( 'lost_password' == $form ) {
			$form = 'lostpswd';
		} elseif ( 'register' == $form ) {
			$form = 'signup';
		}

		// Get Cover Data
		$form_cover = esc_url( logy_options( 'logy_' . $form . '_cover' ) );
		$enable_cover = logy_options( 'logy_' . $form . '_form_enable_header' );
		$cover_class = ! empty( $form_cover ) ? 'logy-custom-cover' : 'logy-default-cover';

		// If cover photo not exist use pattern.
		if ( ! $form_cover ) {
			$form_cover  = LOGY_PA . 'images/geopattern.png';
		}

		?>

    	<header class="logy-form-header">
	    	<?php if ( 'on' == $enable_cover ) : ?>
	    		<div class="logy-form-cover <?php echo $cover_class; ?>" style="background-image: url( <?php echo $form_cover; ?> )">
			        <h2 class="form-cover-title"><?php echo $form_title; ?></h2>
	    		</div>
	    	<?php else : ?>
	    		<div class="form-title">
		    		<h2><?php echo $form_title; ?></h2>
		    		<?php if ( ! empty( $form_subtitle ) ) : ?>
		    			<span class="logy-form-desc"><?php echo $form_subtitle; ?></span>
    				<?php endif; ?>
	    		</div>
    		<?php endif; ?>
    	</header>

	    <?php
	}

	/**
	 * Form Elements
	 */
	function get_form_elements( $form = null ) {

		// New Array's
		$fields = array();
		$actions = array();

		switch ( $form ) :

		case 'login':

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'user',
				'label'	=> __( '用户名或邮箱地址', 'logy' ),
				'id'	=> 'user_login',
				'name'	=> 'log',
				'type'	=> 'text'
			);

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'lock',
				'label'	=> __( '密码', 'logy' ),
				'id'	=> 'user_pass',
				'name'	=> 'pwd',
				'type'	=> 'password'
			);

			$fields[] = array(
				'item' 		=> 'remember-me',
				'label'		=> __( '记住我', 'logy' )
			);

			$actions[] = array(
				'item' 	=> 'submit',
				'icon'	=> 'sign-in'
			);

			if ( get_option( 'users_can_register' ) ) :
				$actions[] = array(
					'item' 	=> 'link',
					'icon'	=> 'pencil',
					'url'	=> logy_page_url( 'register' )
				);
			endif;

			$actions[] = array( 'item' 	=> 'lost_pswd' );

			$actions[] = array( 'item' 	=> 'redirect' );

		break;

		case 'register':

			$username_field = array(
				'item' 	=> 'input',
				'icon'	=> 'user',
				'label'	=> __( '用户名', 'logy' ),
				'id'	=> 'user_login',
				'name'	=> 'username',
				'type'	=> 'text'
			);

			$email_field = array(
				'item' 	=> 'input',
				'icon'	=> 'envelope-o',
				'label'	=> __( '电子邮件', 'logy' ),
				'id'	=> 'email',
				'name'	=> 'email',
				'type'	=> 'email'
			);

			$register_action = array(
				'item' 	=> 'submit',
				'icon'	=> 'pencil'
			);
			
			if ( is_registration_incomplete() ) {

				// Init Vars
				$errors = array();

				// Get Required Fields.
				$required_fields = json_decode( logy_user_session_data( 'get' ), true );

				if ( isset( $required_fields['email'] ) ) {
					$errors[] = sprintf( __( "- %s 未提供你的邮箱地址", 'logy' ), $required_fields['provider'] );
				}

				if ( isset( $required_fields['user_login'] ) ) {
					$errors[] = __( "- 用户名已存在！", 'logy' );
				}

				$erros_msg =  implode( '<br>', $errors ) ;

				if ( ! isset( $_GET['register-errors'] ) ) {
					$fields[] = array(
						'item' 	=> 'note',
						'note'	=> sprintf( __( "<strong>注意</strong> 我们无法获得你的以下信息 <br> %s", 'logy' ), $erros_msg ) 
					);
				}

				// Get Username Field
				if ( isset( $required_fields['user_login'] ) ) {
					$fields[] = $username_field;
				}
				
				if ( isset( $required_fields['email'] ) ) {
					$fields[] = $email_field;
				}

				$actions[] = $register_action;

				$fields[] = array(
					'item' 	=> 'hidden',
					'name'	=> 'complete-registration',
					'value'	=> 'true',
				);

				break;
			} 

			$fields[] = $username_field;

			$fields[] = $email_field;

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'address-card-o',
				'label'	=> __( '姓氏', 'logy' ),
				'id'	=> 'first_name',
				'name'	=> 'first_name',
				'type'	=> 'text'
			);

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'id-card',
				'label'	=> __( '名字', 'logy' ),
				'id'	=> 'last_name',
				'name'	=> 'last_name',
				'type'	=> 'text'
			);

			$fields[] = array(
				'item' 	=> 'note',
				'note'	=> __( '<strong>注意！</strong> 我们将向你发送一封账户激活邮件。', 'logy' )
			);

			$fields[] = array( 'item' => 'captcha' );

			// Display terms and conditions & privacy policy.
			if ( 'on' == logy_options( 'logy_show_terms_privacy_note' ) ) {

				$terms_url = logy_options( 'logy_terms_url' );
				$privacy_url = logy_options( 'logy_privacy_url' );

				$fields[] = array(
					'item'  => 'note',
					'class' => 'logy-terms-note',
					'note'  => sprintf( __( '注册即表明您同意 <a href="%1s">使用条款</a> 和我们的 <a href="%2s" >隐私政策</a>.', 'logy' ), $terms_url, $privacy_url )
				);
			}

			$actions[] = $register_action;

			$actions[] = array(
				'item' 	=> 'link',
				'icon'	=> 'sign-in',
				'url'	=> logy_page_url( 'login' )
			);

			break;

		case 'lost_password':

			if ( isset( $_GET['action'] ) && 'rp' == $_GET['action'] ) {

				$fields[] = array(
					'item' 	=> 'hidden',
					'name'	=> 'rp_login',
					'key'	=> 'login',
				);

				$fields[] = array(
					'item' 	=> 'hidden',
					'name'	=> 'rp_key',
					'key'	=> 'key',
				);

				$fields[] = array(
					'item' 	=> 'input',
					'icon'	=> 'lock',
					'label'	=> __( '新密码', 'logy' ),
					'id'	=> 'pass1',
					'name'	=> 'pass1',
					'type'	=> 'password'
				);

				$fields[] = array(
					'item' 	=> 'input',
					'icon'	=> 'lock',
					'label'	=> __( '确认新密码', 'logy' ),
					'id'	=> 'pass2',
					'name'	=> 'pass2',
					'type'	=> 'password'
				);

				$fields[] = array(
					'item' 	=> 'note',
					'note'	=> wp_get_password_hint()
				);

				$actions[] = array(
					'item' 	=> 'submit',
					'icon'	=> 'undo'
				);

			} else {

				$fields[] = array(
					'item' 	=> 'note',
					'note'	=> __( "输入你的邮箱地址，我们将向你发送一封确认邮件。", 'logy' )
				);

				$fields[] = array(
					'item' 	=> 'input',
					'icon'	=> 'envelope-o',
					'label'	=> __( '邮箱', 'logy' ),
					'id'	=> 'email',
					'name'	=> 'user_login',
					'type'	=> 'email'
				);

				$actions[] = array(
					'item' 	=> 'submit',
					'icon'	=> 'undo'
				);
			}
			break;

		endswitch;

		$elements = array( 'fields' => $fields, 'actions' => $actions );

		return $elements;
	}


	/**
	 * Form Class
	 */
	function get_form_class( $attributes = null ) {

		// Create New Array();
		$form_class = array();

		// Get Form Type.
		$form_type = $attributes['form_type'];

		// Get Form Options Data

		$silver_icons = array(
			'logy-field-v2', 'logy-field-v5', 'logy-field-v10'
		);

		$silver_inputs = array(
			'logy-field-v4', 'logy-field-v6', 'logy-field-v9'
		);

		$use_labels = array(
			'logy-field-v1','logy-field-v2', 'logy-field-v4', 'logy-field-v6', 'logy-field-v11'
		);

		$use_icons = array(
			'logy-field-v2','logy-field-v5', 'logy-field-v6', 'logy-field-v7',
			'logy-field-v8', 'logy-field-v9', 'logy-field-v10', 'logy-field-v11'
		);

		$full_border = array(
			'logy-field-v1','logy-field-v2', 'logy-field-v4', 'logy-field-v5','logy-field-v6',
			'logy-field-v8', 'logy-field-v9', 'logy-field-v11', 'logy-field-v12'
		);

		// Get Form Layout
		$form_layout = logy_options( 'logy_' . $form_type . '_form_layout' );

		// Check if header is Enable Or Disabled.
		if ( 'lost-password' == $attributes['form_action'] ) {
			$use_header = logy_options( 'logy_lostpswd_form_enable_header' );
		} else {
			$use_header = logy_options( 'logy_' . $form_type . '_form_enable_header' );
		}

		// Main Form Class
		$form_class[] = 'logy-form';

		// Add Registration	Incomplete class	
		if ( is_registration_incomplete() ) {
			$form_class[] = 'logy-complete-registration-page';
		}

		// Get Page Class Name
		$form_class[] = "logy-$form_type-page";
		if ( 'lost-password' == $attributes['form_action'] ) {
			$form_class[] = 'logy-lost-password-page';
		}

		// Get Header Type.
		$form_class[] = ( $use_header == 'on' ) ? 'logy-with-header' : 'logy-no-header';

		// Get Labels Type
		$form_class[] = in_array( $form_layout, $use_labels ) ? 'logy-with-labels' : 'logy-no-labels';

		// Get Labels Type
		$form_class[] = in_array( $form_layout, $silver_inputs ) ? 'logy-silver-inputs' : null;

		// Get Icons Type
		$form_class[] = in_array( $form_layout, $use_icons ) ? 'logy-fields-icon' : 'logy-no-icons';

		// Get Border Type
		$form_class[] = in_array( $form_layout, $full_border ) ? 'logy-full-border' : 'logy-bottom-border';

		// Get Border Format.
		$form_class[] = logy_options( 'logy_' . $form_type . '_fields_format' );

		// Icons Options
		if ( in_array( $form_layout, $use_icons ) ) {
			// Get icons position.
			$form_class[] = logy_options( 'logy_' . $form_type . '_icons_position' );
			// Get icons background.
			$form_class[] = in_array( $form_layout, $silver_icons ) ? 'logy-silver-icons' : 'logy-nobg-icons';
		}

		// Add Error Messages Class
		//注意！此代码引起了警告，因此被暂时剔除！
		/*if ( 'login' == $attributes['form_action'] ) {
			$form_class[] = (
				count( $attributes[ 'errors' ] ) > 0 ||
				count( $attributes[ 'logged_out' ] ) > 0 ||
				count( $attributes[ 'registered' ] ) > 0
			) ? 'logy-form-msgs' : null;
		}*/ else {
			$form_class[] = count( $attributes[ 'errors' ] ) > 0 ? 'logy-form-msgs' : null;
		}

		// Return Form Classes.
		return logy_generate_class( $form_class );
	}

	/**
	 * Form Messages
	 */
	function get_form_messages( $attrs ) {

		?>

		<?php if ( count( $attrs['errors'] ) > 0 )  : ?>
			<div class="logy-form-message logy-error-msg">
				<?php foreach ( $attrs['errors'] as $error_msg ) : ?>
					<p><strong><?php _e( 'ERROR', 'logy' ); ?> !</strong><?php echo $error_msg; ?></p>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( isset( $attrs['registered'] ) && $attrs['registered'] ) : ?>
			<div class="logy-form-message logy-success-msg">
				<p>
					<strong><?php _e( 'done!' , 'logy' ); ?></strong>
					<?php _e( '注册成功！你的密码已被发送到你填写的邮箱内', 'logy' ); ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( isset( $attrs['logged_out'] ) && $attrs['logged_out'] ) : ?>
			<div class="logy-form-message logy-info-msg">
				<p>
					<?php _e( '<strong>你已退出登录！</strong> 想要重新登录吗？', 'logy' ); ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( isset( $attrs['lost_password_sent'] ) && $attrs['lost_password_sent'] ) : ?>
			<div class="logy-form-message logy-info-msg">
				<p>
					<?php _e( '检查密码重置邮件', 'logy' ); ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( isset( $attrs['password_updated'] ) && $attrs['password_updated'] ) : ?>
			<div class="logy-form-message logy-success-msg">
				<p>
				<strong><?php _e( 'done!' , 'logy' ); ?></strong>
					<?php _e( '密码重置成功！现在你可以用新密码登录了！', 'logy' ); ?>
				</p>
			</div>
		<?php endif; ?>

	<?php
	}

	/**
	 * Form Fields
	 */
	function get_form_fields( $field, $attrs ) {

		// Get Fields By Type.
		switch ( $field['item'] ) {

			case 'input': ?>
				<div class="logy-form-item">
		    		<div class="logy-item-content">
			           	<?php if ( $attrs['use_labels'] ) : ?>
			           		<label for="<?php echo $field['id']; ?>"><?php echo sanitize_text_field( $field['label'] ); ?></label>
			        	<?php endif; ?>
			           <div class="logy-field-content">
		           			<?php if ( $attrs['use_icons'] ) : ?>
					           <div class="logy-field-icon">
		           					<i class="fa fa-<?php echo $field['icon']; ?>" aria-hidden="true"></i>
		           				</div>
		        			<?php endif; ?>
				    		<input type="<?php echo $field['type'];?>" name="<?php echo $field['name']; ?>" id="<?php echo $field['id']; ?>" placeholder="<?php if ( ! $attrs['use_labels'] ) { echo sanitize_text_field( $field['label'] ); } ?>" required>
			            </div>
		        	</div>
		       	</div>
			<?php	break;

			case 'remember-me': ?>
		    	<div class="logy-form-item logy-remember-me">
		    		<div class="logy-item-content">
			        	<label class="logy_checkbox_field" for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever"><div class="logy_field_indication"></div><?php echo $field['label']; ?></label>
		    			
		        	</div>
					<?php 
						if ( ! $attrs['actions_lostpswd'] ) {
							$this->lost_password_field();
						}
					?>
		        </div>
			<?php break;

			case 'submit': ?>
				<div class="logy-action-item logy-submit-item">
					<div class="logy-item-inner">
	           			<button type="submit" value="submit">
	            			<?php if ( $attrs['actions_icons'] ) : ?>
		           				<div class="logy-button-icon">
		           					<i class="fa fa-<?php echo $field['icon']; ?>" aria-hidden="true"></i>
		           				</div>
		           			<?php endif; ?>
	           				<?php echo sanitize_text_field ( $attrs['submit_title'] ); ?>
	           			</button>
	            	</div>
	            </div>
			<?php break;

			case 'link': ?>
				<div class="logy-action-item logy-link-item">
					<div class="logy-item-inner">
	            		<a href="<?php echo esc_url( $field['url'] ); ?>" class="logy-link-button" >
	            			<?php if ( $attrs['actions_icons'] ) : ?>
    							<div class="logy-button-icon">
		           					<i class="fa fa-<?php echo $field['icon']; ?>" aria-hidden="true"></i>
		           				</div>
		           			<?php endif; ?>
	           				<?php echo sanitize_text_field( $attrs['link_title'] ); ?>
	            		</a>
	            	</div>
	            </div>
			<?php break;

			case 'lost_pswd':
					if ( $attrs['actions_lostpswd'] ) {
						$this->lost_password_field();
					}
				break;

			case 'redirect': ?>
				<?php if ( isset( $_GET['redirect_to'] ) ) : ?>
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( $_GET['redirect_to'] ); ?>">
				<?php endif; ?>
			<?php break;

			case 'note':

				// Init Vars
				$note_class = array();
				$note_class[] = 'logy-form-note';
				$note_class[] = isset( $field['class'] ) ? $field['class'] : null;

				?>

				<div class="<?php echo logy_generate_class( $note_class ); ?>">
					<?php echo $field['note']; ?>
				</div>

			<?php break;

			case 'captcha': ?>
				<?php if ( $attrs['recaptcha_site_key'] ) : ?>
					<div class="logy-recaptcha-container">
						<div class="g-recaptcha" data-sitekey="<?php echo $attrs['recaptcha_site_key']; ?>"></div>
					</div>
				<?php endif; ?>
			<?php break;

			case 'hidden':

				$value = isset( $field['value'] ) ? $field['value'] : $attrs[ $field['key'] ];

			?>
				<input type="hidden" name="<?php echo $field['name']; ?>" value="<?php echo esc_attr( $value ); ?>" autocomplete="off" />
			<?php break;

		}
	}

	/**
	 * Generate Form Fields
	 */
	function generate_form_fields( $fields, $attributes ) {
		// Print Fields
		foreach ( $fields as $field ) {
			$this->get_form_fields( $field, $attributes );
		}
	}

	/**
	 * Generate Form Actions
	 */
	function generate_form_actions( $actions, $attributes ) {
		// Print Fields
		echo '<div class="' . $attributes['action_class'] . '">';
		foreach ( $actions as $action ) {
			$this->get_form_fields( $action, $attributes );
		}
		echo '</div>';
	}

	/**
	 * Lost Password Link
	 */
	function lost_password_field() {
		$field_title = sanitize_text_field( logy_options( 'logy_login_lostpswd_title' ) );
		echo '<a class="logy-forgot-password" href="' . wp_lostpassword_url() . '">' . $field_title . '</a>';
	}

}