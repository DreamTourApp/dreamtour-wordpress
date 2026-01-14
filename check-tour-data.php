<?php
/**
 * Script temporaneo per verificare i dati del tour nel database
 * Accedi a: https://dreamtourviaggi.it/check-tour-data.php?id=13
 * ELIMINARE DOPO L'USO!
 */

require_once('./wp-load.php');

// Solo per amministratori
if (!current_user_can('manage_options')) {
    die('Accesso negato');
}

$tour_id = isset($_GET['id']) ? intval($_GET['id']) : 13;
$post = get_post($tour_id);

if (!$post) {
    die('Tour non trovato');
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Debug Tour #<?php echo $tour_id; ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .section { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #003284; margin-top: 0; }
        .label { font-weight: bold; color: #666; }
        .value { background: #f9f9f9; padding: 10px; border-left: 3px solid #003284; margin: 10px 0; }
        .empty { color: #dc3545; }
        .ok { color: #28a745; }
        pre { background: #f4f4f4; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔍 Debug Tour #<?php echo $tour_id; ?>: <?php echo esc_html($post->post_title); ?></h1>
    
    <div class="section">
        <h2>Informazioni Base</h2>
        <p><span class="label">ID:</span> <?php echo $post->ID; ?></p>
        <p><span class="label">Tipo:</span> <?php echo $post->post_type; ?></p>
        <p><span class="label">Stato:</span> <?php echo $post->post_status; ?></p>
        <p><span class="label">Titolo:</span> <?php echo esc_html($post->post_title); ?></p>
    </div>
    
    <div class="section">
        <h2>Descrizione Breve (post_excerpt)</h2>
        <p><span class="label">Lunghezza:</span> 
            <span class="<?php echo strlen($post->post_excerpt) > 0 ? 'ok' : 'empty'; ?>">
                <?php echo strlen($post->post_excerpt); ?> caratteri
            </span>
        </p>
        <?php if (strlen($post->post_excerpt) > 0): ?>
            <div class="value">
                <?php echo nl2br(esc_html($post->post_excerpt)); ?>
            </div>
        <?php else: ?>
            <p class="empty">❌ Vuoto</p>
        <?php endif; ?>
    </div>
    
    <div class="section">
        <h2>Descrizione Completa (post_content)</h2>
        <p><span class="label">Lunghezza:</span> 
            <span class="<?php echo strlen($post->post_content) > 0 ? 'ok' : 'empty'; ?>">
                <?php echo strlen($post->post_content); ?> caratteri
            </span>
        </p>
        <?php if (strlen($post->post_content) > 0): ?>
            <div class="value">
                <?php echo nl2br(esc_html($post->post_content)); ?>
            </div>
        <?php else: ?>
            <p class="empty">❌ Vuoto</p>
        <?php endif; ?>
    </div>
    
    <div class="section">
        <h2>Meta Fields</h2>
        <?php
        $meta_fields = array(
            '_drtr_price' => 'Prezzo',
            '_drtr_duration' => 'Durata',
            '_drtr_location' => 'Località',
            '_drtr_transport_type' => 'Tipo trasporto',
            '_drtr_max_people' => 'Max persone',
            '_drtr_start_date' => 'Data inizio',
            '_drtr_end_date' => 'Data fine',
            '_drtr_includes' => 'Cosa include',
            '_drtr_not_includes' => 'Cosa non include',
            '_drtr_itinerary' => 'Itinerario',
        );
        
        foreach ($meta_fields as $key => $label) {
            $value = get_post_meta($tour_id, $key, true);
            echo '<p><span class="label">' . $label . ':</span> ';
            if (!empty($value)) {
                echo '<span class="ok">✓</span> ' . (strlen($value) > 50 ? substr(esc_html($value), 0, 50) . '...' : esc_html($value));
            } else {
                echo '<span class="empty">vuoto</span>';
            }
            echo '</p>';
        }
        ?>
    </div>
    
    <div class="section">
        <h2>Query Diretta al Database</h2>
        <?php
        global $wpdb;
        $db_post = $wpdb->get_row($wpdb->prepare(
            "SELECT post_title, post_content, post_excerpt FROM {$wpdb->posts} WHERE ID = %d",
            $tour_id
        ));
        ?>
        <pre><?php print_r($db_post); ?></pre>
    </div>
    
    <div class="section">
        <h2>Azioni</h2>
        <p>
            <a href="<?php echo admin_url('post.php?post=' . $tour_id . '&action=edit'); ?>">✏️ Modifica in WordPress Admin</a> |
            <a href="<?php echo home_url('/gestione-tours/?edit_tour=' . $tour_id); ?>">🔧 Modifica in Gestione Tours</a> |
            <a href="<?php echo get_permalink($tour_id); ?>">👁️ Visualizza Tour</a>
        </p>
    </div>
    
    <div class="section" style="background: #fff3cd; border-left: 4px solid #ffc107;">
        <strong>⚠️ IMPORTANTE:</strong> Questo file è solo per debug. Eliminalo dopo l'uso per sicurezza!
    </div>
</body>
</html>
