<?php
/**
 * Template: Listbox Options Meta Box
 * 
 * @package WCAG_WP
 * @var WP_Post $post Current post object
 * @var array $options Listbox options
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-listbox-options">
    <div class="options-header">
        <h4><?php esc_html_e('Gestione Opzioni & Gruppi', 'wcag-wp'); ?></h4>
        <p class="description">
            <?php esc_html_e('Configura le opzioni del listbox. Supporta raggruppamento, separatori e selezioni predefinite.', 'wcag-wp'); ?>
        </p>
        <div class="header-actions">
            <button type="button" id="add-option-btn" class="button button-secondary">
                <span class="dashicons dashicons-plus-alt"></span>
                <?php esc_html_e('Aggiungi Opzione', 'wcag-wp'); ?>
            </button>
            <button type="button" id="add-group-btn" class="button button-secondary">
                <span class="dashicons dashicons-category"></span>
                <?php esc_html_e('Aggiungi Gruppo', 'wcag-wp'); ?>
            </button>
        </div>
    </div>
    
    <div id="options-container" class="options-container">
        <?php if (empty($options)): ?>
            <div class="no-options-message">
                <p><?php esc_html_e('Nessuna opzione configurata. Clicca "Aggiungi Opzione" per iniziare.', 'wcag-wp'); ?></p>
            </div>
        <?php else: ?>
            <?php foreach ($options as $index => $option): ?>
                <div class="option-item" data-index="<?php echo esc_attr($index); ?>">
                    
                    <!-- Separator Before -->
                    <?php if ($option['separator_before'] ?? false): ?>
                        <div class="option-separator option-separator--before">
                            <span class="separator-line"></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="option-content">
                        <div class="option-handle">
                            <span class="dashicons dashicons-menu"></span>
                        </div>
                        
                        <div class="option-fields">
                            <div class="field-row field-row--primary">
                                <div class="field-group field-group--value">
                                    <label>
                                        <?php esc_html_e('Valore:', 'wcag-wp'); ?>
                                        <input type="text" 
                                               name="wcag_listbox_options[<?php echo esc_attr($index); ?>][value]" 
                                               value="<?php echo esc_attr($option['value'] ?? ''); ?>"
                                               placeholder="<?php esc_attr_e('valore-opzione', 'wcag-wp'); ?>"
                                               class="option-value"
                                               required>
                                    </label>
                                </div>
                                
                                <div class="field-group field-group--label">
                                    <label>
                                        <?php esc_html_e('Etichetta:', 'wcag-wp'); ?>
                                        <input type="text" 
                                               name="wcag_listbox_options[<?php echo esc_attr($index); ?>][label]" 
                                               value="<?php echo esc_attr($option['label'] ?? ''); ?>"
                                               placeholder="<?php esc_attr_e('Etichetta visibile', 'wcag-wp'); ?>"
                                               class="option-label"
                                               required>
                                    </label>
                                </div>
                                
                                <div class="field-group field-group--actions">
                                    <button type="button" class="remove-option-btn button button-link-delete" title="<?php esc_attr_e('Rimuovi opzione', 'wcag-wp'); ?>">
                                        <span class="dashicons dashicons-trash"></span>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="field-row field-row--secondary">
                                <div class="field-group field-group--description">
                                    <label>
                                        <?php esc_html_e('Descrizione:', 'wcag-wp'); ?>
                                        <input type="text" 
                                               name="wcag_listbox_options[<?php echo esc_attr($index); ?>][description]" 
                                               value="<?php echo esc_attr($option['description'] ?? ''); ?>"
                                               placeholder="<?php esc_attr_e('Descrizione opzionale per screen reader', 'wcag-wp'); ?>"
                                               class="option-description">
                                    </label>
                                </div>
                                
                                <div class="field-group field-group--group">
                                    <label>
                                        <?php esc_html_e('Gruppo:', 'wcag-wp'); ?>
                                        <input type="text" 
                                               name="wcag_listbox_options[<?php echo esc_attr($index); ?>][group]" 
                                               value="<?php echo esc_attr($option['group'] ?? ''); ?>"
                                               placeholder="<?php esc_attr_e('Nome gruppo/categoria', 'wcag-wp'); ?>"
                                               class="option-group"
                                               list="available-groups">
                                    </label>
                                </div>
                            </div>
                            
                            <div class="field-row field-row--controls">
                                <div class="field-group field-group--checkboxes">
                                    <label class="checkbox-label">
                                        <input type="checkbox" 
                                               name="wcag_listbox_options[<?php echo esc_attr($index); ?>][selected]" 
                                               value="1"
                                               <?php checked($option['selected'] ?? false); ?>>
                                        <span class="checkbox-text"><?php esc_html_e('Preselezionata', 'wcag-wp'); ?></span>
                                    </label>
                                    
                                    <label class="checkbox-label">
                                        <input type="checkbox" 
                                               name="wcag_listbox_options[<?php echo esc_attr($index); ?>][disabled]" 
                                               value="1"
                                               <?php checked($option['disabled'] ?? false); ?>>
                                        <span class="checkbox-text"><?php esc_html_e('Disabilitata', 'wcag-wp'); ?></span>
                                    </label>
                                </div>
                                
                                <div class="field-group field-group--separators">
                                    <label class="checkbox-label">
                                        <input type="checkbox" 
                                               name="wcag_listbox_options[<?php echo esc_attr($index); ?>][separator_before]" 
                                               value="1"
                                               <?php checked($option['separator_before'] ?? false); ?>>
                                        <span class="checkbox-text"><?php esc_html_e('Separatore prima', 'wcag-wp'); ?></span>
                                    </label>
                                    
                                    <label class="checkbox-label">
                                        <input type="checkbox" 
                                               name="wcag_listbox_options[<?php echo esc_attr($index); ?>][separator_after]" 
                                               value="1"
                                               <?php checked($option['separator_after'] ?? false); ?>>
                                        <span class="checkbox-text"><?php esc_html_e('Separatore dopo', 'wcag-wp'); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Separator After -->
                    <?php if ($option['separator_after'] ?? false): ?>
                        <div class="option-separator option-separator--after">
                            <span class="separator-line"></span>
                        </div>
                    <?php endif; ?>
                    
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Template per nuove opzioni -->
    <script type="text/template" id="option-template">
        <div class="option-item" data-index="{{INDEX}}">
            <div class="option-content">
                <div class="option-handle">
                    <span class="dashicons dashicons-menu"></span>
                </div>
                
                <div class="option-fields">
                    <div class="field-row field-row--primary">
                        <div class="field-group field-group--value">
                            <label>
                                <?php esc_html_e('Valore:', 'wcag-wp'); ?>
                                <input type="text" 
                                       name="wcag_listbox_options[{{INDEX}}][value]" 
                                       placeholder="<?php esc_attr_e('valore-opzione', 'wcag-wp'); ?>"
                                       class="option-value"
                                       required>
                            </label>
                        </div>
                        
                        <div class="field-group field-group--label">
                            <label>
                                <?php esc_html_e('Etichetta:', 'wcag-wp'); ?>
                                <input type="text" 
                                       name="wcag_listbox_options[{{INDEX}}][label]" 
                                       placeholder="<?php esc_attr_e('Etichetta visibile', 'wcag-wp'); ?>"
                                       class="option-label"
                                       required>
                            </label>
                        </div>
                        
                        <div class="field-group field-group--actions">
                            <button type="button" class="remove-option-btn button button-link-delete" title="<?php esc_attr_e('Rimuovi opzione', 'wcag-wp'); ?>">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="field-row field-row--secondary">
                        <div class="field-group field-group--description">
                            <label>
                                <?php esc_html_e('Descrizione:', 'wcag-wp'); ?>
                                <input type="text" 
                                       name="wcag_listbox_options[{{INDEX}}][description]" 
                                       placeholder="<?php esc_attr_e('Descrizione opzionale per screen reader', 'wcag-wp'); ?>"
                                       class="option-description">
                            </label>
                        </div>
                        
                        <div class="field-group field-group--group">
                            <label>
                                <?php esc_html_e('Gruppo:', 'wcag-wp'); ?>
                                <input type="text" 
                                       name="wcag_listbox_options[{{INDEX}}][group]" 
                                       placeholder="<?php esc_attr_e('Nome gruppo/categoria', 'wcag-wp'); ?>"
                                       class="option-group"
                                       list="available-groups">
                            </label>
                        </div>
                    </div>
                    
                    <div class="field-row field-row--controls">
                        <div class="field-group field-group--checkboxes">
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       name="wcag_listbox_options[{{INDEX}}][selected]" 
                                       value="1">
                                <span class="checkbox-text"><?php esc_html_e('Preselezionata', 'wcag-wp'); ?></span>
                            </label>
                            
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       name="wcag_listbox_options[{{INDEX}}][disabled]" 
                                       value="1">
                                <span class="checkbox-text"><?php esc_html_e('Disabilitata', 'wcag-wp'); ?></span>
                            </label>
                        </div>
                        
                        <div class="field-group field-group--separators">
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       name="wcag_listbox_options[{{INDEX}}][separator_before]" 
                                       value="1">
                                <span class="checkbox-text"><?php esc_html_e('Separatore prima', 'wcag-wp'); ?></span>
                            </label>
                            
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       name="wcag_listbox_options[{{INDEX}}][separator_after]" 
                                       value="1">
                                <span class="checkbox-text"><?php esc_html_e('Separatore dopo', 'wcag-wp'); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
    
    <!-- Datalist per gruppi esistenti -->
    <datalist id="available-groups">
        <?php 
        $existing_groups = [];
        foreach ($options as $option) {
            if (!empty($option['group']) && !in_array($option['group'], $existing_groups)) {
                $existing_groups[] = $option['group'];
                echo '<option value="' . esc_attr($option['group']) . '">';
            }
        }
        ?>
    </datalist>
    
    <div class="options-footer">
        <div class="bulk-actions">
            <button type="button" id="select-all-btn" class="button">
                <span class="dashicons dashicons-yes"></span>
                <?php esc_html_e('Seleziona Tutte', 'wcag-wp'); ?>
            </button>
            
            <button type="button" id="deselect-all-btn" class="button">
                <span class="dashicons dashicons-dismiss"></span>
                <?php esc_html_e('Deseleziona Tutte', 'wcag-wp'); ?>
            </button>
            
            <button type="button" id="import-options-btn" class="button">
                <span class="dashicons dashicons-upload"></span>
                <?php esc_html_e('Importa da CSV', 'wcag-wp'); ?>
            </button>
            
            <button type="button" id="export-options-btn" class="button">
                <span class="dashicons dashicons-download"></span>
                <?php esc_html_e('Esporta CSV', 'wcag-wp'); ?>
            </button>
            
            <button type="button" id="clear-options-btn" class="button button-link-delete">
                <span class="dashicons dashicons-trash"></span>
                <?php esc_html_e('Cancella Tutto', 'wcag-wp'); ?>
            </button>
        </div>
        
        <div class="options-stats">
            <span id="options-count">
                <?php 
                $count = count($options);
                printf(
                    esc_html(_n('%d opzione configurata', '%d opzioni configurate', $count, 'wcag-wp')), 
                    $count
                );
                ?>
            </span>
            <span id="selected-count">
                <?php 
                $selected_count = count(array_filter($options, function($opt) { return $opt['selected'] ?? false; }));
                if ($selected_count > 0) {
                    printf(
                        esc_html(__('(%d preselezionate)', 'wcag-wp')), 
                        $selected_count
                    );
                }
                ?>
            </span>
        </div>
    </div>
    
    <!-- Hidden file input for CSV import -->
    <input type="file" id="csv-import-input" accept=".csv" style="display: none;">
</div>

<style>
.wcag-listbox-options {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 0;
}

.options-header {
    padding: 15px 20px;
    border-bottom: 1px solid #ddd;
    background: #f9f9f9;
}

.options-header h4 {
    margin: 0 0 5px 0;
    color: #2271b1;
}

.options-header .description {
    margin: 0 0 15px 0;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.header-actions button {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
}

.options-container {
    padding: 15px 20px;
    min-height: 100px;
}

.no-options-message {
    text-align: center;
    color: #646970;
    font-style: italic;
    padding: 40px 20px;
}

.option-item {
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fafafa;
    position: relative;
}

.option-item:hover {
    background: #f0f0f0;
}

.option-separator {
    height: 1px;
    margin: 10px 0;
    position: relative;
}

.separator-line {
    display: block;
    height: 1px;
    background: linear-gradient(to right, transparent, #ddd 20%, #ddd 80%, transparent);
}

.option-content {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 15px;
}

.option-handle {
    cursor: move;
    color: #646970;
    padding: 5px;
    display: flex;
    align-items: center;
}

.option-handle:hover {
    color: #2271b1;
}

.option-fields {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.field-row {
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.field-row--primary {
    align-items: flex-end;
}

.field-group {
    display: flex;
    flex-direction: column;
}

.field-group--value {
    flex: 2;
    min-width: 150px;
}

.field-group--label {
    flex: 3;
    min-width: 200px;
}

.field-group--description,
.field-group--group {
    flex: 1;
    min-width: 150px;
}

.field-group--actions {
    flex: 0 0 auto;
}

.field-group--checkboxes,
.field-group--separators {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.field-group label {
    font-weight: 500;
    margin-bottom: 5px;
    font-size: 13px;
    color: #1d2327;
}

.field-group input[type="text"] {
    width: 100%;
    padding: 6px 8px;
    border: 1px solid #8c8f94;
    border-radius: 3px;
    font-size: 13px;
}

.field-group input[type="text"]:focus {
    border-color: #2271b1;
    outline: 1px solid #2271b1;
    outline-offset: -1px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    margin-bottom: 0 !important;
}

.checkbox-label input[type="checkbox"] {
    margin: 0;
}

.checkbox-text {
    font-weight: normal;
}

.remove-option-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    font-size: 16px;
    text-decoration: none;
    border-radius: 3px;
    color: #b32d2e;
}

.remove-option-btn:hover {
    background: #d63638;
    color: white;
}

.options-footer {
    padding: 15px 20px;
    border-top: 1px solid #ddd;
    background: #f9f9f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
}

.bulk-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.bulk-actions button {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
}

.options-stats {
    font-size: 13px;
    color: #646970;
    font-weight: 500;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 2px;
}

/* Sortable placeholder */
.ui-sortable-placeholder {
    border: 2px dashed #2271b1;
    background: #f0f6fc;
    height: 120px;
    border-radius: 4px;
    margin-bottom: 15px;
}

/* Responsive */
@media (max-width: 1200px) {
    .field-row {
        flex-direction: column;
        gap: 10px;
    }
    
    .field-group--value,
    .field-group--label,
    .field-group--description,
    .field-group--group {
        flex: 1;
        min-width: auto;
    }
}

@media (max-width: 782px) {
    .options-header {
        padding: 10px 15px;
    }
    
    .header-actions {
        flex-direction: column;
    }
    
    .options-container {
        padding: 10px 15px;
    }
    
    .option-content {
        flex-direction: column;
        gap: 15px;
    }
    
    .option-handle {
        align-self: flex-start;
    }
    
    .field-group--checkboxes,
    .field-group--separators {
        flex-direction: row;
        flex-wrap: wrap;
    }
    
    .options-footer {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }
    
    .bulk-actions {
        justify-content: center;
    }
    
    .options-stats {
        align-items: center;
        text-align: center;
    }
}
</style>

