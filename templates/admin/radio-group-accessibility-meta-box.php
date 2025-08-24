<?php
/**
 * Meta Box Accessibilità Radio Group
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevenire accesso diretto
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="wcag-wp-meta-box">
    <h4><?php _e( 'Accessibilità WCAG 2.1 AA', 'wcag-wp' ); ?></h4>
    
    <div class="wcag-wp-accessibility-info">
        <div class="wcag-wp-accessibility-section">
            <h5><?php _e( '🎯 Criteri di Successo Implementati', 'wcag-wp' ); ?></h5>
            <ul class="wcag-wp-checklist">
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>2.1.1 Tastiera (Livello A):</strong> 
                    <?php _e( 'Navigazione completa con Tab, Arrow keys, Home, End, Enter, Space', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>2.4.7 Focus Visibile (Livello AA):</strong> 
                    <?php _e( 'Outline focus uniforme e visibile su tutti gli elementi', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>4.1.3 Messaggi di Stato (Livello AA):</strong> 
                    <?php _e( 'Live regions per annunci screen reader e messaggi di errore', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>1.4.3 Contrasto (Livello AA):</strong> 
                    <?php _e( 'Contrasti conformi a WCAG AA (≥ 4.5:1 testo normale, ≥ 3:1 testo grande)', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>2.5.5 Target Size (Livello AAA):</strong> 
                    <?php _e( 'Touch targets ≥ 44px per dispositivi mobili', 'wcag-wp' ); ?>
                </li>
            </ul>
        </div>

        <div class="wcag-wp-accessibility-section">
            <h5><?php _e( '🎮 Navigazione Tastiera', 'wcag-wp' ); ?></h5>
            <div class="wcag-wp-keyboard-nav">
                <table class="wcag-wp-table">
                    <thead>
                        <tr>
                            <th><?php _e( 'Tasto', 'wcag-wp' ); ?></th>
                            <th><?php _e( 'Azione', 'wcag-wp' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><kbd>Tab</kbd></td>
                            <td><?php _e( 'Navigazione sequenziale tra i radio button', 'wcag-wp' ); ?></td>
                        </tr>
                        <tr>
                            <td><kbd>↑</kbd> / <kbd>←</kbd></td>
                            <td><?php _e( 'Radio button precedente', 'wcag-wp' ); ?></td>
                        </tr>
                        <tr>
                            <td><kbd>↓</kbd> / <kbd>→</kbd></td>
                            <td><?php _e( 'Radio button successivo', 'wcag-wp' ); ?></td>
                        </tr>
                        <tr>
                            <td><kbd>Home</kbd></td>
                            <td><?php _e( 'Primo radio button', 'wcag-wp' ); ?></td>
                        </tr>
                        <tr>
                            <td><kbd>End</kbd></td>
                            <td><?php _e( 'Ultimo radio button', 'wcag-wp' ); ?></td>
                        </tr>
                        <tr>
                            <td><kbd>Enter</kbd> / <kbd>Space</kbd></td>
                            <td><?php _e( 'Seleziona radio button focalizzato', 'wcag-wp' ); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="wcag-wp-accessibility-section">
            <h5><?php _e( '📢 Screen Reader Support', 'wcag-wp' ); ?></h5>
            <ul class="wcag-wp-checklist">
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>role="radiogroup":</strong> 
                    <?php _e( 'Identifica il gruppo di radio button', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>aria-labelledby:</strong> 
                    <?php _e( 'Associa il titolo al gruppo', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>aria-describedby:</strong> 
                    <?php _e( 'Associa la descrizione al gruppo', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>aria-required:</strong> 
                    <?php _e( 'Indica se il campo è obbligatorio', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>aria-checked:</strong> 
                    <?php _e( 'Stato di selezione di ogni radio button', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>aria-live:</strong> 
                    <?php _e( 'Annunci automatici per cambiamenti di stato', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>role="alert":</strong> 
                    <?php _e( 'Messaggi di errore annunciati immediatamente', 'wcag-wp' ); ?>
                </li>
            </ul>
        </div>

        <div class="wcag-wp-accessibility-section">
            <h5><?php _e( '🎨 Design System Accessibile', 'wcag-wp' ); ?></h5>
            <ul class="wcag-wp-checklist">
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>Focus Outline:</strong> 
                    <?php _e( 'Outline uniforme 2px solid con colore del design system', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>Touch Targets:</strong> 
                    <?php _e( 'Area cliccabile ≥ 44px per ogni opzione', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>Contrasti:</strong> 
                    <?php _e( 'Tutti i colori rispettano i rapporti WCAG AA', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>Reduced Motion:</strong> 
                    <?php _e( 'Supporto per prefers-reduced-motion', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>High Contrast:</strong> 
                    <?php _e( 'Supporto per prefers-contrast: high', 'wcag-wp' ); ?>
                </li>
            </ul>
        </div>

        <div class="wcag-wp-accessibility-section">
            <h5><?php _e( '🔧 Funzionalità Avanzate', 'wcag-wp' ); ?></h5>
            <ul class="wcag-wp-checklist">
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>Validazione:</strong> 
                    <?php _e( 'Validazione automatica per campi obbligatori', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>Stati Dinamici:</strong> 
                    <?php _e( 'Gestione stati disabled, error, success', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>Eventi Personalizzati:</strong> 
                    <?php _e( 'Eventi JavaScript per integrazione con altri componenti', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <strong>Responsive:</strong> 
                    <?php _e( 'Layout adattivo per dispositivi mobili', 'wcag-wp' ); ?>
                </li>
            </ul>
        </div>

        <div class="wcag-wp-accessibility-section">
            <h5><?php _e( '📋 Best Practices Implementate', 'wcag-wp' ); ?></h5>
            <ul class="wcag-wp-checklist">
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <?php _e( 'Input nascosto per screen reader con label associati', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <?php _e( 'Custom radio button con indicatori visivi chiari', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <?php _e( 'Gestione focus programmatica per messaggi di errore', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <?php _e( 'Prevenzione trappole tastiera con navigazione circolare', 'wcag-wp' ); ?>
                </li>
                <li class="wcag-wp-checklist-item wcag-wp-checklist-item--success">
                    <?php _e( 'Supporto per orientamento verticale e orizzontale', 'wcag-wp' ); ?>
                </li>
            </ul>
        </div>

        <div class="wcag-wp-accessibility-section">
            <h5><?php _e( '🧪 Testing Raccomandato', 'wcag-wp' ); ?></h5>
            <div class="wcag-wp-testing-checklist">
                <h6><?php _e( 'Screen Reader Testing:', 'wcag-wp' ); ?></h6>
                <ul>
                    <li><?php _e( 'NVDA (Windows) - Navigazione e annunci', 'wcag-wp' ); ?></li>
                    <li><?php _e( 'VoiceOver (macOS) - Safari + VoiceOver', 'wcag-wp' ); ?></li>
                    <li><?php _e( 'JAWS (se disponibile) - Compatibilità enterprise', 'wcag-wp' ); ?></li>
                </ul>

                <h6><?php _e( 'Keyboard Testing:', 'wcag-wp' ); ?></h6>
                <ul>
                    <li><?php _e( 'Navigazione Tab completa', 'wcag-wp' ); ?></li>
                    <li><?php _e( 'Arrow keys per selezione', 'wcag-wp' ); ?></li>
                    <li><?php _e( 'Home/End per navigazione rapida', 'wcag-wp' ); ?></li>
                    <li><?php _e( 'Enter/Space per attivazione', 'wcag-wp' ); ?></li>
                </ul>

                <h6><?php _e( 'Automated Testing:', 'wcag-wp' ); ?></h6>
                <ul>
                    <li><?php _e( 'axe-core - Scansione errori WCAG', 'wcag-wp' ); ?></li>
                    <li><?php _e( 'WAVE - Validazione web accessibility', 'wcag-wp' ); ?></li>
                    <li><?php _e( 'Lighthouse - Audit accessibilità Chrome DevTools', 'wcag-wp' ); ?></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="wcag-wp-accessibility-footer">
        <p class="wcag-wp-accessibility-note">
            <strong><?php _e( 'Nota:', 'wcag-wp' ); ?></strong> 
            <?php _e( 'Questo componente è stato progettato e testato per conformità WCAG 2.1 AA. Tutte le funzionalità di accessibilità sono integrate di default e non richiedono configurazione aggiuntiva.', 'wcag-wp' ); ?>
        </p>
    </div>
</div>
