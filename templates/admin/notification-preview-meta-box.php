<?php
/**
 * Template: Notification Preview & Shortcode Meta Box
 * 
 * @package WCAG_WP
 * @var WP_Post $post Current post object
 * @var string $shortcode Generated shortcode
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wcag-notification-preview-meta-box">
    
    <!-- Shortcode Section -->
    <div class="wcag-shortcode-section">
        <h4>
            <span class="dashicons dashicons-shortcode"></span>
            <?php esc_html_e('Shortcode', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-shortcode-container">
            <input type="text" 
                   id="wcag-notification-shortcode" 
                   value="<?php echo esc_attr($shortcode); ?>" 
                   readonly 
                   class="widefat code">
            <button type="button" 
                    id="copy-shortcode-btn" 
                    class="button button-secondary"
                    title="<?php esc_attr_e('Copia shortcode negli appunti', 'wcag-wp'); ?>">
                <span class="dashicons dashicons-clipboard"></span>
                <?php esc_html_e('Copia', 'wcag-wp'); ?>
            </button>
        </div>
        
        <p class="description">
            <?php esc_html_e('Usa questo shortcode per inserire la notifica in pagine, post o widget.', 'wcag-wp'); ?>
        </p>
    </div>
    
    <!-- Usage Examples -->
    <div class="wcag-usage-examples" style="margin-top: 20px;">
        <h4>
            <span class="dashicons dashicons-info"></span>
            <?php esc_html_e('Esempi di Utilizzo', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-examples-list">
            <div class="wcag-example">
                <strong><?php esc_html_e('Base:', 'wcag-wp'); ?></strong>
                <code>[wcag-notification id="<?php echo esc_attr($post->ID); ?>"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Override tipo:', 'wcag-wp'); ?></strong>
                <code>[wcag-notification id="<?php echo esc_attr($post->ID); ?>" type="success"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Non chiudibile:', 'wcag-wp'); ?></strong>
                <code>[wcag-notification id="<?php echo esc_attr($post->ID); ?>" dismissible="false"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Auto-chiusura:', 'wcag-wp'); ?></strong>
                <code>[wcag-notification id="<?php echo esc_attr($post->ID); ?>" auto_dismiss="true"]</code>
            </div>
            
            <div class="wcag-example">
                <strong><?php esc_html_e('Classe custom:', 'wcag-wp'); ?></strong>
                <code>[wcag-notification id="<?php echo esc_attr($post->ID); ?>" class="my-notification"]</code>
            </div>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="wcag-notification-stats" style="margin-top: 20px;">
        <h4>
            <span class="dashicons dashicons-chart-bar"></span>
            <?php esc_html_e('Statistiche WCAG', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-stats-grid">
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('Compliance:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value wcag-stat-success">WCAG 2.1 AA</span>
            </div>
            
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('ARIA Live:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value" id="aria-live-value">polite</span>
            </div>
            
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('Keyboard:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value wcag-stat-success">✓ Accessibile</span>
            </div>
            
            <div class="wcag-stat-item">
                <span class="wcag-stat-label"><?php esc_html_e('Screen Reader:', 'wcag-wp'); ?></span>
                <span class="wcag-stat-value wcag-stat-success">✓ Supportato</span>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="wcag-quick-actions" style="margin-top: 20px;">
        <h4>
            <span class="dashicons dashicons-admin-tools"></span>
            <?php esc_html_e('Azioni Rapide', 'wcag-wp'); ?>
        </h4>
        
        <div class="wcag-actions-grid">
            <button type="button" 
                    id="test-notification-btn" 
                    class="button button-secondary">
                <span class="dashicons dashicons-visibility"></span>
                <?php esc_html_e('Test Anteprima', 'wcag-wp'); ?>
            </button>
            
            <button type="button" 
                    id="duplicate-notification-btn" 
                    class="button button-secondary">
                <span class="dashicons dashicons-admin-page"></span>
                <?php esc_html_e('Duplica', 'wcag-wp'); ?>
            </button>
            
            <button type="button" 
                    id="export-notification-btn" 
                    class="button button-secondary">
                <span class="dashicons dashicons-download"></span>
                <?php esc_html_e('Esporta', 'wcag-wp'); ?>
            </button>
        </div>
    </div>
    
</div>

<style>
.wcag-notification-preview-meta-box h4 {
    margin: 0 0 10px 0;
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 600;
}

.wcag-shortcode-container {
    display: flex;
    gap: 10px;
    align-items: center;
}

.wcag-shortcode-container input {
    flex: 1;
}

.wcag-examples-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.wcag-example {
    padding: 8px;
    background: #f6f7f7;
    border-radius: 4px;
    border-left: 3px solid #2271b1;
}

.wcag-example code {
    display: block;
    margin-top: 4px;
    font-size: 12px;
}

.wcag-stats-grid,
.wcag-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
}

.wcag-stat-item {
    display: flex;
    flex-direction: column;
    padding: 10px;
    background: #f9f9f9;
    border-radius: 4px;
    text-align: center;
}

.wcag-stat-label {
    font-size: 11px;
    color: #666;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.wcag-stat-value {
    font-weight: 600;
    font-size: 13px;
}

.wcag-stat-success {
    color: #00a32a;
}

.wcag-actions-grid button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 8px 12px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy shortcode functionality
    const copyBtn = document.getElementById('copy-shortcode-btn');
    const shortcodeInput = document.getElementById('wcag-notification-shortcode');
    
    if (copyBtn && shortcodeInput) {
        copyBtn.addEventListener('click', function() {
            shortcodeInput.select();
            shortcodeInput.setSelectionRange(0, 99999); // For mobile devices
            
            try {
                document.execCommand('copy');
                
                // Visual feedback
                const originalText = copyBtn.innerHTML;
                copyBtn.innerHTML = '<span class="dashicons dashicons-yes"></span> Copiato!';
                copyBtn.classList.add('button-primary');
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                    copyBtn.classList.remove('button-primary');
                }, 2000);
                
            } catch (err) {
                console.error('Errore nella copia:', err);
            }
        });
    }
    
    // Update ARIA live value based on notification type
    const typeSelect = document.getElementById('notification_type');
    const ariaLiveValue = document.getElementById('aria-live-value');
    
    if (typeSelect && ariaLiveValue) {
        function updateAriaLive() {
            const selectedOption = typeSelect.options[typeSelect.selectedIndex];
            const ariaLive = selectedOption.getAttribute('data-aria-live') || 'polite';
            ariaLiveValue.textContent = ariaLive;
            
            // Update color based on priority
            ariaLiveValue.className = 'wcag-stat-value';
            if (ariaLive === 'assertive') {
                ariaLiveValue.classList.add('wcag-stat-warning');
            } else {
                ariaLiveValue.classList.add('wcag-stat-success');
            }
        }
        
        typeSelect.addEventListener('change', updateAriaLive);
        updateAriaLive(); // Initial update
    }
    
    // Quick actions
    const testBtn = document.getElementById('test-notification-btn');
    if (testBtn) {
        testBtn.addEventListener('click', function() {
            // This will be implemented in the admin JavaScript file
            if (typeof window.wcagTestNotification === 'function') {
                window.wcagTestNotification();
            }
        });
    }
});
</script>

