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
        add_shortcode('drtr_reserved_area', array($this, 'render_reserved_area'));        add_shortcode('drtr_user_bookings', array($this, 'render_user_bookings_page'));
        add_shortcode('drtr_admin_bookings', array($this, 'render_admin_bookings_page'));    }
    
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
                    
                    <!-- Card Le Mie Prenotazioni - Tutti gli utenti -->
                    <div class="drtr-ra-card">
                        <div class="drtr-ra-card-icon">
                            <i class="dashicons dashicons-calendar-alt"></i>
                        </div>
                        <h3><?php _e('Le Mie Prenotazioni', 'drtr-reserved-area'); ?></h3>
                        <p><?php _e('Visualizza lo storico delle tue prenotazioni', 'drtr-reserved-area'); ?></p>
                        <a href="#" class="drtr-ra-btn drtr-ra-btn-outline drtr-show-bookings">
                            <?php _e('Vedi Prenotazioni', 'drtr-reserved-area'); ?>
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
    
    /**
     * Renderizzare pagina prenotazioni utente (shortcode)
     */
    public function render_user_bookings_page() {
        // Verificare se l'utente è loggato
        if (!is_user_logged_in()) {
            return '<div class="drtr-ra-login-required">' . 
                   '<p>' . __('Devi effettuare il login per visualizzare le tue prenotazioni.', 'drtr-reserved-area') . '</p>' .
                   '<a href="' . esc_url(home_url('/area-riservata')) . '" class="drtr-ra-btn drtr-ra-btn-primary">' . __('Vai al Login', 'drtr-reserved-area') . '</a>' .
                   '</div>';
        }
        
        ob_start();
        ?>
        <div class="drtr-ra-bookings-page">
            <div class="drtr-ra-page-header">
                <h1><?php _e('Le Mie Prenotazioni', 'drtr-reserved-area'); ?></h1>
                <a href="<?php echo esc_url(home_url('/area-riservata')); ?>" class="drtr-ra-btn drtr-ra-btn-secondary">
                    <i class="dashicons dashicons-arrow-left-alt2"></i>
                    <?php _e('Torna al Dashboard', 'drtr-reserved-area'); ?>
                </a>
            </div>
            <?php $this->render_user_bookings(get_current_user_id()); ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Renderizzare pagina gestione prenotazioni admin (shortcode)
     */
    public function render_admin_bookings_page() {
        // Verificare se l'utente è admin
        if (!current_user_can('manage_options')) {
            return '<div class="drtr-ra-login-required">' . 
                   '<p>' . __('Non hai i permessi per accedere a questa pagina.', 'drtr-reserved-area') . '</p>' .
                   '<a href="' . esc_url(home_url('/area-riservata')) . '" class="drtr-ra-btn drtr-ra-btn-primary">' . __('Torna al Dashboard', 'drtr-reserved-area') . '</a>' .
                   '</div>';
        }
        
        ob_start();
        ?>
        <div class="drtr-ra-bookings-page">
            <div class="drtr-ra-page-header">
                <h1><?php _e('Gestione Prenotazioni', 'drtr-reserved-area'); ?></h1>
                <a href="<?php echo esc_url(home_url('/area-riservata')); ?>" class="drtr-ra-btn drtr-ra-btn-secondary">
                    <i class="dashicons dashicons-arrow-left-alt2"></i>
                    <?php _e('Torna al Dashboard', 'drtr-reserved-area'); ?>
                </a>
            </div>
            <?php $this->render_admin_bookings(); ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Renderizzare le prenotazioni dell'utente
     */
    private function render_user_bookings($user_id) {
        // Verificare se esiste la classe DRTR_Booking
        if (!class_exists('DRTR_Booking')) {
            echo '<p>' . __('Sistema di prenotazioni non disponibile.', 'drtr-reserved-area') . '</p>';
            return;
        }
        
        // Ottenere prenotazioni dell'utente
        $args = array(
            'post_type' => 'drtr_booking',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_booking_user_id',
                    'value' => $user_id,
                    'compare' => '='
                )
            ),
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        $bookings_query = new WP_Query($args);
        
        if ($bookings_query->have_posts()) {
            echo '<div class="drtr-bookings-table-wrapper">';
            echo '<table class="drtr-bookings-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>' . __('Numero', 'drtr-reserved-area') . '</th>';
            echo '<th>' . __('Tour', 'drtr-reserved-area') . '</th>';
            echo '<th>' . __('Data Prenotazione', 'drtr-reserved-area') . '</th>';
            echo '<th>' . __('Partecipanti', 'drtr-reserved-area') . '</th>';
            echo '<th>' . __('Totale', 'drtr-reserved-area') . '</th>';
            echo '<th>' . __('Stato', 'drtr-reserved-area') . '</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            while ($bookings_query->have_posts()) {
                $bookings_query->the_post();
                $booking_id = get_the_ID();
                
                $tour_id = get_post_meta($booking_id, '_booking_tour_id', true);
                $adults = get_post_meta($booking_id, '_booking_adults', true);
                $children = get_post_meta($booking_id, '_booking_children', true);
                $total = get_post_meta($booking_id, '_booking_total', true);
                $status = get_post_status();
                $created_at = get_post_meta($booking_id, '_booking_created_at', true);
                
                $tour_title = $tour_id ? get_the_title($tour_id) : __('Tour non disponibile', 'drtr-reserved-area');
                
                // Status labels
                $status_labels = array(
                    'booking_pending' => __('In Attesa', 'drtr-reserved-area'),
                    'booking_deposit' => __('Acconto Pagato', 'drtr-reserved-area'),
                    'booking_paid' => __('Pagato', 'drtr-reserved-area'),
                    'booking_cancelled' => __('Cancellato', 'drtr-reserved-area'),
                    'booking_completed' => __('Completato', 'drtr-reserved-area'),
                );
                
                $status_class = array(
                    'booking_pending' => 'pending',
                    'booking_deposit' => 'deposit',
                    'booking_paid' => 'paid',
                    'booking_cancelled' => 'cancelled',
                    'booking_completed' => 'completed',
                );
                
                $status_label = isset($status_labels[$status]) ? $status_labels[$status] : $status;
                $status_css = isset($status_class[$status]) ? $status_class[$status] : 'pending';
                
                echo '<tr>';
                echo '<td><strong>#' . esc_html($booking_id) . '</strong></td>';
                echo '<td>' . esc_html($tour_title) . '</td>';
                echo '<td>' . esc_html(date_i18n('d/m/Y H:i', strtotime($created_at))) . '</td>';
                echo '<td>' . sprintf(__('%d adulti, %d bambini', 'drtr-reserved-area'), $adults, $children) . '</td>';
                echo '<td><strong>€' . number_format($total, 2, ',', '.') . '</strong></td>';
                echo '<td><span class="drtr-booking-status drtr-status-' . esc_attr($status_css) . '">' . esc_html($status_label) . '</span></td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            
            wp_reset_postdata();
        } else {
            echo '<div class="drtr-no-bookings">';
            echo '<i class="dashicons dashicons-info"></i>';
            echo '<p>' . __('Non hai ancora effettuato nessuna prenotazione.', 'drtr-reserved-area') . '</p>';
            echo '<a href="' . esc_url(home_url('/tours')) . '" class="drtr-ra-btn drtr-ra-btn-primary">';
            echo __('Esplora i Nostri Tour', 'drtr-reserved-area');
            echo '</a>';
            echo '</div>';
        }
    }
    
    /**
     * Renderizzare tutte le prenotazioni per admin
     */
    private function render_admin_bookings() {
        // Verificare se esiste la classe DRTR_Booking
        if (!class_exists('DRTR_Booking')) {
            echo '<p>' . __('Sistema di prenotazioni non disponibile.', 'drtr-reserved-area') . '</p>';
            return;
        }
        
        // Ottenere tutte le prenotazioni
        $args = array(
            'post_type' => 'drtr_booking',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        $bookings_query = new WP_Query($args);
        
        if ($bookings_query->have_posts()) {
            echo '<div class="drtr-bookings-table-wrapper">';
            echo '<table class="drtr-bookings-table drtr-admin-bookings-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>' . __('ID', 'drtr-reserved-area') . '</th>';
            echo '<th>' . __('Cliente', 'drtr-reserved-area') . '</th>';
            echo '<th>' . __('Tour', 'drtr-reserved-area') . '</th>';
            echo '<th>' . __('Data', 'drtr-reserved-area') . '</th>';
            echo '<th>' . __('Pax', 'drtr-reserved-area') . '</th>';
            echo '<th>' . __('Totale', 'drtr-reserved-area') . '</th>';
            echo '<th>' . __('Pagamento', 'drtr-reserved-area') . '</th>';
            echo '<th>' . __('Stato', 'drtr-reserved-area') . '</th>';
            echo '<th>' . __('Azioni', 'drtr-reserved-area') . '</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            while ($bookings_query->have_posts()) {
                $bookings_query->the_post();
                $booking_id = get_the_ID();
                
                $tour_id = get_post_meta($booking_id, '_booking_tour_id', true);
                $user_id = get_post_meta($booking_id, '_booking_user_id', true);
                $adults = get_post_meta($booking_id, '_booking_adults', true);
                $children = get_post_meta($booking_id, '_booking_children', true);
                $email = get_post_meta($booking_id, '_booking_email', true);
                $phone = get_post_meta($booking_id, '_booking_phone', true);
                $total = get_post_meta($booking_id, '_booking_total', true);
                $deposit = get_post_meta($booking_id, '_booking_deposit', true);
                $payment_method = get_post_meta($booking_id, '_booking_payment_method', true);
                $status = get_post_status();
                $created_at = get_post_meta($booking_id, '_booking_created_at', true);
                
                $tour_title = $tour_id ? get_the_title($tour_id) : __('Tour non disponibile', 'drtr-reserved-area');
                
                // Nome cliente
                $customer_name = '';
                if ($user_id) {
                    $user = get_userdata($user_id);
                    $customer_name = $user ? $user->display_name : $email;
                } else {
                    $customer_name = $email;
                }
                
                // Status labels
                $status_labels = array(
                    'booking_pending' => __('In Attesa', 'drtr-reserved-area'),
                    'booking_deposit' => __('Acconto Pagato', 'drtr-reserved-area'),
                    'booking_paid' => __('Pagato', 'drtr-reserved-area'),
                    'booking_cancelled' => __('Cancellato', 'drtr-reserved-area'),
                    'booking_completed' => __('Completato', 'drtr-reserved-area'),
                );
                
                $status_class = array(
                    'booking_pending' => 'pending',
                    'booking_deposit' => 'deposit',
                    'booking_paid' => 'paid',
                    'booking_cancelled' => 'cancelled',
                    'booking_completed' => 'completed',
                );
                
                $status_label = isset($status_labels[$status]) ? $status_labels[$status] : $status;
                $status_css = isset($status_class[$status]) ? $status_class[$status] : 'pending';
                
                echo '<tr data-booking-id="' . esc_attr($booking_id) . '">';
                echo '<td><strong>#' . esc_html($booking_id) . '</strong></td>';
                echo '<td>';
                echo '<div class="drtr-customer-info">';
                echo '<strong>' . esc_html($customer_name) . '</strong><br>';
                echo '<small>' . esc_html($email) . '</small><br>';
                if ($phone) {
                    echo '<small><i class="dashicons dashicons-phone"></i> ' . esc_html($phone) . '</small>';
                }
                echo '</div>';
                echo '</td>';
                echo '<td>' . esc_html($tour_title) . '</td>';
                echo '<td>' . esc_html(date_i18n('d/m/Y H:i', strtotime($created_at))) . '</td>';
                echo '<td>' . sprintf(__('%d ad., %d bamb.', 'drtr-reserved-area'), $adults, $children) . '</td>';
                echo '<td>';
                echo '<strong>€' . number_format($total, 2, ',', '.') . '</strong><br>';
                echo '<small>Acconto: €' . number_format($deposit, 2, ',', '.') . '</small>';
                echo '</td>';
                echo '<td>';
                $payment_icons = array(
                    'bank_transfer' => 'dashicons-bank',
                    'credit_card' => 'dashicons-credit-card'
                );
                $icon = isset($payment_icons[$payment_method]) ? $payment_icons[$payment_method] : 'dashicons-money';
                echo '<i class="dashicons ' . esc_attr($icon) . '"></i> ';
                echo esc_html(ucfirst(str_replace('_', ' ', $payment_method)));
                echo '</td>';
                echo '<td>';
                echo '<select class="drtr-status-select" data-booking-id="' . esc_attr($booking_id) . '">';
                foreach ($status_labels as $status_key => $status_name) {
                    echo '<option value="' . esc_attr($status_key) . '" ' . selected($status, $status_key, false) . '>';
                    echo esc_html($status_name);
                    echo '</option>';
                }
                echo '</select>';
                echo '</td>';
                echo '<td>';
                echo '<button class="drtr-ra-btn drtr-ra-btn-small drtr-update-status" data-booking-id="' . esc_attr($booking_id) . '">';
                echo '<i class="dashicons dashicons-update"></i> ' . __('Aggiorna', 'drtr-reserved-area');
                echo '</button>';
                echo '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            
            wp_reset_postdata();
        } else {
            echo '<div class="drtr-no-bookings">';
            echo '<i class="dashicons dashicons-info"></i>';
            echo '<p>' . __('Nessuna prenotazione trovata.', 'drtr-reserved-area') . '</p>';
            echo '</div>';
        }
    }
}
