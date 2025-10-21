<?php
/**
 * Plugin Name: Easy Admin Dashboard
 * Description: A user-friendly, learnable, memorable WordPress admin dashboard theme.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: easy-admin-dashboard
 * Domain Path: /languages
 * License: GPL-2.0-or-later
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define constants
if (!defined('EAD_PLUGIN_FILE')) {
    define('EAD_PLUGIN_FILE', __FILE__);
}
if (!defined('EAD_PLUGIN_DIR')) {
    define('EAD_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
if (!defined('EAD_PLUGIN_URL')) {
    define('EAD_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if (!defined('EAD_VERSION')) {
    define('EAD_VERSION', '1.0.0');
}

// Load textdomain
add_action('init', function () {
    load_plugin_textdomain('easy-admin-dashboard', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

// Includes
require_once EAD_PLUGIN_DIR . 'inc/enqueue.php';
require_once EAD_PLUGIN_DIR . 'inc/settings.php';
require_once EAD_PLUGIN_DIR . 'inc/login.php';
require_once EAD_PLUGIN_DIR . 'inc/admin-bar.php';

// Activation/Deactivation hooks
register_activation_hook(__FILE__, function () {
    // Set default options
    $defaults = [
        'ead_color_scheme' => 'light',
        'ead_accent_color' => '#3a60ff',
        'ead_compact_menu' => false,
        'ead_custom_logo' => '',
    ];
    foreach ($defaults as $key => $value) {
        if (get_option($key) === false) {
            add_option($key, $value);
        }
    }
});

register_uninstall_hook(__FILE__, 'ead_uninstall_cleanup');
function ead_uninstall_cleanup()
{
    $keys = [
        'ead_color_scheme',
        'ead_accent_color',
        'ead_compact_menu',
        'ead_custom_logo',
    ];
    foreach ($keys as $key) {
        delete_option($key);
    }
}
