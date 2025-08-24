<?php
// Debug script per verificare le impostazioni WCAG-WP
// Accedi a: /wp-content/plugins/wcag-wp/debug-settings.php

// Carica WordPress
require_once '../../../wp-config.php';

echo "<h2>Debug Impostazioni WCAG-WP</h2>";

// Ottieni le impostazioni
$settings = get_option('wcag_wp_settings', []);

echo "<h3>Impostazioni attuali:</h3>";
echo "<pre>";
print_r($settings);
echo "</pre>";

// Verifica se il plugin è attivo
$active_plugins = get_option('active_plugins', []);
$wcag_active = in_array('wcag-wp/wcag-wp.php', $active_plugins);

echo "<h3>Plugin attivo:</h3>";
echo $wcag_active ? "✅ Sì" : "❌ No";

// Verifica se la classe principale esiste
echo "<h3>Classe principale:</h3>";
echo class_exists('WCAG_WP') ? "✅ WCAG_WP esiste" : "❌ WCAG_WP non trovata";

// Verifica se l'istanza è disponibile
if (function_exists('wcag_wp')) {
    $instance = wcag_wp();
    echo "<br>✅ Istanza disponibile";
    if ($instance) {
        $plugin_settings = $instance->get_settings();
        echo "<h3>Impostazioni dal plugin:</h3>";
        echo "<pre>";
        print_r($plugin_settings);
        echo "</pre>";
    }
} else {
    echo "<br>❌ Funzione wcag_wp() non disponibile";
}

// Verifica hook registrati
echo "<h3>Hook wp_enqueue_scripts:</h3>";
global $wp_filter;
if (isset($wp_filter['wp_enqueue_scripts'])) {
    foreach ($wp_filter['wp_enqueue_scripts']->callbacks as $priority => $callbacks) {
        foreach ($callbacks as $callback) {
            if (is_array($callback['function']) && 
                is_object($callback['function'][0]) && 
                get_class($callback['function'][0]) === 'WCAG_WP') {
                echo "✅ Hook enqueue_frontend_assets registrato (priorità: $priority)<br>";
            }
        }
    }
}

echo "<h3>Script e stili in coda:</h3>";
global $wp_scripts, $wp_styles;
if ($wp_scripts && in_array('wcag-wp-frontend', $wp_scripts->queue)) {
    echo "✅ Script wcag-wp-frontend in coda<br>";
} else {
    echo "❌ Script wcag-wp-frontend NON in coda<br>";
}

if ($wp_styles && in_array('wcag-wp-frontend', $wp_styles->queue)) {
    echo "✅ Style wcag-wp-frontend in coda<br>";
} else {
    echo "❌ Style wcag-wp-frontend NON in coda<br>";
}
?>

