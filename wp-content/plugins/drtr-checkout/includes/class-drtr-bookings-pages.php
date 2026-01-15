<?php
/**
 * Gestione pagine prenotazioni
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_Bookings_Pages {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('drtr_user_bookings', array($this, 'render_user_bookings_page'));
        add_shortcode('drtr_admin_bookings', array($this, 'render_admin_bookings_page'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }
    
    /**
     * Enqueue assets per le pagine prenotazioni
     */
    public function enqueue_assets() {
        if (is_page(array('mie-prenotazioni', 'gestione-prenotazioni'))) {
            // Usa gli stili del reserved area che sono già caricati
            // Oppure crea stili specifici se necessario
        }
    }
    
    /**
     * Creare pagina mie prenotazioni
     */
    public static function create_bookings_page() {
        $page = get_page_by_path('mie-prenotazioni');
        
        if (!$page) {
            $page_id = wp_insert_post(array(
                'post_title'   => __('Le Mie Prenotazioni', 'drtr-checkout'),
                'post_name'    => 'mie-prenotazioni',
                'post_content' => '[drtr_user_bookings]',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ));
            
            if (!is_wp_error($page_id)) {
                update_post_meta($page_id, '_drtr_checkout_page', '1');
            }
        }
    }
    
    /**
     * Creare pagina gestione prenotazioni admin
     */
    public static function create_admin_bookings_page() {
        $page = get_page_by_path('gestione-prenotazioni');
        
        if (!$page) {
            $page_id = wp_insert_post(array(
                'post_title'   => __('Gestione Prenotazioni', 'drtr-checkout'),
                'post_name'    => 'gestione-prenotazioni',
                'post_content' => '[drtr_admin_bookings]',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ));
            
            if (!is_wp_error($page_id)) {
                update_post_meta($page_id, '_drtr_checkout_page', '1');
            }
        }
    }
    
    /**
     * Renderizzare pagina prenotazioni utente (shortcode)
     */
    public function render_user_bookings_page() {
        // Verificare se l'utente è loggato
        if (!is_user_logged_in()) {
            return '<div class="drtr-ra-login-required">' . 
                   '<p>' . __('Devi effettuare il login per visualizzare le tue prenotazioni.', 'drtr-checkout') . '</p>' .
                   '<a href="' . esc_url(home_url('/area-riservata')) . '" class="drtr-ra-btn drtr-ra-btn-primary">' . __('Vai al Login', 'drtr-checkout') . '</a>' .
                   '</div>';
        }
        
        ob_start();
        ?>
        <div class="drtr-ra-bookings-page">
            <div class="drtr-ra-page-header">
                <h1><?php _e('Le Mie Prenotazioni', 'drtr-checkout'); ?></h1>
                <a href="<?php echo esc_url(home_url('/area-riservata')); ?>" class="drtr-ra-btn drtr-ra-btn-secondary">
                    <i class="dashicons dashicons-arrow-left-alt2"></i>
                    <?php _e('Torna al Dashboard', 'drtr-checkout'); ?>
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
                   '<p>' . __('Non hai i permessi per accedere a questa pagina.', 'drtr-checkout') . '</p>' .
                   '<a href="' . esc_url(home_url('/area-riservata')) . '" class="drtr-ra-btn drtr-ra-btn-primary">' . __('Torna al Dashboard', 'drtr-checkout') . '</a>' .
                   '</div>';
        }
        
        ob_start();
        ?>
        <div class="drtr-ra-bookings-page">
            <div class="drtr-ra-page-header">
                <h1><?php _e('Gestione Prenotazioni', 'drtr-checkout'); ?></h1>
                <a href="<?php echo esc_url(home_url('/area-riservata')); ?>" class="drtr-ra-btn drtr-ra-btn-secondary">
                    <i class="dashicons dashicons-arrow-left-alt2"></i>
                    <?php _e('Torna al Dashboard', 'drtr-checkout'); ?>
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
            echo '<p>' . __('Sistema di prenotazioni non disponibile.', 'drtr-checkout') . '</p>';
            return;
        }
        
        // Ottenere prenotazioni dell'utente
        $args = array(
            'post_type' => 'drtr_booking',
            'posts_per_page' => -1,
            'post_status' => 'any', // Includere tutti gli status personalizzati
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
            echo '<th>' . __('Numero', 'drtr-checkout') . '</th>';
            echo '<th>' . __('Tour', 'drtr-checkout') . '</th>';
            echo '<th>' . __('Data Prenotazione', 'drtr-checkout') . '</th>';
            echo '<th>' . __('Partecipanti', 'drtr-checkout') . '</th>';
            echo '<th>' . __('Totale', 'drtr-checkout') . '</th>';
            echo '<th>' . __('Stato', 'drtr-checkout') . '</th>';
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
                
                $tour_title = $tour_id ? get_the_title($tour_id) : __('Tour non disponibile', 'drtr-checkout');
                $tour_url = $tour_id ? get_permalink($tour_id) : '';
                
                // Status labels
                $status_labels = array(
                    'booking_pending' => __('In Attesa', 'drtr-checkout'),
                    'booking_deposit' => __('Acconto Pagato', 'drtr-checkout'),
                    'booking_paid' => __('Pagato', 'drtr-checkout'),
                    'booking_cancelled' => __('Cancellato', 'drtr-checkout'),
                    'booking_completed' => __('Completato', 'drtr-checkout'),
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
                echo '<td>';
                if ($tour_url) {
                    echo '<a href="' . esc_url($tour_url) . '" target="_blank">' . esc_html($tour_title) . ' <i class="dashicons dashicons-external" style="font-size: 14px; vertical-align: middle;"></i></a>';
                } else {
                    echo esc_html($tour_title);
                }
                echo '</td>';
                echo '<td>' . esc_html(date_i18n('d/m/Y H:i', strtotime($created_at))) . '</td>';
                echo '<td>' . sprintf(__('%d adulti, %d bambini', 'drtr-checkout'), $adults, $children) . '</td>';
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
            echo '<p>' . __('Non hai ancora effettuato nessuna prenotazione.', 'drtr-checkout') . '</p>';
            echo '<a href="' . esc_url(home_url('/tours')) . '" class="drtr-ra-btn drtr-ra-btn-primary">';
            echo __('Esplora i Nostri Tour', 'drtr-checkout');
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
            echo '<p>' . __('Sistema di prenotazioni non disponibile.', 'drtr-checkout') . '</p>';
            return;
        }
        
        // Ottenere filtri dalla richiesta
        $filter_name = isset($_GET['filter_name']) ? sanitize_text_field($_GET['filter_name']) : '';
        $filter_email = isset($_GET['filter_email']) ? sanitize_email($_GET['filter_email']) : '';
        $filter_phone = isset($_GET['filter_phone']) ? sanitize_text_field($_GET['filter_phone']) : '';
        $filter_tour = isset($_GET['filter_tour']) ? absint($_GET['filter_tour']) : 0;
        
        // Ottenere tutte le prenotazioni
        $args = array(
            'post_type' => 'drtr_booking',
            'posts_per_page' => -1,
            'post_status' => 'any', // Includere tutti gli status personalizzati
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        // Aggiungere meta query per filtri
        $meta_query = array('relation' => 'AND');
        
        if (!empty($filter_tour)) {
            $meta_query[] = array(
                'key' => '_booking_tour_id',
                'value' => $filter_tour,
                'compare' => '='
            );
        }
        
        if (!empty($filter_email)) {
            $meta_query[] = array(
                'key' => '_booking_email',
                'value' => $filter_email,
                'compare' => 'LIKE'
            );
        }
        
        if (!empty($filter_phone)) {
            $meta_query[] = array(
                'key' => '_booking_phone',
                'value' => $filter_phone,
                'compare' => 'LIKE'
            );
        }
        
        if (!empty($filter_name)) {
            $meta_query[] = array(
                'relation' => 'OR',
                array(
                    'key' => '_booking_first_name',
                    'value' => $filter_name,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => '_booking_last_name',
                    'value' => $filter_name,
                    'compare' => 'LIKE'
                )
            );
        }
        
        if (count($meta_query) > 1) {
            $args['meta_query'] = $meta_query;
        }
        
        $bookings_query = new WP_Query($args);
        
        // Renderizzare form filtri
        $this->render_bookings_filters($filter_name, $filter_email, $filter_phone, $filter_tour);
        
        if ($bookings_query->have_posts()) {
            echo '<div class="drtr-bookings-table-wrapper">';
            echo '<table class="drtr-bookings-table drtr-admin-bookings-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>' . __('ID', 'drtr-checkout') . '</th>';
            echo '<th>' . __('Cliente', 'drtr-checkout') . '</th>';
            echo '<th>' . __('Tour', 'drtr-checkout') . '</th>';
            echo '<th>' . __('Data', 'drtr-checkout') . '</th>';
            echo '<th>' . __('Pax', 'drtr-checkout') . '</th>';
            echo '<th>' . __('Totale', 'drtr-checkout') . '</th>';
            echo '<th>' . __('Tipo', 'drtr-checkout') . '</th>';
            echo '<th>' . __('Pagamento', 'drtr-checkout') . '</th>';
            echo '<th>';
            echo __('Stato', 'drtr-checkout');
            echo ' <span class="dashicons dashicons-info-outline" style="font-size: 16px; vertical-align: middle; cursor: help;" title="' . esc_attr(__('In Attesa: Prenotazione ricevuta, in attesa di pagamento | Acconto Pagato: Acconto ricevuto | Pagato: Pagamento completo ricevuto | Completato: Tour completato | Cancellato: Prenotazione annullata', 'drtr-checkout')) . '"></span>';
            echo '</th>';
            echo '<th>' . __('Azioni', 'drtr-checkout') . '</th>';
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
                $payment_type = get_post_meta($booking_id, '_booking_payment_type', true);
                $payment_method = get_post_meta($booking_id, '_booking_payment_method', true);
                $status = get_post_status();
                $created_at = get_post_meta($booking_id, '_booking_created_at', true);
                
                $tour_title = $tour_id ? get_the_title($tour_id) : __('Tour non disponibile', 'drtr-checkout');
                
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
                    'booking_pending' => __('In Attesa', 'drtr-checkout'),
                    'booking_deposit' => __('Acconto Pagato', 'drtr-checkout'),
                    'booking_paid' => __('Pagato', 'drtr-checkout'),
                    'booking_cancelled' => __('Cancellato', 'drtr-checkout'),
                    'booking_completed' => __('Completato', 'drtr-checkout'),
                );
                
                // Status descriptions
                $status_descriptions = array(
                    'booking_pending' => __('Prenotazione ricevuta, in attesa di ricevere il pagamento', 'drtr-checkout'),
                    'booking_deposit' => __('Acconto del 50% ricevuto, in attesa del saldo', 'drtr-checkout'),
                    'booking_paid' => __('Pagamento completo ricevuto, prenotazione confermata', 'drtr-checkout'),
                    'booking_cancelled' => __('Prenotazione annullata dal cliente o dall\'amministratore', 'drtr-checkout'),
                    'booking_completed' => __('Tour completato con successo', 'drtr-checkout'),
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
                echo '<td>' . sprintf(__('%d ad., %d bamb.', 'drtr-checkout'), $adults, $children) . '</td>';
                echo '<td>';
                echo '<strong>€' . number_format($total, 2, ',', '.') . '</strong><br>';
                echo '<small>Acconto: €' . number_format($deposit, 2, ',', '.') . '</small>';
                echo '</td>';
                echo '<td>';
                if ($payment_type === 'deposit') {
                    echo '<span class="drtr-badge drtr-badge-warning">';
                    echo '<i class="dashicons dashicons-minus" style="font-size: 14px; vertical-align: middle;"></i> ';
                    echo __('Acconto 50%', 'drtr-checkout');
                    echo '</span>';
                } else {
                    echo '<span class="drtr-badge drtr-badge-success">';
                    echo '<i class="dashicons dashicons-yes" style="font-size: 14px; vertical-align: middle;"></i> ';
                    echo __('Completo', 'drtr-checkout');
                    echo '</span>';
                }
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
                echo '<select class="drtr-status-select" data-booking-id="' . esc_attr($booking_id) . '" title="' . esc_attr(isset($status_descriptions[$status]) ? $status_descriptions[$status] : '') . '">';
                foreach ($status_labels as $status_key => $status_name) {
                    $tooltip = isset($status_descriptions[$status_key]) ? $status_descriptions[$status_key] : '';
                    echo '<option value="' . esc_attr($status_key) . '" ' . selected($status, $status_key, false) . ' title="' . esc_attr($tooltip) . '">';
                    echo esc_html($status_name);
                    echo '</option>';
                }
                echo '</select>';
                echo '</td>';
                echo '<td>';
                echo '<button class="drtr-ra-btn drtr-ra-btn-small drtr-update-status" data-booking-id="' . esc_attr($booking_id) . '">';
                echo '<i class="dashicons dashicons-update"></i> ' . __('Aggiorna', 'drtr-checkout');
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
            echo '<p>' . __('Nessuna prenotazione trovata.', 'drtr-checkout') . '</p>';
            echo '</div>';
        }
    }
    
    /**
     * Renderizzare form filtri
     */
    private function render_bookings_filters($filter_name, $filter_email, $filter_phone, $filter_tour) {
        // Ottenere tutti i tour con prenotazioni
        $tours_with_bookings = array();
        $all_bookings = new WP_Query(array(
            'post_type' => 'drtr_booking',
            'posts_per_page' => -1,
            'post_status' => 'any',
            'fields' => 'ids'
        ));
        
        if ($all_bookings->have_posts()) {
            foreach ($all_bookings->posts as $booking_id) {
                $tour_id = get_post_meta($booking_id, '_booking_tour_id', true);
                if ($tour_id && !isset($tours_with_bookings[$tour_id])) {
                    $tour_post = get_post($tour_id);
                    if ($tour_post) {
                        // Ottenere date del tour
                        $tour_dates = get_post_meta($tour_id, '_drtr_dates', true);
                        $date_label = '';
                        if (!empty($tour_dates) && is_array($tour_dates)) {
                            $first_date = reset($tour_dates);
                            if (!empty($first_date['start'])) {
                                $date_label = ' - ' . date_i18n('d/m/Y', strtotime($first_date['start']));
                            }
                        }
                        $tours_with_bookings[$tour_id] = $tour_post->post_title . $date_label;
                    }
                }
            }
        }
        wp_reset_postdata();
        
        echo '<div class="drtr-bookings-filters">';
        echo '<form method="get" action="" class="drtr-filters-form">';
        echo '<input type="hidden" name="page_id" value="' . get_the_ID() . '">';
        
        echo '<div class="drtr-filters-row">';
        
        // Filtro nome
        echo '<div class="drtr-filter-field">';
        echo '<label for="filter_name">' . __('Nome Cliente', 'drtr-checkout') . '</label>';
        echo '<input type="text" id="filter_name" name="filter_name" value="' . esc_attr($filter_name) . '" placeholder="' . esc_attr__('Cerca per nome...', 'drtr-checkout') . '">';
        echo '</div>';
        
        // Filtro email
        echo '<div class="drtr-filter-field">';
        echo '<label for="filter_email">' . __('Email', 'drtr-checkout') . '</label>';
        echo '<input type="text" id="filter_email" name="filter_email" value="' . esc_attr($filter_email) . '" placeholder="' . esc_attr__('Cerca per email...', 'drtr-checkout') . '">';
        echo '</div>';
        
        // Filtro telefono
        echo '<div class="drtr-filter-field">';
        echo '<label for="filter_phone">' . __('Telefono', 'drtr-checkout') . '</label>';
        echo '<input type="text" id="filter_phone" name="filter_phone" value="' . esc_attr($filter_phone) . '" placeholder="' . esc_attr__('Cerca per telefono...', 'drtr-checkout') . '">';
        echo '</div>';
        
        // Filtro tour
        echo '<div class="drtr-filter-field">';
        echo '<label for="filter_tour">' . __('Tour', 'drtr-checkout') . '</label>';
        echo '<select id="filter_tour" name="filter_tour">';
        echo '<option value="">' . __('Tutti i tour', 'drtr-checkout') . '</option>';
        foreach ($tours_with_bookings as $tour_id => $tour_title) {
            echo '<option value="' . esc_attr($tour_id) . '" ' . selected($filter_tour, $tour_id, false) . '>';
            echo esc_html($tour_title);
            echo '</option>';
        }
        echo '</select>';
        echo '</div>';
        
        echo '</div>'; // .drtr-filters-row
        
        // Pulsanti azione
        echo '<div class="drtr-filters-actions">';
        echo '<button type="submit" class="drtr-ra-btn drtr-ra-btn-primary">';
        echo '<i class="dashicons dashicons-search"></i> ' . __('Filtra', 'drtr-checkout');
        echo '</button>';
        
        echo '<a href="' . esc_url(get_permalink()) . '" class="drtr-ra-btn drtr-ra-btn-secondary">';
        echo '<i class="dashicons dashicons-update"></i> ' . __('Resetta', 'drtr-checkout');
        echo '</a>';
        echo '</div>';
        
        echo '</form>';
        echo '</div>';
    }
}
