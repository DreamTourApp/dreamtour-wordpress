<?php
/**
 * Template para posts individuales
 * 
 * @package DreamTour
 */

get_header();
?>

<div class="container">
    <div class="single-post-wrapper">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <header class="post-header">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-featured-image">
                            <?php the_post_thumbnail('dreamtour-hero'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="post-header-content">
                        <div class="post-meta">
                            <span class="post-date">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <?php echo get_the_date(); ?>
                            </span>
                            
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) :
                                ?>
                                <span class="post-category">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                    </svg>
                                    <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>">
                                        <?php echo esc_html($categories[0]->name); ?>
                                    </a>
                                </span>
                            <?php endif; ?>
                            
                            <span class="post-author">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <?php the_author(); ?>
                            </span>
                        </div>
                        
                        <h1 class="post-title"><?php the_title(); ?></h1>
                    </div>
                </header>
                
                <div class="post-content">
                    <?php
                    the_content();
                    
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Páginas:', 'dreamtour'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
                
                <?php if (has_tag()) : ?>
                    <footer class="post-footer">
                        <div class="post-tags">
                            <?php the_tags('<span class="tags-label">Tags: </span>', ', ', ''); ?>
                        </div>
                    </footer>
                <?php endif; ?>
                
            </article>
            
            <?php
            // Navegación de posts
            the_post_navigation(array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__('Anterior:', 'dreamtour') . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__('Siguiente:', 'dreamtour') . '</span> <span class="nav-title">%title</span>',
            ));
            
            // Comentarios
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            
        endwhile;
        ?>
    </div>
</div>

<?php
get_footer();
