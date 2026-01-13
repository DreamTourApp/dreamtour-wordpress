<?php
/**
 * Dashboard del área reservada con gestión de permisos
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_RA_Dashboard {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('drtr_reserved_area', array($this, 'render_reserved_area'));
    }
    
    /**
     * Renderizar el área reservada
     */
    public function render_reserved_area() {
        ob_start();
        
        if (!is_user_logged_in()) {
            $this->render_login_form();
        } else {
            $this->render_dashboard();
        }
        
        return ob_get_clean();
    }
    
    /**
     * Renderizar formulario de login
     */
    private function render_login_form() {
        $error_message = '';
        
        // Mostrar error si viene de una autenticación fallida no-AJAX
        if (isset($_GET['login']) && $_GET['login'] === 'failed') {
            $error_message = __('Credenziali non valide. Riprova.', 'drtr-reserved-area');
        }
        
        ?>
        <div class="drtr-ra-login-container">
            <div class="drtr-ra-login-box">
                <div class="drtr-ra-logo">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <h1><?php bloginfo('name'); ?></h1>
                    <?php endif; ?>
                </div>
                
                <h2 class="drtr-ra-title"><?php _e('Area Riservata', 'drtr-reserved-area'); ?></h2>
                <p class="drtr-ra-subtitle"><?php _e('Accedi con le tue credenziali', 'drtr-reserved-area'); ?></p>
                
                <?php if ($error_message) : ?>
                    <div class="drtr-ra-alert drtr-ra-alert-error">
                        <?php echo esc_html($error_message); ?>
                    </div>
                <?php endif; ?>
                
                <div id="drtr-ra-message"></div>
                
                <form id="drtr-ra-login-form" class="drtr-ra-form" method="post">
                    <div class="drtr-ra-form-group">
                        <label for="drtr-username">
                            <i class="dashicons dashicons-admin-users"></i>
                            <?php _e('Username o Email', 'drtr-reserved-area'); ?>
                        </label>
                        <input 
                            type="text" 
                            id="drtr-username" 
                            name="username" 
                            class="drtr-ra-input" 
                            placeholder="<?php esc_attr_e('Inserisci username o email', 'drtr-reserved-area'); ?>"
                            required
                        >
                    </div>
                    
                    <div class="drtr-ra-form-group">
                        <label for="drtr-password">
                            <i class="dashicons dashicons-lock"></i>
                            <?php _e('Password', 'drtr-reserved-area'); ?>
                        </label>
                        <input 
                            type="password" 
                            id="drtr-password" 
                            name="password" 
                            class="drtr-ra-input" 
                            placeholder="<?php esc_attr_e('Inserisci la tua password', 'drtr-reserved-area'); ?>"
                            required
                        >
                    </div>
                    
                    <div class="drtr-ra-form-group drtr-ra-remember">
                        <label class="drtr-ra-checkbox-label">
                            <input type="checkbox" name="remember" id="drtr-remember" value="1">
                            <?php _e('Ricordami', 'drtr-reserved-area'); ?>
                        </label>
                    </div>
                    
                    <?php wp_nonce_field('drtr_ra_nonce', 'drtr_ra_nonce'); ?>
                    
                    <button type="submit" class="drtr-ra-btn drtr-ra-btn-primary">
                        <?php _e('Accedi', 'drtr-reserved-area'); ?>
                        <i class="dashicons dashicons-arrow-right-alt2"></i>
                    </button>
                </form>
                
                <div class="drtr-ra-links">
                    <a href="<?php echo esc_url(wp_lostpassword_url()); ?>">
                        <?php _e('Password dimenticata?', 'drtr-reserved-area'); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Renderizar dashboard según rol del usuario
     */
    private function render_dashboard() {
        $current_user = wp_get_current_user();
        $is_admin = current_user_can('manage_options');
        
        ?>
        <div class="drtr-ra-dashboard">
            <div class="drtr-ra-dashboard-header">
                <div class="drtr-ra-welcome">
                    <h2><?php printf(__('Benvenuto, %s!', 'drtr-reserved-area'), esc_html($current_user->display_name)); ?></h2>
                    <p class="drtr-ra-user-role">
                        <?php 
                        if ($is_admin) {
                            _e('Amministratore', 'drtr-reserved-area');
                        } else {
                            _e('Utente', 'drtr-reserved-area');
                        }
                        ?>
                    </p>
                </div>
                
                <div class="drtr-ra-user-menu">
                    <a href="<?php echo esc_url(DRTR_RA_Auth::get_logout_url()); ?>" class="drtr-ra-btn drtr-ra-btn-secondary">
                        <i class="dashicons dashicons-exit"></i>
                        <?php _e('Esci', 'drtr-reserved-area'); ?>
                    </a>
                </div>
            </div>
            
            <div class="drtr-ra-dashboard-content">
                <div class="drtr-ra-cards-grid">
                    
                    <!-- Card Profilo - Tutti gli utenti -->
                    <div class="drtr-ra-card">
                        <div class="drtr-ra-card-icon">
                            <i class="dashicons dashicons-admin-users"></i>
                        </div>
                        <h3><?php _e('Il Mio Profilo', 'drtr-reserved-area'); ?></h3>
                        <p><?php _e('Visualizza e modifica le tue informazioni personali', 'drtr-reserved-area'); ?></p>
                        <a href="<?php echo esc_url(admin_url('profile.php')); ?>" class="drtr-ra-btn drtr-ra-btn-outline">
                            <?php _e('Vai al Profilo', 'drtr-reserved-area'); ?>
                        </a>
                    </div>
                    
                    <?php if ($is_admin) : ?>
                        
                        <!-- Card Gestione Tours - Solo Admin -->
                        <div class="drtr-ra-card drtr-ra-card-highlight">
                            <div class="drtr-ra-card-icon drtr-ra-icon-primary">
                                <i class="dashicons dashicons-palmtree"></i>
                            </div>
                            <h3><?php _e('Gestione Tours', 'drtr-reserved-area'); ?></h3>
                            <p><?php _e('Gestisci tutti i tour: aggiungi, modifica ed elimina', 'drtr-reserved-area'); ?></p>
                            <a href="<?php echo esc_url(home_url('/gestione-tours')); ?>" class="drtr-ra-btn drtr-ra-btn-primary">
                                <?php _e('Gestisci Tours', 'drtr-reserved-area'); ?>
                            </a>
                        </div>
                        
                        <!-- Card Dashboard WP - Solo Admin -->
                        <div class="drtr-ra-card">
                            <div class="drtr-ra-card-icon">
                                <i class="dashicons dashicons-dashboard"></i>
                            </div>
                            <h3><?php _e('Dashboard WordPress', 'drtr-reserved-area'); ?></h3>
                            <p><?php _e('Accedi al pannello di amministrazione completo', 'drtr-reserved-area'); ?></p>
                            <a href="<?php echo esc_url(admin_url()); ?>" class="drtr-ra-btn drtr-ra-btn-outline">
                                <?php _e('Apri Dashboard', 'drtr-reserved-area'); ?>
                            </a>
                        </div>
                        
                        <!-- Card Utenti - Solo Admin -->
                        <div class="drtr-ra-card">
                            <div class="drtr-ra-card-icon">
                                <i class="dashicons dashicons-groups"></i>
                            </div>
                            <h3><?php _e('Gestione Utenti', 'drtr-reserved-area'); ?></h3>
                            <p><?php _e('Gestisci gli utenti e i loro permessi', 'drtr-reserved-area'); ?></p>
                            <a href="<?php echo esc_url(admin_url('users.php')); ?>" class="drtr-ra-btn drtr-ra-btn-outline">
                                <?php _e('Gestisci Utenti', 'drtr-reserved-area'); ?>
                            </a>
                        </div>
                        
                        <!-- Card Impostazioni - Solo Admin -->
                        <div class="drtr-ra-card">
                            <div class="drtr-ra-card-icon">
                                <i class="dashicons dashicons-admin-settings"></i>
                            </div>
                            <h3><?php _e('Impostazioni', 'drtr-reserved-area'); ?></h3>
                            <p><?php _e('Configura le impostazioni del sito', 'drtr-reserved-area'); ?></p>
                            <a href="<?php echo esc_url(admin_url('options-general.php')); ?>" class="drtr-ra-btn drtr-ra-btn-outline">
                                <?php _e('Impostazioni', 'drtr-reserved-area'); ?>
                            </a>
                        </div>
                        
                    <?php else : ?>
                        
                        <!-- Card para usuarios no-admin -->
                        <div class="drtr-ra-card">
                            <div class="drtr-ra-card-icon">
                                <i class="dashicons dashicons-info"></i>
                            </div>
                            <h3><?php _e('Informazioni', 'drtr-reserved-area'); ?></h3>
                            <p><?php _e('Contatta l\'amministratore per ulteriori funzionalità', 'drtr-reserved-area'); ?></p>
                        </div>
                        
                    <?php endif; ?>
                    
                </div>
                
                <?php if ($is_admin) : ?>
                <div class="drtr-ra-stats">
                    <h3><?php _e('Statistiche Rapide', 'drtr-reserved-area'); ?></h3>
                    <div class="drtr-ra-stats-grid">
                        <div class="drtr-ra-stat-item">
                            <span class="drtr-ra-stat-number"><?php echo wp_count_posts('drtr_tour')->publish; ?></span>
                            <span class="drtr-ra-stat-label"><?php _e('Tours Pubblicati', 'drtr-reserved-area'); ?></span>
                        </div>
                        <div class="drtr-ra-stat-item">
                            <span class="drtr-ra-stat-number"><?php echo count_users()['total_users']; ?></span>
                            <span class="drtr-ra-stat-label"><?php _e('Utenti Totali', 'drtr-reserved-area'); ?></span>
                        </div>
                        <div class="drtr-ra-stat-item">
                            <span class="drtr-ra-stat-number"><?php echo wp_count_posts('page')->publish; ?></span>
                            <span class="drtr-ra-stat-label"><?php _e('Pagine', 'drtr-reserved-area'); ?></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
