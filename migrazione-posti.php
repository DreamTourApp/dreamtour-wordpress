<?php
/**
 * Accesso Diretto alla Migrazione Posti
 * 
 * Questo file carica WordPress e poi include il template di migrazione
 * Bypassa i problemi di rewrite rules
 */

// Carica WordPress
define('WP_USE_THEMES', false);
require_once('wp-load.php');

// Security check - solo admin
if (!is_user_logged_in() || !current_user_can('manage_options')) {
    wp_die('Accesso negato. Devi essere un amministratore per accedere a questa pagina.');
}

// Include il template di migrazione
$template_file = __DIR__ . '/wp-content/plugins/drtr-posti/templates/migrate-seats.php';

if (file_exists($template_file)) {
    include $template_file;
} else {
    echo '<h1>Errore</h1>';
    echo '<p>File di migrazione non trovato: ' . esc_html($template_file) . '</p>';
}
