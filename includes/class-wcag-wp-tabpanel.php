<?php
declare(strict_types=1);

/**
 * WCAG Tab Panel Management Class
 *
 * Gestisce il componente Tab Panel accessibile (WCAG 2.1 AA):
 * - Custom Post Type di gestione
 * - Meta boxes di configurazione e definizione tab/pannelli
 * - Shortcode e rendering frontend con ARIA Tab pattern
 *
 * @package WCAG_WP
 * @since 1.1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WCAG_WP_TabPanel Class
 */
class WCAG_WP_TabPanel {
    /** Meta key configurazione */
    const META_CONFIG = '_wcag_wp_tabpanel_config';
    /** Meta key tab/panels */
    const META_TABS = '_wcag_wp_tabpanel_tabs';

    public function __construct() {
        $this->init();
    }

    /** Initialize component */
    public function init(): void {
        // Registrazione CPT immediata (siamo già su init dal bootstrap principale)
        $this->register_post_type();

        // Admin hooks
        if (is_admin()) {
            add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
            add_action('save_post', [$this, 'save_meta']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        }

        // Shortcode
        add_shortcode('wcag-tabpanel', [$this, 'shortcode_tabpanel']);
    }

    /** Register Custom Post Type */
    public function register_post_type(): void {
        $labels = [
            'name' => __('WCAG Tab Panel', 'wcag-wp'),
            'singular_name' => __('WCAG Tab Panel', 'wcag-wp'),
            'menu_name' => __('WCAG Tab Panel', 'wcag-wp'),
            'name_admin_bar' => __('WCAG Tab Panel', 'wcag-wp'),
            'add_new' => __('Aggiungi Nuovo', 'wcag-wp'),
            'add_new_item' => __('Aggiungi Nuovo Tab Panel', 'wcag-wp'),
            'new_item' => __('Nuovo Tab Panel', 'wcag-wp'),
            'edit_item' => __('Modifica Tab Panel', 'wcag-wp'),
            'view_item' => __('Visualizza Tab Panel', 'wcag-wp'),
            'all_items' => __('Tutti i Tab Panel', 'wcag-wp'),
            'search_items' => __('Cerca Tab Panel', 'wcag-wp'),
            'not_found' => __('Nessun Tab Panel trovato.', 'wcag-wp'),
            'not_found_in_trash' => __('Nessun Tab Panel nel cestino.', 'wcag-wp'),
        ];

        $args = [
            'labels' => $labels,
            'description' => __('Pannelli a tab accessibili (WCAG 2.1 AA)', 'wcag-wp'),
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => 'wcag-wp-main',
            'query_var' => false,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-index-card',
            'supports' => ['title'],
            'show_in_rest' => true,
            'rest_base' => 'wcag-tabpanel',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ];

        register_post_type('wcag_tabpanel', $args);
        wcag_wp_log('Custom Post Type wcag_tabpanel registered', 'info');
    }

    /** Add meta boxes */
    public function add_meta_boxes(): void {
        add_meta_box(
            'wcag-wp-tabpanel-config',
            __('Configurazione WCAG Tab Panel', 'wcag-wp'),
            [$this, 'render_config_meta_box'],
            'wcag_tabpanel',
            'normal',
            'high'
        );

        add_meta_box(
            'wcag-wp-tabpanel-tabs',
            __('Definizione Tab & Pannelli', 'wcag-wp'),
            [$this, 'render_tabs_meta_box'],
            'wcag_tabpanel',
            'normal',
            'high'
        );

        add_meta_box(
            'wcag-wp-tabpanel-preview',
            __('Anteprima & Shortcode', 'wcag-wp'),
            [$this, 'render_preview_meta_box'],
            'wcag_tabpanel',
            'side',
            'default'
        );
    }

    /** Render config meta box */
    public function render_config_meta_box(WP_Post $post): void {
        wp_nonce_field('wcag_wp_tabpanel_meta_nonce', 'wcag_wp_tabpanel_meta_nonce');

        $config = get_post_meta($post->ID, self::META_CONFIG, true);
        $config = wp_parse_args($config, [
            'keyboard_navigation' => true,
            'activate_on_focus' => false,
            'first_tab_selected' => 0,
            'custom_css_class' => ''
        ]);

        include WCAG_WP_PLUGIN_DIR . 'templates/admin/tabpanel-config-meta-box.php';
    }

    /** Render tabs meta box */
    public function render_tabs_meta_box(WP_Post $post): void {
        $tabs = get_post_meta($post->ID, self::META_TABS, true);
        if (!is_array($tabs)) {
            $tabs = [];
        }

        include WCAG_WP_PLUGIN_DIR . 'templates/admin/tabpanel-tabs-meta-box.php';
    }

    /** Render preview meta box */
    public function render_preview_meta_box(WP_Post $post): void {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/tabpanel-preview-meta-box.php';
    }

    /** Save meta on post save */
    public function save_meta(int $post_id): void {
        if (!isset($_POST['wcag_wp_tabpanel_meta_nonce']) ||
            !wp_verify_nonce($_POST['wcag_wp_tabpanel_meta_nonce'], 'wcag_wp_tabpanel_meta_nonce')) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (get_post_type($post_id) !== 'wcag_tabpanel') {
            return;
        }

        if (isset($_POST['wcag_wp_tabpanel_config'])) {
            $config = $this->sanitize_config($_POST['wcag_wp_tabpanel_config']);
            update_post_meta($post_id, self::META_CONFIG, $config);
        }

        if (isset($_POST['wcag_wp_tabpanel_tabs'])) {
            $tabs = $this->sanitize_tabs($_POST['wcag_wp_tabpanel_tabs']);
            update_post_meta($post_id, self::META_TABS, $tabs);
        }
    }

    /** Sanitize configuration */
    private function sanitize_config(array $config): array {
        return [
            'keyboard_navigation' => (bool)($config['keyboard_navigation'] ?? true),
            'activate_on_focus' => (bool)($config['activate_on_focus'] ?? false),
            'first_tab_selected' => absint($config['first_tab_selected'] ?? 0),
            'custom_css_class' => sanitize_html_class($config['custom_css_class'] ?? ''),
        ];
    }

    /** Sanitize tabs list */
    private function sanitize_tabs(array $tabs): array {
        $sanitized = [];

        foreach ($tabs as $tab) {
            if (!is_array($tab)) {
                continue;
            }

            $id = sanitize_key($tab['id'] ?? '');
            $label = sanitize_text_field($tab['label'] ?? '');
            $content = wp_kses_post($tab['content'] ?? '');
            $disabled = (bool)($tab['disabled'] ?? false);
            $order = absint($tab['order'] ?? 0);

            if ($id === '' || $label === '') {
                // ignora placeholder vuoti
                continue;
            }

            $sanitized[] = [
                'id' => $id,
                'label' => $label,
                'content' => $content,
                'disabled' => $disabled,
                'order' => $order,
            ];
        }

        // Reindex e sort per order
        usort($sanitized, function ($a, $b) { return ($a['order'] ?? 0) <=> ($b['order'] ?? 0); });
        return array_values($sanitized);
    }

    /** Enqueue admin assets */
    public function enqueue_admin_assets(string $hook): void {
        if (!in_array($hook, ['post.php', 'post-new.php', 'edit.php'])) {
            return;
        }

        global $post_type, $post;
        $current_post_type = $post_type ?? (isset($post) ? $post->post_type : '');
        if (!$current_post_type && isset($_GET['post_type'])) {
            $current_post_type = sanitize_text_field($_GET['post_type']);
        }
        if (!$current_post_type && isset($_GET['post'])) {
            $post_id = absint($_GET['post']);
            $current_post_type = get_post_type($post_id);
        }
        if ($current_post_type !== 'wcag_tabpanel') {
            return;
        }

        wp_enqueue_style(
            'wcag-wp-tabpanel-admin',
            WCAG_WP_ASSETS_URL . 'css/tabpanel-admin.css',
            [],
            WCAG_WP_VERSION
        );

        wp_enqueue_script(
            'wcag-wp-tabpanel-admin',
            WCAG_WP_ASSETS_URL . 'js/tabpanel-admin.js',
            ['jquery', 'jquery-ui-sortable'],
            WCAG_WP_VERSION,
            true
        );

        wp_localize_script('wcag-wp-tabpanel-admin', 'wcag_wp_tabpanel', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wcag_wp_tabpanel_nonce'),
            'strings' => [
                'add_tab' => __('Aggiungi Tab', 'wcag-wp'),
                'confirm_delete_tab' => __('Eliminare questo tab?', 'wcag-wp'),
            ]
        ]);
    }

    /** Shortcode handler */
    public function shortcode_tabpanel(array $atts): string {
        $atts = shortcode_atts([
            'id' => 0,
            'class' => '',
        ], $atts, 'wcag-tabpanel');

        $post_id = absint($atts['id']);
        if (!$post_id) {
            return '<div class="wcag-wp-error">' . __('ID WCAG Tab Panel non specificato', 'wcag-wp') . '</div>';
        }

        return $this->render_tabpanel($post_id, $atts);
    }

    /** Render frontend HTML */
    public function render_tabpanel(int $post_id, array $options = []): string {
        $post = get_post($post_id);
        if (!$post || $post->post_type !== 'wcag_tabpanel') {
            return '<div class="wcag-wp-error">' . __('WCAG Tab Panel non trovato', 'wcag-wp') . '</div>';
        }

        $config = get_post_meta($post_id, self::META_CONFIG, true);
        $tabs = get_post_meta($post_id, self::META_TABS, true);
        if (!is_array($tabs) || empty($tabs)) {
            return '<div class="wcag-wp-error">' . __('Nessun tab definito per questo WCAG Tab Panel', 'wcag-wp') . '</div>';
        }

        $config = wp_parse_args($options, $config);

        // Enqueue frontend assets when shortcode is used
        add_action('wp_enqueue_scripts', function() {
            wp_enqueue_script(
                'wcag-wp-tabpanel-frontend',
                WCAG_WP_ASSETS_URL . 'js/tabpanel-frontend.js',
                [],
                WCAG_WP_VERSION,
                true
            );
        });

        ob_start();
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-tabpanel.php';
        return ob_get_clean();
    }
}


