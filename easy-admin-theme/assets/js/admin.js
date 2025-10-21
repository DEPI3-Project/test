/*!
 * Easy Admin Theme - Admin JavaScript
 * Version: 1.0.0
 */

(function($) {
    'use strict';
    
    // Initialize when document is ready
    $(document).ready(function() {
        EasyAdminTheme.init();
    });
    
    // Main Easy Admin Theme object
    window.EasyAdminTheme = {
        
        /**
         * Initialize the theme
         */
        init: function() {
            this.enhanceMenuNavigation();
            this.addQuickActions();
            this.enhanceFormElements();
            this.addLoadingStates();
            this.improveAccessibility();
            this.addKeyboardShortcuts();
            this.enhanceDashboard();
            this.addNotificationSystem();
            this.addSearchEnhancement();
            this.addResponsiveFeatures();
        },
        
        /**
         * Enhance menu navigation
         */
        enhanceMenuNavigation: function() {
            // Add smooth scrolling to menu
            $('#adminmenu a').on('click', function(e) {
                var $this = $(this);
                var href = $this.attr('href');
                
                // Add loading state
                $this.addClass('loading');
                
                // Remove loading state after navigation
                setTimeout(function() {
                    $this.removeClass('loading');
                }, 1000);
            });
            
            // Add menu item hover effects
            $('#adminmenu li.menu-top').hover(
                function() {
                    $(this).find('.wp-menu-name').addClass('hover-effect');
                },
                function() {
                    $(this).find('.wp-menu-name').removeClass('hover-effect');
                }
            );
            
            // Collapsible menu enhancement
            $('#collapse-menu').on('click', function() {
                setTimeout(function() {
                    $(window).trigger('resize');
                }, 300);
            });
        },
        
        /**
         * Add quick actions toolbar
         */
        addQuickActions: function() {
            // Add quick actions to admin bar
            if ($('#wpadminbar').length) {
                var quickActions = $('<div id="eat-quick-actions" class="eat-quick-toolbar">' +
                    '<button type="button" class="eat-quick-btn" data-action="new-post" title="New Post">' +
                        '<span class="dashicons dashicons-edit"></span>' +
                    '</button>' +
                    '<button type="button" class="eat-quick-btn" data-action="new-page" title="New Page">' +
                        '<span class="dashicons dashicons-admin-page"></span>' +
                    '</button>' +
                    '<button type="button" class="eat-quick-btn" data-action="media-library" title="Media Library">' +
                        '<span class="dashicons dashicons-admin-media"></span>' +
                    '</button>' +
                    '<button type="button" class="eat-quick-btn" data-action="customize" title="Customize">' +
                        '<span class="dashicons dashicons-admin-customizer"></span>' +
                    '</button>' +
                '</div>');
                
                $('body').append(quickActions);
                
                // Handle quick action clicks
                $('.eat-quick-btn').on('click', function() {
                    var action = $(this).data('action');
                    var urls = {
                        'new-post': eatAdmin.ajaxUrl.replace('admin-ajax.php', 'post-new.php'),
                        'new-page': eatAdmin.ajaxUrl.replace('admin-ajax.php', 'post-new.php?post_type=page'),
                        'media-library': eatAdmin.ajaxUrl.replace('admin-ajax.php', 'upload.php'),
                        'customize': eatAdmin.ajaxUrl.replace('admin-ajax.php', 'customize.php')
                    };
                    
                    if (urls[action]) {
                        window.location.href = urls[action];
                    }
                });
            }
        },
        
        /**
         * Enhance form elements
         */
        enhanceFormElements: function() {
            // Add floating labels to form inputs
            $('input[type="text"], input[type="email"], input[type="password"], textarea').each(function() {
                var $input = $(this);
                var $label = $('label[for="' + $input.attr('id') + '"]');
                
                if ($label.length && !$input.closest('.eat-enhanced').length) {
                    $input.wrap('<div class="eat-form-group eat-enhanced"></div>');
                    $label.addClass('eat-floating-label');
                    
                    // Check if input has value on load
                    if ($input.val()) {
                        $label.addClass('active');
                    }
                }
            });
            
            // Handle floating label animation
            $(document).on('focus blur', '.eat-enhanced input, .eat-enhanced textarea', function(e) {
                var $input = $(this);
                var $label = $input.siblings('.eat-floating-label');
                
                if (e.type === 'focus' || $input.val()) {
                    $label.addClass('active');
                } else {
                    $label.removeClass('active');
                }
            });
            
            // Add character counter to textareas
            $('textarea').each(function() {
                var $textarea = $(this);
                var maxLength = $textarea.attr('maxlength');
                
                if (maxLength) {
                    var $counter = $('<div class="eat-char-counter">' +
                        '<span class="current">0</span>/' +
                        '<span class="max">' + maxLength + '</span>' +
                    '</div>');
                    
                    $textarea.after($counter);
                    
                    $textarea.on('input', function() {
                        var current = $(this).val().length;
                        $counter.find('.current').text(current);
                        
                        if (current > maxLength * 0.9) {
                            $counter.addClass('warning');
                        } else {
                            $counter.removeClass('warning');
                        }
                    });
                }
            });
        },
        
        /**
         * Add loading states to buttons and forms
         */
        addLoadingStates: function() {
            // Add loading state to buttons
            $(document).on('click', '.button-primary, input[type="submit"]', function() {
                var $btn = $(this);
                var originalText = $btn.val() || $btn.text();
                
                $btn.addClass('loading')
                    .prop('disabled', true)
                    .val(eatAdmin.strings.saving)
                    .text(eatAdmin.strings.saving);
                
                // Remove loading state after form submission
                setTimeout(function() {
                    $btn.removeClass('loading')
                        .prop('disabled', false)
                        .val(originalText)
                        .text(originalText);
                }, 2000);
            });
            
            // Add loading overlay for AJAX requests
            $(document).ajaxStart(function() {
                if (!$('.eat-loading-overlay').length) {
                    $('body').append('<div class="eat-loading-overlay"><div class="eat-spinner"></div></div>');
                }
            }).ajaxStop(function() {
                $('.eat-loading-overlay').remove();
            });
        },
        
        /**
         * Improve accessibility
         */
        improveAccessibility: function() {
            // Add skip links
            if (!$('.eat-skip-link').length) {
                $('body').prepend(
                    '<a href="#wpbody-content" class="eat-skip-link screen-reader-text">' +
                    'Skip to main content</a>'
                );
            }
            
            // Improve focus management
            $(document).on('keydown', function(e) {
                // Escape key to close modals/dropdowns
                if (e.keyCode === 27) {
                    $('.eat-modal, .eat-dropdown').removeClass('active');
                    $('[aria-expanded="true"]').attr('aria-expanded', 'false');
                }
            });
            
            // Add ARIA labels to buttons without text
            $('button:not([aria-label]):empty, input[type="button"]:not([aria-label])[value=""]').each(function() {
                var $btn = $(this);
                var title = $btn.attr('title') || $btn.find('.dashicons').attr('class') || 'Button';
                $btn.attr('aria-label', title);
            });
            
            // Announce dynamic content changes
            if (!$('#eat-live-region').length) {
                $('body').append('<div id="eat-live-region" aria-live="polite" aria-atomic="true" class="screen-reader-text"></div>');
            }
        },
        
        /**
         * Add keyboard shortcuts
         */
        addKeyboardShortcuts: function() {
            $(document).on('keydown', function(e) {
                // Only trigger shortcuts when not in input fields
                if ($(e.target).is('input, textarea, select')) {
                    return;
                }
                
                // Ctrl/Cmd + shortcuts
                if (e.ctrlKey || e.metaKey) {
                    switch(e.keyCode) {
                        case 78: // N - New post
                            e.preventDefault();
                            window.location.href = eatAdmin.ajaxUrl.replace('admin-ajax.php', 'post-new.php');
                            break;
                        case 77: // M - Media library
                            e.preventDefault();
                            window.location.href = eatAdmin.ajaxUrl.replace('admin-ajax.php', 'upload.php');
                            break;
                        case 68: // D - Dashboard
                            e.preventDefault();
                            window.location.href = eatAdmin.ajaxUrl.replace('admin-ajax.php', 'index.php');
                            break;
                    }
                }
                
                // Alt + shortcuts
                if (e.altKey) {
                    switch(e.keyCode) {
                        case 83: // S - Settings
                            e.preventDefault();
                            window.location.href = eatAdmin.ajaxUrl.replace('admin-ajax.php', 'options-general.php');
                            break;
                    }
                }
            });
            
            // Show keyboard shortcuts help
            this.addShortcutsHelp();
        },
        
        /**
         * Add shortcuts help modal
         */
        addShortcutsHelp: function() {
            var helpModal = $('<div id="eat-shortcuts-modal" class="eat-modal">' +
                '<div class="eat-modal-content">' +
                    '<div class="eat-modal-header">' +
                        '<h3>Keyboard Shortcuts</h3>' +
                        '<button type="button" class="eat-modal-close">&times;</button>' +
                    '</div>' +
                    '<div class="eat-modal-body">' +
                        '<div class="eat-shortcut-list">' +
                            '<div class="eat-shortcut-item">' +
                                '<kbd>Ctrl/Cmd + N</kbd>' +
                                '<span>New Post</span>' +
                            '</div>' +
                            '<div class="eat-shortcut-item">' +
                                '<kbd>Ctrl/Cmd + M</kbd>' +
                                '<span>Media Library</span>' +
                            '</div>' +
                            '<div class="eat-shortcut-item">' +
                                '<kbd>Ctrl/Cmd + D</kbd>' +
                                '<span>Dashboard</span>' +
                            '</div>' +
                            '<div class="eat-shortcut-item">' +
                                '<kbd>Alt + S</kbd>' +
                                '<span>Settings</span>' +
                            '</div>' +
                            '<div class="eat-shortcut-item">' +
                                '<kbd>?</kbd>' +
                                '<span>Show this help</span>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>');
            
            $('body').append(helpModal);
            
            // Show help with ? key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 191 && e.shiftKey && !$(e.target).is('input, textarea')) { // ?
                    e.preventDefault();
                    $('#eat-shortcuts-modal').addClass('active');
                }
            });
            
            // Close modal
            $(document).on('click', '.eat-modal-close, .eat-modal', function(e) {
                if (e.target === this) {
                    $('.eat-modal').removeClass('active');
                }
            });
        },
        
        /**
         * Enhance dashboard
         */
        enhanceDashboard: function() {
            if ($('body').hasClass('wp-admin') && window.pagenow === 'dashboard') {
                // Add dashboard search
                this.addDashboardSearch();
                
                // Enhance dashboard widgets
                this.enhanceDashboardWidgets();
                
                // Add dashboard customization
                this.addDashboardCustomization();
            }
        },
        
        /**
         * Add dashboard search
         */
        addDashboardSearch: function() {
            var searchBox = $('<div class="eat-dashboard-search">' +
                '<input type="search" placeholder="Search dashboard..." class="eat-search-input">' +
                '<button type="button" class="eat-search-btn">' +
                    '<span class="dashicons dashicons-search"></span>' +
                '</button>' +
            '</div>');
            
            $('.wrap h1').after(searchBox);
            
            // Handle search
            $('.eat-search-input').on('input', function() {
                var query = $(this).val().toLowerCase();
                
                $('#dashboard-widgets .postbox').each(function() {
                    var $widget = $(this);
                    var title = $widget.find('.hndle').text().toLowerCase();
                    var content = $widget.find('.inside').text().toLowerCase();
                    
                    if (title.includes(query) || content.includes(query) || query === '') {
                        $widget.show();
                    } else {
                        $widget.hide();
                    }
                });
            });
        },
        
        /**
         * Enhance dashboard widgets
         */
        enhanceDashboardWidgets: function() {
            // Add widget controls
            $('#dashboard-widgets .postbox').each(function() {
                var $widget = $(this);
                var $handle = $widget.find('.hndle');
                
                if (!$handle.find('.eat-widget-controls').length) {
                    var controls = $('<div class="eat-widget-controls">' +
                        '<button type="button" class="eat-widget-refresh" title="Refresh">' +
                            '<span class="dashicons dashicons-update"></span>' +
                        '</button>' +
                        '<button type="button" class="eat-widget-minimize" title="Minimize">' +
                            '<span class="dashicons dashicons-minus"></span>' +
                        '</button>' +
                    '</div>');
                    
                    $handle.append(controls);
                }
            });
            
            // Handle widget controls
            $(document).on('click', '.eat-widget-refresh', function() {
                var $widget = $(this).closest('.postbox');
                var $content = $widget.find('.inside');
                
                $content.addClass('loading');
                
                // Simulate refresh
                setTimeout(function() {
                    $content.removeClass('loading');
                    EasyAdminTheme.showNotification('Widget refreshed!', 'success');
                }, 1000);
            });
            
            $(document).on('click', '.eat-widget-minimize', function() {
                var $widget = $(this).closest('.postbox');
                var $icon = $(this).find('.dashicons');
                
                $widget.find('.inside').slideToggle();
                $icon.toggleClass('dashicons-minus dashicons-plus');
            });
        },
        
        /**
         * Add dashboard customization
         */
        addDashboardCustomization: function() {
            // Add customization button
            var customizeBtn = $('<button type="button" class="button eat-customize-dashboard">' +
                '<span class="dashicons dashicons-admin-customizer"></span> ' +
                'Customize Dashboard' +
            '</button>');
            
            $('.eat-dashboard-search').after(customizeBtn);
            
            // Handle customization
            $('.eat-customize-dashboard').on('click', function() {
                $('#dashboard-widgets').toggleClass('eat-customize-mode');
                
                if ($('#dashboard-widgets').hasClass('eat-customize-mode')) {
                    $(this).text('Done Customizing').addClass('button-primary');
                    EasyAdminTheme.showNotification('Drag widgets to rearrange them', 'info');
                } else {
                    $(this).html('<span class="dashicons dashicons-admin-customizer"></span> Customize Dashboard')
                           .removeClass('button-primary');
                }
            });
        },
        
        /**
         * Add notification system
         */
        addNotificationSystem: function() {
            if (!$('#eat-notifications').length) {
                $('body').append('<div id="eat-notifications"></div>');
            }
        },
        
        /**
         * Show notification
         */
        showNotification: function(message, type, duration) {
            type = type || 'info';
            duration = duration || 4000;
            
            var notification = $('<div class="eat-notification eat-notification-' + type + '">' +
                '<span class="eat-notification-message">' + message + '</span>' +
                '<button type="button" class="eat-notification-close">&times;</button>' +
            '</div>');
            
            $('#eat-notifications').append(notification);
            
            // Show notification
            setTimeout(function() {
                notification.addClass('show');
            }, 100);
            
            // Auto hide
            setTimeout(function() {
                notification.removeClass('show');
                setTimeout(function() {
                    notification.remove();
                }, 300);
            }, duration);
            
            // Manual close
            notification.find('.eat-notification-close').on('click', function() {
                notification.removeClass('show');
                setTimeout(function() {
                    notification.remove();
                }, 300);
            });
        },
        
        /**
         * Add search enhancement
         */
        addSearchEnhancement: function() {
            // Enhance admin search
            $('#adminmenu').before('<div class="eat-admin-search">' +
                '<input type="search" placeholder="Search admin..." class="eat-admin-search-input">' +
                '<div class="eat-search-results"></div>' +
            '</div>');
            
            // Handle admin search
            $('.eat-admin-search-input').on('input', function() {
                var query = $(this).val().toLowerCase();
                var $results = $('.eat-search-results');
                
                if (query.length < 2) {
                    $results.empty().hide();
                    return;
                }
                
                var results = [];
                
                // Search menu items
                $('#adminmenu a').each(function() {
                    var $link = $(this);
                    var text = $link.text().toLowerCase();
                    var href = $link.attr('href');
                    
                    if (text.includes(query) && href) {
                        results.push({
                            title: $link.text().trim(),
                            url: href,
                            type: 'menu'
                        });
                    }
                });
                
                // Display results
                if (results.length > 0) {
                    var html = results.slice(0, 5).map(function(item) {
                        return '<a href="' + item.url + '" class="eat-search-result">' +
                            '<span class="eat-result-title">' + item.title + '</span>' +
                            '<span class="eat-result-type">' + item.type + '</span>' +
                        '</a>';
                    }).join('');
                    
                    $results.html(html).show();
                } else {
                    $results.html('<div class="eat-no-results">No results found</div>').show();
                }
            });
            
            // Hide results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.eat-admin-search').length) {
                    $('.eat-search-results').hide();
                }
            });
        },
        
        /**
         * Add responsive features
         */
        addResponsiveFeatures: function() {
            // Mobile menu toggle
            if ($(window).width() <= 782) {
                this.addMobileMenuToggle();
            }
            
            // Handle window resize
            $(window).on('resize', function() {
                if ($(window).width() <= 782) {
                    EasyAdminTheme.addMobileMenuToggle();
                } else {
                    $('.eat-mobile-menu-toggle').remove();
                }
            });
            
            // Touch gestures for mobile
            if ('ontouchstart' in window) {
                this.addTouchGestures();
            }
        },
        
        /**
         * Add mobile menu toggle
         */
        addMobileMenuToggle: function() {
            if (!$('.eat-mobile-menu-toggle').length) {
                var toggle = $('<button type="button" class="eat-mobile-menu-toggle">' +
                    '<span class="dashicons dashicons-menu"></span>' +
                '</button>');
                
                $('#wpadminbar').append(toggle);
                
                toggle.on('click', function() {
                    $('body').toggleClass('eat-mobile-menu-open');
                    $(this).find('.dashicons').toggleClass('dashicons-menu dashicons-no');
                });
            }
        },
        
        /**
         * Add touch gestures
         */
        addTouchGestures: function() {
            var startX, startY, endX, endY;
            
            $(document).on('touchstart', function(e) {
                startX = e.originalEvent.touches[0].clientX;
                startY = e.originalEvent.touches[0].clientY;
            });
            
            $(document).on('touchend', function(e) {
                endX = e.originalEvent.changedTouches[0].clientX;
                endY = e.originalEvent.changedTouches[0].clientY;
                
                var deltaX = endX - startX;
                var deltaY = endY - startY;
                
                // Swipe right to open menu
                if (deltaX > 50 && Math.abs(deltaY) < 50) {
                    $('body').addClass('eat-mobile-menu-open');
                }
                
                // Swipe left to close menu
                if (deltaX < -50 && Math.abs(deltaY) < 50) {
                    $('body').removeClass('eat-mobile-menu-open');
                }
            });
        }
    };
    
})(jQuery);

// Add CSS for JavaScript enhancements
jQuery(document).ready(function($) {
    var dynamicCSS = `
        <style id="eat-dynamic-css">
        /* Quick Actions Toolbar */
        .eat-quick-toolbar {
            position: fixed;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .eat-quick-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .eat-quick-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        /* Form Enhancements */
        .eat-form-group {
            position: relative;
            margin-bottom: 20px;
        }
        
        .eat-floating-label {
            position: absolute;
            top: 15px;
            left: 15px;
            transition: all 0.3s ease;
            pointer-events: none;
            color: #6c757d;
        }
        
        .eat-floating-label.active {
            top: -8px;
            left: 10px;
            font-size: 12px;
            color: #667eea;
            background: white;
            padding: 0 5px;
        }
        
        .eat-char-counter {
            text-align: right;
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .eat-char-counter.warning {
            color: #dc3545;
        }
        
        /* Loading States */
        .loading {
            position: relative;
            pointer-events: none;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            margin: -8px 0 0 -8px;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .eat-loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .eat-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top-color: #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        /* Notifications */
        #eat-notifications {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            max-width: 300px;
        }
        
        .eat-notification {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            padding: 15px;
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease;
            border-left: 4px solid #667eea;
        }
        
        .eat-notification.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .eat-notification-success {
            border-left-color: #28a745;
        }
        
        .eat-notification-warning {
            border-left-color: #ffc107;
        }
        
        .eat-notification-error {
            border-left-color: #dc3545;
        }
        
        .eat-notification-close {
            float: right;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #6c757d;
        }
        
        /* Modal */
        .eat-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .eat-modal.active {
            opacity: 1;
            visibility: visible;
        }
        
        .eat-modal-content {
            background: white;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            transform: scale(0.7);
            transition: transform 0.3s ease;
        }
        
        .eat-modal.active .eat-modal-content {
            transform: scale(1);
        }
        
        .eat-modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e1e5e9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .eat-modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6c757d;
        }
        
        .eat-modal-body {
            padding: 25px;
        }
        
        /* Keyboard Shortcuts */
        .eat-shortcut-list {
            display: grid;
            gap: 15px;
        }
        
        .eat-shortcut-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e1e5e9;
        }
        
        .eat-shortcut-item kbd {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 4px 8px;
            font-family: monospace;
            font-size: 12px;
        }
        
        /* Dashboard Search */
        .eat-dashboard-search {
            display: flex;
            margin: 20px 0;
            max-width: 400px;
        }
        
        .eat-search-input {
            flex: 1;
            padding: 10px 15px;
            border: 2px solid #e1e5e9;
            border-right: none;
            border-radius: 8px 0 0 8px;
        }
        
        .eat-search-btn {
            background: #667eea;
            color: white;
            border: 2px solid #667eea;
            border-radius: 0 8px 8px 0;
            padding: 10px 15px;
            cursor: pointer;
        }
        
        /* Admin Search */
        .eat-admin-search {
            padding: 15px;
            border-bottom: 1px solid #e1e5e9;
            position: relative;
        }
        
        .eat-admin-search-input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
        }
        
        .eat-search-results {
            position: absolute;
            top: 100%;
            left: 15px;
            right: 15px;
            background: white;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .eat-search-result {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #2c3e50;
            border-bottom: 1px solid #f8f9fa;
        }
        
        .eat-search-result:hover {
            background: #f8f9fa;
        }
        
        .eat-result-type {
            float: right;
            font-size: 12px;
            color: #6c757d;
        }
        
        /* Widget Controls */
        .eat-widget-controls {
            display: flex;
            gap: 5px;
        }
        
        .eat-widget-controls button {
            background: none;
            border: none;
            padding: 5px;
            cursor: pointer;
            color: #6c757d;
            border-radius: 4px;
        }
        
        .eat-widget-controls button:hover {
            background: rgba(0, 0, 0, 0.1);
            color: #2c3e50;
        }
        
        /* Mobile Enhancements */
        .eat-mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            padding: 10px;
            cursor: pointer;
        }
        
        @media (max-width: 782px) {
            .eat-mobile-menu-toggle {
                display: block;
            }
            
            .eat-quick-toolbar {
                right: 10px;
            }
            
            .eat-quick-btn {
                width: 40px;
                height: 40px;
            }
            
            #eat-notifications {
                right: 10px;
                max-width: calc(100% - 20px);
            }
        }
        
        /* Animations */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Accessibility */
        .eat-skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: #667eea;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            z-index: 100000;
        }
        
        .eat-skip-link:focus {
            top: 6px;
        }
        </style>
    `;
    
    $('head').append(dynamicCSS);
});