/**
 * WCAG Slider Multi-Thumb Component - Frontend JavaScript
 * 
 * Pattern WCAG APG per slider multi-thumb accessibile
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * WCAG Slider Multi-Thumb Class
     */
    class WcagSliderMultiThumb {
        constructor(element, config = {}) {
            this.element = element;
            this.config = {
                min: 0,
                max: 100,
                step: 1,
                thumbs_count: 2,
                default_values: '25,75',
                unit: '',
                orientation: 'horizontal',
                show_values: true,
                show_range_fill: true,
                show_ticks: false,
                prevent_overlap: true,
                min_distance: 5,
                required: false,
                disabled: false,
                on_change_callback: '',
                ...config
            };
            
            this.thumbs = [];
            this.activeThumb = null;
            this.isDragging = false;
            this.init();
        }
        
        init() {
            this.initializeThumbs();
            this.setupEventListeners();
            this.updatePositions();
            this.updateARIA();
            this.updateValueDisplay();
            this.updateRangeFill();
            this.setupAccessibility();
        }
        
        initializeThumbs() {
            // Parse default values
            const values = this.config.default_values.split(',').map(v => parseFloat(v.trim()));
            const thumbElements = this.element.find('.wcag-wp-slider-multithumb__thumb');
            
            thumbElements.each((index, thumbElement) => {
                const $thumb = $(thumbElement);
                const value = values[index] || this.config.min;
                
                this.thumbs.push({
                    element: $thumb,
                    value: this.clampValue(value),
                    index: index
                });
            });
        }
        
        setupEventListeners() {
            const track = this.element.find('.wcag-wp-slider-multithumb__track');
            
            // Mouse events for each thumb
            this.thumbs.forEach((thumb, index) => {
                thumb.element.on('mousedown', (e) => this.handleMouseDown(e, index));
                thumb.element.on('keydown', (e) => this.handleKeyDown(e, index));
                thumb.element.on('focus', () => this.handleFocus(index));
                thumb.element.on('blur', () => this.handleBlur(index));
            });
            
            // Track click
            track.on('click', (e) => this.handleTrackClick(e));
            
            // Global mouse events
            $(document).on('mousemove', (e) => this.handleMouseMove(e));
            $(document).on('mouseup', () => this.handleMouseUp());
            
            // Touch events
            this.thumbs.forEach((thumb, index) => {
                thumb.element[0].addEventListener('touchstart', (e) => this.handleTouchStart(e, index), { passive: false });
            });
            
            $(document).on('touchmove', (e) => this.handleTouchMove(e));
            $(document).on('touchend', () => this.handleTouchEnd());
            
            // Prevent default drag behavior
            this.element.on('dragstart', (e) => e.preventDefault());
        }
        
        handleMouseDown(e, thumbIndex) {
            if (this.config.disabled) return;
            
            e.preventDefault();
            this.startDrag(thumbIndex, e.clientX, e.clientY);
        }
        
        handleTouchStart(e, thumbIndex) {
            if (this.config.disabled) return;
            
            e.preventDefault();
            const touch = e.touches[0];
            this.startDrag(thumbIndex, touch.clientX, touch.clientY);
        }
        
        startDrag(thumbIndex, clientX, clientY) {
            this.isDragging = true;
            this.activeThumb = thumbIndex;
            this.thumbs[thumbIndex].element.addClass('wcag-wp-slider-multithumb__thumb--dragging');
            this.element.addClass('wcag-wp-slider-multithumb--dragging');
            
            // Focus the thumb
            this.thumbs[thumbIndex].element.focus();
            
            // Store initial position
            this.dragStart = {
                x: clientX,
                y: clientY,
                value: this.thumbs[thumbIndex].value
            };
        }
        
        handleMouseMove(e) {
            if (!this.isDragging || this.activeThumb === null) return;
            this.updateDrag(e.clientX, e.clientY);
        }
        
        handleTouchMove(e) {
            if (!this.isDragging || this.activeThumb === null) return;
            e.preventDefault();
            const touch = e.touches[0];
            this.updateDrag(touch.clientX, touch.clientY);
        }
        
        updateDrag(clientX, clientY) {
            const track = this.element.find('.wcag-wp-slider-multithumb__track');
            const trackRect = track[0].getBoundingClientRect();
            
            let percentage;
            if (this.config.orientation === 'horizontal') {
                percentage = (clientX - trackRect.left) / trackRect.width;
            } else {
                percentage = 1 - (clientY - trackRect.top) / trackRect.height;
            }
            
            percentage = Math.max(0, Math.min(1, percentage));
            const newValue = this.config.min + percentage * (this.config.max - this.config.min);
            
            this.setValue(this.activeThumb, newValue);
        }
        
        handleMouseUp() {
            if (!this.isDragging) return;
            this.endDrag();
        }
        
        handleTouchEnd() {
            if (!this.isDragging) return;
            this.endDrag();
        }
        
        endDrag() {
            if (this.activeThumb !== null) {
                this.thumbs[this.activeThumb].element.removeClass('wcag-wp-slider-multithumb__thumb--dragging');
            }
            
            this.element.removeClass('wcag-wp-slider-multithumb--dragging');
            this.isDragging = false;
            this.activeThumb = null;
            this.dragStart = null;
            
            this.announceChange();
        }
        
        handleTrackClick(e) {
            if (this.config.disabled || this.isDragging) return;
            
            const track = this.element.find('.wcag-wp-slider-multithumb__track');
            const trackRect = track[0].getBoundingClientRect();
            
            let percentage;
            if (this.config.orientation === 'horizontal') {
                percentage = (e.clientX - trackRect.left) / trackRect.width;
            } else {
                percentage = 1 - (e.clientY - trackRect.top) / trackRect.height;
            }
            
            const clickValue = this.config.min + percentage * (this.config.max - this.config.min);
            
            // Find closest thumb
            let closestThumb = 0;
            let minDistance = Math.abs(this.thumbs[0].value - clickValue);
            
            for (let i = 1; i < this.thumbs.length; i++) {
                const distance = Math.abs(this.thumbs[i].value - clickValue);
                if (distance < minDistance) {
                    minDistance = distance;
                    closestThumb = i;
                }
            }
            
            this.setValue(closestThumb, clickValue);
            this.thumbs[closestThumb].element.focus();
        }
        
        handleKeyDown(e, thumbIndex) {
            if (this.config.disabled) return;
            
            let handled = false;
            let newValue = this.thumbs[thumbIndex].value;
            const step = this.config.step;
            const largeStep = step * 10;
            
            switch (e.key) {
                case 'ArrowRight':
                case 'ArrowUp':
                    newValue += step;
                    handled = true;
                    break;
                    
                case 'ArrowLeft':
                case 'ArrowDown':
                    newValue -= step;
                    handled = true;
                    break;
                    
                case 'PageUp':
                    newValue += largeStep;
                    handled = true;
                    break;
                    
                case 'PageDown':
                    newValue -= largeStep;
                    handled = true;
                    break;
                    
                case 'Home':
                    newValue = this.config.min;
                    handled = true;
                    break;
                    
                case 'End':
                    newValue = this.config.max;
                    handled = true;
                    break;
            }
            
            if (handled) {
                e.preventDefault();
                this.setValue(thumbIndex, newValue);
                this.announceChange();
            }
        }
        
        handleFocus(thumbIndex) {
            this.thumbs[thumbIndex].element.addClass('wcag-wp-slider-multithumb__thumb--focused');
        }
        
        handleBlur(thumbIndex) {
            this.thumbs[thumbIndex].element.removeClass('wcag-wp-slider-multithumb__thumb--focused');
        }
        
        setValue(thumbIndex, value) {
            const clampedValue = this.clampValue(value);
            const constrainedValue = this.constrainValue(thumbIndex, clampedValue);
            
            this.thumbs[thumbIndex].value = constrainedValue;
            this.updatePositions();
            this.updateARIA();
            this.updateValueDisplay();
            this.updateRangeFill();
            this.triggerCallback();
        }
        
        clampValue(value) {
            const stepped = Math.round((value - this.config.min) / this.config.step) * this.config.step + this.config.min;
            return Math.max(this.config.min, Math.min(this.config.max, stepped));
        }
        
        constrainValue(thumbIndex, value) {
            if (!this.config.prevent_overlap) {
                return value;
            }
            
            const minDistance = this.config.min_distance;
            let constrainedValue = value;
            
            // Check constraints with other thumbs
            for (let i = 0; i < this.thumbs.length; i++) {
                if (i === thumbIndex) continue;
                
                const otherValue = this.thumbs[i].value;
                
                if (i < thumbIndex) {
                    // This thumb should be after the other thumb
                    constrainedValue = Math.max(constrainedValue, otherValue + minDistance);
                } else {
                    // This thumb should be before the other thumb
                    constrainedValue = Math.min(constrainedValue, otherValue - minDistance);
                }
            }
            
            return this.clampValue(constrainedValue);
        }
        
        updatePositions() {
            this.thumbs.forEach(thumb => {
                const percentage = ((thumb.value - this.config.min) / (this.config.max - this.config.min)) * 100;
                
                if (this.config.orientation === 'horizontal') {
                    thumb.element.css('left', percentage + '%');
                } else {
                    thumb.element.css('bottom', percentage + '%');
                }
            });
        }
        
        updateARIA() {
            this.thumbs.forEach(thumb => {
                thumb.element.attr({
                    'aria-valuenow': thumb.value,
                    'aria-valuetext': this.formatValue(thumb.value)
                });
            });
        }
        
        updateValueDisplay() {
            if (!this.config.show_values) return;
            
            this.thumbs.forEach((thumb, index) => {
                // Update value display inside the thumb
                const valueDisplay = thumb.element.find('.wcag-wp-slider-multithumb__value');
                if (valueDisplay.length) {
                    valueDisplay.text(this.formatValue(thumb.value));
                }
                
                // Update external value display (the values on the right)
                const externalValueDisplay = this.element.find(`.wcag-wp-slider-multithumb__value[data-thumb-index="${index}"] .wcag-wp-slider-multithumb__value-text`);
                if (externalValueDisplay.length) {
                    externalValueDisplay.text(this.formatValue(thumb.value));
                }
            });
        }
        
        updateRangeFill() {
            if (!this.config.show_range_fill || this.thumbs.length < 2) return;
            
            const rangeFill = this.element.find('.wcag-wp-slider-multithumb__range-fill');
            if (!rangeFill.length) return;
            
            // Sort thumbs by value
            const sortedThumbs = [...this.thumbs].sort((a, b) => a.value - b.value);
            const minValue = sortedThumbs[0].value;
            const maxValue = sortedThumbs[sortedThumbs.length - 1].value;
            
            const startPercentage = ((minValue - this.config.min) / (this.config.max - this.config.min)) * 100;
            const endPercentage = ((maxValue - this.config.min) / (this.config.max - this.config.min)) * 100;
            const width = endPercentage - startPercentage;
            
            if (this.config.orientation === 'horizontal') {
                rangeFill.css({
                    'left': startPercentage + '%',
                    'width': width + '%'
                });
            } else {
                rangeFill.css({
                    'bottom': startPercentage + '%',
                    'height': width + '%'
                });
            }
        }
        
        formatValue(value) {
            return value + (this.config.unit || '');
        }
        
        setupAccessibility() {
            // Setup live region for announcements
            if (!this.element.find('.wcag-wp-slider-multithumb__live-region').length) {
                this.element.append('<div class="wcag-wp-slider-multithumb__live-region sr-only" aria-live="polite" aria-atomic="true"></div>');
            }
        }
        
        announceChange() {
            const liveRegion = this.element.find('.wcag-wp-slider-multithumb__live-region');
            if (liveRegion.length && this.activeThumb !== null) {
                const thumb = this.thumbs[this.activeThumb];
                const message = `Thumb ${this.activeThumb + 1}: ${this.formatValue(thumb.value)}`;
                liveRegion.text(message);
            }
        }
        
        triggerCallback() {
            if (this.config.on_change_callback && typeof window[this.config.on_change_callback] === 'function') {
                const values = this.thumbs.map(thumb => thumb.value);
                window[this.config.on_change_callback](values, this.element);
            }
            
            // Trigger custom event
            this.element.trigger('wcag-slider-multithumb:change', {
                values: this.thumbs.map(thumb => thumb.value),
                element: this.element
            });
        }
        
        // Public methods
        getValues() {
            return this.thumbs.map(thumb => thumb.value);
        }
        
        setValues(values) {
            values.forEach((value, index) => {
                if (index < this.thumbs.length) {
                    this.setValue(index, value);
                }
            });
        }
        
        enable() {
            this.config.disabled = false;
            this.element.removeClass('wcag-wp-slider-multithumb--disabled');
            this.thumbs.forEach(thumb => {
                thumb.element.attr('tabindex', '0').removeAttr('aria-disabled');
            });
        }
        
        disable() {
            this.config.disabled = true;
            this.element.addClass('wcag-wp-slider-multithumb--disabled');
            this.thumbs.forEach(thumb => {
                thumb.element.attr('tabindex', '-1').attr('aria-disabled', 'true');
            });
        }
    }
    
    /**
     * jQuery Plugin
     */
    $.fn.wcagSliderMultiThumb = function(options) {
        return this.each(function() {
            const $element = $(this);
            const config = $element.data('config') || {};
            const mergedConfig = $.extend({}, config, options);
            
            if (!$element.data('wcag-slider-multithumb')) {
                $element.data('wcag-slider-multithumb', new WcagSliderMultiThumb($element, mergedConfig));
            }
        });
    };
    
    /**
     * Auto-initialize on DOM ready
     */
    $(document).ready(function() {
        $('.wcag-wp-slider-multithumb[data-wcag-slider-multithumb]').wcagSliderMultiThumb();
    });
    
    /**
     * Initialize dynamically added sliders
     */
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) {
                    const $node = $(node);
                    const sliders = $node.find('.wcag-wp-slider-multithumb[data-wcag-slider-multithumb]');
                    if ($node.hasClass('wcag-wp-slider-multithumb') && $node.attr('data-wcag-slider-multithumb')) {
                        sliders.add($node);
                    }
                    sliders.wcagSliderMultiThumb();
                }
            });
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Expose class globally
    window.WcagSliderMultiThumb = WcagSliderMultiThumb;
    
})(jQuery);
