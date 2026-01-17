<?php
/**
 * QR Code Image Server
 * Serve QR code images bypassing .htaccess restrictions
 */

// Load WordPress
require_once __DIR__ . '/../../wp-load.php';

// Get ticket ID from query string
$ticket_id = isset($_GET['ticket']) ? sanitize_text_field($_GET['ticket']) : '';

if (empty($ticket_id)) {
    header('HTTP/1.1 400 Bad Request');
    die('Ticket ID required');
}

// Build path to QR code file
$upload_dir = wp_upload_dir();
$ticket_dir = $upload_dir['basedir'] . '/drtr-tickets';
$filepath = $ticket_dir . '/qr-' . $ticket_id . '.png';

// Check if file exists
if (!file_exists($filepath)) {
    header('HTTP/1.1 404 Not Found');
    die('QR code not found');
}

// Get file data
$image_data = file_get_contents($filepath);

if ($image_data === false) {
    header('HTTP/1.1 500 Internal Server Error');
    die('Error reading QR code');
}

// Set proper headers for PNG image
header('Content-Type: image/png');
header('Content-Length: ' . strlen($image_data));
header('Cache-Control: public, max-age=31536000'); // Cache for 1 year
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

// Output image
echo $image_data;
exit;
