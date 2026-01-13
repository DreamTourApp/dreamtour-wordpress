<?php
/**
 * Plugin Name: DRTR Reserved Area
 * Plugin URI: https://dreamtour.app
 * Description: Sistema di login personalizzato e area riservata con gestione permessi per ruoli utente
 * Version: 1.0.0
 * Author: Dream Tour
 * Author URI: https://dreamtour.app
 * Text Domain: drtr-reserved-area
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Definir constantes del plugin
 */
define('DRTR_RA_VERSION', '1.0.0');
define('DRTR_RA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DRTR_RA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DRTR_RA_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Clase principal del plugin
 */
class DRTR_Reserved_Area {
    
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
        require_once DRTR_RA_PLUGIN_DIR . 'includes/class-drtr-ra-page-manager.php';
        require_once DRTR_RA_PLUGIN_DIR . 'includes/class-drtr-ra-auth.php';
        require_once DRTR_RA_PLUGIN_DIR . 'includes/class-drtr-ra-dashboard.php';
    }
    
    private function init_hooks() {
        add_action('init', array($this, 'load_textdomain'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        
        // Inicializar clases
        DRTR_RA_Page_Manager::get_instance();
        DRTR_RA_Auth::get_instance();
        DRTR_RA_Dashboard::get_instance();
    }
    
    public function load_textdomain() {
        // Obtener el locale del sitio (que ya incluye la lógica del tema)
        $locale = get_locale();
        
        // Cargar traducciones del plugin
        $mofile = DRTR_RA_PLUGIN_DIR . 'languages/' . $locale . '.mo';
        
        if (file_exists($mofile)) {
            load_textdomain('drtr-reserved-area', $mofile);
        }
        
        // Fallback al método estándar de WordPress
        load_plugin_textdomain('drtr-reserved-area', false, dirname(DRTR_RA_PLUGIN_BASENAME) . '/languages');
    }
    
    public function enqueue_assets() {
        if (is_page('area-riservata')) {
            wp_enqueue_style(
                'drtr-ra-style',
                DRTR_RA_PLUGIN_URL . 'assets/css/style.css',
                array(),
                DRTR_RA_VERSION
            );
            
            wp_enqueue_script(
                'drtr-ra-script',
                DRTR_RA_PLUGIN_URL . 'assets/js/script.js',
                array('jquery'),
                DRTR_RA_VERSION,
                true
            );
            
            wp_localize_script('drtr-ra-script', 'drtrRA', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('drtr_ra_nonce'),
                'strings' => array(
                    'login_error' => __('Errore di login. Verifica le tue credenziali.', 'drtr-reserved-area'),
                    'required_fields' => __('Tutti i campi sono obbligatori.', 'drtr-reserved-area'),
                )
            ));
        }
    }
}

/**
 * Hook de activación
 */
register_activation_hook(__FILE__, 'drtr_ra_activate');
function drtr_ra_activate() {
    // Crear página al activar
    DRTR_RA_Page_Manager::create_reserved_page();
    flush_rewrite_rules();
}

/**
 * Hook de desactivación
 */
register_deactivation_hook(__FILE__, 'drtr_ra_deactivate');
function drtr_ra_deactivate() {
    flush_rewrite_rules();
}

/**
 * Inicializar el plugin
 */
function drtr_ra_init() {
    return DRTR_Reserved_Area::get_instance();
}
add_action('plugins_loaded', 'drtr_ra_init');
