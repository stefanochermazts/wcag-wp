<?php
// Genera ID unico per il componente
$unique_id = 'wcag-slider-multithumb-' . uniqid();

// Genera thumbs dalla configurazione
$thumbs = array();
if (isset($config['thumbs_count']) && isset($config['default_values'])) {
    $values = explode(',', $config['default_values']);
    $count = intval($config['thumbs_count']);
    
    for ($i = 0; $i < $count; $i++) {
        $value = isset($values[$i]) ? floatval(trim($values[$i])) : $config['min'];
        $thumbs[] = array(
            'value' => $value,
            'label' => 'Thumb ' . ($i + 1)
        );
    }
} else {
    // Fallback per compatibilità
    $thumbs = isset($thumbs) ? $thumbs : array(
        array('value' => 25, 'label' => 'Min'),
        array('value' => 75, 'label' => 'Max')
    );
}
?>
<div class="wcag-wp-slider-multithumb <?php echo esc_attr($config['custom_class']); ?> wcag-wp-slider-multithumb--<?php echo esc_attr($config['orientation']); ?> wcag-wp-slider-multithumb--<?php echo esc_attr($config['size']); ?> wcag-wp-slider-multithumb--<?php echo esc_attr($config['theme']); ?><?php echo $config['disabled'] ? ' wcag-wp-slider-multithumb--disabled' : ''; ?>" 
     data-wcag-slider-multithumb 
     data-config="<?php echo esc_attr(json_encode($config)); ?>">
    <?php if (!empty($config['label'])): ?>
        <label class="wcag-wp-slider-multithumb__label" for="<?php echo esc_attr($unique_id); ?>-0">
            <?php echo esc_html($config['label']); ?>
            <?php if ($config['required']): ?>
                <span class="wcag-wp-slider-multithumb__required" aria-label="<?php _e('obbligatorio', 'wcag-wp'); ?>">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>
    
    <?php if (!empty($config['description'])): ?>
        <div class="wcag-wp-slider-multithumb__description" id="<?php echo esc_attr($unique_id); ?>-description">
            <?php echo esc_html($config['description']); ?>
        </div>
    <?php endif; ?>
    
    <div class="wcag-wp-slider-multithumb__container">
        <div class="wcag-wp-slider-multithumb__track" 
             id="<?php echo esc_attr($unique_id); ?>-track"
             <?php echo !empty($config['aria_describedby']) ? 'aria-describedby="' . esc_attr($config['aria_describedby']) . '"' : ''; ?>
             <?php echo !empty($config['description']) ? 'aria-describedby="' . esc_attr($unique_id) . '-description"' : ''; ?>>
            
            <?php if (!empty($config['show_ticks'])): ?>
                <div class="wcag-wp-slider-multithumb__ticks">
                    <?php
                    $tick_labels = !empty($config['tick_labels']) ? explode(',', $config['tick_labels']) : [];
                    $tick_count = 5; // Default number of ticks
                    
                    for ($i = 0; $i < $tick_count; $i++):
                        $tick_value = $config['min'] + (($config['max'] - $config['min']) / ($tick_count - 1)) * $i;
                        $tick_label = isset($tick_labels[$i]) ? trim($tick_labels[$i]) : $tick_value;
                        $tick_percentage = ($i / ($tick_count - 1)) * 100;
                    ?>
                        <div class="wcag-wp-slider-multithumb__tick" style="<?php echo $config['orientation'] === 'horizontal' ? 'left: ' . $tick_percentage . '%' : 'bottom: ' . $tick_percentage . '%'; ?>">
                            <span class="wcag-wp-slider-multithumb__tick-mark"></span>
                            <span class="wcag-wp-slider-multithumb__tick-label"><?php echo esc_html($tick_label); ?></span>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($config['show_range_fill'] && count($thumbs) >= 2): ?>
                <div class="wcag-wp-slider-multithumb__range-fill"></div>
            <?php endif; ?>
            
            <?php foreach ($thumbs as $index => $thumb): ?>
                <?php 
                $thumb_id = $unique_id . '-' . $index;
                $thumb_value = $thumb['value'];
                $thumb_label = $thumb['label'];
                $thumb_percentage = (($thumb_value - $config['min']) / ($config['max'] - $config['min'])) * 100;
                ?>
                <div class="wcag-wp-slider-multithumb__thumb" 
                     id="<?php echo esc_attr($thumb_id); ?>"
                     role="slider" 
                     tabindex="0"
                     aria-valuemin="<?php echo esc_attr($config['min']); ?>"
                     aria-valuemax="<?php echo esc_attr($config['max']); ?>"
                     aria-valuenow="<?php echo esc_attr($thumb_value); ?>"
                     aria-valuetext="<?php echo esc_attr($thumb_value . ($config['unit'] ? ' ' . $config['unit'] : '') . ' - ' . $thumb_label); ?>"
                     data-thumb-index="<?php echo esc_attr($index); ?>"
                     data-thumb-value="<?php echo esc_attr($thumb_value); ?>"
                     style="<?php echo $config['orientation'] === 'horizontal' ? 'left: ' . $thumb_percentage . '%' : 'bottom: ' . $thumb_percentage . '%'; ?>"
                     <?php echo $config['disabled'] ? 'aria-disabled="true"' : ''; ?>
                     <?php echo $config['required'] ? 'aria-required="true"' : ''; ?>
                     <?php echo !empty($config['aria_label']) ? 'aria-label="' . esc_attr($config['aria_label'] . ' - ' . $thumb_label) . '"' : ''; ?>>
                    
                    <?php if (!empty($config['show_tooltip'])): ?>
                        <div class="wcag-wp-slider-multithumb__tooltip" role="tooltip" aria-hidden="true">
                            <span class="wcag-wp-slider-multithumb__tooltip-text"><?php echo esc_html($thumb_value . ($config['unit'] ? ' ' . $config['unit'] : '') . ' - ' . $thumb_label); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($config['show_values']): ?>
                        <div class="wcag-wp-slider-multithumb__value" aria-hidden="true">
                            <?php echo esc_html($thumb_value . ($config['unit'] ? ' ' . $config['unit'] : '')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <span class="wcag-wp-slider-multithumb__thumb-label" aria-hidden="true"><?php echo esc_html($thumb_label); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (!empty($config['show_values'])): ?>
            <div class="wcag-wp-slider-multithumb__values" aria-live="polite" aria-atomic="true">
                <?php foreach ($thumbs as $index => $thumb): ?>
                    <div class="wcag-wp-slider-multithumb__value" data-thumb-index="<?php echo esc_attr($index); ?>">
                        <span class="wcag-wp-slider-multithumb__value-label"><?php echo esc_html($thumb['label']); ?>:</span>
                        <span class="wcag-wp-slider-multithumb__value-text"><?php echo esc_html($thumb['value'] . ($config['unit'] ? ' ' . $config['unit'] : '')); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="wcag-wp-slider-multithumb__validation" id="<?php echo esc_attr($unique_id); ?>-validation" aria-live="polite" aria-atomic="true" hidden></div>
    
    <div class="wcag-wp-sr-only" id="<?php echo esc_attr($unique_id); ?>-announcements" aria-live="polite" aria-atomic="true"></div>
</div>

<script type="application/json" class="wcag-slider-multithumb-config">
{
    "id": "<?php echo esc_js($unique_id); ?>",
    "post_id": <?php echo intval($post_id); ?>,
    "config": {
        "min": <?php echo floatval($config['min']); ?>,
        "max": <?php echo floatval($config['max']); ?>,
        "step": <?php echo floatval($config['step']); ?>,
        "thumbs": <?php echo json_encode($thumbs); ?>,
        "unit": "<?php echo esc_js($config['unit']); ?>",
        "orientation": "<?php echo esc_js($config['orientation']); ?>",
        "show_values": <?php echo $config['show_values'] ? 'true' : 'false'; ?>,
        "show_ticks": <?php echo $config['show_ticks'] ? 'true' : 'false'; ?>,
        "show_tooltip": <?php echo $config['show_tooltip'] ? 'true' : 'false'; ?>,
        "required": <?php echo $config['required'] ? 'true' : 'false'; ?>,
        "disabled": <?php echo $config['disabled'] ? 'true' : 'false'; ?>,
        "format": "<?php echo esc_js($config['format']); ?>",
        "on_change_callback": "<?php echo esc_js($config['on_change_callback']); ?>",
        "allow_overlap": <?php echo $config['allow_overlap'] ? 'true' : 'false'; ?>,
        "min_distance": <?php echo floatval($config['min_distance']); ?>
    }
}
</script>

