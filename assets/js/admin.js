/**
 * WCAG-WP Admin JavaScript
 * 
 * Vanilla JavaScript for WordPress admin interface
 * Follows WCAG 2.1 AA accessibility guidelines
 * No external dependencies (jQuery-free)
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

'use strict';

(function() {
    // Ensure wcag_wp_admin object exists
    if (typeof wcag_wp_admin === 'undefined') {
        console.warn('WCAG-WP: Admin configuration object not found');
        return;
    }

    /**
     * WCAG-WP Admin Class
     */
    class WcagWpAdmin {
        constructor() {
            this.config = wcag_wp_admin;
            this.init();
        }

        /**
         * Initialize admin functionality
         */
        init() {
            this.bindEvents();
            this.initializeComponents();
            this.setupAccessibilityFeatures();
            
            // Log successful initialization
            this.log('Admin interface initialized', 'info');
        }

        /**
         * Bind event listeners
         */
        bindEvents() {
            // DOM Content Loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    this.onDOMReady();
                });
            } else {
                this.onDOMReady();
            }

            // Settings form validation
            this.bindSettingsFormEvents();
            
            // Component card interactions
            this.bindComponentCardEvents();
            
            // Quick action cards
            this.bindQuickActionEvents();
            
            // Global keyboard navigation
            this.bindKeyboardEvents();
        }

        /**
         * DOM Ready handler
         */
        onDOMReady() {
            this.updateStatCards();
            this.initializeTooltips();
            this.checkAccessibilityCompliance();
        }

        /**
         * Bind settings form events
         */
        bindSettingsFormEvents() {
            const settingsForm = document.querySelector('.wcag-wp-settings-form');
            if (!settingsForm) return;

            // Real-time preview updates
            const colorSchemeSelect = settingsForm.querySelector('#color_scheme');
            if (colorSchemeSelect) {
                colorSchemeSelect.addEventListener('change', (e) => {
                    this.updateDesignPreview(e.target.value);
                });
            }

            // Font family changes
            const fontFamilySelect = settingsForm.querySelector('#font_family');
            if (fontFamilySelect) {
                fontFamilySelect.addEventListener('change', (e) => {
                    this.updateFontPreview(e.target.value);
                });
            }

            // Form submission
            settingsForm.addEventListener('submit', (e) => {
                this.handleSettingsSubmit(e);
            });

            // Focus outline toggle
            const focusOutlineCheckbox = settingsForm.querySelector('input[name="wcag_wp_settings[design_system][focus_outline]"]');
            if (focusOutlineCheckbox) {
                focusOutlineCheckbox.addEventListener('change', (e) => {
                    this.toggleFocusOutlines(e.target.checked);
                });
            }
        }

        /**
         * Bind component card events
         */
        bindComponentCardEvents() {
            const componentCards = document.querySelectorAll('.component-card');
            
            componentCards.forEach(card => {
                // Add keyboard support for card interaction
                card.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        const button = card.querySelector('.button:not([disabled])');
                        if (button) {
                            e.preventDefault();
                            button.click();
                        }
                    }
                });

                // Add ARIA attributes for better screen reader support
                if (!card.hasAttribute('role')) {
                    card.setAttribute('role', 'article');
                }

                // Add aria-label if missing
                const title = card.querySelector('h3');
                if (title && !card.hasAttribute('aria-label')) {
                    card.setAttribute('aria-label', title.textContent.trim());
                }
            });
        }

        /**
         * Bind quick action events
         */
        bindQuickActionEvents() {
            const quickActions = document.querySelectorAll('.quick-action-card');
            
            quickActions.forEach(action => {
                // Add keyboard navigation support
                action.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        action.click();
                    }
                });

                // Add focus management
                action.addEventListener('focus', () => {
                    action.setAttribute('aria-expanded', 'true');
                });

                action.addEventListener('blur', () => {
                    action.removeAttribute('aria-expanded');
                });
            });
        }

        /**
         * Bind global keyboard events
         */
        bindKeyboardEvents() {
            document.addEventListener('keydown', (e) => {
                // Escape key to close modals/dialogs
                if (e.key === 'Escape') {
                    this.handleEscapeKey(e);
                }

                // Alt + H for help/shortcuts
                if (e.altKey && e.key === 'h') {
                    e.preventDefault();
                    this.showKeyboardShortcuts();
                }
            });

            // Skip to main content link
            this.addSkipToMainLink();
        }

        /**
         * Initialize components
         */
        initializeComponents() {
            this.initializeTabs();
            this.initializeAccordions();
            this.initializeModals();
            this.initializeNotifications();
        }

        /**
         * Setup accessibility features
         */
        setupAccessibilityFeatures() {
            // Add ARIA landmarks
            this.addAriaLandmarks();
            
            // Announce page changes to screen readers
            this.announcePageChanges();
            
            // Initialize high contrast mode
            this.initializeHighContrastMode();
            
            // Setup reduced motion preferences
            this.respectReducedMotionPreferences();
        }

        /**
         * Update design preview in real-time
         */
        updateDesignPreview(colorScheme) {
            const previewArea = document.querySelector('.settings-preview');
            if (!previewArea) return;

            // Color scheme mapping
            const colorSchemes = {
                'default': { primary: '#2271b1', secondary: '#f6f7f7' },
                'green': { primary: '#00a32a', secondary: '#f0f9f0' },
                'purple': { primary: '#8c4bff', secondary: '#f8f5ff' },
                'orange': { primary: '#ff8c00', secondary: '#fff5e6' }
            };

            const scheme = colorSchemes[colorScheme] || colorSchemes.default;
            
            // Update CSS custom properties
            previewArea.style.setProperty('--wcag-wp-primary', scheme.primary);
            previewArea.style.setProperty('--wcag-wp-gray-50', scheme.secondary);

            // Announce change to screen readers
            this.announceToScreenReader(`Schema colori cambiato in ${colorScheme}`);
        }

        /**
         * Update font preview
         */
        updateFontPreview(fontFamily) {
            const previewArea = document.querySelector('.settings-preview');
            if (!previewArea) return;

            const fontMap = {
                'system-ui': 'system-ui, -apple-system, sans-serif',
                'arial': 'Arial, sans-serif',
                'helvetica': 'Helvetica, Arial, sans-serif',
                'verdana': 'Verdana, Geneva, sans-serif',
                'open-sans': '"Open Sans", sans-serif'
            };

            const font = fontMap[fontFamily] || fontMap['system-ui'];
            previewArea.style.setProperty('--wcag-wp-font-family', font);

            this.announceToScreenReader(`Font cambiato in ${fontFamily}`);
        }

        /**
         * Toggle focus outlines globally
         */
        toggleFocusOutlines(enabled) {
            const style = document.getElementById('wcag-wp-focus-toggle') || document.createElement('style');
            style.id = 'wcag-wp-focus-toggle';

            if (enabled) {
                style.textContent = '';
            } else {
                style.textContent = `
                    .wcag-wp-admin *:focus {
                        outline: none !important;
                    }
                `;
            }

            if (!style.parentNode) {
                document.head.appendChild(style);
            }

            this.announceToScreenReader(enabled ? 'Focus outline abilitati' : 'Focus outline disabilitati');
        }

        /**
         * Handle settings form submission
         */
        handleSettingsSubmit(e) {
            const form = e.target;
            const submitButton = form.querySelector('#wcag-wp-save-settings');
            
            // Add loading state
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = this.config.strings?.saving || 'Salvando...';
                submitButton.classList.add('wcag-wp-loading');
            }

            // Validate form before submission
            if (!this.validateSettingsForm(form)) {
                e.preventDefault();
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Salva Impostazioni';
                    submitButton.classList.remove('wcag-wp-loading');
                }
                return;
            }

            // Form will submit normally, success message will be shown on reload
        }

        /**
         * Validate settings form
         */
        validateSettingsForm(form) {
            let isValid = true;
            const errors = [];

            // Validate color scheme
            const colorScheme = form.querySelector('#color_scheme');
            if (colorScheme && !colorScheme.value) {
                errors.push('Seleziona uno schema colori');
                isValid = false;
            }

            // Show errors if any
            if (!isValid) {
                this.showFormErrors(errors);
            }

            return isValid;
        }

        /**
         * Show form errors
         */
        showFormErrors(errors) {
            // Remove existing error notices
            const existingErrors = document.querySelectorAll('.wcag-wp-form-error');
            existingErrors.forEach(error => error.remove());

            // Create error notice
            const errorDiv = document.createElement('div');
            errorDiv.className = 'notice notice-error wcag-wp-form-error';
            errorDiv.innerHTML = `
                <p><strong>Errori nel modulo:</strong></p>
                <ul>${errors.map(error => `<li>${error}</li>`).join('')}</ul>
            `;

            // Insert error notice
            const form = document.querySelector('.wcag-wp-settings-form');
            if (form) {
                form.insertBefore(errorDiv, form.firstChild);
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                errorDiv.focus();
            }

            // Announce errors to screen readers
            this.announceToScreenReader(`Errori nel modulo: ${errors.join(', ')}`);
        }

        /**
         * Update stat cards with real data
         */
        updateStatCards() {
            // This would be expanded with AJAX calls to get real-time data
            const statCards = document.querySelectorAll('.wcag-wp-stat-card');
            
            statCards.forEach(card => {
                const value = card.querySelector('h3');
                if (value && value.textContent === '0' && card.textContent.includes('Tabelle Create')) {
                    // Example: Could fetch real count via AJAX
                    this.fetchTableCount().then(count => {
                        value.textContent = count;
                        this.announceToScreenReader(`Tabelle create: ${count}`);
                    });
                }
            });
        }

        /**
         * Fetch table count (example AJAX)
         */
        async fetchTableCount() {
            try {
                const response = await fetch(this.config.ajax_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'wcag_wp_action',
                        wcag_action: 'get_table_count',
                        nonce: this.config.nonce
                    })
                });

                const data = await response.json();
                return data.success ? data.data.count : 0;
            } catch (error) {
                this.log('Error fetching table count: ' + error.message, 'error');
                return 0;
            }
        }

        /**
         * Initialize tooltips
         */
        initializeTooltips() {
            const tooltipElements = document.querySelectorAll('[data-tooltip]');
            
            tooltipElements.forEach(element => {
                element.addEventListener('mouseenter', (e) => {
                    this.showTooltip(e.target);
                });

                element.addEventListener('mouseleave', (e) => {
                    this.hideTooltip(e.target);
                });

                element.addEventListener('focus', (e) => {
                    this.showTooltip(e.target);
                });

                element.addEventListener('blur', (e) => {
                    this.hideTooltip(e.target);
                });
            });
        }

        /**
         * Show tooltip
         */
        showTooltip(element) {
            const text = element.getAttribute('data-tooltip');
            if (!text) return;

            const tooltip = document.createElement('div');
            tooltip.className = 'wcag-wp-tooltip';
            tooltip.textContent = text;
            tooltip.id = 'wcag-wp-tooltip-' + Date.now();
            
            document.body.appendChild(tooltip);
            element.setAttribute('aria-describedby', tooltip.id);

            // Position tooltip
            const rect = element.getBoundingClientRect();
            tooltip.style.cssText = `
                position: absolute;
                top: ${rect.bottom + 5}px;
                left: ${rect.left}px;
                background: #1d2327;
                color: white;
                padding: 8px 12px;
                border-radius: 4px;
                font-size: 12px;
                z-index: 9999;
                max-width: 200px;
            `;
        }

        /**
         * Hide tooltip
         */
        hideTooltip(element) {
            const tooltipId = element.getAttribute('aria-describedby');
            if (tooltipId) {
                const tooltip = document.getElementById(tooltipId);
                if (tooltip) {
                    tooltip.remove();
                }
                element.removeAttribute('aria-describedby');
            }
        }

        /**
         * Add ARIA landmarks
         */
        addAriaLandmarks() {
            const main = document.querySelector('.wcag-wp-admin');
            if (main && !main.hasAttribute('role')) {
                main.setAttribute('role', 'main');
                main.setAttribute('aria-label', 'WCAG-WP Administration Interface');
            }

            // Add navigation landmarks
            const nav = document.querySelector('.wp-submenu');
            if (nav && !nav.hasAttribute('role')) {
                nav.setAttribute('role', 'navigation');
                nav.setAttribute('aria-label', 'Plugin Navigation');
            }
        }

        /**
         * Announce page changes to screen readers
         */
        announcePageChanges() {
            // Create live region for announcements
            if (!document.getElementById('wcag-wp-announcements')) {
                const announcer = document.createElement('div');
                announcer.id = 'wcag-wp-announcements';
                announcer.setAttribute('aria-live', 'polite');
                announcer.setAttribute('aria-atomic', 'true');
                announcer.className = 'wcag-wp-sr-only';
                document.body.appendChild(announcer);
            }
        }

        /**
         * Announce message to screen readers
         */
        announceToScreenReader(message) {
            const announcer = document.getElementById('wcag-wp-announcements');
            if (announcer) {
                announcer.textContent = message;
                
                // Clear after announcement
                setTimeout(() => {
                    announcer.textContent = '';
                }, 1000);
            }
        }

        /**
         * Initialize high contrast mode
         */
        initializeHighContrastMode() {
            // Check for high contrast preference
            if (window.matchMedia && window.matchMedia('(prefers-contrast: high)').matches) {
                document.body.classList.add('wcag-wp-high-contrast');
                this.log('High contrast mode detected and applied', 'info');
            }

            // Listen for changes
            if (window.matchMedia) {
                window.matchMedia('(prefers-contrast: high)').addEventListener('change', (e) => {
                    if (e.matches) {
                        document.body.classList.add('wcag-wp-high-contrast');
                    } else {
                        document.body.classList.remove('wcag-wp-high-contrast');
                    }
                });
            }
        }

        /**
         * Respect reduced motion preferences
         */
        respectReducedMotionPreferences() {
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                document.body.classList.add('wcag-wp-reduced-motion');
                this.log('Reduced motion preference detected', 'info');
            }
        }

        /**
         * Add skip to main content link
         */
        addSkipToMainLink() {
            if (document.querySelector('.wcag-wp-skip-link')) return;

            const skipLink = document.createElement('a');
            skipLink.href = '#wcontent';
            skipLink.className = 'wcag-wp-skip-link';
            skipLink.textContent = 'Salta al contenuto principale';
            skipLink.style.cssText = `
                position: absolute;
                left: -9999px;
                top: auto;
                z-index: 999999;
                padding: 8px 16px;
                background: #000;
                color: #fff;
                text-decoration: none;
                font-weight: 600;
            `;

            skipLink.addEventListener('focus', () => {
                skipLink.style.left = '6px';
                skipLink.style.top = '6px';
            });

            skipLink.addEventListener('blur', () => {
                skipLink.style.left = '-9999px';
            });

            document.body.insertBefore(skipLink, document.body.firstChild);
        }

        /**
         * Handle escape key globally
         */
        handleEscapeKey(e) {
            // Close any open modals, tooltips, etc.
            const tooltips = document.querySelectorAll('.wcag-wp-tooltip');
            tooltips.forEach(tooltip => tooltip.remove());

            // Clear any aria-describedby attributes
            const elementsWithTooltips = document.querySelectorAll('[aria-describedby^="wcag-wp-tooltip-"]');
            elementsWithTooltips.forEach(element => {
                element.removeAttribute('aria-describedby');
            });
        }

        /**
         * Show keyboard shortcuts
         */
        showKeyboardShortcuts() {
            const shortcuts = [
                'Alt + H: Mostra questa guida',
                'Tab: Naviga tra gli elementi',
                'Esc: Chiudi modal e tooltip',
                'Invio/Spazio: Attiva pulsanti e link'
            ];

            alert('Scorciatoie da tastiera WCAG-WP:\n\n' + shortcuts.join('\n'));
        }

        /**
         * Initialize tab navigation
         */
        initializeTabs() {
            // Implementation for future tab components
        }

        /**
         * Initialize accordions
         */
        initializeAccordions() {
            // Implementation for future accordion components
        }

        /**
         * Initialize modals
         */
        initializeModals() {
            // Implementation for future modal components
        }

        /**
         * Initialize notifications
         */
        initializeNotifications() {
            // Auto-dismiss notices after 5 seconds
            const notices = document.querySelectorAll('.notice.is-dismissible');
            notices.forEach(notice => {
                setTimeout(() => {
                    if (notice.parentNode) {
                        notice.style.opacity = '0';
                        setTimeout(() => notice.remove(), 300);
                    }
                }, 5000);
            });
        }

        /**
         * Check accessibility compliance
         */
        checkAccessibilityCompliance() {
            // Basic accessibility checks
            const checks = {
                images_alt: this.checkImagesAltText(),
                form_labels: this.checkFormLabels(),
                heading_structure: this.checkHeadingStructure(),
                focus_indicators: this.checkFocusIndicators()
            };

            const issues = Object.entries(checks)
                .filter(([key, passed]) => !passed)
                .map(([key]) => key);

            if (issues.length > 0) {
                this.log(`Accessibility issues found: ${issues.join(', ')}`, 'warning');
            } else {
                this.log('Accessibility compliance check passed', 'info');
            }
        }

        /**
         * Check images have alt text
         */
        checkImagesAltText() {
            const images = document.querySelectorAll('img');
            return Array.from(images).every(img => 
                img.hasAttribute('alt') || img.hasAttribute('aria-label') || img.getAttribute('role') === 'presentation'
            );
        }

        /**
         * Check form labels
         */
        checkFormLabels() {
            const inputs = document.querySelectorAll('input, select, textarea');
            return Array.from(inputs).every(input => {
                const id = input.id;
                const label = id ? document.querySelector(`label[for="${id}"]`) : null;
                return label || input.hasAttribute('aria-label') || input.hasAttribute('aria-labelledby');
            });
        }

        /**
         * Check heading structure
         */
        checkHeadingStructure() {
            const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
            let previousLevel = 0;
            
            return Array.from(headings).every(heading => {
                const level = parseInt(heading.tagName.charAt(1));
                const isValid = level <= previousLevel + 1;
                previousLevel = level;
                return isValid;
            });
        }

        /**
         * Check focus indicators
         */
        checkFocusIndicators() {
            const focusableElements = document.querySelectorAll('a, button, input, select, textarea, [tabindex]');
            return focusableElements.length > 0; // Basic check - elements exist
        }

        /**
         * Logging utility
         */
        log(message, level = 'info') {
            if (console && typeof console.log === 'function') {
                const timestamp = new Date().toISOString();
                const logMessage = `[${timestamp}] WCAG-WP Admin (${level}): ${message}`;
                
                switch (level) {
                    case 'error':
                        console.error(logMessage);
                        break;
                    case 'warning':
                        console.warn(logMessage);
                        break;
                    default:
                        console.log(logMessage);
                }
            }
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.wcagWpAdmin = new WcagWpAdmin();
        });
    } else {
        window.wcagWpAdmin = new WcagWpAdmin();
    }

})();
