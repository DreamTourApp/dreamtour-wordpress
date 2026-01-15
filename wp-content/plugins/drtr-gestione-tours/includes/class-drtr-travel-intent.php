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
    ));
    
    if (is_wp_error($intents)) {
        return array();
    }
    
    // Ordenar manualmente por el número de orden guardado en metadata
    usort($intents, function($a, $b) {
        $order_a = (int) get_term_meta($a->term_id, 'drtr_intent_order', true);
        $order_b = (int) get_term_meta($b->term_id, 'drtr_intent_order', true);
        return $order_a - $order_b;
    });
    
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
 * Obtener intents divididos por tipo (viajes vs meses)
 */
function drtr_get_travel_intents_grouped() {
    $all_intents = drtr_get_travel_intents();
    
    $months = array(
        'january', 'february', 'march', 'april', 'may', 'june',
        'july', 'august', 'september', 'october', 'november', 'december'
    );
    
    $grouped = array(
        'experiences' => array(),
        'months' => array(),
    );
    
    foreach ($all_intents as $term_id => $intent) {
        if (in_array($intent['slug'], $months)) {
            $grouped['months'][$term_id] = $intent;
        } else {
            $grouped['experiences'][$term_id] = $intent;
        }
    }
    
    return $grouped;
}

/**
 * Renderizar multiselect de intents dividido en dos secciones
 */
function drtr_render_intents_multiselect($selected_ids = array()) {
    $grouped = drtr_get_travel_intents_grouped();
    ?>
    <div class="drtr-intents-select">
        <!-- Sección: Experiencias de Viaje -->
        <div class="drtr-intents-section">
            <h4 class="drtr-intents-section-title"><?php _e('Intenciones de Viaje', 'drtr-tours'); ?></h4>
            <div class="drtr-intents-group">
                <?php foreach ($grouped['experiences'] as $term_id => $intent) : ?>
                    <label class="drtr-intent-label">
                        <input type="checkbox" name="travel_intents[]" value="<?php echo esc_attr($term_id); ?>" 
                            <?php checked(in_array($term_id, $selected_ids)); ?> class="drtr-intent-checkbox">
                        <span class="drtr-intent-icon"><?php echo esc_html($intent['icon']); ?></span>
                        <span class="drtr-intent-name"><?php echo esc_html($intent['name']); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Sección: Meses -->
        <div class="drtr-intents-section">
            <h4 class="drtr-intents-section-title"><?php _e('Meses', 'drtr-tours'); ?></h4>
            <div class="drtr-intents-group">
                <?php foreach ($grouped['months'] as $term_id => $intent) : ?>
                    <label class="drtr-intent-label">
                        <input type="checkbox" name="travel_intents[]" value="<?php echo esc_attr($term_id); ?>" 
                            <?php checked(in_array($term_id, $selected_ids)); ?> class="drtr-intent-checkbox">
                        <span class="drtr-intent-icon"><?php echo esc_html($intent['icon']); ?></span>
                        <span class="drtr-intent-name"><?php echo esc_html($intent['name']); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
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

/**
 * Renderizar filtro de intents para homepage (frontend)
 */
function drtr_render_intents_filter() {
    $grouped = drtr_get_travel_intents_grouped();
    ?>
    <div class="filter-group filter-group-intents">
        <button type="button" class="filter-intents-toggle" id="filter-intents-toggle">
            <span><?php esc_html_e('Intención de Viaje', 'dreamtour'); ?></span>
            <span class="filter-count" id="filter-count">0</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
        
        <div class="filter-intents-dropdown" id="filter-intents-dropdown" style="display:none;">
            <!-- Sección: Experiencias de Viaje -->
            <div class="filter-intents-section">
                <h4 class="filter-intents-section-title"><?php esc_html_e('Intenciones de Viaje', 'dreamtour'); ?></h4>
                <div class="filter-intents-group">
                    <?php foreach ($grouped['experiences'] as $term_id => $intent) : ?>
                        <label class="filter-intent-label">
                            <input type="checkbox" name="filter_intents[]" value="<?php echo esc_attr($intent['slug']); ?>" class="filter-intent-checkbox">
                            <span class="filter-intent-icon"><?php echo esc_html($intent['icon']); ?></span>
                            <span class="filter-intent-name"><?php echo esc_html($intent['name']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Sección: Meses -->
            <div class="filter-intents-section">
                <h4 class="filter-intents-section-title"><?php esc_html_e('Meses', 'dreamtour'); ?></h4>
                <div class="filter-intents-group">
                    <?php foreach ($grouped['months'] as $term_id => $intent) : ?>
                        <label class="filter-intent-label">
                            <input type="checkbox" name="filter_intents[]" value="<?php echo esc_attr($intent['slug']); ?>" class="filter-intent-checkbox">
                            <span class="filter-intent-icon"><?php echo esc_html($intent['icon']); ?></span>
                            <span class="filter-intent-name"><?php echo esc_html($intent['name']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Renderizar dos filtros separados: Intenciones de Viaje y Meses
 */
function drtr_render_split_intents_filters() {
    $grouped = drtr_get_travel_intents_grouped();
    ?>
    <!-- Filtro: Intenciones de Viaje -->
    <div class="filter-group filter-group-intents">
        <label for="filter-experiences-toggle"><?php esc_html_e('Intenciones de Viaje', 'dreamtour'); ?></label>
        <button type="button" class="filter-intents-toggle" id="filter-experiences-toggle">
            <span><?php esc_html_e('Intenciones de Viaje', 'dreamtour'); ?></span>
            <span class="filter-count" id="filter-experiences-count">0</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
        
        <div class="filter-intents-dropdown" id="filter-experiences-dropdown" style="display:none;">
            <div class="filter-intents-section">
                <div class="filter-intents-group">
                    <?php foreach ($grouped['experiences'] as $term_id => $intent) : ?>
                        <label class="filter-intent-label">
                            <input type="checkbox" name="filter_experiences[]" value="<?php echo esc_attr($intent['slug']); ?>" class="filter-experience-checkbox">
                            <span class="filter-intent-icon"><?php echo esc_html($intent['icon']); ?></span>
                            <span class="filter-intent-name"><?php echo esc_html($intent['name']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtro: Meses -->
    <div class="filter-group filter-group-intents">
        <label for="filter-months-toggle"><?php esc_html_e('Meses', 'dreamtour'); ?></label>
        <button type="button" class="filter-intents-toggle" id="filter-months-toggle">
            <span><?php esc_html_e('Meses', 'dreamtour'); ?></span>
            <span class="filter-count" id="filter-months-count">0</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
        
        <div class="filter-intents-dropdown" id="filter-months-dropdown" style="display:none;">
            <div class="filter-intents-section">
                <div class="filter-intents-group">
                    <?php foreach ($grouped['months'] as $term_id => $intent) : ?>
                        <label class="filter-intent-label">
                            <input type="checkbox" name="filter_months[]" value="<?php echo esc_attr($intent['slug']); ?>" class="filter-month-checkbox">
                            <span class="filter-intent-icon"><?php echo esc_html($intent['icon']); ?></span>
                            <span class="filter-intent-name"><?php echo esc_html($intent['name']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
