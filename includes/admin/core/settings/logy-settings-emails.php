<?php

/**
* E-mails Settings
*/

function logy_emails_settings() {

    global $Logy_Settings;

    $Logy_Settings->get_field(
        array(
            'title'     => __( '注意', 'logy' ),
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'logy_msgbox_mail_content',
            'msg'       => __( '小心！你选择的 <strong>邮件内容类型</strong> 将适用于所有的电子邮件。', 'logy' )
        )
    );

	$Logy_Settings->get_field(
	    array(
	        'title' => __( '邮件设置', 'logy' ),
	        'type'  => 'openBox'
	    )
	);

    $Logy_Settings->get_field(
        array(
            'title' => __( '邮件内容类型', 'logy' ),
            'desc'  => __( '邮件内容类型', 'logy' ),
            'opts'  => $Logy_Settings->get_field_options( 'mail_content_type' ),
            'id'    => 'logy_mail_content_type',
            'type'  => 'select'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '邮件发件人姓名', 'logy' ),
            'desc'  => __( '邮件从谁发出', 'logy' ),
            'id'    => 'logy_mail_sender_name',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '发件人电子邮件', 'logy' ),
            'desc'  => __( '邮件发送地址', 'logy' ),
            'id'    => 'logy_mail_sender_email',
            'type'  => 'text'
        )
    );

	$Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Logy_Settings->get_field(
        array(
            'title'     => __( '电子邮件标签', 'logy' ),
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'logy_msgbox_mail_tags',
            'msg'       => __( "可用的 <strong电子邮件标签列表</strong> :
                    <ul>
                        <li><strong>{email} :</strong> 用户电子邮件地址。</li>
                        <li><strong>{username} :</strong> 用户用户名。</li>
                        <li><strong>{first_name} :</strong> 用户名字。</li>
                        <li><strong>{last_name} :</strong> 用户姓氏。</li>
                        <li><strong>{display_name} :</strong> 用户昵称。</li>
                        <li><strong>{site_name} :</strong> 网站名称。</li>
                        <li><strong>{admin_email} :</strong> 管理员电子邮件地址。</li>
                        <li><strong>{site_url} :</strong> 网站链接。</li>
                        <li><strong>{login_url} :</strong> 登录页面链接</li>
                        <li><strong>{password_reset_url} :</strong> 密码重置链接。</li>
                    </ul>", 'logy' )
        )
    );

    $Logy_Settings->get_field(
	    array(
	        'title' => __( '注册确认邮件设置', 'logy' ),
	        'type'  => 'openBox'
	    )
	);

    $Logy_Settings->get_field(
        array(
            'title' => __( '标题', 'logy' ),
            'desc'  => __( '邮件标题', 'logy' ),
            'id'    => 'logy_user_confirmation_mail_subject',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '内容', 'logy' ),
            'desc'  => __( '邮件内容主体', 'logy' ),
            'id'    => 'logy_user_confirmation_mail_message',
            'class' => 'logy-fullwidth-item',
            'type'  => 'textarea'
        )
    );

	$Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

	$Logy_Settings->get_field(
	    array(
	        'title' => __( '欢迎邮件设置', 'logy' ),
	        'type'  => 'openBox'
	    )
	);

    $Logy_Settings->get_field(
        array(
            'title' => __( '标题', 'logy' ),
            'desc'  => __( '邮件标题', 'logy' ),
            'id'    => 'logy_user_welcome_mail_subject',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '内容', 'logy' ),
            'desc'  => __( '邮件内容主体', 'logy' ),
            'id'    => 'logy_user_welcome_mail_message',
            'class' => 'logy-fullwidth-item',
            'type'  => 'textarea'
        )
    );

	$Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

	$Logy_Settings->get_field(
	    array(
	        'title' => __( '密码重置邮件设置', 'logy' ),
	        'type'  => 'openBox'
	    )
	);

    $Logy_Settings->get_field(
        array(
            'title' => __( '标题', 'logy' ),
            'desc'  => __( '邮件标题', 'logy' ),
            'id'    => 'logy_user_pswdreset_mail_subject',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '内容', 'logy' ),
            'desc'  => __( '邮件内容主体', 'logy' ),
            'id'    => 'logy_user_pswdreset_mail_message',
            'class' => 'logy-fullwidth-item',
            'type'  => 'textarea'
        )
    );

	$Logy_Settings->get_field( array( 'type' => 'closeBox' ) );

	$Logy_Settings->get_field(
	    array(
	        'title' => __( '密码更改提醒邮件设置', 'logy' ),
	        'type'  => 'openBox'
	    )
	);

    $Logy_Settings->get_field(
        array(
            'title' => __( '标题', 'logy' ),
            'desc'  => __( '邮件标题', 'logy' ),
            'id'    => 'logy_user_pswdchanged_mail_subject',
            'type'  => 'text'
        )
    );

    $Logy_Settings->get_field(
        array(
            'title' => __( '内容', 'logy' ),
            'desc'  => __( '邮件内容主体', 'logy' ),
            'id'    => 'logy_user_pswdchanged_mail_message',
            'class' => 'logy-fullwidth-item',
            'type'  => 'textarea'
        )
    );

	$Logy_Settings->get_field( array( 'type' => 'closeBox' ) );
}