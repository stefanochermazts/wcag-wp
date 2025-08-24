<?php
/**
 * Template: Listbox Preview & Shortcode Meta Box
 * 
 * @package WCAG_WP
 * @var WP_Post $post Current post object
 * @var string $shortcode Generated shortcode
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-listbox-preview-meta-box">
    
    <!-- Shortcode Section -->
    <div class="wcag-shortcode-section">
        <h4>
            <span class="dashicons dashicons-shortcode"></span>
            <?php esc_html_e('Shortcode', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-shortcode-container">
            <input type="text" 
                   id="wcag-listbox-shortcode" 
                   value="<?php echo esc_attr($shortcode); ?>" 
                   readonly 
                   class="widefat code">
            <button type="button" 
                    id="copy-shortcode-btn" 
                    class="button button-secondary"
                    title="<?php esc_attr_e('Copia shortcode negli appunti', 'wcag-wp'); ?>">
                <span class="dashicons dashicons-clipboard"></span>
                <?php esc_html_e('Copia', 'wcag-wp'); ?>
            </button>
        </div>
        
        <p class="description">
            <?php esc_html_e('Usa questo shortcode per inserire il listbox in pagine, post o widget.', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Usage Examples -->
    <div class="wcag-usage-examples">
        <h4>
            <span class="dashicons dashicons-info"></span>
            <?php esc_html_e('Esempi di Utilizzo', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-examples-list">
            <div class="wcag-example">
                <strong><?php esc_html_e('Base:', 'wcag-wp'); ?></strong>
                <code>[wcag-listbox id="<?php echo esc_attr($post->ID); ?>"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Con nome campo:', 'wcag-wp'); ?></strong>
                <code>[wcag-listbox id="<?php echo esc_attr($post->ID); ?>" name="category_selection"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Campo obbligatorio:', 'wcag-wp'); ?></strong>
                <code>[wcag-listbox id="<?php echo esc_attr($post->ID); ?>" required="true"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Dimensione personalizzata:', 'wcag-wp'); ?></strong>
                <code>[wcag-listbox id="<?php echo esc_attr($post->ID); ?>" size="8"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Classe personalizzata:', 'wcag-wp'); ?></strong>
                <code>[wcag-listbox id="<?php echo esc_attr($post->ID); ?>" class="my-listbox"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Con valori preselezionati:', 'wcag-wp'); ?></strong>
                <code>[wcag-listbox id="<?php echo esc_attr($post->ID); ?>" value="option1,option2"]</code>
            </div>
        </div>
    </div>
    
    <!-- API Usage -->
    <div class="wcag-api-usage">
        <h4>
            <span class="dashicons dashicons-rest-api"></span>
            <?php esc_html_e('API REST', 'wcag-wp'); ?>
        </h4>
        
        <div class="api-endpoint">
            <strong><?php esc_html_e('Endpoint opzioni:', 'wcag-wp'); ?></strong>
            <code>GET /wp-json/wcag-wp/v1/listbox/<?php echo esc_attr($post->ID); ?>/options</code>
        </div>
        
        <p class="description">
            <?php esc_html_e('Endpoint pubblico per recuperare le opzioni del listbox via AJAX.', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- JavaScript Integration -->
    <div class="wcag-js-integration">
        <h4>
            <span class="dashicons dashicons-editor-code"></span>
            <?php esc_html_e('Integrazione JavaScript', 'wcag-wp'); ?>
        </h4>
        
        <div class="js-example">
            <strong><?php esc_html_e('Event Listener:', 'wcag-wp'); ?></strong>
            <pre><code>document.addEventListener('wcag-listbox-change', function(e) {
    console.log('Selected values:', e.detail.selectedValues);
    console.log('Listbox ID:', e.detail.listboxId);
});</code></pre>
        </div>
        
        <div class="js-example">
            <strong><?php esc_html_e('Controllo Programmatico:', 'wcag-wp'); ?></strong>
            <pre><code>// Ottieni istanza listbox
const listbox = window.wcagGetListbox('<?php echo esc_js($post->ID); ?>');

// Seleziona opzioni
listbox.selectValues(['option1', 'option2']);

// Deseleziona tutto
listbox.clearSelection();</code></pre>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="wcag-listbox-stats">
        <h4>
            <span class="dashicons dashicons-chart-bar"></span>
            <?php esc_html_e('Statistiche WCAG', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-stats-grid">
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('Compliance:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value wcag-stat-success">WCAG 2.1 AA</span>
            </div>
            
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('Pattern ARIA:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value wcag-stat-success">Listbox APG</span>
            </div>
            
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('Keyboard:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value wcag-stat-success">✓ Avanzata</span>
            </div>
            
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('Screen Reader:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value wcag-stat-success">✓ Completo</span>
            </div>
            
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('Touch:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value wcag-stat-success">44px+ Target</span>
            </div>
            
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('Multi-Select:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value wcag-stat-success">Ctrl+Shift</span>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="wcag-quick-actions">
        <h4>
            <span class="dashicons dashicons-admin-tools"></span>
            <?php esc_html_e('Azioni Rapide', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-actions-grid">
            <button type="button" 
                    id="test-listbox-btn" 
                    class="button button-secondary">
                <span class="dashicons dashicons-visibility"></span>
                <?php esc_html_e('Test Anteprima', 'wcag-wp'); ?>
            </button>
            
            <button type="button" 
                    id="validate-accessibility-btn" 
                    class="button button-secondary">
                <span class="dashicons dashicons-universal-access-alt"></span>
                <?php esc_html_e('Valida WCAG', 'wcag-wp'); ?>
            </button>
            
            <button type="button" 
                    id="test-keyboard-btn" 
                    class="button button-secondary">
                <span class="dashicons dashicons-keyboard-hide"></span>
                <?php esc_html_e('Test Tastiera', 'wcag-wp'); ?>
            </button>
            
            <button type="button" 
                    id="duplicate-listbox-btn" 
                    class="button button-secondary">
                <span class="dashicons dashicons-admin-page"></span>
                <?php esc_html_e('Duplica', 'wcag-wp'); ?>
            </button>
            
            <button type="button" 
                    id="export-config-btn" 
                    class="button button-secondary">
                <span class="dashicons dashicons-download"></span>
                <?php esc_html_e('Esporta Config', 'wcag-wp'); ?>
            </button>
            
            <button type="button" 
                    id="generate-form-btn" 
                    class="button button-secondary">
                <span class="dashicons dashicons-forms"></span>
                <?php esc_html_e('Genera Form', 'wcag-wp'); ?>
            </button>
        </div>
    </div>
    
</div>

<style>
.wcag-listbox-preview-meta-box h4 {
    margin: 0 0 10px 0;
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 600;
    color: #2271b1;
}

.wcag-listbox-preview-meta-box > div {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f0f0f0;
}

.wcag-listbox-preview-meta-box > div:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.wcag-shortcode-container {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-bottom: 8px;
}

.wcag-shortcode-container input {
    flex: 1;
    font-family: monospace;
    font-size: 12px;
    background: #f6f7f7;
}

.wcag-examples-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.wcag-example {
    padding: 8px 10px;
    background: #f6f7f7;
    border-radius: 4px;
    border-left: 3px solid #2271b1;
}

.wcag-example strong {
    display: block;
    margin-bottom: 4px;
    font-size: 12px;
    color: #2271b1;
}

.wcag-example code {
    display: block;
    font-size: 11px;
    color: #646970;
    background: none;
    padding: 0;
}

.api-endpoint {
    padding: 10px;
    background: #f9f9f9;
    border-radius: 4px;
    margin-bottom: 8px;
}

.api-endpoint strong {
    display: block;
    margin-bottom: 5px;
    font-size: 13px;
}

.api-endpoint code {
    font-size: 11px;
    word-break: break-all;
}

.js-example {
    margin-bottom: 15px;
}

.js-example strong {
    display: block;
    margin-bottom: 5px;
    font-size: 12px;
    color: #2271b1;
}

.js-example pre {
    background: #f6f7f7;
    padding: 10px;
    border-radius: 4px;
    font-size: 11px;
    overflow-x: auto;
    margin: 0;
}

.js-example code {
    background: none;
    padding: 0;
    color: #646970;
}

.wcag-stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}

.wcag-stat-item {
    display: flex;
    flex-direction: column;
    padding: 8px;
    background: #f9f9f9;
    border-radius: 4px;
    text-align: center;
    border: 1px solid #e0e0e0;
}

.wcag-stat-label {
    font-size: 10px;
    color: #646970;
    text-transform: uppercase;
    margin-bottom: 4px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.wcag-stat-value {
    font-weight: 600;
    font-size: 11px;
}

.wcag-stat-success {
    color: #00a32a;
}

.wcag-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}

.wcag-actions-grid button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 6px 8px;
    font-size: 11px;
    white-space: nowrap;
}

/* Responsive */
@media (max-width: 782px) {
    .wcag-shortcode-container {
        flex-direction: column;
        align-items: stretch;
    }
    
    .wcag-stats-grid,
    .wcag-actions-grid {
        grid-template-columns: 1fr;
    }
    
    .js-example pre {
        font-size: 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy shortcode functionality
    const copyBtn = document.getElementById('copy-shortcode-btn');
    const shortcodeInput = document.getElementById('wcag-listbox-shortcode');
    
    if (copyBtn && shortcodeInput) {
        copyBtn.addEventListener('click', function() {
            shortcodeInput.select();
            shortcodeInput.setSelectionRange(0, 99999);
            
            try {
                document.execCommand('copy');
                
                const originalText = copyBtn.innerHTML;
                copyBtn.innerHTML = '<span class="dashicons dashicons-yes"></span> Copiato!';
                copyBtn.classList.add('button-primary');
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                    copyBtn.classList.remove('button-primary');
                }, 2000);
                
            } catch (err) {
                console.error('Errore nella copia:', err);
            }
        });
    }
    
    // Quick actions
    const testBtn = document.getElementById('test-listbox-btn');
    if (testBtn) {
        testBtn.addEventListener('click', function() {
            if (typeof window.wcagTestListbox === 'function') {
                window.wcagTestListbox();
            } else {
                alert('Funzione di test non disponibile. Salva il listbox e ricarica la pagina.');
            }
        });
    }
    
    const validateBtn = document.getElementById('validate-accessibility-btn');
    if (validateBtn) {
        validateBtn.addEventListener('click', function() {
            if (typeof window.wcagValidateListbox === 'function') {
                window.wcagValidateListbox();
            } else {
                alert('Validatore accessibilità non disponibile.');
            }
        });
    }
    
    const keyboardBtn = document.getElementById('test-keyboard-btn');
    if (keyboardBtn) {
        keyboardBtn.addEventListener('click', function() {
            alert('Test Navigazione Tastiera:\\n\\n' +
                  '• Tab - Focus sul listbox\\n' +
                  '• ↓/↑ - Naviga opzioni\\n' +
                  '• Space - Seleziona/deseleziona\\n' +
                  '• Ctrl+A - Seleziona tutto\\n' +
                  '• Shift+Click - Selezione range\\n' +
                  '• Home/End - Prima/ultima opzione');
        });
    }
    
    const duplicateBtn = document.getElementById('duplicate-listbox-btn');
    if (duplicateBtn) {
        duplicateBtn.addEventListener('click', function() {
            const postId = document.getElementById('post_ID')?.value;
            if (postId && confirm('Vuoi duplicare questo listbox?')) {
                window.location.href = `post-new.php?post_type=wcag_listbox&duplicate=${postId}`;
            }
        });
    }
    
    const exportBtn = document.getElementById('export-config-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            if (typeof window.wcagExportListbox === 'function') {
                window.wcagExportListbox();
            } else {
                alert('Funzione di export non disponibile.');
            }
        });
    }
    
    const generateFormBtn = document.getElementById('generate-form-btn');
    if (generateFormBtn) {
        generateFormBtn.addEventListener('click', function() {
            const postId = document.getElementById('post_ID')?.value;
            if (postId) {
                const formHtml = `<form method="post" action="">
    <fieldset>
        <legend>Seleziona le tue preferenze:</legend>
        [wcag-listbox id="${postId}" name="preferences" required="true"]
        <button type="submit">Invia</button>
    </fieldset>
</form>`;
                
                prompt('Codice HTML form completo:', formHtml);
            }
        });
    }
});
</script>

