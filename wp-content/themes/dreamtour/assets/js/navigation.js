/**
 * Navigation JavaScript for DreamTour Theme
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        /**
         * Accessible Menu
         */
        var menu = $('.nav-menu');
        
        // Keyboard Navigation
        menu.find('a').on('focus blur', function() {
            $(this).parents('li').toggleClass('focus');
        });
        
        // Sub-menu Toggle for Touch Devices
        if ('ontouchstart' in window) {
            menu.find('.menu-item-has-children > a').on('touchstart', function(e) {
                var el = $(this).parent('li');
                
                if (!el.hasClass('focus')) {
                    e.preventDefault();
                    el.toggleClass('focus');
                    el.siblings('.focus').removeClass('focus');
                }
            });
        }
        
        /**
         * Mobile Menu
         */
        var mobileMenuToggle = $('.menu-toggle');
        var mobileMenu = $('.nav-menu');
        var mobileOverlay = $('.mobile-menu-overlay');
        
        // Create close button
        if (!mobileMenu.find('.mobile-menu-close').length) {
            mobileMenu.prepend('<button class="mobile-menu-close" aria-label="Chiudi menu"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>');
        }
        
        // Open menu
        mobileMenuToggle.on('click', function() {
            var expanded = $(this).attr('aria-expanded') === 'true' || false;
            
            $(this).attr('aria-expanded', !expanded);
            mobileMenu.toggleClass('active');
            mobileOverlay.toggleClass('active');
            $('body').toggleClass('menu-open');
        });
        
        // Close menu with close button
        $(document).on('click', '.mobile-menu-close', function() {
            mobileMenuToggle.trigger('click');
        });
        
        // Close menu on ESC
        $(document).on('keyup', function(e) {
            if (e.key === 'Escape' && mobileMenu.hasClass('active')) {
                mobileMenuToggle.trigger('click');
            }
        });
        
        // Close menu when clicking overlay
        mobileOverlay.on('click', function() {
            if (mobileMenu.hasClass('active')) {
                mobileMenuToggle.trigger('click');
            }
        });
        
        /**
         * Dropdown Menus
         */
        $('.menu-item-has-children').each(function() {
            $(this).append('<button class="submenu-toggle" aria-expanded="false"><span class="screen-reader-text">Expandir</span></button>');
        });
        
        $('.submenu-toggle').on('click', function(e) {
            e.preventDefault();
            
            var parent = $(this).parent();
            var submenu = parent.find('> .sub-menu');
            var expanded = $(this).attr('aria-expanded') === 'true' || false;
            
            $(this).attr('aria-expanded', !expanded);
            parent.toggleClass('open');
            submenu.slideToggle(200);
        });
        
    });
    
})(jQuery);
