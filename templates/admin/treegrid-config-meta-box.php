<?php
if (!defined('ABSPATH')) { exit; }
?>
<div class="wcag-admin-box">
	<p>
		<label for="wcag_treegrid_config_aria_label"><strong><?php echo esc_html__('Aria-label', 'wcag-wp'); ?></strong></label>
		<input type="text" id="wcag_treegrid_config_aria_label" name="wcag_treegrid_config[aria_label]" value="<?php echo esc_attr($config['aria_label'] ?? ''); ?>" class="widefat" />
	</p>
	<p>
		<label><input type="checkbox" name="wcag_treegrid_config[expand_all]" value="1" <?php checked(!empty($config['expand_all'])); ?> /> <?php echo esc_html__('Espandi tutte le righe per default', 'wcag-wp'); ?></label>
	</p>
	<p>
		<label><input type="checkbox" name="wcag_treegrid_config[show_lines]" value="1" <?php checked(!empty($config['show_lines']), true); ?> /> <?php echo esc_html__('Mostra linee gerarchia', 'wcag-wp'); ?></label>
	</p>
</div>
