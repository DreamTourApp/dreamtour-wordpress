<?php
/**
 * Template principal - Index
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
                <?php esc_html_e('Descubre el Mundo con DreamTour', 'dreamtour'); ?>
            </h1>
            <p class="hero-subtitle">
                <?php esc_html_e('Viajes en grupo, experiencias únicas. Únete a nuestra comunidad de viajeros.', 'dreamtour'); ?>
            </p>
            <div class="hero-cta">
                <a href="<?php echo esc_url(home_url('/tours')); ?>" class="btn btn-primary">
                    <?php esc_html_e('Explorar Tours', 'dreamtour'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/sobre-nosotros')); ?>" class="btn btn-outline">
                    <?php esc_html_e('Conocer Más', 'dreamtour'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Tours Section -->
<section class="content-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php esc_html_e('Tours Destacados', 'dreamtour'); ?></h2>
            <p class="section-subtitle"><?php esc_html_e('Descubre nuestros viajes más populares', 'dreamtour'); ?></p>
        </div>
        
        <div class="tours-grid">
            <?php
            // Query para obtener tours
            $tour_args = array(
                'post_type'      => 'tour',
                'posts_per_page' => 6,
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            
            $tour_query = new WP_Query($tour_args);
            
            if ($tour_query->have_posts()) :
                while ($tour_query->have_posts()) : $tour_query->the_post();
                    get_template_part('template-parts/content', 'tour-card');
                endwhile;
                wp_reset_postdata();
            else :
                // Si no hay tours, mostrar posts del blog
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        get_template_part('template-parts/content', 'card');
                    endwhile;
                else :
                    ?>
                    <div class="no-content">
                        <p><?php esc_html_e('No hay contenido disponible.', 'dreamtour'); ?></p>
                    </div>
                    <?php
                endif;
            endif;
            ?>
        </div>
        
        <?php if ($tour_query->have_posts()) : ?>
            <div class="text-center mt-xl">
                <a href="<?php echo esc_url(home_url('/tours')); ?>" class="btn btn-secondary">
                    <?php esc_html_e('Ver Todos los Tours', 'dreamtour'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Features Section -->
<section class="content-section bg-light">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php esc_html_e('¿Por qué viajar con DreamTour?', 'dreamtour'); ?></h2>
            <p class="section-subtitle"><?php esc_html_e('Experiencias únicas en cada destino', 'dreamtour'); ?></p>
        </div>
        
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h3 class="feature-title"><?php esc_html_e('Una Comunidad', 'dreamtour'); ?></h3>
                <p class="feature-description">
                    <?php esc_html_e('Conoce nuevos amigos viajando en pequeños grupos con personas como tú, siempre acompañados por un Coordinador.', 'dreamtour'); ?>
                </p>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                    </svg>
                </div>
                <h3 class="feature-title"><?php esc_html_e('Infinitos Viajes', 'dreamtour'); ?></h3>
                <p class="feature-description">
                    <?php esc_html_e('Vive experiencias únicas en más de 100 países del mundo, eligiendo el estilo que mejor se adapte a ti.', 'dreamtour'); ?>
                </p>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <h3 class="feature-title"><?php esc_html_e('Máxima Flexibilidad', 'dreamtour'); ?></h3>
                <p class="feature-description">
                    <?php esc_html_e('Puedes reservar tu plaza con un anticipo y cambiar de idea gratuitamente. El seguro médico y de equipaje siempre está incluido.', 'dreamtour'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Blog Section -->
<?php
$blog_args = array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$blog_query = new WP_Query($blog_args);

if ($blog_query->have_posts()) :
?>
<section class="content-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php esc_html_e('Últimas Historias', 'dreamtour'); ?></h2>
            <p class="section-subtitle"><?php esc_html_e('Inspiración y consejos de viaje', 'dreamtour'); ?></p>
        </div>
        
        <div class="tours-grid">
            <?php
            while ($blog_query->have_posts()) : $blog_query->the_post();
                get_template_part('template-parts/content', 'card');
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        
        <div class="text-center mt-xl">
            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-outline">
                <?php esc_html_e('Ver Todos los Artículos', 'dreamtour'); ?>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="content-section bg-primary color-white">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title color-white"><?php esc_html_e('¿Listo para tu próxima aventura?', 'dreamtour'); ?></h2>
            <p class="section-subtitle color-white">
                <?php esc_html_e('Únete a miles de viajeros que ya han descubierto el mundo con DreamTour', 'dreamtour'); ?>
            </p>
            <div class="mt-lg">
                <a href="<?php echo esc_url(home_url('/tours')); ?>" class="btn btn-secondary">
                    <?php esc_html_e('Explorar Destinos', 'dreamtour'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
