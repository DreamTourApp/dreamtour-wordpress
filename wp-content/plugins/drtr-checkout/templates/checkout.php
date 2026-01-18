<?php
/**
 * Template Checkout Page
 * 
 * @package DRTR_Gestione_Tours
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="drtr-checkout-container">
    <div class="drtr-checkout-wrapper">
        
        <!-- Riepilogo Tour -->
        <div class="checkout-summary">
            <h2><?php _e('Riepilogo Prenotazione', 'drtr-tours'); ?></h2>
            
            <div class="tour-summary-card">
                <?php if (has_post_thumbnail($tour_id)) : ?>
                    <div class="tour-summary-image">
                        <?php echo get_the_post_thumbnail($tour_id, 'medium'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="tour-summary-details">
                    <h3>
                        <?php 
                        echo esc_html($tour->post_title);
                        // Add start date and time if available
                        $tour_start_date = get_post_meta($tour_id, '_drtr_start_date', true) ?: get_post_meta($tour_id, 'start_date', true);
                        if ($tour_start_date) {
                            $date_obj = DateTime::createFromFormat('Y-m-d\TH:i', $tour_start_date);
                            if ($date_obj) {
                                echo ' - ' . $date_obj->format('d/m/y');
                            }
                        }
                        ?>
                    </h3>
                    
                    <div class="summary-row">
                        <span class="label"><?php _e('Adulti:', 'drtr-tours'); ?></span>
                        <span class="value"><?php echo esc_html($adults); ?></span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="label"><?php _e('Bambini (0-12):', 'drtr-tours'); ?></span>
                        <span class="value"><?php echo esc_html($children); ?></span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="label"><?php _e('Prezzo Adulto:', 'drtr-tours'); ?></span>
                        <span class="value">€<?php echo number_format($tour_price, 2, ',', '.'); ?></span>
                    </div>
                    
                    <?php if ($children > 0) : ?>
                        <div class="summary-row">
                            <span class="label"><?php _e('Prezzo Bambino:', 'drtr-tours'); ?></span>
                            <span class="value">€<?php echo number_format($tour_child_price, 2, ',', '.'); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <div class="summary-row summary-subtotal">
                        <span class="label"><?php _e('Subtotale:', 'drtr-tours'); ?></span>
                        <span class="value">€<?php echo number_format($subtotal, 2, ',', '.'); ?></span>
                    </div>
                    
                    <?php if ($payment_type === 'deposit') : ?>
                        <div class="summary-row">
                            <span class="label"><?php _e('Acconto (50%):', 'drtr-tours'); ?></span>
                            <span class="value">€<?php echo number_format($deposit, 2, ',', '.'); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="summary-row summary-total">
                        <span class="label"><?php _e('Totale da Pagare:', 'drtr-tours'); ?></span>
                        <span class="value">€<?php echo number_format($total, 2, ',', '.'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Checkout -->
        <div class="checkout-form">
            <h2><?php _e('Dati di Fatturazione', 'drtr-tours'); ?></h2>
            
            <form id="drtr-checkout-form">
                <input type="hidden" name="tour_id" value="<?php echo esc_attr($tour_id); ?>">
                <input type="hidden" name="adults" value="<?php echo esc_attr($adults); ?>">
                <input type="hidden" name="children" value="<?php echo esc_attr($children); ?>">
                <input type="hidden" name="payment_type" value="<?php echo esc_attr($payment_type); ?>">
                <input type="hidden" name="subtotal" value="<?php echo esc_attr($subtotal); ?>">
                <input type="hidden" name="deposit" value="<?php echo esc_attr($deposit); ?>">
                <input type="hidden" name="total" value="<?php echo esc_attr($total); ?>">
                
                <!-- Dati Personali -->
                <div class="form-section">
                    <h3><?php _e('Informazioni Personali', 'drtr-tours'); ?></h3>
                    
                    <div class="drtr-info-box">
                        <i class="dashicons dashicons-info"></i>
                        <p><?php _e('Le tue informazioni personali verranno utilizzate per associare l\'ordine al tuo account e inviarti la conferma via email.', 'drtr-tours'); ?></p>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name"><?php _e('Nome *', 'drtr-tours'); ?></label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($user_first_name); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="last_name"><?php _e('Cognome *', 'drtr-tours'); ?></label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($user_last_name); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email"><?php _e('Email *', 'drtr-tours'); ?></label>
                            <input type="email" id="email" name="email" value="<?php echo esc_attr($user_email); ?>" required <?php echo is_user_logged_in() ? 'readonly' : ''; ?>>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" style="flex: 0 0 140px;">
                            <label for="phone_prefix"><?php _e('Prefisso *', 'drtr-tours'); ?></label>
                            <select id="phone_prefix" name="phone_prefix" required>
                                <option value="+39">+39 (IT)</option>
                                <option value="+34">+34 (ES)</option>
                                <option value="+33">+33 (FR)</option>
                                <option value="+49">+49 (DE)</option>
                                <option value="+44">+44 (UK)</option>
                                <option value="+1">+1 (US)</option>
                            </select>
                        </div>
                        
                        <div class="form-group" style="flex: 1;">
                            <label for="phone"><?php _e('Numero di Telefono *', 'drtr-tours'); ?></label>
                            <input type="tel" id="phone" name="phone" value="<?php echo esc_attr($user_phone); ?>" required>
                        </div>
                    </div>
                    
                    <?php if (!is_user_logged_in()) : ?>
                    <div class="drtr-divider">
                        <span><?php _e('oppure', 'drtr-tours'); ?></span>
                    </div>
                    
                    <div class="drtr-account-options">
                        <div class="account-option-box">
                            <label class="account-option-label">
                                <input type="checkbox" id="create_account" name="create_account" value="1">
                                <div class="account-option-content">
                                    <strong><?php _e('Crea un account', 'drtr-tours'); ?></strong>
                                    <p><?php _e('Registrati per gestire le tue prenotazioni e accedere a offerte esclusive', 'drtr-tours'); ?></p>
                                </div>
                            </label>
                        </div>
                        
                        <div id="password-fields" class="password-fields" style="display: none;">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="account_password"><?php _e('Password *', 'drtr-tours'); ?></label>
                                    <input type="password" id="account_password" name="account_password" minlength="8">
                                    <small class="form-hint"><?php _e('Minimo 8 caratteri', 'drtr-tours'); ?></small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="account_password_confirm"><?php _e('Conferma Password *', 'drtr-tours'); ?></label>
                                    <input type="password" id="account_password_confirm" name="account_password_confirm" minlength="8">
                                </div>
                            </div>
                        </div>
                        
                        <p class="drtr-login-link">
                            <?php _e('Hai già un account?', 'drtr-tours'); ?> 
                            <a href="<?php echo esc_url(home_url('/area-riservata')); ?>"><?php _e('Accedi', 'drtr-tours'); ?></a>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Metodo Pagamento -->
                <div class="form-section">
                    <h3><?php _e('Metodo di Pagamento', 'drtr-tours'); ?></h3>
                    
                    <div class="payment-methods">
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="bank_transfer" checked>
                            <div class="payment-method-content">
                                <strong><?php _e('Bonifico Bancario', 'drtr-tours'); ?></strong>
                                <p><?php _e('Effettua un bonifico bancario sul nostro conto. La prenotazione sarà confermata al ricevimento del pagamento.', 'drtr-tours'); ?></p>
                            </div>
                        </label>
                        
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="credit_card">
                            <div class="payment-method-content">
                                <strong><?php _e('Carta di Credito', 'drtr-tours'); ?></strong>
                                <p><?php _e('Paga in modo sicuro con la tua carta di credito.', 'drtr-tours'); ?></p>
                            </div>
                        </label>
                    </div>
                    
                    <!-- Dati Bonifico -->
                    <div id="bank-transfer-details" class="bank-transfer-info">
                        <div class="info-box">
                            <h4><?php _e('Dati per il Bonifico Bancario', 'drtr-tours'); ?></h4>
                            <p><strong><?php _e('Intestatario:', 'drtr-tours'); ?></strong> DreamTour Viaggi</p>
                            <p><strong><?php _e('IBAN:', 'drtr-tours'); ?></strong> IT00 X000 0000 0000 0000 0000 000</p>
                            <p><strong><?php _e('Causale:', 'drtr-tours'); ?></strong> Prenotazione <?php echo esc_html($tour->post_title); ?></p>
                            <p><strong><?php _e('Importo:', 'drtr-tours'); ?></strong> €<?php echo number_format($total, 2, ',', '.'); ?></p>
                            <p class="info-note"><?php _e('Ti preghiamo di effettuare il bonifico entro 3 giorni lavorativi.', 'drtr-tours'); ?></p>
                        </div>
                    </div>
                    
                    <!-- Form Carta di Credito -->
                    <div id="credit-card-form" class="credit-card-form" style="display: none;">
                        <p class="info-note"><?php _e('Il pagamento con carta di credito sarà disponibile a breve.', 'drtr-tours'); ?></p>
                        <!-- Qui si può integrare Stripe, PayPal, ecc. -->
                    </div>
                </div>
                
                <!-- Privacy e Termini -->
                <div class="form-section">
                    <label class="checkbox-label">
                        <input type="checkbox" name="privacy" required>
                        <?php _e('Accetto la', 'drtr-tours'); ?> <a href="/privacy-policy" target="_blank"><?php _e('Privacy Policy', 'drtr-tours'); ?></a>
                    </label>
                    
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required>
                        <?php _e('Accetto i', 'drtr-tours'); ?> <a href="/termini-condizioni" target="_blank"><?php _e('Termini e Condizioni', 'drtr-tours'); ?></a>
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary btn-large" id="submit-checkout">
                        <?php _e('Conferma Prenotazione', 'drtr-tours'); ?>
                    </button>
                </div>
                
                <div id="checkout-message" class="checkout-message" style="display: none;"></div>
            </form>
        </div>
    </div>
</div>

<style>
.drtr-checkout-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.drtr-checkout-wrapper {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 30px;
}

@media (max-width: 768px) {
    .drtr-checkout-wrapper {
        grid-template-columns: 1fr;
    }
}

.checkout-summary,
.checkout-form {
    background: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.checkout-summary h2,
.checkout-form h2 {
    margin-top: 0;
    color: #003284;
    font-size: 24px;
    margin-bottom: 20px;
}

.tour-summary-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
}

.tour-summary-image img {
    width: 100%;
    height: auto;
    display: block;
}

.tour-summary-details {
    padding: 20px;
}

.tour-summary-details h3 {
    margin: 0 0 15px;
    color: #003284;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}

.summary-row .label {
    color: #666;
}

.summary-row .value {
    font-weight: 600;
}

.summary-row hr {
    margin: 15px 0;
    border: none;
    border-top: 1px solid #e0e0e0;
}

.summary-subtotal {
    font-size: 16px;
    margin-top: 10px;
}

.summary-total {
    font-size: 20px;
    color: #003284;
    margin-top: 10px;
    padding-top: 15px;
    border-top: 2px solid #003284;
}

.form-section {
    margin-bottom: 30px;
}

.form-section h3 {
    color: #003284;
    font-size: 18px;
    margin-bottom: 15px;
}

.form-row {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.form-group {
    flex: 1;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #003284;
}

.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.payment-method {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.payment-method:hover {
    border-color: #003284;
    background-color: #f8f9fa;
}

.payment-method input[type="radio"] {
    margin-top: 4px;
}

.payment-method input[type="radio"]:checked + .payment-method-content {
    color: #003284;
}

.payment-method-content p {
    margin: 5px 0 0;
    font-size: 13px;
    color: #666;
}

.bank-transfer-info {
    margin-top: 20px;
}

.info-box {
    background-color: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 20px;
}

.info-box h4 {
    margin-top: 0;
    color: #856404;
}

.info-box p {
    margin: 10px 0;
}

.info-note {
    font-size: 13px;
    color: #856404;
    font-style: italic;
    margin-top: 15px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.checkbox-label input[type="checkbox"] {
    width: auto;
}

.form-actions {
    margin-top: 30px;
}

.btn-primary {
    background-color: #003284;
    color: white;
    border: none;
    padding: 15px 40px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%;
}

.btn-primary:hover {
    background-color: #002266;
}

.btn-primary:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

.checkout-message {
    margin-top: 20px;
    padding: 15px;
    border-radius: 5px;
}

.checkout-message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.checkout-message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Info Box */
.drtr-info-box {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: linear-gradient(135deg, #e3f2fd 0%, #fff 100%);
    border-left: 4px solid #003284;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}

.drtr-info-box .dashicons {
    color: #003284;
    font-size: 20px;
    width: 20px;
    height: 20px;
    flex-shrink: 0;
    margin-top: 2px;
}

.drtr-info-box p {
    margin: 0;
    color: #1e3a5f;
    font-size: 14px;
    line-height: 1.6;
}

/* Divider con "oppure" */
.drtr-divider {
    position: relative;
    text-align: center;
    margin: 30px 0 25px;
}

.drtr-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(to right, transparent, #e0e0e0, transparent);
}

.drtr-divider span {
    position: relative;
    background: white;
    padding: 0 20px;
    color: #718096;
    font-size: 14px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Account Options */
.drtr-account-options {
    margin-top: 20px;
}

.account-option-box {
    background: #f7fafc;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 18px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.account-option-box:hover {
    border-color: #003284;
    background: #ffffff;
}

.account-option-label {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    cursor: pointer;
    margin: 0;
}

.account-option-label input[type="checkbox"] {
    margin-top: 4px;
    width: 20px;
    height: 20px;
    cursor: pointer;
    flex-shrink: 0;
}

.account-option-content {
    flex: 1;
}

.account-option-content strong {
    display: block;
    font-size: 16px;
    color: #2d3748;
    margin-bottom: 5px;
}

.account-option-content p {
    margin: 0;
    font-size: 13px;
    color: #718096;
    line-height: 1.5;
}

.password-fields {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-hint {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #718096;
    font-style: italic;
}

.drtr-login-link {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
    color: #4a5568;
}

.drtr-login-link a {
    color: #003284;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
}

.drtr-login-link a:hover {
    color: #1ba4ce;
    text-decoration: underline;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Toggle create account checkbox
    $('#create_account').on('change', function() {
        if ($(this).is(':checked')) {
            $('#password-fields').slideDown(300);
            $('#account_password, #account_password_confirm').prop('required', true);
        } else {
            $('#password-fields').slideUp(300);
            $('#account_password, #account_password_confirm').prop('required', false).val('');
        }
    });
    
    // Toggle payment method details
    $('input[name="payment_method"]').on('change', function() {
        const method = $(this).val();
        
        if (method === 'bank_transfer') {
            $('#bank-transfer-details').show();
            $('#credit-card-form').hide();
        } else {
            $('#bank-transfer-details').hide();
            $('#credit-card-form').show();
        }
    });
    
    // Submit checkout form
    $('#drtr-checkout-form').on('submit', function(e) {
        e.preventDefault();
        console.log('=== CHECKOUT SUBMIT INIZIATO ===');
        
        const $form = $(this);
        const $submitBtn = $('#submit-checkout');
        const $message = $('#checkout-message');
        
        // Validate password if creating account
        if ($('#create_account').is(':checked')) {
            const password = $('#account_password').val();
            const confirmPassword = $('#account_password_confirm').val();
            
            if (!password || password.length < 8) {
                $message.removeClass('success').addClass('error')
                    .html('La password deve essere di almeno 8 caratteri.')
                    .show();
                return;
            }
            
            if (password !== confirmPassword) {
                $message.removeClass('success').addClass('error')
                    .html('Le password non coincidono.')
                    .show();
                return;
            }
        }
        
        console.log('Form element:', $form[0]);
        console.log('dreamtourData:', dreamtourData);
        
        // Disable submit button
        $submitBtn.prop('disabled', true).text(<?php echo wp_json_encode(__('Elaborazione...', 'drtr-checkout')); ?>);
        
        // Prepare data
        const formData = new FormData($form[0]);
        formData.append('action', 'drtr_process_checkout');
        formData.append('nonce', dreamtourData.nonce);
        
        // Log FormData contents
        console.log('FormData contents:');
        for (let pair of formData.entries()) {
            console.log('  ' + pair[0] + ': ' + pair[1]);
        }
        
        console.log('AJAX URL:', dreamtourData.ajaxUrl);
        console.log('Inviando richiesta AJAX...');
        
        // AJAX request
        $.ajax({
            url: dreamtourData.ajaxUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function(jqXHR, settings) {
                console.log('beforeSend - URL:', settings.url);
                console.log('beforeSend - Type:', settings.type);
            },
            success: function(response) {
                console.log('=== AJAX SUCCESS ===');
                console.log('Response completa:', response);
                console.log('Response type:', typeof response);
                console.log('Response.success:', response.success);
                console.log('Response.data:', response.data);
                
                if (response.success) {
                    console.log('Prenotazione creata con successo!');
                    console.log('Message:', response.data.message);
                    console.log('Booking ID:', response.data.booking_id);
                    console.log('Redirect:', response.data.redirect);
                    
                    $message.removeClass('error').addClass('success')
                        .html(response.data.message).show();
                    
                    // Redirect dopo 2 secondi
                    setTimeout(function() {
                        console.log('Redirecting to:', response.data.redirect);
                        window.location.href = response.data.redirect;
                    }, 2000);
                } else {
                    console.log('Errore dal server:', response.data.message);
                    $message.removeClass('success').addClass('error')
                        .html(response.data.message).show();
                    $submitBtn.prop('disabled', false).text(<?php echo wp_json_encode(__('Conferma Prenotazione', 'drtr-checkout')); ?>);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('=== AJAX ERROR ===');
                console.log('jqXHR:', jqXHR);
                console.log('Status:', jqXHR.status);
                console.log('Status Text:', jqXHR.statusText);
                console.log('Response Text:', jqXHR.responseText);
                console.log('textStatus:', textStatus);
                console.log('errorThrown:', errorThrown);
                console.log('Ready State:', jqXHR.readyState);
                
                $message.removeClass('success').addClass('error')
                    .html(<?php echo wp_json_encode(__('Errore durante l\'elaborazione. Riprova.', 'drtr-checkout')); ?>).show();
                $submitBtn.prop('disabled', false).text(<?php echo wp_json_encode(__('Conferma Prenotazione', 'drtr-checkout')); ?>);
            },
            complete: function(jqXHR, textStatus) {
                console.log('=== AJAX COMPLETE ===');
                console.log('textStatus:', textStatus);
                console.log('Response Headers:', jqXHR.getAllResponseHeaders());
            }
        });
    });
});
</script>
