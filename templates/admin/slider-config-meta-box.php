<div class="wcag-wp-meta-box">
    <div class="wcag-wp-section">
        <h3><?php _e('Impostazioni Base', 'wcag-wp'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="wcag_slider_label"><?php _e('Etichetta', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_slider_config[label]" id="wcag_slider_label" 
                           value="<?php echo esc_attr($config['label']); ?>" class="regular-text">
                    <p class="description"><?php _e('Etichetta principale dello slider', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_description"><?php _e('Descrizione', 'wcag-wp'); ?></label></th>
                <td>
                    <textarea name="wcag_slider_config[description]" id="wcag_slider_description" 
                              rows="3" class="large-text"><?php echo esc_textarea($config['description']); ?></textarea>
                    <p class="description"><?php _e('Descrizione opzionale dello slider', 'wcag-wp'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="wcag-wp-section">
        <h3><?php _e('Range e Valori', 'wcag-wp'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="wcag_slider_min"><?php _e('Valore Minimo', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="number" name="wcag_slider_config[min]" id="wcag_slider_min" 
                           value="<?php echo esc_attr($config['min']); ?>" step="any" class="regular-text">
                    <p class="description"><?php _e('Valore minimo dello slider', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_max"><?php _e('Valore Massimo', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="number" name="wcag_slider_config[max]" id="wcag_slider_max" 
                           value="<?php echo esc_attr($config['max']); ?>" step="any" class="regular-text">
                    <p class="description"><?php _e('Valore massimo dello slider', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_step"><?php _e('Passo', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="number" name="wcag_slider_config[step]" id="wcag_slider_step" 
                           value="<?php echo esc_attr($config['step']); ?>" step="any" class="regular-text">
                    <p class="description"><?php _e('Incremento tra i valori (es. 1, 0.5, 10)', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_default_value"><?php _e('Valore di Default', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="number" name="wcag_slider_config[default_value]" id="wcag_slider_default_value" 
                           value="<?php echo esc_attr($config['default_value']); ?>" step="any" class="regular-text">
                    <p class="description"><?php _e('Valore iniziale dello slider', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_unit"><?php _e('Unità di Misura', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_slider_config[unit]" id="wcag_slider_unit" 
                           value="<?php echo esc_attr($config['unit']); ?>" class="regular-text" placeholder="es. %, €, kg">
                    <p class="description"><?php _e('Unità di misura opzionale (es. %, €, kg)', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_format"><?php _e('Formato', 'wcag-wp'); ?></label></th>
                <td>
                    <select name="wcag_slider_config[format]" id="wcag_slider_format">
                        <option value="number" <?php selected($config['format'], 'number'); ?>><?php _e('Numero', 'wcag-wp'); ?></option>
                        <option value="percentage" <?php selected($config['format'], 'percentage'); ?>><?php _e('Percentuale', 'wcag-wp'); ?></option>
                        <option value="currency" <?php selected($config['format'], 'currency'); ?>><?php _e('Valuta', 'wcag-wp'); ?></option>
                    </select>
                    <p class="description"><?php _e('Formato di visualizzazione del valore', 'wcag-wp'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="wcag-wp-section">
        <h3><?php _e('Aspetto e Comportamento', 'wcag-wp'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="wcag_slider_orientation"><?php _e('Orientamento', 'wcag-wp'); ?></label></th>
                <td>
                    <select name="wcag_slider_config[orientation]" id="wcag_slider_orientation">
                        <option value="horizontal" <?php selected($config['orientation'], 'horizontal'); ?>><?php _e('Orizzontale', 'wcag-wp'); ?></option>
                        <option value="vertical" <?php selected($config['orientation'], 'vertical'); ?>><?php _e('Verticale', 'wcag-wp'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_size"><?php _e('Dimensione', 'wcag-wp'); ?></label></th>
                <td>
                    <select name="wcag_slider_config[size]" id="wcag_slider_size">
                        <option value="small" <?php selected($config['size'], 'small'); ?>><?php _e('Piccolo', 'wcag-wp'); ?></option>
                        <option value="medium" <?php selected($config['size'], 'medium'); ?>><?php _e('Medio', 'wcag-wp'); ?></option>
                        <option value="large" <?php selected($config['size'], 'large'); ?>><?php _e('Grande', 'wcag-wp'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_theme"><?php _e('Tema', 'wcag-wp'); ?></label></th>
                <td>
                    <select name="wcag_slider_config[theme]" id="wcag_slider_theme">
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
                        <input type="checkbox" name="wcag_slider_config[show_value]" value="1" 
                               <?php checked($config['show_value']); ?>>
                        <?php _e('Mostra valore corrente', 'wcag-wp'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="wcag_slider_config[show_ticks]" value="1" 
                               <?php checked($config['show_ticks']); ?>>
                        <?php _e('Mostra tacche', 'wcag-wp'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="wcag_slider_config[show_tooltip]" value="1" 
                               <?php checked($config['show_tooltip']); ?>>
                        <?php _e('Mostra tooltip al passaggio del mouse', 'wcag-wp'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_tick_labels"><?php _e('Etichette Tacche', 'wcag-wp'); ?></label></th>
                <td>
                    <textarea name="wcag_slider_config[tick_labels]" id="wcag_slider_tick_labels" 
                              rows="3" class="large-text" placeholder="Minimo, Basso, Medio, Alto, Massimo"><?php echo esc_textarea($config['tick_labels']); ?></textarea>
                    <p class="description"><?php _e('Etichette personalizzate per le tacche (separate da virgole)', 'wcag-wp'); ?></p>
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
                        <input type="checkbox" name="wcag_slider_config[required]" value="1" 
                               <?php checked($config['required']); ?>>
                        <?php _e('Obbligatorio', 'wcag-wp'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="wcag_slider_config[disabled]" value="1" 
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
                <th scope="row"><label for="wcag_slider_aria_label"><?php _e('ARIA Label', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_slider_config[aria_label]" id="wcag_slider_aria_label" 
                           value="<?php echo esc_attr($config['aria_label']); ?>" class="regular-text">
                    <p class="description"><?php _e('Etichetta ARIA personalizzata per screen reader', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_aria_describedby"><?php _e('ARIA Described By', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_slider_config[aria_describedby]" id="wcag_slider_aria_describedby" 
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
                <th scope="row"><label for="wcag_slider_custom_class"><?php _e('Classe CSS Personalizzata', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_slider_config[custom_class]" id="wcag_slider_custom_class" 
                           value="<?php echo esc_attr($config['custom_class']); ?>" class="regular-text">
                    <p class="description"><?php _e('Classi CSS aggiuntive per personalizzazione', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_slider_on_change_callback"><?php _e('Callback on Change', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_slider_config[on_change_callback]" id="wcag_slider_on_change_callback" 
                           value="<?php echo esc_attr($config['on_change_callback']); ?>" class="regular-text">
                    <p class="description"><?php _e('Nome funzione JavaScript da chiamare quando cambia il valore', 'wcag-wp'); ?></p>
                </td>
            </tr>
        </table>
    </div>
</div>
