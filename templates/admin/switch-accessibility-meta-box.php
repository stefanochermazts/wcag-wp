<div class="wcag-wp-accessibility-meta-box">
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
                        <td><?php _e('Naviga al switch', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>Space</kbd></td>
                        <td><?php _e('Attiva/disattiva switch', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>Enter</kbd></td>
                        <td><?php _e('Attiva/disattiva switch', 'wcag-wp'); ?></td>
                    </tr>
                    <tr>
                        <td><kbd>←</kbd> / <kbd>→</kbd></td>
                        <td><?php _e('Attiva/disattiva switch', 'wcag-wp'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="wcag-wp-section">
        <h4>🔊 <?php _e('Supporto Screen Reader', 'wcag-wp'); ?></h4>
        <div class="screen-reader-support">
            <ul>
                <li><strong>role="switch"</strong> - <?php _e('Identifica il controllo come switch', 'wcag-wp'); ?></li>
                <li><strong>aria-checked</strong> - <?php _e('Indica lo stato attuale (true/false)', 'wcag-wp'); ?></li>
                <li><strong>aria-label</strong> - <?php _e('Etichetta descrittiva per screen reader', 'wcag-wp'); ?></li>
                <li><strong>aria-describedby</strong> - <?php _e('Riferimento a descrizione aggiuntiva', 'wcag-wp'); ?></li>
                <li><strong>Annunci automatici</strong> - <?php _e('Cambiamenti di stato comunicati', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>
    
    <div class="wcag-wp-section">
        <h4>✅ <?php _e('Checklist Testing', 'wcag-wp'); ?></h4>
        <div class="testing-checklist">
            <ul>
                <li>☐ <?php _e('Navigazione tastiera completa', 'wcag-wp'); ?></li>
                <li>☐ <?php _e('Focus visibile e ben definito', 'wcag-wp'); ?></li>
                <li>☐ <?php _e('Screen reader annuncia stato', 'wcag-wp'); ?></li>
                <li>☐ <?php _e('Contrasto colore sufficiente', 'wcag-wp'); ?></li>
                <li>☐ <?php _e('Touch target ≥ 44px', 'wcag-wp'); ?></li>
                <li>☐ <?php _e('Stato attivo/inattivo distinguibile', 'wcag-wp'); ?></li>
                <li>☐ <?php _e('Etichetta associata correttamente', 'wcag-wp'); ?></li>
                <li>☐ <?php _e('Descrizione accessibile', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>
    
    <div class="wcag-wp-section">
        <h4>🎯 <?php _e('Best Practice WCAG', 'wcag-wp'); ?></h4>
        <div class="best-practices">
            <ul>
                <li><?php _e('Usa sempre etichette descrittive', 'wcag-wp'); ?></li>
                <li><?php _e('Fornisci feedback visivo immediato', 'wcag-wp'); ?></li>
                <li><?php _e('Mantieni contrasto ≥ 4.5:1', 'wcag-wp'); ?></li>
                <li><?php _e('Evita dipendenza solo dal colore', 'wcag-wp'); ?></li>
                <li><?php _e('Testa con screen reader', 'wcag-wp'); ?></li>
                <li><?php _e('Verifica navigazione tastiera', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>
    
    <div class="wcag-wp-section">
        <h4>🛠️ <?php _e('Strumenti di Testing', 'wcag-wp'); ?></h4>
        <div class="testing-tools">
            <ul>
                <li><strong>axe-core</strong> - <?php _e('Scansione automatica errori', 'wcag-wp'); ?></li>
                <li><strong>NVDA</strong> - <?php _e('Screen reader Windows', 'wcag-wp'); ?></li>
                <li><strong>VoiceOver</strong> - <?php _e('Screen reader macOS', 'wcag-wp'); ?></li>
                <li><strong>Chrome DevTools</strong> - <?php _e('Audit accessibilità', 'wcag-wp'); ?></li>
                <li><strong>WAVE</strong> - <?php _e('Validazione web accessibility', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>
    
    <div class="wcag-wp-section">
        <h4>📋 <?php _e('Criteri WCAG 2.1 AA', 'wcag-wp'); ?></h4>
        <div class="wcag-criteria">
            <ul>
                <li><strong>1.4.3 Contrasto (Minimo)</strong> - <?php _e('Contrasto ≥ 4.5:1', 'wcag-wp'); ?></li>
                <li><strong>2.1.1 Tastiera</strong> - <?php _e('Navigazione completa via tastiera', 'wcag-wp'); ?></li>
                <li><strong>2.4.7 Focus Visibile</strong> - <?php _e('Indicatore focus visibile', 'wcag-wp'); ?></li>
                <li><strong>4.1.2 Nome, Ruolo, Valore</strong> - <?php _e('ARIA attributes corretti', 'wcag-wp'); ?></li>
                <li><strong>4.1.3 Messaggi di Stato</strong> - <?php _e('Cambiamenti annunciati', 'wcag-wp'); ?></li>
            </ul>
        </div>
    </div>
</div>
