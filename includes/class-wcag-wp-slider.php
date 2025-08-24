<?php
/**
 * WCAG Slider Component
 * 
 * Controllo range con thumb singolo secondo pattern WCAG APG
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WCAG Slider Component Class
 */
class WCAG_WP_Slider {
    
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
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_shortcode('wcag-slider', array($this, 'render_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Admin columns
        add_filter('manage_wcag_slider_posts_columns', array($this, 'admin_columns'));
        add_action('manage_wcag_slider_posts_custom_column', array($this, 'admin_column_content'), 10, 2);
    }
    
    /**
     * Register Custom Post Type
     */
    public function register_post_type() {
        $labels = array(
            'name' => __('Slider WCAG', 'wcag-wp'),
            'singular_name' => __('Slider WCAG', 'wcag-wp'),
            'menu_name' => __('Slider WCAG', 'wcag-wp'),
            'add_new' => __('Aggiungi Slider', 'wcag-wp'),
            'add_new_item' => __('Aggiungi Nuovo Slider', 'wcag-wp'),
            'edit_item' => __('Modifica Slider', 'wcag-wp'),
            'new_item' => __('Nuovo Slider', 'wcag-wp'),
            'view_item' => __('Visualizza Slider', 'wcag-wp'),
            'search_items' => __('Cerca Slider', 'wcag-wp'),
            'not_found' => __('Nessuno slider trovato', 'wcag-wp'),
            'not_found_in_trash' => __('Nessuno slider trovato nel cestino', 'wcag-wp'),
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
        
        register_post_type('wcag_slider', $args);
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'wcag_slider_config',
            __('Configurazione Slider', 'wcag-wp'),
            array($this, 'render_config_meta_box'),
            'wcag_slider',
            'normal',
            'high'
        );
        
        add_meta_box(
            'wcag_slider_preview',
            __('Anteprima e Shortcode', 'wcag-wp'),
            array($this, 'render_preview_meta_box'),
            'wcag_slider',
            'side',
            'high'
        );
        
        add_meta_box(
            'wcag_slider_accessibility',
            __('Accessibilità WCAG', 'wcag-wp'),
            array($this, 'render_accessibility_meta_box'),
            'wcag_slider',
            'side',
            'default'
        );
    }
    
    /**
     * Render config meta box
     */
    public function render_config_meta_box($post) {
        wp_nonce_field('wcag_slider_config', 'wcag_slider_config_nonce');
        
        $config = get_post_meta($post->ID, '_wcag_slider_config', true);
        
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
            'default_value' => 50,
            'unit' => '',
            'show_value' => true,
            'show_ticks' => false,
            'tick_labels' => '',
            'orientation' => 'horizontal',
            'size' => 'medium',
            'theme' => 'default',
            'required' => false,
            'disabled' => false,
            'custom_class' => '',
            'aria_label' => '',
            'aria_describedby' => '',
            'on_change_callback' => '',
            'show_tooltip' => true,
            'format' => 'number'
        );
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/slider-config-meta-box.php';
    }
    
    /**
     * Render preview meta box
     */
    public function render_preview_meta_box($post) {
        $config = get_post_meta($post->ID, '_wcag_slider_config', true);
        
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
            'default_value' => 50,
            'unit' => '',
            'show_value' => true,
            'show_ticks' => false,
            'tick_labels' => '',
            'orientation' => 'horizontal',
            'size' => 'medium',
            'theme' => 'default',
            'required' => false,
            'disabled' => false,
            'custom_class' => '',
            'aria_label' => '',
            'aria_describedby' => '',
            'on_change_callback' => '',
            'show_tooltip' => true,
            'format' => 'number'
        );
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/slider-preview-meta-box.php';
    }
    
    /**
     * Render accessibility meta box
     */
    public function render_accessibility_meta_box($post) {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/slider-accessibility-meta-box.php';
    }
    
    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        // Security checks
        if (!isset($_POST['wcag_slider_config_nonce']) || 
            !wp_verify_nonce($_POST['wcag_slider_config_nonce'], 'wcag_slider_config')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save configuration
        if (isset($_POST['wcag_slider_config'])) {
            $config = $_POST['wcag_slider_config'];
            
            // Sanitize configuration
            $sanitized_config = array(
                'label' => sanitize_text_field($config['label'] ?? ''),
                'description' => sanitize_textarea_field($config['description'] ?? ''),
                'min' => floatval($config['min'] ?? 0),
                'max' => floatval($config['max'] ?? 100),
                'step' => floatval($config['step'] ?? 1),
                'default_value' => floatval($config['default_value'] ?? 50),
                'unit' => sanitize_text_field($config['unit'] ?? ''),
                'show_value' => !empty($config['show_value']),
                'show_ticks' => !empty($config['show_ticks']),
                'tick_labels' => sanitize_textarea_field($config['tick_labels'] ?? ''),
                'orientation' => in_array($config['orientation'] ?? 'horizontal', ['horizontal', 'vertical']) ? $config['orientation'] : 'horizontal',
                'size' => in_array($config['size'] ?? 'medium', ['small', 'medium', 'large']) ? $config['size'] : 'medium',
                'theme' => in_array($config['theme'] ?? 'default', ['default', 'success', 'warning', 'danger']) ? $config['theme'] : 'default',
                'required' => !empty($config['required']),
                'disabled' => !empty($config['disabled']),
                'custom_class' => sanitize_text_field($config['custom_class'] ?? ''),
                'aria_label' => sanitize_text_field($config['aria_label'] ?? ''),
                'aria_describedby' => sanitize_text_field($config['aria_describedby'] ?? ''),
                'on_change_callback' => sanitize_text_field($config['on_change_callback'] ?? ''),
                'show_tooltip' => !empty($config['show_tooltip']),
                'format' => in_array($config['format'] ?? 'number', ['number', 'percentage', 'currency']) ? $config['format'] : 'number'
            );
            
            update_post_meta($post_id, '_wcag_slider_config', $sanitized_config);
        }
    }
    
    /**
     * Render shortcode
     */
    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0
        ), $atts, 'wcag-slider');
        
        $post_id = intval($atts['id']);
        if (!$post_id) {
            return '<p class="wcag-wp-error">' . __('ID slider non valido', 'wcag-wp') . '</p>';
        }
        
        $config = get_post_meta($post_id, '_wcag_slider_config', true);
        
        // Handle case where config might be a serialized string
        if (is_string($config)) {
            $unserialized = maybe_unserialize($config);
            if (is_array($unserialized)) {
                $config = $unserialized;
            }
        }
        
        if (!is_array($config)) {
            return '<p class="wcag-wp-error">' . __('Configurazione slider non trovata', 'wcag-wp') . '</p>';
        }
        
        // Ensure all required keys exist with default values
        $defaults = array(
            'label' => '',
            'description' => '',
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'default_value' => 50,
            'unit' => '',
            'required' => false,
            'disabled' => false,
            'readonly' => false,
            'size' => 'medium',
            'custom_class' => '',
            'aria_label' => '',
            'aria_describedby' => '',
            'on_change_callback' => '',
            'show_value' => true,
            'show_ticks' => false,
            'animation' => true,
            'theme' => 'default'
        );
        
        $config = wp_parse_args($config, $defaults);
        
        // Generate unique ID
        $unique_id = 'wcag-slider-' . $post_id . '-' . uniqid();
        
        // Start output buffering
        ob_start();
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-slider.php';
        return ob_get_clean();
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        wp_enqueue_style(
            'wcag-wp-slider-frontend',
            WCAG_WP_PLUGIN_URL . 'assets/css/slider-frontend.css',
            array('wcag-wp-frontend'),
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-slider-frontend',
            WCAG_WP_PLUGIN_URL . 'assets/js/slider-frontend.js',
            array('jquery', 'wcag-wp-frontend'),
            WCAG_WP_VERSION,
            true
        );
        
        wp_localize_script('wcag-wp-slider-frontend', 'wcag_wp_slider', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wcag_wp_slider_nonce'),
            'strings' => array(
                'value' => __('Valore', 'wcag-wp'),
                'min' => __('Minimo', 'wcag-wp'),
                'max' => __('Massimo', 'wcag-wp'),
                'step' => __('Passo', 'wcag-wp')
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
            $shortcode = '[wcag-slider id="' . $post_id . '"]';
            echo '<code class="wcag-wp-shortcode" data-shortcode="' . esc_attr($shortcode) . '">' . esc_html($shortcode) . '</code>';
            echo '<button type="button" class="wcag-wp-copy-shortcode button button-small" data-shortcode="' . esc_attr($shortcode) . '">' . __('Copia', 'wcag-wp') . '</button>';
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        global $post_type;
        
        if ($post_type !== 'wcag_slider') {
            return;
        }
        
        wp_enqueue_style(
            'wcag-wp-slider-admin',
            WCAG_WP_PLUGIN_URL . 'assets/css/slider-admin.css',
            array(),
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-slider-admin',
            WCAG_WP_PLUGIN_URL . 'assets/js/slider-admin.js',
            array('jquery'),
            WCAG_WP_VERSION,
            true
        );
    }
}

// Initialize the component
WCAG_WP_Slider::get_instance();
