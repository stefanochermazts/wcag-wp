<div class="wcag-wp-switch <?php echo esc_attr($config['custom_class']); ?> wcag-wp-switch--<?php echo esc_attr($config['size']); ?> wcag-wp-switch--<?php echo esc_attr($config['theme']); ?>">
    <?php if (!empty($config['label'])): ?>
        <label class="wcag-wp-switch__label" for="<?php echo esc_attr($unique_id); ?>">
            <?php echo esc_html($config['label']); ?>
            <?php if ($config['required']): ?>
                <span class="wcag-wp-switch__required" aria-label="<?php _e('obbligatorio', 'wcag-wp'); ?>">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>
    
    <?php if (!empty($config['description'])): ?>
        <div class="wcag-wp-switch__description" id="<?php echo esc_attr($unique_id); ?>-description">
            <?php echo esc_html($config['description']); ?>
        </div>
    <?php endif; ?>
    
    <div class="wcag-wp-switch__container">
        <button type="button" 
                id="<?php echo esc_attr($unique_id); ?>"
                class="wcag-wp-switch__toggle <?php echo $config['default_state'] === 'on' ? 'wcag-wp-switch__toggle--active' : ''; ?>"
                role="switch" 
                aria-checked="<?php echo $config['default_state'] === 'on' ? 'true' : 'false'; ?>"
                <?php echo $config['disabled'] ? 'disabled' : ''; ?>
                <?php echo $config['required'] ? 'aria-required="true"' : ''; ?>
                <?php echo !empty($config['aria_label']) ? 'aria-label="' . esc_attr($config['aria_label']) . '"' : ''; ?>
                <?php echo !empty($config['aria_describedby']) ? 'aria-describedby="' . esc_attr($config['aria_describedby']) . '"' : ''; ?>
                <?php echo !empty($config['description']) ? 'aria-describedby="' . esc_attr($unique_id) . '-description"' : ''; ?>>
            
            <span class="wcag-wp-switch__track" aria-hidden="true"></span>
            <span class="wcag-wp-switch__thumb" aria-hidden="true"></span>
            
            <span class="wcag-wp-sr-only">
                <?php echo $config['default_state'] === 'on' ? esc_html($config['on_text']) : esc_html($config['off_text']); ?>
            </span>
        </button>
        
        <?php if (!empty($config['show_labels'])): ?>
            <span class="wcag-wp-switch__status" aria-hidden="true">
                <?php echo $config['default_state'] === 'on' ? esc_html($config['on_text']) : esc_html($config['off_text']); ?>
            </span>
        <?php endif; ?>
    </div>
    
    <div class="wcag-wp-switch__validation" id="<?php echo esc_attr($unique_id); ?>-validation" aria-live="polite" aria-atomic="true" hidden></div>
    
    <div class="wcag-wp-sr-only" id="<?php echo esc_attr($unique_id); ?>-announcements" aria-live="polite" aria-atomic="true"></div>
</div>

<script type="application/json" class="wcag-switch-config">
{
    "id": "<?php echo esc_js($unique_id); ?>",
    "post_id": <?php echo intval($post_id); ?>,
    "config": {
        "on_text": "<?php echo esc_js($config['on_text']); ?>",
        "off_text": "<?php echo esc_js($config['off_text']); ?>",
        "default_state": "<?php echo esc_js($config['default_state']); ?>",
        "required": <?php echo $config['required'] ? 'true' : 'false'; ?>,
        "disabled": <?php echo $config['disabled'] ? 'true' : 'false'; ?>,
        "show_labels": <?php echo $config['show_labels'] ? 'true' : 'false'; ?>,
        "animation": <?php echo $config['animation'] ? 'true' : 'false'; ?>,
        "theme": "<?php echo esc_js($config['theme']); ?>",
        "on_change_callback": "<?php echo esc_js($config['on_change_callback']); ?>"
    }
}
</script>
