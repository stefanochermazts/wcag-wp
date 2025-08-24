<?php
/**
 * Template: Notification Accessibility Information Meta Box
 * 
 * @package WCAG_WP
 * @var WP_Post $post Current post object
 * @var array $config Notification configuration
 * @var string $type Current notification type
 * @var array $type_info Type information
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-accessibility-info">
    
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
                    <p><?php esc_html_e('Tutte le funzionalità accessibili tramite tastiera (Tab, Enter, Esc).', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-compliance-item wcag-success">
                <span class="wcag-compliance-icon">✓</span>
                <div class="wcag-compliance-content">
                    <strong><?php esc_html_e('4.1.3 Messaggi di Stato', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Messaggi comunicati agli screen reader tramite ARIA live regions.', 'wcag-wp'); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ARIA Implementation -->
    <div class="wcag-aria-section" style="margin-top: 20px;">
        <h4>
            <span class="dashicons dashicons-format-status"></span>
            <?php esc_html_e('Implementazione ARIA', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-aria-details">
            <div class="wcag-aria-item">
                <strong>aria-live:</strong>
                <code id="current-aria-live"><?php echo esc_html($type_info['aria_live']); ?></code>
                <span class="wcag-aria-description">
                    <?php 
                    if ($type_info['aria_live'] === 'assertive') {
                        esc_html_e('(Interruzione immediata screen reader)', 'wcag-wp');
                    } else {
                        esc_html_e('(Annuncio educato screen reader)', 'wcag-wp');
                    }
                    ?>
                </span>
            </div>
            
            <div class="wcag-aria-item">
                <strong>role:</strong>
                <code>alert</code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Identifica il contenuto come messaggio importante)', 'wcag-wp'); ?>
                </span>
            </div>
            
            <div class="wcag-aria-item">
                <strong>aria-labelledby:</strong>
                <code>notification-title-{id}</code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Collega il titolo alla notifica)', 'wcag-wp'); ?>
                </span>
            </div>
            
            <div class="wcag-aria-item">
                <strong>aria-describedby:</strong>
                <code>notification-content-{id}</code>
                <span class="wcag-aria-description">
                    <?php esc_html_e('(Collega il contenuto alla notifica)', 'wcag-wp'); ?>
                </span>
            </div>
        </div>
    </div>
    
    <!-- Keyboard Navigation -->
    <div class="wcag-keyboard-section" style="margin-top: 20px;">
        <h4>
            <span class="dashicons dashicons-keyboard-hide"></span>
            <?php esc_html_e('Navigazione Tastiera', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-keyboard-shortcuts">
            <div class="wcag-shortcut-item">
                <kbd>Tab</kbd>
                <span><?php esc_html_e('Focus sul pulsante di chiusura (se presente)', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-shortcut-item">
                <kbd>Enter</kbd> / <kbd>Space</kbd>
                <span><?php esc_html_e('Chiude la notifica quando il pulsante è in focus', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-shortcut-item">
                <kbd>Esc</kbd>
                <span><?php esc_html_e('Chiude la notifica da qualsiasi punto (se chiudibile)', 'wcag-wp'); ?></span>
            </div>
        </div>
    </div>
    
    <!-- Screen Reader Support -->
    <div class="wcag-screenreader-section" style="margin-top: 20px;">
        <h4>
            <span class="dashicons dashicons-megaphone"></span>
            <?php esc_html_e('Supporto Screen Reader', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-screenreader-features">
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">🔊</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Annuncio Automatico', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('La notifica viene annunciata automaticamente quando appare.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">🏷️</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Etichette Semantiche', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Titolo e contenuto collegati tramite ARIA per contesto completo.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-feature-item">
                <span class="wcag-feature-icon">🎯</span>
                <div class="wcag-feature-content">
                    <strong><?php esc_html_e('Priorità Corretta', 'wcag-wp'); ?></strong>
                    <p>
                        <?php 
                        if ($type_info['aria_live'] === 'assertive') {
                            esc_html_e('Interruzione immediata per errori e avvisi critici.', 'wcag-wp');
                        } else {
                            esc_html_e('Annuncio educato per informazioni e successi.', 'wcag-wp');
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Testing Recommendations -->
    <div class="wcag-testing-section" style="margin-top: 20px;">
        <h4>
            <span class="dashicons dashicons-yes-alt"></span>
            <?php esc_html_e('Raccomandazioni Test', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-testing-checklist">
            <div class="wcag-test-item">
                <input type="checkbox" id="test-keyboard" disabled>
                <label for="test-keyboard">
                    <?php esc_html_e('Test navigazione tastiera (Tab, Enter, Esc)', 'wcag-wp'); ?>
                </label>
            </div>
            
            <div class="wcag-test-item">
                <input type="checkbox" id="test-screenreader" disabled>
                <label for="test-screenreader">
                    <?php esc_html_e('Test con screen reader (NVDA, JAWS, VoiceOver)', 'wcag-wp'); ?>
                </label>
            </div>
            
            <div class="wcag-test-item">
                <input type="checkbox" id="test-contrast" disabled>
                <label for="test-contrast">
                    <?php esc_html_e('Verifica contrasto colori (WebAIM, Colour Contrast Analyser)', 'wcag-wp'); ?>
                </label>
            </div>
            
            <div class="wcag-test-item">
                <input type="checkbox" id="test-mobile" disabled>
                <label for="test-mobile">
                    <?php esc_html_e('Test su dispositivi mobili e touch', 'wcag-wp'); ?>
                </label>
            </div>
            
            <div class="wcag-test-item">
                <input type="checkbox" id="test-zoom" disabled>
                <label for="test-zoom">
                    <?php esc_html_e('Test zoom 200% senza perdita funzionalità', 'wcag-wp'); ?>
                </label>
            </div>
        </div>
        
        <p class="description" style="margin-top: 10px;">
            <?php esc_html_e('Questi test sono raccomandati per garantire la piena accessibilità della notifica.', 'wcag-wp'); ?>
        </p>
    </div>
    
</div>

<style>
.wcag-accessibility-info h4 {
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #2271b1;
}

.wcag-compliance-grid {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.wcag-compliance-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px;
    border-radius: 6px;
    border-left: 4px solid;
}

.wcag-compliance-item.wcag-success {
    background: #f0f8f0;
    border-left-color: #00a32a;
}

.wcag-compliance-icon {
    font-size: 16px;
    font-weight: bold;
    color: #00a32a;
    margin-top: 2px;
}

.wcag-compliance-content strong {
    display: block;
    margin-bottom: 4px;
    color: #1d2327;
}

.wcag-compliance-content p {
    margin: 0;
    font-size: 13px;
    color: #646970;
}

.wcag-aria-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.wcag-aria-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    background: #f6f7f7;
    border-radius: 4px;
}

.wcag-aria-item strong {
    min-width: 100px;
    color: #2271b1;
}

.wcag-aria-item code {
    background: #2271b1;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 11px;
}

.wcag-aria-description {
    font-size: 12px;
    color: #646970;
    font-style: italic;
}

.wcag-keyboard-shortcuts {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.wcag-shortcut-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px;
    background: #f9f9f9;
    border-radius: 4px;
}

.wcag-shortcut-item kbd {
    background: #1d2327;
    color: white;
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-family: monospace;
    min-width: 40px;
    text-align: center;
}

.wcag-screenreader-features,
.wcag-testing-checklist {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.wcag-feature-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 6px;
}

.wcag-feature-icon {
    font-size: 20px;
    margin-top: 2px;
}

.wcag-feature-content strong {
    display: block;
    margin-bottom: 4px;
    color: #1d2327;
}

.wcag-feature-content p {
    margin: 0;
    font-size: 13px;
    color: #646970;
}

.wcag-test-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 0;
}

.wcag-test-item input[type="checkbox"] {
    margin: 0;
}

.wcag-test-item label {
    font-size: 13px;
    color: #1d2327;
    cursor: default;
}
</style>

