<?php
declare(strict_types=1);

/**
 * WCAG Listbox Component
 * 
 * Implementa il pattern WCAG APG Listbox per selezioni da liste
 * Supporta selezione singola/multipla, gruppi e navigazione avanzata
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WCAG Listbox Class
 * 
 * Implementa listbox accessibile secondo pattern ARIA:
 * - Lista opzioni con selezione singola/multipla
 * - Navigazione tastiera completa con Ctrl/Shift
 * - Supporto gruppi e separatori
 * - Screen reader support completo
 * - Configurazione opzioni tramite admin
 */
class WCAG_WP_Listbox {
    
    /**
     * Post type name
     */
    const POST_TYPE = 'wcag_listbox';
    
    /**
     * Listbox types
     */
    const TYPES = [
        'single' => [
            'label' => 'Selezione Singola',
            'description' => 'Permette di selezionare una sola opzione alla volta',
            'multiselectable' => false
        ],
        'multiple' => [
            'label' => 'Selezione Multipla',
            'description' => 'Permette di selezionare più opzioni contemporaneamente',
            'multiselectable' => true
        ],
        'grouped' => [
            'label' => 'Lista Raggruppata',
            'description' => 'Opzioni organizzate in gruppi con intestazioni',
            'multiselectable' => false
        ],
        'multi_grouped' => [
            'label' => 'Multipla Raggruppata',
            'description' => 'Selezione multipla con opzioni raggruppate',
            'multiselectable' => true
        ]
    ];
    
    /**
     * Selection modes
     */
    const SELECTION_MODES = [
        'click' => 'Solo click/tap',
        'keyboard' => 'Solo tastiera',
        'both' => 'Click e tastiera',
        'extended' => 'Selezione estesa (Ctrl+Click, Shift+Click)'
    ];
    
    /**
     * Orientation options
     */
    const ORIENTATIONS = [
        'vertical' => 'Verticale (predefinito)',
        'horizontal' => 'Orizzontale',
        'grid' => 'Griglia (2D navigation)'
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
            add_action('save_post', [$this, 'save_listbox_meta']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
            
            // Admin columns
            add_filter('manage_' . self::POST_TYPE . '_posts_columns', [$this, 'admin_columns']);
            add_action('manage_' . self::POST_TYPE . '_posts_custom_column', [$this, 'admin_column_content'], 10, 2);
        }
        
        // Frontend hooks
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_shortcode('wcag-listbox', [$this, 'shortcode_listbox']);
        
        // AJAX hooks for dynamic options
        add_action('wp_ajax_wcag_listbox_get_options', [$this, 'ajax_get_options']);
        add_action('wp_ajax_nopriv_wcag_listbox_get_options', [$this, 'ajax_get_options']);
        
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
            'name' => __('WCAG Listbox', 'wcag-wp'),
            'singular_name' => __('Listbox WCAG', 'wcag-wp'),
            'menu_name' => __('Listbox WCAG', 'wcag-wp'),
            'add_new' => __('Aggiungi Listbox', 'wcag-wp'),
            'add_new_item' => __('Aggiungi Nuovo Listbox', 'wcag-wp'),
            'edit_item' => __('Modifica Listbox', 'wcag-wp'),
            'new_item' => __('Nuovo Listbox', 'wcag-wp'),
            'view_item' => __('Visualizza Listbox', 'wcag-wp'),
            'search_items' => __('Cerca Listbox', 'wcag-wp'),
            'not_found' => __('Nessun listbox trovato', 'wcag-wp'),
            'not_found_in_trash' => __('Nessun listbox nel cestino', 'wcag-wp'),
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
            'menu_icon' => 'dashicons-list-view',
            'menu_position' => 32,
            'rewrite' => false,
            'query_var' => false,
            'show_in_rest' => true,
            'rest_base' => 'wcag-listbox'
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
            'wcag_listbox_config',
            __('Configurazione Listbox WCAG', 'wcag-wp'),
            [$this, 'render_config_meta_box'],
            self::POST_TYPE,
            'normal',
            'high'
        );
        
        // Options meta box
        add_meta_box(
            'wcag_listbox_options',
            __('Opzioni & Gruppi', 'wcag-wp'),
            [$this, 'render_options_meta_box'],
            self::POST_TYPE,
            'normal',
            'high'
        );
        
        // Preview meta box
        add_meta_box(
            'wcag_listbox_preview',
            __('Anteprima & Shortcode', 'wcag-wp'),
            [$this, 'render_preview_meta_box'],
            self::POST_TYPE,
            'side',
            'default'
        );
        
        // Accessibility info meta box
        add_meta_box(
            'wcag_listbox_accessibility',
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
        wp_nonce_field('wcag_listbox_meta', 'wcag_listbox_nonce');
        
        $config = get_post_meta($post->ID, '_wcag_listbox_config', true);
        $config = wp_parse_args($config, [
            'type' => 'single',
            'selection_mode' => 'both',
            'orientation' => 'vertical',
            'size' => 5,
            'required' => false,
            'allow_deselect' => true,
            'wrap_navigation' => true,
            'auto_select_first' => false,
            'show_selection_count' => false,
            'custom_class' => '',
            'data_source' => 'static',
            'external_url' => '',
            'wp_query_args' => '',
            'enable_search' => false,
            'search_placeholder' => ''
        ]);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/listbox-config-meta-box.php';
    }
    
    /**
     * Render options meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_options_meta_box(WP_Post $post): void {
        $options = get_post_meta($post->ID, '_wcag_listbox_options', true);
        if (!is_array($options)) {
            $options = [];
        }
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/listbox-options-meta-box.php';
    }
    
    /**
     * Render preview meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_preview_meta_box(WP_Post $post): void {
        $shortcode = '[wcag-listbox id="' . $post->ID . '"]';
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/listbox-preview-meta-box.php';
    }
    
    /**
     * Render accessibility meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_accessibility_meta_box(WP_Post $post): void {
        $config = get_post_meta($post->ID, '_wcag_listbox_config', true);
        $type = $config['type'] ?? 'single';
        $type_info = self::TYPES[$type] ?? self::TYPES['single'];
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/listbox-accessibility-meta-box.php';
    }
    
    /**
     * Save listbox meta data
     * 
     * @param int $post_id Post ID
     * @return void
     */
    public function save_listbox_meta(int $post_id): void {
        // Verify nonce
        if (!isset($_POST['wcag_listbox_nonce']) || 
            !wp_verify_nonce($_POST['wcag_listbox_nonce'], 'wcag_listbox_meta')) {
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
        if (isset($_POST['wcag_listbox_config'])) {
            $config = $_POST['wcag_listbox_config'];
            
            $sanitized_config = [
                'type' => sanitize_text_field($config['type'] ?? 'single'),
                'selection_mode' => sanitize_text_field($config['selection_mode'] ?? 'both'),
                'orientation' => sanitize_text_field($config['orientation'] ?? 'vertical'),
                'size' => absint($config['size'] ?? 5),
                'required' => (bool)($config['required'] ?? false),
                'allow_deselect' => (bool)($config['allow_deselect'] ?? true),
                'wrap_navigation' => (bool)($config['wrap_navigation'] ?? true),
                'auto_select_first' => (bool)($config['auto_select_first'] ?? false),
                'show_selection_count' => (bool)($config['show_selection_count'] ?? false),
                'custom_class' => sanitize_html_class($config['custom_class'] ?? ''),
                'data_source' => sanitize_text_field($config['data_source'] ?? 'static'),
                'external_url' => esc_url_raw($config['external_url'] ?? ''),
                'wp_query_args' => sanitize_textarea_field($config['wp_query_args'] ?? ''),
                'enable_search' => (bool)($config['enable_search'] ?? false),
                'search_placeholder' => sanitize_text_field($config['search_placeholder'] ?? '')
            ];
            
            // Validate type
            if (!array_key_exists($sanitized_config['type'], self::TYPES)) {
                $sanitized_config['type'] = 'single';
            }
            
            // Validate selection mode
            if (!array_key_exists($sanitized_config['selection_mode'], self::SELECTION_MODES)) {
                $sanitized_config['selection_mode'] = 'both';
            }
            
            // Validate orientation
            if (!array_key_exists($sanitized_config['orientation'], self::ORIENTATIONS)) {
                $sanitized_config['orientation'] = 'vertical';
            }
            
            // Validate data source
            if (!in_array($sanitized_config['data_source'], ['static', 'posts', 'users', 'terms', 'external'], true)) {
                $sanitized_config['data_source'] = 'static';
            }
            
            // Validate size
            if ($sanitized_config['size'] < 1) {
                $sanitized_config['size'] = 1;
            } elseif ($sanitized_config['size'] > 20) {
                $sanitized_config['size'] = 20;
            }
            
            update_post_meta($post_id, '_wcag_listbox_config', $sanitized_config);
        }
        
        // Save options
        if (isset($_POST['wcag_listbox_options'])) {
            $options = $_POST['wcag_listbox_options'];
            $sanitized_options = [];
            
            if (is_array($options)) {
                foreach ($options as $option) {
                    if (!empty($option['value']) || !empty($option['label'])) {
                        $sanitized_options[] = [
                            'value' => sanitize_text_field($option['value'] ?? ''),
                            'label' => sanitize_text_field($option['label'] ?? ''),
                            'description' => sanitize_text_field($option['description'] ?? ''),
                            'group' => sanitize_text_field($option['group'] ?? ''),
                            'disabled' => (bool)($option['disabled'] ?? false),
                            'selected' => (bool)($option['selected'] ?? false),
                            'separator_before' => (bool)($option['separator_before'] ?? false),
                            'separator_after' => (bool)($option['separator_after'] ?? false)
                        ];
                    }
                }
            }
            
            update_post_meta($post_id, '_wcag_listbox_options', $sanitized_options);
        }
    }
    
    /**
     * Admin columns for listbox list
     * 
     * @param array $columns Existing columns
     * @return array Modified columns
     */
    public function admin_columns(array $columns): array {
        $new_columns = [];
        
        foreach ($columns as $key => $title) {
            $new_columns[$key] = $title;
            
            if ($key === 'title') {
                $new_columns['listbox_type'] = __('Tipo', 'wcag-wp');
                $new_columns['listbox_options'] = __('Opzioni', 'wcag-wp');
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
            case 'listbox_type':
                $config = get_post_meta($post_id, '_wcag_listbox_config', true);
                $type = $config['type'] ?? 'single';
                $type_info = self::TYPES[$type] ?? self::TYPES['single'];
                
                echo '<span class="wcag-listbox-type wcag-listbox-type--' . esc_attr($type) . '">';
                echo esc_html($type_info['label']);
                echo '</span>';
                break;
                
            case 'listbox_options':
                $options = get_post_meta($post_id, '_wcag_listbox_options', true);
                $count = is_array($options) ? count($options) : 0;
                
                $config = get_post_meta($post_id, '_wcag_listbox_config', true);
                $data_source = $config['data_source'] ?? 'static';
                
                if ($data_source === 'static') {
                    echo esc_html(sprintf(__('%d opzioni statiche', 'wcag-wp'), $count));
                } else {
                    echo esc_html(sprintf(__('Sorgente: %s', 'wcag-wp'), ucfirst($data_source)));
                }
                break;
                
            case 'shortcode':
                echo '<code>[wcag-listbox id="' . esc_attr($post_id) . '"]</code>';
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
            'wcag-wp-listbox-admin',
            WCAG_WP_ASSETS_URL . 'css/listbox-admin.css',
            [],
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-listbox-admin',
            WCAG_WP_ASSETS_URL . 'js/listbox-admin.js',
            ['jquery', 'jquery-ui-sortable'],
            WCAG_WP_VERSION,
            true
        );
        
        wp_localize_script('wcag-wp-listbox-admin', 'wcagListboxAdmin', [
            'types' => self::TYPES,
            'selection_modes' => self::SELECTION_MODES,
            'orientations' => self::ORIENTATIONS,
            'nonce' => wp_create_nonce('wcag_listbox_admin'),
            'strings' => [
                'add_option' => __('Aggiungi Opzione', 'wcag-wp'),
                'add_group' => __('Aggiungi Gruppo', 'wcag-wp'),
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
        
        if ($post && has_shortcode($post->post_content, 'wcag-listbox')) {
            wp_enqueue_style(
                'wcag-wp-listbox',
                WCAG_WP_ASSETS_URL . 'css/listbox-frontend.css',
                [],
                WCAG_WP_VERSION
            );
            
            wp_enqueue_script(
                'wcag-wp-listbox',
                WCAG_WP_ASSETS_URL . 'js/listbox-frontend.js',
                [],
                WCAG_WP_VERSION,
                true
            );
            
            wp_localize_script('wcag-wp-listbox', 'wcagListbox', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wcag_listbox_frontend'),
                'strings' => [
                    'no_options' => __('Nessuna opzione disponibile', 'wcag-wp'),
                    'loading' => __('Caricamento...', 'wcag-wp'),
                    'selected' => __('selezionato', 'wcag-wp'),
                    'deselected' => __('deselezionato', 'wcag-wp'),
                    'of' => __('di', 'wcag-wp'),
                    'options' => __('opzioni', 'wcag-wp'),
                    'selection_count' => __('Selezionati: %d', 'wcag-wp')
                ]
            ]);
        }
    }
    
    /**
     * Listbox shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function shortcode_listbox(array $atts): string {
        $atts = shortcode_atts([
            'id' => 0,
            'name' => '',
            'value' => '',
            'class' => '',
            'required' => '',
            'size' => ''
        ], $atts, 'wcag-listbox');
        
        $listbox_id = absint($atts['id']);
        
        if (!$listbox_id) {
            return '<div class="wcag-wp-error">' . 
                   __('ID listbox richiesto per il shortcode [wcag-listbox]', 'wcag-wp') . 
                   '</div>';
        }
        
        $listbox = get_post($listbox_id);
        
        if (!$listbox || $listbox->post_type !== self::POST_TYPE || $listbox->post_status !== 'publish') {
            return '<div class="wcag-wp-error">' . 
                   __('Listbox non trovato o non pubblicato', 'wcag-wp') . 
                   '</div>';
        }
        
        // Get configuration and options
        $config = get_post_meta($listbox_id, '_wcag_listbox_config', true);
        $options = get_post_meta($listbox_id, '_wcag_listbox_options', true);
        
        // Override config with shortcode attributes
        if (!empty($atts['required'])) {
            $config['required'] = filter_var($atts['required'], FILTER_VALIDATE_BOOLEAN);
        }
        if (!empty($atts['size'])) {
            $config['size'] = absint($atts['size']);
        }
        if (!empty($atts['class'])) {
            $config['custom_class'] = sanitize_html_class($atts['class']);
        }
        
        // Set form field name
        $field_name = !empty($atts['name']) ? sanitize_text_field($atts['name']) : 'wcag_listbox_' . $listbox_id;
        
        // Render listbox
        ob_start();
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-listbox.php';
        return ob_get_clean();
    }
    
    /**
     * AJAX get options handler
     * 
     * @return void
     */
    public function ajax_get_options(): void {
        check_ajax_referer('wcag_listbox_frontend', 'nonce');
        
        $listbox_id = absint($_POST['listbox_id'] ?? 0);
        
        if (!$listbox_id) {
            wp_send_json_error(['message' => __('ID listbox richiesto', 'wcag-wp')]);
        }
        
        $config = get_post_meta($listbox_id, '_wcag_listbox_config', true);
        $options = $this->get_options($listbox_id, $config);
        
        wp_send_json_success(['options' => $options]);
    }
    
    /**
     * Get options based on data source
     * 
     * @param int $listbox_id Listbox ID
     * @param array $config Listbox configuration
     * @return array Options array
     */
    private function get_options(int $listbox_id, array $config): array {
        $data_source = $config['data_source'] ?? 'static';
        
        switch ($data_source) {
            case 'static':
                return get_post_meta($listbox_id, '_wcag_listbox_options', true) ?: [];
                
            case 'posts':
                return $this->get_posts_options($config);
                
            case 'users':
                return $this->get_users_options($config);
                
            case 'terms':
                return $this->get_terms_options($config);
                
            case 'external':
                return $this->get_external_options($config);
                
            default:
                return [];
        }
    }
    
    /**
     * Get WordPress posts as options
     * 
     * @param array $config Configuration
     * @return array Options
     */
    private function get_posts_options(array $config): array {
        $args = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 50,
            'fields' => 'ids'
        ];
        
        // Parse custom WP_Query args if provided
        if (!empty($config['wp_query_args'])) {
            $custom_args = [];
            parse_str($config['wp_query_args'], $custom_args);
            $args = array_merge($args, $custom_args);
        }
        
        $posts = get_posts($args);
        $options = [];
        
        foreach ($posts as $post_id) {
            $post = get_post($post_id);
            if ($post) {
                $options[] = [
                    'value' => (string)$post_id,
                    'label' => $post->post_title,
                    'description' => wp_trim_words($post->post_excerpt ?: $post->post_content, 15),
                    'group' => get_post_type_object($post->post_type)->labels->singular_name ?? '',
                    'disabled' => false,
                    'selected' => false
                ];
            }
        }
        
        return $options;
    }
    
    /**
     * Get WordPress users as options
     * 
     * @param array $config Configuration
     * @return array Options
     */
    private function get_users_options(array $config): array {
        $args = [
            'number' => 50,
            'fields' => 'all'
        ];
        
        $users = get_users($args);
        $options = [];
        
        foreach ($users as $user) {
            $options[] = [
                'value' => (string)$user->ID,
                'label' => $user->display_name,
                'description' => $user->user_email,
                'group' => __('Utenti', 'wcag-wp'),
                'disabled' => false,
                'selected' => false
            ];
        }
        
        return $options;
    }
    
    /**
     * Get WordPress terms as options
     * 
     * @param array $config Configuration
     * @return array Options
     */
    private function get_terms_options(array $config): array {
        $args = [
            'number' => 50,
            'hide_empty' => false
        ];
        
        $terms = get_terms($args);
        $options = [];
        
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[] = [
                    'value' => (string)$term->term_id,
                    'label' => $term->name,
                    'description' => $term->description,
                    'group' => get_taxonomy($term->taxonomy)->labels->singular_name ?? '',
                    'disabled' => false,
                    'selected' => false
                ];
            }
        }
        
        return $options;
    }
    
    /**
     * Get external API options
     * 
     * @param array $config Configuration
     * @return array Options
     */
    private function get_external_options(array $config): array {
        $external_url = $config['external_url'] ?? '';
        if (empty($external_url)) {
            return [];
        }
        
        // Make API request
        $response = wp_remote_get($external_url, [
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
        $options = [];
        foreach ($data as $item) {
            if (is_array($item)) {
                $options[] = [
                    'value' => $item['value'] ?? $item['id'] ?? '',
                    'label' => $item['label'] ?? $item['name'] ?? $item['title'] ?? '',
                    'description' => $item['description'] ?? '',
                    'group' => $item['group'] ?? $item['category'] ?? '',
                    'disabled' => (bool)($item['disabled'] ?? false),
                    'selected' => (bool)($item['selected'] ?? false)
                ];
            }
        }
        
        return $options;
    }
    
    /**
     * Register REST API endpoints
     * 
     * @return void
     */
    public function register_rest_endpoints(): void {
        register_rest_route('wcag-wp/v1', '/listbox/(?P<id>\d+)/options', [
            'methods' => 'GET',
            'callback' => [$this, 'rest_get_options'],
            'permission_callback' => '__return_true',
            'args' => [
                'id' => [
                    'required' => true,
                    'validate_callback' => function($param) {
                        return is_numeric($param);
                    }
                ]
            ]
        ]);
    }
    
    /**
     * REST API get options endpoint
     * 
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response Response object
     */
    public function rest_get_options(WP_REST_Request $request): WP_REST_Response {
        $listbox_id = (int) $request['id'];
        
        $listbox = get_post($listbox_id);
        if (!$listbox || $listbox->post_type !== self::POST_TYPE || $listbox->post_status !== 'publish') {
            return new WP_REST_Response(['error' => 'Listbox not found'], 404);
        }
        
        $config = get_post_meta($listbox_id, '_wcag_listbox_config', true);
        $options = $this->get_options($listbox_id, $config);
        
        return new WP_REST_Response(['options' => $options], 200);
    }
    
    /**
     * Get listbox types
     * 
     * @return array Listbox types
     */
    public static function get_types(): array {
        return self::TYPES;
    }
    
    /**
     * Get selection modes
     * 
     * @return array Selection modes
     */
    public static function get_selection_modes(): array {
        return self::SELECTION_MODES;
    }
    
    /**
     * Get orientations
     * 
     * @return array Orientations
     */
    public static function get_orientations(): array {
        return self::ORIENTATIONS;
    }
}

// Initialize component
new WCAG_WP_Listbox();

