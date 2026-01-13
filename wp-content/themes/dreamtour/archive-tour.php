<?php
/**
 * Template para archivo de tours
 * 
 * @package DreamTour
 */

get_header();
?>

<!-- Hero Section -->
<section class="hero-section" style="min-height: 350px;">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                <?php esc_html_e('Nuestros Tours', 'dreamtour'); ?>
            </h1>
            <p class="hero-subtitle">
                <?php esc_html_e('Explora destinos increíbles alrededor del mundo', 'dreamtour'); ?>
            </p>
        </div>
    </div>
</section>

<section class="content-section">
    <div class="container">
        
        <?php if (have_posts()) : ?>
            
            <div class="tours-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', 'tour-card');
                endwhile;
                ?>
            </div>
            
            <?php
            // Paginación
            dreamtour_pagination();
            
        else :
            ?>
            <div class="no-content text-center">
                <h2><?php esc_html_e('No se encontraron tours', 'dreamtour'); ?></h2>
                <p><?php esc_html_e('Vuelve pronto para descubrir nuevos destinos.', 'dreamtour'); ?></p>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<?php
get_footer();
