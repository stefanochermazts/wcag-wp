/**
 * WCAG Spinbutton Frontend JavaScript
 * 
 * Accessible spinbutton implementation following WCAG 2.1 AA guidelines
 * Based on WAI-ARIA Authoring Practices Guide (APG) Spinbutton pattern
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * WCAG Spinbutton Class
     */
    class WcagSpinbutton {
        constructor(element, config = {}) {
            this.element = element;
            this.config = this.parseConfig(config);
            this.input = this.element.querySelector('.wcag-wp-spinbutton__input');
            this.incrementButton = this.element.querySelector('.wcag-wp-spinbutton__button--increment');
            this.decrementButton = this.element.querySelector('.wcag-wp-spinbutton__button--decrement');
            this.validationContainer = this.element.querySelector('.wcag-wp-spinbutton__validation');
            this.errorElement = this.element.querySelector('.wcag-wp-spinbutton__error');
            this.helpElement = this.element.querySelector('.wcag-wp-spinbutton__help');
            this.announcementsElement = this.element.querySelector('.wcag-wp-sr-only');
            
            this.currentValue = parseFloat(this.input.value) || this.config.defaultValue;
            this.isEditing = false;
            this.originalValue = null;
            
            this.init();
        }

        /**
         * Parse configuration from JSON or object
         */
        parseConfig(config) {
            if (typeof config === 'string') {
                try {
                    config = JSON.parse(config);
                } catch (e) {
                    console.error('Invalid spinbutton configuration:', e);
                    config = {};
                }
            }

            return {
                id: config.id || '',
                type: config.type || 'integer',
                min: parseFloat(config.min) || 0,
                max: parseFloat(config.max) || 100,
                step: parseFloat(config.step) || 1,
                defaultValue: parseFloat(config.defaultValue) || 0,
                unit: config.unit || '',
                format: config.format || 'number',
                required: config.required || false,
                readonly: config.readonly || false,
                disabled: config.disabled || false,
                strings: {
                    increment: config.strings?.increment || 'Incrementa',
                    decrement: config.strings?.decrement || 'Decrementa',
                    invalidValue: config.strings?.invalidValue || 'Valore non valido',
                    minValue: config.strings?.minValue || 'Valore minimo',
                    maxValue: config.strings?.maxValue || 'Valore massimo',
                    required: config.strings?.required || 'Campo obbligatorio',
                    currentValue: config.strings?.currentValue || 'Valore corrente'
                }
            };
        }

        /**
         * Initialize the spinbutton
         */
        init() {
            if (!this.input) {
                console.error('Spinbutton input not found');
                return;
            }

            this.setupEventListeners();
            this.updateARIA();
            this.validateValue(this.currentValue);
            this.updateButtons();
        }

        /**
         * Setup event listeners
         */
        setupEventListeners() {
            // Button click events
            if (this.incrementButton) {
                this.incrementButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.increment();
                });
            }

            if (this.decrementButton) {
                this.decrementButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.decrement();
                });
            }

            // Input events
            this.input.addEventListener('input', (e) => {
                this.handleInput(e);
            });

            this.input.addEventListener('change', (e) => {
                this.handleChange(e);
            });

            this.input.addEventListener('focus', (e) => {
                this.handleFocus(e);
            });

            this.input.addEventListener('blur', (e) => {
                this.handleBlur(e);
            });

            // Keyboard navigation
            this.input.addEventListener('keydown', (e) => {
                this.handleKeydown(e);
            });

            // Form validation
            this.input.addEventListener('invalid', (e) => {
                this.handleInvalid(e);
            });
        }

        /**
         * Handle input event
         */
        handleInput(e) {
            const value = parseFloat(e.target.value);
            this.currentValue = isNaN(value) ? this.config.defaultValue : value;
            this.updateARIA();
            this.validateValue(this.currentValue);
            this.updateButtons();
        }

        /**
         * Handle change event
         */
        handleChange(e) {
            const value = parseFloat(e.target.value);
            if (!isNaN(value)) {
                this.currentValue = this.clampValue(value);
                this.input.value = this.currentValue;
                this.updateARIA();
                this.validateValue(this.currentValue);
                this.updateButtons();
                this.announceValue();
            }
        }

        /**
         * Handle focus event
         */
        handleFocus(e) {
            this.isEditing = true;
            this.originalValue = this.currentValue;
            this.element.classList.add('wcag-wp-spinbutton--focused');
        }

        /**
         * Handle blur event
         */
        handleBlur(e) {
            this.isEditing = false;
            this.element.classList.remove('wcag-wp-spinbutton--focused');
            
            // Validate and format on blur
            const value = parseFloat(this.input.value);
            if (!isNaN(value)) {
                this.currentValue = this.clampValue(value);
                this.input.value = this.currentValue;
                this.updateARIA();
                this.validateValue(this.currentValue);
            }
        }

        /**
         * Handle keyboard navigation
         */
        handleKeydown(e) {
            switch (e.key) {
                case 'ArrowUp':
                    e.preventDefault();
                    this.increment();
                    break;
                    
                case 'ArrowDown':
                    e.preventDefault();
                    this.decrement();
                    break;
                    
                case 'PageUp':
                    e.preventDefault();
                    this.increment(this.config.step * 10);
                    break;
                    
                case 'PageDown':
                    e.preventDefault();
                    this.decrement(this.config.step * 10);
                    break;
                    
                case 'Home':
                    e.preventDefault();
                    this.setValue(this.config.min);
                    break;
                    
                case 'End':
                    e.preventDefault();
                    this.setValue(this.config.max);
                    break;
                    
                case 'Enter':
                    e.preventDefault();
                    this.commitValue();
                    break;
                    
                case 'Escape':
                    e.preventDefault();
                    this.cancelEdit();
                    break;
            }
        }

        /**
         * Handle invalid event
         */
        handleInvalid(e) {
            e.preventDefault();
            this.showError(this.config.strings.invalidValue);
        }

        /**
         * Increment value
         */
        increment(step = this.config.step) {
            if (this.config.disabled || this.config.readonly) return;
            
            const newValue = this.currentValue + step;
            this.setValue(newValue);
        }

        /**
         * Decrement value
         */
        decrement(step = this.config.step) {
            if (this.config.disabled || this.config.readonly) return;
            
            const newValue = this.currentValue - step;
            this.setValue(newValue);
        }

        /**
         * Set value
         */
        setValue(value) {
            if (this.config.disabled || this.config.readonly) return;
            
            this.currentValue = this.clampValue(value);
            this.input.value = this.currentValue;
            this.updateARIA();
            this.validateValue(this.currentValue);
            this.updateButtons();
            this.announceValue();
        }

        /**
         * Clamp value to min/max range
         */
        clampValue(value) {
            return Math.max(this.config.min, Math.min(this.config.max, value));
        }

        /**
         * Commit current value
         */
        commitValue() {
            this.isEditing = false;
            this.validateValue(this.currentValue);
            this.announceValue();
        }

        /**
         * Cancel edit and restore original value
         */
        cancelEdit() {
            if (this.originalValue !== null) {
                this.currentValue = this.originalValue;
                this.input.value = this.currentValue;
                this.updateARIA();
                this.validateValue(this.currentValue);
                this.updateButtons();
            }
            this.isEditing = false;
        }

        /**
         * Update ARIA attributes
         */
        updateARIA() {
            const valueText = this.formatValue(this.currentValue);
            
            this.input.setAttribute('aria-valuenow', this.currentValue);
            this.input.setAttribute('aria-valuetext', valueText);
        }

        /**
         * Format value for display
         */
        formatValue(value) {
            let formatted = value.toString();
            
            switch (this.config.format) {
                case 'currency':
                    formatted = `€${value.toFixed(2)}`;
                    break;
                case 'percentage':
                    formatted = `${value}%`;
                    break;
                case 'decimal':
                    formatted = value.toFixed(2);
                    break;
                default:
                    formatted = value.toString();
            }
            
            if (this.config.unit) {
                formatted += ` ${this.config.unit}`;
            }
            
            return formatted;
        }

        /**
         * Validate value
         */
        validateValue(value) {
            let isValid = true;
            let errorMessage = '';
            
            // Check required
            if (this.config.required && (isNaN(value) || value === '')) {
                isValid = false;
                errorMessage = this.config.strings.required;
            }
            
            // Check min/max
            if (!isNaN(value)) {
                if (value < this.config.min) {
                    isValid = false;
                    errorMessage = `${this.config.strings.minValue}: ${this.config.min}`;
                } else if (value > this.config.max) {
                    isValid = false;
                    errorMessage = `${this.config.strings.maxValue}: ${this.config.max}`;
                }
            }
            
            // Update validation state
            if (isValid) {
                this.clearError();
                this.element.classList.remove('wcag-wp-spinbutton--error');
                this.element.classList.add('wcag-wp-spinbutton--success');
            } else {
                this.showError(errorMessage);
                this.element.classList.add('wcag-wp-spinbutton--error');
                this.element.classList.remove('wcag-wp-spinbutton--success');
            }
            
            return isValid;
        }

        /**
         * Show error message
         */
        showError(message) {
            if (this.errorElement) {
                this.errorElement.textContent = message;
                this.errorElement.style.display = 'block';
            }
            
            if (this.validationContainer) {
                this.validationContainer.hidden = false;
            }
        }

        /**
         * Clear error message
         */
        clearError() {
            if (this.errorElement) {
                this.errorElement.textContent = '';
                this.errorElement.style.display = 'none';
            }
            
            if (this.validationContainer) {
                this.validationContainer.hidden = true;
            }
        }

        /**
         * Update button states
         */
        updateButtons() {
            if (this.incrementButton) {
                this.incrementButton.disabled = this.config.disabled || 
                                               this.config.readonly || 
                                               this.currentValue >= this.config.max;
            }
            
            if (this.decrementButton) {
                this.decrementButton.disabled = this.config.disabled || 
                                               this.config.readonly || 
                                               this.currentValue <= this.config.min;
            }
        }

        /**
         * Announce value to screen readers
         */
        announceValue() {
            if (this.announcementsElement) {
                const valueText = this.formatValue(this.currentValue);
                this.announcementsElement.textContent = `${this.config.strings.currentValue}: ${valueText}`;
                
                // Clear after announcement
                setTimeout(() => {
                    this.announcementsElement.textContent = '';
                }, 1000);
            }
        }

        /**
         * Get current value
         */
        getValue() {
            return this.currentValue;
        }

        /**
         * Set value programmatically
         */
        setValueProgrammatically(value) {
            this.setValue(value);
        }

        /**
         * Enable/disable spinbutton
         */
        setDisabled(disabled) {
            this.config.disabled = disabled;
            this.input.disabled = disabled;
            this.updateButtons();
            
            if (disabled) {
                this.element.classList.add('wcag-wp-spinbutton--disabled');
            } else {
                this.element.classList.remove('wcag-wp-spinbutton--disabled');
            }
        }

        /**
         * Destroy spinbutton
         */
        destroy() {
            // Remove event listeners
            if (this.incrementButton) {
                this.incrementButton.removeEventListener('click', this.increment);
            }
            if (this.decrementButton) {
                this.decrementButton.removeEventListener('click', this.decrement);
            }
            
            // Remove element
            if (this.element.parentNode) {
                this.element.parentNode.removeChild(this.element);
            }
        }
    }

    /**
     * Initialize all spinbuttons on page load
     */
    function initSpinbuttons() {
        $('.wcag-wp-spinbutton').each(function() {
            const $spinbutton = $(this);
            
            // Skip if already initialized
            if ($spinbutton.data('wcag-spinbutton')) {
                return;
            }
            
            const configElement = $spinbutton.find('.wcag-spinbutton-config');
            
            if (configElement.length) {
                try {
                    const config = JSON.parse(configElement.text());
                    const spinbutton = new WcagSpinbutton(this, config);
                    
                    // Store instance for external access
                    $spinbutton.data('wcag-spinbutton', spinbutton);
                } catch (e) {
                    console.error('Failed to initialize spinbutton:', e);
                }
            }
        });
        
        // Also initialize elements with data-wcag-spinbutton attribute
        $('[data-wcag-spinbutton]').each(function() {
            const $element = $(this);
            
            // Skip if already initialized
            if ($element.data('wcag-spinbutton')) {
                return;
            }
            
            try {
                const config = JSON.parse($element.attr('data-wcag-spinbutton') || '{}');
                const spinbutton = new WcagSpinbutton(this, config);
                
                // Store instance for external access
                $element.data('wcag-spinbutton', spinbutton);
            } catch (e) {
                console.error('Failed to initialize spinbutton with data attribute:', e);
            }
        });
    }

    /**
     * Global API for external access
     */
    window.WcagSpinbutton = WcagSpinbutton;
    window.wcagSpinbuttonAPI = {
        /**
         * Get spinbutton instance by ID
         */
        getInstance: function(id) {
            const element = document.getElementById(id);
            if (element) {
                return $(element).data('wcag-spinbutton');
            }
            return null;
        },

        /**
         * Create new spinbutton
         */
        create: function(element, config) {
            return new WcagSpinbutton(element, config);
        },

        /**
         * Initialize all spinbuttons
         */
        init: initSpinbuttons
    };

    // Initialize on DOM ready
    $(document).ready(function() {
        initSpinbuttons();
    });

    // Initialize on AJAX content load
    $(document).on('wcag-content-loaded', function() {
        initSpinbuttons();
    });

})(jQuery);
