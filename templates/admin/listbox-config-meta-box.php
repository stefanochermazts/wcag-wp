<?php
/**
 * Template: Listbox Configuration Meta Box
 * 
 * @package WCAG_WP
 * @var WP_Post $post Current post object
 * @var array $config Listbox configuration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-listbox-config">
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="listbox_type"><?php esc_html_e('Tipo Listbox', 'wcag-wp'); ?></label>
            </th>
            <td>
                <select name="wcag_listbox_config[type]" id="listbox_type" class="widefat">
                    <?php foreach (WCAG_WP_Listbox::get_types() as $type_key => $type_info): ?>
                        <option value="<?php echo esc_attr($type_key); ?>" 
                                <?php selected($config['type'], $type_key); ?>
                                data-multiselectable="<?php echo esc_attr($type_info['multiselectable'] ? 'true' : 'false'); ?>">
                            <?php echo esc_html($type_info['label']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description" id="listbox_type_description">
                    <?php 
                    $current_type = WCAG_WP_Listbox::get_types()[$config['type']] ?? WCAG_WP_Listbox::get_types()['single'];
                    echo esc_html($current_type['description']);
                    ?>
                </p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="selection_mode"><?php esc_html_e('Modalità Selezione', 'wcag-wp'); ?></label>
            </th>
            <td>
                <select name="wcag_listbox_config[selection_mode]" id="selection_mode" class="widefat">
                    <?php foreach (WCAG_WP_Listbox::get_selection_modes() as $mode_key => $mode_label): ?>
                        <option value="<?php echo esc_attr($mode_key); ?>" 
                                <?php selected($config['selection_mode'], $mode_key); ?>>
                            <?php echo esc_html($mode_label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description">
                    <?php esc_html_e('Definisce come gli utenti possono selezionare le opzioni.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="orientation"><?php esc_html_e('Orientamento', 'wcag-wp'); ?></label>
            </th>
            <td>
                <select name="wcag_listbox_config[orientation]" id="orientation" class="widefat">
                    <?php foreach (WCAG_WP_Listbox::get_orientations() as $orient_key => $orient_label): ?>
                        <option value="<?php echo esc_attr($orient_key); ?>" 
                                <?php selected($config['orientation'], $orient_key); ?>>
                            <?php echo esc_html($orient_label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description">
                    <?php esc_html_e('Layout e navigazione del listbox.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="size"><?php esc_html_e('Dimensione Visibile', 'wcag-wp'); ?></label>
            </th>
            <td>
                <input type="number" 
                       name="wcag_listbox_config[size]" 
                       id="size"
                       value="<?php echo esc_attr($config['size']); ?>"
                       min="1" 
                       max="20" 
                       class="small-text">
                <p class="description">
                    <?php esc_html_e('Numero di opzioni visibili contemporaneamente (1-20).', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label><?php esc_html_e('Opzioni Comportamento', 'wcag-wp'); ?></label>
            </th>
            <td>
                <fieldset>
                    <label>
                        <input type="checkbox" 
                               name="wcag_listbox_config[required]" 
                               value="1" 
                               <?php checked($config['required']); ?>>
                        <?php esc_html_e('Campo obbligatorio', 'wcag-wp'); ?>
                    </label>
                    <br><br>
                    
                    <label>
                        <input type="checkbox" 
                               name="wcag_listbox_config[allow_deselect]" 
                               value="1" 
                               <?php checked($config['allow_deselect']); ?>>
                        <?php esc_html_e('Permetti deselezione', 'wcag-wp'); ?>
                    </label>
                    <p class="description">
                        <?php esc_html_e('Consente di deselezionare tutte le opzioni.', 'wcag-wp'); ?>
                    </p>
                    
                    <label>
                        <input type="checkbox" 
                               name="wcag_listbox_config[wrap_navigation]" 
                               value="1" 
                               <?php checked($config['wrap_navigation']); ?>>
                        <?php esc_html_e('Navigazione circolare', 'wcag-wp'); ?>
                    </label>
                    <p class="description">
                        <?php esc_html_e('Dall\'ultima opzione torna alla prima con le frecce.', 'wcag-wp'); ?>
                    </p>
                    
                    <label>
                        <input type="checkbox" 
                               name="wcag_listbox_config[auto_select_first]" 
                               value="1" 
                               <?php checked($config['auto_select_first']); ?>>
                        <?php esc_html_e('Seleziona automaticamente la prima opzione', 'wcag-wp'); ?>
                    </label>
                    <p class="description">
                        <?php esc_html_e('Utile per listbox a selezione singola obbligatoria.', 'wcag-wp'); ?>
                    </p>
                    
                    <label>
                        <input type="checkbox" 
                               name="wcag_listbox_config[show_selection_count]" 
                               value="1" 
                               <?php checked($config['show_selection_count']); ?>>
                        <?php esc_html_e('Mostra contatore selezioni', 'wcag-wp'); ?>
                    </label>
                    <p class="description">
                        <?php esc_html_e('Visualizza il numero di opzioni selezionate.', 'wcag-wp'); ?>
                    </p>
                </fieldset>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label><?php esc_html_e('Ricerca Integrata', 'wcag-wp'); ?></label>
            </th>
            <td>
                <fieldset>
                    <label>
                        <input type="checkbox" 
                               name="wcag_listbox_config[enable_search]" 
                               id="enable_search"
                               value="1" 
                               <?php checked($config['enable_search']); ?>>
                        <?php esc_html_e('Abilita ricerca nelle opzioni', 'wcag-wp'); ?>
                    </label>
                    <p class="description">
                        <?php esc_html_e('Aggiunge un campo di ricerca sopra il listbox.', 'wcag-wp'); ?>
                    </p>
                    
                    <div id="search_options" style="display: <?php echo $config['enable_search'] ? 'block' : 'none'; ?>; margin-top: 15px;">
                        <label for="search_placeholder">
                            <?php esc_html_e('Placeholder ricerca:', 'wcag-wp'); ?>
                        </label>
                        <input type="text" 
                               name="wcag_listbox_config[search_placeholder]" 
                               id="search_placeholder"
                               value="<?php echo esc_attr($config['search_placeholder']); ?>"
                               class="widefat"
                               placeholder="<?php esc_attr_e('Cerca nelle opzioni...', 'wcag-wp'); ?>">
                    </div>
                </fieldset>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="data_source"><?php esc_html_e('Sorgente Dati', 'wcag-wp'); ?></label>
            </th>
            <td>
                <select name="wcag_listbox_config[data_source]" id="data_source" class="widefat">
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
                    <?php esc_html_e('Definisce da dove vengono caricate le opzioni del listbox.', 'wcag-wp'); ?>
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
                       name="wcag_listbox_config[external_url]" 
                       id="external_url"
                       value="<?php echo esc_attr($config['external_url']); ?>"
                       class="widefat"
                       placeholder="https://api.example.com/options">
                <p class="description">
                    <?php esc_html_e('URL endpoint che restituisce un array JSON di opzioni.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
        
        <!-- WordPress Query Settings -->
        <tr id="wp_query_settings" style="display: <?php echo in_array($config['data_source'], ['posts', 'users', 'terms']) ? 'table-row' : 'none'; ?>;">
            <th scope="row">
                <label for="wp_query_args"><?php esc_html_e('Parametri Query WordPress', 'wcag-wp'); ?></label>
            </th>
            <td>
                <textarea name="wcag_listbox_config[wp_query_args]" 
                          id="wp_query_args"
                          class="widefat" 
                          rows="4"
                          placeholder="post_type=product&post_status=publish&meta_key=featured"><?php echo esc_textarea($config['wp_query_args']); ?></textarea>
                <p class="description">
                    <?php esc_html_e('Parametri aggiuntivi per WP_Query in formato query string.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="custom_class"><?php esc_html_e('Classe CSS Personalizzata', 'wcag-wp'); ?></label>
            </th>
            <td>
                <input type="text" 
                       name="wcag_listbox_config[custom_class]" 
                       id="custom_class"
                       value="<?php echo esc_attr($config['custom_class']); ?>"
                       class="widefat"
                       placeholder="my-custom-listbox">
                <p class="description">
                    <?php esc_html_e('Classe CSS aggiuntiva per personalizzazioni avanzate.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
    </table>
</div>

<style>
.wcag-listbox-config .form-table th {
    width: 200px;
    padding-left: 0;
}

.wcag-listbox-config .form-table td {
    padding-left: 0;
}

.wcag-listbox-config fieldset {
    margin: 0;
}

.wcag-listbox-config fieldset label {
    display: block;
    margin-bottom: 8px;
    font-weight: normal;
}

.wcag-listbox-config fieldset .description {
    margin-left: 25px;
    margin-top: -5px;
    margin-bottom: 15px;
    font-size: 12px;
    color: #646970;
}

@media (max-width: 782px) {
    .wcag-listbox-config .form-table th,
    .wcag-listbox-config .form-table td {
        display: block;
        width: 100%;
        padding: 10px 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update type description when type changes
    const typeSelect = document.getElementById('listbox_type');
    const typeDescription = document.getElementById('listbox_type_description');
    
    if (typeSelect && typeDescription) {
        typeSelect.addEventListener('change', function() {
            const types = <?php echo json_encode(WCAG_WP_Listbox::get_types()); ?>;
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
    
    // Show/hide search options
    const enableSearchCheckbox = document.getElementById('enable_search');
    const searchOptions = document.getElementById('search_options');
    
    if (enableSearchCheckbox && searchOptions) {
        enableSearchCheckbox.addEventListener('change', function() {
            searchOptions.style.display = this.checked ? 'block' : 'none';
        });
    }
});
</script>

