<?php
/**
 * Modern Admin Dashboard Theme Functions
 * 
 * @package ModernAdmin
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
function modern_admin_enqueue_assets() {
    // Enqueue main admin styles
    wp_enqueue_style(
        'modern-admin-style',
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
        'modern-admin-script',
        'modern-admin-pro-script',
        get_template_directory_uri() . '/js/admin.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    // Enqueue login page styles
    wp_enqueue_style(
        'modern-admin-login',
        get_template_directory_uri() . '/css/login.css',
        array(),
        '1.0.0'
    );
}
add_action('admin_enqueue_scripts', 'modern_admin_enqueue_assets');
add_action('login_enqueue_scripts', 'modern_admin_enqueue_assets');

/**
 * Customize admin color scheme
 */
function modern_admin_color_scheme() {
    wp_admin_css_color(
        'modern-blue',
        __('Modern Blue', 'modern-admin'),
        array(
            '#667eea',
            '#764ba2',
            '#ffffff',
            '#f8f9fa'
        ),
        array(
            'base' => '#667eea',
            'focus' => '#764ba2',
            'current' => '#ffffff'
        )
    );
}
add_action('admin_init', 'modern_admin_color_scheme');

/**
 * Set default admin color scheme for new users
 */
function modern_admin_set_default_color_scheme($user_id) {
    update_user_meta($user_id, 'admin_color', 'modern-blue');
}
add_action('user_register', 'modern_admin_set_default_color_scheme');

/**
 * Customize login page
 */
function modern_admin_login_logo() {
    ?>
    <style type="text/css">
        .login h1 a {
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text y=".9em" font-size="90">🚀</text></svg>');
            background-size: contain;
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
        }
    </style>
    <?php
}
add_action('login_head', 'modern_admin_login_logo');

/**
 * Change login logo URL
 */
function modern_admin_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'modern_admin_login_logo_url');

/**
 * Change login logo title
 */
function modern_admin_login_logo_title() {
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'modern_admin_login_logo_title');

/**
 * Customize admin footer
 */
function modern_admin_footer_text() {
    return 'Thank you for using <strong>Modern Admin Dashboard</strong> - A clean and intuitive WordPress admin experience.';
}
add_filter('admin_footer_text', 'modern_admin_footer_text');

/**
 * Remove WordPress version from admin footer
 */
function modern_admin_remove_version() {
    return '';
}
add_filter('update_footer', 'modern_admin_remove_version', 11);

/**
 * Customize admin menu
 */
function modern_admin_menu_order($menu_order) {
    if (!$menu_order) return true;
    
    return array(
        'index.php', // Dashboard
        'edit.php', // Posts
        'edit.php?post_type=page', // Pages
        'upload.php', // Media
        'edit-comments.php', // Comments
        'themes.php', // Appearance
        'plugins.php', // Plugins
        'users.php', // Users
        'tools.php', // Tools
        'options-general.php', // Settings
    );
}
add_filter('custom_menu_order', '__return_true');
add_filter('menu_order', 'modern_admin_menu_order');
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
function modern_admin_dashboard_widgets() {
    // Remove default widgets
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');
    
    // Add custom widgets
    wp_add_dashboard_widget(
        'modern_admin_welcome',
        __('Welcome to Modern Admin', 'modern-admin'),
        'modern_admin_welcome_widget'
function modern_admin_pro_dashboard_widgets() {
    wp_add_dashboard_widget(
        'modern_admin_welcome',
        __('Welcome to Modern Admin Pro', 'modern-admin-pro'),
        'modern_admin_pro_welcome_widget'
    );
    
    wp_add_dashboard_widget(
        'modern_admin_quick_stats',
        __('Quick Stats', 'modern-admin'),
        'modern_admin_quick_stats_widget'
    );
    
    wp_add_dashboard_widget(
        'modern_admin_recent_activity',
        __('Recent Activity', 'modern-admin'),
        'modern_admin_recent_activity_widget'
    );
}
add_action('wp_dashboard_setup', 'modern_admin_dashboard_widgets');

/**
 * Welcome widget content
 */
function modern_admin_welcome_widget() {
    $user = wp_get_current_user();
    $site_name = get_bloginfo('name');
    ?>
    <div class="modern-welcome-widget">
        <h3>Welcome back, <?php echo esc_html($user->display_name); ?>! 👋</h3>
        <p>You're managing <strong><?php echo esc_html($site_name); ?></strong> with our modern admin interface.</p>
        
        <div class="welcome-actions">
            <a href="<?php echo admin_url('post-new.php'); ?>" class="button button-primary">
                <span class="dashicons dashicons-plus-alt"></span> New Post
            </a>
            <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="button button-secondary">
                <span class="dashicons dashicons-admin-page"></span> New Page
            </a>
            <a href="<?php echo admin_url('upload.php'); ?>" class="button button-secondary">
                <span class="dashicons dashicons-admin-media"></span> Media Library
            </a>
        </div>
        
        <div class="welcome-tips">
            <h4>💡 Quick Tips:</h4>
            <ul>
                <li>Use the search bar in the top menu to quickly find anything</li>
                <li>Customize your dashboard by dragging and dropping widgets</li>
                <li>Check out the appearance menu to customize your site's look</li>
            </ul>
        </div>
    </div>
    
    <style>
    .modern-welcome-widget {
        padding: 20px 0;
    }
    
    .modern-welcome-widget h3 {
        color: #667eea;
        margin-bottom: 10px;
    }
    
    .welcome-actions {
        margin: 20px 0;
    }
    
    .welcome-actions .button {
        margin-right: 10px;
        margin-bottom: 10px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .welcome-tips {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        margin-top: 20px;
    }
    
    .welcome-tips h4 {
        margin: 0 0 10px 0;
        color: #495057;
    }
    
    .welcome-tips ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .welcome-tips li {
        margin-bottom: 5px;
        color: #6c757d;
    }
    </style>
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
 * Quick stats widget content
 */
function modern_admin_quick_stats_widget() {
 * Quick stats widget callback
 */
function modern_admin_pro_quick_stats_widget() {
    $posts_count = wp_count_posts('post');
    $pages_count = wp_count_posts('page');
    $comments_count = wp_count_comments();
    $users_count = count_users();
    
    ?>
    <div class="modern-stats-widget">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number"><?php echo $posts_count->publish; ?></div>
                <div class="stat-label">Published Posts</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $pages_count->publish; ?></div>
                <div class="stat-label">Pages</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $comments_count->approved; ?></div>
                <div class="stat-label">Comments</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $users_count['total_users']; ?></div>
                <div class="stat-label">Users</div>
            </div>
        </div>
        
        <div class="stats-actions">
            <a href="<?php echo admin_url('edit.php'); ?>" class="button button-secondary">
                <span class="dashicons dashicons-admin-post"></span> Manage Posts
            </a>
            <a href="<?php echo admin_url('edit-comments.php'); ?>" class="button button-secondary">
                <span class="dashicons dashicons-admin-comments"></span> Manage Comments
            </a>
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
    .modern-stats-widget {
        padding: 20px 0;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 15px;
    }
    
    .stat-item {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }
    
    .stat-number {
        font-size: 24px;
        font-weight: bold;
        color: #667eea;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stats-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .stats-actions .button {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
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
 * Recent activity widget content
 */
function modern_admin_recent_activity_widget() {
    $recent_posts = get_posts(array(
        'numberposts' => 5,
        'post_status' => 'publish'
    ));
    
    $recent_comments = get_comments(array(
        'number' => 5,
        'status' => 'approve'
    ));
    
    ?>
    <div class="modern-activity-widget">
        <div class="activity-section">
            <h4>📝 Recent Posts</h4>
            <?php if ($recent_posts): ?>
                <ul class="activity-list">
                    <?php foreach ($recent_posts as $post): ?>
                        <li>
                            <a href="<?php echo get_edit_post_link($post->ID); ?>">
                                <?php echo esc_html($post->post_title); ?>
                            </a>
                            <span class="activity-date">
                                <?php echo human_time_diff(strtotime($post->post_date), current_time('timestamp')); ?> ago
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No posts yet. <a href="<?php echo admin_url('post-new.php'); ?>">Create your first post</a>!</p>
            <?php endif; ?>
        </div>
        
        <div class="activity-section">
            <h4>💬 Recent Comments</h4>
            <?php if ($recent_comments): ?>
                <ul class="activity-list">
                    <?php foreach ($recent_comments as $comment): ?>
                        <li>
                            <a href="<?php echo get_edit_comment_link($comment->comment_ID); ?>">
                                <?php echo esc_html($comment->comment_author); ?> on 
                                <?php echo esc_html(get_the_title($comment->comment_post_ID)); ?>
                            </a>
                            <span class="activity-date">
                                <?php echo human_time_diff(strtotime($comment->comment_date), current_time('timestamp')); ?> ago
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No comments yet.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <style>
    .modern-activity-widget {
        padding: 20px 0;
    }
    
    .activity-section {
        margin-bottom: 25px;
    }
    
    .activity-section h4 {
        margin: 0 0 15px 0;
        color: #495057;
        font-size: 14px;
    }
    
    .activity-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .activity-list li {
        padding: 8px 0;
        border-bottom: 1px solid #f1f3f4;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .activity-list li:last-child {
        border-bottom: none;
    }
    
    .activity-list a {
        color: #667eea;
        text-decoration: none;
        font-size: 13px;
    }
    
    .activity-list a:hover {
        text-decoration: underline;
    }
    
    .activity-date {
        font-size: 11px;
        color: #6c757d;
    }
    </style>
    <?php
}

/**
 * Customize admin bar
 */
function modern_admin_admin_bar_menu($wp_admin_bar) {
    // Remove WordPress logo
    $wp_admin_bar->remove_node('wp-logo');
    
    // Add custom site info
    $wp_admin_bar->add_node(array(
        'id' => 'modern-site-info',
        'title' => '<span class="ab-icon dashicons dashicons-admin-site"></span> ' . get_bloginfo('name'),
        'href' => home_url(),
        'meta' => array(
            'title' => 'Visit Site'
        )
    ));
}
add_action('admin_bar_menu', 'modern_admin_admin_bar_menu', 25);

/**
 * Add custom admin notices
 */
function modern_admin_admin_notices() {
    if (current_user_can('manage_options')) {
        $theme_version = '1.0.0';
        $current_version = get_option('modern_admin_version', '0');
        
        if (version_compare($current_version, $theme_version, '<')) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p>
                    <strong>Modern Admin Dashboard</strong> has been updated to version <?php echo $theme_version; ?>! 
                    Enjoy the improved interface and new features.
                </p>
            </div>
            <?php
            update_option('modern_admin_version', $theme_version);
        }
    }
}
add_action('admin_notices', 'modern_admin_admin_notices');

/**
 * Customize admin menu icons
 */
function modern_admin_menu_icons() {
    ?>
    <style>
    #adminmenu .menu-icon-post .wp-menu-image:before {
        content: "\f109";
    }
    #adminmenu .menu-icon-page .wp-menu-image:before {
        content: "\f105";
    }
    #adminmenu .menu-icon-media .wp-menu-image:before {
        content: "\f104";
    }
    #adminmenu .menu-icon-comments .wp-menu-image:before {
        content: "\f101";
    }
    #adminmenu .menu-icon-appearance .wp-menu-image:before {
        content: "\f100";
    }
    #adminmenu .menu-icon-plugins .wp-menu-image:before {
        content: "\f106";
    }
    #adminmenu .menu-icon-users .wp-menu-image:before {
        content: "\f110";
    }
    #adminmenu .menu-icon-tools .wp-menu-image:before {
        content: "\f107";
    }
    #adminmenu .menu-icon-settings .wp-menu-image:before {
        content: "\f108";
    }
    </style>
    <?php
}
add_action('admin_head', 'modern_admin_menu_icons');

/**
 * Add custom CSS for better mobile experience
 */
function modern_admin_mobile_styles() {
    ?>
    <style>
    @media (max-width: 782px) {
        .modern-stats-widget .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .welcome-actions .button {
            width: 100%;
            justify-content: center;
            margin-bottom: 10px;
        }
        
        .stats-actions .button {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 480px) {
        .modern-stats-widget .stats-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
    <?php
}
add_action('admin_head', 'modern_admin_mobile_styles');

/**
 * Add theme support
 */
function modern_admin_theme_support() {
    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 100,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Add support for post thumbnails
    add_theme_support('post-thumbnails');
    
    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'modern_admin_theme_support');

/**
 * Clean up admin dashboard
 */
function modern_admin_cleanup_dashboard() {
    // Remove unnecessary dashboard widgets
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
}
add_action('wp_dashboard_setup', 'modern_admin_cleanup_dashboard');

/**
 * Add custom admin styles for better UX
 */
function modern_admin_custom_styles() {
    ?>
    <style>
    /* Smooth transitions */
    #adminmenu .wp-menu-item,
    #adminmenu .wp-submenu,
    .postbox,
    .button {
        transition: all 0.3s ease;
    }
    
    /* Better focus states */
    .button:focus,
    input:focus,
    select:focus,
    textarea:focus {
        outline: 2px solid #667eea;
        outline-offset: 2px;
    }
    
    /* Improved hover effects */
    #adminmenu .wp-menu-item:hover {
        background: rgba(102, 126, 234, 0.1);
    }
    
    /* Custom scrollbar for admin menu */
    #adminmenu::-webkit-scrollbar {
        width: 6px;
    }
    
    #adminmenu::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #adminmenu::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    #adminmenu::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    </style>
    <?php
}
add_action('admin_head', 'modern_admin_custom_styles');
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
