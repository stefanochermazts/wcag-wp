<?php
/**
 * WCAG Spinbutton Frontend Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-spinbutton <?php echo esc_attr($config['custom_class']); ?>">
    
    <?php if (!empty($config['label'])): ?>
        <label class="wcag-wp-spinbutton__label" for="<?php echo esc_attr($unique_id); ?>">
            <?php echo esc_html($config['label']); ?>
            <?php if ($config['required']): ?>
                <span class="wcag-wp-spinbutton__required" aria-label="<?php _e('campo obbligatorio', 'wcag-wp'); ?>">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>
    
    <?php if (!empty($config['description'])): ?>
        <div class="wcag-wp-spinbutton__description" id="<?php echo esc_attr($unique_id); ?>-description">
            <?php echo esc_html($config['description']); ?>
        </div>
    <?php endif; ?>
    
    <div class="wcag-wp-spinbutton__container">
        <input type="number" 
               id="<?php echo esc_attr($unique_id); ?>"
               name="<?php echo esc_attr($unique_id); ?>"
               class="wcag-wp-spinbutton__input wcag-wp-spinbutton__input--<?php echo esc_attr($config['size']); ?>"
               value="<?php echo esc_attr($config['default_value']); ?>"
               min="<?php echo esc_attr($config['min']); ?>"
               max="<?php echo esc_attr($config['max']); ?>"
               step="<?php echo esc_attr($config['step']); ?>"
               placeholder="<?php echo esc_attr($config['placeholder']); ?>"
               <?php echo $config['required'] ? 'required' : ''; ?>
               <?php echo $config['readonly'] ? 'readonly' : ''; ?>
               <?php echo $config['disabled'] ? 'disabled' : ''; ?>
               role="spinbutton"
               aria-valuemin="<?php echo esc_attr($config['min']); ?>"
               aria-valuemax="<?php echo esc_attr($config['max']); ?>"
               aria-valuenow="<?php echo esc_attr($config['default_value']); ?>"
               aria-valuetext="<?php echo esc_attr($config['default_value'] . ($config['unit'] ? ' ' . $config['unit'] : '')); ?>"
               <?php echo !empty($config['aria_label']) ? 'aria-label="' . esc_attr($config['aria_label']) . '"' : ''; ?>
               <?php echo !empty($config['aria_describedby']) ? 'aria-describedby="' . esc_attr($config['aria_describedby']) . '"' : ''; ?>
               <?php echo !empty($config['description']) ? 'aria-describedby="' . esc_attr($unique_id) . '-description"' : ''; ?>>
        
        <?php if (!empty($config['unit'])): ?>
            <span class="wcag-wp-spinbutton__unit" aria-label="<?php echo esc_attr(sprintf(__('unità di misura: %s', 'wcag-wp'), $config['unit'])); ?>">
                <?php echo esc_html($config['unit']); ?>
            </span>
        <?php endif; ?>
    </div>
    
    <div class="wcag-wp-spinbutton__controls">
        <button type="button" 
                class="wcag-wp-spinbutton__button wcag-wp-spinbutton__button--increment"
                aria-label="<?php echo esc_attr(sprintf(__('Incrementa %s di %s', 'wcag-wp'), $config['label'] ?: __('valore', 'wcag-wp'), $config['step'])); ?>"
                data-action="increment"
                data-target="<?php echo esc_attr($unique_id); ?>"
                data-step="<?php echo esc_attr($config['step']); ?>"
                data-min="<?php echo esc_attr($config['min']); ?>"
                data-max="<?php echo esc_attr($config['max']); ?>"
                <?php echo $config['disabled'] || $config['readonly'] ? 'disabled' : ''; ?>>
            <span class="wcag-wp-spinbutton__button-icon" aria-hidden="true">▲</span>
        </button>
        
        <button type="button" 
                class="wcag-wp-spinbutton__button wcag-wp-spinbutton__button--decrement"
                aria-label="<?php echo esc_attr(sprintf(__('Decrementa %s di %s', 'wcag-wp'), $config['label'] ?: __('valore', 'wcag-wp'), $config['step'])); ?>"
                data-action="decrement"
                data-target="<?php echo esc_attr($unique_id); ?>"
                data-step="<?php echo esc_attr($config['step']); ?>"
                data-min="<?php echo esc_attr($config['min']); ?>"
                data-max="<?php echo esc_attr($config['max']); ?>"
                <?php echo $config['disabled'] || $config['readonly'] ? 'disabled' : ''; ?>>
            <span class="wcag-wp-spinbutton__button-icon" aria-hidden="true">▼</span>
        </button>
    </div>
    
    <!-- Validation Messages -->
    <div class="wcag-wp-spinbutton__validation" 
         id="<?php echo esc_attr($unique_id); ?>-validation" 
         aria-live="polite" 
         aria-atomic="true" 
         hidden>
        <div class="wcag-wp-spinbutton__error" role="alert"></div>
        <div class="wcag-wp-spinbutton__help"></div>
    </div>
    
    <!-- Screen Reader Announcements -->
    <div class="wcag-wp-sr-only" 
         id="<?php echo esc_attr($unique_id); ?>-announcements" 
         aria-live="polite" 
         aria-atomic="true">
    </div>
    
</div>

<script type="application/json" class="wcag-spinbutton-config">
{
    "id": "<?php echo esc_js($unique_id); ?>",
    "type": "<?php echo esc_js($config['type']); ?>",
    "min": <?php echo floatval($config['min']); ?>,
    "max": <?php echo floatval($config['max']); ?>,
    "step": <?php echo floatval($config['step']); ?>,
    "defaultValue": <?php echo floatval($config['default_value']); ?>,
    "unit": "<?php echo esc_js($config['unit']); ?>",
    "format": "<?php echo esc_js($config['format']); ?>",
    "required": <?php echo $config['required'] ? 'true' : 'false'; ?>,
    "readonly": <?php echo $config['readonly'] ? 'true' : 'false'; ?>,
    "disabled": <?php echo $config['disabled'] ? 'true' : 'false'; ?>,
    "strings": {
        "increment": "<?php echo esc_js(__('Incrementa', 'wcag-wp')); ?>",
        "decrement": "<?php echo esc_js(__('Decrementa', 'wcag-wp')); ?>",
        "invalidValue": "<?php echo esc_js(__('Valore non valido', 'wcag-wp')); ?>",
        "minValue": "<?php echo esc_js(__('Valore minimo', 'wcag-wp')); ?>",
        "maxValue": "<?php echo esc_js(__('Valore massimo', 'wcag-wp')); ?>",
        "required": "<?php echo esc_js(__('Campo obbligatorio', 'wcag-wp')); ?>",
        "currentValue": "<?php echo esc_js(__('Valore corrente', 'wcag-wp')); ?>"
    }
}
</script>
