<?php
/**
 * Template Name: Debug Logs Posti
 * 
 * Pagina per visualizzare i log del plugin DRTR Posti
 */

// Redirect se non admin
if (!current_user_can('manage_options')) {
    wp_redirect(home_url());
    exit;
}

get_header();
?>

<style>
    .debug-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .log-output {
        background: #1e1e1e;
        color: #d4d4d4;
        padding: 20px;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 13px;
        line-height: 1.6;
        max-height: 600px;
        overflow-y: auto;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    .log-line {
        margin: 5px 0;
    }
    .log-error {
        color: #f48771;
    }
    .log-success {
        color: #89d185;
    }
    .log-info {
        color: #6cb6ff;
    }
    .refresh-btn {
        background: #003284;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        margin-bottom: 20px;
    }
    .refresh-btn:hover {
        background: #002060;
    }
    .clear-btn {
        background: #dc3545;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        margin-left: 10px;
    }
    .clear-btn:hover {
        background: #c82333;
    }
</style>

<div class="debug-container">
    <h1>📋 Debug Logs - DRTR Posti</h1>
    
    <button class="refresh-btn" onclick="location.reload()">🔄 Ricarica</button>
    <button class="clear-btn" onclick="if(confirm('Cancellare tutti i log?')) clearLogs()">🗑️ Cancella Log</button>
    
    <h2>Log WordPress (ultimi 200 righe)</h2>
    <div class="log-output">
<?php
// Leggi il file debug.log di WordPress
$debug_log = WP_CONTENT_DIR . '/debug.log';

if (file_exists($debug_log)) {
    $lines = file($debug_log);
    $lines = array_slice($lines, -200); // Ultimi 200 righe
    
    foreach ($lines as $line) {
        $class = '';
        
        // Evidenzia solo le righe del plugin DRTR POSTI
        if (stripos($line, 'DRTR POSTI') !== false) {
            if (stripos($line, 'ERRORE') !== false || stripos($line, 'ERROR') !== false) {
                $class = 'log-error';
            } elseif (stripos($line, 'successo') !== false || stripos($line, 'SUCCESS') !== false) {
                $class = 'log-success';
            } else {
                $class = 'log-info';
            }
            echo '<div class="log-line ' . $class . '">' . esc_html($line) . '</div>';
        }
    }
    
    if (empty(array_filter($lines, function($line) {
        return stripos($line, 'DRTR POSTI') !== false;
    }))) {
        echo '<div class="log-line log-info">Nessun log trovato per DRTR POSTI</div>';
        echo '<div class="log-line">Cambia lo status di una prenotazione a "Pagato" o "Pagato Acconto" per vedere i log qui.</div>';
    }
} else {
    echo '<div class="log-line log-error">File debug.log non trovato: ' . esc_html($debug_log) . '</div>';
    echo '<div class="log-line log-info">Aggiungi queste righe a wp-config.php prima della riga "That\'s all, stop editing!":</div>';
    echo '<div class="log-line">define(\'WP_DEBUG_LOG\', true);</div>';
    echo '<div class="log-line">define(\'WP_DEBUG_DISPLAY\', false);</div>';
}
?>
    </div>
    
    <h2>Log Server (ultimi 100 righe con "DRTR")</h2>
    <div class="log-output">
<?php
// Cerca log del server
$server_logs = [
    '/home/u802332889/domains/dreamtourviaggi.it/logs/error.log',
    ini_get('error_log'),
    '/var/log/apache2/error.log',
    '/var/log/nginx/error.log'
];

$found = false;
foreach ($server_logs as $log_path) {
    if ($log_path && file_exists($log_path) && is_readable($log_path)) {
        $lines = file($log_path);
        $lines = array_slice($lines, -100);
        
        foreach ($lines as $line) {
            if (stripos($line, 'DRTR') !== false) {
                $class = stripos($line, 'error') !== false ? 'log-error' : 'log-info';
                echo '<div class="log-line ' . $class . '">' . esc_html($line) . '</div>';
                $found = true;
            }
        }
        
        if ($found) break;
    }
}

if (!$found) {
    echo '<div class="log-line">Nessun log server trovato o non accessibile</div>';
}
?>
    </div>
    
    <h2>Info Sistema</h2>
    <div class="log-output">
        <div class="log-line">WP_DEBUG: <?php echo WP_DEBUG ? 'Abilitato' : 'Disabilitato'; ?></div>
        <div class="log-line">WP_DEBUG_LOG: <?php echo defined('WP_DEBUG_LOG') && WP_DEBUG_LOG ? 'Abilitato' : 'Disabilitato'; ?></div>
        <div class="log-line">Debug Log Path: <?php echo WP_CONTENT_DIR . '/debug.log'; ?></div>
        <div class="log-line">Plugin attivo: <?php echo is_plugin_active('drtr-posti/drtr-posti.php') ? 'SI' : 'NO'; ?></div>
        <div class="log-line">Hook registrato: <?php echo has_action('drtr_booking_status_changed') ? 'SI (priorità: ' . has_action('drtr_booking_status_changed') . ')' : 'NO'; ?></div>
    </div>
</div>

<script>
function clearLogs() {
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=drtr_clear_logs&nonce=<?php echo wp_create_nonce('drtr-clear-logs'); ?>'
    })
    .then(() => location.reload());
}
</script>

<?php
get_footer();
