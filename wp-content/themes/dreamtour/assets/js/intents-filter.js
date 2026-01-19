/**
 * Split Travel Intent and Month Filters - Homepage
 */
(function($) {
    'use strict';
    
    $(document).ready(function() {
        const $experiencesToggle = $('#filter-experiences-toggle');
        const $monthsToggle = $('#filter-months-toggle');
        const $experiencesDropdown = $('#filter-experiences-dropdown');
        const $monthsDropdown = $('#filter-months-dropdown');
        const $experiencesCount = $('#filter-experiences-count');
        const $monthsCount = $('#filter-months-count');
        const $experienceCheckboxes = $('.filter-experience-checkbox');
        const $monthCheckboxes = $('.filter-month-checkbox');
        
        // Check for URL filter parameter on page load
        const urlParams = new URLSearchParams(window.location.search);
        const filterParam = urlParams.get('filter');
        if (filterParam) {
            // Check the corresponding checkbox
            const $checkbox = $('.filter-experience-checkbox[value="' + filterParam + '"]');
            if ($checkbox.length) {
                $checkbox.prop('checked', true);
                updateExperiencesCount();
                filterTours();
            }
        }
        
        // Toggle experiences dropdown
        $experiencesToggle.on('click', function(e) {
            e.stopPropagation();
            $experiencesDropdown.toggle();
            $monthsDropdown.hide(); // Close the other one
            $experiencesToggle.toggleClass('active');
            $monthsToggle.removeClass('active');
        });
        
        // Toggle months dropdown
        $monthsToggle.on('click', function(e) {
            e.stopPropagation();
            $monthsDropdown.toggle();
            $experiencesDropdown.hide(); // Close the other one
            $monthsToggle.toggleClass('active');
            $experiencesToggle.removeClass('active');
        });
        
        // Close dropdowns when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.filter-group-intents').length) {
                $experiencesDropdown.hide();
                $monthsDropdown.hide();
                $experiencesToggle.removeClass('active');
                $monthsToggle.removeClass('active');
            }
        });
        
        // Prevent dropdowns from closing when clicking inside
        $experiencesDropdown.on('click', function(e) {
            e.stopPropagation();
        });
        
        $monthsDropdown.on('click', function(e) {
            e.stopPropagation();
        });
        
        // Update count and filter tours for experiences
        $experienceCheckboxes.on('change', function() {
            updateExperiencesCount();
            filterTours();
        });
        
        // Update count and filter tours for months
        $monthCheckboxes.on('change', function() {
            updateMonthsCount();
            filterTours();
        });
        
        function updateExperiencesCount() {
            const count = $experienceCheckboxes.filter(':checked').length;
            if (count > 0) {
                $experiencesCount.text(count).removeAttr('style');
            } else {
                $experiencesCount.attr('style', 'display:none');
            }
        }
        
        function updateMonthsCount() {
            const count = $monthCheckboxes.filter(':checked').length;
            if (count > 0) {
                $monthsCount.text(count).removeAttr('style');
            } else {
                $monthsCount.attr('style', 'display:none');
            }
        }
        
        function filterTours() {
            const selectedExperiences = [];
            const selectedMonths = [];
            
            $experienceCheckboxes.filter(':checked').each(function() {
                selectedExperiences.push($(this).val());
            });
            
            $monthCheckboxes.filter(':checked').each(function() {
                selectedMonths.push($(this).val());
            });
            
            const allSelected = [...selectedExperiences, ...selectedMonths];
            
            // Get other filter values
            const transport = $('#filter-transport').val();
            const duration = $('#filter-duration').val();
            
            // Filter tours
            $('.tour-card').each(function() {
                let show = true;
                
                // Filter by intents (OR logic combining experiences and months)
                if (allSelected.length > 0) {
                    const tourIntents = $(this).data('intents');
                    if (tourIntents) {
                        const tourIntentsArray = tourIntents.toString().split(',').map(s => s.trim());
                        const hasMatch = allSelected.some(intent => 
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
                    const noResultsText = typeof dreamtourFilters !== 'undefined' ? dreamtourFilters.noResults : 'No hay tours que coincidan con tus criterios de búsqueda.';
                    $('#tours-container').append(
                        '<div id="no-tours-message" class="no-content"><p>' + noResultsText + '</p></div>'
                    );
                }
            } else {
                $('#no-tours-message').remove();
            }
        }
        
        // Reset filters
        $('#filter-reset').on('click', function() {
            $experienceCheckboxes.prop('checked', false);
            $monthCheckboxes.prop('checked', false);
            $('#filter-transport').val('');
            $('#filter-duration').val('');
            updateExperiencesCount();
            updateMonthsCount();
            $('.tour-card').show();
            $('#no-tours-message').remove();
            $experiencesDropdown.hide();
            $monthsDropdown.hide();
        });
        
        // Connect existing filters to new system
        $('#filter-transport, #filter-duration').on('change', filterTours);
    });
    
})(jQuery);
