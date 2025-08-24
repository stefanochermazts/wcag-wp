/**
 * WCAG Switch Component - Frontend JavaScript
 * 
 * Pattern WCAG APG per switch accessibile
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * WCAG Switch Class
     */
    class WcagSwitch {
        constructor(element, config = {}) {
            this.element = element;
            this.config = {
                on_text: 'Attivo',
                off_text: 'Inattivo',
                default_state: 'off',
                required: false,
                disabled: false,
                show_labels: true,
                animation: true,
                theme: 'default',
                on_change_callback: '',
                ...config
            };
            
            this.isActive = this.config.default_state === 'on';
            this.init();
        }
        
        init() {
            this.setupEventListeners();
            this.updateARIA();
            this.updateStatus();
            this.setupAccessibility();
        }
        
        setupEventListeners() {
            // Click event
            this.element.on('click', (e) => {
                e.preventDefault();
                if (!this.config.disabled) {
                    this.toggle();
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
        }
        
        handleKeydown(e) {
            switch (e.key) {
                case ' ':
                case 'Enter':
                    e.preventDefault();
                    if (!this.config.disabled) {
                        this.toggle();
                    }
                    break;
                    
                case 'ArrowLeft':
                    e.preventDefault();
                    if (!this.config.disabled && this.isActive) {
                        this.toggle();
                    }
                    break;
                    
                case 'ArrowRight':
                    e.preventDefault();
                    if (!this.config.disabled && !this.isActive) {
                        this.toggle();
                    }
                    break;
                    
                case 'Home':
                    e.preventDefault();
                    if (!this.config.disabled && this.isActive) {
                        this.toggle();
                    }
                    break;
                    
                case 'End':
                    e.preventDefault();
                    if (!this.config.disabled && !this.isActive) {
                        this.toggle();
                    }
                    break;
            }
        }
        
        handleFocus() {
            this.element.addClass('wcag-wp-switch__toggle--focused');
        }
        
        handleBlur() {
            this.element.removeClass('wcag-wp-switch__toggle--focused');
        }
        
        toggle() {
            this.isActive = !this.isActive;
            this.updateVisualState();
            this.updateARIA();
            this.updateStatus();
            this.announceState();
            this.validateState();
            this.triggerCallback();
        }
        
        setState(state) {
            const newState = state === 'on' || state === true;
            if (this.isActive !== newState) {
                this.isActive = newState;
                this.updateVisualState();
                this.updateARIA();
                this.updateStatus();
                this.announceState();
                this.validateState();
            }
        }
        
        getState() {
            return this.isActive ? 'on' : 'off';
        }
        
        updateVisualState() {
            if (this.isActive) {
                this.element.addClass('wcag-wp-switch__toggle--active');
            } else {
                this.element.removeClass('wcag-wp-switch__toggle--active');
            }
        }
        
        updateARIA() {
            this.element.attr('aria-checked', this.isActive ? 'true' : 'false');
        }
        
        updateStatus() {
            if (this.config.show_labels) {
                const statusElement = this.element.closest('.wcag-wp-switch').find('.wcag-wp-switch__status');
                if (statusElement.length) {
                    const statusText = this.isActive ? this.config.on_text : this.config.off_text;
                    statusElement.text(statusText);
                }
            }
        }
        
        announceState() {
            const announcementElement = this.element.closest('.wcag-wp-switch').find('.wcag-wp-switch__announcements');
            if (announcementElement.length) {
                const stateText = this.isActive ? this.config.on_text : this.config.off_text;
                announcementElement.text(stateText);
                
                // Clear after announcement
                setTimeout(() => {
                    announcementElement.text('');
                }, 1000);
            }
        }
        
        validateState() {
            const validationElement = this.element.closest('.wcag-wp-switch').find('.wcag-wp-switch__validation');
            
            if (this.config.required && !this.isActive) {
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
                    window[this.config.on_change_callback](this.getState(), this.element);
                } catch (error) {
                    console.warn('WCAG Switch: Error in callback function:', error);
                }
            }
            
            // Trigger custom event
            this.element.trigger('wcag-switch-change', [this.getState(), this]);
        }
        
        setupAccessibility() {
            // Add role if not present
            if (!this.element.attr('role')) {
                this.element.attr('role', 'switch');
            }
            
            // Ensure proper tabindex
            if (!this.element.attr('tabindex')) {
                this.element.attr('tabindex', '0');
            }
            
            // Add screen reader text if not present
            const srText = this.element.find('.wcag-wp-sr-only');
            if (!srText.length) {
                const stateText = this.isActive ? this.config.on_text : this.config.off_text;
                this.element.append(`<span class="wcag-wp-sr-only">${stateText}</span>`);
            }
        }
        
        enable() {
            this.config.disabled = false;
            this.element.prop('disabled', false);
            this.element.removeClass('wcag-wp-switch__toggle--disabled');
        }
        
        disable() {
            this.config.disabled = true;
            this.element.prop('disabled', true);
            this.element.addClass('wcag-wp-switch__toggle--disabled');
        }
        
        destroy() {
            this.element.off('.wcag-switch');
            this.element.removeData('wcag-switch');
        }
    }
    
    /**
     * Initialize all switches on the page
     */
    function initSwitches() {
        $('.wcag-wp-switch__toggle').each(function() {
            const $toggle = $(this);
            
            // Skip if already initialized
            if ($toggle.data('wcag-switch')) {
                return;
            }
            
            // Get configuration from JSON script tag
            const configScript = $toggle.closest('.wcag-wp-switch').find('.wcag-switch-config');
            let config = {};
            
            if (configScript.length) {
                try {
                    const configData = JSON.parse(configScript.text());
                    config = configData.config || {};
                } catch (error) {
                    console.warn('WCAG Switch: Error parsing configuration:', error);
                }
            }
            
            // Initialize switch
            const switchInstance = new WcagSwitch($toggle, config);
            $toggle.data('wcag-switch', switchInstance);
        });
    }
    
    /**
     * Global API for external access
     */
    window.wcagSwitchAPI = {
        /**
         * Get switch instance by element
         */
        getInstance: function(element) {
            const $element = $(element);
            return $element.data('wcag-switch');
        },
        
        /**
         * Create new switch programmatically
         */
        create: function(element, config) {
            return new WcagSwitch($(element), config);
        },
        
        /**
         * Get all switches on the page
         */
        getAll: function() {
            const switches = [];
            $('.wcag-wp-switch__toggle').each(function() {
                const instance = $(this).data('wcag-switch');
                if (instance) {
                    switches.push(instance);
                }
            });
            return switches;
        },
        
        /**
         * Set state for all switches
         */
        setAllStates: function(state) {
            $('.wcag-wp-switch__toggle').each(function() {
                const instance = $(this).data('wcag-switch');
                if (instance) {
                    instance.setState(state);
                }
            });
        }
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        initSwitches();
    });
    
    // Initialize on dynamic content load
    $(document).on('wcag-content-loaded', function() {
        initSwitches();
    });
    
    // Export for module systems
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = WcagSwitch;
    }
    
})(jQuery);
