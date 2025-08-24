<!DOCTYPE html>
<html>
<head>
    <title>Test WCAG-WP JavaScript</title>
    <?php
    // Carica WordPress
    require_once '../../../wp-config.php';
    
    // Simula wp_head per caricare gli script
    wp_head();
    ?>
</head>
<body>
    <h1>Test WCAG-WP JavaScript</h1>
    <p>Apri la console (F12) per vedere i log di debug.</p>
    
    <div>
        <h2>Componenti WCAG presenti:</h2>
        <?php echo do_shortcode('[wcag-table id="1"]'); ?>
    </div>
    
    <script>
    // Test aggiuntivo
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Test page DOM loaded');
        console.log('wcag_wp available:', typeof wcag_wp);
        console.log('wcagWpFrontend available:', typeof window.wcagWpFrontend);
        
        // Verifica se il toggle è presente (aspetta di più)
        setTimeout(() => {
            const toggle = document.querySelector('.wcag-wp-theme-toggle');
            console.log('Toggle found after 2s:', toggle);
            if (toggle) {
                console.log('Toggle styles:', window.getComputedStyle(toggle));
                console.log('Toggle position:', toggle.getBoundingClientRect());
            } else {
                console.log('All elements with wcag-wp class:', document.querySelectorAll('[class*="wcag-wp"]'));
            }
        }, 2000);
    });
    </script>
    
    <?php wp_footer(); ?>
</body>
</html>
