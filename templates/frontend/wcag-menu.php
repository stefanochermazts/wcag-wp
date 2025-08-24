<?php
/**
 * WCAG Menu Frontend Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$unique_id = $config['id'] ?? 'wcag-menu-' . uniqid();
$menu_role = $config['type'] === 'menubar' ? 'menubar' : 'menu';
$orientation = $config['orientation'] ?? 'horizontal';
$items = $config['items'] ?? [];
?>

<nav class="wcag-wp-menu wcag-wp-menu--<?php echo esc_attr($config['type']); ?> wcag-wp-menu--<?php echo esc_attr($orientation); ?> <?php echo esc_attr($config['custom_class']); ?>"
     aria-label="<?php echo esc_attr($config['aria_label'] ?: __('Menu di navigazione', 'wcag-wp')); ?>"
     data-wcag-menu
     data-config="<?php echo esc_attr(json_encode($config)); ?>">
     
    <ul role="<?php echo esc_attr($menu_role); ?>" 
        class="wcag-wp-menu__list"
        id="<?php echo esc_attr($unique_id); ?>">
        
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $index => $item): ?>
                <?php
                $item_id = $unique_id . '-item-' . $index;
                $has_submenu = !empty($item['submenu']);
                $is_disabled = !empty($item['disabled']);
                ?>
                
                <li role="none" class="wcag-wp-menu__item <?php echo $is_disabled ? 'wcag-wp-menu__item--disabled' : ''; ?>">
                    
                    <?php if ($has_submenu): ?>
                        <!-- Menu item with submenu -->
                        <button type="button"
                                role="menuitem"
                                class="wcag-wp-menu__button"
                                id="<?php echo esc_attr($item_id); ?>"
                                aria-haspopup="menu"
                                aria-expanded="false"
                                aria-controls="<?php echo esc_attr($item_id); ?>-submenu"
                                <?php echo $is_disabled ? 'disabled aria-disabled="true"' : ''; ?>>
                            
                            <?php if ($config['show_icons'] && !empty($item['icon'])): ?>
                                <span class="wcag-wp-menu__icon dashicons <?php echo esc_attr($item['icon']); ?>" aria-hidden="true"></span>
                            <?php endif; ?>
                            
                            <?php if ($config['show_labels']): ?>
                                <span class="wcag-wp-menu__label"><?php echo esc_html($item['label']); ?></span>
                            <?php endif; ?>
                            
                            <span class="wcag-wp-menu__arrow" aria-hidden="true">▼</span>
                        </button>
                        
                        <!-- Submenu -->
                        <ul role="menu"
                            class="wcag-wp-menu__submenu"
                            id="<?php echo esc_attr($item_id); ?>-submenu"
                            aria-labelledby="<?php echo esc_attr($item_id); ?>"
                            hidden>
                            
                            <?php foreach ($item['submenu'] as $sub_index => $sub_item): ?>
                                <?php
                                $sub_item_id = $item_id . '-sub-' . $sub_index;
                                $sub_is_disabled = !empty($sub_item['disabled']);
                                ?>
                                
                                <li role="none" class="wcag-wp-menu__subitem <?php echo $sub_is_disabled ? 'wcag-wp-menu__subitem--disabled' : ''; ?>">
                                    
                                    <?php if (!empty($sub_item['url']) && !$sub_is_disabled): ?>
                                        <a href="<?php echo esc_url($sub_item['url']); ?>"
                                           role="menuitem"
                                           class="wcag-wp-menu__link"
                                           id="<?php echo esc_attr($sub_item_id); ?>"
                                           <?php echo !empty($sub_item['target']) && $sub_item['target'] === '_blank' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                           
                                            <?php if ($config['show_icons'] && !empty($sub_item['icon'])): ?>
                                                <span class="wcag-wp-menu__icon dashicons <?php echo esc_attr($sub_item['icon']); ?>" aria-hidden="true"></span>
                                            <?php endif; ?>
                                            
                                            <?php if ($config['show_labels']): ?>
                                                <span class="wcag-wp-menu__label"><?php echo esc_html($sub_item['label']); ?></span>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($sub_item['target']) && $sub_item['target'] === '_blank'): ?>
                                                <span class="wcag-wp-menu__external" aria-label="<?php _e('(si apre in una nuova finestra)', 'wcag-wp'); ?>">↗</span>
                                            <?php endif; ?>
                                        </a>
                                    <?php else: ?>
                                        <span role="menuitem"
                                              class="wcag-wp-menu__text"
                                              id="<?php echo esc_attr($sub_item_id); ?>"
                                              <?php echo $sub_is_disabled ? 'aria-disabled="true"' : ''; ?>>
                                              
                                            <?php if ($config['show_icons'] && !empty($sub_item['icon'])): ?>
                                                <span class="wcag-wp-menu__icon dashicons <?php echo esc_attr($sub_item['icon']); ?>" aria-hidden="true"></span>
                                            <?php endif; ?>
                                            
                                            <?php if ($config['show_labels']): ?>
                                                <span class="wcag-wp-menu__label"><?php echo esc_html($sub_item['label']); ?></span>
                                            <?php endif; ?>
                                        </span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        
                    <?php else: ?>
                        <!-- Simple menu item -->
                        <?php if (!empty($item['url']) && !$is_disabled): ?>
                            <a href="<?php echo esc_url($item['url']); ?>"
                               role="menuitem"
                               class="wcag-wp-menu__link"
                               id="<?php echo esc_attr($item_id); ?>"
                               <?php echo !empty($item['target']) && $item['target'] === '_blank' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                               
                                <?php if ($config['show_icons'] && !empty($item['icon'])): ?>
                                    <span class="wcag-wp-menu__icon dashicons <?php echo esc_attr($item['icon']); ?>" aria-hidden="true"></span>
                                <?php endif; ?>
                                
                                <?php if ($config['show_labels']): ?>
                                    <span class="wcag-wp-menu__label"><?php echo esc_html($item['label']); ?></span>
                                <?php endif; ?>
                                
                                <?php if (!empty($item['target']) && $item['target'] === '_blank'): ?>
                                    <span class="wcag-wp-menu__external" aria-label="<?php _e('(si apre in una nuova finestra)', 'wcag-wp'); ?>">↗</span>
                                <?php endif; ?>
                            </a>
                        <?php else: ?>
                            <span role="menuitem"
                                  class="wcag-wp-menu__text"
                                  id="<?php echo esc_attr($item_id); ?>"
                                  <?php echo $is_disabled ? 'aria-disabled="true"' : ''; ?>>
                                  
                                <?php if ($config['show_icons'] && !empty($item['icon'])): ?>
                                    <span class="wcag-wp-menu__icon dashicons <?php echo esc_attr($item['icon']); ?>" aria-hidden="true"></span>
                                <?php endif; ?>
                                
                                <?php if ($config['show_labels']): ?>
                                    <span class="wcag-wp-menu__label"><?php echo esc_html($item['label']); ?></span>
                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li role="none" class="wcag-wp-menu__item wcag-wp-menu__item--empty">
                <span role="menuitem" class="wcag-wp-menu__text">
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
</nav>
