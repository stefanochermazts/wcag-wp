<?php
/**
 * Menu Button Configuration Meta Box Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-admin-meta-box">
    
    <!-- Button Configuration -->
    <div class="wcag-wp-field-group">
        <label for="wcag_menubutton_config_button_text" class="wcag-wp-field-label">
            <?php _e('Testo Button', 'wcag-wp'); ?>
        </label>
        <input type="text" 
               id="wcag_menubutton_config_button_text" 
               name="wcag_menubutton_config[button_text]" 
               value="<?php echo esc_attr($config['button_text']); ?>" 
               class="wcag-wp-field-input"
               placeholder="<?php _e('Menu', 'wcag-wp'); ?>" 
               required>
        <p class="wcag-wp-field-description">
            <?php _e('Testo visualizzato sul pulsante del menu', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Button Icon -->
    <div class="wcag-wp-field-group">
        <label for="wcag_menubutton_config_button_icon" class="wcag-wp-field-label">
            <?php _e('Icona Button', 'wcag-wp'); ?>
        </label>
        <input type="text" 
               id="wcag_menubutton_config_button_icon" 
               name="wcag_menubutton_config[button_icon]" 
               value="<?php echo esc_attr($config['button_icon']); ?>" 
               class="wcag-wp-field-input"
               placeholder="<?php _e('dashicons-menu', 'wcag-wp'); ?>">
        <p class="wcag-wp-field-description">
            <?php _e('Icona Dashicons per il pulsante (opzionale)', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- ARIA Label -->
    <div class="wcag-wp-field-group">
        <label for="wcag_menubutton_config_aria_label" class="wcag-wp-field-label">
            <?php _e('Etichetta ARIA', 'wcag-wp'); ?>
        </label>
        <input type="text" 
               id="wcag_menubutton_config_aria_label" 
               name="wcag_menubutton_config[aria_label]" 
               value="<?php echo esc_attr($config['aria_label']); ?>" 
               class="wcag-wp-field-input"
               placeholder="<?php _e('Apri menu opzioni', 'wcag-wp'); ?>">
        <p class="wcag-wp-field-description">
            <?php _e('Descrizione accessibile del pulsante per screen reader', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Menu Position -->
    <div class="wcag-wp-field-group">
        <label for="wcag_menubutton_config_position" class="wcag-wp-field-label">
            <?php _e('Posizione Menu', 'wcag-wp'); ?>
        </label>
        <select id="wcag_menubutton_config_position" name="wcag_menubutton_config[position]" class="wcag-wp-field-input">
            <option value="bottom-left" <?php selected($config['position'], 'bottom-left'); ?>><?php _e('In basso a sinistra', 'wcag-wp'); ?></option>
            <option value="bottom-right" <?php selected($config['position'], 'bottom-right'); ?>><?php _e('In basso a destra', 'wcag-wp'); ?></option>
            <option value="top-left" <?php selected($config['position'], 'top-left'); ?>><?php _e('In alto a sinistra', 'wcag-wp'); ?></option>
            <option value="top-right" <?php selected($config['position'], 'top-right'); ?>><?php _e('In alto a destra', 'wcag-wp'); ?></option>
            <option value="left" <?php selected($config['position'], 'left'); ?>><?php _e('A sinistra', 'wcag-wp'); ?></option>
            <option value="right" <?php selected($config['position'], 'right'); ?>><?php _e('A destra', 'wcag-wp'); ?></option>
        </select>
        <p class="wcag-wp-field-description">
            <?php _e('Posizione del menu rispetto al pulsante', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Trigger -->
    <div class="wcag-wp-field-group">
        <label for="wcag_menubutton_config_trigger" class="wcag-wp-field-label">
            <?php _e('Trigger Apertura', 'wcag-wp'); ?>
        </label>
        <select id="wcag_menubutton_config_trigger" name="wcag_menubutton_config[trigger]" class="wcag-wp-field-input">
            <option value="click" <?php selected($config['trigger'], 'click'); ?>><?php _e('Click', 'wcag-wp'); ?></option>
            <option value="hover" <?php selected($config['trigger'], 'hover'); ?>><?php _e('Hover (sconsigliato per accessibilità)', 'wcag-wp'); ?></option>
        </select>
        <p class="wcag-wp-field-description">
            <?php _e('Come viene attivato il menu. Click è raccomandato per l\'accessibilità', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Menu Items -->
    <div class="wcag-wp-field-group">
        <label class="wcag-wp-field-label">
            <?php _e('Elementi Menu', 'wcag-wp'); ?>
        </label>
        
        <div id="wcag-menubutton-items-container">
            <?php if (!empty($config['menu_items'])): ?>
                <?php foreach ($config['menu_items'] as $index => $item): ?>
                    <div class="wcag-menubutton-item" data-index="<?php echo esc_attr($index); ?>">
                        <div class="wcag-menubutton-item-header">
                            <span class="wcag-menubutton-item-title"><?php echo esc_html($item['label'] ?: __('Elemento senza titolo', 'wcag-wp')); ?></span>
                            <div class="wcag-menubutton-item-actions">
                                <button type="button" class="button wcag-menubutton-item-toggle"><?php _e('Modifica', 'wcag-wp'); ?></button>
                                <button type="button" class="button wcag-menubutton-item-remove"><?php _e('Rimuovi', 'wcag-wp'); ?></button>
                            </div>
                        </div>
                        
                        <div class="wcag-menubutton-item-content" style="display: none;">
                            <div class="wcag-wp-field-row">
                                <div class="wcag-wp-field-col">
                                    <label><?php _e('Etichetta', 'wcag-wp'); ?></label>
                                    <input type="text" 
                                           name="wcag_menubutton_config[menu_items][<?php echo esc_attr($index); ?>][label]" 
                                           value="<?php echo esc_attr($item['label']); ?>" 
                                           placeholder="<?php _e('Testo voce menu', 'wcag-wp'); ?>">
                                </div>
                                <div class="wcag-wp-field-col">
                                    <label><?php _e('URL', 'wcag-wp'); ?></label>
                                    <input type="url" 
                                           name="wcag_menubutton_config[menu_items][<?php echo esc_attr($index); ?>][url]" 
                                           value="<?php echo esc_attr($item['url']); ?>" 
                                           placeholder="<?php _e('https://esempio.com', 'wcag-wp'); ?>">
                                </div>
                            </div>
                            
                            <div class="wcag-wp-field-row">
                                <div class="wcag-wp-field-col">
                                    <label><?php _e('Icona', 'wcag-wp'); ?></label>
                                    <input type="text" 
                                           name="wcag_menubutton_config[menu_items][<?php echo esc_attr($index); ?>][icon]" 
                                           value="<?php echo esc_attr($item['icon']); ?>" 
                                           placeholder="<?php _e('dashicons-admin-home', 'wcag-wp'); ?>">
                                </div>
                                <div class="wcag-wp-field-col">
                                    <label><?php _e('Target', 'wcag-wp'); ?></label>
                                    <select name="wcag_menubutton_config[menu_items][<?php echo esc_attr($index); ?>][target]">
                                        <option value="_self" <?php selected($item['target'], '_self'); ?>><?php _e('Stessa finestra', 'wcag-wp'); ?></option>
                                        <option value="_blank" <?php selected($item['target'], '_blank'); ?>><?php _e('Nuova finestra', 'wcag-wp'); ?></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="wcag-wp-field-row">
                                <div class="wcag-wp-field-col">
                                    <label>
                                        <input type="checkbox" 
                                               name="wcag_menubutton_config[menu_items][<?php echo esc_attr($index); ?>][disabled]" 
                                               value="1" 
                                               <?php checked($item['disabled']); ?>>
                                        <?php _e('Disabilitato', 'wcag-wp'); ?>
                                    </label>
                                </div>
                                <div class="wcag-wp-field-col">
                                    <label>
                                        <input type="checkbox" 
                                               name="wcag_menubutton_config[menu_items][<?php echo esc_attr($index); ?>][separator]" 
                                               value="1" 
                                               <?php checked($item['separator']); ?>>
                                        <?php _e('Separatore dopo', 'wcag-wp'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <button type="button" id="wcag-add-menubutton-item" class="button button-secondary">
            <?php _e('+ Aggiungi Elemento', 'wcag-wp'); ?>
        </button>
    </div>
    
    <!-- Display Options -->
    <div class="wcag-wp-field-group">
        <fieldset>
            <legend class="wcag-wp-field-label"><?php _e('Opzioni Visualizzazione', 'wcag-wp'); ?></legend>
            
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" 
                       name="wcag_menubutton_config[show_arrow]" 
                       value="1" 
                       <?php checked($config['show_arrow']); ?>>
                <?php _e('Mostra freccia indicatore', 'wcag-wp'); ?>
            </label>
        </fieldset>
    </div>
    
    <!-- Behavior Options -->
    <div class="wcag-wp-field-group">
        <fieldset>
            <legend class="wcag-wp-field-label"><?php _e('Comportamento', 'wcag-wp'); ?></legend>
            
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" 
                       name="wcag_menubutton_config[close_on_select]" 
                       value="1" 
                       <?php checked($config['close_on_select']); ?>>
                <?php _e('Chiudi menu dopo selezione', 'wcag-wp'); ?>
            </label>
            
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" 
                       name="wcag_menubutton_config[keyboard_navigation]" 
                       value="1" 
                       <?php checked($config['keyboard_navigation']); ?>>
                <?php _e('Navigazione tastiera', 'wcag-wp'); ?>
            </label>
            
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" 
                       name="wcag_menubutton_config[auto_close]" 
                       value="1" 
                       <?php checked($config['auto_close']); ?>>
                <?php _e('Chiusura automatica', 'wcag-wp'); ?>
            </label>
        </fieldset>
    </div>
    
    <!-- Close Delay -->
    <div class="wcag-wp-field-group">
        <label for="wcag_menubutton_config_close_delay" class="wcag-wp-field-label">
            <?php _e('Ritardo Chiusura (ms)', 'wcag-wp'); ?>
        </label>
        <input type="number" 
               id="wcag_menubutton_config_close_delay" 
               name="wcag_menubutton_config[close_delay]" 
               value="<?php echo esc_attr($config['close_delay']); ?>" 
               min="0" 
               max="5000" 
               step="100" 
               class="wcag-wp-field-input wcag-wp-field-input--small">
        <p class="wcag-wp-field-description">
            <?php _e('Tempo di attesa prima della chiusura automatica del menu', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Custom Class -->
    <div class="wcag-wp-field-group">
        <label for="wcag_menubutton_config_custom_class" class="wcag-wp-field-label">
            <?php _e('Classe CSS Personalizzata', 'wcag-wp'); ?>
        </label>
        <input type="text" 
               id="wcag_menubutton_config_custom_class" 
               name="wcag_menubutton_config[custom_class]" 
               value="<?php echo esc_attr($config['custom_class']); ?>" 
               class="wcag-wp-field-input"
               placeholder="<?php _e('mia-classe-menubutton', 'wcag-wp'); ?>">
        <p class="wcag-wp-field-description">
            <?php _e('Classe CSS aggiuntiva per personalizzazioni avanzate', 'wcag-wp'); ?>
        </p>
    </div>
    
</div>

<!-- Menu Item Template (Hidden) -->
<script type="text/html" id="wcag-menubutton-item-template">
    <div class="wcag-menubutton-item" data-index="{{INDEX}}">
        <div class="wcag-menubutton-item-header">
            <span class="wcag-menubutton-item-title"><?php _e('Nuovo elemento', 'wcag-wp'); ?></span>
            <div class="wcag-menubutton-item-actions">
                <button type="button" class="button wcag-menubutton-item-toggle"><?php _e('Modifica', 'wcag-wp'); ?></button>
                <button type="button" class="button wcag-menubutton-item-remove"><?php _e('Rimuovi', 'wcag-wp'); ?></button>
            </div>
        </div>
        
        <div class="wcag-menubutton-item-content" style="display: none;">
            <div class="wcag-wp-field-row">
                <div class="wcag-wp-field-col">
                    <label><?php _e('Etichetta', 'wcag-wp'); ?></label>
                    <input type="text" 
                           name="wcag_menubutton_config[menu_items][{{INDEX}}][label]" 
                           value="" 
                           placeholder="<?php _e('Testo voce menu', 'wcag-wp'); ?>">
                </div>
                <div class="wcag-wp-field-col">
                    <label><?php _e('URL', 'wcag-wp'); ?></label>
                    <input type="url" 
                           name="wcag_menubutton_config[menu_items][{{INDEX}}][url]" 
                           value="" 
                           placeholder="<?php _e('https://esempio.com', 'wcag-wp'); ?>">
                </div>
            </div>
            
            <div class="wcag-wp-field-row">
                <div class="wcag-wp-field-col">
                    <label><?php _e('Icona', 'wcag-wp'); ?></label>
                    <input type="text" 
                           name="wcag_menubutton_config[menu_items][{{INDEX}}][icon]" 
                           value="" 
                           placeholder="<?php _e('dashicons-admin-home', 'wcag-wp'); ?>">
                </div>
                <div class="wcag-wp-field-col">
                    <label><?php _e('Target', 'wcag-wp'); ?></label>
                    <select name="wcag_menubutton_config[menu_items][{{INDEX}}][target]">
                        <option value="_self"><?php _e('Stessa finestra', 'wcag-wp'); ?></option>
                        <option value="_blank"><?php _e('Nuova finestra', 'wcag-wp'); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="wcag-wp-field-row">
                <div class="wcag-wp-field-col">
                    <label>
                        <input type="checkbox" 
                               name="wcag_menubutton_config[menu_items][{{INDEX}}][disabled]" 
                               value="1">
                        <?php _e('Disabilitato', 'wcag-wp'); ?>
                    </label>
                </div>
                <div class="wcag-wp-field-col">
                    <label>
                        <input type="checkbox" 
                               name="wcag_menubutton_config[menu_items][{{INDEX}}][separator]" 
                               value="1">
                        <?php _e('Separatore dopo', 'wcag-wp'); ?>
                    </label>
                </div>
            </div>
        </div>
    </div>
</script>
