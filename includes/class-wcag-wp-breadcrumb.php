<?php
/**
 * WCAG Breadcrumb Component
 * 
 * Componente di navigazione breadcrumb accessibile WCAG 2.1 AA compliant
 * Integrato con WordPress per generazione automatica breadcrumb da struttura sito
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Classe principale per gestione breadcrumb accessibili
 */
class WCAG_WP_Breadcrumb {

    /**
     * Istanza singleton
     */
    private static ?WCAG_WP_Breadcrumb $instance = null;

    /**
     * Configurazione breadcrumb corrente
     */
    private array $config = [];

    /**
     * Elementi breadcrumb generati
     */
    private array $breadcrumbs = [];

    /**
     * Costruttore privato per singleton
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Ottiene istanza singleton
     */
    public static function get_instance(): WCAG_WP_Breadcrumb {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Inizializza hook WordPress
     */
    private function init_hooks(): void {
        add_action('init', [$this, 'register_post_type']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
        add_action('wp_ajax_wcag_breadcrumb_preview', [$this, 'ajax_preview']);
        add_action('wp_ajax_wcag_breadcrumb_generate', [$this, 'ajax_generate']);
        add_shortcode('wcag-breadcrumb', [$this, 'render_shortcode']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Registra Custom Post Type per breadcrumb
     */
    public function register_post_type(): void {
        $labels = [
            'name'                  => _x('WCAG Breadcrumb', 'post type general name', 'wcag-wp'),
            'singular_name'         => _x('WCAG Breadcrumb', 'post type singular name', 'wcag-wp'),
            'menu_name'             => _x('WCAG Breadcrumb', 'admin menu', 'wcag-wp'),
            'name_admin_bar'        => _x('WCAG Breadcrumb', 'add new on admin bar', 'wcag-wp'),
            'add_new'               => _x('Aggiungi Nuovo', 'wcag breadcrumb', 'wcag-wp'),
            'add_new_item'          => __('Aggiungi Nuovo WCAG Breadcrumb', 'wcag-wp'),
            'new_item'              => __('Nuovo WCAG Breadcrumb', 'wcag-wp'),
            'edit_item'             => __('Modifica WCAG Breadcrumb', 'wcag-wp'),
            'view_item'             => __('Visualizza WCAG Breadcrumb', 'wcag-wp'),
            'all_items'             => __('Tutti i WCAG Breadcrumb', 'wcag-wp'),
            'search_items'          => __('Cerca WCAG Breadcrumb', 'wcag-wp'),
            'parent_item_colon'     => __('WCAG Breadcrumb Padre:', 'wcag-wp'),
            'not_found'             => __('Nessun WCAG Breadcrumb trovato.', 'wcag-wp'),
            'not_found_in_trash'    => __('Nessun WCAG Breadcrumb trovato nel cestino.', 'wcag-wp'),
            'featured_image'        => _x('Immagine in Evidenza WCAG Breadcrumb', 'overrides the "Set as featured image" phrase for this post type', 'wcag-wp'),
            'set_featured_image'    => _x('Imposta immagine in evidenza', 'overrides the "Set as featured image" phrase for this post type', 'wcag-wp'),
            'remove_featured_image' => _x('Rimuovi immagine in evidenza', 'overrides the "Remove featured image" phrase for this post type', 'wcag-wp'),
            'use_featured_image'    => _x('Usa come immagine in evidenza', 'overrides the "Use as featured image" phrase for this post type', 'wcag-wp'),
            'archives'              => _x('Archivio WCAG Breadcrumb', 'the post type archive label used in nav menus', 'wcag-wp'),
            'insert_into_item'      => _x('Inserisci in WCAG Breadcrumb', 'overrides the "Insert into post" phrase (used when inserting media into a post)', 'wcag-wp'),
            'uploaded_to_this_item' => _x('Caricato in questo WCAG Breadcrumb', 'overrides the "Uploaded to this post" phrase (used when viewing media attached to a post)', 'wcag-wp'),
            'filter_items_list'     => _x('Filtra lista WCAG Breadcrumb', 'screen reader text for the filter links heading on the post type listing screen', 'wcag-wp'),
            'items_list_navigation' => _x('Navigazione lista WCAG Breadcrumb', 'screen reader text for the pagination heading on the post type listing screen', 'wcag-wp'),
            'items_list'            => _x('Lista WCAG Breadcrumb', 'screen reader text for the items list heading on the post type listing screen', 'wcag-wp'),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => 'wcag-wp',
            'query_var'          => false,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title'],
            'show_in_rest'       => false,
        ];

        register_post_type('wcag_breadcrumb', $args);
    }

    /**
     * Aggiunge meta boxes per configurazione
     */
    public function add_meta_boxes(): void {
        add_meta_box(
            'wcag_breadcrumb_config',
            __('Configurazione WCAG Breadcrumb', 'wcag-wp'),
            [$this, 'render_config_meta_box'],
            'wcag_breadcrumb',
            'normal',
            'high'
        );

        add_meta_box(
            'wcag_breadcrumb_preview',
            __('Anteprima & Shortcode', 'wcag-wp'),
            [$this, 'render_preview_meta_box'],
            'wcag_breadcrumb',
            'side',
            'high'
        );

        add_meta_box(
            'wcag_breadcrumb_accessibility',
            __('Informazioni Accessibilità', 'wcag-wp'),
            [$this, 'render_accessibility_meta_box'],
            'wcag_breadcrumb',
            'side',
            'default'
        );
    }

    /**
     * Salva meta boxes
     */
    public function save_meta_boxes(int $post_id): void {
        if (!isset($_POST['wcag_breadcrumb_nonce']) || 
            !wp_verify_nonce($_POST['wcag_breadcrumb_nonce'], 'wcag_breadcrumb_save')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $config = [
            'source_type' => sanitize_text_field($_POST['source_type'] ?? 'auto'),
            'home_text' => sanitize_text_field($_POST['home_text'] ?? __('Home', 'wcag-wp')),
            'separator' => sanitize_text_field($_POST['separator'] ?? '/'),
            'max_depth' => absint($_POST['max_depth'] ?? 5),
            'show_current' => isset($_POST['show_current']),
            'show_home' => isset($_POST['show_home']),
            'custom_items' => $this->sanitize_custom_items($_POST['custom_items'] ?? []),
            'css_class' => sanitize_html_class($_POST['css_class'] ?? ''),
            'aria_label' => sanitize_text_field($_POST['aria_label'] ?? __('Breadcrumb navigation', 'wcag-wp')),
        ];

        update_post_meta($post_id, '_wcag_breadcrumb_config', $config);
    }

    /**
     * Sanitizza elementi custom
     */
    private function sanitize_custom_items(array $items): array {
        $sanitized = [];
        foreach ($items as $item) {
            if (!empty($item['text']) && !empty($item['url'])) {
                $sanitized[] = [
                    'text' => sanitize_text_field($item['text']),
                    'url' => esc_url_raw($item['url']),
                    'target' => sanitize_text_field($item['target'] ?? '_self'),
                ];
            }
        }
        return $sanitized;
    }

    /**
     * Renderizza meta box configurazione
     */
    public function render_config_meta_box(WP_Post $post): void {
        wp_nonce_field('wcag_breadcrumb_save', 'wcag_breadcrumb_nonce');
        
        $config = get_post_meta($post->ID, '_wcag_breadcrumb_config', true) ?: [];
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/breadcrumb-config-meta-box.php';
    }

    /**
     * Renderizza meta box anteprima
     */
    public function render_preview_meta_box(WP_Post $post): void {
        $config = get_post_meta($post->ID, '_wcag_breadcrumb_config', true) ?: [];
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/breadcrumb-preview-meta-box.php';
    }

    /**
     * Renderizza meta box accessibilità
     */
    public function render_accessibility_meta_box(WP_Post $post): void {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/breadcrumb-accessibility-meta-box.php';
    }

    /**
     * Gestisce AJAX per anteprima
     */
    public function ajax_preview(): void {
        check_ajax_referer('wcag_breadcrumb_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('Non hai i permessi per questa azione.', 'wcag-wp'));
        }

        $config = [
            'source_type' => sanitize_text_field($_POST['source_type'] ?? 'auto'),
            'home_text' => sanitize_text_field($_POST['home_text'] ?? __('Home', 'wcag-wp')),
            'separator' => sanitize_text_field($_POST['separator'] ?? '/'),
            'max_depth' => absint($_POST['max_depth'] ?? 5),
            'show_current' => isset($_POST['show_current']),
            'show_home' => isset($_POST['show_home']),
            'custom_items' => $this->sanitize_custom_items($_POST['custom_items'] ?? []),
            'css_class' => sanitize_html_class($_POST['css_class'] ?? ''),
            'aria_label' => sanitize_text_field($_POST['aria_label'] ?? __('Breadcrumb navigation', 'wcag-wp')),
        ];

        $breadcrumbs = $this->generate_breadcrumbs($config);
        $html = $this->render_breadcrumb_html($breadcrumbs, $config);

        wp_send_json_success(['html' => $html]);
    }

    /**
     * Gestisce AJAX per generazione automatica
     */
    public function ajax_generate(): void {
        check_ajax_referer('wcag_breadcrumb_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('Non hai i permessi per questa azione.', 'wcag-wp'));
        }

        $config = [
            'source_type' => 'auto',
            'home_text' => sanitize_text_field($_POST['home_text'] ?? __('Home', 'wcag-wp')),
            'separator' => sanitize_text_field($_POST['separator'] ?? '/'),
            'max_depth' => absint($_POST['max_depth'] ?? 5),
            'show_current' => true,
            'show_home' => true,
            'custom_items' => [],
            'css_class' => '',
            'aria_label' => __('Breadcrumb navigation', 'wcag-wp'),
        ];

        $breadcrumbs = $this->generate_breadcrumbs($config);
        
        wp_send_json_success(['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Genera breadcrumb basati su configurazione
     */
    public function generate_breadcrumbs(array $config): array {
        $breadcrumbs = [];

        // Aggiungi home se richiesto
        if ($config['show_home']) {
            $breadcrumbs[] = [
                'text' => $config['home_text'],
                'url' => home_url('/'),
                'current' => is_home() || is_front_page(),
            ];
        }

        // Genera breadcrumb automatici
        if ($config['source_type'] === 'auto') {
            $breadcrumbs = array_merge($breadcrumbs, $this->generate_auto_breadcrumbs($config));
        } else {
            // Usa elementi custom
            foreach ($config['custom_items'] as $item) {
                $breadcrumbs[] = [
                    'text' => $item['text'],
                    'url' => $item['url'],
                    'target' => $item['target'],
                    'current' => false,
                ];
            }
        }

        return array_slice($breadcrumbs, 0, $config['max_depth']);
    }

    /**
     * Genera breadcrumb automatici da struttura WordPress
     */
    private function generate_auto_breadcrumbs(array $config): array {
        $breadcrumbs = [];

        if (is_singular()) {
            $post = get_queried_object();
            
            // Aggiungi categorie/taxonomie
            $taxonomies = get_object_taxonomies($post->post_type, 'objects');
            foreach ($taxonomies as $taxonomy) {
                if ($taxonomy->hierarchical) {
                    $terms = get_the_terms($post->ID, $taxonomy->name);
                    if ($terms && !is_wp_error($terms)) {
                        $term = array_shift($terms);
                        $ancestors = get_ancestors($term->term_id, $taxonomy->name);
                        
                        foreach (array_reverse($ancestors) as $ancestor_id) {
                            $ancestor = get_term($ancestor_id, $taxonomy->name);
                            $breadcrumbs[] = [
                                'text' => $ancestor->name,
                                'url' => get_term_link($ancestor),
                                'current' => false,
                            ];
                        }
                        
                        $breadcrumbs[] = [
                            'text' => $term->name,
                            'url' => get_term_link($term),
                            'current' => false,
                        ];
                        break;
                    }
                }
            }

            // Aggiungi post corrente se richiesto
            if ($config['show_current']) {
                $breadcrumbs[] = [
                    'text' => get_the_title($post),
                    'url' => get_permalink($post),
                    'current' => true,
                ];
            }
        } elseif (is_tax() || is_category() || is_tag()) {
            $term = get_queried_object();
            $ancestors = get_ancestors($term->term_id, $term->taxonomy);
            
            foreach (array_reverse($ancestors) as $ancestor_id) {
                $ancestor = get_term($ancestor_id, $term->taxonomy);
                $breadcrumbs[] = [
                    'text' => $ancestor->name,
                    'url' => get_term_link($ancestor),
                    'current' => false,
                ];
            }
            
            if ($config['show_current']) {
                $breadcrumbs[] = [
                    'text' => $term->name,
                    'url' => get_term_link($term),
                    'current' => true,
                ];
            }
        } elseif (is_archive()) {
            $breadcrumbs[] = [
                'text' => get_the_archive_title(),
                'url' => get_archive_link(),
                'current' => true,
            ];
        } elseif (is_search()) {
            $breadcrumbs[] = [
                'text' => sprintf(__('Risultati ricerca per: %s', 'wcag-wp'), get_search_query()),
                'url' => '',
                'current' => true,
            ];
        } elseif (is_404()) {
            $breadcrumbs[] = [
                'text' => __('Pagina non trovata', 'wcag-wp'),
                'url' => '',
                'current' => true,
            ];
        }

        return $breadcrumbs;
    }

    /**
     * Renderizza shortcode breadcrumb
     */
    public function render_shortcode(array $atts): string {
        $atts = shortcode_atts([
            'id' => 0,
            'source_type' => 'auto',
            'home_text' => __('Home', 'wcag-wp'),
            'separator' => '/',
            'max_depth' => 5,
            'show_current' => 'true',
            'show_home' => 'true',
            'css_class' => '',
            'aria_label' => __('Breadcrumb navigation', 'wcag-wp'),
        ], $atts, 'wcag-breadcrumb');

        // Se ID specificato, carica configurazione dal post
        if ($atts['id'] > 0) {
            $config = get_post_meta($atts['id'], '_wcag_breadcrumb_config', true);
            if ($config) {
                $atts = array_merge($config, $atts);
            }
        }

        // Converti stringhe in booleani
        $atts['show_current'] = filter_var($atts['show_current'], FILTER_VALIDATE_BOOLEAN);
        $atts['show_home'] = filter_var($atts['show_home'], FILTER_VALIDATE_BOOLEAN);

        $breadcrumbs = $this->generate_breadcrumbs($atts);
        
        if (empty($breadcrumbs)) {
            return '';
        }

        return $this->render_breadcrumb_html($breadcrumbs, $atts);
    }

    /**
     * Renderizza HTML breadcrumb
     */
    private function render_breadcrumb_html(array $breadcrumbs, array $config): string {
        $class = 'wcag-wp-breadcrumb';
        if (!empty($config['css_class'])) {
            $class .= ' ' . esc_attr($config['css_class']);
        }

        $html = sprintf(
            '<nav class="%s" aria-label="%s" role="navigation">',
            esc_attr($class),
            esc_attr($config['aria_label'])
        );

        $html .= '<ol class="wcag-wp-breadcrumb-list">';

        foreach ($breadcrumbs as $index => $item) {
            $is_last = $index === count($breadcrumbs) - 1;
            
            $html .= '<li class="wcag-wp-breadcrumb-item">';
            
            if ($is_last && $item['current']) {
                // Elemento corrente (non cliccabile)
                $html .= sprintf(
                    '<span class="wcag-wp-breadcrumb-current" aria-current="page">%s</span>',
                    esc_html($item['text'])
                );
            } else {
                // Link normale
                $target = !empty($item['target']) ? ' target="' . esc_attr($item['target']) . '"' : '';
                $html .= sprintf(
                    '<a href="%s" class="wcag-wp-breadcrumb-link"%s>%s</a>',
                    esc_url($item['url']),
                    $target,
                    esc_html($item['text'])
                );
            }

            // Aggiungi separatore se non è l'ultimo elemento
            if (!$is_last) {
                $html .= sprintf(
                    '<span class="wcag-wp-breadcrumb-separator" aria-hidden="true">%s</span>',
                    esc_html($config['separator'])
                );
            }

            $html .= '</li>';
        }

        $html .= '</ol>';
        $html .= '</nav>';

        return $html;
    }

    /**
     * Carica asset CSS e JS
     */
    public function enqueue_assets(): void {
        // Carica solo se shortcode presente
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'wcag-breadcrumb')) {
            wp_enqueue_style(
                'wcag-wp-breadcrumb-frontend',
                WCAG_WP_PLUGIN_URL . 'assets/css/breadcrumb-frontend.css',
                [],
                WCAG_WP_VERSION
            );
        }
    }
}

// Inizializza componente
WCAG_WP_Breadcrumb::get_instance();
