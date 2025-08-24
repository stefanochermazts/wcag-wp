/**
 * WCAG Toolbar Component - Frontend JavaScript
 * 
 * Componente toolbar accessibile WCAG 2.1 AA compliant
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * WCAG Toolbar Frontend Class
     */
    class WcagToolbar {
        constructor(element) {
            this.element = element;
            this.config = this.parseConfig();
            this.buttons = [];
            this.links = [];
            this.currentFocusIndex = -1;
            this.init();
        }

        /**
         * Initialize the toolbar
         */
        init() {
            this.setupElements();
            this.setupKeyboardNavigation();
            this.setupEventListeners();
            this.setupAriaAttributes();
            this.announceToScreenReader('Toolbar caricata');
        }

        /**
         * Parse configuration from data attribute
         */
        parseConfig() {
            try {
                const configString = this.element.getAttribute('data-config');
                return configString ? JSON.parse(configString) : {};
            } catch (error) {
                console.error('Error parsing toolbar config:', error);
                return {};
            }
        }

        /**
         * Setup toolbar elements
         */
        setupElements() {
            this.buttons = Array.from(this.element.querySelectorAll('.wcag-wp-toolbar-button'));
            this.links = Array.from(this.element.querySelectorAll('.wcag-wp-toolbar-link'));
            this.interactiveElements = [...this.buttons, ...this.links];
        }

        /**
         * Setup keyboard navigation
         */
        setupKeyboardNavigation() {
            this.element.addEventListener('keydown', (e) => {
                this.handleKeyboardNavigation(e);
            });
        }

        /**
         * Setup event listeners
         */
        setupEventListeners() {
            // Button click events
            this.buttons.forEach(button => {
                button.addEventListener('click', (e) => {
                    this.handleButtonClick(e, button);
                });
            });

            // Link click events
            this.links.forEach(link => {
                link.addEventListener('click', (e) => {
                    this.handleLinkClick(e, link);
                });
            });

            // Focus events for announcements
            this.interactiveElements.forEach(element => {
                element.addEventListener('focus', (e) => {
                    this.handleElementFocus(e, element);
                });
            });
        }

        /**
         * Setup ARIA attributes
         */
        setupAriaAttributes() {
            // Set up toolbar role and attributes
            this.element.setAttribute('role', 'toolbar');
            
            if (!this.element.getAttribute('aria-label')) {
                this.element.setAttribute('aria-label', 'Toolbar');
            }

            // Set up button attributes
            this.buttons.forEach((button, index) => {
                button.setAttribute('tabindex', index === 0 ? '0' : '-1');
                
                if (!button.getAttribute('aria-label')) {
                    const label = button.querySelector('.wcag-wp-toolbar-label');
                    if (label) {
                        button.setAttribute('aria-label', label.textContent.trim());
                    }
                }
            });

            // Set up link attributes
            this.links.forEach((link, index) => {
                link.setAttribute('tabindex', index === 0 ? '0' : '-1');
                
                if (!link.getAttribute('aria-label')) {
                    const label = link.querySelector('.wcag-wp-toolbar-label');
                    if (label) {
                        link.setAttribute('aria-label', label.textContent.trim());
                    }
                }
            });
        }

        /**
         * Handle keyboard navigation
         */
        handleKeyboardNavigation(e) {
            const currentElement = document.activeElement;
            const currentIndex = this.interactiveElements.indexOf(currentElement);

            switch (e.key) {
                case 'Tab':
                    // Let default tab behavior work
                    break;

                case 'ArrowRight':
                case 'ArrowDown':
                    e.preventDefault();
                    this.navigateToNext(currentIndex);
                    break;

                case 'ArrowLeft':
                case 'ArrowUp':
                    e.preventDefault();
                    this.navigateToPrevious(currentIndex);
                    break;

                case 'Home':
                    e.preventDefault();
                    this.navigateToFirst();
                    break;

                case 'End':
                    e.preventDefault();
                    this.navigateToLast();
                    break;

                case 'Enter':
                case ' ':
                    e.preventDefault();
                    this.activateCurrentElement(currentElement);
                    break;

                case 'Escape':
                    e.preventDefault();
                    this.handleEscape();
                    break;
            }
        }

        /**
         * Navigate to next element
         */
        navigateToNext(currentIndex) {
            const nextIndex = currentIndex < this.interactiveElements.length - 1 ? currentIndex + 1 : 0;
            this.focusElement(nextIndex);
        }

        /**
         * Navigate to previous element
         */
        navigateToPrevious(currentIndex) {
            const prevIndex = currentIndex > 0 ? currentIndex - 1 : this.interactiveElements.length - 1;
            this.focusElement(prevIndex);
        }

        /**
         * Navigate to first element
         */
        navigateToFirst() {
            this.focusElement(0);
        }

        /**
         * Navigate to last element
         */
        navigateToLast() {
            this.focusElement(this.interactiveElements.length - 1);
        }

        /**
         * Focus element by index
         */
        focusElement(index) {
            if (index >= 0 && index < this.interactiveElements.length) {
                // Update tabindex attributes
                this.interactiveElements.forEach((element, i) => {
                    element.setAttribute('tabindex', i === index ? '0' : '-1');
                });

                // Focus the element
                this.interactiveElements[index].focus();
                this.currentFocusIndex = index;

                // Announce to screen reader
                const element = this.interactiveElements[index];
                const label = element.querySelector('.wcag-wp-toolbar-label');
                if (label) {
                    this.announceToScreenReader(`${label.textContent.trim()}, elemento ${index + 1} di ${this.interactiveElements.length}`);
                }
            }
        }

        /**
         * Activate current element
         */
        activateCurrentElement(element) {
            if (element.classList.contains('wcag-wp-toolbar-button')) {
                this.handleButtonClick(new Event('click'), element);
            } else if (element.classList.contains('wcag-wp-toolbar-link')) {
                this.handleLinkClick(new Event('click'), element);
            }
        }

        /**
         * Handle escape key
         */
        handleEscape() {
            // Remove focus from toolbar
            this.element.blur();
            this.announceToScreenReader('Uscita dalla toolbar');
        }

        /**
         * Handle button click
         */
        handleButtonClick(e, button) {
            const action = button.getAttribute('data-action');
            const label = button.querySelector('.wcag-wp-toolbar-label');
            const labelText = label ? label.textContent.trim() : 'Pulsante';

            if (action) {
                // Trigger custom action
                this.triggerCustomAction(action, button);
                this.announceToScreenReader(`${labelText} attivato`);
            } else {
                // Default button behavior
                this.announceToScreenReader(`${labelText} cliccato`);
            }
        }

        /**
         * Handle link click
         */
        handleLinkClick(e, link) {
            const label = link.querySelector('.wcag-wp-toolbar-label');
            const labelText = label ? label.textContent.trim() : 'Link';
            const url = link.getAttribute('href');
            const target = link.getAttribute('target');

            if (url) {
                if (target === '_blank') {
                    this.announceToScreenReader(`${labelText}, si apre in una nuova finestra`);
                } else {
                    this.announceToScreenReader(`${labelText}, navigazione`);
                }
            }
        }

        /**
         * Handle element focus
         */
        handleElementFocus(e, element) {
            const index = this.interactiveElements.indexOf(element);
            this.currentFocusIndex = index;

            // Update tabindex attributes
            this.interactiveElements.forEach((el, i) => {
                el.setAttribute('tabindex', i === index ? '0' : '-1');
            });

            // Announce to screen reader
            const label = element.querySelector('.wcag-wp-toolbar-label');
            if (label) {
                this.announceToScreenReader(`${label.textContent.trim()}, elemento ${index + 1} di ${this.interactiveElements.length}`);
            }
        }

        /**
         * Trigger custom action
         */
        triggerCustomAction(action, element) {
            // Create custom event
            const event = new CustomEvent('wcag-toolbar-action', {
                detail: {
                    action: action,
                    element: element,
                    config: this.config
                },
                bubbles: true
            });

            // Dispatch event
            this.element.dispatchEvent(event);

            // Also try to call global function if it exists
            if (typeof window[action] === 'function') {
                window[action](element, this.config);
            }
        }

        /**
         * Announce to screen reader
         */
        announceToScreenReader(message) {
            const announcements = this.element.querySelector('.wcag-wp-sr-only');
            if (announcements) {
                announcements.textContent = message;
                
                // Clear after a short delay
                setTimeout(() => {
                    announcements.textContent = '';
                }, 1000);
            }
        }

        /**
         * Get toolbar configuration
         */
        getConfig() {
            return this.config;
        }

        /**
         * Update toolbar configuration
         */
        updateConfig(newConfig) {
            this.config = { ...this.config, ...newConfig };
            this.element.setAttribute('data-config', JSON.stringify(this.config));
        }

        /**
         * Enable/disable toolbar
         */
        setEnabled(enabled) {
            this.interactiveElements.forEach(element => {
                if (enabled) {
                    element.removeAttribute('disabled');
                    element.removeAttribute('aria-disabled');
                    element.style.pointerEvents = '';
                } else {
                    element.setAttribute('disabled', 'disabled');
                    element.setAttribute('aria-disabled', 'true');
                    element.style.pointerEvents = 'none';
                }
            });

            this.announceToScreenReader(enabled ? 'Toolbar abilitata' : 'Toolbar disabilitata');
        }

        /**
         * Show/hide toolbar
         */
        setVisible(visible) {
            if (visible) {
                this.element.style.display = '';
                this.element.removeAttribute('aria-hidden');
                this.announceToScreenReader('Toolbar visibile');
            } else {
                this.element.style.display = 'none';
                this.element.setAttribute('aria-hidden', 'true');
                this.announceToScreenReader('Toolbar nascosta');
            }
        }
    }

    /**
     * Initialize toolbars when DOM is ready
     */
    function initToolbars() {
        const toolbars = document.querySelectorAll('[data-wcag-toolbar]');
        toolbars.forEach(toolbar => {
            new WcagToolbar(toolbar);
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initToolbars);
    } else {
        initToolbars();
    }

    // Initialize new toolbars added dynamically
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    const toolbars = node.querySelectorAll ? node.querySelectorAll('[data-wcag-toolbar]') : [];
                    toolbars.forEach(toolbar => {
                        new WcagToolbar(toolbar);
                    });

                    if (node.hasAttribute && node.hasAttribute('data-wcag-toolbar')) {
                        new WcagToolbar(node);
                    }
                }
            });
        });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Make WcagToolbar available globally
    window.WcagToolbar = WcagToolbar;

})(jQuery);
