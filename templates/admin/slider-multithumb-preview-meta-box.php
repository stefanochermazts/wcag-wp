<?php
/**
 * Template per meta box preview Slider Multi-Thumb
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-preview-container">
    <div class="wcag-wp-preview-section">
        <h4><?php _e('Anteprima Slider Multi-Thumb', 'wcag-wp'); ?></h4>
        <div class="wcag-wp-preview-demo" id="slider-multithumb-preview">
            <?php
            // Render preview del slider multi-thumb
            $preview_config = array_merge([
                'label' => 'Slider Multi-Thumb di Esempio',
                'description' => 'Questo è un esempio di slider multi-thumb',
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'thumbs_count' => 2,
                'default_values' => '20,80',
                'unit' => '%',
                'orientation' => 'horizontal',
                'size' => 'medium',
                'theme' => 'default',
                'show_values' => true,
                'show_range_fill' => true,
                'show_ticks' => false,
                'prevent_overlap' => true,
                'min_distance' => 5,
                'required' => false,
                'disabled' => false,
                'aria_label' => '',
                'aria_describedby' => '',
                'custom_class' => '',
                'on_change_callback' => ''
            ], $config);
            
            // Include template frontend per preview
            $template_path = WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-slider-multithumb.php';
            if (file_exists($template_path)) {
                include $template_path;
            } else {
                echo '<p style="color: #d63638;">' . __('Template frontend non trovato', 'wcag-wp') . '</p>';
            }
            ?>
        </div>
    </div>

    <div class="wcag-wp-shortcode-section">
        <h4><?php _e('Shortcode', 'wcag-wp'); ?></h4>
        <div class="wcag-wp-shortcode-container">
            <code class="wcag-wp-shortcode" id="slider-multithumb-shortcode">
                [wcag-slider-multithumb id="<?php echo get_the_ID(); ?>"]
            </code>
            <button type="button" class="button button-small wcag-wp-copy-shortcode" 
                    data-shortcode="[wcag-slider-multithumb id=&quot;<?php echo get_the_ID(); ?>&quot;]">
                <?php _e('Copia', 'wcag-wp'); ?>
            </button>
        </div>
        <p class="description">
            <?php _e('Usa questo shortcode per inserire il slider multi-thumb nelle tue pagine o post.', 'wcag-wp'); ?>
        </p>
    </div>

    <div class="wcag-wp-config-section">
        <h4><?php _e('Configurazione Corrente', 'wcag-wp'); ?></h4>
        <div class="wcag-wp-config-summary">
            <table class="widefat">
                <tbody>
                    <tr>
                        <td><strong><?php _e('Range:', 'wcag-wp'); ?></strong></td>
                        <td><?php echo esc_html($config['min']); ?> - <?php echo esc_html($config['max']); ?> (step: <?php echo esc_html($config['step']); ?>)</td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Thumbs:', 'wcag-wp'); ?></strong></td>
                        <td><?php echo esc_html($config['thumbs_count']); ?> thumbs</td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Valori Default:', 'wcag-wp'); ?></strong></td>
                        <td><?php echo esc_html($config['default_values']); ?><?php echo $config['unit'] ? ' ' . esc_html($config['unit']) : ''; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Orientamento:', 'wcag-wp'); ?></strong></td>
                        <td><?php echo esc_html(ucfirst($config['orientation'])); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Dimensione:', 'wcag-wp'); ?></strong></td>
                        <td><?php echo esc_html(ucfirst($config['size'])); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Tema:', 'wcag-wp'); ?></strong></td>
                        <td><?php echo esc_html(ucfirst($config['theme'])); ?></td>
                    </tr>
                    <?php if ($config['show_values']): ?>
                    <tr>
                        <td><strong><?php _e('Opzioni:', 'wcag-wp'); ?></strong></td>
                        <td>
                            <?php
                            $options = [];
                            if ($config['show_values']) $options[] = __('Mostra valori', 'wcag-wp');
                            if ($config['show_range_fill']) $options[] = __('Range fill', 'wcag-wp');
                            if ($config['show_ticks']) $options[] = __('Tacche', 'wcag-wp');
                            if ($config['prevent_overlap']) $options[] = __('Previeni overlap', 'wcag-wp');
                            echo esc_html(implode(', ', $options));
                            ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($config['required'] || $config['disabled']): ?>
                    <tr>
                        <td><strong><?php _e('Stato:', 'wcag-wp'); ?></strong></td>
                        <td>
                            <?php
                            $states = [];
                            if ($config['required']) $states[] = __('Obbligatorio', 'wcag-wp');
                            if ($config['disabled']) $states[] = __('Disabilitato', 'wcag-wp');
                            echo esc_html(implode(', ', $states));
                            ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="wcag-wp-examples-section">
        <h4><?php _e('Esempi di Utilizzo', 'wcag-wp'); ?></h4>
        <div class="wcag-wp-examples">
            <h5><?php _e('Shortcode Base', 'wcag-wp'); ?></h5>
            <code>[wcag-slider-multithumb id="<?php echo get_the_ID(); ?>"]</code>
            
            <h5><?php _e('Con Parametri Personalizzati', 'wcag-wp'); ?></h5>
            <code>[wcag-slider-multithumb id="<?php echo get_the_ID(); ?>" class="my-slider" show_values="false"]</code>
            
            <h5><?php _e('In Template PHP', 'wcag-wp'); ?></h5>
            <code>&lt;?php echo do_shortcode('[wcag-slider-multithumb id="<?php echo get_the_ID(); ?>"]'); ?&gt;</code>
        </div>
    </div>
</div>

<style>
.wcag-wp-preview-container {
    background: #fff;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.wcag-wp-preview-section,
.wcag-wp-shortcode-section,
.wcag-wp-config-section,
.wcag-wp-examples-section {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.wcag-wp-preview-section:last-child,
.wcag-wp-shortcode-section:last-child,
.wcag-wp-config-section:last-child,
.wcag-wp-examples-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.wcag-wp-preview-demo {
    background: #f9f9f9;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin: 10px 0;
}

.wcag-wp-shortcode-container {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 10px 0;
}

.wcag-wp-shortcode {
    background: #f1f1f1;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 3px;
    font-family: Consolas, Monaco, monospace;
    font-size: 13px;
    flex: 1;
    user-select: all;
}

.wcag-wp-copy-shortcode {
    white-space: nowrap;
}

.wcag-wp-config-summary table {
    margin-top: 10px;
}

.wcag-wp-config-summary td:first-child {
    width: 150px;
    font-weight: 600;
}

.wcag-wp-examples h5 {
    margin: 15px 0 5px 0;
    color: #0073aa;
}

.wcag-wp-examples code {
    display: block;
    background: #f1f1f1;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 3px;
    font-family: Consolas, Monaco, monospace;
    font-size: 13px;
    margin-bottom: 10px;
    word-wrap: break-word;
}

h4 {
    color: #0073aa;
    margin-top: 0;
    margin-bottom: 15px;
    font-size: 14px;
    font-weight: 600;
}
</style>
