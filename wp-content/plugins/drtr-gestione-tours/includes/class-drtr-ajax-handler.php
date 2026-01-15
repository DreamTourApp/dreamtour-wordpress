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
        add_action('wp_ajax_drtr_duplicate_tour', array($this, 'duplicate_tour'));
        add_action('wp_ajax_drtr_toggle_status', array($this, 'toggle_status'));
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
            'status' => $post->post_status,
            'image_id' => get_post_meta($tour_id, '_drtr_image_id', true),
            'image_url' => '',
            'price' => get_post_meta($tour_id, '_drtr_price', true),
            'child_price' => get_post_meta($tour_id, '_drtr_child_price', true),
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
        
        // RAW values antes de sanitizar
        $content_raw = isset($_POST['content']) ? $_POST['content'] : '';
        $excerpt_raw = isset($_POST['excerpt']) ? $_POST['excerpt'] : '';
        
        error_log('=== DRTR SAVE DEBUG ===');
        error_log('Tour ID: ' . $tour_id);
        error_log('Title: ' . $title);
        error_log('Content RAW (first 100): ' . substr($content_raw, 0, 100));
        error_log('Excerpt RAW (first 100): ' . substr($excerpt_raw, 0, 100));
        error_log('Content RAW length: ' . strlen($content_raw));
        error_log('Excerpt RAW length: ' . strlen($excerpt_raw));
        
        $content = wp_kses_post($content_raw);
        $excerpt = sanitize_textarea_field($excerpt_raw);
        
        error_log('Content AFTER sanitize length: ' . strlen($content));
        error_log('Excerpt AFTER sanitize length: ' . strlen($excerpt));
        
        if (empty($title)) {
            wp_send_json_error(array('message' => __('El título es obligatorio', 'drtr-tours')));
        }
        
        $post_data = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_excerpt' => $excerpt,
            'post_type' => 'drtr_tour',
            'post_status' => isset($_POST['post_status']) ? sanitize_text_field($_POST['post_status']) : 'draft',
        );
        
        error_log('Post data array: ' . print_r($post_data, true));
        
        if ($tour_id) {
            $post_data['ID'] = $tour_id;
            $result = wp_update_post($post_data, true);
            error_log('wp_update_post called with ID: ' . $tour_id);
        } else {
            $result = wp_insert_post($post_data, true);
            error_log('wp_insert_post called (new tour)');
        }
        
        if (is_wp_error($result)) {
            error_log('WP Error: ' . $result->get_error_message());
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        $tour_id = $result;
        error_log('Saved! Tour ID: ' . $tour_id);
        
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
            '_drtr_child_price' => 'sanitize_text_field',
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
        
        // Verificar que se guardó correctamente (debug)
        if (current_user_can('manage_options')) {
            $saved_post = get_post($tour_id);
            error_log('=== POST-SAVE VERIFICATION ===');
            error_log('  Tour ID: ' . $tour_id);
            error_log('  Saved Title: ' . $saved_post->post_title);
            error_log('  Saved Content length: ' . strlen($saved_post->post_content));
            error_log('  Saved Excerpt length: ' . strlen($saved_post->post_excerpt));
            error_log('  Saved Content (first 100): ' . substr($saved_post->post_content, 0, 100));
            error_log('  Saved Excerpt (first 100): ' . substr($saved_post->post_excerpt, 0, 100));
            
            // Query diretta al database
            global $wpdb;
            $db_check = $wpdb->get_row($wpdb->prepare(
                "SELECT post_content, post_excerpt FROM {$wpdb->posts} WHERE ID = %d",
                $tour_id
            ));
            error_log('  DB Content length: ' . strlen($db_check->post_content));
            error_log('  DB Excerpt length: ' . strlen($db_check->post_excerpt));
        }
        
        wp_send_json_success(array(
            'message' => __('Tour guardado correctamente', 'drtr-tours'),
            'tour_id' => $tour_id,
            'debug' => array(
                'content_received' => strlen($content_raw),
                'excerpt_received' => strlen($excerpt_raw),
                'content_saved' => strlen($content),
                'excerpt_saved' => strlen($excerpt),
            ),
        ));
    }
    
    /**
     * Duplicar tour
     */
    public function duplicate_tour() {
        check_ajax_referer('drtr_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('No tienes permisos', 'drtr-tours')));
        }
        
        $tour_id = isset($_POST['tour_id']) ? absint($_POST['tour_id']) : 0;
        
        if (!$tour_id) {
            wp_send_json_error(array('message' => __('ID de tour inválido', 'drtr-tours')));
        }
        
        $original_post = get_post($tour_id);
        
        if (!$original_post || $original_post->post_type !== 'drtr_tour') {
            wp_send_json_error(array('message' => __('Tour no encontrado', 'drtr-tours')));
        }
        
        // Crear nuevo post duplicado
        $new_post_data = array(
            'post_title' => $original_post->post_title . ' (Copia)',
            'post_content' => $original_post->post_content,
            'post_excerpt' => $original_post->post_excerpt,
            'post_type' => 'drtr_tour',
            'post_status' => 'draft', // Crear como borrador
        );
        
        $new_tour_id = wp_insert_post($new_post_data, true);
        
        if (is_wp_error($new_tour_id)) {
            wp_send_json_error(array('message' => $new_tour_id->get_error_message()));
        }
        
        // Copiar todos los meta fields
        $meta_fields = array(
            '_drtr_image_id',
            '_drtr_price',
            '_drtr_child_price',
            '_drtr_duration',
            '_drtr_transport_type',
            '_drtr_max_people',
            '_drtr_start_date',
            '_drtr_end_date',
            '_drtr_location',
            '_drtr_rating',
            '_drtr_includes',
            '_drtr_not_includes',
            '_drtr_itinerary',
        );
        
        foreach ($meta_fields as $meta_key) {
            $meta_value = get_post_meta($tour_id, $meta_key, true);
            if ($meta_value) {
                update_post_meta($new_tour_id, $meta_key, $meta_value);
            }
        }
        
        // Copiar thumbnail si existe
        $thumbnail_id = get_post_meta($tour_id, '_drtr_image_id', true);
        if ($thumbnail_id) {
            set_post_thumbnail($new_tour_id, $thumbnail_id);
        }
        
        // Copiar taxonomías (travel intents)
        $terms = wp_get_object_terms($tour_id, 'drtr_travel_intent', array('fields' => 'ids'));
        if (!is_wp_error($terms) && !empty($terms)) {
            wp_set_object_terms($new_tour_id, $terms, 'drtr_travel_intent');
        }
        
        wp_send_json_success(array(
            'message' => __('Tour duplicado correctamente', 'drtr-tours'),
            'tour_id' => $new_tour_id,
        ));
    }
    
    /**
     * Cambiar estado de publicación de un tour
     */
    public function toggle_status() {
        check_ajax_referer('drtr_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('No tienes permisos', 'drtr-tours')));
        }
        
        $tour_id = isset($_POST['tour_id']) ? absint($_POST['tour_id']) : 0;
        $new_status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
        
        if (!$tour_id || !in_array($new_status, array('publish', 'draft'))) {
            wp_send_json_error(array('message' => __('Parámetros inválidos', 'drtr-tours')));
        }
        
        $post = get_post($tour_id);
        
        if (!$post || $post->post_type !== 'drtr_tour') {
            wp_send_json_error(array('message' => __('Tour no encontrado', 'drtr-tours')));
        }
        
        $result = wp_update_post(array(
            'ID' => $tour_id,
            'post_status' => $new_status,
        ));
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        $status_label = $new_status === 'publish' ? __('publicado', 'drtr-tours') : __('borrador', 'drtr-tours');
        
        wp_send_json_success(array(
            'message' => sprintf(__('Tour marcado como %s', 'drtr-tours'), $status_label),
            'status' => $new_status,
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
