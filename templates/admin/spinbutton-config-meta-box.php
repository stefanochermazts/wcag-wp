<?php
/**
 * Spinbutton Configuration Meta Box Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-meta-box">
    
    <!-- Basic Settings Section -->
    <div class="wcag-wp-section">
        <h3><?php _e('Impostazioni Base', 'wcag-wp'); ?></h3>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_type"><?php _e('Tipo di Dato', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <select name="wcag_spinbutton_config[type]" id="wcag_spinbutton_type">
                        <option value="integer" <?php selected($config['type'], 'integer'); ?>>
                            <?php _e('Numero Intero', 'wcag-wp'); ?>
                        </option>
                        <option value="decimal" <?php selected($config['type'], 'decimal'); ?>>
                            <?php _e('Numero Decimale', 'wcag-wp'); ?>
                        </option>
                        <option value="currency" <?php selected($config['type'], 'currency'); ?>>
                            <?php _e('Valuta', 'wcag-wp'); ?>
                        </option>
                        <option value="percentage" <?php selected($config['type'], 'percentage'); ?>>
                            <?php _e('Percentuale', 'wcag-wp'); ?>
                        </option>
                        <option value="time" <?php selected($config['type'], 'time'); ?>>
                            <?php _e('Tempo (minuti)', 'wcag-wp'); ?>
                        </option>
                        <option value="date" <?php selected($config['type'], 'date'); ?>>
                            <?php _e('Data (giorni)', 'wcag-wp'); ?>
                        </option>
                    </select>
                    <p class="description">
                        <?php _e('Tipo di dato che il spinbutton gestirà', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_min"><?php _e('Valore Minimo', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="number" 
                           name="wcag_spinbutton_config[min]" 
                           id="wcag_spinbutton_min"
                           value="<?php echo esc_attr($config['min']); ?>"
                           step="any"
                           class="regular-text">
                    <p class="description">
                        <?php _e('Valore minimo consentito (lasciare vuoto per nessun limite)', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_max"><?php _e('Valore Massimo', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="number" 
                           name="wcag_spinbutton_config[max]" 
                           id="wcag_spinbutton_max"
                           value="<?php echo esc_attr($config['max']); ?>"
                           step="any"
                           class="regular-text">
                    <p class="description">
                        <?php _e('Valore massimo consentito (lasciare vuoto per nessun limite)', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_step"><?php _e('Incremento', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="number" 
                           name="wcag_spinbutton_config[step]" 
                           id="wcag_spinbutton_step"
                           value="<?php echo esc_attr($config['step']); ?>"
                           step="any"
                           min="0.01"
                           class="regular-text">
                    <p class="description">
                        <?php _e('Valore di incremento/decremento per ogni click', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_default_value"><?php _e('Valore di Default', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="number" 
                           name="wcag_spinbutton_config[default_value]" 
                           id="wcag_spinbutton_default_value"
                           value="<?php echo esc_attr($config['default_value']); ?>"
                           step="any"
                           class="regular-text">
                    <p class="description">
                        <?php _e('Valore iniziale del spinbutton', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_unit"><?php _e('Unità di Misura', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="text" 
                           name="wcag_spinbutton_config[unit]" 
                           id="wcag_spinbutton_unit"
                           value="<?php echo esc_attr($config['unit']); ?>"
                           class="regular-text"
                           placeholder="es. kg, €, %, min">
                    <p class="description">
                        <?php _e('Unità di misura da mostrare accanto al valore (opzionale)', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Display Settings Section -->
    <div class="wcag-wp-section">
        <h3><?php _e('Impostazioni Visualizzazione', 'wcag-wp'); ?></h3>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_label"><?php _e('Etichetta', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="text" 
                           name="wcag_spinbutton_config[label]" 
                           id="wcag_spinbutton_label"
                           value="<?php echo esc_attr($config['label']); ?>"
                           class="regular-text"
                           placeholder="<?php _e('es. Quantità, Prezzo, Età', 'wcag-wp'); ?>">
                    <p class="description">
                        <?php _e('Etichetta descrittiva per il campo', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_description"><?php _e('Descrizione', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <textarea name="wcag_spinbutton_config[description]" 
                              id="wcag_spinbutton_description"
                              rows="3"
                              class="large-text"
                              placeholder="<?php _e('Descrizione aggiuntiva per aiutare l\'utente', 'wcag-wp'); ?>"><?php echo esc_textarea($config['description']); ?></textarea>
                    <p class="description">
                        <?php _e('Testo di aiuto per l\'utente (opzionale)', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_placeholder"><?php _e('Placeholder', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="text" 
                           name="wcag_spinbutton_config[placeholder]" 
                           id="wcag_spinbutton_placeholder"
                           value="<?php echo esc_attr($config['placeholder']); ?>"
                           class="regular-text"
                           placeholder="<?php _e('es. Inserisci quantità', 'wcag-wp'); ?>">
                    <p class="description">
                        <?php _e('Testo placeholder per il campo input', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_size"><?php _e('Dimensione', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <select name="wcag_spinbutton_config[size]" id="wcag_spinbutton_size">
                        <option value="small" <?php selected($config['size'], 'small'); ?>>
                            <?php _e('Piccola', 'wcag-wp'); ?>
                        </option>
                        <option value="medium" <?php selected($config['size'], 'medium'); ?>>
                            <?php _e('Media', 'wcag-wp'); ?>
                        </option>
                        <option value="large" <?php selected($config['size'], 'large'); ?>>
                            <?php _e('Grande', 'wcag-wp'); ?>
                        </option>
                    </select>
                    <p class="description">
                        <?php _e('Dimensione visiva del spinbutton', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_format"><?php _e('Formato Visualizzazione', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <select name="wcag_spinbutton_config[format]" id="wcag_spinbutton_format">
                        <option value="number" <?php selected($config['format'], 'number'); ?>>
                            <?php _e('Numero semplice', 'wcag-wp'); ?>
                        </option>
                        <option value="currency" <?php selected($config['format'], 'currency'); ?>>
                            <?php _e('Valuta (€)', 'wcag-wp'); ?>
                        </option>
                        <option value="percentage" <?php selected($config['format'], 'percentage'); ?>>
                            <?php _e('Percentuale (%)', 'wcag-wp'); ?>
                        </option>
                        <option value="decimal" <?php selected($config['format'], 'decimal'); ?>>
                            <?php _e('Decimale con separatori', 'wcag-wp'); ?>
                        </option>
                    </select>
                    <p class="description">
                        <?php _e('Formato di visualizzazione del valore', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Behavior Settings Section -->
    <div class="wcag-wp-section">
        <h3><?php _e('Comportamento', 'wcag-wp'); ?></h3>
        
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('Stato del Campo', 'wcag-wp'); ?></th>
                <td>
                    <fieldset>
                        <label>
                            <input type="checkbox" 
                                   name="wcag_spinbutton_config[required]" 
                                   value="1" 
                                   <?php checked($config['required']); ?>>
                            <?php _e('Campo obbligatorio', 'wcag-wp'); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" 
                                   name="wcag_spinbutton_config[readonly]" 
                                   value="1" 
                                   <?php checked($config['readonly']); ?>>
                            <?php _e('Solo lettura (non modificabile)', 'wcag-wp'); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" 
                                   name="wcag_spinbutton_config[disabled]" 
                                   value="1" 
                                   <?php checked($config['disabled']); ?>>
                            <?php _e('Disabilitato', 'wcag-wp'); ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Accessibility Settings Section -->
    <div class="wcag-wp-section">
        <h3><?php _e('Accessibilità', 'wcag-wp'); ?></h3>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_aria_label"><?php _e('ARIA Label', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="text" 
                           name="wcag_spinbutton_config[aria_label]" 
                           id="wcag_spinbutton_aria_label"
                           value="<?php echo esc_attr($config['aria_label']); ?>"
                           class="regular-text"
                           placeholder="<?php _e('Etichetta per screen reader', 'wcag-wp'); ?>">
                    <p class="description">
                        <?php _e('Etichetta ARIA per screen reader (se diversa dall\'etichetta visiva)', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_aria_describedby"><?php _e('ARIA Described By', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="text" 
                           name="wcag_spinbutton_config[aria_describedby]" 
                           id="wcag_spinbutton_aria_describedby"
                           value="<?php echo esc_attr($config['aria_describedby']); ?>"
                           class="regular-text"
                           placeholder="ID elemento descrizione">
                    <p class="description">
                        <?php _e('ID dell\'elemento che descrive il campo (opzionale)', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Customization Section -->
    <div class="wcag-wp-section">
        <h3><?php _e('Personalizzazione', 'wcag-wp'); ?></h3>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="wcag_spinbutton_custom_class"><?php _e('Classe CSS Personalizzata', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="text" 
                           name="wcag_spinbutton_config[custom_class]" 
                           id="wcag_spinbutton_custom_class"
                           value="<?php echo esc_attr($config['custom_class']); ?>"
                           class="regular-text"
                           placeholder="mia-classe-spinbutton">
                    <p class="description">
                        <?php _e('Classi CSS aggiuntive per personalizzazione', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    
</div>

<style>
.wcag-wp-meta-box .wcag-wp-section {
    margin-bottom: 2rem;
    padding: 1rem;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.wcag-wp-meta-box .wcag-wp-section h3 {
    margin-top: 0;
    margin-bottom: 1rem;
    color: #23282d;
    border-bottom: 1px solid #ddd;
    padding-bottom: 0.5rem;
}

.wcag-wp-meta-box .form-table th {
    width: 200px;
}

.wcag-wp-meta-box .description {
    color: #666;
    font-style: italic;
    margin-top: 0.25rem;
}
</style>
