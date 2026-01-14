<?php
/**
 * Registro del Custom Post Type Tour
 */

class DRTR_Post_Type {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
    }
    
    public static function register_post_type() {
        $labels = array(
            'name'               => _x('Tours', 'post type general name', 'drtr-tours'),
            'singular_name'      => _x('Tour', 'post type singular name', 'drtr-tours'),
            'menu_name'          => _x('Tours', 'admin menu', 'drtr-tours'),
            'name_admin_bar'     => _x('Tour', 'add new on admin bar', 'drtr-tours'),
            'add_new'            => _x('Añadir Nuevo', 'tour', 'drtr-tours'),
            'add_new_item'       => __('Añadir Nuevo Tour', 'drtr-tours'),
            'new_item'           => __('Nuevo Tour', 'drtr-tours'),
            'edit_item'          => __('Editar Tour', 'drtr-tours'),
            'view_item'          => __('Ver Tour', 'drtr-tours'),
            'all_items'          => __('Todos los Tours', 'drtr-tours'),
            'search_items'       => __('Buscar Tours', 'drtr-tours'),
            'not_found'          => __('No se encontraron tours', 'drtr-tours'),
            'not_found_in_trash' => __('No se encontraron tours en la papelera', 'drtr-tours')
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'tour'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-palmtree',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
            'show_in_rest'       => true,
        );
        
        register_post_type('drtr_tour', $args);
    }
    
    public function register_taxonomies() {
        // Taxonomía: Destino
        $labels = array(
            'name'              => _x('Destinos', 'taxonomy general name', 'drtr-tours'),
            'singular_name'     => _x('Destino', 'taxonomy singular name', 'drtr-tours'),
            'search_items'      => __('Buscar Destinos', 'drtr-tours'),
            'all_items'         => __('Todos los Destinos', 'drtr-tours'),
            'edit_item'         => __('Editar Destino', 'drtr-tours'),
            'update_item'       => __('Actualizar Destino', 'drtr-tours'),
            'add_new_item'      => __('Añadir Nuevo Destino', 'drtr-tours'),
            'new_item_name'     => __('Nuevo Nombre de Destino', 'drtr-tours'),
            'menu_name'         => __('Destinos', 'drtr-tours'),
        );
        
        register_taxonomy('drtr_destination', 'drtr_tour', array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'destino'),
        ));
        
        // Taxonomía: Tipo de Tour
        $labels = array(
            'name'              => _x('Tipos de Tour', 'taxonomy general name', 'drtr-tours'),
            'singular_name'     => _x('Tipo de Tour', 'taxonomy singular name', 'drtr-tours'),
            'search_items'      => __('Buscar Tipos', 'drtr-tours'),
            'all_items'         => __('Todos los Tipos', 'drtr-tours'),
            'edit_item'         => __('Editar Tipo', 'drtr-tours'),
            'update_item'       => __('Actualizar Tipo', 'drtr-tours'),
            'add_new_item'      => __('Añadir Nuevo Tipo', 'drtr-tours'),
            'new_item_name'     => __('Nuevo Nombre de Tipo', 'drtr-tours'),
            'menu_name'         => __('Tipos de Tour', 'drtr-tours'),
        );
        
        register_taxonomy('drtr_tour_type', 'drtr_tour', array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'tipo-tour'),
        ));
        
        // Taxonomía: Intención de Viaje (Meses e Intenciones)
        $labels = array(
            'name'              => _x('Intención de Viaje', 'taxonomy general name', 'drtr-tours'),
            'singular_name'     => _x('Intención', 'taxonomy singular name', 'drtr-tours'),
            'search_items'      => __('Buscar Intenciones', 'drtr-tours'),
            'all_items'         => __('Todas las Intenciones', 'drtr-tours'),
            'edit_item'         => __('Editar Intención', 'drtr-tours'),
            'update_item'       => __('Actualizar Intención', 'drtr-tours'),
            'add_new_item'      => __('Añadir Nueva Intención', 'drtr-tours'),
            'new_item_name'     => __('Nuevo Nombre de Intención', 'drtr-tours'),
            'menu_name'         => __('Intenciones de Viaje', 'drtr-tours'),
        );
        
        register_taxonomy('drtr_travel_intent', 'drtr_tour', array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'intent'),
        ));
        
        // Agregar términos de la tassonomía de intenciones
        $this->add_travel_intent_terms();
    }
    
    /**
     * Agregar términos predefinidos para intención de viaje
     */
    private function add_travel_intent_terms() {
        $intents = array(
            // Meses
            'january'            => array('label' => 'Enero', 'icon' => '🗓️'),
            'february'           => array('label' => 'Febrero', 'icon' => '🗓️'),
            'march'              => array('label' => 'Marzo', 'icon' => '🗓️'),
            'april'              => array('label' => 'Abril', 'icon' => '🗓️'),
            'may'                => array('label' => 'Mayo', 'icon' => '🗓️'),
            'june'               => array('label' => 'Junio', 'icon' => '🗓️'),
            'july'               => array('label' => 'Julio', 'icon' => '🗓️'),
            'august'             => array('label' => 'Agosto', 'icon' => '🗓️'),
            'september'          => array('label' => 'Septiembre', 'icon' => '🗓️'),
            'october'            => array('label' => 'Octubre', 'icon' => '🗓️'),
            'november'           => array('label' => 'Noviembre', 'icon' => '🗓️'),
            'december'           => array('label' => 'Diciembre', 'icon' => '🗓️'),
            
            // Intenciones de viaje
            'group_cruises'      => array('label' => 'Crociere di gruppo', 'icon' => '⛴️'),
            'group_flights'      => array('label' => 'Voli di gruppo', 'icon' => '✈️'),
            'beach_days'         => array('label' => 'Giornate al mare', 'icon' => '🏖️'),
            'italy_trips'        => array('label' => 'Viaggi in Italia', 'icon' => '🇮🇹'),
            'gift_cards'         => array('label' => 'Carte regalo', 'icon' => '🎁'),
            'bernina_express'    => array('label' => 'Bernina Express panoramico', 'icon' => '🚂'),
            'christmas_markets'  => array('label' => 'Mercatini di Natale', 'icon' => '🎄'),
            'mountain_trips'     => array('label' => 'Vacanze in montagna', 'icon' => '⛰️'),
        );
        
        foreach ($intents as $slug => $intent_data) {
            if (!term_exists($slug, 'drtr_travel_intent')) {
                wp_insert_term($intent_data['label'], 'drtr_travel_intent', array('slug' => $slug));
                // Guardar el icono como metadata del término
                update_term_meta(get_term_by('slug', $slug, 'drtr_travel_intent')->term_id, 'drtr_intent_icon', $intent_data['icon']);
            }
        }
    }
}
