<?php
/**
 * Template Name: Seleziona Posti
 * 
 * Page template for seat selection
 */

get_header();
?>

<div class="drtr-page-content">
    <div class="container">
        <?php
        // Check if we have a token
        $token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';
        
        if ($token) {
            // Validate token and show seat selector
            echo do_shortcode('[drtr_seat_selector]');
        } else {
            ?>
            <div class="drtr-error-page">
                <h2><?php _e('Accesso Non Valido', 'drtr-posti'); ?></h2>
                <p><?php _e('Devi accedere a questa pagina tramite il link ricevuto via email.', 'drtr-posti'); ?></p>
                <a href="<?php echo esc_url(home_url('/area-riservata')); ?>" class="drtr-btn">
                    <?php _e('Vai all\'Area Riservata', 'drtr-posti'); ?>
                </a>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<?php get_footer(); ?>
