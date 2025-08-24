/**
 * WCAG Menu Admin JavaScript
 * 
 * Handles dynamic menu item management in admin interface
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Menu Admin Class
     */
    class WcagMenuAdmin {
        constructor() {
            this.container = $('#wcag-menu-items-container');
            this.addButton = $('#wcag-add-menu-item');
            this.template = $('#wcag-menu-item-template').html();
            this.submenuTemplate = $('#wcag-submenu-item-template').html();
            this.itemIndex = this.getNextIndex();
            
            this.init();
        }
        
        init() {
            if (!this.container.length || !this.addButton.length) {
                return;
            }
            
            this.bindEvents();
            this.initExistingItems();
        }
        
        bindEvents() {
            // Add new item
            this.addButton.on('click', (e) => {
                e.preventDefault();
                this.addMenuItem();
            });
            
            // Delegate events for dynamic items
            this.container.on('click', '.wcag-menu-item-toggle', (e) => {
                e.preventDefault();
                this.toggleItem($(e.currentTarget));
            });
            
            this.container.on('click', '.wcag-menu-item-remove', (e) => {
                e.preventDefault();
                this.removeItem($(e.currentTarget));
            });
            
            // Update title when label changes
            this.container.on('input', 'input[name*="[label]"]', (e) => {
                this.updateItemTitle($(e.currentTarget));
            });
            
            // Submenu events
            this.container.on('click', '.wcag-add-submenu-item', (e) => {
                e.preventDefault();
                this.addSubmenuItem($(e.currentTarget));
            });
            
            this.container.on('click', '.wcag-submenu-item-toggle', (e) => {
                e.preventDefault();
                this.toggleSubmenuItem($(e.currentTarget));
            });
            
            this.container.on('click', '.wcag-submenu-item-remove', (e) => {
                e.preventDefault();
                this.removeSubmenuItem($(e.currentTarget));
            });
            
            // Update submenu title when label changes
            this.container.on('input', 'input[name*="[submenu]"][name*="[label]"]', (e) => {
                this.updateSubmenuTitle($(e.currentTarget));
            });
            
            // Make items sortable
            if ($.fn.sortable) {
                this.container.sortable({
                    handle: '.wcag-menu-item-header',
                    placeholder: 'wcag-menu-item-placeholder',
                    update: () => this.updateIndices()
                });
            }
        }
        
        initExistingItems() {
            // Initialize existing items
            this.container.find('.wcag-menu-item').each((index, item) => {
                const $item = $(item);
                const currentIndex = parseInt($item.data('index')) || index;
                if (currentIndex >= this.itemIndex) {
                    this.itemIndex = currentIndex + 1;
                }
            });
        }
        
        addMenuItem() {
            if (!this.template) {
                console.error('Menu item template not found');
                return;
            }
            
            const itemHtml = this.template.replace(/\{\{INDEX\}\}/g, this.itemIndex);
            const $newItem = $(itemHtml);
            
            // Add to container
            this.container.append($newItem);
            
            // Focus on first input
            $newItem.find('input[name*="[label]"]').focus();
            
            // Show content by default for new items
            $newItem.find('.wcag-menu-item-content').show();
            $newItem.find('.wcag-menu-item-toggle').text('Chiudi');
            
            this.itemIndex++;
            
            // Trigger change event for auto-save
            $(document).trigger('wcag-menu-item-added', [$newItem]);
        }
        
        toggleItem($button) {
            const $item = $button.closest('.wcag-menu-item');
            const $content = $item.find('.wcag-menu-item-content');
            const isVisible = $content.is(':visible');
            
            if (isVisible) {
                $content.slideUp(200);
                $button.text('Modifica');
            } else {
                $content.slideDown(200);
                $button.text('Chiudi');
            }
        }
        
        removeItem($button) {
            const $item = $button.closest('.wcag-menu-item');
            const itemTitle = $item.find('.wcag-menu-item-title').text();
            
            if (confirm(`Sei sicuro di voler rimuovere "${itemTitle}"?`)) {
                $item.slideUp(200, function() {
                    $(this).remove();
                });
                
                // Trigger change event
                $(document).trigger('wcag-menu-item-removed');
            }
        }
        
        updateItemTitle($input) {
            const $item = $input.closest('.wcag-menu-item');
            const $title = $item.find('.wcag-menu-item-title');
            const newTitle = $input.val().trim() || 'Elemento senza titolo';
            
            $title.text(newTitle);
        }
        
        updateIndices() {
            this.container.find('.wcag-menu-item').each((index, item) => {
                const $item = $(item);
                const oldIndex = $item.data('index');
                
                // Update data attribute
                $item.attr('data-index', index);
                $item.data('index', index);
                
                // Update all input names
                $item.find('input, select, textarea').each(function() {
                    const $field = $(this);
                    const name = $field.attr('name');
                    if (name) {
                        const newName = name.replace(/\[items\]\[\d+\]/, `[items][${index}]`);
                        $field.attr('name', newName);
                    }
                });
            });
        }
        
        getNextIndex() {
            let maxIndex = -1;
            this.container.find('.wcag-menu-item').each(function() {
                const index = parseInt($(this).data('index')) || 0;
                if (index > maxIndex) {
                    maxIndex = index;
                }
            });
            return maxIndex + 1;
        }
        
        // Submenu Methods
        addSubmenuItem($button) {
            if (!this.submenuTemplate) {
                console.error('Submenu template not found');
                return;
            }
            
            const parentIndex = $button.data('parent-index');
            const $submenuContainer = $button.siblings('.wcag-wp-submenu-container');
            const subIndex = this.getNextSubmenuIndex($submenuContainer);
            
            let submenuHtml = this.submenuTemplate
                .replace(/\{\{PARENT_INDEX\}\}/g, parentIndex)
                .replace(/\{\{SUB_INDEX\}\}/g, subIndex);
            
            const $newSubmenuItem = $(submenuHtml);
            
            // Add to container
            $submenuContainer.append($newSubmenuItem);
            
            // Focus on first input
            $newSubmenuItem.find('input[name*="[label]"]').focus();
            
            // Show content by default for new items
            $newSubmenuItem.find('.wcag-wp-submenu-content').show();
            $newSubmenuItem.find('.wcag-submenu-item-toggle').text('Chiudi');
            
            // Trigger change event
            $(document).trigger('wcag-submenu-item-added', [$newSubmenuItem]);
        }
        
        toggleSubmenuItem($button) {
            const $item = $button.closest('.wcag-wp-submenu-item');
            const $content = $item.find('.wcag-wp-submenu-content');
            const isVisible = $content.is(':visible');
            
            if (isVisible) {
                $content.slideUp(200);
                $button.text('Modifica');
            } else {
                $content.slideDown(200);
                $button.text('Chiudi');
            }
        }
        
        removeSubmenuItem($button) {
            const $item = $button.closest('.wcag-wp-submenu-item');
            const itemTitle = $item.find('.wcag-wp-submenu-title').text();
            
            if (confirm(`Sei sicuro di voler rimuovere "${itemTitle}"?`)) {
                $item.slideUp(200, function() {
                    $(this).remove();
                });
                
                // Trigger change event
                $(document).trigger('wcag-submenu-item-removed');
            }
        }
        
        updateSubmenuTitle($input) {
            const $item = $input.closest('.wcag-wp-submenu-item');
            const $title = $item.find('.wcag-wp-submenu-title');
            const newTitle = $input.val().trim() || 'Sottoelemento senza titolo';
            
            $title.text(newTitle);
        }
        
        getNextSubmenuIndex($container) {
            let maxIndex = -1;
            $container.find('.wcag-wp-submenu-item').each(function() {
                const index = parseInt($(this).data('sub-index')) || 0;
                if (index > maxIndex) {
                    maxIndex = index;
                }
            });
            return maxIndex + 1;
        }
        
        // Public API
        addItem() {
            this.addMenuItem();
        }
        
        getItems() {
            const items = [];
            this.container.find('.wcag-menu-item').each(function() {
                const $item = $(this);
                const itemData = {};
                
                $item.find('input, select, textarea').each(function() {
                    const $field = $(this);
                    const name = $field.attr('name');
                    if (name) {
                        const matches = name.match(/\[(\w+)\]$/);
                        if (matches) {
                            const fieldName = matches[1];
                            if ($field.attr('type') === 'checkbox') {
                                itemData[fieldName] = $field.is(':checked');
                            } else {
                                itemData[fieldName] = $field.val();
                            }
                        }
                    }
                });
                
                items.push(itemData);
            });
            return items;
        }
    }

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        // Initialize Menu Admin
        if ($('#wcag-menu-items-container').length) {
            window.wcagMenuAdmin = new WcagMenuAdmin();
        }
        
        // Auto-save functionality
        let autoSaveTimeout;
        $(document).on('wcag-menu-item-added wcag-menu-item-removed', function() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(function() {
                // Trigger WordPress auto-save
                if (typeof wp !== 'undefined' && wp.autosave) {
                    wp.autosave.server.triggerSave();
                }
            }, 2000);
        });
        
        // Copy shortcode functionality
        $(document).on('click', '.wcag-copy-shortcode', function(e) {
            e.preventDefault();
            
            const shortcode = $(this).data('shortcode');
            const $button = $(this);
            const originalText = $button.text();
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(shortcode).then(function() {
                    $button.text('Copiato!').addClass('copied');
                    setTimeout(function() {
                        $button.text(originalText).removeClass('copied');
                    }, 2000);
                }).catch(function() {
                    // Fallback
                    copyToClipboardFallback(shortcode, $button, originalText);
                });
            } else {
                copyToClipboardFallback(shortcode, $button, originalText);
            }
        });
        
        function copyToClipboardFallback(text, $button, originalText) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.opacity = '0';
            document.body.appendChild(textArea);
            textArea.select();
            
            try {
                document.execCommand('copy');
                $button.text('Copiato!').addClass('copied');
                setTimeout(function() {
                    $button.text(originalText).removeClass('copied');
                }, 2000);
            } catch (err) {
                console.error('Copy failed:', err);
                $button.text('Errore copia').addClass('error');
                setTimeout(function() {
                    $button.text(originalText).removeClass('error');
                }, 2000);
            }
            
            document.body.removeChild(textArea);
        }
    });

})(jQuery);
