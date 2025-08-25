<?php
if (!defined('ABSPATH')) { exit; }
?>
<div class="wcag-admin-box">
	<p><?php echo esc_html__('Dati Treegrid (editor avanzato in arrivo).', 'wcag-wp'); ?></p>
	<p><code>[wcag-treegrid id="<?php echo isset($post) ? esc_attr($post->ID) : 0; ?>"]</code></p>
	<input type="hidden" name="wcag_treegrid_rows[0][id]" value="row-1">
	<input type="hidden" name="wcag_treegrid_rows[0][level]" value="1">
	<input type="hidden" name="wcag_treegrid_rows[0][cells][]" value="Nodo 1">
	<input type="hidden" name="wcag_treegrid_rows[0][cells][]" value="Dettagli">
</div>
