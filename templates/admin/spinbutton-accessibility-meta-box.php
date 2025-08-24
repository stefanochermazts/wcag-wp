<?php
/**
 * Spinbutton Accessibility Meta Box Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-accessibility-meta-box">
    
    <!-- WCAG Compliance Info -->
    <div class="wcag-wp-section">
        <h4>♿ <?php _e('Conformità WCAG 2.1 AA', 'wcag-wp'); ?></h4>
        <div class="wcag-compliance-info">
            <p><strong><?php _e('Livello di Conformità:', 'wcag-wp'); ?></strong> AA</p>
            <p><strong><?php _e('Pattern ARIA:', 'wcag-wp'); ?></strong> <a href="https://www.w3.org/WAI/ARIA/apg/patterns/spinbutton/" target="_blank">Spinbutton</a></p>
            <p><strong><?php _e('Versione WCAG:', 'wcag-wp'); ?></strong> 2.1</p>
        </div>
    </div>
    
    <!-- Keyboard Navigation -->
    <div class="wcag-wp-section">
        <h4>⌨️ <?php _e('Navigazione Tastiera', 'wcag-wp'); ?></h4>
        <div class="keyboard-navigation">
            <table class="keyboard-table">
                <thead>
                    <tr>
                        <th><?php _e('Tasto', 'wcag-wp'); ?></th>
                        <th><?php _e('Azione', 'wcag-wp'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><kbd>Tab</kbd></td>
                        <td><?php _e('Naviga al spinbutton', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>↑</kbd> / <kbd>↓</kbd></td>
                        <td><?php _e('Incrementa/decrementa valore', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>Page Up</kbd> / <kbd>Page Down</kbd></td>
                        <td><?php _e('Incremento/decremento maggiore', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>Home</kbd> / <kbd>End</kbd></td>
                        <td><?php _e('Vai al valore minimo/massimo', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>0-9</kbd></td>
                        <td><?php _e('Inserisci valore direttamente', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>Enter</kbd></td>
                        <td><?php _e('Conferma valore inserito', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>Escape</kbd></td>
                        <td><?php _e('Annulla modifica', 'wcag-wp'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Screen Reader Support -->
    <div class="wcag-wp-section">
        <h4>🔊 <?php _e('Supporto Screen Reader', 'wcag-wp'); ?></h4>
        <div class="screen-reader-support">
            <ul class="sr-features">
                <li>✅ <strong><?php _e('role="spinbutton"', 'wcag-wp'); ?></strong> - <?php _e('Identifica il controllo come spinbutton', 'wcag-wp'); ?></li>
                <li>✅ <strong><?php _e('aria-valuemin', 'wcag-wp'); ?></strong> - <?php _e('Valore minimo consentito', 'wcag-wp'); ?></li>
                <li>✅ <strong><?php _e('aria-valuemax', 'wcag-wp'); ?></strong> - <?php _e('Valore massimo consentito', 'wcag-wp'); ?></li>
                <li>✅ <strong><?php _e('aria-valuenow', 'wcag-wp'); ?></strong> - <?php _e('Valore corrente', 'wcag-wp'); ?></li>
                <li>✅ <strong><?php _e('aria-valuetext', 'wcag-wp'); ?></strong> - <?php _e('Testo descrittivo del valore', 'wcag-wp'); ?></li>
                <li>✅ <strong><?php _e('aria-label', 'wcag-wp'); ?></strong> - <?php _e('Etichetta per screen reader', 'wcag-wp'); ?></li>
                <li>✅ <strong><?php _e('aria-describedby', 'wcag-wp'); ?></strong> - <?php _e('Descrizione aggiuntiva', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>
    
    <!-- Testing Checklist -->
    <div class="wcag-wp-section">
        <h4>🧪 <?php _e('Checklist Testing', 'wcag-wp'); ?></h4>
        <div class="testing-checklist">
            <div class="checklist-item">
                <input type="checkbox" id="test-keyboard" disabled checked>
                <label for="test-keyboard"><?php _e('Navigazione completa con tastiera', 'wcag-wp'); ?></label>
            </div>
            <div class="checklist-item">
                <input type="checkbox" id="test-screen-reader" disabled checked>
                <label for="test-screen-reader"><?php _e('Annunci corretti a screen reader', 'wcag-wp'); ?></label>
            </div>
            <div class="checklist-item">
                <input type="checkbox" id="test-focus" disabled checked>
                <label for="test-focus"><?php _e('Focus visibile e gestito correttamente', 'wcag-wp'); ?></label>
            </div>
            <div class="checklist-item">
                <input type="checkbox" id="test-contrast" disabled checked>
                <label for="test-contrast"><?php _e('Contrasto colori conforme WCAG AA', 'wcag-wp'); ?></label>
            </div>
            <div class="checklist-item">
                <input type="checkbox" id="test-touch" disabled checked>
                <label for="test-touch"><?php _e('Touch targets ≥ 44px su mobile', 'wcag-wp'); ?></label>
            </div>
            <div class="checklist-item">
                <input type="checkbox" id="test-validation" disabled checked>
                <label for="test-validation"><?php _e('Validazione input con messaggi accessibili', 'wcag-wp'); ?></label>
            </div>
            <div class="checklist-item">
                <input type="checkbox" id="test-reduced-motion" disabled checked>
                <label for="test-reduced-motion"><?php _e('Supporto prefers-reduced-motion', 'wcag-wp'); ?></label>
            </div>
        </div>
    </div>
    
    <!-- Best Practices -->
    <div class="wcag-wp-section">
        <h4>💡 <?php _e('Best Practices', 'wcag-wp'); ?></h4>
        <div class="best-practices">
            <ul class="practices-list">
                <li><strong><?php _e('Etichette chiare:', 'wcag-wp'); ?></strong> <?php _e('Usa etichette descrittive che spiegano il tipo di valore', 'wcag-wp'); ?></li>
                <li><strong><?php _e('Range appropriati:', 'wcag-wp'); ?></strong> <?php _e('Imposta min/max realistici per il contesto', 'wcag-wp'); ?></li>
                <li><strong><?php _e('Incrementi logici:', 'wcag-wp'); ?></strong> <?php _e('Usa step che hanno senso per l\'utente', 'wcag-wp'); ?></li>
                <li><strong><?php _e('Unità di misura:', 'wcag-wp'); ?></strong> <?php _e('Mostra sempre l\'unità per chiarezza', 'wcag-wp'); ?></li>
                <li><strong><?php _e('Validazione:', 'wcag-wp'); ?></strong> <?php _e('Fornisci feedback immediato su valori non validi', 'wcag-wp'); ?></li>
                <li><strong><?php _e('Fallback:', 'wcag-wp'); ?></strong> <?php _e('Assicurati che funzioni anche senza JavaScript', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>
    
    <!-- Testing Tools -->
    <div class="wcag-wp-section">
        <h4>🔧 <?php _e('Strumenti di Testing', 'wcag-wp'); ?></h4>
        <div class="testing-tools">
            <ul class="tools-list">
                <li><strong>NVDA:</strong> <?php _e('Test navigazione e annunci', 'wcag-wp'); ?></li>
                <li><strong>VoiceOver:</strong> <?php _e('Test su macOS/iOS', 'wcag-wp'); ?></li>
                <li><strong>JAWS:</strong> <?php _e('Test compatibilità enterprise', 'wcag-wp'); ?></li>
                <li><strong>axe-core:</strong> <?php _e('Scansione automatica errori', 'wcag-wp'); ?></li>
                <li><strong>WAVE:</strong> <?php _e('Validazione accessibilità web', 'wcag-wp'); ?></li>
                <li><strong>Lighthouse:</strong> <?php _e('Audit accessibilità Chrome', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>
    
</div>

<style>
.wcag-wp-accessibility-meta-box .wcag-wp-section {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.wcag-wp-accessibility-meta-box .wcag-wp-section h4 {
    margin-top: 0;
    margin-bottom: 1rem;
    color: #23282d;
    border-bottom: 1px solid #ddd;
    padding-bottom: 0.5rem;
}

.wcag-compliance-info p {
    margin: 0.5rem 0;
}

.wcag-compliance-info a {
    color: #0073aa;
    text-decoration: none;
}

.wcag-compliance-info a:hover {
    text-decoration: underline;
}

.keyboard-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.keyboard-table th,
.keyboard-table td {
    padding: 0.5rem;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.keyboard-table th {
    background: #f1f1f1;
    font-weight: 600;
}

.keyboard-table kbd {
    background: #f1f1f1;
    border: 1px solid #ccc;
    border-radius: 3px;
    padding: 0.125rem 0.25rem;
    font-family: monospace;
    font-size: 0.875rem;
}

.sr-features {
    margin: 0;
    padding: 0;
    list-style: none;
}

.sr-features li {
    padding: 0.25rem 0;
    border-bottom: 1px solid #eee;
}

.sr-features li:last-child {
    border-bottom: none;
}

.sr-features strong {
    color: #23282d;
    font-family: monospace;
}

.testing-checklist {
    margin: 0;
}

.checklist-item {
    display: flex;
    align-items: center;
    padding: 0.25rem 0;
    border-bottom: 1px solid #eee;
}

.checklist-item:last-child {
    border-bottom: none;
}

.checklist-item input[type="checkbox"] {
    margin-right: 0.5rem;
}

.checklist-item input[type="checkbox"]:checked {
    background-color: #00a32a;
    border-color: #00a32a;
}

.practices-list,
.tools-list {
    margin: 0;
    padding: 0;
    list-style: none;
}

.practices-list li,
.tools-list li {
    padding: 0.25rem 0;
    border-bottom: 1px solid #eee;
}

.practices-list li:last-child,
.tools-list li:last-child {
    border-bottom: none;
}

.practices-list strong,
.tools-list strong {
    color: #23282d;
}
</style>
