/**
 * WCAG Toolbar Component - Admin JavaScript
 * 
 * Gestione dinamica dell'interfaccia admin per il componente Toolbar
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * WCAG Toolbar Admin Class
     */
    class WcagToolbarAdmin {
        constructor() {
            this.groupIndex = 0;
            this.controlIndex = 0;
            this.init();
        }

        /**
         * Initialize the admin interface
         */
        init() {
            this.bindEvents();
            this.initializeSortable();
            this.updateGroupNumbers();
            this.updateControlNumbers();
        }

        /**
         * Bind event listeners
         */
        bindEvents() {
            // Add group button
            $(document).on('click', '.wcag-wp-add-group', (e) => {
                e.preventDefault();
                this.addGroup();
            });

            // Add control button
            $(document).on('click', '.wcag-wp-add-control', (e) => {
                e.preventDefault();
                const groupIndex = $(e.target).data('group-index');
                this.addControl(groupIndex);
            });

            // Remove group button
            $(document).on('click', '.wcag-wp-remove-group', (e) => {
                e.preventDefault();
                this.removeGroup($(e.target).closest('.wcag-wp-group'));
            });

            // Remove control button
            $(document).on('click', '.wcag-wp-remove-control', (e) => {
                e.preventDefault();
                this.removeControl($(e.target).closest('.wcag-wp-control'));
            });

            // Toggle group button
            $(document).on('click', '.wcag-wp-toggle-group', (e) => {
                e.preventDefault();
                this.toggleGroup($(e.target).closest('.wcag-wp-group'));
            });

            // Toggle control button
            $(document).on('click', '.wcag-wp-toggle-control', (e) => {
                e.preventDefault();
                this.toggleControl($(e.target).closest('.wcag-wp-control'));
            });

            // Control type change
            $(document).on('change', '.wcag-wp-control-type', (e) => {
                this.handleControlTypeChange($(e.target));
            });

            // Control label change
            $(document).on('input', 'input[name*="[label]"]', (e) => {
                this.updateControlTitle($(e.target));
            });

            // Copy shortcode button
            $(document).on('click', '.wcag-wp-copy-shortcode', (e) => {
                e.preventDefault();
                this.copyShortcode($(e.target));
            });
        }

        /**
         * Initialize sortable functionality
         */
        initializeSortable() {
            // Make groups sortable
            $('#wcag-toolbar-groups').sortable({
                handle: '.wcag-wp-group-header',
                placeholder: 'wcag-wp-group ui-sortable-placeholder',
                helper: 'clone',
                opacity: 0.8,
                update: () => {
                    this.updateGroupNumbers();
                    this.updateGroupIndices();
                }
            });

            // Make controls sortable within each group
            $('.wcag-wp-controls').sortable({
                handle: '.wcag-wp-control-header',
                placeholder: 'wcag-wp-control ui-sortable-placeholder',
                helper: 'clone',
                opacity: 0.8,
                update: () => {
                    this.updateControlNumbers();
                    this.updateControlIndices();
                }
            });
        }

        /**
         * Add a new group
         */
        addGroup() {
            const template = $('#wcag-toolbar-group-template').html();
            const groupIndex = this.getNextGroupIndex();
            const groupNumber = this.getGroupCount() + 1;
            
            const newGroup = template
                .replace(/\{\{groupIndex\}\}/g, groupIndex)
                .replace(/\{\{groupNumber\}\}/g, groupNumber);

            $('#wcag-toolbar-groups').append(newGroup);
            
            // Initialize sortable for the new group
            const newGroupElement = $(`[data-group-index="${groupIndex}"]`);
            newGroupElement.find('.wcag-wp-controls').sortable({
                handle: '.wcag-wp-control-header',
                placeholder: 'wcag-wp-control ui-sortable-placeholder',
                helper: 'clone',
                opacity: 0.8,
                update: () => {
                    this.updateControlNumbers();
                    this.updateControlIndices();
                }
            });

            this.updateGroupNumbers();
            this.updateGroupIndices();
            
            // Focus on the new group label
            newGroupElement.find('input[name*="[label]"]').focus();
        }

        /**
         * Add a new control to a group
         */
        addControl(groupIndex) {
            const template = $('#wcag-toolbar-control-template').html();
            const controlIndex = this.getNextControlIndex();
            
            const newControl = template
                .replace(/\{\{groupIndex\}\}/g, groupIndex)
                .replace(/\{\{controlIndex\}\}/g, controlIndex);

            $(`.wcag-wp-controls[data-group-index="${groupIndex}"]`).append(newControl);
            
            this.updateControlNumbers();
            this.updateControlIndices();
            
            // Focus on the new control label
            $(`[data-control-index="${controlIndex}"]`).find('input[name*="[label]"]').focus();
        }

        /**
         * Remove a group
         */
        removeGroup(groupElement) {
            if (confirm('Sei sicuro di voler rimuovere questo gruppo? Questa azione non può essere annullata.')) {
                groupElement.fadeOut(300, () => {
                    groupElement.remove();
                    this.updateGroupNumbers();
                    this.updateGroupIndices();
                });
            }
        }

        /**
         * Remove a control
         */
        removeControl(controlElement) {
            if (confirm('Sei sicuro di voler rimuovere questo controllo? Questa azione non può essere annullata.')) {
                controlElement.fadeOut(300, () => {
                    controlElement.remove();
                    this.updateControlNumbers();
                    this.updateControlIndices();
                });
            }
        }

        /**
         * Toggle group visibility
         */
        toggleGroup(groupElement) {
            const content = groupElement.find('.wcag-wp-group-content');
            const button = groupElement.find('.wcag-wp-toggle-group');
            const isCollapsed = groupElement.hasClass('collapsed');

            if (isCollapsed) {
                content.slideDown(200);
                groupElement.removeClass('collapsed');
                button.text('Comprimi').attr('aria-expanded', 'true');
            } else {
                content.slideUp(200);
                groupElement.addClass('collapsed');
                button.text('Espandi').attr('aria-expanded', 'false');
            }
        }

        /**
         * Toggle control visibility
         */
        toggleControl(controlElement) {
            const content = controlElement.find('.wcag-wp-control-content');
            const button = controlElement.find('.wcag-wp-toggle-control');
            const isCollapsed = controlElement.hasClass('collapsed');

            if (isCollapsed) {
                content.slideDown(200);
                controlElement.removeClass('collapsed');
                button.text('Comprimi').attr('aria-expanded', 'true');
            } else {
                content.slideUp(200);
                controlElement.addClass('collapsed');
                button.text('Espandi').attr('aria-expanded', 'false');
            }
        }

        /**
         * Handle control type change
         */
        handleControlTypeChange(selectElement) {
            const controlElement = selectElement.closest('.wcag-wp-control');
            const controlType = selectElement.val();
            
            // Show/hide fields based on control type
            controlElement.find('.wcag-wp-control-field').hide();
            
            if (controlType === 'button') {
                controlElement.find('[data-field="label"], [data-field="icon"], [data-field="action"]').show();
            } else if (controlType === 'link') {
                controlElement.find('[data-field="label"], [data-field="icon"], [data-field="url"], [data-field="target"]').show();
            } else if (controlType === 'separator') {
                // Separators don't need additional fields
            }
        }

        /**
         * Update control title in header
         */
        updateControlTitle(inputElement) {
            const controlElement = inputElement.closest('.wcag-wp-control');
            const titleElement = controlElement.find('.wcag-wp-control-title');
            const label = inputElement.val() || 'Controllo';
            titleElement.text(label);
        }

        /**
         * Copy shortcode to clipboard
         */
        copyShortcode(button) {
            const shortcodeElement = $(button.data('clipboard-target'));
            const shortcode = shortcodeElement.text();
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(shortcode).then(() => {
                    this.showCopySuccess(button);
                }).catch(() => {
                    this.fallbackCopy(shortcode, button);
                });
            } else {
                this.fallbackCopy(shortcode, button);
            }
        }

        /**
         * Fallback copy method
         */
        fallbackCopy(text, button) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                document.execCommand('copy');
                this.showCopySuccess(button);
            } catch (err) {
                console.error('Fallback copy failed:', err);
            }
            
            document.body.removeChild(textArea);
        }

        /**
         * Show copy success message
         */
        showCopySuccess(button) {
            const originalText = button.text();
            button.text('Copiato!').addClass('button-primary');
            
            setTimeout(() => {
                button.text(originalText).removeClass('button-primary');
            }, 2000);
        }

        /**
         * Get next group index
         */
        getNextGroupIndex() {
            const existingGroups = $('.wcag-wp-group');
            if (existingGroups.length === 0) {
                return 0;
            }
            
            const maxIndex = Math.max(...existingGroups.map((i, el) => {
                return parseInt($(el).data('group-index')) || 0;
            }).get());
            
            return maxIndex + 1;
        }

        /**
         * Get next control index
         */
        getNextControlIndex() {
            const existingControls = $('.wcag-wp-control');
            if (existingControls.length === 0) {
                return 0;
            }
            
            const maxIndex = Math.max(...existingControls.map((i, el) => {
                return parseInt($(el).data('control-index')) || 0;
            }).get());
            
            return maxIndex + 1;
        }

        /**
         * Get group count
         */
        getGroupCount() {
            return $('.wcag-wp-group').length;
        }

        /**
         * Update group numbers
         */
        updateGroupNumbers() {
            $('.wcag-wp-group').each((index, element) => {
                const groupElement = $(element);
                const header = groupElement.find('h4');
                header.text(`Gruppo #${index + 1}`);
            });
        }

        /**
         * Update group indices
         */
        updateGroupIndices() {
            $('.wcag-wp-group').each((index, element) => {
                const groupElement = $(element);
                groupElement.attr('data-group-index', index);
                
                // Update all form field names
                groupElement.find('input, select').each((i, field) => {
                    const fieldElement = $(field);
                    const name = fieldElement.attr('name');
                    if (name) {
                        const newName = name.replace(/\[groups\]\[\d+\]/, `[groups][${index}]`);
                        fieldElement.attr('name', newName);
                    }
                });
                
                // Update control group indices
                groupElement.find('.wcag-wp-controls').attr('data-group-index', index);
            });
        }

        /**
         * Update control numbers
         */
        updateControlNumbers() {
            $('.wcag-wp-controls').each((groupIndex, groupElement) => {
                $(groupElement).find('.wcag-wp-control').each((controlIndex, controlElement) => {
                    const control = $(controlElement);
                    control.attr('data-control-index', controlIndex);
                    
                    // Update all form field names
                    control.find('input, select').each((i, field) => {
                        const fieldElement = $(field);
                        const name = fieldElement.attr('name');
                        if (name) {
                            const newName = name.replace(/\[controls\]\[\d+\]/, `[controls][${controlIndex}]`);
                            fieldElement.attr('name', newName);
                        }
                    });
                });
            });
        }

        /**
         * Update control indices
         */
        updateControlIndices() {
            $('.wcag-wp-controls').each((groupIndex, groupElement) => {
                const group = $(groupElement);
                const groupIndexValue = group.data('group-index');
                
                group.find('.wcag-wp-control').each((controlIndex, controlElement) => {
                    const control = $(controlElement);
                    control.attr('data-control-index', controlIndex);
                    
                    // Update all form field names
                    control.find('input, select').each((i, field) => {
                        const fieldElement = $(field);
                        const name = fieldElement.attr('name');
                        if (name) {
                            const newName = name
                                .replace(/\[groups\]\[\d+\]/, `[groups][${groupIndexValue}]`)
                                .replace(/\[controls\]\[\d+\]/, `[controls][${controlIndex}]`);
                            fieldElement.attr('name', newName);
                        }
                    });
                });
            });
        }
    }

    // Initialize when document is ready
    $(document).ready(() => {
        new WcagToolbarAdmin();
    });

})(jQuery);
