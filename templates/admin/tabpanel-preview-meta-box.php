<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }

$shortcode = "[wcag-tabpanel id=\"{$post->ID}\"]";
?>
<div class="wcag-wp-tabpanel-preview">
    <p><strong><?php esc_html_e('Shortcode', 'wcag-wp'); ?>:</strong></p>
    <code><?php echo esc_html($shortcode); ?></code>
    <p class="description"><?php esc_html_e('Copia e incolla lo shortcode dove vuoi visualizzare il Tab Panel.', 'wcag-wp'); ?></p>
</div>


