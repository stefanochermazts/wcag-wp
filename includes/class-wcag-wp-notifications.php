<?php
declare(strict_types=1);

/**
 * WCAG Notifications Component
 * 
 * Gestisce notifiche e alert accessibili WCAG 2.1 AA compliant
 * con aria-live regions e styling riconoscibile
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WCAG Notifications Class
 * 
 * Implementa sistema completo di notifiche accessibili:
 * - Custom Post Type per gestione notifiche
 * - Varianti: success, warning, error, info
 * - ARIA live regions per annunci screen reader
 * - Styling accessibile e riconoscibile
 * - Shortcode per inserimento in pagine/post
 */
class WCAG_WP_Notifications {
    
    /**
     * Post type name
     */
    const POST_TYPE = 'wcag_notification';
    
    /**
     * Notification types
     */
    const TYPES = [
        'success' => [
            'label' => 'Successo',
            'icon' => '✅',
            'aria_live' => 'polite',
            'color' => '#00a32a'
        ],
        'info' => [
            'label' => 'Informazione',
            'icon' => 'ℹ️',
            'aria_live' => 'polite',
            'color' => '#2271b1'
        ],
        'warning' => [
            'label' => 'Attenzione',
            'icon' => '⚠️',
            'aria_live' => 'assertive',
            'color' => '#dba617'
        ],
        'error' => [
            'label' => 'Errore',
            'icon' => '❌',
            'aria_live' => 'assertive',
            'color' => '#d63638'
        ]
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
            add_action('save_post', [$this, 'save_notification_meta']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
            
            // Admin columns
            add_filter('manage_' . self::POST_TYPE . '_posts_columns', [$this, 'admin_columns']);
            add_action('manage_' . self::POST_TYPE . '_posts_custom_column', [$this, 'admin_column_content'], 10, 2);
        }
        
        // Frontend hooks
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_shortcode('wcag-notification', [$this, 'shortcode_notification']);
        
        // AJAX hooks for dynamic notifications
        add_action('wp_ajax_wcag_show_notification', [$this, 'ajax_show_notification']);
        add_action('wp_ajax_nopriv_wcag_show_notification', [$this, 'ajax_show_notification']);
    }
    
    /**
     * Register Custom Post Type
     * 
     * @return void
     */
    public function register_post_type(): void {
        $labels = [
            'name' => __('WCAG Notifiche', 'wcag-wp'),
            'singular_name' => __('Notifica WCAG', 'wcag-wp'),
            'menu_name' => __('Notifiche WCAG', 'wcag-wp'),
            'add_new' => __('Aggiungi Notifica', 'wcag-wp'),
            'add_new_item' => __('Aggiungi Nuova Notifica', 'wcag-wp'),
            'edit_item' => __('Modifica Notifica', 'wcag-wp'),
            'new_item' => __('Nuova Notifica', 'wcag-wp'),
            'view_item' => __('Visualizza Notifica', 'wcag-wp'),
            'search_items' => __('Cerca Notifiche', 'wcag-wp'),
            'not_found' => __('Nessuna notifica trovata', 'wcag-wp'),
            'not_found_in_trash' => __('Nessuna notifica nel cestino', 'wcag-wp'),
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
            'supports' => ['title', 'editor'],
            'menu_icon' => 'dashicons-bell',
            'menu_position' => 30,
            'rewrite' => false,
            'query_var' => false,
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
            'wcag_notification_config',
            __('Configurazione Notifica WCAG', 'wcag-wp'),
            [$this, 'render_config_meta_box'],
            self::POST_TYPE,
            'side',
            'high'
        );
        
        // Preview meta box
        add_meta_box(
            'wcag_notification_preview',
            __('Anteprima & Shortcode', 'wcag-wp'),
            [$this, 'render_preview_meta_box'],
            self::POST_TYPE,
            'side',
            'default'
        );
        
        // Accessibility info meta box
        add_meta_box(
            'wcag_notification_accessibility',
            __('Informazioni Accessibilità', 'wcag-wp'),
            [$this, 'render_accessibility_meta_box'],
            self::POST_TYPE,
            'normal',
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
        wp_nonce_field('wcag_notification_meta', 'wcag_notification_nonce');
        
        $config = get_post_meta($post->ID, '_wcag_notification_config', true);
        $config = wp_parse_args($config, [
            'type' => 'info',
            'dismissible' => true,
            'auto_dismiss' => false,
            'auto_dismiss_delay' => 5000,
            'show_icon' => true,
            'custom_class' => '',
            'position' => 'top'
        ]);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/notification-config-meta-box.php';
    }
    
    /**
     * Render preview meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_preview_meta_box(WP_Post $post): void {
        $shortcode = '[wcag-notification id="' . $post->ID . '"]';
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/notification-preview-meta-box.php';
    }
    
    /**
     * Render accessibility meta box
     * 
     * @param WP_Post $post Current post object
     * @return void
     */
    public function render_accessibility_meta_box(WP_Post $post): void {
        $config = get_post_meta($post->ID, '_wcag_notification_config', true);
        $type = $config['type'] ?? 'info';
        $type_info = self::TYPES[$type] ?? self::TYPES['info'];
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/notification-accessibility-meta-box.php';
    }
    
    /**
     * Save notification meta data
     * 
     * @param int $post_id Post ID
     * @return void
     */
    public function save_notification_meta(int $post_id): void {
        // Verify nonce
        if (!isset($_POST['wcag_notification_nonce']) || 
            !wp_verify_nonce($_POST['wcag_notification_nonce'], 'wcag_notification_meta')) {
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
        if (isset($_POST['wcag_notification_config'])) {
            $config = $_POST['wcag_notification_config'];
            
            // Sanitize configuration
            $sanitized_config = [
                'type' => sanitize_text_field($config['type'] ?? 'info'),
                'dismissible' => (bool)($config['dismissible'] ?? true),
                'auto_dismiss' => (bool)($config['auto_dismiss'] ?? false),
                'auto_dismiss_delay' => absint($config['auto_dismiss_delay'] ?? 5000),
                'show_icon' => (bool)($config['show_icon'] ?? true),
                'custom_class' => sanitize_html_class($config['custom_class'] ?? ''),
                'position' => sanitize_text_field($config['position'] ?? 'top')
            ];
            
            // Validate type
            if (!array_key_exists($sanitized_config['type'], self::TYPES)) {
                $sanitized_config['type'] = 'info';
            }
            
            // Validate position
            if (!in_array($sanitized_config['position'], ['top', 'bottom', 'inline'], true)) {
                $sanitized_config['position'] = 'top';
            }
            
            // Validate auto dismiss delay (min 1 second, max 30 seconds)
            if ($sanitized_config['auto_dismiss_delay'] < 1000) {
                $sanitized_config['auto_dismiss_delay'] = 1000;
            } elseif ($sanitized_config['auto_dismiss_delay'] > 30000) {
                $sanitized_config['auto_dismiss_delay'] = 30000;
            }
            
            update_post_meta($post_id, '_wcag_notification_config', $sanitized_config);
        }
    }
    
    /**
     * Admin columns for notifications list
     * 
     * @param array $columns Existing columns
     * @return array Modified columns
     */
    public function admin_columns(array $columns): array {
        $new_columns = [];
        
        foreach ($columns as $key => $title) {
            $new_columns[$key] = $title;
            
            if ($key === 'title') {
                $new_columns['notification_type'] = __('Tipo', 'wcag-wp');
                $new_columns['notification_config'] = __('Configurazione', 'wcag-wp');
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
            case 'notification_type':
                $config = get_post_meta($post_id, '_wcag_notification_config', true);
                $type = $config['type'] ?? 'info';
                $type_info = self::TYPES[$type] ?? self::TYPES['info'];
                
                echo '<span class="wcag-notification-type wcag-notification-type--' . esc_attr($type) . '">';
                echo esc_html($type_info['icon']) . ' ' . esc_html($type_info['label']);
                echo '</span>';
                break;
                
            case 'notification_config':
                $config = get_post_meta($post_id, '_wcag_notification_config', true);
                $features = [];
                
                if ($config['dismissible'] ?? true) {
                    $features[] = 'Chiudibile';
                }
                if ($config['auto_dismiss'] ?? false) {
                    $delay = ($config['auto_dismiss_delay'] ?? 5000) / 1000;
                    $features[] = "Auto-chiusura ({$delay}s)";
                }
                if ($config['show_icon'] ?? true) {
                    $features[] = 'Con icona';
                }
                
                echo esc_html(implode(', ', $features));
                break;
                
            case 'shortcode':
                echo '<code>[wcag-notification id="' . esc_attr($post_id) . '"]</code>';
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
            'wcag-wp-notifications-admin',
            WCAG_WP_ASSETS_URL . 'css/notifications-admin.css',
            [],
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-notifications-admin',
            WCAG_WP_ASSETS_URL . 'js/notifications-admin.js',
            ['jquery'],
            WCAG_WP_VERSION,
            true
        );
        
        wp_localize_script('wcag-wp-notifications-admin', 'wcagNotificationsAdmin', [
            'types' => self::TYPES,
            'nonce' => wp_create_nonce('wcag_notifications_admin'),
            'strings' => [
                'preview_title' => __('Anteprima Notifica', 'wcag-wp'),
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
        // Only enqueue if shortcode is used or dynamic notifications are enabled
        global $post;
        
        $should_enqueue = false;
        
        // Check if shortcode is used in current post
        if ($post && has_shortcode($post->post_content, 'wcag-notification')) {
            $should_enqueue = true;
        }
        
        // Check if dynamic notifications are enabled (future feature)
        if (get_option('wcag_wp_dynamic_notifications', false)) {
            $should_enqueue = true;
        }
        
        if (!$should_enqueue) {
            return;
        }
        
        wp_enqueue_style(
            'wcag-wp-notifications',
            WCAG_WP_ASSETS_URL . 'css/notifications-frontend.css',
            [],
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-notifications',
            WCAG_WP_ASSETS_URL . 'js/notifications-frontend.js',
            [],
            WCAG_WP_VERSION,
            true
        );
        
        wp_localize_script('wcag-wp-notifications', 'wcagNotifications', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wcag_notifications_frontend'),
            'strings' => [
                'close' => __('Chiudi notifica', 'wcag-wp'),
                'notification_dismissed' => __('Notifica chiusa', 'wcag-wp')
            ]
        ]);
    }
    
    /**
     * Notification shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function shortcode_notification(array $atts): string {
        $atts = shortcode_atts([
            'id' => 0,
            'type' => '',
            'dismissible' => '',
            'auto_dismiss' => '',
            'show_icon' => '',
            'class' => '',
            'position' => ''
        ], $atts, 'wcag-notification');
        
        $notification_id = absint($atts['id']);
        
        if (!$notification_id) {
            return '<div class="wcag-wp-error">' . 
                   __('ID notifica richiesto per il shortcode [wcag-notification]', 'wcag-wp') . 
                   '</div>';
        }
        
        $notification = get_post($notification_id);
        
        if (!$notification || $notification->post_type !== self::POST_TYPE || $notification->post_status !== 'publish') {
            return '<div class="wcag-wp-error">' . 
                   __('Notifica non trovata o non pubblicata', 'wcag-wp') . 
                   '</div>';
        }
        
        // Get configuration
        $config = get_post_meta($notification_id, '_wcag_notification_config', true);
        $config = wp_parse_args($config, [
            'type' => 'info',
            'dismissible' => true,
            'auto_dismiss' => false,
            'auto_dismiss_delay' => 5000,
            'show_icon' => true,
            'custom_class' => '',
            'position' => 'top'
        ]);
        
        // Override config with shortcode attributes
        if (!empty($atts['type']) && array_key_exists($atts['type'], self::TYPES)) {
            $config['type'] = $atts['type'];
        }
        if ($atts['dismissible'] !== '') {
            $config['dismissible'] = filter_var($atts['dismissible'], FILTER_VALIDATE_BOOLEAN);
        }
        if ($atts['auto_dismiss'] !== '') {
            $config['auto_dismiss'] = filter_var($atts['auto_dismiss'], FILTER_VALIDATE_BOOLEAN);
        }
        if ($atts['show_icon'] !== '') {
            $config['show_icon'] = filter_var($atts['show_icon'], FILTER_VALIDATE_BOOLEAN);
        }
        if (!empty($atts['class'])) {
            $config['custom_class'] = sanitize_html_class($atts['class']);
        }
        if (!empty($atts['position']) && in_array($atts['position'], ['top', 'bottom', 'inline'], true)) {
            $config['position'] = $atts['position'];
        }
        
        // Render notification
        ob_start();
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-notification.php';
        return ob_get_clean();
    }
    
    /**
     * AJAX handler for showing dynamic notifications
     * 
     * @return void
     */
    public function ajax_show_notification(): void {
        check_ajax_referer('wcag_notifications_frontend', 'nonce');
        
        $notification_id = absint($_POST['notification_id'] ?? 0);
        $type = sanitize_text_field($_POST['type'] ?? 'info');
        $message = sanitize_text_field($_POST['message'] ?? '');
        
        if ($notification_id) {
            // Show existing notification
            $shortcode = '[wcag-notification id="' . $notification_id . '"]';
            $output = do_shortcode($shortcode);
        } else {
            // Create dynamic notification
            if (!array_key_exists($type, self::TYPES)) {
                $type = 'info';
            }
            
            $config = [
                'type' => $type,
                'dismissible' => true,
                'auto_dismiss' => true,
                'auto_dismiss_delay' => 5000,
                'show_icon' => true,
                'custom_class' => 'wcag-notification-dynamic',
                'position' => 'top'
            ];
            
            $notification = (object)[
                'ID' => 0,
                'post_title' => '',
                'post_content' => $message
            ];
            
            ob_start();
            include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-notification.php';
            $output = ob_get_clean();
        }
        
        wp_send_json_success([
            'html' => $output
        ]);
    }
    
    /**
     * Get notification types
     * 
     * @return array Notification types
     */
    public static function get_types(): array {
        return self::TYPES;
    }
    
    /**
     * Create dynamic notification (programmatic)
     * 
     * @param string $message Notification message
     * @param string $type Notification type
     * @param array $options Additional options
     * @return string HTML output
     */
    public static function create_dynamic(string $message, string $type = 'info', array $options = []): string {
        if (!array_key_exists($type, self::TYPES)) {
            $type = 'info';
        }
        
        $config = wp_parse_args($options, [
            'type' => $type,
            'dismissible' => true,
            'auto_dismiss' => false,
            'auto_dismiss_delay' => 5000,
            'show_icon' => true,
            'custom_class' => 'wcag-notification-dynamic',
            'position' => 'inline'
        ]);
        
        $notification = (object)[
            'ID' => 0,
            'post_title' => '',
            'post_content' => $message
        ];
        
        ob_start();
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-notification.php';
        return ob_get_clean();
    }
}

// Initialize component
new WCAG_WP_Notifications();

