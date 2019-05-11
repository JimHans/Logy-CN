<?php

/**
 * # Admin Settings.
 */
function logy_register_settings() {

    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title' => __( '常规设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '启用注册', 'logy' ),
            'desc'  => __( '启用用户注册', 'logy' ),
            'id'    => 'users_can_register',
            'type'  => 'checkbox'
        )
    );

    // Get Site Rules
    global $wp_roles;

    $Logy_Settings->get_field(
        array(
            'title' => __( '新用户默认角色', 'logy' ),
            'desc'  => __( '选择新用户默认角色', 'logy' ),
            'opts'  => $wp_roles->get_names(),
            'id'    => 'default_role',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '注册按钮标题', 'logy' ),
            'desc'  => __( '输入注册按钮标题', 'logy' ),
            'id'    => 'logy_signup_register_btn_title',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '登录按钮标题', 'logy' ),
            'desc'  => __( '输入登录按钮标题', 'logy' ),
            'id'    => 'logy_signup_signin_btn_title',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '条款和隐私政策设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '显示隐私政策', 'logy' ),
            'desc'  => __( '显示条款和隐私政策说明', 'logy' ),
            'id'    => 'logy_show_terms_privacy_note',
            'type'  => 'checkbox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '条款URL', 'logy' ),
            'desc'  => __( '输入条款和条件链接', 'logy' ),
            'id'    => 'logy_terms_url',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '隐私政策URL', 'logy' ),
            'desc'  => __( '输入隐私政策链接', 'logy' ),
            'id'    => 'logy_privacy_url',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    // Get Header Settings
    logy_register_header_settings();
    // Get Fields Settings
    logy_register_fields_settings();
    // Get Buttons Settings
    logy_register_buttons_settings();

    $Logy_Settings->get_field(
        array(
            'title' => __( '注册小部件边距设置', 'logy' ),
            'class' => 'klabs-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '小工具顶部边距', 'logy' ),
            'id'    => 'logy_register_wg_margin_top',
            'desc'  => __( '指定小工具顶部边距', 'logy' ),
            'type'  => 'number'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '小工具底部边距', 'logy' ),
            'id'    => 'logy_register_wg_margin_bottom',
            'desc'  => __( '指定小工具底部边距', 'logy' ),
            'type'  => 'number'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Header Settings
 */
function logy_register_header_settings() {

    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title' => __( '注册表单标题', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '启用表单标题', 'logy' ),
            'desc'  => __( '启用表单标题？', 'logy' ),
            'id'    => 'logy_signup_form_enable_header',
            'type'  => 'checkbox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '表单标题', 'logy' ),
            'desc'  => __( '输入注册表单标题', 'logy' ),
            'id'    => 'logy_signup_form_title',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '表单副标题', 'logy' ),
            'desc'  => __( '输入注册表单副标题', 'logy' ),
            'id'    => 'logy_signup_form_subtitle',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '上传封面', 'logy' ),
            'desc'  => __( '上传注册表单封面', 'logy' ),
            'id'    => 'logy_signup_cover',
            'type'  => 'upload'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );
}

/**
 * Fields Settings
 */
function logy_register_fields_settings() {

    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title' => __( '字段布局', 'logy' ),
            'class' => 'klabs-center-elements',
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'id'    => 'logy_signup_form_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'logy-field-v1', 'logy-field-v2', 'logy-field-v3', 'logy-field-v4', 'logy-field-v5',
                'logy-field-v6', 'logy-field-v7', 'logy-field-v8', 'logy-field-v9', 'logy-field-v10',
                'logy-field-v11', 'logy-field-v12'
            )
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '输入框设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '输入框图标位置', 'logy' ),
            'opts'  => $Logy_Settings->get_field_options( 'form_icons_position' ),
            'desc'  => __( '选择输入框图标位置 <br>( 仅适用于支持图标的输入框样式 )', 'logy' ),
            'id'    => 'logy_signup_icons_position',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '输入框边框样式', 'logy' ),
            'opts'  => $Logy_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( '选择输入框边框样式', 'logy' ),
            'id'    => 'logy_signup_fields_format',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Buttons Settings
 */
function logy_register_buttons_settings() {

    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title' => __( '按钮布局', 'logy' ),
            'class' => 'klabs-center-elements',
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'id'    => 'logy_signup_actions_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'logy-regactions-v1', 'logy-regactions-v2', 'logy-regactions-v3', 'logy-regactions-v4',
                'logy-regactions-v5', 'logy-regactions-v6'
            )
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '按钮设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '按钮图标位置', 'logy' ),
            'opts'  => $Logy_Settings->get_field_options( 'form_icons_position' ),
            'desc'  => __( '选择按钮图标位置 <br>（仅适用于支持显示图标的按钮！）', 'logy' ),
            'id'    => 'logy_signup_btn_icons_position',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '按钮边框样式', 'logy' ),
            'opts'  => $Logy_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( '选择按钮边框样式', 'logy' ),
            'id'    => 'logy_signup_btn_format',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Styling Settings
 */
function logy_register_styling_settings() {

    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title' => __( '标题样式设置', 'logy' ),
            'class' => 'klabs-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '表单标题', 'logy' ),
            'desc'  => __( '表单标题颜色', 'logy' ),
            'id'    => 'logy_signup_title_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '表单副标题', 'logy' ),
            'desc'  => __( '表单副标题颜色', 'logy' ),
            'id'    => 'logy_signup_subtitle_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '封面标题背景', 'logy' ),
            'desc'  => __( '设置封面标题背景颜色', 'logy' ),
            'id'    => 'logy_signup_cover_title_bg_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '字段样式设置', 'logy' ),
            'class' => 'klabs-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '标签', 'logy' ),
            'desc'  => __( '设置标签颜色', 'logy' ),
            'id'    => 'logy_signup_label_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '占位符', 'logy' ),
            'desc'  => __( '设置占位符颜色', 'logy' ),
            'id'    => 'logy_signup_placeholder_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '输入文字', 'logy' ),
            'desc'  => __( '设置输入文字颜色', 'logy' ),
            'id'    => 'logy_signup_inputs_txt_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '输入背景', 'logy' ),
            'desc'  => __( '设置输入背景颜色', 'logy' ),
            'id'    => 'logy_signup_inputs_bg_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '输入边框', 'logy' ),
            'desc'  => __( '设置输入边框颜色', 'logy' ),
            'id'    => 'logy_signup_inputs_border_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '图标', 'logy' ),
            'desc'  => __( '设置字段图标颜色', 'logy' ),
            'id'    => 'logy_signup_fields_icons_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '图标背景', 'logy' ),
            'desc'  => __( '设置字段图标背景颜色', 'logy' ),
            'id'    => 'logy_signup_fields_icons_bg_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '密码备注样式设置', 'logy' ),
            'class' => 'klabs-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '“备注”文字颜色', 'logy' ),
            'desc'  => __( '设置“备注”文字颜色', 'logy' ),
            'id'    => 'logy_signup_pswdnote_word_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '注释描述颜色', 'logy' ),
            'desc'  => __( '设置表单注释描述颜色', 'logy' ),
            'id'    => 'logy_signup_pswdnote_desc_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '按钮样式设置', 'logy' ),
            'class' => 'klabs-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '注册按钮颜色', 'logy' ),
            'desc' => __( '提交按钮背景', 'logy' ),
            'id'    => 'logy_signup_submit_bg_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '注册按钮文字', 'logy' ),
            'desc'  => __( '设置注册按钮文字颜色', 'logy' ),
            'id'    => 'logy_signup_submit_txt_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '登录按钮颜色', 'logy' ),
            'desc'  => __( '登录按钮背景颜色', 'logy' ),
            'id'    => 'logy_signup_loginbutton_bg_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '登录按钮文字', 'logy' ),
            'desc'  => __( '设置登录按钮文字颜色', 'logy' ),
            'id'    => 'logy_signup_loginbutton_txt_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );
}