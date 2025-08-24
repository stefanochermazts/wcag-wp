(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Copy shortcode functionality
        $('.copy-shortcode').on('click', function() {
            const target = $(this).data('clipboard-target');
            const textToCopy = $(target).val();
            
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(function() {
                    const originalText = $(this).text();
                    $(this).text('Copiato!').addClass('copied');
                    setTimeout(() => {
                        $(this).text(originalText).removeClass('copied');
                    }, 2000);
                }.bind(this));
            } else {
                // Fallback per browser non sicuri
                $(target).select();
                document.execCommand('copy');
                const originalText = $(this).text();
                $(this).text('Copiato!').addClass('copied');
                setTimeout(() => {
                    $(this).text(originalText).removeClass('copied');
                }, 2000);
            }
        });
        
        // Live preview update
        function updatePreview() {
            const label = $('#wcag_slider_label').val();
            const description = $('#wcag_slider_description').val();
            const min = $('#wcag_slider_min').val();
            const max = $('#wcag_slider_max').val();
            const defaultValue = $('#wcag_slider_default_value').val();
            const unit = $('#wcag_slider_unit').val();
            const orientation = $('#wcag_slider_orientation').val();
            const size = $('#wcag_slider_size').val();
            const theme = $('#wcag_slider_theme').val();
            const showValue = $('#wcag_slider_config\\[show_value\\]').is(':checked');
            const showTicks = $('#wcag_slider_config\\[show_ticks\\]').is(':checked');
            const showTooltip = $('#wcag_slider_config\\[show_tooltip\\]').is(':checked');
            
            const preview = $('#wcag-slider-preview .wcag-wp-slider');
            
            // Update label
            const labelElement = preview.find('.wcag-wp-slider__label');
            if (label) {
                if (labelElement.length) {
                    labelElement.text(label);
                } else {
                    preview.prepend(`<label class="wcag-wp-slider__label">${label}</label>`);
                }
            } else {
                labelElement.remove();
            }
            
            // Update description
            const descElement = preview.find('.wcag-wp-slider__description');
            if (description) {
                if (descElement.length) {
                    descElement.text(description);
                } else {
                    preview.append(`<div class="wcag-wp-slider__description">${description}</div>`);
                }
            } else {
                descElement.remove();
            }
            
            // Update slider attributes
            const thumb = preview.find('.wcag-wp-slider__thumb');
            thumb.attr('aria-valuemin', min);
            thumb.attr('aria-valuemax', max);
            thumb.attr('aria-valuenow', defaultValue);
            thumb.attr('aria-valuetext', defaultValue + (unit ? ' ' + unit : ''));
            
            // Update value display
            const valueElement = preview.find('.wcag-wp-slider__value-text');
            if (showValue) {
                if (valueElement.length) {
                    valueElement.text(defaultValue + (unit ? ' ' + unit : ''));
                } else {
                    preview.find('.wcag-wp-slider__container').append(`
                        <div class="wcag-wp-slider__value">
                            <span class="wcag-wp-slider__value-text">${defaultValue}${unit ? ' ' + unit : ''}</span>
                        </div>
                    `);
                }
            } else {
                preview.find('.wcag-wp-slider__value').remove();
            }
            
            // Update classes
            preview.removeClass('wcag-wp-slider--horizontal wcag-wp-slider--vertical')
                  .removeClass('wcag-wp-slider--small wcag-wp-slider--medium wcag-wp-slider--large')
                  .removeClass('wcag-wp-slider--default wcag-wp-slider--blue wcag-wp-slider--green wcag-wp-slider--purple wcag-wp-slider--orange')
                  .addClass(`wcag-wp-slider--${orientation} wcag-wp-slider--${size} wcag-wp-slider--${theme}`);
            
            // Update config summary
            updateConfigSummary();
        }
        
        // Update config summary
        function updateConfigSummary() {
            const label = $('#wcag_slider_label').val();
            const min = $('#wcag_slider_min').val();
            const max = $('#wcag_slider_max').val();
            const step = $('#wcag_slider_step').val();
            const defaultValue = $('#wcag_slider_default_value').val();
            const unit = $('#wcag_slider_unit').val();
            const orientation = $('#wcag_slider_orientation').val();
            const size = $('#wcag_slider_size').val();
            const theme = $('#wcag_slider_theme').val();
            const required = $('#wcag_slider_config\\[required\\]').is(':checked');
            const disabled = $('#wcag_slider_config\\[disabled\\]').is(':checked');
            
            const summary = $('.config-summary ul');
            summary.empty();
            
            if (label) summary.append(`<li><strong>Etichetta:</strong> ${label}</li>`);
            summary.append(`<li><strong>Range:</strong> ${min} - ${max} (step: ${step})</li>`);
            summary.append(`<li><strong>Valore di default:</strong> ${defaultValue}${unit ? ' ' + unit : ''}</li>`);
            summary.append(`<li><strong>Orientamento:</strong> ${orientation}</li>`);
            summary.append(`<li><strong>Dimensione:</strong> ${size}</li>`);
            summary.append(`<li><strong>Tema:</strong> ${theme}</li>`);
            summary.append(`<li><strong>Obbligatorio:</strong> ${required ? 'Sì' : 'No'}</li>`);
            summary.append(`<li><strong>Disabilitato:</strong> ${disabled ? 'Sì' : 'No'}</li>`);
        }
        
        // Bind events for live preview
        $('#wcag_slider_label, #wcag_slider_description, #wcag_slider_min, #wcag_slider_max, #wcag_slider_step, #wcag_slider_default_value, #wcag_slider_unit, #wcag_slider_orientation, #wcag_slider_size, #wcag_slider_theme').on('input change', updatePreview);
        $('#wcag_slider_config\\[show_value\\], #wcag_slider_config\\[show_ticks\\], #wcag_slider_config\\[show_tooltip\\], #wcag_slider_config\\[required\\], #wcag_slider_config\\[disabled\\]').on('change', updatePreview);
        
        // Initial update
        updatePreview();
        
        // Form validation
        $('form#post').on('submit', function(e) {
            const min = parseFloat($('#wcag_slider_min').val());
            const max = parseFloat($('#wcag_slider_max').val());
            const defaultValue = parseFloat($('#wcag_slider_default_value').val());
            const step = parseFloat($('#wcag_slider_step').val());
            
            let hasError = false;
            
            // Clear previous errors
            $('.wcag-error').remove();
            
            // Validate min/max
            if (min >= max) {
                $('#wcag_slider_max').after('<div class="wcag-error" style="color: red; font-size: 12px;">Il valore massimo deve essere maggiore del valore minimo</div>');
                hasError = true;
            }
            
            // Validate default value
            if (defaultValue < min || defaultValue > max) {
                $('#wcag_slider_default_value').after('<div class="wcag-error" style="color: red; font-size: 12px;">Il valore di default deve essere compreso tra min e max</div>');
                hasError = true;
            }
            
            // Validate step
            if (step <= 0) {
                $('#wcag_slider_step').after('<div class="wcag-error" style="color: red; font-size: 12px;">Lo step deve essere maggiore di zero</div>');
                hasError = true;
            }
            
            if (hasError) {
                e.preventDefault();
                alert('Ci sono errori nel form. Controlla i campi evidenziati.');
            }
        });
        
        // Auto-save draft functionality
        let autoSaveTimer;
        const autoSaveDelay = 30000; // 30 seconds
        
        function setupAutoSave() {
            const form = $('form#post');
            const titleField = $('#title');
            const contentField = $('#content');
            
            function autoSave() {
                if (titleField.val() || contentField.val()) {
                    const formData = new FormData(form[0]);
                    formData.append('action', 'autosave');
                    formData.append('post_type', 'wcag_slider');
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                console.log('Auto-save completato');
                            }
                        }
                    });
                }
            }
            
            // Auto-save on input
            titleField.add(contentField).on('input', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(autoSave, autoSaveDelay);
            });
        }
        
        setupAutoSave();
        
        // Accessibility testing checklist
        $('.testing-checklist li').on('click', function() {
            $(this).toggleClass('completed');
        });
        
        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // Ctrl+S per salvare
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                $('#publish').click();
            }
            
            // Ctrl+Shift+P per preview
            if (e.ctrlKey && e.shiftKey && e.key === 'P') {
                e.preventDefault();
                $('#preview-action').click();
            }
        });
        
        // Enhanced form field descriptions
        $('.description').each(function() {
            const $this = $(this);
            const text = $this.text();
            
            // Add icons for different types of descriptions
            if (text.includes('obbligatorio') || text.includes('required')) {
                $this.prepend('<span style="color: #d63638;">⚠️ </span>');
            } else if (text.includes('accessibilità') || text.includes('accessibility')) {
                $this.prepend('<span style="color: #00a32a;">♿ </span>');
            } else if (text.includes('WCAG') || text.includes('ARIA')) {
                $this.prepend('<span style="color: #2271b1;">🎯 </span>');
            }
        });
        
        // Real-time character count for text areas
        $('textarea').each(function() {
            const $this = $(this);
            const maxLength = $this.attr('maxlength');
            
            if (maxLength) {
                const counter = $('<div class="char-counter" style="font-size: 12px; color: #666; text-align: right; margin-top: 5px;"></div>');
                $this.after(counter);
                
                function updateCounter() {
                    const current = $this.val().length;
                    const remaining = maxLength - current;
                    counter.text(`${current}/${maxLength} caratteri`);
                    
                    if (remaining < 10) {
                        counter.css('color', '#d63638');
                    } else if (remaining < 50) {
                        counter.css('color', '#dba617');
                    } else {
                        counter.css('color', '#666');
                    }
                }
                
                $this.on('input', updateCounter);
                updateCounter();
            }
        });
        
        // Enhanced number input validation
        $('input[type="number"]').on('input', function() {
            const $this = $(this);
            const value = parseFloat($this.val());
            const min = parseFloat($this.attr('min'));
            const max = parseFloat($this.attr('max'));
            
            if (!isNaN(min) && value < min) {
                $this.css('border-color', '#d63638');
            } else if (!isNaN(max) && value > max) {
                $this.css('border-color', '#d63638');
            } else {
                $this.css('border-color', '');
            }
        });
        
        // Tooltip for complex fields
        $('.form-table th').each(function() {
            const $this = $(this);
            const text = $this.text();
            
            // Add tooltips for specific fields
            if (text.includes('ARIA') || text.includes('aria')) {
                $this.append('<span style="margin-left: 5px; color: #666; cursor: help;" title="Attributi ARIA per l\'accessibilità degli screen reader">ℹ️</span>');
            } else if (text.includes('Tema') || text.includes('theme')) {
                $this.append('<span style="margin-left: 5px; color: #666; cursor: help;" title="Stile visivo del componente">🎨</span>');
            } else if (text.includes('Orientamento') || text.includes('orientation')) {
                $this.append('<span style="margin-left: 5px; color: #666; cursor: help;" title="Direzione del slider (orizzontale o verticale)">↔️</span>');
            }
        });
    });
    
})(jQuery);
