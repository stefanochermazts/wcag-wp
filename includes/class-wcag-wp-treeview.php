<?php
declare(strict_types=1);

// Prevent direct access
if (!defined('ABSPATH')) {
	exit;
}

/**
 * WCAG Treeview Component
 * 
 * Implementazione del pattern ARIA Treeview (APG) con navigazione da tastiera
 * e ruoli/stati corretti. Supporta nodi espandibili e selezione singola.
 *
 * @package WCAG_WP
 */
class WCAG_WP_Treeview {
	private static $instance = null;
	private string $post_type = 'wcag_treeview';

	public static function get_instance(): self {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->init_hooks();
	}

	private function init_hooks(): void {
		// CPT
		if (did_action('init')) {
			$this->register_post_type();
		} else {
			add_action('init', [$this, 'register_post_type']);
		}

		// Admin
		add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
		add_action('save_post', [$this, 'save_meta_boxes']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);

		// Frontend
		add_shortcode('wcag-treeview', [$this, 'render_shortcode']);
		add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
	}

	public function register_post_type(): void {
		$labels = [
			'name' => __('WCAG Treeview', 'wcag-wp'),
			'singular_name' => __('Treeview', 'wcag-wp'),
			'add_new_item' => __('Aggiungi Treeview', 'wcag-wp'),
			'edit_item' => __('Modifica Treeview', 'wcag-wp'),
		];

		$args = [
			'labels' => $labels,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => 'wcag-wp-main',
			'supports' => ['title'],
			'show_in_rest' => false,
			'menu_icon' => 'dashicons-networking',
			'rewrite' => false,
		];

		register_post_type($this->post_type, $args);
	}

	public function add_meta_boxes(): void {
		add_meta_box(
			'wcag_treeview_config',
			__('Configurazione', 'wcag-wp'),
			[$this, 'render_config_meta_box'],
			$this->post_type,
			'side',
			'default'
		);
		add_meta_box(
			'wcag_treeview_structure',
			__('Struttura Treeview', 'wcag-wp'),
			[$this, 'render_structure_meta_box'],
			$this->post_type,
			'normal',
			'high'
		);
		add_meta_box(
			'wcag_treeview_preview',
			__('Anteprima & Shortcode', 'wcag-wp'),
			[$this, 'render_preview_meta_box'],
			$this->post_type,
			'side',
			'default'
		);
	}

	public function render_config_meta_box(WP_Post $post): void {
		wp_nonce_field('wcag_treeview_meta', 'wcag_treeview_nonce');
		$config = get_post_meta($post->ID, '_wcag_treeview_config', true);
		$config = wp_parse_args($config, [
			'aria_label' => '',
			'expand_all' => false,
			'selection_single' => true,
			'show_caret' => true,
			'label_toggles' => true,
			'aria_live_announcements' => true,
		]);
		include WCAG_WP_PLUGIN_DIR . 'templates/admin/treeview-config-meta-box.php';
	}

	public function render_structure_meta_box(WP_Post $post): void {
		$nodes = get_post_meta($post->ID, '_wcag_treeview_nodes', true);
		if (!is_array($nodes)) {
			$nodes = [];
		}
		include WCAG_WP_PLUGIN_DIR . 'templates/admin/treeview-structure-meta-box.php';
	}

	public function render_preview_meta_box(WP_Post $post): void {
		$shortcode = '[wcag-treeview id="' . $post->ID . '"]';
		include WCAG_WP_PLUGIN_DIR . 'templates/admin/treeview-preview-meta-box.php';
	}

	public function save_meta_boxes(int $post_id): void {
		if (!isset($_POST['wcag_treeview_nonce']) || !wp_verify_nonce($_POST['wcag_treeview_nonce'], 'wcag_treeview_meta')) {
			return;
		}
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}
		$config_in = $_POST['wcag_treeview_config'] ?? [];
		$nodes_json = stripslashes($_POST['wcag_treeview_nodes_json'] ?? '');
		$nodes = json_decode($nodes_json, true);
		if (!is_array($nodes)) {
			$nodes = isset($_POST['wcag_treeview_nodes']) && is_array($_POST['wcag_treeview_nodes']) ? array_values($_POST['wcag_treeview_nodes']) : [];
		}
		$sanitized = [];
		foreach ($nodes as $node) {
			$sanitized[] = [
				'id' => sanitize_text_field($node['id'] ?? ''),
				'label' => sanitize_text_field($node['label'] ?? ''),
				'parent' => sanitize_text_field($node['parent'] ?? ''),
				'expanded' => (bool)($node['expanded'] ?? false),
				'url' => esc_url_raw($node['url'] ?? ''),
			];
		}
		update_post_meta($post_id, '_wcag_treeview_nodes', $sanitized);

		$sanitized_config = [
			'aria_label' => sanitize_text_field($config_in['aria_label'] ?? ''),
			'expand_all' => (bool)($config_in['expand_all'] ?? false),
			'selection_single' => (bool)($config_in['selection_single'] ?? true),
			'show_caret' => (bool)($config_in['show_caret'] ?? true),
			'label_toggles' => (bool)($config_in['label_toggles'] ?? true),
			'aria_live_announcements' => (bool)($config_in['aria_live_announcements'] ?? true),
		];
		update_post_meta($post_id, '_wcag_treeview_config', $sanitized_config);
	}

	public function enqueue_admin_assets($hook): void {
		if (!in_array($hook, ['post.php', 'post-new.php'])) {
			return;
		}
		$screen = function_exists('get_current_screen') ? get_current_screen() : null;
		if (!$screen || $screen->post_type !== $this->post_type) {
			return;
		}
		wp_enqueue_style(
			'wcag-wp-treeview-admin',
			WCAG_WP_ASSETS_URL . 'css/treeview-admin.css',
			['wcag-wp-admin'],
			WCAG_WP_VERSION
		);
		wp_enqueue_script(
			'wcag-wp-treeview-admin',
			WCAG_WP_ASSETS_URL . 'js/treeview-admin.js',
			['jquery','jquery-ui-sortable'],
			WCAG_WP_VERSION,
			true
		);
	}

	public function enqueue_assets(): void {
		global $post;
		$load = false;
		if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'wcag-treeview')) {
			$load = true;
		}
		if (is_singular($this->post_type)) {
			$load = true;
		}
		if ($load) {
			wp_enqueue_style(
				'wcag-wp-treeview-frontend',
				WCAG_WP_ASSETS_URL . 'css/treeview-frontend.css',
				['wcag-wp-frontend'],
				WCAG_WP_VERSION
			);
			wp_enqueue_script(
				'wcag-wp-treeview-frontend',
				WCAG_WP_ASSETS_URL . 'js/treeview-frontend.js',
				['jquery','wcag-wp-frontend'],
				WCAG_WP_VERSION,
				true
			);
		}
	}

	public function render_shortcode(array $atts): string {
		$atts = shortcode_atts([
			'id' => 0,
			'class' => ''
		], $atts, 'wcag-treeview');

		$tree_id = absint($atts['id']);
		if (!$tree_id) {
			return '<div class="wcag-wp-error">' . __('ID treeview richiesto', 'wcag-wp') . '</div>';
		}

		$config = get_post_meta($tree_id, '_wcag_treeview_config', true);
		$nodes = get_post_meta($tree_id, '_wcag_treeview_nodes', true);
		if (!is_array($nodes)) {
			$nodes = [];
		}
		$config = is_array($config) ? $config : [];
		$classes = 'wcag-treeview ' . sanitize_html_class($atts['class']);
		ob_start();
		include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-treeview.php';
		return ob_get_clean();
	}
}

// Initialize component
WCAG_WP_Treeview::get_instance();
