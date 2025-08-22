<?php
declare(strict_types=1);

/**
 * WCAG Table Columns Meta Box Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 * 
 * @var WP_Post $post Current post object
 * @var array $columns Table columns configuration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-table-columns">
    <div class="columns-header">
        <p class="description">
            <?php esc_html_e('Definisci le colonne della WCAG tabella specificando tipo di dato, etichetta e proprietà di accessibilità.', 'wcag-wp'); ?>
        </p>
        <button type="button" class="button button-primary" id="add-new-column">
            <span class="dashicons dashicons-plus-alt2"></span>
            <?php esc_html_e('Aggiungi Colonna WCAG', 'wcag-wp'); ?>
        </button>
    </div>
    
    <div class="columns-container" id="columns-container">
        <?php if (!empty($columns)): ?>
            <?php foreach ($columns as $index => $column): ?>
                <div class="column-editor" data-index="<?php echo esc_attr($index); ?>">
                    <div class="column-header">
                        <div class="column-drag-handle">
                            <span class="dashicons dashicons-menu"></span>
                        </div>
                        <div class="column-title">
                            <h4>
                                <span class="column-label"><?php echo esc_html($column['label'] ?: __('Nuova Colonna WCAG', 'wcag-wp')); ?></span>
                                <span class="column-type-badge"><?php echo esc_html(ucfirst($column['type'])); ?></span>
                            </h4>
                        </div>
                        <div class="column-actions">
                            <button type="button" class="button-link column-toggle" aria-expanded="true">
                                <span class="dashicons dashicons-arrow-up-alt2"></span>
                            </button>
                            <button type="button" class="button-link column-delete">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="column-content">
                        <div class="field-row">
                            <div class="field-group">
                                <label for="column_id_<?php echo esc_attr($index); ?>">
                                    <?php esc_html_e('ID Colonna WCAG', 'wcag-wp'); ?> <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       id="column_id_<?php echo esc_attr($index); ?>"
                                       name="wcag_wp_table_columns[<?php echo esc_attr($index); ?>][id]" 
                                       value="<?php echo esc_attr($column['id']); ?>"
                                       class="column-id-input"
                                       required>
                            </div>
                            
                            <div class="field-group">
                                <label for="column_label_<?php echo esc_attr($index); ?>">
                                    <?php esc_html_e('Etichetta Colonna WCAG', 'wcag-wp'); ?> <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       id="column_label_<?php echo esc_attr($index); ?>"
                                       name="wcag_wp_table_columns[<?php echo esc_attr($index); ?>][label]" 
                                       value="<?php echo esc_attr($column['label']); ?>"
                                       class="column-label-input"
                                       required>
                            </div>
                        </div>
                        
                        <div class="field-row">
                            <div class="field-group">
                                <label for="column_type_<?php echo esc_attr($index); ?>">
                                    <?php esc_html_e('Tipo di Dato WCAG', 'wcag-wp'); ?>
                                </label>
                                <select id="column_type_<?php echo esc_attr($index); ?>"
                                        name="wcag_wp_table_columns[<?php echo esc_attr($index); ?>][type]" 
                                        class="column-type-select">
                                    <option value="text" <?php selected($column['type'], 'text'); ?>><?php esc_html_e('Testo', 'wcag-wp'); ?></option>
                                    <option value="number" <?php selected($column['type'], 'number'); ?>><?php esc_html_e('Numero', 'wcag-wp'); ?></option>
                                    <option value="currency" <?php selected($column['type'], 'currency'); ?>><?php esc_html_e('Valuta', 'wcag-wp'); ?></option>
                                    <option value="email" <?php selected($column['type'], 'email'); ?>><?php esc_html_e('Email', 'wcag-wp'); ?></option>
                                    <option value="url" <?php selected($column['type'], 'url'); ?>><?php esc_html_e('URL', 'wcag-wp'); ?></option>
                                    <option value="boolean" <?php selected($column['type'], 'boolean'); ?>><?php esc_html_e('Sì/No', 'wcag-wp'); ?></option>
                                </select>
                            </div>
                            
                            <div class="field-group">
                                <label for="column_align_<?php echo esc_attr($index); ?>">
                                    <?php esc_html_e('Allineamento', 'wcag-wp'); ?>
                                </label>
                                <select id="column_align_<?php echo esc_attr($index); ?>"
                                        name="wcag_wp_table_columns[<?php echo esc_attr($index); ?>][align]">
                                    <option value="left" <?php selected($column['align'], 'left'); ?>><?php esc_html_e('Sinistra', 'wcag-wp'); ?></option>
                                    <option value="center" <?php selected($column['align'], 'center'); ?>><?php esc_html_e('Centro', 'wcag-wp'); ?></option>
                                    <option value="right" <?php selected($column['align'], 'right'); ?>><?php esc_html_e('Destra', 'wcag-wp'); ?></option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="field-row">
                            <div class="field-group">
                                <fieldset>
                                    <legend><?php esc_html_e('Opzioni Colonna WCAG', 'wcag-wp'); ?></legend>
                                    <label>
                                        <input type="checkbox" 
                                               name="wcag_wp_table_columns[<?php echo esc_attr($index); ?>][sortable]" 
                                               value="1" 
                                               <?php checked($column['sortable']); ?>>
                                        <?php esc_html_e('Ordinabile', 'wcag-wp'); ?>
                                    </label>
                                    <label>
                                        <input type="checkbox" 
                                               name="wcag_wp_table_columns[<?php echo esc_attr($index); ?>][required]" 
                                               value="1" 
                                               <?php checked($column['required']); ?>>
                                        <?php esc_html_e('Campo obbligatorio', 'wcag-wp'); ?>
                                    </label>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-columns-message">
                <div class="no-columns-content">
                    <span class="dashicons dashicons-grid-view"></span>
                    <h4><?php esc_html_e('Nessuna colonna WCAG definita', 'wcag-wp'); ?></h4>
                    <p><?php esc_html_e('Clicca "Aggiungi Colonna WCAG" per iniziare a creare la tua tabella accessibile.', 'wcag-wp'); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Column Template (hidden) -->
    <div id="column-template" style="display: none;">
        <div class="column-editor" data-index="{{INDEX}}">
            <div class="column-header">
                <div class="column-drag-handle">
                    <span class="dashicons dashicons-menu"></span>
                </div>
                <div class="column-title">
                    <h4>
                        <span class="column-label"><?php esc_html_e('Nuova Colonna WCAG', 'wcag-wp'); ?></span>
                        <span class="column-type-badge">Text</span>
                    </h4>
                </div>
                <div class="column-actions">
                    <button type="button" class="button-link column-toggle" aria-expanded="true">
                        <span class="dashicons dashicons-arrow-up-alt2"></span>
                    </button>
                    <button type="button" class="button-link column-delete">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                </div>
            </div>
            
            <div class="column-content">
                <div class="field-row">
                    <div class="field-group">
                        <label for="column_id_{{INDEX}}">
                            <?php esc_html_e('ID Colonna WCAG', 'wcag-wp'); ?> <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="column_id_{{INDEX}}"
                               name="wcag_wp_table_columns[{{INDEX}}][id]" 
                               class="column-id-input"
                               data-required="1" disabled>
                    </div>
                    
                    <div class="field-group">
                        <label for="column_label_{{INDEX}}">
                            <?php esc_html_e('Etichetta Colonna WCAG', 'wcag-wp'); ?> <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="column_label_{{INDEX}}"
                               name="wcag_wp_table_columns[{{INDEX}}][label]" 
                               class="column-label-input"
                               data-required="1" disabled>
                    </div>
                </div>
                
                <div class="field-row">
                    <div class="field-group">
                        <label for="column_type_{{INDEX}}">
                            <?php esc_html_e('Tipo di Dato WCAG', 'wcag-wp'); ?>
                        </label>
                        <select id="column_type_{{INDEX}}"
                                name="wcag_wp_table_columns[{{INDEX}}][type]" 
                                class="column-type-select">
                            <option value="text"><?php esc_html_e('Testo', 'wcag-wp'); ?></option>
                            <option value="number"><?php esc_html_e('Numero', 'wcag-wp'); ?></option>
                            <option value="currency"><?php esc_html_e('Valuta', 'wcag-wp'); ?></option>
                            <option value="email"><?php esc_html_e('Email', 'wcag-wp'); ?></option>
                            <option value="url"><?php esc_html_e('URL', 'wcag-wp'); ?></option>
                            <option value="boolean"><?php esc_html_e('Sì/No', 'wcag-wp'); ?></option>
                        </select>
                    </div>
                    
                    <div class="field-group">
                        <label for="column_align_{{INDEX}}">
                            <?php esc_html_e('Allineamento', 'wcag-wp'); ?>
                        </label>
                        <select id="column_align_{{INDEX}}"
                                name="wcag_wp_table_columns[{{INDEX}}][align]">
                            <option value="left"><?php esc_html_e('Sinistra', 'wcag-wp'); ?></option>
                            <option value="center"><?php esc_html_e('Centro', 'wcag-wp'); ?></option>
                            <option value="right"><?php esc_html_e('Destra', 'wcag-wp'); ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="field-row">
                    <div class="field-group">
                        <fieldset>
                            <legend><?php esc_html_e('Opzioni Colonna WCAG', 'wcag-wp'); ?></legend>
                            <label>
                                <input type="checkbox" 
                                       name="wcag_wp_table_columns[{{INDEX}}][sortable]" 
                                       value="1" 
                                       checked>
                                <?php esc_html_e('Ordinabile', 'wcag-wp'); ?>
                            </label>
                            <label>
                                <input type="checkbox" 
                                       name="wcag_wp_table_columns[{{INDEX}}][required]" 
                                       value="1">
                                <?php esc_html_e('Campo obbligatorio', 'wcag-wp'); ?>
                            </label>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>