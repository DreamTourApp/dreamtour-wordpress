/**
 * Main JavaScript for DreamTour Theme
 */

(function($) {
    'use strict';
    
    /**
     * Document Ready
     */
    $(document).ready(function() {
        
        // Mobile Menu Toggle
        $('.menu-toggle').on('click', function() {
            $(this).toggleClass('active');
            $('.nav-menu').toggleClass('active');
        });
        
        // Search Toggle
        $('.search-toggle').on('click', function() {
            $('.search-overlay').addClass('active');
            $('.search-overlay input[type="search"]').focus();
        });
        
        $('.search-close').on('click', function() {
            $('.search-overlay').removeClass('active');
        });
        
        // Close search on ESC key
        $(document).on('keyup', function(e) {
            if (e.key === 'Escape') {
                $('.search-overlay').removeClass('active');
            }
        });
        
        // Close search on overlay click
        $('.search-overlay').on('click', function(e) {
            if ($(e.target).is('.search-overlay')) {
                $(this).removeClass('active');
            }
        });
        
        // Smooth Scroll for Anchor Links
        $('a[href*="#"]').not('[href="#"]').not('[href="#0"]').on('click', function(e) {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && 
                location.hostname === this.hostname) {
                
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                
                if (target.length) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 800);
                }
            }
        });
        
        // Sticky Header on Scroll
        var header = $('.site-header');
        var headerHeight = header.outerHeight();
        
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > headerHeight) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }
        });
        
        // Add animation on scroll for tour cards
        function animateOnScroll() {
            $('.tour-card, .feature-item').each(function() {
                var elementTop = $(this).offset().top;
                var windowBottom = $(window).scrollTop() + $(window).height();
                
                if (elementTop < windowBottom - 50) {
                    $(this).addClass('visible');
                }
            });
        }
        
        $(window).on('scroll', animateOnScroll);
        animateOnScroll(); // Run on load
        
        // Form Validation
        $('form').on('submit', function(e) {
            var form = $(this);
            var valid = true;
            
            form.find('input[required], textarea[required]').each(function() {
                if ($(this).val() === '') {
                    valid = false;
                    $(this).addClass('error');
                } else {
                    $(this).removeClass('error');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Por favor, completa todos los campos requeridos.');
            }
        });
        
        // Tour Card Hover Effect
        $('.tour-card').hover(
            function() {
                $(this).find('.tour-card-image img').css('transform', 'scale(1.05)');
            },
            function() {
                $(this).find('.tour-card-image img').css('transform', 'scale(1)');
            }
        );
        
    });
    
    /**
     * Window Load
     */
    $(window).on('load', function() {
        // Remove preloader if exists
        $('.preloader').fadeOut('slow');
    });
    
})(jQuery);
