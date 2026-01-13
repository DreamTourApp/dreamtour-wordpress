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
        add_filter('the_content', array($this, 'render_management_interface'));
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
     * Renderizar interfaz de gestión
     */
    public function render_management_interface($content) {
        if (!is_page('gestione-tours')) {
            return $content;
        }
        
        // Verificar que solo administradores puedan ver
        if (!current_user_can('manage_options')) {
            return '<div class="drtr-error"><p>' . __('No tienes permisos para acceder a esta página.', 'drtr-tours') . '</p></div>';
        }
        
        ob_start();
        ?>
        <div id="drtr-tours-manager" class="drtr-container">
            <!-- Header -->
            <div class="drtr-header">
                <h1><?php _e('Gestión de Tours', 'drtr-tours'); ?></h1>
                <button id="drtr-add-tour-btn" class="drtr-btn drtr-btn-primary">
                    <span class="dashicons dashicons-plus"></span>
                    <?php _e('Añadir Nuevo Tour', 'drtr-tours'); ?>
                </button>
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
                        <tr>
                            <td colspan="9" class="drtr-loading">
                                <span class="spinner is-active"></span>
                                <?php _e('Cargando tours...', 'drtr-tours'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div id="drtr-pagination" class="drtr-pagination"></div>
            
            <!-- Modal para crear/editar tour -->
            <div id="drtr-tour-modal" class="drtr-modal" style="display: none;">
                <div class="drtr-modal-content">
                    <span class="drtr-modal-close">&times;</span>
                    <h2 id="drtr-modal-title"><?php _e('Añadir Tour', 'drtr-tours'); ?></h2>
                    
                    <form id="drtr-tour-form">
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
                        
                        <div class="drtr-form-row drtr-form-row-2">
                            <div class="drtr-form-group">
                                <label for="drtr-tour-price"><?php _e('Precio (€)', 'drtr-tours'); ?></label>
                                <input type="number" id="drtr-tour-price" name="price" step="0.01">
                            </div>
                            
                            <div class="drtr-form-group">
                                <label for="drtr-tour-duration"><?php _e('Duración (días)', 'drtr-tours'); ?></label>
                                <input type="number" id="drtr-tour-duration" name="duration">
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
                                <input type="number" id="drtr-tour-max-people" name="max_people">
                            </div>
                        </div>
                        
                        <div class="drtr-form-row drtr-form-row-2">
                            <div class="drtr-form-group">
                                <label for="drtr-tour-start-date"><?php _e('Fecha y Hora Inicio', 'drtr-tours'); ?></label>
                                <input type="datetime-local" id="drtr-tour-start-date" name="start_date">
                            </div>
                            
                            <div class="drtr-form-group">
                                <label for="drtr-tour-end-date"><?php _e('Fecha y Hora Fin', 'drtr-tours'); ?></label>
                                <input type="datetime-local" id="drtr-tour-end-date" name="end_date">
                            </div>
                        </div>
                        
                        <div class="drtr-form-row">
                            <div class="drtr-form-group">
                                <label for="drtr-tour-location"><?php _e('Ubicación Principal', 'drtr-tours'); ?></label>
                                <input type="text" id="drtr-tour-location" name="location">
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
                        <div class="drtr-form-row">
                            <div class="drtr-form-group">
                                <label><?php _e('Itinerario del Tour', 'drtr-tours'); ?></label>
                                <div id="drtr-itinerary-container" class="drtr-itinerary-container"></div>
                                <button type="button" id="drtr-add-itinerary-stop" class="drtr-btn drtr-btn-secondary">
                                    <span class="dashicons dashicons-plus"></span>
                                    <?php _e('Agregar Parada', 'drtr-tours'); ?>
                                </button>
                                <input type="hidden" id="drtr-tour-itinerary" name="itinerary">
                            </div>
                        </div>
                        
                        <div class="drtr-modal-actions">
                            <button type="button" class="drtr-btn drtr-btn-secondary drtr-modal-cancel"><?php _e('Cancelar', 'drtr-tours'); ?></button>
                            <button type="submit" class="drtr-btn drtr-btn-primary"><?php _e('Guardar Tour', 'drtr-tours'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Mensajes -->
            <div id="drtr-message" class="drtr-message" style="display: none;"></div>
        </div>
        <?php
        return ob_get_clean();
    }
}
