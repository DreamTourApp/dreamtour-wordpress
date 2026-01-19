<?php
/**
 * Template Name: Come Funziona
 * Template per la pagina Come Funziona
 * 
 * @package DreamTour
 */

get_header();
?>

<section class="how-it-works-section">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Hero -->
            <div class="page-hero">
                <h1 class="page-title"><?php esc_html_e('Come Funziona', 'dreamtour'); ?></h1>
                <p class="page-subtitle"><?php esc_html_e('Organizzare il tuo viaggio con DreamTour è semplice e veloce', 'dreamtour'); ?></p>
            </div>

            <!-- Steps -->
            <div class="steps-section">
                <div class="steps-grid">
                    <!-- Step 1 -->
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3 class="step-title"><?php esc_html_e('Scegli il tuo tour', 'dreamtour'); ?></h3>
                            <p class="step-description">
                                <?php esc_html_e('Esplora i nostri tour organizzati e trova quello perfetto per te. Filtra per destinazione, durata o tipo di esperienza.', 'dreamtour'); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3 class="step-title"><?php esc_html_e('Prenota online', 'dreamtour'); ?></h3>
                            <p class="step-description">
                                <?php esc_html_e('Compila il form di prenotazione, scegli i tuoi posti sul pullman e completa il pagamento in modo sicuro con carta di credito.', 'dreamtour'); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3 class="step-title"><?php esc_html_e('Ricevi la conferma', 'dreamtour'); ?></h3>
                            <p class="step-description">
                                <?php esc_html_e('Riceverai immediatamente una email con la conferma della prenotazione e il tuo biglietto elettronico con QR code.', 'dreamtour'); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h3 class="step-title"><?php esc_html_e('Parti per l\'avventura', 'dreamtour'); ?></h3>
                            <p class="step-description">
                                <?php esc_html_e('Il giorno della partenza, presentati al punto di ritrovo con il tuo biglietto e preparati a vivere un\'esperienza indimenticabile!', 'dreamtour'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="page-content">
                <?php the_content(); ?>
            </div>

            <!-- FAQ Section -->
            <div class="faq-preview">
                <h2><?php esc_html_e('Hai domande?', 'dreamtour'); ?></h2>
                <p><?php esc_html_e('Consulta le nostre FAQ per trovare risposte alle domande più comuni', 'dreamtour'); ?></p>
                <a href="<?php echo esc_url(home_url('/faq')); ?>" class="btn btn-outline">
                    <?php esc_html_e('Vai alle FAQ', 'dreamtour'); ?>
                </a>
            </div>

            <!-- CTA -->
            <div class="page-cta">
                <div class="cta-card">
                    <h2><?php esc_html_e('Pronto a partire?', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Scopri tutti i nostri tour disponibili', 'dreamtour'); ?></p>
                    <a href="<?php echo esc_url(home_url('/tours')); ?>" class="btn btn-primary btn-lg">
                        <?php esc_html_e('Esplora i Tour', 'dreamtour'); ?>
                    </a>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</section>

<?php
get_footer();
