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

<style>
/* Unisciti al Team Styles - Based on Chi Siamo */

.join-team-section {
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

.page-content {
    margin-bottom: 4rem;
    font-size: 1.125rem;
    line-height: 1.8;
    color: #2d3748;
    text-align: center;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #003284;
    text-align: center;
    margin-bottom: 3rem;
}

.benefits-section {
    margin-bottom: 5rem;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 3rem;
}

.benefit-item {
    text-align: center;
    padding: 2.5rem 2rem;
    background: #f7fafc;
    border-radius: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.benefit-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 50, 132, 0.15);
}

.benefit-icon {
    color: #1ba4ce;
    margin-bottom: 1.5rem;
}

.benefit-item h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 1rem;
}

.benefit-item p {
    color: #4a5568;
    line-height: 1.6;
}

.positions-section {
    margin-bottom: 5rem;
}

.positions-list {
    display: grid;
    gap: 2.5rem;
}

.position-card {
    background: #f7fafc;
    border-radius: 20px;
    padding: 3rem;
    border-left: 4px solid #1ba4ce;
}

.position-card h3 {
    font-size: 2rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 0.5rem;
}

.position-type {
    color: #1ba4ce;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 1.5rem;
}

.position-card > p {
    color: #2d3748;
    line-height: 1.8;
    margin-bottom: 1.5rem;
}

.position-requirements {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 0.75rem;
}

.position-requirements li {
    padding-left: 2rem;
    position: relative;
    color: #4a5568;
    line-height: 1.6;
}

.position-requirements li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #1ba4ce;
    font-weight: 900;
    font-size: 1.25rem;
}

.application-form-section {
    margin-bottom: 4rem;
}

.contact-info-box {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 20px;
    padding: 3rem;
    text-align: center;
    border: 2px solid #e2e8f0;
}

.contact-info-box p {
    color: #4a5568;
    font-size: 1.125rem;
    margin-bottom: 1rem;
}

.email-link {
    display: inline-block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1ba4ce;
    text-decoration: none;
    margin: 1rem 0;
    transition: color 0.3s ease;
}

.email-link:hover {
    color: #003284;
}

.note {
    font-size: 0.875rem;
    color: #718096;
    font-style: italic;
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
    
    .section-title {
        font-size: 1.75rem;
    }
    
    .benefits-grid {
        gap: 2rem;
    }
    
    .position-card {
        padding: 2rem;
    }
    
    .contact-info-box {
        padding: 2rem;
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
