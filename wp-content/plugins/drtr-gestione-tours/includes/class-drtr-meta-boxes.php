<?php
/**
 * Meta Boxes para el CPT Tour
 */

class DRTR_Meta_Boxes {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_drtr_tour', array($this, 'save_meta_boxes'), 10, 2);
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'drtr_tour_details',
            __('Detalles del Tour', 'drtr-tours'),
            array($this, 'render_tour_details_meta_box'),
            'drtr_tour',
            'normal',
            'high'
        );
    }
    
    public function render_tour_details_meta_box($post) {
        wp_nonce_field('drtr_tour_meta_box', 'drtr_tour_meta_box_nonce');
        
        // Obtener valores guardados
        $price = get_post_meta($post->ID, '_drtr_price', true);
        $duration = get_post_meta($post->ID, '_drtr_duration', true);
        $transport_type = get_post_meta($post->ID, '_drtr_transport_type', true);
        $max_people = get_post_meta($post->ID, '_drtr_max_people', true);
        $start_date = get_post_meta($post->ID, '_drtr_start_date', true);
        $end_date = get_post_meta($post->ID, '_drtr_end_date', true);
        $location = get_post_meta($post->ID, '_drtr_location', true);
        $rating = get_post_meta($post->ID, '_drtr_rating', true);
        $includes = get_post_meta($post->ID, '_drtr_includes', true);
        $not_includes = get_post_meta($post->ID, '_drtr_not_includes', true);
        $itinerary = get_post_meta($post->ID, '_drtr_itinerary', true);
        $image_id = get_post_meta($post->ID, '_drtr_image_id', true);
        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
        ?>
        
        <div class="drtr-meta-box-container">
            <table class="form-table">
                <tr>
                    <th><label for="drtr_image"><?php _e('Locandina (Imagen)', 'drtr-tours'); ?></label></th>
                    <td>
                        <div class="drtr-admin-image-upload">
                            <input type="hidden" id="drtr_image_id" name="drtr_image_id" value="<?php echo esc_attr($image_id); ?>">
                            <button type="button" class="button drtr-upload-image-btn">
                                <?php _e('Seleccionar Imagen', 'drtr-tours'); ?>
                            </button>
                            <button type="button" class="button drtr-remove-image-btn" style="<?php echo $image_url ? '' : 'display:none;'; ?>">
                                <?php _e('Eliminar Imagen', 'drtr-tours'); ?>
                            </button>
                            <div class="drtr-admin-image-preview" style="margin-top:10px;<?php echo $image_url ? '' : 'display:none;'; ?>">
                                <img src="<?php echo esc_url($image_url); ?>" style="max-width:300px;height:auto;display:block;">
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="drtr_price"><?php _e('Precio (€)', 'drtr-tours'); ?></label></th>
                    <td>
                        <input type="number" id="drtr_price" name="drtr_price" value="<?php echo esc_attr($price); ?>" step="0.01" class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th><label for="drtr_duration"><?php _e('Duración (días)', 'drtr-tours'); ?></label></th>
                    <td>
                        <input type="number" id="drtr_duration" name="drtr_duration" value="<?php echo esc_attr($duration); ?>" class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th><label for="drtr_transport_type"><?php _e('Tipo de Transporte', 'drtr-tours'); ?></label></th>
                    <td>
                        <select id="drtr_transport_type" name="drtr_transport_type" class="regular-text">
                            <option value=""><?php _e('Seleccionar...', 'drtr-tours'); ?></option>
                            <option value="bus" <?php selected($transport_type, 'bus'); ?>><?php _e('Bus', 'drtr-tours'); ?></option>
                            <option value="avion" <?php selected($transport_type, 'avion'); ?>><?php _e('Avión', 'drtr-tours'); ?></option>
                            <option value="tren" <?php selected($transport_type, 'tren'); ?>><?php _e('Tren', 'drtr-tours'); ?></option>
                            <option value="barco" <?php selected($transport_type, 'barco'); ?>><?php _e('Barco', 'drtr-tours'); ?></option>
                            <option value="mixto" <?php selected($transport_type, 'mixto'); ?>><?php _e('Mixto', 'drtr-tours'); ?></option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="drtr_max_people"><?php _e('Máximo de Personas', 'drtr-tours'); ?></label></th>
                    <td>
                        <input type="number" id="drtr_max_people" name="drtr_max_people" value="<?php echo esc_attr($max_people); ?>" class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th><label for="drtr_start_date"><?php _e('Fecha de Inicio', 'drtr-tours'); ?></label></th>
                    <td>
                        <input type="date" id="drtr_start_date" name="drtr_start_date" value="<?php echo esc_attr($start_date); ?>" class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th><label for="drtr_end_date"><?php _e('Fecha de Fin', 'drtr-tours'); ?></label></th>
                    <td>
                        <input type="date" id="drtr_end_date" name="drtr_end_date" value="<?php echo esc_attr($end_date); ?>" class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th><label for="drtr_location"><?php _e('Ubicación/Ciudad', 'drtr-tours'); ?></label></th>
                    <td>
                        <input type="text" id="drtr_location" name="drtr_location" value="<?php echo esc_attr($location); ?>" class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th><label for="drtr_rating"><?php _e('Valoración (0-5)', 'drtr-tours'); ?></label></th>
                    <td>
                        <input type="number" id="drtr_rating" name="drtr_rating" value="<?php echo esc_attr($rating); ?>" min="0" max="5" step="0.1" class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th><label for="drtr_includes"><?php _e('Qué incluye', 'drtr-tours'); ?></label></th>
                    <td>
                        <textarea id="drtr_includes" name="drtr_includes" rows="5" class="large-text"><?php echo esc_textarea($includes); ?></textarea>
                        <p class="description"><?php _e('Un elemento por línea', 'drtr-tours'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="drtr_not_includes"><?php _e('Qué no incluye', 'drtr-tours'); ?></label></th>
                    <td>
                        <textarea id="drtr_not_includes" name="drtr_not_includes" rows="5" class="large-text"><?php echo esc_textarea($not_includes); ?></textarea>
                        <p class="description"><?php _e('Un elemento por línea', 'drtr-tours'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="drtr_itinerary"><?php _e('Itinerario', 'drtr-tours'); ?></label></th>
                    <td>
                        <?php
                        wp_editor($itinerary, 'drtr_itinerary', array(
                            'textarea_name' => 'drtr_itinerary',
                            'textarea_rows' => 10,
                            'media_buttons' => false,
                            'teeny' => true,
                        ));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    
    public function save_meta_boxes($post_id, $post) {
        // Verificar nonce
        if (!isset($_POST['drtr_tour_meta_box_nonce']) || !wp_verify_nonce($_POST['drtr_tour_meta_box_nonce'], 'drtr_tour_meta_box')) {
            return;
        }
        
        // Verificar permisos
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Evitar autoguardado
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Guardar campos
        $fields = array(
            '_drtr_image_id' => 'absint',
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
        
        foreach ($fields as $meta_key => $sanitize_callback) {
            $field_name = str_replace('_drtr_', 'drtr_', $meta_key);
            
            if (isset($_POST[$field_name])) {
                $value = call_user_func($sanitize_callback, $_POST[$field_name]);
                update_post_meta($post_id, $meta_key, $value);
            } else {
                delete_post_meta($post_id, $meta_key);
            }
        }
    }
}
