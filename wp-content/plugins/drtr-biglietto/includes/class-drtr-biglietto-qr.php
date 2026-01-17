<?php
/**
 * QR Code Generation for Tickets
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_Biglietto_QR {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Constructor
    }
    
    /**
     * Generate QR code for a ticket
     * 
     * @param int $booking_id
     * @param string $seat_number
     * @return string URL of QR code image
     */
    public static function generate_qr_code($booking_id, $seat_number) {
        // Create unique ticket ID
        $ticket_id = self::generate_ticket_id($booking_id, $seat_number);
        
        // QR code data (simplified JSON format)
        $qr_data = json_encode([
            'ticket_id' => $ticket_id,
            'booking_id' => (string)$booking_id,
            'seat' => $seat_number,
            'timestamp' => current_time('timestamp'),
            'signature' => self::generate_signature($ticket_id)
        ], JSON_UNESCAPED_SLASHES);
        
        error_log("DRTR BIGLIETTO: Generando QR per biglietto $ticket_id");
        error_log("DRTR BIGLIETTO: QR data length: " . strlen($qr_data));
        
        // Use Google Charts API for QR code generation
        $qr_url = self::generate_qr_with_google_api($qr_data);
        
        // Save QR code locally
        $result = self::save_qr_code_locally($qr_url, $ticket_id);
        error_log("DRTR BIGLIETTO: QR URL finale: " . substr($result, 0, 100));
        
        return $result;
    }
    
    /**
     * Generate unique ticket ID
     */
    private static function generate_ticket_id($booking_id, $seat_number) {
        return 'DT-' . $booking_id . '-' . $seat_number . '-' . substr(md5(time() . $seat_number), 0, 8);
    }
    
    /**
     * Generate security signature for ticket
     */
    private static function generate_signature($ticket_id) {
        $secret_key = defined('AUTH_KEY') ? AUTH_KEY : 'dreamtour-secret';
        return hash_hmac('sha256', $ticket_id, $secret_key);
    }
    
    /**
     * Generate QR code using QR Server API (Google Charts deprecated)
     */
    private static function generate_qr_with_google_api($data) {
        $encoded_data = urlencode($data);
        $size = '300'; // Size in pixels
        // Using api.qrserver.com - free and reliable alternative
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encoded_data}";
    }
    
    /**
     * Save QR code image locally
     */
    private static function save_qr_code_locally($qr_url, $ticket_id) {
        // Download and save QR code locally, then return local URL for email
        
        error_log("DRTR BIGLIETTO: Scarico QR code da: " . $qr_url);
        
        // Download QR code image
        $response = wp_remote_get($qr_url, array(
            'timeout' => 15,
            'sslverify' => false
        ));
        
        if (is_wp_error($response)) {
            error_log("DRTR BIGLIETTO: Errore download QR: " . $response->get_error_message());
            // Fallback to direct API URL
            return $qr_url;
        }
        
        $image_data = wp_remote_retrieve_body($response);
        
        if (empty($image_data) || strlen($image_data) < 100) {
            error_log("DRTR BIGLIETTO: QR code vuoto o corrotto");
            // Fallback to direct API URL
            return $qr_url;
        }
        
        // Save locally
        $upload_dir = wp_upload_dir();
        $ticket_dir = $upload_dir['basedir'] . '/drtr-tickets';
        
        if (!file_exists($ticket_dir)) {
            wp_mkdir_p($ticket_dir);
            
            // Create .htaccess to allow access to images
            $htaccess_content = "# Allow access to QR code images\n";
            $htaccess_content .= "<IfModule mod_rewrite.c>\n";
            $htaccess_content .= "    RewriteEngine Off\n";
            $htaccess_content .= "</IfModule>\n\n";
            $htaccess_content .= "# Allow direct access to PNG files\n";
            $htaccess_content .= "<FilesMatch \"\\.(png|jpg|jpeg|gif)$\">\n";
            $htaccess_content .= "    Order allow,deny\n";
            $htaccess_content .= "    Allow from all\n";
            $htaccess_content .= "    Require all granted\n";
            $htaccess_content .= "</FilesMatch>\n\n";
            $htaccess_content .= "# Prevent directory listing\n";
            $htaccess_content .= "Options -Indexes\n";
            
            file_put_contents($ticket_dir . '/.htaccess', $htaccess_content);
            error_log("DRTR BIGLIETTO: .htaccess creato per permettere accesso alle immagini");
        }
        
        $filename = 'qr-' . $ticket_id . '.png';
        $filepath = $ticket_dir . '/' . $filename;
        
        $saved = file_put_contents($filepath, $image_data);
        
        if ($saved === false) {
            error_log("DRTR BIGLIETTO: Errore salvataggio file");
            return $qr_url;
        }
        
        $local_url = $upload_dir['baseurl'] . '/drtr-tickets/' . $filename;
        error_log("DRTR BIGLIETTO: QR code salvato localmente: " . $local_url);
        
        // Return local URL - works better in emails than base64
        return $local_url;
    }
    
    /**
     * Verify QR code signature
     */
    public static function verify_ticket($qr_data) {
        try {
            $data = json_decode($qr_data, true);
            
            if (!isset($data['ticket_id']) || !isset($data['signature'])) {
                return false;
            }
            
            $expected_signature = self::generate_signature($data['ticket_id']);
            
            return hash_equals($expected_signature, $data['signature']);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get ticket info from QR data
     */
    public static function get_ticket_info($qr_data) {
        try {
            $data = json_decode($qr_data, true);
            
            if (!self::verify_ticket($qr_data)) {
                return ['error' => 'Biglietto non valido'];
            }
            
            $booking_id = $data['booking_id'];
            $seat = $data['seat'];
            
            // Get booking info
            $booking = get_post($booking_id);
            if (!$booking) {
                return ['error' => 'Prenotazione non trovata'];
            }
            
            $tour_id = get_post_meta($booking_id, '_booking_tour_id', true);
            $customer_name = get_post_meta($booking_id, '_booking_name', true);
            
            // Get passenger name from seat
            global $wpdb;
            $table = $wpdb->prefix . 'drtr_posti';
            $passenger = $wpdb->get_var($wpdb->prepare(
                "SELECT passenger_name FROM $table WHERE booking_id = %d AND seat_number = %s",
                $booking_id,
                $seat
            ));
            
            return [
                'valid' => true,
                'ticket_id' => $data['ticket_id'],
                'booking_id' => $booking_id,
                'tour' => get_the_title($tour_id),
                'seat' => $seat,
                'passenger' => $passenger ?: $customer_name,
                'booking_date' => get_the_date('d/m/Y', $booking_id)
            ];
        } catch (Exception $e) {
            return ['error' => 'Errore nella lettura del biglietto'];
        }
    }
}
