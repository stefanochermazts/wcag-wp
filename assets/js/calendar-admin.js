'use strict';

(function($) {
    'use strict';

    // Calendar Admin Management
    class WCAGCalendarAdmin {
        constructor() {
            this.container = $('#wcag-wp-calendar-events-container');
            this.template = $('#wcag-wp-event-template').html();
            this.eventIndex = this.container.find('.wcag-wp-event-item').length;
            
            this.init();
        }

        init() {
            this.bindEvents();
            this.initSortable();
            this.initDatePickers();
        }

        bindEvents() {
            // Add new event
            $('#wcag-wp-add-event').on('click', () => this.addEvent());

            // Delete event
            this.container.on('click', '.wcag-wp-event-delete', (e) => {
                e.preventDefault();
                this.deleteEvent($(e.currentTarget).closest('.wcag-wp-event-item'));
            });

            // Toggle event content
            this.container.on('click', '.wcag-wp-event-toggle', (e) => {
                e.preventDefault();
                this.toggleEvent($(e.currentTarget));
            });

            // Copy shortcode
            $('.wcag-wp-copy-shortcode').on('click', (e) => {
                e.preventDefault();
                this.copyShortcode($(e.currentTarget));
            });

            // All day toggle
            this.container.on('change', '.wcag-wp-all-day-toggle', (e) => {
                this.toggleTimeFields($(e.currentTarget));
            });

            // Update event title on input
            this.container.on('input', 'input[name*="[title]"]', (e) => {
                this.updateEventTitle($(e.currentTarget));
            });

            // Update event date display
            this.container.on('change', 'input[name*="[start_date]"]', (e) => {
                this.updateEventDate($(e.currentTarget));
            });
        }

        initSortable() {
            this.container.sortable({
                handle: '.wcag-wp-event-handle',
                placeholder: 'wcag-wp-event-placeholder',
                update: () => this.updateEventIndexes()
            });
        }

        initDatePickers() {
            // Initialize date pickers for existing events
            this.container.find('.wcag-wp-date-picker').each((_, element) => {
                this.initDatePicker($(element));
            });
        }

        initDatePicker(element) {
            // Set min date to today
            const today = new Date().toISOString().split('T')[0];
            element.attr('min', today);
            
            // Add change event for validation
            element.on('change', (e) => {
                this.validateDateRange($(e.currentTarget));
            });
        }

        validateDateRange(startDateElement) {
            const eventItem = startDateElement.closest('.wcag-wp-event-item');
            const startDate = startDateElement.val();
            const endDateElement = eventItem.find('input[name*="[end_date]"]');
            const endDate = endDateElement.val();

            if (startDate && endDate && startDate > endDate) {
                endDateElement.val(startDate);
                this.updateEventDate(endDateElement);
            }
        }

        addEvent() {
            const newEvent = this.template.replace(/\{\{index\}\}/g, this.eventIndex);
            this.container.append(newEvent);
            
            // Update event index
            this.eventIndex++;
            
            // Update all event indexes
            this.updateEventIndexes();
            
            // Initialize date picker for new event
            const newEventElement = this.container.find('.wcag-wp-event-item').last();
            newEventElement.find('.wcag-wp-date-picker').each((_, element) => {
                this.initDatePicker($(element));
            });
            
            // Scroll to new event
            newEventElement[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        deleteEvent(eventElement) {
            if (confirm(wcag_wp_calendar.strings.confirm_delete_event)) {
                eventElement.fadeOut(300, () => {
                    eventElement.remove();
                    this.updateEventIndexes();
                });
            }
        }

        toggleEvent(button) {
            const eventItem = button.closest('.wcag-wp-event-item');
            const content = eventItem.find('.wcag-wp-event-content');
            const icon = button.find('.dashicons');
            const isExpanded = button.attr('aria-expanded') === 'true';

            if (isExpanded) {
                content.slideUp(300);
                icon.removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
                button.attr('aria-expanded', 'false');
            } else {
                content.slideDown(300);
                icon.removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
                button.attr('aria-expanded', 'true');
            }
        }

        toggleTimeFields(checkbox) {
            const eventItem = checkbox.closest('.wcag-wp-event-item');
            const timeFields = eventItem.find('.wcag-wp-time-fields');
            
            if (checkbox.is(':checked')) {
                timeFields.slideUp(200);
                timeFields.find('input').prop('disabled', true);
            } else {
                timeFields.slideDown(200);
                timeFields.find('input').prop('disabled', false);
            }
        }

        updateEventIndexes() {
            this.container.find('.wcag-wp-event-item').each((index, element) => {
                const $element = $(element);
                
                // Update data attribute
                $element.attr('data-event-index', index);
                $element.data('event-index', index);
                
                // Update form field names
                $element.find('input, textarea, select').each((_, field) => {
                    const $field = $(field);
                    const name = $field.attr('name');
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        $field.attr('name', newName);
                    }
                });
                
                // Update event title
                const titleInput = $element.find('input[name*="[title]"]');
                if (titleInput.length) {
                    this.updateEventTitle(titleInput);
                }
            });
        }

        updateEventTitle(input) {
            const eventItem = input.closest('.wcag-wp-event-item');
            const title = input.val().trim();
            const eventIndex = eventItem.data('event-index');
            const titleElement = eventItem.find('h4');
            
            if (title) {
                titleElement.text(title);
            } else {
                titleElement.text(wcag_wp_calendar.strings.add_event);
            }
        }

        updateEventDate(input) {
            const eventItem = input.closest('.wcag-wp-event-item');
            const date = input.val();
            const dateElement = eventItem.find('.wcag-wp-event-date');
            
            if (date) {
                const formattedDate = this.formatDate(date);
                dateElement.text(formattedDate);
            } else {
                dateElement.text(wcag_wp_calendar.strings.select_date);
            }
        }

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('it-IT', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        copyShortcode(button) {
            const shortcode = button.data('shortcode');
            
            // Create temporary textarea
            const textarea = document.createElement('textarea');
            textarea.value = shortcode;
            document.body.appendChild(textarea);
            textarea.select();
            
            try {
                document.execCommand('copy');
                
                // Show success feedback
                const originalText = button.html();
                button.html('<span class="dashicons dashicons-yes"></span> Copiato!');
                
                setTimeout(() => {
                    button.html(originalText);
                }, 2000);
                
            } catch (err) {
                console.error('Errore durante la copia:', err);
            }
            
            document.body.removeChild(textarea);
        }
    }

    // Initialize when DOM is ready
    $(document).ready(() => {
        if ($('#wcag-wp-calendar-events-container').length) {
            new WCAGCalendarAdmin();
        }
    });

})(jQuery);
