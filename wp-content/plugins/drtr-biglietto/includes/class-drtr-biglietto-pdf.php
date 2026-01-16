<?php
/**
 * PDF Generation for Tickets
 */

if (!defined('ABSPATH')) {
    exit;
}

class DRTR_Biglietto_PDF {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Constructor
    }
    
    /**
     * Generate PDF ticket
     * 
     * Note: This is a simplified version using HTML
     * For production, consider using libraries like TCPDF, FPDF, or mPDF
     */
    public static function generate_ticket_pdf($booking_id, $tickets, $info) {
        $upload_dir = wp_upload_dir();
        $ticket_dir = $upload_dir['basedir'] . '/drtr-tickets';
        
        if (!file_exists($ticket_dir)) {
            wp_mkdir_p($ticket_dir);
        }
        
        $filename = 'ticket-' . $booking_id . '-' . time() . '.html';
        $filepath = $ticket_dir . '/' . $filename;
        
        // Generate HTML content
        $html = self::generate_ticket_html($booking_id, $tickets, $info);
        
        // Save HTML file
        file_put_contents($filepath, $html);
        
        // Return URL
        return $upload_dir['baseurl'] . '/drtr-tickets/' . $filename;
    }
    
    /**
     * Generate printable HTML ticket
     */
    private static function generate_ticket_html($booking_id, $tickets, $info) {
        $logo_url = get_template_directory_uri() . '/assets/images/logo.png';
        
        $tickets_html = '';
        foreach ($tickets as $index => $ticket) {
            $page_break = ($index < count($tickets) - 1) ? 'page-break-after: always;' : '';
            
            $tickets_html .= '
            <div class="ticket-page" style="' . $page_break . '">
                <div class="ticket-card">
                    <div class="ticket-header">
                        <img src="' . esc_url($logo_url) . '" alt="Dream Tour" class="logo">
                        <h1>BIGLIETTO TOUR</h1>
                    </div>
                    
                    <div class="ticket-content">
                        <div class="tour-info">
                            <h2>' . esc_html($info['tour_title']) . '</h2>
                            <p class="date"><strong>Data:</strong> ' . esc_html($info['tour_date']) . '</p>
                        </div>
                        
                        <div class="passenger-info">
                            <div class="info-row">
                                <span class="label">Cliente:</span>
                                <span class="value">' . esc_html($info['customer_name']) . '</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Passeggero:</span>
                                <span class="value">' . esc_html($ticket['passenger']) . '</span>
                            </div>
                            <div class="info-row seat-row">
                                <span class="label">Posto Assegnato:</span>
                                <span class="seat-number">' . esc_html($ticket['seat']) . '</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Prenotazione:</span>
                                <span class="value">#' . esc_html($booking_id) . '</span>
                            </div>
                        </div>
                        
                        <div class="qr-section">
                            <img src="' . esc_url($ticket['qr_code']) . '" alt="QR Code" class="qr-code">
                            <p class="qr-instruction">Mostra questo QR code alla partenza</p>
                        </div>
                        
                        <div class="instructions">
                            <h3>📋 Istruzioni:</h3>
                            <ul>
                                <li>Presenta questo biglietto alla partenza</li>
                                <li>Arriva almeno 15 minuti prima</li>
                                <li>Porta un documento d\'identità valido</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="ticket-footer">
                        <p>Dream Tour - www.dreamtourviaggi.it</p>
                        <p>Per assistenza: info@dreamtourviaggi.it</p>
                    </div>
                </div>
            </div>';
        }
        
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Biglietti Tour - Dream Tour</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        
        .ticket-page {
            width: 100%;
            min-height: 297mm;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .ticket-card {
            width: 100%;
            max-width: 700px;
            border: 3px solid #003284;
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #003284 0%, #1ba4ce 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
            filter: brightness(0) invert(1);
        }
        
        .ticket-header h1 {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 2px;
        }
        
        .ticket-content {
            padding: 40px;
        }
        
        .tour-info {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1ba4ce;
        }
        
        .tour-info h2 {
            color: #003284;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .date {
            font-size: 16px;
            color: #666;
        }
        
        .passenger-info {
            margin-bottom: 30px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .label {
            font-weight: 600;
            color: #003284;
        }
        
        .value {
            color: #333;
        }
        
        .seat-row {
            background: #f0f8ff;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            border: 2px solid #1ba4ce;
        }
        
        .seat-number {
            font-size: 28px;
            font-weight: 700;
            color: #003284;
        }
        
        .qr-section {
            text-align: center;
            margin: 40px 0;
            padding: 30px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        
        .qr-code {
            max-width: 250px;
            height: auto;
            border: 5px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .qr-instruction {
            margin-top: 15px;
            font-size: 14px;
            font-weight: 600;
            color: #003284;
        }
        
        .instructions {
            background: #fff3cd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
        }
        
        .instructions h3 {
            color: #856404;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .instructions ul {
            list-style-position: inside;
            color: #856404;
        }
        
        .instructions li {
            margin: 5px 0;
        }
        
        .ticket-footer {
            background: #f0f0f0;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        .ticket-footer p {
            margin: 5px 0;
        }
        
        @media print {
            body {
                margin: 0;
            }
            
            .ticket-page {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    ' . $tickets_html . '
</body>
</html>';
    }
}
