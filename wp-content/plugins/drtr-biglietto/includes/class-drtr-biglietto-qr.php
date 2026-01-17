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
        // For emails, use base64 embedded image instead of external URL
        // This ensures QR code is always visible regardless of email client
        
        error_log("DRTR BIGLIETTO: Scarico QR code da: " . $qr_url);
        
        // Download QR code image
        $response = wp_remote_get($qr_url, array(
            'timeout' => 15,
            'sslverify' => false
        ));
        
        if (is_wp_error($response)) {
            error_log("DRTR BIGLIETTO: Errore download QR: " . $response->get_error_message());
            return $qr_url;
        }
        
        $image_data = wp_remote_retrieve_body($response);
        
        if (empty($image_data) || strlen($image_data) < 100) {
            error_log("DRTR BIGLIETTO: QR code vuoto o corrotto");
            return $qr_url;
        }
        
        // Convert to base64 for email embedding
        $base64 = base64_encode($image_data);
        $data_uri = 'data:image/png;base64,' . $base64;
        
        error_log("DRTR BIGLIETTO: QR code convertito in base64 (" . strlen($base64) . " bytes)");
        
        // Also save locally as backup
        $upload_dir = wp_upload_dir();
        $ticket_dir = $upload_dir['basedir'] . '/drtr-tickets';
        
        if (!file_exists($ticket_dir)) {
            wp_mkdir_p($ticket_dir);
        }
        
        $filename = 'qr-' . $ticket_id . '.png';
        $filepath = $ticket_dir . '/' . $filename;
        
        file_put_contents($filepath, $image_data);
        
        $local_url = $upload_dir['baseurl'] . '/drtr-tickets/' . $filename;
        error_log("DRTR BIGLIETTO: QR code salvato anche localmente: " . $local_url);
        
        // Return base64 data URI for email
        return $data_uri;
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
