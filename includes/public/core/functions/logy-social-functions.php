<?php

/**
 * Get Available Social Providers.
 */
function logy_get_social_login_box( $attrs = null ) {

	// Check if social login is enabled and there's at least one network available.
	if ( ! logy_is_social_login_enabled() ) {
		return false;
	}

    // Get Providers.
    $providers = logy_get_providers();

    // Get Buttons Type
    $btns_type = isset( $attrs['social_button_type'] ) ? $attrs['social_button_type'] : logy_options( 'logy_social_btns_type' );

    // Action
	do_action( 'logy_social_box_style' );

	?>
	

	<div class="<?php echo logy_get_social_box_class( $attrs ); ?>">
		<div class="logy-social-title">
			<span><?php _e( 'or', 'logy' ); ?></span>
		</div>
		<ul>
			<?php foreach( $providers as $provider ) : ?>
				
				<?php 
					// Hide Not Available Networks.
					if ( ! logy_is_provider_available( $provider ) ) {
						continue;
					}
				?>

				<?php $provider_url = home_url( '/?action=logy&provider=' . $provider ); ?>
				<?php $provider_data = logy_get_provider_data( $provider ); ?>
				<?php $provider_name = strtolower( $provider ); ?>
				
				<li class="logy-<?php echo $provider_name; ?>-btn">
					<a href="<?php echo $provider_url; ?>">
						<div class="logy-button-icon">
							<i class="fa fa-<?php echo $provider_data['icon']; ?>" aria-hidden="true"></i>
						</div>
						<?php if ( 'logy-only-icons' != $btns_type ) : ?>
							<span class="logy-button-title">
							<?php echo sprintf( __( 'connect with %s', 'logy' ), $provider );?>
							</span>
						<?php endif; ?>
					</a>
				</li>

			<?php endforeach; ?>
		</ul>
		
	</div>

	<?php
}

add_action( 'logy_after_login_buttons', 'logy_get_social_login_box' );
add_action( 'logy_after_register_buttons', 'logy_get_social_login_box' );

/**
 * Init Social Login.
 */
function logy_init_social_login( ) {

	if ( ! class_exists( 'Hybrid_Auth', false ) ) {
		require_once( LOGY_PATH . "library/Hybrid/Auth.php" );
	}

	if ( ! class_exists( 'Hybrid_Endpoint', false ) ) {
		require_once( LOGY_PATH . "library/Hybrid/Endpoint.php" );
	}

	if ( isset( $_REQUEST['hauth_start'] ) || isset( $_REQUEST['hauth_done'] ) ) {
	    // Entry point to the End point 
	    Hybrid_Endpoint::process();
	}

}

add_action( 'init', 'logy_init_social_login' );

/**
 * Get Available Social Networks.
 */
function logy_get_providers() {
	// Get Providers
	$providers = logy_options( 'logy_social_providers' );
	// Filter Providers
	$providers = apply_filters( 'logy_providers_list', $providers );
	// Return Providers.
	return $providers;
}

/**
 * Get Providers Data.
 */
function logy_get_provider_data( $provider ) {

	$data = array(
		'Facebook' => array(
			'app'	   => 'id',
			'icon' 	   => 'facebook'
		),
		'Twitter' => array(
			'app'	   => 'key',
			'icon' 	   => 'twitter'
		),
		'Google' => array(
			'app'	   => 'id',
			'icon' 	   => 'google'
		),
		'LinkedIn' => array(
			'app'	   => 'id',
			'icon' 	   => 'linkedin'
		),
		'Instagram' => array(
			'app'	   => 'id',
			'icon' 	   => 'instagram'
		)
	);

	$data = apply_filters( 'logy_providers_data', $data );

	return $data[ $provider ];
}

/**
 * Get Social Buttons Class
 */
function logy_get_social_box_class( $attrs = null ) {

	// Init Array();
	$class = array();

	// Get Main Class.
	$class[] = 'logy-social-buttons';

	// Get Button Type
	$class[] = isset( $attrs['social_button_type'] ) ? $attrs['social_button_type'] : logy_options( 'logy_social_btns_type' );

	// Get Button Border Type
	$class[] = logy_options( 'logy_social_btns_format' );

	// Get Button Icons Position.
	$class[] = logy_options( 'logy_social_btns_icons_position' );

	return implode( ' ', $class );
}

/**
 * Check if Social login is enabled.
 */
function logy_is_social_login_enabled( ) {

	// init Vars.
	$available_network = false;
	$is_social_login_enabled = logy_options( 'logy_enable_social_login' );

	if ( 'off' == $is_social_login_enabled ) {
		return false;
	}

	// Get Providers
	$providers = logy_get_providers();

	foreach( $providers as $provider ) {
		if ( logy_is_provider_available( $provider ) ) {
			$available_network = true;
			break;
		}
	}
	
	// Check if there's at least one available network. 
	if ( ! $available_network ) {
		return false;
	}


	return true;
}

/**
 * Get Complete Registration Url 
 */
function logy_get_complete_registration_url( ) {
	// Get Page Url.
	$url = add_query_arg('action', 'complete-registration', logy_page_url( 'register' ) );
	return $url;
}

/**
 * Check if Provider is available
 */
function logy_is_provider_available( $provider ) {

	$network = strtolower( $provider );

	$is_enabled = logy_options( 'logy_' . $network . '_app_status' );

	if ( 'off' == $is_enabled ) {
		return false;
	}

	$app_key = logy_options( 'logy_' . $network . '_app_key' );
	$app_secret = logy_options( 'logy_' . $network . '_app_secret' );

	if ( empty( $app_key ) || empty( $app_secret ) ) {
		return false;
	}

	return true;
}

/**
 * Change Avatar With User Provider Image
 */
add_filter( 'get_avatar' , 'logy_get_custom_avatar' , 1 , 5 );

function logy_get_custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    $user = false;

    if ( is_numeric( $id_or_email ) ) {

        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );

    } elseif ( is_object( $id_or_email ) ) {

        if ( ! empty( $id_or_email->user_id ) ) {
            $id = (int) $id_or_email->user_id;
            $user = get_user_by( 'id' , $id );
        }

    } else {
        $user = get_user_by( 'email', $id_or_email );	
    }

    if ( $user && is_object( $user ) ) {

    	// Get User Custom Avatar.
    	$user_custom_avatar = get_user_meta( $user->data->ID, 'logy_avatar', true );

    	//
    	// If user has avatar or have no network image return default avatar.
    	if ( logy_has_gravatar( $user->data->user_email ) || ! $user_custom_avatar ) {
    		return $avatar;
    	}

    	// Get Avatar Img.
        $avatar = "<img alt='{$alt}' src='{$user_custom_avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";

    }

    return $avatar;
}

/**
 * Check if User Has Gravatar
 */
function logy_has_gravatar( $email_address ) {

	// Get User Hash
	$hash = md5( strtolower( trim ( $email_address ) ) );

	// Build the Gravatar URL by hasing the email address
	$url = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';

	// Now check the headers...
	$headers = @get_headers( $url );

	// If 200 is found, the user has a Gravatar; otherwise, they don't.
	return preg_match( '|200|', $headers[0] ) ? true : false;

}

/**
 * Get/Set User Session Data
 */
function logy_user_session_data( $operation, $data = null ) {

	// Get User IP
	$user_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR']: false;

	// Get Option Name.
	$transient_name = 'logy_user_data_' . $user_ip;

	if ( 'get' == $operation ) {
		return get_transient( $transient_name );
	} elseif ( 'set' == $operation ) {
		set_transient( $transient_name, json_encode( $data ), HOUR_IN_SECONDS );
	} elseif ( 'delete' == $operation ) {
		return delete_transient( $transient_name );
	}

	return false;
}

/**
 * Get/Set User Profile Data Temporarily
 */
function logy_user_profile_data( $operation, $data = null ) {

	// Get User IP
	$user_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR']: false;

	// Get Option Name.
	$transient_name = 'logy_user_profile_' . $user_ip;

	if ( 'get' == $operation ) {
		return get_transient( $transient_name );
	} elseif ( 'set' == $operation ) {
		set_transient( $transient_name, json_encode( $data ), HOUR_IN_SECONDS );
	} elseif ( 'delete' == $operation ) {
		return delete_transient( $transient_name );
	}

	return false;
}
