<?php
/**
 * Template: Combobox Preview & Shortcode Meta Box
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

<div class="wcag-combobox-preview-meta-box">
    
    <!-- Shortcode Section -->
    <div class="wcag-shortcode-section">
        <h4>
            <span class="dashicons dashicons-shortcode"></span>
            <?php esc_html_e('Shortcode', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-shortcode-container">
            <input type="text" 
                   id="wcag-combobox-shortcode" 
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
            <?php esc_html_e('Usa questo shortcode per inserire il combobox in pagine, post o widget.', 'wcag-wp'); ?>
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
                <code>[wcag-combobox id="<?php echo esc_attr($post->ID); ?>"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Con nome campo:', 'wcag-wp'); ?></strong>
                <code>[wcag-combobox id="<?php echo esc_attr($post->ID); ?>" name="product_search"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Campo obbligatorio:', 'wcag-wp'); ?></strong>
                <code>[wcag-combobox id="<?php echo esc_attr($post->ID); ?>" required="true"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Selezione multipla:', 'wcag-wp'); ?></strong>
                <code>[wcag-combobox id="<?php echo esc_attr($post->ID); ?>" multiple="true"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Classe personalizzata:', 'wcag-wp'); ?></strong>
                <code>[wcag-combobox id="<?php echo esc_attr($post->ID); ?>" class="my-combobox"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Con valore preselezionato:', 'wcag-wp'); ?></strong>
                <code>[wcag-combobox id="<?php echo esc_attr($post->ID); ?>" value="default-value"]</code>
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
            <strong><?php esc_html_e('Endpoint ricerca:', 'wcag-wp'); ?></strong>
            <code>GET /wp-json/wcag-wp/v1/combobox/<?php echo esc_attr($post->ID); ?>/search?q=termine</code>
        </div>
        
        <p class="description">
            <?php esc_html_e('Endpoint pubblico per ricerche AJAX personalizzate.', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Statistics -->
    <div class="wcag-combobox-stats">
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
                <span class="wcag-stat-value wcag-stat-success">Combobox APG</span>
            </div>
            
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('Keyboard:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value wcag-stat-success">✓ Completa</span>
            </div>
            
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('Screen Reader:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value wcag-stat-success">✓ Supportato</span>
            </div>
            
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('Touch:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value wcag-stat-success">44px+ Target</span>
            </div>
            
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('Performance:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value wcag-stat-success">Lazy Load</span>
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
                    id="test-combobox-btn" 
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
                    id="duplicate-combobox-btn" 
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
        </div>
    </div>
    
</div>

<style>
.wcag-combobox-preview-meta-box h4 {
    margin: 0 0 10px 0;
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 600;
    color: #2271b1;
}

.wcag-combobox-preview-meta-box > div {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f0f0f0;
}

.wcag-combobox-preview-meta-box > div:last-child {
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
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy shortcode functionality
    const copyBtn = document.getElementById('copy-shortcode-btn');
    const shortcodeInput = document.getElementById('wcag-combobox-shortcode');
    
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
    const testBtn = document.getElementById('test-combobox-btn');
    if (testBtn) {
        testBtn.addEventListener('click', function() {
            if (typeof window.wcagTestCombobox === 'function') {
                window.wcagTestCombobox();
            }
        });
    }
    
    const validateBtn = document.getElementById('validate-accessibility-btn');
    if (validateBtn) {
        validateBtn.addEventListener('click', function() {
            if (typeof window.wcagValidateCombobox === 'function') {
                window.wcagValidateCombobox();
            }
        });
    }
    
    const duplicateBtn = document.getElementById('duplicate-combobox-btn');
    if (duplicateBtn) {
        duplicateBtn.addEventListener('click', function() {
            const postId = document.getElementById('post_ID')?.value;
            if (postId && confirm('Vuoi duplicare questo combobox?')) {
                window.location.href = `post-new.php?post_type=wcag_combobox&duplicate=${postId}`;
            }
        });
    }
    
    const exportBtn = document.getElementById('export-config-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            if (typeof window.wcagExportCombobox === 'function') {
                window.wcagExportCombobox();
            }
        });
    }
});
</script>

