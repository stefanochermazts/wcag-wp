<?php
declare(strict_types=1);

/**
 * Table of Contents (TOC) Component
 * - Custom Post Type per configurare indici dei contenuti
 * - Shortcode [toc] e [wcag-toc]
 * - Frontend JS per generare/ancorare l'indice scansendo H2–H6
 *
 * NOTA: Etichette e descrizioni senza il termine "WCAG" come da richiesta.
 */

if (!defined('ABSPATH')) { exit; }

class WCAG_WP_TOC {
    const META_CONFIG = '_wcag_wp_toc_config';

    public function __construct() { $this->init(); }

    public function init(): void {
        $this->register_post_type();

        if (is_admin()) {
            add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
            add_action('save_post', [$this, 'save_meta']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        }

        add_shortcode('wcag-toc', [$this, 'shortcode_toc']);
    }

    public function register_post_type(): void {
        $labels = [
            'name' => __('Table of Contents', 'wcag-wp'),
            'singular_name' => __('Table of Contents', 'wcag-wp'),
            'menu_name' => __('Table of Contents', 'wcag-wp'),
            'name_admin_bar' => __('Table of Contents', 'wcag-wp'),
            'add_new' => __('Aggiungi Nuovo', 'wcag-wp'),
            'add_new_item' => __('Aggiungi Nuovo Indice dei contenuti', 'wcag-wp'),
            'new_item' => __('Nuovo Indice dei contenuti', 'wcag-wp'),
            'edit_item' => __('Modifica Indice dei contenuti', 'wcag-wp'),
            'view_item' => __('Visualizza Indice dei contenuti', 'wcag-wp'),
            'all_items' => __('Tutti gli Indici dei contenuti', 'wcag-wp'),
            'search_items' => __('Cerca Indici dei contenuti', 'wcag-wp'),
            'not_found' => __('Nessun indice trovato.', 'wcag-wp'),
            'not_found_in_trash' => __('Nessun indice nel cestino.', 'wcag-wp'),
        ];

        $args = [
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => 'wcag-wp-main',
            'capability_type' => 'post',
            'supports' => ['title'],
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-list-view',
        ];

        register_post_type('wcag_toc', $args);
    }

    public function add_meta_boxes(): void {
        add_meta_box(
            'wcag-wp-toc-config',
            __('Configurazione indice', 'wcag-wp'),
            [$this, 'render_config_meta_box'],
            'wcag_toc',
            'normal',
            'high'
        );

        add_meta_box(
            'wcag-wp-toc-preview',
            __('Anteprima & Shortcode', 'wcag-wp'),
            [$this, 'render_preview_meta_box'],
            'wcag_toc',
            'side',
            'default'
        );
    }

    public function render_config_meta_box(WP_Post $post): void {
        wp_nonce_field('wcag_wp_toc_meta_nonce', 'wcag_wp_toc_meta_nonce');
        $config = get_post_meta($post->ID, self::META_CONFIG, true);
        $config = wp_parse_args($config, [
            'levels' => ['h2','h3','h4'],
            'numbered' => false,
            'collapsible' => false,
            'smooth' => true,
            'container_selector' => 'main',
            'show_title' => true,
            'title_text' => __('Indice dei contenuti', 'wcag-wp'),
        ]);

        include WCAG_WP_PLUGIN_DIR . 'templates/admin/toc-config-meta-box.php';
    }

    public function render_preview_meta_box(WP_Post $post): void {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/toc-preview-meta-box.php';
    }

    public function save_meta(int $post_id): void {
        if (!isset($_POST['wcag_wp_toc_meta_nonce']) || !wp_verify_nonce($_POST['wcag_wp_toc_meta_nonce'], 'wcag_wp_toc_meta_nonce')) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) { return; }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
        if (get_post_type($post_id) !== 'wcag_toc') { return; }

        if (isset($_POST['wcag_wp_toc_config'])) {
            $config = $this->sanitize_config($_POST['wcag_wp_toc_config']);
            update_post_meta($post_id, self::META_CONFIG, $config);
        }
    }

    private function sanitize_config(array $config): array {
        $levels = isset($config['levels']) && is_array($config['levels']) ? array_values(array_intersect($config['levels'], ['h2','h3','h4','h5','h6'])) : ['h2','h3','h4'];
        if (empty($levels)) { $levels = ['h2','h3']; }
        
        // Sanitize container selector
        $container_selector = sanitize_text_field($config['container_selector'] ?? 'main');
        
        return [
            'levels' => $levels,
            'numbered' => (bool)($config['numbered'] ?? false),
            'collapsible' => (bool)($config['collapsible'] ?? false),
            'smooth' => (bool)($config['smooth'] ?? true),
            'container_selector' => $container_selector,
            'show_title' => (bool)($config['show_title'] ?? true),
            'title_text' => sanitize_text_field($config['title_text'] ?? __('Indice dei contenuti', 'wcag-wp')),
        ];
    }

    public function enqueue_admin_assets(string $hook): void {
        if (!in_array($hook, ['post.php', 'post-new.php', 'edit.php'])) { return; }
        global $post_type; if ($post_type !== 'wcag_toc') { return; }
        // Placeholder for future admin assets
    }

    public function shortcode_toc(array $atts): string {
        $atts = shortcode_atts([
            'id' => 0,
            'class' => '',
        ], $atts, 'toc');

        $toc_id = absint($atts['id']);
        if (!$toc_id) {
            return '<nav class="wcag-wp-toc wcag-wp"><p>' . esc_html__('ID indice non specificato', 'wcag-wp') . '</p></nav>';
        }

        $config_raw = get_post_meta($toc_id, self::META_CONFIG, true);
        if (!is_array($config_raw)) {
            return '<nav class="wcag-wp-toc wcag-wp"><p>' . esc_html__('Indice non configurato', 'wcag-wp') . '</p></nav>';
        }

        // Sanitize and set defaults for config
        $config = $this->sanitize_config($config_raw);

        // Enqueue frontend assets
        wp_enqueue_style('wcag-wp-frontend');
        wp_enqueue_script('wcag-wp-toc-frontend', WCAG_WP_ASSETS_URL . 'js/toc-frontend.js', [], WCAG_WP_VERSION, true);

        ob_start();
        $options = [
            'class' => $atts['class'],
        ];
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-toc.php';
        return ob_get_clean();
    }
}


