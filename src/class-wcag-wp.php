<?php
declare(strict_types=1);

/**
 * Main plugin class
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main WCAG_WP Class
 * 
 * Singleton pattern implementation for the main plugin functionality
 */
final class WCAG_WP {
    
    /**
     * Plugin instance
     * 
     * @var WCAG_WP|null
     */
    private static ?WCAG_WP $instance = null;
    
    /**
     * Plugin settings
     * 
     * @var array
     */
    private array $settings = [];
    
    /**
     * Plugin components
     * 
     * @var array
     */
    private array $components = [];
    
    /**
     * Get plugin instance (Singleton pattern)
     * 
     * @return WCAG_WP
     */
    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor - Initialize plugin
     * 
     * @return void
     */
    private function __construct() {
        $this->load_settings();
        $this->define_hooks();
        $this->load_components();
        
        wcag_wp_log('Plugin initialized successfully', 'info');
    }
    
    /**
     * Prevent cloning
     * 
     * @return void
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization
     * 
     * @return void
     */
    public function __wakeup() {
        wp_die('Unserialization of WCAG_WP is not allowed.');
    }
    
    /**
     * Load plugin settings from database
     * 
     * @return void
     */
    private function load_settings(): void {
        $this->settings = get_option('wcag_wp_settings', [
            'design_system' => [
                'color_scheme' => 'default',
                'font_family' => 'system-ui',
                'focus_outline' => true,
                'reduce_motion' => false
            ],
            'accessibility' => [
                'screen_reader_support' => true,
                'keyboard_navigation' => true,
                'high_contrast' => false
            ]
        ]);
    }
    
    /**
     * Define WordPress hooks
     * 
     * @return void
     */
    private function define_hooks(): void {
        // Admin hooks
        if (is_admin()) {
            add_action('admin_menu', [$this, 'add_admin_menu']);
            add_action('admin_init', [$this, 'admin_init']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        }
        
        // Frontend hooks
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('init', [$this, 'init']);
        add_action('wp_head', [$this, 'add_accessibility_meta']);
        
        // Shortcodes
        add_action('init', [$this, 'register_shortcodes']);
        
        // Gutenberg blocks
        add_action('init', [$this, 'register_blocks']);
        
        // AJAX hooks
        add_action('wp_ajax_wcag_wp_action', [$this, 'handle_ajax_request']);
        add_action('wp_ajax_nopriv_wcag_wp_action', [$this, 'handle_ajax_request']);
    }
    
    /**
     * Load plugin components
     * 
     * @return void
     */
    private function load_components(): void {
        // Component files to load
        $components = [
            'tables' => 'includes/class-wcag-wp-tables.php',
            'accordion' => 'includes/class-wcag-wp-accordion.php',
            'design-system' => 'includes/class-design-system.php',
            'accessibility' => 'includes/class-accessibility.php'
        ];
        
        foreach ($components as $name => $file) {
            $file_path = WCAG_WP_PLUGIN_DIR . $file;
            if (file_exists($file_path)) {
                error_log("Loading component: {$name} from {$file_path}");
                require_once $file_path;
                wcag_wp_log("Component loaded: {$name}", 'info');
                if ($name === 'accordion') {
                    error_log("Accordion file loaded, checking class existence: " . (class_exists('WCAG_WP_Accordion') ? 'YES' : 'NO'));
                }
            } else {
                error_log("Component file not found: {$file_path}");
                wcag_wp_log("Component file not found: {$file_path}", 'warning');
            }
        }
    }
    
    /**
     * Initialize plugin functionality
     * 
     * @return void
     */
    public function init(): void {
        // Register custom post types
        $this->register_post_types();
        
        // Setup custom database tables if needed
        $this->setup_database();
        
        // Initialize components
        $this->init_components();
    }
    
    /**
     * Register custom post types
     * 
     * @return void
     */
    private function register_post_types(): void {
        // Register "wcag_tables" custom post type
        register_post_type('wcag_tables', [
            'labels' => [
                'name' => __('Tabelle WCAG', 'wcag-wp'),
                'singular_name' => __('Tabella WCAG', 'wcag-wp'),
                'add_new' => __('Aggiungi Nuova', 'wcag-wp'),
                'add_new_item' => __('Aggiungi Nuova Tabella', 'wcag-wp'),
                'edit_item' => __('Modifica Tabella', 'wcag-wp'),
                'new_item' => __('Nuova Tabella', 'wcag-wp'),
                'view_item' => __('Visualizza Tabella', 'wcag-wp'),
                'search_items' => __('Cerca Tabelle', 'wcag-wp'),
                'not_found' => __('Nessuna tabella trovata', 'wcag-wp'),
                'not_found_in_trash' => __('Nessuna tabella nel cestino', 'wcag-wp'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'wcag-wp-main',
            'capability_type' => 'post',
            'supports' => ['title', 'editor'],
            'has_archive' => false,
            'rewrite' => false,
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Setup custom database tables
     * 
     * @return void
     */
    private function setup_database(): void {
        // Database setup will be implemented when needed
        // For MVP, we'll use post meta for table data storage
    }
    
    /**
     * Initialize components
     * 
     * @return void
     */
    private function init_components(): void {
        // Initialize design system
        if (class_exists('WCAG_WP_Design_System')) {
            $this->components['design_system'] = new WCAG_WP_Design_System($this->settings['design_system']);
        }
        
        // Initialize accessibility features
        if (class_exists('WCAG_WP_Accessibility')) {
            $this->components['accessibility'] = new WCAG_WP_Accessibility($this->settings['accessibility']);
        }
        
        // Initialize WCAG tables component
        if (class_exists('WCAG_WP_Tables')) {
            $this->components['tables'] = new WCAG_WP_Tables();
            wcag_wp_log('WCAG Tables component initialized successfully', 'info');
        }
        
        // Initialize WCAG accordion component
        if (class_exists('WCAG_WP_Accordion')) {
            error_log('WCAG_WP_Accordion class exists, creating instance');
            $this->components['accordion'] = new WCAG_WP_Accordion();
            wcag_wp_log('WCAG Accordion component initialized successfully', 'info');
        } else {
            error_log('WCAG_WP_Accordion class does NOT exist');
        }
    }
    
    /**
     * Add admin menu
     * 
     * @return void
     */
    public function add_admin_menu(): void {
        add_menu_page(
            __('WCAG-WP', 'wcag-wp'),
            __('WCAG-WP', 'wcag-wp'),
            'manage_options',
            'wcag-wp-main',
            [$this, 'admin_page_main'],
            'dashicons-universal-access-alt',
            30
        );
        
        add_submenu_page(
            'wcag-wp-main',
            __('Impostazioni', 'wcag-wp'),
            __('Impostazioni', 'wcag-wp'),
            'manage_options',
            'wcag-wp-settings',
            [$this, 'admin_page_settings']
        );
    }
    
    /**
     * Admin initialization
     * 
     * @return void
     */
    public function admin_init(): void {
        // Register settings
        register_setting('wcag_wp_settings', 'wcag_wp_settings', [
            'sanitize_callback' => [$this, 'sanitize_settings']
        ]);
    }
    
    /**
     * Sanitize plugin settings
     * 
     * @param array $input Raw input data
     * @return array Sanitized data
     */
    public function sanitize_settings(array $input): array {
        $sanitized = [];
        
        // Sanitize design system settings
        if (isset($input['design_system'])) {
            $sanitized['design_system'] = [
                'color_scheme' => sanitize_text_field($input['design_system']['color_scheme'] ?? 'default'),
                'font_family' => sanitize_text_field($input['design_system']['font_family'] ?? 'system-ui'),
                'focus_outline' => (bool)($input['design_system']['focus_outline'] ?? true),
                'reduce_motion' => (bool)($input['design_system']['reduce_motion'] ?? false)
            ];
        }
        
        // Sanitize accessibility settings
        if (isset($input['accessibility'])) {
            $sanitized['accessibility'] = [
                'screen_reader_support' => (bool)($input['accessibility']['screen_reader_support'] ?? true),
                'keyboard_navigation' => (bool)($input['accessibility']['keyboard_navigation'] ?? true),
                'high_contrast' => (bool)($input['accessibility']['high_contrast'] ?? false)
            ];
        }
        
        return $sanitized;
    }
    
    /**
     * Enqueue admin assets
     * 
     * @param string $hook Current admin page hook
     * @return void
     */
    public function enqueue_admin_assets(string $hook): void {
        // Only load on our plugin pages
        if (strpos($hook, 'wcag-wp') === false) {
            return;
        }
        
        wp_enqueue_style(
            'wcag-wp-admin',
            WCAG_WP_ASSETS_URL . 'css/admin.css',
            [],
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-admin',
            WCAG_WP_ASSETS_URL . 'js/admin.js',
            ['jquery'],
            WCAG_WP_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('wcag-wp-admin', 'wcag_wp_admin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wcag_wp_admin_nonce'),
            'strings' => [
                'confirm_delete' => __('Sei sicuro di voler eliminare questo elemento?', 'wcag-wp'),
                'error_generic' => __('Si è verificato un errore. Riprova.', 'wcag-wp')
            ]
        ]);
    }
    
    /**
     * Enqueue frontend assets
     * 
     * @return void
     */
    public function enqueue_frontend_assets(): void {
        wp_enqueue_style(
            'wcag-wp-frontend',
            WCAG_WP_ASSETS_URL . 'css/frontend.css',
            [],
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-frontend',
            WCAG_WP_ASSETS_URL . 'js/frontend.js',
            [],
            WCAG_WP_VERSION,
            true
        );
        
        // Localize script for frontend functionality
        wp_localize_script('wcag-wp-frontend', 'wcag_wp', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wcag_wp_frontend_nonce'),
            'settings' => $this->settings
        ]);
    }
    
    /**
     * Add accessibility meta tags to head
     * 
     * @return void
     */
    public function add_accessibility_meta(): void {
        echo '<meta name="accessibility-compliance" content="WCAG 2.1 AA">' . "\n";
        
        if ($this->settings['accessibility']['high_contrast']) {
            echo '<meta name="color-scheme" content="dark light">' . "\n";
        }
    }
    
    /**
     * Register shortcodes
     * 
     * @return void
     */
    public function register_shortcodes(): void {
        add_shortcode('wcag-table', [$this, 'shortcode_table']);
        add_shortcode('wcag-accordion', [$this, 'shortcode_accordion']);
        add_shortcode('wcag-toc', [$this, 'shortcode_toc']);
    }
    
    /**
     * Register Gutenberg blocks
     * 
     * @return void
     */
    public function register_blocks(): void {
        // Block registration will be implemented in Phase 2
    }
    
    /**
     * Handle AJAX requests
     * 
     * @return void
     */
    public function handle_ajax_request(): void {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'wcag_wp_frontend_nonce') && 
            !wp_verify_nonce($_POST['nonce'] ?? '', 'wcag_wp_admin_nonce')) {
            wp_die(__('Accesso negato', 'wcag-wp'));
        }
        
        $action = sanitize_text_field($_POST['wcag_action'] ?? '');
        
        switch ($action) {
            case 'get_table_data':
                $this->ajax_get_table_data();
                break;
            default:
                wp_send_json_error(__('Azione non valida', 'wcag-wp'));
        }
    }
    
    /**
     * AJAX handler for table data
     * 
     * @return void
     */
    private function ajax_get_table_data(): void {
        $table_id = absint($_POST['table_id'] ?? 0);
        
        if (!$table_id) {
            wp_send_json_error(__('ID tabella non valido', 'wcag-wp'));
        }
        
        // Get table data (implementation in Phase 2)
        wp_send_json_success(['message' => 'Implementazione in Fase 2']);
    }
    
    /**
     * Shortcode: WCAG Table
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
        
        // Delegate to tables component if available
        if (isset($this->components['tables']) && method_exists($this->components['tables'], 'shortcode_table')) {
            return $this->components['tables']->shortcode_table($atts);
        }
        
        return '<div class="wcag-wp-error">' . __('WCAG Tables component non disponibile', 'wcag-wp') . '</div>';
    }
    
    /**
     * Shortcode: Accordion
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
        
        // Delegate to accordion component if available
        if (isset($this->components['accordion']) && method_exists($this->components['accordion'], 'shortcode_accordion')) {
            return $this->components['accordion']->shortcode_accordion($atts);
        }
        
        return '<div class="wcag-wp-error">' . __('WCAG Accordion component non disponibile', 'wcag-wp') . '</div>';
    }
    
    /**
     * Shortcode: Table of Contents
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function shortcode_toc(array $atts): string {
        // Implementation in Phase 3
        return '<div class="wcag-wp-placeholder">TOC WCAG (Implementazione Fase 3)</div>';
    }
    
    /**
     * Admin page: Main dashboard
     * 
     * @return void
     */
    public function admin_page_main(): void {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/main-dashboard.php';
    }
    
    /**
     * Admin page: Settings
     * 
     * @return void
     */
    public function admin_page_settings(): void {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/settings.php';
    }
    
    /**
     * Get plugin settings
     * 
     * @param string|null $key Specific setting key
     * @return mixed
     */
    public function get_settings(?string $key = null) {
        if ($key === null) {
            return $this->settings;
        }
        
        return $this->settings[$key] ?? null;
    }
    
    /**
     * Get plugin component
     * 
     * @param string $component Component name
     * @return object|null
     */
    public function get_component(string $component): ?object {
        return $this->components[$component] ?? null;
    }
}
