<?php
declare(strict_types=1);

/**
 * Plugin Name: WCAG-WP - Componenti Accessibili WordPress
 * Plugin URI: https://github.com/stefanochermazts/wcag-wp
 * Description: Plugin WordPress per componenti accessibili WCAG 2.1 AA compliant. Gestione tabelle, accordion, TOC e altri elementi interattivi con pieno supporto tastiera e screen reader.
 * Version: 1.0.0
 * Author: Stefano Chermazts
 * Author URI: https://github.com/stefanochermazts
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wcag-wp
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 * 
 * WCAG-WP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * WCAG-WP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Plugin version
define('WCAG_WP_VERSION', '1.0.0');

// Plugin paths
define('WCAG_WP_PLUGIN_FILE', __FILE__);
define('WCAG_WP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WCAG_WP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WCAG_WP_ASSETS_URL', WCAG_WP_PLUGIN_URL . 'assets/');

// Minimum requirements
define('WCAG_WP_MIN_WP_VERSION', '6.0');
define('WCAG_WP_MIN_PHP_VERSION', '7.4');

/**
 * Check system requirements before plugin initialization
 * 
 * @return bool True if requirements are met, false otherwise
 */
function wcag_wp_check_requirements(): bool {
    global $wp_version;
    
    // Check WordPress version
    if (version_compare($wp_version, WCAG_WP_MIN_WP_VERSION, '<')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>';
            printf(
                esc_html__('WCAG-WP richiede WordPress %s o superiore. Versione attuale: %s', 'wcag-wp'),
                WCAG_WP_MIN_WP_VERSION,
                $GLOBALS['wp_version']
            );
            echo '</p></div>';
        });
        return false;
    }
    
    // Check PHP version
    if (version_compare(PHP_VERSION, WCAG_WP_MIN_PHP_VERSION, '<')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>';
            printf(
                esc_html__('WCAG-WP richiede PHP %s o superiore. Versione attuale: %s', 'wcag-wp'),
                WCAG_WP_MIN_PHP_VERSION,
                PHP_VERSION
            );
            echo '</p></div>';
        });
        return false;
    }
    
    return true;
}

/**
 * Plugin activation hook
 * 
 * @return void
 */
function wcag_wp_activate(): void {
    // Check requirements during activation
    if (!wcag_wp_check_requirements()) {
        wp_die(
            esc_html__('WCAG-WP non può essere attivato. Controlla i requisiti di sistema.', 'wcag-wp'),
            esc_html__('Errore Attivazione Plugin', 'wcag-wp'),
            ['back_link' => true]
        );
    }
    
    // Set default options
    if (!get_option('wcag_wp_version')) {
        add_option('wcag_wp_version', WCAG_WP_VERSION);
        add_option('wcag_wp_settings', [
            'design_system' => [
                'color_scheme' => 'default',
                'font_family' => 'system-ui',
                'focus_outline' => true,
                'reduce_motion' => false
            ],
            'accessibility' => [
                'screen_reader_support' => true,
                'keyboard_navigation' => true,
                'high_contrast' => false
            ]
        ]);
    }
    
    // Flush rewrite rules for custom post types
    flush_rewrite_rules();
}

/**
 * Plugin deactivation hook
 * 
 * @return void
 */
function wcag_wp_deactivate(): void {
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Clear any scheduled cron jobs
    wp_clear_scheduled_hook('wcag_wp_daily_cleanup');
}

/**
 * Plugin uninstall (will be in separate uninstall.php file)
 * 
 * @return void
 */
function wcag_wp_uninstall(): void {
    // This will be handled in uninstall.php
}

// Register activation/deactivation hooks
register_activation_hook(__FILE__, 'wcag_wp_activate');
register_deactivation_hook(__FILE__, 'wcag_wp_deactivate');

// Initialize plugin only if requirements are met
if (wcag_wp_check_requirements()) {
    // Load plugin textdomain for translations
    add_action('plugins_loaded', function() {
        load_plugin_textdomain(
            'wcag-wp',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    });
    
    // Include main plugin class
    require_once WCAG_WP_PLUGIN_DIR . 'src/class-wcag-wp.php';
    
    // Initialize plugin
    add_action('plugins_loaded', function() {
        if (class_exists('WCAG_WP')) {
            WCAG_WP::get_instance();
        }
    });
}

/**
 * Plugin debug logging function
 * 
 * @param mixed $message Message to log
 * @param string $level Log level (info, warning, error)
 * @return void
 */
function wcag_wp_log($message, string $level = 'info'): void {
    if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
        $timestamp = date('Y-m-d H:i:s');
        $formatted_message = sprintf(
            '[%s] WCAG-WP %s: %s',
            $timestamp,
            strtoupper($level),
            is_array($message) || is_object($message) ? print_r($message, true) : $message
        );
        error_log($formatted_message);
    }
}

/**
 * Get plugin instance helper function
 * 
 * @return WCAG_WP|null
 */
function wcag_wp(): ?WCAG_WP {
    if (class_exists('WCAG_WP')) {
        return WCAG_WP::get_instance();
    }
    return null;
}
