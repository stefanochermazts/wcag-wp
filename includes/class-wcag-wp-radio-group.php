<?php
/**
 * WCAG Radio Group Component
 * 
 * Gruppo di radio button accessibile secondo pattern WCAG APG
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WCAG Radio Group Component Class
 */
class WCAG_WP_Radio_Group {
    
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
        add_shortcode('wcag-radio-group', array($this, 'render_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Admin columns
        add_filter('manage_wcag_radio_group_posts_columns', array($this, 'admin_columns'));
        add_action('manage_wcag_radio_group_posts_custom_column', array($this, 'admin_column_content'), 10, 2);
    }
    
    /**
     * Register Custom Post Type
     */
    public function register_post_type() {
        $labels = array(
            'name' => __('Radio Group WCAG', 'wcag-wp'),
            'singular_name' => __('Radio Group WCAG', 'wcag-wp'),
            'menu_name' => __('Radio Group WCAG', 'wcag-wp'),
            'add_new' => __('Aggiungi Radio Group', 'wcag-wp'),
            'add_new_item' => __('Aggiungi Nuovo Radio Group', 'wcag-wp'),
            'edit_item' => __('Modifica Radio Group', 'wcag-wp'),
            'new_item' => __('Nuovo Radio Group', 'wcag-wp'),
            'view_item' => __('Visualizza Radio Group', 'wcag-wp'),
            'search_items' => __('Cerca Radio Group', 'wcag-wp'),
            'not_found' => __('Nessun radio group trovato', 'wcag-wp'),
            'not_found_in_trash' => __('Nessun radio group trovato nel cestino', 'wcag-wp'),
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
            'menu_icon' => 'dashicons-forms',
            'show_in_rest' => false,
        );
        
        register_post_type('wcag_radio_group', $args);
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'wcag_radio_group_config',
            __('Configurazione Radio Group', 'wcag-wp'),
            array($this, 'render_config_meta_box'),
            'wcag_radio_group',
            'normal',
            'high'
        );
        
        add_meta_box(
            'wcag_radio_group_preview',
            __('Anteprima e Shortcode', 'wcag-wp'),
            array($this, 'render_preview_meta_box'),
            'wcag_radio_group',
            'side',
            'high'
        );
        
        add_meta_box(
            'wcag_radio_group_accessibility',
            __('Accessibilità WCAG', 'wcag-wp'),
            array($this, 'render_accessibility_meta_box'),
            'wcag_radio_group',
            'side',
            'default'
        );
    }
    
    /**
     * Render config meta box
     */
    public function render_config_meta_box($post) {
        wp_nonce_field('wcag_radio_group_config', 'wcag_radio_group_config_nonce');
        
        $config = get_post_meta($post->ID, '_wcag_radio_group_config', true);
        
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
            'options' => array(
                array('value' => 'option1', 'label' => 'Opzione 1', 'description' => ''),
                array('value' => 'option2', 'label' => 'Opzione 2', 'description' => ''),
                array('value' => 'option3', 'label' => 'Opzione 3', 'description' => '')
            ),
            'default_value' => '',
            'required' => false,
            'disabled' => false,
            'layout' => 'vertical',
            'size' => 'medium',
            'theme' => 'default',
            'custom_class' => '',
            'aria_label' => '',
            'aria_describedby' => '',
            'on_change_callback' => '',
            'show_descriptions' => true,
            'validation' => array(
                'required_message' => __('Questo campo è obbligatorio', 'wcag-wp'),
                'custom_validation' => ''
            )
        );
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/radio-group-config-meta-box.php';
    }
    
    /**
     * Render preview meta box
     */
    public function render_preview_meta_box($post) {
        $config = get_post_meta($post->ID, '_wcag_radio_group_config', true);
        
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
            'options' => array(
                array('value' => 'option1', 'label' => 'Opzione 1', 'description' => ''),
                array('value' => 'option2', 'label' => 'Opzione 2', 'description' => ''),
                array('value' => 'option3', 'label' => 'Opzione 3', 'description' => '')
            ),
            'default_value' => '',
            'required' => false,
            'disabled' => false,
            'layout' => 'vertical',
            'size' => 'medium',
            'theme' => 'default',
            'custom_class' => '',
            'aria_label' => '',
            'aria_describedby' => '',
            'on_change_callback' => '',
            'show_descriptions' => true,
            'validation' => array(
                'required_message' => __('Questo campo è obbligatorio', 'wcag-wp'),
                'custom_validation' => ''
            )
        );
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/radio-group-preview-meta-box.php';
    }
    
    /**
     * Render accessibility meta box
     */
    public function render_accessibility_meta_box($post) {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/radio-group-accessibility-meta-box.php';
    }
    
    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        // Security checks
        if (!isset($_POST['wcag_radio_group_config_nonce']) || 
            !wp_verify_nonce($_POST['wcag_radio_group_config_nonce'], 'wcag_radio_group_config')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save configuration
        if (isset($_POST['wcag_radio_group_config'])) {
            $config = $_POST['wcag_radio_group_config'];
            
            // Sanitize configuration
            $sanitized_config = array(
                'title' => sanitize_text_field($config['title'] ?? ''),
                'description' => sanitize_textarea_field($config['description'] ?? ''),
                'options' => $this->sanitize_options($config['options'] ?? array()),
                'default_value' => sanitize_text_field($config['default_value'] ?? ''),
                'required' => !empty($config['required']),
                'disabled' => !empty($config['disabled']),
                'orientation' => in_array($config['orientation'] ?? 'vertical', ['vertical', 'horizontal']) ? $config['orientation'] : 'vertical',
                'size' => in_array($config['size'] ?? 'medium', ['small', 'medium', 'large']) ? $config['size'] : 'medium',
                'show_labels' => !empty($config['show_labels']),
                'aria_live' => in_array($config['aria_live'] ?? 'polite', ['polite', 'assertive', 'off']) ? $config['aria_live'] : 'polite'
            );
            
            update_post_meta($post_id, '_wcag_radio_group_config', $sanitized_config);
        }
    }
    
    /**
     * Sanitize options configuration
     */
    private function sanitize_options($options) {
        if (!is_array($options)) {
            return array(
                array('value' => 'option1', 'label' => 'Opzione 1', 'description' => ''),
                array('value' => 'option2', 'label' => 'Opzione 2', 'description' => ''),
                array('value' => 'option3', 'label' => 'Opzione 3', 'description' => '')
            );
        }
        
        $sanitized_options = array();
        foreach ($options as $option) {
            if (is_array($option) && isset($option['value']) && isset($option['label'])) {
                $sanitized_options[] = array(
                    'value' => sanitize_text_field($option['value']),
                    'label' => sanitize_text_field($option['label']),
                    'description' => sanitize_textarea_field($option['description'] ?? '')
                );
            }
        }
        
        // Ensure at least 2 options
        if (count($sanitized_options) < 2) {
            $sanitized_options = array(
                array('value' => 'option1', 'label' => 'Opzione 1', 'description' => ''),
                array('value' => 'option2', 'label' => 'Opzione 2', 'description' => '')
            );
        }
        
        return $sanitized_options;
    }
    
    /**
     * Render shortcode
     */
    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0
        ), $atts, 'wcag-radio-group');
        
        $post_id = intval($atts['id']);
        if (!$post_id) {
            return '<p class="wcag-wp-error">' . __('ID radio group non valido', 'wcag-wp') . '</p>';
        }
        
        $config = get_post_meta($post_id, '_wcag_radio_group_config', true);
        
        // Handle case where config might be a serialized string
        if (is_string($config)) {
            $unserialized = maybe_unserialize($config);
            if (is_array($unserialized)) {
                $config = $unserialized;
            }
        }
        
        if (!is_array($config)) {
            return '<p class="wcag-wp-error">' . __('Configurazione radio group non trovata', 'wcag-wp') . '</p>';
        }
        
        // Ensure all required keys exist with default values
        $defaults = array(
            'label' => '',
            'description' => '',
            'options' => array(
                array('value' => 'option1', 'label' => 'Opzione 1', 'description' => ''),
                array('value' => 'option2', 'label' => 'Opzione 2', 'description' => ''),
                array('value' => 'option3', 'label' => 'Opzione 3', 'description' => '')
            ),
            'default_value' => '',
            'required' => false,
            'disabled' => false,
            'readonly' => false,
            'layout' => 'vertical',
            'size' => 'medium',
            'custom_class' => '',
            'aria_label' => '',
            'aria_describedby' => '',
            'on_change_callback' => '',
            'show_descriptions' => true,
            'animation' => true,
            'theme' => 'default',
            'validation' => array(
                'required_message' => __('Questo campo è obbligatorio', 'wcag-wp'),
                'custom_validation' => ''
            )
        );
        
        $config = wp_parse_args($config, $defaults);
        
        // Generate unique ID
        $unique_id = 'wcag-radio-group-' . $post_id . '-' . uniqid();
        
        // Start output buffering
        ob_start();
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-radio-group.php';
        return ob_get_clean();
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        wp_enqueue_style(
            'wcag-wp-radio-group-frontend',
            WCAG_WP_PLUGIN_URL . 'assets/css/radio-group-frontend.css',
            array('wcag-wp-frontend'),
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-radio-group-frontend',
            WCAG_WP_PLUGIN_URL . 'assets/js/radio-group-frontend.js',
            array('jquery', 'wcag-wp-frontend'),
            WCAG_WP_VERSION,
            true
        );
        
        wp_localize_script('wcag-wp-radio-group-frontend', 'wcag_wp_radio_group', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wcag_wp_radio_group_nonce'),
            'strings' => array(
                'selected' => __('Selezionato', 'wcag-wp'),
                'unselected' => __('Non selezionato', 'wcag-wp'),
                'required' => __('Obbligatorio', 'wcag-wp')
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
            $shortcode = '[wcag-radio-group id="' . $post_id . '"]';
            echo '<code class="wcag-wp-shortcode" data-shortcode="' . esc_attr($shortcode) . '">' . esc_html($shortcode) . '</code>';
            echo '<button type="button" class="wcag-wp-copy-shortcode button button-small" data-shortcode="' . esc_attr($shortcode) . '">' . __('Copia', 'wcag-wp') . '</button>';
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        global $post_type;
        
        if ($post_type !== 'wcag_radio_group') {
            return;
        }
        
        wp_enqueue_style(
            'wcag-wp-radio-group-admin',
            WCAG_WP_PLUGIN_URL . 'assets/css/radio-group-admin.css',
            array(),
            WCAG_WP_VERSION
        );
        
        wp_enqueue_script(
            'wcag-wp-radio-group-admin',
            WCAG_WP_PLUGIN_URL . 'assets/js/radio-group-admin.js',
            array('jquery'),
            WCAG_WP_VERSION,
            true
        );
    }
}

// Initialize the component
WCAG_WP_Radio_Group::get_instance();

