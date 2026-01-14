<?php
/**
 * Plugin Name: DRTR - Gestione Tours
 * Plugin URI: https://dreamtourviaggi.it
 * Description: Sistema completo de gestión de tours con interfaz AJAX para administradores
 * Version: 1.1.1
 * Author: DreamTour Team
 * Author URI: https://dreamtourviaggi.it
 * Text Domain: drtr-tours
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

// Cargar version helper
require_once WP_CONTENT_DIR . '/version-helper.php';

// Definir constantes
define('DRTR_VERSION', dreamtour_get_version('1.1.1'));
define('DRTR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DRTR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DRTR_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Incluir archivos necesarios
require_once DRTR_PLUGIN_DIR . 'includes/class-drtr-post-type.php';
require_once DRTR_PLUGIN_DIR . 'includes/class-drtr-meta-boxes.php';
require_once DRTR_PLUGIN_DIR . 'includes/class-drtr-ajax-handler.php';
require_once DRTR_PLUGIN_DIR . 'includes/class-drtr-frontend.php';
require_once DRTR_PLUGIN_DIR . 'includes/class-drtr-travel-intent.php';

/**
 * Clase principal del plugin
 */
class DRTR_Gestione_Tours {
    
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
        
        // Encolar scripts y estilos
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    public function activate() {
        // Registrar CPT
        DRTR_Post_Type::register_post_type();
        
        // Limpiar y recrear términos de viaje para garantizar traducciones y orden
        DRTR_Post_Type::reset_travel_intents();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    private function reset_travel_intent_terms() {
        // Obtener todos los términos de viaje existentes
        $terms = get_terms(array(
            'taxonomy' => 'drtr_travel_intent',
            'hide_empty' => false,
        ));
        
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                wp_delete_term($term->term_id, 'drtr_travel_intent');
            }
        }
        
        // Recrear los términos usando DRTR_Post_Type
        DRTR_Post_Type::get_instance();
    }
    
    public function init() {
        // Inicializar componentes
        DRTR_Post_Type::get_instance();
        DRTR_Meta_Boxes::get_instance();
        DRTR_Ajax_Handler::get_instance();
        DRTR_Frontend::get_instance();
    }
    
    public function load_textdomain() {
        // Obtener el locale del sitio (que ya incluye la lógica del tema)
        $locale = get_locale();
        
        // Cargar traducciones del plugin
        $mofile = DRTR_PLUGIN_DIR . 'languages/' . $locale . '.mo';
        
        if (file_exists($mofile)) {
            load_textdomain('drtr-tours', $mofile);
        }
        
        // Fallback al método estándar de WordPress
        load_plugin_textdomain('drtr-tours', false, dirname(DRTR_PLUGIN_BASENAME) . '/languages');
    }
    
    public function enqueue_frontend_assets() {
        if (is_page('gestione-tours')) {
            wp_enqueue_style(
                'drtr-frontend-css',
                DRTR_PLUGIN_URL . 'assets/css/frontend.css',
                array(),
                DRTR_VERSION
            );
            
            wp_enqueue_style(
                'drtr-intents-css',
                DRTR_PLUGIN_URL . 'assets/css/intents.css',
                array(),
                DRTR_VERSION
            );
            
            wp_enqueue_script(
                'drtr-frontend-js',
                DRTR_PLUGIN_URL . 'assets/js/frontend.js',
                array('jquery'),
                DRTR_VERSION,
                true
            );
            
            wp_localize_script('drtr-frontend-js', 'drtrAjax', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('drtr_nonce'),
                'strings' => array(
                    'confirm_delete' => __('¿Estás seguro de eliminar este tour?', 'drtr-tours'),
                    'error' => __('Ha ocurrido un error. Por favor, intenta de nuevo.', 'drtr-tours'),
                    'success_save' => __('Tour guardado correctamente', 'drtr-tours'),
                    'success_delete' => __('Tour eliminado correctamente', 'drtr-tours'),
                    'edit_button' => __('Editar', 'drtr-tours'),
                    'delete_button' => __('Eliminar', 'drtr-tours'),
                    'edit_tour' => __('Editar Tour', 'drtr-tours'),
                    'itinerary_place' => __('Lugar', 'drtr-tours'),
                    'itinerary_place_placeholder' => __('Ej: Milán', 'drtr-tours'),
                    'itinerary_type' => __('Tipo', 'drtr-tours'),
                    'itinerary_arrival' => __('Llegada', 'drtr-tours'),
                    'itinerary_departure' => __('Salida', 'drtr-tours'),
                    'itinerary_notes' => __('Notas', 'drtr-tours'),
                    'itinerary_notes_placeholder' => __('Descripción de la parada...', 'drtr-tours'),
                    'type_city' => __('Ciudad', 'drtr-tours'),
                    'type_train' => __('Tren', 'drtr-tours'),
                    'type_bus' => __('Bus', 'drtr-tours'),
                    'type_plane' => __('Avión', 'drtr-tours'),
                    'type_boat' => __('Barco', 'drtr-tours'),
                    'type_hotel' => __('Hotel', 'drtr-tours'),
                    'type_visit' => __('Visita', 'drtr-tours'),
                    'type_food' => __('Comida', 'drtr-tours'),
                    'type_activity' => __('Actividad', 'drtr-tours'),
                )
            ));
        }
    }
    
    public function enqueue_admin_assets($hook) {
        $post_type = get_post_type();
        if ('post.php' === $hook || 'post-new.php' === $hook) {
            if ('drtr_tour' === $post_type) {
                wp_enqueue_media();
                
                wp_enqueue_style(
                    'drtr-admin-css',
                    DRTR_PLUGIN_URL . 'assets/css/admin.css',
                    array(),
                    DRTR_VERSION
                );
                
                wp_enqueue_script(
                    'drtr-admin-js',
                    DRTR_PLUGIN_URL . 'assets/js/admin.js',
                    array('jquery'),
                    DRTR_VERSION,
                    true
                );
            }
        }
    }
}

// Inicializar el plugin
function drtr_init() {
    return DRTR_Gestione_Tours::get_instance();
}

drtr_init();
