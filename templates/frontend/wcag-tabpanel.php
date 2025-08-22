<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }

/** @var int $post_id */
/** @var array $config */
/** @var array $tabs */

$panel_id = 'wcag-tabpanel-' . $post_id;
$selected = isset($config['first_tab_selected']) ? (int)$config['first_tab_selected'] : 0;
$class = !empty($config['class']) ? ' ' . esc_attr($config['class']) : '';
?>

<div id="<?php echo esc_attr($panel_id); ?>" class="wcag-wp-tabpanel<?php echo $class; ?>" role="tablist" aria-label="<?php echo esc_attr(get_the_title($post_id)); ?>">
    <div class="wcag-wp-tabs" role="tablist">
        <?php foreach ($tabs as $i => $tab):
            $tab_id = $panel_id . '-tab-' . $i;
            $panel_ref = $panel_id . '-panel-' . $i;
            $is_selected = ($i === $selected);
        ?>
            <button
                id="<?php echo esc_attr($tab_id); ?>"
                class="wcag-wp-tab"
                role="tab"
                aria-selected="<?php echo $is_selected ? 'true' : 'false'; ?>"
                aria-controls="<?php echo esc_attr($panel_ref); ?>"
                tabindex="<?php echo $is_selected ? '0' : '-1'; ?>"
            >
                <?php echo esc_html($tab['label']); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div class="wcag-wp-panels">
        <?php foreach ($tabs as $i => $tab):
            $tab_id = $panel_id . '-tab-' . $i;
            $panel_ref = $panel_id . '-panel-' . $i;
            $is_selected = ($i === $selected);
        ?>
            <div
                id="<?php echo esc_attr($panel_ref); ?>"
                class="wcag-wp-tabpanel-panel"
                role="tabpanel"
                aria-labelledby="<?php echo esc_attr($tab_id); ?>"
                <?php if (!$is_selected) echo 'hidden'; ?>
            >
                <?php echo apply_filters('the_content', $tab['content'] ?? ''); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

