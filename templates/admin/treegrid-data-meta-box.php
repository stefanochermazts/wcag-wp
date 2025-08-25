<?php
if (!defined('ABSPATH')) { exit; }
$columns = is_array($columns ?? null) ? $columns : [];

function wcag_wp_treegrid_build_children(array $rows): array {
	$by_parent = [];
	foreach ($rows as $r) {
		$p = $r['parent'] ?? '';
		$by_parent[$p][] = $r;
	}
	return $by_parent;
}
$children_map = wcag_wp_treegrid_build_children($data);

function wcag_wp_treegrid_render_builder(array $map, array $columns, string $parent = ''): void {
	$children = $map[$parent] ?? [];
	echo '<ul class="wcag-treegrid-rows-builder" data-connect="treegrid">';
	foreach ($children as $r) {
		$id = esc_attr($r['id'] ?? '');
		$level = max(1, (int)($r['level'] ?? 1));
		$expanded = !empty($r['expanded']);
		$cells = is_array($r['cells'] ?? null) ? $r['cells'] : [];
		echo '<li class="wcag-treegrid-row" data-id="' . $id . '">';
		echo '<span class="wcag-treegrid-handle" aria-hidden="true">⋮⋮</span>';
		echo '<input type="text" class="wcag-treegrid-field" data-field="id" value="' . $id . '" placeholder="row id" />';
		echo '<label class="wcag-treegrid-flag"><input type="checkbox" class="wcag-treegrid-field" data-field="expanded" ' . checked($expanded, true, false) . ' /> ' . esc_html__('Espanso', 'wcag-wp') . '</label>';
		$idx = 0;
		foreach ($columns as $c) {
			$val = esc_attr($cells[$idx] ?? '');
			$ph = esc_attr($c['label'] ?? $c['key'] ?? '');
			echo '<input type="text" class="wcag-treegrid-cell" data-cell-index="' . $idx . '" placeholder="' . $ph . '" value="' . $val . '" />';
			$idx++;
		}
		wcag_wp_treegrid_render_builder($map, $columns, $r['id'] ?? '');
		echo '</li>';
	}
	echo '</ul>';
}
?>
<div id="wcag-treegrid-data" class="wcag-admin-box" data-treegrid-builder>
	<p><?php echo esc_html__('Definisci righe e trascina per creare la gerarchia. Le celle seguono l’ordine delle colonne.', 'wcag-wp'); ?></p>
	<div class="wcag-treegrid-toolbar">
		<label class="screen-reader-text" for="wcag-treegrid-search"><?php echo esc_html__('Cerca riga', 'wcag-wp'); ?></label>
		<input type="search" id="wcag-treegrid-search" placeholder="<?php echo esc_attr__('Cerca…', 'wcag-wp'); ?>" data-action="search" />
		<button type="button" class="button" data-action="expand-all"><?php echo esc_html__('Espandi tutte', 'wcag-wp'); ?></button>
		<button type="button" class="button" data-action="collapse-all"><?php echo esc_html__('Chiudi tutte', 'wcag-wp'); ?></button>
		<span class="screen-reader-text" aria-live="polite" data-live></span>
	</div>
	<div class="wcag-treegrid-builder-root">
		<?php wcag_wp_treegrid_render_builder($children_map, $columns, ''); ?>
	</div>
	<p>
		<button type="button" class="button" data-action="add-root-row"><?php echo esc_html__('Aggiungi riga radice', 'wcag-wp'); ?></button>
	</p>
	<input type="hidden" name="wcag_treegrid_rows_json" id="wcag_treegrid_rows_json" value="" />
</div>
