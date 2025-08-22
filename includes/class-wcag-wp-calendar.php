<?php
declare(strict_types=1);

/**
 * WCAG Calendar/Events Component
 *
 * Gestisce il componente Calendario Eventi accessibile (WCAG 2.1 AA):
 * - Custom Post Type per eventi
 * - Vista calendario accessibile con navigazione tastiera
 * - Meta boxes per configurazione e gestione eventi
 * - Shortcode e rendering frontend con ARIA Calendar pattern
 *
 * @package WCAG_WP
 * @since 1.1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WCAG_WP_Calendar Class
 */
class WCAG_WP_Calendar {
    /** Meta key configurazione */
    const META_CONFIG = '_wcag_wp_calendar_config';
    /** Meta key eventi */
    const META_EVENTS = '_wcag_wp_calendar_events';

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
        add_shortcode('wcag-calendar', [$this, 'shortcode_calendar']);
    }

    /** Register Custom Post Type */
    public function register_post_type(): void {
        $labels = [
            'name' => __('Calendari Eventi', 'wcag-wp'),
            'singular_name' => __('Calendario Eventi', 'wcag-wp'),
            'menu_name' => __('Calendari Eventi', 'wcag-wp'),
            'name_admin_bar' => __('Calendario Eventi', 'wcag-wp'),
            'add_new' => __('Aggiungi Nuovo', 'wcag-wp'),
            'add_new_item' => __('Aggiungi Nuovo Calendario', 'wcag-wp'),
            'new_item' => __('Nuovo Calendario', 'wcag-wp'),
            'edit_item' => __('Modifica Calendario', 'wcag-wp'),
            'view_item' => __('Visualizza Calendario', 'wcag-wp'),
            'all_items' => __('Tutti i Calendari', 'wcag-wp'),
            'search_items' => __('Cerca Calendari', 'wcag-wp'),
            'not_found' => __('Nessun calendario trovato.', 'wcag-wp'),
            'not_found_in_trash' => __('Nessun calendario nel cestino.', 'wcag-wp'),
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
            'menu_icon' => 'dashicons-calendar-alt',
        ];

        register_post_type('wcag_calendar', $args);
    }

    /** Add meta boxes */
    public function add_meta_boxes(): void {
        add_meta_box(
            'wcag-wp-calendar-config',
            __('Configurazione Calendario', 'wcag-wp'),
            [$this, 'render_config_meta_box'],
            'wcag_calendar',
            'normal',
            'high'
        );

        add_meta_box(
            'wcag-wp-calendar-events',
            __('Gestione Eventi', 'wcag-wp'),
            [$this, 'render_events_meta_box'],
            'wcag_calendar',
            'normal',
            'high'
        );

        add_meta_box(
            'wcag-wp-calendar-preview',
            __('Anteprima & Shortcode', 'wcag-wp'),
            [$this, 'render_preview_meta_box'],
            'wcag_calendar',
            'side',
            'default'
        );
    }

    /** Render config meta box */
    public function render_config_meta_box(WP_Post $post): void {
        wp_nonce_field('wcag_wp_calendar_meta_nonce', 'wcag_wp_calendar_meta_nonce');
        
        $config = get_post_meta($post->ID, self::META_CONFIG, true);
        $config = wp_parse_args($config, [
            'view_type' => 'month',
            'start_day' => 'monday',
            'show_week_numbers' => false,
            'show_today_highlight' => true,
            'show_navigation' => true,
            'keyboard_navigation' => true,
            'screen_reader_announcements' => true,
            'event_limit' => 5,
            'show_list_view' => true,
            'custom_css_class' => '',
        ]);

        include WCAG_WP_PLUGIN_DIR . 'templates/admin/calendar-config-meta-box.php';
    }

    /** Render events meta box */
    public function render_events_meta_box(WP_Post $post): void {
        $events = get_post_meta($post->ID, self::META_EVENTS, true);
        if (!is_array($events)) {
            $events = [];
        }

        include WCAG_WP_PLUGIN_DIR . 'templates/admin/calendar-events-meta-box.php';
    }

    /** Render preview meta box */
    public function render_preview_meta_box(WP_Post $post): void {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/calendar-preview-meta-box.php';
    }

    /** Save meta on post save */
    public function save_meta(int $post_id): void {
        if (!isset($_POST['wcag_wp_calendar_meta_nonce']) || !wp_verify_nonce($_POST['wcag_wp_calendar_meta_nonce'], 'wcag_wp_calendar_meta_nonce')) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) { return; }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
        if (get_post_type($post_id) !== 'wcag_calendar') { return; }

        if (isset($_POST['wcag_wp_calendar_config'])) {
            $config = $this->sanitize_config($_POST['wcag_wp_calendar_config']);
            update_post_meta($post_id, self::META_CONFIG, $config);
        }

        if (isset($_POST['wcag_wp_calendar_events'])) {
            $events = $this->sanitize_events($_POST['wcag_wp_calendar_events']);
            update_post_meta($post_id, self::META_EVENTS, $events);
        }
    }

    /** Sanitize configuration */
    private function sanitize_config(array $config): array {
        return [
            'view_type' => sanitize_text_field($config['view_type'] ?? 'month'),
            'start_day' => sanitize_text_field($config['start_day'] ?? 'monday'),
            'show_week_numbers' => (bool)($config['show_week_numbers'] ?? false),
            'show_today_highlight' => (bool)($config['show_today_highlight'] ?? true),
            'show_navigation' => (bool)($config['show_navigation'] ?? true),
            'keyboard_navigation' => (bool)($config['keyboard_navigation'] ?? true),
            'screen_reader_announcements' => (bool)($config['screen_reader_announcements'] ?? true),
            'event_limit' => absint($config['event_limit'] ?? 5),
            'show_list_view' => (bool)($config['show_list_view'] ?? true),
            'custom_css_class' => sanitize_text_field($config['custom_css_class'] ?? ''),
        ];
    }

    /** Sanitize events */
    private function sanitize_events(array $events): array {
        $sanitized = [];
        foreach ($events as $index => $event) {
            if (empty($event['title']) && empty($event['description'])) {
                continue; // Skip empty events
            }
            
            $sanitized[] = [
                'order' => absint($index),
                'title' => sanitize_text_field($event['title'] ?? ''),
                'description' => wp_kses_post($event['description'] ?? ''),
                'start_date' => sanitize_text_field($event['start_date'] ?? ''),
                'end_date' => sanitize_text_field($event['end_date'] ?? ''),
                'start_time' => sanitize_text_field($event['start_time'] ?? ''),
                'end_time' => sanitize_text_field($event['end_time'] ?? ''),
                'all_day' => (bool)($event['all_day'] ?? false),
                'location' => sanitize_text_field($event['location'] ?? ''),
                'category' => sanitize_text_field($event['category'] ?? ''),
                'color' => sanitize_hex_color($event['color'] ?? '#0073aa'),
                'link_url' => esc_url_raw($event['link_url'] ?? ''),
                'link_text' => sanitize_text_field($event['link_text'] ?? ''),
            ];
        }
        
        // Sort by start date
        usort($sanitized, function($a, $b) {
            return strtotime($a['start_date']) <=> strtotime($b['start_date']);
        });
        
        return $sanitized;
    }

    /** Enqueue admin assets */
    public function enqueue_admin_assets(string $hook): void {
        if (!in_array($hook, ['post.php', 'post-new.php', 'edit.php'])) { return; }
        global $post_type; if ($post_type !== 'wcag_calendar') { return; }

        wp_enqueue_style(
            'wcag-wp-calendar-admin',
            WCAG_WP_ASSETS_URL . 'css/calendar-admin.css',
            [],
            WCAG_WP_VERSION
        );

        wp_enqueue_script(
            'wcag-wp-calendar-admin',
            WCAG_WP_ASSETS_URL . 'js/calendar-admin.js',
            ['jquery', 'jquery-ui-sortable', 'jquery-ui-datepicker'],
            WCAG_WP_VERSION,
            true
        );

        wp_localize_script('wcag-wp-calendar-admin', 'wcag_wp_calendar', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wcag_wp_calendar_nonce'),
            'strings' => [
                'add_event' => __('Aggiungi Evento', 'wcag-wp'),
                'confirm_delete_event' => __('Eliminare questo evento?', 'wcag-wp'),
                'select_date' => __('Seleziona Data', 'wcag-wp'),
                'all_day' => __('Tutto il giorno', 'wcag-wp'),
                'start_date' => __('Data inizio', 'wcag-wp'),
                'end_date' => __('Data fine', 'wcag-wp'),
                'start_time' => __('Ora inizio', 'wcag-wp'),
                'end_time' => __('Ora fine', 'wcag-wp'),
            ]
        ]);
    }

    /** Shortcode handler */
    public function shortcode_calendar(array $atts): string {
        $atts = shortcode_atts([
            'id' => 0,
            'class' => '',
            'view' => null,
            'month' => null,
            'year' => null,
        ], $atts, 'wcag-calendar');

        $post_id = absint($atts['id']);
        if (!$post_id) {
            return '<div class="wcag-wp-error">' . __('ID Calendario non specificato', 'wcag-wp') . '</div>';
        }

        return $this->render_calendar($post_id, $atts);
    }

    /** Render frontend HTML */
    public function render_calendar(int $post_id, array $options = []): string {
        $post = get_post($post_id);
        if (!$post || $post->post_type !== 'wcag_calendar') {
            return '<div class="wcag-wp-error">' . __('Calendario non trovato', 'wcag-wp') . '</div>';
        }

        $config = get_post_meta($post_id, self::META_CONFIG, true);
        $events = get_post_meta($post_id, self::META_EVENTS, true);
        if (!is_array($events)) {
            $events = [];
        }

        // Debug logging
        error_log("WCAG Calendar Debug - Post ID: $post_id");
        error_log("WCAG Calendar Debug - Raw config: " . print_r($config, true));
        error_log("WCAG Calendar Debug - Raw events: " . print_r($events, true));
        error_log("WCAG Calendar Debug - Options: " . print_r($options, true));

        // Sanitize config with defaults
        $config = $this->sanitize_config($config);
        $config = wp_parse_args($config, $options);
        
        error_log("WCAG Calendar Debug - Final config: " . print_r($config, true));

        // Enqueue frontend assets when shortcode is used
        add_action('wp_enqueue_scripts', function() {
            wp_enqueue_style('wcag-wp-frontend');
            wp_enqueue_script(
                'wcag-wp-calendar-frontend',
                WCAG_WP_ASSETS_URL . 'js/calendar-frontend.js',
                [],
                WCAG_WP_VERSION,
                true
            );
        });

        ob_start();
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-calendar.php';
        return ob_get_clean();
    }
}
