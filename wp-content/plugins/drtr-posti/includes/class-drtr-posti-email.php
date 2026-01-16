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
        // Only send if status changes to paid or deposit paid
        if (!in_array($new_status, ['booking_paid', 'booking_deposit'])) {
            return;
        }
        
        $booking = get_post($booking_id);
        if (!$booking) {
            return;
        }
        
        $customer_email = get_post_meta($booking_id, '_booking_email', true);
        $customer_name = get_post_meta($booking_id, '_booking_name', true);
        $tour_id = get_post_meta($booking_id, '_booking_tour_id', true);
        $tour = get_post($tour_id);
        
        if (!$customer_email || !$tour) {
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
            return; // Seats already assigned
        }
        
        // Check tour settings
        $settings = DRTR_Posti_DB::get_tour_settings($tour_id);
        
        if (!$settings['selection_enabled'] || $settings['auto_assign']) {
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
        
        // Generate token for seat selection
        $token = DRTR_Posti_DB::generate_token($booking_id);
        $selection_url = add_query_arg([
            'page' => 'seleziona-posti',
            'token' => $token
        ], home_url());
        
        // Email content
        $subject = sprintf(__('Seleziona i tuoi posti - %s', 'drtr-posti'), get_the_title($tour_id));
        
        $logo_url = get_template_directory_uri() . '/assets/images/logo.png';
        
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
                    <p><strong>' . __('Tour:', 'drtr-posti') . '</strong> ' . esc_html(get_the_title($tour_id)) . '</p>
                    <p>' . __('Ora puoi selezionare i tuoi posti nell\'autobus. Clicca sul pulsante qui sotto per visualizzare la mappa dei posti disponibili:', 'drtr-posti') . '</p>
                    <div style="text-align: center;">
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
        wp_mail($customer_email, $subject, $message, $headers);
    }
}
