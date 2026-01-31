<?php
/**
 * Gestione pagina di registrazione
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_RA_Register {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'maybe_create_register_page'));
        add_shortcode('drtr_register', array($this, 'render_register_page'));
        
        // AJAX handlers
        add_action('wp_ajax_nopriv_drtr_register_user', array($this, 'ajax_register_user'));
    }
    
    /**
     * Crea la pagina di registrazione se non esiste
     */
    public function maybe_create_register_page() {
        $page = get_page_by_path('registrati');
        
        if (!$page) {
            $page_id = wp_insert_post(array(
                'post_title'   => __('Registrati', 'drtr-reserved-area'),
                'post_name'    => 'registrati',
                'post_content' => '[drtr_register]',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ));
            
            if (!is_wp_error($page_id)) {
                update_post_meta($page_id, '_drtr_register_page', '1');
            }
        }
    }
    
    /**
     * Renderizza la pagina di registrazione
     */
    public function render_register_page() {
        if (is_user_logged_in()) {
            return '<div class="drtr-ra-login-required">
                <p>' . __('Sei già registrato e loggato.', 'drtr-reserved-area') . '</p>
                <a href="' . esc_url(home_url('/area-riservata')) . '" class="drtr-ra-btn drtr-ra-btn-primary">' . __('Vai alla Dashboard', 'drtr-reserved-area') . '</a>
            </div>';
        }
        
        ob_start();
        $this->render_register_content();
        return ob_get_clean();
    }
    
    /**
     * Renderizza il contenuto della pagina registrazione
     */
    private function render_register_content() {
        ?>
        <div class="drtr-ra-login-container">
            <div class="drtr-ra-login-box">
                <div class="drtr-ra-logo">
                    <img src="<?php echo esc_url(DRTR_RA_PLUGIN_URL . 'assets/images/logo.png'); ?>" alt="<?php esc_attr_e('Logo', 'drtr-reserved-area'); ?>">  
                </div>
                
                <h2 class="drtr-ra-title"><?php _e('Crea il Tuo Account', 'drtr-reserved-area'); ?></h2>
                <p class="drtr-ra-subtitle"><?php _e('Registrati per gestire le tue prenotazioni', 'drtr-reserved-area'); ?></p>
                
                <div id="drtr-register-message"></div>
                
                <form id="drtr-register-form" class="drtr-ra-form">
                    <div class="drtr-ra-form-group">
                        <label for="register-first-name">
                            <i class="dashicons dashicons-admin-users"></i>
                            <?php _e('Nome *', 'drtr-reserved-area'); ?>
                        </label>
                        <input 
                            type="text" 
                            id="register-first-name" 
                            name="first_name" 
                            class="drtr-ra-input" 
                            placeholder="<?php esc_attr_e('Inserisci il tuo nome', 'drtr-reserved-area'); ?>"
                            required
                        >
                    </div>
                    
                    <div class="drtr-ra-form-group">
                        <label for="register-last-name">
                            <i class="dashicons dashicons-admin-users"></i>
                            <?php _e('Cognome *', 'drtr-reserved-area'); ?>
                        </label>
                        <input 
                            type="text" 
                            id="register-last-name" 
                            name="last_name" 
                            class="drtr-ra-input" 
                            placeholder="<?php esc_attr_e('Inserisci il tuo cognome', 'drtr-reserved-area'); ?>"
                            required
                        >
                    </div>
                    
                    <div class="drtr-ra-form-group">
                        <label for="register-email">
                            <i class="dashicons dashicons-email"></i>
                            <?php _e('Email *', 'drtr-reserved-area'); ?>
                        </label>
                        <input 
                            type="email" 
                            id="register-email" 
                            name="email" 
                            class="drtr-ra-input" 
                            placeholder="<?php esc_attr_e('Inserisci la tua email', 'drtr-reserved-area'); ?>"
                            required
                        >
                    </div>
                    
                    <div class="drtr-ra-form-group">
                        <label for="register-phone">
                            <i class="dashicons dashicons-phone"></i>
                            <?php _e('Telefono', 'drtr-reserved-area'); ?>
                        </label>
                        <input 
                            type="tel" 
                            id="register-phone" 
                            name="phone" 
                            class="drtr-ra-input" 
                            placeholder="<?php esc_attr_e('Inserisci il tuo telefono', 'drtr-reserved-area'); ?>"
                        >
                    </div>
                    
                    <div class="drtr-ra-form-group">
                        <label for="register-password">
                            <i class="dashicons dashicons-lock"></i>
                            <?php _e('Password *', 'drtr-reserved-area'); ?>
                        </label>
                        <div class="drtr-ra-password-wrapper">
                            <input 
                                type="password" 
                                id="register-password" 
                                name="password" 
                                class="drtr-ra-input drtr-ra-password-input" 
                                placeholder="<?php esc_attr_e('Minimo 8 caratteri', 'drtr-reserved-area'); ?>"
                                minlength="8"
                                required
                            >
                            <button type="button" class="drtr-ra-toggle-password" aria-label="<?php esc_attr_e('Mostra password', 'drtr-reserved-area'); ?>">
                                <i class="dashicons dashicons-visibility"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="drtr-ra-form-group">
                        <label for="register-password-confirm">
                            <i class="dashicons dashicons-lock"></i>
                            <?php _e('Conferma Password *', 'drtr-reserved-area'); ?>
                        </label>
                        <div class="drtr-ra-password-wrapper">
                            <input 
                                type="password" 
                                id="register-password-confirm" 
                                name="password_confirm" 
                                class="drtr-ra-input drtr-ra-password-input" 
                                placeholder="<?php esc_attr_e('Ripeti la password', 'drtr-reserved-area'); ?>"
                                minlength="8"
                                required
                            >
                            <button type="button" class="drtr-ra-toggle-password" aria-label="<?php esc_attr_e('Mostra password', 'drtr-reserved-area'); ?>">
                                <i class="dashicons dashicons-visibility"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="drtr-ra-form-group drtr-ra-remember">
                        <label class="drtr-ra-checkbox-label">
                            <input type="checkbox" name="newsletter" id="register-newsletter" value="1">
                            <?php _e('Voglio ricevere offerte e novità via email', 'drtr-reserved-area'); ?>
                        </label>
                    </div>
                    
                    <div class="drtr-ra-form-group drtr-ra-remember">
                        <label class="drtr-ra-checkbox-label">
                            <input type="checkbox" name="terms" id="register-terms" value="1" required>
                            <?php _e('Accetto i', 'drtr-reserved-area'); ?> 
                            <a href="/termini-condizioni" target="_blank"><?php _e('Termini e Condizioni', 'drtr-reserved-area'); ?></a> 
                            <?php _e('e la', 'drtr-reserved-area'); ?> 
                            <a href="/privacy-policy" target="_blank"><?php _e('Privacy Policy', 'drtr-reserved-area'); ?></a>
                        </label>
                    </div>
                    
                    <?php wp_nonce_field('drtr_register_nonce', 'drtr_register_nonce'); ?>
                    
                    <button type="submit" class="drtr-ra-btn drtr-ra-btn-primary">
                        <?php _e('Registrati', 'drtr-reserved-area'); ?>
                        <i class="dashicons dashicons-arrow-right-alt2"></i>
                    </button>
                </form>
                
                <div class="drtr-ra-links">
                    <a href="<?php echo esc_url(home_url('/area-riservata')); ?>">
                        <?php _e('Hai già un account? Accedi', 'drtr-reserved-area'); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * AJAX: Registra utente
     */
    public function ajax_register_user() {
        check_ajax_referer('drtr_register_nonce', 'drtr_register_nonce');
        
        // Sanitize input
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        $newsletter = !empty($_POST['newsletter']) ? '1' : '0';
        
        // Validate
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
            wp_send_json_error(array('message' => __('Tutti i campi obbligatori devono essere compilati.', 'drtr-reserved-area')));
        }
        
        if (!is_email($email)) {
            wp_send_json_error(array('message' => __('Email non valida.', 'drtr-reserved-area')));
        }
        
        if (strlen($password) < 8) {
            wp_send_json_error(array('message' => __('La password deve essere di almeno 8 caratteri.', 'drtr-reserved-area')));
        }
        
        if ($password !== $password_confirm) {
            wp_send_json_error(array('message' => __('Le password non coincidono.', 'drtr-reserved-area')));
        }
        
        // Verificare se l'email esiste già
        if (email_exists($email)) {
            wp_send_json_error(array('message' => __('Esiste già un account con questa email.', 'drtr-reserved-area')));
        }
        
        // Creare username dall'email
        $username = sanitize_user(current(explode('@', $email)));
        
        // Se username esiste già, aggiungere un numero
        if (username_exists($username)) {
            $username = $username . wp_rand(100, 999);
        }
        
        // Creare utente
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            wp_send_json_error(array('message' => $user_id->get_error_message()));
        }
        
        // Aggiornare dati utente
        wp_update_user(array(
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'display_name' => $first_name . ' ' . $last_name,
        ));
        
        // Salvare metadati
        if (!empty($phone)) {
            update_user_meta($user_id, 'phone', $phone);
        }
        update_user_meta($user_id, 'drtr_email_notifications', '1');
        update_user_meta($user_id, 'drtr_newsletter', $newsletter);
        
        // Auto-login
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        
        wp_send_json_success(array(
            'message' => __('Registrazione completata! Verrai reindirizzato...', 'drtr-reserved-area'),
            'redirect' => home_url('/area-riservata')
        ));
    }
}
