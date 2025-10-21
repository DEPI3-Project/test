<?php
if (!defined('ABSPATH')) { exit; }

add_filter('login_headerurl', function($url){
    $custom = get_option('ead_custom_logo', '');
    return $custom ? home_url('/') : $url;
});

add_filter('login_headertext', function($text){
    $custom = get_option('ead_custom_logo', '');
    return $custom ? get_bloginfo('name') : $text;
});

add_action('login_head', function(){
    $logo = esc_url(get_option('ead_custom_logo', ''));
    if (!$logo) { return; }
    echo '<style>.login h1 a{background-image:url(' . $logo . ')!important;background-size:contain;width:200px;height:80px;}</style>';
});
