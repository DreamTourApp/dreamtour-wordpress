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
            // Intenciones de viaje
            'group_cruises'      => array('label' => __('Crociere di gruppo', 'drtr-tours'), 'icon' => '⛴️'),
            'group_flights'      => array('label' => __('Voli di gruppo', 'drtr-tours'), 'icon' => '✈️'),
            'beach_days'         => array('label' => __('Giornate al mare', 'drtr-tours'), 'icon' => '🏖️'),
            'italy_trips'        => array('label' => __('Viaggi in Italia', 'drtr-tours'), 'icon' => '🇮🇹'),
            'gift_cards'         => array('label' => __('Carte regalo', 'drtr-tours'), 'icon' => '🎁'),
            'bernina_express'    => array('label' => __('Bernina Express panoramico', 'drtr-tours'), 'icon' => '🚂'),
            'christmas_markets'  => array('label' => __('Mercatini di Natale', 'drtr-tours'), 'icon' => '🎄'),
            'mountain_trips'     => array('label' => __('Vacanze in montagna', 'drtr-tours'), 'icon' => '⛰️'),
            
            // Meses (Ordenados por número del mes)
            'january'            => array('label' => __('Enero', 'drtr-tours'), 'icon' => '🗓️'),
            'february'           => array('label' => __('Febrero', 'drtr-tours'), 'icon' => '🗓️'),
            'march'              => array('label' => __('Marzo', 'drtr-tours'), 'icon' => '🗓️'),
            'april'              => array('label' => __('Abril', 'drtr-tours'), 'icon' => '🗓️'),
            'may'                => array('label' => __('Mayo', 'drtr-tours'), 'icon' => '🗓️'),
            'june'               => array('label' => __('Junio', 'drtr-tours'), 'icon' => '🗓️'),
            'july'               => array('label' => __('Julio', 'drtr-tours'), 'icon' => '🗓️'),
            'august'             => array('label' => __('Agosto', 'drtr-tours'), 'icon' => '🗓️'),
            'september'          => array('label' => __('Septiembre', 'drtr-tours'), 'icon' => '🗓️'),
            'october'            => array('label' => __('Octubre', 'drtr-tours'), 'icon' => '🗓️'),
            'november'           => array('label' => __('Noviembre', 'drtr-tours'), 'icon' => '🗓️'),
            'december'           => array('label' => __('Diciembre', 'drtr-tours'), 'icon' => '🗓️'),
        );
        
        $order = 1;
        foreach ($intents as $slug => $intent_data) {
            if (!term_exists($slug, 'drtr_travel_intent')) {
                wp_insert_term($intent_data['label'], 'drtr_travel_intent', array('slug' => $slug));
                $term = get_term_by('slug', $slug, 'drtr_travel_intent');
                // Guardar el icono como metadata del término
                update_term_meta($term->term_id, 'drtr_intent_icon', $intent_data['icon']);
                // Guardar el número de orden para ordenar correctamente
                update_term_meta($term->term_id, 'drtr_intent_order', $order);
            }
            $order++;
        }
    }
    
    /**
     * Reset intents para recriarlos con números de orden
     */
    public static function reset_travel_intents() {
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
        
        // Recrear los términos
        $instance = self::get_instance();
        $instance->add_travel_intent_terms();
    }
}
