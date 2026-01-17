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
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #003284; }
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
        .empty {
            color: #999;
            text-align: center;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Verifica Database Posti</h1>
        
        <div class="info-box">
            <strong>Tabella:</strong> <?php echo $table; ?><br>
            <strong>Data:</strong> <?php echo date('d/m/Y H:i:s'); ?>
        </div>
        
        <h2>📊 Stato Tabella</h2>
        <?php
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
