<?php
/**
 * Manejador de peticiones AJAX
 */

class DRTR_Ajax_Handler {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Acciones AJAX
        add_action('wp_ajax_drtr_get_tours', array($this, 'get_tours'));
        add_action('wp_ajax_drtr_get_tour', array($this, 'get_tour'));
        add_action('wp_ajax_drtr_save_tour', array($this, 'save_tour'));
        add_action('wp_ajax_drtr_delete_tour', array($this, 'delete_tour'));
    }
    
    /**
     * Obtener lista de tours con paginación
     */
    public function get_tours() {
        check_ajax_referer('drtr_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('No tienes permisos', 'drtr-tours')));
        }
        
        $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;
        $per_page = 10;
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        
        $args = array(
            'post_type' => 'drtr_tour',
            'posts_per_page' => $per_page,
            'paged' => $paged,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        if (!empty($search)) {
            $args['s'] = $search;
        }
        
        $query = new WP_Query($args);
        
        $tours = array();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                
                $image_id = get_post_meta($post_id, '_drtr_image_id', true);
                $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
                
                $tours[] = array(
                    'id' => $post_id,
                    'title' => get_the_title(),
                    'image_url' => $image_url,
                    'price' => get_post_meta($post_id, '_drtr_price', true),
                    'duration' => get_post_meta($post_id, '_drtr_duration', true),
                    'transport_type' => get_post_meta($post_id, '_drtr_transport_type', true),
                    'location' => get_post_meta($post_id, '_drtr_location', true),
                    'start_date' => get_post_meta($post_id, '_drtr_start_date', true),
                    'max_people' => get_post_meta($post_id, '_drtr_max_people', true),
                    'status' => get_post_status(),
                );
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success(array(
            'tours' => $tours,
            'total_pages' => $query->max_num_pages,
            'current_page' => $paged,
            'total_tours' => $query->found_posts,
        ));
    }
    
    /**
     * Obtener un tour específico
     */
    public function get_tour() {
        check_ajax_referer('drtr_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('No tienes permisos', 'drtr-tours')));
        }
        
        $tour_id = isset($_POST['tour_id']) ? absint($_POST['tour_id']) : 0;
        
        if (!$tour_id) {
            wp_send_json_error(array('message' => __('ID de tour inválido', 'drtr-tours')));
        }
        
        $post = get_post($tour_id);
        
        if (!$post || $post->post_type !== 'drtr_tour') {
            wp_send_json_error(array('message' => __('Tour no encontrado', 'drtr-tours')));
        }
        
        $tour_data = array(
            'id' => $tour_id,
            'title' => $post->post_title,
            'content' => $post->post_content,
            'excerpt' => $post->post_excerpt,
            'image_id' => get_post_meta($tour_id, '_drtr_image_id', true),
            'image_url' => '',
            'price' => get_post_meta($tour_id, '_drtr_price', true),
            'duration' => get_post_meta($tour_id, '_drtr_duration', true),
            'transport_type' => get_post_meta($tour_id, '_drtr_transport_type', true),
            'max_people' => get_post_meta($tour_id, '_drtr_max_people', true),
            'start_date' => get_post_meta($tour_id, '_drtr_start_date', true),
            'end_date' => get_post_meta($tour_id, '_drtr_end_date', true),
            'location' => get_post_meta($tour_id, '_drtr_location', true),
            'rating' => get_post_meta($tour_id, '_drtr_rating', true),
            'includes' => get_post_meta($tour_id, '_drtr_includes', true),
            'not_includes' => get_post_meta($tour_id, '_drtr_not_includes', true),
            'itinerary' => get_post_meta($tour_id, '_drtr_itinerary', true),
            'travel_intents' => drtr_get_tour_intents($tour_id),
        );
        
        // Obtener URL de imagen si existe
        if ($tour_data['image_id']) {
            $tour_data['image_url'] = wp_get_attachment_url($tour_data['image_id']);
        }
        
        wp_send_json_success($tour_data);
    }
    
    /**
     * Guardar o actualizar tour
     */
    public function save_tour() {
        check_ajax_referer('drtr_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('No tienes permisos', 'drtr-tours')));
        }
        
        $tour_id = isset($_POST['tour_id']) ? absint($_POST['tour_id']) : 0;
        $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $content = isset($_POST['content']) ? wp_kses_post($_POST['content']) : '';
        $excerpt = isset($_POST['excerpt']) ? sanitize_textarea_field($_POST['excerpt']) : '';
        
        if (empty($title)) {
            wp_send_json_error(array('message' => __('El título es obligatorio', 'drtr-tours')));
        }
        
        $post_data = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_excerpt' => $excerpt,
            'post_type' => 'drtr_tour',
            'post_status' => 'publish',
        );
        
        if ($tour_id) {
            $post_data['ID'] = $tour_id;
            $result = wp_update_post($post_data, true);
        } else {
            $result = wp_insert_post($post_data, true);
        }
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        $tour_id = $result;
        
        // Manejar subida de imagen si existe
        if (!empty($_FILES['tour_image']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            
            $attachment_id = media_handle_upload('tour_image', $tour_id);
            
            if (!is_wp_error($attachment_id)) {
                update_post_meta($tour_id, '_drtr_image_id', $attachment_id);
                set_post_thumbnail($tour_id, $attachment_id);
            }
        } elseif (isset($_POST['image_id'])) {
            // Si se envió un ID de imagen existente
            $image_id = absint($_POST['image_id']);
            if ($image_id) {
                update_post_meta($tour_id, '_drtr_image_id', $image_id);
                set_post_thumbnail($tour_id, $image_id);
            }
        }
        
        // Guardar meta fields
        $meta_fields = array(
            '_drtr_price' => 'sanitize_text_field',
            '_drtr_duration' => 'absint',
            '_drtr_transport_type' => 'sanitize_text_field',
            '_drtr_max_people' => 'absint',
            '_drtr_start_date' => 'sanitize_text_field',
            '_drtr_end_date' => 'sanitize_text_field',
            '_drtr_location' => 'sanitize_text_field',
            '_drtr_rating' => 'sanitize_text_field',
            '_drtr_includes' => 'sanitize_textarea_field',
            '_drtr_not_includes' => 'sanitize_textarea_field',
            '_drtr_itinerary' => 'wp_kses_post',
        );
        
        foreach ($meta_fields as $meta_key => $sanitize_callback) {
            $field_name = str_replace('_drtr_', '', $meta_key);
            
            if (isset($_POST[$field_name])) {
                $value = call_user_func($sanitize_callback, $_POST[$field_name]);
                update_post_meta($tour_id, $meta_key, $value);
            }
        }
        
        // Guardar Travel Intents (taxonomía)
        if (isset($_POST['travel_intents']) && !empty($_POST['travel_intents'])) {
            $intent_ids = array_map('absint', (array) $_POST['travel_intents']);
            wp_set_object_terms($tour_id, $intent_ids, 'drtr_travel_intent');
        } else {
            wp_set_object_terms($tour_id, array(), 'drtr_travel_intent');
        }
        
        wp_send_json_success(array(
            'message' => __('Tour guardado correctamente', 'drtr-tours'),
            'tour_id' => $tour_id,
        ));
    }
    
    /**
     * Eliminar tour
     */
    public function delete_tour() {
        check_ajax_referer('drtr_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('No tienes permisos', 'drtr-tours')));
        }
        
        $tour_id = isset($_POST['tour_id']) ? absint($_POST['tour_id']) : 0;
        
        if (!$tour_id) {
            wp_send_json_error(array('message' => __('ID de tour inválido', 'drtr-tours')));
        }
        
        $result = wp_delete_post($tour_id, true);
        
        if (!$result) {
            wp_send_json_error(array('message' => __('Error al eliminar el tour', 'drtr-tours')));
        }
        
        wp_send_json_success(array('message' => __('Tour eliminado correctamente', 'drtr-tours')));
    }
}
