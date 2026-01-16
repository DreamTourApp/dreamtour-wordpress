<?php
/**
 * Debug Checkout Page
 * 
 * @package DRTR_Checkout
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="drtr-debug-checkout" style="max-width: 1200px; margin: 40px auto; padding: 20px; font-family: monospace;">
    <h1 style="color: #003284;">🔍 Debug Checkout AJAX</h1>
    
    <!-- AJAX Configuration -->
    <div style="background: #f5f5f5; padding: 20px; margin: 20px 0; border-left: 4px solid #003284;">
        <h2>1. Configurazione AJAX</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>AJAX URL:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo admin_url('admin-ajax.php'); ?></td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Nonce:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo wp_create_nonce('dreamtour-nonce'); ?></td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>dreamtourData disponibile:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                    <span id="dreamtour-data-status">Checking...</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>jQuery disponibile:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                    <span id="jquery-status">Checking...</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Registered Hooks -->
    <div style="background: #f5f5f5; padding: 20px; margin: 20px 0; border-left: 4px solid #1aabe7;">
        <h2>2. Hook AJAX Registrati</h2>
        <pre style="background: white; padding: 15px; overflow-x: auto;"><?php
            global $wp_filter;
            echo "wp_ajax_drtr_process_checkout:\n";
            if (isset($wp_filter['wp_ajax_drtr_process_checkout'])) {
                print_r($wp_filter['wp_ajax_drtr_process_checkout']);
            } else {
                echo "❌ NON REGISTRATO!\n";
            }
            
            echo "\nwp_ajax_nopriv_drtr_process_checkout:\n";
            if (isset($wp_filter['wp_ajax_nopriv_drtr_process_checkout'])) {
                print_r($wp_filter['wp_ajax_nopriv_drtr_process_checkout']);
            } else {
                echo "❌ NON REGISTRATO!\n";
            }
        ?></pre>
    </div>

    <!-- Plugin Status -->
    <div style="background: #f5f5f5; padding: 20px; margin: 20px 0; border-left: 4px solid #46c7f0;">
        <h2>3. Plugin Attivi</h2>
        <pre style="background: white; padding: 15px; overflow-x: auto;"><?php
            $active_plugins = get_option('active_plugins');
            foreach ($active_plugins as $plugin) {
                echo "✓ $plugin\n";
            }
        ?></pre>
    </div>

    <!-- AJAX Test Buttons -->
    <div style="background: #f5f5f5; padding: 20px; margin: 20px 0; border-left: 4px solid #1ba4ce;">
        <h2>4. Test AJAX</h2>
        
        <button id="test-simple-ajax" style="padding: 10px 20px; background: #003284; color: white; border: none; cursor: pointer; margin: 10px 5px;">
            Test AJAX Semplice (senza nonce)
        </button>
        
        <button id="test-checkout-ajax" style="padding: 10px 20px; background: #1aabe7; color: white; border: none; cursor: pointer; margin: 10px 5px;">
            Test Checkout AJAX (completo)
        </button>
        
        <button id="clear-results" style="padding: 10px 20px; background: #666; color: white; border: none; cursor: pointer; margin: 10px 5px;">
            Pulisci Risultati
        </button>
        
        <div id="ajax-results" style="margin-top: 20px; padding: 15px; background: white; min-height: 100px; border: 1px solid #ddd;">
            <p style="color: #999;">I risultati dei test appariranno qui...</p>
        </div>
    </div>

    <!-- Debug Log -->
    <div style="background: #f5f5f5; padding: 20px; margin: 20px 0; border-left: 4px solid #082a5b;">
        <h2>5. Debug Log File</h2>
        <?php
        $debug_file = WP_CONTENT_DIR . '/drtr-checkout-debug.txt';
        if (file_exists($debug_file)) {
            $content = file_get_contents($debug_file);
            echo '<pre style="background: white; padding: 15px; overflow-x: auto; max-height: 400px;">' . esc_html($content) . '</pre>';
            echo '<button id="clear-debug-log" style="padding: 10px 20px; background: #d9534f; color: white; border: none; cursor: pointer; margin: 10px 0;">Pulisci Log</button>';
        } else {
            echo '<p>Nessun log file trovato ancora. Il file verrà creato al primo checkout.</p>';
        }
        ?>
    </div>

    <!-- Server Info -->
    <div style="background: #f5f5f5; padding: 20px; margin: 20px 0; border-left: 4px solid #666;">
        <h2>6. Info Server</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>PHP Version:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo PHP_VERSION; ?></td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>WordPress Version:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo get_bloginfo('version'); ?></td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>WP_DEBUG:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo defined('WP_DEBUG') && WP_DEBUG ? '✓ Attivo' : '✗ Disattivo'; ?></td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Max Execution Time:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo ini_get('max_execution_time'); ?>s</td>
            </tr>
        </table>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Check if dreamtourData is available
    if (typeof dreamtourData !== 'undefined') {
        $('#dreamtour-data-status').html('✓ Disponibile').css('color', 'green');
        console.log('dreamtourData:', dreamtourData);
    } else {
        $('#dreamtour-data-status').html('✗ NON disponibile').css('color', 'red');
    }
    
    // Check if jQuery is available
    if (typeof jQuery !== 'undefined') {
        $('#jquery-status').html('✓ Versione ' + $.fn.jquery).css('color', 'green');
    } else {
        $('#jquery-status').html('✗ NON disponibile').css('color', 'red');
    }
    
    // Test Simple AJAX
    $('#test-simple-ajax').on('click', function() {
        const $results = $('#ajax-results');
        $results.html('<p style="color: blue;">🔄 Test in corso...</p>');
        
        $.ajax({
            url: dreamtourData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'drtr_test_ajax'
            },
            success: function(response) {
                $results.html(
                    '<p style="color: green; font-weight: bold;">✓ SUCCESS!</p>' +
                    '<pre>' + JSON.stringify(response, null, 2) + '</pre>'
                );
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $results.html(
                    '<p style="color: red; font-weight: bold;">✗ ERROR!</p>' +
                    '<p><strong>Status:</strong> ' + jqXHR.status + '</p>' +
                    '<p><strong>Text Status:</strong> ' + textStatus + '</p>' +
                    '<p><strong>Error:</strong> ' + errorThrown + '</p>' +
                    '<p><strong>Response:</strong></p>' +
                    '<pre>' + jqXHR.responseText + '</pre>'
                );
            }
        });
    });
    
    // Test Checkout AJAX
    $('#test-checkout-ajax').on('click', function() {
        const $results = $('#ajax-results');
        $results.html('<p style="color: blue;">🔄 Test checkout completo in corso...</p>');
        
        $.ajax({
            url: dreamtourData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'drtr_process_checkout',
                nonce: dreamtourData.nonce,
                tour_id: 13,
                adults: 1,
                children: 0,
                first_name: 'Test',
                last_name: 'Debug',
                email: 'test@debug.com',
                phone: '1234567890',
                payment_method: 'bank_transfer',
                payment_type: 'deposit',
                subtotal: 80,
                deposit: 40,
                total: 40
            },
            success: function(response) {
                $results.html(
                    '<p style="color: green; font-weight: bold;">✓ CHECKOUT SUCCESS!</p>' +
                    '<pre>' + JSON.stringify(response, null, 2) + '</pre>'
                );
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $results.html(
                    '<p style="color: red; font-weight: bold;">✗ CHECKOUT ERROR!</p>' +
                    '<p><strong>Status:</strong> ' + jqXHR.status + '</p>' +
                    '<p><strong>Ready State:</strong> ' + jqXHR.readyState + '</p>' +
                    '<p><strong>Text Status:</strong> ' + textStatus + '</p>' +
                    '<p><strong>Error:</strong> ' + errorThrown + '</p>' +
                    '<p><strong>Response Text:</strong></p>' +
                    '<pre>' + (jqXHR.responseText || 'EMPTY') + '</pre>' +
                    '<p><strong>Response Headers:</strong></p>' +
                    '<pre>' + jqXHR.getAllResponseHeaders() + '</pre>'
                );
            },
            complete: function(jqXHR, textStatus) {
                console.log('Complete - Status:', textStatus);
                console.log('Complete - Response:', jqXHR.responseText);
            }
        });
    });
    
    // Clear results
    $('#clear-results').on('click', function() {
        $('#ajax-results').html('<p style="color: #999;">I risultati dei test appariranno qui...</p>');
    });
    
    // Clear debug log
    $('#clear-debug-log').on('click', function() {
        if (confirm('Sei sicuro di voler cancellare il log?')) {
            $.post(dreamtourData.ajaxUrl, {
                action: 'drtr_clear_debug_log'
            }, function(response) {
                if (response.success) {
                    location.reload();
                }
            });
        }
    });
});
</script>
