<?php
/**
 * Email Management for Tickets
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_Biglietto_Email {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Hook into seats confirmation
        add_action('drtr_seats_confirmed', array($this, 'send_tickets_email'), 10, 2);
    }
    
    /**
     * Send tickets email with QR codes
     */
    public function send_tickets_email($booking_id, $seats_data) {
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
        
        // Get tour date
        $tour_dates = get_post_meta($tour_id, '_drtr_available_dates', true);
        $tour_date = '';
        if (!empty($tour_dates) && is_array($tour_dates)) {
            $tour_date = date('d/m/Y', strtotime($tour_dates[0]));
        }
        
        // Generate QR codes for each seat
        $tickets = [];
        foreach ($seats_data as $seat) {
            $qr_url = DRTR_Biglietto_QR::generate_qr_code($booking_id, $seat['seat_number']);
            
            $tickets[] = [
                'seat' => $seat['seat_number'],
                'passenger' => $seat['passenger_name'],
                'qr_code' => $qr_url
            ];
        }
        
        // Get tour title with date and time
        $tour_title = get_the_title($tour_id);
        $tour_start_date = get_post_meta($tour_id, '_drtr_start_date', true) ?: get_post_meta($tour_id, 'start_date', true);
        if ($tour_start_date) {
            $date_obj = DateTime::createFromFormat('Y-m-d\TH:i', $tour_start_date);
            if ($date_obj) {
                $tour_title .= ' - ' . $date_obj->format('d/m/y H:i');
            }
        }
        
        // Generate PDF ticket
        $pdf_url = DRTR_Biglietto_PDF::generate_ticket_pdf($booking_id, $tickets, [
            'tour_title' => $tour_title,
            'tour_date' => $tour_date,
            'customer_name' => $customer_name,
            'num_seats' => count($tickets)
        ]);
        
        // Send email
        $this->send_ticket_email($customer_email, $customer_name, $tour, $tickets, $pdf_url);
    }
    
    /**
     * Send actual email with tickets
     */
    private function send_ticket_email($email, $name, $tour, $tickets, $pdf_url) {
        // Get tour title with date and time
        $tour_title = get_the_title($tour);
        $tour_id = is_object($tour) ? $tour->ID : $tour;
        $tour_start_date = get_post_meta($tour_id, '_drtr_start_date', true) ?: get_post_meta($tour_id, 'start_date', true);
        if ($tour_start_date) {
            $date_obj = DateTime::createFromFormat('Y-m-d\TH:i', $tour_start_date);
            if ($date_obj) {
                $tour_title .= ' - ' . $date_obj->format('d/m/y H:i');
            }
        }
        
        $subject = sprintf(__('I tuoi biglietti per %s', 'drtr-biglietto'), $tour_title);
        
        $logo_url = home_url('/wp-content/themes/dreamtour/assets/images/logos/logo.svg');
        
        // Build tickets HTML
        $tickets_html = '';
        foreach ($tickets as $ticket) {
            $tickets_html .= '
            <div style="border: 2px solid #1ba4ce; border-radius: 8px; padding: 20px; margin: 15px 0; background: white;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <h3 style="color: #003284; margin: 0 0 10px 0;">Posto: ' . esc_html($ticket['seat']) . '</h3>
                        <p style="margin: 5px 0;"><strong>Passeggero:</strong> ' . esc_html($ticket['passenger']) . '</p>
                    </div>
                    <div style="text-align: center; margin: 10px;">
                        <img src="' . esc_url($ticket['qr_code']) . '" alt="QR Code" style="max-width: 150px; height: auto;">
                        <p style="font-size: 11px; color: #666; margin-top: 5px;">Mostra questo QR code alla partenza</p>
                    </div>
                </div>
            </div>';
        }
        
        $message = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 700px; margin: 0 auto; padding: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .header img { max-width: 200px; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 8px; }
                .ticket-container { margin: 20px 0; }
                .button { display: inline-block; background: #003284; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
                .info-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <img src="' . esc_url($logo_url) . '" alt="Dream Tour">
                </div>
                <div class="content">
                    <h2>' . __('Ciao', 'drtr-biglietto') . ' ' . esc_html($name) . '!</h2>
                    <p>' . __('Ecco i tuoi biglietti per il tour:', 'drtr-biglietto') . '</p>
                    <h3 style="color: #003284;">' . esc_html($tour_title) . '</h3>
                    
                    <div class="info-box">
                        <strong>📋 ' . __('Informazioni importanti:', 'drtr-biglietto') . '</strong>
                        <ul style="margin: 10px 0;">
                            <li>' . __('Presenta il QR code del tuo biglietto alla partenza', 'drtr-biglietto') . '</li>
                            <li>' . __('Ogni passeggero deve avere il proprio biglietto', 'drtr-biglietto') . '</li>
                            <li>' . __('Puoi stampare i biglietti o mostrarli dal telefono', 'drtr-biglietto') . '</li>
                            <li>' . __('Arriva almeno 15 minuti prima della partenza', 'drtr-biglietto') . '</li>
                        </ul>
                    </div>
                    
                    <div class="ticket-container">
                        <h3 style="color: #003284;">' . __('I tuoi biglietti:', 'drtr-biglietto') . '</h3>
                        ' . $tickets_html . '
                    </div>
                    
                    <div style="text-align: center; margin-top: 30px;">
                        <a href="' . esc_url($pdf_url) . '" class="button">' . __('Scarica PDF Biglietti', 'drtr-biglietto') . '</a>
                    </div>
                    
                    <p style="margin-top: 30px;">' . __('Buon viaggio con Dream Tour!', 'drtr-biglietto') . '</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' Dream Tour. ' . __('Tutti i diritti riservati.', 'drtr-biglietto') . '</p>
                    <p>' . __('Per assistenza:', 'drtr-biglietto') . ' <a href="mailto:info@dreamtourviaggi.it">info@dreamtourviaggi.it</a></p>
                </div>
            </div>
        </body>
        </html>';
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        wp_mail($email, $subject, $message, $headers);
    }
}
