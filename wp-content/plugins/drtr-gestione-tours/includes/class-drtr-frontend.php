<?php
/**
 * Frontend - Página de gestión de tours
 */

class DRTR_Frontend {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'create_management_page'));
        add_action('init', array($this, 'register_shortcode'));
        add_action('template_redirect', array($this, 'handle_management_page'));
        add_filter('the_content', array($this, 'render_management_interface'));
    }
    
    /**
     * Registrar shortcode para la página de gestión
     */
    public function register_shortcode() {
        add_shortcode('drtr_tours_manager', array($this, 'render_shortcode'));
    }
    
    /**
     * Procesar shortcode [drtr_tours_manager]
     */
    public function render_shortcode() {
        if (!current_user_can('manage_options')) {
            return '<div class="drtr-error"><p>' . __('No tienes permisos para acceder a esta página.', 'drtr-tours') . '</p></div>';
        }
        
        // Si hay parámetro new_tour, mostrar página de creación
        if (isset($_GET['new_tour'])) {
            return $this->render_new_tour_page();
        }
        
        // Si hay parámetro edit_tour, mostrar página de edición
        if (isset($_GET['edit_tour'])) {
            return $this->render_edit_page(intval($_GET['edit_tour']));
        }
        
        // Mostrar interfaz de gestión
        ob_start();
        ?>
        <div id="drtr-tours-manager" class="drtr-container">
            <!-- Header -->
            <div class="drtr-header">
                <h1><?php _e('Gestión de Tours', 'drtr-tours'); ?></h1>
                <a href="<?php echo esc_url(add_query_arg('new_tour', '1', get_permalink())); ?>" class="drtr-btn drtr-btn-primary">
                    <span class="dashicons dashicons-plus"></span>
                    <?php _e('Añadir Nuevo Tour', 'drtr-tours'); ?>
                </a>
            </div>
            
            <!-- Búsqueda -->
            <div class="drtr-search-box">
                <input type="text" id="drtr-search-input" placeholder="<?php esc_attr_e('Buscar tours...', 'drtr-tours'); ?>">
                <button id="drtr-search-btn" class="drtr-btn">
                    <span class="dashicons dashicons-search"></span>
                    <?php _e('Buscar', 'drtr-tours'); ?>
                </button>
            </div>
            
            <!-- Tabla de tours -->
            <div id="drtr-tours-table-container">
                <table id="drtr-tours-table" class="drtr-table">
                    <thead>
                        <tr>
                            <th><?php _e('Imagen', 'drtr-tours'); ?></th>
                            <th><?php _e('ID', 'drtr-tours'); ?></th>
                            <th><?php _e('Título', 'drtr-tours'); ?></th>
                            <th><?php _e('Precio', 'drtr-tours'); ?></th>
                            <th><?php _e('Duración', 'drtr-tours'); ?></th>
                            <th><?php _e('Ubicación', 'drtr-tours'); ?></th>
                            <th><?php _e('Fecha Inicio', 'drtr-tours'); ?></th>
                            <th><?php _e('Acciones', 'drtr-tours'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="drtr-tours-tbody">
                        <!-- Cargado con JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div id="drtr-pagination" class="drtr-pagination"></div>
            
            <!-- Mensajes -->
            <div id="drtr-message" class="drtr-message" style="display: none;"></div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Manejar redirecciones en la página de gestión
     */
    public function handle_management_page() {
        if (!is_page('gestione-tours')) {
            return;
        }
        
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Si hay parámetro new_tour o edit_tour, forzar la renderización
        if (isset($_GET['new_tour']) || isset($_GET['edit_tour'])) {
            // El shortcode se encargará de renderizar la página correcta
            return;
        }
    }
    
    /**
     * Crear página de gestión programáticamente
     */
    public function create_management_page() {
        $page_slug = 'gestione-tours';
        
        // Verificar si la página ya existe
        $page = get_page_by_path($page_slug);
        
        if (!$page) {
            $page_data = array(
                'post_title' => __('Gestione Tours', 'drtr-tours'),
                'post_content' => '[drtr_tours_manager]',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $page_slug,
            );
            
            wp_insert_post($page_data);
        }
    }
    
    /**
     * Renderizar interfaz de gestión (deprecated - ahora usa shortcode)
     */
    public function render_management_interface($content) {
        // El shortcode [drtr_tours_manager] se encarga de toda la renderización
        return $content;
    }
    
    /**
     * Renderizar página de creación de nuevo tour
     */
    private function render_new_tour_page() {
        ob_start();
        ?>
        <div id="drtr-new-tour-page" class="drtr-container drtr-edit-page">
            <!-- Back button -->
            <div class="drtr-back-link">
                <a href="<?php echo esc_url(get_permalink()); ?>" class="drtr-btn drtr-btn-secondary">
                    <span class="dashicons dashicons-arrow-left-alt2"></span>
                    <?php _e('Volver a la lista', 'drtr-tours'); ?>
                </a>
            </div>
            
            <!-- Header -->
            <div class="drtr-header">
                <h1><?php _e('Crear Nuevo Tour', 'drtr-tours'); ?></h1>
            </div>
            
            <!-- Formulario de creación -->
            <div class="drtr-edit-form-container">
                <form id="drtr-tour-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="drtr-tour-id" name="tour_id" value="">
                    
                    <div class="drtr-form-row">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-title"><?php _e('Título *', 'drtr-tours'); ?></label>
                            <input type="text" id="drtr-tour-title" name="title" required>
                        </div>
                    </div>
                    
                    <div class="drtr-form-row">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-image"><?php _e('Locandina (Imagen)', 'drtr-tours'); ?></label>
                            <input type="file" id="drtr-tour-image" name="tour_image" accept="image/*" class="drtr-file-input">
                            <input type="hidden" id="drtr-tour-image-id" name="image_id">
                            <div id="drtr-image-preview" class="drtr-image-preview" style="display:none;">
                                <img src="" alt="Preview">
                                <button type="button" class="drtr-remove-image" title="<?php esc_attr_e('Eliminar imagen', 'drtr-tours'); ?>">
                                    <span class="dashicons dashicons-no-alt"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="drtr-form-row">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-excerpt"><?php _e('Descripción Corta', 'drtr-tours'); ?></label>
                            <textarea id="drtr-tour-excerpt" name="excerpt" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="drtr-form-row">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-content"><?php _e('Descripción Completa', 'drtr-tours'); ?></label>
                            <textarea id="drtr-tour-content" name="content" rows="5"></textarea>
                        </div>
                    </div>
                    
                    <div class="drtr-form-row drtr-form-row-3">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-price"><?php _e('Precio (€) *', 'drtr-tours'); ?></label>
                            <input type="number" id="drtr-tour-price" name="price" step="0.01" min="0" required>
                        </div>
                        
                        <div class="drtr-form-group">
                            <label for="drtr-tour-duration"><?php _e('Duración (días) *', 'drtr-tours'); ?></label>
                            <input type="number" id="drtr-tour-duration" name="duration" min="1" required>
                        </div>
                        
                        <div class="drtr-form-group">
                            <label for="drtr-tour-location"><?php _e('Ubicación', 'drtr-tours'); ?></label>
                            <input type="text" id="drtr-tour-location" name="location">
                        </div>
                    </div>
                    
                    <div class="drtr-form-row drtr-form-row-2">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-start-date"><?php _e('Fecha y Hora de Inicio *', 'drtr-tours'); ?></label>
                            <input type="datetime-local" id="drtr-tour-start-date" name="start_date" required>
                        </div>
                        
                        <div class="drtr-form-group">
                            <label for="drtr-tour-end-date"><?php _e('Fecha y Hora de Fin *', 'drtr-tours'); ?></label>
                            <input type="datetime-local" id="drtr-tour-end-date" name="end_date" required>
                        </div>
                    </div>
                    
                    <div class="drtr-form-row drtr-form-row-2">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-transport"><?php _e('Tipo de Transporte', 'drtr-tours'); ?></label>
                            <select id="drtr-tour-transport" name="transport_type">
                                <option value=""><?php _e('Seleccionar...', 'drtr-tours'); ?></option>
                                <option value="bus"><?php _e('Bus', 'drtr-tours'); ?></option>
                                <option value="avion"><?php _e('Avión', 'drtr-tours'); ?></option>
                                <option value="tren"><?php _e('Tren', 'drtr-tours'); ?></option>
                                <option value="barco"><?php _e('Barco', 'drtr-tours'); ?></option>
                                <option value="mixto"><?php _e('Mixto', 'drtr-tours'); ?></option>
                            </select>
                        </div>
                        
                        <div class="drtr-form-group">
                            <label for="drtr-tour-max-people"><?php _e('Máximo de Personas', 'drtr-tours'); ?></label>
                            <input type="number" id="drtr-tour-max-people" name="max_people" min="1">
                        </div>
                    </div>
                    
                    <div class="drtr-form-row drtr-form-row-2">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-includes"><?php _e('Qué Incluye', 'drtr-tours'); ?></label>
                            <textarea id="drtr-tour-includes" name="includes" rows="4" placeholder="<?php esc_attr_e('Un elemento por línea', 'drtr-tours'); ?>"></textarea>
                        </div>
                        
                        <div class="drtr-form-group">
                            <label for="drtr-tour-not-includes"><?php _e('Qué NO Incluye', 'drtr-tours'); ?></label>
                            <textarea id="drtr-tour-not-includes" name="not_includes" rows="4" placeholder="<?php esc_attr_e('Un elemento por línea', 'drtr-tours'); ?>"></textarea>
                        </div>
                    </div>
                    
                    <!-- Itinerario -->
                    <div class="drtr-form-section">
                        <h3><?php _e('Itinerario', 'drtr-tours'); ?></h3>
                        <button type="button" id="drtr-add-itinerary-stop" class="drtr-btn drtr-btn-secondary">
                            <span class="dashicons dashicons-plus"></span>
                            <?php _e('Agregar Parada', 'drtr-tours'); ?>
                        </button>
                        <div id="drtr-itinerary-container" class="drtr-itinerary-container"></div>
                        <input type="hidden" id="drtr-tour-itinerary" name="itinerary">
                    </div>
                    
                    <!-- Rating -->
                    <div class="drtr-form-row">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-rating"><?php _e('Valoración (0-5)', 'drtr-tours'); ?></label>
                            <input type="number" id="drtr-tour-rating" name="rating" min="0" max="5" step="0.1">
                        </div>
                    </div>
                    
                    <!-- Travel Intents -->
                    <div class="drtr-form-section">
                        <h3><?php _e('Intenciones de Viaje', 'drtr-tours'); ?></h3>
                        <?php drtr_render_intents_multiselect(array()); ?>
                    </div>
                    
                    <div class="drtr-form-actions">
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="drtr-btn drtr-btn-secondary">
                            <?php _e('Cancelar', 'drtr-tours'); ?>
                        </a>
                        <button type="submit" class="drtr-btn drtr-btn-primary">
                            <span class="dashicons dashicons-yes"></span>
                            <?php _e('Crear Tour', 'drtr-tours'); ?>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Mensajes -->
            <div id="drtr-message" class="drtr-message" style="display: none;"></div>
        </div>

        <?php
        return ob_get_clean();
    }
    
    /**
     * Renderizar página de edición
     */
    private function render_edit_page($tour_id) {
        ob_start();
        ?>
        <div id="drtr-edit-tour-page" class="drtr-container drtr-edit-page">
            <!-- Back button -->
            <div class="drtr-back-link">
                <a href="<?php echo esc_url(get_permalink()); ?>" class="drtr-btn drtr-btn-secondary">
                    <span class="dashicons dashicons-arrow-left-alt2"></span>
                    <?php _e('Volver a la lista', 'drtr-tours'); ?>
                </a>
            </div>
            
            <!-- Header -->
            <div class="drtr-header">
                <h1><?php _e('Editar Tour', 'drtr-tours'); ?> #<?php echo esc_html($tour_id); ?></h1>
            </div>
            
            <!-- Formulario de edición -->
            <div class="drtr-edit-form-container">
                <form id="drtr-tour-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="drtr-tour-id" name="tour_id" value="<?php echo esc_attr($tour_id); ?>">
                    
                    <div class="drtr-form-row">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-title"><?php _e('Título *', 'drtr-tours'); ?></label>
                            <input type="text" id="drtr-tour-title" name="title" required>
                        </div>
                    </div>
                    
                    <div class="drtr-form-row">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-image"><?php _e('Locandina (Imagen)', 'drtr-tours'); ?></label>
                            <input type="file" id="drtr-tour-image" name="tour_image" accept="image/*" class="drtr-file-input">
                            <input type="hidden" id="drtr-tour-image-id" name="image_id">
                            <div id="drtr-image-preview" class="drtr-image-preview" style="display:none;">
                                <img src="" alt="Preview">
                                <button type="button" class="drtr-remove-image" title="<?php esc_attr_e('Eliminar imagen', 'drtr-tours'); ?>">
                                    <span class="dashicons dashicons-no-alt"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="drtr-form-row">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-excerpt"><?php _e('Descripción Corta', 'drtr-tours'); ?></label>
                            <textarea id="drtr-tour-excerpt" name="excerpt" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="drtr-form-row">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-content"><?php _e('Descripción Completa', 'drtr-tours'); ?></label>
                            <textarea id="drtr-tour-content" name="content" rows="5"></textarea>
                        </div>
                    </div>
                    
                    <div class="drtr-form-row drtr-form-row-3">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-price"><?php _e('Precio (€) *', 'drtr-tours'); ?></label>
                            <input type="number" id="drtr-tour-price" name="price" step="0.01" min="0" required>
                        </div>
                        
                        <div class="drtr-form-group">
                            <label for="drtr-tour-duration"><?php _e('Duración (días) *', 'drtr-tours'); ?></label>
                            <input type="number" id="drtr-tour-duration" name="duration" min="1" required>
                        </div>
                        
                        <div class="drtr-form-group">
                            <label for="drtr-tour-location"><?php _e('Ubicación', 'drtr-tours'); ?></label>
                            <input type="text" id="drtr-tour-location" name="location">
                        </div>
                    </div>
                    
                    <div class="drtr-form-row drtr-form-row-2">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-start-date"><?php _e('Fecha y Hora de Inicio *', 'drtr-tours'); ?></label>
                            <input type="datetime-local" id="drtr-tour-start-date" name="start_date" required>
                        </div>
                        
                        <div class="drtr-form-group">
                            <label for="drtr-tour-end-date"><?php _e('Fecha y Hora de Fin *', 'drtr-tours'); ?></label>
                            <input type="datetime-local" id="drtr-tour-end-date" name="end_date" required>
                        </div>
                    </div>
                    
                    <div class="drtr-form-row drtr-form-row-2">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-transport"><?php _e('Tipo de Transporte', 'drtr-tours'); ?></label>
                            <select id="drtr-tour-transport" name="transport_type">
                                <option value=""><?php _e('Seleccionar...', 'drtr-tours'); ?></option>
                                <option value="bus"><?php _e('Bus', 'drtr-tours'); ?></option>
                                <option value="avion"><?php _e('Avión', 'drtr-tours'); ?></option>
                                <option value="tren"><?php _e('Tren', 'drtr-tours'); ?></option>
                                <option value="barco"><?php _e('Barco', 'drtr-tours'); ?></option>
                                <option value="mixto"><?php _e('Mixto', 'drtr-tours'); ?></option>
                            </select>
                        </div>
                        
                        <div class="drtr-form-group">
                            <label for="drtr-tour-max-people"><?php _e('Máximo de Personas', 'drtr-tours'); ?></label>
                            <input type="number" id="drtr-tour-max-people" name="max_people" min="1">
                        </div>
                    </div>
                    
                    <div class="drtr-form-row drtr-form-row-2">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-includes"><?php _e('Qué Incluye', 'drtr-tours'); ?></label>
                            <textarea id="drtr-tour-includes" name="includes" rows="4" placeholder="<?php esc_attr_e('Un elemento por línea', 'drtr-tours'); ?>"></textarea>
                        </div>
                        
                        <div class="drtr-form-group">
                            <label for="drtr-tour-not-includes"><?php _e('Qué NO Incluye', 'drtr-tours'); ?></label>
                            <textarea id="drtr-tour-not-includes" name="not_includes" rows="4" placeholder="<?php esc_attr_e('Un elemento por línea', 'drtr-tours'); ?>"></textarea>
                        </div>
                    </div>
                    
                    <!-- Itinerario -->
                    <div class="drtr-form-section">
                        <h3><?php _e('Itinerario', 'drtr-tours'); ?></h3>
                        <button type="button" id="drtr-add-itinerary-stop" class="drtr-btn drtr-btn-secondary">
                            <span class="dashicons dashicons-plus"></span>
                            <?php _e('Agregar Parada', 'drtr-tours'); ?>
                        </button>
                        <div id="drtr-itinerary-container" class="drtr-itinerary-container"></div>
                        <input type="hidden" id="drtr-tour-itinerary" name="itinerary">
                    </div>
                    
                    <!-- Rating -->
                    <div class="drtr-form-row">
                        <div class="drtr-form-group">
                            <label for="drtr-tour-rating"><?php _e('Valoración (0-5)', 'drtr-tours'); ?></label>
                            <input type="number" id="drtr-tour-rating" name="rating" min="0" max="5" step="0.1">
                        </div>
                    </div>
                    
                    <!-- Travel Intents -->
                    <div class="drtr-form-section">
                        <h3><?php _e('Intenciones de Viaje', 'drtr-tours'); ?></h3>
                        <?php drtr_render_intents_multiselect(drtr_get_tour_intents($tour_id)); ?>
                    </div>
                    
                    <div class="drtr-form-actions">
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="drtr-btn drtr-btn-secondary">
                            <?php _e('Cancelar', 'drtr-tours'); ?>
                        </a>
                        <button type="submit" class="drtr-btn drtr-btn-primary">
                            <span class="dashicons dashicons-yes"></span>
                            <?php _e('Guardar Cambios', 'drtr-tours'); ?>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Mensajes -->
            <div id="drtr-message" class="drtr-message" style="display: none;"></div>
        </div>

        <?php
        return ob_get_clean();
    }
}
