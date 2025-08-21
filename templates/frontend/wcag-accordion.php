<?php
declare(strict_types=1);

/**
 * Frontend WCAG Accordion Template
 * 
 * @package WCAG_WP
 * @since 1.0.0
 * 
 * @var int $accordion_id Accordion post ID
 * @var WP_Post $accordion_post Accordion post object
 * @var array $config Accordion configuration
 * @var array $sections Accordion sections
 * @var array $options Display options
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Helper function to get icon HTML - must be defined before use
if (!function_exists('get_icon_html')) {
    function get_icon_html($icon_type, $is_open) {
        switch ($icon_type) {
            case 'plus_minus':
                return $is_open ? '−' : '+';
            case 'arrow':
                return $is_open ? '▼' : '▶';
            case 'chevron':
            default:
                return $is_open ? '⌄' : '›';
        }
    }
}

// Generate unique IDs for accessibility
$accordion_html_id = 'wcag-accordion-' . $accordion_id;

// Build CSS classes
$container_classes = [
    'wcag-wp-accordion-container',
    'wcag-wp',
    'wcag-component',
    'wcag-accordion'
];

if (!empty($options['class'])) {
    $container_classes[] = sanitize_html_class($options['class']);
}

if (!empty($config['custom_css_class'])) {
    $container_classes[] = sanitize_html_class($config['custom_css_class']);
}

$accordion_classes = [
    'wcag-wp-accordion'
];

if ($config['keyboard_navigation'] ?? true) {
    $accordion_classes[] = 'wcag-wp-accordion--keyboard-enabled';
}

if ($config['animate_transitions'] ?? true) {
    $accordion_classes[] = 'wcag-wp-accordion--animated';
}

if ($config['allow_multiple_open'] ?? false) {
    $accordion_classes[] = 'wcag-wp-accordion--multiple';
}

// Icon configuration
$icon_type = $config['icon_type'] ?? 'chevron';
$icon_position = $config['icon_position'] ?? 'right';

?>

<div class="<?php echo esc_attr(implode(' ', $container_classes)); ?>" 
     data-accordion-id="<?php echo esc_attr($accordion_id); ?>"
     data-component="wcag-accordion"
     data-allow-multiple="<?php echo $config['allow_multiple_open'] ? 'true' : 'false'; ?>"
     data-keyboard-nav="<?php echo $config['keyboard_navigation'] ? 'true' : 'false'; ?>"
     data-animate="<?php echo $config['animate_transitions'] ? 'true' : 'false'; ?>">
    
    <!-- Skip Link for Screen Readers -->
    <a href="#<?php echo esc_attr($accordion_html_id); ?>" class="wcag-wp skip-link">
        <?php esc_html_e('Salta al WCAG Accordion', 'wcag-wp'); ?>
    </a>
    
    <!-- WCAG Accordion Header -->
    <?php if (!empty($accordion_post->post_title)): ?>
        <div class="wcag-wp-accordion-header">
            <h3 class="wcag-wp-accordion-title">
                <?php echo esc_html($accordion_post->post_title); ?>
            </h3>
        </div>
    <?php endif; ?>
    
    <!-- WCAG Accordion Content -->
    <div class="<?php echo esc_attr(implode(' ', $accordion_classes)); ?>" 
         id="<?php echo esc_attr($accordion_html_id); ?>"
         role="tablist"
         aria-multiselectable="<?php echo $config['allow_multiple_open'] ? 'true' : 'false'; ?>">
        
        <?php foreach ($sections as $section_index => $section): ?>
            <?php
            if (empty($section['id']) || empty($section['title'])) continue;
            
            // Generate unique IDs for this section
            $section_id = sanitize_html_class($section['id']);
            $header_id = "wcag-accordion-header-{$accordion_id}-{$section_id}";
            $panel_id = "wcag-accordion-panel-{$accordion_id}-{$section_id}";
            
            // Determine if section should be open
            $is_open = false;
            if (isset($options['first_open']) && $options['first_open'] !== null) {
                $is_open = ($section_index === 0) && (bool)$options['first_open'];
            } elseif (isset($options['allow_multiple']) && $options['allow_multiple'] !== null) {
                $is_open = (bool)($section['is_open'] ?? false);
            } else {
                if ($config['first_panel_open'] && $section_index === 0) {
                    $is_open = true;
                } else {
                    $is_open = (bool)($section['is_open'] ?? false);
                }
            }
            
            // Section classes
            $section_classes = ['wcag-wp-accordion-section'];
            if (!empty($section['css_class'])) {
                $section_classes[] = sanitize_html_class($section['css_class']);
            }
            if ($is_open) {
                $section_classes[] = 'wcag-wp-accordion-section--open';
            }
            if ($section['disabled'] ?? false) {
                $section_classes[] = 'wcag-wp-accordion-section--disabled';
            }
            ?>
            
            <div class="<?php echo esc_attr(implode(' ', $section_classes)); ?>" 
                 data-section-id="<?php echo esc_attr($section_id); ?>">
                
                <!-- Section Header/Button -->
                <h4 class="wcag-wp-accordion-header">
                    <button type="button"
                            class="wcag-wp-accordion-button"
                            id="<?php echo esc_attr($header_id); ?>"
                            aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                            aria-controls="<?php echo esc_attr($panel_id); ?>"
                            <?php if ($section['disabled'] ?? false): ?>
                            disabled
                            aria-disabled="true"
                            <?php endif; ?>
                            tabindex="0">
                        
                        <?php if ($icon_position === 'left' && $icon_type !== 'none'): ?>
                            <span class="wcag-wp-accordion-icon wcag-wp-accordion-icon--left wcag-wp-accordion-icon--<?php echo esc_attr($icon_type); ?>" 
                                  aria-hidden="true">
                                <?php echo get_icon_html($icon_type, $is_open); ?>
                            </span>
                        <?php endif; ?>
                        
                        <span class="wcag-wp-accordion-title">
                            <?php echo esc_html($section['title']); ?>
                        </span>
                        
                        <?php if ($icon_position === 'right' && $icon_type !== 'none'): ?>
                            <span class="wcag-wp-accordion-icon wcag-wp-accordion-icon--right wcag-wp-accordion-icon--<?php echo esc_attr($icon_type); ?>" 
                                  aria-hidden="true">
                                <?php echo get_icon_html($icon_type, $is_open); ?>
                            </span>
                        <?php endif; ?>
                        
                        <!-- Screen Reader State Announcement -->
                        <span class="sr-only wcag-wp-accordion-state">
                            <?php echo $is_open ? __('Sezione aperta', 'wcag-wp') : __('Sezione chiusa', 'wcag-wp'); ?>
                        </span>
                    </button>
                </h4>
                
                <!-- Section Panel/Content -->
                <div class="wcag-wp-accordion-panel <?php echo $is_open ? 'wcag-wp-accordion-panel--open' : ''; ?>"
                     id="<?php echo esc_attr($panel_id); ?>"
                     role="tabpanel"
                     aria-labelledby="<?php echo esc_attr($header_id); ?>"
                     <?php if (!$is_open): ?>style="display: none;"<?php endif; ?>>
                    
                    <div class="wcag-wp-accordion-content">
                        <?php 
                        // Process content - allow shortcodes and basic HTML
                        $content = $section['content'] ?? '';
                        if (!empty($content)) {
                            echo wp_kses_post(do_shortcode($content));
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Screen Reader Announcements -->
    <div class="wcag-wp-sr-announcements" 
         aria-live="polite" 
         aria-atomic="true" 
         class="sr-only">
    </div>
    
    <!-- Keyboard Instructions (hidden by default, shown on focus) -->
    <div class="wcag-wp-keyboard-instructions" 
         id="<?php echo esc_attr($accordion_html_id); ?>-instructions"
         tabindex="-1">
        <h5><?php esc_html_e('Istruzioni Navigazione Tastiera:', 'wcag-wp'); ?></h5>
        <ul>
            <li><?php esc_html_e('Tab/Shift+Tab: Navigare tra le sezioni', 'wcag-wp'); ?></li>
            <li><?php esc_html_e('Spazio/Enter: Aprire/chiudere la sezione corrente', 'wcag-wp'); ?></li>
            <li><?php esc_html_e('Freccia Giù/Su: Muoversi alla sezione successiva/precedente', 'wcag-wp'); ?></li>
            <li><?php esc_html_e('Home: Andare alla prima sezione', 'wcag-wp'); ?></li>
            <li><?php esc_html_e('End: Andare all\'ultima sezione', 'wcag-wp'); ?></li>
        </ul>
        <button type="button" class="wcag-wp-close-instructions">
            <?php esc_html_e('Chiudi istruzioni', 'wcag-wp'); ?>
        </button>
    </div>
</div>



<!-- Initialize WCAG accordion functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.wcagWpAccordionFrontend !== 'undefined') {
        // WCAG Accordion will be automatically initialized by the frontend JavaScript
        console.log('WCAG Accordion <?php echo esc_js($accordion_id); ?> initialized');
    } else {
        // Fallback basic functionality
        const accordion = document.getElementById('<?php echo esc_js($accordion_html_id); ?>');
        if (accordion) {
            const buttons = accordion.querySelectorAll('.wcag-wp-accordion-button');
            const allowMultiple = accordion.closest('[data-allow-multiple]').getAttribute('data-allow-multiple') === 'true';
            
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const panel = document.getElementById(this.getAttribute('aria-controls'));
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    
                    if (!allowMultiple) {
                        // Close all other panels
                        buttons.forEach(otherButton => {
                            if (otherButton !== this) {
                                const otherPanel = document.getElementById(otherButton.getAttribute('aria-controls'));
                                otherButton.setAttribute('aria-expanded', 'false');
                                otherPanel.style.display = 'none';
                                otherPanel.classList.remove('wcag-wp-accordion-panel--open');
                                otherButton.closest('.wcag-wp-accordion-section').classList.remove('wcag-wp-accordion-section--open');
                            }
                        });
                    }
                    
                    // Toggle current panel
                    if (isExpanded) {
                        this.setAttribute('aria-expanded', 'false');
                        panel.style.display = 'none';
                        panel.classList.remove('wcag-wp-accordion-panel--open');
                        this.closest('.wcag-wp-accordion-section').classList.remove('wcag-wp-accordion-section--open');
                    } else {
                        this.setAttribute('aria-expanded', 'true');
                        panel.style.display = 'block';
                        panel.classList.add('wcag-wp-accordion-panel--open');
                        this.closest('.wcag-wp-accordion-section').classList.add('wcag-wp-accordion-section--open');
                    }
                    
                    // Update screen reader text
                    const srText = this.querySelector('.wcag-wp-accordion-state');
                    if (srText) {
                        srText.textContent = this.getAttribute('aria-expanded') === 'true' 
                            ? '<?php echo esc_js(__("Sezione aperta", "wcag-wp")); ?>' 
                            : '<?php echo esc_js(__("Sezione chiusa", "wcag-wp")); ?>';
                    }
                });
                
                // Basic keyboard navigation
                button.addEventListener('keydown', function(e) {
                    switch(e.key) {
                        case 'ArrowDown':
                        case 'ArrowRight':
                            e.preventDefault();
                            const nextButton = this.closest('.wcag-wp-accordion-section').nextElementSibling?.querySelector('.wcag-wp-accordion-button');
                            if (nextButton) nextButton.focus();
                            break;
                        case 'ArrowUp':
                        case 'ArrowLeft':
                            e.preventDefault();
                            const prevButton = this.closest('.wcag-wp-accordion-section').previousElementSibling?.querySelector('.wcag-wp-accordion-button');
                            if (prevButton) prevButton.focus();
                            break;
                        case 'Home':
                            e.preventDefault();
                            buttons[0]?.focus();
                            break;
                        case 'End':
                            e.preventDefault();
                            buttons[buttons.length - 1]?.focus();
                            break;
                    }
                });
            });
        }
    }
});
</script>
