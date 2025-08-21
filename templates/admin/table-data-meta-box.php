<?php
declare(strict_types=1);

/**
 * WCAG Table Data Meta Box Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 * 
 * @var WP_Post $post Current post object
 * @var array $data Table data rows
 * @var array $columns Table columns configuration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-table-data">
    <?php if (empty($columns)): ?>
        <div class="no-columns-notice">
            <div class="notice notice-warning inline">
                <p>
                    <strong><?php esc_html_e('Attenzione:', 'wcag-wp'); ?></strong>
                    <?php esc_html_e('Prima di inserire i dati, devi definire almeno una colonna WCAG nella sezione "Definizione Colonne" sopra.', 'wcag-wp'); ?>
                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="data-header">
            <p class="description">
                <?php esc_html_e('Inserisci i dati della WCAG tabella riga per riga. Puoi riordinare le righe trascinandole.', 'wcag-wp'); ?>
            </p>
            <div class="data-actions">
                <button type="button" class="button button-primary" id="add-new-row">
                    <span class="dashicons dashicons-plus-alt2"></span>
                    <?php esc_html_e('Aggiungi Riga WCAG', 'wcag-wp'); ?>
                </button>
            </div>
        </div>
        
        <div class="table-data-container">
            <div class="table-scroll-wrapper">
                <table class="data-table" id="data-table">
                    <thead>
                        <tr>
                            <th class="row-handle-header" scope="col">
                                <span class="dashicons dashicons-menu"></span>
                                <span class="screen-reader-text"><?php esc_html_e('Riordina righe WCAG', 'wcag-wp'); ?></span>
                            </th>
                            <?php foreach ($columns as $column): ?>
                                <th scope="col" class="column-header" data-column="<?php echo esc_attr($column['id']); ?>">
                                    <div class="column-header-content">
                                        <span class="column-label"><?php echo esc_html($column['label']); ?></span>
                                        <?php if ($column['required']): ?>
                                            <span class="required-indicator" title="<?php esc_attr_e('Campo obbligatorio', 'wcag-wp'); ?>">*</span>
                                        <?php endif; ?>
                                        <span class="column-type">(<?php echo esc_html($column['type']); ?>)</span>
                                    </div>
                                </th>
                            <?php endforeach; ?>
                            <th class="row-actions-header" scope="col">
                                <?php esc_html_e('Azioni', 'wcag-wp'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="data-table-body">
                        <?php if (!empty($data)): ?>
                            <?php foreach ($data as $row_index => $row): ?>
                                <tr class="data-row" data-index="<?php echo esc_attr($row_index); ?>">
                                    <td class="row-handle">
                                        <span class="dashicons dashicons-menu"></span>
                                        <span class="screen-reader-text"><?php esc_html_e('Trascina per riordinare', 'wcag-wp'); ?></span>
                                    </td>
                                    <?php foreach ($columns as $column): ?>
                                        <td class="data-cell" data-column="<?php echo esc_attr($column['id']); ?>">
                                            <?php
                                            $input_name = "wcag_wp_table_data[{$row_index}][{$column['id']}]";
                                            $input_id = "data_{$row_index}_{$column['id']}";
                                            $value = $row[$column['id']] ?? '';
                                            
                                            switch ($column['type']) {
                                                case 'number':
                                                case 'currency':
                                                    ?>
                                                    <input type="number" 
                                                           id="<?php echo esc_attr($input_id); ?>"
                                                           name="<?php echo esc_attr($input_name); ?>" 
                                                           value="<?php echo esc_attr($value); ?>"
                                                           <?php echo $column['required'] ? 'required' : ''; ?>
                                                           step="any"
                                                           class="data-input number-input">
                                                    <?php
                                                    break;
                                                    
                                                case 'email':
                                                    ?>
                                                    <input type="email" 
                                                           id="<?php echo esc_attr($input_id); ?>"
                                                           name="<?php echo esc_attr($input_name); ?>" 
                                                           value="<?php echo esc_attr($value); ?>"
                                                           <?php echo $column['required'] ? 'required' : ''; ?>
                                                           class="data-input email-input">
                                                    <?php
                                                    break;
                                                    
                                                case 'url':
                                                    ?>
                                                    <input type="url" 
                                                           id="<?php echo esc_attr($input_id); ?>"
                                                           name="<?php echo esc_attr($input_name); ?>" 
                                                           value="<?php echo esc_attr($value); ?>"
                                                           <?php echo $column['required'] ? 'required' : ''; ?>
                                                           class="data-input url-input">
                                                    <?php
                                                    break;
                                                    
                                                case 'boolean':
                                                    ?>
                                                    <select id="<?php echo esc_attr($input_id); ?>"
                                                            name="<?php echo esc_attr($input_name); ?>" 
                                                            <?php echo $column['required'] ? 'required' : ''; ?>
                                                            class="data-input boolean-input">
                                                        <option value=""><?php esc_html_e('Seleziona...', 'wcag-wp'); ?></option>
                                                        <option value="1" <?php selected($value, '1'); ?>><?php esc_html_e('Sì', 'wcag-wp'); ?></option>
                                                        <option value="0" <?php selected($value, '0'); ?>><?php esc_html_e('No', 'wcag-wp'); ?></option>
                                                    </select>
                                                    <?php
                                                    break;
                                                    
                                                default: // text
                                                    ?>
                                                    <input type="text" 
                                                           id="<?php echo esc_attr($input_id); ?>"
                                                           name="<?php echo esc_attr($input_name); ?>" 
                                                           value="<?php echo esc_attr($value); ?>"
                                                           <?php echo $column['required'] ? 'required' : ''; ?>
                                                           class="data-input text-input">
                                                    <?php
                                                    break;
                                            }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="row-actions">
                                        <button type="button" class="button-link row-duplicate" title="<?php esc_attr_e('Duplica riga', 'wcag-wp'); ?>">
                                            <span class="dashicons dashicons-admin-page"></span>
                                        </button>
                                        <button type="button" class="button-link row-delete" title="<?php esc_attr_e('Elimina riga', 'wcag-wp'); ?>">
                                            <span class="dashicons dashicons-trash"></span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="no-data-row">
                                <td colspan="<?php echo count($columns) + 2; ?>" class="no-data-cell">
                                    <div class="no-data-content">
                                        <span class="dashicons dashicons-database"></span>
                                        <p><?php esc_html_e('Nessun dato inserito nella WCAG tabella. Clicca "Aggiungi Riga WCAG" per iniziare.', 'wcag-wp'); ?></p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Row Template (hidden) -->
        <table id="row-template" style="display: none;">
            <tbody>
                <tr class="data-row" data-index="{{INDEX}}">
                    <td class="row-handle">
                        <span class="dashicons dashicons-menu"></span>
                        <span class="screen-reader-text"><?php esc_html_e('Trascina per riordinare', 'wcag-wp'); ?></span>
                    </td>
                    <?php foreach ($columns as $column): ?>
                        <td class="data-cell" data-column="<?php echo esc_attr($column['id']); ?>">
                            <?php
                            $input_name = "wcag_wp_table_data[{{INDEX}}][{$column['id']}]";
                            $input_id = "data_{{INDEX}}_{$column['id']}";
                            
                            switch ($column['type']) {
                                case 'number':
                                case 'currency':
                                    ?>
                                    <input type="number" 
                                           id="<?php echo esc_attr($input_id); ?>"
                                           name="<?php echo esc_attr($input_name); ?>" 
                                           <?php echo $column['required'] ? 'required' : ''; ?>
                                           step="any"
                                           class="data-input number-input">
                                    <?php
                                    break;
                                    
                                case 'email':
                                    ?>
                                    <input type="email" 
                                           id="<?php echo esc_attr($input_id); ?>"
                                           name="<?php echo esc_attr($input_name); ?>" 
                                           <?php echo $column['required'] ? 'required' : ''; ?>
                                           class="data-input email-input">
                                    <?php
                                    break;
                                    
                                case 'url':
                                    ?>
                                    <input type="url" 
                                           id="<?php echo esc_attr($input_id); ?>"
                                           name="<?php echo esc_attr($input_name); ?>" 
                                           <?php echo $column['required'] ? 'required' : ''; ?>
                                           class="data-input url-input">
                                    <?php
                                    break;
                                    
                                case 'boolean':
                                    ?>
                                    <select id="<?php echo esc_attr($input_id); ?>"
                                            name="<?php echo esc_attr($input_name); ?>" 
                                            <?php echo $column['required'] ? 'required' : ''; ?>
                                            class="data-input boolean-input">
                                        <option value=""><?php esc_html_e('Seleziona...', 'wcag-wp'); ?></option>
                                        <option value="1"><?php esc_html_e('Sì', 'wcag-wp'); ?></option>
                                        <option value="0"><?php esc_html_e('No', 'wcag-wp'); ?></option>
                                    </select>
                                    <?php
                                    break;
                                    
                                default: // text
                                    ?>
                                    <input type="text" 
                                           id="<?php echo esc_attr($input_id); ?>"
                                           name="<?php echo esc_attr($input_name); ?>" 
                                           <?php echo $column['required'] ? 'required' : ''; ?>
                                           class="data-input text-input">
                                    <?php
                                    break;
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                    <td class="row-actions">
                        <button type="button" class="button-link row-duplicate" title="<?php esc_attr_e('Duplica riga', 'wcag-wp'); ?>">
                            <span class="dashicons dashicons-admin-page"></span>
                        </button>
                        <button type="button" class="button-link row-delete" title="<?php esc_attr_e('Elimina riga', 'wcag-wp'); ?>">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>