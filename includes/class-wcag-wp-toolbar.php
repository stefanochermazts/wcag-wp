<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WCAG Toolbar Component
 * 
 * Toolbar accessibile WCAG 2.1 AA compliant per raggruppare controlli
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */
class WCAG_WP_Toolbar {
    
    private static $instance = null;
    private string $post_type = 'wcag_toolbar';
    
    /**
     * Get singleton instance
     * 
     * @return self
     */
    public static function get_instance(): self {
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
        
        // Frontend hooks
        add_shortcode('wcag-toolbar', [$this, 'render_shortcode']);
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
        register_post_type($this->post_type, [
            'labels' => [
                'name' => __('WCAG Toolbar', 'wcag-wp'),
                'singular_name' => __('WCAG Toolbar', 'wcag-wp'),
                'add_new' => __('Aggiungi Toolbar', 'wcag-wp'),
                'add_new_item' => __('Aggiungi Nuova Toolbar', 'wcag-wp'),
                'edit_item' => __('Modifica Toolbar', 'wcag-wp'),
                'new_item' => __('Nuova Toolbar', 'wcag-wp'),
                'view_item' => __('Visualizza Toolbar', 'wcag-wp'),
                'search_items' => __('Cerca Toolbar', 'wcag-wp'),
                'not_found' => __('Nessuna toolbar trovata', 'wcag-wp'),
                'not_found_in_trash' => __('Nessuna toolbar nel cestino', 'wcag-wp')
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'wcag-wp-main',
            'menu_position' => 35,
            'capability_type' => 'post',
            'supports' => ['title'],
            'show_in_rest' => true,
            'has_archive' => false,
            'rewrite' => false,
            'query_var' => false
        ]);
    }
    
    /**
     * Add meta boxes
     * 
     * @return void
     */
    public function add_meta_boxes(): void {
        add_meta_box(
            'wcag_toolbar_config',
            __('Configurazione WCAG Toolbar', 'wcag-wp'),
            [$this, 'render_config_meta_box'],
            $this->post_type,
            'normal',
            'high'
        );
        
        add_meta_box(
            'wcag_toolbar_preview',
            __('Anteprima Toolbar', 'wcag-wp'),
            [$this, 'render_preview_meta_box'],
            $this->post_type,
            'side',
            'default'
        );
        
        add_meta_box(
            'wcag_toolbar_accessibility',
            __('Accessibilità WCAG', 'wcag-wp'),
            [$this, 'render_accessibility_meta_box'],
            $this->post_type,
            'side',
            'default'
        );
    }
    
    /**
     * Render config meta box
     * 
     * @param WP_Post $post
     * @return void
     */
    public function render_config_meta_box($post): void {
        wp_nonce_field('wcag_toolbar_meta_box', 'wcag_toolbar_meta_box_nonce');
        
        $config = get_post_meta($post->ID, '_wcag_toolbar_config', true);
        $defaults = [
            'orientation' => 'horizontal',
            'label' => '',
            'groups' => [],
            'custom_class' => '',
            'aria_label' => ''
        ];
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/toolbar-config-meta-box.php';
    }
    
    /**
     * Render preview meta box
     * 
     * @param WP_Post $post
     * @return void
     */
    public function render_preview_meta_box($post): void {
        $config = get_post_meta($post->ID, '_wcag_toolbar_config', true);
        $defaults = [
            'orientation' => 'horizontal',
            'label' => '',
            'groups' => [],
            'custom_class' => '',
            'aria_label' => ''
        ];
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/toolbar-preview-meta-box.php';
    }
    
    /**
     * Render accessibility meta box
     * 
     * @param WP_Post $post
     * @return void
     */
    public function render_accessibility_meta_box($post): void {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/toolbar-accessibility-meta-box.php';
    }
    
    /**
     * Save meta boxes
     * 
     * @param int $post_id
     * @return void
     */
    public function save_meta_boxes($post_id): void {
        // Verify nonce
        if (!isset($_POST['wcag_toolbar_meta_box_nonce']) || 
            !wp_verify_nonce($_POST['wcag_toolbar_meta_box_nonce'], 'wcag_toolbar_meta_box')) {
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
        if (isset($_POST['wcag_toolbar_config'])) {
            $config = $_POST['wcag_toolbar_config'];
            
            // Sanitize configuration
            $sanitized_config = [
                'orientation' => sanitize_text_field($config['orientation'] ?? 'horizontal'),
                'label' => sanitize_text_field($config['label'] ?? ''),
                'groups' => $this->sanitize_toolbar_groups($config['groups'] ?? []),
                'custom_class' => sanitize_html_class($config['custom_class'] ?? ''),
                'aria_label' => sanitize_text_field($config['aria_label'] ?? '')
            ];
            
            update_post_meta($post_id, '_wcag_toolbar_config', $sanitized_config);
        }
    }
    
    /**
     * Sanitize toolbar groups
     * 
     * @param array $groups
     * @return array
     */
    private function sanitize_toolbar_groups($groups): array {
        if (!is_array($groups)) {
            return [];
        }
        
        $sanitized = [];
        
        foreach ($groups as $group) {
            if (!is_array($group)) {
                continue;
            }
            
            $sanitized_group = [
                'label' => sanitize_text_field($group['label'] ?? ''),
                'controls' => $this->sanitize_toolbar_controls($group['controls'] ?? [])
            ];
            
            $sanitized[] = $sanitized_group;
        }
        
        return $sanitized;
    }
    
    /**
     * Sanitize toolbar controls
     * 
     * @param array $controls
     * @return array
     */
    private function sanitize_toolbar_controls($controls): array {
        if (!is_array($controls)) {
            return [];
        }
        
        $sanitized = [];
        
        foreach ($controls as $control) {
            if (!is_array($control)) {
                continue;
            }
            
            $sanitized_control = [
                'type' => sanitize_text_field($control['type'] ?? 'button'),
                'label' => sanitize_text_field($control['label'] ?? ''),
                'icon' => sanitize_text_field($control['icon'] ?? ''),
                'action' => sanitize_text_field($control['action'] ?? ''),
                'url' => esc_url_raw($control['url'] ?? ''),
                'target' => sanitize_text_field($control['target'] ?? '_self'),
                'disabled' => filter_var($control['disabled'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'separator' => filter_var($control['separator'] ?? false, FILTER_VALIDATE_BOOLEAN)
            ];
            
            $sanitized[] = $sanitized_control;
        }
        
        return $sanitized;
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
            'orientation' => 'horizontal',
            'label' => '',
            'groups' => '',
            'custom_class' => '',
            'aria_label' => ''
        ], $atts);
        
        // Get configuration from post if ID provided
        if ($atts['id']) {
            $post = get_post($atts['id']);
            if ($post && $post->post_type === $this->post_type) {
                $config = get_post_meta($atts['id'], '_wcag_toolbar_config', true);
                if ($config) {
                    $atts = array_merge($atts, $config);
                }
            }
        }
        
        // Parse groups if provided as JSON string
        if (is_string($atts['groups'])) {
            $groups = json_decode($atts['groups'], true);
            $atts['groups'] = is_array($groups) ? $groups : [];
        }
        
        // Generate unique ID
        $unique_id = 'wcag-toolbar-' . uniqid();
        
        // Prepare configuration for template
        $config = [
            'id' => $unique_id,
            'orientation' => $atts['orientation'],
            'label' => $atts['label'],
            'groups' => $atts['groups'],
            'custom_class' => $atts['custom_class'],
            'aria_label' => $atts['aria_label']
        ];
        
        // Start output buffering
        ob_start();
        
        // Include template
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-toolbar.php';
        
        return ob_get_clean();
    }
    
    /**
     * Enqueue admin assets
     * 
     * @param string $hook
     * @return void
     */
    public function enqueue_admin_assets($hook): void {
        // Only load on toolbar post type pages
        if (!in_array($hook, ['post.php', 'post-new.php'])) {
            return;
        }
        
        global $post_type;
        if ($post_type !== $this->post_type) {
            return;
        }
        
        wp_enqueue_script(
            'wcag-wp-toolbar-admin',
            WCAG_WP_PLUGIN_URL . 'assets/js/toolbar-admin.js',
            ['jquery', 'jquery-ui-sortable'],
            WCAG_WP_VERSION,
            true
        );
        
        wp_enqueue_style(
            'wcag-wp-toolbar-admin',
            WCAG_WP_PLUGIN_URL . 'assets/css/toolbar-admin.css',
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
        
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'wcag-toolbar')) {
            $load_assets = true;
        }
        
        // Check if we're on a toolbar post type page
        if (is_singular($this->post_type)) {
            $load_assets = true;
        }
        
        if ($load_assets) {
            wp_enqueue_style(
                'wcag-wp-toolbar-frontend',
                WCAG_WP_PLUGIN_URL . 'assets/css/toolbar-frontend.css',
                ['wcag-wp-frontend'],
                WCAG_WP_VERSION
            );
            
            wp_enqueue_script(
                'wcag-wp-toolbar-frontend',
                WCAG_WP_PLUGIN_URL . 'assets/js/toolbar-frontend.js',
                ['jquery', 'wcag-wp-frontend'],
                WCAG_WP_VERSION,
                true
            );
        }
    }
}

// Initialize component
WCAG_WP_Toolbar::get_instance();
