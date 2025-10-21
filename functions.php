<?php
/**
 * Modern Admin Pro - WordPress Admin Theme
 * 
 * @package ModernAdminPro
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue admin styles and scripts
 */
function modern_admin_pro_enqueue_assets() {
    // Only load on admin pages
    if (!is_admin()) {
        return;
    }
    
    // Enqueue custom admin CSS
    wp_enqueue_style(
        'modern-admin-pro-style',
        get_template_directory_uri() . '/style.css',
        array(),
        '1.0.0'
    );
    
    // Enqueue custom admin JavaScript
    wp_enqueue_script(
        'modern-admin-pro-script',
        get_template_directory_uri() . '/js/admin.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    // Localize script for AJAX
    wp_localize_script('modern-admin-pro-script', 'modernAdmin', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('modern_admin_nonce'),
        'strings' => array(
            'loading' => __('Loading...', 'modern-admin-pro'),
            'error' => __('An error occurred. Please try again.', 'modern-admin-pro'),
            'success' => __('Success!', 'modern-admin-pro')
        )
    ));
}
add_action('admin_enqueue_scripts', 'modern_admin_pro_enqueue_assets');

/**
 * Customize admin bar
 */
function modern_admin_pro_admin_bar_customize() {
    global $wp_admin_bar;
    
    // Remove unnecessary items
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('about');
    $wp_admin_bar->remove_menu('wporg');
    $wp_admin_bar->remove_menu('documentation');
    $wp_admin_bar->remove_menu('support-forums');
    $wp_admin_bar->remove_menu('feedback');
    
    // Add custom logo
    $wp_admin_bar->add_menu(array(
        'id' => 'modern-admin-logo',
        'title' => '<span class="ab-icon dashicons-admin-site"></span>',
        'href' => admin_url(),
        'meta' => array(
            'title' => __('Dashboard', 'modern-admin-pro')
        )
    ));
    
    // Add quick access menu
    $wp_admin_bar->add_menu(array(
        'id' => 'modern-quick-access',
        'title' => __('Quick Access', 'modern-admin-pro'),
        'href' => '#'
    ));
    
    $wp_admin_bar->add_menu(array(
        'id' => 'quick-new-post',
        'parent' => 'modern-quick-access',
        'title' => __('New Post', 'modern-admin-pro'),
        'href' => admin_url('post-new.php')
    ));
    
    $wp_admin_bar->add_menu(array(
        'id' => 'quick-new-page',
        'parent' => 'modern-quick-access',
        'title' => __('New Page', 'modern-admin-pro'),
        'href' => admin_url('post-new.php?post_type=page')
    ));
    
    $wp_admin_bar->add_menu(array(
        'id' => 'quick-media',
        'parent' => 'modern-quick-access',
        'title' => __('Media Library', 'modern-admin-pro'),
        'href' => admin_url('upload.php')
    ));
}
add_action('wp_before_admin_bar_render', 'modern_admin_pro_admin_bar_customize');

/**
 * Customize admin menu
 */
function modern_admin_pro_admin_menu() {
    // Add custom admin menu item
    add_menu_page(
        __('Modern Admin', 'modern-admin-pro'),
        __('Modern Admin', 'modern-admin-pro'),
        'manage_options',
        'modern-admin-settings',
        'modern_admin_pro_settings_page',
        'dashicons-admin-customizer',
        30
    );
    
    // Add submenu items
    add_submenu_page(
        'modern-admin-settings',
        __('Dashboard Settings', 'modern-admin-pro'),
        __('Dashboard', 'modern-admin-pro'),
        'manage_options',
        'modern-admin-settings',
        'modern_admin_pro_settings_page'
    );
    
    add_submenu_page(
        'modern-admin-settings',
        __('Theme Options', 'modern-admin-pro'),
        __('Theme Options', 'modern-admin-pro'),
        'manage_options',
        'modern-admin-theme-options',
        'modern_admin_pro_theme_options_page'
    );
    
    add_submenu_page(
        'modern-admin-settings',
        __('Customize', 'modern-admin-pro'),
        __('Customize', 'modern-admin-pro'),
        'manage_options',
        'modern-admin-customize',
        'modern_admin_pro_customize_page'
    );
}
add_action('admin_menu', 'modern_admin_pro_admin_menu');

/**
 * Settings page callback
 */
function modern_admin_pro_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Modern Admin Settings', 'modern-admin-pro'); ?></h1>
        
        <div class="postbox">
            <div class="hndle">
                <h2><?php _e('Welcome to Modern Admin Pro', 'modern-admin-pro'); ?></h2>
            </div>
            <div class="inside">
                <p><?php _e('Thank you for using Modern Admin Pro! This theme provides a clean, modern interface for your WordPress admin dashboard.', 'modern-admin-pro'); ?></p>
                
                <h3><?php _e('Features', 'modern-admin-pro'); ?></h3>
                <ul>
                    <li><?php _e('Clean, modern design', 'modern-admin-pro'); ?></li>
                    <li><?php _e('Responsive layout for all devices', 'modern-admin-pro'); ?></li>
                    <li><?php _e('Dark mode support', 'modern-admin-pro'); ?></li>
                    <li><?php _e('Improved accessibility', 'modern-admin-pro'); ?></li>
                    <li><?php _e('Custom color scheme', 'modern-admin-pro'); ?></li>
                    <li><?php _e('Enhanced user experience', 'modern-admin-pro'); ?></li>
                </ul>
                
                <h3><?php _e('Quick Start', 'modern-admin-pro'); ?></h3>
                <p><?php _e('The theme is automatically active and ready to use. You can customize various aspects of the admin interface using the options below.', 'modern-admin-pro'); ?></p>
            </div>
        </div>
        
        <div class="postbox">
            <div class="hndle">
                <h2><?php _e('Dashboard Customization', 'modern-admin-pro'); ?></h2>
            </div>
            <div class="inside">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('modern_admin_settings');
                    do_settings_sections('modern_admin_settings');
                    ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Color Scheme', 'modern-admin-pro'); ?></th>
                            <td>
                                <select name="modern_admin_color_scheme">
                                    <option value="default" <?php selected(get_option('modern_admin_color_scheme', 'default'), 'default'); ?>><?php _e('Default', 'modern-admin-pro'); ?></option>
                                    <option value="blue" <?php selected(get_option('modern_admin_color_scheme'), 'blue'); ?>><?php _e('Blue', 'modern-admin-pro'); ?></option>
                                    <option value="green" <?php selected(get_option('modern_admin_color_scheme'), 'green'); ?>><?php _e('Green', 'modern-admin-pro'); ?></option>
                                    <option value="purple" <?php selected(get_option('modern_admin_color_scheme'), 'purple'); ?>><?php _e('Purple', 'modern-admin-pro'); ?></option>
                                </select>
                                <p class="description"><?php _e('Choose your preferred color scheme for the admin interface.', 'modern-admin-pro'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Show Welcome Message', 'modern-admin-pro'); ?></th>
                            <td>
                                <input type="checkbox" name="modern_admin_show_welcome" value="1" <?php checked(get_option('modern_admin_show_welcome', 1), 1); ?> />
                                <p class="description"><?php _e('Display a welcome message on the dashboard.', 'modern-admin-pro'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Compact Mode', 'modern-admin-pro'); ?></th>
                            <td>
                                <input type="checkbox" name="modern_admin_compact_mode" value="1" <?php checked(get_option('modern_admin_compact_mode'), 1); ?> />
                                <p class="description"><?php _e('Enable compact mode for a more condensed interface.', 'modern-admin-pro'); ?></p>
                            </td>
                        </tr>
                    </table>
                    
                    <?php submit_button(); ?>
                </form>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Theme options page callback
 */
function modern_admin_pro_theme_options_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Theme Options', 'modern-admin-pro'); ?></h1>
        
        <div class="postbox">
            <div class="hndle">
                <h2><?php _e('Appearance Settings', 'modern-admin-pro'); ?></h2>
            </div>
            <div class="inside">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('modern_admin_theme_options');
                    do_settings_sections('modern_admin_theme_options');
                    ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Admin Logo', 'modern-admin-pro'); ?></th>
                            <td>
                                <input type="url" name="modern_admin_logo" value="<?php echo esc_attr(get_option('modern_admin_logo')); ?>" class="regular-text" />
                                <button type="button" class="button" id="upload-logo"><?php _e('Upload Logo', 'modern-admin-pro'); ?></button>
                                <p class="description"><?php _e('Upload a custom logo for the admin area.', 'modern-admin-pro'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Custom CSS', 'modern-admin-pro'); ?></th>
                            <td>
                                <textarea name="modern_admin_custom_css" rows="10" cols="50" class="large-text"><?php echo esc_textarea(get_option('modern_admin_custom_css')); ?></textarea>
                                <p class="description"><?php _e('Add custom CSS to further customize the admin interface.', 'modern-admin-pro'); ?></p>
                            </td>
                        </tr>
                    </table>
                    
                    <?php submit_button(); ?>
                </form>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Customize page callback
 */
function modern_admin_pro_customize_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Customize Admin Interface', 'modern-admin-pro'); ?></h1>
        
        <div class="postbox">
            <div class="hndle">
                <h2><?php _e('Live Preview', 'modern-admin-pro'); ?></h2>
            </div>
            <div class="inside">
                <p><?php _e('Use the options below to customize your admin interface in real-time.', 'modern-admin-pro'); ?></p>
                
                <div id="customize-preview">
                    <h3><?php _e('Preview', 'modern-admin-pro'); ?></h3>
                    <div class="preview-box">
                        <p><?php _e('Your changes will appear here...', 'modern-admin-pro'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Register settings
 */
function modern_admin_pro_register_settings() {
    // Register settings
    register_setting('modern_admin_settings', 'modern_admin_color_scheme');
    register_setting('modern_admin_settings', 'modern_admin_show_welcome');
    register_setting('modern_admin_settings', 'modern_admin_compact_mode');
    
    register_setting('modern_admin_theme_options', 'modern_admin_logo');
    register_setting('modern_admin_theme_options', 'modern_admin_custom_css');
    
    // Add settings sections
    add_settings_section(
        'modern_admin_general',
        __('General Settings', 'modern-admin-pro'),
        'modern_admin_pro_general_section_callback',
        'modern_admin_settings'
    );
    
    add_settings_section(
        'modern_admin_appearance',
        __('Appearance Settings', 'modern-admin-pro'),
        'modern_admin_pro_appearance_section_callback',
        'modern_admin_theme_options'
    );
}
add_action('admin_init', 'modern_admin_pro_register_settings');

/**
 * Settings section callbacks
 */
function modern_admin_pro_general_section_callback() {
    echo '<p>' . __('Configure general settings for the Modern Admin theme.', 'modern-admin-pro') . '</p>';
}

function modern_admin_pro_appearance_section_callback() {
    echo '<p>' . __('Customize the appearance of your admin interface.', 'modern-admin-pro') . '</p>';
}

/**
 * Add custom admin footer text
 */
function modern_admin_pro_footer_text() {
    return sprintf(
        __('Thank you for using %s', 'modern-admin-pro'),
        '<a href="' . admin_url('admin.php?page=modern-admin-settings') . '">Modern Admin Pro</a>'
    );
}
add_filter('admin_footer_text', 'modern_admin_pro_footer_text');

/**
 * Customize login page
 */
function modern_admin_pro_login_styles() {
    ?>
    <style type="text/css">
        body.login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .login h1 a {
            background-image: none;
            text-indent: 0;
            width: auto;
            height: auto;
            font-size: 32px;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
        }
        
        .login form {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: none;
        }
        
        .login form .input {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 16px;
        }
        
        .login form .input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .login .button-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            text-shadow: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .login .button-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
    <?php
}
add_action('login_head', 'modern_admin_pro_login_styles');

/**
 * Add custom dashboard widgets
 */
function modern_admin_pro_dashboard_widgets() {
    wp_add_dashboard_widget(
        'modern_admin_welcome',
        __('Welcome to Modern Admin Pro', 'modern-admin-pro'),
        'modern_admin_pro_welcome_widget'
    );
    
    wp_add_dashboard_widget(
        'modern_admin_quick_stats',
        __('Quick Stats', 'modern-admin-pro'),
        'modern_admin_pro_quick_stats_widget'
    );
}
add_action('wp_dashboard_setup', 'modern_admin_pro_dashboard_widgets');

/**
 * Welcome widget callback
 */
function modern_admin_pro_welcome_widget() {
    $user = wp_get_current_user();
    ?>
    <div style="text-align: center; padding: 20px;">
        <h3><?php printf(__('Hello, %s!', 'modern-admin-pro'), $user->display_name); ?></h3>
        <p><?php _e('Welcome to your modernized WordPress admin dashboard. Everything is designed to be clean, intuitive, and efficient.', 'modern-admin-pro'); ?></p>
        
        <div style="margin-top: 20px;">
            <a href="<?php echo admin_url('post-new.php'); ?>" class="button button-primary">
                <?php _e('Create New Post', 'modern-admin-pro'); ?>
            </a>
            <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="button button-secondary">
                <?php _e('Create New Page', 'modern-admin-pro'); ?>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Quick stats widget callback
 */
function modern_admin_pro_quick_stats_widget() {
    $posts_count = wp_count_posts('post');
    $pages_count = wp_count_posts('page');
    $comments_count = wp_count_comments();
    $users_count = count_users();
    
    ?>
    <div class="quick-stats">
        <div class="stat-item">
            <span class="stat-number"><?php echo $posts_count->publish; ?></span>
            <span class="stat-label"><?php _e('Posts', 'modern-admin-pro'); ?></span>
        </div>
        <div class="stat-item">
            <span class="stat-number"><?php echo $pages_count->publish; ?></span>
            <span class="stat-label"><?php _e('Pages', 'modern-admin-pro'); ?></span>
        </div>
        <div class="stat-item">
            <span class="stat-number"><?php echo $comments_count->approved; ?></span>
            <span class="stat-label"><?php _e('Comments', 'modern-admin-pro'); ?></span>
        </div>
        <div class="stat-item">
            <span class="stat-number"><?php echo $users_count['total_users']; ?></span>
            <span class="stat-label"><?php _e('Users', 'modern-admin-pro'); ?></span>
        </div>
    </div>
    
    <style>
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 15px;
    }
    
    .stat-item {
        text-align: center;
        padding: 15px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    
    .stat-number {
        display: block;
        font-size: 24px;
        font-weight: 700;
        color: #667eea;
    }
    
    .stat-label {
        display: block;
        font-size: 12px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    </style>
    <?php
}

/**
 * Add custom body classes
 */
function modern_admin_pro_body_classes($classes) {
    if (is_admin()) {
        $classes[] = 'modern-admin-pro';
        
        if (get_option('modern_admin_compact_mode')) {
            $classes[] = 'compact-mode';
        }
        
        $color_scheme = get_option('modern_admin_color_scheme', 'default');
        if ($color_scheme !== 'default') {
            $classes[] = 'color-scheme-' . $color_scheme;
        }
    }
    
    return $classes;
}
add_filter('admin_body_class', 'modern_admin_pro_body_classes');

/**
 * AJAX handler for theme customization
 */
function modern_admin_pro_ajax_handler() {
    check_ajax_referer('modern_admin_nonce', 'nonce');
    
    $action = sanitize_text_field($_POST['action_type']);
    
    switch ($action) {
        case 'save_customization':
            $custom_css = sanitize_textarea_field($_POST['custom_css']);
            update_option('modern_admin_custom_css', $custom_css);
            wp_send_json_success(__('Customization saved successfully!', 'modern-admin-pro'));
            break;
            
        default:
            wp_send_json_error(__('Invalid action.', 'modern-admin-pro'));
    }
}
add_action('wp_ajax_modern_admin_customize', 'modern_admin_pro_ajax_handler');

/**
 * Add custom admin notices
 */
function modern_admin_pro_admin_notices() {
    if (get_option('modern_admin_show_welcome', 1)) {
        ?>
        <div class="notice notice-info is-dismissible">
            <p>
                <strong><?php _e('Welcome to Modern Admin Pro!', 'modern-admin-pro'); ?></strong>
                <?php _e('Your admin interface has been modernized with a clean, user-friendly design.', 'modern-admin-pro'); ?>
                <a href="<?php echo admin_url('admin.php?page=modern-admin-settings'); ?>"><?php _e('Customize your experience', 'modern-admin-pro'); ?></a>
            </p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'modern_admin_pro_admin_notices');

/**
 * Load text domain for translations
 */
function modern_admin_pro_load_textdomain() {
    load_plugin_textdomain('modern-admin-pro', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('init', 'modern_admin_pro_load_textdomain');