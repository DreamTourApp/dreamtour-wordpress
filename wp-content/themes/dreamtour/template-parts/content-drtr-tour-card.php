<?php
/**
 * Template part: DRTR Tour Card
 * 
 * @package DreamTour
 */

$tour_price = get_post_meta(get_the_ID(), '_drtr_price', true);
$tour_child_price = get_post_meta(get_the_ID(), '_drtr_child_price', true);
$tour_duration = get_post_meta(get_the_ID(), '_drtr_duration', true);
$tour_location = get_post_meta(get_the_ID(), '_drtr_location', true);
$tour_transport = get_post_meta(get_the_ID(), '_drtr_transport_type', true);
$tour_max_people = get_post_meta(get_the_ID(), '_drtr_max_people', true);
$tour_start_date = get_post_meta(get_the_ID(), '_drtr_start_date', true);
$image_id = get_post_meta(get_the_ID(), '_drtr_image_id', true);

// Obtener destinos (taxonomía)
$destinations = get_the_terms(get_the_ID(), 'drtr_destination');

// Obtener travel intents (taxonomía)
$travel_intents = get_the_terms(get_the_ID(), 'drtr_travel_intent');
$intent_slugs = array();
if ($travel_intents && !is_wp_error($travel_intents)) {
    $intent_slugs = wp_list_pluck($travel_intents, 'slug');
}
?>

<div class="tour-card" 
    data-destination="<?php echo $destinations && !is_wp_error($destinations) ? esc_attr($destinations[0]->slug) : ''; ?>" 
    data-transport="<?php echo esc_attr($tour_transport); ?>" 
    data-duration="<?php echo esc_attr($tour_duration); ?>"
    data-intents="<?php echo esc_attr(implode(',', $intent_slugs)); ?>">
    
    <?php if (current_user_can('manage_options')) : ?>
        <a href="<?php echo esc_url(home_url('/gestione-tours?action=edit&id=' . get_the_ID())); ?>" 
           class="tour-edit-btn" 
           title="Modifica Tour"
           onclick="event.stopPropagation();">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
        </a>
    <?php endif; ?>
    
    <a href="<?php the_permalink(); ?>" class="tour-card-link">
        
        <div class="tour-card-image">
            <?php if ($image_id) : 
                echo wp_get_attachment_image($image_id, 'medium', false, array('class' => 'tour-thumbnail'));
            elseif (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium'); ?>
            <?php else : ?>
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder.jpg'); ?>" alt="<?php the_title(); ?>">
            <?php endif; ?>
            
            <?php if ($tour_start_date) : 
                $start = new DateTime($tour_start_date);
                $now = new DateTime();
                $diff = $now->diff($start);
                if ($diff->days <= 30 && $start > $now) :
                ?>
                    <span class="tour-badge"><?php esc_html_e('Próximo', 'dreamtour'); ?></span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <div class="tour-card-content">
            <div class="tour-meta">
                <?php if ($tour_duration) : ?>
                    <span class="tour-meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <?php echo esc_html($tour_duration); ?> <?php esc_html_e('días', 'dreamtour'); ?>
                    </span>
                <?php endif; ?>
                
                <?php if ($destinations && !is_wp_error($destinations)) : ?>
                    <span class="tour-meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <?php echo esc_html($destinations[0]->name); ?>
                    </span>
                <?php elseif ($tour_location) : ?>
                    <span class="tour-meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <?php echo esc_html($tour_location); ?>
                    </span>
                <?php endif; ?>
                
                <?php if ($tour_transport) : ?>
                    <span class="tour-meta-item">
                        <?php
                        $transport_icons = array(
                            'bus' => '🚌',
                            'avion' => '✈️',
                            'tren' => '🚂',
                            'barco' => '🚢',
                            'mixto' => '🌐'
                        );
                        echo isset($transport_icons[$tour_transport]) ? $transport_icons[$tour_transport] : '🌐';
                        ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <h3 class="tour-title">
                <?php 
                the_title();
                // Add start date and time if available
                if ($tour_start_date) {
                    $date_obj = DateTime::createFromFormat('Y-m-d\TH:i', $tour_start_date);
                    if ($date_obj) {
                        echo ' - ' . $date_obj->format('d/m/y');
                    }
                }
                ?>
            </h3>
            
            <?php 
            $short_description = get_post_meta(get_the_ID(), '_drtr_short_description', true);
            if ($short_description) : ?>
                <p class="tour-description"><?php echo wp_trim_words($short_description, 15); ?></p>
            <?php elseif (has_excerpt()) : ?>
                <p class="tour-description"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
            <?php endif; ?>
            
            <div class="tour-footer">
                <?php if ($tour_price) : ?>
                    <div class="tour-price">
                        <div class="price-item price-item-adult">
                            <span class="price-label">Adulto:</span>
                            <span class="price-value">€<?php echo esc_html(number_format($tour_price, 0, ',', '.')); ?></span>
                        </div>
                        <?php if ($tour_child_price) : ?>
                            <div class="price-item price-item-child">
                                <span class="price-label">Bambino:</span>
                                <span class="price-value">€<?php echo esc_html(number_format($tour_child_price, 0, ',', '.')); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($tour_max_people) : ?>
                    <span class="tour-rating">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <?php echo esc_html($tour_max_people); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        
    </a>
</div>
