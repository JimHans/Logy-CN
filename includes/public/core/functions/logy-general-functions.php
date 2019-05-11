<?php

/**
 * Logy Options
 */
function logy_options( $option_id ) {

    $option_value = get_option( $option_id );

    // Filter Options.
    if ( has_filter( 'logy_edit_options' ) ) {
        $option_value = apply_filters( 'logy_edit_options', $option_id );
    }

    if ( ! isset( $option_value ) || empty( $option_value ) ) {
        global $Logy_default_options;
        if ( isset( $Logy_default_options[ $option_id ] ) ) {
            $option_value = $Logy_default_options[ $option_id ];
        }
    }

    return $option_value;
}

/**
 * # Get Plugin Pages
 */
function logy_pages( $request_type = null, $id = null ) {

    // Get pages.
    $logy_pages = logy_options( 'logy_pages' );

    // Switch Key <=> Values
    if ( 'ids' == $request_type ) {
        $pages_ids = array_flip( $logy_pages );
        return $pages_ids;
    }

    return $logy_pages;
}

/**
 * # Check if it's a Logy Plugin Page
 */
function is_logy_page() {
    // Get Pages By ID's
    $pages = logy_pages( 'ids' );
    // check if its our plugin page.
    if ( is_page() && isset( $pages[ get_the_ID() ] ) ) {
        return true;
    }
    return false;
}

/**
 * # Get Page Template.
 */
function logy_template( $page_template ) {
    // check if its logy plugin page
    if ( is_logy_page() ) {
		// Print Template
		return LOGY_PATH . 'includes/public/templates/logy-template.php';
    }
    return $page_template;
}
add_filter( 'page_template', 'logy_template' );


/**
 * # Get Page Shortcode.
 */
function logy_get_page_shortcode( $page_id = null ) {

    // Get Plugin Pages.
    $pages = array_flip( logy_options( 'logy_pages' ) );

    // Get Page Name.
    $page = $pages[ $page_id ];

    // Set Shortcode.
    $shortcode = '[logy_' . str_replace( '-', '_', $page ) . '_page]';

    return $shortcode;
}

/**
 * # Get Page URL.
 */
function logy_page_url( $page_name, $user_id = null ) {

	// Get Page Data
    $page_id  = logy_page_id( $page_name );
    $page_url = logy_fix_path( get_permalink( $page_id ) );

	// Return Page Url.
    return $page_url;

}

/**
 * # Get Page ID.
 */
function logy_page_id( $page ) {
    $pages = logy_options( 'logy_pages' );
    return $pages[ $page ];
}

/**
 * Get Wordpress Pages
 */
function logy_get_pages() {

    // Set Up Variables
    $pages    = array();
    $wp_pages = get_pages();

    foreach ( $wp_pages as $page ) {
        $pages[ $page->ID ] = $page->post_title;
    }

    return $pages;
}

/**
 * # Class Generator.
 */
function logy_generate_class( $classes ) {
    // Convert Array to String.
    return implode( ' ' , array_filter( $classes ) );
}

/**
 * # Form Messages.
 */
add_action( 'logy_panel_after_form', 'logy_form_messages' );

function logy_form_messages() {

    ?>

    <!-- Dialog -->
    <div class="quicket-dialog"></div>
    <div class="klabs-form-msg">
        <div id="klabs-action-message"></div>
        <div id="klabs-wait-message">
            <div class="klabs_msg wait_msg">
                <div class="klabs-msg-icon">
                    <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                </div>
                <span><?php _e( 'Please wait ...', 'quicket' ); ?></span>
            </div>
        </div>
    </div>

    <?php

}

/**
 * Popup Dialog Message
 */
function logy_popup_dialog( $type = null ) {

    // Init Alert Types.
    $alert_types = array( 'reset_tab', 'reset_all' );

    // Get Dialog Class.
    $form_class = ( ! empty( $type ) && in_array( $type, $alert_types ) ) ? 'alert' : 'error';
    
    // Get Dialog Name.
    $form_type  = ( ! empty( $type ) && in_array( $type, $alert_types ) ) ? $type : 'error';

    ?>

    <div id="klabs_popup_<?php echo $form_type; ?>" class="klabs-popup klabs-<?php echo $form_class; ?>-popup">
        <div class="klabs-popup-container">
            <div class="klabs-popup-msg"><?php logy_get_dialog_msg( $form_type ); ?></div>
            <ul class="klabs-buttons"><?php logy_get_dialog_buttons( $form_type ); ?></ul>
            <i class="fa fa-times klabs-popup-close" aria-hidden="true"></i>
        </div>
    </div>

    <?php
}

/**
 * Get Pop Up Dialog Buttons
 */
function logy_get_dialog_buttons( $type = null ) {

    // Get Cancel Button title.
    $confirm = __( 'confirm', 'logy' );
    $cancel  = ( 'error' == $type ) ? __( 'Got it!', 'logy' ) : __( 'cancel', 'logy' );

    if ( 'reset_all' == $type ) : ?>
        <li>
            <a class="klabs-confirm-popup klabs-confirm-reset" data-reset="all"><?php echo $confirm; ?></a>
        </li>
    <?php elseif ( 'reset_tab' == $type ) : ?>
        <li>
            <a class="klabs-confirm-popup klabs-confirm-reset" data-reset="tab"><?php echo $confirm; ?></a>
        </li>
    <?php endif; ?>

    <li><a class="klabs-close-popup"><?php echo $cancel; ?></a></li>

    <?php
}

/**
 * Get Pop Up Dialog Message
 */
function logy_get_dialog_msg( $type = null ) {

    if ( 'reset_all' == $type ) : ?>

    <span class="dashicons dashicons-warning"></span>
    <h3><?php _e( 'Are you sure you want to reset all the settings?', 'logy' ); ?></h3>
    <p><?php _e( 'Be careful! this will reset all the plugin settings.', 'logy' ); ?></p>

    <?php elseif ( 'reset_tab' == $type ) : ?>

    <span class="dashicons dashicons-warning"></span>
    <h3><?php _e( 'Are you sure you want to do this ?', 'logy' ); ?></h3>
    <p><?php _e( 'Be careful! this will reset all the current tab settings.', 'logy' ); ?></p>

    <?php elseif ( 'error' == $type ) : ?>

    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
    <h3><?php _e( 'Oops!', 'logy' ); ?></h3>
    <div class="klabs-msg-content"></div>

    <?php endif;

}  

/**
 * Edit Navigation Menu
 */
function logy_edit_nav_menu( $items, $args ) {

    // Set up Array's.
    $forms_pages = array( logy_page_id( 'register' ), logy_page_id( 'lost-password' ) );

    foreach( $items as $key => $item ) {

        // if user logged-in change the Login Page title to Logout.
        if ( $item->object_id == logy_page_id( 'login' ) && is_user_logged_in() ) {
            $item->url   = wp_logout_url();
            $item->title = __( 'Logout', 'logy' );
        }

        // if user is logged-in remove the register page from menu.
        if ( in_array( $item->object_id, $forms_pages ) && is_user_logged_in() ) {
            unset( $items[ $key ] );
        }

    }

    return $items;
}
add_filter( 'wp_nav_menu_objects', 'logy_edit_nav_menu', 10, 2 );

/**
 * Fix Url Path.
 */
function logy_fix_path( $url ) {
    $url = str_replace( '\\', '/', trim( $url ) );
    return ( substr( $url,-1 ) != '/' ) ? $url .= '/' : $url;
}

/**
 * # Get Post ID .
 */
function logy_get_post_id( $post_type, $key_meta , $meta_value ) {

    // Get Posts
    $posts = get_posts(
        array(
            'post_type'  => $post_type,
            'meta_key'   => $key_meta,
            'meta_value' => $meta_value )
        );

    if ( isset( $posts[0] ) && ! empty( $posts ) ) {
        return $posts[0]->ID;
    }

    return false;
}

/**
 * Get Arguments consedering default values.
 */
function logy_get_args( $pairs, $atts, $prefix = null ) {

    // Set Up Arrays
    $out  = array();
    $atts = (array) $atts;

    // Get Prefix Value.
    $prefix = $prefix ? $prefix . '_' : null;

    // Get Values.
    foreach ( $pairs as $name => $default ) {
        if ( array_key_exists(  $prefix . $name, $atts ) ) {
            $out[ $name ] = $atts[ $prefix . $name ];
        } else {
            $out[ $name ] = $default;
        }
    }

    return $out;
}

/**
 * # Get Image Url.
 */
function logy_get_img_url( $path = null ) {

    if ( ! empty( $path ) ) {
        $img_path = $path;
    } else {
        $img_path = LOGY_PA . 'images/default-img.png';
    }

    return $img_path;
}

/**
 * Check If Registration is Incomplete
 */
function is_registration_incomplete() {
    
    // Get User Session Data.
    $user_session_data = logy_user_session_data( 'get' );

    if ( isset( $_GET['action'] ) && 'complete-registration' == $_GET['action'] && ! empty( $user_session_data ) ) {
        return true;
    }

    return false;
}

/**
 * Check If Limit login is enabled.
 */
function is_limit_login_enabled() {

    // Get Limit Login Option
    $enabled = logy_options( 'logy_enable_limit_login' );

    // Check ?
    if ( 'on' == $enabled ) {
        return true;
    }

    return false;
}

/**
 * Hide Dashboard Admin Bar For Non Admins.
 */
function logy_hide_dashboard() {

    // Setup Variables.
    $hide_dashboard = logy_options( 'logy_hide_subscribers_dash' );

    if ( 'on' != $hide_dashboard ) {
        return false;
    }

    // Hide Admin Bar.
    if ( ! is_admin() && current_user_can( 'subscriber' ) ) {
        show_admin_bar( false );
    }

    // Hide Admin Dashboard.
    if ( is_admin() && current_user_can( 'subscriber' ) &&
        ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        wp_redirect( home_url() );
        exit;
    }

}

add_action( 'init', 'logy_hide_dashboard' );

/**
 * Login Form Short Code "[logy_login]"; 
 */
function logy_login_shortcode( $attributes = null ) {

    global $Logy;
    
    // Print Form
    echo '<div class="logy-login-shortcode">';
    $Logy->form->get_form( 'login', $attributes );
    echo '</div>';

}

add_shortcode( 'logy_login', 'logy_login_shortcode' );

/**
 * Register Form Short Code "[logy_register]"; 
 */
function logy_register_shortcode( $attributes = null ) {

    global $Logy;

    // Print Form
    echo '<div class="logy-register-shortcode">';
    $Logy->form->get_form( 'register', $attributes );
    echo '</div>';

}

add_shortcode( 'logy_register', 'logy_register_shortcode' );

/**
 * Lost Password Form Short Code "[logy_lost_password]"; 
 */
function logy_lost_password_shortcode( $attributes = null ) {

    global $Logy;

    // Print Form
    echo '<div class="logy-lost-password-shortcode">';
    $Logy->form->get_form( 'lost_password', $attributes );
    echo '</div>';

}

add_shortcode( 'logy_lost_password', 'logy_lost_password_shortcode' );
