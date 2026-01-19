<?php
/**
 * Template Name: Coordinatori
 * Template per la pagina Coordinatori
 * 
 * @package DreamTour
 */

get_header();
?>

<section class="coordinators-section">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Hero -->
            <div class="page-hero">
                <h1 class="page-title"><?php esc_html_e('I Nostri Coordinatori', 'dreamtour'); ?></h1>
                <p class="page-subtitle"><?php esc_html_e('Il team di esperti che rende ogni viaggio indimenticabile', 'dreamtour'); ?></p>
            </div>

            <!-- Content -->
            <div class="page-content">
                <?php the_content(); ?>
            </div>

            <!-- Coordinatori Grid -->
            <div class="coordinators-grid">
                
                <!-- Coordinatore 1 -->
                <div class="coordinator-card">
                    <div class="coordinator-image">
                        <div class="coordinator-photo-placeholder">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    </div>
                    <div class="coordinator-info">
                        <h3 class="coordinator-name">Shounny</h3>
                        <p class="coordinator-role"><?php esc_html_e('Coordinatrice Senior', 'dreamtour'); ?></p>
                        <p class="coordinator-bio">
                            <?php esc_html_e('Con oltre 10 anni di esperienza nel settore, Shounny coordina i nostri tour più importanti con passione e professionalità.', 'dreamtour'); ?>
                        </p>
                    </div>
                </div>

                <!-- Placeholder per altri coordinatori -->
                <div class="coordinator-card placeholder">
                    <div class="coordinator-image">
                        <div class="coordinator-photo-placeholder">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    </div>
                    <div class="coordinator-info">
                        <h3 class="coordinator-name"><?php esc_html_e('In arrivo', 'dreamtour'); ?></h3>
                        <p class="coordinator-role"><?php esc_html_e('Nuovo coordinatore', 'dreamtour'); ?></p>
                        <p class="coordinator-bio">
                            <?php esc_html_e('Stiamo selezionando nuovi coordinatori per ampliare il nostro team.', 'dreamtour'); ?>
                        </p>
                    </div>
                </div>

                <div class="coordinator-card placeholder">
                    <div class="coordinator-image">
                        <div class="coordinator-photo-placeholder">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    </div>
                    <div class="coordinator-info">
                        <h3 class="coordinator-name"><?php esc_html_e('In arrivo', 'dreamtour'); ?></h3>
                        <p class="coordinator-role"><?php esc_html_e('Nuovo coordinatore', 'dreamtour'); ?></p>
                        <p class="coordinator-bio">
                            <?php esc_html_e('Stiamo selezionando nuovi coordinatori per ampliare il nostro team.', 'dreamtour'); ?>
                        </p>
                    </div>
                </div>

            </div>

            <!-- CTA Unisciti -->
            <div class="page-cta">
                <div class="cta-card">
                    <h2><?php esc_html_e('Vuoi entrare a far parte del team?', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Cerchiamo persone appassionate di viaggi e con esperienza nel coordinamento di gruppi', 'dreamtour'); ?></p>
                    <a href="<?php echo esc_url(home_url('/unisciti-al-team')); ?>" class="btn btn-primary btn-lg">
                        <?php esc_html_e('Candidati Ora', 'dreamtour'); ?>
                    </a>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</section>

<style>
/* Coordinatori Styles - Based on Chi Siamo */

.coordinators-section {
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

.coordinators-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 3rem;
    margin-bottom: 4rem;
}

.coordinator-card {
    background: #f7fafc;
    border-radius: 20px;
    padding: 2.5rem;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.coordinator-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 50, 132, 0.15);
}

.coordinator-card.placeholder {
    opacity: 0.6;
    border: 2px dashed #cbd5e0;
    background: transparent;
}

.coordinator-image {
    margin-bottom: 1.5rem;
}

.coordinator-photo-placeholder {
    width: 150px;
    height: 150px;
    margin: 0 auto;
    background: linear-gradient(135deg, #edf2f7 0%, #e2e8f0 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(0, 50, 132, 0.1);
}

.coordinator-photo-placeholder svg {
    color: #a0aec0;
}

.coordinator-name {
    font-size: 1.75rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 0.5rem;
}

.coordinator-role {
    font-size: 1.125rem;
    color: #1ba4ce;
    font-weight: 600;
    margin-bottom: 1rem;
}

.coordinator-bio {
    color: #4a5568;
    line-height: 1.6;
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
    
    .coordinators-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
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
