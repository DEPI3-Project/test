<?php
/**
 * Plugin Name: Easy Admin Theme
 * Plugin URI: https://github.com/easy-admin-theme/easy-admin-theme
 * Description: A modern, user-friendly WordPress admin dashboard theme that's easy to learn, use, and remember. Features a clean interface, improved navigation, and enhanced user experience.
 * Version: 1.0.0
 * Author: Easy Admin Theme Team
 * Author URI: https://github.com/easy-admin-theme
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: easy-admin-theme
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('EAT_VERSION', '1.0.0');
define('EAT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('EAT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('EAT_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Easy Admin Theme Class
 */
class EasyAdminTheme {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get instance of this class
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        add_action('init', array($this, 'init'));
        add_action('admin_init', array($this, 'admin_init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialize the plugin
     */
    public function init() {
        // Load text domain for translations
        load_plugin_textdomain('easy-admin-theme', false, dirname(EAT_PLUGIN_BASENAME) . '/languages/');
        
        // Include required files
        $this->includes();
        
        // Initialize components
        $this->init_hooks();
    }
    
    /**
     * Admin initialization
     */
    public function admin_init() {
        // Only load admin functionality in admin area
        if (is_admin()) {
            $this->admin_hooks();
        }
    }
    
    /**
     * Include required files
     */
    private function includes() {
        require_once EAT_PLUGIN_PATH . 'includes/class-settings.php';
        require_once EAT_PLUGIN_PATH . 'includes/class-customizer.php';
        require_once EAT_PLUGIN_PATH . 'includes/class-dashboard.php';
        require_once EAT_PLUGIN_PATH . 'includes/class-menu-manager.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Enqueue styles and scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('login_enqueue_scripts', array($this, 'enqueue_login_assets'));
        
        // Add admin body classes
        add_filter('admin_body_class', array($this, 'admin_body_class'));
        
        // Customize admin bar
        add_action('wp_before_admin_bar_render', array($this, 'customize_admin_bar'));
        
        // Add custom admin footer text
        add_filter('admin_footer_text', array($this, 'custom_admin_footer'));
        add_filter('update_footer', array($this, 'custom_update_footer'), 11);
    }
    
    /**
     * Admin-specific hooks
     */
    private function admin_hooks() {
        // Customize admin menu
        add_action('admin_menu', array($this, 'customize_admin_menu'), 999);
        
        // Add settings page
        add_action('admin_menu', array($this, 'add_settings_page'));
        
        // Customize dashboard
        add_action('wp_dashboard_setup', array($this, 'customize_dashboard'));
        
        // Remove unnecessary admin notices for cleaner interface
        add_action('admin_head', array($this, 'hide_admin_notices'));
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Enqueue main admin styles
        wp_enqueue_style(
            'easy-admin-theme-admin',
            EAT_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            EAT_VERSION
        );
        
        // Enqueue admin JavaScript
        wp_enqueue_script(
            'easy-admin-theme-admin',
            EAT_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            EAT_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('easy-admin-theme-admin', 'eatAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('eat_nonce'),
            'strings' => array(
                'saving' => __('Saving...', 'easy-admin-theme'),
                'saved' => __('Saved!', 'easy-admin-theme'),
                'error' => __('Error occurred', 'easy-admin-theme'),
            )
        ));
        
        // Enqueue dashboard-specific styles
        if ($hook === 'index.php') {
            wp_enqueue_style(
                'easy-admin-theme-dashboard',
                EAT_PLUGIN_URL . 'assets/css/dashboard.css',
                array('easy-admin-theme-admin'),
                EAT_VERSION
            );
        }
    }
    
    /**
     * Enqueue login page assets
     */
    public function enqueue_login_assets() {
        wp_enqueue_style(
            'easy-admin-theme-login',
            EAT_PLUGIN_URL . 'assets/css/login.css',
            array(),
            EAT_VERSION
        );
    }
    
    /**
     * Add custom admin body classes
     */
    public function admin_body_class($classes) {
        $classes .= ' easy-admin-theme';
        
        // Add responsive class
        $classes .= ' eat-responsive';
        
        // Add color scheme class
        $color_scheme = get_user_option('admin_color');
        if ($color_scheme) {
            $classes .= ' eat-color-' . $color_scheme;
        }
        
        return $classes;
    }
    
    /**
     * Customize admin bar
     */
    public function customize_admin_bar() {
        global $wp_admin_bar;
        
        // Remove WordPress logo
        $wp_admin_bar->remove_node('wp-logo');
        
        // Add custom branding
        $wp_admin_bar->add_node(array(
            'id' => 'eat-branding',
            'title' => '<span class="eat-logo">📊</span> ' . get_bloginfo('name'),
            'href' => admin_url(),
            'meta' => array(
                'class' => 'eat-admin-bar-brand'
            )
        ));
    }
    
    /**
     * Customize admin menu
     */
    public function customize_admin_menu() {
        // This will be handled by the Menu Manager class
        if (class_exists('EAT_Menu_Manager')) {
            new EAT_Menu_Manager();
        }
    }
    
    /**
     * Add settings page
     */
    public function add_settings_page() {
        add_options_page(
            __('Easy Admin Theme Settings', 'easy-admin-theme'),
            __('Easy Admin Theme', 'easy-admin-theme'),
            'manage_options',
            'easy-admin-theme-settings',
            array($this, 'settings_page_callback')
        );
    }
    
    /**
     * Settings page callback
     */
    public function settings_page_callback() {
        if (class_exists('EAT_Settings')) {
            EAT_Settings::render_settings_page();
        }
    }
    
    /**
     * Customize dashboard
     */
    public function customize_dashboard() {
        if (class_exists('EAT_Dashboard')) {
            new EAT_Dashboard();
        }
    }
    
    /**
     * Hide admin notices for cleaner interface
     */
    public function hide_admin_notices() {
        // Only hide on our settings page and dashboard
        $screen = get_current_screen();
        if (in_array($screen->id, array('dashboard', 'settings_page_easy-admin-theme-settings'))) {
            echo '<style>.notice, .error, .updated { display: none !important; }</style>';
        }
    }
    
    /**
     * Custom admin footer text
     */
    public function custom_admin_footer($text) {
        return sprintf(
            __('Thank you for using %s Easy Admin Theme %s', 'easy-admin-theme'),
            '<strong>',
            '</strong>'
        );
    }
    
    /**
     * Custom update footer
     */
    public function custom_update_footer($text) {
        return __('WordPress & Easy Admin Theme', 'easy-admin-theme');
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Set default options
        $default_options = array(
            'color_scheme' => 'blue',
            'compact_menu' => false,
            'hide_admin_bar' => false,
            'custom_logo' => '',
            'welcome_message' => __('Welcome to your enhanced WordPress dashboard!', 'easy-admin-theme'),
        );
        
        add_option('easy_admin_theme_options', $default_options);
        
        // Set activation flag
        add_option('easy_admin_theme_activated', true);
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Clean up temporary data
        delete_option('easy_admin_theme_activated');
    }
}

// Initialize the plugin
function easy_admin_theme_init() {
    return EasyAdminTheme::get_instance();
}

// Start the plugin
easy_admin_theme_init();