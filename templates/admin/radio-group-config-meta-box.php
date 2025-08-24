<?php
/**
 * Meta Box Configurazione Radio Group
 * 
 * @package WCAG_WP
 * @since 1.0.0
 */

// Prevenire accesso diretto
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Ottieni la configurazione corrente
$config = get_post_meta( $post->ID, '_wcag_radio_group_config', true );
if ( is_string( $config ) ) {
    $config = maybe_unserialize( $config );
}

// Valori di default
$defaults = array(
    'title' => '',
    'description' => '',
    'options' => array(
        array('value' => 'option1', 'label' => 'Opzione 1', 'description' => ''),
        array('value' => 'option2', 'label' => 'Opzione 2', 'description' => ''),
        array('value' => 'option3', 'label' => 'Opzione 3', 'description' => '')
    ),
    'default_value' => '',
    'required' => false,
    'disabled' => false,
    'orientation' => 'vertical',
    'size' => 'medium',
    'show_labels' => true,
    'aria_live' => 'polite'
);

$config = wp_parse_args( $config, $defaults );

// Nonce per sicurezza
wp_nonce_field( 'wcag_radio_group_config', 'wcag_radio_group_config_nonce' );
?>

<div class="wcag-wp-meta-box">
    
    <!-- Titolo -->
    <div class="wcag-wp-field">
        <label for="radio_group_title" class="wcag-wp-label">
            <?php _e( 'Titolo del Radio Group', 'wcag-wp' ); ?>
        </label>
        <input type="text" 
               id="radio_group_title" 
               name="wcag_radio_group_config[title]" 
               value="<?php echo esc_attr( $config['title'] ); ?>"
               class="wcag-wp-input"
               placeholder="<?php esc_attr_e( 'Es: Seleziona la tua preferenza', 'wcag-wp' ); ?>">
        <p class="wcag-wp-field-description">
            <?php _e( 'Titolo principale del gruppo di radio button (opzionale)', 'wcag-wp' ); ?>
        </p>
    </div>

    <!-- Descrizione -->
    <div class="wcag-wp-field">
        <label for="radio_group_description" class="wcag-wp-label">
            <?php _e( 'Descrizione', 'wcag-wp' ); ?>
        </label>
        <textarea id="radio_group_description" 
                  name="wcag_radio_group_config[description]" 
                  class="wcag-wp-textarea"
                  rows="3"
                  placeholder="<?php esc_attr_e( 'Descrizione del radio group...', 'wcag-wp' ); ?>"><?php echo esc_textarea( $config['description'] ); ?></textarea>
        <p class="wcag-wp-field-description">
            <?php _e( 'Descrizione che aiuta l\'utente a comprendere le opzioni', 'wcag-wp' ); ?>
        </p>
    </div>

    <!-- Orientamento -->
    <div class="wcag-wp-field">
        <label for="radio_group_orientation" class="wcag-wp-label">
            <?php _e( 'Orientamento', 'wcag-wp' ); ?>
        </label>
        <select id="radio_group_orientation" 
                name="wcag_radio_group_config[orientation]" 
                class="wcag-wp-select">
            <option value="vertical" <?php selected( $config['orientation'], 'vertical' ); ?>>
                <?php _e( 'Verticale', 'wcag-wp' ); ?>
            </option>
            <option value="horizontal" <?php selected( $config['orientation'], 'horizontal' ); ?>>
                <?php _e( 'Orizzontale', 'wcag-wp' ); ?>
            </option>
        </select>
        <p class="wcag-wp-field-description">
            <?php _e( 'Come disporre le opzioni del radio group', 'wcag-wp' ); ?>
        </p>
    </div>

    <!-- Dimensione -->
    <div class="wcag-wp-field">
        <label for="radio_group_size" class="wcag-wp-label">
            <?php _e( 'Dimensione', 'wcag-wp' ); ?>
        </label>
        <select id="radio_group_size" 
                name="wcag_radio_group_config[size]" 
                class="wcag-wp-select">
            <option value="small" <?php selected( $config['size'], 'small' ); ?>>
                <?php _e( 'Piccola', 'wcag-wp' ); ?>
            </option>
            <option value="medium" <?php selected( $config['size'], 'medium' ); ?>>
                <?php _e( 'Media', 'wcag-wp' ); ?>
            </option>
            <option value="large" <?php selected( $config['size'], 'large' ); ?>>
                <?php _e( 'Grande', 'wcag-wp' ); ?>
            </option>
        </select>
        <p class="wcag-wp-field-description">
            <?php _e( 'Dimensione dei radio button e del testo', 'wcag-wp' ); ?>
        </p>
    </div>

    <!-- Opzioni -->
    <div class="wcag-wp-field">
        <label class="wcag-wp-label">
            <?php _e( 'Opzioni del Radio Group', 'wcag-wp' ); ?>
        </label>
        
        <div id="radio_group_options" class="wcag-wp-options-container">
            <?php 
            if ( ! empty( $config['options'] ) && is_array( $config['options'] ) ) :
                foreach ( $config['options'] as $index => $option ) :
                    $option = wp_parse_args( $option, array(
                        'value' => '',
                        'label' => '',
                        'description' => ''
                    ) );
            ?>
                <div class="wcag-wp-option-row" data-index="<?php echo esc_attr( $index ); ?>">
                    <div class="wcag-wp-option-fields">
                        <input type="text" 
                               name="wcag_radio_group_config[options][<?php echo esc_attr( $index ); ?>][value]" 
                               value="<?php echo esc_attr( $option['value'] ); ?>"
                               class="wcag-wp-input wcag-wp-option-value"
                               placeholder="<?php esc_attr_e( 'Valore', 'wcag-wp' ); ?>"
                               required>
                        
                        <input type="text" 
                               name="wcag_radio_group_config[options][<?php echo esc_attr( $index ); ?>][label]" 
                               value="<?php echo esc_attr( $option['label'] ); ?>"
                               class="wcag-wp-input wcag-wp-option-label"
                               placeholder="<?php esc_attr_e( 'Etichetta', 'wcag-wp' ); ?>"
                               required>
                        
                        <input type="text" 
                               name="wcag_radio_group_config[options][<?php echo esc_attr( $index ); ?>][description]" 
                               value="<?php echo esc_attr( $option['description'] ); ?>"
                               class="wcag-wp-input wcag-wp-option-description"
                               placeholder="<?php esc_attr_e( 'Descrizione (opzionale)', 'wcag-wp' ); ?>">
                        
                        <button type="button" 
                                class="wcag-wp-button wcag-wp-button--danger wcag-wp-remove-option"
                                aria-label="<?php esc_attr_e( 'Rimuovi opzione', 'wcag-wp' ); ?>">
                            <?php _e( 'Rimuovi', 'wcag-wp' ); ?>
                        </button>
                    </div>
                </div>
            <?php 
                endforeach;
            endif;
            ?>
        </div>
        
        <button type="button" 
                id="add_radio_option" 
                class="wcag-wp-button wcag-wp-button--secondary">
            <?php _e( 'Aggiungi Opzione', 'wcag-wp' ); ?>
        </button>
        
        <p class="wcag-wp-field-description">
            <?php _e( 'Aggiungi le opzioni disponibili per il radio group. Ogni opzione deve avere un valore unico e un\'etichetta.', 'wcag-wp' ); ?>
        </p>
    </div>

    <!-- Valore di Default -->
    <div class="wcag-wp-field">
        <label for="radio_group_default_value" class="wcag-wp-label">
            <?php _e( 'Valore di Default', 'wcag-wp' ); ?>
        </label>
        <select id="radio_group_default_value" 
                name="wcag_radio_group_config[default_value]" 
                class="wcag-wp-select">
            <option value=""><?php _e( 'Nessuna selezione', 'wcag-wp' ); ?></option>
            <?php 
            if ( ! empty( $config['options'] ) && is_array( $config['options'] ) ) :
                foreach ( $config['options'] as $index => $option ) :
                    $option = wp_parse_args( $option, array( 'value' => '', 'label' => '' ) );
                    if ( ! empty( $option['value'] ) ) :
            ?>
                <option value="<?php echo esc_attr( $option['value'] ); ?>" 
                        <?php selected( $config['default_value'], $option['value'] ); ?>>
                    <?php echo esc_html( $option['label'] ?: $option['value'] ); ?>
                </option>
            <?php 
                    endif;
                endforeach;
            endif;
            ?>
        </select>
        <p class="wcag-wp-field-description">
            <?php _e( 'Opzione pre-selezionata (opzionale)', 'wcag-wp' ); ?>
        </p>
    </div>

    <!-- Impostazioni Avanzate -->
    <div class="wcag-wp-field-group">
        <h4><?php _e( 'Impostazioni Avanzate', 'wcag-wp' ); ?></h4>
        
        <!-- Obbligatorio -->
        <div class="wcag-wp-field wcag-wp-field--checkbox">
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" 
                       name="wcag_radio_group_config[required]" 
                       value="1" 
                       <?php checked( $config['required'], true ); ?>>
                <span class="wcag-wp-checkbox-text">
                    <?php _e( 'Campo obbligatorio', 'wcag-wp' ); ?>
                </span>
            </label>
            <p class="wcag-wp-field-description">
                <?php _e( 'L\'utente deve selezionare un\'opzione per continuare', 'wcag-wp' ); ?>
            </p>
        </div>

        <!-- Disabilitato -->
        <div class="wcag-wp-field wcag-wp-field--checkbox">
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" 
                       name="wcag_radio_group_config[disabled]" 
                       value="1" 
                       <?php checked( $config['disabled'], true ); ?>>
                <span class="wcag-wp-checkbox-text">
                    <?php _e( 'Disabilitato', 'wcag-wp' ); ?>
                </span>
            </label>
            <p class="wcag-wp-field-description">
                <?php _e( 'Il radio group non sarà interattivo', 'wcag-wp' ); ?>
            </p>
        </div>

        <!-- Mostra Etichette -->
        <div class="wcag-wp-field wcag-wp-field--checkbox">
            <label class="wcag-wp-checkbox-label">
                <input type="checkbox" 
                       name="wcag_radio_group_config[show_labels]" 
                       value="1" 
                       <?php checked( $config['show_labels'], true ); ?>>
                <span class="wcag-wp-checkbox-text">
                    <?php _e( 'Mostra etichette', 'wcag-wp' ); ?>
                </span>
            </label>
            <p class="wcag-wp-field-description">
                <?php _e( 'Mostra le etichette delle opzioni', 'wcag-wp' ); ?>
            </p>
        </div>

        <!-- ARIA Live -->
        <div class="wcag-wp-field">
            <label for="radio_group_aria_live" class="wcag-wp-label">
                <?php _e( 'Priorità Annunci Screen Reader', 'wcag-wp' ); ?>
            </label>
            <select id="radio_group_aria_live" 
                    name="wcag_radio_group_config[aria_live]" 
                    class="wcag-wp-select">
                <option value="polite" <?php selected( $config['aria_live'], 'polite' ); ?>>
                    <?php _e( 'Polite - Annuncio discreto', 'wcag-wp' ); ?>
                </option>
                <option value="assertive" <?php selected( $config['aria_live'], 'assertive' ); ?>>
                    <?php _e( 'Assertive - Annuncio immediato', 'wcag-wp' ); ?>
                </option>
                <option value="off" <?php selected( $config['aria_live'], 'off' ); ?>>
                    <?php _e( 'Off - Nessun annuncio', 'wcag-wp' ); ?>
                </option>
            </select>
            <p class="wcag-wp-field-description">
                <?php _e( 'Priorità degli annunci per gli screen reader', 'wcag-wp' ); ?>
            </p>
        </div>
    </div>

</div>

<script type="text/template" id="radio-option-template">
    <div class="wcag-wp-option-row" data-index="{{index}}">
        <div class="wcag-wp-option-fields">
            <input type="text" 
                   name="wcag_radio_group_config[options][{{index}}][value]" 
                   class="wcag-wp-input wcag-wp-option-value"
                   placeholder="<?php esc_attr_e( 'Valore', 'wcag-wp' ); ?>"
                   required>
            
            <input type="text" 
                   name="wcag_radio_group_config[options][{{index}}][label]" 
                   class="wcag-wp-input wcag-wp-option-label"
                   placeholder="<?php esc_attr_e( 'Etichetta', 'wcag-wp' ); ?>"
                   required>
            
            <input type="text" 
                   name="wcag_radio_group_config[options][{{index}}][description]" 
                   class="wcag-wp-input wcag-wp-option-description"
                   placeholder="<?php esc_attr_e( 'Descrizione (opzionale)', 'wcag-wp' ); ?>">
            
            <button type="button" 
                    class="wcag-wp-button wcag-wp-button--danger wcag-wp-remove-option"
                    aria-label="<?php esc_attr_e( 'Rimuovi opzione', 'wcag-wp' ); ?>">
                <?php _e( 'Rimuovi', 'wcag-wp' ); ?>
            </button>
        </div>
    </div>
</script>
