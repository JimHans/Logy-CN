<?php

/**
 * # Admin Settings.
 */
function logy_login_settings() {

    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title' => __( '常规设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '登录按钮标题', 'logy' ),
            'desc'  => __( '输入登录按钮标题', 'logy' ),
            'id'    => 'logy_login_signin_btn_title',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '注册按钮标题', 'logy' ),
            'desc'  => __( '输入注册按钮标题', 'logy' ),
            'id'    => 'logy_login_register_btn_title',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '丢失密码按钮标题', 'logy' ),
            'desc'  => __( '键入丢失密码按钮标题', 'logy' ),
            'id'    => 'logy_login_lostpswd_title',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '重定向设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '登录后将用户重定向到？', 'logy' ),
            'opts'  => $Logy_Settings->get_field_options( 'user_login_redirect_pages' ),
            'desc'  => __( '用户登录后重定向到哪个页面？', 'logy' ),
            'id'    => 'logy_user_after_login_redirect',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '登录后重定向管理员到？', 'logy' ),
            'desc'  => __( '管理员登录后重定向到哪个页面？', 'logy' ),
            'id'    => 'logy_admin_after_login_redirect',
            'opts'  => $Logy_Settings->get_field_options( 'admin_login_redirect_pages' ),
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '注销后用户重定向到？', 'logy' ),
            'desc'  => __( '用户注销后重定向到哪个页面？', 'logy' ),
            'opts'  => $Logy_Settings->get_field_options( 'logout_redirect_pages' ),
            'id'    => 'logy_after_logout_redirect',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    // Get Header Settings
    logy_login_header_settings();

    // Get Fields Settings
    logy_login_fields_settings();

    // Get Buttons Settings
    logy_login_buttons_settings();

    $Logy_Settings->get_field(
        array(
            'title' => __( '登录小工具边距设置', 'logy' ),
            'class' => 'klabs-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '小工具顶部边距', 'logy' ),
            'id'    => 'logy_login_wg_margin_top',
            'desc'  => __( '指定小工具顶部边距', 'logy' ),
            'type'  => 'number'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '小工具底部边距', 'logy' ),
            'id'    => 'logy_login_wg_margin_bottom',
            'desc'  => __( '指定小工具底部边距', 'logy' ),
            'type'  => 'number'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Header Settings
 */
function logy_login_header_settings() {

    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title' => __( '标题设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '启用表单封面', 'logy' ),
            'desc'  => __( '启用表单标题封面？', 'logy' ),
            'id'    => 'logy_login_form_enable_header',
            'type'  => 'checkbox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '表单标题', 'logy' ),
            'desc'  => __( '登录表单标题', 'logy' ),
            'id'    => 'logy_login_form_title',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '表单子标题', 'logy' ),
            'desc'  => __( '登录表单子标题', 'logy' ),
            'id'    => 'logy_login_form_subtitle',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '表单背景', 'logy' ),
            'desc'  => __( '上传登录表单背景', 'logy' ),
            'id'    => 'logy_login_cover',
            'type'  => 'upload'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Fields Settings
 */
function logy_login_fields_settings() {

    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title' => __( '表单输入框布局', 'logy' ),
            'class' => 'klabs-center-elements',
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'id'    => 'logy_login_form_layout',
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
            'desc'  => __( '选择输入框图标位置 <br>（仅适用于支持图标的布局！）', 'logy' ),
            'id'    => 'logy_login_icons_position',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '输入框边框样式', 'logy' ),
            'opts'  => $Logy_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( '选择输入框边框样式', 'logy' ),
            'id'    => 'logy_login_fields_format',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Buttons Settings
 */
function logy_login_buttons_settings() {

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
            'id'    => 'logy_login_actions_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'logy-actions-v1', 'logy-actions-v2', 'logy-actions-v3', 'logy-actions-v4',
                'logy-actions-v5', 'logy-actions-v6', 'logy-actions-v7', 'logy-actions-v8',
                'logy-actions-v9', 'logy-actions-v10'
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
            'id'    => 'logy_login_btn_icons_position',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '按钮边框样式', 'logy' ),
            'opts'  => $Logy_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( '选择按钮边框样式', 'logy' ),
            'id'    => 'logy_login_btn_format',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Styling Settings
 */
function logy_login_styling_settings() {

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
            'desc'  => __( '设置表单标题颜色', 'logy' ),
            'id'    => 'logy_login_title_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '表单副标题', 'logy' ),
            'desc'  => __( '设置表单副标题颜色', 'logy' ),
            'id'    => 'logy_login_subtitle_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '封面标题背景', 'logy' ),
            'desc'  => __( '设置封面标题背景颜色', 'logy' ),
            'id'    => 'logy_login_cover_title_bg_color',
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
            'desc'  => __( '设置表单标签颜色', 'logy' ),
            'id'    => 'logy_login_label_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '占位符', 'logy' ),
            'desc'  => __( '设置占位符颜色', 'logy' ),
            'id'    => 'logy_login_placeholder_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '输入文字', 'logy' ),
            'desc'  => __( '设置输入文字颜色', 'logy' ),
            'id'    => 'logy_login_inputs_txt_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '输入背景', 'logy' ),
            'desc'  => __( '设置输入背景颜色', 'logy' ),
            'id'    => 'logy_login_inputs_bg_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '输入边框', 'logy' ),
            'desc'  => __( '设置输入边框颜色', 'logy' ),
            'id'    => 'logy_login_inputs_border_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '图标', 'logy' ),
            'desc'  => __( '设置字段图标颜色', 'logy' ),
            'id'    => 'logy_login_fields_icons_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '图标背景', 'logy' ),
            'desc'  => __( '设置字段图标背景颜色', 'logy' ),
            'id'    => 'logy_login_fields_icons_bg_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '”记住我“样式设置', 'logy' ),
            'class' => 'klabs-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '”记住我“的颜色', 'logy' ),
            'desc'  => __( '设置”记住我“选项的颜色', 'logy' ),
            'id'    => 'logy_login_remember_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '复选框边框', 'logy' ),
            'desc'  => __( '设置表单复选框边框颜色', 'logy' ),
            'id'    => 'logy_login_checkbox_border_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '复选框图标', 'logy' ),
            'desc'  => __( '设置表单复选框图标颜色', 'logy' ),
            'id'    => 'logy_login_checkbox_icon_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '按钮样式设置', 'logy' ),
            'class' => 'klabs-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '”忘记密码“颜色', 'logy' ),
            'desc'  => __( '设置”忘记密码“选项的颜色', 'logy' ),
            'id'    => 'logy_login_lostpswd_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '登录按钮颜色', 'logy' ),
            'desc'  => __( '设置登录按钮背景颜色', 'logy' ),
            'id'    => 'logy_login_submit_bg_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '登录按钮文字', 'logy' ),
            'desc'  => __( '设置登录按钮文字颜色', 'logy' ),
            'id'    => 'logy_login_submit_txt_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '注册按钮颜色', 'logy' ),
            'desc'  => __( '设置注册按钮背景颜色', 'logy' ),
            'id'    => 'logy_login_regbutton_bg_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '注册按钮文字', 'logy' ),
            'desc'  => __( '设置注册按钮文字颜色', 'logy' ),
            'id'    => 'logy_login_regbutton_txt_color',
            'type'  => 'color'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );
}
