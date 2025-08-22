<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }

$toc_html_id = 'wcag-toc-' . $toc_id;
$css_class = 'wcag-wp wcag-wp-toc' . (!empty($options['class']) ? ' ' . esc_attr($options['class']) : '');
$toc_post = get_post($toc_id);
?>
<nav id="<?php echo esc_attr($toc_html_id); ?>" class="<?php echo $css_class; ?>" aria-label="Indice dei contenuti">
    <div class="wcag-wp-toc-inner" data-levels="<?php echo esc_attr(implode(',', $config['levels'])); ?>" data-numbered="<?php echo $config['numbered'] ? 'true' : 'false'; ?>" data-collapsible="<?php echo $config['collapsible'] ? 'true' : 'false'; ?>" data-smooth="<?php echo $config['smooth'] ? 'true' : 'false'; ?>" data-container-selector="<?php echo esc_attr($config['container_selector']); ?>">
        <?php if (!empty($config['show_title'])): ?>
            <h2 class="wcag-wp-toc-title"><?php echo esc_html($config['title_text']); ?></h2>
        <?php endif; ?>
        <ol class="wcag-wp-toc-list"></ol>
    </div>
</nav>


