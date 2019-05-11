<?php
/**
 * Captcha Settings
 */
function logy_captcha_settings() {

    global $Logy_Settings;

    // Get Captcha Url
    $captcha_url = 'https://www.google.com/recaptcha/intro/index.html';

    $Logy_Settings->get_field(
        array(
            'title'     => __( '如何获取验证码？', 'logy' ),
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'logy_msgbox_captcha',
            'msg'       => sprintf( __( '获取密钥请访问<strong><a href="%s"> reCAPTCHA网站 </a></strong>或检查有关文档。', 'logy' ), $captcha_url )
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '常规设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '启用验证码', 'logy' ),
            'desc'  => __( '启用使用recaptcha验证码', 'logy' ),
            'id'    => 'logy_enable_recaptcha',
            'type'  => 'checkbox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '验证码公钥', 'logy' ),
            'desc'  => __( 'reCaptcha公钥', 'logy' ),
            'id'    => 'logy_recaptcha_site_key',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '验证码私钥', 'logy' ),
            'desc'  => __( 'reCaptcha私钥', 'logy' ),
            'id'    => 'logy_recaptcha_secret_key',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );
}