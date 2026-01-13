/**
 * DRTR Tours Manager - Frontend JavaScript
 */

(function($) {
    'use strict';
    
    const DrtrToursManager = {
        currentPage: 1,
        searchQuery: '',
        
        init: function() {
            this.bindEvents();
            this.loadTours();
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
            
            // Editar tour (delegado)
            $(document).on('click', '.drtr-edit-tour', function() {
                const tourId = $(this).data('tour-id');
                self.editTour(tourId);
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
                tbody.html('<tr><td colspan="8" class="drtr-no-tours">No se encontraron tours</td></tr>');
                return;
            }
            
            tours.forEach(function(tour) {
                const transportBadge = tour.transport_type ? 
                    `<span class="drtr-badge drtr-badge-${tour.transport_type}">${tour.transport_type}</span>` : '-';
                
                const row = `
                    <tr>
                        <td>${tour.id}</td>
                        <td><strong>${tour.title}</strong></td>
                        <td>${tour.price ? '€' + parseFloat(tour.price).toFixed(2) : '-'}</td>
                        <td>${tour.duration ? tour.duration + ' días' : '-'}</td>
                        <td>${transportBadge}</td>
                        <td>${tour.location || '-'}</td>
                        <td>${tour.start_date || '-'}</td>
                        <td>
                            <button class="drtr-btn drtr-btn-edit drtr-edit-tour" data-tour-id="${tour.id}">
                                <span class="dashicons dashicons-edit"></span> Editar
                            </button>
                            <button class="drtr-btn drtr-btn-danger drtr-delete-tour" data-tour-id="${tour.id}">
                                <span class="dashicons dashicons-trash"></span> Eliminar
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
            $('#drtr-tour-modal').fadeIn();
        },
        
        closeModal: function() {
            $('#drtr-tour-modal').fadeOut();
        },
        
        editTour: function(tourId) {
            const self = this;
            
            $.ajax({
                url: drtrAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'drtr_get_tour',
                    nonce: drtrAjax.nonce,
                    tour_id: tourId
                },
                success: function(response) {
                    if (response.success) {
                        const tour = response.data;
                        
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
                        
                        self.openModal('Editar Tour');
                    } else {
                        self.showMessage(response.data.message || drtrAjax.strings.error, 'error');
                    }
                },
                error: function() {
                    self.showMessage(drtrAjax.strings.error, 'error');
                }
            });
        },
        
        saveTour: function() {
            const self = this;
            const formData = $('#drtr-tour-form').serialize();
            
            $.ajax({
                url: drtrAjax.ajaxurl,
                type: 'POST',
                data: formData + '&action=drtr_save_tour&nonce=' + drtrAjax.nonce,
                success: function(response) {
                    if (response.success) {
                        self.showMessage(drtrAjax.strings.success_save, 'success');
                        self.closeModal();
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
        }
    };
    
    // Inicializar cuando el DOM esté listo
    $(document).ready(function() {
        if ($('#drtr-tours-manager').length) {
            DrtrToursManager.init();
        }
    });
    
})(jQuery);
