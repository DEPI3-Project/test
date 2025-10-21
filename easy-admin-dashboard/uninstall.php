<?php
// Safety: only run from WordPress uninstall
if (!defined('WP_UNINSTALL_PLUGIN')) { exit; }

$keys = [
    'ead_color_scheme',
    'ead_accent_color',
    'ead_compact_menu',
    'ead_custom_logo',
];
foreach ($keys as $key) {
    delete_option($key);
}
