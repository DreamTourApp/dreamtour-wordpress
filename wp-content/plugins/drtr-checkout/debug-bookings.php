<?php
/**
 * DEBUG PRENOTAZIONI
 * Pagina per debuggare le prenotazioni
 * 
 * URL: /wp-content/plugins/drtr-checkout/debug-bookings.php
 */

// Caricare WordPress
require_once('../../../wp-load.php');

// Verificare che sia admin
if (!current_user_can('manage_options')) {
    wp_die('Non hai i permessi per accedere a questa pagina.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Debug Prenotazioni</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #003284;
            border-bottom: 3px solid #1ba4ce;
            padding-bottom: 10px;
        }
        h2 {
            color: #082a5b;
            margin-top: 30px;
            background: #f0f9ff;
            padding: 10px 15px;
            border-left: 4px solid #1ba4ce;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #fff;
        }
        th {
            background: #003284;
            color: #fff;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .meta-table {
            font-size: 13px;
            background: #f9f9f9;
        }
        .meta-table th {
            background: #6c757d;
        }
        .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-deposit { background: #d1ecf1; color: #0c5460; }
        .status-paid { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-completed { background: #d4edda; color: #155724; }
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .alert-info {
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
            color: #0c5460;
        }
        .alert-warning {
            background: #fff3cd;
            border-left: 4px solid #856404;
            color: #856404;
        }
        .alert-success {
            background: #d4edda;
            border-left: 4px solid #155724;
            color: #155724;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: #003284;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
        }
        .btn:hover {
            background: #1ba4ce;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Debug Prenotazioni DreamTour</h1>
        
        <div class="alert alert-info">
            <strong>Informazioni:</strong> Questa pagina mostra tutte le prenotazioni nel database, inclusi metadati e query SQL.
        </div>

        <p>
            <a href="<?php echo home_url('/area-riservata'); ?>" class="btn">← Dashboard</a>
            <a href="<?php echo home_url('/gestione-prenotazioni'); ?>" class="btn">Gestione Prenotazioni</a>
            <a href="<?php echo admin_url('edit.php?post_type=drtr_booking'); ?>" class="btn">WP Admin Bookings</a>
        </p>

        <?php
        // Query 1: Tutte le prenotazioni (qualsiasi status)
        $args_all = array(
            'post_type' => 'drtr_booking',
            'posts_per_page' => -1,
            'post_status' => 'any', // IMPORTANTE: Include tutti gli status personalizzati
            'orderby' => 'ID',
            'order' => 'DESC'
        );
        
        $all_bookings = new WP_Query($args_all);
        
        echo '<h2>📊 Statistiche Database</h2>';
        echo '<p><strong>Totale prenotazioni trovate:</strong> ' . $all_bookings->found_posts . '</p>';
        
        // Debug query
        echo '<div class="alert alert-info">';
        echo '<strong>Debug Query Args:</strong><br>';
        echo '<pre>' . print_r($args_all, true) . '</pre>';
        echo '<strong>SQL Query:</strong><br>';
        echo '<pre>' . $all_bookings->request . '</pre>';
        echo '</div>';
        
        // Contare per status
        $status_counts = array();
        if ($all_bookings->have_posts()) {
            while ($all_bookings->have_posts()) {
                $all_bookings->the_post();
                $status = get_post_status();
                if (!isset($status_counts[$status])) {
                    $status_counts[$status] = 0;
                }
                $status_counts[$status]++;
            }
            wp_reset_postdata();
            
            echo '<ul>';
            foreach ($status_counts as $status => $count) {
                echo '<li><strong>' . $status . ':</strong> ' . $count . ' prenotazioni</li>';
            }
            echo '</ul>';
        } else {
            echo '<div class="alert alert-warning">Nessuna prenotazione trovata con WP_Query, ma la query SQL diretta ne ha trovate!</div>';
        }
        
        // Query 2: Mostrare tutte le prenotazioni con dettagli
        echo '<h2>📋 Elenco Completo Prenotazioni</h2>';
        
        // Ricreare la query per il secondo loop
        $all_bookings_2 = new WP_Query($args_all);
        
        if ($all_bookings_2->have_posts()) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Stato WP</th>';
            echo '<th>Titolo</th>';
            echo '<th>Data Creazione</th>';
            echo '<th>Tour ID</th>';
            echo '<th>User ID</th>';
            echo '<th>Email</th>';
            echo '<th>Totale</th>';
            echo '<th>Azioni</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            // Reset query
            while ($all_bookings_2->have_posts()) {
                $all_bookings_2->the_post();
                $booking_id = get_the_ID();
                $post_status = get_post_status();
                $post_date = get_the_date('Y-m-d H:i:s');
                
                // Meta
                $tour_id = get_post_meta($booking_id, '_booking_tour_id', true);
                $user_id = get_post_meta($booking_id, '_booking_user_id', true);
                $email = get_post_meta($booking_id, '_booking_email', true);
                $total = get_post_meta($booking_id, '_booking_total', true);
                
                $status_class = 'status-' . str_replace('booking_', '', $post_status);
                
                echo '<tr>';
                echo '<td><strong>#' . $booking_id . '</strong></td>';
                echo '<td><span class="status ' . $status_class . '">' . $post_status . '</span></td>';
                echo '<td>' . get_the_title() . '</td>';
                echo '<td>' . $post_date . '</td>';
                echo '<td>' . ($tour_id ? '#' . $tour_id : '-') . '</td>';
                echo '<td>' . ($user_id ? '#' . $user_id : '-') . '</td>';
                echo '<td>' . ($email ? $email : '-') . '</td>';
                echo '<td>€' . number_format($total, 2, ',', '.') . '</td>';
                echo '<td><a href="#meta-' . $booking_id . '">Vedi Meta</a></td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
            
            // Mostrare tutti i meta per ogni prenotazione
            echo '<h2>🔧 Metadati Dettagliati</h2>';
            
            // Ricreare la query per il terzo loop
            $all_bookings_3 = new WP_Query($args_all);
            
            while ($all_bookings_3->have_posts()) {
                $all_bookings_3->the_post();
                $booking_id = get_the_ID();
                
                echo '<h3 id="meta-' . $booking_id . '">Prenotazione #' . $booking_id . ' - ' . get_the_title() . '</h3>';
                
                $all_meta = get_post_meta($booking_id);
                
                if ($all_meta) {
                    echo '<table class="meta-table">';
                    echo '<thead><tr><th>Meta Key</th><th>Meta Value</th></tr></thead>';
                    echo '<tbody>';
                    foreach ($all_meta as $key => $values) {
                        foreach ($values as $value) {
                            echo '<tr>';
                            echo '<td><code>' . esc_html($key) . '</code></td>';
                            echo '<td>' . esc_html($value) . '</td>';
                            echo '</tr>';
                        }
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<p>Nessun metadato trovato.</p>';
                }
            }
            
            wp_reset_postdata();
            
        } else {
            echo '<div class="alert alert-warning">';
            echo '<strong>Nessuna prenotazione trovata!</strong><br>';
            echo 'Possibili cause:<br>';
            echo '- Il custom post type "drtr_booking" non è registrato<br>';
            echo '- Non sono state create prenotazioni<br>';
            echo '- Il plugin drtr-checkout non è attivo';
            echo '</div>';
        }
        
        // Query SQL diretta
        global $wpdb;
        
        echo '<h2>💾 Query SQL Diretta</h2>';
        
        $sql_results = $wpdb->get_results("
            SELECT p.ID, p.post_title, p.post_status, p.post_date, p.post_type
            FROM {$wpdb->posts} p
            WHERE p.post_type = 'drtr_booking'
            ORDER BY p.ID DESC
        ");
        
        echo '<p><strong>Query:</strong> <code>SELECT * FROM wp_posts WHERE post_type = \'drtr_booking\'</code></p>';
        echo '<p><strong>Risultati:</strong> ' . count($sql_results) . '</p>';
        
        if ($sql_results) {
            echo '<table>';
            echo '<thead><tr><th>ID</th><th>Title</th><th>Status</th><th>Date</th><th>Type</th></tr></thead>';
            echo '<tbody>';
            foreach ($sql_results as $row) {
                echo '<tr>';
                echo '<td>' . $row->ID . '</td>';
                echo '<td>' . $row->post_title . '</td>';
                echo '<td>' . $row->post_status . '</td>';
                echo '<td>' . $row->post_date . '</td>';
                echo '<td>' . $row->post_type . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
        
        // Verifica plugin attivi
        echo '<h2>🔌 Plugin Attivi</h2>';
        $active_plugins = get_option('active_plugins');
        echo '<ul>';
        foreach ($active_plugins as $plugin) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
            echo '<li>' . $plugin_data['Name'] . ' (v' . $plugin_data['Version'] . ')</li>';
        }
        echo '</ul>';
        
        // Test registrazione CPT
        echo '<h2>📝 Custom Post Type Registrato?</h2>';
        $post_types = get_post_types(array(), 'objects');
        if (isset($post_types['drtr_booking'])) {
            echo '<div class="alert alert-success">';
            echo '✅ Custom Post Type "drtr_booking" è registrato correttamente!';
            echo '</div>';
        } else {
            echo '<div class="alert alert-warning">';
            echo '❌ Custom Post Type "drtr_booking" NON è registrato!<br>';
            echo 'Verifica che il plugin drtr-checkout sia attivo.';
            echo '</div>';
        }
        ?>
        
        <h2>🔗 Link Utili</h2>
        <p>
            <strong>Pagine prenotazioni:</strong><br>
            - <a href="<?php echo home_url('/mie-prenotazioni'); ?>">/mie-prenotazioni</a> (utenti)<br>
            - <a href="<?php echo home_url('/gestione-prenotazioni'); ?>">/gestione-prenotazioni</a> (admin)<br>
            <br>
            <strong>WordPress Admin:</strong><br>
            - <a href="<?php echo admin_url('edit.php?post_type=drtr_booking'); ?>">Tutte le Prenotazioni</a><br>
            - <a href="<?php echo admin_url('plugins.php'); ?>">Plugin</a><br>
        </p>
        
        <p style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #e0e0e0; color: #6c757d;">
            <small>Debug Prenotazioni - DreamTour Viaggi - <?php echo date('Y-m-d H:i:s'); ?></small>
        </p>
    </div>
</body>
</html>
