<?php
declare(strict_types=1);
if (!defined('ABSPATH')) { exit; }
?>

<div class="wcag-wp-toc-config">
    <p class="description"><?php esc_html_e('Configura l’indice dei contenuti generato automaticamente.', 'wcag-wp'); ?></p>
    <table class="form-table"><tbody>
        <tr>
            <th scope="row"><?php esc_html_e('Titolo indice', 'wcag-wp'); ?></th>
            <td>
                <label><input type="checkbox" name="wcag_wp_toc_config[show_title]" value="1" <?php checked($config['show_title']); ?>> <?php esc_html_e('Mostra titolo', 'wcag-wp'); ?></label><br>
                <input type="text" name="wcag_wp_toc_config[title_text]" value="<?php echo esc_attr($config['title_text']); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e('Livelli da includere', 'wcag-wp'); ?></th>
            <td>
                <?php foreach (['h2','h3','h4','h5','h6'] as $level): ?>
                    <label style="margin-right:12px;">
                        <input type="checkbox" name="wcag_wp_toc_config[levels][]" value="<?php echo esc_attr($level); ?>" <?php checked(in_array($level, $config['levels'], true)); ?>>
                        <?php echo esc_html(strtoupper($level)); ?>
                    </label>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e('Opzioni', 'wcag-wp'); ?></th>
            <td>
                <label><input type="checkbox" name="wcag_wp_toc_config[numbered]" value="1" <?php checked($config['numbered']); ?>> <?php esc_html_e('Numerazione voci', 'wcag-wp'); ?></label><br>
                <label><input type="checkbox" name="wcag_wp_toc_config[collapsible]" value="1" <?php checked($config['collapsible']); ?>> <?php esc_html_e('Collassabile', 'wcag-wp'); ?></label><br>
                <label><input type="checkbox" name="wcag_wp_toc_config[smooth]" value="1" <?php checked($config['smooth']); ?>> <?php esc_html_e('Scorrimento fluido', 'wcag-wp'); ?></label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="container_selector"><?php esc_html_e('Selettore contenuto', 'wcag-wp'); ?></label></th>
            <td>
                <input type="text" id="container_selector" name="wcag_wp_toc_config[container_selector]" class="regular-text" value="<?php echo esc_attr($config['container_selector']); ?>">
                <p class="description"><?php esc_html_e('Selettori CSS (separati da virgola) in cui cercare i titoli, es: #main, .entry-content', 'wcag-wp'); ?></p>
            </td>
        </tr>
    </tbody></table>
</div>


