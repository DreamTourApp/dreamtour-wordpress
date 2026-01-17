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
            $date_obj = DateTime::createFromFormat('Y-m-d\TH:i', $start_date); // ISO 8601: 2026-01-17T06:30
            if (!$date_obj) {
                $date_obj = DateTime::createFromFormat('Y-m-d', $start_date);
            }
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
    
    // Debug: check what's in the table
    $all_seats = $wpdb->get_results("SELECT * FROM $posti_table");
    error_log("DRTR BUS VIEW: Total seats in database: " . count($all_seats));
    if (!empty($all_seats)) {
        foreach ($all_seats as $s) {
            error_log("DRTR BUS VIEW: DB Seat - Tour ID: " . $s->tour_id . " (type: " . gettype($s->tour_id) . "), Booking: " . $s->booking_id . ", Seat: " . $s->seat_number . ", Row: " . $s->row_number . ", Pos: " . $s->position);
        }
    }
    
    // Simplified query - avoid reserved keywords in SELECT
    $seats_query = $wpdb->prepare("
        SELECT seat_number, passenger_name, booking_id, assigned_at, assigned_by
        FROM {$wpdb->prefix}drtr_posti
        WHERE tour_id = %d
        ORDER BY id
    ", $selected_tour);
    
    error_log("DRTR BUS VIEW: Query to execute: " . $seats_query);
    $seats = $wpdb->get_results($seats_query, ARRAY_A);
    
    error_log("DRTR BUS VIEW: Query: " . $wpdb->last_query);
    error_log("DRTR BUS VIEW: Selected tour ID: " . $selected_tour . " (type: " . gettype($selected_tour) . ") - Found " . count($seats) . " seats");
    if ($wpdb->last_error) {
        error_log("DRTR BUS VIEW: SQL Error: " . $wpdb->last_error);
    }
    
    $stats['occupied'] = count($seats);
    $stats['available'] = $stats['total'] - $stats['occupied'];
}

// Create seat map (13 rows, 4 seats per row, last row 5 seats) - Numeric seats 1-53
$seat_map = [];
$seat_counter = 1;

for ($row = 1; $row <= 13; $row++) {
    $seats_in_row = ($row == 13) ? 5 : 4;
    for ($pos = 1; $pos <= $seats_in_row; $pos++) {
        $seat_map[$seat_counter] = [
            'row' => $row,
            'position' => $pos,
            'occupied' => false,
            'passenger' => '',
            'booking_id' => 0
        ];
        $seat_counter++;
    }
}

// Mark occupied seats
foreach ($seats as $seat) {
    $seat_id = intval($seat['seat_number']);
    if (isset($seat_map[$seat_id])) {
        $seat_map[$seat_id]['occupied'] = true;
        $seat_map[$seat_id]['passenger'] = $seat['passenger_name'];
        $seat_map[$seat_id]['booking_id'] = $seat['booking_id'];
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
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .stat-card {
        padding: 15px;
        border-radius: 6px;
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
        font-size: 32px;
        font-weight: bold;
        margin: 5px 0;
    }
    
    .stat-label {
        font-size: 13px;
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
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .bus-row.last {
        gap: 10px;
    }
    
    .row-label {
        width: 30px;
        text-align: center;
        font-weight: 600;
        color: #003284;
        font-size: 14px;
    }
    
    .aisle-spacer {
        width: 40px;
    }
    
    .seat-group {
        display: flex;
        gap: 10px;
    }
    
    .bus-door {
        text-align: center;
        padding: 15px;
        margin: 10px 0;
        background: linear-gradient(90deg, #ffd700 0%, #ffed4e 100%);
        border: 2px dashed #cc9900;
        border-radius: 8px;
        font-weight: bold;
        color: #664400;
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .bus-container {
            margin: 20px 10px;
            padding: 15px;
        }
        
        .page-header {
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .page-header h1 {
            font-size: 24px;
        }
        
        .tour-selector {
            width: 100%;
            flex-direction: column;
        }
        
        .tour-selector select {
            min-width: 100%;
            width: 100%;
        }
        
        .tour-selector button {
            width: 100%;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .stat-card {
            padding: 12px;
        }
        
        .stat-number {
            font-size: 28px;
        }
        
        .stat-label {
            font-size: 12px;
        }
        
        .bus-layout {
            padding: 20px 10px;
            max-width: 100%;
            overflow-x: auto;
        }
        
        .bus-row {
            gap: 40px;
        }
        
        .seat {
            width: 40px;
            height: 50px;
        }
        
        .seat-number {
            font-size: 12px;
        }
        
        .passenger-name {
            font-size: 8px;
        }
        
        .legend {
            flex-direction: column;
            gap: 15px;
            padding: 15px;
        }
        
        .legend-box {
            width: 30px;
            height: 30px;
        }
        
        .passengers-list {
            padding: 15px;
            overflow-x: auto;
        }
        
        .passengers-table {
            font-size: 13px;
        }
        
        .passengers-table th,
        .passengers-table td {
            padding: 8px;
        }
    }
    
    @media (max-width: 480px) {
        .page-header h1 {
            font-size: 20px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .stat-number {
            font-size: 24px;
        }
        
        .bus-row {
            gap: 30px;
        }
        
        .seat {
            width: 35px;
            height: 45px;
        }
        
        .seat-number {
            font-size: 11px;
        }
        
        .passenger-name {
            font-size: 7px;
        }
        
        .passengers-table {
            font-size: 12px;
        }
        
        .passengers-table th,
        .passengers-table td {
            padding: 6px;
        }
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
                    if (!empty($start_date)) {
                        // Try different date formats
                        $date_obj = DateTime::createFromFormat('Y-m-d\TH:i', $start_date); // ISO 8601: 2026-01-17T06:30
                        if (!$date_obj) {
                            $date_obj = DateTime::createFromFormat('Y-m-d', $start_date);
                        }
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
            <div style="text-align: center; margin: 15px 0; padding: 10px; background: #f0f7ff; border-left: 4px solid #003284; border-radius: 4px;">
                <h2 style="margin: 0; color: #003284; font-size: 20px;">
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
            
            <?php 
            $seat_counter = 1;
            for ($row = 1; $row <= 13; $row++): 
                $seats_in_row = ($row == 13) ? 5 : 4;
            ?>
                <div class="bus-row <?php echo $row == 13 ? 'last' : ''; ?>">
                    <span class="row-label"><?php echo $row; ?></span>
                    
                    <!-- Left side: 2 seats -->
                    <div class="seat-group">
                        <?php for ($i = 0; $i < 2; $i++):
                            $seat_info = $seat_map[$seat_counter];
                            $status = $seat_info['occupied'] ? 'occupied' : 'available';
                        ?>
                            <div class="seat <?php echo $status; ?>" 
                                 title="<?php echo $seat_info['occupied'] ? esc_attr($seat_info['passenger']) : 'Disponibile'; ?>">
                                <span class="seat-number"><?php echo $seat_counter; ?></span>
                                <?php if ($seat_info['occupied']): ?>
                                    <span class="passenger-name"><?php echo esc_html(substr($seat_info['passenger'], 0, 12)); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php 
                            $seat_counter++;
                        endfor; 
                        ?>
                    </div>
                    
                    <div class="aisle-spacer"></div>
                    
                    <!-- Right side: 2 seats (3 on last row) -->
                    <div class="seat-group">
                        <?php 
                        $right_seats = ($row == 13) ? 3 : 2;
                        for ($i = 0; $i < $right_seats; $i++):
                            $seat_info = $seat_map[$seat_counter];
                            $status = $seat_info['occupied'] ? 'occupied' : 'available';
                        ?>
                            <div class="seat <?php echo $status; ?>"
                                 title="<?php echo $seat_info['occupied'] ? esc_attr($seat_info['passenger']) : 'Disponibile'; ?>">
                                <span class="seat-number"><?php echo $seat_counter; ?></span>
                                <?php if ($seat_info['occupied']): ?>
                                    <span class="passenger-name"><?php echo esc_html(substr($seat_info['passenger'], 0, 12)); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php 
                            $seat_counter++;
                        endfor; 
                        ?>
                    </div>
                </div>
                
                <?php if ($row == 6): ?>
                    <div class="bus-door">
                        <span>🚪 PORTA / DOOR</span>
                    </div>
                <?php endif; ?>
                
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
