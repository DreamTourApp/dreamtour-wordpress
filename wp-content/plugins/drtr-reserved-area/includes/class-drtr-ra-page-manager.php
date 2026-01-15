<?php
/**
 * Gestión de la página /area-riservata
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_RA_Page_Manager {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'maybe_create_page'));
    }
    
    /**
     * Crear la página si no existe
     */
    public function maybe_create_page() {
        $page = get_page_by_path('area-riservata');
        
        if (!$page) {
            self::create_reserved_page();
        }
    }
    
    /**
     * Crear página de área reservada
     */
    public static function create_reserved_page() {
        $page = get_page_by_path('area-riservata');
        
        if (!$page) {
            $page_id = wp_insert_post(array(
                'post_title'   => __('Area Riservata', 'drtr-reserved-area'),
                'post_name'    => 'area-riservata',
                'post_content' => '[drtr_reserved_area]',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ));
            
            if (!is_wp_error($page_id)) {
                update_post_meta($page_id, '_drtr_ra_page', '1');
            }
        }
    }
}
