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
    <!-- Hero Slideshow -->
    <div class="hero-slideshow">
        <?php
        $hero_images_path = get_template_directory() . '/assets/images/hero/';
        $hero_images_url = get_template_directory_uri() . '/assets/images/hero/';
        
        // Ottenere tutte le immagini dalla cartella hero
        $hero_images = array();
        if (is_dir($hero_images_path)) {
            $files = scandir($hero_images_path);
            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp'))) {
                    $hero_images[] = $hero_images_url . $file;
                }
            }
        }
        
        // Se non hay imágenes, usar una de respaldo
        if (empty($hero_images)) {
            $hero_images[] = get_template_directory_uri() . '/assets/images/default-hero.jpg';
        }
        
        // Renderizar slides
        foreach ($hero_images as $index => $image_url) :
        ?>
            <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo esc_url($image_url); ?>');">
                <div class="hero-overlay"></div>
            </div>
        <?php endforeach; ?>
    </div>
    
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
                <a href="<?php echo esc_url(home_url('/chi-siamo')); ?>" class="btn btn-outline">
                    <?php esc_html_e('Conocer Más', 'dreamtour'); ?>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Slideshow Controls -->
    <?php if (count($hero_images) > 1) : ?>
    <div class="hero-slideshow-controls">
        <button class="hero-prev" aria-label="<?php esc_attr_e('Imagen anterior', 'dreamtour'); ?>">
            <span class="dashicons dashicons-arrow-left-alt2"></span>
        </button>
        <button class="hero-next" aria-label="<?php esc_attr_e('Imagen siguiente', 'dreamtour'); ?>">
            <span class="dashicons dashicons-arrow-right-alt2"></span>
        </button>
    </div>
    <div class="hero-slideshow-dots">
        <?php foreach ($hero_images as $index => $image) : ?>
            <button class="hero-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>" aria-label="<?php echo esc_attr(sprintf(__('Ir a imagen %d', 'dreamtour'), $index + 1)); ?>"></button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<!-- Tours Section -->
<section class="content-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php esc_html_e('Tours Destacados', 'dreamtour'); ?></h2>
            <p class="section-subtitle"><?php esc_html_e('Descubre nuestros viajes más populares', 'dreamtour'); ?></p>
        </div>
        
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
        
        <div class="tours-grid" id="tours-container">
            <?php
            // Query para obtener tours del CPT drtr_tour
            $tour_args = array(
                'post_type'      => 'drtr_tour',
                'posts_per_page' => 6,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'post_status'    => 'publish',
            );
            
            $tour_query = new WP_Query($tour_args);
            
            if ($tour_query->have_posts()) :
                while ($tour_query->have_posts()) : $tour_query->the_post();
                    get_template_part('template-parts/content', 'drtr-tour-card');
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <div class="no-content">
                    <p><?php esc_html_e('No hay tours disponibles en este momento.', 'dreamtour'); ?></p>
                </div>
                <?php
            endif;
            ?>
        </div>
        
        <?php if ($tour_query->have_posts()) : ?>
            <div class="text-center mt-xl">
                <a href="<?php echo esc_url(home_url('/gestione-tours')); ?>" class="btn btn-secondary">
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
