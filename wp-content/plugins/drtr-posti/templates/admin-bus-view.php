<?php
/**
 * Template Name: Visualizzazione Posti Pullman Admin
 * 
 * Pagina per admin per vedere i posti occupati nel pullman
 */

// Redirect se non admin
if (!current_user_can('manage_options')) {
    wp_redirect(home_url());
    exit;
}

get_header();

// Get all tours with seat settings
global $wpdb;
$tours_table = $wpdb->prefix . 'posts';

// Get all published tours (from drtr-gestione-tours plugin)
$tours = $wpdb->get_results("
    SELECT ID, post_title
    FROM $tours_table
    WHERE post_type = 'drtr_tour' 
    AND post_status = 'publish'
    ORDER BY post_title ASC
");

error_log("DRTR POSTI VIEW: Found " . count($tours) . " drtr_tour tours");

// Get selected tour
$selected_tour = isset($_GET['tour_id']) ? intval($_GET['tour_id']) : 0;
$selected_tour_title = '';
$selected_tour_date = '';

if ($selected_tour > 0) {
    $tour_post = get_post($selected_tour);
    if ($tour_post) {
        $selected_tour_title = $tour_post->post_title;
        $start_date = get_post_meta($selected_tour, '_drtr_start_date', true);
        if ($start_date) {
            // Convert date to d/m/Y format without time
            $date_obj = DateTime::createFromFormat('Y-m-d', $start_date);
            if (!$date_obj) {
                $date_obj = DateTime::createFromFormat('Y-m-d H:i:s', $start_date);
            }
            if ($date_obj) {
                $selected_tour_date = $date_obj->format('d/m/Y');
            }
        }
    }
}

// Get seats for selected tour
$seats = [];
$stats = ['total' => 50, 'occupied' => 0, 'available' => 50];

if ($selected_tour > 0) {
    $posti_table = $wpdb->prefix . 'drtr_posti';
    $seats = $wpdb->get_results($wpdb->prepare("
        SELECT seat_number, passenger_name, booking_id, assigned_at, assigned_by, row_number, position
        FROM $posti_table
        WHERE tour_id = %d
        ORDER BY row_number, position
    ", $selected_tour), ARRAY_A);
    
    $stats['occupied'] = count($seats);
    $stats['available'] = $stats['total'] - $stats['occupied'];
}

// Create seat map (13 rows, 4 seats per row, last row 5 seats)
$seat_map = [];
for ($row = 1; $row <= 13; $row++) {
    $seats_in_row = ($row == 13) ? 5 : 4;
    for ($pos = 1; $pos <= $seats_in_row; $pos++) {
        $seat_number = $row . chr(64 + $pos); // 1A, 1B, 1C, 1D, etc.
        $seat_map[$seat_number] = [
            'row' => $row,
            'position' => $pos,
            'occupied' => false,
            'passenger' => '',
            'booking_id' => 0
        ];
    }
}

// Mark occupied seats
foreach ($seats as $seat) {
    if (isset($seat_map[$seat['seat_number']])) {
        $seat_map[$seat['seat_number']]['occupied'] = true;
        $seat_map[$seat['seat_number']]['passenger'] = $seat['passenger_name'];
        $seat_map[$seat['seat_number']]['booking_id'] = $seat['booking_id'];
    }
}
?>

<style>
    .bus-container {
        max-width: 1400px;
        margin: 40px auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #003284;
    }
    
    .tour-selector {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    
    .tour-selector select {
        padding: 10px 15px;
        border: 2px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
        min-width: 300px;
    }
    
    .tour-selector button {
        padding: 10px 20px;
        background: #003284;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }
    
    .tour-selector button:hover {
        background: #002060;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }
    
    .stat-card.total {
        background: #e3f2fd;
        border: 2px solid #2196f3;
    }
    
    .stat-card.occupied {
        background: #ffebee;
        border: 2px solid #f44336;
    }
    
    .stat-card.available {
        background: #e8f5e9;
        border: 2px solid #4caf50;
    }
    
    .stat-number {
        font-size: 48px;
        font-weight: bold;
        margin: 10px 0;
    }
    
    .stat-label {
        font-size: 16px;
        color: #666;
        text-transform: uppercase;
    }
    
    .bus-layout {
        background: linear-gradient(180deg, #f5f5f5 0%, #e0e0e0 100%);
        border: 3px solid #333;
        border-radius: 20px 20px 10px 10px;
        padding: 30px 20px;
        position: relative;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .bus-front {
        text-align: center;
        font-weight: bold;
        color: #003284;
        margin-bottom: 20px;
        font-size: 18px;
    }
    
    .bus-row {
        display: flex;
        justify-content: center;
        gap: 60px;
        margin-bottom: 15px;
    }
    
    .bus-row.last {
        justify-content: center;
        gap: 20px;
    }
    
    .seat-group {
        display: flex;
        gap: 10px;
    }
    
    .seat {
        width: 50px;
        height: 60px;
        border: 2px solid #666;
        border-radius: 8px 8px 4px 4px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        font-size: 12px;
        font-weight: bold;
    }
    
    .seat.available {
        background: linear-gradient(180deg, #4caf50 0%, #45a049 100%);
        border-color: #2e7d32;
        color: white;
    }
    
    .seat.occupied {
        background: linear-gradient(180deg, #f44336 0%, #d32f2f 100%);
        border-color: #c62828;
        color: white;
    }
    
    .seat:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .seat-number {
        font-size: 14px;
        font-weight: bold;
    }
    
    .passenger-name {
        font-size: 9px;
        margin-top: 4px;
        text-align: center;
        line-height: 1.1;
        max-width: 45px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .legend {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-top: 30px;
        padding: 20px;
        background: white;
        border-radius: 8px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .legend-box {
        width: 40px;
        height: 40px;
        border: 2px solid #666;
        border-radius: 6px;
    }
    
    .passengers-list {
        margin-top: 40px;
        padding: 20px;
        background: white;
        border-radius: 8px;
    }
    
    .passengers-list h3 {
        margin-bottom: 20px;
        color: #003284;
    }
    
    .passengers-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .passengers-table th,
    .passengers-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    
    .passengers-table th {
        background: #003284;
        color: white;
        font-weight: bold;
    }
    
    .passengers-table tr:hover {
        background: #f5f5f5;
    }
    
    .no-tour-selected {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }
    
    .no-tour-selected h2 {
        color: #003284;
        margin-bottom: 10px;
    }
</style>

<div class="bus-container">
    <div class="page-header">
        <h1>🚌 Visualizzazione Posti Pullman</h1>
        <div class="tour-selector">
            <select id="tour-select">
                <option value="">-- Seleziona un tour --</option>
                <?php foreach ($tours as $tour): 
                    $start_date = get_post_meta($tour->ID, '_drtr_start_date', true);
                    $date_formatted = '';
                    if ($start_date) {
                        $date_obj = DateTime::createFromFormat('Y-m-d', $start_date);
                        if (!$date_obj) {
                            $date_obj = DateTime::createFromFormat('Y-m-d H:i:s', $start_date);
                        }
                        if ($date_obj) {
                            $date_formatted = ' - ' . $date_obj->format('d/m/Y');
                        }
                    }
                ?>
                    <option value="<?php echo $tour->ID; ?>" <?php selected($selected_tour, $tour->ID); ?>>
                        <?php echo esc_html($tour->post_title) . $date_formatted; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button onclick="loadTour()">Visualizza</button>
        </div>
    </div>
    
    <?php if ($selected_tour > 0): ?>
        
        <!-- Tour Info -->
        <?php if ($selected_tour_title): ?>
            <div style="text-align: center; margin: 20px 0; padding: 15px; background: #f0f7ff; border-left: 4px solid #003284; border-radius: 4px;">
                <h2 style="margin: 0; color: #003284; font-size: 24px;">
                    <?php echo esc_html($selected_tour_title); ?>
                    <?php if ($selected_tour_date): ?>
                        <span style="color: #1aabe7; font-weight: 600;"> - <?php echo esc_html($selected_tour_date); ?></span>
                    <?php endif; ?>
                </h2>
            </div>
        <?php endif; ?>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-label">Posti Totali</div>
                <div class="stat-number"><?php echo $stats['total']; ?></div>
            </div>
            <div class="stat-card occupied">
                <div class="stat-label">Posti Occupati</div>
                <div class="stat-number"><?php echo $stats['occupied']; ?></div>
            </div>
            <div class="stat-card available">
                <div class="stat-label">Posti Disponibili</div>
                <div class="stat-number"><?php echo $stats['available']; ?></div>
            </div>
        </div>
        
        <!-- Bus Layout -->
        <div class="bus-layout">
            <div class="bus-front">⬆ AUTISTA ⬆</div>
            
            <?php for ($row = 1; $row <= 13; $row++): ?>
                <div class="bus-row <?php echo $row == 13 ? 'last' : ''; ?>">
                    <?php if ($row == 13): ?>
                        <!-- Last row: 5 seats -->
                        <?php for ($pos = 1; $pos <= 5; $pos++):
                            $seat_num = $row . chr(64 + $pos);
                            $seat_info = $seat_map[$seat_num];
                            $status = $seat_info['occupied'] ? 'occupied' : 'available';
                        ?>
                            <div class="seat <?php echo $status; ?>" 
                                 title="<?php echo $seat_info['occupied'] ? esc_attr($seat_info['passenger']) : 'Disponibile'; ?>">
                                <span class="seat-number"><?php echo $seat_num; ?></span>
                                <?php if ($seat_info['occupied']): ?>
                                    <span class="passenger-name"><?php echo esc_html(substr($seat_info['passenger'], 0, 12)); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    <?php else: ?>
                        <!-- Regular rows: 2 seats - aisle - 2 seats -->
                        <div class="seat-group">
                            <?php for ($pos = 1; $pos <= 2; $pos++):
                                $seat_num = $row . chr(64 + $pos);
                                $seat_info = $seat_map[$seat_num];
                                $status = $seat_info['occupied'] ? 'occupied' : 'available';
                            ?>
                                <div class="seat <?php echo $status; ?>"
                                     title="<?php echo $seat_info['occupied'] ? esc_attr($seat_info['passenger']) : 'Disponibile'; ?>">
                                    <span class="seat-number"><?php echo $seat_num; ?></span>
                                    <?php if ($seat_info['occupied']): ?>
                                        <span class="passenger-name"><?php echo esc_html(substr($seat_info['passenger'], 0, 12)); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                        
                        <div class="seat-group">
                            <?php for ($pos = 3; $pos <= 4; $pos++):
                                $seat_num = $row . chr(64 + $pos);
                                $seat_info = $seat_map[$seat_num];
                                $status = $seat_info['occupied'] ? 'occupied' : 'available';
                            ?>
                                <div class="seat <?php echo $status; ?>"
                                     title="<?php echo $seat_info['occupied'] ? esc_attr($seat_info['passenger']) : 'Disponibile'; ?>">
                                    <span class="seat-number"><?php echo $seat_num; ?></span>
                                    <?php if ($seat_info['occupied']): ?>
                                        <span class="passenger-name"><?php echo esc_html(substr($seat_info['passenger'], 0, 12)); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
        
        <!-- Legend -->
        <div class="legend">
            <div class="legend-item">
                <div class="legend-box" style="background: linear-gradient(180deg, #4caf50 0%, #45a049 100%);"></div>
                <span>Disponibile</span>
            </div>
            <div class="legend-item">
                <div class="legend-box" style="background: linear-gradient(180deg, #f44336 0%, #d32f2f 100%);"></div>
                <span>Occupato</span>
            </div>
        </div>
        
        <!-- Passengers List -->
        <?php if (!empty($seats)): ?>
            <div class="passengers-list">
                <h3>📋 Elenco Passeggeri (<?php echo count($seats); ?>)</h3>
                <table class="passengers-table">
                    <thead>
                        <tr>
                            <th>Posto</th>
                            <th>Nome Passeggero</th>
                            <th>ID Prenotazione</th>
                            <th>Data Assegnazione</th>
                            <th>Assegnato da</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($seats as $seat): ?>
                            <tr>
                                <td><strong><?php echo esc_html($seat['seat_number']); ?></strong></td>
                                <td><?php echo esc_html($seat['passenger_name']); ?></td>
                                <td>
                                    <a href="<?php echo admin_url('post.php?post=' . $seat['booking_id'] . '&action=edit'); ?>" target="_blank">
                                        #<?php echo $seat['booking_id']; ?>
                                    </a>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($seat['assigned_at'])); ?></td>
                                <td><?php echo esc_html($seat['assigned_by']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        
        <div class="no-tour-selected">
            <h2>Seleziona un tour per visualizzare i posti</h2>
            <p>Scegli un tour dal menu a tendina sopra per vedere la mappa dei posti e i passeggeri.</p>
        </div>
        
    <?php endif; ?>
</div>

<script>
function loadTour() {
    const tourId = document.getElementById('tour-select').value;
    if (tourId) {
        window.location.href = '<?php echo home_url('/visualizza-posti-pullman'); ?>?tour_id=' + tourId;
    }
}

// Auto-reload every 30 seconds if tour is selected
<?php if ($selected_tour > 0): ?>
setTimeout(function() {
    location.reload();
}, 30000);
<?php endif; ?>
</script>

<?php
get_footer();
