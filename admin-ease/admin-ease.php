<?php
/**
 * Plugin Name: AdminEase - Friendly WordPress Admin Theme
 * Description: A clean, accessible, and memorable admin experience. Drop-in admin theme plugin for WordPress.
 * Version: 1.0.0
 * Author: Cursor AI
 * Text Domain: admin-ease
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'ADMINEASE_VERSION' ) ) {
    define( 'ADMINEASE_VERSION', '1.0.0' );
}

if ( ! defined( 'ADMINEASE_FILE' ) ) {
    define( 'ADMINEASE_FILE', __FILE__ );
}

if ( ! defined( 'ADMINEASE_DIR' ) ) {
    define( 'ADMINEASE_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ADMINEASE_URL' ) ) {
    define( 'ADMINEASE_URL', plugin_dir_url( __FILE__ ) );
}

require_once ADMINEASE_DIR . 'includes/class-admin-ease.php';

function adminease_bootstrap() {
    \AdminEase\Plugin::instance();
}
add_action( 'plugins_loaded', 'adminease_bootstrap' );

// Set sane defaults on activation
function adminease_activate() {
    if ( ! get_option( 'adminease_options' ) ) {
        add_option( 'adminease_options', [
            'color_scheme'   => 'system',
            'compact'        => false,
            'login_logo_url' => '',
            'login_bg_color' => '',
        ] );
    }
}
register_activation_hook( __FILE__, 'adminease_activate' );

// Clean up user meta on uninstall
if ( function_exists( 'register_uninstall_hook' ) ) {
    register_uninstall_hook( __FILE__, 'adminease_uninstall' );
}
function adminease_uninstall() {
    delete_option( 'adminease_options' );
    // best-effort remove user meta flag for onboarding
    $users = get_users( [ 'fields' => 'ID' ] );
    foreach ( $users as $uid ) {
        delete_user_meta( $uid, '_adminease_dismissed' );
    }
}
