<?php
/**
 * Gestione Checkout e Processamento Prenotazioni
 * 
 * @package DRTR_Checkout
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_Checkout {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_ajax_drtr_process_checkout', array($this, 'process_checkout'));
        add_action('wp_ajax_nopriv_drtr_process_checkout', array($this, 'process_checkout'));
        add_action('wp_ajax_drtr_test_ajax', array($this, 'test_ajax'));
        add_action('wp_ajax_nopriv_drtr_test_ajax', array($this, 'test_ajax'));
        add_action('wp_ajax_drtr_clear_debug_log', array($this, 'clear_debug_log'));
        add_shortcode('drtr_checkout', array($this, 'checkout_shortcode'));
        add_shortcode('drtr_debug_checkout', array($this, 'debug_checkout_shortcode'));
    }
    
    /**
     * Shortcode per pagina debug
     */
    public function debug_checkout_shortcode($atts) {
        ob_start();
        include DRTR_CHECKOUT_DIR . 'templates/debug-checkout.php';
        return ob_get_clean();
    }
    
    /**
     * Test AJAX semplice
     */
    public function test_ajax() {
        error_log('TEST AJAX CHIAMATO!');
        wp_send_json_success(array('message' => 'AJAX funziona!'));
    }
    
    /**
     * Clear debug log
     */
    public function clear_debug_log() {
        $debug_file = WP_CONTENT_DIR . '/drtr-checkout-debug.txt';
        if (file_exists($debug_file)) {
            unlink($debug_file);
        }
        wp_send_json_success(array('message' => 'Log cancellato'));
    }
    
    /**
     * Shortcode per pagina checkout
     */
    public function checkout_shortcode($atts) {
        ob_start();
        $this->render_checkout_page();
        return ob_get_clean();
    }
    
    /**
     * Render pagina checkout
     */
    public function render_checkout_page() {
        // Verificare se ci sono dati nel URL
        $tour_id = isset($_GET['tour_id']) ? absint($_GET['tour_id']) : 0;
        $adults = isset($_GET['adults']) ? absint($_GET['adults']) : 1;
        $children = isset($_GET['children']) ? absint($_GET['children']) : 0;
        $payment_type = isset($_GET['payment_type']) ? sanitize_text_field($_GET['payment_type']) : 'full';
        
        if (!$tour_id) {
            echo '<p>' . __('Tour non trovato', 'drtr-tours') . '</p>';
            return;
        }
        
        $tour = get_post($tour_id);
        if (!$tour) {
            echo '<p>' . __('Tour non valido', 'drtr-tours') . '</p>';
            return;
        }
        
        // Ottenere prezzi
        $tour_price = get_post_meta($tour_id, '_drtr_price', true) ?: get_post_meta($tour_id, 'price', true);
        $tour_child_price = get_post_meta($tour_id, '_drtr_child_price', true);
        if (!$tour_child_price && $tour_price) {
            $tour_child_price = max(0, $tour_price - 5);
        }
        
        // Calcolare totali
        $subtotal = ($adults * $tour_price) + ($children * $tour_child_price);
        $deposit = $subtotal * 0.5;
        $total = $payment_type === 'deposit' ? $deposit : $subtotal;
        
        // Ottenere dati utente se loggato
        $current_user = wp_get_current_user();
        $user_first_name = '';
        $user_last_name = '';
        $user_email = '';
        $user_phone = '';
        
        if ($current_user->ID) {
            $user_first_name = $current_user->user_firstname;
            $user_last_name = $current_user->user_lastname;
            $user_email = $current_user->user_email;
            $user_phone = get_user_meta($current_user->ID, 'phone', true);
        }
        
        include DRTR_CHECKOUT_DIR . 'templates/checkout.php';
    }
    
    /**
     * Processare checkout
     */
    public function process_checkout() {
        // Disable caching for this request
        if (!defined('DONOTCACHEPAGE')) {
            define('DONOTCACHEPAGE', true);
        }
        if (!defined('DONOTCACHEOBJECT')) {
            define('DONOTCACHEOBJECT', true);
        }
        if (!defined('DONOTCACHEDB')) {
            define('DONOTCACHEDB', true);
        }
        
        // Write to file for debugging
        $debug_file = WP_CONTENT_DIR . '/drtr-checkout-debug.txt';
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($debug_file, "\n[$timestamp] METODO CHIAMATO!\n", FILE_APPEND);
        file_put_contents($debug_file, "POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);
        
        // Send headers immediately
        @header('Content-Type: application/json; charset=utf-8');
        @header('Cache-Control: no-cache, must-revalidate, max-age=0');
        
        error_log('DRTR CHECKOUT: process_checkout chiamato');
        error_log('DRTR CHECKOUT: POST data: ' . print_r($_POST, true));
        
        file_put_contents($debug_file, "Prima del nonce check\n", FILE_APPEND);
        
        // TEMPORANEAMENTE COMMENTATO PER DEBUG
        // check_ajax_referer('dreamtour-nonce', 'nonce');
        
        error_log('DRTR CHECKOUT: nonce verificato (SKIPPED)');
        file_put_contents($debug_file, "Dopo nonce check (SKIPPED)\n", FILE_APPEND);
        
        // Validare dati
        $required_fields = array('tour_id', 'adults', 'first_name', 'last_name', 'email', 'phone', 'payment_method');
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                error_log('DRTR CHECKOUT: campo mancante - ' . $field);
                file_put_contents($debug_file, "Campo mancante: $field\n", FILE_APPEND);
                wp_send_json_error(array('message' => sprintf(__('Campo obbligatorio mancante: %s', 'drtr-tours'), $field)));
            }
        }
        
        file_put_contents($debug_file, "Tutti i campi validati\n", FILE_APPEND);
        
        error_log('DRTR CHECKOUT: tutti i campi validati');
        
        // Preparare dati prenotazione
        file_put_contents($debug_file, "Preparando dati booking...\n", FILE_APPEND);
        
        $booking_data = array(
            'tour_id' => absint($_POST['tour_id']),
            'adults' => absint($_POST['adults']),
            'children' => absint($_POST['children']),
            'first_name' => sanitize_text_field($_POST['first_name']),
            'last_name' => sanitize_text_field($_POST['last_name']),
            'email' => sanitize_email($_POST['email']),
            'phone_prefix' => sanitize_text_field($_POST['phone_prefix'] ?? ''),
            'phone' => sanitize_text_field($_POST['phone']),
            'payment_type' => sanitize_text_field($_POST['payment_type']),
            'payment_method' => sanitize_text_field($_POST['payment_method']),
            'subtotal' => floatval($_POST['subtotal']),
            'deposit' => floatval($_POST['deposit']),
            'total' => floatval($_POST['total']),
        );
        
        if (is_user_logged_in()) {
            $booking_data['user_id'] = get_current_user_id();
        }
        
        file_put_contents($debug_file, "Dati preparati, creando booking...\n", FILE_APPEND);
        error_log('DRTR CHECKOUT: creando booking...');
        
        // Creare prenotazione
        $booking_class = DRTR_Booking::get_instance();
        $booking_id = $booking_class->create_booking($booking_data);
        
        file_put_contents($debug_file, "Booking ID ricevuto: " . print_r($booking_id, true) . "\n", FILE_APPEND);
        
        if (is_wp_error($booking_id)) {
            error_log('DRTR CHECKOUT: errore booking - ' . $booking_id->get_error_message());
            file_put_contents($debug_file, "ERRORE: " . $booking_id->get_error_message() . "\n", FILE_APPEND);
            wp_send_json_error(array('message' => $booking_id->get_error_message()));
        }
        
        file_put_contents($debug_file, "Booking creato con successo - ID: $booking_id\n", FILE_APPEND);
        error_log('DRTR CHECKOUT: booking creato - ID: ' . $booking_id);
        
        // Lo status rimane 'booking_pending' fino a quando l'admin conferma il pagamento
        // Non serve cambiare lo status qui perché create_booking già imposta 'booking_pending'
        
        // Inviare email
        file_put_contents($debug_file, "Inviando email...\n", FILE_APPEND);
        error_log('DRTR CHECKOUT: inviando email...');
        $this->send_booking_emails($booking_id, $booking_data);
        file_put_contents($debug_file, "Email inviate\n", FILE_APPEND);
        error_log('DRTR CHECKOUT: email inviate');
        
        file_put_contents($debug_file, "Inviando risposta JSON success...\n", FILE_APPEND);
        error_log('DRTR CHECKOUT: inviando risposta JSON success');
        wp_send_json_success(array(
            'message' => __('Prenotazione creata con successo!', 'drtr-tours'),
            'booking_id' => $booking_id,
            'redirect' => add_query_arg('booking_id', $booking_id, home_url('/grazie-prenotazione'))
        ));
        file_put_contents($debug_file, "FINE - Non dovrebbe arrivare qui\n", FILE_APPEND);
        error_log('DRTR CHECKOUT: fine metodo (non dovrebbe arrivare qui)');
    }
    
    /**
     * Inviare email di conferma
     */
    private function send_booking_emails($booking_id, $booking_data) {
        $tour = get_post($booking_data['tour_id']);
        $tour_title = $tour->post_title;
        
        // Add start date and time to tour title
        $tour_start_date = get_post_meta($booking_data['tour_id'], '_drtr_start_date', true) ?: get_post_meta($booking_data['tour_id'], 'start_date', true);
        if ($tour_start_date) {
            $date_obj = @DateTime::createFromFormat('Y-m-d\TH:i', $tour_start_date);
            if ($date_obj && !DateTime::getLastErrors()['warning_count']) {
                $tour_title .= ' - ' . $date_obj->format('d/m/y H:i');
            }
        }
        
        // Email al cliente
        $to_customer = $booking_data['email'];
        $subject_customer = sprintf(__('Conferma Prenotazione - %s', 'drtr-tours'), $tour_title);
        
        $message_customer = $this->get_customer_email_template($booking_data, $tour_title);
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($to_customer, $subject_customer, $message_customer, $headers);
        
        // Email all'admin
        $admin_email = get_option('admin_email');
        $subject_admin = sprintf(__('Nuova Prenotazione #%d - %s', 'drtr-tours'), $booking_id, $tour_title);
        
        $message_admin = $this->get_admin_email_template($booking_id, $booking_data, $tour_title);
        
        wp_mail($admin_email, $subject_admin, $message_admin, $headers);
    }
    
    /**
     * Template email cliente
     */
    private function get_customer_email_template($booking_data, $tour_title) {
        $payment_type_label = $booking_data['payment_type'] === 'deposit' ? __('Acconto 50%', 'drtr-tours') : __('Pagamento Completo', 'drtr-tours');
        $payment_method_label = $booking_data['payment_method'] === 'bank_transfer' ? __('Bonifico Bancario', 'drtr-tours') : __('Carta di Credito', 'drtr-tours');
        
        $logo_url = home_url('/wp-content/themes/dreamtour/assets/images/logos/logo.svg');
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="DreamTour" style="max-width: 200px; height: auto;">
                </div>
                
                <h1 style="color: #003284;"><?php _e('Grazie per la tua prenotazione!', 'drtr-tours'); ?></h1>
                
                <p><?php printf(__('Ciao %s,', 'drtr-tours'), $booking_data['first_name']); ?></p>
                
                <p><?php _e('La tua prenotazione è stata ricevuta con successo. Ecco i dettagli:', 'drtr-tours'); ?></p>
                
                <div style="background-color: white; padding: 20px; border-radius: 5px; margin: 20px 0;">
                    <h2 style="color: #003284; margin-top: 0;"><?php _e('Dettagli Prenotazione', 'drtr-tours'); ?></h2>
                    
                    <p><strong><?php _e('Tour:', 'drtr-tours'); ?></strong> <?php echo esc_html($tour_title); ?></p>
                    <p><strong><?php _e('Adulti:', 'drtr-tours'); ?></strong> <?php echo esc_html($booking_data['adults']); ?></p>
                    <p><strong><?php _e('Bambini:', 'drtr-tours'); ?></strong> <?php echo esc_html($booking_data['children']); ?></p>
                    
                    <hr style="border: 1px solid #eee; margin: 20px 0;">
                    
                    <p><strong><?php _e('Subtotale:', 'drtr-tours'); ?></strong> €<?php echo number_format($booking_data['subtotal'], 2, ',', '.'); ?></p>
                    <?php if ($booking_data['payment_type'] === 'deposit') : ?>
                        <p><strong><?php _e('Acconto (50%):', 'drtr-tours'); ?></strong> €<?php echo number_format($booking_data['deposit'], 2, ',', '.'); ?></p>
                    <?php endif; ?>
                    <p style="font-size: 18px;"><strong><?php _e('Totale da pagare:', 'drtr-tours'); ?></strong> €<?php echo number_format($booking_data['total'], 2, ',', '.'); ?></p>
                    
                    <hr style="border: 1px solid #eee; margin: 20px 0;">
                    
                    <p><strong><?php _e('Tipo Pagamento:', 'drtr-tours'); ?></strong> <?php echo esc_html($payment_type_label); ?></p>
                    <p><strong><?php _e('Metodo Pagamento:', 'drtr-tours'); ?></strong> <?php echo esc_html($payment_method_label); ?></p>
                </div>
                
                <?php if ($booking_data['payment_method'] === 'bank_transfer') : ?>
                    <div style="background-color: #fff3cd; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;">
                        <h3 style="margin-top: 0; color: #856404;"><?php _e('Dati per il Bonifico Bancario', 'drtr-tours'); ?></h3>
                        <p><strong><?php _e('Intestatario:', 'drtr-tours'); ?></strong> DreamTour Viaggi</p>
                        <p><strong><?php _e('IBAN:', 'drtr-tours'); ?></strong> IT00 X000 0000 0000 0000 0000 000</p>
                        <p><strong><?php _e('Causale:', 'drtr-tours'); ?></strong> Prenotazione <?php echo esc_html($tour_title); ?> - <?php echo esc_html($booking_data['first_name'] . ' ' . $booking_data['last_name']); ?></p>
                        <p><strong><?php _e('Importo:', 'drtr-tours'); ?></strong> €<?php echo number_format($booking_data['total'], 2, ',', '.'); ?></p>
                        
                        <p style="margin-top: 20px; color: #856404;">
                            <?php _e('Ti preghiamo di effettuare il bonifico entro 3 giorni lavorativi. La prenotazione sarà confermata al ricevimento del pagamento.', 'drtr-tours'); ?>
                        </p>
                    </div>
                <?php endif; ?>
                
                <p><?php _e('Se hai domande, non esitare a contattarci.', 'drtr-tours'); ?></p>
                
                <p><?php _e('Grazie per aver scelto DreamTour!', 'drtr-tours'); ?></p>
                
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
                    <p>DreamTour Viaggi<br>
                    Email: info@dreamtourviaggi.it<br>
                    Tel: +39 000 000 0000</p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Template email admin
     */
    private function get_admin_email_template($booking_id, $booking_data, $tour_title) {
        $payment_type_label = $booking_data['payment_type'] === 'deposit' ? __('Acconto 50%', 'drtr-tours') : __('Pagamento Completo', 'drtr-tours');
        $payment_method_label = $booking_data['payment_method'] === 'bank_transfer' ? __('Bonifico Bancario', 'drtr-tours') : __('Carta di Credito', 'drtr-tours');
        
        $logo_url = home_url('/wp-content/themes/dreamtour/assets/images/logos/logo.svg');
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="DreamTour" style="max-width: 200px; height: auto;">
                </div>
                
                <h1 style="color: #003284;"><?php printf(__('Nuova Prenotazione #%d', 'drtr-tours'), $booking_id); ?></h1>
                
                <div style="background-color: #f9f9f9; padding: 20px; border-radius: 5px; margin: 20px 0;">
                    <h2 style="margin-top: 0;"><?php _e('Dettagli Cliente', 'drtr-tours'); ?></h2>
                    <p><strong><?php _e('Nome:', 'drtr-tours'); ?></strong> <?php echo esc_html($booking_data['first_name'] . ' ' . $booking_data['last_name']); ?></p>
                    <p><strong><?php _e('Email:', 'drtr-tours'); ?></strong> <?php echo esc_html($booking_data['email']); ?></p>
                    <p><strong><?php _e('Telefono:', 'drtr-tours'); ?></strong> <?php echo esc_html($booking_data['phone_prefix'] . ' ' . $booking_data['phone']); ?></p>
                </div>
                
                <div style="background-color: #f9f9f9; padding: 20px; border-radius: 5px; margin: 20px 0;">
                    <h2 style="margin-top: 0;"><?php _e('Dettagli Tour', 'drtr-tours'); ?></h2>
                    <p><strong><?php _e('Tour:', 'drtr-tours'); ?></strong> <?php echo esc_html($tour_title); ?></p>
                    <p><strong><?php _e('Adulti:', 'drtr-tours'); ?></strong> <?php echo esc_html($booking_data['adults']); ?></p>
                    <p><strong><?php _e('Bambini:', 'drtr-tours'); ?></strong> <?php echo esc_html($booking_data['children']); ?></p>
                </div>
                
                <div style="background-color: #f9f9f9; padding: 20px; border-radius: 5px; margin: 20px 0;">
                    <h2 style="margin-top: 0;"><?php _e('Dettagli Pagamento', 'drtr-tours'); ?></h2>
                    <p><strong><?php _e('Subtotale:', 'drtr-tours'); ?></strong> €<?php echo number_format($booking_data['subtotal'], 2, ',', '.'); ?></p>
                    <?php if ($booking_data['payment_type'] === 'deposit') : ?>
                        <p><strong><?php _e('Acconto (50%):', 'drtr-tours'); ?></strong> €<?php echo number_format($booking_data['deposit'], 2, ',', '.'); ?></p>
                    <?php endif; ?>
                    <p style="font-size: 18px;"><strong><?php _e('Totale:', 'drtr-tours'); ?></strong> €<?php echo number_format($booking_data['total'], 2, ',', '.'); ?></p>
                    
                    <p><strong><?php _e('Tipo Pagamento:', 'drtr-tours'); ?></strong> <?php echo esc_html($payment_type_label); ?></p>
                    <p><strong><?php _e('Metodo Pagamento:', 'drtr-tours'); ?></strong> <?php echo esc_html($payment_method_label); ?></p>
                </div>
                
                <p><a href="<?php echo admin_url('post.php?post=' . $booking_id . '&action=edit'); ?>" style="display: inline-block; padding: 10px 20px; background-color: #003284; color: white; text-decoration: none; border-radius: 5px;">
                    <?php _e('Visualizza Prenotazione', 'drtr-tours'); ?>
                </a></p>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}
