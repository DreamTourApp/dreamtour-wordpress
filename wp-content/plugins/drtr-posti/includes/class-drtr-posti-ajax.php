<?php
/**
 * AJAX Handlers for Seat Selection
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_Posti_AJAX {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_ajax_drtr_get_available_seats', array($this, 'get_available_seats'));
        add_action('wp_ajax_nopriv_drtr_get_available_seats', array($this, 'get_available_seats'));
        
        add_action('wp_ajax_drtr_reserve_seats', array($this, 'reserve_seats'));
        add_action('wp_ajax_nopriv_drtr_reserve_seats', array($this, 'reserve_seats'));
        
        add_action('wp_ajax_drtr_admin_assign_seats', array($this, 'admin_assign_seats'));
        add_action('wp_ajax_drtr_update_tour_seat_settings', array($this, 'update_tour_settings'));
    }
    
    /**
     * Get available seats for a tour
     */
    public function get_available_seats() {
        check_ajax_referer('drtr-posti-nonce', 'nonce');
        
        $tour_id = intval($_POST['tour_id']);
        
        if (!$tour_id) {
            wp_send_json_error(['message' => __('Tour ID non valido', 'drtr-posti')]);
        }
        
        $occupied = DRTR_Posti_DB::get_available_seats($tour_id);
        
        wp_send_json_success([
            'occupied_seats' => $occupied,
            'total_seats' => 50
        ]);
    }
    
    /**
     * Reserve seats for customer
     */
    public function reserve_seats() {
        check_ajax_referer('drtr-posti-nonce', 'nonce');
        
        $token = sanitize_text_field($_POST['token']);
        $seats_data = $_POST['seats'];
        
        // Validate token
        $token_data = DRTR_Posti_DB::validate_token($token);
        if (!$token_data) {
            wp_send_json_error(['message' => __('Token non valido o scaduto', 'drtr-posti')]);
        }
        
        $booking_id = $token_data->booking_id;
        $tour_id = intval(get_post_meta($booking_id, '_booking_tour_id', true));
        
        // Validate seats
        if (empty($seats_data) || !is_array($seats_data)) {
            wp_send_json_error(['message' => __('Nessun posto selezionato', 'drtr-posti')]);
        }
        
        // Check if seats are still available
        $occupied = DRTR_Posti_DB::get_available_seats($tour_id);
        $occupied_numbers = array_column($occupied, 'seat_number');
        
        foreach ($seats_data as $seat) {
            if (in_array($seat['seat_number'], $occupied_numbers)) {
                wp_send_json_error(['message' => __('Alcuni posti selezionati non sono più disponibili', 'drtr-posti')]);
            }
        }
        
        // Add assigned_by field
        foreach ($seats_data as &$seat) {
            $seat['assigned_by'] = 'customer';
        }
        
        // Reserve seats
        $result = DRTR_Posti_DB::reserve_seats($booking_id, $tour_id, $seats_data);
        
        if ($result) {
            // Mark token as used
            DRTR_Posti_DB::mark_token_used($token);
            
            // Trigger ticket generation
            do_action('drtr_seats_confirmed', $booking_id, $seats_data);
            
            wp_send_json_success(['message' => __('Posti prenotati con successo!', 'drtr-posti')]);
        } else {
            wp_send_json_error(['message' => __('Errore durante la prenotazione dei posti', 'drtr-posti')]);
        }
    }
    
    /**
     * Admin assign seats manually
     */
    public function admin_assign_seats() {
        check_ajax_referer('drtr-posti-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permessi insufficienti', 'drtr-posti')]);
        }
        
        $booking_id = intval($_POST['booking_id']);
        $seats_data = $_POST['seats'];
        $tour_id = intval(get_post_meta($booking_id, '_booking_tour_id', true));
        
        // Add assigned_by field
        foreach ($seats_data as &$seat) {
            $seat['assigned_by'] = 'admin';
        }
        
        $result = DRTR_Posti_DB::reserve_seats($booking_id, $tour_id, $seats_data);
        
        if ($result) {
            // Trigger ticket generation
            do_action('drtr_seats_confirmed', $booking_id, $seats_data);
            
            wp_send_json_success(['message' => __('Posti assegnati con successo!', 'drtr-posti')]);
        } else {
            wp_send_json_error(['message' => __('Errore durante l\'assegnazione dei posti', 'drtr-posti')]);
        }
    }
    
    /**
     * Update tour seat settings
     */
    public function update_tour_settings() {
        check_ajax_referer('drtr-posti-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permessi insufficienti', 'drtr-posti')]);
        }
        
        $tour_id = intval($_POST['tour_id']);
        $settings = [
            'selection_enabled' => intval($_POST['selection_enabled']),
            'auto_assign' => intval($_POST['auto_assign'])
        ];
        
        DRTR_Posti_DB::update_tour_settings($tour_id, $settings);
        
        wp_send_json_success(['message' => __('Impostazioni aggiornate', 'drtr-posti')]);
    }
}
