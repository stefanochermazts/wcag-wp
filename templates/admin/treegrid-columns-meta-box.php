<?php
if (!defined('ABSPATH')) { exit; }
?>
<div id="wcag-treegrid-columns" class="wcag-admin-box" data-treegrid-columns>
	<ul class="wcag-treegrid-columns-list">
		<?php foreach ($columns as $c): ?>
			<li>
				<input type="text" name="wcag_treegrid_columns[][key]" value="<?php echo esc_attr($c['key'] ?? ''); ?>" placeholder="key" />
				<input type="text" name="wcag_treegrid_columns[][label]" value="<?php echo esc_attr($c['label'] ?? ''); ?>" placeholder="label" />
				<input type="text" name="wcag_treegrid_columns[][width]" value="<?php echo esc_attr($c['width'] ?? ''); ?>" placeholder="width (es. 200px)" />
			</li>
		<?php endforeach; ?>
	</ul>
	<button type="button" class="button" data-treegrid-add-column><?php echo esc_html__('Aggiungi colonna', 'wcag-wp'); ?></button>
</div>
