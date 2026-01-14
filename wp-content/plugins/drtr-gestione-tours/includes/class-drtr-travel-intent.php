<?php
/**
 * Helper para Travel Intent Taxonomy
 */

/**
 * Obtener todos los intents con sus íconos
 */
function drtr_get_travel_intents() {
    $intents = get_terms(array(
        'taxonomy' => 'drtr_travel_intent',
        'hide_empty' => false,
        'orderby' => 'meta_value_num',
        'meta_key' => 'drtr_intent_order',
        'order' => 'ASC',
    ));
    
    if (is_wp_error($intents)) {
        return array();
    }
    
    $result = array();
    foreach ($intents as $intent) {
        $icon = get_term_meta($intent->term_id, 'drtr_intent_icon', true);
        $result[$intent->term_id] = array(
            'slug' => $intent->slug,
            'name' => $intent->name,
            'icon' => $icon ?: '🏷️',
        );
    }
    
    return $result;
}

/**
 * Renderizar multiselect de intents
 */
function drtr_render_intents_multiselect($selected_ids = array()) {
    $intents = drtr_get_travel_intents();
    ?>
    <div class="drtr-intents-select">
        <?php foreach ($intents as $term_id => $intent) : ?>
            <label class="drtr-intent-label">
                <input type="checkbox" name="travel_intents[]" value="<?php echo esc_attr($term_id); ?>" 
                    <?php checked(in_array($term_id, $selected_ids)); ?> class="drtr-intent-checkbox">
                <span class="drtr-intent-icon"><?php echo esc_html($intent['icon']); ?></span>
                <span class="drtr-intent-name"><?php echo esc_html($intent['name']); ?></span>
            </label>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Obtener intents de un tour
 */
function drtr_get_tour_intents($post_id) {
    $terms = get_the_terms($post_id, 'drtr_travel_intent');
    
    if (is_wp_error($terms) || empty($terms)) {
        return array();
    }
    
    return wp_list_pluck($terms, 'term_id');
}
