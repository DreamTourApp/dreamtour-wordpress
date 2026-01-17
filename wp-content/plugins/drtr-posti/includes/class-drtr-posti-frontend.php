<?php
/**
 * Frontend Display for Seat Selection
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_Posti_Frontend {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'register_page'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_shortcode('drtr_seat_selector', array($this, 'render_seat_selector'));
    }
    
    public function register_page() {
        add_rewrite_rule('^seleziona-posti/?', 'index.php?pagename=seleziona-posti', 'top');
    }
    
    public function enqueue_assets() {
        global $post;
        
        // Check if we're on the seat selection page or if the shortcode is in the content
        $is_seat_page = false;
        
        if (is_page('seleziona-posti')) {
            $is_seat_page = true;
        } elseif (isset($_GET['token']) && !empty($_GET['token'])) {
            $is_seat_page = true;
        } elseif ($post && has_shortcode($post->post_content, 'drtr_seat_selector')) {
            $is_seat_page = true;
        }
        
        if ($is_seat_page) {
            wp_enqueue_style('drtr-posti', DRTR_POSTI_PLUGIN_URL . 'assets/css/posti.css', array(), DRTR_POSTI_VERSION);
            wp_enqueue_script('drtr-posti', DRTR_POSTI_PLUGIN_URL . 'assets/js/posti.js', array('jquery'), DRTR_POSTI_VERSION, true);
            
            wp_localize_script('drtr-posti', 'drtrPosti', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('drtr-posti-nonce'),
                'strings' => array(
                    'selectSeats' => __('Seleziona i posti', 'drtr-posti'),
                    'available' => __('Disponibile', 'drtr-posti'),
                    'occupied' => __('Occupato', 'drtr-posti'),
                    'selected' => __('Selezionato', 'drtr-posti'),
                    'confirm' => __('Conferma Selezione', 'drtr-posti'),
                    'passengerName' => __('Nome Passeggero', 'drtr-posti')
                )
            ));
        }
    }
    
    public function render_seat_selector($atts) {
        $token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';
        
        if (!$token) {
            return '<p>' . __('Token non valido', 'drtr-posti') . '</p>';
        }
        
        $token_data = DRTR_Posti_DB::validate_token($token);
        if (!$token_data) {
            return '<p>' . __('Il link è scaduto o non è più valido', 'drtr-posti') . '</p>';
        }
        
        $booking_id = $token_data->booking_id;
        $tour_id = get_post_meta($booking_id, '_booking_tour_id', true);
        $num_people = intval(get_post_meta($booking_id, '_booking_adults', true)) + 
                     intval(get_post_meta($booking_id, '_booking_children', true));
        
        ob_start();
        ?>
        <div class="drtr-posti-container">
            <div class="drtr-posti-header">
                <h2><?php _e('Seleziona i tuoi posti', 'drtr-posti'); ?></h2>
                <p><?php printf(__('Seleziona %d posti per il tour: %s', 'drtr-posti'), $num_people, get_the_title($tour_id)); ?></p>
            </div>
            
            <div class="drtr-bus-layout">
                <div class="drtr-bus-driver">
                    <div class="driver-icon">🚗</div>
                    <span><?php _e('Autista', 'drtr-posti'); ?></span>
                </div>
                
                <div class="drtr-seats-grid" data-tour-id="<?php echo esc_attr($tour_id); ?>" data-num-seats="<?php echo esc_attr($num_people); ?>">
                    <?php $this->render_bus_seats(); ?>
                </div>
            </div>
            
            <div class="drtr-legend">
                <div class="legend-item">
                    <span class="seat-demo available"></span>
                    <span><?php _e('Disponibile', 'drtr-posti'); ?></span>
                </div>
                <div class="legend-item">
                    <span class="seat-demo occupied"></span>
                    <span><?php _e('Occupato', 'drtr-posti'); ?></span>
                </div>
                <div class="legend-item">
                    <span class="seat-demo selected"></span>
                    <span><?php _e('Selezionato', 'drtr-posti'); ?></span>
                </div>
            </div>
            
            <div class="drtr-selected-seats">
                <h3><?php _e('Posti selezionati:', 'drtr-posti'); ?></h3>
                <div id="selected-seats-list"></div>
            </div>
            
            <button id="confirm-seats" class="drtr-btn-confirm" disabled data-token="<?php echo esc_attr($token); ?>">
                <?php _e('Conferma Selezione', 'drtr-posti'); ?>
            </button>
        </div>
        <?php
        return ob_get_clean();
    }
    
    private function render_bus_seats() {
        $seat_number = 1;
        
        // Layout: 13 rows, 4 seats per row (last row 5 seats) = 53 total
        for ($row = 1; $row <= 13; $row++) {
            echo '<div class="seat-row" data-row="' . $row . '">';
            
            // Row number label
            echo '<span class="row-number">' . $row . '</span>';
            
            // Left side seats (2 seats)
            echo '<div class="seat-group left">';
            for ($i = 0; $i < 2; $i++) {
                echo '<div class="seat" data-seat="' . $seat_number . '" data-row="' . $row . '"><span>' . $seat_number . '</span></div>';
                $seat_number++;
            }
            echo '</div>';
            
            // Aisle
            echo '<div class="aisle"></div>';
            
            // Right side seats (2 seats normally, 3 on last row)
            echo '<div class="seat-group right">';
            $seats_right = ($row == 13) ? 3 : 2;
            for ($i = 0; $i < $seats_right; $i++) {
                echo '<div class="seat" data-seat="' . $seat_number . '" data-row="' . $row . '"><span>' . $seat_number . '</span></div>';
                $seat_number++;
            }
            echo '</div>';
            
            echo '</div>';
            
            // Add door indicator after row 6
            if ($row == 6) {
                echo '<div class="bus-door"><span>🚪 PORTA / DOOR</span></div>';
            }
        }
        
        // Add WC at the end
        echo '<div class="bus-wc"><span>🚽 WC</span></div>';
    }
}
