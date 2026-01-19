<?php
/**
 * Template Name: Blog
 * Template per la pagina Blog
 * 
 * @package DreamTour
 */

get_header();
?>

<section class="blog-section">
    <div class="container">
        
        <!-- Hero -->
        <div class="page-hero">
            <h1 class="page-title"><?php esc_html_e('Blog', 'dreamtour'); ?></h1>
            <p class="page-subtitle"><?php esc_html_e('Storie di viaggio, consigli e ispirazioni per i tuoi prossimi tour', 'dreamtour'); ?></p>
        </div>

        <?php
        // Query per i post del blog
        $blog_args = array(
            'post_type'      => 'post',
            'posts_per_page' => 12,
            'post_status'    => 'publish',
            'paged'          => get_query_var('paged') ? get_query_var('paged') : 1,
        );
        
        $blog_query = new WP_Query($blog_args);
        
        if ($blog_query->have_posts()) : ?>
            
            <div class="blog-grid">
                <?php
                while ($blog_query->have_posts()) :
                    $blog_query->the_post();
                ?>
                    <article class="blog-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="blog-card-image">
                                <?php the_post_thumbnail('dreamtour-card', array('loading' => 'lazy')); ?>
                            </a>
                        <?php endif; ?>
                        
                        <div class="blog-card-content">
                            <div class="blog-card-meta">
                                <time datetime="<?php echo get_the_date('c'); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                                <span class="separator">•</span>
                                <span class="blog-card-category">
                                    <?php 
                                    $categories = get_the_category();
                                    if (!empty($categories)) {
                                        echo esc_html($categories[0]->name);
                                    }
                                    ?>
                                </span>
                            </div>
                            
                            <h2 class="blog-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <div class="blog-card-excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="blog-card-link">
                                <?php esc_html_e('Leggi di più', 'dreamtour'); ?>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <?php
            // Paginazione
            if (function_exists('dreamtour_pagination')) {
                dreamtour_pagination($blog_query);
            }
            wp_reset_postdata();
            
        else :
            ?>
            <div class="no-content text-center">
                <h2><?php esc_html_e('Nessun articolo ancora', 'dreamtour'); ?></h2>
                <p><?php esc_html_e('Torna presto per leggere le nostre storie di viaggio!', 'dreamtour'); ?></p>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<?php
get_footer();
