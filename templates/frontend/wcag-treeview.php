<?php
/**
 * Frontend template: WCAG Treeview
 * Variabili disponibili:
 * - $nodes (array)
 * - $classes (string)
 * - $config (array)
 */
if (!defined('ABSPATH')) { exit; }

$wcag_wp_render_tree_nodes = null;
$wcag_wp_render_tree_nodes = function (array $nodes, string $parent = '') use (&$wcag_wp_render_tree_nodes): void {
	foreach ($nodes as $node) {
		if (($node['parent'] ?? '') !== $parent) { continue; }
		$has_children = false;
		foreach ($nodes as $n) {
			if (($n['parent'] ?? '') === ($node['id'] ?? '')) { $has_children = true; break; }
		}
		$expanded = !empty($node['expanded']);
		$role = 'treeitem';
		$aria_expanded = $has_children ? ' aria-expanded="' . ($expanded ? 'true' : 'false') . '"' : '';
		echo '<li role="none">';
		echo '<div role="' . esc_attr($role) . '" tabindex="-1"' . $aria_expanded . ' id="' . esc_attr($node['id'] ?? '') . '" class="wcag-treeitem">';
		echo '<span class="wcag-treeitem__label">' . esc_html($node['label'] ?? '') . '</span>';
		echo '</div>';
		if ($has_children) {
			echo '<ul role="group" class="wcag-treeview__group"' . ($expanded ? '' : ' hidden') . '>';
			$wcag_wp_render_tree_nodes($nodes, $node['id'] ?? '');
			echo '</ul>';
		}
		echo '</li>';
	}
};

$extraFlags = '';
if (empty($config['show_caret'])) { $extraFlags .= ' no-caret'; }
if (empty($config['label_toggles'])) { $extraFlags .= ' label-no-toggle'; }
if (empty($config['aria_live_announcements'])) { $extraFlags .= ' live-off'; }
?>
<nav class="wcag-treeview-container" aria-label="<?php echo esc_attr($config['aria_label'] ?? 'Treeview'); ?>">
	<ul role="tree" class="<?php echo esc_attr(trim($classes . $extraFlags)); ?>" data-wcag-treeview>
		<?php $wcag_wp_render_tree_nodes($nodes); ?>
		<?php if (!empty($config['aria_live_announcements'])): ?>
			<span class="screen-reader-text" aria-live="polite" data-treeview-live></span>
		<?php endif; ?>
	</ul>
</nav>
