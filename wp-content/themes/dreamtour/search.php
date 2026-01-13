<?php
/**
 * Template para mostrar resultados de búsqueda
 * 
 * @package DreamTour
 */

get_header();
?>

<section class="content-section">
    <div class="container">
        
        <header class="page-header">
            <h1 class="page-title">
                <?php
                printf(
                    esc_html__('Resultados de búsqueda para: %s', 'dreamtour'),
                    '<span>' . get_search_query() . '</span>'
                );
                ?>
            </h1>
        </header>
        
        <?php if (have_posts()) : ?>
            
            <div class="tours-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    
                    if (get_post_type() === 'tour') {
                        get_template_part('template-parts/content', 'tour-card');
                    } else {
                        get_template_part('template-parts/content', 'card');
                    }
                    
                endwhile;
                ?>
            </div>
            
            <?php dreamtour_pagination(); ?>
            
        else :
            ?>
            <div class="no-content text-center">
                <h2><?php esc_html_e('No se encontraron resultados', 'dreamtour'); ?></h2>
                <p><?php esc_html_e('Intenta con otra búsqueda.', 'dreamtour'); ?></p>
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<?php
get_footer();
