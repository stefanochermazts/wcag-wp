<?php
/**
 * Test WCAG Breadcrumb Component
 * 
 * File di test per verificare il funzionamento del componente breadcrumb
 * Accessibile via: /wp-content/plugins/wcag-wp/test-breadcrumb.php
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Carica WordPress
require_once('../../../wp-load.php');

// Verifica che l'utente sia loggato e abbia i permessi
if (!current_user_can('manage_options')) {
    wp_die('Accesso negato');
}

// Verifica che il componente sia caricato
if (!class_exists('WCAG_WP\\Components\\WCAG_WP_Breadcrumb')) {
    wp_die('Componente WCAG Breadcrumb non trovato');
}

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test WCAG Breadcrumb Component</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .test-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .test-title {
            color: #2563eb;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .breadcrumb-demo {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        .code-example {
            background: #1f2937;
            color: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 14px;
            overflow-x: auto;
        }
        .success {
            color: #10b981;
            font-weight: bold;
        }
        .error {
            color: #ef4444;
            font-weight: bold;
        }
        .info {
            color: #3b82f6;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>🧪 Test WCAG Breadcrumb Component</h1>
    
    <div class="test-section">
        <h2 class="test-title">✅ Verifica Componente</h2>
        <?php
        try {
            $breadcrumb = \WCAG_WP\Components\WCAG_WP_Breadcrumb::get_instance();
            echo '<p class="success">✓ Componente WCAG Breadcrumb caricato correttamente</p>';
            echo '<p class="info">Classe: ' . get_class($breadcrumb) . '</p>';
        } catch (Exception $e) {
            echo '<p class="error">✗ Errore nel caricamento del componente: ' . $e->getMessage() . '</p>';
        }
        ?>
    </div>

    <div class="test-section">
        <h2 class="test-title">🎯 Test Shortcode Base</h2>
        <div class="breadcrumb-demo">
            <h3>Shortcode: [wcag-breadcrumb]</h3>
            <?php
            $shortcode_output = do_shortcode('[wcag-breadcrumb]');
            if (!empty($shortcode_output)) {
                echo $shortcode_output;
            } else {
                echo '<p class="info">Nessun breadcrumb generato (probabilmente non siamo in una pagina con contenuto)</p>';
            }
            ?>
        </div>
    </div>

    <div class="test-section">
        <h2 class="test-title">🔧 Test Configurazione Personalizzata</h2>
        <div class="breadcrumb-demo">
            <h3>Shortcode con parametri custom:</h3>
            <div class="code-example">
[wcag-breadcrumb home_text="Inizio" separator=" > " show_current="true" show_home="true"]
            </div>
            <?php
            $custom_output = do_shortcode('[wcag-breadcrumb home_text="Inizio" separator=" > " show_current="true" show_home="true"]');
            if (!empty($custom_output)) {
                echo $custom_output;
            } else {
                echo '<p class="info">Nessun breadcrumb generato con configurazione custom</p>';
            }
            ?>
        </div>
    </div>

    <div class="test-section">
        <h2 class="test-title">📱 Test Responsive</h2>
        <div class="breadcrumb-demo">
            <h3>Breadcrumb con classe responsive:</h3>
            <div class="code-example">
[wcag-breadcrumb css_class="wcag-wp-breadcrumb--compact"]
            </div>
            <?php
            $responsive_output = do_shortcode('[wcag-breadcrumb css_class="wcag-wp-breadcrumb--compact"]');
            if (!empty($responsive_output)) {
                echo $responsive_output;
            } else {
                echo '<p class="info">Nessun breadcrumb generato per test responsive</p>';
            }
            ?>
        </div>
    </div>

    <div class="test-section">
        <h2 class="test-title">🎨 Test Design System</h2>
        <div class="breadcrumb-demo">
            <h3>Breadcrumb con design system:</h3>
            <?php
            $design_output = do_shortcode('[wcag-breadcrumb css_class="wcag-wp-breadcrumb--large"]');
            if (!empty($design_output)) {
                echo $design_output;
            } else {
                echo '<p class="info">Nessun breadcrumb generato per test design system</p>';
            }
            ?>
        </div>
    </div>

    <div class="test-section">
        <h2 class="test-title">🔍 Verifica CSS e JavaScript</h2>
        <?php
        // Verifica che i file CSS e JS siano caricati
        global $wp_styles, $wp_scripts;
        
        echo '<h3>CSS Caricati:</h3>';
        $css_found = false;
        foreach ($wp_styles->queue as $handle) {
            if (strpos($handle, 'breadcrumb') !== false) {
                echo '<p class="success">✓ CSS caricato: ' . $handle . '</p>';
                $css_found = true;
            }
        }
        if (!$css_found) {
            echo '<p class="info">Nessun CSS breadcrumb trovato nei file caricati</p>';
        }
        
        echo '<h3>JavaScript Caricati:</h3>';
        $js_found = false;
        foreach ($wp_scripts->queue as $handle) {
            if (strpos($handle, 'breadcrumb') !== false) {
                echo '<p class="success">✓ JavaScript caricato: ' . $handle . '</p>';
                $js_found = true;
            }
        }
        if (!$js_found) {
            echo '<p class="info">Nessun JavaScript breadcrumb trovato nei file caricati</p>';
        }
        ?>
    </div>

    <div class="test-section">
        <h2 class="test-title">📊 Informazioni Sistema</h2>
        <ul>
            <li><strong>WordPress Version:</strong> <?php echo get_bloginfo('version'); ?></li>
            <li><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></li>
            <li><strong>Plugin Version:</strong> <?php echo defined('WCAG_WP_VERSION') ? WCAG_WP_VERSION : 'Non definita'; ?></li>
            <li><strong>Current Page:</strong> <?php echo is_home() ? 'Home' : (is_single() ? 'Single Post' : (is_page() ? 'Page' : 'Other')); ?></li>
            <li><strong>Post Type:</strong> <?php echo get_post_type(); ?></li>
        </ul>
    </div>

    <div class="test-section">
        <h2 class="test-title">🎯 Prossimi Test</h2>
        <p>Per testare completamente il componente breadcrumb:</p>
        <ol>
            <li>Creare un breadcrumb dall'admin WordPress</li>
            <li>Testare su diverse pagine (post, categorie, pagine)</li>
            <li>Verificare navigazione tastiera</li>
            <li>Testare con screen reader</li>
            <li>Verificare responsive design su mobile</li>
        </ol>
    </div>

    <div class="test-section">
        <h2 class="test-title">🔗 Link Utili</h2>
        <ul>
            <li><a href="<?php echo admin_url('edit.php?post_type=wcag_breadcrumb'); ?>">Admin Breadcrumb</a></li>
            <li><a href="<?php echo home_url(); ?>">Home Page</a></li>
            <li><a href="<?php echo admin_url(); ?>">Admin Dashboard</a></li>
        </ul>
    </div>

</body>
</html>
