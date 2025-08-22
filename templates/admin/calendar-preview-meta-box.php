<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }
?>

<div class="wcag-wp-calendar-preview">
    <h4><?php esc_html_e('Shortcode', 'wcag-wp'); ?></h4>
    <p><?php esc_html_e('Usa questo shortcode per inserire il calendario in una pagina o post:', 'wcag-wp'); ?></p>
    
    <div class="wcag-wp-shortcode-display">
        <code id="wcag-wp-calendar-shortcode">[wcag-calendar id="<?php echo esc_attr($post->ID); ?>"]</code>
        <button type="button" class="wcag-wp-copy-shortcode button-secondary" data-shortcode="[wcag-calendar id=&quot;<?php echo esc_attr($post->ID); ?>&quot;]">
            <span class="dashicons dashicons-clipboard"></span>
            <?php esc_html_e('Copia', 'wcag-wp'); ?>
        </button>
    </div>
    
    <h4><?php esc_html_e('Parametri Opzionali', 'wcag-wp'); ?></h4>
    <p><?php esc_html_e('Puoi personalizzare il calendario con questi parametri:', 'wcag-wp'); ?></p>
    
    <ul class="wcag-wp-shortcode-params">
        <li><code>view="month|week|list"</code> - <?php esc_html_e('Tipo di vista', 'wcag-wp'); ?></li>
        <li><code>month="12"</code> - <?php esc_html_e('Mese specifico (1-12)', 'wcag-wp'); ?></li>
        <li><code>year="2024"</code> - <?php esc_html_e('Anno specifico', 'wcag-wp'); ?></li>
        <li><code>class="my-custom-class"</code> - <?php esc_html_e('Classe CSS personalizzata', 'wcag-wp'); ?></li>
    </ul>
    
    <h4><?php esc_html_e('Esempi', 'wcag-wp'); ?></h4>
    <div class="wcag-wp-shortcode-examples">
        <p><code>[wcag-calendar id="<?php echo esc_attr($post->ID); ?>" view="week"]</code></p>
        <p><code>[wcag-calendar id="<?php echo esc_attr($post->ID); ?>" month="6" year="2024"]</code></p>
        <p><code>[wcag-calendar id="<?php echo esc_attr($post->ID); ?>" class="my-calendar"]</code></p>
    </div>
    
    <?php
    // Mostra statistiche se ci sono eventi
    $events = get_post_meta($post->ID, '_wcag_wp_calendar_events', true);
    if (is_array($events) && !empty($events)):
    ?>
    <h4><?php esc_html_e('Statistiche Eventi', 'wcag-wp'); ?></h4>
    <div class="wcag-wp-calendar-stats">
        <p><strong><?php esc_html_e('Totale eventi:', 'wcag-wp'); ?></strong> <?php echo count($events); ?></p>
        
        <?php
        $categories = [];
        $all_day_count = 0;
        $upcoming_count = 0;
        $today = date('Y-m-d');
        
        foreach ($events as $event) {
            if (!empty($event['category'])) {
                $categories[] = $event['category'];
            }
            if ($event['all_day']) {
                $all_day_count++;
            }
            if ($event['start_date'] >= $today) {
                $upcoming_count++;
            }
        }
        ?>
        
        <?php if (!empty($categories)): ?>
        <p><strong><?php esc_html_e('Categorie:', 'wcag-wp'); ?></strong> <?php echo esc_html(implode(', ', array_unique($categories))); ?></p>
        <?php endif; ?>
        
        <p><strong><?php esc_html_e('Eventi tutto il giorno:', 'wcag-wp'); ?></strong> <?php echo $all_day_count; ?></p>
        <p><strong><?php esc_html_e('Eventi futuri:', 'wcag-wp'); ?></strong> <?php echo $upcoming_count; ?></p>
    </div>
    <?php endif; ?>
    
    <h4><?php esc_html_e('Accessibilità WCAG 2.1 AA', 'wcag-wp'); ?></h4>
    <div class="wcag-wp-accessibility-info">
        <ul>
            <li><?php esc_html_e('✓ Navigazione tastiera completa', 'wcag-wp'); ?></li>
            <li><?php esc_html_e('✓ Annunci screen reader', 'wcag-wp'); ?></li>
            <li><?php esc_html_e('✓ ARIA labels e roles', 'wcag-wp'); ?></li>
            <li><?php esc_html_e('✓ Focus management', 'wcag-wp'); ?></li>
            <li><?php esc_html_e('✓ Contrasto colori adeguato', 'wcag-wp'); ?></li>
        </ul>
    </div>
</div>
