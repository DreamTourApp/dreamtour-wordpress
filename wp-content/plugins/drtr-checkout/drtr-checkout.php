<?php
/**
 * Plugin Name: DRTR - Checkout & Prenotazioni
 * Plugin URI: https://dreamtourviaggi.it
 * Description: Sistema completo di gestione checkout e prenotazioni per DreamTour
 * Version: 1.1.0
 * Author: DreamTour Team
 * Author URI: https://dreamtourviaggi.it
 * Text Domain: drtr-checkout
 * Domain Path: /languages
 * Requires Plugins: drtr-gestione-tours
 */

if (!defined('ABSPATH')) {
    exit;
}

// Cargar version helper
require_once WP_CONTENT_DIR . '/version-helper.php';

// Definir constantes
define('DRTR_CHECKOUT_VERSION', dreamtour_get_version('1.1.0'));
define('DRTR_CHECKOUT_DIR', plugin_dir_path(__FILE__));
define('DRTR_CHECKOUT_URL', plugin_dir_url(__FILE__));
define('DRTR_CHECKOUT_BASENAME', plugin_basename(__FILE__));

// Incluir archivos necesarios
require_once DRTR_CHECKOUT_DIR . 'includes/class-drtr-booking.php';
require_once DRTR_CHECKOUT_DIR . 'includes/class-drtr-checkout.php';
require_once DRTR_CHECKOUT_DIR . 'includes/class-drtr-bookings-pages.php';

/**
 * Clase principal del plugin
 */
class DRTR_Checkout_Plugin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Activación del plugin
        register_activation_hook(__FILE__, array($this, 'activate'));
        
        // Inicializar componentes
        add_action('plugins_loaded', array($this, 'init'));
        
        // Cargar traducciones
        add_action('init', array($this, 'load_textdomain'));
    }
    
    public function activate() {
        // Crear página Checkout si no existe
        $this->create_checkout_page();
        
        // Crear página Grazie Prenotazione si no existe
        $this->create_thank_you_page();
        
        // Crear páginas prenotazioni
        DRTR_Bookings_Pages::create_bookings_page();
        DRTR_Bookings_Pages::create_admin_bookings_page();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Crear página Checkout con shortcode
     */
    private function create_checkout_page() {
        $page_slug = 'checkout';
        $page_check = get_page_by_path($page_slug);
        
        if (!$page_check) {
            $page_data = array(
                'post_title' => __('Checkout', 'drtr-checkout'),
                'post_content' => '[drtr_checkout]',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $page_slug,
                'post_author' => 1,
            );
            
            wp_insert_post($page_data);
        }
    }
    
    /**
     * Crear página Grazie Prenotazione
     */
    private function create_thank_you_page() {
        $page_slug = 'grazie-prenotazione';
        $page_check = get_page_by_path($page_slug);
        
        if (!$page_check) {
            $page_data = array(
                'post_title' => __('Grazie per la tua Prenotazione', 'drtr-checkout'),
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $page_slug,
                'post_author' => 1,
                'page_template' => 'page-thank-you.php',
            );
            
            $page_id = wp_insert_post($page_data);
            
            // Impostare template
            if ($page_id) {
                update_post_meta($page_id, '_wp_page_template', 'page-thank-you.php');
            }
        }
    }
    
    public function init() {
        // Verificar que el plugin drtr-gestione-tours esté activo
        if (!class_exists('DRTR_Gestione_Tours')) {
            add_action('admin_notices', array($this, 'missing_dependency_notice'));
            return;
        }
        
        // Inicializar componentes
        DRTR_Booking::get_instance();
        DRTR_Checkout::get_instance();
        DRTR_Bookings_Pages::get_instance();
    }
    
    public function missing_dependency_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php _e('Il plugin DRTR - Checkout & Prenotazioni richiede il plugin DRTR - Gestione Tours per funzionare.', 'drtr-checkout'); ?></p>
        </div>
        <?php
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('drtr-checkout', false, dirname(DRTR_CHECKOUT_BASENAME) . '/languages');
    }
}

// Inizializzare il plugin
function drtr_checkout_init() {
    return DRTR_Checkout_Plugin::get_instance();
}

drtr_checkout_init();
