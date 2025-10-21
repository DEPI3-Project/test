<?php
/**
 * Dashboard Class for Easy Admin Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class EAT_Dashboard {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_dashboard_setup', array($this, 'setup_dashboard'));
        add_action('admin_head-index.php', array($this, 'dashboard_styles'));
    }
    
    /**
     * Setup dashboard widgets
     */
    public function setup_dashboard() {
        // Remove default widgets
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
        remove_meta_box('dashboard_secondary', 'dashboard', 'side');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
        
        // Add custom welcome widget
        wp_add_dashboard_widget(
            'eat_welcome_widget',
            __('Welcome', 'easy-admin-theme'),
            array($this, 'welcome_widget')
        );
        
        // Add quick stats widget
        wp_add_dashboard_widget(
            'eat_quick_stats',
            __('Quick Stats', 'easy-admin-theme'),
            array($this, 'quick_stats_widget')
        );
        
        // Add recent activity widget
        wp_add_dashboard_widget(
            'eat_recent_activity',
            __('Recent Activity', 'easy-admin-theme'),
            array($this, 'recent_activity_widget')
        );
        
        // Add quick actions widget
        wp_add_dashboard_widget(
            'eat_quick_actions',
            __('Quick Actions', 'easy-admin-theme'),
            array($this, 'quick_actions_widget')
        );
    }
    
    /**
     * Welcome widget
     */
    public function welcome_widget() {
        $user = wp_get_current_user();
        $welcome_message = EAT_Settings::get_option('welcome_message', __('Welcome to your enhanced WordPress dashboard!', 'easy-admin-theme'));
        ?>
        <div class="eat-welcome-widget">
            <div class="eat-welcome-header">
                <h2><?php printf(__('Hello, %s!', 'easy-admin-theme'), $user->display_name); ?></h2>
                <p class="eat-welcome-time"><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format')); ?></p>
            </div>
            
            <div class="eat-welcome-content">
                <p><?php echo wp_kses_post($welcome_message); ?></p>
            </div>
            
            <div class="eat-welcome-actions">
                <a href="<?php echo admin_url('post-new.php'); ?>" class="button button-primary">
                    <span class="dashicons dashicons-edit"></span>
                    <?php _e('New Post', 'easy-admin-theme'); ?>
                </a>
                <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="button button-secondary">
                    <span class="dashicons dashicons-admin-page"></span>
                    <?php _e('New Page', 'easy-admin-theme'); ?>
                </a>
                <a href="<?php echo admin_url('upload.php'); ?>" class="button button-secondary">
                    <span class="dashicons dashicons-admin-media"></span>
                    <?php _e('Media Library', 'easy-admin-theme'); ?>
                </a>
            </div>
        </div>
        <?php
    }
    
    /**
     * Quick stats widget
     */
    public function quick_stats_widget() {
        $posts_count = wp_count_posts('post');
        $pages_count = wp_count_posts('page');
        $comments_count = wp_count_comments();
        $users_count = count_users();
        ?>
        <div class="eat-quick-stats">
            <div class="eat-stat-item">
                <div class="eat-stat-icon">
                    <span class="dashicons dashicons-admin-post"></span>
                </div>
                <div class="eat-stat-content">
                    <div class="eat-stat-number"><?php echo number_format_i18n($posts_count->publish); ?></div>
                    <div class="eat-stat-label"><?php _e('Published Posts', 'easy-admin-theme'); ?></div>
                </div>
            </div>
            
            <div class="eat-stat-item">
                <div class="eat-stat-icon">
                    <span class="dashicons dashicons-admin-page"></span>
                </div>
                <div class="eat-stat-content">
                    <div class="eat-stat-number"><?php echo number_format_i18n($pages_count->publish); ?></div>
                    <div class="eat-stat-label"><?php _e('Published Pages', 'easy-admin-theme'); ?></div>
                </div>
            </div>
            
            <div class="eat-stat-item">
                <div class="eat-stat-icon">
                    <span class="dashicons dashicons-admin-comments"></span>
                </div>
                <div class="eat-stat-content">
                    <div class="eat-stat-number"><?php echo number_format_i18n($comments_count->approved); ?></div>
                    <div class="eat-stat-label"><?php _e('Approved Comments', 'easy-admin-theme'); ?></div>
                </div>
            </div>
            
            <div class="eat-stat-item">
                <div class="eat-stat-icon">
                    <span class="dashicons dashicons-admin-users"></span>
                </div>
                <div class="eat-stat-content">
                    <div class="eat-stat-number"><?php echo number_format_i18n($users_count['total_users']); ?></div>
                    <div class="eat-stat-label"><?php _e('Total Users', 'easy-admin-theme'); ?></div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Recent activity widget
     */
    public function recent_activity_widget() {
        $recent_posts = get_posts(array(
            'numberposts' => 5,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        ));
        
        $recent_comments = get_comments(array(
            'number' => 5,
            'status' => 'approve',
            'orderby' => 'comment_date',
            'order' => 'DESC'
        ));
        ?>
        <div class="eat-recent-activity">
            <div class="eat-activity-section">
                <h4><?php _e('Recent Posts', 'easy-admin-theme'); ?></h4>
                <?php if ($recent_posts) : ?>
                    <ul class="eat-activity-list">
                        <?php foreach ($recent_posts as $post) : ?>
                            <li>
                                <a href="<?php echo get_edit_post_link($post->ID); ?>">
                                    <?php echo esc_html($post->post_title); ?>
                                </a>
                                <span class="eat-activity-date">
                                    <?php echo human_time_diff(strtotime($post->post_date), current_time('timestamp')) . ' ' . __('ago', 'easy-admin-theme'); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p><?php _e('No recent posts found.', 'easy-admin-theme'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="eat-activity-section">
                <h4><?php _e('Recent Comments', 'easy-admin-theme'); ?></h4>
                <?php if ($recent_comments) : ?>
                    <ul class="eat-activity-list">
                        <?php foreach ($recent_comments as $comment) : ?>
                            <li>
                                <a href="<?php echo get_edit_comment_link($comment->comment_ID); ?>">
                                    <?php echo esc_html(wp_trim_words($comment->comment_content, 8)); ?>
                                </a>
                                <span class="eat-activity-author">
                                    <?php printf(__('by %s', 'easy-admin-theme'), esc_html($comment->comment_author)); ?>
                                </span>
                                <span class="eat-activity-date">
                                    <?php echo human_time_diff(strtotime($comment->comment_date), current_time('timestamp')) . ' ' . __('ago', 'easy-admin-theme'); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p><?php _e('No recent comments found.', 'easy-admin-theme'); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Quick actions widget
     */
    public function quick_actions_widget() {
        ?>
        <div class="eat-quick-actions">
            <div class="eat-action-grid">
                <a href="<?php echo admin_url('post-new.php'); ?>" class="eat-action-item">
                    <span class="eat-action-icon dashicons dashicons-edit"></span>
                    <span class="eat-action-label"><?php _e('Write Post', 'easy-admin-theme'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="eat-action-item">
                    <span class="eat-action-icon dashicons dashicons-admin-page"></span>
                    <span class="eat-action-label"><?php _e('Create Page', 'easy-admin-theme'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('media-new.php'); ?>" class="eat-action-item">
                    <span class="eat-action-icon dashicons dashicons-cloud-upload"></span>
                    <span class="eat-action-label"><?php _e('Upload Media', 'easy-admin-theme'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('edit-comments.php'); ?>" class="eat-action-item">
                    <span class="eat-action-icon dashicons dashicons-admin-comments"></span>
                    <span class="eat-action-label"><?php _e('Moderate Comments', 'easy-admin-theme'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('themes.php'); ?>" class="eat-action-item">
                    <span class="eat-action-icon dashicons dashicons-admin-appearance"></span>
                    <span class="eat-action-label"><?php _e('Customize Theme', 'easy-admin-theme'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('plugins.php'); ?>" class="eat-action-item">
                    <span class="eat-action-icon dashicons dashicons-admin-plugins"></span>
                    <span class="eat-action-label"><?php _e('Manage Plugins', 'easy-admin-theme'); ?></span>
                </a>
            </div>
        </div>
        <?php
    }
    
    /**
     * Dashboard styles
     */
    public function dashboard_styles() {
        ?>
        <style>
        /* Welcome Widget Styles */
        .eat-welcome-widget {
            padding: 0;
        }
        
        .eat-welcome-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin: -12px -12px 20px -12px;
            border-radius: 4px 4px 0 0;
        }
        
        .eat-welcome-header h2 {
            margin: 0 0 5px 0;
            font-size: 24px;
            font-weight: 300;
        }
        
        .eat-welcome-time {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .eat-welcome-content {
            margin-bottom: 20px;
        }
        
        .eat-welcome-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .eat-welcome-actions .button {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 16px;
            text-decoration: none;
        }
        
        /* Quick Stats Styles */
        .eat-quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
        }
        
        .eat-stat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #2271b1;
        }
        
        .eat-stat-icon {
            font-size: 24px;
            color: #2271b1;
        }
        
        .eat-stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #1d2327;
            line-height: 1;
        }
        
        .eat-stat-label {
            font-size: 12px;
            color: #646970;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Recent Activity Styles */
        .eat-recent-activity {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .eat-activity-section h4 {
            margin: 0 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #f0f0f1;
            color: #1d2327;
        }
        
        .eat-activity-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        .eat-activity-list li {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f1;
        }
        
        .eat-activity-list li:last-child {
            border-bottom: none;
        }
        
        .eat-activity-list a {
            display: block;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 4px;
        }
        
        .eat-activity-date,
        .eat-activity-author {
            display: block;
            font-size: 12px;
            color: #646970;
        }
        
        /* Quick Actions Styles */
        .eat-action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
        }
        
        .eat-action-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 15px;
            background: #f8f9fa;
            border-radius: 6px;
            text-decoration: none;
            color: #1d2327;
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }
        
        .eat-action-item:hover {
            background: #e3f2fd;
            border-color: #2271b1;
            color: #2271b1;
            transform: translateY(-2px);
        }
        
        .eat-action-icon {
            font-size: 32px;
            margin-bottom: 8px;
        }
        
        .eat-action-label {
            font-size: 13px;
            font-weight: 500;
            text-align: center;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .eat-recent-activity {
                grid-template-columns: 1fr;
            }
            
            .eat-welcome-actions {
                flex-direction: column;
            }
            
            .eat-welcome-actions .button {
                justify-content: center;
            }
        }
        </style>
        <?php
    }
}