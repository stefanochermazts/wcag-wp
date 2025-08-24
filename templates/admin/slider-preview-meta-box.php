<div class="wcag-wp-preview-meta-box">
    <div class="wcag-wp-section">
        <h4><?php _e('Shortcode', 'wcag-wp'); ?></h4>
        <div class="shortcode-container">
            <input type="text" id="wcag-slider-shortcode" value='[wcag-slider id="<?php echo esc_attr($post->ID); ?>"]' readonly class="large-text code">
            <button type="button" class="button button-secondary copy-shortcode" data-clipboard-target="#wcag-slider-shortcode"><?php _e('Copia', 'wcag-wp'); ?></button>
        </div>
    </div>
    
    <div class="wcag-wp-section">
        <h4><?php _e('Anteprima Live', 'wcag-wp'); ?></h4>
        <div id="wcag-slider-preview" class="preview-container">
            <div class="wcag-wp-slider wcag-wp-slider--preview">
                <?php if (!empty($config['label'])): ?>
                    <label class="wcag-wp-slider__label"><?php echo esc_html($config['label']); ?></label>
                <?php endif; ?>
                
                <div class="wcag-wp-slider__container">
                    <div class="wcag-wp-slider__track">
                        <div class="wcag-wp-slider__thumb" 
                             role="slider" 
                             tabindex="0"
                             aria-valuemin="<?php echo esc_attr($config['min']); ?>"
                             aria-valuemax="<?php echo esc_attr($config['max']); ?>"
                             aria-valuenow="<?php echo esc_attr($config['default_value']); ?>"
                             aria-valuetext="<?php echo esc_attr($config['default_value'] . ($config['unit'] ? ' ' . $config['unit'] : '')); ?>"
                             <?php echo !empty($config['aria_label']) ? 'aria-label="' . esc_attr($config['aria_label']) . '"' : ''; ?>>
                        </div>
                    </div>
                    
                    <?php if (!empty($config['show_value'])): ?>
                        <div class="wcag-wp-slider__value">
                            <span class="wcag-wp-slider__value-text"><?php echo esc_html($config['default_value'] . ($config['unit'] ? ' ' . $config['unit'] : '')); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($config['description'])): ?>
                    <div class="wcag-wp-slider__description"><?php echo esc_html($config['description']); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="wcag-wp-section">
        <h4><?php _e('Configurazione Attuale', 'wcag-wp'); ?></h4>
        <div class="config-summary">
            <p><strong><?php _e('Etichetta:', 'wcag-wp'); ?></strong> <?php echo !empty($config['label']) ? esc_html($config['label']) : __('Non impostata', 'wcag-wp'); ?></p>
            <p><strong><?php _e('Range:', 'wcag-wp'); ?></strong> <?php echo esc_html($config['min']) . ' - ' . esc_html($config['max']); ?></p>
            <p><strong><?php _e('Valore Default:', 'wcag-wp'); ?></strong> <?php echo esc_html($config['default_value'] . ($config['unit'] ? ' ' . $config['unit'] : '')); ?></p>
            <p><strong><?php _e('Passo:', 'wcag-wp'); ?></strong> <?php echo esc_html($config['step']); ?></p>
            <p><strong><?php _e('Orientamento:', 'wcag-wp'); ?></strong> <?php echo esc_html(ucfirst($config['orientation'])); ?></p>
            <p><strong><?php _e('Dimensione:', 'wcag-wp'); ?></strong> <?php echo esc_html(ucfirst($config['size'])); ?></p>
            <p><strong><?php _e('Tema:', 'wcag-wp'); ?></strong> <?php echo esc_html(ucfirst($config['theme'])); ?></p>
            <p><strong><?php _e('Mostra Valore:', 'wcag-wp'); ?></strong> <?php echo $config['show_value'] ? __('Sì', 'wcag-wp') : __('No', 'wcag-wp'); ?></p>
            <p><strong><?php _e('Mostra Tacche:', 'wcag-wp'); ?></strong> <?php echo $config['show_ticks'] ? __('Sì', 'wcag-wp') : __('No', 'wcag-wp'); ?></p>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Copy shortcode functionality
    $('.copy-shortcode').on('click', function() {
        var target = $(this).data('clipboard-target');
        var text = $(target).val();
        
        // Create temporary input
        var temp = $('<input>');
        $('body').append(temp);
        temp.val(text).select();
        document.execCommand('copy');
        temp.remove();
        
        // Show feedback
        var button = $(this);
        var originalText = button.text();
        button.text('<?php _e('Copiato!', 'wcag-wp'); ?>').addClass('copied');
        setTimeout(function() {
            button.text(originalText).removeClass('copied');
        }, 2000);
    });
    
    // Live preview update
    function updatePreview() {
        var label = $('#wcag_slider_label').val();
        var description = $('#wcag_slider_description').val();
        var min = parseFloat($('#wcag_slider_min').val()) || 0;
        var max = parseFloat($('#wcag_slider_max').val()) || 100;
        var defaultValue = parseFloat($('#wcag_slider_default_value').val()) || 50;
        var unit = $('#wcag_slider_unit').val();
        var showValue = $('#wcag_slider_config\\[show_value\\]').is(':checked');
        var orientation = $('#wcag_slider_orientation').val();
        var size = $('#wcag_slider_size').val();
        var theme = $('#wcag_slider_theme').val();
        
        // Update preview
        var preview = $('#wcag-slider-preview .wcag-wp-slider');
        
        // Update label
        var labelEl = preview.find('.wcag-wp-slider__label');
        if (label) {
            if (labelEl.length) {
                labelEl.text(label);
            } else {
                preview.prepend('<label class="wcag-wp-slider__label">' + label + '</label>');
            }
        } else {
            labelEl.remove();
        }
        
        // Update description
        var descEl = preview.find('.wcag-wp-slider__description');
        if (description) {
            if (descEl.length) {
                descEl.text(description);
            } else {
                preview.append('<div class="wcag-wp-slider__description">' + description + '</div>');
            }
        } else {
            descEl.remove();
        }
        
        // Update value display
        var valueEl = preview.find('.wcag-wp-slider__value');
        if (showValue) {
            var valueText = defaultValue + (unit ? ' ' + unit : '');
            if (valueEl.length) {
                valueEl.find('.wcag-wp-slider__value-text').text(valueText);
            } else {
                preview.find('.wcag-wp-slider__container').append('<div class="wcag-wp-slider__value"><span class="wcag-wp-slider__value-text">' + valueText + '</span></div>');
            }
        } else {
            valueEl.remove();
        }
        
        // Update slider attributes
        var thumb = preview.find('.wcag-wp-slider__thumb');
        thumb.attr('aria-valuemin', min);
        thumb.attr('aria-valuemax', max);
        thumb.attr('aria-valuenow', defaultValue);
        thumb.attr('aria-valuetext', defaultValue + (unit ? ' ' + unit : ''));
        
        // Update thumb position
        var percentage = ((defaultValue - min) / (max - min)) * 100;
        thumb.css('left', percentage + '%');
        
        // Update classes
        preview.removeClass('wcag-wp-slider--horizontal wcag-wp-slider--vertical')
               .addClass('wcag-wp-slider--' + orientation);
        
        preview.removeClass('wcag-wp-slider--small wcag-wp-slider--medium wcag-wp-slider--large')
               .addClass('wcag-wp-slider--' + size);
        
        preview.removeClass('wcag-wp-slider--default wcag-wp-slider--success wcag-wp-slider--warning wcag-wp-slider--danger')
               .addClass('wcag-wp-slider--' + theme);
    }
    
    // Bind events
    $('#wcag_slider_label, #wcag_slider_description, #wcag_slider_min, #wcag_slider_max, #wcag_slider_default_value, #wcag_slider_unit, #wcag_slider_orientation, #wcag_slider_size, #wcag_slider_theme').on('input change', updatePreview);
    $('#wcag_slider_config\\[show_value\\]').on('change', updatePreview);
    
    // Initial update
    updatePreview();
});
</script>
