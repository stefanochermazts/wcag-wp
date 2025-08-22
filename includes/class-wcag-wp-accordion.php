<?php
declare(strict_types=1);

/**
 * WCAG Accordion Management Class
 * 
 * Handles all accordion-related functionality including:
 * - Custom Post Type registration
 * - Meta boxes for accordion configuration
 * - Section definitions and content management
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
 * WCAG_WP_Accordion Class
 * 
 * Manages accessible accordion components with WCAG prefix
 */
class WCAG_WP_Accordion {
    
    /**
     * Accordion configuration meta key
     */
    const META_CONFIG = '_wcag_wp_accordion_config';
    
    /**
     * Accordion sections meta key
     */
    const META_SECTIONS = '_wcag_wp_accordion_sections';
    
    /**
     * Constructor
     */
    public function __construct() {
        // Debug: log constructor call
        if (function_exists('error_log')) {
            error_log('WCAG_WP_Accordion constructor called');
        }
        $this->init();
    }
    
    /**
     * Initialize accordion functionality
     * 
     * @return void
     */
    public function init(): void {
        // Register custom post type immediately if 'init' already fired, otherwise hook into init
        if (did_action('init')) {
            error_log('Init already fired, registering CPT immediately');
            $this->register_post_type();
        } else {
            error_log('Hooking into init action');
            add_action('init', [$this, 'register_post_type']);
        }
        
        // Admin hooks
        if (function_exists('is_admin') && is_admin()) {
            add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
            add_action('save_post', [$this, 'save_accordion_meta']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
            
            // AJAX handlers
            add_action('wp_ajax_wcag_wp_save_accordion_section', [$this, 'ajax_save_accordion_section']);
            add_action('wp_ajax_wcag_wp_delete_accordion_section', [$this, 'ajax_delete_accordion_section']);
        }
        
        // Frontend hooks
        add_shortcode('wcag-accordion', [$this, 'shortcode_accordion']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        
        if (function_exists('wcag_wp_log')) {
            wcag_wp_log('WCAG Accordion component initialized', 'info');
        }
    }
    
    /**
     * Register Custom Post Type for WCAG accordions
     * 
     * @return void
     */
    public function register_post_type(): void {
        // Debug: log post type registration
        if (function_exists('error_log')) {
            error_log('WCAG_WP_Accordion register_post_type called');
        }
        $labels = [
            'name'                  => __('WCAG Accordion', 'wcag-wp'),
            'singular_name'         => __('WCAG Accordion', 'wcag-wp'),
            'menu_name'             => __('WCAG Accordion', 'wcag-wp'),
            'name_admin_bar'        => __('WCAG Accordion', 'wcag-wp'),
            'add_new'              => __('Aggiungi Nuovo', 'wcag-wp'),
            'add_new_item'         => __('Aggiungi Nuovo Accordion', 'wcag-wp'),
            'new_item'             => __('Nuovo Accordion', 'wcag-wp'),
            'edit_item'            => __('Modifica Accordion', 'wcag-wp'),
            'view_item'            => __('Visualizza Accordion', 'wcag-wp'),
            'all_items'            => __('Tutti gli Accordion', 'wcag-wp'),
            'search_items'         => __('Cerca Accordion', 'wcag-wp'),
            'parent_item_colon'    => __('WCAG Accordion Padre:', 'wcag-wp'),
            'not_found'            => __('Nessun accordion trovato.', 'wcag-wp'),
            'not_found_in_trash'   => __('Nessun accordion nel cestino.', 'wcag-wp'),
        ];

        $args = [
            'labels'             => $labels,
            'description'        => __('WCAG Accordion accessibili 2.1 AA compliant', 'wcag-wp'),
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
            'menu_icon'          => 'dashicons-list-view',
            'supports'           => ['title'],
            'show_in_rest'       => true,
            'rest_base'          => 'wcag-accordion',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ];

        register_post_type('wcag_accordion', $args);
        
        if (function_exists('wcag_wp_log')) {
            wcag_wp_log('Custom Post Type wcag_accordion registered', 'info');
        }
    }
    
    /**
     * Add meta boxes for WCAG accordion configuration
     * 
     * @return void
     */
    public function add_meta_boxes(): void {
        error_log("WCAG Accordion add_meta_boxes called");
        
        add_meta_box(
            'wcag-wp-accordion-config',
            __('Configurazione WCAG Accordion', 'wcag-wp'),
            [$this, 'render_config_meta_box'],
            'wcag_accordion',
            'normal',
            'high'
        );
        
        add_meta_box(
            'wcag-wp-accordion-sections',
            __('Sezioni WCAG Accordion', 'wcag-wp'),
            [$this, 'render_sections_meta_box'],
            'wcag_accordion',
            'normal',
            'high'
        );
        
        add_meta_box(
            'wcag-wp-accordion-preview',
            __('Anteprima & Shortcode', 'wcag-wp'),
            [$this, 'render_preview_meta_box'],
            'wcag_accordion',
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
        error_log("WCAG Accordion render_config_meta_box called for post: {$post->ID}");
        
        // Add nonce field for security
        wp_nonce_field('wcag_wp_accordion_meta_nonce', 'wcag_wp_accordion_meta_nonce');
        error_log("WCAG Accordion nonce field generated");
        
        // Get existing configuration
        $config = get_post_meta($post->ID, self::META_CONFIG, true);
        $config = wp_parse_args($config, [
            'allow_multiple_open' => false,
            'first_panel_open' => true,
            'keyboard_navigation' => true,
            'animate_transitions' => true,
            'collapse_all' => true,
            'custom_css_class' => '',
            'icon_type' => 'chevron',
            'icon_position' => 'right'
        ]);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/accordion-config-meta-box.php';
    }
    
    /**
     * Render sections meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_sections_meta_box(WP_Post $post): void {
        // Get existing sections
        $sections = get_post_meta($post->ID, self::META_SECTIONS, true);
        if (!is_array($sections)) {
            $sections = [];
        }
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/accordion-sections-meta-box.php';
    }
    
    /**
     * Render preview and shortcode meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_preview_meta_box(WP_Post $post): void {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/accordion-preview-meta-box.php';
    }
    
    /**
     * Save accordion meta data
     * 
     * @param int $post_id Post ID
     * @return void
     */
    public function save_accordion_meta(int $post_id): void {
        error_log("WCAG Accordion save_accordion_meta called for post_id: {$post_id}");
        error_log("POST keys: " . implode(', ', array_keys($_POST)));
        error_log("Nonce in POST: " . (isset($_POST['wcag_wp_accordion_meta_nonce']) ? $_POST['wcag_wp_accordion_meta_nonce'] : 'NOT SET'));
        
        // Verify nonce
        if (!isset($_POST['wcag_wp_accordion_meta_nonce']) || 
            !wp_verify_nonce($_POST['wcag_wp_accordion_meta_nonce'], 'wcag_wp_accordion_meta_nonce')) {
            error_log("WCAG Accordion save: Nonce verification failed");
            error_log("Expected nonce action: wcag_wp_accordion_meta_nonce");
            return;
        }
        
        // Check if user has permission to edit post
        if (!current_user_can('edit_post', $post_id)) {
            error_log("WCAG Accordion save: User permission check failed");
            return;
        }
        
        // Skip autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            error_log("WCAG Accordion save: Skipping autosave");
            return;
        }
        
        // Only save for our post type
        if (get_post_type($post_id) !== 'wcag_accordion') {
            error_log("WCAG Accordion save: Wrong post type: " . get_post_type($post_id));
            return;
        }
        
        error_log("WCAG Accordion save: All checks passed, proceeding with save");
        
        // Save configuration
        if (isset($_POST['wcag_wp_accordion_config'])) {
            $config = $this->sanitize_accordion_config($_POST['wcag_wp_accordion_config']);
            update_post_meta($post_id, self::META_CONFIG, $config);
        }
        
        // Save sections
        if (isset($_POST['wcag_wp_accordion_sections'])) {
            $sections = $this->sanitize_accordion_sections($_POST['wcag_wp_accordion_sections']);
            update_post_meta($post_id, self::META_SECTIONS, $sections);
        }
        
        wcag_wp_log("WCAG Accordion meta saved for post ID: {$post_id}", 'info');
    }
    
    /**
     * Sanitize accordion configuration
     * 
     * @param array $config Raw configuration data
     * @return array Sanitized configuration
     */
    private function sanitize_accordion_config(array $config): array {
        return [
            'allow_multiple_open' => (bool)($config['allow_multiple_open'] ?? false),
            'first_panel_open' => (bool)($config['first_panel_open'] ?? true),
            'keyboard_navigation' => (bool)($config['keyboard_navigation'] ?? true),
            'animate_transitions' => (bool)($config['animate_transitions'] ?? true),
            'collapse_all' => (bool)($config['collapse_all'] ?? true),
            'custom_css_class' => sanitize_html_class($config['custom_css_class'] ?? ''),
            'icon_type' => sanitize_text_field($config['icon_type'] ?? 'chevron'),
            'icon_position' => sanitize_text_field($config['icon_position'] ?? 'right')
        ];
    }
    
    /**
     * Sanitize accordion sections
     * 
     * @param array $sections Raw sections data
     * @return array Sanitized sections
     */
    private function sanitize_accordion_sections(array $sections): array {
        $sanitized = [];
        
        foreach ($sections as $section) {
            if (!is_array($section)) continue;
            
            $sanitized[] = [
                'id' => sanitize_key($section['id'] ?? ''),
                'title' => sanitize_text_field($section['title'] ?? ''),
                'content' => wp_kses_post($section['content'] ?? ''),
                'is_open' => (bool)($section['is_open'] ?? false),
                'disabled' => (bool)($section['disabled'] ?? false),
                'css_class' => sanitize_html_class($section['css_class'] ?? ''),
                'order' => absint($section['order'] ?? 0)
            ];
        }
        
        return $sanitized;
    }
    
    /**
     * Enqueue admin assets for accordion management
     * 
     * @param string $hook Current admin page hook
     * @return void
     */
    public function enqueue_admin_assets(string $hook): void {
        error_log("WCAG Accordion enqueue_admin_assets called with hook: {$hook}");
        
        // Only load on our post type pages
        if (!in_array($hook, ['post.php', 'post-new.php', 'edit.php'])) {
            error_log("WCAG Accordion enqueue: Wrong hook, returning");
            return;
        }
        
        global $post_type;
        error_log("WCAG Accordion enqueue: post_type is: " . ($post_type ?? 'null'));
        if ($post_type !== 'wcag_accordion') {
            error_log("WCAG Accordion enqueue: Wrong post type, returning");
            return;
        }
        
        error_log("WCAG Accordion enqueue: Loading admin assets");
        
        wp_enqueue_style(
            'wcag-wp-accordion-admin',
            WCAG_WP_ASSETS_URL . 'css/accordion-admin.css',
            [],
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-accordion-admin',
            WCAG_WP_ASSETS_URL . 'js/accordion-admin.js',
            ['jquery', 'jquery-ui-sortable'],
            WCAG_WP_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('wcag-wp-accordion-admin', 'wcag_wp_accordion', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wcag_wp_accordion_nonce'),
            'strings' => [
                'confirm_delete_section' => __('Sei sicuro di voler eliminare questa sezione?', 'wcag-wp'),
                'error_generic' => __('Si è verificato un errore. Riprova.', 'wcag-wp'),
                'section_added' => __('Sezione aggiunta con successo.', 'wcag-wp'),
                'changes_saved' => __('Modifiche salvate.', 'wcag-wp')
            ]
        ]);
    }
    
    /**
     * Enqueue frontend assets
     * 
     * @return void
     */
    public function enqueue_frontend_assets(): void {
        // Only enqueue if accordion shortcode is present
        global $post;
        if (!$post || !has_shortcode($post->post_content, 'wcag-accordion')) {
            return;
        }
        
        wp_enqueue_style(
            'wcag-wp-accordion-frontend',
            WCAG_WP_ASSETS_URL . 'css/accordion-frontend.css',
            ['wcag-wp-frontend'],
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-accordion-frontend',
            WCAG_WP_ASSETS_URL . 'js/accordion-frontend.js',
            ['wcag-wp-frontend'],
            WCAG_WP_VERSION,
            true
        );
        
        // Localize script for frontend functionality
        wp_localize_script('wcag-wp-accordion-frontend', 'wcag_wp_accordion_frontend', [
            'strings' => [
                'expand' => __('Espandi sezione', 'wcag-wp'),
                'collapse' => __('Chiudi sezione', 'wcag-wp'),
                'section_expanded' => __('Sezione espansa', 'wcag-wp'),
                'section_collapsed' => __('Sezione chiusa', 'wcag-wp')
            ]
        ]);
    }
    
    /**
     * Shortcode handler for WCAG accordion display
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function shortcode_accordion(array $atts): string {
        $atts = shortcode_atts([
            'id' => 0,
            'class' => '',
            'allow_multiple' => null,
            'first_open' => null,
            'keyboard' => null
        ], $atts, 'wcag-accordion');
        
        $accordion_id = absint($atts['id']);
        if (!$accordion_id) {
            return '<div class="wcag-wp-error">' . __('ID WCAG Accordion non specificato', 'wcag-wp') . '</div>';
        }
        
        return $this->render_accordion($accordion_id, $atts);
    }
    
    /**
     * Render WCAG accordion HTML
     * 
     * @param int $accordion_id Accordion post ID
     * @param array $options Display options
     * @return string HTML output
     */
    public function render_accordion(int $accordion_id, array $options = []): string {
        // Get accordion post
        $accordion_post = get_post($accordion_id);
        if (!$accordion_post || $accordion_post->post_type !== 'wcag_accordion') {
            return '<div class="wcag-wp-error">' . __('WCAG Accordion non trovato', 'wcag-wp') . '</div>';
        }
        
        // Get accordion data
        $config = get_post_meta($accordion_id, self::META_CONFIG, true);
        $sections = get_post_meta($accordion_id, self::META_SECTIONS, true);
        
        if (!is_array($sections) || empty($sections)) {
            return '<div class="wcag-wp-error">' . __('Nessuna sezione definita per questo WCAG Accordion', 'wcag-wp') . '</div>';
        }
        
        // Merge options with config
        $config = wp_parse_args($options, $config);
        
        // Sort sections by order
        usort($sections, function($a, $b) {
            return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
        });
        
        // Start output buffering
        ob_start();
        
        // Include accordion template
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-accordion.php';
        
        return ob_get_clean();
    }
    
    /**
     * Get WCAG accordion by ID with full data
     * 
     * @param int $accordion_id Accordion post ID
     * @return array|null Accordion data or null if not found
     */
    public function get_accordion(int $accordion_id): ?array {
        $accordion_post = get_post($accordion_id);
        if (!$accordion_post || $accordion_post->post_type !== 'wcag_accordion') {
            return null;
        }
        
        return [
            'id' => $accordion_id,
            'title' => $accordion_post->post_title,
            'config' => get_post_meta($accordion_id, self::META_CONFIG, true),
            'sections' => get_post_meta($accordion_id, self::META_SECTIONS, true),
            'post' => $accordion_post
        ];
    }
    
    /**
     * AJAX handler: Save accordion section
     * 
     * @return void
     */
    public function ajax_save_accordion_section(): void {
        // Verify nonce and permissions
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'wcag_wp_accordion_nonce')) {
            wp_send_json_error(__('Accesso negato', 'wcag-wp'));
        }
        
        $post_id = absint($_POST['post_id'] ?? 0);
        if (!$post_id || !current_user_can('edit_post', $post_id)) {
            wp_send_json_error(__('Permessi insufficienti', 'wcag-wp'));
        }
        
        $section_data = $_POST['section'] ?? [];
        $section = $this->sanitize_accordion_sections([$section_data])[0] ?? null;
        
        if (!$section) {
            wp_send_json_error(__('Dati sezione non validi', 'wcag-wp'));
        }
        
        // Get existing sections
        $sections = get_post_meta($post_id, self::META_SECTIONS, true);
        if (!is_array($sections)) {
            $sections = [];
        }
        
        // Add or update section
        $section_index = absint($_POST['section_index'] ?? -1);
        if ($section_index >= 0 && isset($sections[$section_index])) {
            $sections[$section_index] = $section;
        } else {
            $sections[] = $section;
        }
        
        // Save sections
        update_post_meta($post_id, self::META_SECTIONS, $sections);
        
        wp_send_json_success([
            'message' => __('Sezione WCAG salvata con successo', 'wcag-wp'),
            'section' => $section,
            'sections' => $sections
        ]);
    }
    
    /**
     * AJAX handler: Delete accordion section
     * 
     * @return void
     */
    public function ajax_delete_accordion_section(): void {
        // Verify nonce and permissions
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'wcag_wp_accordion_nonce')) {
            wp_send_json_error(__('Accesso negato', 'wcag-wp'));
        }
        
        $post_id = absint($_POST['post_id'] ?? 0);
        $section_index = absint($_POST['section_index'] ?? -1);
        
        if (!$post_id || !current_user_can('edit_post', $post_id)) {
            wp_send_json_error(__('Permessi insufficienti', 'wcag-wp'));
        }
        
        // Get existing sections
        $sections = get_post_meta($post_id, self::META_SECTIONS, true);
        if (!is_array($sections) || !isset($sections[$section_index])) {
            wp_send_json_error(__('Sezione non trovata', 'wcag-wp'));
        }
        
        // Remove section
        unset($sections[$section_index]);
        $sections = array_values($sections); // Re-index array
        
        // Save sections
        update_post_meta($post_id, self::META_SECTIONS, $sections);
        
        wp_send_json_success([
            'message' => __('Sezione WCAG eliminata con successo', 'wcag-wp'),
            'sections' => $sections
        ]);
    }
}
