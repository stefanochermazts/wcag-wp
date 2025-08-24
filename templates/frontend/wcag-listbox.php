<?php
/**
 * Template: WCAG Listbox Frontend
 * 
 * Renders accessible listbox following WCAG APG pattern
 * 
 * @package WCAG_WP
 * @var WP_Post $listbox Listbox post object
 * @var array $config Listbox configuration
 * @var array $options Listbox options (for static data source)
 * @var string $field_name Form field name
 * @var string $atts Shortcode attributes
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Generate unique IDs for ARIA
$listbox_id = 'wcag-listbox-' . $listbox->ID;
$container_id = $listbox_id . '-container';
$label_id = $listbox_id . '-label';
$description_id = $listbox_id . '-description';
$status_id = $listbox_id . '-status';
$search_id = $listbox_id . '-search';

// Get type info
$type_info = WCAG_WP_Listbox::get_types()[$config['type']] ?? WCAG_WP_Listbox::get_types()['single'];

// Build CSS classes
$css_classes = [
    'wcag-wp-listbox',
    'wcag-wp-listbox--' . esc_attr($config['type']),
    'wcag-wp-listbox--' . esc_attr($config['orientation']),
    'wcag-wp-listbox--' . esc_attr($config['data_source'])
];

if ($config['required']) {
    $css_classes[] = 'wcag-wp-listbox--required';
}

if ($config['enable_search']) {
    $css_classes[] = 'wcag-wp-listbox--searchable';
}

if (!empty($config['custom_class'])) {
    $css_classes[] = esc_attr($config['custom_class']);
}

// Prepare data attributes for JavaScript
$data_attributes = [
    'data-listbox-id' => esc_attr($listbox->ID),
    'data-listbox-type' => esc_attr($config['type']),
    'data-multiselectable' => $type_info['multiselectable'] ? 'true' : 'false',
    'data-orientation' => esc_attr($config['orientation']),
    'data-selection-mode' => esc_attr($config['selection_mode']),
    'data-size' => esc_attr($config['size']),
    'data-required' => $config['required'] ? 'true' : 'false',
    'data-allow-deselect' => $config['allow_deselect'] ? 'true' : 'false',
    'data-wrap-navigation' => $config['wrap_navigation'] ? 'true' : 'false',
    'data-auto-select-first' => $config['auto_select_first'] ? 'true' : 'false',
    'data-show-selection-count' => $config['show_selection_count'] ? 'true' : 'false',
    'data-data-source' => esc_attr($config['data_source'])
];

// Get options based on data source
if ($config['data_source'] === 'static' && is_array($options)) {
    $listbox_options = $options;
} else {
    // For dynamic sources, options will be loaded via AJAX
    $listbox_options = [];
}

// Get pre-selected values
$selected_value = $atts['value'] ?? '';
$selected_values = [];
if (!empty($selected_value)) {
    $selected_values = explode(',', $selected_value);
}

// Group options if needed
$grouped_options = [];
$ungrouped_options = [];

foreach ($listbox_options as $option) {
    if (!empty($option['group'])) {
        if (!isset($grouped_options[$option['group']])) {
            $grouped_options[$option['group']] = [];
        }
        $grouped_options[$option['group']][] = $option;
    } else {
        $ungrouped_options[] = $option;
    }
}
?>

<div id="<?php echo esc_attr($container_id); ?>" 
     class="<?php echo esc_attr(implode(' ', $css_classes)); ?>"
     <?php foreach ($data_attributes as $attr => $value): ?>
         <?php echo esc_attr($attr); ?>="<?php echo esc_attr($value); ?>"
     <?php endforeach; ?>>
    
    <!-- Label -->
    <label id="<?php echo esc_attr($label_id); ?>" 
           for="<?php echo esc_attr($listbox_id); ?>" 
           class="wcag-wp-listbox__label">
        <?php echo wp_kses_post($listbox->post_title); ?>
        <?php if ($config['required']): ?>
            <span class="wcag-wp-listbox__required" aria-label="<?php esc_attr_e('Campo obbligatorio', 'wcag-wp'); ?>">*</span>
        <?php endif; ?>
    </label>
    
    <!-- Description/Help Text -->
    <?php if (!empty($listbox->post_content)): ?>
        <div id="<?php echo esc_attr($description_id); ?>" 
             class="wcag-wp-listbox__description">
            <?php echo wp_kses_post(apply_filters('the_content', $listbox->post_content)); ?>
        </div>
    <?php endif; ?>
    
    <!-- Search Field (if enabled) -->
    <?php if ($config['enable_search']): ?>
        <div class="wcag-wp-listbox__search-container">
            <label for="<?php echo esc_attr($search_id); ?>" class="wcag-wp-sr-only">
                <?php esc_html_e('Cerca nelle opzioni', 'wcag-wp'); ?>
            </label>
            <input type="text" 
                   id="<?php echo esc_attr($search_id); ?>"
                   class="wcag-wp-listbox__search"
                   placeholder="<?php echo esc_attr($config['search_placeholder'] ?: __('Cerca nelle opzioni...', 'wcag-wp')); ?>"
                   autocomplete="off">
            <span class="wcag-wp-listbox__search-icon" aria-hidden="true">🔍</span>
        </div>
    <?php endif; ?>
    
    <!-- Selection Count (if enabled) -->
    <?php if ($config['show_selection_count'] && $type_info['multiselectable']): ?>
        <div class="wcag-wp-listbox__selection-count" 
             id="<?php echo esc_attr($listbox_id); ?>-count"
             aria-live="polite">
            <span class="wcag-wp-listbox__count-text">
                <?php esc_html_e('Selezionati: 0', 'wcag-wp'); ?>
            </span>
        </div>
    <?php endif; ?>
    
    <!-- Listbox Container -->
    <div class="wcag-wp-listbox__container">
        <div id="<?php echo esc_attr($listbox_id); ?>" 
             class="wcag-wp-listbox__listbox"
             role="listbox"
             <?php if ($type_info['multiselectable']): ?>
                 aria-multiselectable="true"
             <?php endif; ?>
             <?php if ($config['orientation'] !== 'vertical'): ?>
                 aria-orientation="<?php echo esc_attr($config['orientation']); ?>"
             <?php endif; ?>
             aria-labelledby="<?php echo esc_attr($label_id); ?>"
             <?php if (!empty($listbox->post_content)): ?>
                 aria-describedby="<?php echo esc_attr($description_id); ?> <?php echo esc_attr($status_id); ?>"
             <?php else: ?>
                 aria-describedby="<?php echo esc_attr($status_id); ?>"
             <?php endif; ?>
             <?php if ($config['required']): ?>
                 aria-required="true"
             <?php endif; ?>
             tabindex="0"
             style="<?php if ($config['size'] > 0): ?>height: <?php echo esc_attr($config['size'] * 2.5); ?>rem;<?php endif; ?>">
            
            <?php if (!empty($ungrouped_options)): ?>
                <?php foreach ($ungrouped_options as $index => $option): ?>
                    <?php 
                    $option_id = $listbox_id . '-option-' . $index;
                    $is_selected = in_array($option['value'] ?? '', $selected_values, true) || ($option['selected'] ?? false);
                    ?>
                    
                    <!-- Separator Before -->
                    <?php if ($option['separator_before'] ?? false): ?>
                        <div class="wcag-wp-listbox__separator" role="separator"></div>
                    <?php endif; ?>
                    
                    <div class="wcag-wp-listbox__option" 
                         role="option"
                         id="<?php echo esc_attr($option_id); ?>"
                         data-value="<?php echo esc_attr($option['value'] ?? ''); ?>"
                         <?php if ($option['disabled'] ?? false): ?>
                             aria-disabled="true"
                         <?php endif; ?>
                         <?php if ($is_selected): ?>
                             aria-selected="true"
                         <?php else: ?>
                             aria-selected="false"
                         <?php endif; ?>>
                        
                        <div class="wcag-wp-listbox__option-content">
                            <div class="wcag-wp-listbox__option-label">
                                <?php echo esc_html($option['label'] ?? $option['value'] ?? ''); ?>
                            </div>
                            
                            <?php if (!empty($option['description'])): ?>
                                <div class="wcag-wp-listbox__option-description">
                                    <?php echo esc_html($option['description']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($type_info['multiselectable']): ?>
                            <div class="wcag-wp-listbox__option-checkbox" aria-hidden="true">
                                <span class="wcag-wp-listbox__checkbox-icon">
                                    <?php if ($is_selected): ?>✓<?php endif; ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                    
                    <!-- Separator After -->
                    <?php if ($option['separator_after'] ?? false): ?>
                        <div class="wcag-wp-listbox__separator" role="separator"></div>
                    <?php endif; ?>
                    
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (!empty($grouped_options)): ?>
                <?php foreach ($grouped_options as $group_name => $group_options): ?>
                    <div class="wcag-wp-listbox__group" role="group" aria-labelledby="<?php echo esc_attr($listbox_id . '-group-' . sanitize_title($group_name)); ?>">
                        
                        <div class="wcag-wp-listbox__group-label" 
                             id="<?php echo esc_attr($listbox_id . '-group-' . sanitize_title($group_name)); ?>"
                             role="presentation">
                            <?php echo esc_html($group_name); ?>
                        </div>
                        
                        <?php foreach ($group_options as $index => $option): ?>
                            <?php 
                            $option_id = $listbox_id . '-option-' . sanitize_title($group_name) . '-' . $index;
                            $is_selected = in_array($option['value'] ?? '', $selected_values, true) || ($option['selected'] ?? false);
                            ?>
                            
                            <!-- Separator Before -->
                            <?php if ($option['separator_before'] ?? false): ?>
                                <div class="wcag-wp-listbox__separator" role="separator"></div>
                            <?php endif; ?>
                            
                            <div class="wcag-wp-listbox__option" 
                                 role="option"
                                 id="<?php echo esc_attr($option_id); ?>"
                                 data-value="<?php echo esc_attr($option['value'] ?? ''); ?>"
                                 data-group="<?php echo esc_attr($group_name); ?>"
                                 <?php if ($option['disabled'] ?? false): ?>
                                     aria-disabled="true"
                                 <?php endif; ?>
                                 <?php if ($is_selected): ?>
                                     aria-selected="true"
                                 <?php else: ?>
                                     aria-selected="false"
                                 <?php endif; ?>>
                                
                                <div class="wcag-wp-listbox__option-content">
                                    <div class="wcag-wp-listbox__option-label">
                                        <?php echo esc_html($option['label'] ?? $option['value'] ?? ''); ?>
                                    </div>
                                    
                                    <?php if (!empty($option['description'])): ?>
                                        <div class="wcag-wp-listbox__option-description">
                                            <?php echo esc_html($option['description']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($type_info['multiselectable']): ?>
                                    <div class="wcag-wp-listbox__option-checkbox" aria-hidden="true">
                                        <span class="wcag-wp-listbox__checkbox-icon">
                                            <?php if ($is_selected): ?>✓<?php endif; ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                
                            </div>
                            
                            <!-- Separator After -->
                            <?php if ($option['separator_after'] ?? false): ?>
                                <div class="wcag-wp-listbox__separator" role="separator"></div>
                            <?php endif; ?>
                            
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <!-- No Options Message -->
            <?php if (empty($listbox_options)): ?>
                <div class="wcag-wp-listbox__no-options" role="option" aria-disabled="true">
                    <div class="wcag-wp-listbox__no-options-content">
                        <span class="wcag-wp-listbox__no-options-icon" aria-hidden="true">📋</span>
                        <span class="wcag-wp-listbox__no-options-text">
                            <?php esc_html_e('Nessuna opzione disponibile', 'wcag-wp'); ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
            
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
    <div class="wcag-wp-listbox__hidden-inputs">
        <?php if ($type_info['multiselectable']): ?>
            <!-- Multiple selection: create array of hidden inputs -->
            <?php foreach ($selected_values as $value): ?>
                <input type="hidden" 
                       name="<?php echo esc_attr($field_name); ?>[]" 
                       value="<?php echo esc_attr($value); ?>">
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Single selection: one hidden input -->
            <input type="hidden" 
                   name="<?php echo esc_attr($field_name); ?>" 
                   value="<?php echo esc_attr($selected_values[0] ?? ''); ?>">
        <?php endif; ?>
    </div>
    
</div>

<!-- Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const listbox = document.getElementById('<?php echo esc_js($container_id); ?>');
    if (listbox && typeof window.wcagInitListbox === 'function') {
        window.wcagInitListbox(listbox);
    }
});
</script>

<?php
// Add inline styles for custom styling if needed
if (!empty($config['custom_styles'])):
?>
<style>
#<?php echo esc_attr($container_id); ?> {
    <?php echo wp_kses($config['custom_styles'], []); ?>
}
</style>
<?php endif; ?>

