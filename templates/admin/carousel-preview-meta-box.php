<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }
?>

<div class="wcag-wp-carousel-preview">
    <h4><?php esc_html_e('Shortcode', 'wcag-wp'); ?></h4>
    <p><?php esc_html_e('Usa questo shortcode per inserire il carousel nelle tue pagine:', 'wcag-wp'); ?></p>
    
    <div class="wcag-wp-shortcode-display">
        <code>[wcag-carousel id="<?php echo esc_attr($post->ID); ?>"]</code>
        <button type="button" class="wcag-wp-copy-shortcode button-secondary" data-shortcode='[wcag-carousel id="<?php echo esc_attr($post->ID); ?>"]'>
            <span class="dashicons dashicons-clipboard"></span>
            <?php esc_html_e('Copia', 'wcag-wp'); ?>
        </button>
    </div>
    
    <h4><?php esc_html_e('Anteprima', 'wcag-wp'); ?></h4>
    <p><?php esc_html_e('Salva il carousel per vedere l\'anteprima qui.', 'wcag-wp'); ?></p>
    
    <div class="wcag-wp-preview-container">
        <?php
        $slides = get_post_meta($post->ID, '_wcag_wp_carousel_slides', true);
        if (!empty($slides) && is_array($slides)): ?>
            <div class="wcag-wp-preview-slides">
                <?php foreach (array_slice($slides, 0, 3) as $index => $slide): ?>
                    <div class="wcag-wp-preview-slide">
                        <?php if (!empty($slide['image'])): ?>
                            <div class="wcag-wp-preview-image">
                                <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['image_alt']); ?>" style="max-width: 100%; height: auto;">
                            </div>
                        <?php endif; ?>
                        
                        <div class="wcag-wp-preview-content">
                            <?php if (!empty($slide['title'])): ?>
                                <h5><?php echo esc_html($slide['title']); ?></h5>
                            <?php endif; ?>
                            
                            <?php if (!empty($slide['content'])): ?>
                                <div class="wcag-wp-preview-text">
                                    <?php echo wp_kses_post(wp_trim_words($slide['content'], 20)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if (count($slides) > 3): ?>
                    <div class="wcag-wp-preview-more">
                        <span class="dashicons dashicons-plus"></span>
                        <?php printf(esc_html__('e altre %d slide...', 'wcag-wp'), count($slides) - 3); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="wcag-wp-no-slides"><?php esc_html_e('Nessuna slide configurata. Aggiungi delle slide per vedere l\'anteprima.', 'wcag-wp'); ?></p>
        <?php endif; ?>
    </div>
</div>
