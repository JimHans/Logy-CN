<?php

/**
 * Lost Password Settings
 */

function logy_lost_password_settings() {

    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title' => __( '常规设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '表单标题', 'logy' ),
            'desc'  => __( '丢失密码表单标题', 'logy' ),
            'id'    => 'logy_lostpswd_form_title',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '表单子标题', 'logy' ),
            'desc'  => __( '丢失密码子标题', 'logy' ),
            'id'    => 'logy_lostpswd_form_subtitle',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '重置按钮标题', 'logy' ),
            'desc'  => __( '重置密码按钮标题', 'logy' ),
            'id'    => 'logy_lostpswd_submit_btn_title',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '封面设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '启用表单封面', 'logy' ),
            'desc'  => __( '启用表单封面图像？', 'logy' ),
            'id'    => 'logy_lostpswd_form_enable_header',
            'type'  => 'checkbox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '上传表单封面', 'logy' ),
            'desc'  => __( '上传你的忘记密码表单封面', 'logy' ),
            'id'    => 'logy_lostpswd_cover',
            'type'  => 'upload'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );
}