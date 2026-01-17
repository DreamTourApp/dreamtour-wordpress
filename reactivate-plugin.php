<?php
/**
 * Script to reactivate plugin and create migration page
 */
define('WP_USE_THEMES', false);
require('./wp-load.php');

if (!function_exists('activate_plugin')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

echo "Riattivazione plugin DRTR Posti...\n";

deactivate_plugins('drtr-posti/drtr-posti.php');
echo "✅ Plugin disattivato\n";

activate_plugin('drtr-posti/drtr-posti.php');
echo "✅ Plugin riattivato\n";

// Create migration page manually if not exists
$page = get_page_by_path('migra-posti-pullman');
if (!$page) {
    echo "Creazione pagina migrazione...\n";
    $page_id = wp_insert_post(array(
        'post_title'   => 'Migrazione Posti Pullman',
        'post_name'    => 'migra-posti-pullman',
        'post_content' => '<!-- Migration page managed by plugin -->',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_author'  => 1,
    ));
    
    if ($page_id) {
        echo "✅ Pagina migrazione creata (ID: $page_id)\n";
        $page = get_post($page_id);
    } else {
        echo "❌ Errore creazione pagina\n";
    }
}

// Flush rewrite rules
flush_rewrite_rules();
echo "✅ Rewrite rules aggiornate\n";

// Check if page was created
if ($page) {
    echo "✅ Pagina migrazione pronta!\n";
    echo "URL: " . get_permalink($page->ID) . "\n";
    echo "\nAccedi a: https://dreamtourviaggi.it/migra-posti-pullman/\n";
} else {
    echo "❌ Errore: pagina non trovata\n";
    
    // List all pages
    echo "\nPagine esistenti:\n";
    $pages = get_pages();
    foreach ($pages as $p) {
        echo "- {$p->post_title} ({$p->post_name}): " . get_permalink($p->ID) . "\n";
    }
}
