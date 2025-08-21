<?php
declare(strict_types=1);

/**
 * WCAG Tables Management Class
 * 
 * Handles all table-related functionality including:
 * - Custom Post Type registration
 * - Meta boxes for table configuration
 * - Column definitions and data management
 * - Frontend rendering and accessibility
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WCAG_WP_Tables Class
 * 
 * Manages accessible table components with WCAG prefix
 */
class WCAG_WP_Tables {
    
    /**
     * Table configuration meta key
     */
    const META_CONFIG = '_wcag_wp_table_config';
    
    /**
     * Table data meta key
     */
    const META_DATA = '_wcag_wp_table_data';
    
    /**
     * Table columns meta key
     */
    const META_COLUMNS = '_wcag_wp_table_columns';
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }
    
    /**
     * Initialize table functionality
     * 
     * @return void
     */
    public function init(): void {
        // Register custom post type
        add_action('init', [$this, 'register_post_type']);
        
        // Admin hooks
        if (is_admin()) {
            add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
            add_action('save_post', [$this, 'save_table_meta']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
            
            // AJAX handlers
            add_action('wp_ajax_wcag_wp_save_table_column', [$this, 'ajax_save_table_column']);
            add_action('wp_ajax_wcag_wp_delete_table_column', [$this, 'ajax_delete_table_column']);
            add_action('wp_ajax_wcag_wp_save_table_row', [$this, 'ajax_save_table_row']);
            add_action('wp_ajax_wcag_wp_delete_table_row', [$this, 'ajax_delete_table_row']);
            add_action('wp_ajax_wcag_wp_export_csv', [$this, 'ajax_export_csv']);
        }
        
        // Frontend hooks
        add_shortcode('wcag-table', [$this, 'shortcode_table']);
        add_action('init', [$this, 'register_gutenberg_blocks']);
        
        wcag_wp_log('WCAG Tables component initialized', 'info');
    }
    
    /**
     * Register Custom Post Type for WCAG tables
     * 
     * @return void
     */
    public function register_post_type(): void {
        $labels = [
            'name'                  => __('WCAG Tabelle', 'wcag-wp'),
            'singular_name'         => __('WCAG Tabella', 'wcag-wp'),
            'menu_name'             => __('WCAG Tabelle', 'wcag-wp'),
            'name_admin_bar'        => __('WCAG Tabella', 'wcag-wp'),
            'add_new'              => __('Aggiungi Nuova', 'wcag-wp'),
            'add_new_item'         => __('Aggiungi Nuova WCAG Tabella', 'wcag-wp'),
            'new_item'             => __('Nuova WCAG Tabella', 'wcag-wp'),
            'edit_item'            => __('Modifica WCAG Tabella', 'wcag-wp'),
            'view_item'            => __('Visualizza WCAG Tabella', 'wcag-wp'),
            'all_items'            => __('Tutte le WCAG Tabelle', 'wcag-wp'),
            'search_items'         => __('Cerca WCAG Tabelle', 'wcag-wp'),
            'parent_item_colon'    => __('WCAG Tabelle Padre:', 'wcag-wp'),
            'not_found'            => __('Nessuna WCAG tabella trovata.', 'wcag-wp'),
            'not_found_in_trash'   => __('Nessuna WCAG tabella nel cestino.', 'wcag-wp'),
        ];

        $args = [
            'labels'             => $labels,
            'description'        => __('WCAG Tabelle accessibili 2.1 AA compliant', 'wcag-wp'),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => 'wcag-wp-main',
            'query_var'          => false,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-grid-view',
            'supports'           => ['title'],
            'show_in_rest'       => true,
            'rest_base'          => 'wcag-tables',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ];

        register_post_type('wcag_tables', $args);
        
        wcag_wp_log('Custom Post Type wcag_tables registered', 'info');
    }
    
    /**
     * Add meta boxes for WCAG table configuration
     * 
     * @return void
     */
    public function add_meta_boxes(): void {
        add_meta_box(
            'wcag-wp-table-config',
            __('Configurazione WCAG Tabella', 'wcag-wp'),
            [$this, 'render_config_meta_box'],
            'wcag_tables',
            'normal',
            'high'
        );
        
        add_meta_box(
            'wcag-wp-table-columns',
            __('Definizione Colonne WCAG', 'wcag-wp'),
            [$this, 'render_columns_meta_box'],
            'wcag_tables',
            'normal',
            'high'
        );
        
        add_meta_box(
            'wcag-wp-table-data',
            __('Dati WCAG Tabella', 'wcag-wp'),
            [$this, 'render_data_meta_box'],
            'wcag_tables',
            'normal',
            'high'
        );
        
        add_meta_box(
            'wcag-wp-table-preview',
            __('Anteprima & Shortcode', 'wcag-wp'),
            [$this, 'render_preview_meta_box'],
            'wcag_tables',
            'side',
            'default'
        );
    }
    
    /**
     * Render configuration meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_config_meta_box(WP_Post $post): void {
        // Add nonce field for security
        wp_nonce_field('wcag_wp_table_meta_nonce', 'wcag_wp_table_meta_nonce');
        
        // Get existing configuration
        $config = get_post_meta($post->ID, self::META_CONFIG, true);
        $config = wp_parse_args($config, [
            'sortable' => true,
            'searchable' => true,
            'responsive' => true,
            'stack_mobile' => false,
            'export_csv' => true,
            'pagination' => false,
            'rows_per_page' => 10,
            'caption' => '',
            'summary' => ''
        ]);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/table-config-meta-box.php';
    }
    
    /**
     * Render columns definition meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_columns_meta_box(WP_Post $post): void {
        // Get existing columns
        $columns = get_post_meta($post->ID, self::META_COLUMNS, true);
        if (!is_array($columns)) {
            $columns = [];
        }
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/table-columns-meta-box.php';
    }
    
    /**
     * Render table data meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_data_meta_box(WP_Post $post): void {
        // Get existing data
        $data = get_post_meta($post->ID, self::META_DATA, true);
        if (!is_array($data)) {
            $data = [];
        }
        
        // Get columns for data entry
        $columns = get_post_meta($post->ID, self::META_COLUMNS, true);
        if (!is_array($columns)) {
            $columns = [];
        }
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/table-data-meta-box.php';
    }
    
    /**
     * Render preview and shortcode meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_preview_meta_box(WP_Post $post): void {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/table-preview-meta-box.php';
    }
    
    /**
     * Save table meta data
     * 
     * @param int $post_id Post ID
     * @return void
     */
    public function save_table_meta(int $post_id): void {
        // Verify nonce
        if (!isset($_POST['wcag_wp_table_meta_nonce']) || 
            !wp_verify_nonce($_POST['wcag_wp_table_meta_nonce'], 'wcag_wp_table_meta_nonce')) {
            return;
        }
        
        // Check if user has permission to edit post
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Skip autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Only save for our post type
        if (get_post_type($post_id) !== 'wcag_tables') {
            return;
        }
        
        // Save configuration
        if (isset($_POST['wcag_wp_table_config'])) {
            $config = $this->sanitize_table_config($_POST['wcag_wp_table_config']);
            update_post_meta($post_id, self::META_CONFIG, $config);
        }
        
        // Save columns
        if (isset($_POST['wcag_wp_table_columns'])) {
            $columns = $this->sanitize_table_columns($_POST['wcag_wp_table_columns']);
            update_post_meta($post_id, self::META_COLUMNS, $columns);
        }
        
        // Save data
        if (isset($_POST['wcag_wp_table_data'])) {
            $data = $this->sanitize_table_data($_POST['wcag_wp_table_data']);
            update_post_meta($post_id, self::META_DATA, $data);
        }
        
        wcag_wp_log("WCAG Table meta saved for post ID: {$post_id}", 'info');
    }
    
    /**
     * Sanitize table configuration
     * 
     * @param array $config Raw configuration data
     * @return array Sanitized configuration
     */
    private function sanitize_table_config(array $config): array {
        return [
            'sortable' => (bool)($config['sortable'] ?? false),
            'searchable' => (bool)($config['searchable'] ?? false),
            'responsive' => (bool)($config['responsive'] ?? true),
            'stack_mobile' => (bool)($config['stack_mobile'] ?? false),
            'export_csv' => (bool)($config['export_csv'] ?? true),
            'pagination' => (bool)($config['pagination'] ?? false),
            'rows_per_page' => absint($config['rows_per_page'] ?? 10),
            'caption' => sanitize_text_field($config['caption'] ?? ''),
            'summary' => sanitize_textarea_field($config['summary'] ?? '')
        ];
    }
    
    /**
     * Sanitize table columns
     * 
     * @param array $columns Raw columns data
     * @return array Sanitized columns
     */
    private function sanitize_table_columns(array $columns): array {
        $sanitized = [];
        
        foreach ($columns as $column) {
            if (!is_array($column)) continue;
            
            $sanitized[] = [
                'id' => sanitize_key($column['id'] ?? ''),
                'label' => sanitize_text_field($column['label'] ?? ''),
                'type' => sanitize_text_field($column['type'] ?? 'text'),
                'sortable' => (bool)($column['sortable'] ?? true),
                'required' => (bool)($column['required'] ?? false),
                'width' => sanitize_text_field($column['width'] ?? ''),
                'align' => sanitize_text_field($column['align'] ?? 'left'),
                'description' => sanitize_textarea_field($column['description'] ?? '')
            ];
        }
        
        return $sanitized;
    }
    
    /**
     * Sanitize table data
     * 
     * @param array $data Raw table data
     * @return array Sanitized data
     */
    private function sanitize_table_data(array $data): array {
        $sanitized = [];
        
        foreach ($data as $row_index => $row) {
            if (!is_array($row)) continue;
            
            $sanitized_row = [];
            foreach ($row as $column_id => $value) {
                $sanitized_row[sanitize_key($column_id)] = sanitize_text_field($value);
            }
            $sanitized[] = $sanitized_row;
        }
        
        return $sanitized;
    }
    
    /**
     * Enqueue admin assets for table management
     * 
     * @param string $hook Current admin page hook
     * @return void
     */
    public function enqueue_admin_assets(string $hook): void {
        // Only load on our post type pages
        if (!in_array($hook, ['post.php', 'post-new.php', 'edit.php'])) {
            return;
        }
        
        global $post_type;
        if ($post_type !== 'wcag_tables') {
            return;
        }
        
        wp_enqueue_style(
            'wcag-wp-tables-admin',
            WCAG_WP_ASSETS_URL . 'css/tables-admin.css',
            ['wcag-wp-admin'],
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-tables-admin',
            WCAG_WP_ASSETS_URL . 'js/tables-admin.js',
            ['wcag-wp-admin', 'jquery-ui-sortable'],
            WCAG_WP_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('wcag-wp-tables-admin', 'wcag_wp_tables', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wcag_wp_tables_nonce'),
            'strings' => [
                'confirm_delete_column' => __('Sei sicuro di voler eliminare questa colonna?', 'wcag-wp'),
                'confirm_delete_row' => __('Sei sicuro di voler eliminare questa riga?', 'wcag-wp'),
                'error_generic' => __('Si è verificato un errore. Riprova.', 'wcag-wp'),
                'column_added' => __('Colonna aggiunta con successo.', 'wcag-wp'),
                'row_added' => __('Riga aggiunta con successo.', 'wcag-wp'),
                'changes_saved' => __('Modifiche salvate.', 'wcag-wp')
            ]
        ]);
    }
    
    /**
     * AJAX handler: Export CSV
     * 
     * @return void
     */
    public function ajax_export_csv(): void {
        // Verify nonce
        if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', 'wcag_wp_export_csv')) {
            wp_die(__('Accesso negato', 'wcag-wp'));
        }
        
        $table_id = absint($_GET['table_id'] ?? 0);
        if (!$table_id) {
            wp_die(__('ID tabella non valido', 'wcag-wp'));
        }
        
        $csv_content = $this->export_csv($table_id);
        if (empty($csv_content)) {
            wp_die(__('Nessun dato da esportare', 'wcag-wp'));
        }
        
        $table_post = get_post($table_id);
        $filename = sanitize_file_name($table_post->post_title ?: 'wcag-table') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        echo $csv_content;
        exit;
    }
    
    /**
     * Shortcode handler for WCAG table display
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function shortcode_table(array $atts): string {
        $atts = shortcode_atts([
            'id' => 0,
            'class' => '',
            'responsive' => null,
            'sortable' => null,
            'searchable' => null
        ], $atts, 'wcag-table');
        
        $table_id = absint($atts['id']);
        if (!$table_id) {
            return '<div class="wcag-wp-error">' . __('ID WCAG Tabella non specificato', 'wcag-wp') . '</div>';
        }
        
        return $this->render_table($table_id, $atts);
    }
    
    /**
     * Render WCAG table HTML
     * 
     * @param int $table_id Table post ID
     * @param array $options Display options
     * @return string HTML output
     */
    public function render_table(int $table_id, array $options = []): string {
        // Get table post
        $table_post = get_post($table_id);
        if (!$table_post || $table_post->post_type !== 'wcag_tables') {
            return '<div class="wcag-wp-error">' . __('WCAG Tabella non trovata', 'wcag-wp') . '</div>';
        }
        
        // Get table data
        $config = get_post_meta($table_id, self::META_CONFIG, true);
        $columns = get_post_meta($table_id, self::META_COLUMNS, true);
        $data = get_post_meta($table_id, self::META_DATA, true);
        
        if (!is_array($columns) || empty($columns)) {
            return '<div class="wcag-wp-error">' . __('Nessuna colonna definita per questa WCAG Tabella', 'wcag-wp') . '</div>';
        }
        
        // Merge options with config
        $config = wp_parse_args($options, $config);
        
        // Start output buffering
        ob_start();
        
        // Include table template
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-table.php';
        
        return ob_get_clean();
    }
    
    /**
     * Register Gutenberg blocks for WCAG tables
     * 
     * @return void
     */
    public function register_gutenberg_blocks(): void {
        // Will be implemented in a future update
        // register_block_type('wcag-wp/wcag-table', [...]);
    }
    
    /**
     * Get WCAG table by ID with full data
     * 
     * @param int $table_id Table post ID
     * @return array|null Table data or null if not found
     */
    public function get_table(int $table_id): ?array {
        $table_post = get_post($table_id);
        if (!$table_post || $table_post->post_type !== 'wcag_tables') {
            return null;
        }
        
        return [
            'id' => $table_id,
            'title' => $table_post->post_title,
            'config' => get_post_meta($table_id, self::META_CONFIG, true),
            'columns' => get_post_meta($table_id, self::META_COLUMNS, true),
            'data' => get_post_meta($table_id, self::META_DATA, true),
            'post' => $table_post
        ];
    }
    
    /**
     * Export WCAG table data as CSV
     * 
     * @param int $table_id Table post ID
     * @return string CSV content
     */
    public function export_csv(int $table_id): string {
        $table = $this->get_table($table_id);
        if (!$table) {
            return '';
        }
        
        $output = fopen('php://temp', 'r+');
        
        // Add headers
        $headers = array_column($table['columns'], 'label');
        fputcsv($output, $headers);
        
        // Add data rows
        foreach ($table['data'] as $row) {
            $csv_row = [];
            foreach ($table['columns'] as $column) {
                $csv_row[] = $row[$column['id']] ?? '';
            }
            fputcsv($output, $csv_row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}