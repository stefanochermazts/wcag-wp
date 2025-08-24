<?php
declare(strict_types=1);

/**
 * WCAG Combobox Component
 * 
 * Implementa il pattern WCAG APG Combobox per input con popup associato
 * Supporta autocomplete, filtri e selezione da lista opzioni
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WCAG Combobox Class
 * 
 * Implementa combobox accessibile secondo pattern ARIA:
 * - Input con popup listbox associato
 * - Autocomplete e filtri in tempo reale
 * - Navigazione tastiera completa
 * - Screen reader support completo
 * - Configurazione opzioni tramite admin
 */
class WCAG_WP_Combobox {
    
    /**
     * Post type name
     */
    const POST_TYPE = 'wcag_combobox';
    
    /**
     * Combobox types
     */
    const TYPES = [
        'autocomplete' => [
            'label' => 'Autocomplete',
            'description' => 'Suggerimenti automatici durante la digitazione',
            'behavior' => 'list'
        ],
        'select' => [
            'label' => 'Select Avanzato',
            'description' => 'Sostituto accessibile per select con ricerca',
            'behavior' => 'listbox'
        ],
        'search' => [
            'label' => 'Campo Ricerca',
            'description' => 'Input ricerca con suggerimenti',
            'behavior' => 'list'
        ],
        'filter' => [
            'label' => 'Filtro Dinamico',
            'description' => 'Filtro opzioni con anteprima risultati',
            'behavior' => 'listbox'
        ]
    ];
    
    /**
     * Autocomplete behaviors
     */
    const AUTOCOMPLETE_BEHAVIORS = [
        'none' => 'Nessun completamento automatico',
        'list' => 'Lista suggerimenti (aria-autocomplete="list")',
        'inline' => 'Completamento inline (aria-autocomplete="inline")',
        'both' => 'Lista + inline (aria-autocomplete="both")'
    ];
    
    /**
     * Initialize component
     */
    public function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize WordPress hooks
     * 
     * @return void
     */
    private function init_hooks(): void {
        // Register post type
        add_action('init', [$this, 'register_post_type']);
        
        // Admin hooks
        if (is_admin()) {
            add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
            add_action('save_post', [$this, 'save_combobox_meta']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
            
            // Admin columns
            add_filter('manage_' . self::POST_TYPE . '_posts_columns', [$this, 'admin_columns']);
            add_action('manage_' . self::POST_TYPE . '_posts_custom_column', [$this, 'admin_column_content'], 10, 2);
        }
        
        // Frontend hooks
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_shortcode('wcag-combobox', [$this, 'shortcode_combobox']);
        
        // AJAX hooks for dynamic options
        add_action('wp_ajax_wcag_combobox_search', [$this, 'ajax_search_options']);
        add_action('wp_ajax_nopriv_wcag_combobox_search', [$this, 'ajax_search_options']);
        
        // REST API endpoints
        add_action('rest_api_init', [$this, 'register_rest_endpoints']);
    }
    
    /**
     * Register Custom Post Type
     * 
     * @return void
     */
    public function register_post_type(): void {
        $labels = [
            'name' => __('WCAG Combobox', 'wcag-wp'),
            'singular_name' => __('Combobox WCAG', 'wcag-wp'),
            'menu_name' => __('Combobox WCAG', 'wcag-wp'),
            'add_new' => __('Aggiungi Combobox', 'wcag-wp'),
            'add_new_item' => __('Aggiungi Nuovo Combobox', 'wcag-wp'),
            'edit_item' => __('Modifica Combobox', 'wcag-wp'),
            'new_item' => __('Nuovo Combobox', 'wcag-wp'),
            'view_item' => __('Visualizza Combobox', 'wcag-wp'),
            'search_items' => __('Cerca Combobox', 'wcag-wp'),
            'not_found' => __('Nessun combobox trovato', 'wcag-wp'),
            'not_found_in_trash' => __('Nessun combobox nel cestino', 'wcag-wp'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'wcag-wp-main',
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability_type' => 'post',
            'capabilities' => [
                'edit_post' => 'manage_options',
                'read_post' => 'manage_options',
                'delete_post' => 'manage_options',
                'edit_posts' => 'manage_options',
                'edit_others_posts' => 'manage_options',
                'delete_posts' => 'manage_options',
                'publish_posts' => 'manage_options',
                'read_private_posts' => 'manage_options'
            ],
            'supports' => ['title'],
            'menu_icon' => 'dashicons-search',
            'menu_position' => 31,
            'rewrite' => false,
            'query_var' => false,
            'show_in_rest' => true,
            'rest_base' => 'wcag-combobox'
        ];
        
        register_post_type(self::POST_TYPE, $args);
    }
    
    /**
     * Add meta boxes
     * 
     * @return void
     */
    public function add_meta_boxes(): void {
        // Configuration meta box
        add_meta_box(
            'wcag_combobox_config',
            __('Configurazione Combobox WCAG', 'wcag-wp'),
            [$this, 'render_config_meta_box'],
            self::POST_TYPE,
            'normal',
            'high'
        );
        
        // Options meta box
        add_meta_box(
            'wcag_combobox_options',
            __('Opzioni & Dati', 'wcag-wp'),
            [$this, 'render_options_meta_box'],
            self::POST_TYPE,
            'normal',
            'high'
        );
        
        // Preview meta box
        add_meta_box(
            'wcag_combobox_preview',
            __('Anteprima & Shortcode', 'wcag-wp'),
            [$this, 'render_preview_meta_box'],
            self::POST_TYPE,
            'side',
            'default'
        );
        
        // Accessibility info meta box
        add_meta_box(
            'wcag_combobox_accessibility',
            __('Informazioni Accessibilità', 'wcag-wp'),
            [$this, 'render_accessibility_meta_box'],
            self::POST_TYPE,
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
        wp_nonce_field('wcag_combobox_meta', 'wcag_combobox_nonce');
        
        $config = get_post_meta($post->ID, '_wcag_combobox_config', true);
        $config = wp_parse_args($config, [
            'type' => 'autocomplete',
            'autocomplete_behavior' => 'list',
            'placeholder' => '',
            'required' => false,
            'multiple' => false,
            'max_results' => 10,
            'min_chars' => 1,
            'debounce_delay' => 300,
            'case_sensitive' => false,
            'custom_class' => '',
            'data_source' => 'static',
            'external_url' => '',
            'wp_query_args' => ''
        ]);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/combobox-config-meta-box.php';
    }
    
    /**
     * Render options meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_options_meta_box(WP_Post $post): void {
        $options = get_post_meta($post->ID, '_wcag_combobox_options', true);
        if (!is_array($options)) {
            $options = [];
        }
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/combobox-options-meta-box.php';
    }
    
    /**
     * Render preview meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_preview_meta_box(WP_Post $post): void {
        $shortcode = '[wcag-combobox id="' . $post->ID . '"]';
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/combobox-preview-meta-box.php';
    }
    
    /**
     * Render accessibility meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_accessibility_meta_box(WP_Post $post): void {
        $config = get_post_meta($post->ID, '_wcag_combobox_config', true);
        $type = $config['type'] ?? 'autocomplete';
        $type_info = self::TYPES[$type] ?? self::TYPES['autocomplete'];
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/combobox-accessibility-meta-box.php';
    }
    
    /**
     * Save combobox meta data
     * 
     * @param int $post_id Post ID
     * @return void
     */
    public function save_combobox_meta(int $post_id): void {
        // Verify nonce
        if (!isset($_POST['wcag_combobox_nonce']) || 
            !wp_verify_nonce($_POST['wcag_combobox_nonce'], 'wcag_combobox_meta')) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Skip autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Save configuration
        if (isset($_POST['wcag_combobox_config'])) {
            $config = $_POST['wcag_combobox_config'];
            
            $sanitized_config = [
                'type' => sanitize_text_field($config['type'] ?? 'autocomplete'),
                'autocomplete_behavior' => sanitize_text_field($config['autocomplete_behavior'] ?? 'list'),
                'placeholder' => sanitize_text_field($config['placeholder'] ?? ''),
                'required' => (bool)($config['required'] ?? false),
                'multiple' => (bool)($config['multiple'] ?? false),
                'max_results' => absint($config['max_results'] ?? 10),
                'min_chars' => absint($config['min_chars'] ?? 1),
                'debounce_delay' => absint($config['debounce_delay'] ?? 300),
                'case_sensitive' => (bool)($config['case_sensitive'] ?? false),
                'custom_class' => sanitize_html_class($config['custom_class'] ?? ''),
                'data_source' => sanitize_text_field($config['data_source'] ?? 'static'),
                'external_url' => esc_url_raw($config['external_url'] ?? ''),
                'wp_query_args' => sanitize_textarea_field($config['wp_query_args'] ?? '')
            ];
            
            // Validate type
            if (!array_key_exists($sanitized_config['type'], self::TYPES)) {
                $sanitized_config['type'] = 'autocomplete';
            }
            
            // Validate autocomplete behavior
            if (!array_key_exists($sanitized_config['autocomplete_behavior'], self::AUTOCOMPLETE_BEHAVIORS)) {
                $sanitized_config['autocomplete_behavior'] = 'list';
            }
            
            // Validate data source
            if (!in_array($sanitized_config['data_source'], ['static', 'posts', 'users', 'terms', 'external'], true)) {
                $sanitized_config['data_source'] = 'static';
            }
            
            // Validate limits
            if ($sanitized_config['max_results'] < 1) {
                $sanitized_config['max_results'] = 1;
            } elseif ($sanitized_config['max_results'] > 100) {
                $sanitized_config['max_results'] = 100;
            }
            
            if ($sanitized_config['min_chars'] < 0) {
                $sanitized_config['min_chars'] = 0;
            } elseif ($sanitized_config['min_chars'] > 10) {
                $sanitized_config['min_chars'] = 10;
            }
            
            if ($sanitized_config['debounce_delay'] < 0) {
                $sanitized_config['debounce_delay'] = 0;
            } elseif ($sanitized_config['debounce_delay'] > 2000) {
                $sanitized_config['debounce_delay'] = 2000;
            }
            
            update_post_meta($post_id, '_wcag_combobox_config', $sanitized_config);
        }
        
        // Save options
        if (isset($_POST['wcag_combobox_options'])) {
            $options = $_POST['wcag_combobox_options'];
            $sanitized_options = [];
            
            if (is_array($options)) {
                foreach ($options as $option) {
                    if (!empty($option['value']) || !empty($option['label'])) {
                        $sanitized_options[] = [
                            'value' => sanitize_text_field($option['value'] ?? ''),
                            'label' => sanitize_text_field($option['label'] ?? ''),
                            'description' => sanitize_text_field($option['description'] ?? ''),
                            'group' => sanitize_text_field($option['group'] ?? ''),
                            'disabled' => (bool)($option['disabled'] ?? false)
                        ];
                    }
                }
            }
            
            update_post_meta($post_id, '_wcag_combobox_options', $sanitized_options);
        }
    }
    
    /**
     * Admin columns for combobox list
     * 
     * @param array $columns Existing columns
     * @return array Modified columns
     */
    public function admin_columns(array $columns): array {
        $new_columns = [];
        
        foreach ($columns as $key => $title) {
            $new_columns[$key] = $title;
            
            if ($key === 'title') {
                $new_columns['combobox_type'] = __('Tipo', 'wcag-wp');
                $new_columns['combobox_options'] = __('Opzioni', 'wcag-wp');
                $new_columns['shortcode'] = __('Shortcode', 'wcag-wp');
            }
        }
        
        return $new_columns;
    }
    
    /**
     * Admin column content
     * 
     * @param string $column Column name
     * @param int $post_id Post ID
     * @return void
     */
    public function admin_column_content(string $column, int $post_id): void {
        switch ($column) {
            case 'combobox_type':
                $config = get_post_meta($post_id, '_wcag_combobox_config', true);
                $type = $config['type'] ?? 'autocomplete';
                $type_info = self::TYPES[$type] ?? self::TYPES['autocomplete'];
                
                echo '<span class="wcag-combobox-type wcag-combobox-type--' . esc_attr($type) . '">';
                echo esc_html($type_info['label']);
                echo '</span>';
                break;
                
            case 'combobox_options':
                $options = get_post_meta($post_id, '_wcag_combobox_options', true);
                $count = is_array($options) ? count($options) : 0;
                
                $config = get_post_meta($post_id, '_wcag_combobox_config', true);
                $data_source = $config['data_source'] ?? 'static';
                
                if ($data_source === 'static') {
                    echo esc_html(sprintf(__('%d opzioni statiche', 'wcag-wp'), $count));
                } else {
                    echo esc_html(sprintf(__('Sorgente: %s', 'wcag-wp'), ucfirst($data_source)));
                }
                break;
                
            case 'shortcode':
                echo '<code>[wcag-combobox id="' . esc_attr($post_id) . '"]</code>';
                break;
        }
    }
    
    /**
     * Enqueue admin assets
     * 
     * @param string $hook Current admin page hook
     * @return void
     */
    public function enqueue_admin_assets(string $hook): void {
        $screen = get_current_screen();
        
        if (!$screen || $screen->post_type !== self::POST_TYPE) {
            return;
        }
        
        wp_enqueue_style(
            'wcag-wp-combobox-admin',
            WCAG_WP_ASSETS_URL . 'css/combobox-admin.css',
            [],
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-combobox-admin',
            WCAG_WP_ASSETS_URL . 'js/combobox-admin.js',
            ['jquery', 'jquery-ui-sortable'],
            WCAG_WP_VERSION,
            true
        );
        
        wp_localize_script('wcag-wp-combobox-admin', 'wcagComboboxAdmin', [
            'types' => self::TYPES,
            'autocomplete_behaviors' => self::AUTOCOMPLETE_BEHAVIORS,
            'nonce' => wp_create_nonce('wcag_combobox_admin'),
            'strings' => [
                'add_option' => __('Aggiungi Opzione', 'wcag-wp'),
                'remove_option' => __('Rimuovi Opzione', 'wcag-wp'),
                'confirm_remove' => __('Sei sicuro di voler rimuovere questa opzione?', 'wcag-wp'),
                'copy_shortcode' => __('Copia Shortcode', 'wcag-wp'),
                'shortcode_copied' => __('Shortcode copiato!', 'wcag-wp')
            ]
        ]);
    }
    
    /**
     * Enqueue frontend assets
     * 
     * @return void
     */
    public function enqueue_frontend_assets(): void {
        // Only enqueue if shortcode is used
        global $post;
        
        if ($post && has_shortcode($post->post_content, 'wcag-combobox')) {
            wp_enqueue_style(
                'wcag-wp-combobox',
                WCAG_WP_ASSETS_URL . 'css/combobox-frontend.css',
                [],
                WCAG_WP_VERSION
            );
            
            wp_enqueue_script(
                'wcag-wp-combobox',
                WCAG_WP_ASSETS_URL . 'js/combobox-frontend.js',
                [],
                WCAG_WP_VERSION,
                true
            );
            
            wp_localize_script('wcag-wp-combobox', 'wcagCombobox', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wcag_combobox_frontend'),
                'strings' => [
                    'no_results' => __('Nessun risultato trovato', 'wcag-wp'),
                    'loading' => __('Caricamento...', 'wcag-wp'),
                    'selected' => __('selezionato', 'wcag-wp'),
                    'of' => __('di', 'wcag-wp'),
                    'results' => __('risultati', 'wcag-wp')
                ]
            ]);
        }
    }
    
    /**
     * Combobox shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function shortcode_combobox(array $atts): string {
        $atts = shortcode_atts([
            'id' => 0,
            'name' => '',
            'value' => '',
            'class' => '',
            'required' => '',
            'multiple' => ''
        ], $atts, 'wcag-combobox');
        
        $combobox_id = absint($atts['id']);
        
        if (!$combobox_id) {
            return '<div class="wcag-wp-error">' . 
                   __('ID combobox richiesto per il shortcode [wcag-combobox]', 'wcag-wp') . 
                   '</div>';
        }
        
        $combobox = get_post($combobox_id);
        
        if (!$combobox || $combobox->post_type !== self::POST_TYPE || $combobox->post_status !== 'publish') {
            return '<div class="wcag-wp-error">' . 
                   __('Combobox non trovato o non pubblicato', 'wcag-wp') . 
                   '</div>';
        }
        
        // Get configuration and options
        $config = get_post_meta($combobox_id, '_wcag_combobox_config', true);
        $options = get_post_meta($combobox_id, '_wcag_combobox_options', true);
        
        // Override config with shortcode attributes
        if (!empty($atts['required'])) {
            $config['required'] = filter_var($atts['required'], FILTER_VALIDATE_BOOLEAN);
        }
        if (!empty($atts['multiple'])) {
            $config['multiple'] = filter_var($atts['multiple'], FILTER_VALIDATE_BOOLEAN);
        }
        if (!empty($atts['class'])) {
            $config['custom_class'] = sanitize_html_class($atts['class']);
        }
        
        // Set form field name
        $field_name = !empty($atts['name']) ? sanitize_text_field($atts['name']) : 'wcag_combobox_' . $combobox_id;
        
        // Render combobox
        ob_start();
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-combobox.php';
        return ob_get_clean();
    }
    
    /**
     * AJAX search options handler
     * 
     * @return void
     */
    public function ajax_search_options(): void {
        check_ajax_referer('wcag_combobox_frontend', 'nonce');
        
        $combobox_id = absint($_POST['combobox_id'] ?? 0);
        $query = sanitize_text_field($_POST['query'] ?? '');
        
        if (!$combobox_id) {
            wp_send_json_error(['message' => __('ID combobox richiesto', 'wcag-wp')]);
        }
        
        $config = get_post_meta($combobox_id, '_wcag_combobox_config', true);
        $results = $this->search_options($combobox_id, $query, $config);
        
        wp_send_json_success(['options' => $results]);
    }
    
    /**
     * Search options based on query
     * 
     * @param int $combobox_id Combobox ID
     * @param string $query Search query
     * @param array $config Combobox configuration
     * @return array Filtered options
     */
    private function search_options(int $combobox_id, string $query, array $config): array {
        $data_source = $config['data_source'] ?? 'static';
        $max_results = $config['max_results'] ?? 10;
        $case_sensitive = $config['case_sensitive'] ?? false;
        
        $results = [];
        
        switch ($data_source) {
            case 'static':
                $options = get_post_meta($combobox_id, '_wcag_combobox_options', true);
                if (is_array($options)) {
                    foreach ($options as $option) {
                        if ($this->option_matches_query($option, $query, $case_sensitive)) {
                            $results[] = $option;
                            if (count($results) >= $max_results) break;
                        }
                    }
                }
                break;
                
            case 'posts':
                $results = $this->search_posts($query, $config);
                break;
                
            case 'users':
                $results = $this->search_users($query, $config);
                break;
                
            case 'terms':
                $results = $this->search_terms($query, $config);
                break;
                
            case 'external':
                $results = $this->search_external($query, $config);
                break;
        }
        
        return array_slice($results, 0, $max_results);
    }
    
    /**
     * Check if option matches query
     * 
     * @param array $option Option data
     * @param string $query Search query
     * @param bool $case_sensitive Case sensitive search
     * @return bool Match result
     */
    private function option_matches_query(array $option, string $query, bool $case_sensitive): bool {
        if (empty($query)) {
            return true;
        }
        
        $search_fields = [$option['label'] ?? '', $option['value'] ?? '', $option['description'] ?? ''];
        $search_text = implode(' ', $search_fields);
        
        if ($case_sensitive) {
            return strpos($search_text, $query) !== false;
        } else {
            return stripos($search_text, $query) !== false;
        }
    }
    
    /**
     * Search WordPress posts
     * 
     * @param string $query Search query
     * @param array $config Configuration
     * @return array Results
     */
    private function search_posts(string $query, array $config): array {
        $args = [
            's' => $query,
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $config['max_results'] ?? 10,
            'fields' => 'ids'
        ];
        
        // Parse custom WP_Query args if provided
        if (!empty($config['wp_query_args'])) {
            $custom_args = [];
            parse_str($config['wp_query_args'], $custom_args);
            $args = array_merge($args, $custom_args);
        }
        
        $posts = get_posts($args);
        $results = [];
        
        foreach ($posts as $post_id) {
            $post = get_post($post_id);
            if ($post) {
                $results[] = [
                    'value' => (string)$post_id,
                    'label' => $post->post_title,
                    'description' => wp_trim_words($post->post_excerpt ?: $post->post_content, 15),
                    'group' => get_post_type_object($post->post_type)->labels->singular_name ?? ''
                ];
            }
        }
        
        return $results;
    }
    
    /**
     * Search WordPress users
     * 
     * @param string $query Search query
     * @param array $config Configuration
     * @return array Results
     */
    private function search_users(string $query, array $config): array {
        $args = [
            'search' => '*' . $query . '*',
            'search_columns' => ['user_login', 'user_email', 'display_name'],
            'number' => $config['max_results'] ?? 10
        ];
        
        $users = get_users($args);
        $results = [];
        
        foreach ($users as $user) {
            $results[] = [
                'value' => (string)$user->ID,
                'label' => $user->display_name,
                'description' => $user->user_email,
                'group' => __('Utenti', 'wcag-wp')
            ];
        }
        
        return $results;
    }
    
    /**
     * Search WordPress terms
     * 
     * @param string $query Search query
     * @param array $config Configuration
     * @return array Results
     */
    private function search_terms(string $query, array $config): array {
        $args = [
            'search' => $query,
            'number' => $config['max_results'] ?? 10,
            'hide_empty' => false
        ];
        
        $terms = get_terms($args);
        $results = [];
        
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $results[] = [
                    'value' => (string)$term->term_id,
                    'label' => $term->name,
                    'description' => $term->description,
                    'group' => get_taxonomy($term->taxonomy)->labels->singular_name ?? ''
                ];
            }
        }
        
        return $results;
    }
    
    /**
     * Search external API
     * 
     * @param string $query Search query
     * @param array $config Configuration
     * @return array Results
     */
    private function search_external(string $query, array $config): array {
        $external_url = $config['external_url'] ?? '';
        if (empty($external_url)) {
            return [];
        }
        
        // Build API URL with query parameter
        $api_url = add_query_arg([
            'q' => urlencode($query),
            'limit' => $config['max_results'] ?? 10
        ], $external_url);
        
        // Make API request
        $response = wp_remote_get($api_url, [
            'timeout' => 5,
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);
        
        if (is_wp_error($response)) {
            return [];
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (!is_array($data)) {
            return [];
        }
        
        // Normalize external data to our format
        $results = [];
        foreach ($data as $item) {
            if (is_array($item)) {
                $results[] = [
                    'value' => $item['value'] ?? $item['id'] ?? '',
                    'label' => $item['label'] ?? $item['name'] ?? $item['title'] ?? '',
                    'description' => $item['description'] ?? '',
                    'group' => $item['group'] ?? $item['category'] ?? ''
                ];
            }
        }
        
        return $results;
    }
    
    /**
     * Register REST API endpoints
     * 
     * @return void
     */
    public function register_rest_endpoints(): void {
        register_rest_route('wcag-wp/v1', '/combobox/(?P<id>\d+)/search', [
            'methods' => 'GET',
            'callback' => [$this, 'rest_search_options'],
            'permission_callback' => '__return_true',
            'args' => [
                'id' => [
                    'required' => true,
                    'validate_callback' => function($param) {
                        return is_numeric($param);
                    }
                ],
                'q' => [
                    'required' => false,
                    'sanitize_callback' => 'sanitize_text_field'
                ]
            ]
        ]);
    }
    
    /**
     * REST API search options endpoint
     * 
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response Response object
     */
    public function rest_search_options(WP_REST_Request $request): WP_REST_Response {
        $combobox_id = (int) $request['id'];
        $query = $request['q'] ?? '';
        
        $combobox = get_post($combobox_id);
        if (!$combobox || $combobox->post_type !== self::POST_TYPE || $combobox->post_status !== 'publish') {
            return new WP_REST_Response(['error' => 'Combobox not found'], 404);
        }
        
        $config = get_post_meta($combobox_id, '_wcag_combobox_config', true);
        $results = $this->search_options($combobox_id, $query, $config);
        
        return new WP_REST_Response(['options' => $results], 200);
    }
    
    /**
     * Get combobox types
     * 
     * @return array Combobox types
     */
    public static function get_types(): array {
        return self::TYPES;
    }
    
    /**
     * Get autocomplete behaviors
     * 
     * @return array Autocomplete behaviors
     */
    public static function get_autocomplete_behaviors(): array {
        return self::AUTOCOMPLETE_BEHAVIORS;
    }
}

// Initialize component
new WCAG_WP_Combobox();

