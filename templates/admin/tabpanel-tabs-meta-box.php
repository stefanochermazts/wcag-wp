<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }
?>

<div class="wcag-wp-tabpanel-tabs">
    <p class="description"><?php esc_html_e('Definisci i Tab e i rispettivi pannelli di contenuto.', 'wcag-wp'); ?></p>

    <div class="tabs-header">
        <button type="button" class="button button-primary" id="add-new-tab">
            <span class="dashicons dashicons-plus-alt2"></span>
            <?php esc_html_e('Aggiungi Tab', 'wcag-wp'); ?>
        </button>
    </div>

    <div class="tabs-container" id="tabs-container">
        <?php if (!empty($tabs)) : ?>
            <?php foreach ($tabs as $index => $tab): ?>
                <div class="tab-editor" data-index="<?php echo esc_attr((string)$index); ?>">
                    <div class="tab-header">
                        <div class="tab-drag-handle"><span class="dashicons dashicons-menu"></span></div>
                        <div class="tab-title"><h4><span class="tab-label"><?php echo esc_html($tab['label']); ?></span></h4></div>
                        <div class="tab-actions">
                            <button type="button" class="button-link tab-toggle" aria-expanded="true" title="<?php esc_attr_e('Apri/chiudi tab', 'wcag-wp'); ?>"><span class="dashicons dashicons-arrow-up-alt2"></span></button>
                            <button type="button" class="button-link tab-duplicate" title="<?php esc_attr_e('Duplica tab', 'wcag-wp'); ?>"><span class="dashicons dashicons-admin-page"></span></button>
                            <button type="button" class="button-link tab-delete" title="<?php esc_attr_e('Elimina tab', 'wcag-wp'); ?>"><span class="dashicons dashicons-trash"></span></button>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="field-row">
                            <div class="field-group">
                                <label for="tab_id_<?php echo esc_attr((string)$index); ?>"><?php esc_html_e('ID Tab', 'wcag-wp'); ?> <span class="required">*</span></label>
                                <input type="text" id="tab_id_<?php echo esc_attr((string)$index); ?>" name="wcag_wp_tabpanel_tabs[<?php echo esc_attr((string)$index); ?>][id]" value="<?php echo esc_attr($tab['id']); ?>" class="tab-id-input" required>
                            </div>
                            <div class="field-group">
                                <label for="tab_label_<?php echo esc_attr((string)$index); ?>"><?php esc_html_e('Etichetta Tab', 'wcag-wp'); ?> <span class="required">*</span></label>
                                <input type="text" id="tab_label_<?php echo esc_attr((string)$index); ?>" name="wcag_wp_tabpanel_tabs[<?php echo esc_attr((string)$index); ?>][label]" value="<?php echo esc_attr($tab['label']); ?>" class="tab-label-input" required>
                            </div>
                        </div>
                        <div class="field-row">
                            <div class="field-group">
                                <label for="tab_content_<?php echo esc_attr((string)$index); ?>"><?php esc_html_e('Contenuto', 'wcag-wp'); ?></label>
                                <textarea id="tab_content_<?php echo esc_attr((string)$index); ?>" name="wcag_wp_tabpanel_tabs[<?php echo esc_attr((string)$index); ?>][content]" rows="4" class="widefat"><?php echo esc_textarea($tab['content'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        <div class="field-row">
                            <label><input type="checkbox" name="wcag_wp_tabpanel_tabs[<?php echo esc_attr((string)$index); ?>][disabled]" value="1" <?php checked(!empty($tab['disabled'])); ?>> <?php esc_html_e('Disabilita tab', 'wcag-wp'); ?></label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-tabs-message">
                <div class="no-tabs-content">
                    <span class="dashicons dashicons-editor-table"></span>
                    <h4><?php esc_html_e('Nessun tab definito', 'wcag-wp'); ?></h4>
                    <p><?php esc_html_e('Clicca "Aggiungi Tab" per iniziare a creare il tuo WCAG Tab Panel.', 'wcag-wp'); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tab Template (hidden) -->
    <div id="tab-template" style="display:none;">
        <div class="tab-editor" data-index="{{INDEX}}">
            <div class="tab-header">
                <div class="tab-drag-handle"><span class="dashicons dashicons-menu"></span></div>
                <div class="tab-title"><h4><span class="tab-label"><?php esc_html_e('Nuovo Tab', 'wcag-wp'); ?></span></h4></div>
                <div class="tab-actions">
                    <button type="button" class="button-link tab-toggle" aria-expanded="true" title="<?php esc_attr_e('Apri/chiudi tab', 'wcag-wp'); ?>"><span class="dashicons dashicons-arrow-up-alt2"></span></button>
                    <button type="button" class="button-link tab-duplicate" title="<?php esc_attr_e('Duplica tab', 'wcag-wp'); ?>"><span class="dashicons dashicons-admin-page"></span></button>
                    <button type="button" class="button-link tab-delete" title="<?php esc_attr_e('Elimina tab', 'wcag-wp'); ?>"><span class="dashicons dashicons-trash"></span></button>
                </div>
            </div>
            <div class="tab-content">
                <div class="field-row">
                    <div class="field-group">
                        <label for="tab_id_{{INDEX}}"><?php esc_html_e('ID Tab', 'wcag-wp'); ?> <span class="required">*</span></label>
                        <input type="text" id="tab_id_{{INDEX}}" name="wcag_wp_tabpanel_tabs[{{INDEX}}][id]" class="tab-id-input" data-required="1" disabled>
                    </div>
                    <div class="field-group">
                        <label for="tab_label_{{INDEX}}"><?php esc_html_e('Etichetta Tab', 'wcag-wp'); ?> <span class="required">*</span></label>
                        <input type="text" id="tab_label_{{INDEX}}" name="wcag_wp_tabpanel_tabs[{{INDEX}}][label]" class="tab-label-input" data-required="1" disabled>
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-group">
                        <label for="tab_content_{{INDEX}}"><?php esc_html_e('Contenuto', 'wcag-wp'); ?></label>
                        <textarea id="tab_content_{{INDEX}}" name="wcag_wp_tabpanel_tabs[{{INDEX}}][content]" rows="4" class="widefat" disabled></textarea>
                    </div>
                </div>
                <div class="field-row">
                    <label><input type="checkbox" name="wcag_wp_tabpanel_tabs[{{INDEX}}][disabled]" value="1" disabled> <?php esc_html_e('Disabilita tab', 'wcag-wp'); ?></label>
                </div>
            </div>
        </div>
    </div>
</div>


