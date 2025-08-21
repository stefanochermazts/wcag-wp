<?php
declare(strict_types=1);

/**
 * WCAG Table Configuration Meta Box Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 * 
 * @var WP_Post $post Current post object
 * @var array $config Table configuration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-table-config">
    <p class="description">
        <?php esc_html_e('Configura il comportamento e le funzionalità della WCAG tabella accessibile.', 'wcag-wp'); ?>
    </p>
    
    <table class="form-table">
        <tbody>
            <!-- Basic Configuration -->
            <tr>
                <th scope="row">
                    <label for="table_caption"><?php esc_html_e('Didascalia WCAG Tabella', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <input type="text" 
                           id="table_caption" 
                           name="wcag_wp_table_config[caption]" 
                           value="<?php echo esc_attr($config['caption']); ?>"
                           class="regular-text"
                           placeholder="<?php esc_attr_e('Es: Prezzi prodotti WCAG compliant 2025', 'wcag-wp'); ?>">
                    <p class="description">
                        <?php esc_html_e('Descrizione breve della WCAG tabella per screen reader (elemento <caption>).', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="table_summary"><?php esc_html_e('Riassunto WCAG Tabella', 'wcag-wp'); ?></label>
                </th>
                <td>
                    <textarea id="table_summary" 
                              name="wcag_wp_table_config[summary]" 
                              rows="3" 
                              class="regular-text"
                              placeholder="<?php esc_attr_e('Descrizione dettagliata della struttura e contenuto della WCAG tabella...', 'wcag-wp'); ?>"><?php echo esc_textarea($config['summary']); ?></textarea>
                    <p class="description">
                        <?php esc_html_e('Descrizione dettagliata per utenti con tecnologie assistive (attributo aria-describedby).', 'wcag-wp'); ?>
                    </p>
                </td>
            </tr>
            
            <!-- Interactive Features -->
            <tr>
                <th scope="row"><?php esc_html_e('Funzionalità Interattive WCAG', 'wcag-wp'); ?></th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <?php esc_html_e('Funzionalità Interattive della WCAG Tabella', 'wcag-wp'); ?>
                        </legend>
                        
                        <label>
                            <input type="checkbox" 
                                   name="wcag_wp_table_config[sortable]" 
                                   value="1" 
                                   <?php checked($config['sortable']); ?>>
                            <?php esc_html_e('Ordinamento colonne accessibile', 'wcag-wp'); ?>
                        </label>
                        <p class="description">
                            <?php esc_html_e('Permette agli utenti di ordinare i dati cliccando sulle intestazioni delle colonne con pieno supporto tastiera.', 'wcag-wp'); ?>
                        </p>
                        
                        <label>
                            <input type="checkbox" 
                                   name="wcag_wp_table_config[searchable]" 
                                   value="1" 
                                   <?php checked($config['searchable']); ?>>
                            <?php esc_html_e('Ricerca testuale WCAG', 'wcag-wp'); ?>
                        </label>
                        <p class="description">
                            <?php esc_html_e('Aggiunge un campo di ricerca accessibile per filtrare le righe della tabella con annunci screen reader.', 'wcag-wp'); ?>
                        </p>
                        
                        <label>
                            <input type="checkbox" 
                                   name="wcag_wp_table_config[export_csv]" 
                                   value="1" 
                                   <?php checked($config['export_csv']); ?>>
                            <?php esc_html_e('Esportazione CSV accessibile', 'wcag-wp'); ?>
                        </label>
                        <p class="description">
                            <?php esc_html_e('Aggiunge un pulsante accessibile per scaricare i dati della WCAG tabella in formato CSV.', 'wcag-wp'); ?>
                        </p>
                    </fieldset>
                </td>
            </tr>
            
            <!-- Responsive Behavior -->
            <tr>
                <th scope="row"><?php esc_html_e('Comportamento Responsive WCAG', 'wcag-wp'); ?></th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <?php esc_html_e('Comportamento Responsive della WCAG Tabella', 'wcag-wp'); ?>
                        </legend>
                        
                        <label>
                            <input type="checkbox" 
                                   name="wcag_wp_table_config[responsive]" 
                                   value="1" 
                                   <?php checked($config['responsive']); ?>>
                            <?php esc_html_e('WCAG Tabella responsive', 'wcag-wp'); ?>
                        </label>
                        <p class="description">
                            <?php esc_html_e('Adatta automaticamente la WCAG tabella per dispositivi mobili e tablet mantenendo l\'accessibilità.', 'wcag-wp'); ?>
                        </p>
                        
                        <label>
                            <input type="checkbox" 
                                   name="wcag_wp_table_config[stack_mobile]" 
                                   value="1" 
                                   <?php checked($config['stack_mobile']); ?>>
                            <?php esc_html_e('Impila su mobile (WCAG ottimizzato)', 'wcag-wp'); ?>
                        </label>
                        <p class="description">
                            <?php esc_html_e('Su mobile, ogni riga diventa una card impilata invece di scroll orizzontale, mantenendo la semantica WCAG.', 'wcag-wp'); ?>
                        </p>
                    </fieldset>
                </td>
            </tr>
            
            <!-- Pagination -->
            <tr>
                <th scope="row"><?php esc_html_e('Paginazione WCAG', 'wcag-wp'); ?></th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <?php esc_html_e('Impostazioni Paginazione WCAG', 'wcag-wp'); ?>
                        </legend>
                        
                        <label>
                            <input type="checkbox" 
                                   name="wcag_wp_table_config[pagination]" 
                                   value="1" 
                                   <?php checked($config['pagination']); ?>
                                   id="enable_pagination">
                            <?php esc_html_e('Abilita paginazione accessibile', 'wcag-wp'); ?>
                        </label>
                        <p class="description">
                            <?php esc_html_e('Suddivide i dati in più pagine con navigazione accessibile da tastiera per migliorare le performance.', 'wcag-wp'); ?>
                        </p>
                        
                        <div id="pagination_options" style="margin-top: 15px; <?php echo $config['pagination'] ? '' : 'display: none;'; ?>">
                            <label for="rows_per_page">
                                <?php esc_html_e('Righe per pagina:', 'wcag-wp'); ?>
                            </label>
                            <select id="rows_per_page" 
                                    name="wcag_wp_table_config[rows_per_page]">
                                <option value="5" <?php selected($config['rows_per_page'], 5); ?>>5</option>
                                <option value="10" <?php selected($config['rows_per_page'], 10); ?>>10</option>
                                <option value="20" <?php selected($config['rows_per_page'], 20); ?>>20</option>
                                <option value="50" <?php selected($config['rows_per_page'], 50); ?>>50</option>
                                <option value="100" <?php selected($config['rows_per_page'], 100); ?>>100</option>
                            </select>
                        </div>
                    </fieldset>
                </td>
            </tr>
        </tbody>
    </table>
    
    <!-- WCAG Compliance Preview Section -->
    <div class="wcag-wp-config-preview">
        <h4><?php esc_html_e('Anteprima Configurazione WCAG', 'wcag-wp'); ?></h4>
        <div class="preview-content">
            <div class="preview-feature" data-feature="sortable">
                <span class="dashicons dashicons-sort"></span>
                <span class="feature-label"><?php esc_html_e('Ordinamento WCAG', 'wcag-wp'); ?></span>
                <span class="feature-status"></span>
            </div>
            <div class="preview-feature" data-feature="searchable">
                <span class="dashicons dashicons-search"></span>
                <span class="feature-label"><?php esc_html_e('Ricerca WCAG', 'wcag-wp'); ?></span>
                <span class="feature-status"></span>
            </div>
            <div class="preview-feature" data-feature="responsive">
                <span class="dashicons dashicons-smartphone"></span>
                <span class="feature-label"><?php esc_html_e('Responsive WCAG', 'wcag-wp'); ?></span>
                <span class="feature-status"></span>
            </div>
            <div class="preview-feature" data-feature="export_csv">
                <span class="dashicons dashicons-download"></span>
                <span class="feature-label"><?php esc_html_e('Export CSV WCAG', 'wcag-wp'); ?></span>
                <span class="feature-status"></span>
            </div>
            <div class="preview-feature" data-feature="pagination">
                <span class="dashicons dashicons-editor-ul"></span>
                <span class="feature-label"><?php esc_html_e('Paginazione WCAG', 'wcag-wp'); ?></span>
                <span class="feature-status"></span>
            </div>
        </div>
    </div>
</div>

<style>
.wcag-wp-table-config .form-table th {
    width: 150px;
    vertical-align: top;
    padding-top: 15px;
}

.wcag-wp-table-config fieldset {
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 4px;
    background: #f9f9f9;
}

.wcag-wp-table-config fieldset legend {
    padding: 0 10px;
    font-weight: 600;
}

.wcag-wp-table-config fieldset label {
    display: block;
    margin-bottom: 10px;
    font-weight: 500;
}

.wcag-wp-table-config fieldset .description {
    margin: 5px 0 15px 25px;
    font-size: 13px;
    color: #666;
}

.wcag-wp-config-preview {
    margin-top: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
}

.wcag-wp-config-preview h4 {
    margin: 0 0 15px 0;
    color: #2271b1;
}

.preview-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
}

.preview-feature {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    border-radius: 4px;
    background: #f6f7f7;
    transition: background-color 0.2s ease;
}

.preview-feature.active {
    background: #e8f5e8;
    border-left: 3px solid #00a32a;
}

.preview-feature .dashicons {
    color: #666;
}

.preview-feature.active .dashicons {
    color: #00a32a;
}

.preview-feature .feature-label {
    font-size: 13px;
    font-weight: 500;
}

.preview-feature .feature-status::after {
    content: "Disabilitato";
    color: #d63638;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.preview-feature.active .feature-status::after {
    content: "Abilitato";
    color: #00a32a;
}

@media (max-width: 782px) {
    .preview-content {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
(function() {
    'use strict';
    
    // Update WCAG preview when checkboxes change
    function updateWCAGPreview() {
        const features = ['sortable', 'searchable', 'responsive', 'export_csv', 'pagination'];
        
        features.forEach(feature => {
            const checkbox = document.querySelector(`input[name="wcag_wp_table_config[${feature}]"]`);
            const previewElement = document.querySelector(`.preview-feature[data-feature="${feature}"]`);
            
            if (checkbox && previewElement) {
                if (checkbox.checked) {
                    previewElement.classList.add('active');
                } else {
                    previewElement.classList.remove('active');
                }
            }
        });
    }
    
    // Toggle pagination options
    function togglePaginationOptions() {
        const paginationCheckbox = document.getElementById('enable_pagination');
        const paginationOptions = document.getElementById('pagination_options');
        
        if (paginationCheckbox && paginationOptions) {
            paginationOptions.style.display = paginationCheckbox.checked ? 'block' : 'none';
        }
    }
    
    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initial WCAG preview update
        updateWCAGPreview();
        togglePaginationOptions();
        
        // Bind events
        const checkboxes = document.querySelectorAll('.wcag-wp-table-config input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateWCAGPreview);
        });
        
        // Pagination toggle
        const paginationCheckbox = document.getElementById('enable_pagination');
        if (paginationCheckbox) {
            paginationCheckbox.addEventListener('change', togglePaginationOptions);
        }
    });
})();
</script>