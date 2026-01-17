<?php
/**
 * Debug page for testing ticket generation
 */

// Load WordPress
require_once __DIR__ . '/wp-load.php';

// Only allow admin access
if (!current_user_can('manage_options')) {
    die('Accesso negato. Solo amministratori possono accedere a questa pagina.');
}

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Generazione Biglietti - DreamTour</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .header {
            background: linear-gradient(135deg, #003284 0%, #1ba4ce 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .section {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .test-result {
            margin: 15px 0;
            padding: 15px;
            border-left: 4px solid #1ba4ce;
            background: #f0f8ff;
        }
        .success {
            border-left-color: #28a745;
            background: #d4edda;
        }
        .error {
            border-left-color: #dc3545;
            background: #f8d7da;
        }
        .qr-preview {
            display: inline-block;
            padding: 20px;
            background: white;
            border: 2px solid #1ba4ce;
            border-radius: 8px;
            margin: 10px;
        }
        .qr-preview img {
            display: block;
            max-width: 250px;
            height: auto;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 13px;
        }
        pre {
            background: #282c34;
            color: #abb2bf;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 13px;
        }
        .log-entry {
            margin: 5px 0;
            padding: 8px;
            background: #f9f9f9;
            border-left: 3px solid #1ba4ce;
            font-family: monospace;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #003284;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
        }
        .btn:hover {
            background: #1ba4ce;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #f8f9fa;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>🎫 Debug Generazione Biglietti</h1>
    <p>Testa la generazione di QR code e biglietti per le prenotazioni DreamTour</p>
</div>

<?php

echo '<div class="section">';
echo '<h2>📋 Test 1: Verifica Classi e Funzioni</h2>';

$checks = [
    'DRTR_Biglietto_QR class exists' => class_exists('DRTR_Biglietto_QR'),
    'DRTR_Biglietto_Email class exists' => class_exists('DRTR_Biglietto_Email'),
    'DRTR_Biglietto_PDF class exists' => class_exists('DRTR_Biglietto_PDF'),
    'wp_remote_get function' => function_exists('wp_remote_get'),
    'Upload directory writable' => is_writable(wp_upload_dir()['basedir'])
];

foreach ($checks as $check => $result) {
    $class = $result ? 'success' : 'error';
    $icon = $result ? '✅' : '❌';
    echo "<div class='test-result $class'>$icon <strong>$check:</strong> " . ($result ? 'OK' : 'FAILED') . "</div>";
}

echo '</div>';

// Test 2: Generate QR Code
echo '<div class="section">';
echo '<h2>🔍 Test 2: Generazione QR Code</h2>';

$test_booking_id = 999;
$test_seat = '15';

echo "<p><strong>Test booking ID:</strong> $test_booking_id</p>";
echo "<p><strong>Test seat number:</strong> $test_seat</p>";

if (class_exists('DRTR_Biglietto_QR')) {
    try {
        echo '<div class="log-entry">🔄 Inizio generazione QR code...</div>';
        
        $qr_result = DRTR_Biglietto_QR::generate_qr_code($test_booking_id, $test_seat);
        
        echo '<div class="test-result success">✅ QR code generato con successo!</div>';
        
        echo '<h3>Risultato:</h3>';
        
        // Check if it's a base64 data URI
        if (strpos($qr_result, 'data:image/png;base64,') === 0) {
            echo '<div class="test-result success">✅ Formato: Base64 Data URI (corretto per email)</div>';
            echo '<p><strong>Lunghezza base64:</strong> ' . strlen($qr_result) . ' caratteri</p>';
            
            echo '<div class="qr-preview">';
            echo '<h4>Anteprima QR Code:</h4>';
            echo '<img src="' . esc_attr($qr_result) . '" alt="QR Code Test">';
            echo '<p style="text-align: center; margin-top: 10px; font-size: 12px; color: #666;">Posto ' . $test_seat . '</p>';
            echo '</div>';
            
            // Show first 200 chars of base64
            echo '<p><strong>Inizio stringa base64:</strong></p>';
            echo '<pre>' . substr($qr_result, 0, 200) . '...</pre>';
            
        } else {
            echo '<div class="test-result error">⚠️ Formato: URL esterno (potrebbe non funzionare nelle email)</div>';
            echo '<p><strong>URL:</strong> <code>' . esc_html($qr_result) . '</code></p>';
            
            echo '<div class="qr-preview">';
            echo '<h4>Anteprima QR Code:</h4>';
            echo '<img src="' . esc_url($qr_result) . '" alt="QR Code Test">';
            echo '</div>';
        }
        
    } catch (Exception $e) {
        echo '<div class="test-result error">❌ Errore: ' . esc_html($e->getMessage()) . '</div>';
    }
} else {
    echo '<div class="test-result error">❌ Classe DRTR_Biglietto_QR non disponibile</div>';
}

echo '</div>';

// Test 3: Check latest bookings
echo '<div class="section">';
echo '<h2>📦 Test 3: Ultime Prenotazioni</h2>';

$bookings_query = new WP_Query([
    'post_type' => 'drtr_booking',
    'posts_per_page' => 5,
    'post_status' => 'any',
    'orderby' => 'date',
    'order' => 'DESC'
]);

if ($bookings_query->have_posts()) {
    echo '<table>';
    echo '<thead><tr>';
    echo '<th>ID</th><th>Tour</th><th>Email</th><th>Posti</th><th>Test</th>';
    echo '</tr></thead><tbody>';
    
    while ($bookings_query->have_posts()) {
        $bookings_query->the_post();
        $booking_id = get_the_ID();
        $tour_id = get_post_meta($booking_id, '_booking_tour_id', true);
        $email = get_post_meta($booking_id, '_booking_email', true);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'drtr_posti';
        $seats_count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE booking_id = %d",
            $booking_id
        ));
        
        echo '<tr>';
        echo '<td><strong>#' . $booking_id . '</strong></td>';
        echo '<td>' . get_the_title($tour_id) . '</td>';
        echo '<td>' . esc_html($email) . '</td>';
        echo '<td>' . $seats_count . ' posti</td>';
        echo '<td><a href="?test_booking=' . $booking_id . '" class="btn">Testa Biglietti</a></td>';
        echo '</tr>';
    }
    
    echo '</tbody></table>';
    wp_reset_postdata();
} else {
    echo '<div class="test-result">Nessuna prenotazione trovata nel database.</div>';
}

echo '</div>';

// Test specific booking
if (isset($_GET['test_booking'])) {
    $test_id = intval($_GET['test_booking']);
    
    echo '<div class="section">';
    echo '<h2>🎯 Test 4: Biglietti per Prenotazione #' . $test_id . '</h2>';
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'drtr_posti';
    
    $seats = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE booking_id = %d ORDER BY seat_number ASC",
        $test_id
    ), ARRAY_A);
    
    if (!empty($seats)) {
        echo '<p><strong>' . count($seats) . ' posti trovati:</strong></p>';
        
        echo '<div style="display: flex; flex-wrap: wrap; gap: 20px; margin: 20px 0;">';
        
        foreach ($seats as $seat) {
            $qr_code = DRTR_Biglietto_QR::generate_qr_code($test_id, $seat['seat_number']);
            
            echo '<div class="qr-preview">';
            echo '<h4>Posto ' . esc_html($seat['seat_number']) . '</h4>';
            echo '<p><strong>' . esc_html($seat['passenger_name']) . '</strong></p>';
            echo '<img src="' . esc_attr($qr_code) . '" alt="QR Code">';
            echo '<p style="font-size: 11px; color: #666; margin-top: 10px;">ID: ' . $test_id . '</p>';
            echo '</div>';
        }
        
        echo '</div>';
        
        // Preview email HTML
        echo '<h3>Anteprima HTML Email:</h3>';
        echo '<div style="border: 2px dashed #1ba4ce; padding: 20px; background: #f9f9f9;">';
        
        foreach ($seats as $seat) {
            $qr_code = DRTR_Biglietto_QR::generate_qr_code($test_id, $seat['seat_number']);
            
            echo '
            <div style="border: 2px solid #1ba4ce; border-radius: 8px; padding: 20px; margin: 15px 0; background: white;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <h3 style="color: #003284; margin: 0 0 10px 0;">Posto: ' . esc_html($seat['seat_number']) . '</h3>
                        <p style="margin: 5px 0;"><strong>Passeggero:</strong> ' . esc_html($seat['passenger_name']) . '</p>
                    </div>
                    <div style="text-align: center; margin: 10px;">
                        <img src="' . esc_attr($qr_code) . '" alt="QR Code" style="max-width: 150px; height: auto;">
                        <p style="font-size: 11px; color: #666; margin-top: 5px;">Mostra questo QR code alla partenza</p>
                    </div>
                </div>
            </div>';
        }
        
        echo '</div>';
        
    } else {
        echo '<div class="test-result error">❌ Nessun posto assegnato per questa prenotazione</div>';
    }
    
    echo '</div>';
}

// Test 5: Google API Test
echo '<div class="section">';
echo '<h2>🌐 Test 5: QR Server API (api.qrserver.com)</h2>';

$test_data = 'DreamTour Test QR Code - ' . date('Y-m-d H:i:s');
$qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($test_data);

echo '<p><strong>URL QR Server API:</strong></p>';
echo '<pre>' . esc_html($qr_url) . '</pre>';

echo '<div class="qr-preview">';
echo '<h4>QR Code da QR Server:</h4>';
echo '<img src="' . esc_url($qr_url) . '" alt="QR Server Test">';
echo '</div>';

// Test download
$response = wp_remote_get($qr_url, ['timeout' => 15, 'sslverify' => false]);

if (is_wp_error($response)) {
    echo '<div class="test-result error">❌ Errore download: ' . $response->get_error_message() . '</div>';
} else {
    $body = wp_remote_retrieve_body($response);
    $code = wp_remote_retrieve_response_code($response);
    
    echo '<div class="test-result success">✅ Download OK - HTTP ' . $code . ' - ' . strlen($body) . ' bytes</div>';
    
    if (strlen($body) > 0) {
        $base64 = base64_encode($body);
        $data_uri = 'data:image/png;base64,' . $base64;
        
        echo '<div class="qr-preview">';
        echo '<h4>Stesso QR in Base64:</h4>';
        echo '<img src="' . esc_attr($data_uri) . '" alt="Base64 QR Test">';
        echo '</div>';
        
        echo '<p><strong>Base64 length:</strong> ' . strlen($base64) . ' caratteri</p>';
    }
}

echo '</div>';

// Upload directory info
echo '<div class="section">';
echo '<h2>📁 Test 6: Directory Upload</h2>';

$upload_dir = wp_upload_dir();
$ticket_dir = $upload_dir['basedir'] . '/drtr-tickets';

echo '<table>';
echo '<tr><th>Proprietà</th><th>Valore</th></tr>';
echo '<tr><td>Base Dir</td><td><code>' . esc_html($upload_dir['basedir']) . '</code></td></tr>';
echo '<tr><td>Base URL</td><td><code>' . esc_html($upload_dir['baseurl']) . '</code></td></tr>';
echo '<tr><td>Tickets Dir</td><td><code>' . esc_html($ticket_dir) . '</code></td></tr>';
echo '<tr><td>Dir Exists</td><td>' . (file_exists($ticket_dir) ? '✅ Sì' : '❌ No') . '</td></tr>';
echo '<tr><td>Writable</td><td>' . (is_writable($upload_dir['basedir']) ? '✅ Sì' : '❌ No') . '</td></tr>';
echo '</table>';

// List ticket files
if (file_exists($ticket_dir)) {
    $files = glob($ticket_dir . '/qr-*.png');
    echo '<p><strong>' . count($files) . ' file QR trovati:</strong></p>';
    
    if (!empty($files)) {
        echo '<ul>';
        foreach (array_slice($files, 0, 10) as $file) {
            $filename = basename($file);
            $filesize = filesize($file);
            $url = $upload_dir['baseurl'] . '/drtr-tickets/' . $filename;
            echo '<li><a href="' . esc_url($url) . '" target="_blank">' . esc_html($filename) . '</a> (' . number_format($filesize) . ' bytes)</li>';
        }
        echo '</ul>';
    }
}

echo '</div>';

?>

<div class="section">
    <h2>🔧 Azioni</h2>
    <a href="?" class="btn">🔄 Aggiorna Pagina</a>
    <a href="<?php echo admin_url('admin.php?page=drtr-bookings'); ?>" class="btn">📋 Vai a Prenotazioni</a>
    <a href="<?php echo home_url(); ?>" class="btn">🏠 Home</a>
</div>

</body>
</html>
