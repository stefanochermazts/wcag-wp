<?php
/**
 * Frontend template: WCAG Treegrid
 * Variabili disponibili:
 * - $data (array)
 * - $classes (string)
 */
if (!defined('ABSPATH')) { exit; }
?>
<div class="wcag-treegrid-container">
	<table role="treegrid" class="<?php echo esc_attr(trim($classes)); ?>" data-wcag-treegrid>
		<thead>
			<tr role="row">
				<th role="columnheader" scope="col"><?php echo esc_html__('Nome', 'wcag-wp'); ?></th>
				<th role="columnheader" scope="col"><?php echo esc_html__('Dettagli', 'wcag-wp'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data as $row): ?>
			<?php
				$id = esc_attr($row['id'] ?? '');
				$level = max(1, (int)($row['level'] ?? 1));
				$expanded = !empty($row['expanded']);
				$cells = is_array($row['cells'] ?? null) ? $row['cells'] : ['',''];
			?>
			<tr role="row" aria-level="<?php echo (int)$level; ?>" aria-expanded="<?php echo $expanded ? 'true' : 'false'; ?>" data-id="<?php echo $id; ?>">
				<td role="gridcell">
					<span class="wcag-treegrid__toggle" aria-hidden="true"></span>
					<span class="wcag-treegrid__cell"><?php echo esc_html($cells[0] ?? ''); ?></span>
				</td>
				<td role="gridcell">
					<span class="wcag-treegrid__cell"><?php echo esc_html($cells[1] ?? ''); ?></span>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
