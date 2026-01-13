<?php
/**
 * Template para tour individual
 * 
 * @package DreamTour
 */

get_header();

while (have_posts()) :
    the_post();
    
    $tour_price = get_post_meta(get_the_ID(), 'tour_price', true);
    $tour_duration = get_post_meta(get_the_ID(), 'tour_duration', true);
    $tour_rating = get_post_meta(get_the_ID(), 'tour_rating', true);
    $tour_location = get_post_meta(get_the_ID(), 'tour_location', true);
    $tour_max_people = get_post_meta(get_the_ID(), 'tour_max_people', true);
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
                        <?php if ($tour_price) : ?>
                            <div class="tour-price-box">
                                <span class="price-label"><?php esc_html_e('Desde', 'dreamtour'); ?></span>
                                <span class="price-amount">€<?php echo esc_html(number_format($tour_price, 0, ',', '.')); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <a href="#" class="btn btn-primary btn-block">
                            <?php esc_html_e('Reservar Ahora', 'dreamtour'); ?>
                        </a>
                        
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
