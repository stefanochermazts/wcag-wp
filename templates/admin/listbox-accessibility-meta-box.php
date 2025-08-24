<?php
/**
 * Template: Listbox Accessibility Information Meta Box
 * 
 * @package WCAG_WP
 * @var WP_Post $post Current post object
 * @var array $config Listbox configuration
 * @var string $type Current listbox type
 * @var array $type_info Type information
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-listbox-accessibility-info">
    
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
                    <p><?php esc_html_e('Navigazione completa con frecce, selezione multipla con Ctrl/Shift.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-compliance-item wcag-success">
                <span class="wcag-compliance-icon">✓</span>
                <div class="wcag-compliance-content">
                    <strong><?php esc_html_e('2.4.3 Ordine del Focus', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Sequenza di focus logica e prevedibile attraverso le opzioni.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-compliance-item wcag-success">
                <span class="wcag-compliance-icon">✓</span>
                <div class="wcag-compliance-content">
                    <strong><?php esc_html_e('4.1.2 Nome, Ruolo, Valore', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Ruoli ARIA corretti con stato selezione comunicato agli screen reader.', 'wcag-wp'); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ARIA Listbox Pattern -->
    <div class="wcag-aria-section">
        <h4>
            <span class="dashicons dashicons-format-status"></span>
            <?php esc_html_e('Pattern ARIA Listbox', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-aria-details">
            <div class="wcag-aria-item">
                <strong>role:</strong>
                <code>listbox</code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Identifica il contenitore come listbox)', 'wcag-wp'); ?>
                </span>
            </div>
            
            <div class="wcag-aria-item">
                <strong>aria-multiselectable:</strong>
                <code><?php echo $type_info['multiselectable'] ? 'true' : 'false'; ?></code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Indica se supporta selezione multipla)', 'wcag-wp'); ?>
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
                <strong>aria-labelledby:</strong>
                <code>label-id</code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Collega etichetta principale)', 'wcag-wp'); ?>
                </span>
            </div>
            
            <div class="wcag-aria-item">
                <strong>aria-describedby:</strong>
                <code>description-id</code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Collega descrizione e istruzioni)', 'wcag-wp'); ?>
                </span>
            </div>
            
            <div class="wcag-aria-item">
                <strong>role="option":</strong>
                <code>aria-selected</code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Stato selezione per ogni opzione)', 'wcag-wp'); ?>
                </span>
            </div>
            
            <?php if (in_array($type, ['grouped', 'multi_grouped'])): ?>
            <div class="wcag-aria-item">
                <strong>role="group":</strong>
                <code>aria-labelledby</code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Raggruppa opzioni correlate)', 'wcag-wp'); ?>
                </span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Keyboard Navigation -->
    <div class="wcag-keyboard-section">
        <h4>
            <span class="dashicons dashicons-keyboard-hide"></span>
            <?php esc_html_e('Navigazione Tastiera Avanzata', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-keyboard-shortcuts">
            <div class="wcag-shortcut-category">
                <h5><?php esc_html_e('Navigazione Base', 'wcag-wp'); ?></h5>
                
                <div class="wcag-shortcut-item">
                    <kbd>Tab</kbd>
                    <span><?php esc_html_e('Focus sul listbox', 'wcag-wp'); ?></span>
                </div>
                
                <div class="wcag-shortcut-item">
                    <kbd>↓</kbd> / <kbd>↑</kbd>
                    <span><?php esc_html_e('Naviga tra le opzioni', 'wcag-wp'); ?></span>
                </div>
                
                <div class="wcag-shortcut-item">
                    <kbd>Home</kbd> / <kbd>End</kbd>
                    <span><?php esc_html_e('Prima/ultima opzione', 'wcag-wp'); ?></span>
                </div>
                
                <div class="wcag-shortcut-item">
                    <kbd>Page Up</kbd> / <kbd>Page Down</kbd>
                    <span><?php esc_html_e('Naviga per pagine (se applicabile)', 'wcag-wp'); ?></span>
                </div>
            </div>
            
            <div class="wcag-shortcut-category">
                <h5><?php esc_html_e('Selezione', 'wcag-wp'); ?></h5>
                
                <div class="wcag-shortcut-item">
                    <kbd>Space</kbd>
                    <span><?php esc_html_e('Seleziona/deseleziona opzione corrente', 'wcag-wp'); ?></span>
                </div>
                
                <?php if ($type_info['multiselectable']): ?>
                <div class="wcag-shortcut-item">
                    <kbd>Ctrl</kbd> + <kbd>A</kbd>
                    <span><?php esc_html_e('Seleziona tutte le opzioni', 'wcag-wp'); ?></span>
                </div>
                
                <div class="wcag-shortcut-item">
                    <kbd>Ctrl</kbd> + <kbd>Space</kbd>
                    <span><?php esc_html_e('Aggiungi/rimuovi dalla selezione', 'wcag-wp'); ?></span>
                </div>
                
                <div class="wcag-shortcut-item">
                    <kbd>Shift</kbd> + <kbd>↓/↑</kbd>
                    <span><?php esc_html_e('Estendi selezione', 'wcag-wp'); ?></span>
                </div>
                
                <div class="wcag-shortcut-item">
                    <kbd>Shift</kbd> + <kbd>Click</kbd>
                    <span><?php esc_html_e('Selezione range con mouse', 'wcag-wp'); ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if ($config['orientation'] === 'grid'): ?>
            <div class="wcag-shortcut-category">
                <h5><?php esc_html_e('Navigazione 2D (Griglia)', 'wcag-wp'); ?></h5>
                
                <div class="wcag-shortcut-item">
                    <kbd>←</kbd> / <kbd>→</kbd>
                    <span><?php esc_html_e('Naviga orizzontalmente', 'wcag-wp'); ?></span>
                </div>
                
                <div class="wcag-shortcut-item">
                    <kbd>Ctrl</kbd> + <kbd>Home</kbd>
                    <span><?php esc_html_e('Prima cella della griglia', 'wcag-wp'); ?></span>
                </div>
                
                <div class="wcag-shortcut-item">
                    <kbd>Ctrl</kbd> + <kbd>End</kbd>
                    <span><?php esc_html_e('Ultima cella della griglia', 'wcag-wp'); ?></span>
                </div>
            </div>
            <?php endif; ?>
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
                    <p><?php esc_html_e('Stato selezione, numero opzioni selezionate e posizione corrente annunciati automaticamente.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">🏷️</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Etichette Semantiche', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Ogni opzione ha etichetta chiara, descrizione opzionale e appartenenza al gruppo.', 'wcag-wp'); ?></p>
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
                    <p><?php esc_html_e('Cambiamenti di selezione comunicati tramite aria-live per feedback immediato.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <?php if (in_array($type, ['grouped', 'multi_grouped'])): ?>
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">📂</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Gruppi Strutturati', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Intestazioni di gruppo annunciate per orientamento e contesto.', 'wcag-wp'); ?></p>
                </div>
            </div>
            <?php endif; ?>
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
                    <p><?php esc_html_e('Ogni opzione ha dimensioni minime 44x44px per tocco preciso.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">📱</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Responsive Design', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Layout adattivo per schermi piccoli con scroll ottimizzato.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">🔍</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Zoom Support', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Funzionalità mantenuta fino a zoom 200% senza scroll orizzontale.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">⚡</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Gesture Support', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Supporto swipe per navigazione e tap-and-hold per selezione multipla.', 'wcag-wp'); ?></p>
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
                    <input type="checkbox" id="test-tab-focus" disabled>
                    <label for="test-tab-focus">
                        <?php esc_html_e('Tab porta focus sul listbox', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-arrow-navigation" disabled>
                    <label for="test-arrow-navigation">
                        <?php esc_html_e('Frecce navigano tra opzioni', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-space-selection" disabled>
                    <label for="test-space-selection">
                        <?php esc_html_e('Space seleziona/deseleziona', 'wcag-wp'); ?>
                    </label>
                </div>
                <?php if ($type_info['multiselectable']): ?>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-ctrl-selection" disabled>
                    <label for="test-ctrl-selection">
                        <?php esc_html_e('Ctrl+A seleziona tutto', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-shift-selection" disabled>
                    <label for="test-shift-selection">
                        <?php esc_html_e('Shift+frecce estende selezione', 'wcag-wp'); ?>
                    </label>
                </div>
                <?php endif; ?>
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
                    <input type="checkbox" id="test-selection-visible" disabled>
                    <label for="test-selection-visible">
                        <?php esc_html_e('Stato selezione chiaramente visibile', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-zoom" disabled>
                    <label for="test-zoom">
                        <?php esc_html_e('Zoom 200% senza perdite', 'wcag-wp'); ?>
                    </label>
                </div>
            </div>
            
            <?php if ($type_info['multiselectable']): ?>
            <div class="wcag-test-category">
                <h5><?php esc_html_e('Test Selezione Multipla', 'wcag-wp'); ?></h5>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-multi-selection" disabled>
                    <label for="test-multi-selection">
                        <?php esc_html_e('Selezione multipla funzionante', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-selection-count" disabled>
                    <label for="test-selection-count">
                        <?php esc_html_e('Contatore selezioni aggiornato', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-test-item">
                    <input type="checkbox" id="test-range-selection" disabled>
                    <label for="test-range-selection">
                        <?php esc_html_e('Selezione range con Shift', 'wcag-wp'); ?>
                    </label>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <p class="description">
            <?php esc_html_e('Completa questi test per garantire la piena accessibilità del listbox.', 'wcag-wp'); ?>
        </p>
    </div>
    
</div>

<style>
.wcag-listbox-accessibility-info h4 {
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #2271b1;
    font-size: 14px;
}

.wcag-listbox-accessibility-info > div {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
}

.wcag-listbox-accessibility-info > div:last-child {
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
    min-width: 140px;
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
    gap: 15px;
}

.wcag-shortcut-category h5 {
    margin: 0 0 8px 0;
    font-size: 13px;
    color: #2271b1;
    font-weight: 600;
}

.wcag-shortcut-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 8px;
    background: #f9f9f9;
    border-radius: 4px;
    font-size: 12px;
    margin-bottom: 4px;
}

.wcag-shortcut-item kbd {
    background: #1d2327;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 10px;
    font-family: monospace;
    min-width: 30px;
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
    
    .wcag-shortcut-item kbd {
        align-self: flex-start;
    }
}
</style>

