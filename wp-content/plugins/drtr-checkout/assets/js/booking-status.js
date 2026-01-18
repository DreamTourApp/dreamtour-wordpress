/**
 * Booking Status Update with Toast Notifications
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Update status select color based on value
        function updateStatusSelectColor($select) {
            const selectedValue = $select.val();
            $select.attr('data-current-status', selectedValue);
        }
        
        // Initialize colors on page load
        $('.drtr-status-select').each(function() {
            updateStatusSelectColor($(this));
        });
        
        // Update color on change
        $(document).on('change', '.drtr-status-select', function() {
            updateStatusSelectColor($(this));
        });
        
        // Update status button click
        $('.drtr-update-status').on('click', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const bookingId = button.data('booking-id');
            const select = $('.drtr-status-select[data-booking-id="' + bookingId + '"]');
            const newStatus = select.val();
            const originalStatus = select.find('option:selected').data('original') || select.data('original-status');
            
            // Check if status changed
            if (!select.data('original-status')) {
                select.data('original-status', select.val());
            }
            
            if (newStatus === select.data('original-status')) {
                showToast('Nessuna modifica allo status', 'info');
                return;
            }
            
            // Confirm action
            if (!confirm(drtrBooking.strings.confirm)) {
                select.val(select.data('original-status'));
                return;
            }
            
            // Disable button
            button.prop('disabled', true).addClass('loading');
            const originalText = button.html();
            button.html('<i class="dashicons dashicons-update spin"></i> ' + drtrBooking.strings.updating);
            
            // AJAX request
            $.ajax({
                url: drtrBooking.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'drtr_update_booking_status',
                    nonce: drtrBooking.nonce,
                    booking_id: bookingId,
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        // Update original status
                        select.data('original-status', newStatus);
                        
                        // Show success message
                        let message = response.data.message;
                        if (response.data.email_sent) {
                            message += ' - ' + drtrBooking.strings.emailSent;
                        }
                        
                        showToast(message, 'success');
                        
                        // Update row styling if needed
                        const row = button.closest('tr');
                        row.addClass('status-updated').fadeOut(300).fadeIn(300);
                        
                        // Reload page after 2 seconds
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showToast(response.data.message || drtrBooking.strings.error, 'error');
                        select.val(select.data('original-status'));
                    }
                },
                error: function() {
                    showToast(drtrBooking.strings.error, 'error');
                    select.val(select.data('original-status'));
                },
                complete: function() {
                    button.prop('disabled', false).removeClass('loading');
                    button.html(originalText);
                }
            });
        });
        
        // Store original status on page load
        $('.drtr-status-select').each(function() {
            $(this).data('original-status', $(this).val());
        });
        
    });
    
    /**
     * Show toast notification
     */
    function showToast(message, type) {
        type = type || 'info';
        
        // Remove existing toasts
        $('.drtr-toast').remove();
        
        // Create toast element
        const toast = $('<div class="drtr-toast drtr-toast-' + type + '"></div>');
        
        // Icon based on type
        let icon = '';
        switch(type) {
            case 'success':
                icon = '<i class="dashicons dashicons-yes-alt"></i>';
                break;
            case 'error':
                icon = '<i class="dashicons dashicons-dismiss"></i>';
                break;
            case 'warning':
                icon = '<i class="dashicons dashicons-warning"></i>';
                break;
            default:
                icon = '<i class="dashicons dashicons-info"></i>';
        }
        
        toast.html(icon + ' <span>' + message + '</span>');
        
        // Add to body
        $('body').append(toast);
        
        // Show toast with animation
        setTimeout(function() {
            toast.addClass('show');
        }, 100);
        
        // Auto hide after 5 seconds
        setTimeout(function() {
            hideToast(toast);
        }, 5000);
        
        // Click to close
        toast.on('click', function() {
            hideToast(toast);
        });
    }
    
    /**
     * Hide toast notification
     */
    function hideToast(toast) {
        toast.removeClass('show');
        setTimeout(function() {
            toast.remove();
        }, 300);
    }
    
    // Add spinning animation for dashicons
    if (!$('style#drtr-spin-animation').length) {
        $('<style id="drtr-spin-animation">.dashicons.spin { animation: drtr-spin 1s linear infinite; } @keyframes drtr-spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }</style>').appendTo('head');
    }
    
})(jQuery);
