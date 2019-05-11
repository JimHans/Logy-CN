<?php

/**
 * # Social Login Settings.
 */
function logy_social_login_settings() {
    
    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( 'enable social login', 'logy' ),
            'desc'  => __( 'activate social login', 'logy' ),
            'id'    => 'logy_enable_social_login',
            'type'  => 'checkbox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( 'Buttons Type', 'logy' ),
            'opts'  => $Logy_Settings->get_field_options( 'social_buttons_type' ),
            'desc'  => __( 'select buttons type', 'logy' ),
            'id'    => 'logy_social_btns_type',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( 'Buttons icons position', 'logy' ),
            'opts'  => $Logy_Settings->get_field_options( 'form_icons_position' ),
            'desc'  => __( 'select buttons icons position', 'logy' ),
            'id'    => 'logy_social_btns_icons_position',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( 'Buttons border style', 'logy' ),
            'opts'  => $Logy_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( 'select buttons border style', 'logy' ),
            'id'    => 'logy_social_btns_format',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    // Get Providers.
    $providers = logy_get_providers();

    if ( empty( $providers ) ) {
        return false;
    }
    
    foreach( $providers as $provider ) :

        // Get Provider Data.
        $provider_data = logy_get_provider_data( $provider );

        // Get Provider.
        $provider = strtolower( $provider );

        // Get Key Or ID.
        $key = ( 'key' == $provider_data['app'] ) ? __( 'Key', 'Logy' ) : __( 'ID', 'Logy' );

        // Get Setup Instruction.
        get_provider_settings_note( $provider );

        $Logy_Settings->get_field(
            array(
            'title' => sprintf( __( '%s Settings', 'logy' ), $provider ),
            'type'  => 'openBox'
            )
        );


        $Logy_Settings->get_field(
            array(
                'title' => __( 'Enable Network', 'logy' ),
                'desc'  => __( 'enable application', 'logy' ),
                'id'    => 'logy_' . $provider . '_app_status',
                'type'  => 'checkbox',
                'std'   => 'on'
            )
        );

        $Logy_Settings->get_field(
            array(
                'title' => sprintf( __( 'Application %s', 'logy' ), $key ),
                'desc'  => sprintf( __( 'enter application %s', 'logy' ), $key ),
                'id'    => 'logy_' . $provider . '_app_key',
                'type'  => 'text'
            )
        );

        $Logy_Settings->get_field(
            array(
                'title' => __( 'Application Secret', 'logy' ),
                'desc'  => __( 'enter application secret key', 'logy' ),
                'id'    => 'logy_' . $provider . '_app_secret',
                'type'  => 'text'
            )
        );

        $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    endforeach;

}


/**
 * Get Setup Instructions.
 */
function get_provider_settings_note( $provider ) {

    global $Logy_Settings;

    $steps = get_provider_instructions( $provider );

    $steps = apply_filters( 'logy_providet_setup_instrcutions', $steps );

    if ( empty( $steps ) ) {
        return false;
    }

    $Logy_Settings->get_field(
        array(
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'logy_' . $provider . '_setup_steps',
            'title'     => sprintf( __( 'How to get %s keys?', 'logy' ), $provider ),
            'msg'       => implode( '<br>', $steps )
        )
    );
}

/**
 * Get Provide instructions
 */
function get_provider_instructions( $provider ) {

    switch ( $provider ) {

        case 'facebook':
                    
            // Init Vars.
            $auth_url = home_url( '/?hauth_done=Facebook' ); 
            $apps_url = 'https://developers.facebook.com/apps';

            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'logy' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Create New App".', 'logy' );
            $steps[] = __( '3. Fill out any required fields such as the application name and description.', 'logy' );
            $steps[] = __( '4. Put your website domain in the Site Url field.', 'logy' );
            $steps[] = __( '5. Go to the Status & Review page.', 'logy' );
            $steps[] = __( '6. Enable <strong>"Do you want to make this app and all its live features available to the general public?"</strong>.', 'logy' );
            $steps[] = __( '7. Facebook Login > Settings > Valid OAuth redirect URIs:', 'logy' );
            $steps[] = sprintf( __( '8. OAuth Url : <strong><a>%s</a></strong>', 'logy' ), $auth_url );
            $steps[] = __( '9. Go to dashboard and get your <strong>App ID</strong> and <strong>App Secret</strong>', 'logy' );

            return $steps;

        case 'twitter':
                
            // Init Vars.
            $apps_url = 'https://dev.twitter.com/apps';
            $auth_url = home_url( '/?hauth.done=Twitter' );

            // Get Note
            $steps[] = __( '<strong><a>Note:</a> Twitter do not provide their users email address, to make that happen you have to submit your application for review untill that time we will request the email from users while registration.</strong>', 'logy' ) . '<br>'; 

            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'logy' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Create New App".', 'logy' );
            $steps[] = __( '3. Fill out any required fields such as the application name and description.', 'logy' );
            $steps[] = __( '4. Put your website domain in the Site Url field.', 'logy' );
            $steps[] = __( '5. Provide URL below as the Callback URL for your application.', 'logy' );
            $steps[] = sprintf( __( '6. Callback Url: <strong><a>%s</a></strong>', 'logy' ), $auth_url );
            $steps[] = __( '7. Register Settings and get Consumer Key and Secret.', 'logy' );

            return $steps;

        case 'google':

            // Init Vars.
            $apps_url = 'https://code.google.com/apis/console/';
            $auth_url = home_url( '/?hauth.done=Google' ); 
            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'logy' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Create a new project".', 'logy' );
            $steps[] = __( '3. Go to API Access under API Project.', 'logy' );
            $steps[] = __( '4. After that click on Create an OAuth 2.0 client ID to create a new application.', 'logy' );
            $steps[] = __( '5. A pop-up named "Create Client ID" will appear, fill out any required fields such as the application name and description and Click on Next.', 'logy' );
            $steps[] = __( '6. On the popup set Application type to Web application and switch to advanced settings by clicking on ( more options ) .', 'logy' );
            $steps[] = __( '7. Provide URL below as the Callback URL for your application.', 'logy' );
            $steps[] = sprintf( __( '8. Callback Url: <strong><a>%s</a></strong>', 'logy' ), $auth_url );
            $steps[] = __( '9. Once you have registered, copy the created application credentials (Client ID and Secret ) .', 'logy' );

            return $steps;

        case 'linkedin':
                
            // Init Vars.
            $apps_url = 'https://www.linkedin.com/developer/apps';
            $auth_url = home_url( '/?hauth.done=LinkedIn' ); 
            
            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'logy' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Create Application".', 'logy' );
            $steps[] = __( '3. Fill out any required fields such as the application name and description.', 'logy' );
            $steps[] = __( '4. Put the below url in the OAuth 2.0 Authorized Redirect URLs:', 'logy' );
            $steps[] = sprintf( __( '5. Redirect Url: <strong><a>%s</a></strong>', 'logy' ), $auth_url );
            $steps[] = __( '6. Once you have registered, copy the created application credentials ( Client ID and Secret ) .', 'logy' );
            return $steps;

        case 'instagram':

            // Init Vars.
            $apps_url = 'instagram.com/developer/clients/manage/';
            $auth_url = home_url( '/?hauth.done=Instagram' ); 
            
            // Get Note
            $steps[] = __( '<strong><a>Note:</a> Instagram do not provide their users email address, to make that happen you have to submit your application for review untill that time we will request the email from users while registration.</strong>', 'logy' ) . '<br>'; 
            
            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'logy' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Register new Client".', 'logy' );
            $steps[] = __( '3. Fill out any required fields such as the application name and description.', 'logy' );
            $steps[] = __( '4. Put the below url as OAuth redirect_uri  Authorized Redirect URLs:', 'logy' );
            $steps[] = sprintf( __( '5. Redirect Url: <strong><a>%s</a></strong>', 'logy' ), $auth_url );
            $steps[] = __( '6. Once you have registered, copy the created application credentials ( Client ID and Secret ) .', 'logy' );

            return $steps;
        
        default:
            return false;
    }
}

?>