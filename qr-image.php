<?php
/**
 * QR Code Image Server - Standalone
 * Serve QR code images bypassing .htaccess restrictions
 * Does NOT load WordPress to avoid routing conflicts
 */

// Get ticket ID from query string
$ticket_id = isset($_GET['ticket']) ? $_GET['ticket'] : '';

// Sanitize input (basic security without WordPress functions)
$ticket_id = preg_replace('/[^a-zA-Z0-9\-_]/', '', $ticket_id);

if (empty($ticket_id)) {
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: text/plain');
    die('Ticket ID required');
}

// Determine WordPress root directory
$wp_root = __DIR__;

// Build path to QR code file
$ticket_dir = $wp_root . '/wp-content/uploads/drtr-tickets';
$filepath = $ticket_dir . '/qr-' . $ticket_id . '.png';

// Check if file exists
if (!file_exists($filepath)) {
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: text/plain');
    die('QR code not found: ' . basename($filepath));
}

// Get file data
$image_data = file_get_contents($filepath);

if ($image_data === false) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: text/plain');
    die('Error reading QR code');
}

// Set proper headers for PNG image
header('Content-Type: image/png');
header('Content-Length: ' . strlen($image_data));
header('Cache-Control: public, max-age=31536000'); // Cache for 1 year
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
header('Access-Control-Allow-Origin: *'); // Allow CORS

// Output image
echo $image_data;
exit;
