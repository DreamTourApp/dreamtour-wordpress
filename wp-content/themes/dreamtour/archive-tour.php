<?php
/**
 * Template para archivo de tours
 * 
 * @package DreamTour
 */

get_header();
?>

<section class="content-section">
    <div class="container">
        
        <!-- Tour Filters -->
        <div class="tour-filters">
            <?php 
            // Include travel intent split filters (Intenciones + Meses)
            if (function_exists('drtr_render_split_intents_filters')) {
                drtr_render_split_intents_filters();
            }
            ?>
            
            <div class="filter-group">
                <label for="filter-transport"><?php esc_html_e('Transporte', 'dreamtour'); ?></label>
                <select id="filter-transport" class="filter-select">
                    <option value=""><?php esc_html_e('Todos', 'dreamtour'); ?></option>
                    <option value="bus"><?php esc_html_e('Bus', 'dreamtour'); ?></option>
                    <option value="avion"><?php esc_html_e('Avión', 'dreamtour'); ?></option>
                    <option value="tren"><?php esc_html_e('Tren', 'dreamtour'); ?></option>
                    <option value="barco"><?php esc_html_e('Barco', 'dreamtour'); ?></option>
                    <option value="mixto"><?php esc_html_e('Mixto', 'dreamtour'); ?></option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="filter-duration"><?php esc_html_e('Duración', 'dreamtour'); ?></label>
                <select id="filter-duration" class="filter-select">
                    <option value=""><?php esc_html_e('Todas', 'dreamtour'); ?></option>
                    <option value="1-3"><?php esc_html_e('1-3 días', 'dreamtour'); ?></option>
                    <option value="4-7"><?php esc_html_e('4-7 días', 'dreamtour'); ?></option>
                    <option value="8-14"><?php esc_html_e('8-14 días', 'dreamtour'); ?></option>
                    <option value="15+"><?php esc_html_e('15+ días', 'dreamtour'); ?></option>
                </select>
            </div>
            
            <div class="filter-group">
                <button id="filter-reset" class="btn btn-outline btn-sm"><?php esc_html_e('Limpiar', 'dreamtour'); ?></button>
            </div>
        </div>
        
        <?php
        // Query per mostrare sia tours che drtr_tour
        $tour_args = array(
            'post_type'      => array('tour', 'drtr_tour'),
            'posts_per_page' => 12,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
            'paged'          => get_query_var('paged') ? get_query_var('paged') : 1,
        );
        
        $tour_query = new WP_Query($tour_args);
        
        if ($tour_query->have_posts()) : ?>
            
            <div class="tours-grid" id="tours-container">
                <?php
                while ($tour_query->have_posts()) :
                    $tour_query->the_post();
                    // Usa il template corretto in base al post type
                    if (get_post_type() === 'drtr_tour') {
                        get_template_part('template-parts/content', 'drtr-tour-card');
                    } else {
                        get_template_part('template-parts/content', 'tour-card');
                    }
                endwhile;
                ?>
            </div>
            
            <?php
            // Paginación
            dreamtour_pagination($tour_query);
            wp_reset_postdata();
            
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
