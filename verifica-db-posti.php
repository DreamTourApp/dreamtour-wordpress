<?php
/**
 * Verifica Completa Database Posti
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
    <title>Verifica Database Completa</title>
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
            font-size: 14px;
        }
        th, td {
            padding: 10px;
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
        .highlight {
            background: #ffffcc !important;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: #003284;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 2px;
        }
        .btn:hover {
            background: #1ba4ce;
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
        
        <h2>🔎 Ricerca Tabelle Posti/Seats</h2>
        
        <?php
        $all_tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
        $seat_tables = [];
        
        echo '<table>';
        echo '<tr><th>#</th><th>Nome Tabella</th><th>Record</th><th>Azione</th></tr>';
        
        $index = 1;
        foreach ($all_tables as $table_row) {
            $table_name = $table_row[0];
            
            $keywords = ['posti', 'seat', 'posto', 'drtr', 'booking'];
            $is_relevant = false;
            
            foreach ($keywords as $keyword) {
                if (stripos($table_name, $keyword) !== false) {
                    $is_relevant = true;
                    break;
                }
            }
            
            if ($is_relevant) {
                $count = $wpdb->get_var("SELECT COUNT(*) FROM `$table_name`");
                $seat_tables[] = [
                    'name' => $table_name,
                    'count' => $count
                ];
                
                $row_class = ($count > 0) ? 'class="highlight"' : '';
                
                echo '<tr ' . $row_class . '>';
                echo '<td>' . $index++ . '</td>';
                echo '<td><strong>' . esc_html($table_name) . '</strong></td>';
                echo '<td><strong>' . $count . '</strong></td>';
                echo '<td><a href="?inspect=' . urlencode($table_name) . '" class="btn">Ispeziona</a></td>';
                echo '</tr>';
            }
        }
        
        echo '</table>';
        
        echo '<div class="info-box">';
        echo '<strong>Tabelle trovate:</strong> ' . count($seat_tables) . '<br>';
        $total = array_sum(array_column($seat_tables, 'count'));
        echo '<strong>Record totali:</strong> ' . $total;
        echo '</div>';
        ?>
        
        <?php
        if (isset($_GET['inspect'])) {
            $inspect_table = sanitize_text_field($_GET['inspect']);
            
            echo '<h2>🔎 Ispezione: ' . esc_html($inspect_table) . '</h2>';
            echo '<p><a href="?" class="btn">← Indietro</a></p>';
            
            $columns = $wpdb->get_results("DESCRIBE `$inspect_table`");
            
            echo '<h3>Struttura</h3>';
            echo '<table>';
            echo '<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th></tr>';
            foreach ($columns as $col) {
                echo '<tr>';
                echo '<td><strong>' . $col->Field . '</strong></td>';
                echo '<td>' . $col->Type . '</td>';
                echo '<td>' . $col->Null . '</td>';
                echo '<td>' . $col->Key . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            
            $count = $wpdb->get_var("SELECT COUNT(*) FROM `$inspect_table`");
            
            echo '<h3>Dati (' . $count . ' record)</h3>';
            
            if ($count > 0) {
                $records = $wpdb->get_results("SELECT * FROM `$inspect_table` LIMIT 50");
                
                echo '<div style="overflow-x: auto;"><table>';
                
                echo '<tr>';
                foreach (array_keys((array)$records[0]) as $key) {
                    echo '<th>' . $key . '</th>';
                }
                echo '</tr>';
                
                foreach ($records as $row) {
                    echo '<tr>';
                    foreach ((array)$row as $value) {
                        echo '<td>' . esc_html($value) . '</td>';
                    }
                    echo '</tr>';
                }
                
                echo '</table></div>';
                
                if ($count > 50) {
                    echo '<p><em>Primi 50 di ' . $count . '</em></p>';
                }
            }
        }
        ?>
        
        <h2>🔗 Link</h2>
        <p>
            <a href="visualizza-posti-pullman/" class="btn">🚌 Posti</a>
            <a href="migrazione.html" class="btn">🔄 Migrazione</a>
        </p>
    </div>
</body>
</html>
