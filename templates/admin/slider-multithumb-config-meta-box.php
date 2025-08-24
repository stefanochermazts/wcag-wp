<?php
/**
 * Template per meta box configurazione Slider Multi-Thumb
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Nonce per sicurezza
wp_nonce_field('wcag_slider_multithumb_config', 'wcag_slider_multithumb_config_nonce');
?>

<div class="wcag-wp-meta-box">
    <div class="wcag-wp-section">
        <h3><?php _e('Impostazioni Base', 'wcag-wp'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_label"><?php _e('Etichetta', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_slider_multithumb_config[label]" id="wcag_slider_multithumb_label" 
                           value="<?php echo esc_attr($config['label']); ?>" class="regular-text">
                    <p class="description"><?php _e('Etichetta principale dello slider multi-thumb', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_description"><?php _e('Descrizione', 'wcag-wp'); ?></label></th>
                <td>
                    <textarea name="wcag_slider_multithumb_config[description]" id="wcag_slider_multithumb_description" 
                              rows="3" class="large-text"><?php echo esc_textarea($config['description']); ?></textarea>
                    <p class="description"><?php _e('Descrizione opzionale dello slider multi-thumb', 'wcag-wp'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="wcag-wp-section">
        <h3><?php _e('Range e Valori', 'wcag-wp'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_min"><?php _e('Valore Minimo', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="number" name="wcag_slider_multithumb_config[min]" id="wcag_slider_multithumb_min" 
                           value="<?php echo esc_attr($config['min']); ?>" step="any" class="regular-text">
                    <p class="description"><?php _e('Valore minimo dello slider', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_max"><?php _e('Valore Massimo', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="number" name="wcag_slider_multithumb_config[max]" id="wcag_slider_multithumb_max" 
                           value="<?php echo esc_attr($config['max']); ?>" step="any" class="regular-text">
                    <p class="description"><?php _e('Valore massimo dello slider', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_step"><?php _e('Passo', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="number" name="wcag_slider_multithumb_config[step]" id="wcag_slider_multithumb_step" 
                           value="<?php echo esc_attr($config['step']); ?>" step="any" class="regular-text">
                    <p class="description"><?php _e('Incremento tra i valori (es. 1, 0.5, 10)', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_thumbs_count"><?php _e('Numero di Thumbs', 'wcag-wp'); ?></label></th>
                <td>
                    <select name="wcag_slider_multithumb_config[thumbs_count]" id="wcag_slider_multithumb_thumbs_count">
                        <option value="2" <?php selected($config['thumbs_count'], '2'); ?>><?php _e('2 Thumbs', 'wcag-wp'); ?></option>
                        <option value="3" <?php selected($config['thumbs_count'], '3'); ?>><?php _e('3 Thumbs', 'wcag-wp'); ?></option>
                        <option value="4" <?php selected($config['thumbs_count'], '4'); ?>><?php _e('4 Thumbs', 'wcag-wp'); ?></option>
                        <option value="5" <?php selected($config['thumbs_count'], '5'); ?>><?php _e('5 Thumbs', 'wcag-wp'); ?></option>
                    </select>
                    <p class="description"><?php _e('Numero di thumbs (maniglie) dello slider', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_default_values"><?php _e('Valori di Default', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_slider_multithumb_config[default_values]" id="wcag_slider_multithumb_default_values" 
                           value="<?php echo esc_attr($config['default_values']); ?>" class="regular-text" placeholder="10,30,50,70">
                    <p class="description"><?php _e('Valori iniziali dei thumbs separati da virgole (es. 10,30,50)', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_unit"><?php _e('Unità di Misura', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_slider_multithumb_config[unit]" id="wcag_slider_multithumb_unit" 
                           value="<?php echo esc_attr($config['unit']); ?>" class="regular-text" placeholder="es. %, €, kg">
                    <p class="description"><?php _e('Unità di misura opzionale (es. %, €, kg)', 'wcag-wp'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="wcag-wp-section">
        <h3><?php _e('Aspetto e Comportamento', 'wcag-wp'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_orientation"><?php _e('Orientamento', 'wcag-wp'); ?></label></th>
                <td>
                    <select name="wcag_slider_multithumb_config[orientation]" id="wcag_slider_multithumb_orientation">
                        <option value="horizontal" <?php selected($config['orientation'], 'horizontal'); ?>><?php _e('Orizzontale', 'wcag-wp'); ?></option>
                        <option value="vertical" <?php selected($config['orientation'], 'vertical'); ?>><?php _e('Verticale', 'wcag-wp'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_size"><?php _e('Dimensione', 'wcag-wp'); ?></label></th>
                <td>
                    <select name="wcag_slider_multithumb_config[size]" id="wcag_slider_multithumb_size">
                        <option value="small" <?php selected($config['size'], 'small'); ?>><?php _e('Piccolo', 'wcag-wp'); ?></option>
                        <option value="medium" <?php selected($config['size'], 'medium'); ?>><?php _e('Medio', 'wcag-wp'); ?></option>
                        <option value="large" <?php selected($config['size'], 'large'); ?>><?php _e('Grande', 'wcag-wp'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_theme"><?php _e('Tema', 'wcag-wp'); ?></label></th>
                <td>
                    <select name="wcag_slider_multithumb_config[theme]" id="wcag_slider_multithumb_theme">
                        <option value="default" <?php selected($config['theme'], 'default'); ?>><?php _e('Default', 'wcag-wp'); ?></option>
                        <option value="success" <?php selected($config['theme'], 'success'); ?>><?php _e('Successo', 'wcag-wp'); ?></option>
                        <option value="warning" <?php selected($config['theme'], 'warning'); ?>><?php _e('Avviso', 'wcag-wp'); ?></option>
                        <option value="danger" <?php selected($config['theme'], 'danger'); ?>><?php _e('Pericolo', 'wcag-wp'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Opzioni', 'wcag-wp'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="wcag_slider_multithumb_config[show_values]" value="1" 
                               <?php checked($config['show_values']); ?>>
                        <?php _e('Mostra valori correnti', 'wcag-wp'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="wcag_slider_multithumb_config[show_range_fill]" value="1" 
                               <?php checked($config['show_range_fill']); ?>>
                        <?php _e('Mostra riempimento tra i thumbs', 'wcag-wp'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="wcag_slider_multithumb_config[show_ticks]" value="1" 
                               <?php checked($config['show_ticks']); ?>>
                        <?php _e('Mostra tacche', 'wcag-wp'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="wcag_slider_multithumb_config[prevent_overlap]" value="1" 
                               <?php checked($config['prevent_overlap']); ?>>
                        <?php _e('Previeni sovrapposizione thumbs', 'wcag-wp'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_min_distance"><?php _e('Distanza Minima', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="number" name="wcag_slider_multithumb_config[min_distance]" id="wcag_slider_multithumb_min_distance" 
                           value="<?php echo esc_attr($config['min_distance']); ?>" step="any" class="regular-text">
                    <p class="description"><?php _e('Distanza minima tra i thumbs (0 = nessuna limitazione)', 'wcag-wp'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="wcag-wp-section">
        <h3><?php _e('Stato', 'wcag-wp'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('Stato', 'wcag-wp'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="wcag_slider_multithumb_config[required]" value="1" 
                               <?php checked($config['required']); ?>>
                        <?php _e('Obbligatorio', 'wcag-wp'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="wcag_slider_multithumb_config[disabled]" value="1" 
                               <?php checked($config['disabled']); ?>>
                        <?php _e('Disabilitato', 'wcag-wp'); ?>
                    </label>
                </td>
            </tr>
        </table>
    </div>

    <div class="wcag-wp-section">
        <h3><?php _e('Accessibilità', 'wcag-wp'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_aria_label"><?php _e('ARIA Label', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_slider_multithumb_config[aria_label]" id="wcag_slider_multithumb_aria_label" 
                           value="<?php echo esc_attr($config['aria_label']); ?>" class="regular-text">
                    <p class="description"><?php _e('Etichetta ARIA personalizzata per screen reader', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_aria_describedby"><?php _e('ARIA Described By', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_slider_multithumb_config[aria_describedby]" id="wcag_slider_multithumb_aria_describedby" 
                           value="<?php echo esc_attr($config['aria_describedby']); ?>" class="regular-text">
                    <p class="description"><?php _e('ID dell\'elemento che descrive lo slider', 'wcag-wp'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="wcag-wp-section">
        <h3><?php _e('Personalizzazione', 'wcag-wp'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_custom_class"><?php _e('Classe CSS Personalizzata', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_slider_multithumb_config[custom_class]" id="wcag_slider_multithumb_custom_class" 
                           value="<?php echo esc_attr($config['custom_class']); ?>" class="regular-text">
                    <p class="description"><?php _e('Classi CSS aggiuntive per personalizzazione', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_multithumb_on_change_callback"><?php _e('Callback on Change', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_slider_multithumb_config[on_change_callback]" id="wcag_slider_multithumb_on_change_callback" 
                           value="<?php echo esc_attr($config['on_change_callback']); ?>" class="regular-text">
                    <p class="description"><?php _e('Nome funzione JavaScript da chiamare quando cambiano i valori', 'wcag-wp'); ?></p>
                </td>
            </tr>
        </table>
    </div>
</div>

<style>
.wcag-wp-meta-box .wcag-wp-section {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #f9f9f9;
}

.wcag-wp-meta-box .wcag-wp-section h3 {
    margin-top: 0;
    color: #0073aa;
    border-bottom: 1px solid #ddd;
    padding-bottom: 8px;
}

.wcag-wp-meta-box .form-table th {
    width: 200px;
    font-weight: 600;
}

.wcag-wp-meta-box .form-table td {
    padding-left: 20px;
}

.wcag-wp-meta-box .description {
    font-style: italic;
    color: #666;
    margin-top: 5px;
}
</style>
