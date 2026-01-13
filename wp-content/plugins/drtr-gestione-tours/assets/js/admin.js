/**
 * JavaScript para el área de administración de DRTR Tours
 * Manejo del Media Uploader de WordPress
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        var mediaUploader;
        
        // Abrir Media Uploader
        $('.drtr-upload-image-btn').on('click', function(e) {
            e.preventDefault();
            
            // Si el uploader ya existe, abrirlo
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            // Crear nuevo Media Uploader
            mediaUploader = wp.media({
                title: 'Seleziona Locandina del Tour',
                button: {
                    text: 'Usa questa immagine'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });
            
            // Cuando se selecciona una imagen
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                
                // Actualizar campo hidden con ID de imagen
                $('#drtr_image_id').val(attachment.id);
                
                // Mostrar vista previa
                $('.drtr-admin-image-preview img').attr('src', attachment.url);
                $('.drtr-admin-image-preview').show();
                $('.drtr-remove-image-btn').show();
            });
            
            // Abrir el uploader
            mediaUploader.open();
        });
        
        // Eliminar imagen
        $('.drtr-remove-image-btn').on('click', function(e) {
            e.preventDefault();
            
            $('#drtr_image_id').val('');
            $('.drtr-admin-image-preview img').attr('src', '');
            $('.drtr-admin-image-preview').hide();
            $(this).hide();
        });
        
    });
    
})(jQuery);
