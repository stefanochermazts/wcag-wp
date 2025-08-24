<?php
// Script per forzare l'aggiornamento delle impostazioni WCAG-WP
// Accedi a: /wp-content/plugins/wcag-wp/force-update-settings.php

// Carica WordPress
require_once '../../../wp-config.php';

echo "<h2>Aggiornamento Impostazioni WCAG-WP</h2>";

// Ottieni le impostazioni attuali
$current_settings = get_option('wcag_wp_settings', []);

echo "<h3>Impostazioni prima dell'aggiornamento:</h3>";
echo "<pre>";
print_r($current_settings);
echo "</pre>";

// Impostazioni di default complete
$default_settings = [
    'design_system' => [
        'color_scheme' => 'default',
        'font_family' => 'system-ui',
        'focus_outline' => true,
        'reduce_motion' => false,
        'theme_switcher' => true,
        'default_theme' => 'light'  // Imposto light come default
    ],
    'accessibility' => [
        'screen_reader_support' => true,
        'keyboard_navigation' => true,
        'high_contrast' => false
    ]
];

// Merge con le impostazioni esistenti
$updated_settings = array_merge_recursive($default_settings, $current_settings);

// Assicurati che i nuovi campi siano presenti
if (!isset($updated_settings['design_system']['theme_switcher'])) {
    $updated_settings['design_system']['theme_switcher'] = true;
}
if (!isset($updated_settings['design_system']['default_theme'])) {
    $updated_settings['design_system']['default_theme'] = 'light';
}

// Aggiorna le impostazioni
$result = update_option('wcag_wp_settings', $updated_settings);

echo "<h3>Risultato aggiornamento:</h3>";
echo $result ? "✅ Impostazioni aggiornate con successo" : "❌ Errore nell'aggiornamento";

echo "<h3>Impostazioni dopo l'aggiornamento:</h3>";
$final_settings = get_option('wcag_wp_settings', []);
echo "<pre>";
print_r($final_settings);
echo "</pre>";

echo "<h3>Verifica theme switcher:</h3>";
$switcher_enabled = $final_settings['design_system']['theme_switcher'] ?? false;
$default_theme = $final_settings['design_system']['default_theme'] ?? 'auto';

echo "Theme Switcher: " . ($switcher_enabled ? "✅ Attivato" : "❌ Disattivato") . "<br>";
echo "Tema Default: " . $default_theme . "<br>";

echo "<br><strong>Ora ricarica la pagina di test per vedere il toggle!</strong>";
?>

