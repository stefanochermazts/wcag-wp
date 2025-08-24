/**
 * Radio Group Component - Frontend JavaScript
 * WCAG 2.1 AA Compliant
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function() {
    'use strict';

    /**
     * Radio Group Component Class
     */
    class WCAGRadioGroup {
        constructor(element) {
            this.element = element;
            this.radioInputs = this.element.querySelectorAll('input[type="radio"]');
            this.labels = this.element.querySelectorAll('label');
            this.liveRegion = this.element.querySelector('[aria-live]');
            this.errorElement = this.element.querySelector('.wcag-wp-radio-group__error');
            this.errorText = this.element.querySelector('.wcag-wp-radio-group__error-text');
            
            this.isRequired = this.element.getAttribute('aria-required') === 'true';
            this.isDisabled = this.element.getAttribute('aria-disabled') === 'true';
            
            this.init();
        }

        /**
         * Inizializza il componente
         */
        init() {
            if (this.isDisabled) {
                return;
            }

            this.setupEventListeners();
            this.setupKeyboardNavigation();
            this.validateInitialState();
            this.announceToScreenReader('Radio group inizializzato');
        }

        /**
         * Configura gli event listener
         */
        setupEventListeners() {
            // Event listener per cambio selezione
            this.radioInputs.forEach(radio => {
                radio.addEventListener('change', (e) => this.handleRadioChange(e));
                radio.addEventListener('focus', (e) => this.handleRadioFocus(e));
                radio.addEventListener('blur', (e) => this.handleRadioBlur(e));
            });

            // Event listener per click sui label
            this.labels.forEach(label => {
                label.addEventListener('click', (e) => this.handleLabelClick(e));
            });

            // Event listener per validazione quando il form viene sottomesso
            const form = this.element.closest('form');
            if (form) {
                form.addEventListener('submit', (e) => this.handleFormSubmit(e));
            }
        }

        /**
         * Configura la navigazione tastiera
         */
        setupKeyboardNavigation() {
            this.element.addEventListener('keydown', (e) => this.handleKeydown(e));
        }

        /**
         * Gestisce la navigazione tastiera
         */
        handleKeydown(event) {
            const currentRadio = event.target;
            
            if (!currentRadio.matches('input[type="radio"]')) {
                return;
            }

            let nextRadio = null;

            switch (event.key) {
                case 'ArrowUp':
                case 'ArrowLeft':
                    event.preventDefault();
                    nextRadio = this.getPreviousRadio(currentRadio);
                    break;
                    
                case 'ArrowDown':
                case 'ArrowRight':
                    event.preventDefault();
                    nextRadio = this.getNextRadio(currentRadio);
                    break;
                    
                case 'Home':
                    event.preventDefault();
                    nextRadio = this.radioInputs[0];
                    break;
                    
                case 'End':
                    event.preventDefault();
                    nextRadio = this.radioInputs[this.radioInputs.length - 1];
                    break;
                    
                case ' ':
                case 'Enter':
                    event.preventDefault();
                    this.selectRadio(currentRadio);
                    break;
            }

            if (nextRadio && !nextRadio.disabled) {
                nextRadio.focus();
                this.announceToScreenReader(`Opzione ${nextRadio.value} focalizzata`);
            }
        }

        /**
         * Ottiene il radio button precedente
         */
        getPreviousRadio(currentRadio) {
            const currentIndex = Array.from(this.radioInputs).indexOf(currentRadio);
            const previousIndex = currentIndex > 0 ? currentIndex - 1 : this.radioInputs.length - 1;
            return this.radioInputs[previousIndex];
        }

        /**
         * Ottiene il radio button successivo
         */
        getNextRadio(currentRadio) {
            const currentIndex = Array.from(this.radioInputs).indexOf(currentRadio);
            const nextIndex = currentIndex < this.radioInputs.length - 1 ? currentIndex + 1 : 0;
            return this.radioInputs[nextIndex];
        }

        /**
         * Gestisce il cambio di selezione
         */
        handleRadioChange(event) {
            const selectedRadio = event.target;
            const selectedLabel = this.getLabelForRadio(selectedRadio);
            
            // Aggiorna aria-checked per tutti i radio
            this.radioInputs.forEach(radio => {
                radio.setAttribute('aria-checked', radio === selectedRadio ? 'true' : 'false');
            });

            // Rimuovi stati di errore se presente
            this.clearError();

            // Annuncia la selezione
            const labelText = selectedLabel ? selectedLabel.textContent.trim() : selectedRadio.value;
            this.announceToScreenReader(`Selezionato: ${labelText}`);

            // Emetti evento personalizzato
            this.emitCustomEvent('radioChange', {
                value: selectedRadio.value,
                label: labelText,
                radio: selectedRadio
            });
        }

        /**
         * Gestisce il focus su un radio button
         */
        handleRadioFocus(event) {
            const radio = event.target;
            const label = this.getLabelForRadio(radio);
            
            // Aggiungi classe di focus
            this.element.classList.add('wcag-wp-radio-group--focused');
            
            // Annuncia l'opzione focalizzata
            const labelText = label ? label.textContent.trim() : radio.value;
            this.announceToScreenReader(`Opzione ${labelText} focalizzata`);
        }

        /**
         * Gestisce la perdita di focus
         */
        handleRadioBlur(event) {
            // Rimuovi classe di focus se nessun radio è focalizzato
            const hasFocus = Array.from(this.radioInputs).some(radio => 
                radio === document.activeElement
            );
            
            if (!hasFocus) {
                this.element.classList.remove('wcag-wp-radio-group--focused');
            }
        }

        /**
         * Gestisce il click sui label
         */
        handleLabelClick(event) {
            const label = event.currentTarget;
            const radio = this.element.querySelector(`#${label.getAttribute('for')}`);
            
            if (radio && !radio.disabled) {
                this.selectRadio(radio);
            }
        }

        /**
         * Seleziona un radio button
         */
        selectRadio(radio) {
            if (radio.disabled) {
                return;
            }

            radio.checked = true;
            radio.dispatchEvent(new Event('change', { bubbles: true }));
        }

        /**
         * Gestisce la sottomissione del form
         */
        handleFormSubmit(event) {
            if (this.isRequired && !this.hasSelection()) {
                event.preventDefault();
                this.showError('Seleziona un\'opzione per continuare');
                return false;
            }
            
            return true;
        }

        /**
         * Valida lo stato iniziale
         */
        validateInitialState() {
            if (this.isRequired && !this.hasSelection()) {
                this.element.classList.add('wcag-wp-radio-group--error');
            }
        }

        /**
         * Verifica se c'è una selezione
         */
        hasSelection() {
            return Array.from(this.radioInputs).some(radio => radio.checked);
        }

        /**
         * Ottiene il label associato a un radio button
         */
        getLabelForRadio(radio) {
            const label = this.element.querySelector(`label[for="${radio.id}"]`);
            return label;
        }

        /**
         * Mostra un messaggio di errore
         */
        showError(message) {
            if (!this.errorElement || !this.errorText) {
                return;
            }

            this.errorText.textContent = message;
            this.errorElement.setAttribute('aria-hidden', 'false');
            this.element.classList.add('wcag-wp-radio-group--error');
            
            // Annuncia l'errore
            this.announceToScreenReader(`Errore: ${message}`, 'assertive');
            
            // Focus sul primo radio per accessibilità
            const firstRadio = this.radioInputs[0];
            if (firstRadio) {
                firstRadio.focus();
            }
        }

        /**
         * Rimuove il messaggio di errore
         */
        clearError() {
            if (!this.errorElement) {
                return;
            }

            this.errorText.textContent = '';
            this.errorElement.setAttribute('aria-hidden', 'true');
            this.element.classList.remove('wcag-wp-radio-group--error');
        }

        /**
         * Annuncia messaggi agli screen reader
         */
        announceToScreenReader(message, priority = 'polite') {
            if (!this.liveRegion) {
                return;
            }

            // Cambia temporaneamente la priorità se specificata
            const originalPriority = this.liveRegion.getAttribute('aria-live');
            if (priority) {
                this.liveRegion.setAttribute('aria-live', priority);
            }

            // Pulisci e imposta il messaggio
            this.liveRegion.textContent = '';
            
            // Usa setTimeout per assicurarsi che il messaggio venga annunciato
            setTimeout(() => {
                this.liveRegion.textContent = message;
                
                // Ripristina la priorità originale
                if (priority && originalPriority) {
                    setTimeout(() => {
                        this.liveRegion.setAttribute('aria-live', originalPriority);
                    }, 100);
                }
            }, 10);
        }

        /**
         * Emette un evento personalizzato
         */
        emitCustomEvent(eventName, detail = {}) {
            const event = new CustomEvent(`wcag-radio-group:${eventName}`, {
                detail: {
                    element: this.element,
                    ...detail
                },
                bubbles: true
            });
            
            this.element.dispatchEvent(event);
        }

        /**
         * Ottiene il valore selezionato
         */
        getValue() {
            const selectedRadio = Array.from(this.radioInputs).find(radio => radio.checked);
            return selectedRadio ? selectedRadio.value : null;
        }

        /**
         * Imposta il valore selezionato
         */
        setValue(value) {
            const radio = Array.from(this.radioInputs).find(r => r.value === value);
            if (radio) {
                this.selectRadio(radio);
            }
        }

        /**
         * Abilita/disabilita il radio group
         */
        setDisabled(disabled) {
            this.isDisabled = disabled;
            this.element.setAttribute('aria-disabled', disabled ? 'true' : 'false');
            
            this.radioInputs.forEach(radio => {
                radio.disabled = disabled;
                radio.setAttribute('aria-disabled', disabled ? 'true' : 'false');
            });

            if (disabled) {
                this.element.classList.add('wcag-wp-radio-group--disabled');
            } else {
                this.element.classList.remove('wcag-wp-radio-group--disabled');
            }
        }

        /**
         * Valida il radio group
         */
        validate() {
            if (this.isRequired && !this.hasSelection()) {
                this.showError('Questo campo è obbligatorio');
                return false;
            }
            
            this.clearError();
            return true;
        }
    }

    /**
     * Inizializza tutti i radio group nella pagina
     */
    function initRadioGroups() {
        const radioGroups = document.querySelectorAll('[data-wcag-radio-group]');
        
        radioGroups.forEach(element => {
            if (!element.wcagRadioGroupInstance) {
                element.wcagRadioGroupInstance = new WCAGRadioGroup(element);
            }
        });
    }

    /**
     * Inizializza quando il DOM è pronto
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRadioGroups);
    } else {
        initRadioGroups();
    }

    /**
     * Inizializza per contenuti caricati dinamicamente
     */
    if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            if (node.matches('[data-wcag-radio-group]')) {
                                node.wcagRadioGroupInstance = new WCAGRadioGroup(node);
                            }
                            
                            const radioGroups = node.querySelectorAll('[data-wcag-radio-group]');
                            radioGroups.forEach(element => {
                                if (!element.wcagRadioGroupInstance) {
                                    element.wcagRadioGroupInstance = new WCAGRadioGroup(element);
                                }
                            });
                        }
                    });
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    /**
     * Espone la classe globalmente per uso esterno
     */
    window.WCAGRadioGroup = WCAGRadioGroup;

})();
