<?php
if (!defined('ABSPATH')) { exit; }
?>
<div class="wcag-admin-box">
	<p>
		<label for="wcag_treeview_config_aria_label"><strong><?php echo esc_html__('Aria-label', 'wcag-wp'); ?></strong></label>
		<input type="text" id="wcag_treeview_config_aria_label" name="wcag_treeview_config[aria_label]" value="<?php echo esc_attr($config['aria_label'] ?? ''); ?>" class="widefat" />
	</p>
	<p>
		<label><input type="checkbox" name="wcag_treeview_config[expand_all]" value="1" <?php checked(!empty($config['expand_all'])); ?> /> <?php echo esc_html__('Espandi tutti per default', 'wcag-wp'); ?></label>
	</p>
	<p>
		<label><input type="checkbox" name="wcag_treeview_config[selection_single]" value="1" <?php checked(!empty($config['selection_single']), true); ?> /> <?php echo esc_html__('Selezione singola', 'wcag-wp'); ?></label>
	</p>
	<hr/>
	<p>
		<label><input type="checkbox" name="wcag_treeview_config[show_caret]" value="1" <?php checked(!empty($config['show_caret']), true); ?> /> <?php echo esc_html__('Mostra caret espandi/chiudi', 'wcag-wp'); ?></label>
	</p>
	<p>
		<label><input type="checkbox" name="wcag_treeview_config[label_toggles]" value="1" <?php checked(!empty($config['label_toggles']), true); ?> /> <?php echo esc_html__('Click su etichetta alterna espansione', 'wcag-wp'); ?></label>
	</p>
	<p>
		<label><input type="checkbox" name="wcag_treeview_config[aria_live_announcements]" value="1" <?php checked(!empty($config['aria_live_announcements']), true); ?> /> <?php echo esc_html__('Annunci screen reader (aria-live)', 'wcag-wp'); ?></label>
	</p>
</div>
