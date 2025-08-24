<?php
// Script per correggere le impostazioni WCAG-WP
require_once '../../../wp-config.php';

echo "<h2>Correzione Impostazioni WCAG-WP</h2>";

// Impostazioni corrette (non usare array_merge_recursive)
$correct_settings = [
    'design_system' => [
        'color_scheme' => 'default',
        'font_family' => 'system-ui',
        'focus_outline' => true,
        'reduce_motion' => false,
        'theme_switcher' => true,
        'default_theme' => 'light'
    ],
    'accessibility' => [
        'screen_reader_support' => true,
        'keyboard_navigation' => true,
        'high_contrast' => false
    ]
];

// Sovrascrivi completamente le impostazioni
$result = update_option('wcag_wp_settings', $correct_settings);

echo "<h3>Risultato correzione:</h3>";
echo $result ? "✅ Impostazioni corrette" : "❌ Errore nella correzione";

echo "<h3>Impostazioni finali:</h3>";
$final_settings = get_option('wcag_wp_settings', []);
echo "<pre>";
print_r($final_settings);
echo "</pre>";

echo "<br><strong>Ora ricarica la pagina per testare!</strong>";
?>

