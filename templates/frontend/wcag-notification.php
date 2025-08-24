<?php
/**
 * Template: WCAG Notification Frontend
 * 
 * Renders accessible notification with ARIA live regions
 * 
 * @package WCAG_WP
 * @var WP_Post $notification Notification post object
 * @var array $config Notification configuration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get notification type info
$type_info = WCAG_WP_Notifications::get_types()[$config['type']] ?? WCAG_WP_Notifications::get_types()['info'];

// Generate unique IDs for ARIA
$notification_id = 'wcag-notification-' . ($notification->ID ?: uniqid());
$title_id = $notification_id . '-title';
$content_id = $notification_id . '-content';
$close_id = $notification_id . '-close';

// Build CSS classes
$css_classes = [
    'wcag-wp-notification',
    'wcag-wp-notification--' . esc_attr($config['type']),
    'wcag-wp-notification--position-' . esc_attr($config['position'])
];

if ($config['dismissible']) {
    $css_classes[] = 'wcag-wp-notification--dismissible';
}

if ($config['auto_dismiss']) {
    $css_classes[] = 'wcag-wp-notification--auto-dismiss';
}

if (!empty($config['custom_class'])) {
    $css_classes[] = esc_attr($config['custom_class']);
}

// Prepare data attributes for JavaScript
$data_attributes = [
    'data-notification-id' => esc_attr($notification->ID),
    'data-notification-type' => esc_attr($config['type']),
    'data-dismissible' => $config['dismissible'] ? 'true' : 'false',
    'data-auto-dismiss' => $config['auto_dismiss'] ? 'true' : 'false'
];

if ($config['auto_dismiss']) {
    $data_attributes['data-auto-dismiss-delay'] = esc_attr($config['auto_dismiss_delay']);
}
?>

<div id="<?php echo esc_attr($notification_id); ?>" 
     class="<?php echo esc_attr(implode(' ', $css_classes)); ?>"
     role="alert"
     aria-live="<?php echo esc_attr($type_info['aria_live']); ?>"
     aria-labelledby="<?php echo esc_attr($title_id); ?>"
     aria-describedby="<?php echo esc_attr($content_id); ?>"
     <?php foreach ($data_attributes as $attr => $value): ?>
         <?php echo esc_attr($attr); ?>="<?php echo esc_attr($value); ?>"
     <?php endforeach; ?>>
    
    <!-- Notification Content Container -->
    <div class="wcag-wp-notification__container">
        
        <!-- Icon (if enabled) -->
        <?php if ($config['show_icon']): ?>
            <div class="wcag-wp-notification__icon" aria-hidden="true">
                <span class="wcag-wp-notification__icon-symbol">
                    <?php echo esc_html($type_info['icon']); ?>
                </span>
            </div>
        <?php endif; ?>
        
        <!-- Content -->
        <div class="wcag-wp-notification__content">
            
            <!-- Title (if present) -->
            <?php if (!empty($notification->post_title)): ?>
                <div id="<?php echo esc_attr($title_id); ?>" 
                     class="wcag-wp-notification__title">
                    <?php echo wp_kses_post($notification->post_title); ?>
                </div>
            <?php endif; ?>
            
            <!-- Message Content -->
            <div id="<?php echo esc_attr($content_id); ?>" 
                 class="wcag-wp-notification__message">
                <?php 
                // Apply content filters for shortcodes, etc.
                $content = apply_filters('the_content', $notification->post_content);
                echo wp_kses_post($content);
                ?>
            </div>
            
        </div>
        
        <!-- Close Button (if dismissible) -->
        <?php if ($config['dismissible']): ?>
            <button type="button" 
                    id="<?php echo esc_attr($close_id); ?>"
                    class="wcag-wp-notification__close"
                    aria-label="<?php esc_attr_e('Chiudi notifica', 'wcag-wp'); ?>"
                    title="<?php esc_attr_e('Chiudi questa notifica', 'wcag-wp'); ?>">
                
                <!-- Close Icon -->
                <span class="wcag-wp-notification__close-icon" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M8 7.293l2.146-2.147a.5.5 0 01.708.708L8.707 8l2.147 2.146a.5.5 0 01-.708.708L8 8.707l-2.146 2.147a.5.5 0 01-.708-.708L7.293 8 5.146 5.854a.5.5 0 01.708-.708L8 7.293z"/>
                    </svg>
                </span>
                
                <!-- Screen Reader Text -->
                <span class="wcag-wp-sr-only">
                    <?php esc_html_e('Chiudi notifica', 'wcag-wp'); ?>
                </span>
                
            </button>
        <?php endif; ?>
        
    </div>
    
    <!-- Progress Bar (for auto-dismiss) -->
    <?php if ($config['auto_dismiss']): ?>
        <div class="wcag-wp-notification__progress" aria-hidden="true">
            <div class="wcag-wp-notification__progress-bar" 
                 style="animation-duration: <?php echo esc_attr($config['auto_dismiss_delay']); ?>ms;"></div>
        </div>
    <?php endif; ?>
    
</div>

<!-- Screen Reader Announcement (for dynamic notifications) -->
<?php if ($notification->ID === 0): // Dynamic notification ?>
    <div class="wcag-wp-sr-only" 
         aria-live="<?php echo esc_attr($type_info['aria_live']); ?>" 
         aria-atomic="true">
        <?php 
        printf(
            /* translators: 1: notification type, 2: notification message */
            esc_html__('Notifica %1$s: %2$s', 'wcag-wp'),
            esc_html($type_info['label']),
            wp_strip_all_tags($notification->post_content)
        );
        ?>
    </div>
<?php endif; ?>

<?php
// Add inline styles for custom colors if needed
if ($config['type'] === 'custom' && !empty($config['custom_colors'])):
?>
<style>
#<?php echo esc_attr($notification_id); ?> {
    --wcag-notification-color: <?php echo esc_attr($config['custom_colors']['primary'] ?? '#2271b1'); ?>;
    --wcag-notification-bg: <?php echo esc_attr($config['custom_colors']['background'] ?? '#f0f6fc'); ?>;
    --wcag-notification-border: <?php echo esc_attr($config['custom_colors']['border'] ?? '#c3dafe'); ?>;
}
</style>
<?php endif; ?>

<script>
// Initialize notification behavior when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const notification = document.getElementById('<?php echo esc_js($notification_id); ?>');
    if (notification && typeof window.wcagInitNotification === 'function') {
        window.wcagInitNotification(notification);
    }
});
</script>

