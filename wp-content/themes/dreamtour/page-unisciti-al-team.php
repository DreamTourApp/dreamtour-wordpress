<?php
/**
 * Template Name: Unisciti al Team
 * Template per la pagina Unisciti al Team
 * 
 * @package DreamTour
 */

get_header();
?>

<section class="join-team-section">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Hero -->
            <div class="page-hero">
                <h1 class="page-title"><?php esc_html_e('Unisciti al Team', 'dreamtour'); ?></h1>
                <p class="page-subtitle"><?php esc_html_e('Lavora con noi e trasforma la tua passione per i viaggi in una carriera', 'dreamtour'); ?></p>
            </div>

            <!-- Content -->
            <div class="page-content">
                <?php the_content(); ?>
            </div>

            <!-- Perché Lavorare con Noi -->
            <div class="benefits-section">
                <h2 class="section-title"><?php esc_html_e('Perché lavorare con DreamTour?', 'dreamtour'); ?></h2>
                
                <div class="benefits-grid">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <h3><?php esc_html_e('Viaggia il Mondo', 'dreamtour'); ?></h3>
                        <p><?php esc_html_e('Accompagna i nostri gruppi nelle destinazioni più belle del mondo', 'dreamtour'); ?></p>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3><?php esc_html_e('Team Giovane', 'dreamtour'); ?></h3>
                        <p><?php esc_html_e('Lavora in un ambiente dinamico e stimolante con colleghi appassionati', 'dreamtour'); ?></p>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                            </svg>
                        </div>
                        <h3><?php esc_html_e('Crescita Professionale', 'dreamtour'); ?></h3>
                        <p><?php esc_html_e('Formazione continua e opportunità di crescita nel settore turistico', 'dreamtour'); ?></p>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <h3><?php esc_html_e('Compenso Competitivo', 'dreamtour'); ?></h3>
                        <p><?php esc_html_e('Retribuzione adeguata e bonus basati sulle performance', 'dreamtour'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Posizioni Aperte -->
            <div class="positions-section">
                <h2 class="section-title"><?php esc_html_e('Posizioni Aperte', 'dreamtour'); ?></h2>
                
                <div class="positions-list">
                    <div class="position-card">
                        <h3><?php esc_html_e('Coordinatore Tour', 'dreamtour'); ?></h3>
                        <p class="position-type"><?php esc_html_e('Part-time / Full-time', 'dreamtour'); ?></p>
                        <p><?php esc_html_e('Cerchiamo coordinatori esperti per accompagnare i nostri gruppi in tour nazionali e internazionali.', 'dreamtour'); ?></p>
                        <ul class="position-requirements">
                            <li><?php esc_html_e('Esperienza nel settore turistico', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Conoscenza di almeno 2 lingue straniere', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Ottime capacità comunicative', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Disponibilità a viaggiare', 'dreamtour'); ?></li>
                        </ul>
                    </div>

                    <div class="position-card">
                        <h3><?php esc_html_e('Social Media Manager', 'dreamtour'); ?></h3>
                        <p class="position-type"><?php esc_html_e('Part-time', 'dreamtour'); ?></p>
                        <p><?php esc_html_e('Gestisci i nostri canali social e crea contenuti coinvolgenti per la community.', 'dreamtour'); ?></p>
                        <ul class="position-requirements">
                            <li><?php esc_html_e('Esperienza nella gestione social', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Creatività e passione per i viaggi', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Competenze grafiche (Canva, Photoshop)', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Conoscenza analytics e advertising', 'dreamtour'); ?></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Form Candidatura -->
            <div class="application-form-section">
                <h2 class="section-title"><?php esc_html_e('Invia la tua candidatura', 'dreamtour'); ?></h2>
                
                <div class="contact-info-box">
                    <p><?php esc_html_e('Per candidarti, invia il tuo CV e una lettera di presentazione a:', 'dreamtour'); ?></p>
                    <a href="mailto:lavora@dreamtourviaggi.it" class="email-link">lavora@dreamtourviaggi.it</a>
                    <p class="note"><?php esc_html_e('Specifica nell\'oggetto la posizione per cui ti candidi', 'dreamtour'); ?></p>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</section>

<?php
get_footer();
