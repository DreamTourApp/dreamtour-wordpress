<?php
/**
 * Migrazione Posti da Formato Alfanumerico a Numerico
 * 
 * Converte: 1A, 1B, 1C, 1D -> 1, 2, 3, 4
 *           2A, 2B, 2C, 2D -> 5, 6, 7, 8
 *           ...
 *           13A, 13B, 13C, 13D, 13E -> 49, 50, 51, 52, 53
 */

// Security check
if (!defined('ABSPATH')) {
    require_once('../../../../wp-load.php');
}

// Only admins can access this page
if (!current_user_can('manage_options')) {
    wp_die('Accesso negato. Solo gli amministratori possono eseguire questa migrazione.');
}

global $wpdb;
$table = $wpdb->prefix . 'drtr_posti';

// Check if migration should run
$do_migration = isset($_POST['confirm_migration']) && $_POST['confirm_migration'] === 'yes';
$nonce_valid = isset($_POST['migration_nonce']) && wp_verify_nonce($_POST['migration_nonce'], 'drtr_migrate_seats');

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Migrazione Posti Pullman</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #003284 0%, #1ba4ce 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px;
        }
        
        .info-box {
            background: #f0f7ff;
            border-left: 4px solid #003284;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 4px;
        }
        
        .info-box h3 {
            color: #003284;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .info-box ul {
            list-style: none;
            padding-left: 0;
        }
        
        .info-box li {
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        
        .info-box li:last-child {
            border-bottom: none;
        }
        
        .mapping-example {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        
        .mapping-col {
            background: white;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
        }
        
        .mapping-col h4 {
            margin-bottom: 10px;
            color: #003284;
        }
        
        .seat-example {
            display: inline-block;
            background: #1ba4ce;
            color: white;
            padding: 5px 10px;
            margin: 3px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        
        .warning-box h3 {
            color: #856404;
            margin-bottom: 10px;
        }
        
        .warning-box p {
            color: #856404;
            line-height: 1.6;
        }
        
        .form-box {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
        }
        
        .btn {
            display: inline-block;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #003284 0%, #1ba4ce 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 50, 132, 0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
            margin-left: 10px;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .results {
            margin-top: 30px;
        }
        
        .success-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .success-box h3 {
            color: #155724;
            margin-bottom: 10px;
        }
        
        .error-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .error-box h3 {
            color: #721c24;
            margin-bottom: 10px;
        }
        
        .migration-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .migration-table th,
        .migration-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .migration-table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #003284;
        }
        
        .migration-table tr:hover {
            background: #f8f9fa;
        }
        
        .old-seat {
            background: #f44336;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .new-seat {
            background: #4caf50;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        
        .stat-card {
            background: white;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #003284;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔄 Migrazione Posti Pullman</h1>
            <p>Conversione da formato alfanumerico a numerico</p>
        </div>
        
        <div class="content">
            
            <?php if (!$do_migration || !$nonce_valid): ?>
                
                <!-- Pre-migration info -->
                <div class="info-box">
                    <h3>📋 Informazioni Migrazione</h3>
                    <p style="margin-bottom: 15px;">Questa procedura convertirà tutti i numeri di posto dal formato <strong>alfanumerico</strong> (1A, 1B, 1C...) al formato <strong>numerico progressivo</strong> (1, 2, 3...).</p>
                    
                    <?php
                    // Get current data
                    $current_seats = $wpdb->get_results("SELECT id, seat_number, passenger_name, tour_id FROM $table ORDER BY tour_id, seat_number");
                    $total_records = count($current_seats);
                    ?>
                    
                    <ul>
                        <li><strong>Totale posti da migrare:</strong> <?php echo $total_records; ?></li>
                        <li><strong>Tabella database:</strong> <?php echo $table; ?></li>
                        <li><strong>Backup consigliato:</strong> Sì (prima di procedere)</li>
                    </ul>
                </div>
                
                <!-- Mapping example -->
                <div class="info-box">
                    <h3>🔄 Schema di Conversione</h3>
                    <div class="mapping-example">
                        <div class="mapping-col">
                            <h4>❌ Formato Vecchio</h4>
                            <div>
                                <span class="seat-example old-seat">1A</span>
                                <span class="seat-example old-seat">1B</span>
                                <span class="seat-example old-seat">1C</span>
                                <span class="seat-example old-seat">1D</span>
                            </div>
                            <div>
                                <span class="seat-example old-seat">2A</span>
                                <span class="seat-example old-seat">2B</span>
                                <span class="seat-example old-seat">2C</span>
                                <span class="seat-example old-seat">2D</span>
                            </div>
                            <div style="margin-top: 10px;">...</div>
                            <div>
                                <span class="seat-example old-seat">13A</span>
                                <span class="seat-example old-seat">13B</span>
                                <span class="seat-example old-seat">13C</span>
                                <span class="seat-example old-seat">13D</span>
                                <span class="seat-example old-seat">13E</span>
                            </div>
                        </div>
                        
                        <div class="mapping-col">
                            <h4>✅ Formato Nuovo</h4>
                            <div>
                                <span class="seat-example new-seat">1</span>
                                <span class="seat-example new-seat">2</span>
                                <span class="seat-example new-seat">3</span>
                                <span class="seat-example new-seat">4</span>
                            </div>
                            <div>
                                <span class="seat-example new-seat">5</span>
                                <span class="seat-example new-seat">6</span>
                                <span class="seat-example new-seat">7</span>
                                <span class="seat-example new-seat">8</span>
                            </div>
                            <div style="margin-top: 10px;">...</div>
                            <div>
                                <span class="seat-example new-seat">49</span>
                                <span class="seat-example new-seat">50</span>
                                <span class="seat-example new-seat">51</span>
                                <span class="seat-example new-seat">52</span>
                                <span class="seat-example new-seat">53</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Warning -->
                <div class="warning-box">
                    <h3>⚠️ ATTENZIONE</h3>
                    <p><strong>Questa operazione modificherà permanentemente i dati nel database.</strong></p>
                    <p>Si consiglia di fare un backup del database prima di procedere. La migrazione è irreversibile.</p>
                </div>
                
                <!-- Preview current data -->
                <?php if ($total_records > 0): ?>
                    <div class="info-box">
                        <h3>👀 Anteprima Dati Attuali</h3>
                        <table class="migration-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tour ID</th>
                                    <th>Posto Attuale</th>
                                    <th>Passeggero</th>
                                    <th>Diventerà</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $preview_limit = min(10, $total_records);
                                for ($i = 0; $i < $preview_limit; $i++): 
                                    $seat = $current_seats[$i];
                                    $new_number = convert_seat_to_numeric($seat->seat_number);
                                ?>
                                    <tr>
                                        <td><?php echo $seat->id; ?></td>
                                        <td><?php echo $seat->tour_id; ?></td>
                                        <td><span class="old-seat"><?php echo esc_html($seat->seat_number); ?></span></td>
                                        <td><?php echo esc_html($seat->passenger_name); ?></td>
                                        <td><span class="new-seat"><?php echo $new_number; ?></span></td>
                                    </tr>
                                <?php endfor; ?>
                                <?php if ($total_records > 10): ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #666;">
                                            ... e altri <?php echo $total_records - 10; ?> record
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                
                <!-- Migration form -->
                <div class="form-box">
                    <form method="POST" onsubmit="return confirm('Sei sicuro di voler procedere con la migrazione? Questa operazione è irreversibile!');">
                        <?php wp_nonce_field('drtr_migrate_seats', 'migration_nonce'); ?>
                        <input type="hidden" name="confirm_migration" value="yes">
                        <button type="submit" class="btn btn-primary">
                            🚀 Avvia Migrazione
                        </button>
                        <a href="<?php echo admin_url('admin.php?page=drtr-posti-view'); ?>" class="btn btn-secondary">
                            ❌ Annulla
                        </a>
                    </form>
                </div>
                
            <?php else: ?>
                
                <!-- Execute migration -->
                <?php
                $migration_results = execute_migration($wpdb, $table);
                ?>
                
                <div class="results">
                    <?php if ($migration_results['success']): ?>
                        <div class="success-box">
                            <h3>✅ Migrazione Completata con Successo!</h3>
                            <p><?php echo $migration_results['message']; ?></p>
                        </div>
                        
                        <div class="stats">
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $migration_results['total']; ?></div>
                                <div class="stat-label">Record Processati</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $migration_results['updated']; ?></div>
                                <div class="stat-label">Aggiornati</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $migration_results['skipped']; ?></div>
                                <div class="stat-label">Saltati</div>
                            </div>
                        </div>
                        
                        <?php if (!empty($migration_results['details'])): ?>
                            <div class="info-box">
                                <h3>📊 Dettagli Migrazione</h3>
                                <table class="migration-table">
                                    <thead>
                                        <tr>
                                            <th>ID Record</th>
                                            <th>Da</th>
                                            <th>A</th>
                                            <th>Passeggero</th>
                                            <th>Stato</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($migration_results['details'] as $detail): ?>
                                            <tr>
                                                <td><?php echo $detail['id']; ?></td>
                                                <td><span class="old-seat"><?php echo $detail['old']; ?></span></td>
                                                <td><span class="new-seat"><?php echo $detail['new']; ?></span></td>
                                                <td><?php echo esc_html($detail['passenger']); ?></td>
                                                <td><?php echo $detail['status']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                        
                    <?php else: ?>
                        <div class="error-box">
                            <h3>❌ Errore durante la Migrazione</h3>
                            <p><?php echo $migration_results['message']; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-box" style="margin-top: 30px;">
                        <a href="<?php echo admin_url('admin.php?page=drtr-posti-view'); ?>" class="btn btn-primary">
                            ← Torna alla Visualizzazione Posti
                        </a>
                    </div>
                </div>
                
            <?php endif; ?>
            
        </div>
    </div>
</body>
</html>

<?php

/**
 * Convert alphanumeric seat to numeric
 * 1A -> 1, 1B -> 2, 1C -> 3, 1D -> 4
 * 2A -> 5, 2B -> 6, etc.
 */
function convert_seat_to_numeric($seat_number) {
    // If already numeric, return as is
    if (is_numeric($seat_number)) {
        return intval($seat_number);
    }
    
    // Parse format like "1A", "13E"
    if (preg_match('/^(\d+)([A-E])$/', $seat_number, $matches)) {
        $row = intval($matches[1]);
        $letter = $matches[2];
        
        // Convert letter to position (A=1, B=2, C=3, D=4, E=5)
        $position = ord($letter) - ord('A') + 1;
        
        // Calculate numeric seat number
        // Rows 1-12: 4 seats each
        // Row 13: 5 seats
        if ($row <= 12) {
            $numeric = (($row - 1) * 4) + $position;
        } else {
            // Row 13
            $numeric = (12 * 4) + $position; // 48 + position
        }
        
        return $numeric;
    }
    
    // If format not recognized, return original
    return $seat_number;
}

/**
 * Execute the migration
 */
function execute_migration($wpdb, $table) {
    $results = array(
        'success' => false,
        'message' => '',
        'total' => 0,
        'updated' => 0,
        'skipped' => 0,
        'details' => array()
    );
    
    // Get all seats
    $seats = $wpdb->get_results("SELECT * FROM $table ORDER BY id");
    
    if (empty($seats)) {
        $results['message'] = 'Nessun posto da migrare trovato nel database.';
        return $results;
    }
    
    $results['total'] = count($seats);
    
    // Start transaction
    $wpdb->query('START TRANSACTION');
    
    try {
        foreach ($seats as $seat) {
            $old_number = $seat->seat_number;
            $new_number = convert_seat_to_numeric($old_number);
            
            // Skip if already numeric
            if ($old_number == $new_number) {
                $results['skipped']++;
                $results['details'][] = array(
                    'id' => $seat->id,
                    'old' => $old_number,
                    'new' => $new_number,
                    'passenger' => $seat->passenger_name,
                    'status' => '⏭️ Già numerico'
                );
                continue;
            }
            
            // Update the record
            $updated = $wpdb->update(
                $table,
                array('seat_number' => $new_number),
                array('id' => $seat->id),
                array('%s'),
                array('%d')
            );
            
            if ($updated !== false) {
                $results['updated']++;
                $results['details'][] = array(
                    'id' => $seat->id,
                    'old' => $old_number,
                    'new' => $new_number,
                    'passenger' => $seat->passenger_name,
                    'status' => '✅ Aggiornato'
                );
            } else {
                throw new Exception("Errore aggiornamento ID {$seat->id}");
            }
        }
        
        // Commit transaction
        $wpdb->query('COMMIT');
        
        $results['success'] = true;
        $results['message'] = "Migrazione completata: {$results['updated']} posti aggiornati, {$results['skipped']} saltati.";
        
    } catch (Exception $e) {
        // Rollback on error
        $wpdb->query('ROLLBACK');
        $results['success'] = false;
        $results['message'] = 'Errore durante la migrazione: ' . $e->getMessage();
    }
    
    return $results;
}
