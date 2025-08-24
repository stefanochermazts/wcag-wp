<?php
/**
 * Spinbutton Preview Meta Box Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-preview-meta-box">
    
    <!-- Shortcode Section -->
    <div class="wcag-wp-section">
        <h4><?php _e('Shortcode', 'wcag-wp'); ?></h4>
        <div class="shortcode-container">
            <input type="text" 
                   id="wcag-spinbutton-shortcode" 
                   value='[wcag-spinbutton id="<?php echo esc_attr($post->ID); ?>"]' 
                   readonly 
                   class="large-text code">
            <button type="button" 
                    class="button button-secondary copy-shortcode" 
                    data-clipboard-target="#wcag-spinbutton-shortcode">
                <?php _e('Copia', 'wcag-wp'); ?>
            </button>
        </div>
        <p class="description">
            <?php _e('Copia questo shortcode e incollalo nella tua pagina o post.', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Live Preview Section -->
    <div class="wcag-wp-section">
        <h4><?php _e('Anteprima Live', 'wcag-wp'); ?></h4>
        <div id="wcag-spinbutton-preview" class="preview-container">
            <!-- Preview will be loaded here via JavaScript -->
            <div class="wcag-wp-spinbutton wcag-wp-spinbutton--preview">
                <?php if (!empty($config['label'])): ?>
                    <label class="wcag-wp-spinbutton__label" for="preview-spinbutton">
                        <?php echo esc_html($config['label']); ?>
                        <?php if ($config['required']): ?>
                            <span class="wcag-wp-spinbutton__required">*</span>
                        <?php endif; ?>
                    </label>
                <?php endif; ?>
                
                <?php if (!empty($config['description'])): ?>
                    <div class="wcag-wp-spinbutton__description">
                        <?php echo esc_html($config['description']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="wcag-wp-spinbutton__container">
                    <input type="number" 
                           id="preview-spinbutton"
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
                           <?php echo !empty($config['aria_describedby']) ? 'aria-describedby="' . esc_attr($config['aria_describedby']) . '"' : ''; ?>>
                    
                    <div class="wcag-wp-spinbutton__controls">
                        <button type="button" 
                                class="wcag-wp-spinbutton__button wcag-wp-spinbutton__button--increment"
                                aria-label="<?php _e('Incrementa', 'wcag-wp'); ?>"
                                <?php echo $config['disabled'] ? 'disabled' : ''; ?>>
                            <span class="wcag-wp-spinbutton__button-icon">▲</span>
                        </button>
                        <button type="button" 
                                class="wcag-wp-spinbutton__button wcag-wp-spinbutton__button--decrement"
                                aria-label="<?php _e('Decrementa', 'wcag-wp'); ?>"
                                <?php echo $config['disabled'] ? 'disabled' : ''; ?>>
                            <span class="wcag-wp-spinbutton__button-icon">▼</span>
                        </button>
                    </div>
                    
                    <?php if (!empty($config['unit'])): ?>
                        <span class="wcag-wp-spinbutton__unit"><?php echo esc_html($config['unit']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Configuration Summary -->
    <div class="wcag-wp-section">
        <h4><?php _e('Riepilogo Configurazione', 'wcag-wp'); ?></h4>
        <ul class="config-summary">
            <li><strong><?php _e('Tipo:', 'wcag-wp'); ?></strong> 
                <?php 
                $types = array(
                    'integer' => __('Numero Intero', 'wcag-wp'),
                    'decimal' => __('Numero Decimale', 'wcag-wp'),
                    'currency' => __('Valuta', 'wcag-wp'),
                    'percentage' => __('Percentuale', 'wcag-wp'),
                    'time' => __('Tempo (minuti)', 'wcag-wp'),
                    'date' => __('Data (giorni)', 'wcag-wp')
                );
                echo esc_html($types[$config['type']] ?? $config['type']);
                ?>
            </li>
            <li><strong><?php _e('Range:', 'wcag-wp'); ?></strong> 
                <?php echo esc_html($config['min']); ?> - <?php echo esc_html($config['max']); ?>
            </li>
            <li><strong><?php _e('Incremento:', 'wcag-wp'); ?></strong> 
                <?php echo esc_html($config['step']); ?>
            </li>
            <li><strong><?php _e('Default:', 'wcag-wp'); ?></strong> 
                <?php echo esc_html($config['default_value']); ?>
                <?php if (!empty($config['unit'])): ?>
                    <?php echo esc_html($config['unit']); ?>
                <?php endif; ?>
            </li>
            <li><strong><?php _e('Dimensione:', 'wcag-wp'); ?></strong> 
                <?php 
                $sizes = array(
                    'small' => __('Piccola', 'wcag-wp'),
                    'medium' => __('Media', 'wcag-wp'),
                    'large' => __('Grande', 'wcag-wp')
                );
                echo esc_html($sizes[$config['size']] ?? $config['size']);
                ?>
            </li>
            <li><strong><?php _e('Stato:', 'wcag-wp'); ?></strong> 
                <?php
                $states = array();
                if ($config['required']) $states[] = __('Obbligatorio', 'wcag-wp');
                if ($config['readonly']) $states[] = __('Solo lettura', 'wcag-wp');
                if ($config['disabled']) $states[] = __('Disabilitato', 'wcag-wp');
                if (empty($states)) $states[] = __('Normale', 'wcag-wp');
                echo esc_html(implode(', ', $states));
                ?>
            </li>
        </ul>
    </div>
    
</div>

<style>
.wcag-wp-preview-meta-box .wcag-wp-section {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.wcag-wp-preview-meta-box .wcag-wp-section h4 {
    margin-top: 0;
    margin-bottom: 1rem;
    color: #23282d;
    border-bottom: 1px solid #ddd;
    padding-bottom: 0.5rem;
}

.shortcode-container {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.shortcode-container input {
    flex: 1;
    font-family: monospace;
    background: #fff;
}

.copy-shortcode {
    white-space: nowrap;
}

.preview-container {
    background: #fff;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    min-height: 120px;
}

.config-summary {
    margin: 0;
    padding: 0;
    list-style: none;
}

.config-summary li {
    padding: 0.25rem 0;
    border-bottom: 1px solid #eee;
}

.config-summary li:last-child {
    border-bottom: none;
}

.config-summary strong {
    color: #23282d;
    min-width: 80px;
    display: inline-block;
}

/* Preview Spinbutton Styles */
.wcag-wp-spinbutton--preview {
    max-width: 300px;
}

.wcag-wp-spinbutton__container {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.wcag-wp-spinbutton__input {
    flex: 1;
    min-height: 44px;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.wcag-wp-spinbutton__input--small {
    min-height: 36px;
    padding: 0.5rem;
    font-size: 0.875rem;
}

.wcag-wp-spinbutton__input--large {
    min-height: 52px;
    padding: 1rem;
    font-size: 1.125rem;
}

.wcag-wp-spinbutton__controls {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.wcag-wp-spinbutton__button {
    width: 32px;
    height: 20px;
    border: 1px solid #ddd;
    background: #f9f9f9;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    transition: background-color 0.2s;
}

.wcag-wp-spinbutton__button:hover {
    background: #e9e9e9;
}

.wcag-wp-spinbutton__button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.wcag-wp-spinbutton__unit {
    color: #666;
    font-size: 0.875rem;
    white-space: nowrap;
}

.wcag-wp-spinbutton__label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #23282d;
}

.wcag-wp-spinbutton__required {
    color: #d63638;
    margin-left: 0.25rem;
}

.wcag-wp-spinbutton__description {
    font-size: 0.875rem;
    color: #666;
    margin-bottom: 0.5rem;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Copy shortcode functionality
    $('.copy-shortcode').on('click', function() {
        const shortcodeInput = $('#wcag-spinbutton-shortcode');
        shortcodeInput.select();
        document.execCommand('copy');
        
        const button = $(this);
        const originalText = button.text();
        button.text('Copiato!');
        button.addClass('button-primary');
        
        setTimeout(function() {
            button.text(originalText);
            button.removeClass('button-primary');
        }, 2000);
    });
    
    // Live preview functionality
    function updatePreview() {
        // This will be enhanced with JavaScript to show real-time updates
        console.log('Preview updated');
    }
    
    // Update preview when configuration changes
    $('input, select, textarea').on('change', function() {
        updatePreview();
    });
});
</script>
