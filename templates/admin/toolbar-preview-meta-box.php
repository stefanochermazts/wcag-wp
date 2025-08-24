<?php
/**
 * WCAG Toolbar Preview Meta Box
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-toolbar-preview">
    
    <!-- Live Preview -->
    <div class="wcag-wp-preview-container">
        <h4><?php _e('Anteprima Toolbar', 'wcag-wp'); ?></h4>
        
        <div class="wcag-wp-preview-content">
            <?php if (!empty($config['groups'])): ?>
                <div class="wcag-wp-toolbar wcag-wp-toolbar--<?php echo esc_attr($config['orientation']); ?> <?php echo esc_attr($config['custom_class']); ?>"
                     role="toolbar"
                     aria-label="<?php echo esc_attr($config['aria_label'] ?: $config['label'] ?: __('Toolbar', 'wcag-wp')); ?>">
                    
                    <?php foreach ($config['groups'] as $group_index => $group): ?>
                        <?php if (!empty($group['controls'])): ?>
                            <div class="wcag-wp-toolbar-group" role="group" aria-label="<?php echo esc_attr($group['label']); ?>">
                                <?php foreach ($group['controls'] as $control_index => $control): ?>
                                    <?php if ($control['type'] === 'separator'): ?>
                                        <div class="wcag-wp-toolbar-separator" role="separator"></div>
                                    <?php elseif ($control['type'] === 'link' && !empty($control['url'])): ?>
                                        <a href="<?php echo esc_url($control['url']); ?>"
                                           class="wcag-wp-toolbar-link"
                                           <?php echo !empty($control['target']) && $control['target'] === '_blank' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
                                           <?php echo !empty($control['disabled']) ? 'aria-disabled="true"' : ''; ?>>
                                            <?php if (!empty($control['icon'])): ?>
                                                <span class="wcag-wp-toolbar-icon dashicons <?php echo esc_attr($control['icon']); ?>" aria-hidden="true"></span>
                                            <?php endif; ?>
                                            <span class="wcag-wp-toolbar-label"><?php echo esc_html($control['label']); ?></span>
                                        </a>
                                    <?php else: ?>
                                        <button type="button"
                                                class="wcag-wp-toolbar-button"
                                                <?php echo !empty($control['action']) ? 'data-action="' . esc_attr($control['action']) . '"' : ''; ?>
                                                <?php echo !empty($control['disabled']) ? 'disabled' : ''; ?>>
                                            <?php if (!empty($control['icon'])): ?>
                                                <span class="wcag-wp-toolbar-icon dashicons <?php echo esc_attr($control['icon']); ?>" aria-hidden="true"></span>
                                            <?php endif; ?>
                                            <span class="wcag-wp-toolbar-label"><?php echo esc_html($control['label']); ?></span>
                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="wcag-wp-preview-empty">
                    <p><?php _e('Nessun gruppo o controllo configurato. Aggiungi gruppi e controlli per vedere l\'anteprima.', 'wcag-wp'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Shortcode -->
    <div class="wcag-wp-shortcode-section">
        <h4><?php _e('Shortcode', 'wcag-wp'); ?></h4>
        <div class="wcag-wp-shortcode-container">
            <code id="wcag-toolbar-shortcode">[wcag-toolbar id="<?php echo esc_attr(get_the_ID()); ?>"]</code>
            <button type="button" class="button wcag-wp-copy-shortcode" data-clipboard-target="#wcag-toolbar-shortcode">
                <?php _e('Copia', 'wcag-wp'); ?>
            </button>
        </div>
        <p class="description">
            <?php _e('Copia questo shortcode e incollalo nel contenuto della tua pagina o post.', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Usage Instructions -->
    <div class="wcag-wp-usage-section">
        <h4><?php _e('Istruzioni Utilizzo', 'wcag-wp'); ?></h4>
        <ul>
            <li><?php _e('Configura i gruppi e i controlli nella sezione principale.', 'wcag-wp'); ?></li>
            <li><?php _e('Ogni gruppo può contenere pulsanti, link o separatori.', 'wcag-wp'); ?></li>
            <li><?php _e('I pulsanti possono eseguire azioni JavaScript personalizzate.', 'wcag-wp'); ?></li>
            <li><?php _e('I link possono aprire URL interni o esterni.', 'wcag-wp'); ?></li>
            <li><?php _e('I separatori dividono visivamente i controlli.', 'wcag-wp'); ?></li>
        </ul>
    </div>
</div>
