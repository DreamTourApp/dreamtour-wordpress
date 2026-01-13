<?php
/**
 * Template para tour individual
 * 
 * @package DreamTour
 */

get_header();

while (have_posts()) :
    the_post();
    
    // Get meta fields with DRTR prefix
    $tour_price = get_post_meta(get_the_ID(), '_drtr_price', true);
    $tour_duration = get_post_meta(get_the_ID(), '_drtr_duration', true);
    $tour_rating = get_post_meta(get_the_ID(), '_drtr_rating', true);
    $tour_location = get_post_meta(get_the_ID(), '_drtr_location', true);
    $tour_max_people = get_post_meta(get_the_ID(), '_drtr_max_people', true);
    $tour_transport_type = get_post_meta(get_the_ID(), '_drtr_transport_type', true);
    $tour_start_date = get_post_meta(get_the_ID(), '_drtr_start_date', true);
    $tour_end_date = get_post_meta(get_the_ID(), '_drtr_end_date', true);
    $tour_includes = get_post_meta(get_the_ID(), '_drtr_includes', true);
    $tour_not_includes = get_post_meta(get_the_ID(), '_drtr_not_includes', true);
    $tour_itinerary = get_post_meta(get_the_ID(), '_drtr_itinerary', true);
    
    // Calculate deposit (50%)
    $tour_deposit = $tour_price ? round($tour_price * 0.5, 2) : 0;
    ?>
    
    <article id="post-<?php the_ID(); ?>" <?php post_class('single-tour'); ?>>
        
        <?php if (has_post_thumbnail()) : ?>
            <div class="tour-hero-image">
                <?php the_post_thumbnail('dreamtour-hero'); ?>
            </div>
        <?php endif; ?>
        
        <div class="container">
            <div class="single-tour-wrapper">
                
                <div class="tour-main-content">
                    <header class="tour-header">
                        <h1 class="tour-title"><?php the_title(); ?></h1>
                        
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
                                    <span><?php echo esc_html($tour_duration); ?> días</span>
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
                                    <span><?php echo esc_html($tour_max_people); ?> personas máx.</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tour_rating) : ?>
                                <div class="tour-meta-item">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                    <span><?php echo esc_html($tour_rating); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </header>
                    
                    <div class="tour-content">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Itinerary Section -->
                    <?php if ($tour_itinerary) : ?>
                        <section class="tour-section tour-itinerary">
                            <h2><?php esc_html_e('Itinerario', 'dreamtour'); ?></h2>
                            <div class="itinerary-content">
                                <?php echo wp_kses_post($tour_itinerary); ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Includes Section -->
                    <?php if ($tour_includes) : ?>
                        <section class="tour-section tour-includes-section">
                            <h2><?php esc_html_e('Qué incluye', 'dreamtour'); ?></h2>
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
                    
                    <!-- Not Includes Section -->
                    <?php if ($tour_not_includes) : ?>
                        <section class="tour-section tour-not-includes-section">
                            <h2><?php esc_html_e('Qué no incluye', 'dreamtour'); ?></h2>
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
                    
                    <!-- Tour Details Section -->
                    <section class="tour-section tour-details-section">
                        <h2><?php esc_html_e('Detalles del Tour', 'dreamtour'); ?></h2>
                        <div class="details-grid">
                            <?php if ($tour_duration) : ?>
                                <div class="detail-card">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <h4><?php esc_html_e('Duración', 'dreamtour'); ?></h4>
                                    <p><?php echo esc_html($tour_duration); ?> <?php esc_html_e('días', 'dreamtour'); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tour_location) : ?>
                                <div class="detail-card">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <h4><?php esc_html_e('Ubicación', 'dreamtour'); ?></h4>
                                    <p><?php echo esc_html($tour_location); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tour_transport_type) : ?>
                                <div class="detail-card">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    <h4><?php esc_html_e('Transporte', 'dreamtour'); ?></h4>
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
                                    <h4><?php esc_html_e('Máx. Personas', 'dreamtour'); ?></h4>
                                    <p><?php echo esc_html($tour_max_people); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                    
                    <?php
                    // Mostrar términos de taxonomía
                    $destinations = get_the_terms(get_the_ID(), 'destination');
                    $tour_types = get_the_terms(get_the_ID(), 'tour_type');
                    
                    if ($destinations || $tour_types) :
                        ?>
                        <footer class="tour-footer">
                            <?php if ($destinations && !is_wp_error($destinations)) : ?>
                                <div class="tour-taxonomy">
                                    <strong><?php esc_html_e('Destinos:', 'dreamtour'); ?></strong>
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
                                    <strong><?php esc_html_e('Tipo de viaje:', 'dreamtour'); ?></strong>
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
                
                <aside class="tour-sidebar">
                    <div class="tour-booking-card">
                        <!-- Price Box -->
                        <?php if ($tour_price) : ?>
                            <div class="tour-price-box" data-price="<?php echo esc_attr($tour_price); ?>">
                                <span class="price-label"><?php esc_html_e('Precio por persona', 'dreamtour'); ?></span>
                                <span class="price-amount">€<?php echo esc_html(number_format($tour_price, 2, ',', '.')); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Booking Form -->
                        <div class="tour-booking-form">
                            <h3><?php esc_html_e('Reservar tu plaza', 'dreamtour'); ?></h3>
                            
                            <!-- Passengers Selection -->
                            <div class="booking-field">
                                <label><?php esc_html_e('Adultos', 'dreamtour'); ?></label>
                                <div class="quantity-control">
                                    <button type="button" class="qty-minus" data-type="adults">−</button>
                                    <input type="number" id="adults" name="adults" value="1" min="1" readonly>
                                    <button type="button" class="qty-plus" data-type="adults">+</button>
                                </div>
                            </div>
                            
                            <div class="booking-field">
                                <label><?php esc_html_e('Bambini (0-12 años)', 'dreamtour'); ?></label>
                                <div class="quantity-control">
                                    <button type="button" class="qty-minus" data-type="children">−</button>
                                    <input type="number" id="children" name="children" value="0" min="0" readonly>
                                    <button type="button" class="qty-plus" data-type="children">+</button>
                                </div>
                            </div>
                            
                            <!-- Payment Preview -->
                            <div class="payment-preview">
                                <div class="preview-row">
                                    <span><?php esc_html_e('Subtotal', 'dreamtour'); ?></span>
                                    <span id="subtotal">€0</span>
                                </div>
                                
                                <div class="preview-row">
                                    <span><?php esc_html_e('Acconto (50%)', 'dreamtour'); ?></span>
                                    <span id="deposit" class="deposit-amount">€0</span>
                                </div>
                                
                                <div class="preview-row total">
                                    <span><?php esc_html_e('Totale da pagare oggi', 'dreamtour'); ?></span>
                                    <span id="total-amount" class="total-amount">€0</span>
                                </div>
                            </div>
                            
                            <!-- Payment Options -->
                            <div class="payment-options">
                                <label class="radio-option">
                                    <input type="radio" name="payment-type" value="deposit" checked>
                                    <span><?php esc_html_e('Pagar Acconto (50%)', 'dreamtour'); ?></span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="payment-type" value="full">
                                    <span><?php esc_html_e('Pagar completo', 'dreamtour'); ?></span>
                                </label>
                            </div>
                            
                            <!-- Book Button -->
                            <button type="button" class="btn btn-primary btn-block" id="book-btn">
                                <?php esc_html_e('Continuar a Reserva', 'dreamtour'); ?>
                            </button>
                            
                            <p class="booking-notice"><?php esc_html_e('Cancelación flexible hasta 14 días antes', 'dreamtour'); ?></p>
                        </div>
                        
                        <!-- Quick Info -->
                        <ul class="tour-includes">
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <?php esc_html_e('Coordinador incluido', 'dreamtour'); ?>
                            </li>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <?php esc_html_e('Seguro médico y de equipaje', 'dreamtour'); ?>
                            </li>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <?php esc_html_e('Cancelación flexible', 'dreamtour'); ?>
                            </li>
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <?php esc_html_e('Grupo reducido', 'dreamtour'); ?>
                            </li>
                        </ul>
                        
                        <!-- Contact -->
                        <div class="tour-contact">
                            <p><?php esc_html_e('¿Tienes dudas?', 'dreamtour'); ?></p>
                            <a href="#" class="btn btn-outline btn-block">
                                <?php esc_html_e('Contáctanos', 'dreamtour'); ?>
                            </a>
                        </div>
                    </div>
                </aside>
                
            </div>
        </div>
        
    </article>
    
    <?php
endwhile;

get_footer();
