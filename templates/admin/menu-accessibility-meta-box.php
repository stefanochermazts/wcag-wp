<?php
/**
 * Menu Accessibility Meta Box Template
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
        <p><?php _e('Questo componente Menu è completamente conforme agli standard WCAG 2.1 AA e implementa il pattern Menu/Menubar delle WAI-ARIA Authoring Practices Guide (APG).', 'wcag-wp'); ?></p>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('⌨️ Navigazione Tastiera', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-accessibility-list">
            <li><strong>Tab:</strong> <?php _e('Entra nel menu e naviga tra i menu di primo livello', 'wcag-wp'); ?></li>
            <li><strong>Frecce (←→↑↓):</strong> <?php _e('Naviga tra gli elementi del menu', 'wcag-wp'); ?></li>
            <li><strong>Enter/Spazio:</strong> <?php _e('Attiva l\'elemento del menu', 'wcag-wp'); ?></li>
            <li><strong>Escape:</strong> <?php _e('Chiude i sottomenu e torna al livello superiore', 'wcag-wp'); ?></li>
            <li><strong>Home:</strong> <?php _e('Va al primo elemento del menu corrente', 'wcag-wp'); ?></li>
            <li><strong>End:</strong> <?php _e('Va all\'ultimo elemento del menu corrente', 'wcag-wp'); ?></li>
        </ul>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('🔊 Screen Reader Support', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-accessibility-list">
            <li><strong>role="menubar"/"menu":</strong> <?php _e('Identifica il tipo di menu per gli screen reader', 'wcag-wp'); ?></li>
            <li><strong>role="menuitem":</strong> <?php _e('Ogni elemento è identificato come voce di menu', 'wcag-wp'); ?></li>
            <li><strong>aria-haspopup:</strong> <?php _e('Indica se un elemento ha un sottomenu', 'wcag-wp'); ?></li>
            <li><strong>aria-expanded:</strong> <?php _e('Comunica lo stato aperto/chiuso dei sottomenu', 'wcag-wp'); ?></li>
            <li><strong>aria-label:</strong> <?php _e('Fornisce etichette descrittive per il menu', 'wcag-wp'); ?></li>
            <li><strong>aria-current:</strong> <?php _e('Indica l\'elemento attualmente selezionato', 'wcag-wp'); ?></li>
        </ul>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('🎨 Design Accessibile', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-accessibility-list">
            <li><strong><?php _e('Contrasto Colori:', 'wcag-wp'); ?></strong> <?php _e('Rapporto ≥ 4.5:1 per testo normale, ≥ 3:1 per testo grande', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Focus Visibile:', 'wcag-wp'); ?></strong> <?php _e('Outline chiaramente visibile su tutti gli elementi focalizzabili', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Touch Targets:', 'wcag-wp'); ?></strong> <?php _e('Dimensione minima 44x44px per dispositivi touch', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Responsive:', 'wcag-wp'); ?></strong> <?php _e('Adattamento automatico a schermi di diverse dimensioni', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Reduced Motion:', 'wcag-wp'); ?></strong> <?php _e('Rispetta le preferenze utente per animazioni ridotte', 'wcag-wp'); ?></li>
        </ul>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('📱 Supporto Dispositivi', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-accessibility-list">
            <li><strong><?php _e('Desktop:', 'wcag-wp'); ?></strong> <?php _e('Navigazione completa con mouse e tastiera', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Mobile/Tablet:', 'wcag-wp'); ?></strong> <?php _e('Interazione touch ottimizzata', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Screen Reader:', 'wcag-wp'); ?></strong> <?php _e('Compatibile con NVDA, JAWS, VoiceOver', 'wcag-wp'); ?></li>
            <li><strong><?php _e('Voice Control:', 'wcag-wp'); ?></strong> <?php _e('Supporto per controllo vocale e switch navigation', 'wcag-wp'); ?></li>
        </ul>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('✅ Best Practices', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-accessibility-list">
            <li><?php _e('Usa etichette descrittive per ogni elemento del menu', 'wcag-wp'); ?></li>
            <li><?php _e('Fornisci un\'etichetta ARIA per identificare il menu', 'wcag-wp'); ?></li>
            <li><?php _e('Mantieni una struttura logica e prevedibile', 'wcag-wp'); ?></li>
            <li><?php _e('Evita menu troppo profondi (max 3 livelli consigliati)', 'wcag-wp'); ?></li>
            <li><?php _e('Testa la navigazione con tastiera e screen reader', 'wcag-wp'); ?></li>
            <li><?php _e('Verifica il contrasto dei colori in tutti i temi', 'wcag-wp'); ?></li>
        </ul>
    </div>
    
    <div class="wcag-wp-accessibility-section">
        <h4><?php _e('🔧 Testing Accessibilità', 'wcag-wp'); ?></h4>
        <div class="wcag-wp-testing-tools">
            <p><strong><?php _e('Strumenti Consigliati:', 'wcag-wp'); ?></strong></p>
            <ul class="wcag-wp-accessibility-list">
                <li><strong>axe DevTools:</strong> <?php _e('Estensione browser per audit automatici', 'wcag-wp'); ?></li>
                <li><strong>WAVE:</strong> <?php _e('Web Accessibility Evaluation Tool', 'wcag-wp'); ?></li>
                <li><strong>Lighthouse:</strong> <?php _e('Audit accessibilità integrato in Chrome', 'wcag-wp'); ?></li>
                <li><strong>Colour Contrast Analyser:</strong> <?php _e('Verifica contrasti colori', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>
    
    <div class="wcag-wp-accessibility-section wcag-wp-accessibility-warning">
        <h4><?php _e('⚠️ Note Importanti', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-accessibility-list">
            <li><?php _e('Testa sempre il menu con utenti reali che utilizzano tecnologie assistive', 'wcag-wp'); ?></li>
            <li><?php _e('Verifica la compatibilità con i principali screen reader', 'wcag-wp'); ?></li>
            <li><?php _e('Assicurati che tutti i link siano raggiungibili e funzionanti', 'wcag-wp'); ?></li>
            <li><?php _e('Mantieni coerenza con altri elementi di navigazione del sito', 'wcag-wp'); ?></li>
        </ul>
    </div>
    
</div>
