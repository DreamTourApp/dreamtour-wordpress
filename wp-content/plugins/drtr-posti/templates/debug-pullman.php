<?php
/**
 * Debug page for bus view
 */

if (!current_user_can('manage_options')) {
    wp_redirect(home_url());
    exit;
}

get_header();

global $wpdb;

// Test 1: Check all post types
$all_post_types = $wpdb->get_results("
    SELECT post_type, COUNT(*) as count
    FROM {$wpdb->prefix}posts
    WHERE post_status = 'publish'
    GROUP BY post_type
");

// Test 2: Check 'tour' posts specifically
$tour_posts = $wpdb->get_results("
    SELECT ID, post_title, post_type, post_status
    FROM {$wpdb->prefix}posts
    WHERE post_type = 'tour'
    ORDER BY post_title ASC
");

// Test 3: Check drtr_tour posts (from plugin)
$drtr_tour_posts = $wpdb->get_results("
    SELECT ID, post_title, post_type, post_status
    FROM {$wpdb->prefix}posts
    WHERE post_type = 'drtr_tour'
    ORDER BY post_title ASC
");

// Test 4: Check all posts that might be tours
$possible_tours = $wpdb->get_results("
    SELECT ID, post_title, post_type, post_status
    FROM {$wpdb->prefix}posts
    WHERE post_type LIKE '%tour%'
    ORDER BY post_type, post_title ASC
");

// Test 5: Check seat tables
$posti_table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}drtr_posti'");
$seats_count = 0;
if ($posti_table_exists) {
    $seats_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}drtr_posti");
}

?>

<style>
    .debug-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .debug-section {
        margin-bottom: 30px;
        padding: 20px;
        background: #f9f9f9;
        border-left: 4px solid #003284;
        border-radius: 4px;
    }
    
    .debug-section h2 {
        margin-top: 0;
        color: #003284;
    }
    
    .debug-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        background: white;
    }
    
    .debug-table th,
    .debug-table td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }
    
    .debug-table th {
        background: #003284;
        color: white;
    }
    
    .debug-table tr:nth-child(even) {
        background: #f5f5f5;
    }
    
    .success {
        color: #4caf50;
        font-weight: bold;
    }
    
    .error {
        color: #f44336;
        font-weight: bold;
    }
    
    .info {
        color: #2196f3;
        font-weight: bold;
    }
    
    .code-block {
        background: #1e1e1e;
        color: #d4d4d4;
        padding: 15px;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 13px;
        overflow-x: auto;
        margin-top: 10px;
    }
</style>

<div class="debug-container">
    <h1>🔍 Debug Visualizzazione Posti Pullman</h1>
    
    <!-- Section 1: Post Types -->
    <div class="debug-section">
        <h2>1️⃣ Post Types nel Database</h2>
        <p>Tutti i post types pubblicati trovati:</p>
        <table class="debug-table">
            <thead>
                <tr>
                    <th>Post Type</th>
                    <th>Numero di Post</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_post_types as $pt): ?>
                    <tr>
                        <td><strong><?php echo esc_html($pt->post_type); ?></strong></td>
                        <td><?php echo $pt->count; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Section 2: Tour Posts -->
    <div class="debug-section">
        <h2>2️⃣ Post Type "tour" (dal tema)</h2>
        <?php if (empty($tour_posts)): ?>
            <p class="error">❌ Nessun post trovato con post_type='tour'</p>
        <?php else: ?>
            <p class="success">✅ Trovati <?php echo count($tour_posts); ?> tour</p>
            <table class="debug-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titolo</th>
                        <th>Post Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tour_posts as $tour): ?>
                        <tr>
                            <td><?php echo $tour->ID; ?></td>
                            <td><?php echo esc_html($tour->post_title); ?></td>
                            <td><?php echo esc_html($tour->post_type); ?></td>
                            <td><?php echo esc_html($tour->post_status); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <!-- Section 3: DRTR Tour Posts -->
    <div class="debug-section">
        <h2>3️⃣ Post Type "drtr_tour" (dal plugin gestione tours)</h2>
        <?php if (empty($drtr_tour_posts)): ?>
            <p class="error">❌ Nessun post trovato con post_type='drtr_tour'</p>
        <?php else: ?>
            <p class="success">✅ Trovati <?php echo count($drtr_tour_posts); ?> tour DRTR</p>
            <table class="debug-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titolo</th>
                        <th>Post Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($drtr_tour_posts as $tour): ?>
                        <tr>
                            <td><?php echo $tour->ID; ?></td>
                            <td><?php echo esc_html($tour->post_title); ?></td>
                            <td><?php echo esc_html($tour->post_type); ?></td>
                            <td><?php echo esc_html($tour->post_status); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <!-- Section 4: All possible tours -->
    <div class="debug-section">
        <h2>4️⃣ Tutti i post type contenenti "tour"</h2>
        <?php if (empty($possible_tours)): ?>
            <p class="error">❌ Nessun post trovato con post_type contenente 'tour'</p>
        <?php else: ?>
            <p class="info">ℹ️ Trovati <?php echo count($possible_tours); ?> post con 'tour' nel post_type</p>
            <table class="debug-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titolo</th>
                        <th>Post Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($possible_tours as $tour): ?>
                        <tr>
                            <td><?php echo $tour->ID; ?></td>
                            <td><?php echo esc_html($tour->post_title); ?></td>
                            <td><strong><?php echo esc_html($tour->post_type); ?></strong></td>
                            <td><?php echo esc_html($tour->post_status); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <!-- Section 5: Seat Data -->
    <div class="debug-section">
        <h2>5️⃣ Tabella Posti</h2>
        <?php if ($posti_table_exists): ?>
            <p class="success">✅ Tabella wp_drtr_posti esiste</p>
            <p>Posti totali assegnati: <strong><?php echo $seats_count; ?></strong></p>
            
            <?php if ($seats_count > 0):
                $seats_sample = $wpdb->get_results("
                    SELECT * FROM {$wpdb->prefix}drtr_posti
                    ORDER BY assigned_at DESC
                    LIMIT 10
                ");
            ?>
                <p>Ultimi 10 posti assegnati:</p>
                <table class="debug-table">
                    <thead>
                        <tr>
                            <th>Tour ID</th>
                            <th>Booking ID</th>
                            <th>Posto</th>
                            <th>Passeggero</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($seats_sample as $seat): ?>
                            <tr>
                                <td><?php echo $seat->tour_id; ?></td>
                                <td><?php echo $seat->booking_id; ?></td>
                                <td><strong><?php echo esc_html($seat->seat_number); ?></strong></td>
                                <td><?php echo esc_html($seat->passenger_name); ?></td>
                                <td><?php echo $seat->assigned_at; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php else: ?>
            <p class="error">❌ Tabella wp_drtr_posti NON esiste!</p>
        <?php endif; ?>
    </div>
    
    <!-- Section 6: Query originale -->
    <div class="debug-section">
        <h2>6️⃣ Query Originale nella Pagina</h2>
        <p>Query SQL usata nella pagina visualizza-posti-pullman:</p>
        <div class="code-block">
SELECT ID, post_title
FROM wp_posts
WHERE post_type = 'tour' 
AND post_status = 'publish'
ORDER BY post_title ASC
        </div>
        
        <p style="margin-top: 20px;"><strong>Risultato di questa query:</strong></p>
        <?php if (empty($tour_posts)): ?>
            <p class="error">❌ NESSUN RISULTATO - Questo è il problema!</p>
            <p>Possibili cause:</p>
            <ul>
                <li>I tour sono registrati con un post_type diverso da 'tour'</li>
                <li>I tour non sono pubblicati (status != 'publish')</li>
                <li>Non ci sono tour nel database</li>
            </ul>
        <?php else: ?>
            <p class="success">✅ Trovati <?php echo count($tour_posts); ?> tour - La query funziona!</p>
        <?php endif; ?>
    </div>
    
    <!-- Section 7: Raccomandazioni -->
    <div class="debug-section">
        <h2>7️⃣ Raccomandazioni</h2>
        <?php
        $recommendation = '';
        if (!empty($drtr_tour_posts) && empty($tour_posts)) {
            $recommendation = '<p class="info">💡 I tour sono registrati come <strong>drtr_tour</strong> (dal plugin), non come <strong>tour</strong> (dal tema).</p>
                              <p>Devi modificare la query nella pagina visualizza-posti-pullman per usare <code>post_type = \'drtr_tour\'</code> invece di <code>post_type = \'tour\'</code></p>';
        } elseif (!empty($tour_posts)) {
            $recommendation = '<p class="success">✅ I tour sono correttamente registrati come <strong>tour</strong>. La pagina dovrebbe funzionare!</p>
                              <p>Se ancora non vedi i tour nella dropdown, prova a svuotare la cache del browser o del server.</p>';
        } elseif (!empty($possible_tours)) {
            $recommendation = '<p class="info">💡 Trovati post con \'tour\' nel nome del post_type ma nessuno esattamente \'tour\' o \'drtr_tour\'.</p>
                              <p>Post type trovati: <strong>' . implode(', ', array_unique(wp_list_pluck($possible_tours, 'post_type'))) . '</strong></p>';
        } else {
            $recommendation = '<p class="error">❌ Nessun tour trovato nel database! Devi creare dei tour prima.</p>';
        }
        
        echo $recommendation;
        ?>
    </div>
    
    <div style="text-align: center; margin-top: 40px;">
        <a href="<?php echo home_url('/visualizza-posti-pullman'); ?>" class="button button-primary button-large">
            ← Torna alla Visualizzazione Posti
        </a>
    </div>
</div>

<?php
get_footer();
