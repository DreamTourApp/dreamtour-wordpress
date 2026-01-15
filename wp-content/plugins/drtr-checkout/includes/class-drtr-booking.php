<?php
/**
 * Gestione Custom Post Type Prenotazioni
 * 
 * @package DRTR_Checkout
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_Booking {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_booking_statuses'));
    }
    
    /**
     * Registrar Custom Post Type para Prenotazioni
     */
    public function register_post_type() {
        $labels = array(
            'name' => __('Prenotazioni', 'drtr-tours'),
            'singular_name' => __('Prenotazione', 'drtr-tours'),
            'menu_name' => __('Prenotazioni', 'drtr-tours'),
            'add_new' => __('Nuova Prenotazione', 'drtr-tours'),
            'add_new_item' => __('Aggiungi Nuova Prenotazione', 'drtr-tours'),
            'edit_item' => __('Modifica Prenotazione', 'drtr-tours'),
            'view_item' => __('Visualizza Prenotazione', 'drtr-tours'),
            'all_items' => __('Tutte le Prenotazioni', 'drtr-tours'),
            'search_items' => __('Cerca Prenotazioni', 'drtr-tours'),
            'not_found' => __('Nessuna prenotazione trovata', 'drtr-tours'),
        );
        
        $args = array(
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=drtr_tour',
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => array('title'),
            'has_archive' => false,
            'rewrite' => false,
        );
        
        register_post_type('drtr_booking', $args);
    }
    
    /**
     * Registrar stati personalizzati per le prenotazioni
     */
    public function register_booking_statuses() {
        register_post_status('booking_pending', array(
            'label' => __('In Attesa', 'drtr-tours'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('In Attesa <span class="count">(%s)</span>', 'In Attesa <span class="count">(%s)</span>', 'drtr-tours')
        ));
        
        register_post_status('booking_deposit', array(
            'label' => __('Acconto Pagato', 'drtr-tours'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Acconto <span class="count">(%s)</span>', 'Acconto <span class="count">(%s)</span>', 'drtr-tours')
        ));
        
        register_post_status('booking_paid', array(
            'label' => __('Pagato', 'drtr-tours'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Pagato <span class="count">(%s)</span>', 'Pagato <span class="count">(%s)</span>', 'drtr-tours')
        ));
        
        register_post_status('booking_cancelled', array(
            'label' => __('Cancellato', 'drtr-tours'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Cancellato <span class="count">(%s)</span>', 'Cancellato <span class="count">(%s)</span>', 'drtr-tours')
        ));
        
        register_post_status('booking_completed', array(
            'label' => __('Completato', 'drtr-tours'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Completato <span class="count">(%s)</span>', 'Completato <span class="count">(%s)</span>', 'drtr-tours')
        ));
    }
    
    /**
     * Creare una nuova prenotazione
     * 
     * @param array $booking_data Dati della prenotazione
     * @return int|WP_Error ID della prenotazione o errore
     */
    public function create_booking($booking_data) {
        // Validazione dati
        if (empty($booking_data['tour_id']) || empty($booking_data['email'])) {
            return new WP_Error('invalid_data', __('Dati mancanti per creare la prenotazione', 'drtr-tours'));
        }
        
        // Titolo prenotazione
        $tour_title = get_the_title($booking_data['tour_id']);
        $booking_title = sprintf(
            __('Prenotazione #%s - %s', 'drtr-tours'),
            time(),
            $tour_title
        );
        
        // Creare post
        $booking_id = wp_insert_post(array(
            'post_title' => $booking_title,
            'post_type' => 'drtr_booking',
            'post_status' => 'booking_pending',
        ));
        
        if (is_wp_error($booking_id)) {
            return $booking_id;
        }
        
        // Salvare meta fields
        $meta_fields = array(
            '_booking_tour_id' => absint($booking_data['tour_id']),
            '_booking_adults' => absint($booking_data['adults']),
            '_booking_children' => absint($booking_data['children']),
            '_booking_first_name' => sanitize_text_field($booking_data['first_name']),
            '_booking_last_name' => sanitize_text_field($booking_data['last_name']),
            '_booking_email' => sanitize_email($booking_data['email']),
            '_booking_phone_prefix' => sanitize_text_field($booking_data['phone_prefix']),
            '_booking_phone' => sanitize_text_field($booking_data['phone']),
            '_booking_payment_type' => sanitize_text_field($booking_data['payment_type']),
            '_booking_payment_method' => sanitize_text_field($booking_data['payment_method']),
            '_booking_subtotal' => floatval($booking_data['subtotal']),
            '_booking_deposit' => floatval($booking_data['deposit']),
            '_booking_total' => floatval($booking_data['total']),
            '_booking_created_at' => current_time('mysql'),
        );
        
        if (!empty($booking_data['user_id'])) {
            $meta_fields['_booking_user_id'] = absint($booking_data['user_id']);
        }
        
        foreach ($meta_fields as $key => $value) {
            update_post_meta($booking_id, $key, $value);
        }
        
        return $booking_id;
    }
    
    /**
     * Aggiornare stato prenotazione
     * 
     * @param int $booking_id ID prenotazione
     * @param string $status Nuovo stato
     * @return bool
     */
    public function update_booking_status($booking_id, $status) {
        $valid_statuses = array(
            'booking_pending',
            'booking_deposit',
            'booking_paid',
            'booking_cancelled',
            'booking_completed'
        );
        
        if (!in_array($status, $valid_statuses)) {
            return false;
        }
        
        wp_update_post(array(
            'ID' => $booking_id,
            'post_status' => $status
        ));
        
        update_post_meta($booking_id, '_booking_status_updated', current_time('mysql'));
        
        return true;
    }
    
    /**
     * Ottenere dati prenotazione
     * 
     * @param int $booking_id ID prenotazione
     * @return array|false
     */
    public function get_booking($booking_id) {
        $booking = get_post($booking_id);
        
        if (!$booking || $booking->post_type !== 'drtr_booking') {
            return false;
        }
        
        return array(
            'id' => $booking_id,
            'title' => $booking->post_title,
            'status' => $booking->post_status,
            'tour_id' => get_post_meta($booking_id, '_booking_tour_id', true),
            'adults' => get_post_meta($booking_id, '_booking_adults', true),
            'children' => get_post_meta($booking_id, '_booking_children', true),
            'first_name' => get_post_meta($booking_id, '_booking_first_name', true),
            'last_name' => get_post_meta($booking_id, '_booking_last_name', true),
            'email' => get_post_meta($booking_id, '_booking_email', true),
            'phone_prefix' => get_post_meta($booking_id, '_booking_phone_prefix', true),
            'phone' => get_post_meta($booking_id, '_booking_phone', true),
            'payment_type' => get_post_meta($booking_id, '_booking_payment_type', true),
            'payment_method' => get_post_meta($booking_id, '_booking_payment_method', true),
            'subtotal' => get_post_meta($booking_id, '_booking_subtotal', true),
            'deposit' => get_post_meta($booking_id, '_booking_deposit', true),
            'total' => get_post_meta($booking_id, '_booking_total', true),
            'created_at' => get_post_meta($booking_id, '_booking_created_at', true),
        );
    }
}

// Inizializzare
DRTR_Booking::get_instance();
