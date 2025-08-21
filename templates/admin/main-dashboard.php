<?php
declare(strict_types=1);

/**
 * Admin Main Dashboard Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Security check
if (!current_user_can('manage_options')) {
    wp_die(__('Non hai i permessi per accedere a questa pagina.', 'wcag-wp'));
}

$plugin_instance = wcag_wp();
$settings = $plugin_instance ? $plugin_instance->get_settings() : [];
?>

<div class="wrap wcag-wp-admin">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-universal-access-alt"></span>
        <?php esc_html_e('WCAG-WP Dashboard', 'wcag-wp'); ?>
    </h1>
    <span class="wcag-wp-version">v<?php echo esc_html(WCAG_WP_VERSION); ?></span>
    
    <hr class="wp-header-end">
    
    <!-- Welcome Panel -->
    <div class="wcag-wp-welcome-panel">
        <div class="welcome-panel-content">
            <h2><?php esc_html_e('Benvenuto in WCAG-WP', 'wcag-wp'); ?></h2>
            <p class="about-description">
                <?php esc_html_e('Il plugin per componenti WordPress 100% accessibili e conformi alle linee guida WCAG 2.1 AA.', 'wcag-wp'); ?>
            </p>
            
            <div class="welcome-panel-column-container">
                <div class="welcome-panel-column">
                    <h3><?php esc_html_e('🚀 Inizia Subito', 'wcag-wp'); ?></h3>
                    <p><?php esc_html_e('Crea la tua prima tabella accessibile in pochi clic.', 'wcag-wp'); ?></p>
                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=wcag_tables')); ?>" class="button button-primary">
                        <?php esc_html_e('Crea Tabella', 'wcag-wp'); ?>
                    </a>
                </div>
                
                <div class="welcome-panel-column">
                    <h3><?php esc_html_e('⚙️ Configurazione', 'wcag-wp'); ?></h3>
                    <p><?php esc_html_e('Personalizza il design system e le impostazioni di accessibilità.', 'wcag-wp'); ?></p>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=wcag-wp-settings')); ?>" class="button">
                        <?php esc_html_e('Impostazioni', 'wcag-wp'); ?>
                    </a>
                </div>
                
                <div class="welcome-panel-column">
                    <h3><?php esc_html_e('📖 Documentazione', 'wcag-wp'); ?></h3>
                    <p><?php esc_html_e('Scopri come utilizzare tutti i componenti accessibili.', 'wcag-wp'); ?></p>
                    <a href="https://github.com/stefanochermazts/wcag-wp#readme" class="button" target="_blank">
                        <?php esc_html_e('Vai alla Docs', 'wcag-wp'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="wcag-wp-stats-grid">
        <div class="wcag-wp-stat-card">
            <div class="stat-icon">
                <span class="dashicons dashicons-grid-view"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html(wp_count_posts('wcag_tables')->publish ?? 0); ?></h3>
                <p><?php esc_html_e('Tabelle Create', 'wcag-wp'); ?></p>
            </div>
        </div>
        
        <div class="wcag-wp-stat-card">
            <div class="stat-icon">
                <span class="dashicons dashicons-yes-alt"></span>
            </div>
            <div class="stat-content">
                <h3><?php esc_html_e('AA', 'wcag-wp'); ?></h3>
                <p><?php esc_html_e('Conformità WCAG', 'wcag-wp'); ?></p>
            </div>
        </div>
        
        <div class="wcag-wp-stat-card">
            <div class="stat-icon">
                <span class="dashicons dashicons-smartphone"></span>
            </div>
            <div class="stat-content">
                <h3><?php esc_html_e('100%', 'wcag-wp'); ?></h3>
                <p><?php esc_html_e('Responsive', 'wcag-wp'); ?></p>
            </div>
        </div>
        
        <div class="wcag-wp-stat-card">
            <div class="stat-icon">
                <span class="dashicons dashicons-performance"></span>
            </div>
            <div class="stat-content">
                <h3><?php esc_html_e('0', 'wcag-wp'); ?></h3>
                <p><?php esc_html_e('Dipendenze JS', 'wcag-wp'); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Components Overview -->
    <div class="wcag-wp-components-section">
        <h2><?php esc_html_e('Componenti Disponibili', 'wcag-wp'); ?></h2>
        
        <div class="wcag-wp-components-grid">
            <!-- Tables Component -->
            <div class="component-card available">
                <div class="component-header">
                    <span class="dashicons dashicons-grid-view"></span>
                    <h3><?php esc_html_e('Tabelle Accessibili', 'wcag-wp'); ?></h3>
                    <span class="status-badge available"><?php esc_html_e('Disponibile', 'wcag-wp'); ?></span>
                </div>
                <p><?php esc_html_e('Tabelle responsive con ordinamento, ricerca e pieno supporto screen reader.', 'wcag-wp'); ?></p>
                <div class="component-actions">
                    <a href="<?php echo esc_url(admin_url('edit.php?post_type=wcag_tables')); ?>" class="button">
                        <?php esc_html_e('Gestisci Tabelle', 'wcag-wp'); ?>
                    </a>
                </div>
            </div>
            
            <!-- Accordion Component -->
            <div class="component-card coming-soon">
                <div class="component-header">
                    <span class="dashicons dashicons-menu-alt3"></span>
                    <h3><?php esc_html_e('Accordion & Tab Panel', 'wcag-wp'); ?></h3>
                    <span class="status-badge coming-soon"><?php esc_html_e('Prossimamente', 'wcag-wp'); ?></span>
                </div>
                <p><?php esc_html_e('Accordion e tab panel con navigazione tastiera e supporto ARIA completo.', 'wcag-wp'); ?></p>
                <div class="component-actions">
                    <button class="button" disabled><?php esc_html_e('In Sviluppo', 'wcag-wp'); ?></button>
                </div>
            </div>
            
            <!-- TOC Component -->
            <div class="component-card coming-soon">
                <div class="component-header">
                    <span class="dashicons dashicons-list-view"></span>
                    <h3><?php esc_html_e('Table of Contents', 'wcag-wp'); ?></h3>
                    <span class="status-badge coming-soon"><?php esc_html_e('Prossimamente', 'wcag-wp'); ?></span>
                </div>
                <p><?php esc_html_e('Indice automatico dei contenuti con navigazione accessibile e smooth scroll.', 'wcag-wp'); ?></p>
                <div class="component-actions">
                    <button class="button" disabled><?php esc_html_e('In Sviluppo', 'wcag-wp'); ?></button>
                </div>
            </div>
            
            <!-- Slider Component -->
            <div class="component-card coming-soon">
                <div class="component-header">
                    <span class="dashicons dashicons-images-alt2"></span>
                    <h3><?php esc_html_e('Slider/Carousel', 'wcag-wp'); ?></h3>
                    <span class="status-badge coming-soon"><?php esc_html_e('Prossimamente', 'wcag-wp'); ?></span>
                </div>
                <p><?php esc_html_e('Carosello immagini con controlli tastiera e annunci screen reader.', 'wcag-wp'); ?></p>
                <div class="component-actions">
                    <button class="button" disabled><?php esc_html_e('In Sviluppo', 'wcag-wp'); ?></button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="wcag-wp-quick-actions">
        <h2><?php esc_html_e('Azioni Rapide', 'wcag-wp'); ?></h2>
        
        <div class="quick-actions-grid">
            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=wcag_tables')); ?>" class="quick-action-card">
                <span class="dashicons dashicons-plus-alt2"></span>
                <span><?php esc_html_e('Nuova Tabella', 'wcag-wp'); ?></span>
            </a>
            
            <a href="<?php echo esc_url(admin_url('admin.php?page=wcag-wp-settings')); ?>" class="quick-action-card">
                <span class="dashicons dashicons-admin-settings"></span>
                <span><?php esc_html_e('Impostazioni', 'wcag-wp'); ?></span>
            </a>
            
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=wcag_tables')); ?>" class="quick-action-card">
                <span class="dashicons dashicons-list-view"></span>
                <span><?php esc_html_e('Tutte le Tabelle', 'wcag-wp'); ?></span>
            </a>
            
            <a href="https://github.com/stefanochermazts/wcag-wp/issues" class="quick-action-card" target="_blank">
                <span class="dashicons dashicons-sos"></span>
                <span><?php esc_html_e('Supporto', 'wcag-wp'); ?></span>
            </a>
        </div>
    </div>
</div>

<!-- Admin CSS inline (temporaneo, sarà spostato in file separato) -->
<style>
.wcag-wp-admin {
    max-width: 1200px;
}

.wcag-wp-version {
    color: #666;
    font-size: 0.9em;
    margin-left: 10px;
}

.wcag-wp-welcome-panel {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    margin: 16px 0;
    padding: 23px 10px 0;
    position: relative;
}

.wcag-wp-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.wcag-wp-stat-card {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon .dashicons {
    font-size: 32px;
    color: #2271b1;
}

.stat-content h3 {
    margin: 0 0 5px 0;
    font-size: 24px;
    font-weight: 600;
}

.stat-content p {
    margin: 0;
    color: #666;
}

.wcag-wp-components-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.component-card {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    padding: 20px;
    transition: box-shadow 0.2s ease;
}

.component-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.component-card.coming-soon {
    opacity: 0.7;
}

.component-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.component-header .dashicons {
    font-size: 24px;
    color: #2271b1;
}

.component-header h3 {
    margin: 0;
    flex: 1;
}

.status-badge {
    padding: 2px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.available {
    background: #00a32a;
    color: white;
}

.status-badge.coming-soon {
    background: #dba617;
    color: white;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin: 20px 0;
}

.quick-action-card {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    padding: 20px;
    text-align: center;
    text-decoration: none;
    color: #1d2327;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.quick-action-card:hover {
    background: #f6f7f7;
    border-color: #2271b1;
    color: #2271b1;
    text-decoration: none;
}

.quick-action-card .dashicons {
    font-size: 32px;
}
</style>
