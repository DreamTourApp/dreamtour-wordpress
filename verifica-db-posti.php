<?php
/**
 * Verifica Database Posti
 */
define('WP_USE_THEMES', false);
require_once('wp-load.php');

if (!current_user_can('manage_options')) {
    wp_die('Accesso negato');
}

global $wpdb;
$table = $wpdb->prefix . 'drtr_posti';

// Cerca TUTTE le tabelle del database
$all_tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica Database Posti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #003284; }
        h2 { color: #1ba4ce; margin-top: 30px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #003284;
            color: white;
        }
        .info-box {
            background: #f0f7ff;
            border-left: 4px solid #003284;
            padding: 15px;
            margin: 20px 0;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .empty {
            color: #999;
            text-align: center;
            padding: 40px;
        }
        .highlight {
            background: #ffffcc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Verifica Completa Database Posti</h1>
        
        <div class="info-box">
            <strong>Tabella Attesa:</strong> <?php echo $table; ?><br>
            <strong>Prefix WordPress:</strong> <?php echo $wpdb->prefix; ?><br>
            <strong>Database:</strong> <?php echo DB_NAME; ?><br>
            <strong>Data:</strong> <?php echo date('d/m/Y H:i:s'); ?>
        </div>
        
        <h2>📋 Tutte le Tabelle del Database</h2>
        <div class="warning">
            <strong>🔍 Ricerca tabelle con "posti" nel nome...</strong>
        </div>
        <?php
        echo '<table>';
        echo '<tr><th>#</th><th>Nome Tabella</th><th>Record</th><th>Azioni</th></tr>';
        
        $seat_tables = [];
        $index = 1;
        
        foreach ($all_tables as $table_row) {
            $table_name = $table_row[0];
            
            // Evidenzia tabelle con "posti" o "seat" nel nome
            $is_seat_table = (stripos($table_name, 'posti') !== false || 
                             stripos($table_name, 'seat') !== false ||
                             stripos($table_name, 'drtr') !== false);
            
            if ($is_seat_table) {
                $seat_tables[] = $table_name;
                $count = $wpdb->get_var("SELECT COUNT(*) FROM `$table_name`");
                
                echo '<tr class="highlight">';
                echo '<td>' . $index++ . '</td>';
                echo '<td><strong>' . $table_name . '</strong></td>';
                echo '<td><strong>' . $count . ' record</strong></td>';
                echo '<td><a href="?inspect=' . urlencode($table_name) . '">Ispeziona</a></td>';
                echo '</tr>';
            }
        }
        
        if (empty($seat_tables)) {
            echo '<tr><td colspan="4" style="color: red; text-align: center;">❌ Nessuna tabella trovata con "posti", "seat" o "drtr"</td></tr>';
        }
        
        echo '</table>';
        
        // Se richiesta ispezione di una tabella specifica
        if (isset($_GET['inspect'])) {
            $inspect_table = $_GET['inspect'];
            
            echo '<h2>🔎 Ispezione Tabella: ' . esc_html($inspect_table) . '</h2>';
            
            // Struttura
            echo '<h3>Struttura</h3>';
            $columns = $wpdb->get_results("DESCRIBE `$inspect_table`");
            echo '<table>';
            echo '<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>';
            foreach ($columns as $col) {
                echo '<tr>';
                echo '<td><strong>' . $col->Field . '</strong></td>';
                echo '<td>' . $col->Type . '</td>';
                echo '<td>' . $col->Null . '</td>';
                echo '<td>' . $col->Key . '</td>';
                echo '<td>' . ($col->Default ?? 'NULL') . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            
            // Dati
            $count = $wpdb->get_var("SELECT COUNT(*) FROM `$inspect_table`");
            echo '<h3>Dati (Totale: ' . $count . ')</h3>';
            
            if ($count > 0) {
                $records = $wpdb->get_results("SELECT * FROM `$inspect_table` LIMIT 100");
                
                if (!empty($records)) {
                    echo '<table>';
                    
                    // Header
                    echo '<tr>';
                    $first_record = (array)$records[0];
                    foreach (array_keys($first_record) as $key) {
                        echo '<th>' . $key . '</th>';
                    }
                    echo '</tr>';
                    
                    // Rows
                    foreach ($records as $row) {
                        echo '<tr>';
                        foreach ((array)$row as $value) {
                            echo '<td>' . esc_html($value) . '</td>';
                        }
                        echo '</tr>';
                    }
                    
                    echo '</table>';
                    
                    if ($count > 100) {
                        echo '<p><em>Mostrati primi 100 di ' . $count . ' record</em></p>';
                    }
                }
            } else {
                echo '<p class="empty">Tabella vuota</p>';
            }
        }
        ?>
        // Check if table exists
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table'");
        
        if ($table_exists !== $table) {
            echo '<p style="color: red;">❌ Tabella non esiste!</p>';
            echo '<p>Vai su Plugin → Disattiva e riattiva "DRTR Gestione Posti"</p>';
        } else {
            echo '<p style="color: green;">✅ Tabella esiste</p>';
            
            // Get table structure
            echo '<h3>Struttura Tabella</h3>';
            $columns = $wpdb->get_results("DESCRIBE $table");
            echo '<table>';
            echo '<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th></tr>';
            foreach ($columns as $col) {
                echo '<tr>';
                echo '<td>' . $col->Field . '</td>';
                echo '<td>' . $col->Type . '</td>';
                echo '<td>' . $col->Null . '</td>';
                echo '<td>' . $col->Key . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            
            // Count records
            $count = $wpdb->get_var("SELECT COUNT(*) FROM $table");
            echo '<h3>Totale Record: ' . $count . '</h3>';
            
            if ($count > 0) {
                // Show all records
                $records = $wpdb->get_results("SELECT * FROM $table ORDER BY id");
                
                echo '<h3>📋 Tutti i Record</h3>';
                echo '<table>';
                echo '<tr><th>ID</th><th>Booking</th><th>Tour</th><th>Posto</th><th>Passeggero</th><th>Row</th><th>Pos</th><th>By</th><th>Data</th></tr>';
                
                foreach ($records as $row) {
                    echo '<tr>';
                    echo '<td>' . $row->id . '</td>';
                    echo '<td>' . $row->booking_id . '</td>';
                    echo '<td>' . $row->tour_id . '</td>';
                    echo '<td><strong>' . $row->seat_number . '</strong></td>';
                    echo '<td>' . $row->passenger_name . '</td>';
                    echo '<td>' . $row->row_number . '</td>';
                    echo '<td>' . $row->position . '</td>';
                    echo '<td>' . $row->assigned_by . '</td>';
                    echo '<td>' . date('d/m/Y H:i', strtotime($row->assigned_at)) . '</td>';
                    echo '</tr>';
                }
                
                echo '</table>';
                
                // Check seat number format
                echo '<h3>🔍 Analisi Formato Posti</h3>';
                $first_seat = $records[0]->seat_number;
                if (is_numeric($first_seat)) {
                    echo '<p style="color: green;">✅ Posti già in formato numerico (esempio: ' . $first_seat . ')</p>';
                    echo '<p>Non serve migrazione!</p>';
                } else {
                    echo '<p style="color: orange;">⚠️ Posti in formato alfanumerico (esempio: ' . $first_seat . ')</p>';
                    echo '<p>Migrazione necessaria!</p>';
                }
                
            } else {
                echo '<div class="empty">';
                echo '<h3>📭 Tabella Vuota</h3>';
                echo '<p>Non ci sono posti prenotati nel database.</p>';
                echo '<p>I posti verranno creati quando un cliente seleziona i posti per una prenotazione.</p>';
                echo '</div>';
            }
        }
        ?>
        
        <h2>🔗 Link Utili</h2>
        <p>
            <a href="visualizza-posti-pullman/" style="display: inline-block; padding: 10px 20px; background: #003284; color: white; text-decoration: none; border-radius: 4px; margin: 5px;">🚌 Visualizza Posti</a>
            <a href="migrazione.html" style="display: inline-block; padding: 10px 20px; background: #1ba4ce; color: white; text-decoration: none; border-radius: 4px; margin: 5px;">🔄 Migrazione</a>
            <a href="wp-admin/plugins.php" style="display: inline-block; padding: 10px 20px; background: #666; color: white; text-decoration: none; border-radius: 4px; margin: 5px;">⚙️ Plugin</a>
        </p>
    </div>
</body>
</html>
