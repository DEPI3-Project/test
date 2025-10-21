/**
 * Modern Admin Dashboard JavaScript
 * 
 * @package ModernAdmin
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function() {
        initModernAdmin();
    });

    /**
     * Initialize Modern Admin features
     */
    function initModernAdmin() {
        enhanceDashboard();
        improveMenuInteraction();
        addSmoothScrolling();
        enhanceFormElements();
        addKeyboardShortcuts();
        improveAccessibility();
        addLoadingStates();
    }

    /**
     * Enhance dashboard widgets
     */
    function enhanceDashboard() {
        // Add hover effects to dashboard widgets
        $('.postbox').hover(
            function() {
                $(this).addClass('widget-hover');
            },
            function() {
                $(this).removeClass('widget-hover');
            }
        );

        // Add click animation to buttons
        $('.button').on('click', function() {
            $(this).addClass('button-clicked');
            setTimeout(() => {
                $(this).removeClass('button-clicked');
            }, 200);
        });

        // Enhance welcome panel
        $('.welcome-panel').each(function() {
            $(this).addClass('fade-in');
        });
    }

    /**
     * Improve menu interaction
     */
    function improveMenuInteraction() {
        // Add smooth transitions to menu items
        $('#adminmenu .wp-menu-item').hover(
            function() {
                $(this).addClass('menu-item-hover');
            },
            function() {
                $(this).removeClass('menu-item-hover');
            }
        );

        // Add click feedback
        $('#adminmenu .wp-menu-item').on('click', function() {
            $(this).addClass('menu-item-clicked');
            setTimeout(() => {
                $(this).removeClass('menu-item-clicked');
            }, 300);
        });

        // Enhance submenu interactions
        $('#adminmenu .wp-has-submenu').hover(
            function() {
                $(this).find('.wp-submenu').addClass('submenu-hover');
            },
            function() {
                $(this).find('.wp-submenu').removeClass('submenu-hover');
            }
        );
    }

    /**
     * Add smooth scrolling
     */
    function addSmoothScrolling() {
        // Smooth scroll for anchor links
        $('a[href*="#"]').on('click', function(e) {
            var target = $(this.hash);
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 50
                }, 500);
            }
        });
    }

    /**
     * Enhance form elements
     */
    function enhanceFormElements() {
        // Add floating labels effect
        $('input[type="text"], input[type="email"], input[type="password"], textarea').each(function() {
            var $input = $(this);
            var $label = $input.siblings('label');
            
            if ($label.length) {
                $input.on('focus blur', function() {
                    if ($input.val() !== '' || $input.is(':focus')) {
                        $label.addClass('label-floating');
                    } else {
                        $label.removeClass('label-floating');
                    }
                });
            }
        });

        // Add input validation feedback
        $('input[required], textarea[required]').on('blur', function() {
            var $input = $(this);
            if ($input.val() === '') {
                $input.addClass('input-error');
            } else {
                $input.removeClass('input-error');
            }
        });

        // Add success state
        $('input[required], textarea[required]').on('input', function() {
            var $input = $(this);
            if ($input.val() !== '') {
                $input.removeClass('input-error').addClass('input-success');
            }
        });
    }

    /**
     * Add keyboard shortcuts
     */
    function addKeyboardShortcuts() {
        $(document).on('keydown', function(e) {
            // Ctrl/Cmd + K for search
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 75) {
                e.preventDefault();
                $('#adminbar-search-input').focus();
            }

            // Ctrl/Cmd + N for new post
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 78) {
                e.preventDefault();
                window.location.href = ajaxurl.replace('admin-ajax.php', 'post-new.php');
            }

            // Ctrl/Cmd + M for media library
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 77) {
                e.preventDefault();
                window.location.href = ajaxurl.replace('admin-ajax.php', 'upload.php');
            }
        });
    }

    /**
     * Improve accessibility
     */
    function improveAccessibility() {
        // Add ARIA labels to interactive elements
        $('.button').attr('role', 'button');
        $('.postbox .hndle').attr('role', 'button');
        $('.postbox .hndle').attr('aria-expanded', 'true');

        // Add skip links
        if ($('#wpbody').length) {
            $('body').prepend('<a href="#wpbody" class="skip-link screen-reader-text">Skip to main content</a>');
        }

        // Enhance focus management
        $('.button, input, select, textarea').on('focus', function() {
            $(this).addClass('element-focused');
        }).on('blur', function() {
            $(this).removeClass('element-focused');
        });
    }

    /**
     * Add loading states
     */
    function addLoadingStates() {
        // Add loading state to forms
        $('form').on('submit', function() {
            var $form = $(this);
            var $submitBtn = $form.find('input[type="submit"], .button-primary');
            
            if ($submitBtn.length) {
                $submitBtn.addClass('loading');
                $submitBtn.prop('disabled', true);
            }
        });

        // Add loading state to links that might take time
        $('a[href*="action="], a[href*="bulk-action="]').on('click', function() {
            var $link = $(this);
            $link.addClass('loading');
        });
    }

    /**
     * Add custom animations
     */
    function addCustomAnimations() {
        // Fade in animation for new elements
        $('.postbox, .welcome-panel').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
        });

        // Slide in animation for menu items
        $('#adminmenu .wp-menu-item').each(function(index) {
            $(this).css('animation-delay', (index * 0.05) + 's');
        });
    }

    /**
     * Add tooltips
     */
    function addTooltips() {
        // Add tooltips to buttons without titles
        $('.button:not([title])').each(function() {
            var $btn = $(this);
            var text = $btn.text().trim();
            if (text) {
                $btn.attr('title', text);
            }
        });

        // Add tooltips to menu items
        $('#adminmenu .wp-menu-item').each(function() {
            var $item = $(this);
            var text = $item.find('.wp-menu-name').text().trim();
            if (text) {
                $item.attr('title', text);
            }
        });
    }

    /**
     * Add responsive enhancements
     */
    function addResponsiveEnhancements() {
        // Handle window resize
        $(window).on('resize', function() {
            // Adjust dashboard layout for mobile
            if ($(window).width() < 782) {
                $('.postbox').addClass('mobile-layout');
            } else {
                $('.postbox').removeClass('mobile-layout');
            }
        });

        // Trigger resize on load
        $(window).trigger('resize');
    }

    /**
     * Add performance optimizations
     */
    function addPerformanceOptimizations() {
        // Lazy load images
        $('img[data-src]').each(function() {
            var $img = $(this);
            var src = $img.data('src');
            if (src) {
                $img.attr('src', src);
            }
        });

        // Debounce resize events
        var resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Resize handling code here
            }, 250);
        });
    }

    /**
     * Add error handling
     */
    function addErrorHandling() {
        // Global error handler
        window.addEventListener('error', function(e) {
            console.error('Modern Admin Error:', e.error);
        });

        // AJAX error handling
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            console.error('AJAX Error:', thrownError);
        });
    }

    /**
     * Add theme customization
     */
    function addThemeCustomization() {
        // Allow users to toggle dark mode (if implemented)
        if (localStorage.getItem('modern-admin-dark-mode') === 'true') {
            $('body').addClass('dark-mode');
        }

        // Add theme toggle button
        if ($('#wpadminbar').length) {
            var darkModeToggle = '<li id="modern-admin-theme-toggle" class="modern-admin-theme-toggle">' +
                '<a href="#" class="ab-item" title="Toggle Dark Mode">' +
                '<span class="dashicons dashicons-admin-appearance"></span>' +
                '</a>' +
                '</li>';
            $('#wpadminbar .ab-top-menu').append(darkModeToggle);

            // Handle theme toggle
            $(document).on('click', '#modern-admin-theme-toggle a', function(e) {
                e.preventDefault();
                $('body').toggleClass('dark-mode');
                var isDark = $('body').hasClass('dark-mode');
                localStorage.setItem('modern-admin-dark-mode', isDark);
            });
        }
    }

    // Initialize additional features
    addCustomAnimations();
    addTooltips();
    addResponsiveEnhancements();
    addPerformanceOptimizations();
    addErrorHandling();
    addThemeCustomization();

})(jQuery);

/**
 * Add CSS for JavaScript enhancements
 */
jQuery(document).ready(function($) {
    var customCSS = `
        <style>
        /* Button click animation */
        .button-clicked {
            transform: scale(0.95);
            transition: transform 0.1s ease;
        }

        /* Widget hover effect */
        .widget-hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Menu item hover */
        .menu-item-hover {
            background: rgba(102, 126, 234, 0.1);
        }

        /* Menu item click */
        .menu-item-clicked {
            background: rgba(102, 126, 234, 0.2);
        }

        /* Submenu hover */
        .submenu-hover {
            background: rgba(102, 126, 234, 0.05);
        }

        /* Input states */
        .input-error {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1) !important;
        }

        .input-success {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1) !important;
        }

        /* Loading states */
        .loading {
            position: relative;
            color: transparent !important;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            margin: -8px 0 0 -8px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Skip link */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            z-index: 999999;
            color: #fff;
            background: #000;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 0 0 4px 4px;
        }

        .skip-link:focus {
            top: 0;
        }

        /* Focus states */
        .element-focused {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }

        /* Mobile layout */
        .mobile-layout {
            margin-bottom: 15px;
        }

        /* Dark mode toggle */
        .modern-admin-theme-toggle .ab-item {
            color: #fff !important;
        }

        .modern-admin-theme-toggle .ab-item:hover {
            background: rgba(255,255,255,0.1) !important;
        }

        /* Fade in animation */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive improvements */
        @media (max-width: 782px) {
            .widget-hover {
                transform: none;
            }
        }
        </style>
    `;
    
    $('head').append(customCSS);
});