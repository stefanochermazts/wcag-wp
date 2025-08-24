<?php
// Script per aggiornare le impostazioni con il nuovo campo
require_once '../../../wp-config.php';

echo "<h2>Aggiornamento Finale Impostazioni WCAG-WP</h2>";

// Impostazioni complete con il nuovo campo
$complete_settings = [
    'design_system' => [
        'color_scheme' => 'default',
        'font_family' => 'system-ui',
        'focus_outline' => true,
        'reduce_motion' => false,
        'theme_switcher' => true,
        'default_theme' => 'light',
        'toggle_position_selector' => ''
    ],
    'accessibility' => [
        'screen_reader_support' => true,
        'keyboard_navigation' => true,
        'high_contrast' => false
    ]
];

// Aggiorna le impostazioni
$result = update_option('wcag_wp_settings', $complete_settings);

echo "<h3>Risultato:</h3>";
echo $result ? "✅ Impostazioni aggiornate con successo" : "❌ Errore nell'aggiornamento";

echo "<h3>Impostazioni finali:</h3>";
$final_settings = get_option('wcag_wp_settings', []);
echo "<pre>";
print_r($final_settings);
echo "</pre>";

echo "<br><strong>Ora puoi:</strong><br>";
echo "1. Andare in Bacheca → WCAG-WP → Impostazioni<br>";
echo "2. Impostare il 'Selettore Posizionamento Toggle' (es: '.my-header-nav')<br>";
echo "3. Testare TabPanel e Carousel con tema scuro<br>";
?>

