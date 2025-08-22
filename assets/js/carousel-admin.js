'use strict';

(function($) {
    'use strict';

    // Carousel Admin Management
    class WCAGCarouselAdmin {
        constructor() {
            this.container = $('#wcag-wp-carousel-slides-container');
            this.template = $('#wcag-wp-slide-template').html();
            this.slideIndex = this.container.find('.wcag-wp-slide-item').length;
            
            this.init();
        }

        init() {
            this.bindEvents();
            this.initSortable();
            this.initMediaUploader();
        }

        bindEvents() {
            // Add new slide
            $('#wcag-wp-add-slide').on('click', () => this.addSlide());

            // Delete slide
            this.container.on('click', '.wcag-wp-slide-delete', (e) => {
                e.preventDefault();
                this.deleteSlide($(e.currentTarget).closest('.wcag-wp-slide-item'));
            });

            // Toggle slide content
            this.container.on('click', '.wcag-wp-slide-toggle', (e) => {
                e.preventDefault();
                this.toggleSlide($(e.currentTarget));
            });

            // Copy shortcode
            $('.wcag-wp-copy-shortcode').on('click', (e) => {
                e.preventDefault();
                this.copyShortcode($(e.currentTarget));
            });

            // Image selection
            this.container.on('click', '.wcag-wp-select-image', (e) => {
                e.preventDefault();
                this.selectImage($(e.currentTarget));
            });

            // Remove image
            this.container.on('click', '.wcag-wp-remove-image', (e) => {
                e.preventDefault();
                this.removeImage($(e.currentTarget));
            });

            // Update slide title on input
            this.container.on('input', 'input[name*="[title]"]', (e) => {
                this.updateSlideTitle($(e.currentTarget));
            });
        }

        initSortable() {
            this.container.sortable({
                handle: '.wcag-wp-slide-handle',
                placeholder: 'wcag-wp-slide-placeholder',
                update: () => this.updateSlideIndexes()
            });
        }

        initMediaUploader() {
            // Media uploader will be initialized per button click
        }

        addSlide() {
            const newSlide = this.template.replace(/\{\{index\}\}/g, this.slideIndex);
            this.container.append(newSlide);
            
            // Update slide index
            this.slideIndex++;
            
            // Update all slide indexes
            this.updateSlideIndexes();
            
            // Scroll to new slide
            const newSlideElement = this.container.find('.wcag-wp-slide-item').last();
            newSlideElement[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        deleteSlide(slideElement) {
            if (confirm(wcag_wp_carousel.strings.confirm_delete_slide)) {
                slideElement.fadeOut(300, () => {
                    slideElement.remove();
                    this.updateSlideIndexes();
                });
            }
        }

        toggleSlide(button) {
            const slideItem = button.closest('.wcag-wp-slide-item');
            const content = slideItem.find('.wcag-wp-slide-content');
            const icon = button.find('.dashicons');
            const isExpanded = button.attr('aria-expanded') === 'true';

            if (isExpanded) {
                content.slideUp(300);
                icon.removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
                button.attr('aria-expanded', 'false');
            } else {
                content.slideDown(300);
                icon.removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
                button.attr('aria-expanded', 'true');
            }
        }

        updateSlideIndexes() {
            this.container.find('.wcag-wp-slide-item').each((index, element) => {
                const $element = $(element);
                const oldIndex = $element.data('slide-index');
                
                // Update data attribute
                $element.attr('data-slide-index', index);
                $element.data('slide-index', index);
                
                // Update form field names
                $element.find('input, textarea, select').each((_, field) => {
                    const $field = $(field);
                    const name = $field.attr('name');
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        $field.attr('name', newName);
                    }
                });
                
                // Update slide title
                const titleInput = $element.find('input[name*="[title]"]');
                if (titleInput.length) {
                    this.updateSlideTitle(titleInput);
                }
            });
        }

        updateSlideTitle(input) {
            const slideItem = input.closest('.wcag-wp-slide-item');
            const title = input.val().trim();
            const slideIndex = slideItem.data('slide-index');
            const titleElement = slideItem.find('h4');
            
            if (title) {
                titleElement.text(title);
            } else {
                titleElement.text(`Slide ${slideIndex + 1}`);
            }
        }

        selectImage(button) {
            console.log('WCAG Carousel: selectImage chiamato');
            
            // Verifica che wp.media sia disponibile
            if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
                console.error('WCAG Carousel: wp.media non è disponibile');
                return;
            }
            
            const imageField = button.closest('.wcag-wp-image-field');
            const urlInput = imageField.find('.wcag-wp-image-url');
            const altInput = imageField.find('input[name*="[image_alt]"]');
            const preview = imageField.find('.wcag-wp-image-preview img');
            const removeButton = imageField.find('.wcag-wp-remove-image');

            console.log('WCAG Carousel: Creazione media frame');
            
            // Create media frame
            const frame = wp.media({
                title: wcag_wp_carousel.strings.select_image,
                button: {
                    text: wcag_wp_carousel.strings.select_image
                },
                multiple: false
            });

            frame.on('select', () => {
                console.log('WCAG Carousel: Immagine selezionata');
                const attachment = frame.state().get('selection').first().toJSON();
                
                urlInput.val(attachment.url);
                preview.attr('src', attachment.url);
                imageField.find('.wcag-wp-image-preview').show();
                removeButton.show();
                
                // Auto-fill alt text if empty
                if (!altInput.val() && attachment.alt) {
                    altInput.val(attachment.alt);
                }
                
                console.log('WCAG Carousel: Immagine impostata:', attachment.url);
            });

            frame.open();
        }

        removeImage(button) {
            const imageField = button.closest('.wcag-wp-image-field');
            const urlInput = imageField.find('.wcag-wp-image-url');
            const preview = imageField.find('.wcag-wp-image-preview');
            
            urlInput.val('');
            preview.hide();
            button.hide();
        }

        copyShortcode(button) {
            const shortcode = button.data('shortcode');
            
            // Create temporary textarea
            const textarea = document.createElement('textarea');
            textarea.value = shortcode;
            document.body.appendChild(textarea);
            textarea.select();
            
            try {
                document.execCommand('copy');
                
                // Show success feedback
                const originalText = button.html();
                button.html('<span class="dashicons dashicons-yes"></span> Copiato!');
                
                setTimeout(() => {
                    button.html(originalText);
                }, 2000);
                
            } catch (err) {
                console.error('Errore durante la copia:', err);
            }
            
            document.body.removeChild(textarea);
        }
    }

    // Initialize when DOM is ready
    $(document).ready(() => {
        if ($('#wcag-wp-carousel-slides-container').length) {
            new WCAGCarouselAdmin();
        }
    });

})(jQuery);
