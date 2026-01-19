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

<?php
get_footer();
