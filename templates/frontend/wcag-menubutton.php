<?php
/**
 * WCAG Menu Button Frontend Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$unique_id = $config['id'] ?? 'wcag-menubutton-' . uniqid();
$items = $config['menu_items'] ?? [];
$position = $config['position'] ?? 'bottom';


?>

<div class="wcag-wp-menubutton wcag-wp-menubutton--<?php echo esc_attr($position); ?> <?php echo esc_attr($config['custom_class']); ?>"
     data-wcag-menubutton
     data-config="<?php echo esc_attr(json_encode($config)); ?>">
    
    <!-- Menu Button -->
    <button type="button"
            class="wcag-wp-menubutton__button"
            id="<?php echo esc_attr($unique_id); ?>-button"
            aria-haspopup="menu"
            aria-expanded="false"
            aria-controls="<?php echo esc_attr($unique_id); ?>-menu"
            aria-label="<?php echo esc_attr($config['aria_label'] ?: __('Menu opzioni', 'wcag-wp')); ?>">
        
        <?php if (!empty($config['button_icon'])): ?>
            <span class="wcag-wp-menubutton__icon dashicons <?php echo esc_attr($config['button_icon']); ?>" aria-hidden="true"></span>
        <?php endif; ?>
        
        <?php if (!empty($config['button_text'])): ?>
            <span class="wcag-wp-menubutton__text"><?php echo esc_html($config['button_text']); ?></span>
        <?php endif; ?>
        
        <span class="wcag-wp-menubutton__arrow" aria-hidden="true">▼</span>
    </button>
    
    <!-- Menu Container -->
    <ul role="menu"
        class="wcag-wp-menubutton__menu"
        id="<?php echo esc_attr($unique_id); ?>-menu"
        aria-labelledby="<?php echo esc_attr($unique_id); ?>-button"
        hidden>
        
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $index => $item): ?>
                <?php
                $item_id = $unique_id . '-item-' . $index;
                $is_disabled = !empty($item['disabled']);
                $is_separator = !empty($item['separator']);
                ?>
                
                <?php if ($is_separator): ?>
                    <!-- Separator -->
                    <li role="separator" 
                        class="wcag-wp-menubutton__item wcag-wp-menubutton__item--separator"
                        aria-hidden="true">
                        <hr>
                    </li>
                <?php else: ?>
                    <!-- Menu Item -->
                    <li role="none" 
                        class="wcag-wp-menubutton__item <?php echo $is_disabled ? 'wcag-wp-menubutton__item--disabled' : ''; ?>">
                        
                        <?php if (!empty($item['url']) && !$is_disabled): ?>
                            <!-- Link Item -->
                            <a href="<?php echo esc_url($item['url']); ?>"
                               role="menuitem"
                               class="wcag-wp-menubutton__link"
                               id="<?php echo esc_attr($item_id); ?>"
                               <?php echo !empty($item['target']) && $item['target'] === '_blank' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                
                                <?php if (!empty($item['icon'])): ?>
                                    <span class="wcag-wp-menubutton__item-icon dashicons <?php echo esc_attr($item['icon']); ?>" aria-hidden="true"></span>
                                <?php endif; ?>
                                
                                <span class="wcag-wp-menubutton__item-label"><?php echo esc_html($item['label']); ?></span>
                                
                                <?php if (!empty($item['target']) && $item['target'] === '_blank'): ?>
                                    <span class="wcag-wp-menubutton__external" aria-label="<?php _e('(si apre in una nuova finestra)', 'wcag-wp'); ?>">↗</span>
                                <?php endif; ?>
                            </a>
                        <?php else: ?>
                            <!-- Text Item -->
                            <span role="menuitem"
                                  class="wcag-wp-menubutton__text"
                                  id="<?php echo esc_attr($item_id); ?>"
                                  <?php echo $is_disabled ? 'aria-disabled="true"' : ''; ?>>
                                
                                <?php if (!empty($item['icon'])): ?>
                                    <span class="wcag-wp-menubutton__item-icon dashicons <?php echo esc_attr($item['icon']); ?>" aria-hidden="true"></span>
                                <?php endif; ?>
                                
                                <span class="wcag-wp-menubutton__item-label"><?php echo esc_html($item['label']); ?></span>
                            </span>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Empty State -->
            <li role="none" class="wcag-wp-menubutton__item wcag-wp-menubutton__item--empty">
                <span role="menuitem" class="wcag-wp-menubutton__text">
                    <?php _e('Nessun elemento nel menu', 'wcag-wp'); ?>
                </span>
            </li>
        <?php endif; ?>
    </ul>
    
    <!-- Screen Reader Announcements -->
    <div class="wcag-wp-sr-only" 
         id="<?php echo esc_attr($unique_id); ?>-announcements" 
         aria-live="polite" 
         aria-atomic="true">
    </div>
</div>
