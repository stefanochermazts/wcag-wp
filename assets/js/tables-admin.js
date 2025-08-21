/**
 * WCAG-WP Admin Tables JavaScript
 * 
 * Vanilla JavaScript for WCAG tables administration
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

'use strict';

(function() {
    // Ensure wcag_wp_tables object exists
    if (typeof wcag_wp_tables === 'undefined') {
        console.warn('WCAG-WP Tables: Configuration object not found');
        return;
    }

    /**
     * WCAG Tables Admin Class
     */
    class WcagWpTablesAdmin {
        constructor() {
            this.config = wcag_wp_tables;
            this.columnIndex = 0;
            this.rowIndex = 0;
            this.init();
        }

        /**
         * Initialize admin functionality
         */
        init() {
            this.bindEvents();
            this.initializeFeatures();
            this.log('WCAG Tables admin interface initialized', 'info');
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

            // Configuration preview updates
            this.bindConfigurationEvents();
            
            // Column management
            this.bindColumnEvents();
            
            // Data management
            this.bindDataEvents();
            
            // Preview functionality
            this.bindPreviewEvents();
        }

        /**
         * DOM Ready handler
         */
        onDOMReady() {
            this.updateConfigPreview();
            this.initializeSortables();
            this.countExistingItems();
        }

        /**
         * Bind configuration events
         */
        bindConfigurationEvents() {
            // Configuration checkboxes
            const configCheckboxes = document.querySelectorAll('.wcag-wp-table-config input[type="checkbox"]');
            configCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    this.updateConfigPreview();
                });
            });

            // Pagination toggle
            const paginationCheckbox = document.getElementById('enable_pagination');
            if (paginationCheckbox) {
                paginationCheckbox.addEventListener('change', () => {
                    this.togglePaginationOptions();
                });
            }
        }

        /**
         * Bind column management events
         */
        bindColumnEvents() {
            // Add new column
            const addColumnBtn = document.getElementById('add-new-column');
            if (addColumnBtn) {
                addColumnBtn.addEventListener('click', () => {
                    this.addNewColumn();
                });
            }

            // Event delegation for dynamic elements
            document.addEventListener('click', (e) => {
                if (e.target.closest('.column-delete')) {
                    this.deleteColumn(e);
                } else if (e.target.closest('.column-toggle')) {
                    this.toggleColumn(e);
                }
            });

            // Update column titles and badges on input
            document.addEventListener('input', (e) => {
                if (e.target.classList.contains('column-label-input')) {
                    this.updateColumnTitle(e);
                }
            });

            document.addEventListener('change', (e) => {
                if (e.target.classList.contains('column-type-select')) {
                    this.updateColumnTypeBadge(e);
                }
            });
        }

        /**
         * Bind data management events
         */
        bindDataEvents() {
            // Add new row
            const addRowBtn = document.getElementById('add-new-row');
            if (addRowBtn) {
                addRowBtn.addEventListener('click', () => {
                    this.addNewRow();
                });
            }

            // Event delegation for row actions
            document.addEventListener('click', (e) => {
                if (e.target.closest('.row-delete')) {
                    this.deleteRow(e);
                } else if (e.target.closest('.row-duplicate')) {
                    this.duplicateRow(e);
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
                }
            });

            // Refresh preview
            const refreshBtn = document.getElementById('refresh-preview');
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
            this.togglePaginationOptions();
        }

        /**
         * Update configuration preview
         */
        updateConfigPreview() {
            const features = ['sortable', 'searchable', 'responsive', 'export_csv', 'pagination'];
            
            features.forEach(feature => {
                const checkbox = document.querySelector(`input[name="wcag_wp_table_config[${feature}]"]`);
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
         * Toggle pagination options visibility
         */
        togglePaginationOptions() {
            const paginationCheckbox = document.getElementById('enable_pagination');
            const paginationOptions = document.getElementById('pagination_options');
            
            if (paginationCheckbox && paginationOptions) {
                paginationOptions.style.display = paginationCheckbox.checked ? 'block' : 'none';
            }
        }

        /**
         * Add new column
         */
        addNewColumn() {
            const template = document.getElementById('column-template');
            if (!template) return;

            const newColumn = template.innerHTML.replace(/\{\{INDEX\}\}/g, this.columnIndex);
            
            const container = document.getElementById('columns-container');
            const noColumnsMessage = container.querySelector('.no-columns-message');
            
            if (noColumnsMessage) {
                noColumnsMessage.remove();
            }
            
            const div = document.createElement('div');
            div.innerHTML = newColumn;
            const columnElement = div.firstElementChild;
            container.appendChild(columnElement);
            
            this.columnIndex++;
            
            // Focus on the first input of the new column
            const firstInput = columnElement.querySelector('.column-id-input');
            if (firstInput) {
                firstInput.focus();
            }

            this.announceToScreenReader('Nuova colonna WCAG aggiunta');
        }

        /**
         * Delete column
         */
        deleteColumn(e) {
            if (confirm(this.config.strings.confirm_delete_column)) {
                const column = e.target.closest('.column-editor');
                column.remove();
                
                // Show no columns message if no columns left
                const container = document.getElementById('columns-container');
                if (!container.querySelector('.column-editor')) {
                    this.showNoColumnsMessage(container);
                }

                this.announceToScreenReader('Colonna WCAG eliminata');
            }
        }

        /**
         * Toggle column expanded/collapsed
         */
        toggleColumn(e) {
            const column = e.target.closest('.column-editor');
            const content = column.querySelector('.column-content');
            const icon = e.target.closest('.column-toggle').querySelector('.dashicons');
            
            content.classList.toggle('collapsed');
            if (content.classList.contains('collapsed')) {
                icon.className = 'dashicons dashicons-arrow-down-alt2';
                e.target.closest('.column-toggle').setAttribute('aria-expanded', 'false');
            } else {
                icon.className = 'dashicons dashicons-arrow-up-alt2';
                e.target.closest('.column-toggle').setAttribute('aria-expanded', 'true');
            }
        }

        /**
         * Update column title in header
         */
        updateColumnTitle(e) {
            const column = e.target.closest('.column-editor');
            const labelSpan = column.querySelector('.column-label');
            labelSpan.textContent = e.target.value || 'Nuova Colonna WCAG';
        }

        /**
         * Update column type badge
         */
        updateColumnTypeBadge(e) {
            const column = e.target.closest('.column-editor');
            const typeBadge = column.querySelector('.column-type-badge');
            typeBadge.textContent = e.target.options[e.target.selectedIndex].text;
        }

        /**
         * Add new row
         */
        addNewRow() {
            const template = document.querySelector('#row-template tbody');
            if (!template) return;

            const newRow = template.innerHTML.replace(/\{\{INDEX\}\}/g, this.rowIndex);
            
            const tbody = document.getElementById('data-table-body');
            const noDataRow = tbody.querySelector('.no-data-row');
            
            if (noDataRow) {
                noDataRow.remove();
            }
            
            const div = document.createElement('div');
            div.innerHTML = '<table><tbody>' + newRow + '</tbody></table>';
            const newRowElement = div.querySelector('tr');
            
            tbody.appendChild(newRowElement);
            this.rowIndex++;
            
            // Focus on first input
            const firstInput = newRowElement.querySelector('.data-input');
            if (firstInput) {
                firstInput.focus();
            }

            this.announceToScreenReader('Nuova riga WCAG aggiunta');
        }

        /**
         * Delete row
         */
        deleteRow(e) {
            if (confirm(this.config.strings.confirm_delete_row)) {
                const row = e.target.closest('.data-row');
                row.remove();
                
                // Show no data message if no rows left
                const tbody = document.getElementById('data-table-body');
                if (!tbody.querySelector('.data-row')) {
                    this.showNoDataMessage(tbody);
                }

                this.announceToScreenReader('Riga WCAG eliminata');
            }
        }

        /**
         * Duplicate row
         */
        duplicateRow(e) {
            const row = e.target.closest('.data-row');
            const newRow = row.cloneNode(true);
            
            // Update indices
            newRow.setAttribute('data-index', this.rowIndex);
            const inputs = newRow.querySelectorAll('.data-input');
            inputs.forEach(input => {
                const oldName = input.name;
                const newName = oldName.replace(/\[\d+\]/, `[${this.rowIndex}]`);
                input.name = newName;
                input.id = input.id.replace(/_\d+_/, `_${this.rowIndex}_`);
            });
            
            row.parentNode.insertBefore(newRow, row.nextSibling);
            this.rowIndex++;

            this.announceToScreenReader('Riga WCAG duplicata');
        }

        /**
         * Copy shortcode to clipboard
         */
        copyShortcode(e) {
            const targetId = e.target.closest('.copy-shortcode').getAttribute('data-target');
            const input = document.getElementById(targetId);
            
            if (input) {
                input.select();
                document.execCommand('copy');
                
                this.showCopySuccess();
                this.announceToScreenReader('Shortcode WCAG copiato negli appunti');
            }
        }

        /**
         * Show copy success message
         */
        showCopySuccess() {
            const message = document.createElement('div');
            message.textContent = 'Shortcode WCAG copiato!';
            message.style.cssText = `
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
            
            document.body.appendChild(message);
            
            setTimeout(() => {
                message.remove();
            }, 2000);
        }

        /**
         * Refresh preview
         */
        refreshPreview() {
            const iframe = document.getElementById('preview-iframe');
            if (iframe) {
                iframe.src = iframe.src;
                this.announceToScreenReader('Anteprima WCAG tabella aggiornata');
            }
        }

        /**
         * Initialize sortable functionality
         */
        initializeSortables() {
            // Initialize sortable columns if jQuery UI is available
            if (typeof jQuery !== 'undefined' && jQuery.ui && jQuery.ui.sortable) {
                jQuery('#columns-container').sortable({
                    items: '.column-editor',
                    handle: '.column-drag-handle',
                    placeholder: 'column-editor sortable-placeholder',
                    tolerance: 'pointer',
                    cursor: 'grabbing'
                });

                jQuery('#data-table-body').sortable({
                    items: '.data-row',
                    handle: '.row-handle',
                    cursor: 'grabbing',
                    helper: 'clone',
                    tolerance: 'pointer'
                });
            }
        }

        /**
         * Count existing items to set indices
         */
        countExistingItems() {
            const columns = document.querySelectorAll('.column-editor');
            this.columnIndex = columns.length;

            const rows = document.querySelectorAll('.data-row');
            this.rowIndex = rows.length;
        }

        /**
         * Show no columns message
         */
        showNoColumnsMessage(container) {
            container.innerHTML = `
                <div class="no-columns-message">
                    <div class="no-columns-content">
                        <span class="dashicons dashicons-grid-view"></span>
                        <h4>Nessuna colonna WCAG definita</h4>
                        <p>Clicca "Aggiungi Colonna" per iniziare a creare la tua WCAG tabella accessibile.</p>
                    </div>
                </div>
            `;
        }

        /**
         * Show no data message
         */
        showNoDataMessage(tbody) {
            const columnsCount = document.querySelectorAll('.column-header').length;
            tbody.innerHTML = `
                <tr class="no-data-row">
                    <td colspan="${columnsCount + 2}" class="no-data-cell">
                        <div class="no-data-content">
                            <span class="dashicons dashicons-database"></span>
                            <p>Nessun dato inserito nella WCAG tabella. Clicca "Aggiungi Riga" per iniziare.</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        /**
         * Announce message to screen readers
         */
        announceToScreenReader(message) {
            // Create temporary live region if it doesn't exist
            let announcer = document.getElementById('wcag-wp-admin-announcements');
            if (!announcer) {
                announcer = document.createElement('div');
                announcer.id = 'wcag-wp-admin-announcements';
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
                const logMessage = `[${timestamp}] WCAG-WP Tables Admin (${level}): ${message}`;
                
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
            window.wcagWpTablesAdmin = new WcagWpTablesAdmin();
        });
    } else {
        window.wcagWpTablesAdmin = new WcagWpTablesAdmin();
    }

})();
