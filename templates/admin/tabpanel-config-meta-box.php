<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }
?>

<div class="wcag-wp-tabpanel-config">
    <p class="description"><?php esc_html_e('Configura il comportamento del WCAG Tab Panel.', 'wcag-wp'); ?></p>

    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="first_tab_selected"><?php esc_html_e('Tab iniziale selezionato', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="number" id="first_tab_selected" name="wcag_wp_tabpanel_config[first_tab_selected]" value="<?php echo esc_attr((string)$config['first_tab_selected']); ?>" min="0" class="small-text">
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Accessibilità', 'wcag-wp'); ?></th>
                <td>
                    <label><input type="checkbox" name="wcag_wp_tabpanel_config[keyboard_navigation]" value="1" <?php checked($config['keyboard_navigation']); ?>> <?php esc_html_e('Abilita navigazione tastiera', 'wcag-wp'); ?></label>
                    <br>
                    <label><input type="checkbox" name="wcag_wp_tabpanel_config[activate_on_focus]" value="1" <?php checked($config['activate_on_focus']); ?>> <?php esc_html_e('Attiva tab al focus (vs Enter/Space)', 'wcag-wp'); ?></label>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="custom_css_class"><?php esc_html_e('Classe CSS personalizzata', 'wcag-wp'); ?></label></th>
                <td>
                    <input type="text" id="custom_css_class" name="wcag_wp_tabpanel_config[custom_css_class]" value="<?php echo esc_attr($config['custom_css_class']); ?>" class="regular-text">
                </td>
            </tr>
        </tbody>
    </table>
</div>


