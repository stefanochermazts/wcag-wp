<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }
?>

<div class="wcag-wp-calendar-events">
    <p class="description"><?php esc_html_e('Gestisci gli eventi del calendario. Trascina per riordinare.', 'wcag-wp'); ?></p>
    
    <div id="wcag-wp-calendar-events-container">
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $index => $event): ?>
                <div class="wcag-wp-event-item" data-event-index="<?php echo esc_attr($index); ?>">
                    <div class="wcag-wp-event-header">
                        <span class="wcag-wp-event-handle dashicons dashicons-menu"></span>
                        <h4><?php echo esc_html($event['title'] ?: __('Nuovo Evento', 'wcag-wp')); ?></h4>
                        <span class="wcag-wp-event-date"><?php echo esc_html($event['start_date'] ?: __('Data non impostata', 'wcag-wp')); ?></span>
                        <button type="button" class="wcag-wp-event-toggle button-secondary" aria-expanded="true">
                            <span class="dashicons dashicons-arrow-up-alt2"></span>
                        </button>
                        <button type="button" class="wcag-wp-event-delete button-link-delete" aria-label="<?php esc_attr_e('Elimina evento', 'wcag-wp'); ?>">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </div>
                    
                    <div class="wcag-wp-event-content">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php esc_html_e('Titolo Evento', 'wcag-wp'); ?></th>
                                <td>
                                    <input type="text" name="wcag_wp_calendar_events[<?php echo esc_attr($index); ?>][title]" value="<?php echo esc_attr($event['title']); ?>" class="regular-text">
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Descrizione', 'wcag-wp'); ?></th>
                                <td>
                                    <?php 
                                    wp_editor(
                                        $event['description'],
                                        'wcag_wp_calendar_event_description_' . $index,
                                        [
                                            'textarea_name' => 'wcag_wp_calendar_events[' . $index . '][description]',
                                            'textarea_rows' => 4,
                                            'media_buttons' => false,
                                            'teeny' => true,
                                            'quicktags' => false,
                                        ]
                                    );
                                    ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Data Inizio', 'wcag-wp'); ?></th>
                                <td>
                                    <input type="date" name="wcag_wp_calendar_events[<?php echo esc_attr($index); ?>][start_date]" value="<?php echo esc_attr($event['start_date']); ?>" class="wcag-wp-date-picker">
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Data Fine', 'wcag-wp'); ?></th>
                                <td>
                                    <input type="date" name="wcag_wp_calendar_events[<?php echo esc_attr($index); ?>][end_date]" value="<?php echo esc_attr($event['end_date']); ?>" class="wcag-wp-date-picker">
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Tutto il Giorno', 'wcag-wp'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="wcag_wp_calendar_events[<?php echo esc_attr($index); ?>][all_day]" value="1" <?php checked($event['all_day']); ?> class="wcag-wp-all-day-toggle">
                                        <?php esc_html_e('Evento per tutto il giorno', 'wcag-wp'); ?>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr class="wcag-wp-time-fields" <?php echo $event['all_day'] ? 'style="display:none;"' : ''; ?>>
                                <th scope="row"><?php esc_html_e('Ora Inizio', 'wcag-wp'); ?></th>
                                <td>
                                    <input type="time" name="wcag_wp_calendar_events[<?php echo esc_attr($index); ?>][start_time]" value="<?php echo esc_attr($event['start_time']); ?>">
                                </td>
                            </tr>
                            
                            <tr class="wcag-wp-time-fields" <?php echo $event['all_day'] ? 'style="display:none;"' : ''; ?>>
                                <th scope="row"><?php esc_html_e('Ora Fine', 'wcag-wp'); ?></th>
                                <td>
                                    <input type="time" name="wcag_wp_calendar_events[<?php echo esc_attr($index); ?>][end_time]" value="<?php echo esc_attr($event['end_time']); ?>">
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Luogo', 'wcag-wp'); ?></th>
                                <td>
                                    <input type="text" name="wcag_wp_calendar_events[<?php echo esc_attr($index); ?>][location]" value="<?php echo esc_attr($event['location']); ?>" class="regular-text" placeholder="<?php esc_attr_e('Indirizzo o luogo dell\'evento', 'wcag-wp'); ?>">
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Categoria', 'wcag-wp'); ?></th>
                                <td>
                                    <input type="text" name="wcag_wp_calendar_events[<?php echo esc_attr($index); ?>][category]" value="<?php echo esc_attr($event['category']); ?>" class="regular-text" placeholder="<?php esc_attr_e('Categoria evento', 'wcag-wp'); ?>">
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Colore', 'wcag-wp'); ?></th>
                                <td>
                                    <input type="color" name="wcag_wp_calendar_events[<?php echo esc_attr($index); ?>][color]" value="<?php echo esc_attr($event['color']); ?>" class="wcag-wp-color-picker">
                                    <p class="description"><?php esc_html_e('Colore per identificare l\'evento nel calendario.', 'wcag-wp'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Link Evento', 'wcag-wp'); ?></th>
                                <td>
                                    <input type="url" name="wcag_wp_calendar_events[<?php echo esc_attr($index); ?>][link_url]" value="<?php echo esc_attr($event['link_url']); ?>" class="regular-text" placeholder="<?php esc_attr_e('URL pagina evento', 'wcag-wp'); ?>"><br>
                                    <input type="text" name="wcag_wp_calendar_events[<?php echo esc_attr($index); ?>][link_text]" value="<?php echo esc_attr($event['link_text']); ?>" class="regular-text" placeholder="<?php esc_attr_e('Testo del link', 'wcag-wp'); ?>">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="wcag-wp-event-actions">
        <button type="button" id="wcag-wp-add-event" class="button button-primary">
            <span class="dashicons dashicons-plus-alt2"></span>
            <?php esc_html_e('Aggiungi Evento', 'wcag-wp'); ?>
        </button>
    </div>
    
    <!-- Template per nuovo evento -->
    <script type="text/template" id="wcag-wp-event-template">
        <div class="wcag-wp-event-item" data-event-index="{{index}}">
            <div class="wcag-wp-event-header">
                <span class="wcag-wp-event-handle dashicons dashicons-menu"></span>
                <h4><?php esc_html_e('Nuovo Evento', 'wcag-wp'); ?></h4>
                <span class="wcag-wp-event-date"><?php esc_html_e('Data non impostata', 'wcag-wp'); ?></span>
                <button type="button" class="wcag-wp-event-toggle button-secondary" aria-expanded="true">
                    <span class="dashicons dashicons-arrow-up-alt2"></span>
                </button>
                <button type="button" class="wcag-wp-event-delete button-link-delete" aria-label="<?php esc_attr_e('Elimina evento', 'wcag-wp'); ?>">
                    <span class="dashicons dashicons-trash"></span>
                </button>
            </div>
            
            <div class="wcag-wp-event-content">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Titolo Evento', 'wcag-wp'); ?></th>
                        <td>
                            <input type="text" name="wcag_wp_calendar_events[{{index}}][title]" value="" class="regular-text">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Descrizione', 'wcag-wp'); ?></th>
                        <td>
                            <textarea name="wcag_wp_calendar_events[{{index}}][description]" rows="4" class="large-text"></textarea>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Data Inizio', 'wcag-wp'); ?></th>
                        <td>
                            <input type="date" name="wcag_wp_calendar_events[{{index}}][start_date]" value="" class="wcag-wp-date-picker">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Data Fine', 'wcag-wp'); ?></th>
                        <td>
                            <input type="date" name="wcag_wp_calendar_events[{{index}}][end_date]" value="" class="wcag-wp-date-picker">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Tutto il Giorno', 'wcag-wp'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="wcag_wp_calendar_events[{{index}}][all_day]" value="1" class="wcag-wp-all-day-toggle">
                                <?php esc_html_e('Evento per tutto il giorno', 'wcag-wp'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr class="wcag-wp-time-fields">
                        <th scope="row"><?php esc_html_e('Ora Inizio', 'wcag-wp'); ?></th>
                        <td>
                            <input type="time" name="wcag_wp_calendar_events[{{index}}][start_time]" value="">
                        </td>
                    </tr>
                    
                    <tr class="wcag-wp-time-fields">
                        <th scope="row"><?php esc_html_e('Ora Fine', 'wcag-wp'); ?></th>
                        <td>
                            <input type="time" name="wcag_wp_calendar_events[{{index}}][end_time]" value="">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Luogo', 'wcag-wp'); ?></th>
                        <td>
                            <input type="text" name="wcag_wp_calendar_events[{{index}}][location]" value="" class="regular-text" placeholder="<?php esc_attr_e('Indirizzo o luogo dell\'evento', 'wcag-wp'); ?>">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Categoria', 'wcag-wp'); ?></th>
                        <td>
                            <input type="text" name="wcag_wp_calendar_events[{{index}}][category]" value="" class="regular-text" placeholder="<?php esc_attr_e('Categoria evento', 'wcag-wp'); ?>">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Colore', 'wcag-wp'); ?></th>
                        <td>
                            <input type="color" name="wcag_wp_calendar_events[{{index}}][color]" value="#0073aa" class="wcag-wp-color-picker">
                            <p class="description"><?php esc_html_e('Colore per identificare l\'evento nel calendario.', 'wcag-wp'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Link Evento', 'wcag-wp'); ?></th>
                        <td>
                            <input type="url" name="wcag_wp_calendar_events[{{index}}][link_url]" value="" class="regular-text" placeholder="<?php esc_attr_e('URL pagina evento', 'wcag-wp'); ?>"><br>
                            <input type="text" name="wcag_wp_calendar_events[{{index}}][link_text]" value="" class="regular-text" placeholder="<?php esc_attr_e('Testo del link', 'wcag-wp'); ?>">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </script>
</div>
