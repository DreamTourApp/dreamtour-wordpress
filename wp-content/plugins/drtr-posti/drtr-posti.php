<?php
/**
 * Plugin Name: DRTR Gestione Posti
 * Plugin URI: https://dreamtourviaggi.it
 * Description: Sistema di gestione posti nell'autobus per tour DreamTour
 * Version: 1.0.0
 * Author: DreamTour Team
 * Text Domain: drtr-posti
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('DRTR_POSTI_VERSION', '1.0.0');
define('DRTR_POSTI_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DRTR_POSTI_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main Plugin Class
 */
class DRTR_Posti {
    
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
        require_once DRTR_POSTI_PLUGIN_DIR . 'includes/class-drtr-posti-db.php';
        require_once DRTR_POSTI_PLUGIN_DIR . 'includes/class-drtr-posti-ajax.php';
        require_once DRTR_POSTI_PLUGIN_DIR . 'includes/class-drtr-posti-frontend.php';
        require_once DRTR_POSTI_PLUGIN_DIR . 'includes/class-drtr-posti-email.php';
    }
    
    private function init_hooks() {
        add_action('plugins_loaded', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
    }
    
    public function init() {
        // Initialize components
        DRTR_Posti_DB::get_instance();
        DRTR_Posti_AJAX::get_instance();
        DRTR_Posti_Frontend::get_instance();
        DRTR_Posti_Email::get_instance();
        
        // Load text domain
        load_plugin_textdomain('drtr-posti', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function activate() {
        DRTR_Posti_DB::create_tables();
        $this->create_seat_selection_page();
        flush_rewrite_rules();
    }
    
    /**
     * Create seat selection page
     */
    private function create_seat_selection_page() {
        $page = get_page_by_path('seleziona-posti');
        
        if (!$page) {
            wp_insert_post(array(
                'post_title'   => __('Seleziona Posti', 'drtr-posti'),
                'post_name'    => 'seleziona-posti',
                'post_content' => '[drtr_seat_selector]',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
                'page_template' => 'page-seleziona-posti.php'
            ));
        }
    }
}

// Initialize plugin
function drtr_posti() {
    return DRTR_Posti::get_instance();
}
drtr_posti();
