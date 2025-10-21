<?php
if (!defined('ABSPATH')) { exit; }

add_action('admin_bar_menu', function($wp_admin_bar){
    if (!current_user_can('manage_options')) { return; }
    // Parent node linking to settings
    $wp_admin_bar->add_node([
        'id' => 'ead-quick-toggle',
        'title' => '<span class="ab-icon dashicons-admin-appearance"></span><span class="ab-label">Easy Admin</span>',
        'href' => admin_url('options-general.php?page=ead-settings'),
        'meta' => [ 'title' => __('Easy Admin Settings', 'easy-admin-dashboard') ]
    ]);

    // Child: toggle light
    $wp_admin_bar->add_node([
        'id' => 'ead-scheme-light',
        'parent' => 'ead-quick-toggle',
        'title' => __('Light scheme', 'easy-admin-dashboard'),
        'href' => '#',
        'meta' => [ 'onclick' => 'window.EAD_setScheme && window.EAD_setScheme("light"); return false;' ]
    ]);
    // Child: toggle dark
    $wp_admin_bar->add_node([
        'id' => 'ead-scheme-dark',
        'parent' => 'ead-quick-toggle',
        'title' => __('Dark scheme', 'easy-admin-dashboard'),
        'href' => '#',
        'meta' => [ 'onclick' => 'window.EAD_setScheme && window.EAD_setScheme("dark"); return false;' ]
    ]);
    // Child: toggle system
    $wp_admin_bar->add_node([
        'id' => 'ead-scheme-system',
        'parent' => 'ead-quick-toggle',
        'title' => __('System (auto)', 'easy-admin-dashboard'),
        'href' => '#',
        'meta' => [ 'onclick' => 'window.EAD_setScheme && window.EAD_setScheme("system"); return false;' ]
    ]);
}, 100);
