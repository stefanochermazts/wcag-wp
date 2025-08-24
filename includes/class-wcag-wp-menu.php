<?php
/**
 * WCAG Menu Component
 * 
 * Accessible menu/menubar implementation following WCAG 2.1 AA guidelines
 * Based on WAI-ARIA Authoring Practices Guide (APG) Menu and Menubar patterns
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class WCAG_WP_Menu {
    
    /**
     * Singleton instance
     * 
     * @var WCAG_WP_Menu
     */
    private static $instance = null;
    
    /**
     * Post type name
     * 
     * @var string
     */
    private $post_type = 'wcag_menu';
    
    /**
     * Get singleton instance
     * 
     * @return WCAG_WP_Menu
     */
    public static function get_instance(): WCAG_WP_Menu {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     * 
     * @return void
     */
    private function init_hooks(): void {
        // Register post type
        if (did_action('init')) {
            $this->register_post_type();
        } else {
            add_action('init', [$this, 'register_post_type']);
        }
        
        // Admin hooks
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
        add_filter('manage_' . $this->post_type . '_posts_columns', [$this, 'add_admin_columns']);
        add_action('manage_' . $this->post_type . '_posts_custom_column', [$this, 'render_admin_columns'], 10, 2);
        
        // Frontend hooks
        add_shortcode('wcag-menu', [$this, 'render_shortcode']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // Admin hooks
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }
    
    /**
     * Register custom post type
     * 
     * @return void
     */
    public function register_post_type(): void {
        $labels = [
            'name' => __('Menu WCAG', 'wcag-wp'),
            'singular_name' => __('Menu WCAG', 'wcag-wp'),
            'menu_name' => __('Menu WCAG', 'wcag-wp'),
            'add_new' => __('Aggiungi Nuovo', 'wcag-wp'),
            'add_new_item' => __('Aggiungi Nuovo Menu', 'wcag-wp'),
            'edit_item' => __('Modifica Menu', 'wcag-wp'),
            'new_item' => __('Nuovo Menu', 'wcag-wp'),
            'view_item' => __('Visualizza Menu', 'wcag-wp'),
            'search_items' => __('Cerca Menu', 'wcag-wp'),
            'not_found' => __('Nessun menu trovato', 'wcag-wp'),
            'not_found_in_trash' => __('Nessun menu nel cestino', 'wcag-wp')
        ];
        
        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'wcag-wp-main',
            'menu_position' => 35,
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports' => ['title'],
            'has_archive' => false,
            'rewrite' => false,
            'query_var' => false
        ];
        
        register_post_type($this->post_type, $args);
    }
    
    /**
     * Add meta boxes
     * 
     * @return void
     */
    public function add_meta_boxes(): void {
        add_meta_box(
            'wcag_menu_config',
            __('Configurazione Menu', 'wcag-wp'),
            [$this, 'render_config_meta_box'],
            $this->post_type,
            'normal',
            'high'
        );
        
        add_meta_box(
            'wcag_menu_preview',
            __('Anteprima Menu', 'wcag-wp'),
            [$this, 'render_preview_meta_box'],
            $this->post_type,
            'side',
            'default'
        );
        
        add_meta_box(
            'wcag_menu_accessibility',
            __('Accessibilità', 'wcag-wp'),
            [$this, 'render_accessibility_meta_box'],
            $this->post_type,
            'side',
            'low'
        );
    }
    
    /**
     * Render configuration meta box
     * 
     * @param WP_Post $post
     * @return void
     */
    public function render_config_meta_box($post): void {
        wp_nonce_field('wcag_menu_meta_box', 'wcag_menu_meta_box_nonce');
        
        $config = get_post_meta($post->ID, '_wcag_menu_config', true);
        $defaults = [
            'type' => 'menubar',
            'orientation' => 'horizontal',
            'items' => [],
            'show_icons' => true,
            'show_labels' => true,
            'custom_class' => '',
            'aria_label' => '',
            'keyboard_navigation' => true,
            'auto_close' => true,
            'close_delay' => 300
        ];
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/menu-config-meta-box.php';
    }
    
    /**
     * Render preview meta box
     * 
     * @param WP_Post $post
     * @return void
     */
    public function render_preview_meta_box($post): void {
        $config = get_post_meta($post->ID, '_wcag_menu_config', true);
        $defaults = [
            'type' => 'menubar',
            'orientation' => 'horizontal',
            'items' => [],
            'show_icons' => true,
            'show_labels' => true,
            'custom_class' => '',
            'aria_label' => '',
            'keyboard_navigation' => true,
            'auto_close' => true,
            'close_delay' => 300
        ];
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/menu-preview-meta-box.php';
    }
    
    /**
     * Render accessibility meta box
     * 
     * @param WP_Post $post
     * @return void
     */
    public function render_accessibility_meta_box($post): void {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/menu-accessibility-meta-box.php';
    }
    
    /**
     * Save meta boxes
     * 
     * @param int $post_id
     * @return void
     */
    public function save_meta_boxes($post_id): void {
        // Verify nonce
        if (!isset($_POST['wcag_menu_meta_box_nonce']) || 
            !wp_verify_nonce($_POST['wcag_menu_meta_box_nonce'], 'wcag_menu_meta_box')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Check post type
        if (get_post_type($post_id) !== $this->post_type) {
            return;
        }
        
        // Save configuration
        if (isset($_POST['wcag_menu_config'])) {
            $config = $_POST['wcag_menu_config'];
            
            // Sanitize configuration
            $sanitized_config = [
                'type' => sanitize_text_field($config['type'] ?? 'menubar'),
                'orientation' => sanitize_text_field($config['orientation'] ?? 'horizontal'),
                'items' => $this->sanitize_menu_items($config['items'] ?? []),
                'show_icons' => filter_var($config['show_icons'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'show_labels' => filter_var($config['show_labels'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'custom_class' => sanitize_html_class($config['custom_class'] ?? ''),
                'aria_label' => sanitize_text_field($config['aria_label'] ?? ''),
                'keyboard_navigation' => filter_var($config['keyboard_navigation'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'auto_close' => filter_var($config['auto_close'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'close_delay' => absint($config['close_delay'] ?? 300)
            ];
            
            update_post_meta($post_id, '_wcag_menu_config', $sanitized_config);
        }
    }
    
    /**
     * Sanitize menu items
     * 
     * @param array $items
     * @return array
     */
    private function sanitize_menu_items($items): array {
        if (!is_array($items)) {
            return [];
        }
        
        $sanitized = [];
        
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            
            $sanitized_item = [
                'label' => sanitize_text_field($item['label'] ?? ''),
                'url' => esc_url_raw($item['url'] ?? ''),
                'icon' => sanitize_text_field($item['icon'] ?? ''),
                'target' => sanitize_text_field($item['target'] ?? '_self'),
                'disabled' => filter_var($item['disabled'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'submenu' => []
            ];
            
            // Sanitize submenu items recursively
            if (isset($item['submenu']) && is_array($item['submenu'])) {
                $sanitized_item['submenu'] = $this->sanitize_menu_items($item['submenu']);
            }
            
            $sanitized[] = $sanitized_item;
        }
        
        return $sanitized;
    }
    
    /**
     * Add admin columns
     * 
     * @param array $columns
     * @return array
     */
    public function add_admin_columns($columns): array {
        $new_columns = [];
        
        foreach ($columns as $key => $title) {
            $new_columns[$key] = $title;
            
            if ($key === 'title') {
                $new_columns['menu_type'] = __('Tipo', 'wcag-wp');
                $new_columns['menu_items'] = __('Elementi', 'wcag-wp');
                $new_columns['shortcode'] = __('Shortcode', 'wcag-wp');
            }
        }
        
        return $new_columns;
    }
    
    /**
     * Render admin columns
     * 
     * @param string $column
     * @param int $post_id
     * @return void
     */
    public function render_admin_columns($column, $post_id): void {
        $config = get_post_meta($post_id, '_wcag_menu_config', true);
        
        switch ($column) {
            case 'menu_type':
                echo esc_html(ucfirst($config['type'] ?? 'menubar'));
                break;
                
            case 'menu_items':
                $items_count = count($config['items'] ?? []);
                echo esc_html(sprintf(_n('%d elemento', '%d elementi', $items_count, 'wcag-wp'), $items_count));
                break;
                
            case 'shortcode':
                echo '<code>[wcag-menu id="' . esc_attr($post_id) . '"]</code>';
                echo '<button type="button" class="button button-small wcag-copy-shortcode" data-shortcode="[wcag-menu id=&quot;' . esc_attr($post_id) . '&quot;]" style="margin-left: 8px;">' . __('Copia', 'wcag-wp') . '</button>';
                break;
        }
    }
    
    /**
     * Render shortcode
     * 
     * @param array $atts
     * @return string
     */
    public function render_shortcode($atts): string {
        $atts = shortcode_atts([
            'id' => 0,
            'type' => 'menubar',
            'orientation' => 'horizontal',
            'items' => '',
            'show_icons' => true,
            'show_labels' => true,
            'custom_class' => '',
            'aria_label' => ''
        ], $atts);
        
        // Get configuration from post if ID provided
        if ($atts['id']) {
            $post = get_post($atts['id']);
            if ($post && $post->post_type === $this->post_type) {
                $config = get_post_meta($atts['id'], '_wcag_menu_config', true);
                if ($config) {
                    $atts = array_merge($atts, $config);
                }
            }
        }
        
        // Parse items if provided as JSON string
        if (is_string($atts['items'])) {
            $items = json_decode($atts['items'], true);
            $atts['items'] = is_array($items) ? $items : [];
        }
        
        // Generate unique ID
        $unique_id = 'wcag-menu-' . uniqid();
        
        // Prepare configuration for template
        $config = [
            'id' => $unique_id,
            'type' => $atts['type'],
            'orientation' => $atts['orientation'],
            'items' => $atts['items'],
            'show_icons' => filter_var($atts['show_icons'], FILTER_VALIDATE_BOOLEAN),
            'show_labels' => filter_var($atts['show_labels'], FILTER_VALIDATE_BOOLEAN),
            'custom_class' => $atts['custom_class'],
            'aria_label' => $atts['aria_label'],
            'keyboard_navigation' => true,
            'auto_close' => true,
            'close_delay' => 300
        ];
        
        // Start output buffering
        ob_start();
        
        // Include template
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-menu.php';
        
        return ob_get_clean();
    }
    
    /**
     * Enqueue admin assets
     * 
     * @param string $hook
     * @return void
     */
    public function enqueue_admin_assets($hook): void {
        // Only load on menu post type pages
        if (!in_array($hook, ['post.php', 'post-new.php'])) {
            return;
        }
        
        global $post_type;
        if ($post_type !== $this->post_type) {
            return;
        }
        
        wp_enqueue_script(
            'wcag-wp-menu-admin',
            WCAG_WP_PLUGIN_URL . 'assets/js/menu-admin.js',
            ['jquery', 'jquery-ui-sortable'],
            WCAG_WP_VERSION,
            true
        );
        
        wp_enqueue_style(
            'wcag-wp-menu-admin',
            WCAG_WP_PLUGIN_URL . 'assets/css/menu-admin.css',
            ['wp-admin'],
            WCAG_WP_VERSION
        );
    }
    
    /**
     * Enqueue assets
     * 
     * @return void
     */
    public function enqueue_assets(): void {
        global $post;
        
        // Check if shortcode is used in current post
        $load_assets = false;
        
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'wcag-menu')) {
            $load_assets = true;
        }
        
        // Check if we're on a menu post type page
        if (is_singular($this->post_type)) {
            $load_assets = true;
        }
        
        if ($load_assets) {
            wp_enqueue_style(
                'wcag-wp-menu-frontend',
                WCAG_WP_PLUGIN_URL . 'assets/css/menu-frontend.css',
                ['wcag-wp-frontend'],
                WCAG_WP_VERSION
            );
            
            wp_enqueue_script(
                'wcag-wp-menu-frontend',
                WCAG_WP_PLUGIN_URL . 'assets/js/menu-frontend.js',
                ['jquery', 'wcag-wp-frontend'],
                WCAG_WP_VERSION,
                true
            );
        }
    }
}

// Initialize component
WCAG_WP_Menu::get_instance();
