<?php
/**
 * Settings Class for Easy Admin Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class EAT_Settings {
    
    /**
     * Initialize settings
     */
    public static function init() {
        add_action('admin_init', array(__CLASS__, 'register_settings'));
        add_action('wp_ajax_eat_save_settings', array(__CLASS__, 'save_settings_ajax'));
    }
    
    /**
     * Register settings
     */
    public static function register_settings() {
        register_setting('easy_admin_theme_options', 'easy_admin_theme_options', array(
            'sanitize_callback' => array(__CLASS__, 'sanitize_options')
        ));
    }
    
    /**
     * Sanitize options
     */
    public static function sanitize_options($options) {
        $sanitized = array();
        
        if (isset($options['color_scheme'])) {
            $sanitized['color_scheme'] = sanitize_text_field($options['color_scheme']);
        }
        
        if (isset($options['compact_menu'])) {
            $sanitized['compact_menu'] = (bool) $options['compact_menu'];
        }
        
        if (isset($options['hide_admin_bar'])) {
            $sanitized['hide_admin_bar'] = (bool) $options['hide_admin_bar'];
        }
        
        if (isset($options['custom_logo'])) {
            $sanitized['custom_logo'] = esc_url_raw($options['custom_logo']);
        }
        
        if (isset($options['welcome_message'])) {
            $sanitized['welcome_message'] = wp_kses_post($options['welcome_message']);
        }
        
        return $sanitized;
    }
    
    /**
     * Get option value
     */
    public static function get_option($key, $default = '') {
        $options = get_option('easy_admin_theme_options', array());
        return isset($options[$key]) ? $options[$key] : $default;
    }
    
    /**
     * Render settings page
     */
    public static function render_settings_page() {
        $options = get_option('easy_admin_theme_options', array());
        ?>
        <div class="wrap eat-settings-wrap">
            <h1><?php _e('Easy Admin Theme Settings', 'easy-admin-theme'); ?></h1>
            
            <div class="eat-settings-container">
                <div class="eat-settings-main">
                    <form method="post" action="options.php" class="eat-settings-form">
                        <?php settings_fields('easy_admin_theme_options'); ?>
                        
                        <div class="eat-settings-section">
                            <h2><?php _e('Appearance', 'easy-admin-theme'); ?></h2>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><?php _e('Color Scheme', 'easy-admin-theme'); ?></th>
                                    <td>
                                        <select name="easy_admin_theme_options[color_scheme]" class="regular-text">
                                            <option value="blue" <?php selected(self::get_option('color_scheme', 'blue'), 'blue'); ?>><?php _e('Blue', 'easy-admin-theme'); ?></option>
                                            <option value="green" <?php selected(self::get_option('color_scheme'), 'green'); ?>><?php _e('Green', 'easy-admin-theme'); ?></option>
                                            <option value="purple" <?php selected(self::get_option('color_scheme'), 'purple'); ?>><?php _e('Purple', 'easy-admin-theme'); ?></option>
                                            <option value="orange" <?php selected(self::get_option('color_scheme'), 'orange'); ?>><?php _e('Orange', 'easy-admin-theme'); ?></option>
                                            <option value="dark" <?php selected(self::get_option('color_scheme'), 'dark'); ?>><?php _e('Dark', 'easy-admin-theme'); ?></option>
                                        </select>
                                        <p class="description"><?php _e('Choose your preferred color scheme for the admin interface.', 'easy-admin-theme'); ?></p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php _e('Custom Logo URL', 'easy-admin-theme'); ?></th>
                                    <td>
                                        <input type="url" name="easy_admin_theme_options[custom_logo]" value="<?php echo esc_attr(self::get_option('custom_logo')); ?>" class="regular-text" />
                                        <p class="description"><?php _e('Enter a URL for your custom logo to replace the WordPress logo.', 'easy-admin-theme'); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="eat-settings-section">
                            <h2><?php _e('Layout Options', 'easy-admin-theme'); ?></h2>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><?php _e('Compact Menu', 'easy-admin-theme'); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="easy_admin_theme_options[compact_menu]" value="1" <?php checked(self::get_option('compact_menu'), 1); ?> />
                                            <?php _e('Enable compact menu layout', 'easy-admin-theme'); ?>
                                        </label>
                                        <p class="description"><?php _e('Reduces the size of the admin menu for more content space.', 'easy-admin-theme'); ?></p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php _e('Hide Admin Bar', 'easy-admin-theme'); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="easy_admin_theme_options[hide_admin_bar]" value="1" <?php checked(self::get_option('hide_admin_bar'), 1); ?> />
                                            <?php _e('Hide admin bar on frontend', 'easy-admin-theme'); ?>
                                        </label>
                                        <p class="description"><?php _e('Hides the admin bar when viewing the frontend of your site.', 'easy-admin-theme'); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="eat-settings-section">
                            <h2><?php _e('Dashboard', 'easy-admin-theme'); ?></h2>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><?php _e('Welcome Message', 'easy-admin-theme'); ?></th>
                                    <td>
                                        <textarea name="easy_admin_theme_options[welcome_message]" rows="3" class="large-text"><?php echo esc_textarea(self::get_option('welcome_message', __('Welcome to your enhanced WordPress dashboard!', 'easy-admin-theme'))); ?></textarea>
                                        <p class="description"><?php _e('Custom welcome message displayed on the dashboard.', 'easy-admin-theme'); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <?php submit_button(__('Save Settings', 'easy-admin-theme'), 'primary', 'submit', true, array('class' => 'eat-save-button')); ?>
                    </form>
                </div>
                
                <div class="eat-settings-sidebar">
                    <div class="eat-settings-widget">
                        <h3><?php _e('About Easy Admin Theme', 'easy-admin-theme'); ?></h3>
                        <p><?php _e('Transform your WordPress admin experience with a modern, intuitive interface designed for productivity and ease of use.', 'easy-admin-theme'); ?></p>
                        
                        <h4><?php _e('Features:', 'easy-admin-theme'); ?></h4>
                        <ul>
                            <li><?php _e('Modern, clean design', 'easy-admin-theme'); ?></li>
                            <li><?php _e('Responsive layout', 'easy-admin-theme'); ?></li>
                            <li><?php _e('Multiple color schemes', 'easy-admin-theme'); ?></li>
                            <li><?php _e('Enhanced dashboard widgets', 'easy-admin-theme'); ?></li>
                            <li><?php _e('Improved navigation', 'easy-admin-theme'); ?></li>
                        </ul>
                    </div>
                    
                    <div class="eat-settings-widget">
                        <h3><?php _e('Quick Actions', 'easy-admin-theme'); ?></h3>
                        <p>
                            <a href="<?php echo admin_url(); ?>" class="button button-secondary"><?php _e('View Dashboard', 'easy-admin-theme'); ?></a>
                        </p>
                        <p>
                            <a href="<?php echo admin_url('customize.php'); ?>" class="button button-secondary"><?php _e('Customize Theme', 'easy-admin-theme'); ?></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .eat-settings-wrap {
            margin: 20px 0;
        }
        
        .eat-settings-container {
            display: flex;
            gap: 30px;
            margin-top: 20px;
        }
        
        .eat-settings-main {
            flex: 2;
        }
        
        .eat-settings-sidebar {
            flex: 1;
            max-width: 300px;
        }
        
        .eat-settings-section {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ccd0d4;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        
        .eat-settings-section h2 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .eat-settings-widget {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ccd0d4;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        
        .eat-settings-widget h3 {
            margin-top: 0;
        }
        
        .eat-settings-widget ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .eat-save-button {
            background: #2271b1 !important;
            border-color: #2271b1 !important;
            box-shadow: 0 1px 0 #135e96 !important;
        }
        </style>
        <?php
    }
    
    /**
     * AJAX save settings
     */
    public static function save_settings_ajax() {
        check_ajax_referer('eat_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'easy-admin-theme'));
        }
        
        $options = isset($_POST['options']) ? $_POST['options'] : array();
        $sanitized_options = self::sanitize_options($options);
        
        update_option('easy_admin_theme_options', $sanitized_options);
        
        wp_send_json_success(array(
            'message' => __('Settings saved successfully!', 'easy-admin-theme')
        ));
    }
}

// Initialize settings
EAT_Settings::init();