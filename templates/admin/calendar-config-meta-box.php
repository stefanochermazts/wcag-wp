<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }
?>

<table class="form-table">
    <tr>
        <th scope="row"><?php esc_html_e('Tipo Vista', 'wcag-wp'); ?></th>
        <td>
            <select name="wcag_wp_calendar_config[view_type]">
                <option value="month" <?php selected($config['view_type'], 'month'); ?>><?php esc_html_e('Mensile', 'wcag-wp'); ?></option>
                <option value="week" <?php selected($config['view_type'], 'week'); ?>><?php esc_html_e('Settimanale', 'wcag-wp'); ?></option>
                <option value="list" <?php selected($config['view_type'], 'list'); ?>><?php esc_html_e('Lista', 'wcag-wp'); ?></option>
            </select>
            <p class="description"><?php esc_html_e('Tipo di visualizzazione predefinita del calendario.', 'wcag-wp'); ?></p>
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php esc_html_e('Giorno Inizio Settimana', 'wcag-wp'); ?></th>
        <td>
            <select name="wcag_wp_calendar_config[start_day]">
                <option value="monday" <?php selected($config['start_day'], 'monday'); ?>><?php esc_html_e('Lunedì', 'wcag-wp'); ?></option>
                <option value="sunday" <?php selected($config['start_day'], 'sunday'); ?>><?php esc_html_e('Domenica', 'wcag-wp'); ?></option>
            </select>
            <p class="description"><?php esc_html_e('Giorno con cui inizia la settimana nel calendario.', 'wcag-wp'); ?></p>
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php esc_html_e('Mostra Numeri Settimana', 'wcag-wp'); ?></th>
        <td>
            <label>
                <input type="checkbox" name="wcag_wp_calendar_config[show_week_numbers]" value="1" <?php checked($config['show_week_numbers']); ?>>
                <?php esc_html_e('Mostra i numeri delle settimane', 'wcag-wp'); ?>
            </label>
            <p class="description"><?php esc_html_e('Visualizza i numeri delle settimane accanto al calendario.', 'wcag-wp'); ?></p>
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php esc_html_e('Evidenzia Oggi', 'wcag-wp'); ?></th>
        <td>
            <label>
                <input type="checkbox" name="wcag_wp_calendar_config[show_today_highlight]" value="1" <?php checked($config['show_today_highlight']); ?>>
                <?php esc_html_e('Evidenzia la data odierna', 'wcag-wp'); ?>
            </label>
            <p class="description"><?php esc_html_e('Evidenzia visivamente la data corrente nel calendario.', 'wcag-wp'); ?></p>
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php esc_html_e('Mostra Navigazione', 'wcag-wp'); ?></th>
        <td>
            <label>
                <input type="checkbox" name="wcag_wp_calendar_config[show_navigation]" value="1" <?php checked($config['show_navigation']); ?>>
                <?php esc_html_e('Mostra controlli di navigazione', 'wcag-wp'); ?>
            </label>
            <p class="description"><?php esc_html_e('Mostra i pulsanti per navigare tra mesi/settimane.', 'wcag-wp'); ?></p>
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php esc_html_e('Navigazione Tastiera', 'wcag-wp'); ?></th>
        <td>
            <label>
                <input type="checkbox" name="wcag_wp_calendar_config[keyboard_navigation]" value="1" <?php checked($config['keyboard_navigation']); ?>>
                <?php esc_html_e('Abilita navigazione da tastiera', 'wcag-wp'); ?>
            </label>
            <p class="description"><?php esc_html_e('Permette la navigazione del calendario tramite tastiera (frecce, Tab, Enter).', 'wcag-wp'); ?></p>
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php esc_html_e('Annunci Screen Reader', 'wcag-wp'); ?></th>
        <td>
            <label>
                <input type="checkbox" name="wcag_wp_calendar_config[screen_reader_announcements]" value="1" <?php checked($config['screen_reader_announcements']); ?>>
                <?php esc_html_e('Annunci automatici per screen reader', 'wcag-wp'); ?>
            </label>
            <p class="description"><?php esc_html_e('Annuncia automaticamente eventi e cambi di data agli screen reader.', 'wcag-wp'); ?></p>
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php esc_html_e('Limite Eventi per Giorno', 'wcag-wp'); ?></th>
        <td>
            <input type="number" name="wcag_wp_calendar_config[event_limit]" value="<?php echo esc_attr($config['event_limit']); ?>" min="1" max="20" class="small-text">
            <p class="description"><?php esc_html_e('Numero massimo di eventi da mostrare per giorno nella vista calendario.', 'wcag-wp'); ?></p>
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php esc_html_e('Mostra Vista Lista', 'wcag-wp'); ?></th>
        <td>
            <label>
                <input type="checkbox" name="wcag_wp_calendar_config[show_list_view]" value="1" <?php checked($config['show_list_view']); ?>>
                <?php esc_html_e('Mostra opzione vista lista', 'wcag-wp'); ?>
            </label>
            <p class="description"><?php esc_html_e('Permette agli utenti di passare alla vista lista degli eventi.', 'wcag-wp'); ?></p>
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php esc_html_e('Classe CSS Personalizzata', 'wcag-wp'); ?></th>
        <td>
            <input type="text" name="wcag_wp_calendar_config[custom_css_class]" value="<?php echo esc_attr($config['custom_css_class']); ?>" class="regular-text">
            <p class="description"><?php esc_html_e('Classe CSS aggiuntiva per personalizzare lo stile del calendario.', 'wcag-wp'); ?></p>
        </td>
    </tr>
</table>
