/**
 * Seat Selection JavaScript
 */

(function($) {
    'use strict';
    
    let selectedSeats = [];
    let occupiedSeats = [];
    let maxSeats = 0;
    let tourId = 0;
    
    $(document).ready(function() {
        const container = $('.drtr-seats-grid');
        
        if (!container.length) {
            return;
        }
        
        tourId = container.data('tour-id');
        maxSeats = parseInt(container.data('num-seats'));
        
        loadAvailableSeats();
        
        // Seat click handler
        $(document).on('click', '.seat:not(.occupied)', function() {
            const seat = $(this);
            const seatNumber = seat.data('seat');
            const row = seat.data('row');
            const position = seat.data('position');
            
            if (seat.hasClass('selected')) {
                // Deselect
                deselectSeat(seatNumber);
                seat.removeClass('selected');
            } else {
                // Select if not at max
                if (selectedSeats.length < maxSeats) {
                    selectSeat(seatNumber, row, position);
                    seat.addClass('selected');
                } else {
                    alert(drtrPosti.strings.selectSeats + ': ' + maxSeats);
                }
            }
            
            updateSelectedSeatsList();
            updateConfirmButton();
        });
        
        // Confirm button
        $('#confirm-seats').on('click', function() {
            confirmSeats();
        });
    });
    
    function loadAvailableSeats() {
        $.ajax({
            url: drtrPosti.ajaxUrl,
            type: 'POST',
            data: {
                action: 'drtr_get_available_seats',
                nonce: drtrPosti.nonce,
                tour_id: tourId
            },
            success: function(response) {
                if (response.success) {
                    occupiedSeats = response.data.occupied_seats || [];
                    markOccupiedSeats();
                }
            }
        });
    }
    
    function markOccupiedSeats() {
        occupiedSeats.forEach(function(seat) {
            const seatEl = $('.seat[data-seat="' + seat.seat_number + '"]');
            seatEl.addClass('occupied');
            seatEl.attr('title', 'Occupato da: ' + seat.passenger_name);
        });
    }
    
    function selectSeat(seatNumber, row, position) {
        selectedSeats.push({
            seat_number: seatNumber,
            row_number: row,
            position: position,
            passenger_name: ''
        });
    }
    
    function deselectSeat(seatNumber) {
        selectedSeats = selectedSeats.filter(function(seat) {
            return seat.seat_number !== seatNumber;
        });
    }
    
    function updateSelectedSeatsList() {
        const list = $('#selected-seats-list');
        list.empty();
        
        if (selectedSeats.length === 0) {
            list.html('<p style="color: #666;">Nessun posto selezionato</p>');
            return;
        }
        
        selectedSeats.forEach(function(seat, index) {
            const item = $('<div class="selected-seat-item"></div>');
            item.html(`
                <div class="seat-label">Posto ${seat.seat_number}</div>
                <input 
                    type="text" 
                    class="passenger-name-input" 
                    data-seat="${seat.seat_number}"
                    placeholder="${drtrPosti.strings.passengerName} ${index + 1}"
                    value="${seat.passenger_name}"
                >
            `);
            list.append(item);
        });
        
        // Update passenger names on input
        $('.passenger-name-input').on('input', function() {
            const seatNumber = $(this).data('seat');
            const name = $(this).val();
            
            const seat = selectedSeats.find(s => s.seat_number === seatNumber);
            if (seat) {
                seat.passenger_name = name;
            }
        });
    }
    
    function updateConfirmButton() {
        const btn = $('#confirm-seats');
        
        if (selectedSeats.length === maxSeats) {
            btn.prop('disabled', false);
        } else {
            btn.prop('disabled', true);
        }
    }
    
    function confirmSeats() {
        // Validate all names are filled
        let allNamesFilled = true;
        selectedSeats.forEach(function(seat) {
            if (!seat.passenger_name || seat.passenger_name.trim() === '') {
                allNamesFilled = false;
            }
        });
        
        if (!allNamesFilled) {
            alert('Inserisci il nome di tutti i passeggeri');
            return;
        }
        
        const token = $('#confirm-seats').data('token');
        const btn = $('#confirm-seats');
        
        btn.prop('disabled', true).text('Prenotazione in corso...');
        
        $.ajax({
            url: drtrPosti.ajaxUrl,
            type: 'POST',
            data: {
                action: 'drtr_reserve_seats',
                nonce: drtrPosti.nonce,
                token: token,
                seats: selectedSeats
            },
            success: function(response) {
                if (response.success) {
                    showSuccessMessage(response.data.message);
                    
                    // Disable all interactions
                    $('.seat').addClass('occupied').off('click');
                    btn.remove();
                    
                    setTimeout(function() {
                        window.location.href = '/area-riservata';
                    }, 3000);
                } else {
                    showErrorMessage(response.data.message);
                    btn.prop('disabled', false).text(drtrPosti.strings.confirm);
                }
            },
            error: function() {
                showErrorMessage('Errore di connessione. Riprova.');
                btn.prop('disabled', false).text(drtrPosti.strings.confirm);
            }
        });
    }
    
    function showSuccessMessage(message) {
        const msg = $('<div class="drtr-success-message"></div>').text(message);
        $('.drtr-posti-container').prepend(msg);
    }
    
    function showErrorMessage(message) {
        const msg = $('<div class="drtr-error-message"></div>').text(message);
        $('.drtr-posti-container').prepend(msg);
        
        setTimeout(function() {
            msg.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }
    
})(jQuery);
