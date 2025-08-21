/**
 * WCAG-WP Admin Accordion JavaScript
 * 
 * Vanilla JavaScript for WCAG accordion administration
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

'use strict';

(function() {
    // Ensure wcag_wp_accordion object exists
    if (typeof wcag_wp_accordion === 'undefined') {
        console.warn('WCAG-WP Accordion: Configuration object not found');
        return;
    }

    /**
     * WCAG Accordion Admin Class
     */
    class WcagWpAccordionAdmin {
        constructor() {
            console.log('[WCAG-WP] WcagWpAccordionAdmin constructor called');
            this.config = wcag_wp_accordion;
            this.sectionIndex = 0;
            console.log('[WCAG-WP] Config loaded:', this.config);
            this.init();
        }

        /**
         * Initialize admin functionality
         */
        init() {
            console.log('[WCAG-WP] init() method called');
            this.bindEvents();
            this.initializeFeatures();
            this.log('WCAG Accordion admin interface initialized', 'info');
            console.log('[WCAG-WP] Initialization completed');
        }

        /**
         * Bind event listeners
         */
        bindEvents() {
            console.log('[WCAG-WP] bindEvents() called');
            // DOM Content Loaded
            if (document.readyState === 'loading') {
                console.log('[WCAG-WP] Document still loading, adding DOMContentLoaded listener');
                document.addEventListener('DOMContentLoaded', () => {
                    console.log('[WCAG-WP] DOMContentLoaded fired');
                    this.onDOMReady();
                });
            } else {
                console.log('[WCAG-WP] Document already loaded, calling onDOMReady immediately');
                this.onDOMReady();
            }

            // Configuration preview updates
            this.bindConfigurationEvents();
            
            // Section management
            this.bindSectionEvents();
            
            // Preview functionality
            this.bindPreviewEvents();
        }

        /**
         * DOM Ready handler
         */
        onDOMReady() {
            console.log('[WCAG-WP] onDOMReady() called');
            this.updateConfigPreview();
            this.initializeSortables();
            this.countExistingSections();
            console.log('[WCAG-WP] onDOMReady() completed');
        }

        /**
         * Bind configuration events
         */
        bindConfigurationEvents() {
            // Configuration checkboxes
            const configCheckboxes = document.querySelectorAll('.wcag-wp-accordion-config input[type="checkbox"]');
            configCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    this.updateConfigPreview();
                });
            });
        }

        /**
         * Bind section management events
         */
        bindSectionEvents() {
            console.log('[WCAG-WP] bindSectionEvents() called');
            // Add new section
            const addSectionBtn = document.getElementById('add-new-section');
            console.log('[WCAG-WP] Looking for add-new-section button:', addSectionBtn);
            if (addSectionBtn) {
                console.log('[WCAG-WP] add-new-section button found, adding event listener');
                addSectionBtn.addEventListener('click', () => {
                    console.log('[WCAG-WP] Add new section button clicked!');
                    this.addNewSection();
                });
            } else {
                console.error('[WCAG-WP] add-new-section button NOT FOUND!');
            }

            // Event delegation for dynamic elements
            document.addEventListener('click', (e) => {
                if (e.target.closest('.section-delete')) {
                    this.deleteSection(e);
                } else if (e.target.closest('.section-toggle')) {
                    this.toggleSection(e);
                }
            });

            // Update section titles on input
            document.addEventListener('input', (e) => {
                if (e.target.classList.contains('section-title-input')) {
                    this.updateSectionTitle(e);
                }
            });

            // Update section status badges
            document.addEventListener('change', (e) => {
                if (e.target.name && e.target.name.includes('[is_open]')) {
                    this.updateSectionBadges(e);
                } else if (e.target.name && e.target.name.includes('[disabled]')) {
                    this.updateSectionBadges(e);
                }
            });
        }

        /**
         * Bind preview events
         */
        bindPreviewEvents() {
            // Copy shortcode
            document.addEventListener('click', (e) => {
                if (e.target.closest('.copy-shortcode')) {
                    this.copyShortcode(e);
                } else if (e.target.closest('.copy-code')) {
                    this.copyCode(e);
                }
            });

            // Refresh preview
            const refreshBtn = document.getElementById('refresh-accordion-preview');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', () => {
                    this.refreshPreview();
                });
            }
        }

        /**
         * Initialize features
         */
        initializeFeatures() {
            this.updateConfigPreview();
        }

        /**
         * Update configuration preview
         */
        updateConfigPreview() {
            const features = ['keyboard_navigation', 'animate_transitions', 'allow_multiple_open', 'first_panel_open'];
            
            features.forEach(feature => {
                const checkbox = document.querySelector(`input[name="wcag_wp_accordion_config[${feature}]"]`);
                const previewElement = document.querySelector(`.preview-feature[data-feature="${feature}"]`);
                
                if (checkbox && previewElement) {
                    if (checkbox.checked) {
                        previewElement.classList.add('active');
                    } else {
                        previewElement.classList.remove('active');
                    }
                }
            });
        }

        /**
         * Add new section
         */
        addNewSection() {
            const template = document.getElementById('section-template');
            if (!template) {
                console.error('[WCAG-WP] Section template not found');
                return;
            }

            console.log(`[WCAG-WP] Adding new section with index: ${this.sectionIndex}`);
            
            // Clone the template content
            const templateContent = template.innerHTML;
            console.log('[WCAG-WP] Original template:', templateContent.substring(0, 200));
            
            // Replace all placeholders with current index
            const newSection = templateContent.replace(/\{\{INDEX\}\}/g, this.sectionIndex);
            console.log('[WCAG-WP] After replacement:', newSection.substring(0, 200));
            
            const container = document.getElementById('sections-container');
            const noSectionsMessage = container.querySelector('.no-sections-message');
            
            if (noSectionsMessage) {
                noSectionsMessage.remove();
            }
            
            // Create a temporary container and parse the HTML
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = newSection;
            const sectionElement = tempDiv.firstElementChild;

            // Enable inputs from template and set proper name/required attributes
            const templatedFields = sectionElement.querySelectorAll('[data-name]');
            templatedFields.forEach(el => {
                const dataName = el.getAttribute('data-name');
                if (dataName) {
                    el.setAttribute('name', dataName);
                }
                el.removeAttribute('disabled');
                // Re-add required only for specific fields
                if (el.classList.contains('section-id-input') || el.classList.contains('section-title-input')) {
                    el.setAttribute('required', 'required');
                }
            });
            
            if (!sectionElement) {
                console.error('[WCAG-WP] Failed to create section element');
                return;
            }
            
            // Verify that placeholders were replaced in the actual element
            const inputs = sectionElement.querySelectorAll('input, textarea');
            let hasUnreplacedPlaceholders = false;
            inputs.forEach(input => {
                if (input.name && input.name.includes('{{INDEX}}')) {
                    console.error(`[WCAG-WP] Unreplaced placeholder in: ${input.name}`);
                    hasUnreplacedPlaceholders = true;
                }
            });
            
            if (hasUnreplacedPlaceholders) {
                console.error('[WCAG-WP] Section contains unreplaced placeholders, aborting');
                return;
            }
            
            container.appendChild(sectionElement);
            
            this.sectionIndex++;
            
            // Focus on the first input of the new section
            const firstInput = sectionElement.querySelector('.section-id-input');
            if (firstInput) {
                firstInput.focus();
            }

            this.announceToScreenReader('Nuova sezione WCAG Accordion aggiunta');
        }

        /**
         * Delete section
         */
        deleteSection(e) {
            if (confirm(this.config.strings.confirm_delete_section)) {
                const section = e.target.closest('.section-editor');
                section.remove();
                
                // Show no sections message if no sections left
                const container = document.getElementById('sections-container');
                if (!container.querySelector('.section-editor')) {
                    this.showNoSectionsMessage(container);
                }

                this.announceToScreenReader('Sezione WCAG Accordion eliminata');
            }
        }

        /**
         * Toggle section expanded/collapsed
         */
        toggleSection(e) {
            const section = e.target.closest('.section-editor');
            const content = section.querySelector('.section-content');
            const icon = e.target.closest('.section-toggle').querySelector('.dashicons');
            
            content.classList.toggle('collapsed');
            if (content.classList.contains('collapsed')) {
                icon.className = 'dashicons dashicons-arrow-down-alt2';
                e.target.closest('.section-toggle').setAttribute('aria-expanded', 'false');
            } else {
                icon.className = 'dashicons dashicons-arrow-up-alt2';
                e.target.closest('.section-toggle').setAttribute('aria-expanded', 'true');
            }
        }

        /**
         * Update section title in header
         */
        updateSectionTitle(e) {
            const section = e.target.closest('.section-editor');
            const labelSpan = section.querySelector('.section-label');
            labelSpan.textContent = e.target.value || 'Nuova Sezione WCAG';
        }

        /**
         * Update section status badges
         */
        updateSectionBadges(e) {
            const section = e.target.closest('.section-editor');
            const title = section.querySelector('.section-title h4');
            
            // Remove existing badges
            const existingBadges = title.querySelectorAll('.section-status-badge');
            existingBadges.forEach(badge => badge.remove());
            
            // Get current values
            const isOpen = section.querySelector('input[name*="[is_open]"]')?.checked;
            const isDisabled = section.querySelector('input[name*="[disabled]"]')?.checked;
            
            // Add badges based on current state
            if (isOpen) {
                const openBadge = document.createElement('span');
                openBadge.className = 'section-status-badge open';
                openBadge.textContent = 'Aperta';
                title.appendChild(openBadge);
            }
            
            if (isDisabled) {
                const disabledBadge = document.createElement('span');
                disabledBadge.className = 'section-status-badge disabled';
                disabledBadge.textContent = 'Disabilitata';
                title.appendChild(disabledBadge);
            }
        }

        /**
         * Copy shortcode to clipboard
         */
        copyShortcode(e) {
            const targetId = e.target.closest('.copy-shortcode').getAttribute('data-target');
            const input = document.getElementById(targetId);
            
            if (input) {
                this.copyToClipboard(input.value);
                this.showCopySuccess('Shortcode WCAG Accordion copiato!');
                this.announceToScreenReader('Shortcode WCAG Accordion copiato negli appunti');
            }
        }

        /**
         * Copy code to clipboard
         */
        copyCode(e) {
            const code = e.target.closest('.copy-code').getAttribute('data-code');
            if (code) {
                this.copyToClipboard(code.replace(/&quot;/g, '"'));
                this.showCopySuccess('Codice PHP copiato!');
                this.announceToScreenReader('Codice PHP copiato negli appunti');
            }
        }

        /**
         * Copy text to clipboard
         */
        copyToClipboard(text) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text);
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                } catch (err) {
                    console.error('Fallback copy failed', err);
                }
                document.body.removeChild(textArea);
            }
        }

        /**
         * Show copy success message
         */
        showCopySuccess(message) {
            const messageEl = document.createElement('div');
            messageEl.textContent = message;
            messageEl.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: #00a32a;
                color: white;
                padding: 10px 20px;
                border-radius: 4px;
                z-index: 999999;
                font-size: 14px;
                font-weight: 600;
            `;
            
            document.body.appendChild(messageEl);
            
            setTimeout(() => {
                messageEl.remove();
            }, 2000);
        }

        /**
         * Refresh preview
         */
        refreshPreview() {
            // Update mini preview based on current sections
            this.updateMiniPreview();
            this.announceToScreenReader('Anteprima WCAG Accordion aggiornata');
        }

        /**
         * Update mini preview
         */
        updateMiniPreview() {
            const preview = document.getElementById('accordion-mini-preview');
            if (!preview) return;

            const sections = document.querySelectorAll('.section-editor');
            let previewHTML = '';

            sections.forEach((section, index) => {
                const titleInput = section.querySelector('.section-title-input');
                const contentTextarea = section.querySelector('textarea[name*="[content]"]');
                const isOpenCheckbox = section.querySelector('input[name*="[is_open]"]');
                const isDisabledCheckbox = section.querySelector('input[name*="[disabled]"]');

                const title = titleInput?.value || 'Sezione senza titolo';
                const content = contentTextarea?.value || '';
                const isOpen = isOpenCheckbox?.checked || false;
                const isDisabled = isDisabledCheckbox?.checked || false;

                const openClass = isOpen ? 'open' : '';
                const disabledClass = isDisabled ? 'disabled' : '';

                previewHTML += `
                    <div class="preview-section-item ${openClass} ${disabledClass}">
                        <div class="preview-section-header">
                            <span class="preview-icon">›</span>
                            <span class="preview-title">${title}</span>
                        </div>
                        ${isOpen ? `
                            <div class="preview-section-content">
                                <p>${this.truncateText(this.stripTags(content), 15)}</p>
                            </div>
                        ` : ''}
                    </div>
                `;
            });

            if (!previewHTML) {
                previewHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nessuna sezione definita</p>';
            }

            preview.innerHTML = previewHTML;
        }

        /**
         * Initialize sortable functionality
         */
        initializeSortables() {
            // Initialize sortable sections if jQuery UI is available
            if (typeof jQuery !== 'undefined' && jQuery.ui && jQuery.ui.sortable) {
                jQuery('#sections-container').sortable({
                    items: '.section-editor',
                    handle: '.section-drag-handle',
                    placeholder: 'section-editor sortable-placeholder',
                    tolerance: 'pointer',
                    cursor: 'grabbing',
                    update: () => {
                        this.updateMiniPreview();
                    }
                });
            }
        }

        /**
         * Count existing sections to set index
         */
        countExistingSections() {
            const sections = document.querySelectorAll('.section-editor');
            this.sectionIndex = sections.length;
        }

        /**
         * Show no sections message
         */
        showNoSectionsMessage(container) {
            container.innerHTML = `
                <div class="no-sections-message">
                    <div class="no-sections-content">
                        <span class="dashicons dashicons-list-view"></span>
                        <h4>Nessuna sezione WCAG definita</h4>
                        <p>Clicca "Aggiungi Sezione WCAG" per iniziare a creare il tuo accordion accessibile.</p>
                    </div>
                </div>
            `;
        }

        /**
         * Strip HTML tags from text
         */
        stripTags(text) {
            const div = document.createElement('div');
            div.innerHTML = text;
            return div.textContent || div.innerText || '';
        }

        /**
         * Truncate text to specified word count
         */
        truncateText(text, wordCount) {
            const words = text.split(' ');
            if (words.length <= wordCount) return text;
            return words.slice(0, wordCount).join(' ') + '...';
        }

        /**
         * Announce message to screen readers
         */
        announceToScreenReader(message) {
            // Create temporary live region if it doesn't exist
            let announcer = document.getElementById('wcag-wp-accordion-announcements');
            if (!announcer) {
                announcer = document.createElement('div');
                announcer.id = 'wcag-wp-accordion-announcements';
                announcer.setAttribute('aria-live', 'polite');
                announcer.setAttribute('aria-atomic', 'true');
                announcer.style.cssText = 'position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden;';
                document.body.appendChild(announcer);
            }

            announcer.textContent = message;
            
            // Clear after announcement
            setTimeout(() => {
                announcer.textContent = '';
            }, 1000);
        }

        /**
         * Logging utility
         */
        log(message, level = 'info') {
            if (console && typeof console.log === 'function') {
                const timestamp = new Date().toISOString();
                const logMessage = `[${timestamp}] WCAG-WP Accordion Admin (${level}): ${message}`;
                
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
            window.wcagWpAccordionAdmin = new WcagWpAccordionAdmin();
        });
    } else {
        window.wcagWpAccordionAdmin = new WcagWpAccordionAdmin();
    }

})();
