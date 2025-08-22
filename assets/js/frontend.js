/**
 * WCAG-WP Frontend JavaScript
 * 
 * Vanilla JavaScript for public-facing components
 * Follows WCAG 2.1 AA accessibility guidelines
 * No external dependencies - Pure JavaScript
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

'use strict';

(function() {
    // Ensure wcag_wp object exists
    if (typeof wcag_wp === 'undefined') {
        console.warn('WCAG-WP: Frontend configuration object not found');
        return;
    }

    /**
     * WCAG-WP Frontend Class
     */
    class WcagWpFrontend {
        constructor() {
            this.config = wcag_wp;
            this.components = new Map();
            this.init();
        }

        /**
         * Initialize frontend functionality
         */
        init() {
            this.setupAccessibilityFeatures();
            this.initializeComponents();
            this.bindGlobalEvents();
            
            this.log('Frontend components initialized', 'info');
        }

        /**
         * Setup global accessibility features
         */
        setupAccessibilityFeatures() {
            this.addSkipLinks();
            this.setupFocusManagement();
            this.setupKeyboardNavigation();
            this.setupScreenReaderAnnouncements();
            this.respectUserPreferences();
        }

        /**
         * Initialize all WCAG components
         */
        initializeComponents() {
            // Initialize tables
            this.initializeTables();
            
            // Initialize buttons
            this.initializeButtons();
            
            // Initialize notifications
            this.initializeNotifications();
            
            // Initialize pagination
            this.initializePagination();
            
            // Future components will be added here
            // this.initializeAccordions();
            // this.initializeTabPanels();
            // this.initializeCarousels();
        }

        /**
         * Initialize accessible tables
         */
        initializeTables() {
            const tables = document.querySelectorAll('.wcag-wp-table-container');

            const getBoolAttr = (el, name) => {
                if (!el.hasAttribute(name)) return false;
                const v = el.getAttribute(name);
                return String(v).toLowerCase() === 'true';
            };

            tables.forEach((container, index) => {
                const table = new WcagWpTable(container, {
                    sortable: getBoolAttr(container, 'data-sortable'),
                    searchable: getBoolAttr(container, 'data-searchable'),
                    responsive: getBoolAttr(container, 'data-responsive'),
                    stackOnMobile: getBoolAttr(container, 'data-stack-mobile')
                });

                this.components.set(`table-${index}`, table);
            });
        }

        /**
         * Initialize accessible buttons
         */
        initializeButtons() {
            const buttons = document.querySelectorAll('.wcag-wp-button');
            
            buttons.forEach(button => {
                // Ensure minimum touch target size
                this.ensureTouchTargetSize(button);
                
                // Add loading state management
                if (button.hasAttribute('data-loading-text')) {
                    this.setupButtonLoadingState(button);
                }
                
                // Setup confirmation dialogs
                if (button.hasAttribute('data-confirm')) {
                    this.setupButtonConfirmation(button);
                }
            });
        }

        /**
         * Initialize notifications
         */
        initializeNotifications() {
            const notifications = document.querySelectorAll('.wcag-wp-notification');
            
            notifications.forEach(notification => {
                // Auto-dismiss if specified
                const autoDismiss = notification.getAttribute('data-auto-dismiss');
                if (autoDismiss) {
                    setTimeout(() => {
                        this.dismissNotification(notification);
                    }, parseInt(autoDismiss) * 1000);
                }
                
                // Add close button if dismissible
                if (notification.hasAttribute('data-dismissible')) {
                    this.addNotificationCloseButton(notification);
                }
            });
        }

        /**
         * Initialize pagination
         */
        initializePagination() {
            const paginations = document.querySelectorAll('.wcag-wp-pagination');
            
            paginations.forEach(pagination => {
                // Add ARIA attributes
                pagination.setAttribute('role', 'navigation');
                pagination.setAttribute('aria-label', 'Navigazione paginazione');
                
                // Setup keyboard navigation
                this.setupPaginationKeyboardNav(pagination);
            });
        }

        /**
         * Add skip links for accessibility
         */
        addSkipLinks() {
            const skipLinks = [
                { href: '#main', text: 'Salta al contenuto principale' },
                { href: '#navigation', text: 'Salta alla navigazione' }
            ];

            const skipContainer = document.createElement('div');
            skipContainer.className = 'wcag-wp-skip-links';

            skipLinks.forEach(link => {
                const skipLink = document.createElement('a');
                skipLink.href = link.href;
                skipLink.textContent = link.text;
                skipLink.className = 'wcag-wp skip-link';
                skipContainer.appendChild(skipLink);
            });

            document.body.insertBefore(skipContainer, document.body.firstChild);
        }

        /**
         * Setup focus management
         */
        setupFocusManagement() {
            // Track focus for keyboard users
            let keyboardUser = false;

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    keyboardUser = true;
                    document.body.classList.add('wcag-wp-keyboard-user');
                }
            });

            document.addEventListener('mousedown', () => {
                if (keyboardUser) {
                    keyboardUser = false;
                    document.body.classList.remove('wcag-wp-keyboard-user');
                }
            });

            // Focus trap for modals (when implemented)
            this.setupFocusTrap();
        }

        /**
         * Setup global keyboard navigation
         */
        setupKeyboardNavigation() {
            document.addEventListener('keydown', (e) => {
                // Handle global keyboard shortcuts
                switch (e.key) {
                    case 'Escape':
                        this.handleEscapeKey(e);
                        break;
                    case 'Enter':
                        this.handleEnterKey(e);
                        break;
                    case ' ':
                        this.handleSpaceKey(e);
                        break;
                }
            });
        }

        /**
         * Setup screen reader announcements
         */
        setupScreenReaderAnnouncements() {
            // Create live region for announcements
            if (!document.getElementById('wcag-wp-announcements')) {
                const announcer = document.createElement('div');
                announcer.id = 'wcag-wp-announcements';
                announcer.setAttribute('aria-live', 'polite');
                announcer.setAttribute('aria-atomic', 'true');
                announcer.className = 'wcag-wp sr-only';
                document.body.appendChild(announcer);
            }
        }

        /**
         * Respect user preferences
         */
        respectUserPreferences() {
            // Reduced motion
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                document.body.classList.add('wcag-wp-reduce-motion');
            }

            // High contrast
            if (window.matchMedia && window.matchMedia('(prefers-contrast: high)').matches) {
                document.body.classList.add('wcag-wp-high-contrast');
            }

            // Dark mode
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.body.classList.add('wcag-wp-dark-mode');
            }
        }

        /**
         * Bind global events
         */
        bindGlobalEvents() {
            // Window resize for responsive adjustments
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    this.handleResize();
                }, 250);
            });

            // Page visibility changes
            document.addEventListener('visibilitychange', () => {
                this.handleVisibilityChange();
            });
        }

        /**
         * Handle window resize
         */
        handleResize() {
            // Update responsive tables
            this.components.forEach((component, key) => {
                if (key.startsWith('table-') && component.updateResponsive) {
                    component.updateResponsive();
                }
            });
        }

        /**
         * Handle visibility change
         */
        handleVisibilityChange() {
            if (document.hidden) {
                // Pause any animations/timers when page is hidden
                this.pauseComponents();
            } else {
                // Resume when page becomes visible
                this.resumeComponents();
            }
        }

        /**
         * Ensure minimum touch target size (44x44px for WCAG)
         */
        ensureTouchTargetSize(element) {
            const rect = element.getBoundingClientRect();
            if (rect.width < 44 || rect.height < 44) {
                element.style.minWidth = '44px';
                element.style.minHeight = '44px';
            }
        }

        /**
         * Setup button loading state
         */
        setupButtonLoadingState(button) {
            button.addEventListener('click', () => {
                if (button.hasAttribute('data-loading')) return;

                const originalText = button.textContent;
                const loadingText = button.getAttribute('data-loading-text');

                button.setAttribute('data-loading', 'true');
                button.disabled = true;
                button.textContent = loadingText;
                button.classList.add('wcag-wp-loading');

                // Auto-restore after timeout if no manual restore
                setTimeout(() => {
                    this.restoreButtonState(button, originalText);
                }, 30000); // 30 second timeout
            });
        }

        /**
         * Restore button state
         */
        restoreButtonState(button, originalText) {
            button.removeAttribute('data-loading');
            button.disabled = false;
            button.textContent = originalText;
            button.classList.remove('wcag-wp-loading');
        }

        /**
         * Setup button confirmation
         */
        setupButtonConfirmation(button) {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                const confirmText = button.getAttribute('data-confirm');
                if (confirm(confirmText)) {
                    // Proceed with original action
                    const href = button.getAttribute('href');
                    if (href) {
                        window.location.href = href;
                    }
                }
            });
        }

        /**
         * Dismiss notification
         */
        dismissNotification(notification) {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                notification.remove();
            }, 300);
        }

        /**
         * Add close button to notification
         */
        addNotificationCloseButton(notification) {
            const closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.className = 'wcag-wp-notification-close';
            closeButton.innerHTML = '&times;';
            closeButton.setAttribute('aria-label', 'Chiudi notifica');
            
            closeButton.addEventListener('click', () => {
                this.dismissNotification(notification);
            });
            
            notification.appendChild(closeButton);
        }

        /**
         * Setup pagination keyboard navigation
         */
        setupPaginationKeyboardNav(pagination) {
            const links = pagination.querySelectorAll('a');
            
            links.forEach((link, index) => {
                link.addEventListener('keydown', (e) => {
                    switch (e.key) {
                        case 'ArrowLeft':
                            e.preventDefault();
                            if (index > 0) {
                                links[index - 1].focus();
                            }
                            break;
                        case 'ArrowRight':
                            e.preventDefault();
                            if (index < links.length - 1) {
                                links[index + 1].focus();
                            }
                            break;
                        case 'Home':
                            e.preventDefault();
                            links[0].focus();
                            break;
                        case 'End':
                            e.preventDefault();
                            links[links.length - 1].focus();
                            break;
                    }
                });
            });
        }

        /**
         * Setup focus trap for modals
         */
        setupFocusTrap() {
            // Implementation for future modal components
        }

        /**
         * Handle global keyboard events
         */
        handleEscapeKey(e) {
            // Close any open modals, dropdowns, etc.
            const activeModal = document.querySelector('.wcag-wp-modal.active');
            if (activeModal) {
                this.closeModal(activeModal);
            }
        }

        handleEnterKey(e) {
            // Handle Enter key on custom interactive elements
            if (e.target.hasAttribute('role') && e.target.getAttribute('role') === 'button') {
                e.target.click();
            }
        }

        handleSpaceKey(e) {
            // Handle Space key on custom interactive elements
            if (e.target.hasAttribute('role') && e.target.getAttribute('role') === 'button') {
                e.preventDefault();
                e.target.click();
            }
        }

        /**
         * Announce message to screen readers
         */
        announceToScreenReader(message, priority = 'polite') {
            const announcer = document.getElementById('wcag-wp-announcements');
            if (announcer) {
                announcer.setAttribute('aria-live', priority);
                announcer.textContent = message;
                
                // Clear after announcement
                setTimeout(() => {
                    announcer.textContent = '';
                }, 1000);
            }
        }

        /**
         * Pause components (when page hidden)
         */
        pauseComponents() {
            this.components.forEach(component => {
                if (component.pause && typeof component.pause === 'function') {
                    component.pause();
                }
            });
        }

        /**
         * Resume components (when page visible)
         */
        resumeComponents() {
            this.components.forEach(component => {
                if (component.resume && typeof component.resume === 'function') {
                    component.resume();
                }
            });
        }

        /**
         * Logging utility
         */
        log(message, level = 'info') {
            if (console && typeof console.log === 'function') {
                const timestamp = new Date().toISOString();
                const logMessage = `[${timestamp}] WCAG-WP Frontend (${level}): ${message}`;
                
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

    /**
     * WCAG Table Component
     */
    class WcagWpTable {
        constructor(container, options = {}) {
            this.container = container;
            this.table = container.querySelector('.wcag-wp-table');
            this.options = {
                sortable: false,
                searchable: false,
                responsive: true,
                stackOnMobile: false,
                ...options
            };
            
            this.currentSort = { column: null, direction: 'asc' };
            this.originalRows = [];
            
            this.init();
        }

        init() {
            if (!this.table) return;

            this.setupTable();
            if (this.options.sortable) this.setupSorting();
            if (this.options.searchable) this.setupSearch();
            if (this.options.responsive) this.setupResponsive();
            
            this.announceToScreenReader('Tabella caricata e pronta per l\'interazione');
        }

        setupTable() {
            // Store original rows for searching/sorting
            const tbody = this.table.querySelector('tbody');
            if (tbody) {
                this.originalRows = Array.from(tbody.rows);
            }

            // Add ARIA attributes
            this.table.setAttribute('role', 'table');
            
            const caption = this.table.querySelector('caption');
            if (!caption) {
                const title = this.container.querySelector('.wcag-wp-table-title');
                if (title) {
                    this.table.setAttribute('aria-label', title.textContent.trim());
                }
            }
        }

        setupSorting() {
            const headers = this.table.querySelectorAll('th[data-sortable], th[aria-sort]');
            
            headers.forEach((header, index) => {
                header.setAttribute('aria-sort', 'none');
                header.setAttribute('tabindex', '0');
                header.style.cursor = 'pointer';
                
                header.addEventListener('click', () => this.sortTable(index));
                header.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.sortTable(index);
                    }
                });
            });
        }

        setupSearch() {
            const searchInput = this.container.querySelector('.wcag-wp-search-input');
            if (!searchInput) return;

            searchInput.addEventListener('input', (e) => {
                this.searchTable(e.target.value);
            });
        }

        setupResponsive() {
            if (this.options.stackOnMobile) {
                this.table.classList.add('stack-on-mobile');
                this.addDataLabels();
            }
        }

        sortTable(columnIndex) {
            const tbody = this.table.querySelector('tbody');
            if (!tbody) return;

            const rows = Array.from(tbody.rows);
            const headers = this.table.querySelectorAll('th');
            const header = headers[columnIndex];
            
            // Determine sort direction
            let direction = 'asc';
            if (this.currentSort.column === columnIndex && this.currentSort.direction === 'asc') {
                direction = 'desc';
            }

            // Clear previous sort indicators
            headers.forEach(h => h.setAttribute('aria-sort', 'none'));
            header.setAttribute('aria-sort', direction);

            // Sort rows
            rows.sort((a, b) => {
                const aVal = a.cells[columnIndex].textContent.trim();
                const bVal = b.cells[columnIndex].textContent.trim();
                
                // Check if values are numbers
                const aNum = parseFloat(aVal);
                const bNum = parseFloat(bVal);
                
                if (!isNaN(aNum) && !isNaN(bNum)) {
                    return direction === 'asc' ? aNum - bNum : bNum - aNum;
                }
                
                // String comparison
                const comparison = aVal.localeCompare(bVal);
                return direction === 'asc' ? comparison : -comparison;
            });

            // Update table
            rows.forEach(row => tbody.appendChild(row));
            
            this.currentSort = { column: columnIndex, direction };
            this.announceToScreenReader(`Tabella ordinata per ${header.textContent.trim()} in ordine ${direction === 'asc' ? 'crescente' : 'decrescente'}`);
        }

        searchTable(query) {
            const tbody = this.table.querySelector('tbody');
            if (!tbody) return;

            const rows = Array.from(tbody.rows);
            const searchTerm = query.toLowerCase().trim();
            
            let visibleCount = 0;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const isVisible = !searchTerm || text.includes(searchTerm);
                
                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });

            this.announceToScreenReader(`${visibleCount} righe trovate per "${query}"`);
        }

        addDataLabels() {
            const headers = this.table.querySelectorAll('th');
            const rows = this.table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                Array.from(row.cells).forEach((cell, index) => {
                    if (headers[index]) {
                        cell.setAttribute('data-label', headers[index].textContent.trim());
                    }
                });
            });
        }

        updateResponsive() {
            // Called on window resize
            if (this.options.responsive) {
                // Additional responsive logic if needed
            }
        }

        announceToScreenReader(message) {
            const announcer = document.getElementById('wcag-wp-announcements');
            if (announcer) {
                announcer.textContent = message;
                setTimeout(() => announcer.textContent = '', 1000);
            }
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.wcagWpFrontend = new WcagWpFrontend();
        });
    } else {
        window.wcagWpFrontend = new WcagWpFrontend();
    }

})();
