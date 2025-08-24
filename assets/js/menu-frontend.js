/**
 * WCAG Menu Frontend JavaScript
 * 
 * Handles accessible menu/menubar interactions
 * Follows WAI-ARIA Authoring Practices Guide (APG)
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * WCAG Menu Class
     */
    class WcagMenu {
        constructor(element) {
            this.menu = $(element);
            this.config = this.menu.data('config') || {};
            this.menuList = this.menu.find('.wcag-wp-menu__list').first();
            this.items = this.menuList.find('> .wcag-wp-menu__item');
            this.currentIndex = -1;
            this.announcements = this.menu.find('[id$="-announcements"]');
            
            this.init();
        }
        
        init() {
            if (!this.menu.length || !this.menuList.length) {
                return;
            }
            
            this.setupARIA();
            this.bindEvents();
            this.initSubmenuHandlers();
        }
        
        setupARIA() {
            // Ensure proper ARIA setup
            if (!this.menuList.attr('role')) {
                this.menuList.attr('role', this.config.type === 'menubar' ? 'menubar' : 'menu');
            }
            
            // Set tabindex for keyboard navigation
            this.items.each((index, item) => {
                const $item = $(item);
                const $link = $item.find('.wcag-wp-menu__link, .wcag-wp-menu__button').first();
                
                if ($link.length) {
                    $link.attr('tabindex', index === 0 ? '0' : '-1');
                }
            });
        }
        
        bindEvents() {
            // Keyboard navigation
            this.menuList.on('keydown', (e) => {
                this.handleKeydown(e);
            });
            
            // Mouse interactions
            this.items.on('mouseenter', '.wcag-wp-menu__button[aria-haspopup="menu"]', (e) => {
                if (this.config.trigger === 'hover') {
                    this.openSubmenu($(e.currentTarget));
                }
            });
            
            this.items.on('mouseleave', (e) => {
                if (this.config.trigger === 'hover') {
                    this.scheduleCloseSubmenu($(e.currentTarget));
                }
            });
            
            // Click interactions
            this.items.on('click', '.wcag-wp-menu__button[aria-haspopup="menu"]', (e) => {
                e.preventDefault();
                this.toggleSubmenu($(e.currentTarget));
            });
            
            // Focus management
            this.items.on('focus', '.wcag-wp-menu__link, .wcag-wp-menu__button', (e) => {
                this.setCurrentItem($(e.currentTarget).closest('.wcag-wp-menu__item'));
            });
            
            // Close submenus when clicking outside
            $(document).on('click', (e) => {
                if (!this.menu.is(e.target) && !this.menu.has(e.target).length) {
                    this.closeAllSubmenus();
                }
            });
        }
        
        initSubmenuHandlers() {
            this.items.each((index, item) => {
                const $item = $(item);
                const $button = $item.find('.wcag-wp-menu__button[aria-haspopup="menu"]');
                const $submenu = $item.find('.wcag-wp-menu__submenu');
                
                if ($button.length && $submenu.length) {
                    // Setup submenu keyboard navigation
                    $submenu.on('keydown', (e) => {
                        this.handleSubmenuKeydown(e, $submenu);
                    });
                    
                    // Setup submenu item focus
                    $submenu.find('.wcag-wp-menu__link').on('focus', (e) => {
                        this.setCurrentSubmenuItem($submenu, $(e.currentTarget));
                    });
                }
            });
        }
        
        handleKeydown(e) {
            const isHorizontal = this.config.orientation !== 'vertical';
            let handled = false;
            
            switch (e.key) {
                case 'ArrowRight':
                    if (isHorizontal) {
                        this.moveToNext();
                        handled = true;
                    } else {
                        this.openCurrentSubmenu();
                        handled = true;
                    }
                    break;
                    
                case 'ArrowLeft':
                    if (isHorizontal) {
                        this.moveToPrevious();
                        handled = true;
                    } else {
                        this.closeCurrentSubmenu();
                        handled = true;
                    }
                    break;
                    
                case 'ArrowDown':
                    if (isHorizontal) {
                        this.openCurrentSubmenu();
                        handled = true;
                    } else {
                        this.moveToNext();
                        handled = true;
                    }
                    break;
                    
                case 'ArrowUp':
                    if (isHorizontal) {
                        this.openCurrentSubmenu();
                        handled = true;
                    } else {
                        this.moveToPrevious();
                        handled = true;
                    }
                    break;
                    
                case 'Home':
                    this.moveToFirst();
                    handled = true;
                    break;
                    
                case 'End':
                    this.moveToLast();
                    handled = true;
                    break;
                    
                case 'Enter':
                case ' ':
                    this.activateCurrentItem();
                    handled = true;
                    break;
                    
                case 'Escape':
                    this.closeAllSubmenus();
                    handled = true;
                    break;
            }
            
            if (handled) {
                e.preventDefault();
                e.stopPropagation();
            }
        }
        
        handleSubmenuKeydown(e, $submenu) {
            let handled = false;
            
            switch (e.key) {
                case 'ArrowDown':
                    this.moveToNextSubmenuItem($submenu);
                    handled = true;
                    break;
                    
                case 'ArrowUp':
                    this.moveToPreviousSubmenuItem($submenu);
                    handled = true;
                    break;
                    
                case 'ArrowLeft':
                    this.closeSubmenuAndFocusParent($submenu);
                    handled = true;
                    break;
                    
                case 'Home':
                    this.moveToFirstSubmenuItem($submenu);
                    handled = true;
                    break;
                    
                case 'End':
                    this.moveToLastSubmenuItem($submenu);
                    handled = true;
                    break;
                    
                case 'Escape':
                    this.closeSubmenuAndFocusParent($submenu);
                    handled = true;
                    break;
                    
                case 'Enter':
                case ' ':
                    // Let the default action happen for links
                    break;
            }
            
            if (handled) {
                e.preventDefault();
                e.stopPropagation();
            }
        }
        
        moveToNext() {
            const nextIndex = (this.currentIndex + 1) % this.items.length;
            this.focusItem(nextIndex);
        }
        
        moveToPrevious() {
            const prevIndex = this.currentIndex <= 0 ? this.items.length - 1 : this.currentIndex - 1;
            this.focusItem(prevIndex);
        }
        
        moveToFirst() {
            this.focusItem(0);
        }
        
        moveToLast() {
            this.focusItem(this.items.length - 1);
        }
        
        focusItem(index) {
            if (index >= 0 && index < this.items.length) {
                const $item = this.items.eq(index);
                const $focusable = $item.find('.wcag-wp-menu__link, .wcag-wp-menu__button').first();
                
                if ($focusable.length) {
                    // Update tabindex
                    this.items.find('.wcag-wp-menu__link, .wcag-wp-menu__button').attr('tabindex', '-1');
                    $focusable.attr('tabindex', '0').focus();
                    
                    this.currentIndex = index;
                }
            }
        }
        
        setCurrentItem($item) {
            this.currentIndex = this.items.index($item);
        }
        
        activateCurrentItem() {
            if (this.currentIndex >= 0) {
                const $item = this.items.eq(this.currentIndex);
                const $button = $item.find('.wcag-wp-menu__button[aria-haspopup="menu"]');
                const $link = $item.find('.wcag-wp-menu__link');
                
                if ($button.length) {
                    this.toggleSubmenu($button);
                } else if ($link.length) {
                    $link[0].click();
                }
            }
        }
        
        openCurrentSubmenu() {
            if (this.currentIndex >= 0) {
                const $item = this.items.eq(this.currentIndex);
                const $button = $item.find('.wcag-wp-menu__button[aria-haspopup="menu"]');
                
                if ($button.length) {
                    this.openSubmenu($button);
                }
            }
        }
        
        closeCurrentSubmenu() {
            if (this.currentIndex >= 0) {
                const $item = this.items.eq(this.currentIndex);
                const $submenu = $item.find('.wcag-wp-menu__submenu');
                
                if ($submenu.length && !$submenu.prop('hidden')) {
                    this.closeSubmenu($item.find('.wcag-wp-menu__button'));
                }
            }
        }
        
        toggleSubmenu($button) {
            const isExpanded = $button.attr('aria-expanded') === 'true';
            
            if (isExpanded) {
                this.closeSubmenu($button);
            } else {
                this.openSubmenu($button);
            }
        }
        
        openSubmenu($button) {
            const $item = $button.closest('.wcag-wp-menu__item');
            const $submenu = $item.find('.wcag-wp-menu__submenu');
            
            if ($submenu.length) {
                // Close other submenus
                this.closeAllSubmenus();
                
                // Open this submenu
                $button.attr('aria-expanded', 'true');
                $submenu.prop('hidden', false);
                
                // Focus first submenu item
                const $firstItem = $submenu.find('.wcag-wp-menu__link').first();
                if ($firstItem.length) {
                    $firstItem.focus();
                }
                
                this.announce('Sottomenu aperto');
            }
        }
        
        closeSubmenu($button) {
            const $item = $button.closest('.wcag-wp-menu__item');
            const $submenu = $item.find('.wcag-wp-menu__submenu');
            
            if ($submenu.length) {
                $button.attr('aria-expanded', 'false');
                $submenu.prop('hidden', true);
                
                this.announce('Sottomenu chiuso');
            }
        }
        
        closeAllSubmenus() {
            this.items.each((index, item) => {
                const $item = $(item);
                const $button = $item.find('.wcag-wp-menu__button[aria-haspopup="menu"]');
                const $submenu = $item.find('.wcag-wp-menu__submenu');
                
                if ($button.length && $submenu.length) {
                    $button.attr('aria-expanded', 'false');
                    $submenu.prop('hidden', true);
                }
            });
        }
        
        scheduleCloseSubmenu($item) {
            clearTimeout(this.closeTimeout);
            this.closeTimeout = setTimeout(() => {
                const $button = $item.find('.wcag-wp-menu__button[aria-haspopup="menu"]');
                if ($button.length) {
                    this.closeSubmenu($button);
                }
            }, this.config.close_delay || 300);
        }
        
        // Submenu navigation methods
        moveToNextSubmenuItem($submenu) {
            const $items = $submenu.find('.wcag-wp-menu__link');
            const $current = $items.filter(':focus');
            const currentIndex = $items.index($current);
            const nextIndex = (currentIndex + 1) % $items.length;
            
            $items.eq(nextIndex).focus();
        }
        
        moveToPreviousSubmenuItem($submenu) {
            const $items = $submenu.find('.wcag-wp-menu__link');
            const $current = $items.filter(':focus');
            const currentIndex = $items.index($current);
            const prevIndex = currentIndex <= 0 ? $items.length - 1 : currentIndex - 1;
            
            $items.eq(prevIndex).focus();
        }
        
        moveToFirstSubmenuItem($submenu) {
            const $firstItem = $submenu.find('.wcag-wp-menu__link').first();
            $firstItem.focus();
        }
        
        moveToLastSubmenuItem($submenu) {
            const $lastItem = $submenu.find('.wcag-wp-menu__link').last();
            $lastItem.focus();
        }
        
        setCurrentSubmenuItem($submenu, $item) {
            // Update focus management for submenu
        }
        
        closeSubmenuAndFocusParent($submenu) {
            const $item = $submenu.closest('.wcag-wp-menu__item');
            const $button = $item.find('.wcag-wp-menu__button[aria-haspopup="menu"]');
            
            this.closeSubmenu($button);
            $button.focus();
        }
        
        announce(message) {
            if (this.announcements.length) {
                this.announcements.text(message);
            }
        }
        
        // Public API
        destroy() {
            this.menu.off();
            $(document).off('click.wcag-menu-' + this.menu.attr('id'));
        }
    }

    /**
     * Initialize WCAG Menus
     */
    function initWcagMenus() {
        $('[data-wcag-menu]').each(function() {
            if (!$(this).data('wcag-menu-instance')) {
                const instance = new WcagMenu(this);
                $(this).data('wcag-menu-instance', instance);
            }
        });
    }

    /**
     * Initialize on document ready and after dynamic content
     */
    $(document).ready(initWcagMenus);
    
    // Re-initialize after AJAX or dynamic content changes
    $(document).on('wcag-components-refresh', initWcagMenus);
    
    // Expose for manual initialization
    window.WcagMenu = WcagMenu;
    window.initWcagMenus = initWcagMenus;

})(jQuery);
