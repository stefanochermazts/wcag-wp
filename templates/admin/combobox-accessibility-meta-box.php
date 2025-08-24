<?php
/**
 * Template: Combobox Accessibility Information Meta Box
 * 
 * @package WCAG_WP
 * @var WP_Post $post Current post object
 * @var array $config Combobox configuration
 * @var string $type Current combobox type
 * @var array $type_info Type information
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-combobox-accessibility-info">
    
    <!-- WCAG Compliance Overview -->
    <div class="wcag-compliance-section">
        <h4>
            <span class="dashicons dashicons-universal-access-alt"></span>
            <?php esc_html_e('Conformità WCAG 2.1 AA', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-compliance-grid">
            <div class="wcag-compliance-item wcag-success">
                <span class="wcag-compliance-icon">✓</span>
                <div class="wcag-compliance-content">
                    <strong><?php esc_html_e('1.4.3 Contrasto (Minimo)', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Rapporto di contrasto ≥ 4.5:1 per testo normale, ≥ 3:1 per testo grande.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-compliance-item wcag-success">
                <span class="wcag-compliance-icon">✓</span>
                <div class="wcag-compliance-content">
                    <strong><?php esc_html_e('2.1.1 Tastiera', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Tutte le funzionalità accessibili tramite tastiera con navigazione logica.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-compliance-item wcag-success">
                <span class="wcag-compliance-icon">✓</span>
                <div class="wcag-compliance-content">
                    <strong><?php esc_html_e('4.1.2 Nome, Ruolo, Valore', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Elementi identificati correttamente con ruoli e proprietà ARIA.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-compliance-item wcag-success">
                <span class="wcag-compliance-icon">✓</span>
                <div class="wcag-compliance-content">
                    <strong><?php esc_html_e('3.2.2 Input', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Nessun cambiamento di contesto inaspettato durante l\'input.', 'wcag-wp'); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ARIA Combobox Pattern -->
    <div class="wcag-aria-section">
        <h4>
            <span class="dashicons dashicons-format-status"></span>
            <?php esc_html_e('Pattern ARIA Combobox', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-aria-details">
            <div class="wcag-aria-item">
                <strong>role:</strong>
                <code>combobox</code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Identifica l\'elemento come combobox)', 'wcag-wp'); ?>
                </span>
            </div>
            
            <div class="wcag-aria-item">
                <strong>aria-expanded:</strong>
                <code>true/false</code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Stato apertura/chiusura popup)', 'wcag-wp'); ?>
                </span>
            </div>
            
            <div class="wcag-aria-item">
                <strong>aria-autocomplete:</strong>
                <code id="current-autocomplete"><?php echo esc_html($config['autocomplete_behavior'] ?? 'list'); ?></code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Tipo di completamento automatico)', 'wcag-wp'); ?>
                </span>
            </div>
            
            <div class="wcag-aria-item">
                <strong>aria-controls:</strong>
                <code>listbox-id</code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Collega al popup delle opzioni)', 'wcag-wp'); ?>
                </span>
            </div>
            
            <div class="wcag-aria-item">
                <strong>aria-activedescendant:</strong>
                <code>option-id</code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Opzione attualmente focalizzata)', 'wcag-wp'); ?>
                </span>
            </div>
            
            <div class="wcag-aria-item">
                <strong>aria-describedby:</strong>
                <code>help-text-id</code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Collega testo di aiuto/istruzioni)', 'wcag-wp'); ?>
                </span>
            </div>
        </div>
    </div>
    
    <!-- Keyboard Navigation -->
    <div class="wcag-keyboard-section">
        <h4>
            <span class="dashicons dashicons-keyboard-hide"></span>
            <?php esc_html_e('Navigazione Tastiera', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-keyboard-shortcuts">
            <div class="wcag-shortcut-item">
                <kbd>Tab</kbd>
                <span><?php esc_html_e('Focus sul combobox', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-shortcut-item">
                <kbd>Enter</kbd> / <kbd>Space</kbd>
                <span><?php esc_html_e('Apre/chiude il popup delle opzioni', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-shortcut-item">
                <kbd>↓</kbd> / <kbd>↑</kbd>
                <span><?php esc_html_e('Naviga tra le opzioni nel popup', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-shortcut-item">
                <kbd>Home</kbd> / <kbd>End</kbd>
                <span><?php esc_html_e('Prima/ultima opzione nel popup', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-shortcut-item">
                <kbd>Esc</kbd>
                <span><?php esc_html_e('Chiude il popup senza selezione', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-shortcut-item">
                <kbd>Caratteri</kbd>
                <span><?php esc_html_e('Filtrano le opzioni in tempo reale', 'wcag-wp'); ?></span>
            </div>
        </div>
    </div>
    
    <!-- Screen Reader Support -->
    <div class="wcag-screenreader-section">
        <h4>
            <span class="dashicons dashicons-megaphone"></span>
            <?php esc_html_e('Supporto Screen Reader', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-screenreader-features">
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">🔊</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Annunci Automatici', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Numero risultati, opzione selezionata e stato popup annunciati automaticamente.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">🏷️</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Etichette Semantiche', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Label, descrizioni e istruzioni collegate tramite ARIA per contesto completo.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">🎯</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Navigazione Virtuale', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Supporto completo per modalità navigazione e focus degli screen reader.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">📢</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Live Regions', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Aggiornamenti dinamici comunicati tramite aria-live per feedback immediato.', 'wcag-wp'); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile & Touch -->
    <div class="wcag-mobile-section">
        <h4>
            <span class="dashicons dashicons-smartphone"></span>
            <?php esc_html_e('Accessibilità Mobile', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-mobile-features">
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">👆</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Touch Targets', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Dimensioni minime 44x44px per tutti gli elementi interattivi.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">📱</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Responsive Design', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Layout adattivo per schermi piccoli con orientamento portrait/landscape.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">🔍</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Zoom Support', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Funzionalità mantenuta fino a zoom 200% senza scroll orizzontale.', 'wcag-wp'); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Testing Recommendations -->
    <div class="wcag-testing-section">
        <h4>
            <span class="dashicons dashicons-yes-alt"></span>
            <?php esc_html_e('Checklist Test Accessibilità', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-testing-checklist">
            <div class="wcag-test-category">
                <h5><?php esc_html_e('Test Tastiera', 'wcag-wp'); ?></h5>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-tab-navigation" disabled>
                    <label for="test-tab-navigation">
                        <?php esc_html_e('Navigazione Tab in ordine logico', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-arrow-navigation" disabled>
                    <label for="test-arrow-navigation">
                        <?php esc_html_e('Frecce per navigazione opzioni', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-enter-selection" disabled>
                    <label for="test-enter-selection">
                        <?php esc_html_e('Enter/Space per selezione', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-escape-close" disabled>
                    <label for="test-escape-close">
                        <?php esc_html_e('Esc per chiudere popup', 'wcag-wp'); ?>
                    </label>
                </div>
            </div>
            
            <div class="wcag-test-category">
                <h5><?php esc_html_e('Test Screen Reader', 'wcag-wp'); ?></h5>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-nvda" disabled>
                    <label for="test-nvda">
                        <?php esc_html_e('NVDA (Windows)', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-jaws" disabled>
                    <label for="test-jaws">
                        <?php esc_html_e('JAWS (Windows)', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-voiceover" disabled>
                    <label for="test-voiceover">
                        <?php esc_html_e('VoiceOver (macOS/iOS)', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-talkback" disabled>
                    <label for="test-talkback">
                        <?php esc_html_e('TalkBack (Android)', 'wcag-wp'); ?>
                    </label>
                </div>
            </div>
            
            <div class="wcag-test-category">
                <h5><?php esc_html_e('Test Visivi', 'wcag-wp'); ?></h5>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-contrast" disabled>
                    <label for="test-contrast">
                        <?php esc_html_e('Contrasto colori (WebAIM)', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-focus-visible" disabled>
                    <label for="test-focus-visible">
                        <?php esc_html_e('Indicatori focus visibili', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-zoom" disabled>
                    <label for="test-zoom">
                        <?php esc_html_e('Zoom 200% senza perdite', 'wcag-wp'); ?>
                    </label>
                </div>
            </div>
        </div>
        
        <p class="description">
            <?php esc_html_e('Completa questi test per garantire la piena accessibilità del combobox.', 'wcag-wp'); ?>
        </p>
    </div>
    
</div>

<style>
.wcag-combobox-accessibility-info h4 {
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #2271b1;
    font-size: 14px;
}

.wcag-combobox-accessibility-info > div {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
}

.wcag-combobox-accessibility-info > div:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.wcag-compliance-grid {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.wcag-compliance-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px;
    border-radius: 4px;
    border-left: 4px solid;
    background: #f9f9f9;
}

.wcag-compliance-item.wcag-success {
    border-left-color: #00a32a;
    background: #f0f8f0;
}

.wcag-compliance-icon {
    font-size: 14px;
    font-weight: bold;
    color: #00a32a;
    margin-top: 1px;
}

.wcag-compliance-content strong {
    display: block;
    margin-bottom: 4px;
    color: #1d2327;
    font-size: 13px;
}

.wcag-compliance-content p {
    margin: 0;
    font-size: 12px;
    color: #646970;
    line-height: 1.4;
}

.wcag-aria-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.wcag-aria-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 8px;
    background: #f6f7f7;
    border-radius: 4px;
    font-size: 12px;
}

.wcag-aria-item strong {
    min-width: 120px;
    color: #2271b1;
    font-size: 11px;
}

.wcag-aria-item code {
    background: #2271b1;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 10px;
}

.wcag-aria-description {
    font-size: 10px;
    color: #646970;
    font-style: italic;
}

.wcag-keyboard-shortcuts {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.wcag-shortcut-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 8px;
    background: #f9f9f9;
    border-radius: 4px;
    font-size: 12px;
}

.wcag-shortcut-item kbd {
    background: #1d2327;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 10px;
    font-family: monospace;
    min-width: 40px;
    text-align: center;
}

.wcag-screenreader-features,
.wcag-mobile-features {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.wcag-feature-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 4px;
}

.wcag-feature-icon {
    font-size: 16px;
    margin-top: 2px;
}

.wcag-feature-content strong {
    display: block;
    margin-bottom: 4px;
    color: #1d2327;
    font-size: 12px;
}

.wcag-feature-content p {
    margin: 0;
    font-size: 11px;
    color: #646970;
    line-height: 1.4;
}

.wcag-testing-checklist {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.wcag-test-category h5 {
    margin: 0 0 8px 0;
    font-size: 13px;
    color: #2271b1;
    font-weight: 600;
}

.wcag-test-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px 0;
    margin-left: 15px;
}

.wcag-test-item input[type="checkbox"] {
    margin: 0;
}

.wcag-test-item label {
    font-size: 12px;
    color: #1d2327;
    cursor: default;
}

/* Responsive */
@media (max-width: 782px) {
    .wcag-aria-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
    
    .wcag-shortcut-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
}
</style>

