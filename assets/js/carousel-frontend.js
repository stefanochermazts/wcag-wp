'use strict';

(function() {
    'use strict';

    // WCAG Carousel Frontend
    class WCAGCarousel {
        constructor(element) {
            this.element = element;
            this.container = element.querySelector('.wcag-wp-carousel-container');
            this.track = element.querySelector('.wcag-wp-carousel-track');
            this.slides = Array.from(element.querySelectorAll('.wcag-wp-carousel-slide'));
            this.indicators = Array.from(element.querySelectorAll('.wcag-wp-carousel-indicator'));
            this.controls = {
                prev: element.querySelector('.wcag-wp-carousel-control--prev'),
                next: element.querySelector('.wcag-wp-carousel-control--next'),
                autoplay: element.querySelector('.wcag-wp-carousel-autoplay-toggle')
            };
            this.status = element.querySelector('.wcag-wp-carousel-status');
            
            // Configuration
            this.config = {
                autoplay: element.dataset.autoplay === 'true',
                autoplaySpeed: parseInt(element.dataset.autoplaySpeed) || 5000,
                pauseOnHover: element.dataset.pauseOnHover === 'true',
                showIndicators: element.dataset.showIndicators === 'true',
                showControls: element.dataset.showControls === 'true',
                keyboardNavigation: element.dataset.keyboardNavigation === 'true',
                touchSwipe: element.dataset.touchSwipe === 'true',
                infiniteLoop: element.dataset.infiniteLoop === 'true',
                animationType: element.dataset.animationType || 'slide'
            };
            
            // State
            this.currentIndex = 0;
            this.totalSlides = this.slides.length;
            this.autoplayInterval = null;
            this.isPaused = false;
            this.isTransitioning = false;
            
            this.init();
        }

        init() {
            if (this.totalSlides <= 1) {
                return; // No need for carousel functionality
            }

            this.setupEventListeners();
            this.setupAccessibility();
            this.startAutoplay();
            this.updateStatus();
        }

        setupEventListeners() {
            // Control buttons
            if (this.controls.prev) {
                this.controls.prev.addEventListener('click', () => this.goToPrevious());
            }
            if (this.controls.next) {
                this.controls.next.addEventListener('click', () => this.goToNext());
            }

            // Indicators
            this.indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => this.goToSlide(index));
            });

            // Autoplay controls
            if (this.controls.autoplay) {
                this.controls.autoplay.addEventListener('click', () => this.toggleAutoplay());
            }

            // Keyboard navigation
            if (this.config.keyboardNavigation) {
                this.element.addEventListener('keydown', (e) => this.handleKeyboard(e));
                this.element.setAttribute('tabindex', '0');
            }

            // Touch/swipe support
            if (this.config.touchSwipe) {
                this.setupTouchSupport();
            }

            // Pause on hover
            if (this.config.pauseOnHover) {
                this.element.addEventListener('mouseenter', () => this.pauseAutoplay());
                this.element.addEventListener('mouseleave', () => this.resumeAutoplay());
            }

            // Focus management
            this.element.addEventListener('focusin', (e) => this.handleFocusIn(e));
        }

        setupAccessibility() {
            // Set initial ARIA states
            this.updateAriaStates();
            
            // Announce slide changes
            this.element.addEventListener('transitionend', () => {
                this.announceSlideChange();
            });
        }

        setupTouchSupport() {
            let startX = 0;
            let startY = 0;
            let isDragging = false;

            this.container.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                isDragging = false;
            }, { passive: true });

            this.container.addEventListener('touchmove', (e) => {
                if (!startX || !startY) return;

                const deltaX = e.touches[0].clientX - startX;
                const deltaY = e.touches[0].clientY - startY;

                // Check if horizontal swipe
                if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > 10) {
                    isDragging = true;
                    e.preventDefault();
                }
            });

            this.container.addEventListener('touchend', (e) => {
                if (!isDragging) return;

                const deltaX = e.changedTouches[0].clientX - startX;
                const threshold = 50;

                if (Math.abs(deltaX) > threshold) {
                    if (deltaX > 0) {
                        this.goToPrevious();
                    } else {
                        this.goToNext();
                    }
                }

                startX = 0;
                startY = 0;
                isDragging = false;
            });
        }

        handleKeyboard(e) {
            switch (e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    this.goToPrevious();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    this.goToNext();
                    break;
                case 'Home':
                    e.preventDefault();
                    this.goToSlide(0);
                    break;
                case 'End':
                    e.preventDefault();
                    this.goToSlide(this.totalSlides - 1);
                    break;
                case ' ':
                case 'Enter':
                    if (e.target === this.controls.autoplay) {
                        e.preventDefault();
                        this.toggleAutoplay();
                    }
                    break;
            }
        }

        handleFocusIn(e) {
            // Ensure focused element is visible
            if (e.target.classList.contains('wcag-wp-carousel-indicator') || 
                e.target.classList.contains('wcag-wp-carousel-control')) {
                const slideIndex = e.target.dataset.slideIndex;
                if (slideIndex !== undefined && parseInt(slideIndex) !== this.currentIndex) {
                    this.goToSlide(parseInt(slideIndex));
                }
            }
        }

        goToSlide(index) {
            if (this.isTransitioning || index === this.currentIndex) return;

            // Handle infinite loop
            if (this.config.infiniteLoop) {
                if (index < 0) index = this.totalSlides - 1;
                if (index >= this.totalSlides) index = 0;
            } else {
                if (index < 0 || index >= this.totalSlides) return;
            }

            this.isTransitioning = true;
            const previousIndex = this.currentIndex;
            this.currentIndex = index;

            // Update slides
            this.slides[previousIndex].classList.remove('wcag-wp-carousel-slide--active');
            this.slides[previousIndex].setAttribute('aria-hidden', 'true');
            this.slides[this.currentIndex].classList.add('wcag-wp-carousel-slide--active');
            this.slides[this.currentIndex].setAttribute('aria-hidden', 'false');

            // Update indicators
            if (this.indicators.length > 0) {
                this.indicators[previousIndex].classList.remove('wcag-wp-carousel-indicator--active');
                this.indicators[previousIndex].setAttribute('aria-selected', 'false');
                this.indicators[this.currentIndex].classList.add('wcag-wp-carousel-indicator--active');
                this.indicators[this.currentIndex].setAttribute('aria-selected', 'true');
            }

            // Update status
            this.updateStatus();

            // Reset autoplay
            this.resetAutoplay();

            // Transition end
            setTimeout(() => {
                this.isTransitioning = false;
            }, 300);
        }

        goToNext() {
            this.goToSlide(this.currentIndex + 1);
        }

        goToPrevious() {
            this.goToSlide(this.currentIndex - 1);
        }

        updateAriaStates() {
            // Update slide states
            this.slides.forEach((slide, index) => {
                slide.setAttribute('aria-hidden', index === this.currentIndex ? 'false' : 'true');
            });

            // Update indicator states
            this.indicators.forEach((indicator, index) => {
                indicator.setAttribute('aria-selected', index === this.currentIndex ? 'true' : 'false');
            });

            // Update autoplay button
            if (this.controls.autoplay) {
                this.controls.autoplay.setAttribute('aria-pressed', this.isPaused ? 'false' : 'true');
            }
        }

        updateStatus() {
            if (this.status) {
                this.status.textContent = `Slide ${this.currentIndex + 1} di ${this.totalSlides}`;
            }
        }

        announceSlideChange() {
            const currentSlide = this.slides[this.currentIndex];
            const title = currentSlide.querySelector('.wcag-wp-carousel-title');
            
            if (title) {
                // Create temporary announcement
                const announcement = document.createElement('div');
                announcement.setAttribute('aria-live', 'polite');
                announcement.setAttribute('aria-atomic', 'true');
                announcement.className = 'sr-only';
                announcement.textContent = `Slide attiva: ${title.textContent}`;
                
                document.body.appendChild(announcement);
                
                // Remove after announcement
                setTimeout(() => {
                    document.body.removeChild(announcement);
                }, 1000);
            }
        }

        startAutoplay() {
            if (!this.config.autoplay || this.isPaused) return;

            this.autoplayInterval = setInterval(() => {
                this.goToNext();
            }, this.config.autoplaySpeed);
        }

        stopAutoplay() {
            if (this.autoplayInterval) {
                clearInterval(this.autoplayInterval);
                this.autoplayInterval = null;
            }
        }

        pauseAutoplay() {
            if (this.config.autoplay && !this.isPaused) {
                this.stopAutoplay();
            }
        }

        resumeAutoplay() {
            if (this.config.autoplay && !this.isPaused) {
                this.startAutoplay();
            }
        }

        resetAutoplay() {
            if (this.config.autoplay) {
                this.stopAutoplay();
                this.startAutoplay();
            }
        }

        toggleAutoplay() {
            this.isPaused = !this.isPaused;
            
            if (this.isPaused) {
                this.stopAutoplay();
                if (this.controls.autoplay) {
                    this.controls.autoplay.setAttribute('aria-label', 'Riprendi autoplay');
                    this.controls.autoplay.setAttribute('aria-pressed', 'false');
                    this.controls.autoplay.querySelector('.dashicons').className = 'dashicons dashicons-controls-play';
                }
            } else {
                this.startAutoplay();
                if (this.controls.autoplay) {
                    this.controls.autoplay.setAttribute('aria-label', 'Pausa autoplay');
                    this.controls.autoplay.setAttribute('aria-pressed', 'true');
                    this.controls.autoplay.querySelector('.dashicons').className = 'dashicons dashicons-controls-pause';
                }
            }
        }
    }

    // Initialize all carousels
    function initCarousels() {
        const carousels = document.querySelectorAll('.wcag-wp-carousel');
        carousels.forEach(carousel => new WCAGCarousel(carousel));
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCarousels);
    } else {
        initCarousels();
    }

})();
