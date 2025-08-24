<?php
/**
 * Menu Button Accessibility Meta Box Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-admin-meta-box wcag-wp-accessibility-info">
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('🎯 Conformità WCAG 2.1 AA', 'wcag-wp'); ?></h4>
        <p><?php _e('Questo componente Menu Button è completamente conforme agli standard WCAG 2.1 AA e implementa il pattern Menu Button delle WAI-ARIA Authoring Practices Guide (APG).', 'wcag-wp'); ?></p>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('⌨️ Navigazione Tastiera', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-accessibility-list">
            <li><strong>Tab:</strong> <?php _e('Raggiunge il pulsante del menu', 'wcag-wp'); ?></li>
            <li><strong>Enter/Spazio:</strong> <?php _e('Apre/chiude il menu', 'wcag-wp'); ?></li>
            <li><strong>Frecce (↑↓):</strong> <?php _e('Naviga tra gli elementi del menu aperto', 'wcag-wp'); ?></li>
            <li><strong>Escape:</strong> <?php _e('Chiude il menu e torna al pulsante', 'wcag-wp'); ?></li>
            <li><strong>Home:</strong> <?php _e('Va al primo elemento del menu', 'wcag-wp'); ?></li>
            <li><strong>End:</strong> <?php _e('Va all\'ultimo elemento del menu', 'wcag-wp'); ?></li>
            <li><strong>Caratteri:</strong> <?php _e('Ricerca rapida per lettera iniziale', 'wcag-wp'); ?></li>
        </ul>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('🔊 Screen Reader Support', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-accessibility-list">
            <li><strong>role="button":</strong> <?php _e('Il pulsante è identificato come controllo interattivo', 'wcag-wp'); ?></li>
            <li><strong>aria-haspopup="menu":</strong> <?php _e('Indica che il pulsante apre un menu', 'wcag-wp'); ?></li>
            <li><strong>aria-expanded:</strong> <?php _e('Comunica se il menu è aperto o chiuso', 'wcag-wp'); ?></li>
            <li><strong>aria-controls:</strong> <?php _e('Collega il pulsante al menu che controlla', 'wcag-wp'); ?></li>
            <li><strong>role="menu":</strong> <?php _e('Il menu è identificato come lista di azioni', 'wcag-wp'); ?></li>
            <li><strong>role="menuitem":</strong> <?php _e('Ogni elemento è identificato come voce di menu', 'wcag-wp'); ?></li>
            <li><strong>aria-label:</strong> <?php _e('Fornisce etichette descrittive per pulsante e menu', 'wcag-wp'); ?></li>
        </ul>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('🎨 Design Accessibile', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-accessibility-list">
            <li><strong><?php _e('Contrasto Colori:', 'wcag-wp'); ?></strong> <?php _e('Rapporto ≥ 4.5:1 per testo normale, ≥ 3:1 per testo grande', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Focus Visibile:', 'wcag-wp'); ?></strong> <?php _e('Outline chiaramente visibile su pulsante e elementi menu', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Touch Targets:', 'wcag-wp'); ?></strong> <?php _e('Dimensione minima 44x44px per dispositivi touch', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Posizionamento:', 'wcag-wp'); ?></strong> <?php _e('Menu posizionato per evitare sovrapposizioni problematiche', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Reduced Motion:', 'wcag-wp'); ?></strong> <?php _e('Rispetta le preferenze utente per animazioni ridotte', 'wcag-wp'); ?></li>
        </ul>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('🎯 Focus Management', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-accessibility-list">
            <li><strong><?php _e('Apertura Menu:', 'wcag-wp'); ?></strong> <?php _e('Il focus si sposta al primo elemento del menu', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Navigazione:', 'wcag-wp'); ?></strong> <?php _e('Il focus rimane all\'interno del menu aperto', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Chiusura Menu:', 'wcag-wp'); ?></strong> <?php _e('Il focus torna al pulsante che ha aperto il menu', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Selezione:', 'wcag-wp'); ?></strong> <?php _e('Dopo la selezione, il focus gestito appropriatamente', 'wcag-wp'); ?></li>
        </ul>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('📱 Supporto Dispositivi', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-accessibility-list">
            <li><strong><?php _e('Desktop:', 'wcag-wp'); ?></strong> <?php _e('Interazione completa con mouse e tastiera', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Mobile/Tablet:', 'wcag-wp'); ?></strong> <?php _e('Touch ottimizzato con target di dimensioni adeguate', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Screen Reader:', 'wcag-wp'); ?></strong> <?php _e('Compatibile con NVDA, JAWS, VoiceOver', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Voice Control:', 'wcag-wp'); ?></strong> <?php _e('Supporto per controllo vocale e switch navigation', 'wcag-wp'); ?></li>
        </ul>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('✅ Best Practices', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-accessibility-list">
            <li><?php _e('Usa etichette chiare e descrittive per il pulsante', 'wcag-wp'); ?></li>
            <li><?php _e('Fornisci un\'etichetta ARIA se il testo del pulsante non è autoesplicativo', 'wcag-wp'); ?></li>
            <li><?php _e('Mantieni il menu vicino al pulsante che lo attiva', 'wcag-wp'); ?></li>
            <li><?php _e('Evita menu con troppi elementi (max 7-10 consigliati)', 'wcag-wp'); ?></li>
            <li><?php _e('Usa separatori per raggruppare elementi correlati', 'wcag-wp'); ?></li>
            <li><?php _e('Preferisci trigger "click" invece di "hover" per accessibilità', 'wcag-wp'); ?></li>
            <li><?php _e('Testa con tastiera e screen reader prima della pubblicazione', 'wcag-wp'); ?></li>
        </ul>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('🔧 Testing Accessibilità', 'wcag-wp'); ?></h4>
        <div class="wcag-wp-testing-tools">
            <p><strong><?php _e('Checklist di Test:', 'wcag-wp'); ?></strong></p>
            <ul class="wcag-wp-accessibility-list">
                <li><?php _e('Il pulsante è raggiungibile con Tab', 'wcag-wp'); ?></li>
                <li><?php _e('Enter/Spazio aprono il menu', 'wcag-wp'); ?></li>
                <li><?php _e('Le frecce navigano nel menu', 'wcag-wp'); ?></li>
                <li><?php _e('Escape chiude il menu', 'wcag-wp'); ?></li>
                <li><?php _e('Il focus è visibile in ogni momento', 'wcag-wp'); ?></li>
                <li><?php _e('Screen reader annuncia correttamente stati e azioni', 'wcag-wp'); ?></li>
                <li><?php _e('Touch funziona su dispositivi mobili', 'wcag-wp'); ?></li>
                <li><?php _e('Contrasti colori rispettano WCAG AA', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('⚡ Differenze con Menu Standard', 'wcag-wp'); ?></h4>
        <table class="wcag-wp-comparison-table">
            <thead>
                <tr>
                    <th><?php _e('Aspetto', 'wcag-wp'); ?></th>
                    <th><?php _e('Menu Button', 'wcag-wp'); ?></th>
                    <th><?php _e('Menu/Menubar', 'wcag-wp'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong><?php _e('Scopo', 'wcag-wp'); ?></strong></td>
                    <td><?php _e('Azioni contestuali', 'wcag-wp'); ?></td>
                    <td><?php _e('Navigazione sito', 'wcag-wp'); ?></td>
                </tr>
                <tr>
                    <td><strong><?php _e('Visibilità', 'wcag-wp'); ?></strong></td>
                    <td><?php _e('Menu nascosto inizialmente', 'wcag-wp'); ?></td>
                    <td><?php _e('Menu sempre visibile', 'wcag-wp'); ?></td>
                </tr>
                <tr>
                    <td><strong><?php _e('Attivazione', 'wcag-wp'); ?></strong></td>
                    <td><?php _e('Click su pulsante', 'wcag-wp'); ?></td>
                    <td><?php _e('Hover o focus', 'wcag-wp'); ?></td>
                </tr>
                <tr>
                    <td><strong><?php _e('Comportamento', 'wcag-wp'); ?></strong></td>
                    <td><?php _e('Chiude dopo selezione', 'wcag-wp'); ?></td>
                    <td><?php _e('Rimane aperto', 'wcag-wp'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="wcag-wp-accessibility-section wcag-wp-accessibility-warning">
        <h4><?php _e('⚠️ Note Importanti', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-accessibility-list">
            <li><?php _e('Non usare hover come unico trigger - problematico per touch e accessibilità', 'wcag-wp'); ?></li>
            <li><?php _e('Assicurati che il menu non copra contenuti importanti', 'wcag-wp'); ?></li>
            <li><?php _e('Testa con utenti reali che utilizzano tecnologie assistive', 'wcag-wp'); ?></li>
            <li><?php _e('Verifica che tutti i link nel menu siano funzionanti', 'wcag-wp'); ?></li>
            <li><?php _e('Mantieni coerenza con altri pattern di interazione del sito', 'wcag-wp'); ?></li>
        </ul>
    </div>
    
</div>
