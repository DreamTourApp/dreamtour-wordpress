<?php
/**
 * Template para tour individual - Versión mejorada con soporte completo
 * 
 * @package DreamTour
 */

get_header();

while (have_posts()) :
    the_post();
    
    // Get meta fields with DRTR prefix (nueva forma) o sin prefijo (forma antigua)
    $tour_price = get_post_meta(get_the_ID(), '_drtr_price', true) ?: get_post_meta(get_the_ID(), 'price', true);
    $tour_duration = get_post_meta(get_the_ID(), '_drtr_duration', true) ?: get_post_meta(get_the_ID(), 'duration', true);
    $tour_rating = get_post_meta(get_the_ID(), '_drtr_rating', true) ?: get_post_meta(get_the_ID(), 'rating', true);
    $tour_location = get_post_meta(get_the_ID(), '_drtr_location', true) ?: get_post_meta(get_the_ID(), 'location', true);
    $tour_max_people = get_post_meta(get_the_ID(), '_drtr_max_people', true) ?: get_post_meta(get_the_ID(), 'max_people', true);
    $tour_transport_type = get_post_meta(get_the_ID(), '_drtr_transport_type', true) ?: get_post_meta(get_the_ID(), 'transport_type', true);
    $tour_start_date = get_post_meta(get_the_ID(), '_drtr_start_date', true) ?: get_post_meta(get_the_ID(), 'start_date', true);
    $tour_end_date = get_post_meta(get_the_ID(), '_drtr_end_date', true) ?: get_post_meta(get_the_ID(), 'end_date', true);
    $tour_includes = get_post_meta(get_the_ID(), '_drtr_includes', true) ?: get_post_meta(get_the_ID(), 'includes', true);
    $tour_not_includes = get_post_meta(get_the_ID(), '_drtr_not_includes', true) ?: get_post_meta(get_the_ID(), 'not_includes', true);
    $tour_itinerary = get_post_meta(get_the_ID(), '_drtr_itinerary', true) ?: get_post_meta(get_the_ID(), 'itinerary', true);

    // Debug logging to detect missing data in production (check wp-content/debug.log)
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $meta_debug = array(
            'post_id' => get_the_ID(),
            'slug' => get_post_field('post_name', get_the_ID()),
            'price' => $tour_price,
            'duration' => $tour_duration,
            'rating' => $tour_rating,
            'location' => $tour_location,
            'max_people' => $tour_max_people,
            'transport_type' => $tour_transport_type,
            'start_date' => $tour_start_date,
            'end_date' => $tour_end_date,
            'includes_length' => $tour_includes ? strlen($tour_includes) : 0,
            'not_includes_length' => $tour_not_includes ? strlen($tour_not_includes) : 0,
            'itinerary_length' => $tour_itinerary ? strlen($tour_itinerary) : 0,
        );
        error_log('[single-tour] meta ' . wp_json_encode($meta_debug));
    }
    
    // Calculate deposit (50%)
    $tour_deposit = $tour_price ? round($tour_price * 0.5, 2) : 0;
    
    // Taxonomías
    $destinations = get_the_terms(get_the_ID(), 'drtr_destination') ?: get_the_terms(get_the_ID(), 'destination');
    $tour_types = get_the_terms(get_the_ID(), 'drtr_tour_type') ?: get_the_terms(get_the_ID(), 'tour_type');

    // Debug logging for taxonomies
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $tax_debug = array(
            'destinations' => $destinations && !is_wp_error($destinations) ? wp_list_pluck($destinations, 'slug') : 'none',
            'tour_types' => $tour_types && !is_wp_error($tour_types) ? wp_list_pluck($tour_types, 'slug') : 'none',
        );
        error_log('[single-tour] taxonomies ' . wp_json_encode($tax_debug));
    }
    ?>
    
    <article id="post-<?php the_ID(); ?>" <?php post_class('single-tour'); ?>>
        
        <!-- Hero Image -->
        <?php if (has_post_thumbnail()) : ?>
            <div class="tour-hero-image">
                <?php the_post_thumbnail('full'); ?>
            </div>
        <?php endif; ?>
        
        <div class="container">
            <div class="single-tour-wrapper">
                
                <!-- Main Content -->
                <div class="tour-main-content">
                    
                    <!-- Tour Title -->
                    <header class="tour-header">
                        <h1 class="tour-title"><?php the_title(); ?></h1>
                        
                        <!-- Quick Meta Info -->
                        <div class="tour-meta-list">
                            <?php if ($tour_location) : ?>
                                <div class="tour-meta-item">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span><?php echo esc_html($tour_location); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tour_duration) : ?>
                                <div class="tour-meta-item">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span><?php echo esc_html($tour_duration); ?> <?php _e('días', 'dreamtour'); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tour_max_people) : ?>
                                <div class="tour-meta-item">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    <span><?php echo esc_html($tour_max_people); ?> <?php _e('personas', 'dreamtour'); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tour_rating) : ?>
                                <div class="tour-meta-item">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                    <span><?php echo esc_html($tour_rating); ?>/5</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </header>
                    
                    <!-- Main Description -->
                    <section class="tour-section tour-description">
                        <h2><?php _e('Acerca de este viaje', 'dreamtour'); ?></h2>
                        <?php 
                        if (!empty($post->post_excerpt)) {
                            echo '<p class="tour-excerpt">' . wp_kses_post($post->post_excerpt) . '</p>';
                        }
                        the_content(); 
                        ?>
                    </section>
                    
                    <!-- Itinerary -->
                    <?php if ($tour_itinerary) : ?>
                        <section class="tour-section tour-itinerary">
                            <h2><?php _e('Itinerario', 'dreamtour'); ?></h2>
                            <div class="itinerary-content">
                                <?php echo wp_kses_post($tour_itinerary); ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- What's Included -->
                    <?php if ($tour_includes) : ?>
                        <section class="tour-section tour-includes-section">
                            <h2><?php _e('Qué incluye', 'dreamtour'); ?></h2>
                            <ul class="includes-list">
                                <?php
                                $includes_items = array_filter(array_map('trim', explode("\n", $tour_includes)));
                                foreach ($includes_items as $item) :
                                    ?>
                                    <li>
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <?php echo esc_html($item); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>
                    
                    <!-- What's Not Included -->
                    <?php if ($tour_not_includes) : ?>
                        <section class="tour-section tour-not-includes-section">
                            <h2><?php _e('Qué no incluye', 'dreamtour'); ?></h2>
                            <ul class="not-includes-list">
                                <?php
                                $not_includes_items = array_filter(array_map('trim', explode("\n", $tour_not_includes)));
                                foreach ($not_includes_items as $item) :
                                    ?>
                                    <li>
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        <?php echo esc_html($item); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Tour Details -->
                    <section class="tour-section tour-details-section">
                        <h2><?php _e('Detalles del Tour', 'dreamtour'); ?></h2>
                        <div class="details-grid">
                            <?php if ($tour_duration) : ?>
                                <div class="detail-card">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <h4><?php _e('Duración', 'dreamtour'); ?></h4>
                                    <p><?php echo esc_html($tour_duration); ?> <?php _e('días', 'dreamtour'); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tour_location) : ?>
                                <div class="detail-card">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <h4><?php _e('Ubicación', 'dreamtour'); ?></h4>
                                    <p><?php echo esc_html($tour_location); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tour_transport_type) : ?>
                                <div class="detail-card">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 17h2c.5523 0 1-.4477 1-1v-3c0-.5523-.4477-1-1-1h-2m0-4h2c.5523 0 1 .4477 1 1v3c0 .5523-.4477 1-1 1h-2M7 7h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2zm0 0H5c-.5523 0-1 .4477-1 1v10c0 .5523.4477 1 1 1h2"></path>
                                    </svg>
                                    <h4><?php _e('Transporte', 'dreamtour'); ?></h4>
                                    <p><?php echo esc_html(ucfirst($tour_transport_type)); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tour_max_people) : ?>
                                <div class="detail-card">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    <h4><?php _e('Máx. Personas', 'dreamtour'); ?></h4>
                                    <p><?php echo esc_html($tour_max_people); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tour_price) : ?>
                                <div class="detail-card">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    <h4><?php _e('Precio', 'dreamtour'); ?></h4>
                                    <p>€<?php echo esc_html(number_format($tour_price, 2, ',', '.')); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tour_rating) : ?>
                                <div class="detail-card">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                    <h4><?php _e('Valoración', 'dreamtour'); ?></h4>
                                    <p><?php echo esc_html($tour_rating); ?>/5 ⭐</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                    
                    <!-- Taxonomies -->
                    <?php if (($destinations && !is_wp_error($destinations)) || ($tour_types && !is_wp_error($tour_types))) : ?>
                        <footer class="tour-footer">
                            <?php if ($destinations && !is_wp_error($destinations)) : ?>
                                <div class="tour-taxonomy">
                                    <strong><?php _e('Destinos:', 'dreamtour'); ?></strong>
                                    <?php
                                    $destination_links = array();
                                    foreach ($destinations as $destination) {
                                        $destination_links[] = '<a href="' . esc_url(get_term_link($destination)) . '">' . esc_html($destination->name) . '</a>';
                                    }
                                    echo implode(', ', $destination_links);
                                    ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tour_types && !is_wp_error($tour_types)) : ?>
                                <div class="tour-taxonomy">
                                    <strong><?php _e('Tipo de viaje:', 'dreamtour'); ?></strong>
                                    <?php
                                    $type_links = array();
                                    foreach ($tour_types as $type) {
                                        $type_links[] = '<a href="' . esc_url(get_term_link($type)) . '">' . esc_html($type->name) . '</a>';
                                    }
                                    echo implode(', ', $type_links);
                                    ?>
                                </div>
                            <?php endif; ?>
                        </footer>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar with Booking -->
                <aside class="tour-sidebar">
                    <div class="tour-booking-card">
                        <!-- Price Box -->
                        <?php if ($tour_price) : ?>
                            <div class="tour-price-box" data-price="<?php echo esc_attr($tour_price); ?>">
                                <span class="price-label"><?php _e('Precio por persona', 'dreamtour'); ?></span>
                                <span class="price-amount">€<?php echo esc_html(number_format($tour_price, 2, ',', '.')); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Booking Form -->
                        <div class="tour-booking-form">
                            <h3><?php _e('Reservar tu plaza', 'dreamtour'); ?></h3>
                            
                            <!-- Passengers Selection -->
                            <div class="booking-field">
                                <label><?php _e('Adultos', 'dreamtour'); ?></label>
                                <div class="quantity-control">
                                    <button type="button" class="qty-minus" data-type="adults">−</button>
                                    <input type="number" id="adults" name="adults" value="1" min="1" readonly>
                                    <button type="button" class="qty-plus" data-type="adults">+</button>
                                </div>
                            </div>
                            
                            <div class="booking-field">
                                <label><?php _e('Niños (0-12 años)', 'dreamtour'); ?></label>
                                <div class="quantity-control">
                                    <button type="button" class="qty-minus" data-type="children">−</button>
                                    <input type="number" id="children" name="children" value="0" min="0" readonly>
                                    <button type="button" class="qty-plus" data-type="children">+</button>
                                </div>
                            </div>
                            
                            <!-- Payment Preview -->
                            <div class="payment-preview">
                                <div class="preview-row">
                                    <span><?php _e('Subtotal', 'dreamtour'); ?></span>
                                    <span id="subtotal">€0</span>
                                </div>
                                
                                <div class="preview-row">
                                    <span><?php _e('Acconto (50%)', 'dreamtour'); ?></span>
                                    <span id="deposit" class="deposit-amount">€0</span>
                                </div>
                                
                                <div class="preview-row total">
                                    <span><?php _e('Total a pagar hoy', 'dreamtour'); ?></span>
                                    <span id="total-amount" class="total-amount">€0</span>
                                </div>
                            </div>
                            
                            <!-- Payment Options -->
                            <div class="payment-options">
                                <label class="radio-option">
                                    <input type="radio" name="payment-type" value="deposit" checked>
                                    <span><?php _e('Pagar Acconto (50%)', 'dreamtour'); ?></span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="payment-type" value="full">
                                    <span><?php _e('Pagar completo', 'dreamtour'); ?></span>
                                </label>
                            </div>
                            
                            <!-- Book Button -->
                            <button type="button" class="btn btn-primary btn-block" id="book-btn">
                                <?php _e('Continuar a Reserva', 'dreamtour'); ?>
                            </button>
                            
                            <p class="booking-notice"><?php _e('Cancelación flexible hasta 14 días antes', 'dreamtour'); ?></p>
                        </div>
                        
                        <!-- Quick Info -->
                        <ul class="tour-includes">
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <?php _e('Coordinador incluido', 'dreamtour'); ?>
                            </li>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <?php _e('Seguro médico y de equipaje', 'dreamtour'); ?>
                            </li>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <?php _e('Cancelación flexible', 'dreamtour'); ?>
                            </li>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <?php _e('Grupo reducido', 'dreamtour'); ?>
                            </li>
                        </ul>
                        
                        <!-- Contact -->
                        <div class="tour-contact">
                            <p><?php _e('¿Tienes dudas?', 'dreamtour'); ?></p>
                            <a href="#" class="btn btn-outline btn-block">
                                <?php _e('Contáctanos', 'dreamtour'); ?>
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </article>
    
    <?php if (defined('WP_DEBUG') && WP_DEBUG) : ?>
        <script>
            console.log('[single-tour]', {
                id: <?php echo (int) get_the_ID(); ?>,
                slug: <?php echo wp_json_encode(get_post_field('post_name', get_the_ID())); ?>,
                meta: <?php echo wp_json_encode(array(
                    'price' => $tour_price,
                    'duration' => $tour_duration,
                    'rating' => $tour_rating,
                    'location' => $tour_location,
                    'max_people' => $tour_max_people,
                    'transport_type' => $tour_transport_type,
                    'start_date' => $tour_start_date,
                    'end_date' => $tour_end_date,
                    'includes_length' => $tour_includes ? strlen($tour_includes) : 0,
                    'not_includes_length' => $tour_not_includes ? strlen($tour_not_includes) : 0,
                    'itinerary_length' => $tour_itinerary ? strlen($tour_itinerary) : 0,
                )); ?>,
                taxonomies: <?php echo wp_json_encode(array(
                    'destinations' => ($destinations && !is_wp_error($destinations)) ? wp_list_pluck($destinations, 'slug') : array(),
                    'tour_types' => ($tour_types && !is_wp_error($tour_types)) ? wp_list_pluck($tour_types, 'slug') : array(),
                )); ?>
            });
        </script>
    <?php endif; ?>
    
    <?php
endwhile;

get_footer();
