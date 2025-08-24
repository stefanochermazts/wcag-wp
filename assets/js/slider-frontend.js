/**
 * WCAG Slider Component - Frontend JavaScript
 * 
 * Pattern WCAG APG per slider accessibile
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * WCAG Slider Class
     */
    class WcagSlider {
        constructor(element, config = {}) {
            this.element = element;
            this.config = {
                min: 0,
                max: 100,
                step: 1,
                default_value: 50,
                unit: '',
                orientation: 'horizontal',
                show_value: true,
                show_ticks: false,
                show_tooltip: true,
                required: false,
                disabled: false,
                format: 'number',
                on_change_callback: '',
                ...config
            };
            
            this.currentValue = this.config.default_value;
            this.isDragging = false;
            this.init();
        }
        
        init() {
            this.setupEventListeners();
            this.updatePosition();
            this.updateARIA();
            this.updateValueDisplay();
            this.setupAccessibility();
        }
        
        setupEventListeners() {
            // Mouse events
            this.element.on('mousedown', (e) => {
                if (!this.config.disabled) {
                    this.startDrag(e);
                }
            });
            
            // Touch events
            this.element.on('touchstart', (e) => {
                if (!this.config.disabled) {
                    e.preventDefault();
                    this.startDrag(e.touches[0]);
                }
            });
            
            // Track click
            this.element.closest('.wcag-wp-slider__track').on('click', (e) => {
                if (!this.config.disabled) {
                    this.handleTrackClick(e);
                }
            });
            
            // Keyboard events
            this.element.on('keydown', (e) => {
                this.handleKeydown(e);
            });
            
            // Focus events
            this.element.on('focus', () => {
                this.handleFocus();
            });
            
            this.element.on('blur', () => {
                this.handleBlur();
            });
            
            // Global mouse/touch events
            $(document).on('mousemove touchmove', (e) => {
                if (this.isDragging) {
                    e.preventDefault();
                    this.handleDrag(e.type === 'touchmove' ? e.touches[0] : e);
                }
            });
            
            $(document).on('mouseup touchend', () => {
                if (this.isDragging) {
                    this.stopDrag();
                }
            });
        }
        
        startDrag(e) {
            this.isDragging = true;
            this.element.addClass('wcag-wp-slider__thumb--dragging');
            this.handleDrag(e);
        }
        
        handleDrag(e) {
            const track = this.element.closest('.wcag-wp-slider__track')[0];
            const rect = track.getBoundingClientRect();
            
            let percentage;
            if (this.config.orientation === 'horizontal') {
                percentage = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
            } else {
                percentage = Math.max(0, Math.min(1, (rect.bottom - e.clientY) / rect.height));
            }
            
            const value = this.config.min + (this.config.max - this.config.min) * percentage;
            this.setValue(this.snapToStep(value));
        }
        
        stopDrag() {
            this.isDragging = false;
            this.element.removeClass('wcag-wp-slider__thumb--dragging');
        }
        
        handleTrackClick(e) {
            const track = $(e.currentTarget);
            const rect = track[0].getBoundingClientRect();
            
            let percentage;
            if (this.config.orientation === 'horizontal') {
                percentage = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
            } else {
                percentage = Math.max(0, Math.min(1, (rect.bottom - e.clientY) / rect.height));
            }
            
            const value = this.config.min + (this.config.max - this.config.min) * percentage;
            this.setValue(this.snapToStep(value));
        }
        
        handleKeydown(e) {
            if (this.config.disabled) return;
            
            let newValue = this.currentValue;
            let step = this.config.step;
            
            switch (e.key) {
                case 'ArrowLeft':
                case 'ArrowDown':
                    e.preventDefault();
                    newValue = Math.max(this.config.min, this.currentValue - step);
                    break;
                    
                case 'ArrowRight':
                case 'ArrowUp':
                    e.preventDefault();
                    newValue = Math.min(this.config.max, this.currentValue + step);
                    break;
                    
                case 'Home':
                    e.preventDefault();
                    newValue = this.config.min;
                    break;
                    
                case 'End':
                    e.preventDefault();
                    newValue = this.config.max;
                    break;
                    
                case 'PageUp':
                    e.preventDefault();
                    newValue = Math.min(this.config.max, this.currentValue + step * 10);
                    break;
                    
                case 'PageDown':
                    e.preventDefault();
                    newValue = Math.max(this.config.min, this.currentValue - step * 10);
                    break;
            }
            
            if (newValue !== this.currentValue) {
                this.setValue(newValue);
            }
        }
        
        handleFocus() {
            this.element.addClass('wcag-wp-slider__thumb--focused');
        }
        
        handleBlur() {
            this.element.removeClass('wcag-wp-slider__thumb--focused');
        }
        
        setValue(value) {
            const clampedValue = Math.max(this.config.min, Math.min(this.config.max, value));
            const snappedValue = this.snapToStep(clampedValue);
            
            if (snappedValue !== this.currentValue) {
                this.currentValue = snappedValue;
                this.updatePosition();
                this.updateARIA();
                this.updateValueDisplay();
                this.announceValue();
                this.validateValue();
                this.triggerCallback();
            }
        }
        
        getValue() {
            return this.currentValue;
        }
        
        snapToStep(value) {
            const steps = (this.config.max - this.config.min) / this.config.step;
            const stepIndex = Math.round((value - this.config.min) / this.config.step);
            return this.config.min + (stepIndex * this.config.step);
        }
        
        updatePosition() {
            const percentage = ((this.currentValue - this.config.min) / (this.config.max - this.config.min)) * 100;
            
            if (this.config.orientation === 'horizontal') {
                this.element.css('left', percentage + '%');
            } else {
                this.element.css('top', (100 - percentage) + '%');
            }
        }
        
        updateARIA() {
            this.element.attr('aria-valuenow', this.currentValue);
            this.element.attr('aria-valuetext', this.formatValue(this.currentValue));
        }
        
        updateValueDisplay() {
            if (this.config.show_value) {
                const valueElement = this.element.closest('.wcag-wp-slider').find('.wcag-wp-slider__value-text');
                if (valueElement.length) {
                    valueElement.text(this.formatValue(this.currentValue));
                }
            }
            
            if (this.config.show_tooltip) {
                const tooltipElement = this.element.find('.wcag-wp-slider__tooltip-text');
                if (tooltipElement.length) {
                    tooltipElement.text(this.formatValue(this.currentValue));
                }
            }
        }
        
        formatValue(value) {
            let formattedValue = value;
            
            switch (this.config.format) {
                case 'percentage':
                    formattedValue = Math.round(value) + '%';
                    break;
                case 'currency':
                    formattedValue = '€' + value.toFixed(2);
                    break;
                default:
                    formattedValue = value.toString();
            }
            
            if (this.config.unit) {
                formattedValue += ' ' + this.config.unit;
            }
            
            return formattedValue;
        }
        
        announceValue() {
            const announcementElement = this.element.closest('.wcag-wp-slider').find('.wcag-wp-slider__announcements');
            if (announcementElement.length) {
                announcementElement.text(this.formatValue(this.currentValue));
                
                // Clear after announcement
                setTimeout(() => {
                    announcementElement.text('');
                }, 1000);
            }
        }
        
        validateValue() {
            const validationElement = this.element.closest('.wcag-wp-slider').find('.wcag-wp-slider__validation');
            
            if (this.config.required && this.currentValue === this.config.min) {
                this.showError('Questo campo è obbligatorio', validationElement);
            } else {
                this.clearError(validationElement);
            }
        }
        
        showError(message, validationElement) {
            if (validationElement.length) {
                validationElement
                    .attr('data-type', 'error')
                    .text(message)
                    .removeAttr('hidden');
            }
        }
        
        clearError(validationElement) {
            if (validationElement.length) {
                validationElement
                    .removeAttr('data-type')
                    .text('')
                    .attr('hidden', 'hidden');
            }
        }
        
        triggerCallback() {
            if (this.config.on_change_callback && typeof window[this.config.on_change_callback] === 'function') {
                try {
                    window[this.config.on_change_callback](this.currentValue, this.element);
                } catch (error) {
                    console.warn('WCAG Slider: Error in callback function:', error);
                }
            }
            
            // Trigger custom event
            this.element.trigger('wcag-slider-change', [this.currentValue, this]);
        }
        
        setupAccessibility() {
            // Add role if not present
            if (!this.element.attr('role')) {
                this.element.attr('role', 'slider');
            }
            
            // Ensure proper tabindex
            if (!this.element.attr('tabindex')) {
                this.element.attr('tabindex', '0');
            }
        }
        
        enable() {
            this.config.disabled = false;
            this.element.removeAttr('aria-disabled');
            this.element.removeClass('wcag-wp-slider__thumb--disabled');
        }
        
        disable() {
            this.config.disabled = true;
            this.element.attr('aria-disabled', 'true');
            this.element.addClass('wcag-wp-slider__thumb--disabled');
        }
        
        destroy() {
            this.element.off('.wcag-slider');
            this.element.removeData('wcag-slider');
        }
    }
    
    /**
     * Initialize all sliders on the page
     */
    function initSliders() {
        $('.wcag-wp-slider__thumb').each(function() {
            const $thumb = $(this);
            
            // Skip if already initialized
            if ($thumb.data('wcag-slider')) {
                return;
            }
            
            // Get configuration from JSON script tag
            const configScript = $thumb.closest('.wcag-wp-slider').find('.wcag-slider-config');
            let config = {};
            
            if (configScript.length) {
                try {
                    const configData = JSON.parse(configScript.text());
                    config = configData.config || {};
                } catch (error) {
                    console.warn('WCAG Slider: Error parsing configuration:', error);
                }
            }
            
            // Initialize slider
            const sliderInstance = new WcagSlider($thumb, config);
            $thumb.data('wcag-slider', sliderInstance);
        });
    }
    
    /**
     * Global API for external access
     */
    window.wcagSliderAPI = {
        /**
         * Get slider instance by element
         */
        getInstance: function(element) {
            const $element = $(element);
            return $element.data('wcag-slider');
        },
        
        /**
         * Create new slider programmatically
         */
        create: function(element, config) {
            return new WcagSlider($(element), config);
        },
        
        /**
         * Get all sliders on the page
         */
        getAll: function() {
            const sliders = [];
            $('.wcag-wp-slider__thumb').each(function() {
                const instance = $(this).data('wcag-slider');
                if (instance) {
                    sliders.push(instance);
                }
            });
            return sliders;
        },
        
        /**
         * Set value for all sliders
         */
        setAllValues: function(value) {
            $('.wcag-wp-slider__thumb').each(function() {
                const instance = $(this).data('wcag-slider');
                if (instance) {
                    instance.setValue(value);
                }
            });
        }
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        initSliders();
    });
    
    // Initialize on dynamic content load
    $(document).on('wcag-content-loaded', function() {
        initSliders();
    });
    
    // Export for module systems
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = WcagSlider;
    }
    
})(jQuery);
