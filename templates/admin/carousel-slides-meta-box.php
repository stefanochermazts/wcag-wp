<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }
?>

<div class="wcag-wp-carousel-slides">
    <p class="description"><?php esc_html_e('Gestisci le slide del carousel. Trascina per riordinare.', 'wcag-wp'); ?></p>
    
    <div id="wcag-wp-carousel-slides-container">
        <?php if (!empty($slides)): ?>
            <?php foreach ($slides as $index => $slide): ?>
                <div class="wcag-wp-slide-item" data-slide-index="<?php echo esc_attr($index); ?>">
                    <div class="wcag-wp-slide-header">
                        <span class="wcag-wp-slide-handle dashicons dashicons-menu"></span>
                        <h4><?php echo esc_html($slide['title'] ?: __('Slide', 'wcag-wp') . ' ' . ($index + 1)); ?></h4>
                        <button type="button" class="wcag-wp-slide-toggle button-secondary" aria-expanded="true">
                            <span class="dashicons dashicons-arrow-up-alt2"></span>
                        </button>
                        <button type="button" class="wcag-wp-slide-delete button-link-delete" aria-label="<?php esc_attr_e('Elimina slide', 'wcag-wp'); ?>">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </div>
                    
                    <div class="wcag-wp-slide-content">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php esc_html_e('Titolo', 'wcag-wp'); ?></th>
                                <td>
                                    <input type="text" name="wcag_wp_carousel_slides[<?php echo esc_attr($index); ?>][title]" value="<?php echo esc_attr($slide['title']); ?>" class="regular-text">
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Contenuto', 'wcag-wp'); ?></th>
                                <td>
                                    <?php 
                                    wp_editor(
                                        $slide['content'],
                                        'wcag_wp_carousel_slide_content_' . $index,
                                        [
                                            'textarea_name' => 'wcag_wp_carousel_slides[' . $index . '][content]',
                                            'textarea_rows' => 5,
                                            'media_buttons' => false,
                                            'teeny' => true,
                                            'quicktags' => false,
                                        ]
                                    );
                                    ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Immagine', 'wcag-wp'); ?></th>
                                <td>
                                    <div class="wcag-wp-image-field">
                                        <input type="hidden" name="wcag_wp_carousel_slides[<?php echo esc_attr($index); ?>][image]" value="<?php echo esc_attr($slide['image']); ?>" class="wcag-wp-image-url">
                                        <input type="text" name="wcag_wp_carousel_slides[<?php echo esc_attr($index); ?>][image_alt]" value="<?php echo esc_attr($slide['image_alt']); ?>" placeholder="<?php esc_attr_e('Testo alternativo immagine', 'wcag-wp'); ?>" class="regular-text">
                                        <button type="button" class="wcag-wp-select-image button-secondary"><?php esc_html_e('Seleziona Immagine', 'wcag-wp'); ?></button>
                                        <button type="button" class="wcag-wp-remove-image button-secondary" <?php echo empty($slide['image']) ? 'style="display:none;"' : ''; ?>><?php esc_html_e('Rimuovi', 'wcag-wp'); ?></button>
                                        <div class="wcag-wp-image-preview" <?php echo empty($slide['image']) ? 'style="display:none;"' : ''; ?>>
                                            <img src="<?php echo esc_url($slide['image']); ?>" alt="" style="max-width: 200px; height: auto;">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Link', 'wcag-wp'); ?></th>
                                <td>
                                    <input type="url" name="wcag_wp_carousel_slides[<?php echo esc_attr($index); ?>][link_url]" value="<?php echo esc_attr($slide['link_url']); ?>" placeholder="<?php esc_attr_e('URL del link', 'wcag-wp'); ?>" class="regular-text"><br>
                                    <input type="text" name="wcag_wp_carousel_slides[<?php echo esc_attr($index); ?>][link_text]" value="<?php echo esc_attr($slide['link_text']); ?>" placeholder="<?php esc_attr_e('Testo del link', 'wcag-wp'); ?>" class="regular-text">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="wcag-wp-slide-actions">
        <button type="button" id="wcag-wp-add-slide" class="button button-primary">
            <span class="dashicons dashicons-plus-alt2"></span>
            <?php esc_html_e('Aggiungi Slide', 'wcag-wp'); ?>
        </button>
    </div>
    
    <!-- Template per nuova slide -->
    <script type="text/template" id="wcag-wp-slide-template">
        <div class="wcag-wp-slide-item" data-slide-index="{{index}}">
            <div class="wcag-wp-slide-header">
                <span class="wcag-wp-slide-handle dashicons dashicons-menu"></span>
                <h4><?php esc_html_e('Nuova Slide', 'wcag-wp'); ?></h4>
                <button type="button" class="wcag-wp-slide-toggle button-secondary" aria-expanded="true">
                    <span class="dashicons dashicons-arrow-up-alt2"></span>
                </button>
                <button type="button" class="wcag-wp-slide-delete button-link-delete" aria-label="<?php esc_attr_e('Elimina slide', 'wcag-wp'); ?>">
                    <span class="dashicons dashicons-trash"></span>
                </button>
            </div>
            
            <div class="wcag-wp-slide-content">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Titolo', 'wcag-wp'); ?></th>
                        <td>
                            <input type="text" name="wcag_wp_carousel_slides[{{index}}][title]" value="" class="regular-text">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Contenuto', 'wcag-wp'); ?></th>
                        <td>
                            <textarea name="wcag_wp_carousel_slides[{{index}}][content]" rows="5" class="large-text"></textarea>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Immagine', 'wcag-wp'); ?></th>
                        <td>
                            <div class="wcag-wp-image-field">
                                <input type="hidden" name="wcag_wp_carousel_slides[{{index}}][image]" value="" class="wcag-wp-image-url">
                                <input type="text" name="wcag_wp_carousel_slides[{{index}}][image_alt]" value="" placeholder="<?php esc_attr_e('Testo alternativo immagine', 'wcag-wp'); ?>" class="regular-text">
                                <button type="button" class="wcag-wp-select-image button-secondary"><?php esc_html_e('Seleziona Immagine', 'wcag-wp'); ?></button>
                                <button type="button" class="wcag-wp-remove-image button-secondary" style="display:none;"><?php esc_html_e('Rimuovi', 'wcag-wp'); ?></button>
                                <div class="wcag-wp-image-preview" style="display:none;">
                                    <img src="" alt="" style="max-width: 200px; height: auto;">
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Link', 'wcag-wp'); ?></th>
                        <td>
                            <input type="url" name="wcag_wp_carousel_slides[{{index}}][link_url]" value="" placeholder="<?php esc_attr_e('URL del link', 'wcag-wp'); ?>" class="regular-text"><br>
                            <input type="text" name="wcag_wp_carousel_slides[{{index}}][link_text]" value="" placeholder="<?php esc_attr_e('Testo del link', 'wcag-wp'); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </script>
</div>
