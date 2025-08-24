/**
 * WCAG Combobox Frontend JavaScript
 * 
 * Implements accessible combobox following WCAG APG pattern:
 * - Keyboard navigation (Arrow keys, Enter, Esc, Tab)
 * - ARIA state management
 * - Screen reader announcements
 * - Dynamic option filtering
 * - Multiple selection support
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function() {
    'use strict';
    
    // Global combobox manager
    window.wcagCombobox = window.wcagCombobox || {};
    
    /**
     * Combobox Manager Class
     */
    class WcagComboboxManager {
        constructor() {
            this.comboboxes = new Map();
            this.init();
        }
        
        /**
         * Initialize combobox system
         */
        init() {
            this.initializeExistingComboboxes();
        }
        
        /**
         * Initialize existing comboboxes on page load
         */
        initializeExistingComboboxes() {
            const comboboxes = document.querySelectorAll('.wcag-wp-combobox');
            comboboxes.forEach(combobox => {
                this.initCombobox(combobox);
            });
        }
        
        /**
         * Initialize a single combobox
         * 
         * @param {HTMLElement} element Combobox element
         */
        initCombobox(element) {
            if (!element || this.comboboxes.has(element)) {
                return;
            }
            
            const config = this.getComboboxConfig(element);
            const combobox = new WcagCombobox(element, config);
            
            this.comboboxes.set(element, combobox);
            
            return combobox;
        }
        
        /**
         * Get combobox configuration from element
         * 
         * @param {HTMLElement} element Combobox element
         * @returns {Object} Configuration object
         */
        getComboboxConfig(element) {
            return {
                id: element.getAttribute('data-combobox-id') || '',
                type: element.getAttribute('data-combobox-type') || 'autocomplete',
                autocomplete: element.getAttribute('data-autocomplete') || 'list',
                multiple: element.getAttribute('data-multiple') === 'true',
                required: element.getAttribute('data-required') === 'true',
                maxResults: parseInt(element.getAttribute('data-max-results')) || 10,
                minChars: parseInt(element.getAttribute('data-min-chars')) || 1,
                debounceDelay: parseInt(element.getAttribute('data-debounce-delay')) || 300,
                caseSensitive: element.getAttribute('data-case-sensitive') === 'true',
                dataSource: element.getAttribute('data-data-source') || 'static'
            };
        }
        
        /**
         * Remove combobox from manager
         * 
         * @param {HTMLElement} element Combobox element
         */
        removeCombobox(element) {
            this.comboboxes.delete(element);
        }
    }
    
    /**
     * Individual Combobox Class
     */
    class WcagCombobox {
        constructor(element, config) {
            this.element = element;
            this.config = config;
            
            // Get DOM elements
            this.input = element.querySelector('.wcag-wp-combobox__input');
            this.toggle = element.querySelector('.wcag-wp-combobox__toggle');
            this.listbox = element.querySelector('.wcag-wp-combobox__listbox');
            this.status = element.querySelector('[aria-live]');
            this.selectedContainer = element.querySelector('.wcag-wp-combobox__selected');
            this.noResults = element.querySelector('.wcag-wp-combobox__no-results');
            
            // State
            this.isOpen = false;
            this.activeIndex = -1;
            this.options = [];
            this.selectedValues = new Set();
            this.searchTimeout = null;
            
            this.init();
        }
        
        /**
         * Initialize combobox
         */
        init() {
            this.loadInitialOptions();
            this.loadSelectedValues();
            this.bindEvents();
            this.updateDisplay();
        }
        
        /**
         * Load initial options from DOM
         */
        loadInitialOptions() {
            const optionElements = this.listbox.querySelectorAll('.wcag-wp-combobox__option');
            this.options = Array.from(optionElements).map((el, index) => ({
                element: el,
                value: el.getAttribute('data-value') || '',
                label: el.querySelector('.wcag-wp-combobox__option-label')?.textContent || '',
                description: el.querySelector('.wcag-wp-combobox__option-description')?.textContent || '',
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
        }
        
        /**
         * Load selected values from hidden inputs
         */
        loadSelectedValues() {
            const hiddenInputs = this.element.querySelectorAll('input[type="hidden"]');
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
            // Input events
            this.input.addEventListener('input', (e) => this.handleInput(e));
            this.input.addEventListener('keydown', (e) => this.handleKeyDown(e));
            this.input.addEventListener('focus', (e) => this.handleFocus(e));
            this.input.addEventListener('blur', (e) => this.handleBlur(e));
            
            // Toggle button
            this.toggle.addEventListener('click', (e) => this.handleToggleClick(e));
            
            // Listbox events
            this.listbox.addEventListener('click', (e) => this.handleListboxClick(e));
            this.listbox.addEventListener('mousedown', (e) => e.preventDefault()); // Prevent blur
            
            // Global events
            document.addEventListener('click', (e) => this.handleDocumentClick(e));
            window.addEventListener('resize', () => this.updatePosition());
        }
        
        /**
         * Handle input changes
         * 
         * @param {Event} e Input event
         */
        handleInput(e) {
            const query = e.target.value;
            
            // Clear previous timeout
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            // Debounce search
            this.searchTimeout = setTimeout(() => {
                this.search(query);
            }, this.config.debounceDelay);
        }
        
        /**
         * Handle keyboard navigation
         * 
         * @param {Event} e Keyboard event
         */
        handleKeyDown(e) {
            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (!this.isOpen) {
                        this.open();
                    } else {
                        this.moveActiveIndex(1);
                    }
                    break;
                    
                case 'ArrowUp':
                    e.preventDefault();
                    if (this.isOpen) {
                        this.moveActiveIndex(-1);
                    }
                    break;
                    
                case 'Enter':
                    e.preventDefault();
                    if (this.isOpen && this.activeIndex >= 0) {
                        this.selectOption(this.options[this.activeIndex]);
                    } else if (!this.isOpen) {
                        this.open();
                    }
                    break;
                    
                case 'Escape':
                    e.preventDefault();
                    this.close();
                    break;
                    
                case 'Tab':
                    if (this.isOpen) {
                        this.close();
                    }
                    break;
                    
                case 'Home':
                    if (this.isOpen) {
                        e.preventDefault();
                        this.setActiveIndex(0);
                    }
                    break;
                    
                case 'End':
                    if (this.isOpen) {
                        e.preventDefault();
                        this.setActiveIndex(this.options.length - 1);
                    }
                    break;
                    
                case ' ':
                    if (!this.isOpen && this.input.value === '') {
                        e.preventDefault();
                        this.open();
                    }
                    break;
            }
        }
        
        /**
         * Handle input focus
         * 
         * @param {Event} e Focus event
         */
        handleFocus(e) {
            // Auto-open on focus if configured
            if (this.config.type === 'select' && !this.isOpen) {
                setTimeout(() => this.open(), 100);
            }
        }
        
        /**
         * Handle input blur
         * 
         * @param {Event} e Blur event
         */
        handleBlur(e) {
            // Delay close to allow for option selection
            setTimeout(() => {
                if (!this.element.contains(document.activeElement)) {
                    this.close();
                }
            }, 150);
        }
        
        /**
         * Handle toggle button click
         * 
         * @param {Event} e Click event
         */
        handleToggleClick(e) {
            e.preventDefault();
            
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
                this.input.focus();
            }
        }
        
        /**
         * Handle listbox clicks
         * 
         * @param {Event} e Click event
         */
        handleListboxClick(e) {
            const optionElement = e.target.closest('.wcag-wp-combobox__option');
            if (optionElement && !optionElement.classList.contains('wcag-wp-combobox__no-results')) {
                const option = this.options.find(opt => opt.element === optionElement);
                if (option && !option.disabled) {
                    this.selectOption(option);
                }
            }
        }
        
        /**
         * Handle document clicks (close on outside click)
         * 
         * @param {Event} e Click event
         */
        handleDocumentClick(e) {
            if (!this.element.contains(e.target)) {
                this.close();
            }
        }
        
        /**
         * Search for options
         * 
         * @param {string} query Search query
         */
        search(query) {
            if (query.length < this.config.minChars) {
                this.showAllOptions();
                return;
            }
            
            if (this.config.dataSource === 'static') {
                this.searchStatic(query);
            } else {
                this.searchDynamic(query);
            }
        }
        
        /**
         * Search static options
         * 
         * @param {string} query Search query
         */
        searchStatic(query) {
            const filteredOptions = this.options.filter(option => {
                const searchText = `${option.label} ${option.description} ${option.group}`;
                
                if (this.config.caseSensitive) {
                    return searchText.includes(query);
                } else {
                    return searchText.toLowerCase().includes(query.toLowerCase());
                }
            });
            
            this.displayOptions(filteredOptions);
            
            if (!this.isOpen) {
                this.open();
            }
        }
        
        /**
         * Search dynamic options via AJAX
         * 
         * @param {string} query Search query
         */
        searchDynamic(query) {
            this.setLoading(true);
            
            const data = new FormData();
            data.append('action', 'wcag_combobox_search');
            data.append('nonce', window.wcagCombobox.nonce);
            data.append('combobox_id', this.config.id);
            data.append('query', query);
            
            fetch(window.wcagCombobox.ajax_url, {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(result => {
                this.setLoading(false);
                
                if (result.success && result.data.options) {
                    this.createDynamicOptions(result.data.options);
                    
                    if (!this.isOpen) {
                        this.open();
                    }
                } else {
                    this.displayOptions([]);
                }
            })
            .catch(error => {
                console.error('Combobox search error:', error);
                this.setLoading(false);
                this.displayOptions([]);
            });
        }
        
        /**
         * Create dynamic options from AJAX response
         * 
         * @param {Array} optionsData Options data from server
         */
        createDynamicOptions(optionsData) {
            // Clear existing options
            this.options = [];
            this.listbox.innerHTML = '';
            
            // Create new options
            optionsData.forEach((optionData, index) => {
                const optionElement = this.createOptionElement(optionData, index);
                this.listbox.appendChild(optionElement);
                
                this.options.push({
                    element: optionElement,
                    value: optionData.value || '',
                    label: optionData.label || '',
                    description: optionData.description || '',
                    group: optionData.group || '',
                    disabled: optionData.disabled || false,
                    selected: this.selectedValues.has(optionData.value || ''),
                    index: index
                });
            });
            
            // Re-append no results element
            if (this.noResults) {
                this.listbox.appendChild(this.noResults);
            }
            
            this.displayOptions(this.options);
        }
        
        /**
         * Create option element
         * 
         * @param {Object} optionData Option data
         * @param {number} index Option index
         * @returns {HTMLElement} Option element
         */
        createOptionElement(optionData, index) {
            const option = document.createElement('div');
            option.className = 'wcag-wp-combobox__option';
            option.setAttribute('role', 'option');
            option.setAttribute('id', `${this.input.id}-option-${index}`);
            option.setAttribute('data-value', optionData.value || '');
            option.setAttribute('aria-selected', 'false');
            
            if (optionData.group) {
                option.setAttribute('data-group', optionData.group);
            }
            
            if (optionData.disabled) {
                option.setAttribute('aria-disabled', 'true');
            }
            
            const content = document.createElement('div');
            content.className = 'wcag-wp-combobox__option-content';
            
            const label = document.createElement('div');
            label.className = 'wcag-wp-combobox__option-label';
            label.textContent = optionData.label || optionData.value || '';
            content.appendChild(label);
            
            if (optionData.description) {
                const description = document.createElement('div');
                description.className = 'wcag-wp-combobox__option-description';
                description.textContent = optionData.description;
                content.appendChild(description);
            }
            
            if (optionData.group) {
                const group = document.createElement('div');
                group.className = 'wcag-wp-combobox__option-group';
                group.textContent = optionData.group;
                content.appendChild(group);
            }
            
            option.appendChild(content);
            
            if (this.config.multiple) {
                const checkbox = document.createElement('div');
                checkbox.className = 'wcag-wp-combobox__option-checkbox';
                checkbox.setAttribute('aria-hidden', 'true');
                
                const checkboxIcon = document.createElement('span');
                checkboxIcon.className = 'wcag-wp-combobox__checkbox-icon';
                checkbox.appendChild(checkboxIcon);
                
                option.appendChild(checkbox);
            }
            
            return option;
        }
        
        /**
         * Display filtered options
         * 
         * @param {Array} filteredOptions Filtered options to display
         */
        displayOptions(filteredOptions) {
            // Hide all options first
            this.options.forEach(option => {
                option.element.style.display = 'none';
            });
            
            // Show filtered options
            filteredOptions.forEach(option => {
                option.element.style.display = 'flex';
                
                // Update selected state
                const isSelected = this.selectedValues.has(option.value);
                option.element.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                option.selected = isSelected;
                
                // Update checkbox for multiple selection
                if (this.config.multiple) {
                    const checkboxIcon = option.element.querySelector('.wcag-wp-combobox__checkbox-icon');
                    if (checkboxIcon) {
                        checkboxIcon.textContent = isSelected ? '✓' : '';
                    }
                }
            });
            
            // Show/hide no results message
            if (this.noResults) {
                if (filteredOptions.length === 0) {
                    this.noResults.removeAttribute('hidden');
                } else {
                    this.noResults.setAttribute('hidden', '');
                }
            }
            
            // Reset active index
            this.setActiveIndex(-1);
            
            // Announce results to screen reader
            this.announceResults(filteredOptions.length);
        }
        
        /**
         * Show all options
         */
        showAllOptions() {
            this.displayOptions(this.options);
        }
        
        /**
         * Open listbox
         */
        open() {
            if (this.isOpen) return;
            
            this.isOpen = true;
            this.input.setAttribute('aria-expanded', 'true');
            this.listbox.removeAttribute('hidden');
            this.element.classList.add('wcag-wp-combobox--open');
            
            this.updatePosition();
            
            // Show all options if no search query
            if (this.input.value.length < this.config.minChars) {
                this.showAllOptions();
            }
            
            // Announce to screen reader
            this.announceStatus(window.wcagCombobox.strings?.listbox_opened || 'Lista opzioni aperta');
        }
        
        /**
         * Close listbox
         */
        close() {
            if (!this.isOpen) return;
            
            this.isOpen = false;
            this.input.setAttribute('aria-expanded', 'false');
            this.listbox.setAttribute('hidden', '');
            this.element.classList.remove('wcag-wp-combobox--open');
            
            this.setActiveIndex(-1);
            
            // Announce to screen reader
            this.announceStatus(window.wcagCombobox.strings?.listbox_closed || 'Lista opzioni chiusa');
        }
        
        /**
         * Move active index
         * 
         * @param {number} direction Direction to move (-1 or 1)
         */
        moveActiveIndex(direction) {
            const visibleOptions = this.options.filter(opt => 
                opt.element.style.display !== 'none' && !opt.disabled
            );
            
            if (visibleOptions.length === 0) return;
            
            let newIndex = this.activeIndex + direction;
            
            if (newIndex < 0) {
                newIndex = visibleOptions.length - 1;
            } else if (newIndex >= visibleOptions.length) {
                newIndex = 0;
            }
            
            const globalIndex = this.options.indexOf(visibleOptions[newIndex]);
            this.setActiveIndex(globalIndex);
        }
        
        /**
         * Set active index
         * 
         * @param {number} index Index to set as active
         */
        setActiveIndex(index) {
            // Remove previous active state
            if (this.activeIndex >= 0 && this.options[this.activeIndex]) {
                this.options[this.activeIndex].element.classList.remove('wcag-wp-combobox__option--focused');
            }
            
            this.activeIndex = index;
            
            // Set new active state
            if (this.activeIndex >= 0 && this.options[this.activeIndex]) {
                const activeOption = this.options[this.activeIndex];
                activeOption.element.classList.add('wcag-wp-combobox__option--focused');
                
                // Update aria-activedescendant
                this.input.setAttribute('aria-activedescendant', activeOption.element.id);
                
                // Scroll into view
                activeOption.element.scrollIntoView({
                    block: 'nearest',
                    behavior: 'smooth'
                });
                
                // Announce to screen reader
                this.announceActiveOption(activeOption);
            } else {
                this.input.removeAttribute('aria-activedescendant');
            }
        }
        
        /**
         * Select option
         * 
         * @param {Object} option Option to select
         */
        selectOption(option) {
            if (option.disabled) return;
            
            if (this.config.multiple) {
                // Toggle selection for multiple
                if (this.selectedValues.has(option.value)) {
                    this.selectedValues.delete(option.value);
                    option.selected = false;
                } else {
                    this.selectedValues.add(option.value);
                    option.selected = true;
                }
                
                this.updateSelectedDisplay();
                this.updateHiddenInputs();
                
                // Keep listbox open for multiple selection
                this.displayOptions(this.options.filter(opt => 
                    opt.element.style.display !== 'none'
                ));
                
            } else {
                // Single selection
                this.selectedValues.clear();
                this.selectedValues.add(option.value);
                
                // Update input value
                this.input.value = option.label;
                
                // Update hidden input
                this.updateHiddenInputs();
                
                // Close listbox
                this.close();
            }
            
            // Announce selection
            this.announceSelection(option);
            
            // Trigger change event
            this.triggerChangeEvent();
        }
        
        /**
         * Update selected items display (for multiple selection)
         */
        updateSelectedDisplay() {
            if (!this.config.multiple || !this.selectedContainer) return;
            
            this.selectedContainer.innerHTML = '';
            
            this.selectedValues.forEach(value => {
                const option = this.options.find(opt => opt.value === value);
                if (option) {
                    const selectedItem = this.createSelectedItem(option);
                    this.selectedContainer.appendChild(selectedItem);
                }
            });
        }
        
        /**
         * Create selected item element
         * 
         * @param {Object} option Selected option
         * @returns {HTMLElement} Selected item element
         */
        createSelectedItem(option) {
            const item = document.createElement('div');
            item.className = 'wcag-wp-combobox__selected-item';
            item.setAttribute('data-value', option.value);
            
            const label = document.createElement('span');
            label.className = 'wcag-wp-combobox__selected-label';
            label.textContent = option.label;
            item.appendChild(label);
            
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'wcag-wp-combobox__selected-remove';
            removeBtn.setAttribute('aria-label', 
                `${window.wcagCombobox.strings?.remove || 'Rimuovi'} ${option.label}`);
            removeBtn.innerHTML = '×';
            
            removeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.selectedValues.delete(option.value);
                option.selected = false;
                this.updateSelectedDisplay();
                this.updateHiddenInputs();
                this.displayOptions(this.options.filter(opt => 
                    opt.element.style.display !== 'none'
                ));
                this.triggerChangeEvent();
                
                // Announce removal
                this.announceStatus(
                    `${option.label} ${window.wcagCombobox.strings?.removed || 'rimosso'}`
                );
            });
            
            item.appendChild(removeBtn);
            
            return item;
        }
        
        /**
         * Update hidden inputs for form submission
         */
        updateHiddenInputs() {
            const hiddenContainer = this.element.querySelector('.wcag-wp-combobox__hidden-inputs') || 
                                   this.element;
            
            // Remove existing hidden inputs
            const existingInputs = hiddenContainer.querySelectorAll('input[type="hidden"]');
            existingInputs.forEach(input => input.remove());
            
            // Create new hidden inputs
            if (this.config.multiple) {
                const fieldName = this.input.name.replace('[]', '');
                this.selectedValues.forEach(value => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `${fieldName}[]`;
                    input.value = value;
                    hiddenContainer.appendChild(input);
                });
            } else {
                const fieldName = this.input.name + '_value';
                const value = Array.from(this.selectedValues)[0] || '';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = fieldName;
                input.value = value;
                hiddenContainer.appendChild(input);
            }
        }
        
        /**
         * Set loading state
         * 
         * @param {boolean} loading Loading state
         */
        setLoading(loading) {
            if (loading) {
                this.element.classList.add('wcag-wp-combobox--loading');
            } else {
                this.element.classList.remove('wcag-wp-combobox--loading');
            }
        }
        
        /**
         * Update listbox position
         */
        updatePosition() {
            if (!this.isOpen) return;
            
            const rect = this.input.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const spaceBelow = viewportHeight - rect.bottom;
            const spaceAbove = rect.top;
            
            // Position listbox below or above based on available space
            if (spaceBelow < 200 && spaceAbove > spaceBelow) {
                this.listbox.style.top = 'auto';
                this.listbox.style.bottom = '100%';
                this.listbox.style.borderTop = '2px solid var(--wcag-primary, #2563eb)';
                this.listbox.style.borderBottom = 'none';
                this.listbox.style.borderTopLeftRadius = 'var(--wcag-radius, 6px)';
                this.listbox.style.borderTopRightRadius = 'var(--wcag-radius, 6px)';
                this.listbox.style.borderBottomLeftRadius = '0';
                this.listbox.style.borderBottomRightRadius = '0';
            } else {
                this.listbox.style.top = '100%';
                this.listbox.style.bottom = 'auto';
                this.listbox.style.borderTop = 'none';
                this.listbox.style.borderBottom = '2px solid var(--wcag-primary, #2563eb)';
                this.listbox.style.borderTopLeftRadius = '0';
                this.listbox.style.borderTopRightRadius = '0';
                this.listbox.style.borderBottomLeftRadius = 'var(--wcag-radius, 6px)';
                this.listbox.style.borderBottomRightRadius = 'var(--wcag-radius, 6px)';
            }
        }
        
        /**
         * Update display based on current state
         */
        updateDisplay() {
            this.updateSelectedDisplay();
            this.updateHiddenInputs();
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
         */
        announceResults(count) {
            let message;
            if (count === 0) {
                message = window.wcagCombobox.strings?.no_results || 'Nessun risultato trovato';
            } else if (count === 1) {
                message = '1 ' + (window.wcagCombobox.strings?.result || 'risultato');
            } else {
                message = `${count} ${window.wcagCombobox.strings?.results || 'risultati'}`;
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
                message += `, ${option.group}`;
            }
            
            this.announceStatus(message);
        }
        
        /**
         * Announce selection
         * 
         * @param {Object} option Selected option
         */
        announceSelection(option) {
            const action = this.selectedValues.has(option.value) ? 
                (window.wcagCombobox.strings?.selected || 'selezionato') :
                (window.wcagCombobox.strings?.deselected || 'deselezionato');
            
            this.announceStatus(`${option.label} ${action}`);
        }
        
        /**
         * Trigger change event
         */
        triggerChangeEvent() {
            const event = new CustomEvent('wcag-combobox-change', {
                detail: {
                    combobox: this,
                    selectedValues: Array.from(this.selectedValues)
                },
                bubbles: true
            });
            
            this.element.dispatchEvent(event);
        }
    }
    
    /**
     * Global function to initialize individual comboboxes
     * Called from combobox templates
     * 
     * @param {HTMLElement} element Combobox element
     * @returns {WcagCombobox} Combobox instance
     */
    window.wcagInitCombobox = function(element) {
        if (window.wcagComboboxManager) {
            return window.wcagComboboxManager.initCombobox(element);
        }
        return null;
    };
    
    /**
     * Initialize combobox system when DOM is ready
     */
    function initComboboxSystem() {
        if (!window.wcagComboboxManager) {
            window.wcagComboboxManager = new WcagComboboxManager();
        }
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initComboboxSystem);
    } else {
        initComboboxSystem();
    }
    
})();

