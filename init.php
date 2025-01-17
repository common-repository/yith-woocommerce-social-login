<?php
/*
Plugin Name: YITH WooCommerce Social Login
Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-social-login/
Description: <code><strong>YITH WooCommerce Social Login</strong></code> allows your users and customers to register and log into your store using one of their favourite social networks, like Facebook, Google, Twitter etc. Perfect for speeding up the login process on your e-commerce shop. <a href="https://yithemes.com/" target="_blank">Get more plugins for your e-commerce shop on <strong>YITH</strong></a>.
Version: 1.4.9
Author: YITH
Author URI: https://yithemes.com/
Text Domain: yith-woocommerce-social-login
Domain Path: /languages/
WC requires at least: 4.2.0
WC tested up to: 5.0
*/

/*
 * @package YITH WooCommerce Social Login
 * @since   1.0.0
 * @author  YITH
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}


if ( ! defined( 'YITH_YWSL_DIR' ) ) {
    define( 'YITH_YWSL_DIR', plugin_dir_path( __FILE__ ) );
}

/* Plugin Framework Version Check */
if( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_YWSL_DIR . 'plugin-fw/init.php' ) ) {
    require_once( YITH_YWSL_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YITH_YWSL_DIR  );


// This version can't be activate if premium version is active  ________________________________________
if ( defined( 'YITH_YWSL_PREMIUM' ) ) {
    function yith_ywsl_install_free_admin_notice() {
        ?>
        <div class="error">
            <p><?php _e( 'You can\'t activate the free version of YITH Woocommerce Social Login while you are using the premium one.', 'yith-woocommerce-social-login' ); ?></p>
        </div>
    <?php
    }

    add_action( 'admin_notices', 'yith_ywsl_install_free_admin_notice' );

    deactivate_plugins( plugin_basename( __FILE__ ) );
    return;
}

// Registration hook  ________________________________________
if ( !function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

if ( !function_exists( 'yith_ywsl_install_woocommerce_admin_notice' ) ) {
	function yith_ywsl_install_woocommerce_admin_notice() {
		?>
		<div class="error">
			<p><?php _e( 'YITH Woocommerce Social Login is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-social-login' ); ?></p>
		</div>
	<?php
	}
}

// Define constants ________________________________________
if ( defined( 'YITH_YWSL_VERSION' ) ) {
    return;
}else{
    define( 'YITH_YWSL_VERSION', '1.4.9' );
}

if ( ! defined( 'YITH_YWSL_FREE_INIT' ) ) {
    define( 'YITH_YWSL_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_YWSL_INIT' ) ) {
    define( 'YITH_YWSL_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_YWSL_FILE' ) ) {
    define( 'YITH_YWSL_FILE', __FILE__ );
}


if ( ! defined( 'YITH_YWSL_URL' ) ) {
    define( 'YITH_YWSL_URL', plugins_url( '/', __FILE__ ) );
}

if ( ! defined( 'YITH_YWSL_ASSETS_URL' ) ) {
    define( 'YITH_YWSL_ASSETS_URL', YITH_YWSL_URL . 'assets' );
}

if ( ! defined( 'YITH_YWSL_TEMPLATE_PATH' ) ) {
    define( 'YITH_YWSL_TEMPLATE_PATH', YITH_YWSL_DIR . 'templates' );
}

if ( ! defined( 'YITH_YWSL_INC' ) ) {
    define( 'YITH_YWSL_INC', YITH_YWSL_DIR . '/includes/' );
}

if ( !defined( 'YITH_YWSL_SLUG' ) ) {
	define( 'YITH_YWSL_SLUG', 'yith-woocommerce-social-login' );
}

if ( ! function_exists( 'yith_ywsl_install' ) ) {
	function yith_ywsl_install() {

		if ( !function_exists( 'WC' ) ) {
			add_action( 'admin_notices', 'yith_ywsl_install_woocommerce_admin_notice' );
		} else {
			do_action( 'yith_ywsl_init' );
		}
	}

	add_action( 'plugins_loaded', 'yith_ywsl_install', 11 );
}


function yith_ywsl_constructor() {

    // Load YWSL text domain ___________________________________
    load_plugin_textdomain( 'yith-woocommerce-social-login', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	if ( ! is_admin() && session_id() == '' && ! headers_sent() ) {
		session_start();
	}


	require_once( YITH_YWSL_INC . 'functions.yith-social-login.php' );
    require_once( YITH_YWSL_INC . 'class-yith-social-login.php' );
	require_once( YITH_YWSL_INC . 'class-yith-social-login-session.php' );
    if ( is_admin() ) {
        require_once( YITH_YWSL_INC . 'class-yith-social-login-admin.php' );
        YITH_WC_Social_Login_Admin();
    }
    else {
        require_once( YITH_YWSL_INC . 'class-yith-social-login-frontend.php' );
        YITH_WC_Social_Login_Frontend();
    }

    YITH_WC_Social_Login();

}
add_action( 'yith_ywsl_init', 'yith_ywsl_constructor' );