<?php
/**
 * Template: Combobox Configuration Meta Box
 * 
 * @package WCAG_WP
 * @var WP_Post $post Current post object
 * @var array $config Combobox configuration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-combobox-config">
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="combobox_type"><?php esc_html_e('Tipo Combobox', 'wcag-wp'); ?></label>
            </th>
            <td>
                <select name="wcag_combobox_config[type]" id="combobox_type" class="widefat">
                    <?php foreach (WCAG_WP_Combobox::get_types() as $type_key => $type_info): ?>
                        <option value="<?php echo esc_attr($type_key); ?>" 
                                <?php selected($config['type'], $type_key); ?>
                                data-behavior="<?php echo esc_attr($type_info['behavior']); ?>">
                            <?php echo esc_html($type_info['label']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description" id="combobox_type_description">
                    <?php 
                    $current_type = WCAG_WP_Combobox::get_types()[$config['type']] ?? WCAG_WP_Combobox::get_types()['autocomplete'];
                    echo esc_html($current_type['description']);
                    ?>
                </p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="autocomplete_behavior"><?php esc_html_e('Comportamento Autocomplete', 'wcag-wp'); ?></label>
            </th>
            <td>
                <select name="wcag_combobox_config[autocomplete_behavior]" id="autocomplete_behavior" class="widefat">
                    <?php foreach (WCAG_WP_Combobox::get_autocomplete_behaviors() as $behavior_key => $behavior_label): ?>
                        <option value="<?php echo esc_attr($behavior_key); ?>" 
                                <?php selected($config['autocomplete_behavior'], $behavior_key); ?>>
                            <?php echo esc_html($behavior_label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description">
                    <?php esc_html_e('Definisce come vengono presentati i suggerimenti agli screen reader.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="placeholder"><?php esc_html_e('Placeholder', 'wcag-wp'); ?></label>
            </th>
            <td>
                <input type="text" 
                       name="wcag_combobox_config[placeholder]" 
                       id="placeholder"
                       value="<?php echo esc_attr($config['placeholder']); ?>"
                       class="widefat"
                       placeholder="<?php esc_attr_e('Inizia a digitare per cercare...', 'wcag-wp'); ?>">
                <p class="description">
                    <?php esc_html_e('Testo di aiuto mostrato quando il campo è vuoto.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label><?php esc_html_e('Opzioni Campo', 'wcag-wp'); ?></label>
            </th>
            <td>
                <fieldset>
                    <label>
                        <input type="checkbox" 
                               name="wcag_combobox_config[required]" 
                               value="1" 
                               <?php checked($config['required']); ?>>
                        <?php esc_html_e('Campo obbligatorio', 'wcag-wp'); ?>
                    </label>
                    <br><br>
                    
                    <label>
                        <input type="checkbox" 
                               name="wcag_combobox_config[multiple]" 
                               id="multiple_selection"
                               value="1" 
                               <?php checked($config['multiple']); ?>>
                        <?php esc_html_e('Selezione multipla', 'wcag-wp'); ?>
                    </label>
                    <p class="description">
                        <?php esc_html_e('Permette di selezionare più opzioni contemporaneamente.', 'wcag-wp'); ?>
                    </p>
                </fieldset>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label><?php esc_html_e('Parametri Ricerca', 'wcag-wp'); ?></label>
            </th>
            <td>
                <div class="wcag-search-params">
                    <div class="param-group">
                        <label for="max_results">
                            <?php esc_html_e('Massimo risultati:', 'wcag-wp'); ?>
                        </label>
                        <input type="number" 
                               name="wcag_combobox_config[max_results]" 
                               id="max_results"
                               value="<?php echo esc_attr($config['max_results']); ?>"
                               min="1" 
                               max="100" 
                               class="small-text">
                    </div>
                    
                    <div class="param-group">
                        <label for="min_chars">
                            <?php esc_html_e('Caratteri minimi:', 'wcag-wp'); ?>
                        </label>
                        <input type="number" 
                               name="wcag_combobox_config[min_chars]" 
                               id="min_chars"
                               value="<?php echo esc_attr($config['min_chars']); ?>"
                               min="0" 
                               max="10" 
                               class="small-text">
                    </div>
                    
                    <div class="param-group">
                        <label for="debounce_delay">
                            <?php esc_html_e('Ritardo ricerca (ms):', 'wcag-wp'); ?>
                        </label>
                        <input type="number" 
                               name="wcag_combobox_config[debounce_delay]" 
                               id="debounce_delay"
                               value="<?php echo esc_attr($config['debounce_delay']); ?>"
                               min="0" 
                               max="2000" 
                               step="50"
                               class="small-text">
                    </div>
                    
                    <div class="param-group">
                        <label>
                            <input type="checkbox" 
                                   name="wcag_combobox_config[case_sensitive]" 
                                   value="1" 
                                   <?php checked($config['case_sensitive']); ?>>
                            <?php esc_html_e('Ricerca case-sensitive', 'wcag-wp'); ?>
                        </label>
                    </div>
                </div>
                
                <p class="description">
                    <?php esc_html_e('Configurazione comportamento ricerca e filtri.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="data_source"><?php esc_html_e('Sorgente Dati', 'wcag-wp'); ?></label>
            </th>
            <td>
                <select name="wcag_combobox_config[data_source]" id="data_source" class="widefat">
                    <option value="static" <?php selected($config['data_source'], 'static'); ?>>
                        <?php esc_html_e('Opzioni Statiche', 'wcag-wp'); ?>
                    </option>
                    <option value="posts" <?php selected($config['data_source'], 'posts'); ?>>
                        <?php esc_html_e('Post WordPress', 'wcag-wp'); ?>
                    </option>
                    <option value="users" <?php selected($config['data_source'], 'users'); ?>>
                        <?php esc_html_e('Utenti WordPress', 'wcag-wp'); ?>
                    </option>
                    <option value="terms" <?php selected($config['data_source'], 'terms'); ?>>
                        <?php esc_html_e('Termini/Categorie', 'wcag-wp'); ?>
                    </option>
                    <option value="external" <?php selected($config['data_source'], 'external'); ?>>
                        <?php esc_html_e('API Esterna', 'wcag-wp'); ?>
                    </option>
                </select>
                <p class="description">
                    <?php esc_html_e('Definisce da dove vengono caricate le opzioni del combobox.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
        
        <!-- External API Settings -->
        <tr id="external_api_settings" style="display: <?php echo $config['data_source'] === 'external' ? 'table-row' : 'none'; ?>;">
            <th scope="row">
                <label for="external_url"><?php esc_html_e('URL API Esterna', 'wcag-wp'); ?></label>
            </th>
            <td>
                <input type="url" 
                       name="wcag_combobox_config[external_url]" 
                       id="external_url"
                       value="<?php echo esc_attr($config['external_url']); ?>"
                       class="widefat"
                       placeholder="https://api.example.com/search">
                <p class="description">
                    <?php esc_html_e('URL endpoint che accetta parametro "q" per la ricerca e "limit" per il numero massimo risultati.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
        
        <!-- WordPress Query Settings -->
        <tr id="wp_query_settings" style="display: <?php echo in_array($config['data_source'], ['posts', 'users', 'terms']) ? 'table-row' : 'none'; ?>;">
            <th scope="row">
                <label for="wp_query_args"><?php esc_html_e('Parametri Query WordPress', 'wcag-wp'); ?></label>
            </th>
            <td>
                <textarea name="wcag_combobox_config[wp_query_args]" 
                          id="wp_query_args"
                          class="widefat" 
                          rows="4"
                          placeholder="post_type=product&post_status=publish&meta_key=featured"><?php echo esc_textarea($config['wp_query_args']); ?></textarea>
                <p class="description">
                    <?php esc_html_e('Parametri aggiuntivi per WP_Query in formato query string (es: post_type=product&meta_key=featured).', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="custom_class"><?php esc_html_e('Classe CSS Personalizzata', 'wcag-wp'); ?></label>
            </th>
            <td>
                <input type="text" 
                       name="wcag_combobox_config[custom_class]" 
                       id="custom_class"
                       value="<?php echo esc_attr($config['custom_class']); ?>"
                       class="widefat"
                       placeholder="my-custom-combobox">
                <p class="description">
                    <?php esc_html_e('Classe CSS aggiuntiva per personalizzazioni avanzate.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
    </table>
</div>

<style>
.wcag-combobox-config .form-table th {
    width: 200px;
    padding-left: 0;
}

.wcag-combobox-config .form-table td {
    padding-left: 0;
}

.wcag-search-params {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 10px;
}

.param-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.param-group label {
    font-weight: 500;
    font-size: 13px;
}

.param-group input[type="number"] {
    max-width: 80px;
}

.param-group input[type="checkbox"] {
    margin-right: 5px;
}

@media (max-width: 782px) {
    .wcag-search-params {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update type description when type changes
    const typeSelect = document.getElementById('combobox_type');
    const typeDescription = document.getElementById('combobox_type_description');
    
    if (typeSelect && typeDescription) {
        typeSelect.addEventListener('change', function() {
            const types = <?php echo json_encode(WCAG_WP_Combobox::get_types()); ?>;
            const selectedType = types[this.value];
            if (selectedType) {
                typeDescription.textContent = selectedType.description;
            }
        });
    }
    
    // Show/hide data source specific settings
    const dataSourceSelect = document.getElementById('data_source');
    const externalSettings = document.getElementById('external_api_settings');
    const wpQuerySettings = document.getElementById('wp_query_settings');
    
    if (dataSourceSelect && externalSettings && wpQuerySettings) {
        dataSourceSelect.addEventListener('change', function() {
            const value = this.value;
            
            // Hide all specific settings first
            externalSettings.style.display = 'none';
            wpQuerySettings.style.display = 'none';
            
            // Show relevant settings
            if (value === 'external') {
                externalSettings.style.display = 'table-row';
            } else if (['posts', 'users', 'terms'].includes(value)) {
                wpQuerySettings.style.display = 'table-row';
            }
        });
    }
});
</script>

