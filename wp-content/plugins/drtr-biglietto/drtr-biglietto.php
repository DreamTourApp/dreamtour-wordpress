<?php
/**
 * Plugin Name: DRTR Biglietto QR Code
 * Plugin URI: https://dreamtourviaggi.it
 * Description: Genera biglietti con QR code per tour DreamTour
 * Version: 1.0.0
 * Author: DreamTour Team
 * Text Domain: drtr-biglietto
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('DRTR_BIGLIETTO_VERSION', '1.0.0');
define('DRTR_BIGLIETTO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DRTR_BIGLIETTO_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main Plugin Class
 */
class DRTR_Biglietto {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }
    
    private function load_dependencies() {
        require_once DRTR_BIGLIETTO_PLUGIN_DIR . 'includes/class-drtr-biglietto-qr.php';
        require_once DRTR_BIGLIETTO_PLUGIN_DIR . 'includes/class-drtr-biglietto-email.php';
        require_once DRTR_BIGLIETTO_PLUGIN_DIR . 'includes/class-drtr-biglietto-pdf.php';
    }
    
    private function init_hooks() {
        add_action('plugins_loaded', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
    }
    
    public function init() {
        // Initialize components
        DRTR_Biglietto_QR::get_instance();
        DRTR_Biglietto_Email::get_instance();
        DRTR_Biglietto_PDF::get_instance();
        
        // Load text domain
        load_plugin_textdomain('drtr-biglietto', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function activate() {
        // Create upload directory for tickets
        $upload_dir = wp_upload_dir();
        $ticket_dir = $upload_dir['basedir'] . '/drtr-tickets';
        
        if (!file_exists($ticket_dir)) {
            wp_mkdir_p($ticket_dir);
        }
        
        // Create .htaccess to protect ticket files
        $htaccess = $ticket_dir . '/.htaccess';
        if (!file_exists($htaccess)) {
            file_put_contents($htaccess, 'deny from all');
        }
    }
}

// Initialize plugin
function drtr_biglietto() {
    return DRTR_Biglietto::get_instance();
}
drtr_biglietto();
