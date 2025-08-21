<?php
declare(strict_types=1);

/**
 * Admin Settings Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Security check
if (!current_user_can('manage_options')) {
    wp_die(__('Non hai i permessi per accedere a questa pagina.', 'wcag-wp'));
}

$plugin_instance = wcag_wp();
$settings = $plugin_instance ? $plugin_instance->get_settings() : [];

// Handle form submission
if (isset($_POST['submit']) && wp_verify_nonce($_POST['wcag_wp_settings_nonce'], 'wcag_wp_settings')) {
    $updated_settings = $plugin_instance->sanitize_settings($_POST['wcag_wp_settings']);
    update_option('wcag_wp_settings', $updated_settings);
    
    echo '<div class="notice notice-success is-dismissible"><p>' . 
         esc_html__('Impostazioni salvate con successo!', 'wcag-wp') . 
         '</p></div>';
    
    // Reload settings
    $settings = $updated_settings;
}
?>

<div class="wrap wcag-wp-admin wcag-wp-settings">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-admin-settings"></span>
        <?php esc_html_e('Impostazioni WCAG-WP', 'wcag-wp'); ?>
    </h1>
    
    <hr class="wp-header-end">
    
    <form method="post" action="" class="wcag-wp-settings-form">
        <?php wp_nonce_field('wcag_wp_settings', 'wcag_wp_settings_nonce'); ?>
        
        <!-- Design System Settings -->
        <div class="settings-section">
            <h2 class="section-title">
                <span class="dashicons dashicons-art"></span>
                <?php esc_html_e('Design System', 'wcag-wp'); ?>
            </h2>
            <p class="section-description">
                <?php esc_html_e('Personalizza l\'aspetto dei componenti accessibili per adattarli al tuo tema.', 'wcag-wp'); ?>
            </p>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="color_scheme"><?php esc_html_e('Schema Colori', 'wcag-wp'); ?></label>
                    </th>
                    <td>
                        <select name="wcag_wp_settings[design_system][color_scheme]" id="color_scheme" class="regular-text">
                            <option value="default" <?php selected($settings['design_system']['color_scheme'] ?? 'default', 'default'); ?>>
                                <?php esc_html_e('Default (Blu Accessibile)', 'wcag-wp'); ?>
                            </option>
                            <option value="green" <?php selected($settings['design_system']['color_scheme'] ?? 'default', 'green'); ?>>
                                <?php esc_html_e('Verde Natura', 'wcag-wp'); ?>
                            </option>
                            <option value="purple" <?php selected($settings['design_system']['color_scheme'] ?? 'default', 'purple'); ?>>
                                <?php esc_html_e('Viola Elegante', 'wcag-wp'); ?>
                            </option>
                            <option value="orange" <?php selected($settings['design_system']['color_scheme'] ?? 'default', 'orange'); ?>>
                                <?php esc_html_e('Arancione Vivace', 'wcag-wp'); ?>
                            </option>
                            <option value="custom" <?php selected($settings['design_system']['color_scheme'] ?? 'default', 'custom'); ?>>
                                <?php esc_html_e('Personalizzato', 'wcag-wp'); ?>
                            </option>
                        </select>
                        <p class="description">
                            <?php esc_html_e('Tutti gli schemi rispettano i criteri di contrasto WCAG AA (4.5:1).', 'wcag-wp'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="font_family"><?php esc_html_e('Famiglia Font', 'wcag-wp'); ?></label>
                    </th>
                    <td>
                        <select name="wcag_wp_settings[design_system][font_family]" id="font_family" class="regular-text">
                            <option value="system-ui" <?php selected($settings['design_system']['font_family'] ?? 'system-ui', 'system-ui'); ?>>
                                <?php esc_html_e('System UI (Consigliato)', 'wcag-wp'); ?>
                            </option>
                            <option value="arial" <?php selected($settings['design_system']['font_family'] ?? 'system-ui', 'arial'); ?>>
                                <?php esc_html_e('Arial', 'wcag-wp'); ?>
                            </option>
                            <option value="helvetica" <?php selected($settings['design_system']['font_family'] ?? 'system-ui', 'helvetica'); ?>>
                                <?php esc_html_e('Helvetica', 'wcag-wp'); ?>
                            </option>
                            <option value="verdana" <?php selected($settings['design_system']['font_family'] ?? 'system-ui', 'verdana'); ?>>
                                <?php esc_html_e('Verdana', 'wcag-wp'); ?>
                            </option>
                            <option value="open-sans" <?php selected($settings['design_system']['font_family'] ?? 'system-ui', 'open-sans'); ?>>
                                <?php esc_html_e('Open Sans (Locale)', 'wcag-wp'); ?>
                            </option>
                        </select>
                        <p class="description">
                            <?php esc_html_e('Font sans-serif ottimizzati per leggibilità e accessibilità. Nessun Google Font per rispettare la privacy.', 'wcag-wp'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="focus_outline"><?php esc_html_e('Outline Focus', 'wcag-wp'); ?></label>
                    </th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="checkbox" 
                                       name="wcag_wp_settings[design_system][focus_outline]" 
                                       value="1" 
                                       <?php checked($settings['design_system']['focus_outline'] ?? true); ?>>
                                <?php esc_html_e('Mostra outline visibile su tutti gli elementi attivabili', 'wcag-wp'); ?>
                            </label>
                            <p class="description">
                                <?php esc_html_e('Essenziale per la navigazione da tastiera. Consigliato sempre attivo.', 'wcag-wp'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="reduce_motion"><?php esc_html_e('Animazioni Ridotte', 'wcag-wp'); ?></label>
                    </th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="checkbox" 
                                       name="wcag_wp_settings[design_system][reduce_motion]" 
                                       value="1" 
                                       <?php checked($settings['design_system']['reduce_motion'] ?? false); ?>>
                                <?php esc_html_e('Rispetta le preferenze utente per il movimento ridotto', 'wcag-wp'); ?>
                            </label>
                            <p class="description">
                                <?php esc_html_e('Disabilita automaticamente le animazioni per utenti che hanno impostato "prefers-reduced-motion".', 'wcag-wp'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Accessibility Settings -->
        <div class="settings-section">
            <h2 class="section-title">
                <span class="dashicons dashicons-universal-access-alt"></span>
                <?php esc_html_e('Accessibilità', 'wcag-wp'); ?>
            </h2>
            <p class="section-description">
                <?php esc_html_e('Configurazioni avanzate per massimizzare l\'accessibilità dei componenti.', 'wcag-wp'); ?>
            </p>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="screen_reader_support"><?php esc_html_e('Supporto Screen Reader', 'wcag-wp'); ?></label>
                    </th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="checkbox" 
                                       name="wcag_wp_settings[accessibility][screen_reader_support]" 
                                       value="1" 
                                       <?php checked($settings['accessibility']['screen_reader_support'] ?? true); ?>>
                                <?php esc_html_e('Attiva annunci e descrizioni per screen reader', 'wcag-wp'); ?>
                            </label>
                            <p class="description">
                                <?php esc_html_e('Include aria-live regions e descrizioni ARIA per elementi dinamici.', 'wcag-wp'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="keyboard_navigation"><?php esc_html_e('Navigazione Tastiera', 'wcag-wp'); ?></label>
                    </th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="checkbox" 
                                       name="wcag_wp_settings[accessibility][keyboard_navigation]" 
                                       value="1" 
                                       <?php checked($settings['accessibility']['keyboard_navigation'] ?? true); ?>>
                                <?php esc_html_e('Abilita navigazione completa da tastiera', 'wcag-wp'); ?>
                            </label>
                            <p class="description">
                                <?php esc_html_e('Supporto per Tab, Shift+Tab, frecce direzionali, Spazio, Enter, Escape.', 'wcag-wp'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="high_contrast"><?php esc_html_e('Modalità Alto Contrasto', 'wcag-wp'); ?></label>
                    </th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="checkbox" 
                                       name="wcag_wp_settings[accessibility][high_contrast]" 
                                       value="1" 
                                       <?php checked($settings['accessibility']['high_contrast'] ?? false); ?>>
                                <?php esc_html_e('Attiva modalità alto contrasto per utenti ipovedenti', 'wcag-wp'); ?>
                            </label>
                            <p class="description">
                                <?php esc_html_e('Migliora la visibilità con contrasti superiori al rapporto 7:1 (AAA).', 'wcag-wp'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Performance Settings -->
        <div class="settings-section">
            <h2 class="section-title">
                <span class="dashicons dashicons-performance"></span>
                <?php esc_html_e('Performance', 'wcag-wp'); ?>
            </h2>
            <p class="section-description">
                <?php esc_html_e('Ottimizzazioni per velocità e leggerezza del plugin.', 'wcag-wp'); ?>
            </p>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Dimensioni Asset', 'wcag-wp'); ?></th>
                    <td>
                        <p class="description">
                            <strong><?php esc_html_e('CSS:', 'wcag-wp'); ?></strong> ~15KB minificato<br>
                            <strong><?php esc_html_e('JavaScript:', 'wcag-wp'); ?></strong> ~8KB minificato<br>
                            <strong><?php esc_html_e('Dipendenze:', 'wcag-wp'); ?></strong> 0 (Vanilla JS)
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php esc_html_e('Caricamento Assets', 'wcag-wp'); ?></th>
                    <td>
                        <p class="description">
                            <?php esc_html_e('Gli asset vengono caricati solo nelle pagine che contengono componenti WCAG-WP.', 'wcag-wp'); ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Advanced Settings -->
        <div class="settings-section">
            <h2 class="section-title">
                <span class="dashicons dashicons-admin-tools"></span>
                <?php esc_html_e('Avanzate', 'wcag-wp'); ?>
            </h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Debug Mode', 'wcag-wp'); ?></th>
                    <td>
                        <p class="description">
                            <?php if (defined('WP_DEBUG') && WP_DEBUG): ?>
                                <span style="color: #d63638;">✓</span> <?php esc_html_e('Debug WordPress attivo', 'wcag-wp'); ?>
                            <?php else: ?>
                                <span style="color: #00a32a;">✓</span> <?php esc_html_e('Modalità produzione', 'wcag-wp'); ?>
                            <?php endif; ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php esc_html_e('Versione Plugin', 'wcag-wp'); ?></th>
                    <td>
                        <code><?php echo esc_html(WCAG_WP_VERSION); ?></code>
                        <p class="description">
                            <a href="https://github.com/stefanochermazts/wcag-wp/releases" target="_blank">
                                <?php esc_html_e('Controlla aggiornamenti su GitHub', 'wcag-wp'); ?>
                            </a>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php submit_button(__('Salva Impostazioni', 'wcag-wp'), 'primary', 'submit', true, ['id' => 'wcag-wp-save-settings']); ?>
    </form>
    
    <!-- Settings Preview -->
    <div class="settings-preview">
        <h2><?php esc_html_e('Anteprima Componenti', 'wcag-wp'); ?></h2>
        <p><?php esc_html_e('Ecco come appariranno i componenti con le impostazioni attuali:', 'wcag-wp'); ?></p>
        
        <div class="preview-components">
            <!-- Button Preview -->
            <div class="preview-item">
                <h4><?php esc_html_e('Pulsanti', 'wcag-wp'); ?></h4>
                <button class="wcag-wp-button primary" tabindex="0">
                    <?php esc_html_e('Pulsante Primario', 'wcag-wp'); ?>
                </button>
                <button class="wcag-wp-button secondary" tabindex="0">
                    <?php esc_html_e('Pulsante Secondario', 'wcag-wp'); ?>
                </button>
            </div>
            
            <!-- Table Preview -->
            <div class="preview-item">
                <h4><?php esc_html_e('Tabella', 'wcag-wp'); ?></h4>
                <table class="wcag-wp-table" role="table" aria-label="Esempio tabella accessibile">
                    <thead>
                        <tr>
                            <th scope="col"><?php esc_html_e('Nome', 'wcag-wp'); ?></th>
                            <th scope="col"><?php esc_html_e('Valore', 'wcag-wp'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php esc_html_e('Contrasto', 'wcag-wp'); ?></td>
                            <td>4.5:1 (AA)</td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e('Focus', 'wcag-wp'); ?></td>
                            <td><?php esc_html_e('Visibile', 'wcag-wp'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Settings CSS -->
<style>
.wcag-wp-settings .settings-section {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    margin: 20px 0;
    padding: 0;
}

.wcag-wp-settings .section-title {
    background: #f6f7f7;
    border-bottom: 1px solid #c3c4c7;
    padding: 15px 20px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.wcag-wp-settings .section-title .dashicons {
    color: #2271b1;
}

.wcag-wp-settings .section-description {
    padding: 15px 20px 0;
    margin: 0;
    color: #666;
    font-style: italic;
}

.wcag-wp-settings .form-table {
    margin: 0;
    padding: 0 20px 20px;
}

.wcag-wp-settings .form-table th {
    width: 200px;
    font-weight: 600;
}

.settings-preview {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    padding: 20px;
    margin: 20px 0;
}

.preview-components {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.preview-item {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
}

.preview-item h4 {
    margin: 0 0 15px 0;
    color: #2271b1;
}

/* Component Previews */
.wcag-wp-button {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    margin: 5px 5px 5px 0;
    transition: all 0.2s ease;
    position: relative;
}

.wcag-wp-button:focus {
    outline: 2px solid #2271b1;
    outline-offset: 2px;
}

.wcag-wp-button.primary {
    background: #2271b1;
    color: white;
}

.wcag-wp-button.primary:hover {
    background: #135e96;
}

.wcag-wp-button.secondary {
    background: #f6f7f7;
    color: #2271b1;
    border: 1px solid #2271b1;
}

.wcag-wp-button.secondary:hover {
    background: #2271b1;
    color: white;
}

.wcag-wp-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.wcag-wp-table th,
.wcag-wp-table td {
    padding: 8px 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.wcag-wp-table th {
    background: #f6f7f7;
    font-weight: 600;
    color: #2271b1;
}

.wcag-wp-table tr:hover {
    background: #f9f9f9;
}

#wcag-wp-save-settings {
    margin: 20px 0;
}
</style>
