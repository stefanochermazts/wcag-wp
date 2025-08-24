<?php
/**
 * Template frontend per Radio Group WCAG
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevenire accesso diretto
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Verifica che la configurazione sia presente
if ( empty( $config ) ) {
    echo '<p class="wcag-wp-error">Configurazione radio group non trovata</p>';
    return;
}

// Valori di default
$defaults = array(
    'title' => '',
    'description' => '',
    'options' => array(),
    'default_value' => '',
    'required' => false,
    'disabled' => false,
    'orientation' => 'vertical', // vertical, horizontal
    'size' => 'medium', // small, medium, large
    'show_labels' => true,
    'aria_live' => 'polite'
);

$config = wp_parse_args( $config, $defaults );

// Genera ID unico per il gruppo
$group_id = 'wcag-radio-group-' . uniqid();
$name = 'wcag-radio-' . uniqid();

// Classe CSS per orientamento
$orientation_class = 'wcag-radio-group--' . $config['orientation'];
$size_class = 'wcag-radio-group--' . $config['size'];

// Attributi ARIA
$aria_required = $config['required'] ? 'true' : 'false';
$aria_disabled = $config['disabled'] ? 'true' : 'false';
?>

<div class="wcag-wp-radio-group <?php echo esc_attr( $orientation_class . ' ' . $size_class ); ?>" 
     id="<?php echo esc_attr( $group_id ); ?>"
     role="radiogroup"
     aria-labelledby="<?php echo esc_attr( $group_id ); ?>-title"
     aria-describedby="<?php echo esc_attr( $group_id ); ?>-description"
     aria-required="<?php echo esc_attr( $aria_required ); ?>"
     aria-disabled="<?php echo esc_attr( $aria_disabled ); ?>"
     data-wcag-radio-group>

    <?php if ( ! empty( $config['title'] ) ) : ?>
        <h3 class="wcag-wp-radio-group__title" 
            id="<?php echo esc_attr( $group_id ); ?>-title">
            <?php echo esc_html( $config['title'] ); ?>
            <?php if ( $config['required'] ) : ?>
                <span class="wcag-wp-required" aria-label="Campo obbligatorio">*</span>
            <?php endif; ?>
        </h3>
    <?php endif; ?>

    <?php if ( ! empty( $config['description'] ) ) : ?>
        <p class="wcag-wp-radio-group__description" 
           id="<?php echo esc_attr( $group_id ); ?>-description">
            <?php echo esc_html( $config['description'] ); ?>
        </p>
    <?php endif; ?>

    <div class="wcag-wp-radio-group__options">
        <?php 
        if ( ! empty( $config['options'] ) && is_array( $config['options'] ) ) :
            foreach ( $config['options'] as $index => $option ) :
                $option_id = $group_id . '-option-' . $index;
                $option_value = isset( $option['value'] ) ? $option['value'] : '';
                $option_label = isset( $option['label'] ) ? $option['label'] : '';
                $option_description = isset( $option['description'] ) ? $option['description'] : '';
                
                // Verifica se è l'opzione di default
                $is_checked = ( $option_value === $config['default_value'] );
                $checked_attr = $is_checked ? 'checked' : '';
                $aria_checked = $is_checked ? 'true' : 'false';
                
                // Attributi per stato disabilitato
                $disabled_attr = $config['disabled'] ? 'disabled' : '';
                $aria_disabled_option = $config['disabled'] ? 'true' : 'false';
        ?>
            <div class="wcag-wp-radio-option">
                <input type="radio" 
                       id="<?php echo esc_attr( $option_id ); ?>"
                       name="<?php echo esc_attr( $name ); ?>"
                       value="<?php echo esc_attr( $option_value ); ?>"
                       class="wcag-wp-radio-option__input"
                       <?php echo $checked_attr; ?>
                       <?php echo $disabled_attr; ?>
                       aria-checked="<?php echo esc_attr( $aria_checked ); ?>"
                       aria-disabled="<?php echo esc_attr( $aria_disabled_option ); ?>"
                       <?php if ( ! empty( $option_description ) ) : ?>
                           aria-describedby="<?php echo esc_attr( $option_id ); ?>-description"
                       <?php endif; ?>>
                
                <label for="<?php echo esc_attr( $option_id ); ?>" 
                       class="wcag-wp-radio-option__label">
                    <span class="wcag-wp-radio-option__custom"></span>
                    <span class="wcag-wp-radio-option__text">
                        <?php echo esc_html( $option_label ); ?>
                    </span>
                </label>
                
                <?php if ( ! empty( $option_description ) ) : ?>
                    <div class="wcag-wp-radio-option__description" 
                         id="<?php echo esc_attr( $option_id ); ?>-description">
                        <?php echo esc_html( $option_description ); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php 
            endforeach;
        else :
        ?>
            <p class="wcag-wp-radio-group__no-options">
                Nessuna opzione disponibile per questo radio group.
            </p>
        <?php endif; ?>
    </div>

    <!-- Live region per annunci screen reader -->
    <div class="sr-only" 
         aria-live="<?php echo esc_attr( $config['aria_live'] ); ?>" 
         aria-atomic="true"
         id="<?php echo esc_attr( $group_id ); ?>-live">
    </div>

    <!-- Messaggio di errore (nascosto di default) -->
    <div class="wcag-wp-radio-group__error" 
         id="<?php echo esc_attr( $group_id ); ?>-error" 
         role="alert" 
         aria-hidden="true">
        <span class="wcag-wp-radio-group__error-text"></span>
    </div>
</div>
