<?php

/**
 * # General Settings.
 */

function logy_general_settings() {

    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title' => __( '页面设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '登录页面', 'logy' ),
            'desc'  => __( '选择登录页面', 'logy' ),
            'std'   => logy_page_id( 'login' ),
            'id'    => 'login',
            'opts'  => logy_get_pages(),
            'type'  => 'select'
        ),
        'logy_pages'
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '注册页面', 'logy' ),
            'desc'  => __( '选择注册页面', 'logy' ),
            'std'   => logy_page_id( 'register' ),
            'opts'  => logy_get_pages(),
            'id'    => 'register',
            'type'  => 'select'
        ),
        'logy_pages'
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '找回密码页面', 'logy' ),
            'desc'  => __( '选择找回密码页面', 'logy' ),
            'std'   => logy_page_id( 'lost-password' ),
            'opts'  => logy_get_pages(),
            'id'    => 'lost-password',
            'type'  => 'select'
        ),
        'logy_pages'
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '仪表板与工具栏设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '对订阅者隐藏仪表板与工具栏', 'logy' ),
            'desc'  => __( '仅对订阅者隐藏仪表板与工具栏', 'logy' ),
            'id'    => 'logy_hide_subscribers_dash',
            'type'  => 'checkbox'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '边距设置', 'logy' ),
            'class' => 'klabs-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '顶部边距', 'logy' ),
            'id'    => 'logy_plugin_margin_top',
            'desc'  => __( '自定义logy登录框的顶部边距', 'logy' ),
            'type'  => 'number'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '底部边距', 'logy' ),
            'id'    => 'logy_plugin_margin_bottom',
            'desc'  => __( '自定义logy登录框的底部边距', 'logy' ),
            'type'  => 'number'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '面板设置', 'logy' ),
            'class' => 'klabs-panel-scheme',
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'id'    =>  'logy_panel_scheme',
            'type'  => 'imgSelect',
            'opts'  => array(
                'klabs-orange-scheme', 'klabs-white-scheme', 'klabs-pink-scheme',
                'klabs-red-scheme', 'klabs-darkgray-scheme', 'klabs-yellow-scheme',
                'klabs-blue-scheme', 'klabs-purple-scheme', 'klabs-green-scheme'
            )
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );
    
    $Logy_Settings->get_field(
        array(
            'title' => __( '恢复默认设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'button_title' => __( '重置', 'logy' ),
            'title' => __( '重置所有设置为默认设置', 'logy' ),
            'desc'  => __( '恢复插件的所有设置为默认设置', 'logy' ),
            'id'    => 'klabs-reset-all-settings',
            'type'  => 'button'
        )
    );

    logy_popup_dialog( 'reset_all' );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );
}