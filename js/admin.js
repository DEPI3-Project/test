/**
 * Modern Admin Pro - Admin JavaScript
 * 
 * @package ModernAdminPro
 * @version 1.0.0
 */

(function($) {
    'use strict';
    
    // Initialize when document is ready
    $(document).ready(function() {
        initModernAdmin();
    });
    
    /**
     * Initialize Modern Admin functionality
     */
    function initModernAdmin() {
        initMobileMenu();
        initSmoothScrolling();
        initFormEnhancements();
        initDashboardWidgets();
        initCustomizer();
        initNotifications();
        initKeyboardShortcuts();
    }
    
    /**
     * Initialize mobile menu functionality
     */
    function initMobileMenu() {
        // Create mobile menu toggle
        if ($(window).width() <= 782) {
            if (!$('#mobile-menu-toggle').length) {
                $('body').prepend('<button id="mobile-menu-toggle" class="mobile-menu-toggle"><span class="dashicons dashicons-menu"></span></button>');
            }
            
            // Toggle mobile menu
            $(document).on('click', '#mobile-menu-toggle', function(e) {
                e.preventDefault();
                $('#adminmenu').toggleClass('mobile-open');
                $(this).toggleClass('active');
            });
            
            // Close mobile menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#adminmenu, #mobile-menu-toggle').length) {
                    $('#adminmenu').removeClass('mobile-open');
                    $('#mobile-menu-toggle').removeClass('active');
                }
            });
        }
        
        // Handle window resize
        $(window).on('resize', function() {
            if ($(window).width() > 782) {
                $('#adminmenu').removeClass('mobile-open');
                $('#mobile-menu-toggle').removeClass('active');
            }
        });
    }
    
    /**
     * Initialize smooth scrolling
     */
    function initSmoothScrolling() {
        $('a[href*="#"]').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 500);
            }
        });
    }
    
    /**
     * Initialize form enhancements
     */
    function initFormEnhancements() {
        // Add loading states to forms
        $('form').on('submit', function() {
            var $form = $(this);
            var $submitBtn = $form.find('input[type="submit"], button[type="submit"]');
            
            if ($submitBtn.length && !$submitBtn.hasClass('loading')) {
                $submitBtn.addClass('loading').prop('disabled', true);
                
                // Remove loading state after 3 seconds (fallback)
                setTimeout(function() {
                    $submitBtn.removeClass('loading').prop('disabled', false);
                }, 3000);
            }
        });
        
        // Enhanced form validation
        $('input[required], textarea[required], select[required]').on('blur', function() {
            validateField($(this));
        });
        
        // Real-time validation
        $('input[type="email"]').on('input', function() {
            var email = $(this).val();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                $(this).addClass('error');
                showFieldError($(this), 'Please enter a valid email address.');
            } else {
                $(this).removeClass('error');
                hideFieldError($(this));
            }
        });
    }
    
    /**
     * Initialize dashboard widgets
     */
    function initDashboardWidgets() {
        // Make dashboard widgets sortable
        if ($('.meta-box-sortables').length) {
            $('.meta-box-sortables').sortable({
                connectWith: '.meta-box-sortables',
                handle: '.hndle',
                cursor: 'move',
                placeholder: 'sortable-placeholder',
                forcePlaceholderSize: true,
                tolerance: 'pointer',
                start: function(event, ui) {
                    ui.placeholder.height(ui.item.height());
                }
            });
        }
        
        // Add collapse/expand functionality to widgets
        $('.postbox .hndle').on('click', function() {
            var $widget = $(this).closest('.postbox');
            $widget.toggleClass('closed');
        });
    }
    
    /**
     * Initialize customizer
     */
    function initCustomizer() {
        // Live preview for custom CSS
        $('#modern_admin_custom_css').on('input', function() {
            var customCSS = $(this).val();
            updateCustomCSS(customCSS);
        });
        
        // Color scheme changer
        $('select[name="modern_admin_color_scheme"]').on('change', function() {
            var colorScheme = $(this).val();
            applyColorScheme(colorScheme);
        });
        
        // Logo uploader
        $('#upload-logo').on('click', function(e) {
            e.preventDefault();
            openMediaUploader();
        });
    }
    
    /**
     * Initialize notifications
     */
    function initNotifications() {
        // Auto-dismiss notices after 5 seconds
        $('.notice.is-dismissible').each(function() {
            var $notice = $(this);
            setTimeout(function() {
                $notice.fadeOut();
            }, 5000);
        });
        
        // Manual dismiss
        $(document).on('click', '.notice-dismiss', function() {
            $(this).closest('.notice').fadeOut();
        });
    }
    
    /**
     * Initialize keyboard shortcuts
     */
    function initKeyboardShortcuts() {
        $(document).on('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 83) {
                e.preventDefault();
                $('form').first().submit();
            }
            
            // Ctrl/Cmd + N for new post
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 78) {
                e.preventDefault();
                window.location.href = ajaxurl.replace('admin-ajax.php', 'post-new.php');
            }
            
            // Escape to close modals/menus
            if (e.keyCode === 27) {
                $('#adminmenu').removeClass('mobile-open');
                $('#mobile-menu-toggle').removeClass('active');
            }
        });
    }
    
    /**
     * Validate form field
     */
    function validateField($field) {
        var value = $field.val();
        var isValid = true;
        var errorMessage = '';
        
        if ($field.prop('required') && !value.trim()) {
            isValid = false;
            errorMessage = 'This field is required.';
        }
        
        if (isValid) {
            $field.removeClass('error');
            hideFieldError($field);
        } else {
            $field.addClass('error');
            showFieldError($field, errorMessage);
        }
        
        return isValid;
    }
    
    /**
     * Show field error
     */
    function showFieldError($field, message) {
        hideFieldError($field);
        $field.after('<div class="field-error">' + message + '</div>');
    }
    
    /**
     * Hide field error
     */
    function hideFieldError($field) {
        $field.siblings('.field-error').remove();
    }
    
    /**
     * Update custom CSS
     */
    function updateCustomCSS(css) {
        var $existingStyle = $('#modern-admin-custom-css');
        
        if ($existingStyle.length) {
            $existingStyle.text(css);
        } else {
            $('head').append('<style id="modern-admin-custom-css" type="text/css">' + css + '</style>');
        }
    }
    
    /**
     * Apply color scheme
     */
    function applyColorScheme(scheme) {
        $('body').removeClass('color-scheme-default color-scheme-blue color-scheme-green color-scheme-purple');
        $('body').addClass('color-scheme-' + scheme);
        
        // Save preference
        $.post(ajaxurl, {
            action: 'modern_admin_save_preference',
            preference: 'color_scheme',
            value: scheme,
            nonce: modernAdmin.nonce
        });
    }
    
    /**
     * Open media uploader
     */
    function openMediaUploader() {
        if (typeof wp !== 'undefined' && wp.media) {
            var frame = wp.media({
                title: 'Select Logo',
                button: {
                    text: 'Use as Logo'
                },
                multiple: false
            });
            
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('input[name="modern_admin_logo"]').val(attachment.url);
            });
            
            frame.open();
        }
    }
    
    /**
     * Show loading state
     */
    function showLoading($element) {
        $element.addClass('loading');
        if ($element.is('button, input[type="submit"]')) {
            $element.prop('disabled', true);
        }
    }
    
    /**
     * Hide loading state
     */
    function hideLoading($element) {
        $element.removeClass('loading');
        if ($element.is('button, input[type="submit"]')) {
            $element.prop('disabled', false);
        }
    }
    
    /**
     * Show notification
     */
    function showNotification(message, type) {
        type = type || 'info';
        
        var $notification = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
        
        $('.wrap h1').after($notification);
        
        setTimeout(function() {
            $notification.fadeOut();
        }, 5000);
    }
    
    /**
     * AJAX helper
     */
    function ajaxRequest(action, data, callback) {
        data = data || {};
        data.action = action;
        data.nonce = modernAdmin.nonce;
        
        $.post(modernAdmin.ajaxUrl, data)
            .done(function(response) {
                if (response.success) {
                    callback(null, response.data);
                } else {
                    callback(response.data, null);
                }
            })
            .fail(function() {
                callback('Request failed', null);
            });
    }
    
    // Add custom styles for mobile menu toggle
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .mobile-menu-toggle {
                display: none;
                position: fixed;
                top: 50px;
                left: 10px;
                z-index: 10000;
                background: #667eea;
                color: white;
                border: none;
                padding: 10px;
                border-radius: 5px;
                cursor: pointer;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            }
            
            .mobile-menu-toggle:hover {
                background: #5a67d8;
            }
            
            .mobile-menu-toggle.active {
                background: #4c51bf;
            }
            
            @media (max-width: 782px) {
                .mobile-menu-toggle {
                    display: block;
                }
            }
            
            .field-error {
                color: #ef4444;
                font-size: 12px;
                margin-top: 5px;
                display: block;
            }
            
            .error {
                border-color: #ef4444 !important;
                box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
            }
            
            .sortable-placeholder {
                background: #f1f5f9;
                border: 2px dashed #cbd5e1;
                margin-bottom: 20px;
                border-radius: 8px;
            }
            
            .loading {
                position: relative;
                color: transparent !important;
            }
            
            .loading:after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 20px;
                height: 20px;
                margin: -10px 0 0 -10px;
                border: 2px solid #f3f3f3;
                border-top: 2px solid #667eea;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
        `)
        .appendTo('head');
    
})(jQuery);