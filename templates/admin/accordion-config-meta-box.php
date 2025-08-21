<?php
declare(strict_types=1);

/**
 * WCAG Accordion Configuration Meta Box Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 * 
 * @var WP_Post $post Current post object
 * @var array $config Accordion configuration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-accordion-config">
    <p class="description">
        <?php esc_html_e('Configura il comportamento e le funzionalità del WCAG Accordion accessibile.', 'wcag-wp'); ?>
    </p>
    
    <table class="form-table">
        <tbody>
            <!-- Behavior Configuration -->
            <tr>
                <th scope="row"><?php esc_html_e('Comportamento WCAG Accordion', 'wcag-wp'); ?></th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <?php esc_html_e('Comportamento del WCAG Accordion', 'wcag-wp'); ?>
                        </legend>
                        
                        <label>
                            <input type="checkbox" 
                                   name="wcag_wp_accordion_config[allow_multiple_open]" 
                                   value="1" 
                                   <?php checked($config['allow_multiple_open']); ?>>
                            <?php esc_html_e('Permetti apertura multipla', 'wcag-wp'); ?>
                        </label>
                        <p class="description">
                            <?php esc_html_e('Consente di aprire più sezioni contemporaneamente, altrimenti solo una alla volta.', 'wcag-wp'); ?>
                        </p>
                        
                        <label>
                            <input type="checkbox" 
                                   name="wcag_wp_accordion_config[first_panel_open]" 
                                   value="1" 
                                   <?php checked($config['first_panel_open']); ?>>
                            <?php esc_html_e('Prima sezione aperta all\'inizio', 'wcag-wp'); ?>
                        </label>
                        <p class="description">
                            <?php esc_html_e('La prima sezione del WCAG Accordion sarà aperta di default al caricamento della pagina.', 'wcag-wp'); ?>
                        </p>
                        
                        <label>
                            <input type="checkbox" 
                                   name="wcag_wp_accordion_config[collapse_all]" 
                                   value="1" 
                                   <?php checked($config['collapse_all']); ?>>
                            <?php esc_html_e('Permetti chiusura completa', 'wcag-wp'); ?>
                        </label>
                        <p class="description">
                            <?php esc_html_e('Consente di chiudere tutte le sezioni, anche quando non è permessa apertura multipla.', 'wcag-wp'); ?>
                        </p>
                    </fieldset>
                </td>
            </tr>
            
            <!-- Accessibility Configuration -->
            <tr>
                <th scope="row"><?php esc_html_e('Accessibilità WCAG', 'wcag-wp'); ?></th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <?php esc_html_e('Impostazioni Accessibilità WCAG', 'wcag-wp'); ?>
                        </legend>
                        
                        <label>
                            <input type="checkbox" 
                                   name="wcag_wp_accordion_config[keyboard_navigation]" 
                                   value="1" 
                                   <?php checked($config['keyboard_navigation']); ?>>
                            <?php esc_html_e('Navigazione da tastiera avanzata', 'wcag-wp'); ?>
                        </label>
                        <p class="description">
                            <?php esc_html_e('Abilita navigazione completa con frecce, Home, End, Tab per pieno supporto WCAG.', 'wcag-wp'); ?>
                        </p>
                        
                        <label>
                            <input type="checkbox" 
                                   name="wcag_wp_accordion_config[animate_transitions]" 
                                   value="1" 
                                   <?php checked($config['animate_transitions']); ?>>
                            <?php esc_html_e('Animazioni rispettose (prefers-reduced-motion)', 'wcag-wp'); ?>
                        </label>
                        <p class="description">
                            <?php esc_html_e('Animazioni fluide che rispettano le preferenze di movimento ridotto dell\'utente.', 'wcag-wp'); ?>
                        </p>
                    </fieldset>
                </td>
            </tr>
            
            <!-- Visual Configuration -->
            <tr>
                <th scope="row"><?php esc_html_e('Aspetto Visuale WCAG', 'wcag-wp'); ?></th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <?php esc_html_e('Configurazione Aspetto del WCAG Accordion', 'wcag-wp'); ?>
                        </legend>
                        
                        <div class="field-row">
                            <div class="field-group">
                                <label for="icon_type"><?php esc_html_e('Tipo Icona:', 'wcag-wp'); ?></label>
                                <select id="icon_type" name="wcag_wp_accordion_config[icon_type]">
                                    <option value="chevron" <?php selected($config['icon_type'], 'chevron'); ?>><?php esc_html_e('Chevron (›)', 'wcag-wp'); ?></option>
                                    <option value="plus_minus" <?php selected($config['icon_type'], 'plus_minus'); ?>><?php esc_html_e('Plus/Minus (+/-)', 'wcag-wp'); ?></option>
                                    <option value="arrow" <?php selected($config['icon_type'], 'arrow'); ?>><?php esc_html_e('Freccia (↓)', 'wcag-wp'); ?></option>
                                    <option value="none" <?php selected($config['icon_type'], 'none'); ?>><?php esc_html_e('Nessuna icona', 'wcag-wp'); ?></option>
                                </select>
                            </div>
                            
                            <div class="field-group">
                                <label for="icon_position"><?php esc_html_e('Posizione Icona:', 'wcag-wp'); ?></label>
                                <select id="icon_position" name="wcag_wp_accordion_config[icon_position]">
                                    <option value="left" <?php selected($config['icon_position'], 'left'); ?>><?php esc_html_e('Sinistra', 'wcag-wp'); ?></option>
                                    <option value="right" <?php selected($config['icon_position'], 'right'); ?>><?php esc_html_e('Destra', 'wcag-wp'); ?></option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="field-group full-width">
                            <label for="custom_css_class"><?php esc_html_e('Classe CSS Personalizzata:', 'wcag-wp'); ?></label>
                            <input type="text" 
                                   id="custom_css_class" 
                                   name="wcag_wp_accordion_config[custom_css_class]" 
                                   value="<?php echo esc_attr($config['custom_css_class']); ?>"
                                   class="regular-text"
                                   placeholder="<?php esc_attr_e('es: my-custom-accordion', 'wcag-wp'); ?>">
                            <p class="description">
                                <?php esc_html_e('Classe CSS aggiuntiva per personalizzazione stilistica del WCAG Accordion.', 'wcag-wp'); ?>
                            </p>
                        </div>
                    </fieldset>
                </td>
            </tr>
        </tbody>
    </table>
    
    <!-- WCAG Compliance Preview Section -->
    <div class="wcag-wp-config-preview">
        <h4><?php esc_html_e('Anteprima Configurazione WCAG Accordion', 'wcag-wp'); ?></h4>
        <div class="preview-content">
            <div class="preview-feature" data-feature="keyboard_navigation">
                <span class="dashicons dashicons-universal-access"></span>
                <span class="feature-label"><?php esc_html_e('Navigazione Tastiera', 'wcag-wp'); ?></span>
                <span class="feature-status"></span>
            </div>
            <div class="preview-feature" data-feature="animate_transitions">
                <span class="dashicons dashicons-controls-play"></span>
                <span class="feature-label"><?php esc_html_e('Animazioni Accessibili', 'wcag-wp'); ?></span>
                <span class="feature-status"></span>
            </div>
            <div class="preview-feature" data-feature="allow_multiple_open">
                <span class="dashicons dashicons-editor-ul"></span>
                <span class="feature-label"><?php esc_html_e('Apertura Multipla', 'wcag-wp'); ?></span>
                <span class="feature-status"></span>
            </div>
            <div class="preview-feature" data-feature="first_panel_open">
                <span class="dashicons dashicons-arrow-down-alt2"></span>
                <span class="feature-label"><?php esc_html_e('Prima Sezione Aperta', 'wcag-wp'); ?></span>
                <span class="feature-status"></span>
            </div>
        </div>
        
        <!-- WCAG Compliance Badge -->
        <div class="wcag-compliance-badge">
            <div class="badge-content">
                <span class="badge-icon">♿</span>
                <div class="badge-text">
                    <span class="badge-title">WCAG 2.1 AA</span>
                    <span class="badge-subtitle">Compliant</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.wcag-wp-accordion-config .form-table th {
    width: 150px;
    vertical-align: top;
    padding-top: 15px;
}

.wcag-wp-accordion-config fieldset {
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 4px;
    background: #f9f9f9;
}

.wcag-wp-accordion-config fieldset legend {
    padding: 0 10px;
    font-weight: 600;
}

.wcag-wp-accordion-config fieldset label {
    display: block;
    margin-bottom: 10px;
    font-weight: 500;
}

.wcag-wp-accordion-config fieldset .description {
    margin: 5px 0 15px 25px;
    font-size: 13px;
    color: #666;
}

.field-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.field-group.full-width {
    grid-column: 1 / -1;
}

.field-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    color: #23282d;
}

.field-group select,
.field-group input[type="text"] {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.wcag-wp-config-preview {
    margin-top: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
    position: relative;
}

.wcag-wp-config-preview h4 {
    margin: 0 0 15px 0;
    color: #2271b1;
}

.preview-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 10px;
    margin-bottom: 20px;
}

.preview-feature {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    border-radius: 4px;
    background: #f6f7f7;
    transition: background-color 0.2s ease;
}

.preview-feature.active {
    background: #e8f5e8;
    border-left: 3px solid #00a32a;
}

.preview-feature .dashicons {
    color: #666;
}

.preview-feature.active .dashicons {
    color: #00a32a;
}

.preview-feature .feature-label {
    font-size: 13px;
    font-weight: 500;
}

.preview-feature .feature-status::after {
    content: "Disabilitato";
    color: #d63638;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.preview-feature.active .feature-status::after {
    content: "Abilitato";
    color: #00a32a;
}

.wcag-compliance-badge {
    position: absolute;
    top: 15px;
    right: 15px;
}

.badge-content {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: #00a32a;
    color: white;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.badge-icon {
    font-size: 16px;
}

.badge-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.badge-title {
    font-size: 11px;
    text-transform: uppercase;
}

.badge-subtitle {
    font-size: 10px;
    opacity: 0.9;
}

@media (max-width: 782px) {
    .preview-content {
        grid-template-columns: 1fr;
    }
    
    .field-row {
        grid-template-columns: 1fr;
    }
    
    .wcag-compliance-badge {
        position: static;
        text-align: center;
        margin-top: 15px;
    }
}
</style>

<script>
(function() {
    'use strict';
    
    // Update WCAG preview when checkboxes change
    function updateWCAGAccordionPreview() {
        const features = ['keyboard_navigation', 'animate_transitions', 'allow_multiple_open', 'first_panel_open'];
        
        features.forEach(feature => {
            const checkbox = document.querySelector(`input[name="wcag_wp_accordion_config[${feature}]"]`);
            const previewElement = document.querySelector(`.preview-feature[data-feature="${feature}"]`);
            
            if (checkbox && previewElement) {
                if (checkbox.checked) {
                    previewElement.classList.add('active');
                } else {
                    previewElement.classList.remove('active');
                }
            }
        });
    }
    
    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initial WCAG preview update
        updateWCAGAccordionPreview();
        
        // Bind events
        const checkboxes = document.querySelectorAll('.wcag-wp-accordion-config input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateWCAGAccordionPreview);
        });
    });
})();
</script>
