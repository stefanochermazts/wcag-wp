<?php
/**
 * Template per meta box accessibilità Slider Multi-Thumb
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-accessibility-info">
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('🎯 Conformità WCAG 2.1 AA', 'wcag-wp'); ?></h4>
        <p><?php _e('Questo componente Slider Multi-Thumb è completamente conforme agli standard WCAG 2.1 AA per l\'accessibilità web.', 'wcag-wp'); ?></p>
        
        <div class="wcag-wp-compliance-grid">
            <div class="wcag-wp-compliance-item">
                <span class="wcag-wp-compliance-badge success">✓</span>
                <strong><?php _e('1.4.3 Contrasto (Minimo)', 'wcag-wp'); ?></strong>
                <p><?php _e('Rapporto di contrasto ≥ 4.5:1 per tutti gli elementi di testo e ≥ 3:1 per elementi grafici.', 'wcag-wp'); ?></p>
            </div>
            
            <div class="wcag-wp-compliance-item">
                <span class="wcag-wp-compliance-badge success">✓</span>
                <strong><?php _e('2.1.1 Tastiera', 'wcag-wp'); ?></strong>
                <p><?php _e('Navigazione completa tramite tastiera con supporto per frecce direzionali, Home, End, Page Up/Down.', 'wcag-wp'); ?></p>
            </div>
            
            <div class="wcag-wp-compliance-item">
                <span class="wcag-wp-compliance-badge success">✓</span>
                <strong><?php _e('2.4.7 Focus Visibile', 'wcag-wp'); ?></strong>
                <p><?php _e('Indicatore di focus chiaramente visibile su tutti i thumbs con outline di 2px.', 'wcag-wp'); ?></p>
            </div>
            
            <div class="wcag-wp-compliance-item">
                <span class="wcag-wp-compliance-badge success">✓</span>
                <strong><?php _e('4.1.3 Messaggi di Stato', 'wcag-wp'); ?></strong>
                <p><?php _e('Annunci automatici dei cambiamenti di valore tramite ARIA live regions.', 'wcag-wp'); ?></p>
            </div>
        </div>
    </div>

    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('⌨️ Navigazione Tastiera', 'wcag-wp'); ?></h4>
        <div class="wcag-wp-keyboard-shortcuts">
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('Tasto', 'wcag-wp'); ?></th>
                        <th><?php _e('Azione', 'wcag-wp'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><kbd>Tab</kbd></td>
                        <td><?php _e('Naviga tra i thumbs dello slider', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>←</kbd> / <kbd>↓</kbd></td>
                        <td><?php _e('Diminuisce il valore del thumb attivo', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>→</kbd> / <kbd>↑</kbd></td>
                        <td><?php _e('Aumenta il valore del thumb attivo', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>Home</kbd></td>
                        <td><?php _e('Imposta il thumb al valore minimo', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>End</kbd></td>
                        <td><?php _e('Imposta il thumb al valore massimo', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>Page Up</kbd></td>
                        <td><?php _e('Aumenta il valore di 10 step', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>Page Down</kbd></td>
                        <td><?php _e('Diminuisce il valore di 10 step', 'wcag-wp'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('📢 Supporto Screen Reader', 'wcag-wp'); ?></h4>
        <div class="wcag-wp-aria-implementation">
            <h5><?php _e('Attributi ARIA Implementati', 'wcag-wp'); ?></h5>
            <ul class="wcag-wp-aria-list">
                <li><code>role="slider"</code> - <?php _e('Identifica ogni thumb come controllo slider', 'wcag-wp'); ?></li>
                <li><code>aria-valuemin</code> - <?php _e('Valore minimo del range', 'wcag-wp'); ?></li>
                <li><code>aria-valuemax</code> - <?php _e('Valore massimo del range', 'wcag-wp'); ?></li>
                <li><code>aria-valuenow</code> - <?php _e('Valore corrente del thumb', 'wcag-wp'); ?></li>
                <li><code>aria-valuetext</code> - <?php _e('Descrizione testuale del valore (con unità)', 'wcag-wp'); ?></li>
                <li><code>aria-label</code> - <?php _e('Etichetta descrittiva per ogni thumb', 'wcag-wp'); ?></li>
                <li><code>aria-describedby</code> - <?php _e('Riferimento alla descrizione del componente', 'wcag-wp'); ?></li>
                <li><code>aria-live="polite"</code> - <?php _e('Annunci non invasivi dei cambiamenti', 'wcag-wp'); ?></li>
            </ul>
            
            <h5><?php _e('Annunci Screen Reader', 'wcag-wp'); ?></h5>
            <ul>
                <li><?php _e('Valore corrente di ogni thumb durante la navigazione', 'wcag-wp'); ?></li>
                <li><?php _e('Conferma dei cambiamenti di valore', 'wcag-wp'); ?></li>
                <li><?php _e('Avvisi quando si raggiungono i limiti min/max', 'wcag-wp'); ?></li>
                <li><?php _e('Notifiche di sovrapposizione tra thumbs (se abilitata prevenzione)', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>

    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('🎨 Design System Accessibile', 'wcag-wp'); ?></h4>
        <div class="wcag-wp-design-features">
            <ul>
                <li><strong><?php _e('Contrasti Conformi:', 'wcag-wp'); ?></strong> <?php _e('Tutti i colori rispettano i rapporti WCAG AA', 'wcag-wp'); ?></li>
                <li><strong><?php _e('Focus Ring Uniforme:', 'wcag-wp'); ?></strong> <?php _e('Outline di 2px su tutti gli elementi focalizzabili', 'wcag-wp'); ?></li>
                <li><strong><?php _e('Touch Targets:', 'wcag-wp'); ?></strong> <?php _e('Thumbs di almeno 44x44px per dispositivi touch', 'wcag-wp'); ?></li>
                <li><strong><?php _e('Tema Scuro:', 'wcag-wp'); ?></strong> <?php _e('Supporto automatico per tema scuro con contrasti adeguati', 'wcag-wp'); ?></li>
                <li><strong><?php _e('Reduced Motion:', 'wcag-wp'); ?></strong> <?php _e('Rispetta le preferenze utente per animazioni ridotte', 'wcag-wp'); ?></li>
                <li><strong><?php _e('High Contrast:', 'wcag-wp'); ?></strong> <?php _e('Compatibile con modalità alto contrasto del sistema', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>

    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('🧪 Testing Raccomandato', 'wcag-wp'); ?></h4>
        <div class="wcag-wp-testing-checklist">
            <h5><?php _e('Screen Reader Testing', 'wcag-wp'); ?></h5>
            <ul class="wcag-wp-checklist">
                <li><input type="checkbox" disabled> <?php _e('NVDA (Windows) - Navigazione e annunci', 'wcag-wp'); ?></li>
                <li><input type="checkbox" disabled> <?php _e('VoiceOver (macOS) - Compatibilità Safari', 'wcag-wp'); ?></li>
                <li><input type="checkbox" disabled> <?php _e('JAWS (se disponibile) - Test enterprise', 'wcag-wp'); ?></li>
            </ul>
            
            <h5><?php _e('Keyboard Testing', 'wcag-wp'); ?></h5>
            <ul class="wcag-wp-checklist">
                <li><input type="checkbox" disabled> <?php _e('Tab navigation - Ordine logico', 'wcag-wp'); ?></li>
                <li><input type="checkbox" disabled> <?php _e('Arrow keys - Controllo valori', 'wcag-wp'); ?></li>
                <li><input type="checkbox" disabled> <?php _e('Home/End - Valori estremi', 'wcag-wp'); ?></li>
                <li><input type="checkbox" disabled> <?php _e('Page Up/Down - Incrementi grandi', 'wcag-wp'); ?></li>
            </ul>
            
            <h5><?php _e('Automated Testing', 'wcag-wp'); ?></h5>
            <ul class="wcag-wp-checklist">
                <li><input type="checkbox" disabled> <?php _e('axe-core - Scansione automatica WCAG', 'wcag-wp'); ?></li>
                <li><input type="checkbox" disabled> <?php _e('WAVE - Validazione accessibilità web', 'wcag-wp'); ?></li>
                <li><input type="checkbox" disabled> <?php _e('Lighthouse - Audit Chrome DevTools', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>

    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('📚 Risorse Utili', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-resources">
            <li><a href="https://www.w3.org/WAI/ARIA/apg/patterns/slider-multithumb/" target="_blank" rel="noopener">
                <?php _e('WAI-ARIA Authoring Practices - Multi-Thumb Slider', 'wcag-wp'); ?>
            </a></li>
            <li><a href="https://www.w3.org/WAI/WCAG21/quickref/" target="_blank" rel="noopener">
                <?php _e('WCAG 2.1 Quick Reference', 'wcag-wp'); ?>
            </a></li>
            <li><a href="https://webaim.org/articles/screenreader_testing/" target="_blank" rel="noopener">
                <?php _e('Screen Reader Testing Guide', 'wcag-wp'); ?>
            </a></li>
        </ul>
    </div>
</div>

<style>
.wcag-wp-accessibility-info {
    background: #fff;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.wcag-wp-accessibility-section {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.wcag-wp-accessibility-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.wcag-wp-accessibility-section h4 {
    color: #0073aa;
    margin-top: 0;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: 600;
}

.wcag-wp-accessibility-section h5 {
    color: #333;
    margin: 15px 0 10px 0;
    font-size: 14px;
    font-weight: 600;
}

.wcag-wp-compliance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.wcag-wp-compliance-item {
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #f9f9f9;
}

.wcag-wp-compliance-badge {
    display: inline-block;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    text-align: center;
    line-height: 24px;
    font-weight: bold;
    margin-right: 10px;
    font-size: 14px;
}

.wcag-wp-compliance-badge.success {
    background: #00a32a;
    color: white;
}

.wcag-wp-keyboard-shortcuts table {
    margin-top: 10px;
}

.wcag-wp-keyboard-shortcuts kbd {
    background: #f1f1f1;
    border: 1px solid #ccc;
    border-radius: 3px;
    padding: 2px 6px;
    font-family: monospace;
    font-size: 12px;
}

.wcag-wp-aria-list {
    list-style: none;
    padding-left: 0;
}

.wcag-wp-aria-list li {
    margin-bottom: 8px;
    padding-left: 20px;
    position: relative;
}

.wcag-wp-aria-list li:before {
    content: "→";
    position: absolute;
    left: 0;
    color: #0073aa;
    font-weight: bold;
}

.wcag-wp-aria-list code {
    background: #f1f1f1;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: Consolas, Monaco, monospace;
    font-size: 12px;
}

.wcag-wp-design-features ul,
.wcag-wp-resources {
    list-style: none;
    padding-left: 0;
}

.wcag-wp-design-features li,
.wcag-wp-resources li {
    margin-bottom: 8px;
    padding-left: 20px;
    position: relative;
}

.wcag-wp-design-features li:before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #00a32a;
    font-weight: bold;
}

.wcag-wp-resources li:before {
    content: "🔗";
    position: absolute;
    left: 0;
}

.wcag-wp-checklist {
    list-style: none;
    padding-left: 0;
}

.wcag-wp-checklist li {
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.wcag-wp-checklist input[type="checkbox"] {
    margin: 0;
}

.wcag-wp-resources a {
    color: #0073aa;
    text-decoration: none;
}

.wcag-wp-resources a:hover {
    text-decoration: underline;
}
</style>
