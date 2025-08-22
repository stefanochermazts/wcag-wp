<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }

$carousel_id = 'wcag-carousel-' . $post_id;
$css_class = 'wcag-wp wcag-wp-carousel' . (!empty($options['class']) ? ' ' . esc_attr($options['class']) : '') . (!empty($config['custom_css_class']) ? ' ' . esc_attr($config['custom_css_class']) : '');
?>

<div id="<?php echo esc_attr($carousel_id); ?>" class="<?php echo $css_class; ?>" 
     role="region" 
     aria-label="<?php echo esc_attr($post->post_title); ?>"
     data-autoplay="<?php echo $config['autoplay'] ? 'true' : 'false'; ?>"
     data-autoplay-speed="<?php echo esc_attr($config['autoplay_speed']); ?>"
     data-pause-on-hover="<?php echo $config['pause_on_hover'] ? 'true' : 'false'; ?>"
     data-show-indicators="<?php echo $config['show_indicators'] ? 'true' : 'false'; ?>"
     data-show-controls="<?php echo $config['show_controls'] ? 'true' : 'false'; ?>"
     data-keyboard-navigation="<?php echo $config['keyboard_navigation'] ? 'true' : 'false'; ?>"
     data-touch-swipe="<?php echo $config['touch_swipe'] ? 'true' : 'false'; ?>"
     data-infinite-loop="<?php echo $config['infinite_loop'] ? 'true' : 'false'; ?>"
     data-animation-type="<?php echo esc_attr($config['animation_type']); ?>">
    
    <div class="wcag-wp-carousel-container">
        <div class="wcag-wp-carousel-track" role="list">
            <?php foreach ($slides as $index => $slide): ?>
                <div class="wcag-wp-carousel-slide<?php echo $index === 0 ? ' wcag-wp-carousel-slide--active' : ''; ?>" 
                     role="listitem" 
                     aria-hidden="<?php echo $index === 0 ? 'false' : 'true'; ?>"
                     data-slide-index="<?php echo esc_attr($index); ?>">
                    
                    <?php if (!empty($slide['image'])): ?>
                        <div class="wcag-wp-carousel-image">
                            <img src="<?php echo esc_url($slide['image']); ?>" 
                                 alt="<?php echo esc_attr($slide['image_alt']); ?>"
                                 loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>">
                        </div>
                    <?php endif; ?>
                    
                    <div class="wcag-wp-carousel-content">
                        <?php if (!empty($slide['title'])): ?>
                            <h3 class="wcag-wp-carousel-title"><?php echo esc_html($slide['title']); ?></h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($slide['content'])): ?>
                            <div class="wcag-wp-carousel-text">
                                <?php echo wp_kses_post($slide['content']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($slide['link_url']) && !empty($slide['link_text'])): ?>
                            <div class="wcag-wp-carousel-link">
                                <a href="<?php echo esc_url($slide['link_url']); ?>" class="wcag-wp-carousel-button">
                                    <?php echo esc_html($slide['link_text']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($config['show_controls'] && count($slides) > 1): ?>
            <button type="button" 
                    class="wcag-wp-carousel-control wcag-wp-carousel-control--prev" 
                    aria-label="<?php esc_attr_e('Slide precedente', 'wcag-wp'); ?>"
                    aria-controls="<?php echo esc_attr($carousel_id); ?>">
                <span class="dashicons dashicons-arrow-left-alt2" aria-hidden="true"></span>
            </button>
            
            <button type="button" 
                    class="wcag-wp-carousel-control wcag-wp-carousel-control--next" 
                    aria-label="<?php esc_attr_e('Slide successiva', 'wcag-wp'); ?>"
                    aria-controls="<?php echo esc_attr($carousel_id); ?>">
                <span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span>
            </button>
        <?php endif; ?>
    </div>
    
    <?php if ($config['show_indicators'] && count($slides) > 1): ?>
        <div class="wcag-wp-carousel-indicators" role="tablist" aria-label="<?php esc_attr_e('Indicatori slide', 'wcag-wp'); ?>">
            <?php foreach ($slides as $index => $slide): ?>
                <button type="button" 
                        class="wcag-wp-carousel-indicator<?php echo $index === 0 ? ' wcag-wp-carousel-indicator--active' : ''; ?>" 
                        role="tab"
                        aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr($carousel_id); ?>"
                        data-slide-index="<?php echo esc_attr($index); ?>">
                    <span class="wcag-wp-carousel-indicator-text">
                        <?php printf(esc_html__('Slide %d', 'wcag-wp'), $index + 1); ?>
                    </span>
                </button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($config['autoplay']): ?>
        <div class="wcag-wp-carousel-autoplay-controls">
            <button type="button" 
                    class="wcag-wp-carousel-autoplay-toggle" 
                    aria-label="<?php esc_attr_e('Pausa autoplay', 'wcag-wp'); ?>"
                    aria-pressed="true">
                <span class="dashicons dashicons-controls-pause" aria-hidden="true"></span>
            </button>
        </div>
    <?php endif; ?>
    
    <div class="wcag-wp-carousel-status" aria-live="polite" aria-atomic="true">
        <?php printf(esc_html__('Slide %1$d di %2$d', 'wcag-wp'), 1, count($slides)); ?>
    </div>
</div>
