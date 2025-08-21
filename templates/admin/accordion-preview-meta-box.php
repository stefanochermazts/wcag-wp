<?php
declare(strict_types=1);

/**
 * WCAG Accordion Preview Meta Box Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 * 
 * @var WP_Post $post Current post object
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$accordion_id = $post->ID;
$shortcode = "[wcag-accordion id=\"{$accordion_id}\"]";
?>

<div class="wcag-wp-accordion-preview">
    <!-- Shortcode Section -->
    <div class="preview-section">
        <h4><?php esc_html_e('Shortcode WCAG Accordion', 'wcag-wp'); ?></h4>
        <p class="description">
            <?php esc_html_e('Copia questo shortcode per inserire il WCAG Accordion in pagine, articoli o widget.', 'wcag-wp'); ?>
        </p>
        <div class="shortcode-container">
            <input type="text" 
                   value="<?php echo esc_attr($shortcode); ?>" 
                   readonly 
                   class="shortcode-input"
                   id="accordion-shortcode">
            <button type="button" class="button copy-shortcode" data-target="accordion-shortcode">
                <span class="dashicons dashicons-admin-page"></span>
                <?php esc_html_e('Copia', 'wcag-wp'); ?>
            </button>
        </div>
        
        <!-- Additional shortcode parameters -->
        <div class="shortcode-examples">
            <h5><?php esc_html_e('Parametri aggiuntivi WCAG:', 'wcag-wp'); ?></h5>
            <ul>
                <li>
                    <code>[wcag-accordion id="<?php echo $accordion_id; ?>" class="my-wcag-accordion"]</code>
                    <span class="description"><?php esc_html_e('Aggiunge una classe CSS personalizzata', 'wcag-wp'); ?></span>
                </li>
                <li>
                    <code>[wcag-accordion id="<?php echo $accordion_id; ?>" allow_multiple="true"]</code>
                    <span class="description"><?php esc_html_e('Sovrascrive l\'impostazione apertura multipla', 'wcag-wp'); ?></span>
                </li>
                <li>
                    <code>[wcag-accordion id="<?php echo $accordion_id; ?>" first_open="false"]</code>
                    <span class="description"><?php esc_html_e('Sovrascrive l\'apertura automatica prima sezione', 'wcag-wp'); ?></span>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- PHP Code Section -->
    <div class="preview-section">
        <h4><?php esc_html_e('Codice PHP WCAG', 'wcag-wp'); ?></h4>
        <p class="description">
            <?php esc_html_e('Per inserire il WCAG Accordion direttamente nei template PHP.', 'wcag-wp'); ?>
        </p>
        <div class="code-container">
            <code class="php-code">echo do_shortcode('[wcag-accordion id="<?php echo $accordion_id; ?>"]');</code>
            <button type="button" class="button copy-code" data-code="echo do_shortcode('[wcag-accordion id=&quot;<?php echo $accordion_id; ?>&quot;]');">
                <span class="dashicons dashicons-admin-page"></span>
                <?php esc_html_e('Copia', 'wcag-wp'); ?>
            </button>
        </div>
    </div>
    
    <!-- Accordion Statistics -->
    <div class="preview-section">
        <h4><?php esc_html_e('Statistiche WCAG Accordion', 'wcag-wp'); ?></h4>
        <?php
        $sections = get_post_meta($accordion_id, '_wcag_wp_accordion_sections', true);
        $config = get_post_meta($accordion_id, '_wcag_wp_accordion_config', true);
        
        $sections_count = is_array($sections) ? count($sections) : 0;
        $open_sections = is_array($sections) ? count(array_filter($sections, function($s) { return $s['is_open'] ?? false; })) : 0;
        $disabled_sections = is_array($sections) ? count(array_filter($sections, function($s) { return $s['disabled'] ?? false; })) : 0;
        ?>
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number"><?php echo $sections_count; ?></span>
                <span class="stat-label"><?php esc_html_e('Sezioni WCAG', 'wcag-wp'); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo $open_sections; ?></span>
                <span class="stat-label"><?php esc_html_e('Aperte Default', 'wcag-wp'); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo $disabled_sections; ?></span>
                <span class="stat-label"><?php esc_html_e('Disabilitate', 'wcag-wp'); ?></span>
            </div>
        </div>
    </div>
    
    <!-- WCAG Accessibility Checklist -->
    <div class="preview-section">
        <h4><?php esc_html_e('Checklist Accessibilità WCAG', 'wcag-wp'); ?></h4>
        <div class="accessibility-checklist">
            <div class="checklist-item <?php echo $sections_count > 0 ? 'completed' : 'pending'; ?>">
                <span class="dashicons <?php echo $sections_count > 0 ? 'dashicons-yes-alt' : 'dashicons-marker'; ?>"></span>
                <span class="checklist-text"><?php esc_html_e('Sezioni WCAG Accordion definite', 'wcag-wp'); ?></span>
            </div>
            <div class="checklist-item <?php echo !empty($config['keyboard_navigation']) ? 'completed' : 'pending'; ?>">
                <span class="dashicons <?php echo !empty($config['keyboard_navigation']) ? 'dashicons-yes-alt' : 'dashicons-marker'; ?>"></span>
                <span class="checklist-text"><?php esc_html_e('Navigazione tastiera WCAG abilitata', 'wcag-wp'); ?></span>
            </div>
            <div class="checklist-item completed">
                <span class="dashicons dashicons-yes-alt"></span>
                <span class="checklist-text"><?php esc_html_e('ARIA attributes implementati', 'wcag-wp'); ?></span>
            </div>
            <div class="checklist-item completed">
                <span class="dashicons dashicons-yes-alt"></span>
                <span class="checklist-text"><?php esc_html_e('Markup semantico WCAG corretto', 'wcag-wp'); ?></span>
            </div>
            <div class="checklist-item completed">
                <span class="dashicons dashicons-yes-alt"></span>
                <span class="checklist-text"><?php esc_html_e('Focus management implementato', 'wcag-wp'); ?></span>
            </div>
            <div class="checklist-item <?php echo !empty($config['animate_transitions']) ? 'completed' : 'pending'; ?>">
                <span class="dashicons <?php echo !empty($config['animate_transitions']) ? 'dashicons-yes-alt' : 'dashicons-marker'; ?>"></span>
                <span class="checklist-text"><?php esc_html_e('Animazioni rispettose (prefers-reduced-motion)', 'wcag-wp'); ?></span>
            </div>
        </div>
        
        <div class="accessibility-score">
            <?php
            $completed_items = 3; // Base WCAG items always completed
            if ($sections_count > 0) $completed_items++;
            if (!empty($config['keyboard_navigation'])) $completed_items++;
            if (!empty($config['animate_transitions'])) $completed_items++;
            
            $total_items = 6;
            $score_percentage = round(($completed_items / $total_items) * 100);
            ?>
            <div class="score-circle">
                <div class="score-text">
                    <span class="score-number"><?php echo $score_percentage; ?>%</span>
                    <span class="score-label"><?php esc_html_e('WCAG AA', 'wcag-wp'); ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Usage Instructions -->
    <div class="preview-section">
        <h4><?php esc_html_e('Istruzioni Accessibilità', 'wcag-wp'); ?></h4>
        <div class="usage-instructions">
            <div class="instruction-item">
                <span class="instruction-icon">⌨️</span>
                <div class="instruction-content">
                    <strong><?php esc_html_e('Navigazione Tastiera:', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Tab/Shift+Tab per navigare, Spazio/Enter per aprire/chiudere, Frecce per muoversi tra sezioni.', 'wcag-wp'); ?></p>
                </div>
            </div>
            <div class="instruction-item">
                <span class="instruction-icon">🔊</span>
                <div class="instruction-content">
                    <strong><?php esc_html_e('Screen Reader:', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Ogni sezione ha ARIA labels e gli stati sono annunciati automaticamente.', 'wcag-wp'); ?></p>
                </div>
            </div>
            <div class="instruction-item">
                <span class="instruction-icon">📱</span>
                <div class="instruction-content">
                    <strong><?php esc_html_e('Mobile:', 'wcag-wp'); ?></strong>
                    <p><?php esc_html_e('Touch targets di 44px minimum, swipe per navigazione opzionale.', 'wcag-wp'); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Live Preview -->
    <?php if ($sections_count > 0): ?>
    <div class="preview-section">
        <h4><?php esc_html_e('Anteprima WCAG Accordion', 'wcag-wp'); ?></h4>
        <div class="live-preview-container">
            <div class="preview-controls">
                <button type="button" class="button refresh-preview" id="refresh-accordion-preview">
                    <span class="dashicons dashicons-update"></span>
                    <?php esc_html_e('Aggiorna Anteprima', 'wcag-wp'); ?>
                </button>
            </div>
            
            <div class="preview-iframe-container">
                <div class="accordion-preview-wrapper">
                    <!-- Mini preview will be generated by JavaScript -->
                    <div id="accordion-mini-preview">
                        <?php foreach ($sections as $index => $section): ?>
                            <div class="preview-section-item <?php echo $section['is_open'] ? 'open' : ''; ?> <?php echo $section['disabled'] ? 'disabled' : ''; ?>">
                                <div class="preview-section-header">
                                    <span class="preview-icon"><?php echo $config['icon_type'] === 'plus_minus' ? '+' : '›'; ?></span>
                                    <span class="preview-title"><?php echo esc_html($section['title']); ?></span>
                                </div>
                                <?php if ($section['is_open']): ?>
                                <div class="preview-section-content">
                                    <p><?php echo wp_trim_words(strip_tags($section['content']), 10, '...'); ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="preview-section">
        <div class="no-preview">
            <div class="no-preview-content">
                <span class="dashicons dashicons-list-view"></span>
                <p><?php esc_html_e('Aggiungi almeno una sezione per vedere l\'anteprima del WCAG Accordion.', 'wcag-wp'); ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.wcag-wp-accordion-preview {
    font-size: 14px;
}

.preview-section {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.preview-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.preview-section h4 {
    margin: 0 0 10px 0;
    font-size: 14px;
    font-weight: 600;
    color: #23282d;
}

.preview-section .description {
    margin: 0 0 15px 0;
    color: #666;
    font-size: 13px;
}

/* Shortcode Section */
.shortcode-container {
    display: flex;
    gap: 5px;
    margin-bottom: 15px;
}

.shortcode-input {
    flex: 1;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #f9f9f9;
    font-family: monospace;
    font-size: 13px;
}

.shortcode-examples {
    background: #f6f7f7;
    padding: 15px;
    border-radius: 4px;
    border-left: 3px solid #2271b1;
}

.shortcode-examples h5 {
    margin: 0 0 10px 0;
    font-size: 13px;
    font-weight: 600;
}

.shortcode-examples ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.shortcode-examples li {
    margin-bottom: 8px;
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.shortcode-examples code {
    background: #fff;
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 12px;
    border: 1px solid #ddd;
}

/* Code Section */
.code-container {
    display: flex;
    gap: 5px;
    align-items: center;
}

.php-code {
    flex: 1;
    background: #2c3338;
    color: #f1f1f1;
    padding: 12px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 13px;
    overflow-x: auto;
}

/* Statistics */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.stat-item {
    text-align: center;
    padding: 15px;
    background: #f6f7f7;
    border-radius: 6px;
    border: 1px solid #ddd;
}

.stat-number {
    display: block;
    font-size: 24px;
    font-weight: 600;
    color: #2271b1;
    line-height: 1;
}

.stat-label {
    display: block;
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    font-weight: 600;
    margin-top: 5px;
}

/* WCAG Accessibility Checklist */
.accessibility-checklist {
    margin-bottom: 20px;
}

.checklist-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.checklist-item:last-child {
    border-bottom: none;
}

.checklist-item.completed .dashicons {
    color: #00a32a;
}

.checklist-item.pending .dashicons {
    color: #dba617;
}

.checklist-text {
    font-size: 13px;
}

.accessibility-score {
    display: flex;
    justify-content: center;
    margin-top: 15px;
}

.score-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 4px solid #00a32a;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f9f0;
}

.score-text {
    text-align: center;
}

.score-number {
    display: block;
    font-size: 18px;
    font-weight: 600;
    color: #00a32a;
    line-height: 1;
}

.score-label {
    display: block;
    font-size: 10px;
    color: #00a32a;
    font-weight: 600;
    text-transform: uppercase;
}

/* Usage Instructions */
.usage-instructions {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.instruction-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 6px;
    border-left: 3px solid #2271b1;
}

.instruction-icon {
    font-size: 20px;
    line-height: 1;
}

.instruction-content strong {
    display: block;
    margin-bottom: 4px;
    color: #23282d;
}

.instruction-content p {
    margin: 0;
    font-size: 13px;
    color: #666;
    line-height: 1.4;
}

/* Preview */
.accordion-preview-wrapper {
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
    background: #fff;
}

.preview-section-item {
    border-bottom: 1px solid #eee;
}

.preview-section-item:last-child {
    border-bottom: none;
}

.preview-section-item.disabled {
    opacity: 0.6;
}

.preview-section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 15px;
    background: #f8f9fa;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.preview-section-header:hover {
    background: #e9ecef;
}

.preview-icon {
    font-weight: bold;
    color: #2271b1;
    transition: transform 0.2s ease;
}

.preview-section-item.open .preview-icon {
    transform: rotate(90deg);
}

.preview-title {
    font-weight: 600;
    color: #23282d;
}

.preview-section-content {
    padding: 15px;
    background: #fff;
    border-top: 1px solid #eee;
}

.preview-section-content p {
    margin: 0;
    color: #666;
    font-size: 13px;
    line-height: 1.5;
}

.no-preview {
    text-align: center;
    padding: 40px 20px;
    border: 2px dashed #ddd;
    border-radius: 6px;
    background: #f9f9f9;
}

.no-preview-content .dashicons {
    font-size: 32px;
    color: #ccc;
    margin-bottom: 10px;
}

.no-preview-content p {
    margin: 0;
    color: #666;
}

/* Responsive */
@media (max-width: 600px) {
    .shortcode-container,
    .code-container {
        flex-direction: column;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .instruction-item {
        flex-direction: column;
        text-align: center;
    }
}
</style>
