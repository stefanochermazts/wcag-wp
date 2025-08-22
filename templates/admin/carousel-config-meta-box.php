<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }
?>

<div class="wcag-wp-carousel-config">
    <p class="description"><?php esc_html_e('Configura le opzioni del carousel accessibile.', 'wcag-wp'); ?></p>
    
    <table class="form-table"><tbody>
        <tr>
            <th scope="row"><?php esc_html_e('Autoplay', 'wcag-wp'); ?></th>
            <td>
                <label><input type="checkbox" name="wcag_wp_carousel_config[autoplay]" value="1" <?php checked($config['autoplay']); ?>> <?php esc_html_e('Abilita autoplay', 'wcag-wp'); ?></label><br>
                <label><?php esc_html_e('Velocità (ms):', 'wcag-wp'); ?> <input type="number" name="wcag_wp_carousel_config[autoplay_speed]" value="<?php echo esc_attr($config['autoplay_speed']); ?>" min="1000" max="30000" step="500"></label><br>
                <label><input type="checkbox" name="wcag_wp_carousel_config[pause_on_hover]" value="1" <?php checked($config['pause_on_hover']); ?>> <?php esc_html_e('Pausa al passaggio del mouse', 'wcag-wp'); ?></label>
            </td>
        </tr>
        
        <tr>
            <th scope="row"><?php esc_html_e('Controlli', 'wcag-wp'); ?></th>
            <td>
                <label><input type="checkbox" name="wcag_wp_carousel_config[show_controls]" value="1" <?php checked($config['show_controls']); ?>> <?php esc_html_e('Mostra pulsanti prev/next', 'wcag-wp'); ?></label><br>
                <label><input type="checkbox" name="wcag_wp_carousel_config[show_indicators]" value="1" <?php checked($config['show_indicators']); ?>> <?php esc_html_e('Mostra indicatori di posizione', 'wcag-wp'); ?></label>
            </td>
        </tr>
        
        <tr>
            <th scope="row"><?php esc_html_e('Accessibilità', 'wcag-wp'); ?></th>
            <td>
                <label><input type="checkbox" name="wcag_wp_carousel_config[keyboard_navigation]" value="1" <?php checked($config['keyboard_navigation']); ?>> <?php esc_html_e('Navigazione tastiera (frecce)', 'wcag-wp'); ?></label><br>
                <label><input type="checkbox" name="wcag_wp_carousel_config[touch_swipe]" value="1" <?php checked($config['touch_swipe']); ?>> <?php esc_html_e('Swipe touch su mobile', 'wcag-wp'); ?></label>
            </td>
        </tr>
        
        <tr>
            <th scope="row"><?php esc_html_e('Comportamento', 'wcag-wp'); ?></th>
            <td>
                <label><input type="checkbox" name="wcag_wp_carousel_config[infinite_loop]" value="1" <?php checked($config['infinite_loop']); ?>> <?php esc_html_e('Loop infinito', 'wcag-wp'); ?></label><br>
                <label><?php esc_html_e('Tipo animazione:', 'wcag-wp'); ?>
                    <select name="wcag_wp_carousel_config[animation_type]">
                        <option value="slide" <?php selected($config['animation_type'], 'slide'); ?>><?php esc_html_e('Slide', 'wcag-wp'); ?></option>
                        <option value="fade" <?php selected($config['animation_type'], 'fade'); ?>><?php esc_html_e('Dissolvenza', 'wcag-wp'); ?></option>
                    </select>
                </label>
            </td>
        </tr>
        
        <tr>
            <th scope="row"><?php esc_html_e('Classe CSS personalizzata', 'wcag-wp'); ?></th>
            <td>
                <input type="text" name="wcag_wp_carousel_config[custom_css_class]" value="<?php echo esc_attr($config['custom_css_class']); ?>" class="regular-text">
                <p class="description"><?php esc_html_e('Classe CSS aggiuntiva per personalizzazione', 'wcag-wp'); ?></p>
            </td>
        </tr>
    </tbody></table>
</div>
