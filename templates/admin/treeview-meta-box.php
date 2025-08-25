<?php
if (!defined('ABSPATH')) { exit; }
?>
<div class="wcag-admin-box">
	<p><?php echo esc_html__('Struttura Treeview (editor avanzato in arrivo).', 'wcag-wp'); ?></p>
	<p><code>[wcag-treeview id="<?php echo isset($post) ? esc_attr($post->ID) : 0; ?>"]</code></p>
	<input type="hidden" name="wcag_treeview_nodes[0][id]" value="root">
	<input type="hidden" name="wcag_treeview_nodes[0][label]" value="Root">
</div>
