<?php
/*
 * Plugin Name: Logy汉化版
 * Plugin URI:  http://www.kainelabs.com/logy
 * Description: Logy是一个安全的高级登录/注册/重置密码系统，具有优雅的响应式设计和许多强大的功能，如社交登录，限制登录尝试，Captcha等...极其可定制性和一系列其他功能。由KaineLabs提供。
 * Version:     1.0.0
 * Author:     翎风
 * Author URI:  https://dev.leyoblog.top/
 * Text Domain: logy
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

// Main Path.
define( 'LOGY_PATH', plugin_dir_path( __FILE__ ) );
define( 'LOGY_URL', plugin_dir_url( __FILE__ ) );

// Public & Admin Core Path's
define( 'LOGY_CORE', LOGY_PATH. 'includes/public/core/' );
define( 'LOGY_ADMIN', LOGY_PATH . 'includes/admin/' );

// Assets ( PA = Public Assets & AA = Admin Assets ).
define( 'LOGY_PA', plugin_dir_url( __FILE__ ) . 'includes/public/assets/' );
define( 'LOGY_AA', plugin_dir_url( __FILE__ ) . 'includes/admin/assets/' );

// Activation Hook.
register_activation_hook( __FILE__, array( 'Logy', 'plugin_activation' ) );

// Init.
require_once LOGY_PATH . 'class.logy.php';

// Init Class
$Logy = new Logy();

// Init Admin
if ( is_admin() ) {
    require_once LOGY_PATH . 'includes/admin/class-logy-admin.php';
    $Logy_Admin = new Logy_Admin();
}