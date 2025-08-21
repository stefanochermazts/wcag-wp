<?php
declare(strict_types=1);

/**
 * Uninstall script for WCAG-WP Plugin
 * 
 * This file is executed when the plugin is uninstalled (deleted) from WordPress.
 * It handles the complete cleanup of plugin data, options, and database tables.
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Prevent execution if not called by WordPress
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove plugin options
 * 
 * @return void
 */
function wcag_wp_remove_options(): void {
    $options_to_remove = [
        'wcag_wp_version',
        'wcag_wp_settings',
        'wcag_wp_tables_settings',
        'wcag_wp_design_system',
        'wcag_wp_accessibility_settings'
    ];
    
    foreach ($options_to_remove as $option) {
        delete_option($option);
        // Also remove from multisite if applicable
        delete_site_option($option);
    }
}

/**
 * Remove custom post types and their content
 * 
 * @return void
 */
function wcag_wp_remove_post_types(): void {
    global $wpdb;
    
    // Get all WCAG-WP custom post types
    $post_types = ['wcag_tables', 'wcag_accordion', 'wcag_slider'];
    
    foreach ($post_types as $post_type) {
        // Get all posts of this type
        $posts = get_posts([
            'post_type' => $post_type,
            'post_status' => 'any',
            'numberposts' => -1,
            'fields' => 'ids'
        ]);
        
        // Delete each post and its metadata
        foreach ($posts as $post_id) {
            // Delete post meta
            $wpdb->delete(
                $wpdb->postmeta,
                ['post_id' => $post_id],
                ['%d']
            );
            
            // Delete the post
            wp_delete_post($post_id, true);
        }
    }
}

/**
 * Remove custom database tables
 * 
 * @return void
 */
function wcag_wp_remove_custom_tables(): void {
    global $wpdb;
    
    // List of custom tables to remove
    $tables_to_remove = [
        $wpdb->prefix . 'wcag_wp_tables_data',
        $wpdb->prefix . 'wcag_wp_table_columns',
        $wpdb->prefix . 'wcag_wp_table_rows'
    ];
    
    foreach ($tables_to_remove as $table) {
        $wpdb->query("DROP TABLE IF EXISTS `{$table}`");
    }
}

/**
 * Remove uploaded files and directories
 * 
 * @return void
 */
function wcag_wp_remove_uploads(): void {
    $upload_dir = wp_upload_dir();
    $wcag_wp_dir = $upload_dir['basedir'] . '/wcag-wp/';
    
    if (is_dir($wcag_wp_dir)) {
        wcag_wp_recursive_rmdir($wcag_wp_dir);
    }
}

/**
 * Recursively remove directory and its contents
 * 
 * @param string $dir Directory path
 * @return bool Success status
 */
function wcag_wp_recursive_rmdir(string $dir): bool {
    if (!is_dir($dir)) {
        return false;
    }
    
    $files = array_diff(scandir($dir), ['.', '..']);
    
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            wcag_wp_recursive_rmdir($path);
        } else {
            unlink($path);
        }
    }
    
    return rmdir($dir);
}

/**
 * Remove scheduled cron jobs
 * 
 * @return void
 */
function wcag_wp_remove_cron_jobs(): void {
    $cron_hooks = [
        'wcag_wp_daily_cleanup',
        'wcag_wp_weekly_maintenance',
        'wcag_wp_table_cache_cleanup'
    ];
    
    foreach ($cron_hooks as $hook) {
        wp_clear_scheduled_hook($hook);
    }
}

/**
 * Remove user capabilities
 * 
 * @return void
 */
function wcag_wp_remove_capabilities(): void {
    $capabilities = [
        'manage_wcag_tables',
        'edit_wcag_tables',
        'delete_wcag_tables',
        'publish_wcag_tables'
    ];
    
    $roles = ['administrator', 'editor', 'author'];
    
    foreach ($roles as $role_name) {
        $role = get_role($role_name);
        if ($role) {
            foreach ($capabilities as $capability) {
                $role->remove_cap($capability);
            }
        }
    }
}

/**
 * Clean up transients
 * 
 * @return void
 */
function wcag_wp_remove_transients(): void {
    global $wpdb;
    
    // Remove all transients with wcag_wp prefix
    $wpdb->query(
        "DELETE FROM {$wpdb->options} 
         WHERE option_name LIKE '_transient_wcag_wp_%' 
         OR option_name LIKE '_transient_timeout_wcag_wp_%'"
    );
    
    // Also remove from multisite
    if (is_multisite()) {
        $wpdb->query(
            "DELETE FROM {$wpdb->sitemeta} 
             WHERE meta_key LIKE '_site_transient_wcag_wp_%' 
             OR meta_key LIKE '_site_transient_timeout_wcag_wp_%'"
        );
    }
}

/**
 * Log uninstall process
 * 
 * @param string $message Log message
 * @return void
 */
function wcag_wp_uninstall_log(string $message): void {
    if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
        error_log('[WCAG-WP Uninstall] ' . $message);
    }
}

/**
 * Main uninstall process
 * 
 * @return void
 */
function wcag_wp_uninstall(): void {
    // Log start of uninstall
    wcag_wp_uninstall_log('Starting plugin uninstall process');
    
    try {
        // Remove plugin options
        wcag_wp_remove_options();
        wcag_wp_uninstall_log('Plugin options removed');
        
        // Remove custom post types and content
        wcag_wp_remove_post_types();
        wcag_wp_uninstall_log('Custom post types and content removed');
        
        // Remove custom database tables
        wcag_wp_remove_custom_tables();
        wcag_wp_uninstall_log('Custom database tables removed');
        
        // Remove uploaded files
        wcag_wp_remove_uploads();
        wcag_wp_uninstall_log('Uploaded files removed');
        
        // Remove scheduled cron jobs
        wcag_wp_remove_cron_jobs();
        wcag_wp_uninstall_log('Cron jobs removed');
        
        // Remove user capabilities
        wcag_wp_remove_capabilities();
        wcag_wp_uninstall_log('User capabilities removed');
        
        // Clean up transients
        wcag_wp_remove_transients();
        wcag_wp_uninstall_log('Transients cleaned up');
        
        // Flush rewrite rules
        flush_rewrite_rules();
        wcag_wp_uninstall_log('Rewrite rules flushed');
        
        wcag_wp_uninstall_log('Plugin uninstall completed successfully');
        
    } catch (Exception $e) {
        wcag_wp_uninstall_log('Uninstall error: ' . $e->getMessage());
    }
}

// Only run uninstall if called by WordPress and user has proper permissions
if (current_user_can('activate_plugins')) {
    // Run uninstall process
    wcag_wp_uninstall();
} else {
    wcag_wp_uninstall_log('Uninstall cancelled: insufficient user permissions');
}
