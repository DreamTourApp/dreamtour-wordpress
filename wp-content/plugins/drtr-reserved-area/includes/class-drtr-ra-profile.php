<?php
/**
 * Gestione profilo utente con impostazioni e conformità GDPR
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_RA_Profile {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'maybe_create_profile_page'));
        add_shortcode('drtr_profile', array($this, 'render_profile_page'));
        
        // AJAX handlers
        add_action('wp_ajax_drtr_update_profile', array($this, 'ajax_update_profile'));
        add_action('wp_ajax_drtr_update_password', array($this, 'ajax_update_password'));
        add_action('wp_ajax_drtr_delete_account', array($this, 'ajax_delete_account'));
        add_action('wp_ajax_drtr_export_data', array($this, 'ajax_export_data'));
    }
    
    /**
     * Crea la pagina del profilo se non esiste
     */
    public function maybe_create_profile_page() {
        $page = get_page_by_path('profilo');
        
        if (!$page) {
            $page_id = wp_insert_post(array(
                'post_title'   => __('Il Mio Profilo', 'drtr-reserved-area'),
                'post_name'    => 'profilo',
                'post_content' => '[drtr_profile]',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ));
            
            if (!is_wp_error($page_id)) {
                update_post_meta($page_id, '_drtr_profile_page', '1');
            }
        }
    }
    
    /**
     * Renderizza la pagina del profilo
     */
    public function render_profile_page() {
        if (!is_user_logged_in()) {
            return '<div class="drtr-ra-login-required">
                <p>' . __('Devi effettuare il login per accedere a questa pagina.', 'drtr-reserved-area') . '</p>
                <a href="' . esc_url(home_url('/area-riservata')) . '" class="drtr-ra-btn drtr-ra-btn-primary">' . __('Vai al Login', 'drtr-reserved-area') . '</a>
            </div>';
        }
        
        ob_start();
        $this->render_profile_content();
        return ob_get_clean();
    }
    
    /**
     * Renderizza il contenuto della pagina profilo
     */
    private function render_profile_content() {
        $current_user = wp_get_current_user();
        $user_meta = get_user_meta($current_user->ID);
        
        // Preferenze utente
        $email_notifications = get_user_meta($current_user->ID, 'drtr_email_notifications', true);
        $newsletter = get_user_meta($current_user->ID, 'drtr_newsletter', true);
        
        if ($email_notifications === '') {
            $email_notifications = '1'; // Default abilitato
        }
        if ($newsletter === '') {
            $newsletter = '0'; // Default disabilitato
        }
        
        ?>
        <div class="drtr-ra-bookings-page">
            <div class="drtr-ra-page-header">
                <h1>
                    <i class="dashicons dashicons-admin-users"></i>
                    <?php _e('Il Mio Profilo', 'drtr-reserved-area'); ?>
                </h1>
                <a href="<?php echo esc_url(home_url('/area-riservata')); ?>" class="drtr-ra-btn drtr-ra-btn-outline">
                    <i class="dashicons dashicons-arrow-left-alt2"></i>
                    <?php _e('Torna alla Dashboard', 'drtr-reserved-area'); ?>
                </a>
            </div>
            
            <!-- Messaggi di successo/errore -->
            <div id="drtr-profile-message"></div>
            
            <!-- Sezione Informazioni Personali -->
            <div class="drtr-ra-bookings-section" style="margin-bottom: 2rem;">
                <div class="drtr-ra-section-header">
                    <h3>
                        <i class="dashicons dashicons-id-alt"></i>
                        <?php _e('Informazioni Personali', 'drtr-reserved-area'); ?>
                    </h3>
                </div>
                
                <form id="drtr-profile-form" class="drtr-profile-form">
                    <div class="drtr-profile-grid">
                        <div class="drtr-profile-field">
                            <label for="first_name">
                                <?php _e('Nome', 'drtr-reserved-area'); ?> *
                            </label>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                class="drtr-ra-input"
                                value="<?php echo esc_attr($current_user->first_name); ?>"
                                required
                            >
                        </div>
                        
                        <div class="drtr-profile-field">
                            <label for="last_name">
                                <?php _e('Cognome', 'drtr-reserved-area'); ?> *
                            </label>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                class="drtr-ra-input"
                                value="<?php echo esc_attr($current_user->last_name); ?>"
                                required
                            >
                        </div>
                        
                        <div class="drtr-profile-field">
                            <label for="user_email">
                                <?php _e('Email', 'drtr-reserved-area'); ?> *
                            </label>
                            <input 
                                type="email" 
                                id="user_email" 
                                name="user_email" 
                                class="drtr-ra-input"
                                value="<?php echo esc_attr($current_user->user_email); ?>"
                                required
                            >
                        </div>
                        
                        <div class="drtr-profile-field">
                            <label for="phone">
                                <?php _e('Telefono', 'drtr-reserved-area'); ?>
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                class="drtr-ra-input"
                                value="<?php echo esc_attr(get_user_meta($current_user->ID, 'phone', true)); ?>"
                            >
                        </div>
                    </div>
                    
                    <?php wp_nonce_field('drtr_update_profile', 'drtr_profile_nonce'); ?>
                    
                    <button type="submit" class="drtr-ra-btn drtr-ra-btn-primary">
                        <i class="dashicons dashicons-saved"></i>
                        <?php _e('Salva Modifiche', 'drtr-reserved-area'); ?>
                    </button>
                </form>
            </div>
            
            <!-- Sezione Sicurezza -->
            <div class="drtr-ra-bookings-section" style="margin-bottom: 2rem;">
                <div class="drtr-ra-section-header">
                    <h3>
                        <i class="dashicons dashicons-lock"></i>
                        <?php _e('Sicurezza', 'drtr-reserved-area'); ?>
                    </h3>
                </div>
                
                <form id="drtr-password-form" class="drtr-profile-form">
                    <div class="drtr-profile-grid">
                        <div class="drtr-profile-field">
                            <label for="current_password">
                                <?php _e('Password Attuale', 'drtr-reserved-area'); ?> *
                            </label>
                            <input 
                                type="password" 
                                id="current_password" 
                                name="current_password" 
                                class="drtr-ra-input"
                                required
                            >
                        </div>
                        
                        <div class="drtr-profile-field">
                            <label for="new_password">
                                <?php _e('Nuova Password', 'drtr-reserved-area'); ?> *
                            </label>
                            <input 
                                type="password" 
                                id="new_password" 
                                name="new_password" 
                                class="drtr-ra-input"
                                minlength="8"
                                required
                            >
                            <small class="drtr-field-hint">
                                <?php _e('Minimo 8 caratteri', 'drtr-reserved-area'); ?>
                            </small>
                        </div>
                        
                        <div class="drtr-profile-field">
                            <label for="confirm_password">
                                <?php _e('Conferma Nuova Password', 'drtr-reserved-area'); ?> *
                            </label>
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                class="drtr-ra-input"
                                minlength="8"
                                required
                            >
                        </div>
                    </div>
                    
                    <?php wp_nonce_field('drtr_update_password', 'drtr_password_nonce'); ?>
                    
                    <button type="submit" class="drtr-ra-btn drtr-ra-btn-primary">
                        <i class="dashicons dashicons-shield-alt"></i>
                        <?php _e('Cambia Password', 'drtr-reserved-area'); ?>
                    </button>
                </form>
            </div>
            
            <!-- Sezione Preferenze -->
            <div class="drtr-ra-bookings-section" style="margin-bottom: 2rem;">
                <div class="drtr-ra-section-header">
                    <h3>
                        <i class="dashicons dashicons-admin-settings"></i>
                        <?php _e('Preferenze', 'drtr-reserved-area'); ?>
                    </h3>
                </div>
                
                <form id="drtr-preferences-form" class="drtr-profile-form">
                    <div class="drtr-preferences-list">
                        <div class="drtr-preference-item">
                            <label class="drtr-preference-label">
                                <input 
                                    type="checkbox" 
                                    name="email_notifications" 
                                    id="email_notifications"
                                    value="1"
                                    <?php checked($email_notifications, '1'); ?>
                                >
                                <div class="drtr-preference-content">
                                    <strong><?php _e('Notifiche Email', 'drtr-reserved-area'); ?></strong>
                                    <p><?php _e('Ricevi email di conferma e aggiornamenti sulle tue prenotazioni', 'drtr-reserved-area'); ?></p>
                                </div>
                            </label>
                        </div>
                        
                        <div class="drtr-preference-item">
                            <label class="drtr-preference-label">
                                <input 
                                    type="checkbox" 
                                    name="newsletter" 
                                    id="newsletter"
                                    value="1"
                                    <?php checked($newsletter, '1'); ?>
                                >
                                <div class="drtr-preference-content">
                                    <strong><?php _e('Newsletter', 'drtr-reserved-area'); ?></strong>
                                    <p><?php _e('Ricevi offerte esclusive e novità sui nostri tour', 'drtr-reserved-area'); ?></p>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <?php wp_nonce_field('drtr_update_profile', 'drtr_preferences_nonce'); ?>
                    
                    <button type="submit" class="drtr-ra-btn drtr-ra-btn-primary">
                        <i class="dashicons dashicons-saved"></i>
                        <?php _e('Salva Preferenze', 'drtr-reserved-area'); ?>
                    </button>
                </form>
            </div>
            
            <!-- Sezione GDPR -->
            <div class="drtr-ra-bookings-section drtr-gdpr-section">
                <div class="drtr-ra-section-header">
                    <h3>
                        <i class="dashicons dashicons-privacy"></i>
                        <?php _e('Privacy e Dati Personali', 'drtr-reserved-area'); ?>
                    </h3>
                </div>
                
                <div class="drtr-gdpr-content">
                    <div class="drtr-gdpr-info">
                        <p>
                            <?php _e('In conformità con il GDPR (Regolamento Generale sulla Protezione dei Dati), hai il diritto di accedere, esportare ed eliminare i tuoi dati personali.', 'drtr-reserved-area'); ?>
                        </p>
                    </div>
                    
                    <div class="drtr-gdpr-actions">
                        <button type="button" id="drtr-export-data" class="drtr-ra-btn drtr-ra-btn-secondary">
                            <i class="dashicons dashicons-download"></i>
                            <?php _e('Esporta i Miei Dati', 'drtr-reserved-area'); ?>
                        </button>
                        
                        <button type="button" id="drtr-delete-account" class="drtr-ra-btn drtr-ra-btn-danger">
                            <i class="dashicons dashicons-trash"></i>
                            <?php _e('Elimina il Mio Account', 'drtr-reserved-area'); ?>
                        </button>
                    </div>
                    
                    <div class="drtr-gdpr-warning">
                        <i class="dashicons dashicons-warning"></i>
                        <p>
                            <?php _e('Attenzione: L\'eliminazione del tuo account è permanente e irreversibile. Tutti i tuoi dati, incluse le prenotazioni, verranno eliminati.', 'drtr-reserved-area'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Conferma Eliminazione Account -->
        <div id="drtr-delete-modal" class="drtr-modal" style="display: none;">
            <div class="drtr-modal-overlay"></div>
            <div class="drtr-modal-content">
                <div class="drtr-modal-header">
                    <h3>
                        <i class="dashicons dashicons-warning"></i>
                        <?php _e('Conferma Eliminazione Account', 'drtr-reserved-area'); ?>
                    </h3>
                    <button type="button" class="drtr-modal-close">
                        <i class="dashicons dashicons-no-alt"></i>
                    </button>
                </div>
                <div class="drtr-modal-body">
                    <p>
                        <?php _e('Sei sicuro di voler eliminare il tuo account? Questa azione è irreversibile.', 'drtr-reserved-area'); ?>
                    </p>
                    <p>
                        <strong><?php _e('Verranno eliminati:', 'drtr-reserved-area'); ?></strong>
                    </p>
                    <ul>
                        <li><?php _e('Tutti i tuoi dati personali', 'drtr-reserved-area'); ?></li>
                        <li><?php _e('Lo storico delle tue prenotazioni', 'drtr-reserved-area'); ?></li>
                        <li><?php _e('Le tue preferenze e impostazioni', 'drtr-reserved-area'); ?></li>
                    </ul>
                    
                    <form id="drtr-confirm-delete-form">
                        <div class="drtr-profile-field" style="margin-top: 1.5rem;">
                            <label for="delete_password">
                                <?php _e('Inserisci la tua password per confermare:', 'drtr-reserved-area'); ?>
                            </label>
                            <input 
                                type="password" 
                                id="delete_password" 
                                name="delete_password" 
                                class="drtr-ra-input"
                                required
                            >
                        </div>
                        
                        <?php wp_nonce_field('drtr_delete_account', 'drtr_delete_nonce'); ?>
                        
                        <div class="drtr-modal-actions">
                            <button type="button" class="drtr-ra-btn drtr-ra-btn-outline drtr-modal-close">
                                <?php _e('Annulla', 'drtr-reserved-area'); ?>
                            </button>
                            <button type="submit" class="drtr-ra-btn drtr-ra-btn-danger">
                                <i class="dashicons dashicons-trash"></i>
                                <?php _e('Elimina Definitivamente', 'drtr-reserved-area'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * AJAX: Aggiorna profilo utente
     */
    public function ajax_update_profile() {
        check_ajax_referer('drtr_update_profile', 'drtr_profile_nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('Devi effettuare il login.', 'drtr-reserved-area')));
        }
        
        $user_id = get_current_user_id();
        
        // Sanitize input
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $user_email = sanitize_email($_POST['user_email']);
        $phone = sanitize_text_field($_POST['phone']);
        
        // Validate email
        if (!is_email($user_email)) {
            wp_send_json_error(array('message' => __('Email non valida.', 'drtr-reserved-area')));
        }
        
        // Check if email is already used by another user
        $email_exists = email_exists($user_email);
        if ($email_exists && $email_exists != $user_id) {
            wp_send_json_error(array('message' => __('Questa email è già utilizzata da un altro account.', 'drtr-reserved-area')));
        }
        
        // Update user data
        $user_data = array(
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'user_email' => $user_email,
            'display_name' => $first_name . ' ' . $last_name,
        );
        
        $result = wp_update_user($user_data);
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        // Update phone
        update_user_meta($user_id, 'phone', $phone);
        
        // Update preferences if included
        if (isset($_POST['email_notifications'])) {
            update_user_meta($user_id, 'drtr_email_notifications', sanitize_text_field($_POST['email_notifications']));
        } else {
            update_user_meta($user_id, 'drtr_email_notifications', '0');
        }
        
        if (isset($_POST['newsletter'])) {
            update_user_meta($user_id, 'drtr_newsletter', sanitize_text_field($_POST['newsletter']));
        } else {
            update_user_meta($user_id, 'drtr_newsletter', '0');
        }
        
        wp_send_json_success(array('message' => __('Profilo aggiornato con successo!', 'drtr-reserved-area')));
    }
    
    /**
     * AJAX: Aggiorna password
     */
    public function ajax_update_password() {
        check_ajax_referer('drtr_update_password', 'drtr_password_nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('Devi effettuare il login.', 'drtr-reserved-area')));
        }
        
        $user = wp_get_current_user();
        
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Verify current password
        if (!wp_check_password($current_password, $user->user_pass, $user->ID)) {
            wp_send_json_error(array('message' => __('La password attuale non è corretta.', 'drtr-reserved-area')));
        }
        
        // Check if passwords match
        if ($new_password !== $confirm_password) {
            wp_send_json_error(array('message' => __('Le nuove password non coincidono.', 'drtr-reserved-area')));
        }
        
        // Check password length
        if (strlen($new_password) < 8) {
            wp_send_json_error(array('message' => __('La password deve essere di almeno 8 caratteri.', 'drtr-reserved-area')));
        }
        
        // Update password
        wp_set_password($new_password, $user->ID);
        
        // Re-authenticate user
        wp_set_auth_cookie($user->ID);
        
        wp_send_json_success(array('message' => __('Password modificata con successo!', 'drtr-reserved-area')));
    }
    
    /**
     * AJAX: Esporta dati utente (GDPR)
     */
    public function ajax_export_data() {
        check_ajax_referer('drtr_ra_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('Devi effettuare il login.', 'drtr-reserved-area')));
        }
        
        $user_id = get_current_user_id();
        $user = get_userdata($user_id);
        
        // Collect user data
        $export_data = array(
            'user_info' => array(
                'ID' => $user->ID,
                'username' => $user->user_login,
                'email' => $user->user_email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'display_name' => $user->display_name,
                'registered' => $user->user_registered,
            ),
            'user_meta' => array(
                'phone' => get_user_meta($user_id, 'phone', true),
                'email_notifications' => get_user_meta($user_id, 'drtr_email_notifications', true),
                'newsletter' => get_user_meta($user_id, 'drtr_newsletter', true),
            ),
            'bookings' => array(),
        );
        
        // Get user bookings if drtr-checkout plugin is active
        global $wpdb;
        $bookings = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}drtr_bookings WHERE user_id = %d ORDER BY created_at DESC",
            $user_id
        ), ARRAY_A);
        
        if ($bookings) {
            $export_data['bookings'] = $bookings;
        }
        
        // Create filename with timestamp
        $filename = 'dreamtour_data_' . $user->user_login . '_' . date('Y-m-d') . '.json';
        
        // Send JSON file
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo json_encode($export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * AJAX: Elimina account (GDPR)
     */
    public function ajax_delete_account() {
        check_ajax_referer('drtr_delete_account', 'drtr_delete_nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('Devi effettuare il login.', 'drtr-reserved-area')));
        }
        
        $user = wp_get_current_user();
        $password = $_POST['delete_password'];
        
        // Verify password
        if (!wp_check_password($password, $user->user_pass, $user->ID)) {
            wp_send_json_error(array('message' => __('Password non corretta.', 'drtr-reserved-area')));
        }
        
        // Don't allow admins to delete their own account
        if (user_can($user->ID, 'manage_options')) {
            wp_send_json_error(array('message' => __('Gli amministratori non possono eliminare il proprio account da qui.', 'drtr-reserved-area')));
        }
        
        // Delete user bookings if table exists
        global $wpdb;
        $table_name = $wpdb->prefix . 'drtr_bookings';
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
            $wpdb->delete($table_name, array('user_id' => $user->ID), array('%d'));
        }
        
        // Delete user
        require_once(ABSPATH . 'wp-admin/includes/user.php');
        $result = wp_delete_user($user->ID);
        
        if (!$result) {
            wp_send_json_error(array('message' => __('Errore durante l\'eliminazione dell\'account. Riprova.', 'drtr-reserved-area')));
        }
        
        // Logout user
        wp_logout();
        
        wp_send_json_success(array(
            'message' => __('Account eliminato con successo. Verrai reindirizzato alla home page.', 'drtr-reserved-area'),
            'redirect' => home_url()
        ));
    }
}
