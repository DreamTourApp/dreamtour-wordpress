<?php
/**
 * Email Management for Seat Selection
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_Posti_Email {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Hook into booking status change
        add_action('drtr_booking_status_changed', array($this, 'send_seat_selection_email'), 10, 3);
    }
    
    /**
     * Send seat selection email when booking is paid
     */
    public function send_seat_selection_email($booking_id, $old_status, $new_status) {
        error_log("DRTR POSTI: Hook chiamato - Booking ID: $booking_id, Old: $old_status, New: $new_status");
        
        // Only send if status changes to paid or deposit paid
        if (!in_array($new_status, ['booking_paid', 'booking_deposit'])) {
            error_log("DRTR POSTI: Status non valido - New status: $new_status");
            return;
        }
        
        error_log("DRTR POSTI: Status valido, procedo...");
        
        // Check if tables exist before proceeding
        if (!DRTR_Posti_DB::tables_exist()) {
            error_log("DRTR POSTI: Tabelle non esistono!");
            return;
        }
        
        error_log("DRTR POSTI: Tabelle esistono");
        
        $booking = get_post($booking_id);
        if (!$booking) {
            error_log("DRTR POSTI: Booking non trovato!");
            return;
        }
        
        $customer_email = get_post_meta($booking_id, '_booking_email', true);
        $customer_name = get_post_meta($booking_id, '_booking_name', true);
        $tour_id = get_post_meta($booking_id, '_booking_tour_id', true);
        $tour = get_post($tour_id);
        
        error_log("DRTR POSTI: Email: $customer_email, Nome: $customer_name, Tour ID: $tour_id");
        
        if (!$customer_email || !$tour) {
            error_log("DRTR POSTI: Email o tour mancante!");
            return;
        }
        
        // Check if seats already assigned
        $seats = DRTR_Posti_DB::get_available_seats($tour_id);
        $booking_seats = array_filter($seats, function($seat) use ($booking_id) {
            global $wpdb;
            $table = $wpdb->prefix . 'drtr_posti';
            $result = $wpdb->get_var($wpdb->prepare(
                "SELECT booking_id FROM $table WHERE seat_number = %s",
                $seat['seat_number']
            ));
            return $result == $booking_id;
        });
        
        if (!empty($booking_seats)) {
            error_log("DRTR POSTI: Posti già assegnati!");
            return; // Seats already assigned
        }
        
        error_log("DRTR POSTI: Nessun posto già assegnato");
        
        // Check tour settings
        $settings = DRTR_Posti_DB::get_tour_settings($tour_id);
        error_log("DRTR POSTI: Settings tour - Enabled: " . ($settings['selection_enabled'] ? 'SI' : 'NO') . ", Auto-assign: " . ($settings['auto_assign'] ? 'SI' : 'NO'));
        error_log("DRTR POSTI: Settings tour - Enabled: " . ($settings['selection_enabled'] ? 'SI' : 'NO') . ", Auto-assign: " . ($settings['auto_assign'] ? 'SI' : 'NO'));
        
        if (!$settings['selection_enabled'] || $settings['auto_assign']) {
            error_log("DRTR POSTI: Selezione non abilitata o auto-assign attivo, assegno automaticamente");
            // Auto assign seats
            $num_people = intval(get_post_meta($booking_id, '_booking_adults', true)) + 
                         intval(get_post_meta($booking_id, '_booking_children', true));
            
            $passenger_names = [$customer_name];
            for ($i = 1; $i < $num_people; $i++) {
                $passenger_names[] = "Accompagnatore " . $i;
            }
            
            DRTR_Posti_DB::auto_assign_seats($booking_id, $tour_id, $num_people, $passenger_names);
            return;
        }
        
        error_log("DRTR POSTI: Procedo con invio email...");
        
        // Generate token for seat selection
        $token = DRTR_Posti_DB::generate_token($booking_id);
        error_log("DRTR POSTI: Token generato: $token");
        
        // Get or create seat selection page
        $page = get_page_by_path('seleziona-posti');
        if (!$page) {
            // Create page if it doesn't exist
            $page_id = wp_insert_post(array(
                'post_title'   => __('Seleziona Posti', 'drtr-posti'),
                'post_name'    => 'seleziona-posti',
                'post_content' => '[drtr_seat_selector]',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ));
            $selection_url = add_query_arg('token', $token, get_permalink($page_id));
        } else {
            $selection_url = add_query_arg('token', $token, get_permalink($page->ID));
        }
        
        // Email content
        $tour_title = get_the_title($tour_id);
        
        // Add start date and time to tour title
        $tour_start_date = get_post_meta($tour_id, '_drtr_start_date', true) ?: get_post_meta($tour_id, 'start_date', true);
        if ($tour_start_date) {
            $date_obj = @DateTime::createFromFormat('Y-m-d\TH:i', $tour_start_date);
            if ($date_obj && !DateTime::getLastErrors()['warning_count']) {
                $tour_title .= ' - ' . $date_obj->format('d/m/y H:i');
            }
        }
        
        $subject = sprintf(__('Seleziona i tuoi posti - %s', 'drtr-posti'), $tour_title);
        
        $logo_url = home_url('/wp-content/themes/dreamtour/assets/images/logos/logo.png');
        
        $message = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .header img { max-width: 200px; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 8px; }
                .button { display: inline-block; background: #003284; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <img src="' . esc_url($logo_url) . '" alt="Dream Tour">
                </div>
                <div class="content">
                    <h2>' . __('Ciao', 'drtr-posti') . ' ' . esc_html($customer_name) . ',</h2>
                    <p>' . __('La tua prenotazione è stata confermata!', 'drtr-posti') . '</p>
                    <p><strong>' . __('Tour:', 'drtr-posti') . '</strong> ' . esc_html($tour_title) . '</p>
                    <p>' . __('Ora puoi selezionare i tuoi posti nell\'autobus. Clicca sul pulsante qui sotto per visualizzare la mappa dei posti disponibili:', 'drtr-posti') . '</p>
                    <div style="text-align: center; color: white;">
                        <a href="' . esc_url($selection_url) . '" class="button">' . __('Seleziona Posti', 'drtr-posti') . '</a>
                    </div>
                    <p><small>' . __('Questo link è valido per 7 giorni.', 'drtr-posti') . '</small></p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' Dream Tour. ' . __('Tutti i diritti riservati.', 'drtr-posti') . '</p>
                </div>
            </div>
        </body>
        </html>';
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        error_log("DRTR POSTI: Invio email a: $customer_email");
        error_log("DRTR POSTI: Subject: $subject");
        error_log("DRTR POSTI: Link selezione: $selection_url");
        
        $result = wp_mail($customer_email, $subject, $message, $headers);
        
        if ($result) {
            error_log("DRTR POSTI: Email inviata con successo!");
        } else {
            error_log("DRTR POSTI: ERRORE - Email NON inviata!");
        }
    }
}
