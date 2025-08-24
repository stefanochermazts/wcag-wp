/**
 * WCAG Notifications Frontend JavaScript
 * 
 * Handles accessible notification interactions:
 * - Keyboard navigation (Tab, Enter, Esc)
 * - Auto-dismiss functionality
 * - Screen reader announcements
 * - Animation management
 * - Focus management
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function() {
    'use strict';
    
    // Global notification manager
    window.wcagNotifications = window.wcagNotifications || {};
    
    /**
     * Notification Manager Class
     */
    class WcagNotificationManager {
        constructor() {
            this.notifications = new Map();
            this.init();
        }
        
        /**
         * Initialize notification system
         */
        init() {
            this.bindGlobalEvents();
            this.initializeExistingNotifications();
        }
        
        /**
         * Bind global keyboard events
         */
        bindGlobalEvents() {
            // Global Escape key handler
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeTopNotification();
                }
            });
            
            // Handle visibility change (pause auto-dismiss when tab not active)
            document.addEventListener('visibilitychange', () => {
                this.handleVisibilityChange();
            });
        }
        
        /**
         * Initialize existing notifications on page load
         */
        initializeExistingNotifications() {
            const notifications = document.querySelectorAll('.wcag-wp-notification');
            notifications.forEach(notification => {
                this.initNotification(notification);
            });
        }
        
        /**
         * Initialize a single notification
         * 
         * @param {HTMLElement} element Notification element
         */
        initNotification(element) {
            if (!element || this.notifications.has(element)) {
                return;
            }
            
            const config = this.getNotificationConfig(element);
            const notification = new WcagNotification(element, config);
            
            this.notifications.set(element, notification);
            
            // Add entry animation
            this.addEntryAnimation(element);
            
            return notification;
        }
        
        /**
         * Get notification configuration from element
         * 
         * @param {HTMLElement} element Notification element
         * @returns {Object} Configuration object
         */
        getNotificationConfig(element) {
            return {
                id: element.getAttribute('data-notification-id') || '',
                type: element.getAttribute('data-notification-type') || 'info',
                dismissible: element.getAttribute('data-dismissible') === 'true',
                autoDismiss: element.getAttribute('data-auto-dismiss') === 'true',
                autoDismissDelay: parseInt(element.getAttribute('data-auto-dismiss-delay')) || 5000
            };
        }
        
        /**
         * Add entry animation to notification
         * 
         * @param {HTMLElement} element Notification element
         */
        addEntryAnimation(element) {
            // Skip animation if user prefers reduced motion
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return;
            }
            
            element.classList.add('wcag-wp-notification--entering');
            
            // Remove animation class after completion
            setTimeout(() => {
                element.classList.remove('wcag-wp-notification--entering');
            }, 300);
        }
        
        /**
         * Close the topmost (most recent) notification
         */
        closeTopNotification() {
            const notifications = Array.from(this.notifications.keys())
                .filter(el => el.isConnected && this.notifications.get(el).config.dismissible)
                .sort((a, b) => {
                    // Sort by z-index or DOM order
                    const aIndex = parseInt(getComputedStyle(a).zIndex) || 0;
                    const bIndex = parseInt(getComputedStyle(b).zIndex) || 0;
                    return bIndex - aIndex;
                });
            
            if (notifications.length > 0) {
                this.closeNotification(notifications[0]);
            }
        }
        
        /**
         * Close a specific notification
         * 
         * @param {HTMLElement} element Notification element
         */
        closeNotification(element) {
            const notification = this.notifications.get(element);
            if (notification) {
                notification.close();
            }
        }
        
        /**
         * Handle visibility change (tab focus)
         */
        handleVisibilityChange() {
            const isHidden = document.hidden;
            
            this.notifications.forEach(notification => {
                if (notification.autoDismissTimer) {
                    if (isHidden) {
                        notification.pauseAutoDismiss();
                    } else {
                        notification.resumeAutoDismiss();
                    }
                }
            });
        }
        
        /**
         * Create and show a dynamic notification
         * 
         * @param {string} message Notification message
         * @param {string} type Notification type (success, info, warning, error)
         * @param {Object} options Additional options
         * @returns {Promise} Promise that resolves when notification is shown
         */
        showDynamic(message, type = 'info', options = {}) {
            return new Promise((resolve, reject) => {
                if (!window.wcagNotifications || !window.wcagNotifications.ajax_url) {
                    reject(new Error('WCAG Notifications not properly initialized'));
                    return;
                }
                
                const data = new FormData();
                data.append('action', 'wcag_show_notification');
                data.append('nonce', window.wcagNotifications.nonce);
                data.append('type', type);
                data.append('message', message);
                
                // Add custom options
                Object.keys(options).forEach(key => {
                    data.append(key, options[key]);
                });
                
                fetch(window.wcagNotifications.ajax_url, {
                    method: 'POST',
                    body: data
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data.html) {
                        this.insertDynamicNotification(result.data.html);
                        resolve(result.data);
                    } else {
                        reject(new Error(result.data || 'Failed to create notification'));
                    }
                })
                .catch(error => {
                    reject(error);
                });
            });
        }
        
        /**
         * Insert dynamic notification into DOM
         * 
         * @param {string} html Notification HTML
         */
        insertDynamicNotification(html) {
            const container = this.getDynamicNotificationContainer();
            
            // Create temporary container to parse HTML
            const temp = document.createElement('div');
            temp.innerHTML = html;
            
            const notification = temp.firstElementChild;
            if (notification) {
                container.appendChild(notification);
                this.initNotification(notification);
            }
        }
        
        /**
         * Get or create container for dynamic notifications
         * 
         * @returns {HTMLElement} Container element
         */
        getDynamicNotificationContainer() {
            let container = document.getElementById('wcag-notifications-container');
            
            if (!container) {
                container = document.createElement('div');
                container.id = 'wcag-notifications-container';
                container.className = 'wcag-notifications-container';
                container.setAttribute('aria-live', 'polite');
                container.setAttribute('aria-atomic', 'false');
                
                // Insert at top of body
                document.body.insertBefore(container, document.body.firstChild);
            }
            
            return container;
        }
        
        /**
         * Remove notification from manager
         * 
         * @param {HTMLElement} element Notification element
         */
        removeNotification(element) {
            this.notifications.delete(element);
        }
    }
    
    /**
     * Individual Notification Class
     */
    class WcagNotification {
        constructor(element, config) {
            this.element = element;
            this.config = config;
            this.autoDismissTimer = null;
            this.autoDismissStartTime = null;
            this.autoDismissRemainingTime = null;
            
            this.init();
        }
        
        /**
         * Initialize notification
         */
        init() {
            this.bindEvents();
            this.setupAutoDismiss();
            this.announceToScreenReader();
        }
        
        /**
         * Bind notification events
         */
        bindEvents() {
            // Close button
            const closeBtn = this.element.querySelector('.wcag-wp-notification__close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.close());
                closeBtn.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.close();
                    }
                });
            }
            
            // Pause auto-dismiss on hover/focus
            if (this.config.autoDismiss) {
                this.element.addEventListener('mouseenter', () => this.pauseAutoDismiss());
                this.element.addEventListener('mouseleave', () => this.resumeAutoDismiss());
                this.element.addEventListener('focusin', () => this.pauseAutoDismiss());
                this.element.addEventListener('focusout', () => this.resumeAutoDismiss());
            }
        }
        
        /**
         * Setup auto-dismiss functionality
         */
        setupAutoDismiss() {
            if (!this.config.autoDismiss || !this.config.dismissible) {
                return;
            }
            
            this.startAutoDismiss();
        }
        
        /**
         * Start auto-dismiss timer
         */
        startAutoDismiss() {
            this.autoDismissStartTime = Date.now();
            this.autoDismissRemainingTime = this.config.autoDismissDelay;
            
            this.autoDismissTimer = setTimeout(() => {
                this.close();
            }, this.autoDismissRemainingTime);
        }
        
        /**
         * Pause auto-dismiss timer
         */
        pauseAutoDismiss() {
            if (!this.autoDismissTimer) return;
            
            clearTimeout(this.autoDismissTimer);
            this.autoDismissTimer = null;
            
            // Calculate remaining time
            const elapsed = Date.now() - this.autoDismissStartTime;
            this.autoDismissRemainingTime = Math.max(0, this.config.autoDismissDelay - elapsed);
            
            // Pause progress bar animation
            const progressBar = this.element.querySelector('.wcag-wp-notification__progress-bar');
            if (progressBar) {
                progressBar.style.animationPlayState = 'paused';
            }
        }
        
        /**
         * Resume auto-dismiss timer
         */
        resumeAutoDismiss() {
            if (this.autoDismissTimer || this.autoDismissRemainingTime <= 0) return;
            
            this.autoDismissStartTime = Date.now();
            
            this.autoDismissTimer = setTimeout(() => {
                this.close();
            }, this.autoDismissRemainingTime);
            
            // Resume progress bar animation
            const progressBar = this.element.querySelector('.wcag-wp-notification__progress-bar');
            if (progressBar) {
                progressBar.style.animationPlayState = 'running';
                progressBar.style.animationDuration = this.autoDismissRemainingTime + 'ms';
            }
        }
        
        /**
         * Announce notification to screen readers
         */
        announceToScreenReader() {
            // The aria-live region will handle this automatically
            // But we can add additional context if needed
            
            const type = this.config.type;
            const typeLabels = {
                success: 'Successo',
                info: 'Informazione', 
                warning: 'Attenzione',
                error: 'Errore'
            };
            
            // Add type information to screen reader announcement
            const srAnnouncement = document.createElement('div');
            srAnnouncement.className = 'wcag-wp-sr-only';
            srAnnouncement.setAttribute('aria-live', 'polite');
            srAnnouncement.textContent = `Notifica ${typeLabels[type] || 'Informazione'}`;
            
            this.element.appendChild(srAnnouncement);
            
            // Remove after announcement
            setTimeout(() => {
                if (srAnnouncement.parentNode) {
                    srAnnouncement.parentNode.removeChild(srAnnouncement);
                }
            }, 1000);
        }
        
        /**
         * Close notification with animation
         */
        close() {
            if (!this.config.dismissible) {
                return;
            }
            
            // Clear auto-dismiss timer
            if (this.autoDismissTimer) {
                clearTimeout(this.autoDismissTimer);
                this.autoDismissTimer = null;
            }
            
            // Add exit animation
            if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                this.element.classList.add('wcag-wp-notification--exiting');
                
                // Remove element after animation
                setTimeout(() => {
                    this.remove();
                }, 200);
            } else {
                // Remove immediately if reduced motion
                this.remove();
            }
            
            // Announce closure to screen reader
            this.announceClosureToScreenReader();
        }
        
        /**
         * Announce notification closure to screen reader
         */
        announceClosureToScreenReader() {
            const announcement = document.createElement('div');
            announcement.className = 'wcag-wp-sr-only';
            announcement.setAttribute('aria-live', 'polite');
            announcement.textContent = window.wcagNotifications?.strings?.notification_dismissed || 'Notifica chiusa';
            
            document.body.appendChild(announcement);
            
            // Remove after announcement
            setTimeout(() => {
                if (announcement.parentNode) {
                    announcement.parentNode.removeChild(announcement);
                }
            }, 1000);
        }
        
        /**
         * Remove notification from DOM
         */
        remove() {
            if (this.element.parentNode) {
                this.element.parentNode.removeChild(this.element);
            }
            
            // Remove from manager
            if (window.wcagNotificationManager) {
                window.wcagNotificationManager.removeNotification(this.element);
            }
        }
    }
    
    /**
     * Initialize notification system when DOM is ready
     */
    function initNotificationSystem() {
        if (!window.wcagNotificationManager) {
            window.wcagNotificationManager = new WcagNotificationManager();
        }
    }
    
    /**
     * Global function to initialize individual notifications
     * Called from notification templates
     * 
     * @param {HTMLElement} element Notification element
     * @returns {WcagNotification} Notification instance
     */
    window.wcagInitNotification = function(element) {
        if (window.wcagNotificationManager) {
            return window.wcagNotificationManager.initNotification(element);
        }
        return null;
    };
    
    /**
     * Global function to show dynamic notifications
     * 
     * @param {string} message Notification message
     * @param {string} type Notification type
     * @param {Object} options Additional options
     * @returns {Promise} Promise that resolves when notification is shown
     */
    window.wcagShowNotification = function(message, type = 'info', options = {}) {
        if (window.wcagNotificationManager) {
            return window.wcagNotificationManager.showDynamic(message, type, options);
        }
        return Promise.reject(new Error('Notification manager not initialized'));
    };
    
    /**
     * Global function to close all notifications
     */
    window.wcagCloseAllNotifications = function() {
        if (window.wcagNotificationManager) {
            window.wcagNotificationManager.notifications.forEach((notification, element) => {
                if (notification.config.dismissible) {
                    notification.close();
                }
            });
        }
    };
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNotificationSystem);
    } else {
        initNotificationSystem();
    }
    
})();

