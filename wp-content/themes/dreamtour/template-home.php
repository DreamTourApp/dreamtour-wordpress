<?php
/**
 * Template Name: Página de Inicio
 * 
 * @package DreamTour
 */

get_header();
?>

<!-- Hero Section with Slider -->
<section class="hero-section">
    <!-- Hero Slideshow -->
    <div class="hero-slideshow" id="heroSlideshow">
        <?php
        // Get featured tours for hero slider
        $slider_args = array(
            'post_type'      => 'drtr_tour',
            'posts_per_page' => 5,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        
        $slider_query = new WP_Query($slider_args);
        
        if ($slider_query->have_posts()) {
            $slide_count = 0;
            while ($slider_query->have_posts()) {
                $slider_query->the_post();
                $slide_count++;
                $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'dreamtour-hero');
                $background_style = $featured_image ? 'style="background-image: url(\'' . esc_url($featured_image) . '\');"' : '';
                ?>
                <div class="hero-slide <?php echo $slide_count === 1 ? 'active' : ''; ?>" <?php echo wp_kses_post($background_style); ?>>
                    <div class="hero-overlay"></div>
                </div>
                <?php
            }
        } else {
            // Fallback slide if no tours
            ?>
            <div class="hero-slide active" style="background-color: var(--color-primary);">
                <div class="hero-overlay"></div>
            </div>
            <?php
        }
        wp_reset_postdata();
        ?>
    </div>
    
    <!-- Hero Content -->
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                <?php esc_html_e('Viaja. Descubre. Conecta.', 'dreamtour'); ?>
            </h1>
            <p class="hero-subtitle">
                <?php esc_html_e('Únete a miles de viajeros en experiencias únicas por todo el mundo', 'dreamtour'); ?>
            </p>
            <div class="hero-cta">
                <a href="<?php echo esc_url(home_url('/tours')); ?>" class="btn btn-primary">
                    <?php esc_html_e('Explorar Tours', 'dreamtour'); ?>
                </a>
                <a href="#features" class="btn btn-outline">
                    <?php esc_html_e('Saber Más', 'dreamtour'); ?>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Slideshow Controls -->
    <div class="hero-slideshow-controls">
        <button class="hero-prev" aria-label="<?php esc_attr_e('Diapositiva anterior', 'dreamtour'); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <button class="hero-next" aria-label="<?php esc_attr_e('Siguiente diapositiva', 'dreamtour'); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>
    </div>
    
    <!-- Slideshow Dots -->
    <div class="hero-slideshow-dots" id="heroDots">
        <?php
        if ($slider_query->have_posts()) {
            for ($i = 0; $i < $slide_count; $i++) {
                $active = $i === 0 ? 'active' : '';
                echo '<button class="hero-dot ' . esc_attr($active) . '" data-slide="' . esc_attr($i) . '" aria-label="' . sprintf(esc_attr__('Ir a la diapositiva %d', 'dreamtour'), $i + 1) . '"></button>';
            }
        }
        ?>
    </div>
</section>

<?php
get_footer();
