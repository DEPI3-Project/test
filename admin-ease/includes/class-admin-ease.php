<?php
namespace AdminEase;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Plugin {
    private static $instance = null;

    public static function instance() : Plugin {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', [ $this, 'load_textdomain' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'login_enqueue_scripts', [ $this, 'enqueue_login_assets' ] );
        add_action( 'login_head', [ $this, 'inline_login_styles' ] );
        add_action( 'admin_menu', [ $this, 'register_settings_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_filter( 'admin_footer_text', [ $this, 'admin_footer_text' ] );
        add_filter( 'update_footer', [ $this, 'update_footer_version' ], 999 );
        add_action( 'wp_dashboard_setup', [ $this, 'customize_dashboard' ] );
        add_filter( 'admin_body_class', [ $this, 'admin_body_classes' ] );
        add_action( 'admin_bar_menu', [ $this, 'customize_admin_bar' ], 100 );
        add_filter( 'login_headerurl', [ $this, 'login_logo_url' ] );
        add_filter( 'login_headertext', [ $this, 'login_logo_text' ] );
        add_action( 'admin_notices', [ $this, 'onboarding_notice' ] );
        add_filter( 'menu_order', [ $this, 'menu_order' ] );
        add_filter( 'custom_menu_order', '__return_true' );
    }

    public function load_textdomain() : void {
        load_plugin_textdomain( 'admin-ease', false, dirname( plugin_basename( ADMINEASE_FILE ) ) . '/languages' );
    }

    public function enqueue_assets() : void {
        wp_enqueue_style( 'adminease-admin', ADMINEASE_URL . 'assets/css/admin.css', [], ADMINEASE_VERSION );
        wp_enqueue_script( 'adminease-admin', ADMINEASE_URL . 'assets/js/admin.js', [ 'jquery' ], ADMINEASE_VERSION, true );
        wp_localize_script( 'adminease-admin', 'AdminEase', [
            'colorScheme' => $this->get_option( 'color_scheme', 'system' ),
            'compact' => (bool) $this->get_option( 'compact', false ),
        ] );
    }

    public function enqueue_login_assets() : void {
        wp_enqueue_style( 'adminease-login', ADMINEASE_URL . 'assets/css/login.css', [], ADMINEASE_VERSION );
    }

    public function inline_login_styles() : void {
        $logo = $this->get_option( 'login_logo_url', '' );
        $bg   = $this->get_option( 'login_bg_color', '' );
        if ( empty( $logo ) && empty( $bg ) ) {
            return;
        }
        echo '<style id="adminease-login-inline">';
        if ( ! empty( $bg ) ) {
            echo 'body.login{background:' . esc_html( $bg ) . '}';
        }
        if ( ! empty( $logo ) ) {
            echo '.login h1 a{background-image:url(' . esc_url( $logo ) . '); background-size: contain;}';
        }
        echo '</style>';
    }

    public function admin_body_classes( string $classes ) : string {
        $scheme = $this->get_option( 'color_scheme', 'system' );
        $compact = (bool) $this->get_option( 'compact', false );
        $classes .= ' adminease-scheme-' . sanitize_html_class( $scheme );
        if ( $compact ) {
            $classes .= ' adminease-compact';
        }
        return $classes;
    }

    public function admin_footer_text( string $text ) : string {
        return sprintf( /* translators: 1: plugin name */ __( '%s active. Have a productive day! ', 'admin-ease' ), 'AdminEase' ) . $text;
    }

    public function update_footer_version( string $text ) : string {
        return 'AdminEase v' . ADMINEASE_VERSION;
    }

    public function register_settings_page() : void {
        add_menu_page(
            __( 'AdminEase', 'admin-ease' ),
            __( 'AdminEase', 'admin-ease' ),
            'manage_options',
            'adminease',
            [ $this, 'render_settings_page' ],
            'dashicons-admin-customizer',
            2
        );
    }

    public function render_settings_page() : void {
        ?>
        <div class="wrap adminease-settings">
            <h1><?php echo esc_html__( 'AdminEase Settings', 'admin-ease' ); ?></h1>
            <form action="options.php" method="post">
                <?php settings_fields( 'adminease_options' ); ?>
                <?php do_settings_sections( 'adminease' ); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() : void {
        register_setting( 'adminease_options', 'adminease_options', [ $this, 'sanitize_options' ] );

        add_settings_section(
            'adminease_section_general',
            __( 'General', 'admin-ease' ),
            function () { echo '<p>' . esc_html__( 'Tweak the admin appearance for clarity and focus.', 'admin-ease' ) . '</p>'; },
            'adminease'
        );

        add_settings_field(
            'color_scheme',
            __( 'Color scheme', 'admin-ease' ),
            [ $this, 'field_color_scheme' ],
            'adminease',
            'adminease_section_general'
        );

        add_settings_field(
            'compact',
            __( 'Compact spacing', 'admin-ease' ),
            [ $this, 'field_compact' ],
            'adminease',
            'adminease_section_general'
        );

        add_settings_section(
            'adminease_section_login',
            __( 'Login Screen', 'admin-ease' ),
            function () { echo '<p>' . esc_html__( 'Brand the login screen.', 'admin-ease' ) . '</p>'; },
            'adminease'
        );

        add_settings_field(
            'login_logo_url',
            __( 'Login logo URL', 'admin-ease' ),
            [ $this, 'field_login_logo_url' ],
            'adminease',
            'adminease_section_login'
        );

        add_settings_field(
            'login_bg_color',
            __( 'Login background color', 'admin-ease' ),
            [ $this, 'field_login_bg_color' ],
            'adminease',
            'adminease_section_login'
        );
    }

    public function sanitize_options( array $options ) : array {
        $sanitized = [];
        $sanitized['color_scheme'] = in_array( $options['color_scheme'] ?? 'system', [ 'system', 'light', 'dark' ], true ) ? $options['color_scheme'] : 'system';
        $sanitized['compact'] = ! empty( $options['compact'] );
        $sanitized['login_logo_url'] = esc_url_raw( $options['login_logo_url'] ?? '' );
        $sanitized['login_bg_color'] = preg_match( '/^#([A-Fa-f0-9]{3}){1,2}$/', $options['login_bg_color'] ?? '' ) ? $options['login_bg_color'] : '';
        return $sanitized;
    }

    private function get_option( string $key, $default = null ) {
        $opts = get_option( 'adminease_options', [] );
        return $opts[ $key ] ?? $default;
    }

    public function field_color_scheme() : void {
        $value = $this->get_option( 'color_scheme', 'system' );
        ?>
        <select name="adminease_options[color_scheme]">
            <option value="system" <?php selected( $value, 'system' ); ?>><?php esc_html_e( 'System (auto)', 'admin-ease' ); ?></option>
            <option value="light" <?php selected( $value, 'light' ); ?>><?php esc_html_e( 'Light', 'admin-ease' ); ?></option>
            <option value="dark" <?php selected( $value, 'dark' ); ?>><?php esc_html_e( 'Dark', 'admin-ease' ); ?></option>
        </select>
        <?php
    }

    public function field_compact() : void {
        $value = (bool) $this->get_option( 'compact', false );
        ?>
        <label>
            <input type="checkbox" name="adminease_options[compact]" value="1" <?php checked( $value ); ?> />
            <?php esc_html_e( 'Reduce spacing (denser layout)', 'admin-ease' ); ?>
        </label>
        <?php
    }

    public function field_login_logo_url() : void {
        $value = $this->get_option( 'login_logo_url', '' );
        ?>
        <input type="url" class="regular-text" name="adminease_options[login_logo_url]" value="<?php echo esc_attr( $value ); ?>" placeholder="https://example.com/logo.svg" />
        <?php
    }

    public function field_login_bg_color() : void {
        $value = $this->get_option( 'login_bg_color', '' );
        ?>
        <input type="text" class="regular-text" name="adminease_options[login_bg_color]" value="<?php echo esc_attr( $value ); ?>" placeholder="#0e1116" />
        <p class="description"><?php esc_html_e( 'Hex color like #0e1116', 'admin-ease' ); ?></p>
        <?php
    }

    public function customize_dashboard() : void {
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );

        wp_add_dashboard_widget( 'adminease_welcome', __( 'Welcome to your site', 'admin-ease' ), function () {
            echo '<div class="adminease-welcome"><p>' . esc_html__( 'Quick links to get you moving.', 'admin-ease' ) . '</p>';
            echo '<p><a class="button button-primary" href="' . esc_url( admin_url( 'post-new.php' ) ) . '">' . esc_html__( 'Create Post', 'admin-ease' ) . '</a> ';
            echo '<a class="button" href="' . esc_url( admin_url( 'customize.php' ) ) . '">' . esc_html__( 'Customize', 'admin-ease' ) . '</a></p></div>';
        } );
    }

    public function customize_admin_bar( \WP_Admin_Bar $wp_admin_bar ) : void {
        $wp_admin_bar->remove_node( 'wp-logo' );
        $wp_admin_bar->remove_node( 'updates' );
    }

    public function menu_order( $menu_order ) {
        if ( ! is_array( $menu_order ) ) {
            return $menu_order;
        }
        $priority = [ 'index.php', 'edit.php?post_type=page', 'edit.php', 'upload.php', 'themes.php', 'plugins.php', 'users.php', 'tools.php', 'options-general.php' ];
        usort( $menu_order, function ( $a, $b ) use ( $priority ) {
            $ai = array_search( $a, $priority, true );
            $bi = array_search( $b, $priority, true );
            $ai = $ai === false ? PHP_INT_MAX : $ai;
            $bi = $bi === false ? PHP_INT_MAX : $bi;
            return $ai <=> $bi;
        } );
        return $menu_order;
    }

    public function login_logo_url() : string {
        return home_url( '/' );
    }

    public function login_logo_text() : string {
        return get_bloginfo( 'name' );
    }

    public function onboarding_notice() : void {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        if ( get_user_meta( get_current_user_id(), '_adminease_dismissed', true ) ) {
            return;
        }
        echo '<div class="notice notice-info is-dismissible adminease-onboarding"><p>' . esc_html__( 'AdminEase active: visit Settings → AdminEase to adjust colors, spacing, and login.', 'admin-ease' ) . '</p></div>';
        add_action( 'admin_print_footer_scripts', function () {
            ?>
            <script>
            jQuery(function($){
                $(document).on('click', '.adminease-onboarding .notice-dismiss', function(){
                    $.post(ajaxurl, { action: 'adminease_dismiss' });
                });
            });
            </script>
            <?php
        } );
        add_action( 'wp_ajax_adminease_dismiss', function(){
            update_user_meta( get_current_user_id(), '_adminease_dismissed', 1 );
            wp_die();
        } );
    }
}
