<?php

class Logy_Fields {

	function __construct() {

	}

	/**
	 * # Fields Generator.
	 */
	function get_field( $option, $options_name = 'logy_options' ) {

		// Get Data.
		if ( 'open' != $option['type']  && 'close' != $option['type']  && ! empty( $option['id'] ) ) {
			// Set Up Variablesyz.
			$real_value = null;
			// Get Default Value.
			$default_value = ! empty( $option['std'] ) ? $option['std'] : null ;
			// Get Option Value
			$option_value = logy_options( $option['id'] );
			// Get Default Value.
			$user_value = ! empty( $option_value ) ? $option_value : $default_value;			
		}

		// Get Option Value.
		$real_value = ! empty( $user_value ) ? $user_value : null;

		// Forbidden types.
		$forbidden_types = array(
			'open', 'close', 'start', 'end', 'msgBox',
			'imgSelect', 'hidden', 'openBox', 'closeBox',
			'openDiv', 'closeDiv'
		);

		if ( ! in_array( $option['type'], $forbidden_types ) ) {
			$field_description = isset( $option['desc'] ) ?
			 '<p class="option-desc">' . $option['desc'] .'</p>' : null;

			$option_class = isset( $option['class'] ) ? ' ' . $option['class'] : null;
		?>

			<div class="klabs-option-item<?php echo $option_class; ?>">
				<div class="option-infos">
					<label for="<?php echo $option['id']; ?>" class="option-title"><?php if ( ! empty( $option['title'] ) ) echo $option['title']; ?></label><?php echo $field_description; ?>
				</div>
				<div class="option-content">

		<?php

		}

		$this->get_option( $options_name, $option, $real_value );

		// Close Option Divs
		if ( ! in_array( $option['type'], $forbidden_types ) ) {
			echo '</div></div>';
		}

	}

	function get_option( $options_name, $option, $real_value ) {

		// Get Filed Data.
		$field_id    = isset( $option['id'] ) ? $option['id'] : null;
		$field_title = isset( $option['title'] ) ? $option['title'] : null;
		$field_name  = ! empty( $field_id ) ? 'name="' . $options_name . '[' . $field_id . ']"' : null;

		// Standard Field Name.
		if ( isset( $option['no_options'] ) ) {
		   $field_name = "name='$field_id'";
		}

		// Hide Field Name.
		if ( isset( $option['hide_name'] ) ) {
			$field_name = null;
		}

		switch ( $option['type'] ) :

		case 'open':

			// Get Tab ID
			if ( empty( $option['id'] ) ) {
				$tab_id = str_replace( ' ', '-', strtolower( $option['title'] ) );
			} else {
				$tab_id = $option['id'];
			}

			$tab_class = ! isset( $option['widget_section'] ) ? 'klabs-no-widgets' : null;
			$button_id = isset( $option['button_id'] ) ? $option['button_id'] : null;

			?>

			<form id="klabs-<?php echo $tab_id; ?>" method="post" class="klabs-settings-form">
				<div class="options-section-title">
					<h2>
                    	<i class="fa fa-<?php echo $option['icon']; ?>" aria-hidden="true"></i>
                    	<?php echo $field_title; ?>
                    </h2>
					<div class="qt-form-actions">
						<?php if ( $button_id ) : ?>
						<a id="<?php echo $button_id; ?>" class="yza-item-button">
							<?php echo $option['button_text']; ?>
						</a>
						<?php endif; ?>
						<button name="save" class="klabs-save-options" type="submit">
							<?php _e( 'save changes', 'logy' ); ?>
						</button>
					</div>
				</div>
				<div class="klabs-section-content <?php echo $tab_class ?>">

		<?php break;

		case 'close':

			?>

				</div><!-- .klabs-settings-form-->

				<?php $this->form_action(); ?>

			</form>

		<?php break;

		case 'start':

			// Get Form Class
			$form_class = isset( $option['class'] ) ? 'klabs-settings-form ' . $option['class'] : 'klabs-settings-form';

			?>

			<form id="<?php echo $option['id']; ?>" class="<?php echo $form_class; ?>">
				<div class="klabs-panel-actions klabs-header-actions">
	            	<div class="klabs-panel-title">
						<h2><i class="fa fa-<?php echo $option['icon']; ?>" aria-hidden="true"></i><?php echo $option['title']; ?></h2>
	                </div>
	                <?php $this->admin_form_actions( 'top' ); ?>
				</div>
				<div class="klabs-section-content">

		<?php	break;

		case 'end':
			echo '</div><div class="klabs-panel-actions klabs-footer-actions">';
	        $this->admin_form_actions( 'bottom' );
	        echo '</div></form>';
			break;

		case 'openDiv':

			$class_name = $option['class'];
			echo "<div class='$class_name'>";
			break;

		case 'closeDiv':

			echo '</div>';
			break;

		case 'openBox':

			// Get Box Class
			$box_class = ! empty( $option['class'] ) ? 'klabs-box-item ' . $option['class'] : 'klabs-box-item';

			?>

			<div class="<?php echo $box_class; ?>">
				<div class="klabs-box-title">
					<h2><?php echo $field_title; ?></h2>
				</div>
				<div class="klabs-box-content">

			<?php

			break;

		case 'closeBox';

			echo '</div></div>';

			break;

		case 'sectionTitle'; ?>

			<div class="klabs-box-title">
				<h2><?php echo $field_title; ?></h2>
			</div>

		<?php break;

		case 'text':

		$placeholder = isset( $option['placeholder'] ) ? $field_title : null;

		?>

			<input type="text" id="<?php echo $field_id; ?>" <?php echo $field_name; ?> placeholder="<?php echo $placeholder; ?>" value="<?php echo $real_value; ?>" />

		<?php break;

		case 'password': ?>

			<input type="password" id="<?php echo $field_id; ?>" name="logy_options_pswd[<?php echo $field_id; ?>]" placeholder="<?php echo $field_title; ?>" value="<?php echo $real_value; ?>" />

		<?php break;

		case 'number':

			$step = isset( $option['step'] ) ? $option['step'] : '1';

		?>

			<input type="number" step="<?php echo $step; ?>" class="klabs-number-input" value="<?php echo $real_value; ?>" id="<?php echo $field_id; ?>" <?php echo $field_name; ?> />

		<?php break;

		case 'hidden':

			$class = isset( $option['class'] ) ? 'klabs-hidden-input ' . $option['class'] : 'klabs-hidden-input';

		?>

			<input class="<?php echo $class; ?>" type="hidden" <?php echo $field_name; ?> value="<?php echo $real_value; ?>" />

		<?php break;

		case 'textarea': ?>

			<textarea <?php echo $field_name; ?> ><?php echo esc_textarea( $real_value ); ?></textarea>

		<?php break;

		case 'button': ?>

			<a id="<?php echo $field_id; ?>" class="klabs-option-button" ><?php echo $option['button_title']; ?></a>

		<?php break;

		case 'image':

			global $Quicket_Vars;

			// Get Images Data.
			$img_preview = logy_get_img_url( $real_value );

			// Show/Hide Trash Icon.
			$trash_icon_class = 'fa fa-trash klabs-delete-photo';
			$trash_icon_class .= ( $img_preview != $Quicket_Vars['default_img'] ) ? ' klabs-show-trash' : '';

		?>

			<div class="klabs-uploader-item">
	            <label for="upload_<?php echo $field_id; ?>" class="klabs-upload-photo" ><?php _e( 'upload image', 'logy' ) ?></label>
	            <input id="upload_<?php echo $field_id; ?>" type="file" name="upload_<?php echo $field_id; ?>" class="klabs_upload_file" accept="image/*" />
	            <div class="klabs-photo-preview" style="background-image: url(<?php echo $img_preview; ?>);">
					<i class="<?php echo $trash_icon_class; ?>" aria-hidden="true"></i>
	            </div>
				<input type="hidden" class="qt-photo-url" <?php echo $field_name; ?> value="<?php echo $real_value; ?>"/>
			</div>

		<?php break;

		case 'icon':

			$icons_type = empty( $option['icons_type'] ) ? "web_application" : $option['icons_type'];

			?>

			<div id="<?php echo $field_id; ?>" class="klabs_iconPicker" data-icons-type="<?php echo $icons_type; ?>">
				<div class="klabs_icon_selector">
					<i class="fa fa-<?php echo $real_value; ?>" aria-hidden="true"></i>
					<span class="klabs_select_icon">
						<i class="fa fa-sort-desc" aria-hidden="true"></i>
					</span>
				</div>
				<input type="hidden" class="klabs-selected-icon" <?php echo $field_name; ?> value="<?php echo $real_value; ?>">
			</div>

		<?php break;

		case 'upload':

			// Get Image Preview.
			$img_preview = logy_get_img_url( $real_value );

			?>

			<div id="<?php echo $field_id; ?>" class="klabs-uploader">
				<div class="klabs-upload-photo">
					<input type="text" class="klabs-photo-url" <?php echo $field_name; ?> value="<?php echo $real_value; ?>"/>
					<input type="button" class="klabs-upload-button" value="Upload"/>
				</div>
				<div class="klabs-photo-preview" style="background-image: url( <?php echo $img_preview ?> );">
				</div>
			</div>

		<?php break;

		case 'select':

			echo "<div class='klabs-select-field'><select id='$field_id' $field_name>";

			// Loop options
			foreach ( $option['opts'] as $key => $value ) {
				// Which options should be selected
				$active_attr = ( $key == $real_value ) ? 'selected' : null;
				// Print Option.
				echo "<option value='$key' $active_attr>$value</option>";
			}

			echo '</select><div class="klabs-select-arrow"></div></div>';

		break;

		case 'radio':

			foreach ( $option['opts'] as $value => $key ) {

				// Which options should be selected
				$active_attr = ( $value == $real_value ) ? 'checked' : null;

				$radio_id = "$field_id-$value";

				?>

				<label class="klabs-label-radio" for="<?php echo $radio_id; ?>"><input type="radio" id="<?php echo $radio_id; ?>" <?php echo $field_name; ?> value="<?php echo $value;?>" <?php echo $active_attr;?>><div class="klabs_field_indication"></div><?php echo $key; ?></label>

				<?php
			}

		break;

		case 'checkbox':

			// Which options should be selected
			$active_attr = ( 'on' == $real_value ) ? 'checked' : '';

			// Convert Registration Value
			if ( $field_id == 'users_can_register' && get_option( 'users_can_register' ) == 1 ) {
				$active_attr = 'checked';
			}

			?>

			<div class="klabs-checkbox-item">
				<input class="klabs-hidden-input" value="off" type="hidden" <?php echo $field_name; ?>>
				<input id="<?php echo $field_id; ?>" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" value="on" <?php echo $field_name; ?> <?php echo $active_attr; ?>>
				<label for="<?php echo $field_id;?>"></label>
			</div>

		<?php break;

		case 'color';

			// Get Color Value
			$color_value = ! empty( $real_value['color'] ) ? $real_value['color'] : null;
			$field_name  = ! empty( $field_id ) ? 'name="' . $options_name . '[' . $field_id . '][color]"' : null;

			// Standard Field Name.
			if ( isset( $option['no_options'] ) ) {
			   $field_name = "name='$field_id'";
			}

			?>

			<input type="text" class="klabs-picker-input" <?php echo $field_name; ?> value="<?php echo $color_value; ?>">

		<?php break;

		case 'tags':

			$field_name  = ! empty( $field_id ) ?  $options_name . '[' . $field_id . ']' : null;

			// Standard Field Name.
			if ( isset( $option['no_options'] ) ) {
			   $field_name = "$field_id";
			}

		?>

			<ul class="klabs_tags" data-option-name="<?php echo $field_name; ?>[]">

				<li class="tagAdd taglist">
					<input type="text" class="klabs_tags_field">
				</li>

				<?php $tags = $real_value; ?>

				<?php if ( $tags ) : foreach ( $tags as $tag ) : ?>

					<li class="addedTag">
						<?php echo $tag; ?>
						<span class="klabs-tagRemove">x</span>
						<input type="hidden" value="<?php echo $tag; ?>" name="<?php echo $field_name; ?>[]">
					</li>

				<?php endforeach; endif; ?>

			</ul>

		<?php break;

		case 'msgBox':

			$show_msg = $real_value;

            ?>

            <input type="hidden" <?php echo $field_name; ?> value="<?php echo $real_value; ?>">

            <?php
			
			// Hide Message if its disabled by the user or there's no message content.
			if ( 'never' == $show_msg || empty( $option['msg'] ) ) {
				return false;
			}

			// Message Default Class.
			$msg_class[] = 'klabs-panel-msg';

			// Get User Message Class.
			if ( isset( $option['msg_type'] ) ) {
				$msg_class[] = 'klabs-' . $option['msg_type'] . '-msg';
			}

			// Show Or Hide Message
			if ( 'on' == $show_msg ) {
				$msg_class[] = 'klabs-show-msg';
			}

			// Get Data Id.
			$data_id = $options_name . "[{$field_id}]";

			?>

            <div class="<?php echo logy_generate_class( $msg_class ); ?>" data-id="<?php echo $data_id; ?>">
            	<div class="klabs-msg-head">
	                <span class="dashicons dashicons-editor-help klabs-msg-icon"></span>
	                <h3><?php echo $option['title']; ?></h3>
	                <div class="klabs-msg-actions">
		                <span class="dashicons dashicons-arrow-down-alt2 klabs-toggle-msg"></span>
		                <span class="dashicons dashicons-no-alt klabs-close-msg" title="<?php _e( "不要再显示此信息", 'logy' ); ?>"></span>
	                </div>
            	</div>
                <div class="klabs-msg-content">
                	<p><?php echo $option['msg']; ?></p>
                </div>
            </div>

	        <?php break;

		case 'imgSelect':

			foreach( $option['opts'] as $key => $value ) {

				// Which options should be selected
				if ( $value == $real_value ) {
					$active_attr = 'checked';
				} else {
					$active_attr = '';
				}

				// Get Key Value
				$key = is_numeric( $key ) ? $value : $key;

				// Get item ID
				$item_id = "$field_id-$key";

				?>

				<div class="imgSelect">
					<input type="radio" id="<?php echo $item_id ; ?>" <?php echo $field_name; ?> value="<?php echo $value; ?>" <?php echo $active_attr; ?>>
					<label for="<?php echo $item_id; ?>">
						<?php if ( ! isset( $option['use_class'] ) ) : ?>
							<?php $img_path = LOGY_AA . "images/imgSelect/$key.png"; ?>
							<img class="img-selection2" src="<?php echo $img_path; ?>" alt=""/>
						<?php endif; ?>
					</label>
				</div>

				<?php

			}

		break;

		endswitch;

	}

	/**
	 * # Form Save Changes Area.
	 */
	function form_action() {

		$security_nonce = wp_create_nonce( 'logy_nonce_security' );

		?>

		<div class="klabs-settings-actions">
            <input type="hidden" name="action" value="logy_save_settings">
            <input type="hidden" name="security" value="<?php echo $security_nonce; ?>">
            <button name="save" class="klabs-save-options" type="submit">
            	<?php _e( '保存设置', 'logy' ) ?>
            </button>
            <div class="klabs-copyright">
        		<p>
        			<?php _e( '乐由科技汉化，由' ); ?>
        			<a href="http://www.kainelabs.com" target="_blank">KAINELABS.COM设计并拥有版权</a>
        		</p>
        	</div>
        </div>

		<?php

	}

	/**
	 * # Form Actions Area.
	 */
	function admin_form_actions( $position ) {

		?>

        <div class="panel-<?php echo $position; ?>-actions">

            <div class="klabs-actions-buttons">
                <input type="hidden" name="action" value="logy_admin_save_data" />
                <input type="hidden" name="security" value="<?php echo wp_create_nonce( "logy-settings-data" )?>" />
                <button name="save" class="klabs-save-options" type="submit">
                	<?php _e( '保存设置', 'logy' );  ?>
                </button>

        		<?php if ( 'bottom' == $position ) : ?>
                	<a class="klabs-reset-options"><?php _e( '重置设置', 'logy' ); ?></a>
                <?php endif; ?>

            </div>

        	<?php if ( 'bottom' == $position ) : ?>
				<div class="klabs-copyright">
            		<p>
            			<?php _e( '乐由科技汉化，由' ); ?>
            			<a href="http://www.kainelabs.com" target="_blank">KAINELABS.COM设计并拥有版权</a>
            		</p>
            	</div>
         	<?php endif; ?>

        </div>

		<?php

	}

	/**
	 * # Field Options .
	 */
	function get_field_options( $element ) {
		$options = array(
			'user_login_redirect_pages' => array(
				'home'      => __( 'home', 'logy' ),
				'profile' => __( 'profile', 'logy' ),
			),
			'admin_login_redirect_pages' => array(
				'home'      => __( 'home', 'logy' ),
				'dashboard' => __( 'dashboard', 'logy' ),
			),
			'logout_redirect_pages' => array(
				'home'      => __( 'home', 'logy' ),
				'login'   	=> __( 'login', 'logy' ),
			),
			'border_styles'     => array(
				'flat'          => __( 'flat', 'logy' ),
				'radius'        => __( 'radius', 'logy' ),
				'circle'        => __( 'circle', 'logy' )
			),
			'form_icons_position' => array(
				'logy-icons-left'   => __( 'left', 'logy' ),
				'logy-icons-right'  => __( 'right', 'logy' ),
			),
			'fields_format' => array(
				'logy-border-flat'     => __( 'flat', 'logy' ),
				'logy-border-radius'   => __( 'radius', 'logy' ),
				'logy-border-rounded'  => __( 'rounded', 'logy' ),
			),
			'social_buttons_type' => array(
				'logy-only-icons'  => __( 'Only Icons', 'logy' ),
				'logy-full-button' => __( 'Full Width', 'logy' ),
			),
			'image_formats'     => array(
				'flat', 'radius', 'circle'
			),
			'input_types' => array(
				'text'      => __( 'text', 'logy' ),
				'number'    => __( 'number', 'logy' ),
				'textarea'  => __( 'textarea', 'logy' ),
				'select'    => __( 'select', 'logy' ),
				'radio'     => __( 'radio', 'logy' ),
			),
			'mail_content_type' => array(
				'text/plain' => __( 'text/plain', 'logy' ),
				'text/html'  => __( 'text/html', 'logy' )
			)
		);
		return $options[ $element ];
	}
}