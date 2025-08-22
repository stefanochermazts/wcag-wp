<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }
$shortcode = "[toc id=\"{$post->ID}\"]";
?>
<div class="wcag-wp-toc-preview">
    <p><strong><?php esc_html_e('Shortcode', 'wcag-wp'); ?>:</strong></p>
    <code><?php echo esc_html($shortcode); ?></code>
    <p class="description"><?php esc_html_e('Copia e incolla lo shortcode dove vuoi visualizzare l’indice dei contenuti.', 'wcag-wp'); ?></p>
</div>


