<?php
/**
 * Menu Manager Class for Easy Admin Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class EAT_Menu_Manager {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'reorganize_menu'), 999);
        add_action('admin_head', array($this, 'menu_styles'));
        add_filter('custom_menu_order', '__return_true');
        add_filter('menu_order', array($this, 'custom_menu_order'));
    }
    
    /**
     * Reorganize admin menu
     */
    public function reorganize_menu() {
        global $menu, $submenu;
        
        // Add separators for better organization
        $this->add_menu_separators();
        
        // Rename menu items for better clarity
        $this->rename_menu_items();
        
        // Add custom menu icons
        $this->update_menu_icons();
    }
    
    /**
     * Add menu separators
     */
    private function add_menu_separators() {
        global $menu;
        
        // Add separator after Dashboard
        $menu[3] = array('', 'read', 'separator-dashboard', '', 'wp-menu-separator');
        
        // Add separator before Appearance
        $menu[58] = array('', 'read', 'separator-appearance', '', 'wp-menu-separator');
        
        // Add separator before Settings
        $menu[79] = array('', 'read', 'separator-settings', '', 'wp-menu-separator');
    }
    
    /**
     * Rename menu items for clarity
     */
    private function rename_menu_items() {
        global $menu;
        
        foreach ($menu as $key => $item) {
            if (isset($item[0])) {
                switch ($item[2]) {
                    case 'edit.php':
                        $menu[$key][0] = __('Blog Posts', 'easy-admin-theme');
                        break;
                    case 'upload.php':
                        $menu[$key][0] = __('Media Library', 'easy-admin-theme');
                        break;
                    case 'edit.php?post_type=page':
                        $menu[$key][0] = __('Pages', 'easy-admin-theme');
                        break;
                    case 'edit-comments.php':
                        $menu[$key][0] = __('Comments', 'easy-admin-theme');
                        break;
                    case 'themes.php':
                        $menu[$key][0] = __('Appearance', 'easy-admin-theme');
                        break;
                    case 'plugins.php':
                        $menu[$key][0] = __('Plugins', 'easy-admin-theme');
                        break;
                    case 'users.php':
                        $menu[$key][0] = __('Users', 'easy-admin-theme');
                        break;
                    case 'tools.php':
                        $menu[$key][0] = __('Tools', 'easy-admin-theme');
                        break;
                    case 'options-general.php':
                        $menu[$key][0] = __('Settings', 'easy-admin-theme');
                        break;
                }
            }
        }
    }
    
    /**
     * Update menu icons
     */
    private function update_menu_icons() {
        global $menu;
        
        foreach ($menu as $key => $item) {
            if (isset($item[6])) {
                switch ($item[2]) {
                    case 'index.php':
                        $menu[$key][6] = 'dashicons-dashboard';
                        break;
                    case 'edit.php':
                        $menu[$key][6] = 'dashicons-edit-large';
                        break;
                    case 'upload.php':
                        $menu[$key][6] = 'dashicons-admin-media';
                        break;
                    case 'edit.php?post_type=page':
                        $menu[$key][6] = 'dashicons-admin-page';
                        break;
                    case 'edit-comments.php':
                        $menu[$key][6] = 'dashicons-admin-comments';
                        break;
                    case 'themes.php':
                        $menu[$key][6] = 'dashicons-admin-appearance';
                        break;
                    case 'plugins.php':
                        $menu[$key][6] = 'dashicons-admin-plugins';
                        break;
                    case 'users.php':
                        $menu[$key][6] = 'dashicons-admin-users';
                        break;
                    case 'tools.php':
                        $menu[$key][6] = 'dashicons-admin-tools';
                        break;
                    case 'options-general.php':
                        $menu[$key][6] = 'dashicons-admin-settings';
                        break;
                }
            }
        }
    }
    
    /**
     * Custom menu order
     */
    public function custom_menu_order($menu_order) {
        return array(
            'index.php',                    // Dashboard
            'separator-dashboard',          // Separator
            'edit.php',                     // Blog Posts
            'edit.php?post_type=page',      // Pages
            'upload.php',                   // Media Library
            'edit-comments.php',            // Comments
            'separator-appearance',         // Separator
            'themes.php',                   // Appearance
            'plugins.php',                  // Plugins
            'users.php',                    // Users
            'tools.php',                    // Tools
            'separator-settings',           // Separator
            'options-general.php',          // Settings
        );
    }
    
    /**
     * Menu styles
     */
    public function menu_styles() {
        $compact_menu = EAT_Settings::get_option('compact_menu', false);
        ?>
        <style>
        /* Enhanced Menu Styles */
        #adminmenu {
            background: #1e1e2e;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        #adminmenu li.menu-top {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        #adminmenu .wp-menu-arrow {
            display: none;
        }
        
        #adminmenu a.menu-top {
            padding: 12px 8px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        #adminmenu a.menu-top:hover,
        #adminmenu a.menu-top:focus,
        #adminmenu li.opensub > a.menu-top,
        #adminmenu li > a.menu-top:focus {
            background: rgba(255,255,255,0.1);
            color: #ffffff;
        }
        
        #adminmenu li.current > a.menu-top,
        #adminmenu li.wp-has-current-submenu > a.menu-top {
            background: #2271b1;
            color: #ffffff;
            position: relative;
        }
        
        #adminmenu li.current > a.menu-top::before,
        #adminmenu li.wp-has-current-submenu > a.menu-top::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #ffffff;
        }
        
        #adminmenu .wp-menu-name {
            font-weight: 500;
            font-size: 14px;
        }
        
        #adminmenu div.wp-menu-image {
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }
        
        #adminmenu a:hover div.wp-menu-image,
        #adminmenu li.opensub > a div.wp-menu-image,
        #adminmenu li.current > a div.wp-menu-image {
            opacity: 1;
        }
        
        /* Submenu Styles */
        #adminmenu .wp-submenu {
            background: #2c2c3e;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
        }
        
        #adminmenu .wp-submenu a {
            padding: 10px 16px;
            color: rgba(255,255,255,0.8);
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        
        #adminmenu .wp-submenu a:hover,
        #adminmenu .wp-submenu a:focus {
            background: rgba(255,255,255,0.05);
            color: #ffffff;
            border-left-color: #2271b1;
        }
        
        #adminmenu .wp-submenu li.current a,
        #adminmenu .wp-submenu li.current a:hover {
            background: rgba(34,113,177,0.2);
            color: #ffffff;
            border-left-color: #2271b1;
        }
        
        /* Menu Separators */
        #adminmenu .wp-menu-separator {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 8px 0;
        }
        
        /* Compact Menu Option */
        <?php if ($compact_menu) : ?>
        #adminmenu a.menu-top {
            padding: 8px;
        }
        
        #adminmenu .wp-menu-name {
            font-size: 13px;
        }
        
        #adminmenu div.wp-menu-image {
            width: 20px;
            height: 20px;
        }
        
        #adminmenu div.wp-menu-image:before {
            font-size: 16px;
        }
        <?php endif; ?>
        
        /* Responsive Menu */
        @media (max-width: 960px) {
            #adminmenu {
                position: fixed;
                z-index: 99999;
            }
            
            .folded #adminmenu {
                width: 36px;
            }
            
            .folded #adminmenu .wp-menu-name {
                display: none;
            }
        }
        
        /* Menu Animation */
        #adminmenu li {
            animation: slideInLeft 0.3s ease forwards;
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Menu Icons Enhancement */
        #adminmenu div.wp-menu-image:before {
            font-size: 18px;
            width: 20px;
            height: 20px;
            line-height: 20px;
        }
        
        /* Custom Menu Item Styles */
        #adminmenu li#menu-dashboard div.wp-menu-image:before {
            content: "\f226";
        }
        
        #adminmenu li#menu-posts div.wp-menu-image:before {
            content: "\f109";
        }
        
        #adminmenu li#menu-media div.wp-menu-image:before {
            content: "\f104";
        }
        
        #adminmenu li#menu-pages div.wp-menu-image:before {
            content: "\f105";
        }
        
        #adminmenu li#menu-comments div.wp-menu-image:before {
            content: "\f101";
        }
        
        #adminmenu li#menu-appearance div.wp-menu-image:before {
            content: "\f100";
        }
        
        #adminmenu li#menu-plugins div.wp-menu-image:before {
            content: "\f106";
        }
        
        #adminmenu li#menu-users div.wp-menu-image:before {
            content: "\f110";
        }
        
        #adminmenu li#menu-tools div.wp-menu-image:before {
            content: "\f107";
        }
        
        #adminmenu li#menu-settings div.wp-menu-image:before {
            content: "\f108";
        }
        </style>
        <?php
    }
}