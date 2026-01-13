<?php
/**
 * Template Name: Página de Inicio
 * 
 * @package DreamTour
 */

get_header();
?>

<!-- Hero Section -->
<section class="hero-section">
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
</section>

<?php
get_footer();
