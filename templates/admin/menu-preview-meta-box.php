<?php
/**
 * Menu Preview Meta Box Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$unique_id = 'wcag-menu-preview-' . uniqid();
?>

<div class="wcag-wp-admin-meta-box">
    
    <!-- Preview Container -->
    <div class="wcag-wp-preview-container">
        <h4><?php _e('Anteprima Menu', 'wcag-wp'); ?></h4>
        
        <div class="wcag-wp-preview-wrapper" id="<?php echo esc_attr($unique_id); ?>-wrapper">
            <?php
            // Include frontend template for preview
            if (!empty($config['items'])) {
                include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-menu.php';
            } else {
                echo '<p class="wcag-wp-preview-empty">' . __('Aggiungi elementi al menu per visualizzare l\'anteprima', 'wcag-wp') . '</p>';
            }
            ?>
        </div>
    </div>
    
    <!-- Shortcode -->
    <div class="wcag-wp-shortcode-container">
        <h4><?php _e('Shortcode', 'wcag-wp'); ?></h4>
        <div class="wcag-wp-shortcode-wrapper">
            <code class="wcag-wp-shortcode-text" id="<?php echo esc_attr($unique_id); ?>-shortcode">
                [wcag-menu id="<?php echo esc_attr($post->ID); ?>"]
            </code>
            <button type="button" 
                    class="button button-small wcag-copy-shortcode" 
                    data-shortcode="[wcag-menu id=&quot;<?php echo esc_attr($post->ID); ?>&quot;]"
                    title="<?php _e('Copia shortcode', 'wcag-wp'); ?>">
                <?php _e('Copia', 'wcag-wp'); ?>
            </button>
        </div>
        <p class="wcag-wp-shortcode-description">
            <?php _e('Usa questo shortcode per inserire il menu nelle pagine o nei post', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Usage Examples -->
    <div class="wcag-wp-usage-container">
        <h4><?php _e('Esempi di Utilizzo', 'wcag-wp'); ?></h4>
        
        <div class="wcag-wp-usage-example">
            <h5><?php _e('Shortcode Base', 'wcag-wp'); ?></h5>
            <code>[wcag-menu id="<?php echo esc_attr($post->ID); ?>"]</code>
            <p><?php _e('Utilizza la configurazione salvata nel post', 'wcag-wp'); ?></p>
        </div>
        
        <div class="wcag-wp-usage-example">
            <h5><?php _e('Shortcode con Parametri', 'wcag-wp'); ?></h5>
            <code>[wcag-menu type="menubar" orientation="horizontal" aria_label="Menu principale"]</code>
            <p><?php _e('Sovrascrive la configurazione con parametri personalizzati', 'wcag-wp'); ?></p>
        </div>
        
        <div class="wcag-wp-usage-example">
            <h5><?php _e('Template PHP', 'wcag-wp'); ?></h5>
            <code>&lt;?php echo do_shortcode('[wcag-menu id="<?php echo esc_attr($post->ID); ?>"]'); ?&gt;</code>
            <p><?php _e('Per inserire il menu direttamente nei file template', 'wcag-wp'); ?></p>
        </div>
    </div>
    
    <!-- Configuration Summary -->
    <div class="wcag-wp-config-summary">
        <h4><?php _e('Riepilogo Configurazione', 'wcag-wp'); ?></h4>
        
        <table class="wcag-wp-config-table">
            <tr>
                <td><strong><?php _e('Tipo:', 'wcag-wp'); ?></strong></td>
                <td><?php echo esc_html(ucfirst($config['type'])); ?></td>
            </tr>
            <tr>
                <td><strong><?php _e('Orientamento:', 'wcag-wp'); ?></strong></td>
                <td><?php echo esc_html(ucfirst($config['orientation'])); ?></td>
            </tr>
            <tr>
                <td><strong><?php _e('Elementi:', 'wcag-wp'); ?></strong></td>
                <td><?php echo esc_html(count($config['items'])); ?></td>
            </tr>
            <tr>
                <td><strong><?php _e('Icone:', 'wcag-wp'); ?></strong></td>
                <td><?php echo $config['show_icons'] ? __('Sì', 'wcag-wp') : __('No', 'wcag-wp'); ?></td>
            </tr>
            <tr>
                <td><strong><?php _e('Etichette:', 'wcag-wp'); ?></strong></td>
                <td><?php echo $config['show_labels'] ? __('Sì', 'wcag-wp') : __('No', 'wcag-wp'); ?></td>
            </tr>
            <tr>
                <td><strong><?php _e('Navigazione Tastiera:', 'wcag-wp'); ?></strong></td>
                <td><?php echo $config['keyboard_navigation'] ? __('Abilitata', 'wcag-wp') : __('Disabilitata', 'wcag-wp'); ?></td>
            </tr>
            <?php if (!empty($config['aria_label'])): ?>
            <tr>
                <td><strong><?php _e('ARIA Label:', 'wcag-wp'); ?></strong></td>
                <td><?php echo esc_html($config['aria_label']); ?></td>
            </tr>
            <?php endif; ?>
            <?php if (!empty($config['custom_class'])): ?>
            <tr>
                <td><strong><?php _e('Classe CSS:', 'wcag-wp'); ?></strong></td>
                <td><code><?php echo esc_html($config['custom_class']); ?></code></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
    
</div>

<script>
jQuery(document).ready(function($) {
    // Update preview when configuration changes
    function updatePreview() {
        // This will be implemented in the admin JavaScript
        console.log('Menu preview update triggered');
    }
    
    // Copy shortcode functionality
    $('.wcag-copy-shortcode').on('click', function() {
        const shortcode = $(this).data('shortcode');
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(shortcode).then(function() {
                // Show success feedback
                const button = $(this);
                const originalText = button.text();
                button.text('<?php _e('Copiato!', 'wcag-wp'); ?>');
                setTimeout(function() {
                    button.text(originalText);
                }, 2000);
            }.bind(this));
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = shortcode;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            
            const button = $(this);
            const originalText = button.text();
            button.text('<?php _e('Copiato!', 'wcag-wp'); ?>');
            setTimeout(function() {
                button.text(originalText);
            }, 2000);
        }
    });
});
</script>
