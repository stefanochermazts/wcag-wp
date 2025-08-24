<?php
/**
 * Template Meta Box Informazioni Accessibilità WCAG Breadcrumb
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-meta-box">
    
    <!-- WCAG Compliance -->
    <div class="wcag-wp-field-group">
        <h4><?php _e('WCAG 2.1 AA Compliance', 'wcag-wp'); ?></h4>
        
        <div class="wcag-wp-compliance-list">
            <div class="wcag-wp-compliance-item">
                <span class="wcag-wp-compliance-icon wcag-wp-compliance-success">✓</span>
                <div class="wcag-wp-compliance-content">
                    <strong><?php _e('Criterio 2.4.8 - Location', 'wcag-wp'); ?></strong>
                    <p><?php _e('Breadcrumb fornisce informazioni sulla posizione dell\'utente nella struttura del sito.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-wp-compliance-item">
                <span class="wcag-wp-compliance-icon wcag-wp-compliance-success">✓</span>
                <div class="wcag-wp-compliance-content">
                    <strong><?php _e('Criterio 2.1.1 - Keyboard', 'wcag-wp'); ?></strong>
                    <p><?php _e('Tutti i link sono raggiungibili e attivabili tramite tastiera.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-wp-compliance-item">
                <span class="wcag-wp-compliance-icon wcag-wp-compliance-success">✓</span>
                <div class="wcag-wp-compliance-content">
                    <strong><?php _e('Criterio 2.4.7 - Focus Visible', 'wcag-wp'); ?></strong>
                    <p><?php _e('Focus outline visibile su tutti gli elementi interattivi.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-wp-compliance-item">
                <span class="wcag-wp-compliance-icon wcag-wp-compliance-success">✓</span>
                <div class="wcag-wp-compliance-content">
                    <strong><?php _e('Criterio 1.4.3 - Contrast', 'wcag-wp'); ?></strong>
                    <p><?php _e('Contrasto colore ≥ 4.5:1 per testo normale, ≥ 3:1 per testo grande.', 'wcag-wp'); ?></p>
                </div>
            </div>
            
            <div class="wcag-wp-compliance-item">
                <span class="wcag-wp-compliance-icon wcag-wp-compliance-success">✓</span>
                <div class="wcag-wp-compliance-content">
                    <strong><?php _e('Criterio 4.1.2 - Name, Role, Value', 'wcag-wp'); ?></strong>
                    <p><?php _e('Attributi ARIA corretti per screen reader e tecnologie assistive.', 'wcag-wp'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- ARIA Implementation -->
    <div class="wcag-wp-field-group">
        <h4><?php _e('Implementazione ARIA', 'wcag-wp'); ?></h4>
        
        <div class="wcag-wp-aria-info">
            <div class="wcag-wp-aria-item">
                <code>role="navigation"</code>
                <span><?php _e('Identifica il breadcrumb come elemento di navigazione', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-wp-aria-item">
                <code>aria-label</code>
                <span><?php _e('Descrizione del breadcrumb per screen reader', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-wp-aria-item">
                <code>aria-current="page"</code>
                <span><?php _e('Indica la pagina corrente nell\'ultimo elemento', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-wp-aria-item">
                <code>aria-hidden="true"</code>
                <span><?php _e('Nasconde i separatori ai screen reader', 'wcag-wp'); ?></span>
            </div>
        </div>
    </div>

    <!-- Keyboard Navigation -->
    <div class="wcag-wp-field-group">
        <h4><?php _e('Navigazione Tastiera', 'wcag-wp'); ?></h4>
        
        <div class="wcag-wp-keyboard-info">
            <div class="wcag-wp-keyboard-item">
                <kbd>Tab</kbd>
                <span><?php _e('Navigazione sequenziale tra i link', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-wp-keyboard-item">
                <kbd>Enter</kbd>
                <span><?php _e('Attivazione link selezionato', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-wp-keyboard-item">
                <kbd>Space</kbd>
                <span><?php _e('Attivazione link selezionato (alternativa)', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-wp-keyboard-item">
                <kbd>Home</kbd>
                <span><?php _e('Primo link del breadcrumb', 'wcag-wp'); ?></span>
            </div>
            
            <div class="wcag-wp-keyboard-item">
                <kbd>End</kbd>
                <span><?php _e('Ultimo link del breadcrumb', 'wcag-wp'); ?></span>
            </div>
        </div>
    </div>

    <!-- Screen Reader Support -->
    <div class="wcag-wp-field-group">
        <h4><?php _e('Supporto Screen Reader', 'wcag-wp'); ?></h4>
        
        <div class="wcag-wp-screenreader-info">
            <p><?php _e('Il breadcrumb è completamente accessibile ai screen reader:', 'wcag-wp'); ?></p>
            
            <ul class="wcag-wp-screenreader-list">
                <li><?php _e('Struttura semantica con elementi <code>&lt;nav&gt;</code> e <code>&lt;ol&gt;</code>', 'wcag-wp'); ?></li>
                <li><?php _e('Etichette ARIA descrittive per ogni elemento', 'wcag-wp'); ?></li>
                <li><?php _e('Indicazione della pagina corrente con <code>aria-current</code>', 'wcag-wp'); ?></li>
                <li><?php _e('Separatori nascosti ai screen reader per evitare confusione', 'wcag-wp'); ?></li>
                <li><?php _e('Ordine di lettura logico da sinistra a destra', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>

    <!-- Testing Checklist -->
    <div class="wcag-wp-field-group">
        <h4><?php _e('Checklist Testing Accessibilità', 'wcag-wp'); ?></h4>
        
        <div class="wcag-wp-testing-checklist">
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" checked disabled />
                <?php _e('Navigazione completa con Tab key', 'wcag-wp'); ?>
            </label>
            
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" checked disabled />
                <?php _e('Focus outline visibile su tutti i link', 'wcag-wp'); ?>
            </label>
            
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" checked disabled />
                <?php _e('Screen reader annuncia struttura breadcrumb', 'wcag-wp'); ?>
            </label>
            
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" checked disabled />
                <?php _e('Contrasto colore conforme WCAG AA', 'wcag-wp'); ?>
            </label>
            
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" checked disabled />
                <?php _e('Separatori nascosti ai screen reader', 'wcag-wp'); ?>
            </label>
            
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" checked disabled />
                <?php _e('Pagina corrente identificata correttamente', 'wcag-wp'); ?>
            </label>
        </div>
    </div>

    <!-- Best Practices -->
    <div class="wcag-wp-field-group">
        <h4><?php _e('Best Practices', 'wcag-wp'); ?></h4>
        
        <div class="wcag-wp-best-practices">
            <div class="wcag-wp-practice-item">
                <strong><?php _e('Posizionamento', 'wcag-wp'); ?></strong>
                <p><?php _e('Posiziona il breadcrumb in alto nella pagina, prima del contenuto principale.', 'wcag-wp'); ?></p>
            </div>
            
            <div class="wcag-wp-practice-item">
                <strong><?php _e('Separatori', 'wcag-wp'); ?></strong>
                <p><?php _e('Usa separatori visivamente distinti ma semanticamente nascosti ai screen reader.', 'wcag-wp'); ?></p>
            </div>
            
            <div class="wcag-wp-practice-item">
                <strong><?php _e('Profondità', 'wcag-wp'); ?></strong>
                <p><?php _e('Limita la profondità a 5-7 livelli per evitare breadcrumb troppo lunghi.', 'wcag-wp'); ?></p>
            </div>
            
            <div class="wcag-wp-practice-item">
                <strong><?php _e('Responsive', 'wcag-wp'); ?></strong>
                <p><?php _e('Assicurati che il breadcrumb sia leggibile su dispositivi mobili.', 'wcag-wp'); ?></p>
            </div>
        </div>
    </div>

</div>

<style>
.wcag-wp-compliance-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.wcag-wp-compliance-item {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.wcag-wp-compliance-icon {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
    flex-shrink: 0;
}

.wcag-wp-compliance-success {
    background-color: var(--wcag-green-500);
    color: white;
}

.wcag-wp-compliance-content strong {
    display: block;
    font-size: 13px;
    margin-bottom: 4px;
}

.wcag-wp-compliance-content p {
    margin: 0;
    font-size: 12px;
    color: var(--wcag-gray-600);
}

.wcag-wp-aria-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.wcag-wp-aria-item {
    display: flex;
    gap: 8px;
    align-items: center;
    font-size: 12px;
}

.wcag-wp-aria-item code {
    background-color: var(--wcag-gray-100);
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 600;
    min-width: 120px;
}

.wcag-wp-keyboard-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.wcag-wp-keyboard-item {
    display: flex;
    gap: 8px;
    align-items: center;
    font-size: 12px;
}

.wcag-wp-keyboard-item kbd {
    background-color: var(--wcag-gray-100);
    border: 1px solid var(--wcag-gray-300);
    border-radius: 3px;
    padding: 2px 6px;
    font-size: 11px;
    font-weight: 600;
    min-width: 40px;
    text-align: center;
}

.wcag-wp-screenreader-list {
    margin: 8px 0 0 0;
    padding-left: 20px;
    font-size: 12px;
}

.wcag-wp-screenreader-list li {
    margin-bottom: 4px;
}

.wcag-wp-screenreader-list code {
    background-color: var(--wcag-gray-100);
    padding: 1px 4px;
    border-radius: 2px;
    font-size: 10px;
}

.wcag-wp-testing-checklist {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.wcag-wp-testing-checklist .wcag-wp-checkbox-label {
    font-size: 12px;
    color: var(--wcag-gray-600);
}

.wcag-wp-best-practices {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.wcag-wp-practice-item strong {
    display: block;
    font-size: 13px;
    margin-bottom: 4px;
    color: var(--wcag-gray-900);
}

.wcag-wp-practice-item p {
    margin: 0;
    font-size: 12px;
    color: var(--wcag-gray-600);
}
</style>
