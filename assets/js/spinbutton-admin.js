/**
 * WCAG Spinbutton Admin JavaScript
 * 
 * Funzionalità JavaScript per l'interfaccia admin del componente Spinbutton
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Inizializzazione quando il documento è pronto
    $(document).ready(function() {
        initSpinbuttonAdmin();
    });

    /**
     * Inizializza le funzionalità admin del componente Spinbutton
     */
    function initSpinbuttonAdmin() {
        // Copy shortcode functionality
        setupCopyShortcode();
        
        // Live preview updates
        setupLivePreview();
        
        // Form validation
        setupFormValidation();
        
        // Auto-save functionality
        setupAutoSave();
        
        // Accessibility checklist interaction
        setupAccessibilityChecklist();
    }

    /**
     * Setup copy shortcode functionality
     */
    function setupCopyShortcode() {
        $(document).on('click', '.wcag-wp-copy-shortcode', function(e) {
            e.preventDefault();
            
            const shortcode = $(this).data('shortcode');
            const button = $(this);
            const originalText = button.text();
            
            // Copy to clipboard
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(shortcode).then(function() {
                    showSuccessMessage(button, 'Shortcode copiato!');
                }).catch(function() {
                    fallbackCopyTextToClipboard(shortcode, button);
                });
            } else {
                fallbackCopyTextToClipboard(shortcode, button);
            }
        });
    }

    /**
     * Fallback copy method for older browsers
     */
    function fallbackCopyTextToClipboard(text, button) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.top = '0';
        textArea.style.left = '0';
        textArea.style.position = 'fixed';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showSuccessMessage(button, 'Shortcode copiato!');
            } else {
                showErrorMessage(button, 'Errore nella copia');
            }
        } catch (err) {
            showErrorMessage(button, 'Errore nella copia');
        }
        
        document.body.removeChild(textArea);
    }

    /**
     * Show success message
     */
    function showSuccessMessage(button, message) {
        const originalText = button.text();
        button.text(message).addClass('button-primary');
        
        setTimeout(function() {
            button.text(originalText).removeClass('button-primary');
        }, 2000);
    }

    /**
     * Show error message
     */
    function showErrorMessage(button, message) {
        const originalText = button.text();
        button.text(message).addClass('button-secondary');
        
        setTimeout(function() {
            button.text(originalText).removeClass('button-secondary');
        }, 2000);
    }

    /**
     * Setup live preview updates
     */
    function setupLivePreview() {
        const previewArea = $('.wcag-wp-preview-area');
        if (previewArea.length === 0) return;

        // Update preview when form fields change
        $('input, select, textarea').on('change keyup', function() {
            updatePreview();
        });

        // Initial preview update
        updatePreview();
    }

    /**
     * Update the live preview
     */
    function updatePreview() {
        const previewArea = $('.wcag-wp-preview-area');
        if (previewArea.length === 0) return;

        // Collect form data
        const formData = {
            label: $('#wcag_spinbutton_config\\[label\\]').val() || 'Spinbutton Label',
            description: $('#wcag_spinbutton_config\\[description\\]').val() || 'Descrizione del campo',
            min: $('#wcag_spinbutton_config\\[min\\]').val() || '0',
            max: $('#wcag_spinbutton_config\\[max\\]').val() || '100',
            step: $('#wcag_spinbutton_config\\[step\\]').val() || '1',
            default_value: $('#wcag_spinbutton_config\\[default_value\\]').val() || '50',
            unit: $('#wcag_spinbutton_config\\[unit\\]').val() || '',
            size: $('#wcag_spinbutton_config\\[size\\]').val() || 'medium',
            required: $('#wcag_spinbutton_config\\[required\\]').is(':checked'),
            disabled: $('#wcag_spinbutton_config\\[disabled\\]').is(':checked')
        };

        // Generate preview HTML
        const previewHTML = generatePreviewHTML(formData);
        previewArea.html(previewHTML);
    }

    /**
     * Generate preview HTML
     */
    function generatePreviewHTML(data) {
        const requiredMark = data.required ? '<span class="wcag-wp-spinbutton__required" aria-label="Campo obbligatorio">*</span>' : '';
        const disabledAttr = data.disabled ? 'disabled' : '';
        const sizeClass = data.size !== 'medium' ? `wcag-wp-spinbutton__input--${data.size}` : '';
        const unitDisplay = data.unit ? `<span class="wcag-wp-spinbutton__unit">${data.unit}</span>` : '';

        return `
            <h4>Anteprima Spinbutton</h4>
            <div class="wcag-wp-spinbutton">
                <label class="wcag-wp-spinbutton__label">
                    ${data.label}${requiredMark}
                </label>
                ${data.description ? `<div class="wcag-wp-spinbutton__description">${data.description}</div>` : ''}
                <div class="wcag-wp-spinbutton__container">
                    <input type="number" 
                           class="wcag-wp-spinbutton__input ${sizeClass}" 
                           value="${data.default_value}"
                           min="${data.min}" 
                           max="${data.max}" 
                           step="${data.step}"
                           ${disabledAttr}
                           aria-describedby="spinbutton-description">
                    <button type="button" class="wcag-wp-spinbutton__increment" aria-label="Incrementa">▲</button>
                    <button type="button" class="wcag-wp-spinbutton__decrement" aria-label="Decrementa">▼</button>
                    ${unitDisplay}
                </div>
            </div>
        `;
    }

    /**
     * Setup form validation
     */
    function setupFormValidation() {
        $('#post').on('submit', function(e) {
            const errors = validateForm();
            
            if (errors.length > 0) {
                e.preventDefault();
                showValidationErrors(errors);
            }
        });
    }

    /**
     * Validate form fields
     */
    function validateForm() {
        const errors = [];
        
        // Check min/max values
        const min = parseFloat($('#wcag_spinbutton_config\\[min\\]').val());
        const max = parseFloat($('#wcag_spinbutton_config\\[max\\]').val());
        const defaultValue = parseFloat($('#wcag_spinbutton_config\\[default_value\\]').val());
        
        if (min >= max) {
            errors.push('Il valore minimo deve essere inferiore al valore massimo');
        }
        
        if (defaultValue < min || defaultValue > max) {
            errors.push('Il valore di default deve essere compreso tra min e max');
        }
        
        return errors;
    }

    /**
     * Show validation errors
     */
    function showValidationErrors(errors) {
        let errorHTML = '<div class="wcag-wp-error-message"><strong>Errori di validazione:</strong><ul>';
        errors.forEach(function(error) {
            errorHTML += `<li>${error}</li>`;
        });
        errorHTML += '</ul></div>';
        
        $('.wcag-wp-error-message').remove();
        $('#post').prepend(errorHTML);
        $('.wcag-wp-error-message').show();
        
        // Scroll to top
        $('html, body').animate({
            scrollTop: 0
        }, 500);
    }

    /**
     * Setup auto-save functionality
     */
    function setupAutoSave() {
        let autoSaveTimer;
        
        $('input, select, textarea').on('change', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                // Trigger auto-save (WordPress handles this)
                $('#post').trigger('submit');
            }, 3000);
        });
    }

    /**
     * Setup accessibility checklist interaction
     */
    function setupAccessibilityChecklist() {
        $('.wcag-wp-accessibility-checklist input[type="checkbox"]').on('change', function() {
            const checklist = $(this).closest('.wcag-wp-accessibility-checklist');
            const totalItems = checklist.find('input[type="checkbox"]').length;
            const checkedItems = checklist.find('input[type="checkbox"]:checked').length;
            
            // Update progress
            const progress = Math.round((checkedItems / totalItems) * 100);
            checklist.find('.wcag-wp-checklist-progress').text(`${checkedItems}/${totalItems} (${progress}%)`);
            
            // Update progress bar
            checklist.find('.wcag-wp-checklist-progress-bar').css('width', progress + '%');
        });
    }

})(jQuery);
