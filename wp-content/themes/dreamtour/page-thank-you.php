<?php
/**
 * Template Name: Grazie Prenotazione
 * 
 * @package DreamTour
 */

get_header();

$booking_id = isset($_GET['booking_id']) ? absint($_GET['booking_id']) : 0;

if ($booking_id) {
    // Ottener dettagli prenotazione
    $booking_class = DRTR_Booking::get_instance();
    $booking = $booking_class->get_booking($booking_id);
    
    if ($booking) {
        $tour = get_post($booking['tour_id']);
        ?>
        
        <div class="container thank-you-page">
            <div class="thank-you-content">
                <div class="success-icon">
                    <span class="dashicons dashicons-yes-alt"></span>
                </div>
                
                <h1><?php _e('Grazie per la tua prenotazione!', 'dreamtour'); ?></h1>
                
                <p class="lead"><?php _e('La tua prenotazione è stata ricevuta con successo.', 'dreamtour'); ?></p>
                
                <div class="booking-details-card">
                    <h2><?php _e('Dettagli Prenotazione', 'dreamtour'); ?></h2>
                    
                    <p><strong><?php _e('Numero Prenotazione:', 'dreamtour'); ?></strong> #<?php echo $booking_id; ?></p>
                    <p><strong><?php _e('Tour:', 'dreamtour'); ?></strong> <?php echo esc_html($tour->post_title); ?></p>
                    <p><strong><?php _e('Adulti:', 'dreamtour'); ?></strong> <?php echo $booking['adults']; ?></p>
                    <p><strong><?php _e('Bambini:', 'dreamtour'); ?></strong> <?php echo $booking['children']; ?></p>
                    <p><strong><?php _e('Totale:', 'dreamtour'); ?></strong> €<?php echo number_format($booking['total'], 2, ',', '.'); ?></p>
                    
                    <?php if ($booking['payment_method'] === 'bank_transfer') : ?>
                        <div class="payment-info">
                            <h3><?php _e('Istruzioni per il Pagamento', 'dreamtour'); ?></h3>
                            <p><?php _e('Abbiamo inviato i dettagli per il bonifico bancario alla tua email.', 'dreamtour'); ?></p>
                            <p><?php _e('La prenotazione sarà confermata al ricevimento del pagamento.', 'dreamtour'); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <p class="email-sent">
                        <?php printf(__('Ti abbiamo inviato una email di conferma a: %s', 'dreamtour'), '<strong>' . esc_html($booking['email']) . '</strong>'); ?>
                    </p>
                </div>
                
                <div class="actions">
                    <a href="<?php echo home_url('/tours'); ?>" class="btn btn-primary">
                        <?php _e('Scopri Altri Tour', 'dreamtour'); ?>
                    </a>
                    <a href="<?php echo home_url(); ?>" class="btn btn-secondary">
                        <?php _e('Torna alla Home', 'dreamtour'); ?>
                    </a>
                </div>
            </div>
        </div>
        
        <style>
        .thank-you-page {
            max-width: 800px;
            margin: 60px auto;
            text-align: center;
            padding: 0 20px;
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background-color: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .success-icon .dashicons {
            font-size: 60px;
            width: 60px;
            height: 60px;
            color: white;
        }
        
        .thank-you-content h1 {
            color: #003284;
            margin-bottom: 15px;
        }
        
        .lead {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
        }
        
        .booking-details-card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: left;
            margin-bottom: 30px;
        }
        
        .booking-details-card h2 {
            color: #003284;
            margin-top: 0;
            margin-bottom: 20px;
        }
        
        .booking-details-card p {
            margin: 10px 0;
        }
        
        .payment-info {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .payment-info h3 {
            margin-top: 0;
            color: #856404;
        }
        
        .email-sent {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 14px;
            color: #666;
        }
        
        .actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .actions .btn {
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: #003284;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #002266;
        }
        
        .btn-secondary {
            background-color: white;
            color: #003284;
            border: 2px solid #003284;
        }
        
        .btn-secondary:hover {
            background-color: #f8f9fa;
        }
        </style>
        
        <?php
    } else {
        echo '<div class="container"><p>' . __('Prenotazione non trovata.', 'dreamtour') . '</p></div>';
    }
} else {
    echo '<div class="container"><p>' . __('Nessuna prenotazione specificata.', 'dreamtour') . '</p></div>';
}

get_footer();
