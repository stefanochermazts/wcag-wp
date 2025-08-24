<div class="wcag-wp-preview-meta-box">
    <div class="wcag-wp-section">
        <h4><?php _e('Shortcode', 'wcag-wp'); ?></h4>
        <div class="shortcode-container">
            <input type="text" id="wcag-switch-shortcode" value='[wcag-switch id="<?php echo esc_attr($post->ID); ?>"]' readonly class="large-text code">
            <button type="button" class="button button-secondary copy-shortcode" data-clipboard-target="#wcag-switch-shortcode"><?php _e('Copia', 'wcag-wp'); ?></button>
        </div>
    </div>
    
    <div class="wcag-wp-section">
        <h4><?php _e('Anteprima Live', 'wcag-wp'); ?></h4>
        <div id="wcag-switch-preview" class="preview-container">
            <div class="wcag-wp-switch wcag-wp-switch--preview">
                <?php if (!empty($config['label'])): ?>
                    <label class="wcag-wp-switch__label"><?php echo esc_html($config['label']); ?></label>
                <?php endif; ?>
                
                <div class="wcag-wp-switch__container">
                    <button type="button" class="wcag-wp-switch__toggle" 
                            role="switch" 
                            aria-checked="<?php echo $config['default_state'] === 'on' ? 'true' : 'false'; ?>"
                            <?php echo !empty($config['aria_label']) ? 'aria-label="' . esc_attr($config['aria_label']) . '"' : ''; ?>>
                        <span class="wcag-wp-switch__track"></span>
                        <span class="wcag-wp-switch__thumb"></span>
                    </button>
                    
                    <?php if (!empty($config['show_labels'])): ?>
                        <span class="wcag-wp-switch__status">
                            <?php echo $config['default_state'] === 'on' ? esc_html($config['on_text']) : esc_html($config['off_text']); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($config['description'])): ?>
                    <div class="wcag-wp-switch__description"><?php echo esc_html($config['description']); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="wcag-wp-section">
        <h4><?php _e('Configurazione Attuale', 'wcag-wp'); ?></h4>
        <div class="config-summary">
            <p><strong><?php _e('Etichetta:', 'wcag-wp'); ?></strong> <?php echo !empty($config['label']) ? esc_html($config['label']) : __('Non impostata', 'wcag-wp'); ?></p>
            <p><strong><?php _e('Stato Default:', 'wcag-wp'); ?></strong> <?php echo $config['default_state'] === 'on' ? __('Attivo', 'wcag-wp') : __('Inattivo', 'wcag-wp'); ?></p>
            <p><strong><?php _e('Dimensione:', 'wcag-wp'); ?></strong> <?php echo esc_html(ucfirst($config['size'])); ?></p>
            <p><strong><?php _e('Tema:', 'wcag-wp'); ?></strong> <?php echo esc_html(ucfirst($config['theme'])); ?></p>
            <p><strong><?php _e('Obbligatorio:', 'wcag-wp'); ?></strong> <?php echo $config['required'] ? __('Sì', 'wcag-wp') : __('No', 'wcag-wp'); ?></p>
            <p><strong><?php _e('Disabilitato:', 'wcag-wp'); ?></strong> <?php echo $config['disabled'] ? __('Sì', 'wcag-wp') : __('No', 'wcag-wp'); ?></p>
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
        var label = $('#wcag_switch_label').val();
        var description = $('#wcag_switch_description').val();
        var onText = $('#wcag_switch_on_text').val();
        var offText = $('#wcag_switch_off_text').val();
        var defaultState = $('#wcag_switch_default_state').val();
        var showLabels = $('#wcag_switch_config\\[show_labels\\]').is(':checked');
        var size = $('#wcag_switch_size').val();
        var theme = $('#wcag_switch_theme').val();
        
        // Update preview
        var preview = $('#wcag-switch-preview .wcag-wp-switch');
        
        // Update label
        var labelEl = preview.find('.wcag-wp-switch__label');
        if (label) {
            if (labelEl.length) {
                labelEl.text(label);
            } else {
                preview.prepend('<label class="wcag-wp-switch__label">' + label + '</label>');
            }
        } else {
            labelEl.remove();
        }
        
        // Update description
        var descEl = preview.find('.wcag-wp-switch__description');
        if (description) {
            if (descEl.length) {
                descEl.text(description);
            } else {
                preview.append('<div class="wcag-wp-switch__description">' + description + '</div>');
            }
        } else {
            descEl.remove();
        }
        
        // Update status text
        var statusEl = preview.find('.wcag-wp-switch__status');
        if (showLabels) {
            var statusText = defaultState === 'on' ? onText : offText;
            if (statusEl.length) {
                statusEl.text(statusText);
            } else {
                preview.find('.wcag-wp-switch__container').append('<span class="wcag-wp-switch__status">' + statusText + '</span>');
            }
        } else {
            statusEl.remove();
        }
        
        // Update switch state
        var toggle = preview.find('.wcag-wp-switch__toggle');
        toggle.attr('aria-checked', defaultState === 'on' ? 'true' : 'false');
        if (defaultState === 'on') {
            toggle.addClass('wcag-wp-switch__toggle--active');
        } else {
            toggle.removeClass('wcag-wp-switch__toggle--active');
        }
        
        // Update classes
        preview.removeClass('wcag-wp-switch--small wcag-wp-switch--medium wcag-wp-switch--large')
               .addClass('wcag-wp-switch--' + size);
        
        preview.removeClass('wcag-wp-switch--default wcag-wp-switch--success wcag-wp-switch--warning wcag-wp-switch--danger')
               .addClass('wcag-wp-switch--' + theme);
    }
    
    // Bind events
    $('#wcag_switch_label, #wcag_switch_description, #wcag_switch_on_text, #wcag_switch_off_text, #wcag_switch_default_state, #wcag_switch_size, #wcag_switch_theme').on('input change', updatePreview);
    $('#wcag_switch_config\\[show_labels\\]').on('change', updatePreview);
    
    // Initial update
    updatePreview();
});
</script>
