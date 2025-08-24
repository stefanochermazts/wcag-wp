<?php
/**
 * Menu Button Preview Meta Box Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$unique_id = 'wcag-menubutton-preview-' . uniqid();
?>

<div class="wcag-wp-admin-meta-box">
    
    <!-- Preview Container -->
    <div class="wcag-wp-preview-container">
        <h4><?php _e('Anteprima Menu Button', 'wcag-wp'); ?></h4>
        
        <div class="wcag-wp-preview-wrapper" id="<?php echo esc_attr($unique_id); ?>-wrapper">
            <?php
            // Include frontend template for preview
            if (!empty($config['button_text']) || !empty($config['menu_items'])) {
                include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-menubutton.php';
            } else {
                echo '<p class="wcag-wp-preview-empty">' . __('Configura il testo del pulsante e gli elementi del menu per visualizzare l\'anteprima', 'wcag-wp') . '</p>';
            }
            ?>
        </div>
    </div>
    
    <!-- Shortcode -->
    <div class="wcag-wp-shortcode-container">
        <h4><?php _e('Shortcode', 'wcag-wp'); ?></h4>
        <div class="wcag-wp-shortcode-wrapper">
            <code class="wcag-wp-shortcode-text" id="<?php echo esc_attr($unique_id); ?>-shortcode">
                [wcag-menubutton id="<?php echo esc_attr($post->ID); ?>"]
            </code>
            <button type="button" 
                    class="button button-small wcag-copy-shortcode" 
                    data-shortcode="[wcag-menubutton id=&quot;<?php echo esc_attr($post->ID); ?>&quot;]"
                    title="<?php _e('Copia shortcode', 'wcag-wp'); ?>">
                <?php _e('Copia', 'wcag-wp'); ?>
            </button>
        </div>
        <p class="wcag-wp-shortcode-description">
            <?php _e('Usa questo shortcode per inserire il menu button nelle pagine o nei post', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Usage Examples -->
    <div class="wcag-wp-usage-container">
        <h4><?php _e('Esempi di Utilizzo', 'wcag-wp'); ?></h4>
        
        <div class="wcag-wp-usage-example">
            <h5><?php _e('Shortcode Base', 'wcag-wp'); ?></h5>
            <code>[wcag-menubutton id="<?php echo esc_attr($post->ID); ?>"]</code>
            <p><?php _e('Utilizza la configurazione salvata nel post', 'wcag-wp'); ?></p>
        </div>
        
        <div class="wcag-wp-usage-example">
            <h5><?php _e('Shortcode con Parametri', 'wcag-wp'); ?></h5>
            <code>[wcag-menubutton button_text="Opzioni" position="bottom-right" trigger="click"]</code>
            <p><?php _e('Sovrascrive la configurazione con parametri personalizzati', 'wcag-wp'); ?></p>
        </div>
        
        <div class="wcag-wp-usage-example">
            <h5><?php _e('Template PHP', 'wcag-wp'); ?></h5>
            <code>&lt;?php echo do_shortcode('[wcag-menubutton id="<?php echo esc_attr($post->ID); ?>"]'); ?&gt;</code>
            <p><?php _e('Per inserire il menu button direttamente nei file template', 'wcag-wp'); ?></p>
        </div>
    </div>
    
    <!-- Configuration Summary -->
    <div class="wcag-wp-config-summary">
        <h4><?php _e('Riepilogo Configurazione', 'wcag-wp'); ?></h4>
        
        <table class="wcag-wp-config-table">
            <tr>
                <td><strong><?php _e('Testo Button:', 'wcag-wp'); ?></strong></td>
                <td><?php echo esc_html($config['button_text'] ?: __('Non impostato', 'wcag-wp')); ?></td>
            </tr>
            <?php if (!empty($config['button_icon'])): ?>
            <tr>
                <td><strong><?php _e('Icona:', 'wcag-wp'); ?></strong></td>
                <td><code><?php echo esc_html($config['button_icon']); ?></code></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td><strong><?php _e('Posizione Menu:', 'wcag-wp'); ?></strong></td>
                <td><?php echo esc_html(ucfirst(str_replace('-', ' ', $config['position']))); ?></td>
            </tr>
            <tr>
                <td><strong><?php _e('Trigger:', 'wcag-wp'); ?></strong></td>
                <td><?php echo esc_html(ucfirst($config['trigger'])); ?></td>
            </tr>
            <tr>
                <td><strong><?php _e('Elementi Menu:', 'wcag-wp'); ?></strong></td>
                <td><?php echo esc_html(count($config['menu_items'])); ?></td>
            </tr>
            <tr>
                <td><strong><?php _e('Freccia Indicatore:', 'wcag-wp'); ?></strong></td>
                <td><?php echo $config['show_arrow'] ? __('Sì', 'wcag-wp') : __('No', 'wcag-wp'); ?></td>
            </tr>
            <tr>
                <td><strong><?php _e('Chiudi dopo Selezione:', 'wcag-wp'); ?></strong></td>
                <td><?php echo $config['close_on_select'] ? __('Sì', 'wcag-wp') : __('No', 'wcag-wp'); ?></td>
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
    
    <!-- Menu Items List -->
    <?php if (!empty($config['menu_items'])): ?>
    <div class="wcag-wp-menu-items-list">
        <h4><?php _e('Elementi del Menu', 'wcag-wp'); ?></h4>
        <ul class="wcag-wp-items-preview">
            <?php foreach ($config['menu_items'] as $item): ?>
                <li class="wcag-wp-item-preview">
                    <?php if (!empty($item['icon'])): ?>
                        <span class="dashicons <?php echo esc_attr($item['icon']); ?>"></span>
                    <?php endif; ?>
                    <strong><?php echo esc_html($item['label']); ?></strong>
                    <?php if (!empty($item['url'])): ?>
                        <br><small><code><?php echo esc_html($item['url']); ?></code></small>
                    <?php endif; ?>
                    <?php if ($item['disabled']): ?>
                        <span class="wcag-wp-item-status wcag-wp-item-disabled"><?php _e('Disabilitato', 'wcag-wp'); ?></span>
                    <?php endif; ?>
                    <?php if ($item['separator']): ?>
                        <span class="wcag-wp-item-status wcag-wp-item-separator"><?php _e('+ Separatore', 'wcag-wp'); ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
</div>

<script>
jQuery(document).ready(function($) {
    // Update preview when configuration changes
    function updatePreview() {
        // This will be implemented in the admin JavaScript
        console.log('Menu Button preview update triggered');
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
