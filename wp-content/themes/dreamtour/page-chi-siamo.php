<?php
/**
 * Template Name: Chi Siamo
 * Template per la pagina Chi Siamo
 * 
 * @package DreamTour
 */

get_header();
?>

<section class="about-section">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Hero About -->
            <div class="about-hero">
                <div class="about-hero-content">
                    <h1 class="about-title"><?php esc_html_e('Chi Siamo', 'dreamtour'); ?></h1>
                    <p class="about-subtitle"><?php esc_html_e('La storia dietro DreamTour', 'dreamtour'); ?></p>
                </div>
            </div>

            <!-- Sezione Fondatore -->
            <div class="about-founder">
                <div class="founder-grid">
                    <div class="founder-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large', array('class' => 'founder-photo')); ?>
                        <?php else : ?>
                            <div class="founder-photo-placeholder">
                                <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <p class="placeholder-text"><?php esc_html_e('Aggiungi foto in Immagine in Evidenza', 'dreamtour'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="founder-content">
                        <h2 class="founder-name">Shounny</h2>
                        <p class="founder-title"><?php esc_html_e('Fondatrice & Travel Expert', 'dreamtour'); ?></p>
                        
                        <div class="founder-story">
                            <?php if (get_the_content()) : ?>
                                <?php the_content(); ?>
                            <?php else : ?>
                                <p><?php esc_html_e('Ciao! Sono Shounny, la fondatrice di DreamTour. La mia passione per i viaggi è iniziata molti anni fa e da allora non ho mai smesso di esplorare il mondo.', 'dreamtour'); ?></p>
                                
                                <p><?php esc_html_e('Ho creato DreamTour con un obiettivo chiaro: offrire esperienze di viaggio autentiche e indimenticabili. Non si tratta solo di visitare luoghi, ma di creare ricordi che durano per tutta la vita.', 'dreamtour'); ?></p>
                                
                                <p><?php esc_html_e('Ogni tour che organizziamo è pensato con cura, prestando attenzione ai dettagli e alle esigenze di ogni viaggiatore. Voglio che ogni persona che viaggia con DreamTour si senta parte di una grande famiglia.', 'dreamtour'); ?></p>
                                
                                <p><?php esc_html_e('Unisciti a noi e scopri il mondo attraverso gli occhi di chi ama davvero viaggiare!', 'dreamtour'); ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="founder-cta">
                            <a href="<?php echo esc_url(home_url('/tours')); ?>" class="btn btn-primary">
                                <?php esc_html_e('Scopri i Nostri Tour', 'dreamtour'); ?>
                            </a>
                            <a href="<?php echo esc_url(home_url('/contatti')); ?>" class="btn btn-outline">
                                <?php esc_html_e('Contattaci', 'dreamtour'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sezione Valori -->
            <div class="about-values">
                <div class="section-header">
                    <h2 class="section-title"><?php esc_html_e('I Nostri Valori', 'dreamtour'); ?></h2>
                </div>
                
                <div class="values-grid">
                    <div class="value-item">
                        <div class="value-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </div>
                        <h3 class="value-title"><?php esc_html_e('Passione', 'dreamtour'); ?></h3>
                        <p class="value-description"><?php esc_html_e('Amiamo quello che facciamo e lo trasmettiamo in ogni viaggio che organizziamo.', 'dreamtour'); ?></p>
                    </div>
                    
                    <div class="value-item">
                        <div class="value-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 6v6l4 2"></path>
                            </svg>
                        </div>
                        <h3 class="value-title"><?php esc_html_e('Esperienza', 'dreamtour'); ?></h3>
                        <p class="value-description"><?php esc_html_e('Anni di esperienza nel settore ci permettono di offrire il meglio ad ogni viaggiatore.', 'dreamtour'); ?></p>
                    </div>
                    
                    <div class="value-item">
                        <div class="value-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="value-title"><?php esc_html_e('Comunità', 'dreamtour'); ?></h3>
                        <p class="value-description"><?php esc_html_e('Creiamo una famiglia di viaggiatori che condividono la stessa passione per l\'avventura.', 'dreamtour'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Gallery -->
            <?php 
            // Mostra galleria se sono presenti immagini nel contenuto
            $gallery_images = get_post_gallery_images();
            if (!empty($gallery_images)) : 
            ?>
            <div class="about-gallery">
                <div class="section-header">
                    <h2 class="section-title"><?php esc_html_e('Le Nostre Avventure', 'dreamtour'); ?></h2>
                </div>
                
                <div class="gallery-grid">
                    <?php foreach ($gallery_images as $image_url) : ?>
                        <div class="gallery-item">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php esc_attr_e('DreamTour Experience', 'dreamtour'); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- CTA Final -->
            <div class="about-cta">
                <div class="cta-card">
                    <h2><?php esc_html_e('Pronto a partire?', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Unisciti a noi per la tua prossima avventura indimenticabile', 'dreamtour'); ?></p>
                    <a href="<?php echo esc_url(home_url('/tours')); ?>" class="btn btn-primary btn-lg">
                        <?php esc_html_e('Esplora i Tour', 'dreamtour'); ?>
                    </a>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</section>

<style>
/* About Section Styles */
.about-section {
    padding: 4rem 0;
}

.about-hero {
    text-align: center;
    padding: 4rem 0;
    background: linear-gradient(135deg, #003284 0%, #1ba4ce 100%);
    border-radius: 20px;
    margin-bottom: 4rem;
    color: white;
}

.about-title {
    font-size: 3rem;
    font-weight: 900;
    margin-bottom: 1rem;
}

.about-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
}

.about-founder {
    margin-bottom: 6rem;
}

.founder-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 4rem;
    align-items: start;
}

.founder-image {
    position: sticky;
    top: 2rem;
}

.founder-photo {
    width: 100%;
    height: auto;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 50, 132, 0.2);
}

.founder-photo-placeholder {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 20px;
    padding: 4rem 2rem;
    text-align: center;
    border: 2px dashed #cbd5e0;
}

.founder-photo-placeholder svg {
    color: #a0aec0;
    margin-bottom: 1rem;
}

.placeholder-text {
    color: #718096;
    font-size: 0.875rem;
}

.founder-name {
    font-size: 2.5rem;
    font-weight: 900;
    color: #003284;
    margin-bottom: 0.5rem;
}

.founder-title {
    font-size: 1.25rem;
    color: #1ba4ce;
    font-weight: 600;
    margin-bottom: 2rem;
}

.founder-story {
    font-size: 1.125rem;
    line-height: 1.8;
    color: #2d3748;
    margin-bottom: 2rem;
}

.founder-story p {
    margin-bottom: 1.5rem;
}

.founder-cta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.about-values {
    background: #f7fafc;
    padding: 4rem;
    border-radius: 20px;
    margin-bottom: 4rem;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 3rem;
    margin-top: 3rem;
}

.value-item {
    text-align: center;
}

.value-icon {
    color: #1ba4ce;
    margin-bottom: 1.5rem;
}

.value-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 1rem;
}

.value-description {
    color: #4a5568;
    line-height: 1.6;
}

.about-gallery {
    margin-bottom: 4rem;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.gallery-item {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.gallery-item:hover {
    transform: translateY(-5px);
}

.gallery-item img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    display: block;
}

.about-cta {
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
    .founder-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .founder-image {
        position: relative;
        top: 0;
    }
    
    .about-title {
        font-size: 2rem;
    }
    
    .founder-name {
        font-size: 2rem;
    }
    
    .about-values {
        padding: 2rem 1.5rem;
    }
    
    .values-grid {
        gap: 2rem;
    }
    
    .founder-cta {
        flex-direction: column;
    }
    
    .founder-cta .btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<?php
get_footer();
