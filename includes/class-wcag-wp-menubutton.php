<?php
/**
 * WCAG Menu Button Component
 * 
 * Accessible menu button implementation following WCAG 2.1 AA guidelines
 * Based on WAI-ARIA Authoring Practices Guide (APG) Menu Button pattern
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class WCAG_WP_MenuButton {
    
    /**
     * Singleton instance
     * 
     * @var WCAG_WP_MenuButton
     */
    private static $instance = null;
    
    /**
     * Post type name
     * 
     * @var string
     */
    private $post_type = 'wcag_menubutton';
    
    /**
     * Get singleton instance
     * 
     * @return WCAG_WP_MenuButton
     */
    public static function get_instance(): WCAG_WP_MenuButton {
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
        add_shortcode('wcag-menubutton', [$this, 'render_shortcode']);
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
            'name' => __('Menu Button WCAG', 'wcag-wp'),
            'singular_name' => __('Menu Button WCAG', 'wcag-wp'),
            'menu_name' => __('Menu Button WCAG', 'wcag-wp'),
            'add_new' => __('Aggiungi Nuovo', 'wcag-wp'),
            'add_new_item' => __('Aggiungi Nuovo Menu Button', 'wcag-wp'),
            'edit_item' => __('Modifica Menu Button', 'wcag-wp'),
            'new_item' => __('Nuovo Menu Button', 'wcag-wp'),
            'view_item' => __('Visualizza Menu Button', 'wcag-wp'),
            'search_items' => __('Cerca Menu Button', 'wcag-wp'),
            'not_found' => __('Nessun menu button trovato', 'wcag-wp'),
            'not_found_in_trash' => __('Nessun menu button nel cestino', 'wcag-wp')
        ];
        
        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'wcag-wp-main',
            'menu_position' => 36,
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
            'wcag_menubutton_config',
            __('Configurazione Menu Button', 'wcag-wp'),
            [$this, 'render_config_meta_box'],
            $this->post_type,
            'normal',
            'high'
        );
        
        add_meta_box(
            'wcag_menubutton_preview',
            __('Anteprima Menu Button', 'wcag-wp'),
            [$this, 'render_preview_meta_box'],
            $this->post_type,
            'side',
            'default'
        );
        
        add_meta_box(
            'wcag_menubutton_accessibility',
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
        wp_nonce_field('wcag_menubutton_meta_box', 'wcag_menubutton_meta_box_nonce');
        
        $config = get_post_meta($post->ID, '_wcag_menubutton_config', true);
        $defaults = [
            'button_text' => '',
            'button_icon' => '',
            'menu_items' => [],
            'position' => 'bottom-left',
            'trigger' => 'click',
            'show_arrow' => true,
            'close_on_select' => true,
            'custom_class' => '',
            'aria_label' => '',
            'keyboard_navigation' => true,
            'auto_close' => true,
            'close_delay' => 300
        ];
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/menubutton-config-meta-box.php';
    }
    
    /**
     * Render preview meta box
     * 
     * @param WP_Post $post
     * @return void
     */
    public function render_preview_meta_box($post): void {
        $config = get_post_meta($post->ID, '_wcag_menubutton_config', true);
        $defaults = [
            'button_text' => '',
            'button_icon' => '',
            'menu_items' => [],
            'position' => 'bottom-left',
            'trigger' => 'click',
            'show_arrow' => true,
            'close_on_select' => true,
            'custom_class' => '',
            'aria_label' => '',
            'keyboard_navigation' => true,
            'auto_close' => true,
            'close_delay' => 300
        ];
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/menubutton-preview-meta-box.php';
    }
    
    /**
     * Render accessibility meta box
     * 
     * @param WP_Post $post
     * @return void
     */
    public function render_accessibility_meta_box($post): void {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/menubutton-accessibility-meta-box.php';
    }
    
    /**
     * Save meta boxes
     * 
     * @param int $post_id
     * @return void
     */
    public function save_meta_boxes($post_id): void {
        // Verify nonce
        if (!isset($_POST['wcag_menubutton_meta_box_nonce']) || 
            !wp_verify_nonce($_POST['wcag_menubutton_meta_box_nonce'], 'wcag_menubutton_meta_box')) {
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
        if (isset($_POST['wcag_menubutton_config'])) {
            $config = $_POST['wcag_menubutton_config'];
            
            // Sanitize configuration
            $sanitized_config = [
                'button_text' => sanitize_text_field($config['button_text'] ?? ''),
                'button_icon' => sanitize_text_field($config['button_icon'] ?? ''),
                'menu_items' => $this->sanitize_menu_items($config['menu_items'] ?? []),
                'position' => sanitize_text_field($config['position'] ?? 'bottom-left'),
                'trigger' => sanitize_text_field($config['trigger'] ?? 'click'),
                'show_arrow' => filter_var($config['show_arrow'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'close_on_select' => filter_var($config['close_on_select'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'custom_class' => sanitize_html_class($config['custom_class'] ?? ''),
                'aria_label' => sanitize_text_field($config['aria_label'] ?? ''),
                'keyboard_navigation' => filter_var($config['keyboard_navigation'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'auto_close' => filter_var($config['auto_close'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'close_delay' => absint($config['close_delay'] ?? 300)
            ];
            
            update_post_meta($post_id, '_wcag_menubutton_config', $sanitized_config);
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
                'separator' => filter_var($item['separator'] ?? false, FILTER_VALIDATE_BOOLEAN)
            ];
            
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
                $new_columns['button_text'] = __('Testo Button', 'wcag-wp');
                $new_columns['menu_items'] = __('Elementi Menu', 'wcag-wp');
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
        $config = get_post_meta($post_id, '_wcag_menubutton_config', true);
        
        switch ($column) {
            case 'button_text':
                echo esc_html($config['button_text'] ?? __('Nessun testo', 'wcag-wp'));
                break;
                
            case 'menu_items':
                $items_count = count($config['menu_items'] ?? []);
                echo esc_html(sprintf(_n('%d elemento', '%d elementi', $items_count, 'wcag-wp'), $items_count));
                break;
                
            case 'shortcode':
                echo '<code>[wcag-menubutton id="' . esc_attr($post_id) . '"]</code>';
                echo '<button type="button" class="button button-small wcag-copy-shortcode" data-shortcode="[wcag-menubutton id=&quot;' . esc_attr($post_id) . '&quot;]" style="margin-left: 8px;">' . __('Copia', 'wcag-wp') . '</button>';
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
            'button_text' => '',
            'button_icon' => '',
            'menu_items' => '',
            'position' => 'bottom-left',
            'trigger' => 'click',
            'show_arrow' => true,
            'close_on_select' => true,
            'custom_class' => '',
            'aria_label' => ''
        ], $atts);
        
        // Get configuration from post if ID provided
        if ($atts['id']) {
            $post = get_post($atts['id']);
            if ($post && $post->post_type === $this->post_type) {
                $config = get_post_meta($atts['id'], '_wcag_menubutton_config', true);
                if ($config) {
                    $atts = array_merge($atts, $config);
                }
            }
        }
        
        // Parse menu items if provided as JSON string
        if (is_string($atts['menu_items'])) {
            $items = json_decode($atts['menu_items'], true);
            $atts['menu_items'] = is_array($items) ? $items : [];
        }
        
        // Generate unique ID
        $unique_id = 'wcag-menubutton-' . uniqid();
        
        // Prepare configuration for template
        $config = [
            'id' => $unique_id,
            'button_text' => $atts['button_text'],
            'button_icon' => $atts['button_icon'],
            'menu_items' => $atts['menu_items'],
            'position' => $atts['position'],
            'trigger' => $atts['trigger'],
            'show_arrow' => filter_var($atts['show_arrow'], FILTER_VALIDATE_BOOLEAN),
            'close_on_select' => filter_var($atts['close_on_select'], FILTER_VALIDATE_BOOLEAN),
            'custom_class' => $atts['custom_class'],
            'aria_label' => $atts['aria_label'],
            'keyboard_navigation' => true,
            'auto_close' => true,
            'close_delay' => 300
        ];
        
        // Start output buffering
        ob_start();
        
        // Include template
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-menubutton.php';
        
        return ob_get_clean();
    }
    
    /**
     * Enqueue admin assets
     * 
     * @param string $hook
     * @return void
     */
    public function enqueue_admin_assets($hook): void {
        // Only load on menubutton post type pages
        if (!in_array($hook, ['post.php', 'post-new.php'])) {
            return;
        }
        
        global $post_type;
        if ($post_type !== $this->post_type) {
            return;
        }
        
        wp_enqueue_script(
            'wcag-wp-menubutton-admin',
            WCAG_WP_PLUGIN_URL . 'assets/js/menubutton-admin.js',
            ['jquery', 'jquery-ui-sortable'],
            WCAG_WP_VERSION,
            true
        );
        
        wp_enqueue_style(
            'wcag-wp-menubutton-admin',
            WCAG_WP_PLUGIN_URL . 'assets/css/menubutton-admin.css',
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
        
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'wcag-menubutton')) {
            $load_assets = true;
        }
        
        // Check if we're on a menubutton post type page
        if (is_singular($this->post_type)) {
            $load_assets = true;
        }
        
        if ($load_assets) {
            wp_enqueue_style(
                'wcag-wp-menubutton-frontend',
                WCAG_WP_PLUGIN_URL . 'assets/css/menubutton-frontend.css',
                ['wcag-wp-frontend'],
                WCAG_WP_VERSION
            );
            
            wp_enqueue_script(
                'wcag-wp-menubutton-frontend',
                WCAG_WP_PLUGIN_URL . 'assets/js/menubutton-frontend.js',
                ['jquery', 'wcag-wp-frontend'],
                WCAG_WP_VERSION,
                true
            );
        }
    }
}

// Initialize component
WCAG_WP_MenuButton::get_instance();
