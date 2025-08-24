/**
 * WCAG Notifications Admin JavaScript
 * 
 * Handles admin interface interactions:
 * - Live preview updates
 * - Shortcode copying
 * - Configuration changes
 * - Testing functionality
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * Notification Admin Manager
     */
    class WcagNotificationAdmin {
        constructor() {
            this.init();
        }
        
        /**
         * Initialize admin functionality
         */
        init() {
            this.bindEvents();
            this.initializeLivePreview();
            this.setupShortcodeCopy();
        }
        
        /**
         * Bind admin events
         */
        bindEvents() {
            // Configuration changes trigger preview update
            $(document).on('change', '.wcag-notification-config input, .wcag-notification-config select', () => {
                this.updateLivePreview();
            });
            
            // Auto-dismiss checkbox toggle
            $(document).on('change', '#notification_auto_dismiss', function() {
                const settings = $('#auto_dismiss_settings');
                if (this.checked) {
                    settings.slideDown(200);
                } else {
                    settings.slideUp(200);
                }
            });
            
            // Notification type change updates ARIA live value
            $(document).on('change', '#notification_type', () => {
                this.updateAriaLiveDisplay();
            });
            
            // Quick action buttons
            $(document).on('click', '#test-notification-btn', () => {
                this.testNotification();
            });
            
            $(document).on('click', '#duplicate-notification-btn', () => {
                this.duplicateNotification();
            });
            
            $(document).on('click', '#export-notification-btn', () => {
                this.exportNotification();
            });
        }
        
        /**
         * Initialize live preview
         */
        initializeLivePreview() {
            this.updateLivePreview();
        }
        
        /**
         * Update live preview based on current configuration
         */
        updateLivePreview() {
            const container = $('#notification-preview-container');
            if (!container.length) return;
            
            // Show loading state
            container.addClass('wcag-notification-preview-updating');
            
            // Get current configuration
            const config = this.getCurrentConfig();
            const content = this.getPreviewContent();
            
            // Generate preview HTML
            const previewHtml = this.generatePreviewHtml(config, content);
            
            // Update preview with animation
            setTimeout(() => {
                container.html(previewHtml);
                container.removeClass('wcag-notification-preview-updating');
                
                // Initialize preview notification behavior
                this.initializePreviewNotification(container.find('.wcag-wp-notification'));
            }, 150);
        }
        
        /**
         * Get current notification configuration from form
         * 
         * @returns {Object} Configuration object
         */
        getCurrentConfig() {
            return {
                type: $('#notification_type').val() || 'info',
                dismissible: $('#notification_dismissible').is(':checked'),
                autoDismiss: $('#notification_auto_dismiss').is(':checked'),
                autoDismissDelay: parseInt($('#auto_dismiss_delay').val()) || 5000,
                showIcon: $('#notification_show_icon').is(':checked'),
                position: $('#notification_position').val() || 'inline',
                customClass: $('#notification_custom_class').val() || ''
            };
        }
        
        /**
         * Get preview content from post editor
         * 
         * @returns {Object} Content object with title and message
         */
        getPreviewContent() {
            const title = $('#title').val() || 'Titolo Notifica di Esempio';
            let message = '';
            
            // Try to get content from different editor types
            if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                message = tinymce.get('content').getContent();
            } else if ($('#content').length) {
                message = $('#content').val();
            }
            
            if (!message) {
                message = 'Questo è un esempio di notifica WCAG accessibile. Il contenuto viene preso dal campo editor principale.';
            }
            
            return { title, message };
        }
        
        /**
         * Generate preview HTML
         * 
         * @param {Object} config Configuration
         * @param {Object} content Content object
         * @returns {string} HTML string
         */
        generatePreviewHtml(config, content) {
            const typeInfo = window.wcagNotificationsAdmin?.types?.[config.type] || {
                label: 'Info',
                icon: 'ℹ️',
                color: '#2271b1'
            };
            
            const notificationId = 'wcag-notification-preview';
            const cssClasses = [
                'wcag-wp-notification',
                `wcag-wp-notification--${config.type}`,
                `wcag-wp-notification--position-${config.position}`
            ];
            
            if (config.dismissible) {
                cssClasses.push('wcag-wp-notification--dismissible');
            }
            
            if (config.autoDismiss) {
                cssClasses.push('wcag-wp-notification--auto-dismiss');
            }
            
            if (config.customClass) {
                cssClasses.push(config.customClass);
            }
            
            let html = `
                <div id="${notificationId}" 
                     class="${cssClasses.join(' ')}"
                     role="alert"
                     aria-live="polite"
                     style="position: relative; margin: 0;">
                    
                    <div class="wcag-wp-notification__container">
            `;
            
            // Icon
            if (config.showIcon) {
                html += `
                    <div class="wcag-wp-notification__icon" aria-hidden="true">
                        <span class="wcag-wp-notification__icon-symbol">${typeInfo.icon}</span>
                    </div>
                `;
            }
            
            // Content
            html += `
                <div class="wcag-wp-notification__content">
            `;
            
            if (content.title) {
                html += `
                    <div class="wcag-wp-notification__title">${this.escapeHtml(content.title)}</div>
                `;
            }
            
            html += `
                    <div class="wcag-wp-notification__message">${content.message}</div>
                </div>
            `;
            
            // Close button
            if (config.dismissible) {
                html += `
                    <button type="button" 
                            class="wcag-wp-notification__close"
                            aria-label="Chiudi notifica"
                            title="Chiudi questa notifica">
                        <span class="wcag-wp-notification__close-icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M8 7.293l2.146-2.147a.5.5 0 01.708.708L8.707 8l2.147 2.146a.5.5 0 01-.708.708L8 8.707l-2.146 2.147a.5.5 0 01-.708-.708L7.293 8 5.146 5.854a.5.5 0 01.708-.708L8 7.293z"/>
                            </svg>
                        </span>
                    </button>
                `;
            }
            
            html += `
                    </div>
            `;
            
            // Progress bar for auto-dismiss
            if (config.autoDismiss) {
                html += `
                    <div class="wcag-wp-notification__progress" aria-hidden="true">
                        <div class="wcag-wp-notification__progress-bar" 
                             style="animation-duration: ${config.autoDismissDelay}ms; animation-play-state: paused;"></div>
                    </div>
                `;
            }
            
            html += `</div>`;
            
            return html;
        }
        
        /**
         * Initialize preview notification behavior
         * 
         * @param {jQuery} $notification Notification element
         */
        initializePreviewNotification($notification) {
            if (!$notification.length) return;
            
            // Close button functionality
            $notification.find('.wcag-wp-notification__close').on('click', function(e) {
                e.preventDefault();
                $notification.fadeOut(200);
                
                // Show reset message
                setTimeout(() => {
                    $('#notification-preview-container').html(`
                        <div class="notification-preview-placeholder">
                            Anteprima nascosta. Modifica la configurazione per rigenerare.
                        </div>
                    `);
                }, 200);
            });
            
            // Hover effects for auto-dismiss
            if ($notification.hasClass('wcag-wp-notification--auto-dismiss')) {
                const $progressBar = $notification.find('.wcag-wp-notification__progress-bar');
                
                $notification.on('mouseenter focusin', function() {
                    $progressBar.css('animation-play-state', 'paused');
                });
                
                $notification.on('mouseleave focusout', function() {
                    $progressBar.css('animation-play-state', 'running');
                });
            }
        }
        
        /**
         * Update ARIA live display in preview meta box
         */
        updateAriaLiveDisplay() {
            const selectedType = $('#notification_type').val();
            const typeInfo = window.wcagNotificationsAdmin?.types?.[selectedType];
            
            if (typeInfo) {
                const $ariaLiveValue = $('#aria-live-value');
                if ($ariaLiveValue.length) {
                    $ariaLiveValue.text(typeInfo.aria_live);
                    
                    // Update styling based on priority
                    $ariaLiveValue.removeClass('wcag-stat-success wcag-stat-warning');
                    if (typeInfo.aria_live === 'assertive') {
                        $ariaLiveValue.addClass('wcag-stat-warning');
                    } else {
                        $ariaLiveValue.addClass('wcag-stat-success');
                    }
                }
            }
        }
        
        /**
         * Setup shortcode copy functionality
         */
        setupShortcodeCopy() {
            $(document).on('click', '#copy-shortcode-btn', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const $input = $('#wcag-notification-shortcode');
                
                if (!$input.length) return;
                
                // Select and copy text
                $input.select();
                $input[0].setSelectionRange(0, 99999); // For mobile devices
                
                try {
                    document.execCommand('copy');
                    
                    // Visual feedback
                    const originalHtml = $button.html();
                    $button.html('<span class="dashicons dashicons-yes"></span> Copiato!')
                           .addClass('wcag-copied');
                    
                    setTimeout(() => {
                        $button.html(originalHtml).removeClass('wcag-copied');
                    }, 2000);
                    
                } catch (err) {
                    console.error('Errore nella copia del shortcode:', err);
                    
                    // Fallback: show selection
                    $button.text('Selezionato - Copia manualmente');
                    setTimeout(() => {
                        $button.html('<span class="dashicons dashicons-clipboard"></span> Copia');
                    }, 3000);
                }
            });
        }
        
        /**
         * Test notification functionality
         */
        testNotification() {
            const config = this.getCurrentConfig();
            const content = this.getPreviewContent();
            
            // Create test notification in a modal or overlay
            const testHtml = this.generatePreviewHtml(config, content);
            
            // Create overlay
            const $overlay = $(`
                <div id="wcag-notification-test-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 100000;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                ">
                    <div style="
                        background: white;
                        padding: 20px;
                        border-radius: 8px;
                        max-width: 600px;
                        width: 100%;
                        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                    ">
                        <h3 style="margin: 0 0 15px 0;">Test Notifica WCAG</h3>
                        ${testHtml}
                        <div style="margin-top: 15px; text-align: center;">
                            <button type="button" class="button button-primary" onclick="$('#wcag-notification-test-overlay').remove();">
                                Chiudi Test
                            </button>
                        </div>
                    </div>
                </div>
            `);
            
            $('body').append($overlay);
            
            // Initialize test notification
            this.initializePreviewNotification($overlay.find('.wcag-wp-notification'));
            
            // Close on Escape key
            $(document).on('keydown.wcag-test', function(e) {
                if (e.key === 'Escape') {
                    $overlay.remove();
                    $(document).off('keydown.wcag-test');
                }
            });
        }
        
        /**
         * Duplicate notification functionality
         */
        duplicateNotification() {
            if (confirm('Vuoi duplicare questa notifica?')) {
                // Get current post ID
                const postId = $('#post_ID').val();
                
                if (postId) {
                    // Redirect to new post with duplication parameters
                    window.location.href = `post-new.php?post_type=wcag_notification&duplicate=${postId}`;
                }
            }
        }
        
        /**
         * Export notification functionality
         */
        exportNotification() {
            const config = this.getCurrentConfig();
            const content = this.getPreviewContent();
            const postId = $('#post_ID').val();
            
            const exportData = {
                id: postId,
                title: content.title,
                content: content.message,
                config: config,
                export_date: new Date().toISOString(),
                plugin_version: '1.0.0'
            };
            
            // Create and download JSON file
            const dataStr = JSON.stringify(exportData, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            const url = URL.createObjectURL(dataBlob);
            
            const link = document.createElement('a');
            link.href = url;
            link.download = `wcag-notification-${postId || 'new'}.json`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            URL.revokeObjectURL(url);
        }
        
        /**
         * Escape HTML for safe insertion
         * 
         * @param {string} text Text to escape
         * @returns {string} Escaped text
         */
        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    }
    
    /**
     * Global functions for template use
     */
    window.wcagUpdateNotificationPreview = function() {
        if (window.wcagNotificationAdminInstance) {
            window.wcagNotificationAdminInstance.updateLivePreview();
        }
    };
    
    window.wcagTestNotification = function() {
        if (window.wcagNotificationAdminInstance) {
            window.wcagNotificationAdminInstance.testNotification();
        }
    };
    
    /**
     * Initialize when document is ready
     */
    $(document).ready(function() {
        // Only initialize on notification edit pages
        if ($('body').hasClass('post-type-wcag_notification')) {
            window.wcagNotificationAdminInstance = new WcagNotificationAdmin();
        }
    });
    
})(jQuery);

