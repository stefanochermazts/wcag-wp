/**
 * WCAG Listbox Frontend JavaScript
 * 
 * Implements accessible listbox following WCAG APG pattern:
 * - Advanced keyboard navigation (Arrow keys, Ctrl+A, Shift+Click)
 * - ARIA state management with multiselectable support
 * - Screen reader announcements
 * - Dynamic option filtering with search
 * - Extended selection modes (range, toggle)
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function() {
    'use strict';
    
    // Global listbox manager
    window.wcagListbox = window.wcagListbox || {};
    
    /**
     * Listbox Manager Class
     */
    class WcagListboxManager {
        constructor() {
            this.listboxes = new Map();
            this.init();
        }
        
        /**
         * Initialize listbox system
         */
        init() {
            this.initializeExistingListboxes();
        }
        
        /**
         * Initialize existing listboxes on page load
         */
        initializeExistingListboxes() {
            const listboxes = document.querySelectorAll('.wcag-wp-listbox');
            listboxes.forEach(listbox => {
                this.initListbox(listbox);
            });
        }
        
        /**
         * Initialize a single listbox
         * 
         * @param {HTMLElement} element Listbox element
         */
        initListbox(element) {
            if (!element || this.listboxes.has(element)) {
                return;
            }
            
            const config = this.getListboxConfig(element);
            const listbox = new WcagListbox(element, config);
            
            this.listboxes.set(element, listbox);
            
            return listbox;
        }
        
        /**
         * Get listbox configuration from element
         * 
         * @param {HTMLElement} element Listbox element
         * @returns {Object} Configuration object
         */
        getListboxConfig(element) {
            return {
                id: element.getAttribute('data-listbox-id') || '',
                type: element.getAttribute('data-listbox-type') || 'single',
                multiselectable: element.getAttribute('data-multiselectable') === 'true',
                orientation: element.getAttribute('data-orientation') || 'vertical',
                selectionMode: element.getAttribute('data-selection-mode') || 'both',
                size: parseInt(element.getAttribute('data-size')) || 5,
                required: element.getAttribute('data-required') === 'true',
                allowDeselect: element.getAttribute('data-allow-deselect') === 'true',
                wrapNavigation: element.getAttribute('data-wrap-navigation') === 'true',
                autoSelectFirst: element.getAttribute('data-auto-select-first') === 'true',
                showSelectionCount: element.getAttribute('data-show-selection-count') === 'true',
                dataSource: element.getAttribute('data-data-source') || 'static'
            };
        }
        
        /**
         * Get listbox instance by ID
         * 
         * @param {string} id Listbox ID
         * @returns {WcagListbox|null} Listbox instance
         */
        getListbox(id) {
            for (const [element, listbox] of this.listboxes) {
                if (listbox.config.id === id) {
                    return listbox;
                }
            }
            return null;
        }
        
        /**
         * Remove listbox from manager
         * 
         * @param {HTMLElement} element Listbox element
         */
        removeListbox(element) {
            this.listboxes.delete(element);
        }
    }
    
    /**
     * Individual Listbox Class
     */
    class WcagListbox {
        constructor(element, config) {
            this.element = element;
            this.config = config;
            
            // Get DOM elements
            this.listbox = element.querySelector('.wcag-wp-listbox__listbox');
            this.search = element.querySelector('.wcag-wp-listbox__search');
            this.status = element.querySelector('[aria-live]');
            this.countDisplay = element.querySelector('.wcag-wp-listbox__selection-count .wcag-wp-listbox__count-text');
            this.hiddenInputsContainer = element.querySelector('.wcag-wp-listbox__hidden-inputs');
            
            // State
            this.options = [];
            this.filteredOptions = [];
            this.selectedValues = new Set();
            this.activeIndex = -1;
            this.lastSelectedIndex = -1;
            this.searchTimeout = null;
            
            this.init();
        }
        
        /**
         * Initialize listbox
         */
        init() {
            this.loadOptions();
            this.loadSelectedValues();
            this.bindEvents();
            this.updateDisplay();
            
            // Auto-select first option if configured
            if (this.config.autoSelectFirst && this.options.length > 0 && this.selectedValues.size === 0) {
                this.selectOption(this.options[0], false);
            }
        }
        
        /**
         * Load options from DOM
         */
        loadOptions() {
            const optionElements = this.listbox.querySelectorAll('.wcag-wp-listbox__option');
            this.options = Array.from(optionElements).map((el, index) => ({
                element: el,
                value: el.getAttribute('data-value') || '',
                label: el.querySelector('.wcag-wp-listbox__option-label')?.textContent || '',
                description: el.querySelector('.wcag-wp-listbox__option-description')?.textContent || '',
                group: el.getAttribute('data-group') || '',
                disabled: el.getAttribute('aria-disabled') === 'true',
                selected: el.getAttribute('aria-selected') === 'true',
                index: index
            }));
            
            // Load selected values
            this.options.forEach(option => {
                if (option.selected) {
                    this.selectedValues.add(option.value);
                }
            });
            
            // Initialize filtered options
            this.filteredOptions = [...this.options];
        }
        
        /**
         * Load selected values from hidden inputs
         */
        loadSelectedValues() {
            const hiddenInputs = this.hiddenInputsContainer.querySelectorAll('input[type="hidden"]');
            hiddenInputs.forEach(input => {
                const value = input.value;
                if (value) {
                    this.selectedValues.add(value);
                }
            });
        }
        
        /**
         * Bind event listeners
         */
        bindEvents() {
            // Listbox events
            this.listbox.addEventListener('keydown', (e) => this.handleKeyDown(e));
            this.listbox.addEventListener('click', (e) => this.handleClick(e));
            this.listbox.addEventListener('focus', (e) => this.handleFocus(e));
            this.listbox.addEventListener('blur', (e) => this.handleBlur(e));
            
            // Search events
            if (this.search) {
                this.search.addEventListener('input', (e) => this.handleSearchInput(e));
                this.search.addEventListener('keydown', (e) => this.handleSearchKeyDown(e));
            }
            
            // Global events
            document.addEventListener('click', (e) => this.handleDocumentClick(e));
        }
        
        /**
         * Handle keyboard navigation
         * 
         * @param {Event} e Keyboard event
         */
        handleKeyDown(e) {
            const { key, ctrlKey, shiftKey, metaKey } = e;
            
            switch (key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (shiftKey && this.config.multiselectable) {
                        this.extendSelection(1);
                    } else {
                        this.moveActiveIndex(1);
                    }
                    break;
                    
                case 'ArrowUp':
                    e.preventDefault();
                    if (shiftKey && this.config.multiselectable) {
                        this.extendSelection(-1);
                    } else {
                        this.moveActiveIndex(-1);
                    }
                    break;
                    
                case 'ArrowRight':
                    if (this.config.orientation === 'horizontal' || this.config.orientation === 'grid') {
                        e.preventDefault();
                        if (shiftKey && this.config.multiselectable) {
                            this.extendSelection(1);
                        } else {
                            this.moveActiveIndex(1);
                        }
                    }
                    break;
                    
                case 'ArrowLeft':
                    if (this.config.orientation === 'horizontal' || this.config.orientation === 'grid') {
                        e.preventDefault();
                        if (shiftKey && this.config.multiselectable) {
                            this.extendSelection(-1);
                        } else {
                            this.moveActiveIndex(-1);
                        }
                    }
                    break;
                    
                case ' ':
                case 'Spacebar':
                    e.preventDefault();
                    if (this.activeIndex >= 0) {
                        const option = this.filteredOptions[this.activeIndex];
                        if (ctrlKey && this.config.multiselectable) {
                            this.toggleOption(option);
                        } else {
                            this.selectOption(option, !this.config.multiselectable);
                        }
                    }
                    break;
                    
                case 'Enter':
                    e.preventDefault();
                    if (this.activeIndex >= 0) {
                        const option = this.filteredOptions[this.activeIndex];
                        this.selectOption(option, !this.config.multiselectable);
                    }
                    break;
                    
                case 'Home':
                    e.preventDefault();
                    if (ctrlKey && shiftKey && this.config.multiselectable) {
                        this.selectRange(0, this.activeIndex);
                    } else {
                        this.setActiveIndex(0);
                    }
                    break;
                    
                case 'End':
                    e.preventDefault();
                    if (ctrlKey && shiftKey && this.config.multiselectable) {
                        this.selectRange(this.activeIndex, this.filteredOptions.length - 1);
                    } else {
                        this.setActiveIndex(this.filteredOptions.length - 1);
                    }
                    break;
                    
                case 'PageUp':
                    e.preventDefault();
                    this.moveActiveIndex(-Math.max(1, Math.floor(this.config.size / 2)));
                    break;
                    
                case 'PageDown':
                    e.preventDefault();
                    this.moveActiveIndex(Math.max(1, Math.floor(this.config.size / 2)));
                    break;
                    
                case 'a':
                case 'A':
                    if ((ctrlKey || metaKey) && this.config.multiselectable) {
                        e.preventDefault();
                        this.selectAll();
                    }
                    break;
                    
                case 'Escape':
                    e.preventDefault();
                    if (this.config.allowDeselect) {
                        this.clearSelection();
                    }
                    break;
                    
                default:
                    // Character navigation
                    if (key.length === 1 && !ctrlKey && !metaKey) {
                        this.navigateByCharacter(key);
                    }
                    break;
            }
        }
        
        /**
         * Handle click events
         * 
         * @param {Event} e Click event
         */
        handleClick(e) {
            const optionElement = e.target.closest('.wcag-wp-listbox__option');
            if (!optionElement) return;
            
            const option = this.options.find(opt => opt.element === optionElement);
            if (!option || option.disabled) return;
            
            const optionIndex = this.filteredOptions.indexOf(option);
            if (optionIndex === -1) return;
            
            // Handle different selection modes
            if (e.shiftKey && this.config.multiselectable && this.lastSelectedIndex !== -1) {
                // Range selection
                this.selectRange(Math.min(this.lastSelectedIndex, optionIndex), 
                               Math.max(this.lastSelectedIndex, optionIndex));
            } else if (e.ctrlKey && this.config.multiselectable) {
                // Toggle selection
                this.toggleOption(option);
                this.setActiveIndex(optionIndex);
            } else {
                // Single selection or replace selection
                this.selectOption(option, !this.config.multiselectable);
                this.setActiveIndex(optionIndex);
            }
        }
        
        /**
         * Handle focus events
         * 
         * @param {Event} e Focus event
         */
        handleFocus(e) {
            // Set initial active index if none set
            if (this.activeIndex === -1 && this.filteredOptions.length > 0) {
                // Try to focus first selected option, otherwise first option
                const firstSelected = this.filteredOptions.find(opt => this.selectedValues.has(opt.value));
                const initialIndex = firstSelected ? this.filteredOptions.indexOf(firstSelected) : 0;
                this.setActiveIndex(initialIndex);
            }
        }
        
        /**
         * Handle blur events
         * 
         * @param {Event} e Blur event
         */
        handleBlur(e) {
            // Remove active state when losing focus
            setTimeout(() => {
                if (!this.element.contains(document.activeElement)) {
                    this.clearActiveState();
                }
            }, 100);
        }
        
        /**
         * Handle search input
         * 
         * @param {Event} e Input event
         */
        handleSearchInput(e) {
            const query = e.target.value;
            
            // Clear previous timeout
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            // Debounce search
            this.searchTimeout = setTimeout(() => {
                this.filterOptions(query);
            }, 300);
        }
        
        /**
         * Handle search field keyboard events
         * 
         * @param {Event} e Keyboard event
         */
        handleSearchKeyDown(e) {
            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    this.listbox.focus();
                    if (this.filteredOptions.length > 0) {
                        this.setActiveIndex(0);
                    }
                    break;
                    
                case 'Escape':
                    e.preventDefault();
                    e.target.value = '';
                    this.filterOptions('');
                    break;
            }
        }
        
        /**
         * Handle document clicks (for closing search results)
         * 
         * @param {Event} e Click event
         */
        handleDocumentClick(e) {
            if (!this.element.contains(e.target)) {
                // Clicked outside - could implement dropdown closing here if needed
            }
        }
        
        /**
         * Filter options based on search query
         * 
         * @param {string} query Search query
         */
        filterOptions(query) {
            if (!query.trim()) {
                // Show all options
                this.filteredOptions = [...this.options];
                this.showAllOptions();
            } else {
                // Filter options
                this.filteredOptions = this.options.filter(option => {
                    const searchText = `${option.label} ${option.description}`.toLowerCase();
                    return searchText.includes(query.toLowerCase());
                });
                
                this.showFilteredOptions();
            }
            
            // Reset active index
            this.setActiveIndex(this.filteredOptions.length > 0 ? 0 : -1);
            
            // Announce results
            this.announceSearchResults(this.filteredOptions.length, query);
        }
        
        /**
         * Show all options
         */
        showAllOptions() {
            this.options.forEach(option => {
                option.element.style.display = '';
                
                // Show parent group if hidden
                const group = option.element.closest('.wcag-wp-listbox__group');
                if (group) {
                    group.style.display = '';
                }
            });
        }
        
        /**
         * Show only filtered options
         */
        showFilteredOptions() {
            // Hide all options first
            this.options.forEach(option => {
                option.element.style.display = 'none';
            });
            
            // Hide all groups first
            const groups = this.listbox.querySelectorAll('.wcag-wp-listbox__group');
            groups.forEach(group => {
                group.style.display = 'none';
            });
            
            // Show filtered options and their groups
            const visibleGroups = new Set();
            
            this.filteredOptions.forEach(option => {
                option.element.style.display = '';
                
                const group = option.element.closest('.wcag-wp-listbox__group');
                if (group && !visibleGroups.has(group)) {
                    group.style.display = '';
                    visibleGroups.add(group);
                }
            });
        }
        
        /**
         * Move active index
         * 
         * @param {number} direction Direction to move (-1 or 1)
         */
        moveActiveIndex(direction) {
            if (this.filteredOptions.length === 0) return;
            
            let newIndex = this.activeIndex + direction;
            
            if (this.config.wrapNavigation) {
                if (newIndex < 0) {
                    newIndex = this.filteredOptions.length - 1;
                } else if (newIndex >= this.filteredOptions.length) {
                    newIndex = 0;
                }
            } else {
                newIndex = Math.max(0, Math.min(newIndex, this.filteredOptions.length - 1));
            }
            
            this.setActiveIndex(newIndex);
        }
        
        /**
         * Set active index
         * 
         * @param {number} index Index to set as active
         */
        setActiveIndex(index) {
            // Remove previous active state
            if (this.activeIndex >= 0 && this.filteredOptions[this.activeIndex]) {
                this.filteredOptions[this.activeIndex].element.classList.remove('wcag-wp-listbox__option--focused');
            }
            
            this.activeIndex = index;
            
            // Set new active state
            if (this.activeIndex >= 0 && this.filteredOptions[this.activeIndex]) {
                const activeOption = this.filteredOptions[this.activeIndex];
                activeOption.element.classList.add('wcag-wp-listbox__option--focused');
                
                // Update aria-activedescendant
                this.listbox.setAttribute('aria-activedescendant', activeOption.element.id);
                
                // Scroll into view
                activeOption.element.scrollIntoView({
                    block: 'nearest',
                    behavior: 'smooth'
                });
                
                // Announce to screen reader
                this.announceActiveOption(activeOption);
            } else {
                this.listbox.removeAttribute('aria-activedescendant');
            }
        }
        
        /**
         * Clear active state
         */
        clearActiveState() {
            if (this.activeIndex >= 0 && this.filteredOptions[this.activeIndex]) {
                this.filteredOptions[this.activeIndex].element.classList.remove('wcag-wp-listbox__option--focused');
            }
            this.activeIndex = -1;
            this.listbox.removeAttribute('aria-activedescendant');
        }
        
        /**
         * Select option
         * 
         * @param {Object} option Option to select
         * @param {boolean} clearOthers Whether to clear other selections
         */
        selectOption(option, clearOthers = false) {
            if (option.disabled) return;
            
            if (clearOthers) {
                this.selectedValues.clear();
            }
            
            this.selectedValues.add(option.value);
            this.lastSelectedIndex = this.filteredOptions.indexOf(option);
            
            this.updateOptionStates();
            this.updateHiddenInputs();
            this.updateSelectionCount();
            
            // Announce selection
            this.announceSelection(option, true);
            
            // Trigger change event
            this.triggerChangeEvent();
        }
        
        /**
         * Toggle option selection
         * 
         * @param {Object} option Option to toggle
         */
        toggleOption(option) {
            if (option.disabled) return;
            
            const wasSelected = this.selectedValues.has(option.value);
            
            if (wasSelected) {
                this.selectedValues.delete(option.value);
            } else {
                this.selectedValues.add(option.value);
            }
            
            this.lastSelectedIndex = this.filteredOptions.indexOf(option);
            
            this.updateOptionStates();
            this.updateHiddenInputs();
            this.updateSelectionCount();
            
            // Announce selection change
            this.announceSelection(option, !wasSelected);
            
            // Trigger change event
            this.triggerChangeEvent();
        }
        
        /**
         * Select range of options
         * 
         * @param {number} startIndex Start index
         * @param {number} endIndex End index
         */
        selectRange(startIndex, endIndex) {
            if (!this.config.multiselectable) return;
            
            const start = Math.max(0, Math.min(startIndex, endIndex));
            const end = Math.min(this.filteredOptions.length - 1, Math.max(startIndex, endIndex));
            
            for (let i = start; i <= end; i++) {
                const option = this.filteredOptions[i];
                if (option && !option.disabled) {
                    this.selectedValues.add(option.value);
                }
            }
            
            this.updateOptionStates();
            this.updateHiddenInputs();
            this.updateSelectionCount();
            
            // Announce range selection
            const count = end - start + 1;
            this.announceStatus(
                window.wcagListbox.strings?.range_selected?.replace('%d', count) || 
                `${count} opzioni selezionate`
            );
            
            // Trigger change event
            this.triggerChangeEvent();
        }
        
        /**
         * Extend selection in direction
         * 
         * @param {number} direction Direction (-1 or 1)
         */
        extendSelection(direction) {
            if (!this.config.multiselectable || this.activeIndex === -1) return;
            
            const newIndex = Math.max(0, Math.min(this.activeIndex + direction, this.filteredOptions.length - 1));
            
            if (this.lastSelectedIndex === -1) {
                this.lastSelectedIndex = this.activeIndex;
            }
            
            // Select range from last selected to new position
            this.selectRange(this.lastSelectedIndex, newIndex);
            this.setActiveIndex(newIndex);
        }
        
        /**
         * Select all options
         */
        selectAll() {
            if (!this.config.multiselectable) return;
            
            this.filteredOptions.forEach(option => {
                if (!option.disabled) {
                    this.selectedValues.add(option.value);
                }
            });
            
            this.updateOptionStates();
            this.updateHiddenInputs();
            this.updateSelectionCount();
            
            // Announce select all
            this.announceStatus(
                window.wcagListbox.strings?.all_selected || 'Tutte le opzioni selezionate'
            );
            
            // Trigger change event
            this.triggerChangeEvent();
        }
        
        /**
         * Clear all selections
         */
        clearSelection() {
            if (!this.config.allowDeselect) return;
            
            this.selectedValues.clear();
            this.lastSelectedIndex = -1;
            
            this.updateOptionStates();
            this.updateHiddenInputs();
            this.updateSelectionCount();
            
            // Announce clear
            this.announceStatus(
                window.wcagListbox.strings?.selection_cleared || 'Selezione cancellata'
            );
            
            // Trigger change event
            this.triggerChangeEvent();
        }
        
        /**
         * Navigate by character (type-ahead)
         * 
         * @param {string} char Character to search for
         */
        navigateByCharacter(char) {
            const startIndex = this.activeIndex + 1;
            const searchChar = char.toLowerCase();
            
            // Search from current position forward
            for (let i = 0; i < this.filteredOptions.length; i++) {
                const index = (startIndex + i) % this.filteredOptions.length;
                const option = this.filteredOptions[index];
                
                if (option.label.toLowerCase().startsWith(searchChar)) {
                    this.setActiveIndex(index);
                    return;
                }
            }
        }
        
        /**
         * Update option states in DOM
         */
        updateOptionStates() {
            this.options.forEach(option => {
                const isSelected = this.selectedValues.has(option.value);
                option.element.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                option.selected = isSelected;
                
                // Update checkbox for multiselectable
                if (this.config.multiselectable) {
                    const checkboxIcon = option.element.querySelector('.wcag-wp-listbox__checkbox-icon');
                    if (checkboxIcon) {
                        checkboxIcon.textContent = isSelected ? '✓' : '';
                    }
                }
            });
        }
        
        /**
         * Update hidden inputs for form submission
         */
        updateHiddenInputs() {
            // Clear existing inputs
            this.hiddenInputsContainer.innerHTML = '';
            
            // Create new inputs
            if (this.config.multiselectable) {
                // Multiple selection: create array of inputs
                const fieldName = this.getFieldName();
                this.selectedValues.forEach(value => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = fieldName + '[]';
                    input.value = value;
                    this.hiddenInputsContainer.appendChild(input);
                });
            } else {
                // Single selection: one input
                const fieldName = this.getFieldName();
                const value = Array.from(this.selectedValues)[0] || '';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = fieldName;
                input.value = value;
                this.hiddenInputsContainer.appendChild(input);
            }
        }
        
        /**
         * Update selection count display
         */
        updateSelectionCount() {
            if (!this.config.showSelectionCount || !this.countDisplay) return;
            
            const count = this.selectedValues.size;
            const text = window.wcagListbox.strings?.selection_count?.replace('%d', count) || 
                        `Selezionati: ${count}`;
            
            this.countDisplay.textContent = text;
        }
        
        /**
         * Update display based on current state
         */
        updateDisplay() {
            this.updateOptionStates();
            this.updateHiddenInputs();
            this.updateSelectionCount();
        }
        
        /**
         * Get field name for form submission
         * 
         * @returns {string} Field name
         */
        getFieldName() {
            // Try to get from existing hidden input or generate default
            const existingInput = this.hiddenInputsContainer.querySelector('input[type="hidden"]');
            if (existingInput) {
                return existingInput.name.replace('[]', '');
            }
            return `wcag_listbox_${this.config.id}`;
        }
        
        /**
         * Announce status to screen reader
         * 
         * @param {string} message Message to announce
         */
        announceStatus(message) {
            if (this.status) {
                this.status.textContent = message;
            }
        }
        
        /**
         * Announce search results
         * 
         * @param {number} count Number of results
         * @param {string} query Search query
         */
        announceSearchResults(count, query) {
            let message;
            if (count === 0) {
                message = window.wcagListbox.strings?.no_results || 'Nessun risultato trovato';
            } else if (count === 1) {
                message = '1 ' + (window.wcagListbox.strings?.result || 'risultato');
            } else {
                message = `${count} ${window.wcagListbox.strings?.results || 'risultati'}`;
            }
            
            if (query) {
                message += ` per "${query}"`;
            }
            
            this.announceStatus(message);
        }
        
        /**
         * Announce active option
         * 
         * @param {Object} option Active option
         */
        announceActiveOption(option) {
            let message = option.label;
            if (option.description) {
                message += `, ${option.description}`;
            }
            if (option.group) {
                message += `, gruppo ${option.group}`;
            }
            
            // Add position info
            const position = this.filteredOptions.indexOf(option) + 1;
            message += `, ${position} di ${this.filteredOptions.length}`;
            
            this.announceStatus(message);
        }
        
        /**
         * Announce selection change
         * 
         * @param {Object} option Selected/deselected option
         * @param {boolean} selected Whether option was selected or deselected
         */
        announceSelection(option, selected) {
            const action = selected ? 
                (window.wcagListbox.strings?.selected || 'selezionato') :
                (window.wcagListbox.strings?.deselected || 'deselezionato');
            
            let message = `${option.label} ${action}`;
            
            if (this.config.multiselectable) {
                const count = this.selectedValues.size;
                message += `. ${count} ${window.wcagListbox.strings?.options || 'opzioni'} selezionate`;
            }
            
            this.announceStatus(message);
        }
        
        /**
         * Trigger change event
         */
        triggerChangeEvent() {
            const event = new CustomEvent('wcag-listbox-change', {
                detail: {
                    listbox: this,
                    listboxId: this.config.id,
                    selectedValues: Array.from(this.selectedValues)
                },
                bubbles: true
            });
            
            this.element.dispatchEvent(event);
        }
        
        /**
         * Public API: Select values programmatically
         * 
         * @param {Array} values Array of values to select
         */
        selectValues(values) {
            if (!Array.isArray(values)) return;
            
            if (!this.config.multiselectable) {
                // Single selection: only select first value
                values = values.slice(0, 1);
            }
            
            this.selectedValues.clear();
            values.forEach(value => {
                const option = this.options.find(opt => opt.value === value);
                if (option && !option.disabled) {
                    this.selectedValues.add(value);
                }
            });
            
            this.updateDisplay();
            this.triggerChangeEvent();
        }
        
        /**
         * Public API: Get selected values
         * 
         * @returns {Array} Array of selected values
         */
        getSelectedValues() {
            return Array.from(this.selectedValues);
        }
        
        /**
         * Public API: Clear selection
         */
        clearSelection() {
            this.selectedValues.clear();
            this.updateDisplay();
            this.triggerChangeEvent();
        }
    }
    
    /**
     * Global function to initialize individual listboxes
     * Called from listbox templates
     * 
     * @param {HTMLElement} element Listbox element
     * @returns {WcagListbox} Listbox instance
     */
    window.wcagInitListbox = function(element) {
        if (window.wcagListboxManager) {
            return window.wcagListboxManager.initListbox(element);
        }
        return null;
    };
    
    /**
     * Global function to get listbox by ID
     * 
     * @param {string} id Listbox ID
     * @returns {WcagListbox|null} Listbox instance
     */
    window.wcagGetListbox = function(id) {
        if (window.wcagListboxManager) {
            return window.wcagListboxManager.getListbox(id);
        }
        return null;
    };
    
    /**
     * Initialize listbox system when DOM is ready
     */
    function initListboxSystem() {
        if (!window.wcagListboxManager) {
            window.wcagListboxManager = new WcagListboxManager();
        }
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initListboxSystem);
    } else {
        initListboxSystem();
    }
    
})();

