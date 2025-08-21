<?php
declare(strict_types=1);

/**
 * WCAG Accordion Sections Meta Box Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 * 
 * @var WP_Post $post Current post object
 * @var array $sections Accordion sections
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-wp-accordion-sections">
    <div class="sections-header">
        <p class="description">
            <?php esc_html_e('Crea e gestisci le sezioni del WCAG Accordion. Puoi riordinare le sezioni trascinandole.', 'wcag-wp'); ?>
        </p>
        <button type="button" class="button button-primary" id="add-new-section">
            <span class="dashicons dashicons-plus-alt2"></span>
            <?php esc_html_e('Aggiungi Sezione WCAG', 'wcag-wp'); ?>
        </button>
    </div>
    
    <div class="sections-container" id="sections-container">
        <?php if (!empty($sections)): ?>
            <?php foreach ($sections as $index => $section): ?>
                <div class="section-editor" data-index="<?php echo esc_attr($index); ?>">
                    <div class="section-header">
                        <div class="section-drag-handle">
                            <span class="dashicons dashicons-menu"></span>
                        </div>
                        <div class="section-title">
                            <h4>
                                <span class="section-label"><?php echo esc_html($section['title'] ?: __('Nuova Sezione WCAG', 'wcag-wp')); ?></span>
                                <?php if ($section['is_open']): ?>
                                    <span class="section-status-badge open"><?php esc_html_e('Aperta', 'wcag-wp'); ?></span>
                                <?php endif; ?>
                                <?php if ($section['disabled']): ?>
                                    <span class="section-status-badge disabled"><?php esc_html_e('Disabilitata', 'wcag-wp'); ?></span>
                                <?php endif; ?>
                            </h4>
                        </div>
                        <div class="section-actions">
                            <button type="button" class="button-link section-toggle" aria-expanded="true">
                                <span class="dashicons dashicons-arrow-up-alt2"></span>
                                <span class="screen-reader-text"><?php esc_html_e('Collassa sezione', 'wcag-wp'); ?></span>
                            </button>
                            <button type="button" class="button-link section-delete" title="<?php esc_attr_e('Elimina sezione', 'wcag-wp'); ?>">
                                <span class="dashicons dashicons-trash"></span>
                                <span class="screen-reader-text"><?php esc_html_e('Elimina sezione', 'wcag-wp'); ?></span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="section-content">
                        <div class="section-fields">
                            <div class="field-row">
                                <div class="field-group">
                                    <label for="section_id_<?php echo esc_attr($index); ?>">
                                        <?php esc_html_e('ID Sezione WCAG', 'wcag-wp'); ?> <span class="required">*</span>
                                    </label>
                                    <input type="text" 
                                           id="section_id_<?php echo esc_attr($index); ?>"
                                           name="wcag_wp_accordion_sections[<?php echo esc_attr($index); ?>][id]" 
                                           value="<?php echo esc_attr($section['id']); ?>"
                                           class="section-id-input"
                                           pattern="[a-z0-9_-]+"
                                           required
                                           placeholder="<?php esc_attr_e('es: sezione_1', 'wcag-wp'); ?>">
                                    <p class="description">
                                        <?php esc_html_e('Identificatore univoco (solo lettere minuscole, numeri, underscore e trattini).', 'wcag-wp'); ?>
                                    </p>
                                </div>
                                
                                <div class="field-group">
                                    <label for="section_title_<?php echo esc_attr($index); ?>">
                                        <?php esc_html_e('Titolo Sezione WCAG', 'wcag-wp'); ?> <span class="required">*</span>
                                    </label>
                                    <input type="text" 
                                           id="section_title_<?php echo esc_attr($index); ?>"
                                           name="wcag_wp_accordion_sections[<?php echo esc_attr($index); ?>][title]" 
                                           value="<?php echo esc_attr($section['title']); ?>"
                                           class="section-title-input"
                                           required
                                           placeholder="<?php esc_attr_e('es: Informazioni Generali', 'wcag-wp'); ?>">
                                    <p class="description">
                                        <?php esc_html_e('Titolo visibile dell\'intestazione della sezione.', 'wcag-wp'); ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="field-row">
                                <div class="field-group full-width">
                                    <label for="section_content_<?php echo esc_attr($index); ?>">
                                        <?php esc_html_e('Contenuto Sezione WCAG', 'wcag-wp'); ?>
                                    </label>
                                    <?php
                                    $editor_id = 'section_content_' . $index;
                                    $editor_settings = [
                                        'textarea_name' => "wcag_wp_accordion_sections[{$index}][content]",
                                        'textarea_rows' => 10,
                                        'media_buttons' => true,
                                        'teeny' => false,
                                        'quicktags' => true,
                                        'tinymce' => [
                                            'toolbar1' => 'bold,italic,underline,strikethrough,|,bullist,numlist,blockquote,|,link,unlink,|,undo,redo',
                                            'toolbar2' => 'formatselect,|,pastetext,removeformat,|,charmap,|,outdent,indent,|,wp_help'
                                        ]
                                    ];
                                    wp_editor($section['content'], $editor_id, $editor_settings);
                                    ?>
                                    <p class="description">
                                        <?php esc_html_e('Contenuto della sezione. Supporta HTML e shortcode.', 'wcag-wp'); ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="field-row">
                                <div class="field-group">
                                    <label for="section_order_<?php echo esc_attr($index); ?>">
                                        <?php esc_html_e('Ordine Visualizzazione', 'wcag-wp'); ?>
                                    </label>
                                    <input type="number" 
                                           id="section_order_<?php echo esc_attr($index); ?>"
                                           name="wcag_wp_accordion_sections[<?php echo esc_attr($index); ?>][order]" 
                                           value="<?php echo esc_attr($section['order']); ?>"
                                           min="0"
                                           step="1"
                                           class="small-text">
                                    <p class="description">
                                        <?php esc_html_e('Numero di ordinamento (0 = primo).', 'wcag-wp'); ?>
                                    </p>
                                </div>
                                
                                <div class="field-group">
                                    <label for="section_css_class_<?php echo esc_attr($index); ?>">
                                        <?php esc_html_e('Classe CSS Personalizzata', 'wcag-wp'); ?>
                                    </label>
                                    <input type="text" 
                                           id="section_css_class_<?php echo esc_attr($index); ?>"
                                           name="wcag_wp_accordion_sections[<?php echo esc_attr($index); ?>][css_class]" 
                                           value="<?php echo esc_attr($section['css_class']); ?>"
                                           placeholder="<?php esc_attr_e('es: sezione-speciale', 'wcag-wp'); ?>">
                                    <p class="description">
                                        <?php esc_html_e('Classe CSS aggiuntiva per questa sezione.', 'wcag-wp'); ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="field-row">
                                <div class="field-group">
                                    <fieldset>
                                        <legend><?php esc_html_e('Opzioni Sezione WCAG', 'wcag-wp'); ?></legend>
                                        <label>
                                            <input type="checkbox" 
                                                   name="wcag_wp_accordion_sections[<?php echo esc_attr($index); ?>][is_open]" 
                                                   value="1" 
                                                   <?php checked($section['is_open']); ?>>
                                            <?php esc_html_e('Aperta di default', 'wcag-wp'); ?>
                                        </label>
                                        <label>
                                            <input type="checkbox" 
                                                   name="wcag_wp_accordion_sections[<?php echo esc_attr($index); ?>][disabled]" 
                                                   value="1" 
                                                   <?php checked($section['disabled']); ?>>
                                            <?php esc_html_e('Sezione disabilitata', 'wcag-wp'); ?>
                                        </label>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-sections-message">
                <div class="no-sections-content">
                    <span class="dashicons dashicons-list-view"></span>
                    <h4><?php esc_html_e('Nessuna sezione WCAG definita', 'wcag-wp'); ?></h4>
                    <p><?php esc_html_e('Clicca "Aggiungi Sezione WCAG" per iniziare a creare il tuo accordion accessibile.', 'wcag-wp'); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Section Template (hidden) -->
    <div id="section-template" style="display: none;">
        <div class="section-editor" data-index="{{INDEX}}">
            <div class="section-header">
                <div class="section-drag-handle">
                    <span class="dashicons dashicons-menu"></span>
                </div>
                <div class="section-title">
                    <h4>
                        <span class="section-label"><?php esc_html_e('Nuova Sezione WCAG', 'wcag-wp'); ?></span>
                    </h4>
                </div>
                <div class="section-actions">
                    <button type="button" class="button-link section-toggle" aria-expanded="true">
                        <span class="dashicons dashicons-arrow-up-alt2"></span>
                    </button>
                    <button type="button" class="button-link section-delete">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                </div>
            </div>
            
            <div class="section-content">
                <div class="section-fields">
                    <div class="field-row">
                        <div class="field-group">
                            <label for="section_id_{{INDEX}}">
                                <?php esc_html_e('ID Sezione WCAG', 'wcag-wp'); ?> <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   id="section_id_{{INDEX}}"
                                   name="wcag_wp_accordion_sections[{{INDEX}}][id]" 
                                   class="section-id-input"
                                   required>
                        </div>
                        
                        <div class="field-group">
                            <label for="section_title_{{INDEX}}">
                                <?php esc_html_e('Titolo Sezione WCAG', 'wcag-wp'); ?> <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   id="section_title_{{INDEX}}"
                                   name="wcag_wp_accordion_sections[{{INDEX}}][title]" 
                                   class="section-title-input"
                                   required>
                        </div>
                    </div>
                    
                    <div class="field-row">
                        <div class="field-group full-width">
                            <label for="section_content_{{INDEX}}">
                                <?php esc_html_e('Contenuto Sezione WCAG', 'wcag-wp'); ?>
                            </label>
                            <textarea id="section_content_{{INDEX}}"
                                      name="wcag_wp_accordion_sections[{{INDEX}}][content]" 
                                      rows="5"
                                      class="large-text"></textarea>
                        </div>
                    </div>
                    
                    <div class="field-row">
                        <div class="field-group">
                            <label for="section_order_{{INDEX}}">
                                <?php esc_html_e('Ordine Visualizzazione', 'wcag-wp'); ?>
                            </label>
                            <input type="number" 
                                   id="section_order_{{INDEX}}"
                                   name="wcag_wp_accordion_sections[{{INDEX}}][order]" 
                                   value="0"
                                   min="0"
                                   step="1"
                                   class="small-text">
                        </div>
                        
                        <div class="field-group">
                            <label for="section_css_class_{{INDEX}}">
                                <?php esc_html_e('Classe CSS Personalizzata', 'wcag-wp'); ?>
                            </label>
                            <input type="text" 
                                   id="section_css_class_{{INDEX}}"
                                   name="wcag_wp_accordion_sections[{{INDEX}}][css_class]">
                        </div>
                    </div>
                    
                    <div class="field-row">
                        <div class="field-group">
                            <fieldset>
                                <legend><?php esc_html_e('Opzioni Sezione WCAG', 'wcag-wp'); ?></legend>
                                <label>
                                    <input type="checkbox" 
                                           name="wcag_wp_accordion_sections[{{INDEX}}][is_open]" 
                                           value="1">
                                    <?php esc_html_e('Aperta di default', 'wcag-wp'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" 
                                           name="wcag_wp_accordion_sections[{{INDEX}}][disabled]" 
                                           value="1">
                                    <?php esc_html_e('Sezione disabilitata', 'wcag-wp'); ?>
                                </label>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.wcag-wp-accordion-sections {
    margin: 0;
}

.sections-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #ddd;
}

.sections-header .description {
    margin: 0;
    flex: 1;
}

.sections-container {
    min-height: 100px;
}

.no-sections-message {
    text-align: center;
    padding: 40px 20px;
    border: 2px dashed #ddd;
    border-radius: 8px;
    background: #f9f9f9;
}

.no-sections-content .dashicons {
    font-size: 48px;
    color: #ccc;
}

.no-sections-content h4 {
    margin: 10px 0 5px 0;
    color: #666;
}

.no-sections-content p {
    margin: 0;
    color: #666;
}

.section-editor {
    border: 1px solid #ddd;
    border-radius: 6px;
    margin-bottom: 15px;
    background: #fff;
    transition: box-shadow 0.2s ease;
}

.section-editor:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.section-editor.sortable-placeholder {
    border-style: dashed;
    background: #f0f8ff;
}

.section-header {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    background: #f6f7f7;
    border-bottom: 1px solid #ddd;
    border-radius: 6px 6px 0 0;
    cursor: pointer;
}

.section-drag-handle {
    margin-right: 10px;
    color: #999;
    cursor: grab;
}

.section-drag-handle:active {
    cursor: grabbing;
}

.section-title {
    flex: 1;
}

.section-title h4 {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.section-status-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.section-status-badge.open {
    background: #00a32a;
    color: white;
}

.section-status-badge.disabled {
    background: #d63638;
    color: white;
}

.section-actions {
    display: flex;
    gap: 5px;
}

.section-actions .button-link {
    padding: 4px;
    border: none;
    background: none;
    color: #666;
    cursor: pointer;
    border-radius: 3px;
    transition: background-color 0.2s ease;
}

.section-actions .button-link:hover {
    background: #ddd;
    color: #000;
}

.section-content {
    padding: 20px;
}

.section-content.collapsed {
    display: none;
}

.field-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.field-group.full-width {
    grid-column: 1 / -1;
}

.field-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    color: #23282d;
}

.field-group .required {
    color: #d63638;
}

.field-group input,
.field-group select,
.field-group textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.2s ease;
}

.field-group input:focus,
.field-group select:focus,
.field-group textarea:focus {
    border-color: #2271b1;
    box-shadow: 0 0 0 1px #2271b1;
    outline: none;
}

.field-group .description {
    margin: 5px 0 0 0;
    font-size: 13px;
    color: #666;
}

.field-group fieldset {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    background: #f9f9f9;
}

.field-group fieldset legend {
    padding: 0 8px;
    font-size: 13px;
    font-weight: 600;
}

.field-group fieldset label {
    display: block;
    margin: 5px 0;
    font-weight: normal;
}

.field-group fieldset input[type="checkbox"] {
    width: auto;
    margin-right: 8px;
}

@media (max-width: 782px) {
    .sections-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .field-row {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-wrap: wrap;
    }
    
    .section-actions {
        width: 100%;
        justify-content: flex-end;
        margin-top: 10px;
    }
}
</style>
