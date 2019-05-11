<?php
/**
 * Captcha Settings
 */
function logy_limit_login_settings() {

    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title' => __( '常规设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '启用限制登录', 'logy' ),
            'desc'  => __( '启用限制登录尝试', 'logy' ),
            'id'    => 'logy_enable_limit_login',
            'type'  => 'checkbox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '允许登录重试', 'logy' ),
            'desc'  => __( '经过设定的尝试次数后锁定', 'logy' ),
            'id'    => 'logy_allowed_retries',
            'type'  => 'number'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '短暂锁定账户', 'logy' ),
            'desc'  => __( '在指定次数登陆失败后暂时锁定账号以保护用户账号安全', 'logy' ),
            'id'    => 'logy_allowed_lockouts',
            'type'  => 'number'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '锁定重置时间', 'logy' ),
            'desc'  => __( '在设定时间后重置锁定', 'logy' ),
            'id'    => 'logy_retries_duration',
            'type'  => 'number'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '短暂锁定持续时间', 'logy' ),
            'desc'  => __( '短暂锁定时长设定', 'logy' ),
            'id'    => 'logy_short_lockout_duration',
            'type'  => 'number'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '账户冻结时间', 'logy' ),
            'desc'  => __( '账户冻结时长设定', 'logy' ),
            'id'    => 'logy_long_lockout_duration',
            'type'  => 'number'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );
}