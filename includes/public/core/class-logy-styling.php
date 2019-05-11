<?php

class Logy_Styling {

    function __construct() {

        add_action( 'wp_enqueue_scripts', array( &$this, 'custom_styling' ) );

    }

    /**
     * Styling Data.
     */
    function styles_data() {

        // Spacing Styles

        $this->styles_data[] = array(
            'id'        =>  'logy_plugin_margin_top',
            'selector'  =>  '.logy-page-box',
            'property'  =>  'margin-top',
            'unit'      =>  'px'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_plugin_margin_bottom',
            'selector'  =>  '.logy-page-box',
            'property'  =>  'margin-bottom',
            'unit'      =>  'px'
        );

        // Registration Page .

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_title_color',
            'selector'  =>  '.logy-signup-page .form-cover-title,.logy-signup-page .form-title h2',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_subtitle_color',
            'selector'  =>  '.logy-signup-page .form-title span',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_cover_title_bg_color',
            'selector'  =>  '.logy-signup-page .form-cover-title',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_label_color',
            'selector'  =>  '.logy-signup-page .logy-form-item label',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_inputs_txt_color',
            'selector'  =>  ".logy-signup-page .logy-form-item input:not([type='checkbox'])",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_inputs_bg_color',
            'selector'  =>  ".logy-signup-page .logy-form-item .logy-field-content",
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_inputs_border_color',
            'selector'  =>  ".logy-signup-page .logy-form-item .logy-field-content",
            'property'  =>  'border-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_fields_icons_color',
            'selector'  =>  ".logy-signup-page .logy-form-item .logy-field-icon",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_fields_icons_bg_color',
            'selector'  =>  ".logy-signup-page .logy-form-item .logy-field-icon",
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_placeholder_color',
            'selector'  =>  ".logy-signup-page input::-webkit-input-placeholder",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_placeholder_color',
            'selector'  =>  ".logy-signup-page input::-moz-placeholder",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_placeholder_color',
            'selector'  =>  ".logy-signup-page input::-ms-input-placeholder",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_pswdnote_desc_color',
            'selector'  =>  ".logy-signup-page .logy-form-note",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_pswdnote_word_color',
            'selector'  =>  ".logy-signup-page .logy-form-note strong",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_submit_bg_color',
            'selector'  =>  '.logy-signup-page .logy-action-item button',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_submit_txt_color',
            'selector'  =>  '.logy-signup-page .logy-action-item button',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_loginbutton_bg_color',
            'selector'  =>  '.logy-signup-page .logy-link-button',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_signup_loginbutton_txt_color',
            'selector'  =>  '.logy-signup-page .logy-link-button',
            'property'  =>  'color'
        );

        // Login Page .

        $this->styles_data[] = array(
            'id'        =>  'logy_login_title_color',
            'selector'  =>  '.logy-login-page .form-cover-title,.logy-login-page .form-title h2',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_subtitle_color',
            'selector'  =>  '.logy-login-page .form-title span',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_cover_title_bg_color',
            'selector'  =>  '.logy-login-page .form-cover-title',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_label_color',
            'selector'  =>  '.logy-login-page .logy-form-item label',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_inputs_txt_color',
            'selector'  =>  ".logy-login-page .logy-form-item input:not([type='checkbox'])",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_inputs_bg_color',
            'selector'  =>  ".logy-login-page .logy-form-item .logy-field-content",
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_inputs_border_color',
            'selector'  =>  ".logy-login-page .logy-form-item .logy-field-content",
            'property'  =>  'border-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_fields_icons_color',
            'selector'  =>  ".logy-login-page .logy-form-item .logy-field-icon",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_fields_icons_bg_color',
            'selector'  =>  ".logy-login-page .logy-form-item .logy-field-icon",
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_placeholder_color',
            'selector'  =>  ".logy-login-page input::-webkit-input-placeholder",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_placeholder_color',
            'selector'  =>  ".logy-login-page input::-moz-placeholder",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_placeholder_color',
            'selector'  =>  ".logy-login-page input::-ms-input-placeholder",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_lostpswd_color',
            'selector'  =>  ".logy-login-page .logy-forgot-password",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_remember_color',
            'selector'  =>  ".logy-login-page .logy-remember-me label",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_checkbox_border_color',
            'selector'  =>  ".logy-login-page .logy-remember-me .logy_field_indication",
            'property'  =>  'border-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_checkbox_icon_color',
            'selector'  =>  ".logy-login-page .logy-remember-me .logy_checkbox_field .logy_field_indication:after",
            'property'  =>  'border-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_submit_bg_color',
            'selector'  =>  '.logy-login-page .logy-action-item button',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_submit_txt_color',
            'selector'  =>  '.logy-login-page .logy-action-item button',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_regbutton_bg_color',
            'selector'  =>  '.logy-login-page .logy-link-button',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_regbutton_txt_color',
            'selector'  =>  '.logy-login-page .logy-link-button',
            'property'  =>  'color'
        );

        // Widgets Spaces

        $this->styles_data[] = array(
            'id'        =>  'logy_login_wg_margin_top',
            'selector'  =>  '.logy-login-widget',
            'property'  =>  'margin-top',
            'unit'      =>  'px'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_login_wg_margin_bottom',
            'selector'  =>  '.logy-login-widget',
            'property'  =>  'margin-bottom',
            'unit'      =>  'px'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_register_wg_margin_top',
            'selector'  =>  '.logy-register-widget',
            'property'  =>  'margin-top',
            'unit'      =>  'px'
        );

        $this->styles_data[] = array(
            'id'        =>  'logy_register_wg_margin_bottom',
            'selector'  =>  '.logy-register-widget',
            'property'  =>  'margin-bottom',
            'unit'      =>  'px'
        );
        
        return $this->styles_data;
    }

    /**
     * Custom Styling.
     */
    function custom_styling() {

        // Custom Styling File.
        wp_enqueue_style( 'logy-customStyle', LOGY_AA . 'css/custom-script.css' );

        // Print Styles
        foreach ( $this->styles_data() as $key ) {

            // Get Data.
            $selector = $key['selector'];
            $property = $key['property'];

            $option = logy_options( $key['id'] );
            $option = isset( $option['color'] ) ? $option['color'] : $option;
            if ( empty( $key['type'] ) && ! empty( $option ) ) {
                $unit = isset( $key['unit'] ) ? $key['unit'] : null;
                $custom_css = "
                    $selector {
                	$property: $option$unit !important;
                    }";
                wp_add_inline_style( 'logy-customStyle', $custom_css );
            }
        }
    }

}