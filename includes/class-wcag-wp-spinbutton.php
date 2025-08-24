<?php
/**
 * WCAG Spinbutton Component
 * 
 * Accessible spinbutton implementation following WCAG 2.1 AA guidelines
 * Based on WAI-ARIA Authoring Practices Guide (APG) Spinbutton pattern
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WCAG Spinbutton Component Class
 * 
 * Implements accessible spinbutton with keyboard navigation,
 * screen reader support, and WCAG 2.1 AA compliance
 */
class WCAG_WP_Spinbutton {
    
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
        add_action('wp_ajax_wcag_spinbutton_get_options', array($this, 'ajax_get_options'));
        add_action('wp_ajax_nopriv_wcag_spinbutton_get_options', array($this, 'ajax_get_options'));
        add_shortcode('wcag-spinbutton', array($this, 'render_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Admin columns
        add_filter('manage_wcag_spinbutton_posts_columns', array($this, 'admin_columns'));
        add_action('manage_wcag_spinbutton_posts_custom_column', array($this, 'admin_column_content'), 10, 2);
    }
    
    /**
     * Register Custom Post Type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Spinbutton WCAG', 'Post type general name', 'wcag-wp'),
            'singular_name'         => _x('Spinbutton WCAG', 'Post type singular name', 'wcag-wp'),
            'menu_name'             => _x('Spinbutton WCAG', 'Admin Menu text', 'wcag-wp'),
            'name_admin_bar'        => _x('Spinbutton WCAG', 'Add New on Toolbar', 'wcag-wp'),
            'add_new'               => __('Aggiungi Nuovo', 'wcag-wp'),
            'add_new_item'          => __('Aggiungi Nuovo Spinbutton', 'wcag-wp'),
            'new_item'              => __('Nuovo Spinbutton', 'wcag-wp'),
            'edit_item'             => __('Modifica Spinbutton', 'wcag-wp'),
            'view_item'             => __('Visualizza Spinbutton', 'wcag-wp'),
            'all_items'             => __('Tutti gli Spinbutton', 'wcag-wp'),
            'search_items'          => __('Cerca Spinbutton', 'wcag-wp'),
            'parent_item_colon'     => __('Spinbutton Padre:', 'wcag-wp'),
            'not_found'             => __('Nessuno spinbutton trovato.', 'wcag-wp'),
            'not_found_in_trash'    => __('Nessuno spinbutton trovato nel cestino.', 'wcag-wp'),
            'featured_image'        => _x('Immagine Spinbutton', 'Overrides the "Featured Image" phrase', 'wcag-wp'),
            'set_featured_image'    => _x('Imposta immagine spinbutton', 'Overrides the "Set featured image" phrase', 'wcag-wp'),
            'remove_featured_image' => _x('Rimuovi immagine spinbutton', 'Overrides the "Remove featured image" phrase', 'wcag-wp'),
            'use_featured_image'    => _x('Usa come immagine spinbutton', 'Overrides the "Use as featured image" phrase', 'wcag-wp'),
            'archives'              => _x('Archivio spinbutton', 'The post type archive label', 'wcag-wp'),
            'insert_into_item'      => _x('Inserisci in spinbutton', 'Overrides the "Insert into post" phrase', 'wcag-wp'),
            'uploaded_to_this_item' => _x('Caricato in questo spinbutton', 'Overrides the "Uploaded to this post" phrase', 'wcag-wp'),
            'filter_items_list'     => _x('Filtra lista spinbutton', 'Screen reader text for the filter links', 'wcag-wp'),
            'items_list_navigation' => _x('Navigazione lista spinbutton', 'Screen reader text for the pagination', 'wcag-wp'),
            'items_list'            => _x('Lista spinbutton', 'Screen reader text for the items list', 'wcag-wp'),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => 'wcag-wp-main',
            'query_var'          => true,
            'rewrite'            => array('slug' => 'wcag-spinbutton'),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title'),
            'show_in_rest'       => false,
            'menu_icon'          => 'dashicons-controls-volumeon',
        );
        
        register_post_type('wcag_spinbutton', $args);
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'wcag-spinbutton-config',
            __('Configurazione Spinbutton', 'wcag-wp'),
            array($this, 'render_config_meta_box'),
            'wcag_spinbutton',
            'normal',
            'high'
        );
        
        add_meta_box(
            'wcag-spinbutton-preview',
            __('Anteprima e Shortcode', 'wcag-wp'),
            array($this, 'render_preview_meta_box'),
            'wcag_spinbutton',
            'side',
            'high'
        );
        
        add_meta_box(
            'wcag-spinbutton-accessibility',
            __('Accessibilità WCAG', 'wcag-wp'),
            array($this, 'render_accessibility_meta_box'),
            'wcag_spinbutton',
            'side',
            'default'
        );
    }
    
    /**
     * Render configuration meta box
     */
    public function render_config_meta_box($post) {
        wp_nonce_field('wcag_spinbutton_meta_box', 'wcag_spinbutton_meta_box_nonce');
        
        $config = get_post_meta($post->ID, '_wcag_spinbutton_config', true);
        if (!is_array($config)) {
            $config = array();
        }
        
        $defaults = array(
            'type' => 'integer',
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'default_value' => 0,
            'placeholder' => '',
            'label' => '',
            'description' => '',
            'required' => false,
            'readonly' => false,
            'disabled' => false,
            'size' => 'medium',
            'unit' => '',
            'format' => 'number',
            'custom_class' => '',
            'aria_label' => '',
            'aria_describedby' => ''
        );
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/spinbutton-config-meta-box.php';
    }
    
    /**
     * Render preview meta box
     */
    public function render_preview_meta_box($post) {
        $config = get_post_meta($post->ID, '_wcag_spinbutton_config', true);
        if (!is_array($config)) {
            $config = array();
        }
        
        $defaults = array(
            'type' => 'integer',
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'default_value' => 0,
            'placeholder' => '',
            'label' => '',
            'description' => '',
            'required' => false,
            'readonly' => false,
            'disabled' => false,
            'size' => 'medium',
            'unit' => '',
            'format' => 'number',
            'custom_class' => '',
            'aria_label' => '',
            'aria_describedby' => ''
        );
        
        $config = wp_parse_args($config, $defaults);
        
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/spinbutton-preview-meta-box.php';
    }
    
    /**
     * Render accessibility meta box
     */
    public function render_accessibility_meta_box($post) {
        include WCAG_WP_PLUGIN_DIR . 'templates/admin/spinbutton-accessibility-meta-box.php';
    }
    
    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        // Security checks
        if (!isset($_POST['wcag_spinbutton_meta_box_nonce']) || 
            !wp_verify_nonce($_POST['wcag_spinbutton_meta_box_nonce'], 'wcag_spinbutton_meta_box')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save configuration
        if (isset($_POST['wcag_spinbutton_config'])) {
            $config = array();
            
            // Basic settings
            $config['type'] = sanitize_text_field($_POST['wcag_spinbutton_config']['type'] ?? 'integer');
            $config['min'] = floatval($_POST['wcag_spinbutton_config']['min'] ?? 0);
            $config['max'] = floatval($_POST['wcag_spinbutton_config']['max'] ?? 100);
            $config['step'] = floatval($_POST['wcag_spinbutton_config']['step'] ?? 1);
            $config['default_value'] = floatval($_POST['wcag_spinbutton_config']['default_value'] ?? 0);
            $config['placeholder'] = sanitize_text_field($_POST['wcag_spinbutton_config']['placeholder'] ?? '');
            $config['label'] = sanitize_text_field($_POST['wcag_spinbutton_config']['label'] ?? '');
            $config['description'] = sanitize_textarea_field($_POST['wcag_spinbutton_config']['description'] ?? '');
            $config['required'] = isset($_POST['wcag_spinbutton_config']['required']);
            $config['readonly'] = isset($_POST['wcag_spinbutton_config']['readonly']);
            $config['disabled'] = isset($_POST['wcag_spinbutton_config']['disabled']);
            $config['size'] = sanitize_text_field($_POST['wcag_spinbutton_config']['size'] ?? 'medium');
            $config['unit'] = sanitize_text_field($_POST['wcag_spinbutton_config']['unit'] ?? '');
            $config['format'] = sanitize_text_field($_POST['wcag_spinbutton_config']['format'] ?? 'number');
            $config['custom_class'] = sanitize_text_field($_POST['wcag_spinbutton_config']['custom_class'] ?? '');
            $config['aria_label'] = sanitize_text_field($_POST['wcag_spinbutton_config']['aria_label'] ?? '');
            $config['aria_describedby'] = sanitize_text_field($_POST['wcag_spinbutton_config']['aria_describedby'] ?? '');
            
            update_post_meta($post_id, '_wcag_spinbutton_config', $config);
        }
    }
    
    /**
     * AJAX handler for getting options
     */
    public function ajax_get_options() {
        check_ajax_referer('wcag_wp_nonce', 'nonce');
        
        $post_id = intval($_POST['post_id'] ?? 0);
        if (!$post_id) {
            wp_die();
        }
        
        $config = get_post_meta($post_id, '_wcag_spinbutton_config', true);
        if (!is_array($config)) {
            wp_die();
        }
        
        wp_send_json_success($config);
    }
    
    /**
     * Render shortcode
     */
    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'class' => '',
            'value' => '',
            'min' => '',
            'max' => '',
            'step' => '',
            'unit' => '',
            'label' => '',
            'description' => '',
            'required' => 'false',
            'readonly' => 'false',
            'disabled' => 'false'
        ), $atts, 'wcag-spinbutton');
        
        $post_id = intval($atts['id']);
        if (!$post_id) {
            return '<p class="wcag-wp-error">' . __('ID spinbutton non valido', 'wcag-wp') . '</p>';
        }
        
        $config = get_post_meta($post_id, '_wcag_spinbutton_config', true);
        if (!is_array($config)) {
            return '<p class="wcag-wp-error">' . __('Configurazione spinbutton non trovata', 'wcag-wp') . '</p>';
        }
        
        // Override with shortcode attributes
        if (!empty($atts['value'])) {
            $config['default_value'] = floatval($atts['value']);
        }
        if (!empty($atts['min'])) {
            $config['min'] = floatval($atts['min']);
        }
        if (!empty($atts['max'])) {
            $config['max'] = floatval($atts['max']);
        }
        if (!empty($atts['step'])) {
            $config['step'] = floatval($atts['step']);
        }
        if (!empty($atts['unit'])) {
            $config['unit'] = sanitize_text_field($atts['unit']);
        }
        if (!empty($atts['label'])) {
            $config['label'] = sanitize_text_field($atts['label']);
        }
        if (!empty($atts['description'])) {
            $config['description'] = sanitize_textarea_field($atts['description']);
        }
        
        $config['required'] = filter_var($atts['required'], FILTER_VALIDATE_BOOLEAN);
        $config['readonly'] = filter_var($atts['readonly'], FILTER_VALIDATE_BOOLEAN);
        $config['disabled'] = filter_var($atts['disabled'], FILTER_VALIDATE_BOOLEAN);
        
        // Add custom class
        if (!empty($atts['class'])) {
            $config['custom_class'] = trim($config['custom_class'] . ' ' . sanitize_text_field($atts['class']));
        }
        
        // Generate unique ID
        $unique_id = 'wcag-spinbutton-' . $post_id . '-' . uniqid();
        
        ob_start();
        include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-spinbutton.php';
        return ob_get_clean();
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        if (has_shortcode(get_the_content(), 'wcag-spinbutton')) {
            wp_enqueue_style(
                'wcag-wp-spinbutton-frontend',
                WCAG_WP_PLUGIN_URL . 'assets/css/spinbutton-frontend.css',
                array('wcag-wp-frontend'),
                WCAG_WP_VERSION
            );
            
            wp_enqueue_script(
                'wcag-wp-spinbutton-frontend',
                WCAG_WP_PLUGIN_URL . 'assets/js/spinbutton-frontend.js',
                array('jquery'),
                WCAG_WP_VERSION,
                true
            );
            
            wp_localize_script('wcag-wp-spinbutton-frontend', 'wcagWpSpinbutton', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wcag_wp_nonce'),
                'strings' => array(
                    'increment' => __('Incrementa', 'wcag-wp'),
                    'decrement' => __('Decrementa', 'wcag-wp'),
                    'invalid_value' => __('Valore non valido', 'wcag-wp'),
                    'min_value' => __('Valore minimo', 'wcag-wp'),
                    'max_value' => __('Valore massimo', 'wcag-wp')
                )
            ));
        }
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
            $shortcode = '[wcag-spinbutton id="' . $post_id . '"]';
            echo '<code class="wcag-wp-shortcode" data-shortcode="' . esc_attr($shortcode) . '">' . esc_html($shortcode) . '</code>';
            echo '<button type="button" class="wcag-wp-copy-shortcode button button-small" data-shortcode="' . esc_attr($shortcode) . '">' . __('Copia', 'wcag-wp') . '</button>';
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        global $post_type;
        
        if ($post_type === 'wcag_spinbutton') {
            wp_enqueue_style(
                'wcag-wp-spinbutton-admin',
                WCAG_WP_PLUGIN_URL . 'assets/css/spinbutton-admin.css',
                array(),
                WCAG_WP_VERSION
            );
            
            wp_enqueue_script(
                'wcag-wp-spinbutton-admin',
                WCAG_WP_PLUGIN_URL . 'assets/js/spinbutton-admin.js',
                array('jquery'),
                WCAG_WP_VERSION,
                true
            );
            
            wp_localize_script('wcag-wp-spinbutton-admin', 'wcagWpSpinbuttonAdmin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wcag_wp_nonce'),
                'strings' => array(
                    'preview_updated' => __('Anteprima aggiornata', 'wcag-wp'),
                    'shortcode_copied' => __('Shortcode copiato negli appunti', 'wcag-wp')
                )
            ));
        }
    }
}

// Initialize the component
WCAG_WP_Spinbutton::get_instance();
