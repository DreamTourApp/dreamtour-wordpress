/**
 * Travel Intent Filter - Homepage
 */
(function($) {
    'use strict';
    
    $(document).ready(function() {
        const $toggle = $('#filter-intents-toggle');
        const $dropdown = $('#filter-intents-dropdown');
        const $count = $('#filter-count');
        const $checkboxes = $('.filter-intent-checkbox');
        
        // Toggle dropdown
        $toggle.on('click', function(e) {
            e.stopPropagation();
            $dropdown.toggle();
            $toggle.toggleClass('active');
        });
        
        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.filter-group-intents').length) {
                $dropdown.hide();
                $toggle.removeClass('active');
            }
        });
        
        // Prevent dropdown from closing when clicking inside
        $dropdown.on('click', function(e) {
            e.stopPropagation();
        });
        
        // Update count and filter tours
        $checkboxes.on('change', function() {
            updateCount();
            filterTours();
        });
        
        function updateCount() {
            const count = $checkboxes.filter(':checked').length;
            if (count > 0) {
                $count.text(count).show();
            } else {
                $count.hide();
            }
        }
        
        function filterTours() {
            const selectedIntents = [];
            $checkboxes.filter(':checked').each(function() {
                selectedIntents.push($(this).val());
            });
            
            // Get other filter values
            const transport = $('#filter-transport').val();
            const duration = $('#filter-duration').val();
            
            // Filter tours
            $('.tour-card').each(function() {
                let show = true;
                
                // Filter by intents (OR logic)
                if (selectedIntents.length > 0) {
                    const tourIntents = $(this).data('intents');
                    if (tourIntents) {
                        const tourIntentsArray = tourIntents.toString().split(',');
                        const hasMatch = selectedIntents.some(intent => 
                            tourIntentsArray.includes(intent)
                        );
                        show = show && hasMatch;
                    } else {
                        show = false;
                    }
                }
                
                // Filter by transport
                if (transport && show) {
                    const tourTransport = $(this).data('transport');
                    show = show && tourTransport === transport;
                }
                
                // Filter by duration
                if (duration && show) {
                    const tourDuration = parseInt($(this).data('duration'));
                    if (duration === '1-3') {
                        show = show && tourDuration >= 1 && tourDuration <= 3;
                    } else if (duration === '4-7') {
                        show = show && tourDuration >= 4 && tourDuration <= 7;
                    } else if (duration === '8-14') {
                        show = show && tourDuration >= 8 && tourDuration <= 14;
                    } else if (duration === '15+') {
                        show = show && tourDuration >= 15;
                    }
                }
                
                $(this).toggle(show);
            });
            
            // Show "no results" message if no tours visible
            const $visibleTours = $('.tour-card:visible');
            if ($visibleTours.length === 0) {
                if (!$('#no-tours-message').length) {
                    $('#tours-container').append(
                        '<div id="no-tours-message" class="no-content"><p>No hay tours que coincidan con tus criterios de búsqueda.</p></div>'
                    );
                }
            } else {
                $('#no-tours-message').remove();
            }
        }
        
        // Reset filters
        $('#filter-reset').on('click', function() {
            $checkboxes.prop('checked', false);
            $('#filter-transport').val('');
            $('#filter-duration').val('');
            updateCount();
            $('.tour-card').show();
            $('#no-tours-message').remove();
        });
        
        // Connect existing filters to new system
        $('#filter-transport, #filter-duration').on('change', filterTours);
    });
    
})(jQuery);
