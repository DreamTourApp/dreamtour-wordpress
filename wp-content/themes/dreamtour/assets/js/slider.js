/**
 * DreamTour Hero Slider
 * 
 * @package DreamTour
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Inizializza lo slider hero quando il DOM è pronto
     */
    function initHeroSlider() {
        const sliderElement = document.querySelector('.hero-slider');
        
        if (!sliderElement) {
            return;
        }

        // Inizializza Swiper
        const heroSwiper = new Swiper('.hero-slider', {
            // Parametri di base
            loop: true,
            speed: 1000,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            
            // Navigazione
            navigation: {
                nextEl: '.hero-slider-button-next',
                prevEl: '.hero-slider-button-prev',
            },
            
            // Paginazione
            pagination: {
                el: '.hero-slider-pagination',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '"></span>';
                },
            },
            
            // Effetti di parallasse
            parallax: true,
            
            // Keyboard navigation
            keyboard: {
                enabled: true,
                onlyInViewport: true,
            },
            
            // Accessibilità
            a11y: {
                prevSlideMessage: 'Diapositiva precedente',
                nextSlideMessage: 'Prossima diapositiva',
                firstSlideMessage: 'Questa è la prima diapositiva',
                lastSlideMessage: 'Questa è l\'ultima diapositiva',
                paginationBulletMessage: 'Vai alla diapositiva {{index}}',
            },
            
            // Eventi
            on: {
                init: function() {
                    // Animazione iniziale
                    animateSlideContent(this.slides[this.activeIndex]);
                },
                slideChange: function() {
                    // Animazione quando cambia slide
                    animateSlideContent(this.slides[this.activeIndex]);
                },
            },
        });

        // Pausa autoplay al focus (accessibilità)
        sliderElement.addEventListener('focusin', function() {
            heroSwiper.autoplay.stop();
        });

        sliderElement.addEventListener('focusout', function() {
            heroSwiper.autoplay.start();
        });
    }

    /**
     * Anima il contenuto della slide
     */
    function animateSlideContent(slide) {
        if (!slide) return;

        const title = slide.querySelector('.hero-slide-title');
        const subtitle = slide.querySelector('.hero-slide-subtitle');
        const cta = slide.querySelector('.hero-slide-cta');

        // Reset animazioni
        [title, subtitle, cta].forEach(el => {
            if (el) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
            }
        });

        // Anima elementi in sequenza
        setTimeout(() => {
            if (title) {
                title.style.transition = 'all 0.8s ease 0.2s';
                title.style.opacity = '1';
                title.style.transform = 'translateY(0)';
            }
        }, 100);

        setTimeout(() => {
            if (subtitle) {
                subtitle.style.transition = 'all 0.8s ease 0.3s';
                subtitle.style.opacity = '1';
                subtitle.style.transform = 'translateY(0)';
            }
        }, 200);

        setTimeout(() => {
            if (cta) {
                cta.style.transition = 'all 0.8s ease 0.4s';
                cta.style.opacity = '1';
                cta.style.transform = 'translateY(0)';
            }
        }, 300);
    }

    /**
     * Inizializza quando il documento è pronto
     */
    $(document).ready(function() {
        initHeroSlider();
    });

})(jQuery);
