/**
 * Radio Group Component - Admin JavaScript
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Radio Group Admin Class
     */
    class WCAGRadioGroupAdmin {
        constructor() {
            this.optionIndex = 0;
            this.init();
        }

        /**
         * Inizializza il componente admin
         */
        init() {
            this.setupEventListeners();
            this.updateDefaultValueOptions();
            this.setupCopyShortcode();
        }

        /**
         * Configura gli event listener
         */
        setupEventListeners() {
            // Aggiungi opzione
            $(document).on('click', '#add_radio_option', (e) => {
                e.preventDefault();
                this.addOption();
            });

            // Rimuovi opzione
            $(document).on('click', '.wcag-wp-remove-option', (e) => {
                e.preventDefault();
                this.removeOption($(e.target).closest('.wcag-wp-option-row'));
            });

            // Aggiorna opzioni di default quando cambiano le opzioni
            $(document).on('input', '.wcag-wp-option-value, .wcag-wp-option-label', (e) => {
                this.updateDefaultValueOptions();
            });

            // Aggiorna preview quando cambiano le impostazioni
            $(document).on('change input', 'input[name*="wcag_radio_group_config"], select[name*="wcag_radio_group_config"], textarea[name*="wcag_radio_group_config"]', (e) => {
                this.debounceUpdatePreview();
            });

            // Refresh preview
            $(document).on('click', '.wcag-wp-refresh-preview', (e) => {
                e.preventDefault();
                this.refreshPreview();
            });

            // Validazione campi obbligatori
            $(document).on('blur', '.wcag-wp-option-value, .wcag-wp-option-label', (e) => {
                this.validateOptionField($(e.target));
            });

            // Salvataggio automatico preview
            $(document).on('submit', '#post', () => {
                this.savePreviewState();
            });
        }

        /**
         * Aggiunge una nuova opzione
         */
        addOption() {
            const template = $('#radio-option-template').html();
            const newIndex = this.getNextOptionIndex();
            const newOption = template.replace(/\{\{index\}\}/g, newIndex);
            
            $('#radio_group_options').append(newOption);
            
            // Focus sul primo campo della nuova opzione
            const newRow = $('#radio_group_options .wcag-wp-option-row').last();
            newRow.find('.wcag-wp-option-value').focus();
            
            // Aggiorna le opzioni di default
            this.updateDefaultValueOptions();
            
            // Aggiorna preview
            this.debounceUpdatePreview();
            
            // Annuncia agli screen reader
            this.announceToScreenReader('Nuova opzione aggiunta');
        }

        /**
         * Rimuove un'opzione
         */
        removeOption(optionRow) {
            const optionLabel = optionRow.find('.wcag-wp-option-label').val() || 'opzione';
            
            if (confirm(`Sei sicuro di voler rimuovere l'opzione "${optionLabel}"?`)) {
                optionRow.fadeOut(300, () => {
                    optionRow.remove();
                    this.updateDefaultValueOptions();
                    this.debounceUpdatePreview();
                    this.announceToScreenReader(`Opzione "${optionLabel}" rimossa`);
                });
            }
        }

        /**
         * Ottiene il prossimo indice per le opzioni
         */
        getNextOptionIndex() {
            const existingOptions = $('.wcag-wp-option-row');
            if (existingOptions.length === 0) {
                return 0;
            }
            
            const lastIndex = Math.max(...existingOptions.map((i, el) => {
                return parseInt($(el).data('index')) || 0;
            }).get());
            
            return lastIndex + 1;
        }

        /**
         * Aggiorna le opzioni del campo valore di default
         */
        updateDefaultValueOptions() {
            const defaultValueSelect = $('#radio_group_default_value');
            const currentValue = defaultValueSelect.val();
            
            // Salva il valore corrente
            const currentOptions = defaultValueSelect.find('option').not(':first').clone();
            
            // Pulisci le opzioni esistenti (tranne la prima)
            defaultValueSelect.find('option').not(':first').remove();
            
            // Aggiungi le nuove opzioni
            $('.wcag-wp-option-row').each((index, row) => {
                const valueField = $(row).find('.wcag-wp-option-value');
                const labelField = $(row).find('.wcag-wp-option-label');
                
                const value = valueField.val().trim();
                const label = labelField.val().trim();
                
                if (value && label) {
                    const option = $('<option></option>')
                        .val(value)
                        .text(label);
                    
                    defaultValueSelect.append(option);
                }
            });
            
            // Ripristina il valore selezionato se ancora valido
            if (currentValue && defaultValueSelect.find(`option[value="${currentValue}"]`).length > 0) {
                defaultValueSelect.val(currentValue);
            } else {
                defaultValueSelect.val('');
            }
        }

        /**
         * Valida un campo opzione
         */
        validateOptionField(field) {
            const value = field.val().trim();
            const row = field.closest('.wcag-wp-option-row');
            
            if (!value) {
                field.addClass('wcag-wp-field-error');
                row.addClass('wcag-wp-option-row--error');
            } else {
                field.removeClass('wcag-wp-field-error');
                row.removeClass('wcag-wp-option-row--error');
            }
            
            // Verifica duplicati per il campo valore
            if (field.hasClass('wcag-wp-option-value')) {
                this.checkDuplicateValues();
            }
        }

        /**
         * Verifica valori duplicati
         */
        checkDuplicateValues() {
            const values = [];
            let hasDuplicates = false;
            
            $('.wcag-wp-option-value').each((index, field) => {
                const value = $(field).val().trim();
                if (value) {
                    if (values.includes(value)) {
                        hasDuplicates = true;
                        $(field).addClass('wcag-wp-field-error');
                        $(field).closest('.wcag-wp-option-row').addClass('wcag-wp-option-row--error');
                    } else {
                        values.push(value);
                    }
                }
            });
            
            if (hasDuplicates) {
                this.showError('I valori delle opzioni devono essere unici');
            } else {
                this.clearError();
            }
        }

        /**
         * Aggiorna il preview con debounce
         */
        debounceUpdatePreview() {
            clearTimeout(this.previewTimeout);
            this.previewTimeout = setTimeout(() => {
                this.updatePreview();
            }, 500);
        }

        /**
         * Aggiorna il preview
         */
        updatePreview() {
            const config = this.getCurrentConfig();
            
            if (!config.options || config.options.length === 0) {
                $('.wcag-wp-preview-frame').html(
                    '<div class="wcag-wp-preview-placeholder">' +
                    '<p>Nessuna opzione configurata. Aggiungi delle opzioni per vedere l\'anteprima.</p>' +
                    '</div>'
                );
                return;
            }
            
            // Simula il rendering del frontend
            this.renderPreview(config);
        }

        /**
         * Ottiene la configurazione corrente
         */
        getCurrentConfig() {
            const config = {};
            
            // Raccogli i dati dal form
            $('input[name*="wcag_radio_group_config"], select[name*="wcag_radio_group_config"], textarea[name*="wcag_radio_group_config"]').each((index, field) => {
                const $field = $(field);
                const name = $field.attr('name');
                const value = $field.val();
                
                if (name && value !== undefined) {
                    // Estrai il percorso dalla chiave
                    const path = name.replace('wcag_radio_group_config[', '').replace(']', '').split('[');
                    this.setNestedValue(config, path, value);
                }
            });
            
            // Raccogli le opzioni
            config.options = [];
            $('.wcag-wp-option-row').each((index, row) => {
                const $row = $(row);
                const value = $row.find('.wcag-wp-option-value').val().trim();
                const label = $row.find('.wcag-wp-option-label').val().trim();
                const description = $row.find('.wcag-wp-option-description').val().trim();
                
                if (value && label) {
                    config.options.push({
                        value: value,
                        label: label,
                        description: description
                    });
                }
            });
            
            return config;
        }

        /**
         * Imposta un valore annidato in un oggetto
         */
        setNestedValue(obj, path, value) {
            let current = obj;
            
            for (let i = 0; i < path.length - 1; i++) {
                const key = path[i];
                if (!current[key]) {
                    current[key] = {};
                }
                current = current[key];
            }
            
            const lastKey = path[path.length - 1];
            current[lastKey] = value;
        }

        /**
         * Renderizza il preview
         */
        renderPreview(config) {
            // Per ora, aggiorniamo solo le informazioni
            this.updatePreviewInfo(config);
        }

        /**
         * Aggiorna le informazioni del preview
         */
        updatePreviewInfo(config) {
            const infoContainer = $('.wcag-wp-preview-info');
            
            if (infoContainer.length) {
                const optionsCount = config.options ? config.options.length : 0;
                const orientation = config.orientation || 'vertical';
                const size = config.size || 'medium';
                const required = config.required ? 'Sì' : 'No';
                const disabled = config.disabled ? 'Sì' : 'No';
                
                infoContainer.find('li').each((index, li) => {
                    const $li = $(li);
                    const text = $li.text();
                    
                    if (text.includes('Orientamento:')) {
                        $li.html(`<strong>Orientamento:</strong> ${orientation.charAt(0).toUpperCase() + orientation.slice(1)}`);
                    } else if (text.includes('Dimensione:')) {
                        $li.html(`<strong>Dimensione:</strong> ${size.charAt(0).toUpperCase() + size.slice(1)}`);
                    } else if (text.includes('Opzioni:')) {
                        $li.html(`<strong>Opzioni:</strong> ${optionsCount}`);
                    } else if (text.includes('Obbligatorio:')) {
                        $li.html(`<strong>Obbligatorio:</strong> ${required}`);
                    } else if (text.includes('Disabilitato:')) {
                        $li.html(`<strong>Disabilitato:</strong> ${disabled}`);
                    }
                });
            }
        }

        /**
         * Refresh del preview
         */
        refreshPreview() {
            this.updatePreview();
            this.announceToScreenReader('Anteprima aggiornata');
        }

        /**
         * Setup per la copia dello shortcode
         */
        setupCopyShortcode() {
            $(document).on('click', '.wcag-wp-copy-shortcode', (e) => {
                e.preventDefault();
                const shortcode = $(e.target).data('shortcode');
                this.copyToClipboard(shortcode);
            });
        }

        /**
         * Copia testo negli appunti
         */
        copyToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    this.showSuccess('Shortcode copiato negli appunti');
                }).catch(() => {
                    this.fallbackCopyToClipboard(text);
                });
            } else {
                this.fallbackCopyToClipboard(text);
            }
        }

        /**
         * Fallback per la copia negli appunti
         */
        fallbackCopyToClipboard(text) {
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
                this.showSuccess('Shortcode copiato negli appunti');
            } catch (err) {
                this.showError('Impossibile copiare lo shortcode');
            }
            
            document.body.removeChild(textArea);
        }

        /**
         * Salva lo stato del preview
         */
        savePreviewState() {
            const config = this.getCurrentConfig();
            sessionStorage.setItem('wcag_radio_group_preview_config', JSON.stringify(config));
        }

        /**
         * Mostra un messaggio di successo
         */
        showSuccess(message) {
            this.showMessage(message, 'success');
        }

        /**
         * Mostra un messaggio di errore
         */
        showError(message) {
            this.showMessage(message, 'error');
        }

        /**
         * Mostra un messaggio
         */
        showMessage(message, type) {
            const messageClass = type === 'success' ? 'wcag-wp-message--success' : 'wcag-wp-message--error';
            const messageHtml = `
                <div class="wcag-wp-message ${messageClass}">
                    <span class="wcag-wp-message-text">${message}</span>
                    <button type="button" class="wcag-wp-message-close" aria-label="Chiudi messaggio">×</button>
                </div>
            `;
            
            // Rimuovi messaggi esistenti
            $('.wcag-wp-message').remove();
            
            // Aggiungi il nuovo messaggio
            $('.wcag-wp-meta-box').first().prepend(messageHtml);
            
            // Auto-rimozione dopo 5 secondi
            setTimeout(() => {
                $('.wcag-wp-message').fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
            
            // Event listener per chiudere
            $(document).on('click', '.wcag-wp-message-close', function() {
                $(this).closest('.wcag-wp-message').fadeOut(300, function() {
                    $(this).remove();
                });
            });
        }

        /**
         * Pulisce i messaggi di errore
         */
        clearError() {
            $('.wcag-wp-message--error').fadeOut(300, function() {
                $(this).remove();
            });
        }

        /**
         * Annuncia messaggi agli screen reader
         */
        announceToScreenReader(message) {
            // Crea un elemento temporaneo per l'annuncio
            const announcement = $('<div></div>')
                .attr({
                    'aria-live': 'polite',
                    'aria-atomic': 'true'
                })
                .addClass('sr-only')
                .text(message);
            
            $('body').append(announcement);
            
            // Rimuovi dopo l'annuncio
            setTimeout(() => {
                announcement.remove();
            }, 1000);
        }
    }

    /**
     * Inizializza quando il DOM è pronto
     */
    $(document).ready(() => {
        new WCAGRadioGroupAdmin();
    });

})(jQuery);
