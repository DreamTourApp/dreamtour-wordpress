<?php
/**
 * QR Code Scanner and Ticket Validator
 * Pagina per validare i biglietti scansionando il QR code con la fotocamera
 */

// Load WordPress
require_once __DIR__ . '/wp-load.php';

// Only allow admin access
if (!current_user_can('manage_options')) {
    die('Accesso negato. Solo amministratori possono accedere a questa pagina.');
}

// Handle AJAX validation request
if (isset($_POST['action']) && $_POST['action'] === 'validate_ticket') {
    header('Content-Type: application/json');
    
    if (!check_ajax_referer('drtr-ticket-validation', 'nonce', false)) {
        echo json_encode(['success' => false, 'message' => 'Verifica di sicurezza fallita']);
        exit;
    }
    
    $qr_data = isset($_POST['qr_data']) ? $_POST['qr_data'] : '';
    
    if (empty($qr_data)) {
        echo json_encode(['success' => false, 'message' => 'Dati QR mancanti']);
        exit;
    }
    
    // Parse QR code data
    $ticket_data = json_decode($qr_data, true);
    
    if (!$ticket_data || !isset($ticket_data['booking_id']) || !isset($ticket_data['seat'])) {
        echo json_encode(['success' => false, 'message' => 'QR code non valido']);
        exit;
    }
    
    $booking_id = intval($ticket_data['booking_id']);
    $seat_number = sanitize_text_field($ticket_data['seat']);
    $ticket_id = isset($ticket_data['ticket_id']) ? sanitize_text_field($ticket_data['ticket_id']) : '';
    
    // Verify signature
    if (isset($ticket_data['signature'])) {
        $secret_key = defined('AUTH_KEY') ? AUTH_KEY : 'dreamtour-secret';
        $expected_signature = hash_hmac('sha256', $ticket_id, $secret_key);
        
        if ($ticket_data['signature'] !== $expected_signature) {
            echo json_encode(['success' => false, 'message' => 'Firma di sicurezza non valida']);
            exit;
        }
    }
    
    // Get booking info
    $booking = get_post($booking_id);
    
    if (!$booking || $booking->post_type !== 'drtr_booking') {
        echo json_encode(['success' => false, 'message' => 'Prenotazione non trovata']);
        exit;
    }
    
    // Get seat info from database
    global $wpdb;
    $table_name = $wpdb->prefix . 'drtr_posti';
    
    $seat_info = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE booking_id = %d AND seat_number = %s",
        $booking_id,
        $seat_number
    ), ARRAY_A);
    
    if (!$seat_info) {
        echo json_encode(['success' => false, 'message' => 'Posto non trovato']);
        exit;
    }
    
    // Get tour info
    $tour_id = get_post_meta($booking_id, '_booking_tour_id', true);
    $tour = get_post($tour_id);
    $tour_title = $tour ? get_the_title($tour_id) : 'Tour non disponibile';
    
    // Add tour date
    $tour_start_date = get_post_meta($tour_id, '_drtr_start_date', true) ?: get_post_meta($tour_id, 'start_date', true);
    $tour_date_formatted = '';
    if ($tour_start_date) {
        $date_obj = @DateTime::createFromFormat('Y-m-d\TH:i', $tour_start_date);
        if ($date_obj && !DateTime::getLastErrors()['warning_count']) {
            $tour_date_formatted = $date_obj->format('d/m/Y H:i');
        }
    }
    
    // Get customer info
    $customer_email = get_post_meta($booking_id, '_booking_email', true);
    $customer_phone = get_post_meta($booking_id, '_booking_phone', true);
    $booking_status = get_post_status($booking_id);
    
    // Check if already validated
    $validated_at = get_post_meta($booking_id, '_seat_' . $seat_number . '_validated_at', true);
    $validated_by = get_post_meta($booking_id, '_seat_' . $seat_number . '_validated_by', true);
    
    $was_validated = !empty($validated_at);
    
    // Mark as validated if requested
    if (isset($_POST['mark_validated']) && $_POST['mark_validated'] === 'true') {
        $current_user = wp_get_current_user();
        update_post_meta($booking_id, '_seat_' . $seat_number . '_validated_at', current_time('mysql'));
        update_post_meta($booking_id, '_seat_' . $seat_number . '_validated_by', $current_user->display_name);
        
        $validated_at = current_time('mysql');
        $validated_by = $current_user->display_name;
        $was_validated = false; // First time validation
    }
    
    // Return ticket info
    echo json_encode([
        'success' => true,
        'ticket' => [
            'ticket_id' => $ticket_id,
            'booking_id' => $booking_id,
            'seat_number' => $seat_number,
            'passenger_name' => $seat_info['passenger_name'],
            'tour_title' => $tour_title,
            'tour_date' => $tour_date_formatted,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'booking_status' => $booking_status,
            'validated_at' => $validated_at,
            'validated_by' => $validated_by,
            'was_already_validated' => $was_validated
        ]
    ]);
    exit;
}

$nonce = wp_create_nonce('drtr-ticket-validation');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validazione Biglietti - DreamTour</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #003284 0%, #1ba4ce 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .scanner-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        #reader {
            border-radius: 8px;
            overflow: hidden;
            margin: 20px 0;
            background: #f5f5f5;
        }
        
        .result-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            display: none;
        }
        
        .result-card.show {
            display: block;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .success-badge {
            background: #d4edda;
            color: #155724;
            padding: 15px 20px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 600;
        }
        
        .warning-badge {
            background: #fff3cd;
            color: #856404;
            padding: 15px 20px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 600;
        }
        
        .error-badge {
            background: #f8d7da;
            color: #721c24;
            padding: 15px 20px;
            border-radius: 8px;
            border-left: 4px solid #dc3545;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 600;
        }
        
        .ticket-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
        }
        
        .info-item label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .info-item .value {
            font-size: 16px;
            color: #003284;
            font-weight: 600;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            margin: 5px;
        }
        
        .btn-primary {
            background: #003284;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1ba4ce;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .stats {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-item .number {
            font-size: 32px;
            font-weight: 700;
            display: block;
        }
        
        .stat-item .label {
            font-size: 12px;
            opacity: 0.9;
            text-transform: uppercase;
        }
        
        .loading {
            text-align: center;
            padding: 20px;
        }
        
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #003284;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        #startButton {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .validated-info {
            background: #e7f3ff;
            border-left: 4px solid #1ba4ce;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
        }
        
        .validated-info h4 {
            color: #003284;
            margin-bottom: 10px;
        }
        
        .actions {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🎫 Validazione Biglietti</h1>
        <p>Scansiona il QR code del biglietto con la fotocamera</p>
    </div>
    
    <div class="stats">
        <div class="stat-item">
            <span class="number" id="validatedCount">0</span>
            <span class="label">Validati</span>
        </div>
        <div class="stat-item">
            <span class="number" id="scannedCount">0</span>
            <span class="label">Scansionati</span>
        </div>
        <div class="stat-item">
            <span class="number" id="errorCount">0</span>
            <span class="label">Errori</span>
        </div>
    </div>
    
    <div class="scanner-card">
        <button id="startButton" class="btn btn-primary">📷 Avvia Scanner</button>
        <div id="reader"></div>
        <p style="text-align: center; color: #666; font-size: 13px; margin-top: 15px;">
            Inquadra il QR code del biglietto nell'area della fotocamera
        </p>
    </div>
    
    <div id="resultCard" class="result-card">
        <div id="resultContent"></div>
    </div>
</div>

<!-- Include html5-qrcode library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrcodeScanner = null;
let isScanning = false;
let stats = {
    validated: 0,
    scanned: 0,
    errors: 0
};

// Start/Stop button
document.getElementById('startButton').addEventListener('click', function() {
    if (!isScanning) {
        startScanner();
    } else {
        stopScanner();
    }
});

function startScanner() {
    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0
    };
    
    html5QrcodeScanner = new Html5Qrcode("reader");
    
    html5QrcodeScanner.start(
        { facingMode: "environment" }, // Use back camera
        config,
        onScanSuccess,
        onScanFailure
    ).then(() => {
        isScanning = true;
        document.getElementById('startButton').textContent = '⏸️ Ferma Scanner';
        document.getElementById('startButton').classList.remove('btn-primary');
        document.getElementById('startButton').classList.add('btn-secondary');
    }).catch(err => {
        console.error('Errore avvio scanner:', err);
        alert('Impossibile avviare la fotocamera. Assicurati di aver dato i permessi.');
    });
}

function stopScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then(() => {
            isScanning = false;
            document.getElementById('startButton').textContent = '📷 Avvia Scanner';
            document.getElementById('startButton').classList.remove('btn-secondary');
            document.getElementById('startButton').classList.add('btn-primary');
        });
    }
}

function onScanSuccess(decodedText, decodedResult) {
    // Stop scanner briefly to process
    if (isScanning) {
        stopScanner();
    }
    
    stats.scanned++;
    updateStats();
    
    // Show loading
    document.getElementById('resultCard').classList.add('show');
    document.getElementById('resultContent').innerHTML = `
        <div class="loading">
            <div class="spinner"></div>
            <p style="margin-top: 15px;">Validazione in corso...</p>
        </div>
    `;
    
    // Validate ticket
    validateTicket(decodedText, false);
}

function onScanFailure(error) {
    // Silent - scanner constantly tries to scan
}

function validateTicket(qrData, markAsValidated = false) {
    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'validate_ticket',
            nonce: '<?php echo $nonce; ?>',
            qr_data: qrData,
            mark_validated: markAsValidated ? 'true' : 'false'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayTicketInfo(data.ticket, markAsValidated);
            if (markAsValidated) {
                stats.validated++;
                updateStats();
            }
        } else {
            displayError(data.message);
            stats.errors++;
            updateStats();
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        displayError('Errore di connessione al server');
        stats.errors++;
        updateStats();
    });
}

function displayTicketInfo(ticket, wasJustValidated) {
    let statusBadge = '';
    let validateButton = '';
    
    if (ticket.validated_at) {
        if (wasJustValidated) {
            statusBadge = `
                <div class="success-badge">
                    ✅ Biglietto validato con successo!
                </div>
            `;
        } else {
            statusBadge = `
                <div class="warning-badge">
                    ⚠️ Questo biglietto è già stato validato
                </div>
                <div class="validated-info">
                    <h4>Informazioni validazione precedente:</h4>
                    <p><strong>Data:</strong> ${ticket.validated_at}</p>
                    <p><strong>Validato da:</strong> ${ticket.validated_by}</p>
                </div>
            `;
        }
    } else {
        statusBadge = `
            <div class="success-badge">
                ✅ Biglietto valido e non ancora utilizzato
            </div>
        `;
        validateButton = `
            <button class="btn btn-success" onclick="markAsValidated('${ticket.ticket_id}')">
                ✓ Marca come Validato
            </button>
        `;
    }
    
    const content = `
        ${statusBadge}
        
        <h3 style="color: #003284; margin-bottom: 20px;">📋 Dettagli Biglietto</h3>
        
        <div class="ticket-info">
            <div class="info-item">
                <label>Passeggero</label>
                <div class="value">${ticket.passenger_name}</div>
            </div>
            <div class="info-item">
                <label>Posto</label>
                <div class="value">#${ticket.seat_number}</div>
            </div>
            <div class="info-item">
                <label>Prenotazione</label>
                <div class="value">#${ticket.booking_id}</div>
            </div>
            <div class="info-item">
                <label>Tour</label>
                <div class="value">${ticket.tour_title}</div>
            </div>
            ${ticket.tour_date ? `
            <div class="info-item">
                <label>Data Partenza</label>
                <div class="value">${ticket.tour_date}</div>
            </div>
            ` : ''}
            <div class="info-item">
                <label>Email</label>
                <div class="value" style="font-size: 13px;">${ticket.customer_email}</div>
            </div>
            ${ticket.customer_phone ? `
            <div class="info-item">
                <label>Telefono</label>
                <div class="value">${ticket.customer_phone}</div>
            </div>
            ` : ''}
        </div>
        
        <div class="actions">
            ${validateButton}
            <button class="btn btn-primary" onclick="continueScan()">
                Continua Scansione
            </button>
        </div>
    `;
    
    document.getElementById('resultContent').innerHTML = content;
    
    // Play success sound
    playBeep();
}

function displayError(message) {
    const content = `
        <div class="error-badge">
            ❌ ${message}
        </div>
        <div class="actions">
            <button class="btn btn-primary" onclick="continueScan()">
                Riprova
            </button>
        </div>
    `;
    
    document.getElementById('resultContent').innerHTML = content;
    
    // Play error sound
    playErrorBeep();
}

function markAsValidated(ticketId) {
    // Get the last scanned QR data and re-validate with mark_validated flag
    const lastQrData = document.getElementById('resultContent').dataset.lastQr;
    if (lastQrData) {
        validateTicket(lastQrData, true);
    }
}

// Store last QR data for validation
let lastScannedQr = '';
const originalValidateTicket = validateTicket;
validateTicket = function(qrData, markAsValidated) {
    lastScannedQr = qrData;
    document.getElementById('resultContent').dataset.lastQr = qrData;
    return originalValidateTicket(qrData, markAsValidated);
};

function continueScan() {
    document.getElementById('resultCard').classList.remove('show');
    startScanner();
}

function updateStats() {
    document.getElementById('validatedCount').textContent = stats.validated;
    document.getElementById('scannedCount').textContent = stats.scanned;
    document.getElementById('errorCount').textContent = stats.errors;
}

// Audio feedback
function playBeep() {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.value = 800;
    oscillator.type = 'sine';
    
    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
    
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.2);
}

function playErrorBeep() {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.value = 200;
    oscillator.type = 'sawtooth';
    
    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
    
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.3);
}
</script>

</body>
</html>
