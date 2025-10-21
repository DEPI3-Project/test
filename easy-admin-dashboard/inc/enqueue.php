<?php
if (!defined('ABSPATH')) { exit; }

add_action('admin_enqueue_scripts', function($hook_suffix) {
    wp_enqueue_style('ead-admin', EAD_PLUGIN_URL . 'assets/css/admin.css', [], EAD_VERSION);
    wp_enqueue_script('ead-admin', EAD_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], EAD_VERSION, true);

    $accent = get_option('ead_accent_color', '#3a60ff');
    wp_add_inline_style('ead-admin', ":root{--ead-accent: {$accent}}" );
});

// Add classes and scheme data attribute to body
add_filter('admin_body_class', function($classes){
    $compact = get_option('ead_compact_menu', false);
    if ($compact) {
        $classes .= ' ead-compact';
    }
    $scheme = get_option('ead_color_scheme', 'light');
    add_action('admin_print_footer_scripts', function() use ($scheme){
        echo '<script>document.body.setAttribute("data-ead-scheme","'.esc_js($scheme).'");</script>';
    });
    return $classes;
});

add_action('login_enqueue_scripts', function() {
    wp_enqueue_style('ead-login', EAD_PLUGIN_URL . 'assets/css/login.css', [], EAD_VERSION);
    $accent = get_option('ead_accent_color', '#3a60ff');
    wp_add_inline_style('ead-login', ":root{--ead-accent: {$accent}}" );
});
