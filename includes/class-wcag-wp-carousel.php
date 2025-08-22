<?php
declare(strict_types=1);

/**
 * WCAG Carousel/Slider Component
 *
 * Gestisce il componente Carousel accessibile (WCAG 2.1 AA):
 * - Custom Post Type di gestione
 * - Meta boxes di configurazione e definizione slide
 * - Shortcode e rendering frontend con ARIA Carousel pattern
 *
 * @package WCAG_WP
 * @since 1.1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WCAG_WP_Carousel Class
 */
class WCAG_WP_Carousel {
    /** Meta key configurazione */
    const META_CONFIG = '_wcag_wp_carousel_config';
    /** Meta key slide */
    const META_SLIDES = '_wcag_wp_carousel_slides';

    public function __construct() {
        $this->init();
    }

    /** Initialize component */
    public function init(): void {
        // Registrazione CPT immediata
        $this->register_post_type();

        // Admin hooks
        if (is_admin()) {
            add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
            add_action('save_post', [$this, 'save_meta']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        }

        // Shortcode
        add_shortcode('wcag-carousel', [$this, 'shortcode_carousel']);
    }

    /** Register Custom Post Type */
    public function register_post_type(): void {
        $labels = [
            'name' => __('Carousel', 'wcag-wp'),
            'singular_name' => __('Carousel', 'wcag-wp'),
            'menu_name' => __('Carousel', 'wcag-wp'),
            'name_admin_bar' => __('Carousel', 'wcag-wp'),
            'add_new' => __('Aggiungi Nuovo', 'wcag-wp'),
            'add_new_item' => __('Aggiungi Nuovo Carousel', 'wcag-wp'),
            'new_item' => __('Nuovo Carousel', 'wcag-wp'),
            'edit_item' => __('Modifica Carousel', 'wcag-wp'),
            'view_item' => __('Visualizza Carousel', 'wcag-wp'),
            'all_items' => __('Tutti i Carousel', 'wcag-wp'),
            'search_items' => __('Cerca Carousel', 'wcag-wp'),
            'not_found' => __('Nessun carousel trovato.', 'wcag-wp'),
            'not_found_in_trash' => __('Nessun carousel nel cestino.', 'wcag-wp'),
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
            'menu_icon' => 'dashicons-slides',
        ];

        register_post_type('wcag_carousel', $args);
    }

    /** Add meta boxes */
    public function add_meta_boxes(): void {
        add_meta_box(
            'wcag-wp-carousel-config',
            __('Configurazione Carousel', 'wcag-wp'),
            [$this, 'render_config_meta_box'],
            'wcag_carousel',
            'normal',
            'high'
        );

        add_meta_box(
            'wcag-wp-carousel-slides',
            __('Gestione Slide', 'wcag-wp'),
            [$this, 'render_slides_meta_box'],
            'wcag_carousel',
            'normal',
            'high'
        );

        add_meta_box(
            'wcag-wp-carousel-preview',
            __('Anteprima & Shortcode', 'wcag-wp'),
            [$this, 'render_preview_meta_box'],
            'wcag_carousel',
            'side',
            'default'
        );
    }

    /** Render config meta box */
    public function render_config_meta_box(WP_Post $post): void {
        wp_nonce_field('wcag_wp_carousel_meta_nonce', 'wcag_wp_carousel_meta_nonce');
        
        $config = get_post_meta($post->ID, self::META_CONFIG, true);
        $config = wp_parse_args($config, [
            'autoplay' => false,
            'autoplay_speed' => 5000,
            'pause_on_hover' => true,
            'show_indicators' => true,
            'show_controls' => true,
            'keyboard_navigation' => true,
            'touch_swipe' => true,
            'infinite_loop' => true,
            'animation_type' => 'slide',
            'custom_css_class' => '',
        ]);

        include WCAG_WP_PLUGIN_DIR . 'templates/admin/carousel-config-meta-box.php';
    }

    /** Render slides meta box */
    public function render_slides_meta_box(WP_Post $post): void {
        $slides = get_post_meta($post->ID, self::META_SLIDES, true);
        if (!is_array($slides)) {
            $slides = [];
        }

        include WCAG_WP_PLUGIN_DIR . 'templates/admin/carousel-slides-meta-box.php';
    }

    /** Render preview meta box */
    public function render_preview_meta_box(WP_Post $post): void {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/carousel-preview-meta-box.php';
    }

    /** Save meta on post save */
    public function save_meta(int $post_id): void {
        if (!isset($_POST['wcag_wp_carousel_meta_nonce']) || !wp_verify_nonce($_POST['wcag_wp_carousel_meta_nonce'], 'wcag_wp_carousel_meta_nonce')) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) { return; }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
        if (get_post_type($post_id) !== 'wcag_carousel') { return; }

        if (isset($_POST['wcag_wp_carousel_config'])) {
            $config = $this->sanitize_config($_POST['wcag_wp_carousel_config']);
            update_post_meta($post_id, self::META_CONFIG, $config);
        }

        if (isset($_POST['wcag_wp_carousel_slides'])) {
            $slides = $this->sanitize_slides($_POST['wcag_wp_carousel_slides']);
            update_post_meta($post_id, self::META_SLIDES, $slides);
        }
    }

    /** Sanitize configuration */
    private function sanitize_config(array $config): array {
        return [
            'autoplay' => (bool)($config['autoplay'] ?? false),
            'autoplay_speed' => absint($config['autoplay_speed'] ?? 5000),
            'pause_on_hover' => (bool)($config['pause_on_hover'] ?? true),
            'show_indicators' => (bool)($config['show_indicators'] ?? true),
            'show_controls' => (bool)($config['show_controls'] ?? true),
            'keyboard_navigation' => (bool)($config['keyboard_navigation'] ?? true),
            'touch_swipe' => (bool)($config['touch_swipe'] ?? true),
            'infinite_loop' => (bool)($config['infinite_loop'] ?? true),
            'animation_type' => sanitize_text_field($config['animation_type'] ?? 'slide'),
            'custom_css_class' => sanitize_text_field($config['custom_css_class'] ?? ''),
        ];
    }

    /** Sanitize slides */
    private function sanitize_slides(array $slides): array {
        $sanitized = [];
        foreach ($slides as $index => $slide) {
            if (empty($slide['title']) && empty($slide['content']) && empty($slide['image'])) {
                continue; // Skip empty slides
            }
            
            $sanitized[] = [
                'order' => absint($index),
                'title' => sanitize_text_field($slide['title'] ?? ''),
                'content' => wp_kses_post($slide['content'] ?? ''),
                'image' => esc_url_raw($slide['image'] ?? ''),
                'image_alt' => sanitize_text_field($slide['image_alt'] ?? ''),
                'link_url' => esc_url_raw($slide['link_url'] ?? ''),
                'link_text' => sanitize_text_field($slide['link_text'] ?? ''),
            ];
        }
        
        // Sort by order
        usort($sanitized, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });
        
        return $sanitized;
    }

    /** Enqueue admin assets */
    public function enqueue_admin_assets(string $hook): void {
        if (!in_array($hook, ['post.php', 'post-new.php', 'edit.php'])) { return; }
        global $post_type; if ($post_type !== 'wcag_carousel') { return; }

        // Enqueue WordPress media uploader
        wp_enqueue_media();

        wp_enqueue_style(
            'wcag-wp-carousel-admin',
            WCAG_WP_ASSETS_URL . 'css/carousel-admin.css',
            [],
            WCAG_WP_VERSION
        );

        wp_enqueue_script(
            'wcag-wp-carousel-admin',
            WCAG_WP_ASSETS_URL . 'js/carousel-admin.js',
            ['jquery', 'jquery-ui-sortable'],
            WCAG_WP_VERSION,
            true
        );

        wp_localize_script('wcag-wp-carousel-admin', 'wcag_wp_carousel', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wcag_wp_carousel_nonce'),
            'strings' => [
                'add_slide' => __('Aggiungi Slide', 'wcag-wp'),
                'confirm_delete_slide' => __('Eliminare questa slide?', 'wcag-wp'),
                'select_image' => __('Seleziona Immagine', 'wcag-wp'),
                'remove_image' => __('Rimuovi Immagine', 'wcag-wp'),
            ]
        ]);
    }

    /** Shortcode handler */
    public function shortcode_carousel(array $atts): string {
        $atts = shortcode_atts([
            'id' => 0,
            'class' => '',
        ], $atts, 'wcag-carousel');

        $post_id = absint($atts['id']);
        if (!$post_id) {
            return '<div class="wcag-wp-error">' . __('ID Carousel non specificato', 'wcag-wp') . '</div>';
        }

        return $this->render_carousel($post_id, $atts);
    }

    /** Render frontend HTML */
    public function render_carousel(int $post_id, array $options = []): string {
        $post = get_post($post_id);
        if (!$post || $post->post_type !== 'wcag_carousel') {
            return '<div class="wcag-wp-error">' . __('Carousel non trovato', 'wcag-wp') . '</div>';
        }

        $config = get_post_meta($post_id, self::META_CONFIG, true);
        $slides = get_post_meta($post_id, self::META_SLIDES, true);
        if (!is_array($slides) || empty($slides)) {
            return '<div class="wcag-wp-error">' . __('Nessuna slide definita per questo Carousel', 'wcag-wp') . '</div>';
        }

        $config = wp_parse_args($options, $config);

        // Enqueue frontend assets when shortcode is used
        add_action('wp_enqueue_scripts', function() {
            wp_enqueue_style('wcag-wp-frontend');
            wp_enqueue_script(
                'wcag-wp-carousel-frontend',
                WCAG_WP_ASSETS_URL . 'js/carousel-frontend.js',
                [],
                WCAG_WP_VERSION,
                true
            );
        });

        ob_start();
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-carousel.php';
        return ob_get_clean();
    }
}
