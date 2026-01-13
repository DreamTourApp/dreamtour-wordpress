<?php
/**
 * Template para páginas 404
 * 
 * @package DreamTour
 */

get_header();
?>

<section class="content-section">
    <div class="container">
        <div class="error-404 text-center">
            <h1 style="font-size: 120px; font-weight: 900; color: var(--color-primary); margin-bottom: var(--spacing-md);">
                404
            </h1>
            <h2 style="margin-bottom: var(--spacing-md);">
                <?php esc_html_e('Página no encontrada', 'dreamtour'); ?>
            </h2>
            <p class="description" style="font-size: 18px; margin-bottom: var(--spacing-xl);">
                <?php esc_html_e('La página que buscas no existe o ha sido movida.', 'dreamtour'); ?>
            </p>
            
            <div style="margin-bottom: var(--spacing-xxl);">
                <?php get_search_form(); ?>
            </div>
            
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                <?php esc_html_e('Volver al Inicio', 'dreamtour'); ?>
            </a>
        </div>
    </div>
</section>

<?php
get_footer();
