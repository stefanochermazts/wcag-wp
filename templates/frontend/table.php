<?php
declare(strict_types=1);

/**
 * Frontend Table Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 * 
 * @var int $table_id Table post ID
 * @var WP_Post $table_post Table post object
 * @var array $config Table configuration
 * @var array $columns Table columns
 * @var array $data Table data
 * @var array $options Display options
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue frontend assets
wp_enqueue_style('wcag-wp-frontend');
wp_enqueue_script('wcag-wp-frontend');

// Generate unique IDs for accessibility
$table_html_id = 'wcag-table-' . $table_id;
$search_id = 'wcag-search-' . $table_id;
$caption_id = 'wcag-caption-' . $table_id;
$summary_id = 'wcag-summary-' . $table_id;

// Build CSS classes
$container_classes = [
    'wcag-wp-table-container',
    'wcag-wp'
];

if (!empty($options['class'])) {
    $container_classes[] = sanitize_html_class($options['class']);
}

$table_classes = [
    'wcag-wp-table'
];

if ($config['responsive'] ?? true) {
    $table_classes[] = 'wcag-wp-table--responsive';
}

if ($config['stack_mobile'] ?? false) {
    $table_classes[] = 'stack-on-mobile';
}

if ($config['sortable'] ?? true) {
    $table_classes[] = 'wcag-wp-table--sortable';
}

// Build data attributes
$data_attributes = [
    'data-table-id' => $table_id,
    'data-sortable' => $config['sortable'] ? 'true' : 'false',
    'data-searchable' => $config['searchable'] ? 'true' : 'false',
    'data-responsive' => $config['responsive'] ? 'true' : 'false',
    'data-stack-mobile' => $config['stack_mobile'] ? 'true' : 'false'
];

?>

<div class="<?php echo esc_attr(implode(' ', $container_classes)); ?>" <?php echo implode(' ', array_map(function($k, $v) { return $k . '="' . esc_attr($v) . '"'; }, array_keys($data_attributes), $data_attributes)); ?>>
    
    <!-- Skip Link for Screen Readers -->
    <a href="#<?php echo esc_attr($table_html_id); ?>" class="wcag-wp skip-link">
        <?php esc_html_e('Salta alla tabella', 'wcag-wp'); ?>
    </a>
    
    <!-- Table Header with Title and Controls -->
    <?php if (!empty($table_post->post_title) || $config['searchable'] || $config['export_csv']): ?>
        <div class="wcag-wp-table-header">
            <?php if (!empty($table_post->post_title)): ?>
                <h3 class="wcag-wp-table-title" id="<?php echo esc_attr($caption_id); ?>">
                    <?php echo esc_html($table_post->post_title); ?>
                </h3>
            <?php endif; ?>
            
            <?php if ($config['searchable'] || $config['export_csv']): ?>
                <div class="wcag-wp-table-controls">
                    <?php if ($config['searchable']): ?>
                        <div class="wcag-wp-search-container">
                            <label for="<?php echo esc_attr($search_id); ?>" class="wcag-wp-search-label">
                                <?php esc_html_e('Cerca nella tabella:', 'wcag-wp'); ?>
                            </label>
                            <input type="search" 
                                   id="<?php echo esc_attr($search_id); ?>"
                                   class="wcag-wp-search-input" 
                                   placeholder="<?php esc_attr_e('Cerca...', 'wcag-wp'); ?>"
                                   aria-describedby="<?php echo esc_attr($search_id); ?>-help">
                            <div id="<?php echo esc_attr($search_id); ?>-help" class="wcag-wp-search-help sr-only">
                                <?php esc_html_e('Digita per filtrare le righe della tabella in tempo reale', 'wcag-wp'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($config['export_csv']): ?>
                        <button type="button" 
                                class="wcag-wp-button wcag-wp-button--secondary wcag-wp-export-csv"
                                data-table-id="<?php echo esc_attr($table_id); ?>">
                            <span class="dashicons dashicons-download" aria-hidden="true"></span>
                            <?php esc_html_e('Esporta CSV', 'wcag-wp'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <!-- Summary for Screen Readers -->
    <?php if (!empty($config['summary'])): ?>
        <div id="<?php echo esc_attr($summary_id); ?>" class="wcag-wp-table-summary sr-only">
            <?php echo esc_html($config['summary']); ?>
        </div>
    <?php endif; ?>
    
    <!-- Table Results Counter -->
    <div class="wcag-wp-table-results" id="table-results-<?php echo esc_attr($table_id); ?>" aria-live="polite" aria-atomic="true">
        <span class="sr-only"><?php esc_html_e('Risultati della tabella:', 'wcag-wp'); ?></span>
        <span class="results-text">
            <?php printf(esc_html__('Visualizzate %d righe di %d totali', 'wcag-wp'), count($data), count($data)); ?>
        </span>
    </div>
    
    <!-- Table Wrapper for Responsive Scroll -->
    <div class="wcag-wp-table-wrapper">
        <table class="<?php echo esc_attr(implode(' ', $table_classes)); ?>" 
               id="<?php echo esc_attr($table_html_id); ?>"
               role="table"
               <?php if (!empty($config['caption'])): ?>
               aria-labelledby="<?php echo esc_attr($caption_id); ?>"
               <?php endif; ?>
               <?php if (!empty($config['summary'])): ?>
               aria-describedby="<?php echo esc_attr($summary_id); ?>"
               <?php endif; ?>>
            
            <!-- Caption for Accessibility -->
            <?php if (!empty($config['caption'])): ?>
                <caption class="wcag-wp-table-caption">
                    <?php echo esc_html($config['caption']); ?>
                </caption>
            <?php endif; ?>
            
            <!-- Table Header -->
            <thead>
                <tr>
                    <?php foreach ($columns as $column_index => $column): ?>
                        <th scope="col" 
                            class="wcag-wp-table-header-cell"
                            data-column="<?php echo esc_attr($column['id']); ?>"
                            data-type="<?php echo esc_attr($column['type']); ?>"
                            <?php if ($column['sortable'] && $config['sortable']): ?>
                            aria-sort="none"
                            tabindex="0"
                            role="columnheader button"
                            <?php else: ?>
                            role="columnheader"
                            <?php endif; ?>
                            <?php if (!empty($column['description'])): ?>
                            aria-describedby="col-desc-<?php echo esc_attr($table_id); ?>-<?php echo esc_attr($column['id']); ?>"
                            <?php endif; ?>
                            <?php if (!empty($column['width'])): ?>
                            style="width: <?php echo esc_attr($column['width']); ?>;"
                            <?php endif; ?>>
                            
                            <div class="wcag-wp-header-content">
                                <span class="wcag-wp-header-text">
                                    <?php echo esc_html($column['label']); ?>
                                </span>
                                <?php if ($column['sortable'] && $config['sortable']): ?>
                                    <span class="wcag-wp-sort-indicator" aria-hidden="true">
                                        <span class="sort-arrow sort-asc">▲</span>
                                        <span class="sort-arrow sort-desc">▼</span>
                                    </span>
                                    <span class="sr-only wcag-wp-sort-instructions">
                                        <?php esc_html_e('Premi Invio o Spazio per ordinare questa colonna', 'wcag-wp'); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Column Description for Screen Readers -->
                            <?php if (!empty($column['description'])): ?>
                                <div id="col-desc-<?php echo esc_attr($table_id); ?>-<?php echo esc_attr($column['id']); ?>" class="sr-only">
                                    <?php echo esc_html($column['description']); ?>
                                </div>
                            <?php endif; ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            
            <!-- Table Body -->
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach ($data as $row_index => $row): ?>
                        <tr class="wcag-wp-table-row" data-row="<?php echo esc_attr($row_index); ?>">
                            <?php foreach ($columns as $column): ?>
                                <?php 
                                $cell_value = $row[$column['id']] ?? '';
                                $cell_classes = [
                                    'wcag-wp-table-cell',
                                    'wcag-wp-table-cell--' . $column['type']
                                ];
                                
                                if (!empty($column['align'])) {
                                    $cell_classes[] = 'wcag-wp-table-cell--align-' . $column['align'];
                                }
                                ?>
                                <td class="<?php echo esc_attr(implode(' ', $cell_classes)); ?>"
                                    data-column="<?php echo esc_attr($column['id']); ?>"
                                    data-type="<?php echo esc_attr($column['type']); ?>"
                                    <?php if ($config['stack_mobile']): ?>
                                    data-label="<?php echo esc_attr($column['label']); ?>"
                                    <?php endif; ?>>
                                    
                                    <?php
                                    // Render cell content based on column type
                                    switch ($column['type']) {
                                        case 'url':
                                            if (!empty($cell_value) && filter_var($cell_value, FILTER_VALIDATE_URL)) {
                                                printf(
                                                    '<a href="%s" class="wcag-wp-table-link" target="_blank" rel="noopener">%s<span class="sr-only"> %s</span></a>',
                                                    esc_url($cell_value),
                                                    esc_html($cell_value),
                                                    esc_html__('(si apre in una nuova finestra)', 'wcag-wp')
                                                );
                                            } else {
                                                echo esc_html($cell_value);
                                            }
                                            break;
                                            
                                        case 'email':
                                            if (!empty($cell_value) && is_email($cell_value)) {
                                                printf(
                                                    '<a href="mailto:%s" class="wcag-wp-table-email">%s</a>',
                                                    esc_attr($cell_value),
                                                    esc_html($cell_value)
                                                );
                                            } else {
                                                echo esc_html($cell_value);
                                            }
                                            break;
                                            
                                        case 'image':
                                            if (!empty($cell_value) && filter_var($cell_value, FILTER_VALIDATE_URL)) {
                                                printf(
                                                    '<img src="%s" alt="%s" class="wcag-wp-table-image" loading="lazy">',
                                                    esc_url($cell_value),
                                                    esc_attr(sprintf(__('Immagine per %s', 'wcag-wp'), $column['label']))
                                                );
                                            } else {
                                                echo esc_html($cell_value);
                                            }
                                            break;
                                            
                                        case 'boolean':
                                            if ($cell_value === '1') {
                                                echo '<span class="wcag-wp-boolean wcag-wp-boolean--yes" aria-label="' . esc_attr__('Sì', 'wcag-wp') . '">✓</span>';
                                            } elseif ($cell_value === '0') {
                                                echo '<span class="wcag-wp-boolean wcag-wp-boolean--no" aria-label="' . esc_attr__('No', 'wcag-wp') . '">✗</span>';
                                            } else {
                                                echo '<span class="wcag-wp-boolean wcag-wp-boolean--empty" aria-label="' . esc_attr__('Non specificato', 'wcag-wp') . '">—</span>';
                                            }
                                            break;
                                            
                                        case 'currency':
                                            if (is_numeric($cell_value)) {
                                                echo '<span class="wcag-wp-currency">' . esc_html(number_format((float)$cell_value, 2, ',', '.')) . ' €</span>';
                                            } else {
                                                echo esc_html($cell_value);
                                            }
                                            break;
                                            
                                        case 'number':
                                            if (is_numeric($cell_value)) {
                                                echo '<span class="wcag-wp-number">' . esc_html(number_format((float)$cell_value, 0, ',', '.')) . '</span>';
                                            } else {
                                                echo esc_html($cell_value);
                                            }
                                            break;
                                            
                                        case 'date':
                                            if (!empty($cell_value)) {
                                                $date = DateTime::createFromFormat('Y-m-d', $cell_value);
                                                if ($date) {
                                                    echo '<time datetime="' . esc_attr($cell_value) . '">' . esc_html($date->format('d/m/Y')) . '</time>';
                                                } else {
                                                    echo esc_html($cell_value);
                                                }
                                            }
                                            break;
                                            
                                        default: // text
                                            echo esc_html($cell_value);
                                            break;
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="wcag-wp-table-empty">
                        <td colspan="<?php echo count($columns); ?>" class="wcag-wp-table-empty-cell">
                            <div class="wcag-wp-empty-content">
                                <span class="dashicons dashicons-database" aria-hidden="true"></span>
                                <p><?php esc_html_e('Nessun dato disponibile', 'wcag-wp'); ?></p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination (if enabled) -->
    <?php if ($config['pagination'] && !empty($data) && count($data) > ($config['rows_per_page'] ?? 10)): ?>
        <nav class="wcag-wp-pagination" role="navigation" aria-label="<?php esc_attr_e('Navigazione tabella', 'wcag-wp'); ?>">
            <!-- Pagination will be handled by JavaScript -->
            <div class="wcag-wp-pagination-info" aria-live="polite">
                <span class="pagination-info-text"></span>
            </div>
            <ul class="wcag-wp-pagination-list" role="list">
                <!-- Pagination items will be generated by JavaScript -->
            </ul>
        </nav>
    <?php endif; ?>
    
    <!-- Loading Indicator -->
    <div class="wcag-wp-loading-indicator" style="display: none;" aria-hidden="true">
        <div class="wcag-wp-spinner"></div>
        <span class="sr-only"><?php esc_html_e('Caricamento...', 'wcag-wp'); ?></span>
    </div>
    
    <!-- Screen Reader Announcements -->
    <div class="wcag-wp-sr-announcements" aria-live="polite" aria-atomic="true" class="sr-only"></div>
    
</div>

<!-- Initialize table functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.wcagWpFrontend !== 'undefined') {
        // Table will be automatically initialized by the frontend JavaScript
        console.log('WCAG-WP Table <?php echo esc_js($table_id); ?> initialized');
    }
});
</script>
