/**
 * WCAG Switch Component - Admin JavaScript
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Copy shortcode functionality
        $('.copy-shortcode').on('click', function() {
            const target = $(this).data('clipboard-target');
            const text = $(target).val();
            
            // Create temporary input
            const temp = $('<input>');
            $('body').append(temp);
            temp.val(text).select();
            document.execCommand('copy');
            temp.remove();
            
            // Show feedback
            const button = $(this);
            const originalText = button.text();
            button.text('Copiato!').addClass('copied');
            setTimeout(function() {
                button.text(originalText).removeClass('copied');
            }, 2000);
        });
        
        // Live preview update
        function updatePreview() {
            const label = $('#wcag_switch_label').val();
            const description = $('#wcag_switch_description').val();
            const onText = $('#wcag_switch_on_text').val();
            const offText = $('#wcag_switch_off_text').val();
            const defaultState = $('#wcag_switch_default_state').val();
            const showLabels = $('#wcag_switch_config\\[show_labels\\]').is(':checked');
            const size = $('#wcag_switch_size').val();
            const theme = $('#wcag_switch_theme').val();
            
            // Update preview
            const preview = $('#wcag-switch-preview .wcag-wp-switch');
            
            // Update label
            let labelEl = preview.find('.wcag-wp-switch__label');
            if (label) {
                if (labelEl.length) {
                    labelEl.text(label);
                } else {
                    preview.prepend('<label class="wcag-wp-switch__label">' + label + '</label>');
                }
            } else {
                labelEl.remove();
            }
            
            // Update description
            let descEl = preview.find('.wcag-wp-switch__description');
            if (description) {
                if (descEl.length) {
                    descEl.text(description);
                } else {
                    preview.append('<div class="wcag-wp-switch__description">' + description + '</div>');
                }
            } else {
                descEl.remove();
            }
            
            // Update status text
            let statusEl = preview.find('.wcag-wp-switch__status');
            if (showLabels) {
                const statusText = defaultState === 'on' ? onText : offText;
                if (statusEl.length) {
                    statusEl.text(statusText);
                } else {
                    preview.find('.wcag-wp-switch__container').append('<span class="wcag-wp-switch__status">' + statusText + '</span>');
                }
            } else {
                statusEl.remove();
            }
            
            // Update switch state
            const toggle = preview.find('.wcag-wp-switch__toggle');
            toggle.attr('aria-checked', defaultState === 'on' ? 'true' : 'false');
            if (defaultState === 'on') {
                toggle.addClass('wcag-wp-switch__toggle--active');
            } else {
                toggle.removeClass('wcag-wp-switch__toggle--active');
            }
            
            // Update classes
            preview.removeClass('wcag-wp-switch--small wcag-wp-switch--medium wcag-wp-switch--large')
                   .addClass('wcag-wp-switch--' + size);
            
            preview.removeClass('wcag-wp-switch--default wcag-wp-switch--success wcag-wp-switch--warning wcag-wp-switch--danger')
                   .addClass('wcag-wp-switch--' + theme);
        }
        
        // Bind events for live preview
        $('#wcag_switch_label, #wcag_switch_description, #wcag_switch_on_text, #wcag_switch_off_text, #wcag_switch_default_state, #wcag_switch_size, #wcag_switch_theme').on('input change', updatePreview);
        $('#wcag_switch_config\\[show_labels\\]').on('change', updatePreview);
        
        // Initial update
        updatePreview();
        
        // Form validation
        $('form#post').on('submit', function(e) {
            const label = $('#wcag_switch_label').val().trim();
            const onText = $('#wcag_switch_on_text').val().trim();
            const offText = $('#wcag_switch_off_text').val().trim();
            
            let hasError = false;
            
            // Clear previous errors
            $('.wcag-wp-error-message').remove();
            
            // Validate label
            if (!label) {
                $('#wcag_switch_label').after('<div class="wcag-wp-error-message" style="color: #d63638; font-size: 13px; margin-top: 4px;">L\'etichetta è obbligatoria</div>');
                hasError = true;
            }
            
            // Validate on text
            if (!onText) {
                $('#wcag_switch_on_text').after('<div class="wcag-wp-error-message" style="color: #d63638; font-size: 13px; margin-top: 4px;">Il testo per lo stato attivo è obbligatorio</div>');
                hasError = true;
            }
            
            // Validate off text
            if (!offText) {
                $('#wcag_switch_off_text').after('<div class="wcag-wp-error-message" style="color: #d63638; font-size: 13px; margin-top: 4px;">Il testo per lo stato inattivo è obbligatorio</div>');
                hasError = true;
            }
            
            if (hasError) {
                e.preventDefault();
                alert('Per favore correggi gli errori nel form prima di salvare.');
                return false;
            }
        });
        
        // Auto-save draft functionality
        let autoSaveTimer;
        const autoSaveDelay = 30000; // 30 seconds
        
        function setupAutoSave() {
            $('input, select, textarea').on('input change', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(function() {
                    // Trigger WordPress auto-save
                    if (typeof wp !== 'undefined' && wp.autosave) {
                        wp.autosave.server.triggerSave();
                    }
                }, autoSaveDelay);
            });
        }
        
        setupAutoSave();
        
        // Accessibility testing checklist
        $('.testing-checklist li').on('click', function() {
            const $li = $(this);
            const $checkbox = $li.find('input[type="checkbox"]');
            
            if ($checkbox.length) {
                $checkbox.prop('checked', !$checkbox.prop('checked'));
            } else {
                // Create checkbox if it doesn't exist
                const checkbox = $('<input type="checkbox" style="margin-right: 8px;">');
                $li.prepend(checkbox);
                checkbox.prop('checked', true);
            }
            
            // Update visual state
            if ($li.find('input[type="checkbox"]').prop('checked')) {
                $li.css('text-decoration', 'line-through');
                $li.css('opacity', '0.6');
            } else {
                $li.css('text-decoration', 'none');
                $li.css('opacity', '1');
            }
        });
        
        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                $('#publish').click();
            }
            
            // Ctrl/Cmd + Enter to save and preview
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                $('#post-preview').click();
            }
        });
        
        // Enhanced form field descriptions
        $('.description').each(function() {
            const $desc = $(this);
            const $field = $desc.prev('input, select, textarea');
            
            if ($field.length) {
                $field.attr('aria-describedby', $desc.attr('id') || 'desc-' + Math.random().toString(36).substr(2, 9));
            }
        });
        
        // Real-time character count for text areas
        $('textarea').each(function() {
            const $textarea = $(this);
            const $counter = $('<div class="char-counter" style="font-size: 12px; color: #646970; text-align: right; margin-top: 4px;"></div>');
            $textarea.after($counter);
            
            function updateCounter() {
                const count = $textarea.val().length;
                const maxLength = $textarea.attr('maxlength');
                let text = count + ' caratteri';
                
                if (maxLength) {
                    text += ' / ' + maxLength;
                    if (count > maxLength) {
                        $counter.css('color', '#d63638');
                    } else {
                        $counter.css('color', '#646970');
                    }
                }
                
                $counter.text(text);
            }
            
            $textarea.on('input', updateCounter);
            updateCounter();
        });
    });
    
})(jQuery);
