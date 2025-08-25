<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * WCAG Treegrid Component
 * 
 * Implementazione del pattern ARIA Treegrid (APG) per tabelle gerarchiche.
 * Navigazione con frecce, Home/End, PageUp/PageDown, espansione/collasso righe.
 */
class WCAG_WP_Treegrid {
	private static $instance = null;
	private string $post_type = 'wcag_treegrid';

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
		if (did_action('init')) {
			$this->register_post_type();
		} else {
			add_action('init', [$this, 'register_post_type']);
		}

		add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
		add_action('save_post', [$this, 'save_meta_boxes']);

		add_shortcode('wcag-treegrid', [$this, 'render_shortcode']);
		add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
	}

	public function register_post_type(): void {
		$labels = [
			'name' => __('WCAG Treegrid', 'wcag-wp'),
			'singular_name' => __('Treegrid', 'wcag-wp'),
		];
		$args = [
			'labels' => $labels,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => 'wcag-wp-main',
			'supports' => ['title'],
			'rewrite' => false,
			'show_in_rest' => false,
			'menu_icon' => 'dashicons-editor-table',
		];
		register_post_type($this->post_type, $args);
	}

	public function add_meta_boxes(): void {
		add_meta_box(
			'wcag_treegrid_config',
			__('Configurazione', 'wcag-wp'),
			[$this, 'render_config_meta_box'],
			$this->post_type,
			'side',
			'default'
		);
		add_meta_box(
			'wcag_treegrid_columns',
			__('Colonne', 'wcag-wp'),
			[$this, 'render_columns_meta_box'],
			$this->post_type,
			'normal',
			'default'
		);
		add_meta_box(
			'wcag_treegrid_data',
			__('Dati Treegrid', 'wcag-wp'),
			[$this, 'render_data_meta_box'],
			$this->post_type,
			'normal',
			'high'
		);
		add_meta_box(
			'wcag_treegrid_preview',
			__('Anteprima & Shortcode', 'wcag-wp'),
			[$this, 'render_preview_meta_box'],
			$this->post_type,
			'side',
			'default'
		);
	}

	public function render_config_meta_box(WP_Post $post): void {
		wp_nonce_field('wcag_treegrid_meta', 'wcag_treegrid_nonce');
		$config = get_post_meta($post->ID, '_wcag_treegrid_config', true);
		$config = wp_parse_args($config, [
			'aria_label' => '',
			'expand_all' => false,
			'show_lines' => true,
		]);
		include WCAG_WP_PLUGIN_DIR . 'templates/admin/treegrid-config-meta-box.php';
	}

	public function render_columns_meta_box(WP_Post $post): void {
		$columns = get_post_meta($post->ID, '_wcag_treegrid_columns', true);
		if (!is_array($columns)) { $columns = []; }
		include WCAG_WP_PLUGIN_DIR . 'templates/admin/treegrid-columns-meta-box.php';
	}

	public function render_data_meta_box(WP_Post $post): void {
		$data = get_post_meta($post->ID, '_wcag_treegrid_rows', true);
		if (!is_array($data)) { $data = []; }
		include WCAG_WP_PLUGIN_DIR . 'templates/admin/treegrid-data-meta-box.php';
	}

	public function render_preview_meta_box(WP_Post $post): void {
		$shortcode = '[wcag-treegrid id="' . $post->ID . '"]';
		include WCAG_WP_PLUGIN_DIR . 'templates/admin/treegrid-preview-meta-box.php';
	}

	public function save_meta_boxes(int $post_id): void {
		if (!isset($_POST['wcag_treegrid_nonce']) || !wp_verify_nonce($_POST['wcag_treegrid_nonce'], 'wcag_treegrid_meta')) {
			return;
		}
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}
		$config_in = $_POST['wcag_treegrid_config'] ?? [];
		$columns_in = isset($_POST['wcag_treegrid_columns']) && is_array($_POST['wcag_treegrid_columns']) ? array_values($_POST['wcag_treegrid_columns']) : [];
		$rows_json = stripslashes($_POST['wcag_treegrid_rows_json'] ?? '');
		$rows = json_decode($rows_json, true);
		if (!is_array($rows)) {
			$rows = isset($_POST['wcag_treegrid_rows']) && is_array($_POST['wcag_treegrid_rows']) ? array_values($_POST['wcag_treegrid_rows']) : [];
		}
		$sanitized = [];
		foreach ($rows as $row) {
			$sanitized[] = [
				'id' => sanitize_text_field($row['id'] ?? ''),
				'parent' => sanitize_text_field($row['parent'] ?? ''),
				'level' => absint($row['level'] ?? 1),
				'expanded' => (bool)($row['expanded'] ?? false),
				'cells' => array_map('sanitize_text_field', isset($row['cells']) && is_array($row['cells']) ? $row['cells'] : []),
			];
		}
		update_post_meta($post_id, '_wcag_treegrid_rows', $sanitized);

		$sanitized_columns = [];
		foreach ($columns_in as $col) {
			if (($col['key'] ?? '') === '') { continue; }
			$sanitized_columns[] = [
				'key' => sanitize_key($col['key']),
				'label' => sanitize_text_field($col['label'] ?? ''),
				'width' => sanitize_text_field($col['width'] ?? ''),
			];
		}
		update_post_meta($post_id, '_wcag_treegrid_columns', $sanitized_columns);

		$sanitized_config = [
			'aria_label' => sanitize_text_field($config_in['aria_label'] ?? ''),
			'expand_all' => (bool)($config_in['expand_all'] ?? false),
			'show_lines' => (bool)($config_in['show_lines'] ?? true),
		];
		update_post_meta($post_id, '_wcag_treegrid_config', $sanitized_config);
	}

	public function enqueue_assets(): void {
		global $post;
		$load = false;
		if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'wcag-treegrid')) {
			$load = true;
		}
		if (is_singular($this->post_type)) {
			$load = true;
		}
		if ($load) {
			wp_enqueue_style(
				'wcag-wp-treegrid-frontend',
				WCAG_WP_ASSETS_URL . 'css/treegrid-frontend.css',
				['wcag-wp-frontend'],
				WCAG_WP_VERSION
			);
			wp_enqueue_script(
				'wcag-wp-treegrid-frontend',
				WCAG_WP_ASSETS_URL . 'js/treegrid-frontend.js',
				['jquery','wcag-wp-frontend'],
				WCAG_WP_VERSION,
				true
			);
			wp_enqueue_style(
				'wcag-wp-treegrid-admin',
				WCAG_WP_ASSETS_URL . 'css/treegrid-admin.css',
				['wcag-wp-admin'],
				WCAG_WP_VERSION
			);
			wp_enqueue_script(
				'wcag-wp-treegrid-admin',
				WCAG_WP_ASSETS_URL . 'js/treegrid-admin.js',
				['jquery','jquery-ui-sortable'],
				WCAG_WP_VERSION,
				true
			);
		}
	}

	public function render_shortcode(array $atts): string {
		$atts = shortcode_atts([
			'id' => 0,
			'class' => ''
		], $atts, 'wcag-treegrid');

		$grid_id = absint($atts['id']);
		if (!$grid_id) {
			return '<div class="wcag-wp-error">' . __('ID treegrid richiesto', 'wcag-wp') . '</div>';
		}

		$config = get_post_meta($grid_id, '_wcag_treegrid_config', true);
		$columns = get_post_meta($grid_id, '_wcag_treegrid_columns', true);
		$data = get_post_meta($grid_id, '_wcag_treegrid_rows', true);
		if (!is_array($data)) {
			$data = [];
		}
		$columns = is_array($columns) ? $columns : [];
		$config = is_array($config) ? $config : [];

		$classes = 'wcag-treegrid ' . sanitize_html_class($atts['class']);
		ob_start();
		include WCAG_WP_PLUGIN_DIR . 'templates/frontend/wcag-treegrid.php';
		return ob_get_clean();
	}
}

WCAG_WP_Treegrid::get_instance();
