<?php
/**
 * Template Name: Contatti
 * Template per la pagina Contatti
 * 
 * @package DreamTour
 */

get_header();
?>

<section class="contact-section">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Header -->
            <div class="contact-header">
                <h1 class="contact-title"><?php esc_html_e('Contattaci', 'dreamtour'); ?></h1>
                <p class="contact-subtitle"><?php esc_html_e('Siamo qui per aiutarti a pianificare il tuo prossimo viaggio', 'dreamtour'); ?></p>
            </div>

            <div class="contact-grid">
                <!-- Info Contatti -->
                <div class="contact-info">
                    <h2><?php esc_html_e('Informazioni di Contatto', 'dreamtour'); ?></h2>
                    
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <div>
                            <h3><?php esc_html_e('Email', 'dreamtour'); ?></h3>
                            <p><a href="mailto:<?php echo esc_attr(get_option('admin_email')); ?>"><?php echo esc_html(get_option('admin_email')); ?></a></p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3><?php esc_html_e('Telefono', 'dreamtour'); ?></h3>
                            <p><a href="tel:+390000000000">+39 000 000 0000</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 6v6l4 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h3><?php esc_html_e('Orari', 'dreamtour'); ?></h3>
                            <p><?php esc_html_e('Lun - Ven: 9:00 - 18:00', 'dreamtour'); ?><br>
                            <?php esc_html_e('Sab - Dom: Chiuso', 'dreamtour'); ?></p>
                        </div>
                    </div>

                    <div class="contact-social">
                        <h3><?php esc_html_e('Seguici', 'dreamtour'); ?></h3>
                        <div class="social-links">
                            <a href="#" class="social-link" aria-label="Facebook">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-link" aria-label="Instagram">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-link" aria-label="WhatsApp">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Form Contatti -->
                <div class="contact-form-wrapper">
                    <h2><?php esc_html_e('Invia un Messaggio', 'dreamtour'); ?></h2>
                    
                    <form id="contact-form" class="contact-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact-name"><?php esc_html_e('Nome *', 'dreamtour'); ?></label>
                                <input type="text" id="contact-name" name="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact-email"><?php esc_html_e('Email *', 'dreamtour'); ?></label>
                                <input type="email" id="contact-email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact-phone"><?php esc_html_e('Telefono', 'dreamtour'); ?></label>
                                <input type="tel" id="contact-phone" name="phone">
                            </div>
                            
                            <div class="form-group">
                                <label for="contact-subject"><?php esc_html_e('Oggetto *', 'dreamtour'); ?></label>
                                <input type="text" id="contact-subject" name="subject" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-message"><?php esc_html_e('Messaggio *', 'dreamtour'); ?></label>
                            <textarea id="contact-message" name="message" rows="6" required></textarea>
                        </div>
                        
                        <div class="form-message" id="form-message"></div>
                        
                        <button type="submit" class="btn btn-primary btn-lg" id="contact-submit">
                            <span class="btn-text"><?php esc_html_e('Invia Messaggio', 'dreamtour'); ?></span>
                            <span class="btn-loading" style="display: none;">
                                <span class="spinner"></span>
                                <?php esc_html_e('Invio...', 'dreamtour'); ?>
                            </span>
                        </button>
                    </form>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</section>

<style>
/* Contact Section */
.contact-section {
    padding: 4rem 0;
}

.contact-header {
    text-align: center;
    margin-bottom: 4rem;
    position: relative;
    padding-bottom: 2rem;
}

.contact-header::after {
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

.contact-title {
    font-size: 3rem;
    font-weight: 900;
    color: #003284;
    margin-bottom: 1rem;
}

.contact-subtitle {
    font-size: 1.25rem;
    color: #4a5568;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 4rem;
    align-items: start;
}

/* Contact Info */
.contact-info {
    background: #f7fafc;
    padding: 2.5rem;
    border-radius: 16px;
    position: sticky;
    top: 2rem;
}

.contact-info h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 2rem;
}

.contact-info-item {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e2e8f0;
}

.contact-info-item:last-of-type {
    border-bottom: none;
}

.contact-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #003284 0%, #1ba4ce 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.contact-info-item h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.contact-info-item p {
    color: #4a5568;
    line-height: 1.6;
}

.contact-info-item a {
    color: #1ba4ce;
    text-decoration: none;
    transition: color 0.2s;
}

.contact-info-item a:hover {
    color: #003284;
}

.contact-social {
    margin-top: 2rem;
}

.contact-social h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1rem;
}

.social-links {
    display: flex;
    gap: 1rem;
}

.social-link {
    width: 48px;
    height: 48px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4a5568;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.social-link:hover {
    background: linear-gradient(135deg, #003284 0%, #1ba4ce 100%);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 50, 132, 0.3);
}

/* Contact Form */
.contact-form-wrapper {
    background: white;
    padding: 2.5rem;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.contact-form-wrapper h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 2rem;
}

.contact-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.contact-form .form-group {
    margin-bottom: 0;
}

.contact-form label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: inherit;
}

.contact-form input:focus,
.contact-form textarea:focus {
    outline: none;
    border-color: #1ba4ce;
    box-shadow: 0 0 0 3px rgba(27, 164, 206, 0.1);
}

.contact-form textarea {
    resize: vertical;
    min-height: 150px;
}

.form-message {
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    display: none;
}

.form-message.success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    display: block;
}

.form-message.error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
    display: block;
}

.btn-lg {
    width: 100%;
    padding: 1rem 2rem;
    font-size: 1.125rem;
}

.btn-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .contact-info {
        position: relative;
        top: 0;
    }
    
    .contact-title {
        font-size: 2rem;
    }
    
    .contact-form .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#contact-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitBtn = $('#contact-submit');
        const $message = $('#form-message');
        
        // Disable submit button
        $submitBtn.prop('disabled', true);
        $submitBtn.find('.btn-text').hide();
        $submitBtn.find('.btn-loading').show();
        $message.hide().removeClass('success error');
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'dreamtour_send_contact',
                nonce: '<?php echo wp_create_nonce('dreamtour_contact_nonce'); ?>',
                name: $('#contact-name').val(),
                email: $('#contact-email').val(),
                phone: $('#contact-phone').val(),
                subject: $('#contact-subject').val(),
                message: $('#contact-message').val()
            },
            success: function(response) {
                if (response.success) {
                    $message.addClass('success').text(response.data.message).show();
                    $form[0].reset();
                } else {
                    $message.addClass('error').text(response.data.message).show();
                }
            },
            error: function() {
                $message.addClass('error').text('<?php esc_html_e('Errore durante l\'invio. Riprova più tardi.', 'dreamtour'); ?>').show();
            },
            complete: function() {
                $submitBtn.prop('disabled', false);
                $submitBtn.find('.btn-text').show();
                $submitBtn.find('.btn-loading').hide();
            }
        });
    });
});
</script>

<?php
get_footer();
