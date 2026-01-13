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
        
        mobileMenuToggle.on('click', function() {
            var expanded = $(this).attr('aria-expanded') === 'true' || false;
            
            $(this).attr('aria-expanded', !expanded);
            mobileMenu.toggleClass('active');
            $('body').toggleClass('menu-open');
        });
        
        // Close menu on ESC
        $(document).on('keyup', function(e) {
            if (e.key === 'Escape' && mobileMenu.hasClass('active')) {
                mobileMenuToggle.trigger('click');
            }
        });
        
        // Close menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.main-navigation').length && mobileMenu.hasClass('active')) {
                mobileMenuToggle.trigger('click');
            }
        });
        
        // Prevent closing when clicking inside menu
        mobileMenu.on('click', function(e) {
            e.stopPropagation();
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
