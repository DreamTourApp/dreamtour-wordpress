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
        
    });
    
})(jQuery);
