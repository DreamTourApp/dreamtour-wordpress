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
        
        // ========================================================================
        // PROFILE PAGE
        // ========================================================================
        
        // Update Profile
        $('#drtr-profile-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            
            var formData = $form.serialize() + '&action=drtr_update_profile';
            
            $submitBtn.prop('disabled', true).html('<i class="dashicons dashicons-update"></i> Salvataggio...');
            
            $.ajax({
                url: drtrRA.ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showProfileMessage('success', response.data.message);
                    } else {
                        showProfileMessage('error', response.data.message);
                    }
                },
                error: function() {
                    showProfileMessage('error', 'Errore di connessione. Riprova.');
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).html('<i class="dashicons dashicons-saved"></i> Salva Modifiche');
                }
            });
        });
        
        // Update Password
        $('#drtr-password-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            
            var newPassword = $('#new_password').val();
            var confirmPassword = $('#confirm_password').val();
            
            if (newPassword !== confirmPassword) {
                showProfileMessage('error', 'Le password non coincidono.');
                return;
            }
            
            if (newPassword.length < 8) {
                showProfileMessage('error', 'La password deve essere di almeno 8 caratteri.');
                return;
            }
            
            var formData = $form.serialize() + '&action=drtr_update_password';
            
            $submitBtn.prop('disabled', true).html('<i class="dashicons dashicons-update"></i> Modifica...');
            
            $.ajax({
                url: drtrRA.ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showProfileMessage('success', response.data.message);
                        $form[0].reset();
                    } else {
                        showProfileMessage('error', response.data.message);
                    }
                },
                error: function() {
                    showProfileMessage('error', 'Errore di connessione. Riprova.');
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).html('<i class="dashicons dashicons-shield-alt"></i> Cambia Password');
                }
            });
        });
        
        // Update Preferences
        $('#drtr-preferences-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            
            var formData = $form.serialize() + '&action=drtr_update_profile';
            
            $submitBtn.prop('disabled', true).html('<i class="dashicons dashicons-update"></i> Salvataggio...');
            
            $.ajax({
                url: drtrRA.ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showProfileMessage('success', response.data.message);
                    } else {
                        showProfileMessage('error', response.data.message);
                    }
                },
                error: function() {
                    showProfileMessage('error', 'Errore di connessione. Riprova.');
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).html('<i class="dashicons dashicons-saved"></i> Salva Preferenze');
                }
            });
        });
        
        // Export Data
        $('#drtr-export-data').on('click', function() {
            var $btn = $(this);
            
            $btn.prop('disabled', true).html('<i class="dashicons dashicons-update"></i> Esportazione...');
            
            // Create a form and submit it
            var form = $('<form>', {
                action: drtrRA.ajaxurl,
                method: 'POST'
            });
            
            form.append($('<input>', {
                type: 'hidden',
                name: 'action',
                value: 'drtr_export_data'
            }));
            
            form.append($('<input>', {
                type: 'hidden',
                name: 'nonce',
                value: drtrRA.nonce
            }));
            
            $('body').append(form);
            form.submit();
            
            setTimeout(function() {
                $btn.prop('disabled', false).html('<i class="dashicons dashicons-download"></i> Esporta i Miei Dati');
                form.remove();
            }, 2000);
        });
        
        // Delete Account - Open Modal
        $('#drtr-delete-account').on('click', function() {
            $('#drtr-delete-modal').fadeIn(300);
            $('body').css('overflow', 'hidden');
        });
        
        // Close Modal
        $('.drtr-modal-close, .drtr-modal-overlay').on('click', function() {
            $('#drtr-delete-modal').fadeOut(300);
            $('body').css('overflow', '');
            $('#drtr-confirm-delete-form')[0].reset();
        });
        
        // Confirm Delete Account
        $('#drtr-confirm-delete-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            
            if (!confirm('Sei assolutamente sicuro? Questa azione è irreversibile!')) {
                return;
            }
            
            var formData = $form.serialize() + '&action=drtr_delete_account';
            
            $submitBtn.prop('disabled', true).html('<i class="dashicons dashicons-update"></i> Eliminazione...');
            
            $.ajax({
                url: drtrRA.ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showProfileMessage('success', response.data.message);
                        
                        // Redirect to home after 2 seconds
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 2000);
                    } else {
                        showProfileMessage('error', response.data.message);
                        $submitBtn.prop('disabled', false).html('<i class="dashicons dashicons-trash"></i> Elimina Definitivamente');
                    }
                },
                error: function() {
                    showProfileMessage('error', 'Errore di connessione. Riprova.');
                    $submitBtn.prop('disabled', false).html('<i class="dashicons dashicons-trash"></i> Elimina Definitivamente');
                }
            });
        });
        
        // Helper: Show profile message
        function showProfileMessage(type, message) {
            var alertClass = type === 'success' ? 'drtr-ra-alert-success' : 'drtr-ra-alert-error';
            
            var $alert = $('<div>', {
                class: 'drtr-ra-alert ' + alertClass,
                html: message
            });
            
            $('#drtr-profile-message').html($alert);
            
            // Scroll to message
            $('html, body').animate({
                scrollTop: $('#drtr-profile-message').offset().top - 100
            }, 500);
            
            setTimeout(function() {
                $alert.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }
        
        // ========================================================================
        // REGISTRATION PAGE
        // ========================================================================
        
        // Handle registration form submit
        $('#drtr-register-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            var $message = $('#drtr-register-message');
            
            // Validate passwords
            var password = $('#register-password').val();
            var confirmPassword = $('#register-password-confirm').val();
            
            if (password !== confirmPassword) {
                showRegisterMessage('error', 'Le password non coincidono.');
                return;
            }
            
            if (password.length < 8) {
                showRegisterMessage('error', 'La password deve essere di almeno 8 caratteri.');
                return;
            }
            
            var formData = $form.serialize() + '&action=drtr_register_user';
            
            $submitBtn.prop('disabled', true).html('<i class="dashicons dashicons-update"></i> Registrazione...');
            
            $.ajax({
                url: drtrRA.ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showRegisterMessage('success', response.data.message);
                        
                        // Redirect after 2 seconds
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 2000);
                    } else {
                        showRegisterMessage('error', response.data.message);
                        $submitBtn.prop('disabled', false).html('Registrati <i class="dashicons dashicons-arrow-right-alt2"></i>');
                    }
                },
                error: function() {
                    showRegisterMessage('error', 'Errore di connessione. Riprova.');
                    $submitBtn.prop('disabled', false).html('Registrati <i class="dashicons dashicons-arrow-right-alt2"></i>');
                }
            });
        });
        
        // Helper: Show register message
        function showRegisterMessage(type, message) {
            var alertClass = type === 'success' ? 'drtr-ra-alert-success' : 'drtr-ra-alert-error';
            
            var $alert = $('<div>', {
                class: 'drtr-ra-alert ' + alertClass,
                html: message
            });
            
            $('#drtr-register-message').html($alert);
            
            // Scroll to message
            $('html, body').animate({
                scrollTop: $('#drtr-register-message').offset().top - 100
            }, 500);
            
            if (type === 'success') {
                setTimeout(function() {
                    $alert.fadeOut(function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        }
        
    });
    
})(jQuery);
