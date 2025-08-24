<div class="wcag-wp-slider <?php echo esc_attr($config['custom_class']); ?> wcag-wp-slider--<?php echo esc_attr($config['orientation']); ?> wcag-wp-slider--<?php echo esc_attr($config['size']); ?> wcag-wp-slider--<?php echo esc_attr($config['theme']); ?>">
    <?php if (!empty($config['label'])): ?>
        <label class="wcag-wp-slider__label" for="<?php echo esc_attr($unique_id); ?>">
            <?php echo esc_html($config['label']); ?>
            <?php if ($config['required']): ?>
                <span class="wcag-wp-slider__required" aria-label="<?php _e('obbligatorio', 'wcag-wp'); ?>">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>
    
    <?php if (!empty($config['description'])): ?>
        <div class="wcag-wp-slider__description" id="<?php echo esc_attr($unique_id); ?>-description">
            <?php echo esc_html($config['description']); ?>
        </div>
    <?php endif; ?>
    
    <div class="wcag-wp-slider__container">
        <div class="wcag-wp-slider__track" 
             id="<?php echo esc_attr($unique_id); ?>-track"
             <?php echo !empty($config['aria_describedby']) ? 'aria-describedby="' . esc_attr($config['aria_describedby']) . '"' : ''; ?>
             <?php echo !empty($config['description']) ? 'aria-describedby="' . esc_attr($unique_id) . '-description"' : ''; ?>>
            
            <?php if (!empty($config['show_ticks'])): ?>
                <div class="wcag-wp-slider__ticks">
                    <?php
                    $tick_labels = !empty($config['tick_labels']) ? explode(',', $config['tick_labels']) : [];
                    $tick_count = 5; // Default number of ticks
                    
                    for ($i = 0; $i < $tick_count; $i++):
                        $tick_value = $config['min'] + (($config['max'] - $config['min']) / ($tick_count - 1)) * $i;
                        $tick_label = isset($tick_labels[$i]) ? trim($tick_labels[$i]) : $tick_value;
                        $tick_percentage = ($i / ($tick_count - 1)) * 100;
                    ?>
                        <div class="wcag-wp-slider__tick" style="<?php echo $config['orientation'] === 'horizontal' ? 'left: ' . $tick_percentage . '%' : 'bottom: ' . $tick_percentage . '%'; ?>">
                            <span class="wcag-wp-slider__tick-mark"></span>
                            <span class="wcag-wp-slider__tick-label"><?php echo esc_html($tick_label); ?></span>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
            
            <div class="wcag-wp-slider__thumb" 
                 id="<?php echo esc_attr($unique_id); ?>"
                 role="slider" 
                 tabindex="0"
                 aria-valuemin="<?php echo esc_attr($config['min']); ?>"
                 aria-valuemax="<?php echo esc_attr($config['max']); ?>"
                 aria-valuenow="<?php echo esc_attr($config['default_value']); ?>"
                 aria-valuetext="<?php echo esc_attr($config['default_value'] . ($config['unit'] ? ' ' . $config['unit'] : '')); ?>"
                 <?php echo $config['disabled'] ? 'aria-disabled="true"' : ''; ?>
                 <?php echo $config['required'] ? 'aria-required="true"' : ''; ?>
                 <?php echo !empty($config['aria_label']) ? 'aria-label="' . esc_attr($config['aria_label']) . '"' : ''; ?>>
                
                <?php if (!empty($config['show_tooltip'])): ?>
                    <div class="wcag-wp-slider__tooltip" role="tooltip" aria-hidden="true">
                        <span class="wcag-wp-slider__tooltip-text"><?php echo esc_html($config['default_value'] . ($config['unit'] ? ' ' . $config['unit'] : '')); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (!empty($config['show_value'])): ?>
            <div class="wcag-wp-slider__value" aria-live="polite" aria-atomic="true">
                <span class="wcag-wp-slider__value-text"><?php echo esc_html($config['default_value'] . ($config['unit'] ? ' ' . $config['unit'] : '')); ?></span>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="wcag-wp-slider__validation" id="<?php echo esc_attr($unique_id); ?>-validation" aria-live="polite" aria-atomic="true" hidden></div>
    
    <div class="wcag-wp-sr-only" id="<?php echo esc_attr($unique_id); ?>-announcements" aria-live="polite" aria-atomic="true"></div>
</div>

<script type="application/json" class="wcag-slider-config">
{
    "id": "<?php echo esc_js($unique_id); ?>",
    "post_id": <?php echo intval($post_id); ?>,
    "config": {
        "min": <?php echo floatval($config['min']); ?>,
        "max": <?php echo floatval($config['max']); ?>,
        "step": <?php echo floatval($config['step']); ?>,
        "default_value": <?php echo floatval($config['default_value']); ?>,
        "unit": "<?php echo esc_js($config['unit']); ?>",
        "orientation": "<?php echo esc_js($config['orientation']); ?>",
        "show_value": <?php echo $config['show_value'] ? 'true' : 'false'; ?>,
        "show_ticks": <?php echo $config['show_ticks'] ? 'true' : 'false'; ?>,
        "show_tooltip": <?php echo $config['show_tooltip'] ? 'true' : 'false'; ?>,
        "required": <?php echo $config['required'] ? 'true' : 'false'; ?>,
        "disabled": <?php echo $config['disabled'] ? 'true' : 'false'; ?>,
        "format": "<?php echo esc_js($config['format']); ?>",
        "on_change_callback": "<?php echo esc_js($config['on_change_callback']); ?>"
    }
}
</script>
