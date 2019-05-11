<?php

/**
 * Notifications Settings
 */

function logy_notifications_settings() {

    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title' => __( '常规设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '注册时通知管理员', 'logy' ),
            'desc'  => __( '在注册新用户时收到通知？', 'logy' ),
            'id'    => 'logy_notify_admin_on_registration',
            'type'  => 'checkbox'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title' => __( '新用户通知设置', 'logy' ),
            'type'  => 'openBox'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '标题', 'logy' ),
            'desc'  => __( '管理员通知标题设置', 'logy' ),
            'id'    => 'logy_newuser_notification_subject',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '消息', 'logy' ),
            'desc'  => __( '管理员通知消息正文设置', 'logy' ),
            'id'    => 'logy_newuser_notification_message',
            'class' => 'logy-fullwidth-item',
            'type'  => 'textarea'
        )
    );

    $Logy_Settings->get_field( array( 'type' => 'closeBox' ) );
}