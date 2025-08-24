<?php
/**
 * Template: Combobox Options Meta Box
 * 
 * @package WCAG_WP
 * @var WP_Post $post Current post object
 * @var array $options Combobox options
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-combobox-options">
    <div class="options-header">
        <h4><?php esc_html_e('Gestione Opzioni Statiche', 'wcag-wp'); ?></h4>
        <p class="description">
            <?php esc_html_e('Queste opzioni sono utilizzate solo quando la sorgente dati è impostata su "Opzioni Statiche".', 'wcag-wp'); ?>
        </p>
        <button type="button" id="add-option-btn" class="button button-secondary">
            <span class="dashicons dashicons-plus-alt"></span>
            <?php esc_html_e('Aggiungi Opzione', 'wcag-wp'); ?>
        </button>
    </div>
    
    <div id="options-container" class="options-container">
        <?php if (empty($options)): ?>
            <div class="no-options-message">
                <p><?php esc_html_e('Nessuna opzione configurata. Clicca "Aggiungi Opzione" per iniziare.', 'wcag-wp'); ?></p>
            </div>
        <?php else: ?>
            <?php foreach ($options as $index => $option): ?>
                <div class="option-item" data-index="<?php echo esc_attr($index); ?>">
                    <div class="option-handle">
                        <span class="dashicons dashicons-menu"></span>
                    </div>
                    
                    <div class="option-fields">
                        <div class="field-group">
                            <label>
                                <?php esc_html_e('Valore:', 'wcag-wp'); ?>
                                <input type="text" 
                                       name="wcag_combobox_options[<?php echo esc_attr($index); ?>][value]" 
                                       value="<?php echo esc_attr($option['value'] ?? ''); ?>"
                                       placeholder="<?php esc_attr_e('valore-opzione', 'wcag-wp'); ?>"
                                       class="option-value">
                            </label>
                        </div>
                        
                        <div class="field-group">
                            <label>
                                <?php esc_html_e('Etichetta:', 'wcag-wp'); ?>
                                <input type="text" 
                                       name="wcag_combobox_options[<?php echo esc_attr($index); ?>][label]" 
                                       value="<?php echo esc_attr($option['label'] ?? ''); ?>"
                                       placeholder="<?php esc_attr_e('Etichetta visibile', 'wcag-wp'); ?>"
                                       class="option-label">
                            </label>
                        </div>
                        
                        <div class="field-group">
                            <label>
                                <?php esc_html_e('Descrizione:', 'wcag-wp'); ?>
                                <input type="text" 
                                       name="wcag_combobox_options[<?php echo esc_attr($index); ?>][description]" 
                                       value="<?php echo esc_attr($option['description'] ?? ''); ?>"
                                       placeholder="<?php esc_attr_e('Descrizione opzionale', 'wcag-wp'); ?>"
                                       class="option-description">
                            </label>
                        </div>
                        
                        <div class="field-group">
                            <label>
                                <?php esc_html_e('Gruppo:', 'wcag-wp'); ?>
                                <input type="text" 
                                       name="wcag_combobox_options[<?php echo esc_attr($index); ?>][group]" 
                                       value="<?php echo esc_attr($option['group'] ?? ''); ?>"
                                       placeholder="<?php esc_attr_e('Categoria/Gruppo', 'wcag-wp'); ?>"
                                       class="option-group">
                            </label>
                        </div>
                        
                        <div class="field-group option-controls">
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       name="wcag_combobox_options[<?php echo esc_attr($index); ?>][disabled]" 
                                       value="1"
                                       <?php checked($option['disabled'] ?? false); ?>>
                                <?php esc_html_e('Disabilitata', 'wcag-wp'); ?>
                            </label>
                            
                            <button type="button" class="remove-option-btn button button-link-delete">
                                <span class="dashicons dashicons-trash"></span>
                                <?php esc_html_e('Rimuovi', 'wcag-wp'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Template per nuove opzioni -->
    <script type="text/template" id="option-template">
        <div class="option-item" data-index="{{INDEX}}">
            <div class="option-handle">
                <span class="dashicons dashicons-menu"></span>
            </div>
            
            <div class="option-fields">
                <div class="field-group">
                    <label>
                        <?php esc_html_e('Valore:', 'wcag-wp'); ?>
                        <input type="text" 
                               name="wcag_combobox_options[{{INDEX}}][value]" 
                               placeholder="<?php esc_attr_e('valore-opzione', 'wcag-wp'); ?>"
                               class="option-value">
                    </label>
                </div>
                
                <div class="field-group">
                    <label>
                        <?php esc_html_e('Etichetta:', 'wcag-wp'); ?>
                        <input type="text" 
                               name="wcag_combobox_options[{{INDEX}}][label]" 
                               placeholder="<?php esc_attr_e('Etichetta visibile', 'wcag-wp'); ?>"
                               class="option-label">
                    </label>
                </div>
                
                <div class="field-group">
                    <label>
                        <?php esc_html_e('Descrizione:', 'wcag-wp'); ?>
                        <input type="text" 
                               name="wcag_combobox_options[{{INDEX}}][description]" 
                               placeholder="<?php esc_attr_e('Descrizione opzionale', 'wcag-wp'); ?>"
                               class="option-description">
                    </label>
                </div>
                
                <div class="field-group">
                    <label>
                        <?php esc_html_e('Gruppo:', 'wcag-wp'); ?>
                        <input type="text" 
                               name="wcag_combobox_options[{{INDEX}}][group]" 
                               placeholder="<?php esc_attr_e('Categoria/Gruppo', 'wcag-wp'); ?>"
                               class="option-group">
                    </label>
                </div>
                
                <div class="field-group option-controls">
                    <label class="checkbox-label">
                        <input type="checkbox" 
                               name="wcag_combobox_options[{{INDEX}}][disabled]" 
                               value="1">
                        <?php esc_html_e('Disabilitata', 'wcag-wp'); ?>
                    </label>
                    
                    <button type="button" class="remove-option-btn button button-link-delete">
                        <span class="dashicons dashicons-trash"></span>
                        <?php esc_html_e('Rimuovi', 'wcag-wp'); ?>
                    </button>
                </div>
            </div>
        </div>
    </script>
    
    <div class="options-footer">
        <div class="bulk-actions">
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
        </div>
    </div>
    
    <!-- Hidden file input for CSV import -->
    <input type="file" id="csv-import-input" accept=".csv" style="display: none;">
</div>

<style>
.wcag-combobox-options {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 0;
}

.options-header {
    padding: 15px 20px;
    border-bottom: 1px solid #ddd;
    background: #f9f9f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}

.options-header h4 {
    margin: 0;
    color: #2271b1;
}

.options-header .description {
    margin: 5px 0 0 0;
    flex-basis: 100%;
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
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 15px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fafafa;
    position: relative;
}

.option-item:hover {
    background: #f0f0f0;
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
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    align-items: start;
}

.field-group {
    display: flex;
    flex-direction: column;
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

.option-controls {
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
}

.checkbox-label input[type="checkbox"] {
    margin: 0;
}

.remove-option-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 3px;
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
    gap: 10px;
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
}

/* Sortable placeholder */
.ui-sortable-placeholder {
    border: 2px dashed #2271b1;
    background: #f0f6fc;
    height: 80px;
    border-radius: 4px;
    margin-bottom: 10px;
}

/* Responsive */
@media (max-width: 782px) {
    .options-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .option-fields {
        grid-template-columns: 1fr;
    }
    
    .options-footer {
        flex-direction: column;
        align-items: stretch;
    }
    
    .bulk-actions {
        justify-content: center;
    }
}
</style>

