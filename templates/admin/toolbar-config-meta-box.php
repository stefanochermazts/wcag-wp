<?php
/**
 * WCAG Toolbar Configuration Meta Box
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-toolbar-config">
    
    <!-- Basic Configuration -->
    <div class="wcag-wp-config-section">
        <h3><?php _e('Configurazione Base', 'wcag-wp'); ?></h3>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="wcag_toolbar_orientation"><?php _e('Orientamento', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <select name="wcag_toolbar_config[orientation]" id="wcag_toolbar_orientation">
                        <option value="horizontal" <?php selected($config['orientation'], 'horizontal'); ?>>
                            <?php _e('Orizzontale', 'wcag-wp'); ?>
                        </option>
                        <option value="vertical" <?php selected($config['orientation'], 'vertical'); ?>>
                            <?php _e('Verticale', 'wcag-wp'); ?>
                        </option>
                    </select>
                    <p class="description">
                        <?php _e('Orientamento della toolbar e dei suoi controlli.', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="wcag_toolbar_label"><?php _e('Etichetta Toolbar', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="text" 
                           name="wcag_toolbar_config[label]" 
                           id="wcag_toolbar_label"
                           value="<?php echo esc_attr($config['label']); ?>"
                           class="regular-text"
                           placeholder="<?php _e('Es: Controlli documento', 'wcag-wp'); ?>">
                    <p class="description">
                        <?php _e('Etichetta descrittiva per la toolbar (opzionale).', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="wcag_toolbar_aria_label"><?php _e('ARIA Label', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="text" 
                           name="wcag_toolbar_config[aria_label]" 
                           id="wcag_toolbar_aria_label"
                           value="<?php echo esc_attr($config['aria_label']); ?>"
                           class="regular-text"
                           placeholder="<?php _e('Es: Barra strumenti per la formattazione', 'wcag-wp'); ?>">
                    <p class="description">
                        <?php _e('Etichetta ARIA per screen reader (opzionale).', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="wcag_toolbar_custom_class"><?php _e('Classe CSS Personalizzata', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="text" 
                           name="wcag_toolbar_config[custom_class]" 
                           id="wcag_toolbar_custom_class"
                           value="<?php echo esc_attr($config['custom_class']); ?>"
                           class="regular-text"
                           placeholder="<?php _e('Es: my-toolbar', 'wcag-wp'); ?>">
                    <p class="description">
                        <?php _e('Classe CSS aggiuntiva per personalizzazione (opzionale).', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Toolbar Groups -->
    <div class="wcag-wp-config-section">
        <h3><?php _e('Gruppi di Controlli', 'wcag-wp'); ?></h3>
        <p class="description">
            <?php _e('Organizza i controlli in gruppi logici per una migliore usabilità.', 'wcag-wp'); ?>
        </p>
        
        <div id="wcag-toolbar-groups" class="wcag-wp-groups-container">
            <?php if (!empty($config['groups'])): ?>
                <?php foreach ($config['groups'] as $group_index => $group): ?>
                    <div class="wcag-wp-group" data-group-index="<?php echo esc_attr($group_index); ?>">
                        <div class="wcag-wp-group-header">
                            <h4><?php _e('Gruppo', 'wcag-wp'); ?> #<?php echo esc_html($group_index + 1); ?></h4>
                            <div class="wcag-wp-group-actions">
                                <button type="button" class="button wcag-wp-toggle-group" aria-expanded="true">
                                    <?php _e('Comprimi', 'wcag-wp'); ?>
                                </button>
                                <button type="button" class="button wcag-wp-remove-group">
                                    <?php _e('Rimuovi Gruppo', 'wcag-wp'); ?>
                                </button>
                            </div>
                        </div>
                        
                        <div class="wcag-wp-group-content">
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="wcag_toolbar_group_label_<?php echo esc_attr($group_index); ?>">
                                            <?php _e('Etichetta Gruppo', 'wcag-wp'); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <input type="text" 
                                               name="wcag_toolbar_config[groups][<?php echo esc_attr($group_index); ?>][label]"
                                               id="wcag_toolbar_group_label_<?php echo esc_attr($group_index); ?>"
                                               value="<?php echo esc_attr($group['label']); ?>"
                                               class="regular-text"
                                               placeholder="<?php _e('Es: Formattazione testo', 'wcag-wp'); ?>">
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Controls in this group -->
                            <div class="wcag-wp-controls-container">
                                <h5><?php _e('Controlli', 'wcag-wp'); ?></h5>
                                
                                <div class="wcag-wp-controls" data-group-index="<?php echo esc_attr($group_index); ?>">
                                    <?php if (!empty($group['controls'])): ?>
                                        <?php foreach ($group['controls'] as $control_index => $control): ?>
                                            <div class="wcag-wp-control" data-control-index="<?php echo esc_attr($control_index); ?>">
                                                <div class="wcag-wp-control-header">
                                                    <span class="wcag-wp-control-title">
                                                        <?php echo esc_html($control['label'] ?: __('Controllo', 'wcag-wp')); ?>
                                                    </span>
                                                    <div class="wcag-wp-control-actions">
                                                        <button type="button" class="button wcag-wp-toggle-control" aria-expanded="true">
                                                            <?php _e('Comprimi', 'wcag-wp'); ?>
                                                        </button>
                                                        <button type="button" class="button wcag-wp-remove-control">
                                                            <?php _e('Rimuovi', 'wcag-wp'); ?>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <div class="wcag-wp-control-content">
                                                    <table class="form-table">
                                                        <tr>
                                                            <th scope="row">
                                                                <label for="wcag_toolbar_control_type_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>">
                                                                    <?php _e('Tipo Controllo', 'wcag-wp'); ?>
                                                                </label>
                                                            </th>
                                                            <td>
                                                                <select name="wcag_toolbar_config[groups][<?php echo esc_attr($group_index); ?>][controls][<?php echo esc_attr($control_index); ?>][type]"
                                                                        id="wcag_toolbar_control_type_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>"
                                                                        class="wcag-wp-control-type">
                                                                    <option value="button" <?php selected($control['type'], 'button'); ?>>
                                                                        <?php _e('Pulsante', 'wcag-wp'); ?>
                                                                    </option>
                                                                    <option value="link" <?php selected($control['type'], 'link'); ?>>
                                                                        <?php _e('Link', 'wcag-wp'); ?>
                                                                    </option>
                                                                    <option value="separator" <?php selected($control['type'], 'separator'); ?>>
                                                                        <?php _e('Separatore', 'wcag-wp'); ?>
                                                                    </option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr class="wcag-wp-control-field" data-field="label">
                                                            <th scope="row">
                                                                <label for="wcag_toolbar_control_label_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>">
                                                                    <?php _e('Etichetta', 'wcag-wp'); ?>
                                                                </label>
                                                            </th>
                                                            <td>
                                                                <input type="text" 
                                                                       name="wcag_toolbar_config[groups][<?php echo esc_attr($group_index); ?>][controls][<?php echo esc_attr($control_index); ?>][label]"
                                                                       id="wcag_toolbar_control_label_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>"
                                                                       value="<?php echo esc_attr($control['label']); ?>"
                                                                       class="regular-text"
                                                                       placeholder="<?php _e('Es: Grassetto', 'wcag-wp'); ?>">
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr class="wcag-wp-control-field" data-field="icon">
                                                            <th scope="row">
                                                                <label for="wcag_toolbar_control_icon_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>">
                                                                    <?php _e('Icona', 'wcag-wp'); ?>
                                                                </label>
                                                            </th>
                                                            <td>
                                                                <input type="text" 
                                                                       name="wcag_toolbar_config[groups][<?php echo esc_attr($group_index); ?>][controls][<?php echo esc_attr($control_index); ?>][icon]"
                                                                       id="wcag_toolbar_control_icon_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>"
                                                                       value="<?php echo esc_attr($control['icon']); ?>"
                                                                       class="regular-text"
                                                                       placeholder="<?php _e('Es: dashicons-editor-bold', 'wcag-wp'); ?>">
                                                                <p class="description">
                                                                    <?php _e('Classe CSS dell\'icona (opzionale).', 'wcag-wp'); ?>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr class="wcag-wp-control-field" data-field="action">
                                                            <th scope="row">
                                                                <label for="wcag_toolbar_control_action_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>">
                                                                    <?php _e('Azione', 'wcag-wp'); ?>
                                                                </label>
                                                            </th>
                                                            <td>
                                                                <input type="text" 
                                                                       name="wcag_toolbar_config[groups][<?php echo esc_attr($group_index); ?>][controls][<?php echo esc_attr($control_index); ?>][action]"
                                                                       id="wcag_toolbar_control_action_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>"
                                                                       value="<?php echo esc_attr($control['action']); ?>"
                                                                       class="regular-text"
                                                                       placeholder="<?php _e('Es: formatBold', 'wcag-wp'); ?>">
                                                                <p class="description">
                                                                    <?php _e('Nome dell\'azione JavaScript (per pulsanti).', 'wcag-wp'); ?>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr class="wcag-wp-control-field" data-field="url">
                                                            <th scope="row">
                                                                <label for="wcag_toolbar_control_url_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>">
                                                                    <?php _e('URL', 'wcag-wp'); ?>
                                                                </label>
                                                            </th>
                                                            <td>
                                                                <input type="url" 
                                                                       name="wcag_toolbar_config[groups][<?php echo esc_attr($group_index); ?>][controls][<?php echo esc_attr($control_index); ?>][url]"
                                                                       id="wcag_toolbar_control_url_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>"
                                                                       value="<?php echo esc_attr($control['url']); ?>"
                                                                       class="regular-text"
                                                                       placeholder="<?php _e('https://example.com', 'wcag-wp'); ?>">
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr class="wcag-wp-control-field" data-field="target">
                                                            <th scope="row">
                                                                <label for="wcag_toolbar_control_target_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>">
                                                                    <?php _e('Target', 'wcag-wp'); ?>
                                                                </label>
                                                            </th>
                                                            <td>
                                                                <select name="wcag_toolbar_config[groups][<?php echo esc_attr($group_index); ?>][controls][<?php echo esc_attr($control_index); ?>][target]"
                                                                        id="wcag_toolbar_control_target_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>">
                                                                    <option value="_self" <?php selected($control['target'], '_self'); ?>>
                                                                        <?php _e('Stessa finestra', 'wcag-wp'); ?>
                                                                    </option>
                                                                    <option value="_blank" <?php selected($control['target'], '_blank'); ?>>
                                                                        <?php _e('Nuova finestra', 'wcag-wp'); ?>
                                                                    </option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <th scope="row">
                                                                <label for="wcag_toolbar_control_disabled_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>">
                                                                    <?php _e('Disabilitato', 'wcag-wp'); ?>
                                                                </label>
                                                            </th>
                                                            <td>
                                                                <input type="checkbox" 
                                                                       name="wcag_toolbar_config[groups][<?php echo esc_attr($group_index); ?>][controls][<?php echo esc_attr($control_index); ?>][disabled]"
                                                                       id="wcag_toolbar_control_disabled_<?php echo esc_attr($group_index); ?>_<?php echo esc_attr($control_index); ?>"
                                                                       value="1"
                                                                       <?php checked(!empty($control['disabled'])); ?>>
                                                                <span class="description">
                                                                    <?php _e('Rendi questo controllo disabilitato.', 'wcag-wp'); ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                
                                <button type="button" class="button wcag-wp-add-control" data-group-index="<?php echo esc_attr($group_index); ?>">
                                    <?php _e('Aggiungi Controllo', 'wcag-wp'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <button type="button" class="button button-primary wcag-wp-add-group">
            <?php _e('Aggiungi Gruppo', 'wcag-wp'); ?>
        </button>
    </div>
</div>

<!-- Templates for dynamic content -->
<script type="text/html" id="wcag-toolbar-group-template">
    <div class="wcag-wp-group" data-group-index="{{groupIndex}}">
        <div class="wcag-wp-group-header">
            <h4><?php _e('Gruppo', 'wcag-wp'); ?> #{{groupNumber}}</h4>
            <div class="wcag-wp-group-actions">
                <button type="button" class="button wcag-wp-toggle-group" aria-expanded="true">
                    <?php _e('Comprimi', 'wcag-wp'); ?>
                </button>
                <button type="button" class="button wcag-wp-remove-group">
                    <?php _e('Rimuovi Gruppo', 'wcag-wp'); ?>
                </button>
            </div>
        </div>
        
        <div class="wcag-wp-group-content">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="wcag_toolbar_group_label_{{groupIndex}}">
                            <?php _e('Etichetta Gruppo', 'wcag-wp'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" 
                               name="wcag_toolbar_config[groups][{{groupIndex}}][label]"
                               id="wcag_toolbar_group_label_{{groupIndex}}"
                               class="regular-text"
                               placeholder="<?php _e('Es: Formattazione testo', 'wcag-wp'); ?>">
                    </td>
                </tr>
            </table>
            
            <div class="wcag-wp-controls-container">
                <h5><?php _e('Controlli', 'wcag-wp'); ?></h5>
                <div class="wcag-wp-controls" data-group-index="{{groupIndex}}"></div>
                <button type="button" class="button wcag-wp-add-control" data-group-index="{{groupIndex}}">
                    <?php _e('Aggiungi Controllo', 'wcag-wp'); ?>
                </button>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="wcag-toolbar-control-template">
    <div class="wcag-wp-control" data-control-index="{{controlIndex}}">
        <div class="wcag-wp-control-header">
            <span class="wcag-wp-control-title">
                <?php _e('Controllo', 'wcag-wp'); ?>
            </span>
            <div class="wcag-wp-control-actions">
                <button type="button" class="button wcag-wp-toggle-control" aria-expanded="true">
                    <?php _e('Comprimi', 'wcag-wp'); ?>
                </button>
                <button type="button" class="button wcag-wp-remove-control">
                    <?php _e('Rimuovi', 'wcag-wp'); ?>
                </button>
            </div>
        </div>
        
        <div class="wcag-wp-control-content">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="wcag_toolbar_control_type_{{groupIndex}}_{{controlIndex}}">
                            <?php _e('Tipo Controllo', 'wcag-wp'); ?>
                        </label>
                    </th>
                    <td>
                        <select name="wcag_toolbar_config[groups][{{groupIndex}}][controls][{{controlIndex}}][type]"
                                id="wcag_toolbar_control_type_{{groupIndex}}_{{controlIndex}}"
                                class="wcag-wp-control-type">
                            <option value="button"><?php _e('Pulsante', 'wcag-wp'); ?></option>
                            <option value="link"><?php _e('Link', 'wcag-wp'); ?></option>
                            <option value="separator"><?php _e('Separatore', 'wcag-wp'); ?></option>
                        </select>
                    </td>
                </tr>
                
                <tr class="wcag-wp-control-field" data-field="label">
                    <th scope="row">
                        <label for="wcag_toolbar_control_label_{{groupIndex}}_{{controlIndex}}">
                            <?php _e('Etichetta', 'wcag-wp'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" 
                               name="wcag_toolbar_config[groups][{{groupIndex}}][controls][{{controlIndex}}][label]"
                               id="wcag_toolbar_control_label_{{groupIndex}}_{{controlIndex}}"
                               class="regular-text"
                               placeholder="<?php _e('Es: Grassetto', 'wcag-wp'); ?>">
                    </td>
                </tr>
                
                <tr class="wcag-wp-control-field" data-field="icon">
                    <th scope="row">
                        <label for="wcag_toolbar_control_icon_{{groupIndex}}_{{controlIndex}}">
                            <?php _e('Icona', 'wcag-wp'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" 
                               name="wcag_toolbar_config[groups][{{groupIndex}}][controls][{{controlIndex}}][icon]"
                               id="wcag_toolbar_control_icon_{{groupIndex}}_{{controlIndex}}"
                               class="regular-text"
                               placeholder="<?php _e('Es: dashicons-editor-bold', 'wcag-wp'); ?>">
                        <p class="description">
                            <?php _e('Classe CSS dell\'icona (opzionale).', 'wcag-wp'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr class="wcag-wp-control-field" data-field="action">
                    <th scope="row">
                        <label for="wcag_toolbar_control_action_{{groupIndex}}_{{controlIndex}}">
                            <?php _e('Azione', 'wcag-wp'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" 
                               name="wcag_toolbar_config[groups][{{groupIndex}}][controls][{{controlIndex}}][action]"
                               id="wcag_toolbar_control_action_{{groupIndex}}_{{controlIndex}}"
                               class="regular-text"
                               placeholder="<?php _e('Es: formatBold', 'wcag-wp'); ?>">
                        <p class="description">
                            <?php _e('Nome dell\'azione JavaScript (per pulsanti).', 'wcag-wp'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr class="wcag-wp-control-field" data-field="url">
                    <th scope="row">
                        <label for="wcag_toolbar_control_url_{{groupIndex}}_{{controlIndex}}">
                            <?php _e('URL', 'wcag-wp'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="url" 
                               name="wcag_toolbar_config[groups][{{groupIndex}}][controls][{{controlIndex}}][url]"
                               id="wcag_toolbar_control_url_{{groupIndex}}_{{controlIndex}}"
                               class="regular-text"
                               placeholder="<?php _e('https://example.com', 'wcag-wp'); ?>">
                    </td>
                </tr>
                
                <tr class="wcag-wp-control-field" data-field="target">
                    <th scope="row">
                        <label for="wcag_toolbar_control_target_{{groupIndex}}_{{controlIndex}}">
                            <?php _e('Target', 'wcag-wp'); ?>
                        </label>
                    </th>
                    <td>
                        <select name="wcag_toolbar_config[groups][{{groupIndex}}][controls][{{controlIndex}}][target]"
                                id="wcag_toolbar_control_target_{{groupIndex}}_{{controlIndex}}">
                            <option value="_self"><?php _e('Stessa finestra', 'wcag-wp'); ?></option>
                            <option value="_blank"><?php _e('Nuova finestra', 'wcag-wp'); ?></option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="wcag_toolbar_control_disabled_{{groupIndex}}_{{controlIndex}}">
                            <?php _e('Disabilitato', 'wcag-wp'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" 
                               name="wcag_toolbar_config[groups][{{groupIndex}}][controls][{{controlIndex}}][disabled]"
                               id="wcag_toolbar_control_disabled_{{groupIndex}}_{{controlIndex}}"
                               value="1">
                        <span class="description">
                            <?php _e('Rendi questo controllo disabilitato.', 'wcag-wp'); ?>
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</script>
