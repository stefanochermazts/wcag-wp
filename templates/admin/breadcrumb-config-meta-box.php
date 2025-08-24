<?php
/**
 * Template Meta Box Configurazione WCAG Breadcrumb
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$config = $config ?: [];
$defaults = [
    'source_type' => 'auto',
    'home_text' => __('Home', 'wcag-wp'),
    'separator' => '/',
    'max_depth' => 5,
    'show_current' => true,
    'show_home' => true,
    'custom_items' => [],
    'css_class' => '',
    'aria_label' => __('Breadcrumb navigation', 'wcag-wp'),
];

$config = array_merge($defaults, $config);
?>

<div class="wcag-wp-meta-box">
    
    <!-- Tipo Sorgente -->
    <div class="wcag-wp-field-group">
        <label for="source_type" class="wcag-wp-label">
            <?php _e('Tipo Sorgente', 'wcag-wp'); ?>
        </label>
        <select id="source_type" name="source_type" class="wcag-wp-select">
            <option value="auto" <?php selected($config['source_type'], 'auto'); ?>>
                <?php _e('Automatico (da struttura WordPress)', 'wcag-wp'); ?>
            </option>
            <option value="custom" <?php selected($config['source_type'], 'custom'); ?>>
                <?php _e('Personalizzato (elementi manuali)', 'wcag-wp'); ?>
            </option>
        </select>
        <p class="wcag-wp-description">
            <?php _e('Scegli se generare automaticamente i breadcrumb dalla struttura del sito o definire elementi personalizzati.', 'wcag-wp'); ?>
        </p>
    </div>

    <!-- Configurazione Generale -->
    <div class="wcag-wp-field-group">
        <h4><?php _e('Configurazione Generale', 'wcag-wp'); ?></h4>
        
        <div class="wcag-wp-field-row">
            <div class="wcag-wp-field">
                <label for="home_text" class="wcag-wp-label">
                    <?php _e('Testo Home', 'wcag-wp'); ?>
                </label>
                <input type="text" id="home_text" name="home_text" 
                       value="<?php echo esc_attr($config['home_text']); ?>" 
                       class="wcag-wp-input" />
            </div>
            
            <div class="wcag-wp-field">
                <label for="separator" class="wcag-wp-label">
                    <?php _e('Separatore', 'wcag-wp'); ?>
                </label>
                <input type="text" id="separator" name="separator" 
                       value="<?php echo esc_attr($config['separator']); ?>" 
                       class="wcag-wp-input" maxlength="5" />
            </div>
        </div>

        <div class="wcag-wp-field-row">
            <div class="wcag-wp-field">
                <label for="max_depth" class="wcag-wp-label">
                    <?php _e('Profondità Massima', 'wcag-wp'); ?>
                </label>
                <input type="number" id="max_depth" name="max_depth" 
                       value="<?php echo esc_attr($config['max_depth']); ?>" 
                       class="wcag-wp-input" min="1" max="10" />
            </div>
            
            <div class="wcag-wp-field">
                <label for="aria_label" class="wcag-wp-label">
                    <?php _e('ARIA Label', 'wcag-wp'); ?>
                </label>
                <input type="text" id="aria_label" name="aria_label" 
                       value="<?php echo esc_attr($config['aria_label']); ?>" 
                       class="wcag-wp-input" />
            </div>
        </div>

        <div class="wcag-wp-field-row">
            <div class="wcag-wp-field">
                <label class="wcag-wp-checkbox-label">
                    <input type="checkbox" name="show_home" value="1" 
                           <?php checked($config['show_home']); ?> />
                    <?php _e('Mostra link Home', 'wcag-wp'); ?>
                </label>
            </div>
            
            <div class="wcag-wp-field">
                <label class="wcag-wp-checkbox-label">
                    <input type="checkbox" name="show_current" value="1" 
                           <?php checked($config['show_current']); ?> />
                    <?php _e('Mostra pagina corrente', 'wcag-wp'); ?>
                </label>
            </div>
        </div>
    </div>

    <!-- Elementi Personalizzati -->
    <div class="wcag-wp-field-group" id="custom-items-section" 
         style="display: <?php echo $config['source_type'] === 'custom' ? 'block' : 'none'; ?>;">
        <h4><?php _e('Elementi Personalizzati', 'wcag-wp'); ?></h4>
        
        <div id="custom-items-container">
            <?php if (!empty($config['custom_items'])): ?>
                <?php foreach ($config['custom_items'] as $index => $item): ?>
                    <div class="wcag-wp-custom-item" data-index="<?php echo $index; ?>">
                        <div class="wcag-wp-field-row">
                            <div class="wcag-wp-field">
                                <label class="wcag-wp-label">
                                    <?php _e('Testo', 'wcag-wp'); ?>
                                </label>
                                <input type="text" name="custom_items[<?php echo $index; ?>][text]" 
                                       value="<?php echo esc_attr($item['text']); ?>" 
                                       class="wcag-wp-input" />
                            </div>
                            
                            <div class="wcag-wp-field">
                                <label class="wcag-wp-label">
                                    <?php _e('URL', 'wcag-wp'); ?>
                                </label>
                                <input type="url" name="custom_items[<?php echo $index; ?>][url]" 
                                       value="<?php echo esc_attr($item['url']); ?>" 
                                       class="wcag-wp-input" />
                            </div>
                            
                            <div class="wcag-wp-field">
                                <label class="wcag-wp-label">
                                    <?php _e('Target', 'wcag-wp'); ?>
                                </label>
                                <select name="custom_items[<?php echo $index; ?>][target]" class="wcag-wp-select">
                                    <option value="_self" <?php selected($item['target'], '_self'); ?>>
                                        <?php _e('Stessa finestra', 'wcag-wp'); ?>
                                    </option>
                                    <option value="_blank" <?php selected($item['target'], '_blank'); ?>>
                                        <?php _e('Nuova finestra', 'wcag-wp'); ?>
                                    </option>
                                </select>
                            </div>
                            
                            <div class="wcag-wp-field">
                                <button type="button" class="wcag-wp-button wcag-wp-button-danger remove-item">
                                    <?php _e('Rimuovi', 'wcag-wp'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <button type="button" id="add-custom-item" class="wcag-wp-button wcag-wp-button-secondary">
            <?php _e('Aggiungi Elemento', 'wcag-wp'); ?>
        </button>
    </div>

    <!-- Personalizzazione CSS -->
    <div class="wcag-wp-field-group">
        <h4><?php _e('Personalizzazione CSS', 'wcag-wp'); ?></h4>
        
        <div class="wcag-wp-field">
            <label for="css_class" class="wcag-wp-label">
                <?php _e('Classe CSS Personalizzata', 'wcag-wp'); ?>
            </label>
            <input type="text" id="css_class" name="css_class" 
                   value="<?php echo esc_attr($config['css_class']); ?>" 
                   class="wcag-wp-input" placeholder="my-custom-breadcrumb" />
            <p class="wcag-wp-description">
                <?php _e('Aggiungi una classe CSS personalizzata per styling custom del breadcrumb.', 'wcag-wp'); ?>
            </p>
        </div>
    </div>

    <!-- Anteprima Live -->
    <div class="wcag-wp-field-group">
        <h4><?php _e('Anteprima Live', 'wcag-wp'); ?></h4>
        
        <div id="breadcrumb-preview" class="wcag-wp-preview-container">
            <div class="wcag-wp-preview-placeholder">
                <?php _e('Configura le opzioni per vedere l\'anteprima del breadcrumb.', 'wcag-wp'); ?>
            </div>
        </div>
        
        <button type="button" id="generate-preview" class="wcag-wp-button wcag-wp-button-primary">
            <?php _e('Genera Anteprima', 'wcag-wp'); ?>
        </button>
    </div>

</div>

<script type="text/template" id="custom-item-template">
    <div class="wcag-wp-custom-item" data-index="{{index}}">
        <div class="wcag-wp-field-row">
            <div class="wcag-wp-field">
                <label class="wcag-wp-label">
                    <?php _e('Testo', 'wcag-wp'); ?>
                </label>
                <input type="text" name="custom_items[{{index}}][text]" 
                       class="wcag-wp-input" required />
            </div>
            
            <div class="wcag-wp-field">
                <label class="wcag-wp-label">
                    <?php _e('URL', 'wcag-wp'); ?>
                </label>
                <input type="url" name="custom_items[{{index}}][url]" 
                       class="wcag-wp-input" required />
            </div>
            
            <div class="wcag-wp-field">
                <label class="wcag-wp-label">
                    <?php _e('Target', 'wcag-wp'); ?>
                </label>
                <select name="custom_items[{{index}}][target]" class="wcag-wp-select">
                    <option value="_self"><?php _e('Stessa finestra', 'wcag-wp'); ?></option>
                    <option value="_blank"><?php _e('Nuova finestra', 'wcag-wp'); ?></option>
                </select>
            </div>
            
            <div class="wcag-wp-field">
                <button type="button" class="wcag-wp-button wcag-wp-button-danger remove-item">
                    <?php _e('Rimuovi', 'wcag-wp'); ?>
                </button>
            </div>
        </div>
    </div>
</script>
