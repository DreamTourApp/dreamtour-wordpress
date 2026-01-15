/**
 * JavaScript para DRTR Reserved Area
 * Manejo de login AJAX
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Manejar envío del formulario de login
        $('#drtr-ra-login-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            var $messageContainer = $('#drtr-ra-message');
            
            // Obtener datos del formulario
            var formData = {
                action: 'drtr_ra_login',
                username: $('#drtr-username').val(),
                password: $('#drtr-password').val(),
                remember: $('#drtr-remember').is(':checked') ? 1 : 0,
                nonce: drtrRA.nonce
            };
            
            // Validar campos
            if (!formData.username || !formData.password) {
                showMessage('error', drtrRA.strings.required_fields);
                return;
            }
            
            // Deshabilitar botón
            $submitBtn.prop('disabled', true).html('<i class="dashicons dashicons-update"></i> Caricamento...');
            
            // Enviar petición AJAX
            $.ajax({
                url: drtrRA.ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showMessage('success', response.data.message);
                        
                        // Redirigir después de 1 segundo
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 1000);
                    } else {
                        showMessage('error', response.data.message);
                        $submitBtn.prop('disabled', false).html('Accedi <i class="dashicons dashicons-arrow-right-alt2"></i>');
                    }
                },
                error: function() {
                    showMessage('error', drtrRA.strings.login_error);
                    $submitBtn.prop('disabled', false).html('Accedi <i class="dashicons dashicons-arrow-right-alt2"></i>');
                }
            });
        });
        
        /**
         * Mostrar mensajes
         */
        function showMessage(type, message) {
            var alertClass = type === 'error' ? 'drtr-ra-alert-error' : 'drtr-ra-alert-success';
            var html = '<div class="drtr-ra-alert ' + alertClass + '">' + message + '</div>';
            
            $('#drtr-ra-message').html(html);
            
            // Auto-ocultar después de 5 segundos si es éxito
            if (type === 'success') {
                setTimeout(function() {
                    $('#drtr-ra-message').fadeOut(function() {
                        $(this).html('').show();
                    });
                }, 5000);
            }
        }
        
        /**
         * Toggle sezione prenotazioni
         */
        $('.drtr-show-bookings').on('click', function(e) {
            e.preventDefault();
            $('.drtr-ra-cards').fadeOut(300, function() {
                $('#drtr-ra-bookings-section').fadeIn(300);
            });
        });
        
        $('.drtr-hide-bookings').on('click', function(e) {
            e.preventDefault();
            $('#drtr-ra-bookings-section').fadeOut(300, function() {
                $('.drtr-ra-cards').fadeIn(300);
            });
        });
        
        /**
         * Toggle sezione admin prenotazioni
         */
        $('.drtr-show-admin-bookings').on('click', function(e) {
            e.preventDefault();
            $('.drtr-ra-cards').fadeOut(300, function() {
                $('#drtr-ra-admin-bookings-section').fadeIn(300);
            });
        });
        
        $('.drtr-hide-admin-bookings').on('click', function(e) {
            e.preventDefault();
            $('#drtr-ra-admin-bookings-section').fadeOut(300, function() {
                $('.drtr-ra-cards').fadeIn(300);
            });
        });
        
        /**
         * Aggiornare stato prenotazione
         */
        $(document).on('click', '.drtr-update-status', function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var bookingId = $btn.data('booking-id');
            var $select = $('.drtr-status-select[data-booking-id="' + bookingId + '"]');
            var newStatus = $select.val();
            var originalText = $btn.html();
            
            // Disabilitare bottone
            $btn.prop('disabled', true).html('<i class="dashicons dashicons-update spin"></i> Aggiornamento...');
            
            // Inviare richiesta AJAX
            $.ajax({
                url: drtrRA.ajaxurl,
                type: 'POST',
                data: {
                    action: 'drtr_ra_update_booking_status',
                    booking_id: bookingId,
                    status: newStatus,
                    nonce: drtrRA.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Mostrare messaggio successo
                        var $row = $btn.closest('tr');
                        $row.css('background-color', '#d4edda');
                        
                        setTimeout(function() {
                            $row.css('background-color', '');
                        }, 2000);
                        
                        // Ripristinare bottone
                        $btn.prop('disabled', false).html(originalText);
                        
                        // Mostrare notifica
                        showAdminMessage('success', response.data.message);
                    } else {
                        showAdminMessage('error', response.data.message);
                        $btn.prop('disabled', false).html(originalText);
                    }
                },
                error: function() {
                    showAdminMessage('error', 'Errore durante l\'aggiornamento.');
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        });
        
        /**
         * Mostrare messaggio admin
         */
        function showAdminMessage(type, message) {
            var alertClass = type === 'error' ? 'drtr-ra-alert-error' : 'drtr-ra-alert-success';
            var $alert = $('<div class="drtr-ra-alert ' + alertClass + ' drtr-admin-alert">' + message + '</div>');
            
            $('#drtr-ra-admin-bookings-section').prepend($alert);
            
            setTimeout(function() {
                $alert.fadeOut(function() {
                    $(this).remove();
                });
            }, 3000);
        }
        
    });
    
})(jQuery);
