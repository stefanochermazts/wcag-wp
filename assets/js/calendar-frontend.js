'use strict';

(function() {
    'use strict';

    // Calendar Frontend Management
    class WCAGCalendarFrontend {
        constructor(container) {
            console.log('WCAG Calendar: Inizializzazione calendario', container);
            this.container = container;
            this.config = JSON.parse(container.dataset.config || '{}');
            this.events = JSON.parse(container.dataset.events || '[]');
            this.currentMonth = parseInt(container.dataset.currentMonth) || new Date().getMonth() + 1;
            this.currentYear = parseInt(container.dataset.currentYear) || new Date().getFullYear();
            this.viewType = container.dataset.viewType || 'month';
            
            console.log('WCAG Calendar: Configurazione caricata:', this.config);
            console.log('WCAG Calendar: Eventi caricati:', this.events);
            console.log('WCAG Calendar: Mese corrente:', this.currentMonth);
            console.log('WCAG Calendar: Anno corrente:', this.currentYear);
            console.log('WCAG Calendar: Tipo vista:', this.viewType);
            
            this.init();
        }

        init() {
            console.log('WCAG Calendar: Inizializzazione componenti');
            this.bindEvents();
            this.setupAccessibility();
            this.setInitialView();
            this.announceCurrentView();
        }

        bindEvents() {
            console.log('WCAG Calendar: Binding eventi');
            
            // Navigation buttons
            this.container.addEventListener('click', (e) => {
                if (e.target.closest('.wcag-wp-calendar-prev')) {
                    e.preventDefault();
                    console.log('WCAG Calendar: Navigazione mese precedente');
                    this.navigateMonth(-1);
                } else if (e.target.closest('.wcag-wp-calendar-next')) {
                    e.preventDefault();
                    console.log('WCAG Calendar: Navigazione mese successivo');
                    this.navigateMonth(1);
                }
            });

            // View toggle buttons
            this.container.addEventListener('click', (e) => {
                if (e.target.closest('.wcag-wp-calendar-view-btn')) {
                    e.preventDefault();
                    console.log('WCAG Calendar: Cambio vista');
                    this.toggleView(e.target.closest('.wcag-wp-calendar-view-btn'));
                }
            });

            // Day clicks
            this.container.addEventListener('click', (e) => {
                if (e.target.closest('.wcag-wp-calendar-day.current-month')) {
                    e.preventDefault();
                    console.log('WCAG Calendar: Click su giorno');
                    this.handleDayClick(e.target.closest('.wcag-wp-calendar-day'));
                }
            });

            // Event clicks
            this.container.addEventListener('click', (e) => {
                if (e.target.closest('.wcag-wp-calendar-event')) {
                    e.preventDefault();
                    console.log('WCAG Calendar: Click su evento');
                    this.handleEventClick(e.target.closest('.wcag-wp-calendar-event'));
                }
            });

            // Keyboard navigation
            this.container.addEventListener('keydown', (e) => {
                console.log('WCAG Calendar: Tasto premuto:', e.key);
                this.handleKeyboardNavigation(e);
            });
        }

        setupAccessibility() {
            console.log('WCAG Calendar: Setup accessibilità');
            
            // Set up ARIA live regions
            this.liveRegion = this.container.querySelector('.wcag-wp-calendar-live-region');
            if (!this.liveRegion) {
                console.log('WCAG Calendar: Creazione live region');
                this.liveRegion = document.createElement('div');
                this.liveRegion.className = 'wcag-wp-calendar-live-region';
                this.liveRegion.setAttribute('aria-live', 'polite');
                this.liveRegion.setAttribute('aria-atomic', 'true');
                this.liveRegion.style.position = 'absolute';
                this.liveRegion.style.left = '-10000px';
                this.liveRegion.style.width = '1px';
                this.liveRegion.style.height = '1px';
                this.liveRegion.style.overflow = 'hidden';
                this.container.appendChild(this.liveRegion);
            }

            // Set up focus management
            this.setupFocusManagement();
        }

        setupFocusManagement() {
            console.log('WCAG Calendar: Setup focus management');
            
            // Make calendar days focusable
            const days = this.container.querySelectorAll('.wcag-wp-calendar-day');
            console.log('WCAG Calendar: Giorni trovati per focus:', days.length);
            
            days.forEach(day => {
                day.setAttribute('tabindex', '0');
            });

            // Set initial focus
            const today = this.container.querySelector('.wcag-wp-calendar-day.today');
            if (today) {
                console.log('WCAG Calendar: Focus su oggi');
                today.focus();
            } else {
                console.log('WCAG Calendar: Nessun giorno "oggi" trovato');
                // Focus on first day of current month
                const firstDay = this.container.querySelector('.wcag-wp-calendar-day');
                if (firstDay) {
                    console.log('WCAG Calendar: Focus sul primo giorno del mese');
                    firstDay.focus();
                }
            }
        }

        navigateMonth(direction) {
            const newDate = new Date(this.currentYear, this.currentMonth - 1 + direction, 1);
            this.currentMonth = newDate.getMonth() + 1;
            this.currentYear = newDate.getFullYear();

            // Update data attributes
            this.container.dataset.currentMonth = this.currentMonth;
            this.container.dataset.currentYear = this.currentYear;

            // Reload calendar
            this.reloadCalendar();

            // Announce navigation
            const monthName = newDate.toLocaleDateString('it-IT', { month: 'long', year: 'numeric' });
            this.announce(`Navigato a ${monthName}`);
        }

        reloadCalendar() {
            // This would typically make an AJAX call to reload the calendar
            // For now, we'll just update the title
            const titleElement = this.container.querySelector('.wcag-wp-calendar-title');
            if (titleElement) {
                const newDate = new Date(this.currentYear, this.currentMonth - 1, 1);
                titleElement.textContent = newDate.toLocaleDateString('it-IT', { month: 'long', year: 'numeric' });
            }

            // Update focus management
            this.setupFocusManagement();
        }

        toggleView(button) {
            const view = button.dataset.view;
            const buttons = this.container.querySelectorAll('.wcag-wp-calendar-view-btn');
            const views = this.container.querySelectorAll('.wcag-wp-calendar-view');

            // Update button states
            buttons.forEach(btn => {
                btn.classList.remove('active');
                btn.setAttribute('aria-pressed', 'false');
            });
            button.classList.add('active');
            button.setAttribute('aria-pressed', 'true');

            // Show/hide views
            views.forEach(viewElement => {
                if (viewElement.classList.contains(`wcag-wp-calendar-${view}`)) {
                    viewElement.style.display = 'block';
                    viewElement.setAttribute('aria-hidden', 'false');
                } else {
                    viewElement.style.display = 'none';
                    viewElement.setAttribute('aria-hidden', 'true');
                }
            });

            // Update internal state and apply specific behavior
            if (view === 'calendar') {
                this.viewType = 'month';
                this.applyWeekFilter(null);
                this.setupFocusManagement();
            } else if (view === 'list') {
                this.viewType = 'list';
                this.applyWeekFilter(null);
                this.setupListFocusManagement();
            }

            // Announce view change
            const viewName = view === 'calendar' ? 'vista calendario' : 'vista lista';
            this.announce(`Passato a ${viewName}`);
        }

        handleDayClick(dayElement) {
            const date = dayElement.dataset.date;
            if (!date) return;

            const events = this.getEventsForDate(date);
            if (events.length > 0) {
                this.announce(`${events.length} evento${events.length > 1 ? 'i' : ''} per ${this.formatDate(date)}`);
                this.showDayEvents(dayElement, events);
            } else {
                this.announce(`Nessun evento per ${this.formatDate(date)}`);
            }
        }

        handleEventClick(eventElement) {
            const eventTitle = eventElement.querySelector('.wcag-wp-calendar-event-title')?.textContent;
            if (eventTitle) {
                this.announce(`Evento: ${eventTitle}`);
            }
        }

        handleKeyboardNavigation(e) {
            console.log('WCAG Calendar: Gestione navigazione tastiera, configurazione:', this.config.keyboard_navigation);
            
            // Check if keyboard navigation is enabled
            if (!this.config.keyboard_navigation) {
                console.log('WCAG Calendar: Navigazione tastiera disabilitata');
                return;
            }
            
            const focusedDay = this.container.querySelector('.wcag-wp-calendar-day:focus');
            if (!focusedDay) {
                console.log('WCAG Calendar: Nessun giorno in focus, tentativo di focus sul primo giorno');
                const firstDay = this.container.querySelector('.wcag-wp-calendar-day.current-month');
                if (firstDay) {
                    firstDay.focus();
                }
                return;
            }

            let targetDay = null;

            switch (e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    console.log('WCAG Calendar: Freccia sinistra');
                    targetDay = this.getAdjacentDay(focusedDay, -1);
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    console.log('WCAG Calendar: Freccia destra');
                    targetDay = this.getAdjacentDay(focusedDay, 1);
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    console.log('WCAG Calendar: Freccia su');
                    targetDay = this.getAdjacentDay(focusedDay, -7);
                    break;
                case 'ArrowDown':
                    e.preventDefault();
                    console.log('WCAG Calendar: Freccia giù');
                    targetDay = this.getAdjacentDay(focusedDay, 7);
                    break;
                case 'Home':
                    e.preventDefault();
                    console.log('WCAG Calendar: Home');
                    targetDay = this.getFirstDayOfMonth();
                    break;
                case 'End':
                    e.preventDefault();
                    console.log('WCAG Calendar: End');
                    targetDay = this.getLastDayOfMonth();
                    break;
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    console.log('WCAG Calendar: Enter/Space');
                    this.handleDayClick(focusedDay);
                    return;
            }

            if (targetDay) {
                targetDay.focus();
                const date = targetDay.dataset.date;
                if (date) {
                    this.announce(this.formatDate(date));
                }
            }
        }

        getAdjacentDay(currentDay, offset) {
            const selector = this.viewType === 'week'
                ? '.wcag-wp-calendar-day.current-month[aria-hidden="false"]'
                : '.wcag-wp-calendar-day.current-month';
            const days = Array.from(this.container.querySelectorAll(selector));
            const currentIndex = days.indexOf(currentDay);
            const targetIndex = currentIndex + offset;
            
            if (targetIndex >= 0 && targetIndex < days.length) {
                return days[targetIndex];
            }
            return null;
        }

        getFirstDayOfMonth() {
            return this.container.querySelector('.wcag-wp-calendar-day.current-month');
        }

        getLastDayOfMonth() {
            const days = this.container.querySelectorAll('.wcag-wp-calendar-day.current-month');
            return days[days.length - 1];
        }

        getEventsForDate(date) {
            return this.events.filter(event => event.start_date === date);
        }

        showDayEvents(dayElement, events) {
            // Create or update event details popup
            let popup = this.container.querySelector('.wcag-wp-calendar-event-popup');
            if (!popup) {
                popup = document.createElement('div');
                popup.className = 'wcag-wp-calendar-event-popup';
                popup.setAttribute('role', 'dialog');
                popup.setAttribute('aria-modal', 'true');
                this.container.appendChild(popup);
            }

            const date = dayElement.dataset.date;
            const formattedDate = this.formatDate(date);
            
            popup.innerHTML = `
                <div class="wcag-wp-calendar-event-popup-header">
                    <h3>Eventi del ${formattedDate}</h3>
                    <button type="button" class="wcag-wp-calendar-event-popup-close" aria-label="Chiudi">
                        <span class="dashicons dashicons-no-alt"></span>
                    </button>
                </div>
                <div class="wcag-wp-calendar-event-popup-content">
                    ${events.map(event => `
                        <div class="wcag-wp-calendar-event-popup-item">
                            <h4>${event.title}</h4>
                            ${event.description ? `<p>${event.description}</p>` : ''}
                            ${event.location ? `<p><strong>Luogo:</strong> ${event.location}</p>` : ''}
                            ${event.link_url ? `<a href="${event.link_url}" class="wcag-wp-calendar-event-link">${event.link_text || 'Maggiori informazioni'}</a>` : ''}
                        </div>
                    `).join('')}
                </div>
            `;

            popup.style.display = 'block';
            
            // Focus management
            const closeButton = popup.querySelector('.wcag-wp-calendar-event-popup-close');
            if (closeButton) {
                closeButton.focus();
                closeButton.addEventListener('click', () => this.hideEventPopup());
            }

            // Close on escape
            const handleEscape = (e) => {
                if (e.key === 'Escape') {
                    this.hideEventPopup();
                    document.removeEventListener('keydown', handleEscape);
                }
            };
            document.addEventListener('keydown', handleEscape);
        }

        hideEventPopup() {
            const popup = this.container.querySelector('.wcag-wp-calendar-event-popup');
            if (popup) {
                popup.style.display = 'none';
            }
        }

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('it-IT', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        announce(message) {
            console.log('WCAG Calendar: Annuncio:', message, 'Config:', this.config.screen_reader_announcements, 'Live region:', !!this.liveRegion);
            if (this.config.screen_reader_announcements && this.liveRegion) {
                this.liveRegion.textContent = message;
                // Clear after a short delay
                setTimeout(() => {
                    this.liveRegion.textContent = '';
                }, 1000);
            }
        }

        setInitialView() {
            console.log('WCAG Calendar: Impostazione vista iniziale:', this.viewType);
            
            // Get all view buttons and views
            const buttons = this.container.querySelectorAll('.wcag-wp-calendar-view-btn');
            const views = this.container.querySelectorAll('.wcag-wp-calendar-view');
            
            // Reset all buttons and views
            buttons.forEach(btn => {
                btn.classList.remove('active');
                btn.setAttribute('aria-pressed', 'false');
            });
            
            views.forEach(viewElement => {
                viewElement.style.display = 'none';
                viewElement.setAttribute('aria-hidden', 'true');
            });
            
            // Set the correct initial view
            if (this.viewType === 'list') {
                // Show list view
                const listView = this.container.querySelector('.wcag-wp-calendar-list');
                const listButton = this.container.querySelector('.wcag-wp-calendar-view-btn[data-view="list"]');
                
                if (listView) {
                    listView.style.display = 'block';
                    listView.setAttribute('aria-hidden', 'false');
                }
                
                if (listButton) {
                    listButton.classList.add('active');
                    listButton.setAttribute('aria-pressed', 'true');
                }
                this.setupListFocusManagement();
            } else if (this.viewType === 'week') {
                // Week view is a subset of calendar view
                const calendarView = this.container.querySelector('.wcag-wp-calendar-grid');
                const calendarButton = this.container.querySelector('.wcag-wp-calendar-view-btn[data-view="calendar"]');

                if (calendarView) {
                    calendarView.style.display = 'block';
                    calendarView.setAttribute('aria-hidden', 'false');
                }

                if (calendarButton) {
                    calendarButton.classList.add('active');
                    calendarButton.setAttribute('aria-pressed', 'true');
                }

                const currentWeek = this.getWeekIndexOfToday();
                this.applyWeekFilter(currentWeek);
                this.setupFocusManagement();
            } else {
                // Show calendar view (default)
                const calendarView = this.container.querySelector('.wcag-wp-calendar-grid');
                const calendarButton = this.container.querySelector('.wcag-wp-calendar-view-btn[data-view="calendar"]');
                
                if (calendarView) {
                    calendarView.style.display = 'block';
                    calendarView.setAttribute('aria-hidden', 'false');
                }
                
                if (calendarButton) {
                    calendarButton.classList.add('active');
                    calendarButton.setAttribute('aria-pressed', 'true');
                }
                this.applyWeekFilter(null);
                this.setupFocusManagement();
            }
        }

        announceCurrentView() {
            let viewName = 'vista calendario';
            if (this.viewType === 'list') viewName = 'vista lista';
            if (this.viewType === 'week') viewName = 'vista settimanale';
            const monthName = new Date(this.currentYear, this.currentMonth - 1, 1).toLocaleDateString('it-IT', { month: 'long', year: 'numeric' });
            this.announce(`Calendario ${monthName}, ${viewName}`);
        }

        // Hide/show days to emulate a week view using the monthly grid
        applyWeekFilter(weekIndex) {
            const days = this.container.querySelectorAll('.wcag-wp-calendar-day');
            if (weekIndex === null || typeof weekIndex === 'undefined') {
                days.forEach(d => {
                    d.style.display = '';
                    d.setAttribute('aria-hidden', 'false');
                });
                return;
            }

            days.forEach(d => {
                const w = parseInt(d.getAttribute('data-week-index') || '-1', 10);
                const inWeek = w === weekIndex;
                d.style.display = inWeek ? '' : 'none';
                d.setAttribute('aria-hidden', inWeek ? 'false' : 'true');
            });
        }

        getWeekIndexOfToday() {
            const todayEl = this.container.querySelector('.wcag-wp-calendar-day.today');
            if (!todayEl) {
                // fallback: first week containing first day of month
                const first = this.container.querySelector('.wcag-wp-calendar-day[data-week-index]');
                return first ? parseInt(first.getAttribute('data-week-index') || '0', 10) : 0;
            }
            return parseInt(todayEl.getAttribute('data-week-index') || '0', 10);
        }

        // Focusable items when in list view
        setupListFocusManagement() {
            const items = this.container.querySelectorAll('.wcag-wp-calendar-list [role="listitem"], .wcag-wp-calendar-event-link');
            items.forEach(el => el.setAttribute('tabindex', '0'));
        }
    }

    // Initialize all calendars when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        const calendars = document.querySelectorAll('.wcag-wp-calendar');
        calendars.forEach(calendar => {
            new WCAGCalendarFrontend(calendar);
        });
    });

})();
