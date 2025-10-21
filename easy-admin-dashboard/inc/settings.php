<?php
if (!defined('ABSPATH')) { exit; }

class EAD_Settings_Page {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_menu() {
        add_options_page(
            __('Easy Admin', 'easy-admin-dashboard'),
            __('Easy Admin', 'easy-admin-dashboard'),
            'manage_options',
            'ead-settings',
            [$this, 'render_page']
        );
    }

    public function register_settings() {
        register_setting('ead_options', 'ead_color_scheme', [
            'type' => 'string',
            'sanitize_callback' => [$this, 'sanitize_scheme'],
            'default' => 'light',
        ]);
        register_setting('ead_options', 'ead_accent_color', [
            'type' => 'string',
            'sanitize_callback' => [$this, 'sanitize_accent'],
            'default' => '#3a60ff',
        ]);
        register_setting('ead_options', 'ead_compact_menu', [
            'type' => 'boolean',
            'sanitize_callback' => [$this, 'sanitize_bool'],
            'default' => false,
        ]);
        register_setting('ead_options', 'ead_custom_logo', [
            'type' => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default' => '',
        ]);

        add_settings_section('ead_main', __('Appearance', 'easy-admin-dashboard'), function(){
            echo '<p>' . esc_html__('Customize your admin look and feel.', 'easy-admin-dashboard') . '</p>';
        }, 'ead-settings');

        add_settings_field('ead_color_scheme', __('Color scheme', 'easy-admin-dashboard'), [$this, 'field_color_scheme'], 'ead-settings', 'ead_main');
        add_settings_field('ead_accent_color', __('Accent color', 'easy-admin-dashboard'), [$this, 'field_accent_color'], 'ead-settings', 'ead_main');
        add_settings_field('ead_compact_menu', __('Compact menu', 'easy-admin-dashboard'), [$this, 'field_compact_menu'], 'ead-settings', 'ead_main');
        add_settings_field('ead_custom_logo', __('Custom login/Admin logo URL', 'easy-admin-dashboard'), [$this, 'field_custom_logo'], 'ead-settings', 'ead_main');
    }

    public function field_color_scheme() {
        $value = get_option('ead_color_scheme', 'light');
        ?>
        <select name="ead_color_scheme">
            <option value="light" <?php selected($value, 'light'); ?>><?php esc_html_e('Light', 'easy-admin-dashboard'); ?></option>
            <option value="dark" <?php selected($value, 'dark'); ?>><?php esc_html_e('Dark', 'easy-admin-dashboard'); ?></option>
            <option value="system" <?php selected($value, 'system'); ?>><?php esc_html_e('System (auto)', 'easy-admin-dashboard'); ?></option>
        </select>
        <?php
    }

    public function field_accent_color() {
        $value = get_option('ead_accent_color', '#3a60ff');
        ?>
        <input type="text" class="regular-text" name="ead_accent_color" value="<?php echo esc_attr($value); ?>" placeholder="#3a60ff" />
        <p class="description"><?php esc_html_e('Use any valid CSS color (hex, rgb, hsl).', 'easy-admin-dashboard'); ?></p>
        <?php
    }

    public function field_compact_menu() {
        $value = (bool) get_option('ead_compact_menu', false);
        ?>
        <input type="hidden" name="ead_compact_menu" value="0" />
        <label>
            <input type="checkbox" name="ead_compact_menu" value="1" <?php checked($value, true); ?> />
            <?php esc_html_e('Reduce menu spacing and font size', 'easy-admin-dashboard'); ?>
        </label>
        <?php
    }

    public function field_custom_logo() {
        $value = get_option('ead_custom_logo', '');
        ?>
        <input type="url" class="regular-text" name="ead_custom_logo" value="<?php echo esc_url($value); ?>" placeholder="https://example.com/logo.svg" />
        <p class="description"><?php esc_html_e('Displayed on login and admin bar.', 'easy-admin-dashboard'); ?></p>
        <?php
    }

    public function render_page() {
        ?>
        <div class="wrap" id="ead-settings">
            <h1><?php esc_html_e('Easy Admin Dashboard', 'easy-admin-dashboard'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('ead_options');
                do_settings_sections('ead-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function sanitize_scheme($value) {
        $allowed = ['light', 'dark', 'system'];
        return in_array($value, $allowed, true) ? $value : 'light';
    }

    public function sanitize_accent($value) {
        $hex = sanitize_hex_color($value);
        return $hex ? $hex : '#3a60ff';
    }

    public function sanitize_bool($value) {
        return (int) (bool) $value;
    }
}

new EAD_Settings_Page();
