<?php
/**
 * Database Management for Bus Seats
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_Posti_DB {
    
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
     * Create database tables
     */
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Table for bus configurations
        $table_bus = $wpdb->prefix . 'drtr_bus_config';
        $sql_bus = "CREATE TABLE IF NOT EXISTS $table_bus (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            total_seats int(11) NOT NULL DEFAULT 50,
            rows_count int(11) NOT NULL DEFAULT 13,
            seats_per_row int(11) NOT NULL DEFAULT 4,
            layout text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Table for seat assignments
        $table_seats = $wpdb->prefix . 'drtr_posti';
        $sql_seats = "CREATE TABLE IF NOT EXISTS $table_seats (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            booking_id bigint(20) NOT NULL,
            tour_id bigint(20) NOT NULL,
            passenger_name varchar(255) NOT NULL,
            seat_number varchar(10) NOT NULL,
            row_number int(11) NOT NULL,
            position varchar(10) NOT NULL,
            assigned_by varchar(50) DEFAULT 'customer',
            assigned_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_seat (tour_id, seat_number),
            KEY booking_id (booking_id),
            KEY tour_id (tour_id)
        ) $charset_collate;";
        
        // Table for seat selection tokens
        $table_tokens = $wpdb->prefix . 'drtr_posti_tokens';
        $sql_tokens = "CREATE TABLE IF NOT EXISTS $table_tokens (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            booking_id bigint(20) NOT NULL,
            token varchar(64) NOT NULL,
            expires_at datetime NOT NULL,
            used tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY token (token),
            KEY booking_id (booking_id)
        ) $charset_collate;";
        
        // Table for tour seat settings
        $table_tour_settings = $wpdb->prefix . 'drtr_tour_seat_settings';
        $sql_tour_settings = "CREATE TABLE IF NOT EXISTS $table_tour_settings (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            tour_id bigint(20) NOT NULL,
            selection_enabled tinyint(1) DEFAULT 1,
            auto_assign tinyint(1) DEFAULT 0,
            bus_config_id bigint(20) DEFAULT 1,
            PRIMARY KEY (id),
            UNIQUE KEY tour_id (tour_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_bus);
        dbDelta($sql_seats);
        dbDelta($sql_tokens);
        dbDelta($sql_tour_settings);
        
        // Insert default bus configuration
        self::insert_default_bus_config();
    }
    
    /**
     * Insert default bus configuration (Gran Turismo standard)
     */
    private static function insert_default_bus_config() {
        global $wpdb;
        
        $table = $wpdb->prefix . 'drtr_bus_config';
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM $table");
        
        if ($exists == 0) {
            $layout = json_encode([
                'rows' => 13,
                'seats_per_row' => 4,
                'aisle_position' => 2, // Corridor after 2nd seat
                'last_row_seats' => 5  // Last row has 5 seats
            ]);
            
            $wpdb->insert($table, [
                'name' => 'Pullman Gran Turismo Standard',
                'total_seats' => 50,
                'rows_count' => 13,
                'seats_per_row' => 4,
                'layout' => $layout
            ]);
        }
    }
    
    /**
     * Get available seats for a tour
     */
    public static function get_available_seats($tour_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'drtr_posti';
        $occupied = $wpdb->get_results($wpdb->prepare(
            "SELECT seat_number, passenger_name FROM $table WHERE tour_id = %d",
            $tour_id
        ), ARRAY_A);
        
        return $occupied;
    }
    
    /**
     * Reserve seats for a booking
     */
    public static function reserve_seats($booking_id, $tour_id, $seats_data) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'drtr_posti';
        
        foreach ($seats_data as $seat) {
            $wpdb->insert($table, [
                'booking_id' => $booking_id,
                'tour_id' => $tour_id,
                'passenger_name' => sanitize_text_field($seat['passenger_name']),
                'seat_number' => sanitize_text_field($seat['seat_number']),
                'row_number' => intval($seat['row_number']),
                'position' => sanitize_text_field($seat['position']),
                'assigned_by' => sanitize_text_field($seat['assigned_by'])
            ]);
        }
        
        return true;
    }
    
    /**
     * Generate seat selection token
     */
    public static function generate_token($booking_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'drtr_posti_tokens';
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+7 days'));
        
        $wpdb->insert($table, [
            'booking_id' => $booking_id,
            'token' => $token,
            'expires_at' => $expires
        ]);
        
        return $token;
    }
    
    /**
     * Validate token
     */
    public static function validate_token($token) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'drtr_posti_tokens';
        $result = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE token = %s AND expires_at > NOW() AND used = 0",
            $token
        ));
        
        return $result;
    }
    
    /**
     * Mark token as used
     */
    public static function mark_token_used($token) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'drtr_posti_tokens';
        $wpdb->update($table, ['used' => 1], ['token' => $token]);
    }
    
    /**
     * Get tour seat settings
     */
    public static function get_tour_settings($tour_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'drtr_tour_seat_settings';
        $settings = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE tour_id = %d",
            $tour_id
        ), ARRAY_A);
        
        if (!$settings) {
            // Create default settings
            $wpdb->insert($table, [
                'tour_id' => $tour_id,
                'selection_enabled' => 1,
                'auto_assign' => 0,
                'bus_config_id' => 1
            ]);
            
            return [
                'selection_enabled' => 1,
                'auto_assign' => 0,
                'bus_config_id' => 1
            ];
        }
        
        return $settings;
    }
    
    /**
     * Update tour seat settings
     */
    public static function update_tour_settings($tour_id, $settings) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'drtr_tour_seat_settings';
        
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE tour_id = %d",
            $tour_id
        ));
        
        if ($exists) {
            $wpdb->update($table, $settings, ['tour_id' => $tour_id]);
        } else {
            $settings['tour_id'] = $tour_id;
            $wpdb->insert($table, $settings);
        }
    }
    
    /**
     * Auto assign seats
     */
    public static function auto_assign_seats($booking_id, $tour_id, $num_seats, $passenger_names) {
        $occupied_seats = self::get_available_seats($tour_id);
        $occupied_numbers = array_column($occupied_seats, 'seat_number');
        
        $seats_data = [];
        $assigned = 0;
        
        // Try to assign consecutive seats
        for ($row = 1; $row <= 13 && $assigned < $num_seats; $row++) {
            $positions = ['A', 'B', 'C', 'D'];
            if ($row == 13) {
                $positions[] = 'E'; // Last row has 5 seats
            }
            
            foreach ($positions as $pos) {
                if ($assigned >= $num_seats) break;
                
                $seat_num = $row . $pos;
                if (!in_array($seat_num, $occupied_numbers)) {
                    $seats_data[] = [
                        'seat_number' => $seat_num,
                        'row_number' => $row,
                        'position' => $pos,
                        'passenger_name' => $passenger_names[$assigned] ?? 'Passeggero ' . ($assigned + 1),
                        'assigned_by' => 'auto'
                    ];
                    $assigned++;
                }
            }
        }
        
        if ($assigned == $num_seats) {
            return self::reserve_seats($booking_id, $tour_id, $seats_data);
        }
        
        return false;
    }
}
