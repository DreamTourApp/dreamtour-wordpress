<?php
/**
 * Template part: Tour Card
 * 
 * @package DreamTour
 */

$tour_price = get_post_meta(get_the_ID(), 'tour_price', true);
$tour_duration = get_post_meta(get_the_ID(), 'tour_duration', true);
$tour_rating = get_post_meta(get_the_ID(), 'tour_rating', true);
$tour_badge = get_post_meta(get_the_ID(), 'tour_badge', true);
?>

<div class="tour-card">
    <a href="<?php the_permalink(); ?>" class="tour-card-link">
        
        <div class="tour-card-image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('dreamtour-card'); ?>
            <?php else : ?>
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder.jpg'); ?>" alt="<?php the_title(); ?>">
            <?php endif; ?>
            
            <?php if ($tour_badge) : ?>
                <span class="tour-badge"><?php echo esc_html($tour_badge); ?></span>
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
                        <?php echo esc_html($tour_duration); ?> días
                    </span>
                <?php endif; ?>
                
                <?php
                $destinations = get_the_terms(get_the_ID(), 'destination');
                if ($destinations && !is_wp_error($destinations)) :
                    ?>
                    <span class="tour-meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <?php echo esc_html($destinations[0]->name); ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <h3 class="tour-title"><?php the_title(); ?></h3>
            
            <?php if (has_excerpt()) : ?>
                <p class="tour-description"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
            <?php endif; ?>
            
            <div class="tour-footer">
                <?php if ($tour_price) : ?>
                    <span class="tour-price">€<?php echo esc_html(number_format($tour_price, 0, ',', '.')); ?></span>
                <?php endif; ?>
                
                <?php if ($tour_rating) : ?>
                    <span class="tour-rating">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <?php echo esc_html($tour_rating); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        
    </a>
</div>
