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

<style>
/* Come Funziona Styles - Based on Chi Siamo */

.how-it-works-section {
    padding: 2rem 0;
}

.page-hero {
    text-align: center;
    padding: 3rem 0;
    margin-bottom: 4rem;
    position: relative;
}

.page-hero::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    background: linear-gradient(90deg, #003284 0%, #1ba4ce 100%);
    border-radius: 2px;
}

.page-title {
    font-size: 3rem;
    font-weight: 900;
    margin-bottom: 1rem;
    color: #003284;
}

.page-subtitle {
    font-size: 1.25rem;
    color: #4a5568;
}

.steps-section {
    margin-bottom: 4rem;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 3rem;
}

.step-item {
    text-align: center;
    padding: 2rem;
    background: #f7fafc;
    border-radius: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.step-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 50, 132, 0.1);
}

.step-number {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #003284 0%, #1ba4ce 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 900;
    margin: 0 auto 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 50, 132, 0.3);
}

.step-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 1rem;
}

.step-description {
    color: #4a5568;
    line-height: 1.6;
}

.page-content {
    margin-bottom: 4rem;
    font-size: 1.125rem;
    line-height: 1.8;
    color: #2d3748;
}

.faq-preview {
    background: #f7fafc;
    padding: 3rem;
    border-radius: 20px;
    text-align: center;
    margin-bottom: 4rem;
}

.faq-preview h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 1rem;
}

.faq-preview p {
    color: #4a5568;
    margin-bottom: 2rem;
}

.page-cta {
    margin-top: 4rem;
}

.cta-card {
    background: linear-gradient(135deg, #003284 0%, #1ba4ce 100%);
    border-radius: 20px;
    padding: 4rem 2rem;
    text-align: center;
    color: white;
}

.cta-card h2 {
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 1rem;
    color: white;
}

.cta-card p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.btn-lg {
    padding: 1rem 2.5rem;
    font-size: 1.125rem;
}

@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .steps-grid {
        gap: 2rem;
    }
    
    .faq-preview {
        padding: 2rem 1.5rem;
    }
    
    .cta-card {
        padding: 3rem 1.5rem;
    }
    
    .cta-card h2 {
        font-size: 1.75rem;
    }
}
</style>

<?php
get_footer();
