/**
 * DRTR Tours Manager - Frontend JavaScript
 */

(function($) {
    'use strict';
    
    console.log('DRTR Frontend JS loaded');
    
    const DrtrToursManager = {
        currentPage: 1,
        searchQuery: '',
        
        init: function() {
            console.log('DrtrToursManager.init() called');
            this.bindEvents();
            
            // Solo cargar tours si no estamos en modo edición ni en modo nuevo tour
            const urlParams = new URLSearchParams(window.location.search);
            if (!urlParams.has('edit_tour') && !urlParams.has('new_tour')) {
                this.loadTours();
            }
            
            this.checkEditMode();
        },
        
        checkEditMode: function() {
            // Verificar si hay parámetro edit_tour en URL
            const urlParams = new URLSearchParams(window.location.search);
            const editTourId = urlParams.get('edit_tour');
            
            console.log('checkEditMode called, URL params:', window.location.search);
            console.log('editTourId:', editTourId);
            
            if (editTourId) {
                console.log('Edit mode detected, loading tour:', editTourId);
                // Cargar datos del tour en la página de edición
                this.loadTourData(editTourId);
            } else {
                console.log('Not in edit mode');
            }
        },
        
        bindEvents: function() {
            const self = this;
            
            // Añadir nuevo tour
            $('#drtr-add-tour-btn').on('click', function() {
                self.openModal();
            });
            
            // Cerrar modal
            $('.drtr-modal-close, .drtr-modal-cancel').on('click', function() {
                self.closeModal();
            });
            
            // Cerrar modal al hacer clic fuera
            $(window).on('click', function(e) {
                if ($(e.target).is('#drtr-tour-modal')) {
                    self.closeModal();
                }
            });
            
            // Vista previa de imagen
            $('#drtr-tour-image').on('change', function(e) {
                self.previewImage(e.target);
            });
            
            // Eliminar imagen preview
            $(document).on('click', '.drtr-remove-image', function(e) {
                e.preventDefault();
                self.removeImagePreview();
            });
            
            // Agregar parada de itinerario (delegado)
            $(document).on('click', '#drtr-add-itinerary-stop', function(e) {
                e.preventDefault();
                console.log('Botón agregar parada clickeado');
                self.addItineraryStop();
            });
            
            // Eliminar parada de itinerario
            $(document).on('click', '.drtr-remove-stop', function() {
                $(this).closest('.drtr-itinerary-stop').remove();
                self.updateItineraryJSON();
            });
            
            // Actualizar JSON al cambiar valores
            $(document).on('change', '.drtr-itinerary-stop input, .drtr-itinerary-stop select, .drtr-itinerary-stop textarea', function() {
                self.updateItineraryJSON();
            });
            
            // Buscar
            $('#drtr-search-btn').on('click', function() {
                self.searchQuery = $('#drtr-search-input').val();
                self.currentPage = 1;
                self.loadTours();
            });
            
            // Buscar al presionar Enter
            $('#drtr-search-input').on('keypress', function(e) {
                if (e.which === 13) {
                    $('#drtr-search-btn').click();
                }
            });
            
            // Guardar tour
            $('#drtr-tour-form').on('submit', function(e) {
                e.preventDefault();
                self.saveTour();
            });
            
            // Editar tour (delegado) - Redirigir a página de edición
            $(document).on('click', '.drtr-edit-tour', function(e) {
                e.preventDefault();
                const tourId = $(this).data('tour-id');
                console.log('Edit button clicked, tour ID:', tourId);
                // Redirigir a la página de edición
                window.location.href = window.location.pathname + '?edit_tour=' + tourId;
            });
            
            // Eliminar tour (delegado)
            $(document).on('click', '.drtr-delete-tour', function() {
                const tourId = $(this).data('tour-id');
                self.deleteTour(tourId);
            });
        },
        
        loadTours: function() {
            const self = this;
            
            $('#drtr-tours-tbody').html('<tr><td colspan="8" class="drtr-loading"><span class="spinner is-active"></span> Cargando tours...</td></tr>');
            
            $.ajax({
                url: drtrAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'drtr_get_tours',
                    nonce: drtrAjax.nonce,
                    page: self.currentPage,
                    search: self.searchQuery
                },
                success: function(response) {
                    if (response.success) {
                        self.renderTours(response.data.tours);
                        self.renderPagination(response.data.current_page, response.data.total_pages);
                    } else {
                        self.showMessage(response.data.message || drtrAjax.strings.error, 'error');
                    }
                },
                error: function() {
                    self.showMessage(drtrAjax.strings.error, 'error');
                }
            });
        },
        
        renderTours: function(tours) {
            const tbody = $('#drtr-tours-tbody');
            tbody.empty();
            
            if (tours.length === 0) {
                tbody.html('<tr><td colspan="9" class="drtr-no-tours">No se encontraron tours</td></tr>');
                return;
            }
            
            tours.forEach(function(tour) {
                const imageThumb = tour.image_url ? 
                    `<img src="${tour.image_url}" alt="${tour.title}" class="drtr-tour-thumb">` : 
                    '<span class="dashicons dashicons-format-image drtr-no-image"></span>';
                
                const row = `
                    <tr>
                        <td class="drtr-image-cell">${imageThumb}</td>
                        <td>${tour.id}</td>
                        <td><strong>${tour.title}</strong></td>
                        <td>${tour.price ? '€' + parseFloat(tour.price).toFixed(2) : '-'}</td>
                        <td>${tour.duration ? tour.duration + ' días' : '-'}</td>
                        <td>${tour.location || '-'}</td>
                        <td>${tour.start_date || '-'}</td>
                        <td>
                            <button class="drtr-btn drtr-btn-edit drtr-edit-tour" data-tour-id="${tour.id}">
                                <span class="dashicons dashicons-edit"></span> ${drtrAjax.strings.edit_button}
                            </button>
                            <button class="drtr-btn drtr-btn-danger drtr-delete-tour" data-tour-id="${tour.id}">
                                <span class="dashicons dashicons-trash"></span> ${drtrAjax.strings.delete_button}
                            </button>
                        </td>
                    </tr>
                `;
                
                tbody.append(row);
            });
        },
        
        renderPagination: function(currentPage, totalPages) {
            const self = this;
            const pagination = $('#drtr-pagination');
            pagination.empty();
            
            if (totalPages <= 1) return;
            
            // Botón anterior
            const prevBtn = $('<button>')
                .text('« Anterior')
                .prop('disabled', currentPage === 1)
                .on('click', function() {
                    if (currentPage > 1) {
                        self.currentPage = currentPage - 1;
                        self.loadTours();
                    }
                });
            
            pagination.append(prevBtn);
            
            // Páginas
            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = $('<button>')
                    .text(i)
                    .toggleClass('active', i === currentPage)
                    .on('click', function() {
                        self.currentPage = i;
                        self.loadTours();
                    });
                
                pagination.append(pageBtn);
            }
            
            // Botón siguiente
            const nextBtn = $('<button>')
                .text('Siguiente »')
                .prop('disabled', currentPage === totalPages)
                .on('click', function() {
                    if (currentPage < totalPages) {
                        self.currentPage = currentPage + 1;
                        self.loadTours();
                    }
                });
            
            pagination.append(nextBtn);
        },
        
        openModal: function(title) {
            $('#drtr-modal-title').text(title || 'Añadir Tour');
            $('#drtr-tour-form')[0].reset();
            $('#drtr-tour-id').val('');
            this.removeImagePreview();
            $('#drtr-itinerary-container').empty();
            $('#drtr-tour-itinerary').val('');
            $('#drtr-tour-modal').fadeIn();
        },
        
        closeModal: function() {
            $('#drtr-tour-modal').fadeOut();
            this.removeImagePreview();
            $('#drtr-itinerary-container').empty();
            
            // Rimuovere parametro edit_tour dall'URL se presente
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('edit_tour')) {
                console.log('Removing edit_tour parameter from URL');
                // Reindirizzare alla pagina senza parametri
                window.location.href = window.location.pathname;
            }
        },
        
        loadTourData: function(tourId) {
            const self = this;
            console.log('Loading tour data for editing, ID:', tourId);
            
            $.ajax({
                url: drtrAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'drtr_get_tour',
                    nonce: drtrAjax.nonce,
                    tour_id: tourId
                },
                success: function(response) {
                    console.log('Tour data loaded:', response);
                    console.log('response.success:', response.success);
                    console.log('response.data:', response.data);
                    
                    if (response.success && response.data) {
                        const tour = response.data;
                        console.log('Tour object:', tour);
                        
                        // Rellenar campos del formulario
                        $('#drtr-tour-id').val(tour.id);
                        $('#drtr-tour-title').val(tour.title);
                        $('#drtr-tour-content').val(tour.content);
                        $('#drtr-tour-excerpt').val(tour.excerpt);
                        $('#drtr-tour-price').val(tour.price);
                        $('#drtr-tour-duration').val(tour.duration);
                        $('#drtr-tour-location').val(tour.location);
                        $('#drtr-tour-start-date').val(tour.start_date);
                        $('#drtr-tour-end-date').val(tour.end_date);
                        $('#drtr-tour-transport').val(tour.transport_type);
                        $('#drtr-tour-max-people').val(tour.max_people);
                        $('#drtr-tour-includes').val(tour.includes);
                        $('#drtr-tour-not-includes').val(tour.not_includes);
                        
                        console.log('Form fields populated successfully');
                        console.log('Content loaded:', tour.content ? tour.content.substring(0, 50) + '...' : 'empty');
                        console.log('Excerpt loaded:', tour.excerpt ? tour.excerpt.substring(0, 50) + '...' : 'empty');
                        
                        // Cargar imagen si existe
                        if (tour.image_url) {
                            $('#drtr-tour-image-id').val(tour.image_id);
                            $('#drtr-image-preview img').attr('src', tour.image_url);
                            $('#drtr-image-preview').show();
                            $('#drtr-tour-image').hide();
                        }
                        
                        // Cargar itinerario
                        self.loadItinerary(tour.itinerary);
                        
                        // Cargar Travel Intents si están disponibles
                        if (tour.travel_intents && Array.isArray(tour.travel_intents)) {
                            tour.travel_intents.forEach(function(intentId) {
                                $('input[name="travel_intents[]"][value="' + intentId + '"]').prop('checked', true);
                            });
                        }
                        
                        console.log('Tour data loaded and form populated!');
                    } else {
                        console.error('Condition failed - response.success:', response.success, 'response.data:', response.data);
                        self.showMessage('Error al cargar el tour', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading tour:', status, error);
                    self.showMessage('Error al cargar el tour', 'error');
                }
            });
        },
        
        editTour: function(tourId) {
            const self = this;
            console.log('editTour function called with ID:', tourId);
            
            $.ajax({
                url: drtrAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'drtr_get_tour',
                    nonce: drtrAjax.nonce,
                    tour_id: tourId
                },
                beforeSend: function() {
                    console.log('AJAX request starting for tour:', tourId);
                },
                success: function(response) {
                    console.log('AJAX response received:', response);
                    if (response.success) {
                        const tour = response.data;
                        console.log('Tour data:', tour);
                        
                        $('#drtr-tour-id').val(tour.id);
                        $('#drtr-tour-title').val(tour.title);
                        $('#drtr-tour-content').val(tour.content);
                        $('#drtr-tour-excerpt').val(tour.excerpt);
                        $('#drtr-tour-price').val(tour.price);
                        $('#drtr-tour-duration').val(tour.duration);
                        $('#drtr-tour-transport').val(tour.transport_type);
                        $('#drtr-tour-max-people').val(tour.max_people);
                        $('#drtr-tour-start-date').val(tour.start_date);
                        $('#drtr-tour-end-date').val(tour.end_date);
                        $('#drtr-tour-location').val(tour.location);
                        $('#drtr-tour-includes').val(tour.includes);
                        $('#drtr-tour-not-includes').val(tour.not_includes);
                        
                        console.log('Form fields populated');
                        
                        // Mostrar imagen si existe
                        if (tour.image_url) {
                            console.log('Loading image:', tour.image_url);
                            $('#drtr-tour-image-id').val(tour.image_id);
                            $('#drtr-image-preview img').attr('src', tour.image_url);
                            $('#drtr-image-preview').show();
                            $('#drtr-tour-image').hide();
                        }
                        
                        // Cargar itinerario
                        console.log('Loading itinerary:', tour.itinerary);
                        self.loadItinerary(tour.itinerary);
                        
                        self.openModal(drtrAjax.strings.edit_tour);
                        console.log('Modal opened');
                    } else {
                        console.error('Response error:', response.data);
                        self.showMessage(response.data.message || drtrAjax.strings.error, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error, xhr);
                    self.showMessage(drtrAjax.strings.error, 'error');
                }
            });
        },
        
        saveTour: function() {
            const self = this;
            const form = document.getElementById('drtr-tour-form');
            const formData = new FormData(form);
            
            // Agregar action y nonce
            formData.append('action', 'drtr_save_tour');
            formData.append('nonce', drtrAjax.nonce);
            
            // Verificar si estamos en modo edición
            const isEditMode = new URLSearchParams(window.location.search).has('edit_tour');
            
            $.ajax({
                url: drtrAjax.ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        console.log('Save successful:', response);
                        if (response.data.debug) {
                            console.log('Debug info:', response.data.debug);
                        }
                        self.showMessage(drtrAjax.strings.success_save, 'success');
                        
                        // Si estamos en modo edición, redirigir a la lista
                        if (isEditMode) {
                            setTimeout(function() {
                                window.location.href = window.location.pathname;
                            }, 1500);
                        } else {
                            self.closeModal();
                            self.loadTours();
                        }
                    } else {
                        self.showMessage(response.data.message || drtrAjax.strings.error, 'error');
                    }
                },
                error: function() {
                    self.showMessage(drtrAjax.strings.error, 'error');
                }
            });
        },
        
        deleteTour: function(tourId) {
            const self = this;
            
            if (!confirm(drtrAjax.strings.confirm_delete)) {
                return;
            }
            
            $.ajax({
                url: drtrAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'drtr_delete_tour',
                    nonce: drtrAjax.nonce,
                    tour_id: tourId
                },
                success: function(response) {
                    if (response.success) {
                        self.showMessage(drtrAjax.strings.success_delete, 'success');
                        self.loadTours();
                    } else {
                        self.showMessage(response.data.message || drtrAjax.strings.error, 'error');
                    }
                },
                error: function() {
                    self.showMessage(drtrAjax.strings.error, 'error');
                }
            });
        },
        
        showMessage: function(message, type) {
            const messageDiv = $('#drtr-message');
            messageDiv
                .removeClass('success error')
                .addClass(type)
                .text(message)
                .fadeIn();
            
            setTimeout(function() {
                messageDiv.fadeOut();
            }, 3000);
        },
        
        previewImage: function(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    $('#drtr-image-preview img').attr('src', e.target.result);
                    $('#drtr-image-preview').fadeIn();
                    $('#drtr-tour-image').hide();
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        },
        
        removeImagePreview: function() {
            $('#drtr-image-preview').fadeOut();
            $('#drtr-image-preview img').attr('src', '');
            $('#drtr-tour-image').val('').show();
            $('#drtr-tour-image-id').val('');
        },
        
        addItineraryStop: function(data) {
            console.log('addItineraryStop llamado', data);
            data = data || {};
            const stopIndex = $('.drtr-itinerary-stop').length;
            const strings = drtrAjax.strings;
            const stopHtml = `
                <div class="drtr-itinerary-stop" data-index="${stopIndex}">
                    <div class="drtr-stop-header">
                        <span class="drtr-stop-number">${stopIndex + 1}</span>
                        <button type="button" class="drtr-remove-stop">
                            <span class="dashicons dashicons-no-alt"></span>
                        </button>
                    </div>
                    <div class="drtr-stop-fields">
                        <div class="drtr-stop-field">
                            <label>${strings.itinerary_place}</label>
                            <input type="text" class="drtr-stop-name" placeholder="${strings.itinerary_place_placeholder}" value="${data.name || ''}">
                        </div>
                        <div class="drtr-stop-field">
                            <label>${strings.itinerary_type}</label>
                            <select class="drtr-stop-icon">
                                <option value="city" ${data.icon === 'city' ? 'selected' : ''}>🏙️ ${strings.type_city}</option>
                                <option value="train" ${data.icon === 'train' ? 'selected' : ''}>🚂 ${strings.type_train}</option>
                                <option value="bus" ${data.icon === 'bus' ? 'selected' : ''}>🚌 ${strings.type_bus}</option>
                                <option value="plane" ${data.icon === 'plane' ? 'selected' : ''}>✈️ ${strings.type_plane}</option>
                                <option value="boat" ${data.icon === 'boat' ? 'selected' : ''}>🚢 ${strings.type_boat}</option>
                                <option value="hotel" ${data.icon === 'hotel' ? 'selected' : ''}>🏨 ${strings.type_hotel}</option>
                                <option value="visit" ${data.icon === 'visit' ? 'selected' : ''}>👁️ ${strings.type_visit}</option>
                                <option value="food" ${data.icon === 'food' ? 'selected' : ''}>🍽️ ${strings.type_food}</option>
                                <option value="activity" ${data.icon === 'activity' ? 'selected' : ''}>🎯 ${strings.type_activity}</option>
                            </select>
                        </div>
                        <div class="drtr-stop-field">
                            <label>${strings.itinerary_arrival}</label>
                            <input type="datetime-local" class="drtr-stop-arrival" value="${data.arrival || ''}">
                        </div>
                        <div class="drtr-stop-field">
                            <label>${strings.itinerary_departure}</label>
                            <input type="datetime-local" class="drtr-stop-departure" value="${data.departure || ''}">
                        </div>
                        <div class="drtr-stop-field drtr-stop-field-full">
                            <label>${strings.itinerary_notes}</label>
                            <textarea class="drtr-stop-notes" rows="2" placeholder="${strings.itinerary_notes_placeholder}">${data.notes || ''}</textarea>
                        </div>
                    </div>
                </div>
            `;
            
            console.log('Añadiendo parada al contenedor');
            $('#drtr-itinerary-container').append(stopHtml);
            this.updateItineraryJSON();
        },
        
        updateItineraryJSON: function() {
            const stops = [];
            $('.drtr-itinerary-stop').each(function(index) {
                const $stop = $(this);
                stops.push({
                    order: index + 1,
                    name: $stop.find('.drtr-stop-name').val(),
                    icon: $stop.find('.drtr-stop-icon').val(),
                    arrival: $stop.find('.drtr-stop-arrival').val(),
                    departure: $stop.find('.drtr-stop-departure').val(),
                    notes: $stop.find('.drtr-stop-notes').val()
                });
            });
            
            $('#drtr-tour-itinerary').val(JSON.stringify(stops));
        },
        
        loadItinerary: function(itineraryJSON) {
            $('#drtr-itinerary-container').empty();
            
            if (itineraryJSON) {
                try {
                    const stops = JSON.parse(itineraryJSON);
                    stops.forEach(stop => this.addItineraryStop(stop));
                } catch(e) {
                    console.error('Error parsing itinerary:', e);
                }
            }
        }
    };
    
    // Inicializar cuando el DOM esté listo
    $(document).ready(function() {
        console.log('Document ready');
        console.log('#drtr-tours-manager exists:', $('#drtr-tours-manager').length > 0);
        console.log('#drtr-edit-tour-page exists:', $('#drtr-edit-tour-page').length > 0);
        console.log('#drtr-new-tour-page exists:', $('#drtr-new-tour-page').length > 0);
        
        if ($('#drtr-tours-manager').length || $('#drtr-edit-tour-page').length || $('#drtr-new-tour-page').length) {
            console.log('Initializing DrtrToursManager');
            DrtrToursManager.init();
        } else {
            console.log('No DRTR container found, skipping initialization');
        }
    });
    
})(jQuery);
