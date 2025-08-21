<?php
declare(strict_types=1);

/**
 * WCAG Table Preview Meta Box Template
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

$table_id = $post->ID;
$shortcode = "[wcag-table id=\"{$table_id}\"]";
?>

<div class="wcag-wp-table-preview">
    <!-- Shortcode Section -->
    <div class="preview-section">
        <h4><?php esc_html_e('Shortcode WCAG', 'wcag-wp'); ?></h4>
        <p class="description">
            <?php esc_html_e('Copia questo shortcode per inserire la WCAG tabella in pagine, articoli o widget.', 'wcag-wp'); ?>
        </p>
        <div class="shortcode-container">
            <input type="text" 
                   value="<?php echo esc_attr($shortcode); ?>" 
                   readonly 
                   class="shortcode-input"
                   id="table-shortcode">
            <button type="button" class="button copy-shortcode" data-target="table-shortcode">
                <span class="dashicons dashicons-admin-page"></span>
                <?php esc_html_e('Copia', 'wcag-wp'); ?>
            </button>
        </div>
        
        <!-- Additional shortcode parameters -->
        <div class="shortcode-examples">
            <h5><?php esc_html_e('Parametri aggiuntivi WCAG:', 'wcag-wp'); ?></h5>
            <ul>
                <li>
                    <code>[wcag-table id="<?php echo $table_id; ?>" class="my-wcag-class"]</code>
                    <span class="description"><?php esc_html_e('Aggiunge una classe CSS personalizzata', 'wcag-wp'); ?></span>
                </li>
                <li>
                    <code>[wcag-table id="<?php echo $table_id; ?>" responsive="false"]</code>
                    <span class="description"><?php esc_html_e('Disabilita il comportamento responsive WCAG', 'wcag-wp'); ?></span>
                </li>
                <li>
                    <code>[wcag-table id="<?php echo $table_id; ?>" sortable="false"]</code>
                    <span class="description"><?php esc_html_e('Disabilita l\'ordinamento WCAG', 'wcag-wp'); ?></span>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- PHP Code Section -->
    <div class="preview-section">
        <h4><?php esc_html_e('Codice PHP WCAG', 'wcag-wp'); ?></h4>
        <p class="description">
            <?php esc_html_e('Per inserire la WCAG tabella direttamente nei template PHP.', 'wcag-wp'); ?>
        </p>
        <div class="code-container">
            <code class="php-code">echo do_shortcode('[wcag-table id="<?php echo $table_id; ?>"]');</code>
            <button type="button" class="button copy-code" data-code="echo do_shortcode('[wcag-table id=&quot;<?php echo $table_id; ?>&quot;]');">
                <span class="dashicons dashicons-admin-page"></span>
                <?php esc_html_e('Copia', 'wcag-wp'); ?>
            </button>
        </div>
    </div>
    
    <!-- Table Statistics -->
    <div class="preview-section">
        <h4><?php esc_html_e('Statistiche WCAG Tabella', 'wcag-wp'); ?></h4>
        <?php
        $columns = get_post_meta($table_id, '_wcag_wp_table_columns', true);
        $data = get_post_meta($table_id, '_wcag_wp_table_data', true);
        $config = get_post_meta($table_id, '_wcag_wp_table_config', true);
        
        $columns_count = is_array($columns) ? count($columns) : 0;
        $rows_count = is_array($data) ? count($data) : 0;
        ?>
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number"><?php echo $columns_count; ?></span>
                <span class="stat-label"><?php esc_html_e('Colonne WCAG', 'wcag-wp'); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo $rows_count; ?></span>
                <span class="stat-label"><?php esc_html_e('Righe WCAG', 'wcag-wp'); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo $columns_count * $rows_count; ?></span>
                <span class="stat-label"><?php esc_html_e('Celle WCAG', 'wcag-wp'); ?></span>
            </div>
        </div>
    </div>
    
    <!-- WCAG Accessibility Checklist -->
    <div class="preview-section">
        <h4><?php esc_html_e('Checklist Accessibilità WCAG', 'wcag-wp'); ?></h4>
        <div class="accessibility-checklist">
            <div class="checklist-item <?php echo !empty($config['caption']) ? 'completed' : 'pending'; ?>">
                <span class="dashicons <?php echo !empty($config['caption']) ? 'dashicons-yes-alt' : 'dashicons-marker'; ?>"></span>
                <span class="checklist-text"><?php esc_html_e('Didascalia WCAG tabella definita', 'wcag-wp'); ?></span>
            </div>
            <div class="checklist-item <?php echo $columns_count > 0 ? 'completed' : 'pending'; ?>">
                <span class="dashicons <?php echo $columns_count > 0 ? 'dashicons-yes-alt' : 'dashicons-marker'; ?>"></span>
                <span class="checklist-text"><?php esc_html_e('Intestazioni colonne WCAG definite', 'wcag-wp'); ?></span>
            </div>
            <div class="checklist-item <?php echo $rows_count > 0 ? 'completed' : 'pending'; ?>">
                <span class="dashicons <?php echo $rows_count > 0 ? 'dashicons-yes-alt' : 'dashicons-marker'; ?>"></span>
                <span class="checklist-text"><?php esc_html_e('Dati WCAG tabella inseriti', 'wcag-wp'); ?></span>
            </div>
            <div class="checklist-item completed">
                <span class="dashicons dashicons-yes-alt"></span>
                <span class="checklist-text"><?php esc_html_e('Navigazione tastiera WCAG supportata', 'wcag-wp'); ?></span>
            </div>
            <div class="checklist-item completed">
                <span class="dashicons dashicons-yes-alt"></span>
                <span class="checklist-text"><?php esc_html_e('Markup semantico WCAG corretto', 'wcag-wp'); ?></span>
            </div>
            <div class="checklist-item completed">
                <span class="dashicons dashicons-yes-alt"></span>
                <span class="checklist-text"><?php esc_html_e('Supporto screen reader WCAG attivo', 'wcag-wp'); ?></span>
            </div>
        </div>
        
        <div class="accessibility-score">
            <?php
            $completed_items = 3; // Base WCAG items always completed
            if (!empty($config['caption'])) $completed_items++;
            if ($columns_count > 0) $completed_items++;
            if ($rows_count > 0) $completed_items++;
            
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
    
    <!-- Export Options -->
    <div class="preview-section">
        <h4><?php esc_html_e('Esportazione WCAG', 'wcag-wp'); ?></h4>
        <?php if ($rows_count > 0): ?>
            <div class="export-options">
                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-ajax.php?action=wcag_wp_export_csv&table_id=' . $table_id), 'wcag_wp_export_csv')); ?>" 
                   class="button">
                    <span class="dashicons dashicons-download"></span>
                    <?php esc_html_e('Esporta CSV WCAG', 'wcag-wp'); ?>
                </a>
            </div>
        <?php else: ?>
            <p class="description">
                <?php esc_html_e('Aggiungi dei dati WCAG per abilitare l\'esportazione.', 'wcag-wp'); ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<style>
.wcag-wp-table-preview {
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

/* Export Options */
.export-options {
    display: flex;
    gap: 10px;
}

.export-options .button {
    display: flex;
    align-items: center;
    gap: 5px;
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
    
    .export-options {
        flex-direction: column;
    }
}
</style>