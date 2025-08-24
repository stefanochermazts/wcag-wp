/**
 * WCAG Menu Button Frontend JavaScript
 * 
 * Handles accessible menu button interactions
 * Follows WAI-ARIA Authoring Practices Guide (APG)
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * WCAG Menu Button Class
     */
    class WcagMenuButton {
        constructor(element) {
            this.container = $(element);
            this.config = this.container.data('config') || {};
            this.button = this.container.find('.wcag-wp-menubutton__button');
            this.menu = this.container.find('.wcag-wp-menubutton__menu');
            this.menuItems = this.menu.find('.wcag-wp-menubutton__link');
            this.currentIndex = -1;
            this.announcements = this.container.find('[id$="-announcements"]');
            this.isOpen = false;
            
            this.init();
        }
        
        init() {
            if (!this.button.length || !this.menu.length) {
                return;
            }
            
            this.setupARIA();
            this.bindEvents();
        }
        
        setupARIA() {
            // Ensure proper ARIA setup
            if (!this.button.attr('aria-haspopup')) {
                this.button.attr('aria-haspopup', 'menu');
            }
            
            if (!this.button.attr('aria-expanded')) {
                this.button.attr('aria-expanded', 'false');
            }
            
            // Set up menu role
            if (!this.menu.attr('role')) {
                this.menu.attr('role', 'menu');
            }
            
            // Set up menu items
            this.menuItems.each(function() {
                if (!$(this).attr('role')) {
                    $(this).attr('role', 'menuitem');
                }
            });
            
            // Initially hide menu
            this.menu.prop('hidden', true);
        }
        
        bindEvents() {
            // Button interactions
            this.button.on('click', (e) => {
                e.preventDefault();
                this.toggle();
            });
            
            this.button.on('keydown', (e) => {
                this.handleButtonKeydown(e);
            });
            
            // Menu interactions
            this.menu.on('keydown', (e) => {
                this.handleMenuKeydown(e);
            });
            
            this.menuItems.on('click', (e) => {
                if (this.config.close_on_select) {
                    this.close();
                }
            });
            
            this.menuItems.on('focus', (e) => {
                this.setCurrentItem($(e.currentTarget));
            });
            
            // Hover interactions (if enabled)
            if (this.config.trigger === 'hover') {
                this.container.on('mouseenter', () => {
                    this.open();
                });
                
                this.container.on('mouseleave', () => {
                    this.scheduleClose();
                });
            }
            
            // Close when clicking outside
            $(document).on('click', (e) => {
                if (!this.container.is(e.target) && !this.container.has(e.target).length) {
                    this.close();
                }
            });
            
            // Close on Escape key globally
            $(document).on('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.close();
                    this.button.focus();
                }
            });
        }
        
        handleButtonKeydown(e) {
            let handled = false;
            
            switch (e.key) {
                case 'Enter':
                case ' ':
                    this.toggle();
                    handled = true;
                    break;
                    
                case 'ArrowDown':
                case 'ArrowUp':
                    this.open();
                    if (e.key === 'ArrowDown') {
                        this.focusFirstItem();
                    } else {
                        this.focusLastItem();
                    }
                    handled = true;
                    break;
            }
            
            if (handled) {
                e.preventDefault();
                e.stopPropagation();
            }
        }
        
        handleMenuKeydown(e) {
            let handled = false;
            
            switch (e.key) {
                case 'ArrowDown':
                    this.moveToNext();
                    handled = true;
                    break;
                    
                case 'ArrowUp':
                    this.moveToPrevious();
                    handled = true;
                    break;
                    
                case 'Home':
                    this.focusFirstItem();
                    handled = true;
                    break;
                    
                case 'End':
                    this.focusLastItem();
                    handled = true;
                    break;
                    
                case 'Enter':
                case ' ':
                    this.activateCurrentItem();
                    handled = true;
                    break;
                    
                case 'Escape':
                    this.close();
                    this.button.focus();
                    handled = true;
                    break;
                    
                case 'Tab':
                    // Allow Tab to close menu and continue navigation
                    this.close();
                    break;
            }
            
            if (handled) {
                e.preventDefault();
                e.stopPropagation();
            }
        }
        
        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        }
        
        open() {
            if (this.isOpen) {
                return;
            }
            
            this.isOpen = true;
            this.button.attr('aria-expanded', 'true');
            this.menu.prop('hidden', false);
            
            // Position menu
            this.positionMenu();
            
            // Focus first item if opened via keyboard
            if (document.activeElement === this.button[0]) {
                this.focusFirstItem();
            }
            
            this.announce('Menu aperto');
            
            // Trigger custom event
            this.container.trigger('wcag-menubutton:opened');
        }
        
        close() {
            if (!this.isOpen) {
                return;
            }
            
            this.isOpen = false;
            this.button.attr('aria-expanded', 'false');
            this.menu.prop('hidden', true);
            this.currentIndex = -1;
            
            // Clear any scheduled close
            clearTimeout(this.closeTimeout);
            
            this.announce('Menu chiuso');
            
            // Trigger custom event
            this.container.trigger('wcag-menubutton:closed');
        }
        
        scheduleClose() {
            clearTimeout(this.closeTimeout);
            this.closeTimeout = setTimeout(() => {
                this.close();
            }, this.config.close_delay || 300);
        }
        
        positionMenu() {
            const position = this.config.position || 'bottom';
            
            // Reset positioning classes
            this.container.removeClass('wcag-wp-menubutton--top wcag-wp-menubutton--bottom wcag-wp-menubutton--left wcag-wp-menubutton--right');
            
            // Add positioning class
            this.container.addClass(`wcag-wp-menubutton--${position}`);
            
            // Check if menu fits in viewport and adjust if needed
            const menuRect = this.menu[0].getBoundingClientRect();
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;
            
            // Adjust horizontal position if menu overflows
            if (menuRect.right > viewportWidth) {
                this.container.removeClass('wcag-wp-menubutton--left wcag-wp-menubutton--right');
                this.container.addClass('wcag-wp-menubutton--right');
            }
            
            // Adjust vertical position if menu overflows
            if (menuRect.bottom > viewportHeight && position === 'bottom') {
                this.container.removeClass('wcag-wp-menubutton--bottom');
                this.container.addClass('wcag-wp-menubutton--top');
            }
        }
        
        moveToNext() {
            const nextIndex = (this.currentIndex + 1) % this.menuItems.length;
            this.focusItem(nextIndex);
        }
        
        moveToPrevious() {
            const prevIndex = this.currentIndex <= 0 ? this.menuItems.length - 1 : this.currentIndex - 1;
            this.focusItem(prevIndex);
        }
        
        focusFirstItem() {
            this.focusItem(0);
        }
        
        focusLastItem() {
            this.focusItem(this.menuItems.length - 1);
        }
        
        focusItem(index) {
            if (index >= 0 && index < this.menuItems.length) {
                const $item = this.menuItems.eq(index);
                $item.focus();
                this.currentIndex = index;
            }
        }
        
        setCurrentItem($item) {
            this.currentIndex = this.menuItems.index($item);
        }
        
        activateCurrentItem() {
            if (this.currentIndex >= 0) {
                const $item = this.menuItems.eq(this.currentIndex);
                
                // Trigger click event
                $item[0].click();
                
                // Close menu if configured to do so
                if (this.config.close_on_select) {
                    this.close();
                }
            }
        }
        
        announce(message) {
            if (this.announcements.length) {
                this.announcements.text(message);
            }
        }
        
        // Public API
        isMenuOpen() {
            return this.isOpen;
        }
        
        openMenu() {
            this.open();
        }
        
        closeMenu() {
            this.close();
        }
        
        toggleMenu() {
            this.toggle();
        }
        
        destroy() {
            this.container.off();
            $(document).off('click.wcag-menubutton-' + this.container.attr('id'));
            $(document).off('keydown.wcag-menubutton-' + this.container.attr('id'));
        }
    }

    /**
     * Initialize WCAG Menu Buttons
     */
    function initWcagMenuButtons() {
        $('[data-wcag-menubutton]').each(function() {
            if (!$(this).data('wcag-menubutton-instance')) {
                const instance = new WcagMenuButton(this);
                $(this).data('wcag-menubutton-instance', instance);
            }
        });
    }

    /**
     * Initialize on document ready and after dynamic content
     */
    $(document).ready(initWcagMenuButtons);
    
    // Re-initialize after AJAX or dynamic content changes
    $(document).on('wcag-components-refresh', initWcagMenuButtons);
    
    // Expose for manual initialization
    window.WcagMenuButton = WcagMenuButton;
    window.initWcagMenuButtons = initWcagMenuButtons;

})(jQuery);
