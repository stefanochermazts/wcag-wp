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
        // Applica subito lo schema colori così l'attributo è presente su <html>
        // prima del rendering dell'header del tema
        $this->apply_color_scheme();
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
                'reduce_motion' => false,
                'theme_switcher' => true,
                'default_theme' => 'auto',
                'toggle_position_selector' => '',
                'custom_primary' => '#2563eb',
                'custom_primary_dark' => '#1d4ed8',
                'custom_primary_light' => '#dbeafe',
                'custom_secondary' => '#64748b'
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
                'tabpanel' => 'includes/class-wcag-wp-tabpanel.php',
                'toc' => 'includes/class-wcag-wp-toc.php',
                'carousel' => 'includes/class-wcag-wp-carousel.php',
                'calendar' => 'includes/class-wcag-wp-calendar.php',
                'notifications' => 'includes/class-wcag-wp-notifications.php',
                'breadcrumb' => 'includes/components/class-wcag-wp-breadcrumb.php',
                'combobox' => 'includes/class-wcag-wp-combobox.php',
                'listbox' => 'includes/class-wcag-wp-listbox.php',
                'spinbutton' => 'includes/class-wcag-wp-spinbutton.php',
                'switch' => 'includes/class-wcag-wp-switch.php',
                'slider' => 'includes/class-wcag-wp-slider.php', // Added for Slider
                'slider-multithumb' => 'includes/class-wcag-wp-slider-multithumb.php', // Added for Slider Multi-Thumb
                'radio-group' => 'includes/class-wcag-wp-radio-group.php', // Added for Radio Group
                'menu' => 'includes/class-wcag-wp-menu.php', // Added for Menu/Menubar
                'menubutton' => 'includes/class-wcag-wp-menubutton.php', // Added for Menu Button
                'toolbar' => 'includes/class-wcag-wp-toolbar.php', // Added for Toolbar
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
        // La registrazione del CPT "wcag_tables" è gestita dal componente WCAG_WP_Tables
        // per evitare duplicazioni e conflitti.
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

        // Initialize WCAG tabpanel component
        if (class_exists('WCAG_WP_TabPanel')) {
            $this->components['tabpanel'] = new WCAG_WP_TabPanel();
            wcag_wp_log('WCAG Tab Panel component initialized successfully', 'info');
        }

        // Initialize TOC component
        if (class_exists('WCAG_WP_TOC')) {
            $this->components['toc'] = new WCAG_WP_TOC();
            wcag_wp_log('TOC component initialized successfully', 'info');
        }

        // Initialize Carousel component
        if (class_exists('WCAG_WP_Carousel')) {
            $this->components['carousel'] = new WCAG_WP_Carousel();
            wcag_wp_log('Carousel component initialized successfully', 'info');
        }

        // Initialize Calendar component
        if (class_exists('WCAG_WP_Calendar')) {
            $this->components['calendar'] = new WCAG_WP_Calendar();
            wcag_wp_log('Calendar component initialized successfully', 'info');
        }

        // Initialize Notifications component
        if (class_exists('WCAG_WP_Notifications')) {
            $this->components['notifications'] = new WCAG_WP_Notifications();
            wcag_wp_log('Notifications component initialized successfully', 'info');
        }

        // Initialize Combobox component
        if (class_exists('WCAG_WP_Combobox')) {
            $this->components['combobox'] = new WCAG_WP_Combobox();
            wcag_wp_log('Combobox component initialized successfully', 'info');
        }

        // Initialize Listbox component
        if (class_exists('WCAG_WP_Listbox')) {
            $this->components['listbox'] = new WCAG_WP_Listbox();
            wcag_wp_log('Listbox component initialized successfully', 'info');
        }

        // Initialize Spinbutton component
        if (class_exists('WCAG_WP_Spinbutton')) {
            $this->components['spinbutton'] = WCAG_WP_Spinbutton::get_instance();
            wcag_wp_log('Spinbutton component initialized successfully', 'info');
        }

        // Initialize Switch component
        if (class_exists('WCAG_WP_Switch')) {
            $this->components['switch'] = WCAG_WP_Switch::get_instance();
            wcag_wp_log('Switch component initialized successfully', 'info');
        }

        // Initialize Slider component
        if (class_exists('WCAG_WP_Slider')) {
            $this->components['slider'] = WCAG_WP_Slider::get_instance();
            wcag_wp_log('Slider component initialized successfully', 'info');
        }

        // Initialize Slider Multi-Thumb component
        if (class_exists('WCAG_WP_Slider_Multithumb')) {
            $this->components['slider_multithumb'] = WCAG_WP_Slider_Multithumb::get_instance();
            wcag_wp_log('Slider Multi-Thumb component initialized successfully', 'info');
        }

        // Initialize Radio Group component
        if (class_exists('WCAG_WP_Radio_Group')) {
            $this->components['radio_group'] = WCAG_WP_Radio_Group::get_instance();
            wcag_wp_log('Radio Group component initialized successfully', 'info');
        }

        // Initialize Breadcrumb component
        if (class_exists('WCAG_WP\\Components\\WCAG_WP_Breadcrumb')) {
            $this->components['breadcrumb'] = \WCAG_WP\Components\WCAG_WP_Breadcrumb::get_instance();
            wcag_wp_log('Breadcrumb component initialized successfully', 'info');
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
        
        // Handle settings page redirect
        if (isset($_GET['settings-updated']) && $_GET['page'] === 'wcag-wp-settings') {
            // Force reload settings after save
            $this->load_settings();
        }
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
                'color_scheme' => in_array(($input['design_system']['color_scheme'] ?? 'default'), ['default','green','purple','orange','custom'], true)
                    ? $input['design_system']['color_scheme']
                    : 'default',
                'font_family' => sanitize_text_field($input['design_system']['font_family'] ?? 'system-ui'),
                'focus_outline' => (bool)($input['design_system']['focus_outline'] ?? true),
                'reduce_motion' => (bool)($input['design_system']['reduce_motion'] ?? false),
                'theme_switcher' => (bool)($input['design_system']['theme_switcher'] ?? true),
                'default_theme' => in_array(($input['design_system']['default_theme'] ?? 'auto'), ['auto','dark','light'], true)
                    ? $input['design_system']['default_theme']
                    : 'auto',
                'toggle_position_selector' => sanitize_text_field($input['design_system']['toggle_position_selector'] ?? ''),
                'custom_primary' => sanitize_hex_color($input['design_system']['custom_primary'] ?? '#2563eb') ?: '#2563eb',
                'custom_primary_dark' => sanitize_hex_color($input['design_system']['custom_primary_dark'] ?? '#1d4ed8') ?: '#1d4ed8',
                'custom_primary_light' => sanitize_hex_color($input['design_system']['custom_primary_light'] ?? '#dbeafe') ?: '#dbeafe',
                'custom_secondary' => sanitize_hex_color($input['design_system']['custom_secondary'] ?? '#64748b') ?: '#64748b'
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
        // Always enqueue frontend assets for theme toggle and global styles
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
            'settings' => $this->settings,
            'theme' => [
                'switcher' => (bool)($this->settings['design_system']['theme_switcher'] ?? true),
                'default' => $this->settings['design_system']['default_theme'] ?? 'auto',
                'position_selector' => $this->settings['design_system']['toggle_position_selector'] ?? ''
            ],
            'color_scheme' => [
                'current' => $this->settings['design_system']['color_scheme'] ?? 'default',
                'custom_colors' => [
                    'primary' => $this->settings['design_system']['custom_primary'] ?? '#2563eb',
                    'primary_dark' => $this->settings['design_system']['custom_primary_dark'] ?? '#1d4ed8',
                    'primary_light' => $this->settings['design_system']['custom_primary_light'] ?? '#dbeafe',
                    'secondary' => $this->settings['design_system']['custom_secondary'] ?? '#64748b'
                ]
            ]
        ]);
        
        // Applica schema colori al body
        $this->apply_color_scheme();
    }
    
    /**
     * Apply color scheme to body element
     * 
     * @return void
     */
    private function apply_color_scheme(): void {
        $color_scheme = $this->settings['design_system']['color_scheme'] ?? 'default';
        
        // Aggiungi CSS inline per colori personalizzati se necessario
        if ($color_scheme === 'custom') {
            add_action('wp_head', [$this, 'add_custom_color_css']);
        }
        
        // Applica schema colori direttamente al tag HTML (lato server) - priorità alta
        add_filter('language_attributes', function($output) use ($color_scheme) {
            return $output . ' data-wcag-color-scheme="' . esc_attr($color_scheme) . '"';
        }, 5);
        
        // Applica anche via JavaScript immediatamente nell'head
        add_action('wp_head', function() use ($color_scheme) {
            echo '<script>document.documentElement.setAttribute("data-wcag-color-scheme", "' . esc_js($color_scheme) . '");</script>' . "\n";
        }, 1);
        
        // Applica anche via body class come fallback
        add_filter('body_class', function($classes) use ($color_scheme) {
            $classes[] = 'wcag-color-scheme-' . $color_scheme;
            return $classes;
        });
    }
    
    /**
     * Add custom color CSS variables
     * 
     * @return void
     */
    public function add_custom_color_css(): void {
        $custom_colors = [
            'primary' => $this->settings['design_system']['custom_primary'] ?? '#2563eb',
            'primary_dark' => $this->settings['design_system']['custom_primary_dark'] ?? '#1d4ed8',
            'primary_light' => $this->settings['design_system']['custom_primary_light'] ?? '#dbeafe',
            'secondary' => $this->settings['design_system']['custom_secondary'] ?? '#64748b'
        ];
        
        echo '<style id="wcag-wp-custom-colors">' . "\n";
        echo ':root {' . "\n";
        echo '  --wcag-custom-primary: ' . esc_attr($custom_colors['primary']) . ';' . "\n";
        echo '  --wcag-custom-primary-dark: ' . esc_attr($custom_colors['primary_dark']) . ';' . "\n";
        echo '  --wcag-custom-primary-light: ' . esc_attr($custom_colors['primary_light']) . ';' . "\n";
        echo '  --wcag-custom-secondary: ' . esc_attr($custom_colors['secondary']) . ';' . "\n";
        echo '}' . "\n";
        echo '</style>' . "\n";
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
