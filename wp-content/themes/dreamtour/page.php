<?php
/**
 * Template para páginas individuales
 * 
 * @package DreamTour
 */

get_header();
?>

<div class="container">
    <div class="page-wrapper">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="page-featured-image">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>
                
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>
                
                <div class="page-content">
                    <?php
                    the_content();
                    
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Páginas:', 'dreamtour'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
                
            </article>
            
            <?php
            // Si los comentarios están abiertos o hay al menos un comentario
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            
        endwhile;
        ?>
    </div>
</div>

<?php
get_footer();
