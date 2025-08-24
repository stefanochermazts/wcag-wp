<?php
/**
 * Template: Notification Configuration Meta Box
 * 
 * @package WCAG_WP
 * @var WP_Post $post Current post object
 * @var array $config Notification configuration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-notification-config">
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="notification_type"><?php esc_html_e('Tipo Notifica', 'wcag-wp'); ?></label>
            </th>
            <td>
                <select name="wcag_notification_config[type]" id="notification_type" class="widefat">
                    <?php foreach (WCAG_WP_Notifications::get_types() as $type_key => $type_info): ?>
                        <option value="<?php echo esc_attr($type_key); ?>" 
                                <?php selected($config['type'], $type_key); ?>
                                data-icon="<?php echo esc_attr($type_info['icon']); ?>"
                                data-color="<?php echo esc_attr($type_info['color']); ?>"
                                data-aria-live="<?php echo esc_attr($type_info['aria_live']); ?>">
                            <?php echo esc_html($type_info['icon'] . ' ' . $type_info['label']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description">
                    <?php esc_html_e('Il tipo determina colore, icona e priorità degli annunci screen reader.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="notification_dismissible"><?php esc_html_e('Comportamento', 'wcag-wp'); ?></label>
            </th>
            <td>
                <fieldset>
                    <label>
                        <input type="checkbox" 
                               name="wcag_notification_config[dismissible]" 
                               id="notification_dismissible"
                               value="1" 
                               <?php checked($config['dismissible']); ?>>
                        <?php esc_html_e('Chiudibile dall\'utente', 'wcag-wp'); ?>
                    </label>
                    <br><br>
                    
                    <label>
                        <input type="checkbox" 
                               name="wcag_notification_config[auto_dismiss]" 
                               id="notification_auto_dismiss"
                               value="1" 
                               <?php checked($config['auto_dismiss']); ?>>
                        <?php esc_html_e('Chiusura automatica', 'wcag-wp'); ?>
                    </label>
                    
                    <div id="auto_dismiss_settings" style="margin-top: 10px; <?php echo $config['auto_dismiss'] ? '' : 'display: none;'; ?>">
                        <label for="auto_dismiss_delay">
                            <?php esc_html_e('Ritardo chiusura (millisecondi):', 'wcag-wp'); ?>
                        </label>
                        <input type="number" 
                               name="wcag_notification_config[auto_dismiss_delay]" 
                               id="auto_dismiss_delay"
                               value="<?php echo esc_attr($config['auto_dismiss_delay']); ?>"
                               min="1000" 
                               max="30000" 
                               step="500"
                               class="small-text">
                        <p class="description">
                            <?php esc_html_e('Minimo 1 secondo (1000ms), massimo 30 secondi (30000ms).', 'wcag-wp'); ?>
                        </p>
                    </div>
                </fieldset>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="notification_show_icon"><?php esc_html_e('Aspetto', 'wcag-wp'); ?></label>
            </th>
            <td>
                <fieldset>
                    <label>
                        <input type="checkbox" 
                               name="wcag_notification_config[show_icon]" 
                               id="notification_show_icon"
                               value="1" 
                               <?php checked($config['show_icon']); ?>>
                        <?php esc_html_e('Mostra icona', 'wcag-wp'); ?>
                    </label>
                    <p class="description">
                        <?php esc_html_e('L\'icona aiuta l\'identificazione visiva del tipo di notifica.', 'wcag-wp'); ?>
                    </p>
                </fieldset>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="notification_position"><?php esc_html_e('Posizionamento', 'wcag-wp'); ?></label>
            </th>
            <td>
                <select name="wcag_notification_config[position]" id="notification_position" class="widefat">
                    <option value="top" <?php selected($config['position'], 'top'); ?>>
                        <?php esc_html_e('In alto (fixed)', 'wcag-wp'); ?>
                    </option>
                    <option value="bottom" <?php selected($config['position'], 'bottom'); ?>>
                        <?php esc_html_e('In basso (fixed)', 'wcag-wp'); ?>
                    </option>
                    <option value="inline" <?php selected($config['position'], 'inline'); ?>>
                        <?php esc_html_e('Nel contenuto (inline)', 'wcag-wp'); ?>
                    </option>
                </select>
                <p class="description">
                    <?php esc_html_e('Posizione della notifica nella pagina.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="notification_custom_class"><?php esc_html_e('CSS Personalizzato', 'wcag-wp'); ?></label>
            </th>
            <td>
                <input type="text" 
                       name="wcag_notification_config[custom_class]" 
                       id="notification_custom_class"
                       value="<?php echo esc_attr($config['custom_class']); ?>"
                       class="widefat"
                       placeholder="my-custom-notification">
                <p class="description">
                    <?php esc_html_e('Classe CSS aggiuntiva per personalizzazioni avanzate.', 'wcag-wp'); ?>
                </p>
            </td>
        </tr>
    </table>
</div>

<div class="wcag-notification-live-preview" style="margin-top: 20px;">
    <h4><?php esc_html_e('Anteprima Live', 'wcag-wp'); ?></h4>
    <div id="notification-preview-container">
        <!-- Live preview will be inserted here by JavaScript -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss settings toggle
    const autoDismissCheckbox = document.getElementById('notification_auto_dismiss');
    const autoDismissSettings = document.getElementById('auto_dismiss_settings');
    
    if (autoDismissCheckbox && autoDismissSettings) {
        autoDismissCheckbox.addEventListener('change', function() {
            autoDismissSettings.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    // Live preview update (will be enhanced by admin JS)
    const configInputs = document.querySelectorAll('.wcag-notification-config input, .wcag-notification-config select');
    configInputs.forEach(input => {
        input.addEventListener('change', updatePreview);
    });
    
    function updatePreview() {
        // This will be implemented in the admin JavaScript file
        if (typeof window.wcagUpdateNotificationPreview === 'function') {
            window.wcagUpdateNotificationPreview();
        }
    }
    
    // Initial preview
    updatePreview();
});
</script>

