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
        
        // Language Switcher Toggle
        $('.language-toggle').on('click', function(e) {
            e.stopPropagation();
            $('.language-switcher').toggleClass('active');
        });
        
        // Close language dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.language-switcher').length) {
                $('.language-switcher').removeClass('active');
            }
        });
        
        // Close language dropdown on ESC
        $(document).on('keyup', function(e) {
            if (e.key === 'Escape') {
                $('.language-switcher').removeClass('active');
            }
        });
        
        // Tour Filters
        function filterTours() {
            const destination = $('#filter-destination').val();
            const transport = $('#filter-transport').val();
            const duration = $('#filter-duration').val();
            
            $('.tour-card').each(function() {
                const $card = $(this);
                let show = true;
                
                // Filter by destination
                if (destination && $card.data('destination') !== destination) {
                    show = false;
                }
                
                // Filter by transport
                if (transport && $card.data('transport') !== transport) {
                    show = false;
                }
                
                // Filter by duration
                if (duration) {
                    const cardDuration = parseInt($card.data('duration'));
                    if (duration === '1-3' && (cardDuration < 1 || cardDuration > 3)) {
                        show = false;
                    } else if (duration === '4-7' && (cardDuration < 4 || cardDuration > 7)) {
                        show = false;
                    } else if (duration === '8-14' && (cardDuration < 8 || cardDuration > 14)) {
                        show = false;
                    } else if (duration === '15+' && cardDuration < 15) {
                        show = false;
                    }
                }
                
                if (show) {
                    $card.fadeIn(300);
                } else {
                    $card.fadeOut(300);
                }
            });
        }
        
        $('.filter-select').on('change', filterTours);
        
        $('#filter-reset').on('click', function(e) {
            e.preventDefault();
            $('.filter-select').val('');
            $('.tour-card').fadeIn(300);
        });
        
    });
    
    /**
     * Window Load
     */
    $(window).on('load', function() {
        // Remove preloader if exists
        $('.preloader').fadeOut('slow');
    });
    
})(jQuery);
