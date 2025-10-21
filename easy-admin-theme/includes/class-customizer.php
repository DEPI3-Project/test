<?php
/**
 * Customizer Class for Easy Admin Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class EAT_Customizer {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('customize_register', array($this, 'register_customizer_settings'));
        add_action('wp_head', array($this, 'output_customizer_css'));
    }
    
    /**
     * Register customizer settings
     */
    public function register_customizer_settings($wp_customize) {
        // Add Easy Admin Theme section
        $wp_customize->add_section('easy_admin_theme_section', array(
            'title' => __('Easy Admin Theme', 'easy-admin-theme'),
            'priority' => 30,
            'description' => __('Customize your WordPress admin experience', 'easy-admin-theme'),
        ));
        
        // Primary Color Setting
        $wp_customize->add_setting('eat_primary_color', array(
            'default' => '#2271b1',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'eat_primary_color', array(
            'label' => __('Primary Color', 'easy-admin-theme'),
            'section' => 'easy_admin_theme_section',
            'settings' => 'eat_primary_color',
        )));
        
        // Secondary Color Setting
        $wp_customize->add_setting('eat_secondary_color', array(
            'default' => '#1e1e2e',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'eat_secondary_color', array(
            'label' => __('Secondary Color', 'easy-admin-theme'),
            'section' => 'easy_admin_theme_section',
            'settings' => 'eat_secondary_color',
        )));
        
        // Admin Bar Color Setting
        $wp_customize->add_setting('eat_admin_bar_color', array(
            'default' => '#23282d',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'eat_admin_bar_color', array(
            'label' => __('Admin Bar Color', 'easy-admin-theme'),
            'section' => 'easy_admin_theme_section',
            'settings' => 'eat_admin_bar_color',
        )));
        
        // Enable Dark Mode
        $wp_customize->add_setting('eat_dark_mode', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control('eat_dark_mode', array(
            'label' => __('Enable Dark Mode', 'easy-admin-theme'),
            'section' => 'easy_admin_theme_section',
            'type' => 'checkbox',
        ));
        
        // Custom CSS
        $wp_customize->add_setting('eat_custom_css', array(
            'default' => '',
            'sanitize_callback' => 'wp_strip_all_tags',
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control('eat_custom_css', array(
            'label' => __('Custom CSS', 'easy-admin-theme'),
            'section' => 'easy_admin_theme_section',
            'type' => 'textarea',
            'description' => __('Add custom CSS for additional styling', 'easy-admin-theme'),
        ));
    }
    
    /**
     * Output customizer CSS
     */
    public function output_customizer_css() {
        $primary_color = get_theme_mod('eat_primary_color', '#2271b1');
        $secondary_color = get_theme_mod('eat_secondary_color', '#1e1e2e');
        $admin_bar_color = get_theme_mod('eat_admin_bar_color', '#23282d');
        $dark_mode = get_theme_mod('eat_dark_mode', false);
        $custom_css = get_theme_mod('eat_custom_css', '');
        
        if (is_admin()) {
            ?>
            <style type="text/css">
            :root {
                --eat-primary-color: <?php echo esc_attr($primary_color); ?>;
                --eat-secondary-color: <?php echo esc_attr($secondary_color); ?>;
                --eat-admin-bar-color: <?php echo esc_attr($admin_bar_color); ?>;
            }
            
            /* Apply custom colors */
            #wpadminbar {
                background: var(--eat-admin-bar-color) !important;
            }
            
            .wp-core-ui .button-primary {
                background: var(--eat-primary-color) !important;
                border-color: var(--eat-primary-color) !important;
            }
            
            #adminmenu li.current > a.menu-top,
            #adminmenu li.wp-has-current-submenu > a.menu-top {
                background: var(--eat-primary-color) !important;
            }
            
            <?php if ($dark_mode) : ?>
            /* Dark Mode Styles */
            body {
                background: #1a1a1a !important;
                color: #e0e0e0 !important;
            }
            
            .wrap {
                background: #1a1a1a !important;
                color: #e0e0e0 !important;
            }
            
            .postbox,
            .meta-box-sortables .postbox {
                background: #2d2d2d !important;
                border-color: #444 !important;
                color: #e0e0e0 !important;
            }
            
            .postbox .hndle {
                background: #333 !important;
                color: #e0e0e0 !important;
                border-bottom-color: #444 !important;
            }
            
            input[type="text"],
            input[type="email"],
            input[type="url"],
            input[type="password"],
            input[type="search"],
            textarea,
            select {
                background: #2d2d2d !important;
                border-color: #444 !important;
                color: #e0e0e0 !important;
            }
            
            .wp-list-table {
                background: #2d2d2d !important;
                color: #e0e0e0 !important;
            }
            
            .wp-list-table th {
                background: #333 !important;
                border-color: #444 !important;
                color: #e0e0e0 !important;
            }
            
            .wp-list-table td {
                border-color: #444 !important;
            }
            
            .alternate {
                background: #333 !important;
            }
            <?php endif; ?>
            
            <?php if ($custom_css) : ?>
            /* Custom CSS */
            <?php echo wp_strip_all_tags($custom_css); ?>
            <?php endif; ?>
            </style>
            <?php
        }
    }
}

// Initialize customizer
new EAT_Customizer();