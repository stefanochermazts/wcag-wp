<?php
if (!defined('ABSPATH')) { exit; }

// Costruisce mappa figli da array flat
function wcag_wp_treeview_build_children(array $nodes): array {
	$by_parent = [];
	foreach ($nodes as $n) {
		$p = $n['parent'] ?? '';
		$by_parent[$p][] = $n;
	}
	return $by_parent;
}

$children_map = wcag_wp_treeview_build_children($nodes);

function wcag_wp_treeview_render_builder(array $children_map, string $parent_id = ''): void {
	$children = $children_map[$parent_id] ?? [];
	if (empty($children)) { echo '<ul class="wcag-treeview-builder" data-connect="treeview"></ul>'; return; }
	echo '<ul class="wcag-treeview-builder" data-connect="treeview">';
	foreach ($children as $n) {
		$id = esc_attr($n['id'] ?? '');
		$label = esc_attr($n['label'] ?? '');
		$url = esc_url($n['url'] ?? '');
		$expanded = !empty($n['expanded']);
		echo '<li class="wcag-treeview-item" data-id="' . $id . '">';
		echo '<span class="wcag-treeview-handle" aria-hidden="true">⋮⋮</span>';
		echo '<input type="text" class="wcag-treeview-field" data-field="label" value="' . $label . '" placeholder="' . esc_attr__('etichetta', 'wcag-wp') . '" />';
		echo '<input type="url" class="wcag-treeview-field" data-field="url" value="' . $url . '" placeholder="' . esc_attr__('url (opzionale)', 'wcag-wp') . '" />';
		echo '<label class="wcag-treeview-flag"><input type="checkbox" class="wcag-treeview-field" data-field="expanded" ' . checked($expanded, true, false) . ' /> ' . esc_html__('Espanso', 'wcag-wp') . '</label>';
		echo '<button type="button" class="button button-small" data-action="add-child">' . esc_html__('Aggiungi figlio', 'wcag-wp') . '</button> ';
		echo '<button type="button" class="button button-small" data-action="remove">' . esc_html__('Rimuovi', 'wcag-wp') . '</button>';
		wcag_wp_treeview_render_builder($children_map, $n['id'] ?? '');
		echo '</li>';
	}
	echo '</ul>';
}
?>
<div id="wcag-treeview-structure" class="wcag-admin-box" data-treeview-builder>
	<p><?php echo esc_html__('Costruisci la gerarchia trascinando elementi. Usa "Aggiungi figlio" per annidare.', 'wcag-wp'); ?></p>
	<div class="wcag-treeview-toolbar">
		<label class="screen-reader-text" for="wcag-treeview-search"><?php echo esc_html__('Cerca nodo', 'wcag-wp'); ?></label>
		<input type="search" id="wcag-treeview-search" placeholder="<?php echo esc_attr__('Cerca…', 'wcag-wp'); ?>" data-action="search" />
		<button type="button" class="button" data-action="expand-all"><?php echo esc_html__('Espandi tutti', 'wcag-wp'); ?></button>
		<button type="button" class="button" data-action="collapse-all"><?php echo esc_html__('Chiudi tutti', 'wcag-wp'); ?></button>
		<span class="screen-reader-text" aria-live="polite" data-live></span>
	</div>
	<div class="wcag-treeview-builder-root">
		<?php wcag_wp_treeview_render_builder($children_map, ''); ?>
	</div>
	<p>
		<button type="button" class="button" data-action="add-root"><?php echo esc_html__('Aggiungi nodo radice', 'wcag-wp'); ?></button>
	</p>
	<input type="hidden" name="wcag_treeview_nodes_json" id="wcag_treeview_nodes_json" value="" />
</div>
