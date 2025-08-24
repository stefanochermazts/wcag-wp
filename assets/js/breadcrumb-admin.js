/**
 * WCAG Breadcrumb Component - Admin JavaScript
 * 
 * Gestione interfaccia amministrazione per componente breadcrumb
 * Funzionalità: preview live, gestione elementi custom, AJAX
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Namespace per il componente breadcrumb
    window.WCAGBreadcrumbAdmin = {
        
        // Configurazione
        config: {
            ajaxUrl: wcag_wp_ajax.ajax_url,
            nonce: wcag_wp_ajax.nonce,
            postId: 0
        },

        // Elementi DOM
        elements: {},

        // Inizializzazione
        init: function() {
            this.bindEvents();
            this.initCustomItems();
            this.initPreview();
        },

        // Binding eventi
        bindEvents: function() {
            // Toggle tipo sorgente
            const sourceTypeSelect = document.getElementById('source_type');
            if (sourceTypeSelect) {
                sourceTypeSelect.addEventListener('change', this.handleSourceTypeChange.bind(this));
            }

            // Aggiungi elemento custom
            const addItemBtn = document.getElementById('add-custom-item');
            if (addItemBtn) {
                addItemBtn.addEventListener('click', this.addCustomItem.bind(this));
            }

            // Rimuovi elemento custom
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    e.preventDefault();
                    WCAGBreadcrumbAdmin.removeCustomItem(e.target);
                }
            });

            // Genera anteprima
            const generatePreviewBtn = document.getElementById('generate-preview');
            if (generatePreviewBtn) {
                generatePreviewBtn.addEventListener('click', this.generatePreview.bind(this));
            }

            // Copia shortcode
            const copyShortcodeBtn = document.querySelector('.copy-shortcode');
            if (copyShortcodeBtn) {
                copyShortcodeBtn.addEventListener('click', this.copyShortcode.bind(this));
            }

            // Test breadcrumb
            const testBreadcrumbBtn = document.querySelector('.test-breadcrumb');
            if (testBreadcrumbBtn) {
                testBreadcrumbBtn.addEventListener('click', this.testBreadcrumb.bind(this));
            }

            // Duplica breadcrumb
            const duplicateBreadcrumbBtn = document.querySelector('.duplicate-breadcrumb');
            if (duplicateBreadcrumbBtn) {
                duplicateBreadcrumbBtn.addEventListener('click', this.duplicateBreadcrumb.bind(this));
            }

            // Esporta configurazione
            const exportConfigBtn = document.querySelector('.export-config');
            if (exportConfigBtn) {
                exportConfigBtn.addEventListener('click', this.exportConfig.bind(this));
            }

            // Auto-preview su cambio configurazione
            this.bindAutoPreview();
        },

        // Gestione cambio tipo sorgente
        handleSourceTypeChange: function(e) {
            const sourceType = e.target.value;
            const customSection = document.getElementById('custom-items-section');
            
            if (sourceType === 'custom') {
                customSection.style.display = 'block';
            } else {
                customSection.style.display = 'none';
            }

            // Trigger auto-preview
            this.triggerAutoPreview();
        },

        // Inizializzazione elementi custom
        initCustomItems: function() {
            const container = document.getElementById('custom-items-container');
            if (!container) return;

            // Aggiorna indici degli elementi esistenti
            this.updateCustomItemIndices();
        },

        // Aggiungi elemento custom
        addCustomItem: function() {
            const container = document.getElementById('custom-items-container');
            const template = document.getElementById('custom-item-template');
            
            if (!container || !template) return;

            const newIndex = container.children.length;
            const newItem = template.innerHTML.replace(/\{\{index\}\}/g, newIndex);
            
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = newItem;
            
            container.appendChild(tempDiv.firstElementChild);
            
            // Focus sul primo campo
            const firstInput = container.lastElementChild.querySelector('input[type="text"]');
            if (firstInput) {
                firstInput.focus();
            }

            // Trigger auto-preview
            this.triggerAutoPreview();
        },

        // Rimuovi elemento custom
        removeCustomItem: function(button) {
            const item = button.closest('.wcag-wp-custom-item');
            if (item) {
                item.remove();
                this.updateCustomItemIndices();
                this.triggerAutoPreview();
            }
        },

        // Aggiorna indici elementi custom
        updateCustomItemIndices: function() {
            const container = document.getElementById('custom-items-container');
            if (!container) return;

            const items = container.querySelectorAll('.wcag-wp-custom-item');
            items.forEach((item, index) => {
                item.dataset.index = index;
                
                // Aggiorna nomi dei campi
                const inputs = item.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const name = input.name;
                    if (name.includes('[0]')) {
                        input.name = name.replace(/\[\d+\]/, `[${index}]`);
                    }
                });
            });
        },

        // Binding auto-preview
        bindAutoPreview: function() {
            const inputs = document.querySelectorAll('#wcag-wp-breadcrumb-config input, #wcag-wp-breadcrumb-config select');
            inputs.forEach(input => {
                input.addEventListener('change', this.triggerAutoPreview.bind(this));
                input.addEventListener('input', this.debounce(this.triggerAutoPreview.bind(this), 500));
            });
        },

        // Trigger auto-preview
        triggerAutoPreview: function() {
            // Debounce per evitare troppe chiamate AJAX
            clearTimeout(this.autoPreviewTimeout);
            this.autoPreviewTimeout = setTimeout(() => {
                this.generatePreview();
            }, 1000);
        },

        // Genera anteprima
        generatePreview: function() {
            const previewContainer = document.getElementById('breadcrumb-preview');
            if (!previewContainer) return;

            // Mostra loading
            previewContainer.innerHTML = '<div class="wcag-wp-loading">Generazione anteprima...</div>';

            // Raccogli dati configurazione
            const formData = this.collectFormData();
            
            // Chiamata AJAX
            fetch(this.config.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'wcag_breadcrumb_preview',
                    nonce: this.config.nonce,
                    ...formData
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    previewContainer.innerHTML = data.data.html;
                    this.highlightPreview();
                } else {
                    previewContainer.innerHTML = '<div class="wcag-wp-error">Errore nella generazione anteprima</div>';
                }
            })
            .catch(error => {
                console.error('Errore AJAX:', error);
                previewContainer.innerHTML = '<div class="wcag-wp-error">Errore di connessione</div>';
            });
        },

        // Raccogli dati form
        collectFormData: function() {
            const form = document.querySelector('#post');
            if (!form) return {};

            const formData = new FormData(form);
            const data = {};

            // Filtra solo i campi breadcrumb
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('source_type') || 
                    key.startsWith('home_text') || 
                    key.startsWith('separator') || 
                    key.startsWith('max_depth') || 
                    key.startsWith('show_current') || 
                    key.startsWith('show_home') || 
                    key.startsWith('custom_items') || 
                    key.startsWith('css_class') || 
                    key.startsWith('aria_label')) {
                    data[key] = value;
                }
            }

            return data;
        },

        // Evidenzia anteprima
        highlightPreview: function() {
            const preview = document.getElementById('breadcrumb-preview');
            if (!preview) return;

            preview.style.animation = 'wcag-wp-highlight 0.5s ease-in-out';
            setTimeout(() => {
                preview.style.animation = '';
            }, 500);
        },

        // Copia shortcode
        copyShortcode: function() {
            const shortcodeInput = document.querySelector('.wcag-wp-shortcode-input');
            if (!shortcodeInput) return;

            shortcodeInput.select();
            shortcodeInput.setSelectionRange(0, 99999); // Per dispositivi mobili

            try {
                document.execCommand('copy');
                this.showNotification('Shortcode copiato negli appunti!', 'success');
            } catch (err) {
                this.showNotification('Errore nella copia dello shortcode', 'error');
            }
        },

        // Test breadcrumb
        testBreadcrumb: function() {
            const preview = document.getElementById('breadcrumb-preview');
            if (!preview) return;

            // Simula interazione utente
            const links = preview.querySelectorAll('.wcag-wp-breadcrumb-link');
            if (links.length > 0) {
                // Focus sul primo link
                links[0].focus();
                
                // Simula hover
                setTimeout(() => {
                    links[0].classList.add('wcag-wp-test-hover');
                    setTimeout(() => {
                        links[0].classList.remove('wcag-wp-test-hover');
                    }, 1000);
                }, 500);
            }
        },

        // Duplica breadcrumb
        duplicateBreadcrumb: function() {
            if (!confirm('Sei sicuro di voler duplicare questo breadcrumb?')) {
                return;
            }

            const formData = this.collectFormData();
            formData.action = 'wcag_breadcrumb_duplicate';
            formData.nonce = this.config.nonce;

            fetch(this.config.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showNotification('Breadcrumb duplicato con successo!', 'success');
                    // Redirect alla nuova pagina
                    if (data.data.redirect_url) {
                        window.location.href = data.data.redirect_url;
                    }
                } else {
                    this.showNotification('Errore nella duplicazione', 'error');
                }
            })
            .catch(error => {
                console.error('Errore AJAX:', error);
                this.showNotification('Errore di connessione', 'error');
            });
        },

        // Esporta configurazione
        exportConfig: function() {
            const config = this.collectFormData();
            const configJson = JSON.stringify(config, null, 2);
            
            // Crea blob e download
            const blob = new Blob([configJson], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.href = url;
            a.download = 'wcag-breadcrumb-config.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            this.showNotification('Configurazione esportata!', 'success');
        },

        // Mostra notifica
        showNotification: function(message, type) {
            const notification = document.createElement('div');
            notification.className = `wcag-wp-notification wcag-wp-notification--${type}`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Animazione entrata
            setTimeout(() => {
                notification.classList.add('wcag-wp-notification--show');
            }, 100);
            
            // Auto-rimozione
            setTimeout(() => {
                notification.classList.remove('wcag-wp-notification--show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        },

        // Utility: debounce
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    };

    // Inizializzazione quando DOM è pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            WCAGBreadcrumbAdmin.init();
        });
    } else {
        WCAGBreadcrumbAdmin.init();
    }

})();

// CSS per animazioni e notifiche
const style = document.createElement('style');
style.textContent = `
    @keyframes wcag-wp-highlight {
        0% { background-color: var(--wcag-primary-light); }
        100% { background-color: transparent; }
    }

    .wcag-wp-loading {
        text-align: center;
        padding: 20px;
        color: var(--wcag-gray-600);
        font-style: italic;
    }

    .wcag-wp-error {
        text-align: center;
        padding: 20px;
        color: var(--wcag-red-500);
        background-color: var(--wcag-red-50);
        border: 1px solid var(--wcag-red-200);
        border-radius: 4px;
    }

    .wcag-wp-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 16px;
        border-radius: 4px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    }

    .wcag-wp-notification--show {
        transform: translateX(0);
    }

    .wcag-wp-notification--success {
        background-color: var(--wcag-green-500);
    }

    .wcag-wp-notification--error {
        background-color: var(--wcag-red-500);
    }

    .wcag-wp-test-hover {
        background-color: var(--wcag-primary-light) !important;
        transform: translateY(-1px) !important;
    }
`;
document.head.appendChild(style);
