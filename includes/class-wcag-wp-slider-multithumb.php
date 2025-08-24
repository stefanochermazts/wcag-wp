<?php
/**
 * WCAG Slider Multi-Thumb Component
 * 
 * Controllo range con multiple thumbs secondo pattern WCAG APG
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WCAG Slider Multi-Thumb Component Class
 */
class WCAG_WP_Slider_Multithumb {
    
    /**
     * Singleton instance
     */
    private static $instance = null;
    
    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        // Registra immediatamente il CPT se init è già passato
        if (did_action('init')) {
            $this->register_post_type();
        } else {
            add_action('init', array($this, 'register_post_type'));
        }
        
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_shortcode('wcag-slider-multithumb', array($this, 'render_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Admin columns
        add_filter('manage_wcag_slider_multi_posts_columns', array($this, 'admin_columns'));
        add_action('manage_wcag_slider_multi_posts_custom_column', array($this, 'admin_column_content'), 10, 2);
    }
    
    /**
     * Register Custom Post Type
     */
    public function register_post_type() {
        $labels = array(
            'name' => __('Slider Multi-Thumb WCAG', 'wcag-wp'),
            'singular_name' => __('Slider Multi-Thumb WCAG', 'wcag-wp'),
            'menu_name' => __('Slider Multi-Thumb WCAG', 'wcag-wp'),
            'add_new' => __('Aggiungi Slider Multi-Thumb', 'wcag-wp'),
            'add_new_item' => __('Aggiungi Nuovo Slider Multi-Thumb', 'wcag-wp'),
            'edit_item' => __('Modifica Slider Multi-Thumb', 'wcag-wp'),
            'new_item' => __('Nuovo Slider Multi-Thumb', 'wcag-wp'),
            'view_item' => __('Visualizza Slider Multi-Thumb', 'wcag-wp'),
            'search_items' => __('Cerca Slider Multi-Thumb', 'wcag-wp'),
            'not_found' => __('Nessuno slider multi-thumb trovato', 'wcag-wp'),
            'not_found_in_trash' => __('Nessuno slider multi-thumb trovato nel cestino', 'wcag-wp'),
        );
        
        $args = array(
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'wcag-wp-main',
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => false,
            'supports' => array('title'),
            'menu_icon' => 'dashicons-slides',
            'show_in_rest' => false,
        );
        
        register_post_type('wcag_slider_multi', $args);
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'wcag_slider_multithumb_config',
            __('Configurazione Slider Multi-Thumb', 'wcag-wp'),
            array($this, 'render_config_meta_box'),
            'wcag_slider_multi',
            'normal',
            'high'
        );
        
        add_meta_box(
            'wcag_slider_multithumb_preview',
            __('Anteprima e Shortcode', 'wcag-wp'),
            array($this, 'render_preview_meta_box'),
            'wcag_slider_multi',
            'side',
            'high'
        );
        
        add_meta_box(
            'wcag_slider_multithumb_accessibility',
            __('Accessibilità WCAG', 'wcag-wp'),
            array($this, 'render_accessibility_meta_box'),
            'wcag_slider_multi',
            'side',
            'default'
        );
    }
    
    /**
     * Render config meta box
     */
    public function render_config_meta_box($post) {
        wp_nonce_field('wcag_slider_multithumb_config', 'wcag_slider_multithumb_config_nonce');
        
        $config = get_post_meta($post->ID, '_wcag_slider_multithumb_config', true);
        
        // Handle case where config might be a serialized string
        if (is_string($config)) {
            $unserialized = maybe_unserialize($config);
            if (is_array($unserialized)) {
                $config = $unserialized;
            }
        }
        
        if (!is_array($config)) {
            $config = array();
        }
        
        // Default values
        $defaults = array(
            'label' => '',
            'description' => '',
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'thumbs_count' => 2,
            'default_values' => '25,75',
            'unit' => '',
            'show_values' => true,
            'show_range_fill' => true,
            'show_ticks' => false,
            'prevent_overlap' => true,
            'min_distance' => 5,
            'orientation' => 'horizontal',
            'size' => 'medium',
            'theme' => 'default',
            'required' => false,
            'disabled' => false,
            'custom_class' => '',
            'aria_label' => '',
            'aria_describedby' => '',
            'on_change_callback' => ''
        );
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/slider-multithumb-config-meta-box.php';
    }
    
    /**
     * Render preview meta box
     */
    public function render_preview_meta_box($post) {
        $config = get_post_meta($post->ID, '_wcag_slider_multithumb_config', true);
        
        // Handle case where config might be a serialized string
        if (is_string($config)) {
            $unserialized = maybe_unserialize($config);
            if (is_array($unserialized)) {
                $config = $unserialized;
            }
        }
        
        if (!is_array($config)) {
            $config = array();
        }
        
        // Ensure all required keys exist with default values for preview
        $defaults = array(
            'label' => '',
            'description' => '',
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'thumbs_count' => 2,
            'default_values' => '25,75',
            'unit' => '',
            'show_values' => true,
            'show_range_fill' => true,
            'show_ticks' => false,
            'prevent_overlap' => true,
            'min_distance' => 5,
            'orientation' => 'horizontal',
            'size' => 'medium',
            'theme' => 'default',
            'required' => false,
            'disabled' => false,
            'custom_class' => '',
            'aria_label' => '',
            'aria_describedby' => '',
            'on_change_callback' => ''
        );
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/slider-multithumb-preview-meta-box.php';
    }
    
    /**
     * Render accessibility meta box
     */
    public function render_accessibility_meta_box($post) {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/slider-multithumb-accessibility-meta-box.php';
    }
    
    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        // Security checks
        if (!isset($_POST['wcag_slider_multithumb_config_nonce']) || 
            !wp_verify_nonce($_POST['wcag_slider_multithumb_config_nonce'], 'wcag_slider_multithumb_config')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save configuration
        if (isset($_POST['wcag_slider_multithumb_config'])) {
            $config = $_POST['wcag_slider_multithumb_config'];
            
            // Sanitize configuration
            $sanitized_config = array(
                'label' => sanitize_text_field($config['label'] ?? ''),
                'description' => sanitize_textarea_field($config['description'] ?? ''),
                'min' => floatval($config['min'] ?? 0),
                'max' => floatval($config['max'] ?? 100),
                'step' => floatval($config['step'] ?? 1),
                'thumbs_count' => intval($config['thumbs_count'] ?? 2),
                'default_values' => sanitize_text_field($config['default_values'] ?? '25,75'),
                'unit' => sanitize_text_field($config['unit'] ?? ''),
                'show_values' => !empty($config['show_values']),
                'show_range_fill' => !empty($config['show_range_fill']),
                'show_ticks' => !empty($config['show_ticks']),
                'prevent_overlap' => !empty($config['prevent_overlap']),
                'min_distance' => floatval($config['min_distance'] ?? 0),
                'orientation' => in_array($config['orientation'] ?? 'horizontal', ['horizontal', 'vertical']) ? $config['orientation'] : 'horizontal',
                'size' => in_array($config['size'] ?? 'medium', ['small', 'medium', 'large']) ? $config['size'] : 'medium',
                'theme' => in_array($config['theme'] ?? 'default', ['default', 'success', 'warning', 'danger']) ? $config['theme'] : 'default',
                'required' => !empty($config['required']),
                'disabled' => !empty($config['disabled']),
                'custom_class' => sanitize_text_field($config['custom_class'] ?? ''),
                'aria_label' => sanitize_text_field($config['aria_label'] ?? ''),
                'aria_describedby' => sanitize_text_field($config['aria_describedby'] ?? ''),
                'on_change_callback' => sanitize_text_field($config['on_change_callback'] ?? '')
            );
            
            update_post_meta($post_id, '_wcag_slider_multithumb_config', $sanitized_config);
        }
    }
    
    /**
     * Sanitize thumbs configuration
     */
    private function sanitize_thumbs($thumbs) {
        if (!is_array($thumbs)) {
            return array(
                array('value' => 25, 'label' => 'Min'),
                array('value' => 75, 'label' => 'Max')
            );
        }
        
        $sanitized_thumbs = array();
        foreach ($thumbs as $thumb) {
            if (is_array($thumb) && isset($thumb['value']) && isset($thumb['label'])) {
                $sanitized_thumbs[] = array(
                    'value' => floatval($thumb['value']),
                    'label' => sanitize_text_field($thumb['label'])
                );
            }
        }
        
        // Ensure at least 2 thumbs
        if (count($sanitized_thumbs) < 2) {
            $sanitized_thumbs = array(
                array('value' => 25, 'label' => 'Min'),
                array('value' => 75, 'label' => 'Max')
            );
        }
        
        return $sanitized_thumbs;
    }
    
    /**
     * Render shortcode
     */
    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0
        ), $atts, 'wcag-slider-multithumb');
        
        $post_id = intval($atts['id']);
        if (!$post_id) {
            return '<p class="wcag-wp-error">' . __('ID slider multi-thumb non valido', 'wcag-wp') . '</p>';
        }
        
        $config = get_post_meta($post_id, '_wcag_slider_multithumb_config', true);
        
        // Handle case where config might be a serialized string
        if (is_string($config)) {
            $unserialized = maybe_unserialize($config);
            if (is_array($unserialized)) {
                $config = $unserialized;
            }
        }
        
        if (!is_array($config)) {
            return '<p class="wcag-wp-error">' . __('Configurazione slider multi-thumb non trovata', 'wcag-wp') . '</p>';
        }
        
        // Ensure all required keys exist with default values
        $defaults = array(
            'label' => '',
            'description' => '',
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'thumbs' => array(
                array('value' => 25, 'label' => 'Min'),
                array('value' => 75, 'label' => 'Max')
            ),
            'unit' => '',
            'required' => false,
            'disabled' => false,
            'readonly' => false,
            'size' => 'medium',
            'custom_class' => '',
            'aria_label' => '',
            'aria_describedby' => '',
            'on_change_callback' => '',
            'show_values' => true,
            'show_ticks' => false,
            'animation' => true,
            'theme' => 'default',
            'orientation' => 'horizontal',
            'show_tooltip' => true,
            'format' => 'number',
            'allow_overlap' => false,
            'min_distance' => 0
        );
        
        $config = wp_parse_args($config, $defaults);
        
        // Generate unique ID
        $unique_id = 'wcag-slider-multithumb-' . $post_id . '-' . uniqid();
        
        // Start output buffering
        ob_start();
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-slider-multithumb.php';
        return ob_get_clean();
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        wp_enqueue_style(
            'wcag-wp-slider-multithumb-frontend',
            WCAG_WP_PLUGIN_URL . 'assets/css/slider-multithumb-frontend.css',
            array('wcag-wp-frontend'),
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-slider-multithumb-frontend',
            WCAG_WP_PLUGIN_URL . 'assets/js/slider-multithumb-frontend.js',
            array('jquery', 'wcag-wp-frontend'),
            WCAG_WP_VERSION,
            true
        );
        
        wp_localize_script('wcag-wp-slider-multithumb-frontend', 'wcag_wp_slider_multithumb', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wcag_wp_slider_multithumb_nonce'),
            'strings' => array(
                'value' => __('Valore', 'wcag-wp'),
                'min' => __('Minimo', 'wcag-wp'),
                'max' => __('Massimo', 'wcag-wp'),
                'step' => __('Passo', 'wcag-wp'),
                'thumb' => __('Thumb', 'wcag-wp')
            )
        ));
    }
    
    /**
     * Add admin columns
     */
    public function admin_columns($columns) {
        $new_columns = array();
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key === 'title') {
                $new_columns['shortcode'] = __('Shortcode', 'wcag-wp');
            }
        }
        return $new_columns;
    }
    
    /**
     * Admin column content
     */
    public function admin_column_content($column, $post_id) {
        if ($column === 'shortcode') {
            $shortcode = '[wcag-slider-multithumb id="' . $post_id . '"]';
            echo '<code class="wcag-wp-shortcode" data-shortcode="' . esc_attr($shortcode) . '">' . esc_html($shortcode) . '</code>';
            echo '<button type="button" class="wcag-wp-copy-shortcode button button-small" data-shortcode="' . esc_attr($shortcode) . '">' . __('Copia', 'wcag-wp') . '</button>';
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        global $post_type;
        
        if ($post_type !== 'wcag_slider_multi') {
            return;
        }
        
        wp_enqueue_style(
            'wcag-wp-slider-multithumb-admin',
            WCAG_WP_PLUGIN_URL . 'assets/css/slider-multithumb-admin.css',
            array(),
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-slider-multithumb-admin',
            WCAG_WP_PLUGIN_URL . 'assets/js/slider-multithumb-admin.js',
            array('jquery'),
            WCAG_WP_VERSION,
            true
        );
    }
}

// Initialize the component
WCAG_WP_Slider_Multithumb::get_instance();

