/**
 * Main JavaScript for DreamTour Theme
 */

(function($) {
    'use strict';
    
    /**
     * Document Ready
     */
    $(document).ready(function() {
        
        // Hero Slider
        initHeroSlider();
        
        // Tour Booking Form
        initTourBookingForm();
        
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
     * Tour Booking Form Initialization
     */
    function initTourBookingForm() {
        const $adultsInput = $('#adults');
        const $childrenInput = $('#children');
        const pricePerPerson = parseFloat(document.querySelector('[data-price]')?.getAttribute('data-price') || 0);
        
        // Get price from the tour-price-box or from hidden data
        let tourPrice = 0;
        const priceText = $('.price-amount').text();
        if (priceText) {
            tourPrice = parseFloat(priceText.replace('€', '').replace('.', '').replace(',', '.')) || 0;
        }
        
        function updatePriceCalculation() {
            const adults = parseInt($adultsInput.val()) || 1;
            const children = parseInt($childrenInput.val()) || 0;
            const subtotal = (adults + children) * tourPrice;
            const deposit = subtotal * 0.5;
            const paymentType = $('input[name="payment-type"]:checked').val();
            const totalAmount = paymentType === 'deposit' ? deposit : subtotal;
            
            $('#subtotal').text('€' + subtotal.toFixed(2).replace('.', ','));
            $('#deposit').text('€' + deposit.toFixed(2).replace('.', ','));
            
            if (paymentType === 'deposit') {
                $('#total-amount').text('€' + deposit.toFixed(2).replace('.', ','));
            } else {
                $('#total-amount').text('€' + subtotal.toFixed(2).replace('.', ','));
            }
        }
        
        // Quantity controls
        $('.qty-plus[data-type="adults"]').on('click', function() {
            $adultsInput.val(parseInt($adultsInput.val()) + 1);
            updatePriceCalculation();
        });
        
        $('.qty-minus[data-type="adults"]').on('click', function() {
            const currentVal = parseInt($adultsInput.val());
            if (currentVal > 1) {
                $adultsInput.val(currentVal - 1);
                updatePriceCalculation();
            }
        });
        
        $('.qty-plus[data-type="children"]').on('click', function() {
            $childrenInput.val(parseInt($childrenInput.val()) + 1);
            updatePriceCalculation();
        });
        
        $('.qty-minus[data-type="children"]').on('click', function() {
            const currentVal = parseInt($childrenInput.val());
            if (currentVal > 0) {
                $childrenInput.val(currentVal - 1);
                updatePriceCalculation();
            }
        });
        
        // Payment type change
        $('input[name="payment-type"]').on('change', function() {
            updatePriceCalculation();
        });
        
        // Book button
        $('#book-btn').on('click', function(e) {
            e.preventDefault();
            const adults = parseInt($adultsInput.val()) || 1;
            const children = parseInt($childrenInput.val()) || 0;
            const paymentType = $('input[name="payment-type"]:checked').val();
            const subtotal = (adults + children) * tourPrice;
            const deposit = subtotal * 0.5;
            const totalAmount = paymentType === 'deposit' ? deposit : subtotal;
            
            // Aquí puedes redirigir al usuario a la página de pago o mostrar un modal
            // Por ahora mostramos un mensaje
            const message = `Adultos: ${adults}, Bambini: ${children}, Totale: €${totalAmount.toFixed(2)}`;
            alert('Continuare a reserva: ' + message);
        });
        
        // Initial calculation
        updatePriceCalculation();
    }
    
    /**
     * Hero Slider Initialization
     */
    function initHeroSlider() {
        const $slideshow = $('#heroSlideshow');
        const $slides = $('.hero-slide');
        const $dots = $('.hero-dot');
        const $prevBtn = $('.hero-prev');
        const $nextBtn = $('.hero-next');
        
        if ($slides.length === 0) return;
        
        let currentSlide = 0;
        let slideInterval;
        const slideDuration = 5000; // Change slide every 5 seconds
        
        /**
         * Show specific slide
         */
        function goToSlide(n) {
            if (n >= $slides.length) {
                currentSlide = 0;
            } else if (n < 0) {
                currentSlide = $slides.length - 1;
            } else {
                currentSlide = n;
            }
            
            $slides.removeClass('active');
            $dots.removeClass('active');
            
            $slides.eq(currentSlide).addClass('active');
            $dots.eq(currentSlide).addClass('active');
        }
        
        /**
         * Next slide
         */
        function nextSlide() {
            goToSlide(currentSlide + 1);
            resetAutoplay();
        }
        
        /**
         * Previous slide
         */
        function prevSlide() {
            goToSlide(currentSlide - 1);
            resetAutoplay();
        }
        
        /**
         * Autoplay slides
         */
        function startAutoplay() {
            slideInterval = setInterval(nextSlide, slideDuration);
        }
        
        /**
         * Reset autoplay on user interaction
         */
        function resetAutoplay() {
            clearInterval(slideInterval);
            startAutoplay();
        }
        
        // Event listeners
        $prevBtn.on('click', prevSlide);
        $nextBtn.on('click', nextSlide);
        
        $dots.on('click', function() {
            const slideIndex = $(this).data('slide');
            goToSlide(slideIndex);
            resetAutoplay();
        });
        
        // Pause autoplay on hover
        $slideshow.on('mouseenter', function() {
            clearInterval(slideInterval);
        });
        
        $slideshow.on('mouseleave', function() {
            startAutoplay();
        });
        
        // Start autoplay
        startAutoplay();
        
        // Keyboard navigation
        $(document).on('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                prevSlide();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
            }
        });
    }
    
    /**
     * Window Load
     */
    $(window).on('load', function() {
        // Remove preloader if exists
        $('.preloader').fadeOut('slow');
    });
    
})(jQuery);
