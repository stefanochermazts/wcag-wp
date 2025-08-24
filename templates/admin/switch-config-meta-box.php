<div class="wcag-wp-meta-box">
    <div class="wcag-wp-section">
        <h3><?php _e('Impostazioni Base', 'wcag-wp'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="wcag_switch_label"><?php _e('Etichetta', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_switch_config[label]" id="wcag_switch_label" 
                           value="<?php echo esc_attr($config['label']); ?>" class="regular-text">
                    <p class="description"><?php _e('Etichetta principale del switch', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_switch_description"><?php _e('Descrizione', 'wcag-wp'); ?></label></th>
                <td>
                    <textarea name="wcag_switch_config[description]" id="wcag_switch_description" 
                              rows="3" class="large-text"><?php echo esc_textarea($config['description']); ?></textarea>
                    <p class="description"><?php _e('Descrizione opzionale del switch', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_switch_on_text"><?php _e('Testo Stato Attivo', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_switch_config[on_text]" id="wcag_switch_on_text" 
                           value="<?php echo esc_attr($config['on_text']); ?>" class="regular-text">
                    <p class="description"><?php _e('Testo mostrato quando il switch è attivo', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_switch_off_text"><?php _e('Testo Stato Inattivo', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_switch_config[off_text]" id="wcag_switch_off_text" 
                           value="<?php echo esc_attr($config['off_text']); ?>" class="regular-text">
                    <p class="description"><?php _e('Testo mostrato quando il switch è inattivo', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_switch_default_state"><?php _e('Stato di Default', 'wcag-wp'); ?></label></th>
                <td>
                    <select name="wcag_switch_config[default_state]" id="wcag_switch_default_state">
                        <option value="off" <?php selected($config['default_state'], 'off'); ?>><?php _e('Inattivo', 'wcag-wp'); ?></option>
                        <option value="on" <?php selected($config['default_state'], 'on'); ?>><?php _e('Attivo', 'wcag-wp'); ?></option>
                    </select>
                    <p class="description"><?php _e('Stato iniziale del switch', 'wcag-wp'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="wcag-wp-section">
        <h3><?php _e('Comportamento', 'wcag-wp'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('Stato', 'wcag-wp'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="wcag_switch_config[required]" value="1" 
                               <?php checked($config['required']); ?>>
                        <?php _e('Obbligatorio', 'wcag-wp'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="wcag_switch_config[disabled]" value="1" 
                               <?php checked($config['disabled']); ?>>
                        <?php _e('Disabilitato', 'wcag-wp'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_switch_size"><?php _e('Dimensione', 'wcag-wp'); ?></label></th>
                <td>
                    <select name="wcag_switch_config[size]" id="wcag_switch_size">
                        <option value="small" <?php selected($config['size'], 'small'); ?>><?php _e('Piccolo', 'wcag-wp'); ?></option>
                        <option value="medium" <?php selected($config['size'], 'medium'); ?>><?php _e('Medio', 'wcag-wp'); ?></option>
                        <option value="large" <?php selected($config['size'], 'large'); ?>><?php _e('Grande', 'wcag-wp'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_switch_theme"><?php _e('Tema', 'wcag-wp'); ?></label></th>
                <td>
                    <select name="wcag_switch_config[theme]" id="wcag_switch_theme">
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
                        <input type="checkbox" name="wcag_switch_config[show_labels]" value="1" 
                               <?php checked($config['show_labels']); ?>>
                        <?php _e('Mostra etichette testuali', 'wcag-wp'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="wcag_switch_config[animation]" value="1" 
                               <?php checked($config['animation']); ?>>
                        <?php _e('Abilita animazioni', 'wcag-wp'); ?>
                    </label>
                </td>
            </tr>
        </table>
    </div>

    <div class="wcag-wp-section">
        <h3><?php _e('Accessibilità', 'wcag-wp'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="wcag_switch_aria_label"><?php _e('ARIA Label', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_switch_config[aria_label]" id="wcag_switch_aria_label" 
                           value="<?php echo esc_attr($config['aria_label']); ?>" class="regular-text">
                    <p class="description"><?php _e('Etichetta ARIA personalizzata per screen reader', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_switch_aria_describedby"><?php _e('ARIA Described By', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_switch_config[aria_describedby]" id="wcag_switch_aria_describedby" 
                           value="<?php echo esc_attr($config['aria_describedby']); ?>" class="regular-text">
                    <p class="description"><?php _e('ID dell\'elemento che descrive il switch', 'wcag-wp'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="wcag-wp-section">
        <h3><?php _e('Personalizzazione', 'wcag-wp'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="wcag_switch_custom_class"><?php _e('Classe CSS Personalizzata', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_switch_config[custom_class]" id="wcag_switch_custom_class" 
                           value="<?php echo esc_attr($config['custom_class']); ?>" class="regular-text">
                    <p class="description"><?php _e('Classi CSS aggiuntive per personalizzazione', 'wcag-wp'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wcag_switch_on_change_callback"><?php _e('Callback on Change', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" name="wcag_switch_config[on_change_callback]" id="wcag_switch_on_change_callback" 
                           value="<?php echo esc_attr($config['on_change_callback']); ?>" class="regular-text">
                    <p class="description"><?php _e('Nome funzione JavaScript da chiamare quando cambia lo stato', 'wcag-wp'); ?></p>
                </td>
            </tr>
        </table>
    </div>
</div>
