<?php
/**
 * Menu Configuration Meta Box Template
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
    
    <!-- Menu Type -->
    <div class="wcag-wp-field-group">
        <label for="wcag_menu_config_type" class="wcag-wp-field-label">
            <?php _e('Tipo Menu', 'wcag-wp'); ?>
        </label>
        <select id="wcag_menu_config_type" name="wcag_menu_config[type]" class="wcag-wp-field-input">
            <option value="menubar" <?php selected($config['type'], 'menubar'); ?>><?php _e('Menubar (Orizzontale)', 'wcag-wp'); ?></option>
            <option value="menu" <?php selected($config['type'], 'menu'); ?>><?php _e('Menu (Verticale)', 'wcag-wp'); ?></option>
        </select>
        <p class="wcag-wp-field-description">
            <?php _e('Menubar per navigazione principale, Menu per menu contestuali', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Orientation -->
    <div class="wcag-wp-field-group">
        <label for="wcag_menu_config_orientation" class="wcag-wp-field-label">
            <?php _e('Orientamento', 'wcag-wp'); ?>
        </label>
        <select id="wcag_menu_config_orientation" name="wcag_menu_config[orientation]" class="wcag-wp-field-input">
            <option value="horizontal" <?php selected($config['orientation'], 'horizontal'); ?>><?php _e('Orizzontale', 'wcag-wp'); ?></option>
            <option value="vertical" <?php selected($config['orientation'], 'vertical'); ?>><?php _e('Verticale', 'wcag-wp'); ?></option>
        </select>
    </div>
    
    <!-- ARIA Label -->
    <div class="wcag-wp-field-group">
        <label for="wcag_menu_config_aria_label" class="wcag-wp-field-label">
            <?php _e('Etichetta ARIA', 'wcag-wp'); ?>
        </label>
        <input type="text" 
               id="wcag_menu_config_aria_label" 
               name="wcag_menu_config[aria_label]" 
               value="<?php echo esc_attr($config['aria_label']); ?>" 
               class="wcag-wp-field-input"
               placeholder="<?php _e('es: Menu principale navigazione', 'wcag-wp'); ?>">
        <p class="wcag-wp-field-description">
            <?php _e('Descrizione accessibile del menu per screen reader', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Menu Items -->
    <div class="wcag-wp-field-group">
        <label class="wcag-wp-field-label">
            <?php _e('Elementi Menu', 'wcag-wp'); ?>
        </label>
        
        <div id="wcag-menu-items-container">
            <?php if (!empty($config['items'])): ?>
                <?php foreach ($config['items'] as $index => $item): ?>
                    <div class="wcag-menu-item" data-index="<?php echo esc_attr($index); ?>">
                        <div class="wcag-menu-item-header">
                            <span class="wcag-menu-item-title"><?php echo esc_html($item['label'] ?: __('Elemento senza titolo', 'wcag-wp')); ?></span>
                            <div class="wcag-menu-item-actions">
                                <button type="button" class="button wcag-menu-item-toggle"><?php _e('Modifica', 'wcag-wp'); ?></button>
                                <button type="button" class="button wcag-menu-item-remove"><?php _e('Rimuovi', 'wcag-wp'); ?></button>
                            </div>
                        </div>
                        
                        <div class="wcag-menu-item-content" style="display: none;">
                            <div class="wcag-wp-field-row">
                                <div class="wcag-wp-field-col">
                                    <label><?php _e('Etichetta', 'wcag-wp'); ?></label>
                                    <input type="text" 
                                           name="wcag_menu_config[items][<?php echo esc_attr($index); ?>][label]" 
                                           value="<?php echo esc_attr($item['label']); ?>" 
                                           placeholder="<?php _e('Testo del menu', 'wcag-wp'); ?>">
                                </div>
                                <div class="wcag-wp-field-col">
                                    <label><?php _e('URL', 'wcag-wp'); ?></label>
                                    <input type="url" 
                                           name="wcag_menu_config[items][<?php echo esc_attr($index); ?>][url]" 
                                           value="<?php echo esc_attr($item['url']); ?>" 
                                           placeholder="<?php _e('https://esempio.com', 'wcag-wp'); ?>">
                                </div>
                            </div>
                            
                            <div class="wcag-wp-field-row">
                                <div class="wcag-wp-field-col">
                                    <label><?php _e('Icona', 'wcag-wp'); ?></label>
                                    <input type="text" 
                                           name="wcag_menu_config[items][<?php echo esc_attr($index); ?>][icon]" 
                                           value="<?php echo esc_attr($item['icon']); ?>" 
                                           placeholder="<?php _e('dashicons-admin-home', 'wcag-wp'); ?>">
                                </div>
                                <div class="wcag-wp-field-col">
                                    <label><?php _e('Target', 'wcag-wp'); ?></label>
                                    <select name="wcag_menu_config[items][<?php echo esc_attr($index); ?>][target]">
                                        <option value="_self" <?php selected($item['target'], '_self'); ?>><?php _e('Stessa finestra', 'wcag-wp'); ?></option>
                                        <option value="_blank" <?php selected($item['target'], '_blank'); ?>><?php _e('Nuova finestra', 'wcag-wp'); ?></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="wcag-wp-field-row">
                                <div class="wcag-wp-field-col">
                                    <label>
                                        <input type="checkbox" 
                                               name="wcag_menu_config[items][<?php echo esc_attr($index); ?>][disabled]" 
                                               value="1" 
                                               <?php checked($item['disabled']); ?>>
                                        <?php _e('Disabilitato', 'wcag-wp'); ?>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Sottomenu Section -->
                            <div class="wcag-wp-submenu-section">
                                <h4><?php _e('Sottomenu', 'wcag-wp'); ?></h4>
                                <div class="wcag-wp-submenu-container" data-parent-index="<?php echo esc_attr($index); ?>">
                                    <?php if (!empty($item['submenu'])): ?>
                                        <?php foreach ($item['submenu'] as $sub_index => $sub_item): ?>
                                            <div class="wcag-wp-submenu-item" data-sub-index="<?php echo esc_attr($sub_index); ?>">
                                                <div class="wcag-wp-submenu-header">
                                                    <span class="wcag-wp-submenu-title"><?php echo esc_html($sub_item['label'] ?: __('Sottoelemento senza titolo', 'wcag-wp')); ?></span>
                                                    <div class="wcag-wp-submenu-actions">
                                                        <button type="button" class="button wcag-submenu-item-toggle"><?php _e('Modifica', 'wcag-wp'); ?></button>
                                                        <button type="button" class="button wcag-submenu-item-remove"><?php _e('Rimuovi', 'wcag-wp'); ?></button>
                                                    </div>
                                                </div>
                                                
                                                <div class="wcag-wp-submenu-content" style="display: none;">
                                                    <div class="wcag-wp-field-row">
                                                        <div class="wcag-wp-field-col">
                                                            <label><?php _e('Etichetta', 'wcag-wp'); ?></label>
                                                            <input type="text" 
                                                                   name="wcag_menu_config[items][<?php echo esc_attr($index); ?>][submenu][<?php echo esc_attr($sub_index); ?>][label]" 
                                                                   value="<?php echo esc_attr($sub_item['label']); ?>" 
                                                                   placeholder="<?php _e('Testo del sottomenu', 'wcag-wp'); ?>">
                                                        </div>
                                                        <div class="wcag-wp-field-col">
                                                            <label><?php _e('URL', 'wcag-wp'); ?></label>
                                                            <input type="url" 
                                                                   name="wcag_menu_config[items][<?php echo esc_attr($index); ?>][submenu][<?php echo esc_attr($sub_index); ?>][url]" 
                                                                   value="<?php echo esc_attr($sub_item['url']); ?>" 
                                                                   placeholder="<?php _e('https://esempio.com', 'wcag-wp'); ?>">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="wcag-wp-field-row">
                                                        <div class="wcag-wp-field-col">
                                                            <label><?php _e('Icona', 'wcag-wp'); ?></label>
                                                            <input type="text" 
                                                                   name="wcag_menu_config[items][<?php echo esc_attr($index); ?>][submenu][<?php echo esc_attr($sub_index); ?>][icon]" 
                                                                   value="<?php echo esc_attr($sub_item['icon']); ?>" 
                                                                   placeholder="<?php _e('dashicons-admin-home', 'wcag-wp'); ?>">
                                                        </div>
                                                        <div class="wcag-wp-field-col">
                                                            <label><?php _e('Target', 'wcag-wp'); ?></label>
                                                            <select name="wcag_menu_config[items][<?php echo esc_attr($index); ?>][submenu][<?php echo esc_attr($sub_index); ?>][target]">
                                                                <option value="_self" <?php selected($sub_item['target'], '_self'); ?>><?php _e('Stessa finestra', 'wcag-wp'); ?></option>
                                                                <option value="_blank" <?php selected($sub_item['target'], '_blank'); ?>><?php _e('Nuova finestra', 'wcag-wp'); ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="wcag-wp-field-row">
                                                        <div class="wcag-wp-field-col">
                                                            <label>
                                                                <input type="checkbox" 
                                                                       name="wcag_menu_config[items][<?php echo esc_attr($index); ?>][submenu][<?php echo esc_attr($sub_index); ?>][disabled]" 
                                                                       value="1" 
                                                                       <?php checked($sub_item['disabled']); ?>>
                                                                <?php _e('Disabilitato', 'wcag-wp'); ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                
                                <button type="button" class="button button-secondary wcag-add-submenu-item" data-parent-index="<?php echo esc_attr($index); ?>">
                                    <?php _e('+ Aggiungi Sottoelemento', 'wcag-wp'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <button type="button" id="wcag-add-menu-item" class="button button-secondary">
            <?php _e('+ Aggiungi Elemento', 'wcag-wp'); ?>
        </button>
    </div>
    
    <!-- Display Options -->
    <div class="wcag-wp-field-group">
        <fieldset>
            <legend class="wcag-wp-field-label"><?php _e('Opzioni Visualizzazione', 'wcag-wp'); ?></legend>
            
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" 
                       name="wcag_menu_config[show_icons]" 
                       value="1" 
                       <?php checked($config['show_icons']); ?>>
                <?php _e('Mostra icone', 'wcag-wp'); ?>
            </label>
            
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" 
                       name="wcag_menu_config[show_labels]" 
                       value="1" 
                       <?php checked($config['show_labels']); ?>>
                <?php _e('Mostra etichette', 'wcag-wp'); ?>
            </label>
        </fieldset>
    </div>
    
    <!-- Behavior Options -->
    <div class="wcag-wp-field-group">
        <fieldset>
            <legend class="wcag-wp-field-label"><?php _e('Comportamento', 'wcag-wp'); ?></legend>
            
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" 
                       name="wcag_menu_config[keyboard_navigation]" 
                       value="1" 
                       <?php checked($config['keyboard_navigation']); ?>>
                <?php _e('Navigazione tastiera', 'wcag-wp'); ?>
            </label>
            
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" 
                       name="wcag_menu_config[auto_close]" 
                       value="1" 
                       <?php checked($config['auto_close']); ?>>
                <?php _e('Chiusura automatica sottomenu', 'wcag-wp'); ?>
            </label>
        </fieldset>
    </div>
    
    <!-- Close Delay -->
    <div class="wcag-wp-field-group">
        <label for="wcag_menu_config_close_delay" class="wcag-wp-field-label">
            <?php _e('Ritardo Chiusura (ms)', 'wcag-wp'); ?>
        </label>
        <input type="number" 
               id="wcag_menu_config_close_delay" 
               name="wcag_menu_config[close_delay]" 
               value="<?php echo esc_attr($config['close_delay']); ?>" 
               min="0" 
               max="5000" 
               step="100" 
               class="wcag-wp-field-input wcag-wp-field-input--small">
        <p class="wcag-wp-field-description">
            <?php _e('Tempo di attesa prima della chiusura automatica dei sottomenu', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Custom Class -->
    <div class="wcag-wp-field-group">
        <label for="wcag_menu_config_custom_class" class="wcag-wp-field-label">
            <?php _e('Classe CSS Personalizzata', 'wcag-wp'); ?>
        </label>
        <input type="text" 
               id="wcag_menu_config_custom_class" 
               name="wcag_menu_config[custom_class]" 
               value="<?php echo esc_attr($config['custom_class']); ?>" 
               class="wcag-wp-field-input"
               placeholder="<?php _e('mia-classe-menu', 'wcag-wp'); ?>">
        <p class="wcag-wp-field-description">
            <?php _e('Classe CSS aggiuntiva per personalizzazioni avanzate', 'wcag-wp'); ?>
        </p>
    </div>
    
</div>

<!-- Menu Item Template (Hidden) -->
<script type="text/html" id="wcag-menu-item-template">
    <div class="wcag-menu-item" data-index="{{INDEX}}">
        <div class="wcag-menu-item-header">
            <span class="wcag-menu-item-title"><?php _e('Nuovo elemento', 'wcag-wp'); ?></span>
            <div class="wcag-menu-item-actions">
                <button type="button" class="button wcag-menu-item-toggle"><?php _e('Modifica', 'wcag-wp'); ?></button>
                <button type="button" class="button wcag-menu-item-remove"><?php _e('Rimuovi', 'wcag-wp'); ?></button>
            </div>
        </div>
        
        <div class="wcag-menu-item-content" style="display: none;">
            <div class="wcag-wp-field-row">
                <div class="wcag-wp-field-col">
                    <label><?php _e('Etichetta', 'wcag-wp'); ?></label>
                    <input type="text" 
                           name="wcag_menu_config[items][{{INDEX}}][label]" 
                           value="" 
                           placeholder="<?php _e('Testo del menu', 'wcag-wp'); ?>">
                </div>
                <div class="wcag-wp-field-col">
                    <label><?php _e('URL', 'wcag-wp'); ?></label>
                    <input type="url" 
                           name="wcag_menu_config[items][{{INDEX}}][url]" 
                           value="" 
                           placeholder="<?php _e('https://esempio.com', 'wcag-wp'); ?>">
                </div>
            </div>
            
            <div class="wcag-wp-field-row">
                <div class="wcag-wp-field-col">
                    <label><?php _e('Icona', 'wcag-wp'); ?></label>
                    <input type="text" 
                           name="wcag_menu_config[items][{{INDEX}}][icon]" 
                           value="" 
                           placeholder="<?php _e('dashicons-admin-home', 'wcag-wp'); ?>">
                </div>
                <div class="wcag-wp-field-col">
                    <label><?php _e('Target', 'wcag-wp'); ?></label>
                    <select name="wcag_menu_config[items][{{INDEX}}][target]">
                        <option value="_self"><?php _e('Stessa finestra', 'wcag-wp'); ?></option>
                        <option value="_blank"><?php _e('Nuova finestra', 'wcag-wp'); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="wcag-wp-field-row">
                <div class="wcag-wp-field-col">
                    <label>
                        <input type="checkbox" 
                               name="wcag_menu_config[items][{{INDEX}}][disabled]" 
                               value="1">
                        <?php _e('Disabilitato', 'wcag-wp'); ?>
                    </label>
                </div>
            </div>
            
            <!-- Sottomenu Section -->
            <div class="wcag-wp-submenu-section">
                <h4><?php _e('Sottomenu', 'wcag-wp'); ?></h4>
                <div class="wcag-wp-submenu-container" data-parent-index="{{INDEX}}">
                </div>
                
                <button type="button" class="button button-secondary wcag-add-submenu-item" data-parent-index="{{INDEX}}">
                    <?php _e('+ Aggiungi Sottoelemento', 'wcag-wp'); ?>
                </button>
            </div>
        </div>
    </div>
</script>

<!-- Submenu Item Template (Hidden) -->
<script type="text/html" id="wcag-submenu-item-template">
    <div class="wcag-wp-submenu-item" data-sub-index="{{SUB_INDEX}}">
        <div class="wcag-wp-submenu-header">
            <span class="wcag-wp-submenu-title"><?php _e('Nuovo sottoelemento', 'wcag-wp'); ?></span>
            <div class="wcag-wp-submenu-actions">
                <button type="button" class="button wcag-submenu-item-toggle"><?php _e('Modifica', 'wcag-wp'); ?></button>
                <button type="button" class="button wcag-submenu-item-remove"><?php _e('Rimuovi', 'wcag-wp'); ?></button>
            </div>
        </div>
        
        <div class="wcag-wp-submenu-content" style="display: none;">
            <div class="wcag-wp-field-row">
                <div class="wcag-wp-field-col">
                    <label><?php _e('Etichetta', 'wcag-wp'); ?></label>
                    <input type="text" 
                           name="wcag_menu_config[items][{{PARENT_INDEX}}][submenu][{{SUB_INDEX}}][label]" 
                           value="" 
                           placeholder="<?php _e('Testo del sottomenu', 'wcag-wp'); ?>">
                </div>
                <div class="wcag-wp-field-col">
                    <label><?php _e('URL', 'wcag-wp'); ?></label>
                    <input type="url" 
                           name="wcag_menu_config[items][{{PARENT_INDEX}}][submenu][{{SUB_INDEX}}][url]" 
                           value="" 
                           placeholder="<?php _e('https://esempio.com', 'wcag-wp'); ?>">
                </div>
            </div>
            
            <div class="wcag-wp-field-row">
                <div class="wcag-wp-field-col">
                    <label><?php _e('Icona', 'wcag-wp'); ?></label>
                    <input type="text" 
                           name="wcag_menu_config[items][{{PARENT_INDEX}}][submenu][{{SUB_INDEX}}][icon]" 
                           value="" 
                           placeholder="<?php _e('dashicons-admin-home', 'wcag-wp'); ?>">
                </div>
                <div class="wcag-wp-field-col">
                    <label><?php _e('Target', 'wcag-wp'); ?></label>
                    <select name="wcag_menu_config[items][{{PARENT_INDEX}}][submenu][{{SUB_INDEX}}][target]">
                        <option value="_self"><?php _e('Stessa finestra', 'wcag-wp'); ?></option>
                        <option value="_blank"><?php _e('Nuova finestra', 'wcag-wp'); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="wcag-wp-field-row">
                <div class="wcag-wp-field-col">
                    <label>
                        <input type="checkbox" 
                               name="wcag_menu_config[items][{{PARENT_INDEX}}][submenu][{{SUB_INDEX}}][disabled]" 
                               value="1">
                        <?php _e('Disabilitato', 'wcag-wp'); ?>
                    </label>
                </div>
            </div>
        </div>
    </div>
</script>
