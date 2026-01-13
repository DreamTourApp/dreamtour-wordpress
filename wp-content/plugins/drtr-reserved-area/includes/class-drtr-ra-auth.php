<?php
/**
 * Sistema de autenticación personalizado
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_RA_Auth {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_ajax_nopriv_drtr_ra_login', array($this, 'ajax_login'));
        add_action('wp_ajax_drtr_ra_logout', array($this, 'ajax_logout'));
        add_action('init', array($this, 'handle_logout'));
    }
    
    /**
     * Manejar login AJAX
     */
    public function ajax_login() {
        check_ajax_referer('drtr_ra_nonce', 'nonce');
        
        $username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $remember = isset($_POST['remember']) ? true : false;
        
        if (empty($username) || empty($password)) {
            wp_send_json_error(array(
                'message' => __('Username e password sono obbligatori.', 'drtr-reserved-area')
            ));
        }
        
        $credentials = array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => $remember,
        );
        
        $user = wp_signon($credentials, is_ssl());
        
        if (is_wp_error($user)) {
            wp_send_json_error(array(
                'message' => __('Credenziali non valide. Riprova.', 'drtr-reserved-area')
            ));
        }
        
        wp_send_json_success(array(
            'message' => __('Login effettuato con successo!', 'drtr-reserved-area'),
            'redirect' => get_permalink(get_page_by_path('area-riservata'))
        ));
    }
    
    /**
     * Manejar logout
     */
    public function handle_logout() {
        if (isset($_GET['drtr_logout']) && $_GET['drtr_logout'] === '1') {
            if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'drtr_logout')) {
                wp_logout();
                wp_safe_redirect(get_permalink(get_page_by_path('area-riservata')));
                exit;
            }
        }
    }
    
    /**
     * Obtener URL de logout
     */
    public static function get_logout_url() {
        $page_url = get_permalink(get_page_by_path('area-riservata'));
        return wp_nonce_url(add_query_arg('drtr_logout', '1', $page_url), 'drtr_logout');
    }
}
