<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }

// Debug output
if (defined('WP_DEBUG') && WP_DEBUG) {
    echo "<!-- WCAG Calendar Debug - Config: " . htmlspecialchars(json_encode($config)) . " -->\n";
    echo "<!-- WCAG Calendar Debug - Events: " . htmlspecialchars(json_encode($events)) . " -->\n";
}

// Preparazione dati per il calendario
$current_month = isset($options['month']) ? absint($options['month']) : (int)date('n');
$current_year = isset($options['year']) ? absint($options['year']) : (int)date('Y');
$view_type = $options['view'] ?? $config['view_type'] ?? 'month';

// Calcolo date per il calendario
$first_day = mktime(0, 0, 0, $current_month, 1, $current_year);
$days_in_month = date('t', $first_day);
$first_day_of_week = date('w', $first_day);
$start_day_offset = $config['start_day'] === 'monday' ? ($first_day_of_week == 0 ? 6 : $first_day_of_week - 1) : $first_day_of_week;

// Organizzazione eventi per data
$events_by_date = [];
foreach ($events as $event) {
    $start_date = $event['start_date'];
    if (!isset($events_by_date[$start_date])) {
        $events_by_date[$start_date] = [];
    }
    $events_by_date[$start_date][] = $event;
}

$calendar_id = 'wcag-calendar-' . $post_id;
$custom_class = $config['custom_css_class'] ? ' ' . esc_attr($config['custom_css_class']) : '';
?>

<div id="<?php echo esc_attr($calendar_id); ?>" class="wcag-wp wcag-wp-calendar<?php echo $custom_class; ?>" 
     data-config='<?php echo wp_json_encode($config); ?>'
     data-events='<?php echo wp_json_encode($events); ?>'
     data-current-month="<?php echo esc_attr($current_month); ?>"
     data-current-year="<?php echo esc_attr($current_year); ?>"
     data-view-type="<?php echo esc_attr($view_type); ?>"
     role="application" 
     aria-label="<?php echo esc_attr(sprintf(__('Calendario %s', 'wcag-wp'), $post->post_title)); ?>">

    <?php if ($config['show_navigation']): ?>
    <div class="wcag-wp-calendar-navigation" role="toolbar" aria-label="<?php esc_attr_e('Navigazione calendario', 'wcag-wp'); ?>">
        <button type="button" class="wcag-wp-calendar-prev" aria-label="<?php esc_attr_e('Mese precedente', 'wcag-wp'); ?>">
            <span class="dashicons dashicons-arrow-left-alt2"></span>
        </button>
        
        <h2 class="wcag-wp-calendar-title" id="<?php echo esc_attr($calendar_id); ?>-title">
            <?php echo esc_html(date_i18n('F Y', $first_day)); ?>
        </h2>
        
        <button type="button" class="wcag-wp-calendar-next" aria-label="<?php esc_attr_e('Mese successivo', 'wcag-wp'); ?>">
            <span class="dashicons dashicons-arrow-right-alt2"></span>
        </button>
        
        <?php if ($config['show_list_view']): ?>
        <div class="wcag-wp-calendar-view-toggle">
            <button type="button" class="wcag-wp-calendar-view-btn active" data-view="calendar" aria-pressed="true">
                <?php esc_html_e('Calendario', 'wcag-wp'); ?>
            </button>
            <button type="button" class="wcag-wp-calendar-view-btn" data-view="list" aria-pressed="false">
                <?php esc_html_e('Lista', 'wcag-wp'); ?>
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="wcag-wp-calendar-content">
        <!-- Vista Calendario -->
        <div class="wcag-wp-calendar-view wcag-wp-calendar-grid" role="grid" aria-labelledby="<?php echo esc_attr($calendar_id); ?>-title">
            <!-- Header giorni della settimana -->
            <div class="wcag-wp-calendar-header" role="row">
                <?php
                $weekdays = $config['start_day'] === 'monday' 
                    ? [__('Lun', 'wcag-wp'), __('Mar', 'wcag-wp'), __('Mer', 'wcag-wp'), __('Gio', 'wcag-wp'), __('Ven', 'wcag-wp'), __('Sab', 'wcag-wp'), __('Dom', 'wcag-wp')]
                    : [__('Dom', 'wcag-wp'), __('Lun', 'wcag-wp'), __('Mar', 'wcag-wp'), __('Mer', 'wcag-wp'), __('Gio', 'wcag-wp'), __('Ven', 'wcag-wp'), __('Sab', 'wcag-wp')];
                
                foreach ($weekdays as $weekday):
                ?>
                <div class="wcag-wp-calendar-day-header" role="columnheader" scope="col">
                    <?php echo esc_html($weekday); ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Griglia giorni -->
            <div class="wcag-wp-calendar-grid-body" role="rowgroup">
                <?php
                $day_count = 1;
                $total_cells = ceil(($days_in_month + $start_day_offset) / 7) * 7;
                
                for ($i = 0; $i < $total_cells; $i++):
                    $is_current_month = $i >= $start_day_offset && $day_count <= $days_in_month;
                    $current_date = $is_current_month ? sprintf('%04d-%02d-%02d', $current_year, $current_month, $day_count) : '';
                    $is_today = $current_date === date('Y-m-d');
                    $day_events = $is_current_month && isset($events_by_date[$current_date]) ? $events_by_date[$current_date] : [];
                    $week_index = (int) floor($i / 7);
                    $day_of_week = $i % 7;
                    
                    $day_classes = ['wcag-wp-calendar-day'];
                    if ($is_current_month) {
                        $day_classes[] = 'current-month';
                        if ($is_today && $config['show_today_highlight']) {
                            $day_classes[] = 'today';
                        }
                        if (!empty($day_events)) {
                            $day_classes[] = 'has-events';
                        }
                    } else {
                        $day_classes[] = 'other-month';
                    }
                ?>
                <div class="<?php echo esc_attr(implode(' ', $day_classes)); ?>" 
                     role="gridcell" 
                     <?php if ($is_current_month): ?>
                     tabindex="0"
                     aria-label="<?php echo esc_attr(sprintf(__('%s %d %s', 'wcag-wp'), date_i18n('l', strtotime($current_date)), $day_count, date_i18n('F Y', strtotime($current_date)))); ?>"
                     data-date="<?php echo esc_attr($current_date); ?>"
                     data-week-index="<?php echo esc_attr((string) $week_index); ?>"
                     data-day-of-week="<?php echo esc_attr((string) $day_of_week); ?>"
                     <?php endif; ?>>
                    
                    <div class="wcag-wp-calendar-day-number">
                        <?php echo $is_current_month ? esc_html($day_count) : '&nbsp;'; ?>
                    </div>
                    
                    <?php if (!empty($day_events)): ?>
                    <div class="wcag-wp-calendar-day-events">
                        <?php 
                        $events_to_show = array_slice($day_events, 0, $config['event_limit']);
                        foreach ($events_to_show as $event_index => $event): 
                        ?>
                        <div class="wcag-wp-calendar-event" 
                             style="background-color: <?php echo esc_attr($event['color']); ?>"
                             aria-label="<?php echo esc_attr($event['title']); ?>">
                            <span class="wcag-wp-calendar-event-title"><?php echo esc_html($event['title']); ?></span>
                            <?php if ($event_index === $config['event_limit'] - 1 && count($day_events) > $config['event_limit']): ?>
                            <span class="wcag-wp-calendar-event-more">+<?php echo count($day_events) - $config['event_limit']; ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php
                    if ($is_current_month) {
                        $day_count++;
                    }
                endfor;
                ?>
            </div>
        </div>

        <!-- Vista Lista -->
        <?php if ($config['show_list_view']): ?>
        <div class="wcag-wp-calendar-view wcag-wp-calendar-list" style="display: none;" role="list" aria-labelledby="<?php echo esc_attr($calendar_id); ?>-title">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                <div class="wcag-wp-calendar-list-event" role="listitem">
                    <div class="wcag-wp-calendar-list-event-header">
                        <h3 class="wcag-wp-calendar-list-event-title"><?php echo esc_html($event['title']); ?></h3>
                        <div class="wcag-wp-calendar-list-event-date">
                            <?php 
                            $start_date_obj = new DateTime($event['start_date']);
                            $end_date_obj = new DateTime($event['end_date']);
                            
                            if ($event['start_date'] === $event['end_date']) {
                                echo esc_html($start_date_obj->format('j F Y'));
                            } else {
                                echo esc_html(sprintf(__('Dal %s al %s', 'wcag-wp'), 
                                    $start_date_obj->format('j F Y'), 
                                    $end_date_obj->format('j F Y')));
                            }
                            
                            if (!$event['all_day'] && !empty($event['start_time'])) {
                                echo ' ' . esc_html($event['start_time']);
                                if (!empty($event['end_time'])) {
                                    echo ' - ' . esc_html($event['end_time']);
                                }
                            }
                            ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($event['description'])): ?>
                    <div class="wcag-wp-calendar-list-event-description">
                        <?php echo wp_kses_post($event['description']); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($event['location'])): ?>
                    <div class="wcag-wp-calendar-list-event-location">
                        <strong><?php esc_html_e('Luogo:', 'wcag-wp'); ?></strong> <?php echo esc_html($event['location']); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($event['link_url'])): ?>
                    <div class="wcag-wp-calendar-list-event-link">
                        <a href="<?php echo esc_url($event['link_url']); ?>" class="wcag-wp-calendar-event-link">
                            <?php echo esc_html($event['link_text'] ?: __('Maggiori informazioni', 'wcag-wp')); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="wcag-wp-calendar-list-empty">
                    <p><?php esc_html_e('Nessun evento programmato.', 'wcag-wp'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Live region per annunci screen reader -->
    <div class="wcag-wp-calendar-live-region" aria-live="polite" aria-atomic="true" style="position: absolute; left: -10000px; width: 1px; height: 1px; overflow: hidden;"></div>
</div>
