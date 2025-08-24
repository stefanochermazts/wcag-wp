<?php
/**
 * WCAG Toolbar Frontend Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$unique_id = $config['id'] ?? 'wcag-toolbar-' . uniqid();
$groups = $config['groups'] ?? [];
$orientation = $config['orientation'] ?? 'horizontal';
?>

<div class="wcag-wp-toolbar wcag-wp-toolbar--<?php echo esc_attr($orientation); ?> <?php echo esc_attr($config['custom_class']); ?>"
     id="<?php echo esc_attr($unique_id); ?>"
     role="toolbar"
     aria-label="<?php echo esc_attr($config['aria_label'] ?: $config['label'] ?: __('Toolbar', 'wcag-wp')); ?>"
     data-wcag-toolbar
     data-config="<?php echo esc_attr(json_encode($config)); ?>">
    
    <?php if (!empty($config['label'])): ?>
        <div class="wcag-wp-toolbar-label" id="<?php echo esc_attr($unique_id); ?>-label">
            <?php echo esc_html($config['label']); ?>
        </div>
    <?php endif; ?>
    
    <div class="wcag-wp-toolbar-content">
        <?php if (!empty($groups)): ?>
            <?php foreach ($groups as $group_index => $group): ?>
                <?php if (!empty($group['controls'])): ?>
                    <div class="wcag-wp-toolbar-group" 
                         role="group" 
                         aria-label="<?php echo esc_attr($group['label']); ?>"
                         id="<?php echo esc_attr($unique_id); ?>-group-<?php echo esc_attr($group_index); ?>">
                        
                        <?php if (!empty($group['label'])): ?>
                            <div class="wcag-wp-toolbar-group-label" aria-hidden="true">
                                <?php echo esc_html($group['label']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="wcag-wp-toolbar-controls">
                            <?php foreach ($group['controls'] as $control_index => $control): ?>
                                <?php
                                $control_id = $unique_id . '-control-' . $group_index . '-' . $control_index;
                                $is_disabled = !empty($control['disabled']);
                                ?>
                                
                                <?php if ($control['type'] === 'separator'): ?>
                                    <!-- Separator -->
                                    <div class="wcag-wp-toolbar-separator" 
                                         role="separator" 
                                         aria-hidden="true">
                                        <span class="wcag-wp-toolbar-separator-line"></span>
                                    </div>
                                    
                                <?php elseif ($control['type'] === 'link' && !empty($control['url'])): ?>
                                    <!-- Link Control -->
                                    <a href="<?php echo esc_url($control['url']); ?>"
                                       class="wcag-wp-toolbar-link"
                                       id="<?php echo esc_attr($control_id); ?>"
                                       <?php echo !empty($control['target']) && $control['target'] === '_blank' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
                                       <?php echo $is_disabled ? 'aria-disabled="true" tabindex="-1"' : ''; ?>
                                       <?php echo !empty($control['action']) ? 'data-action="' . esc_attr($control['action']) . '"' : ''; ?>>
                                        
                                        <?php if (!empty($control['icon'])): ?>
                                            <span class="wcag-wp-toolbar-icon dashicons <?php echo esc_attr($control['icon']); ?>" aria-hidden="true"></span>
                                        <?php endif; ?>
                                        
                                        <span class="wcag-wp-toolbar-label"><?php echo esc_html($control['label']); ?></span>
                                        
                                        <?php if (!empty($control['target']) && $control['target'] === '_blank'): ?>
                                            <span class="wcag-wp-toolbar-external" aria-label="<?php _e('(si apre in una nuova finestra)', 'wcag-wp'); ?>">↗</span>
                                        <?php endif; ?>
                                    </a>
                                    
                                <?php else: ?>
                                    <!-- Button Control -->
                                    <button type="button"
                                            class="wcag-wp-toolbar-button"
                                            id="<?php echo esc_attr($control_id); ?>"
                                            <?php echo !empty($control['action']) ? 'data-action="' . esc_attr($control['action']) . '"' : ''; ?>
                                            <?php echo $is_disabled ? 'disabled' : ''; ?>
                                            <?php echo !empty($control['aria_label']) ? 'aria-label="' . esc_attr($control['aria_label']) . '"' : ''; ?>>
                                        
                                        <?php if (!empty($control['icon'])): ?>
                                            <span class="wcag-wp-toolbar-icon dashicons <?php echo esc_attr($control['icon']); ?>" aria-hidden="true"></span>
                                        <?php endif; ?>
                                        
                                        <span class="wcag-wp-toolbar-label"><?php echo esc_html($control['label']); ?></span>
                                    </button>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="wcag-wp-toolbar-empty">
                <p><?php _e('Nessun controllo configurato nella toolbar.', 'wcag-wp'); ?></p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Screen Reader Announcements -->
    <div class="wcag-wp-sr-only" 
         id="<?php echo esc_attr($unique_id); ?>-announcements" 
         aria-live="polite" 
         aria-atomic="true">
    </div>
</div>
