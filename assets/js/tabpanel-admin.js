'use strict';

(function() {
    if (typeof wcag_wp_tabpanel === 'undefined') {
        console.warn('WCAG-WP TabPanel: config not found');
        window.wcag_wp_tabpanel = { strings: { add_tab: 'Aggiungi Tab' } };
    }

    class WcagWpTabpanelAdmin {
        constructor() {
            this.index = 0;
            this.init();
        }

        init() {
            this.bindEvents();
            this.countExisting();
            this.initSortables();
        }

        bindEvents() {
            const addBtn = document.getElementById('add-new-tab');
            if (addBtn) {
                addBtn.addEventListener('click', () => this.addNewTab());
            }

            document.addEventListener('click', (e) => {
                if (e.target.closest('.tab-delete')) {
                    const tab = e.target.closest('.tab-editor');
                    tab.remove();
                    const container = document.getElementById('tabs-container');
                    if (!container.querySelector('.tab-editor')) {
                        container.innerHTML = '<div class="no-tabs-message"><div class="no-tabs-content"><span class="dashicons dashicons-editor-table"></span><h4>Nessun tab definito</h4></div></div>';
                    }
                } else if (e.target.closest('.tab-duplicate')) {
                    const tab = e.target.closest('.tab-editor');
                    const clone = tab.cloneNode(true);
                    const container = document.getElementById('tabs-container');
                    // aggiorna indici e name/id dei campi
                    clone.setAttribute('data-index', String(this.index));
                    const inputs = clone.querySelectorAll('input, textarea, select, label');
                    inputs.forEach(el => {
                        if (el.name) el.name = el.name.replace(/\[\d+\]/, '[' + this.index + ']');
                        if (el.id) el.id = el.id.replace(/_\d+$/, '_' + this.index);
                        if (el.htmlFor) el.htmlFor = el.htmlFor.replace(/_\d+$/, '_' + this.index);
                    });
                    container.insertBefore(clone, tab.nextSibling);
                    this.index++;
                } else if (e.target.closest('.tab-toggle')) {
                    const editor = e.target.closest('.tab-editor');
                    const content = editor.querySelector('.tab-content');
                    const icon = e.target.closest('.tab-toggle').querySelector('.dashicons');
                    content.classList.toggle('collapsed');
                    const expanded = !content.classList.contains('collapsed');
                    icon.className = expanded ? 'dashicons dashicons-arrow-up-alt2' : 'dashicons dashicons-arrow-down-alt2';
                    e.target.closest('.tab-toggle').setAttribute('aria-expanded', String(expanded));
                }
            });

            document.addEventListener('input', (e) => {
                if (e.target.classList.contains('tab-label-input')) {
                    const editor = e.target.closest('.tab-editor');
                    const label = editor.querySelector('.tab-label');
                    label.textContent = e.target.value || 'Nuovo Tab';
                }
            });
        }

        countExisting() {
            const tabs = document.querySelectorAll('.tab-editor');
            this.index = tabs.length;
        }

        initSortables() {
            if (typeof jQuery !== 'undefined' && jQuery.ui && jQuery.ui.sortable) {
                jQuery('#tabs-container').sortable({
                    items: '.tab-editor',
                    handle: '.tab-drag-handle',
                    tolerance: 'pointer',
                    cursor: 'grabbing'
                });
            }
        }

        addNewTab() {
            const template = document.getElementById('tab-template');
            if (!template) return;

            const html = template.innerHTML.replace(/\{\{INDEX\}\}/g, String(this.index));
            const container = document.getElementById('tabs-container');
            const empty = container.querySelector('.no-tabs-message');
            if (empty) empty.remove();

            const wrap = document.createElement('div');
            wrap.innerHTML = html;
            const node = wrap.firstElementChild;
            container.appendChild(node);

            const disabledInputs = node.querySelectorAll('input[disabled], textarea[disabled], select[disabled]');
            disabledInputs.forEach(el => {
                el.disabled = false;
                if (el.hasAttribute('data-required')) {
                    el.setAttribute('required', '');
                    el.removeAttribute('data-required');
                }
            });

            this.index++;
            const first = node.querySelector('.tab-id-input');
            if (first) first.focus();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => { window.wcagWpTabpanelAdmin = new WcagWpTabpanelAdmin(); });
    } else {
        window.wcagWpTabpanelAdmin = new WcagWpTabpanelAdmin();
    }
})();


