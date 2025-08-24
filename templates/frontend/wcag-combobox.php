<?php
/**
 * Template: WCAG Combobox Frontend
 * 
 * Renders accessible combobox following WCAG APG pattern
 * 
 * @package WCAG_WP
 * @var WP_Post $combobox Combobox post object
 * @var array $config Combobox configuration
 * @var array $options Combobox options (for static data source)
 * @var string $field_name Form field name
 * @var string $atts Shortcode attributes
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Generate unique IDs for ARIA
$combobox_id = 'wcag-combobox-' . $combobox->ID;
$input_id = $combobox_id . '-input';
$listbox_id = $combobox_id . '-listbox';
$label_id = $combobox_id . '-label';
$description_id = $combobox_id . '-description';
$status_id = $combobox_id . '-status';

// Get type info
$type_info = WCAG_WP_Combobox::get_types()[$config['type']] ?? WCAG_WP_Combobox::get_types()['autocomplete'];

// Build CSS classes
$css_classes = [
    'wcag-wp-combobox',
    'wcag-wp-combobox--' . esc_attr($config['type']),
    'wcag-wp-combobox--' . esc_attr($config['data_source'])
];

if ($config['multiple']) {
    $css_classes[] = 'wcag-wp-combobox--multiple';
}

if ($config['required']) {
    $css_classes[] = 'wcag-wp-combobox--required';
}

if (!empty($config['custom_class'])) {
    $css_classes[] = esc_attr($config['custom_class']);
}

// Prepare data attributes for JavaScript
$data_attributes = [
    'data-combobox-id' => esc_attr($combobox->ID),
    'data-combobox-type' => esc_attr($config['type']),
    'data-autocomplete' => esc_attr($config['autocomplete_behavior']),
    'data-multiple' => $config['multiple'] ? 'true' : 'false',
    'data-required' => $config['required'] ? 'true' : 'false',
    'data-max-results' => esc_attr($config['max_results']),
    'data-min-chars' => esc_attr($config['min_chars']),
    'data-debounce-delay' => esc_attr($config['debounce_delay']),
    'data-case-sensitive' => $config['case_sensitive'] ? 'true' : 'false',
    'data-data-source' => esc_attr($config['data_source'])
];

// Prepare initial options for static data source
$initial_options = [];
if ($config['data_source'] === 'static' && is_array($options)) {
    $initial_options = array_slice($options, 0, $config['max_results']);
}

// Get pre-selected value
$selected_value = $atts['value'] ?? '';
$selected_values = [];
if ($config['multiple'] && !empty($selected_value)) {
    $selected_values = explode(',', $selected_value);
} elseif (!empty($selected_value)) {
    $selected_values = [$selected_value];
}
?>

<div id="<?php echo esc_attr($combobox_id); ?>" 
     class="<?php echo esc_attr(implode(' ', $css_classes)); ?>"
     <?php foreach ($data_attributes as $attr => $value): ?>
         <?php echo esc_attr($attr); ?>="<?php echo esc_attr($value); ?>"
     <?php endforeach; ?>>
    
    <!-- Label -->
    <label id="<?php echo esc_attr($label_id); ?>" 
           for="<?php echo esc_attr($input_id); ?>" 
           class="wcag-wp-combobox__label">
        <?php echo wp_kses_post($combobox->post_title); ?>
        <?php if ($config['required']): ?>
            <span class="wcag-wp-combobox__required" aria-label="<?php esc_attr_e('Campo obbligatorio', 'wcag-wp'); ?>">*</span>
        <?php endif; ?>
    </label>
    
    <!-- Description/Help Text -->
    <?php if (!empty($combobox->post_content)): ?>
        <div id="<?php echo esc_attr($description_id); ?>" 
             class="wcag-wp-combobox__description">
            <?php echo wp_kses_post(apply_filters('the_content', $combobox->post_content)); ?>
        </div>
    <?php endif; ?>
    
    <!-- Combobox Container -->
    <div class="wcag-wp-combobox__container">
        
        <!-- Input Field -->
        <input type="text" 
               id="<?php echo esc_attr($input_id); ?>"
               name="<?php echo esc_attr($field_name); ?><?php echo $config['multiple'] ? '[]' : ''; ?>"
               class="wcag-wp-combobox__input"
               role="combobox"
               aria-expanded="false"
               aria-autocomplete="<?php echo esc_attr($config['autocomplete_behavior']); ?>"
               aria-controls="<?php echo esc_attr($listbox_id); ?>"
               aria-labelledby="<?php echo esc_attr($label_id); ?>"
               <?php if (!empty($combobox->post_content)): ?>
                   aria-describedby="<?php echo esc_attr($description_id); ?> <?php echo esc_attr($status_id); ?>"
               <?php else: ?>
                   aria-describedby="<?php echo esc_attr($status_id); ?>"
               <?php endif; ?>
               <?php if ($config['required']): ?>
                   required aria-required="true"
               <?php endif; ?>
               <?php if (!empty($config['placeholder'])): ?>
                   placeholder="<?php echo esc_attr($config['placeholder']); ?>"
               <?php endif; ?>
               autocomplete="off"
               spellcheck="false">
        
        <!-- Toggle Button -->
        <button type="button" 
                class="wcag-wp-combobox__toggle"
                aria-label="<?php esc_attr_e('Mostra opzioni', 'wcag-wp'); ?>"
                tabindex="-1">
            <span class="wcag-wp-combobox__toggle-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M4.427 9.573l3.396-3.396a.25.25 0 01.354 0l3.396 3.396a.25.25 0 01-.177.427H4.604a.25.25 0 01-.177-.427z"/>
                </svg>
            </span>
        </button>
        
        <!-- Loading Indicator -->
        <div class="wcag-wp-combobox__loading" aria-hidden="true">
            <span class="wcag-wp-combobox__spinner"></span>
        </div>
        
    </div>
    
    <!-- Selected Items (for multiple selection) -->
    <?php if ($config['multiple']): ?>
        <div class="wcag-wp-combobox__selected" 
             role="region" 
             aria-label="<?php esc_attr_e('Elementi selezionati', 'wcag-wp'); ?>">
            <!-- Selected items will be inserted here by JavaScript -->
        </div>
    <?php endif; ?>
    
    <!-- Listbox Popup -->
    <div id="<?php echo esc_attr($listbox_id); ?>" 
         class="wcag-wp-combobox__listbox"
         role="listbox"
         <?php if ($config['multiple']): ?>
             aria-multiselectable="true"
         <?php endif; ?>
         aria-labelledby="<?php echo esc_attr($label_id); ?>"
         hidden>
        
        <!-- Options will be inserted here by JavaScript -->
        <?php if ($config['data_source'] === 'static' && !empty($initial_options)): ?>
            <?php foreach ($initial_options as $index => $option): ?>
                <div class="wcag-wp-combobox__option" 
                     role="option"
                     id="<?php echo esc_attr($combobox_id . '-option-' . $index); ?>"
                     data-value="<?php echo esc_attr($option['value'] ?? ''); ?>"
                     <?php if (!empty($option['group'])): ?>
                         data-group="<?php echo esc_attr($option['group']); ?>"
                     <?php endif; ?>
                     <?php if ($option['disabled'] ?? false): ?>
                         aria-disabled="true"
                     <?php endif; ?>
                     <?php if (in_array($option['value'] ?? '', $selected_values, true)): ?>
                         aria-selected="true"
                     <?php else: ?>
                         aria-selected="false"
                     <?php endif; ?>>
                    
                    <div class="wcag-wp-combobox__option-content">
                        <div class="wcag-wp-combobox__option-label">
                            <?php echo esc_html($option['label'] ?? $option['value'] ?? ''); ?>
                        </div>
                        
                        <?php if (!empty($option['description'])): ?>
                            <div class="wcag-wp-combobox__option-description">
                                <?php echo esc_html($option['description']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($option['group'])): ?>
                            <div class="wcag-wp-combobox__option-group">
                                <?php echo esc_html($option['group']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($config['multiple']): ?>
                        <div class="wcag-wp-combobox__option-checkbox" aria-hidden="true">
                            <span class="wcag-wp-combobox__checkbox-icon">
                                <?php if (in_array($option['value'] ?? '', $selected_values, true)): ?>
                                    ✓
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <!-- No Results Message -->
        <div class="wcag-wp-combobox__no-results" role="option" hidden>
            <div class="wcag-wp-combobox__no-results-content">
                <span class="wcag-wp-combobox__no-results-icon" aria-hidden="true">🔍</span>
                <span class="wcag-wp-combobox__no-results-text">
                    <?php esc_html_e('Nessun risultato trovato', 'wcag-wp'); ?>
                </span>
            </div>
        </div>
        
    </div>
    
    <!-- Status/Announcements for Screen Readers -->
    <div id="<?php echo esc_attr($status_id); ?>" 
         class="wcag-wp-sr-only" 
         aria-live="polite" 
         aria-atomic="true">
        <!-- Status messages will be inserted here by JavaScript -->
    </div>
    
    <!-- Hidden Inputs for Form Submission -->
    <?php if ($config['multiple']): ?>
        <div class="wcag-wp-combobox__hidden-inputs">
            <!-- Hidden inputs for selected values will be inserted here by JavaScript -->
            <?php foreach ($selected_values as $value): ?>
                <input type="hidden" 
                       name="<?php echo esc_attr($field_name); ?>[]" 
                       value="<?php echo esc_attr($value); ?>">
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <input type="hidden" 
               name="<?php echo esc_attr($field_name); ?>_value" 
               value="<?php echo esc_attr($selected_value); ?>">
    <?php endif; ?>
    
</div>

<!-- Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const combobox = document.getElementById('<?php echo esc_js($combobox_id); ?>');
    if (combobox && typeof window.wcagInitCombobox === 'function') {
        window.wcagInitCombobox(combobox);
    }
});
</script>

<?php
// Add inline styles for custom styling if needed
if (!empty($config['custom_styles'])):
?>
<style>
#<?php echo esc_attr($combobox_id); ?> {
    <?php echo wp_kses($config['custom_styles'], []); ?>
}
</style>
<?php endif; ?>

