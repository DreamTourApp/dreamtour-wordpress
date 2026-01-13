/**
 * Template part: Blog Card
 * 
 * @package DreamTour
 */
?>

<div class="tour-card">
    <a href="<?php the_permalink(); ?>" class="tour-card-link">
        
        <div class="tour-card-image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('dreamtour-card'); ?>
            <?php else : ?>
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder.jpg'); ?>" alt="<?php the_title(); ?>">
            <?php endif; ?>
            
            <?php
            $categories = get_the_category();
            if (!empty($categories)) :
                ?>
                <span class="tour-badge"><?php echo esc_html($categories[0]->name); ?></span>
            <?php endif; ?>
        </div>
        
        <div class="tour-card-content">
            <div class="tour-meta">
                <span class="tour-meta-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <?php echo get_the_date(); ?>
                </span>
                
                <span class="tour-meta-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <?php the_author(); ?>
                </span>
            </div>
            
            <h3 class="tour-title"><?php the_title(); ?></h3>
            
            <?php if (has_excerpt()) : ?>
                <p class="tour-description"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
            <?php else : ?>
                <p class="tour-description"><?php echo wp_trim_words(get_the_content(), 15); ?></p>
            <?php endif; ?>
            
            <div class="tour-footer">
                <span class="read-more">
                    <?php esc_html_e('Leer más', 'dreamtour'); ?> →
                </span>
            </div>
        </div>
        
    </a>
</div>
