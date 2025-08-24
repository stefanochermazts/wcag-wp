<?php
/**
 * Meta Box Preview Radio Group
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
    'options' => array(),
    'default_value' => '',
    'required' => false,
    'disabled' => false,
    'orientation' => 'vertical',
    'size' => 'medium',
    'show_labels' => true,
    'aria_live' => 'polite'
);

$config = wp_parse_args( $config, $defaults );
?>

<div class="wcag-wp-meta-box">
    <h4><?php _e( 'Anteprima Radio Group', 'wcag-wp' ); ?></h4>
    
    <div class="wcag-wp-preview-container">
        <?php if ( empty( $config['options'] ) || ! is_array( $config['options'] ) ) : ?>
            <div class="wcag-wp-preview-placeholder">
                <p><?php _e( 'Nessuna opzione configurata. Aggiungi delle opzioni nella sezione "Configurazione" per vedere l\'anteprima.', 'wcag-wp' ); ?></p>
            </div>
        <?php else : ?>
            <div class="wcag-wp-preview-frame">
                <?php
                // Simula il rendering del frontend
                $preview_config = $config;
                
                // Genera ID unico per il preview
                $group_id = 'wcag-radio-group-preview-' . uniqid();
                $name = 'wcag-radio-preview-' . uniqid();
                
                // Classe CSS per orientamento
                $orientation_class = 'wcag-radio-group--' . $preview_config['orientation'];
                $size_class = 'wcag-radio-group--' . $preview_config['size'];
                
                // Attributi ARIA
                $aria_required = $preview_config['required'] ? 'true' : 'false';
                $aria_disabled = $preview_config['disabled'] ? 'true' : 'false';
                ?>
                
                <div class="wcag-wp-radio-group <?php echo esc_attr( $orientation_class . ' ' . $size_class ); ?>" 
                     id="<?php echo esc_attr( $group_id ); ?>"
                     role="radiogroup"
                     aria-labelledby="<?php echo esc_attr( $group_id ); ?>-title"
                     aria-describedby="<?php echo esc_attr( $group_id ); ?>-description"
                     aria-required="<?php echo esc_attr( $aria_required ); ?>"
                     aria-disabled="<?php echo esc_attr( $aria_disabled ); ?>"
                     data-wcag-radio-group>

                    <?php if ( ! empty( $preview_config['title'] ) ) : ?>
                        <h3 class="wcag-wp-radio-group__title" 
                            id="<?php echo esc_attr( $group_id ); ?>-title">
                            <?php echo esc_html( $preview_config['title'] ); ?>
                            <?php if ( $preview_config['required'] ) : ?>
                                <span class="wcag-wp-required" aria-label="Campo obbligatorio">*</span>
                            <?php endif; ?>
                        </h3>
                    <?php endif; ?>

                    <?php if ( ! empty( $preview_config['description'] ) ) : ?>
                        <p class="wcag-wp-radio-group__description" 
                           id="<?php echo esc_attr( $group_id ); ?>-description">
                            <?php echo esc_html( $preview_config['description'] ); ?>
                        </p>
                    <?php endif; ?>

                    <div class="wcag-wp-radio-group__options">
                        <?php 
                        foreach ( $preview_config['options'] as $index => $option ) :
                            $option = wp_parse_args( $option, array(
                                'value' => '',
                                'label' => '',
                                'description' => ''
                            ) );
                            
                            if ( empty( $option['value'] ) || empty( $option['label'] ) ) {
                                continue;
                            }
                            
                            $option_id = $group_id . '-option-' . $index;
                            
                            // Verifica se è l'opzione di default
                            $is_checked = ( $option['value'] === $preview_config['default_value'] );
                            $checked_attr = $is_checked ? 'checked' : '';
                            $aria_checked = $is_checked ? 'true' : 'false';
                            
                            // Attributi per stato disabilitato
                            $disabled_attr = $preview_config['disabled'] ? 'disabled' : '';
                            $aria_disabled_option = $preview_config['disabled'] ? 'true' : 'false';
                        ?>
                            <div class="wcag-wp-radio-option">
                                <input type="radio" 
                                       id="<?php echo esc_attr( $option_id ); ?>"
                                       name="<?php echo esc_attr( $name ); ?>"
                                       value="<?php echo esc_attr( $option['value'] ); ?>"
                                       class="wcag-wp-radio-option__input"
                                       <?php echo $checked_attr; ?>
                                       <?php echo $disabled_attr; ?>
                                       aria-checked="<?php echo esc_attr( $aria_checked ); ?>"
                                       aria-disabled="<?php echo esc_attr( $aria_disabled_option ); ?>"
                                       <?php if ( ! empty( $option['description'] ) ) : ?>
                                           aria-describedby="<?php echo esc_attr( $option_id ); ?>-description"
                                       <?php endif; ?>>
                                
                                <label for="<?php echo esc_attr( $option_id ); ?>" 
                                       class="wcag-wp-radio-option__label">
                                    <span class="wcag-wp-radio-option__custom"></span>
                                    <span class="wcag-wp-radio-option__text">
                                        <?php echo esc_html( $option['label'] ); ?>
                                    </span>
                                </label>
                                
                                <?php if ( ! empty( $option['description'] ) ) : ?>
                                    <div class="wcag-wp-radio-option__description" 
                                         id="<?php echo esc_attr( $option_id ); ?>-description">
                                        <?php echo esc_html( $option['description'] ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Live region per annunci screen reader -->
                    <div class="sr-only" 
                         aria-live="<?php echo esc_attr( $preview_config['aria_live'] ); ?>" 
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
            </div>
            
            <div class="wcag-wp-preview-info">
                <h5><?php _e( 'Informazioni Configurazione', 'wcag-wp' ); ?></h5>
                <ul>
                    <li><strong><?php _e( 'Orientamento:', 'wcag-wp' ); ?></strong> 
                        <?php echo esc_html( ucfirst( $preview_config['orientation'] ) ); ?></li>
                    <li><strong><?php _e( 'Dimensione:', 'wcag-wp' ); ?></strong> 
                        <?php echo esc_html( ucfirst( $preview_config['size'] ) ); ?></li>
                    <li><strong><?php _e( 'Opzioni:', 'wcag-wp' ); ?></strong> 
                        <?php echo count( $preview_config['options'] ); ?></li>
                    <li><strong><?php _e( 'Obbligatorio:', 'wcag-wp' ); ?></strong> 
                        <?php echo $preview_config['required'] ? __( 'Sì', 'wcag-wp' ) : __( 'No', 'wcag-wp' ); ?></li>
                    <li><strong><?php _e( 'Disabilitato:', 'wcag-wp' ); ?></strong> 
                        <?php echo $preview_config['disabled'] ? __( 'Sì', 'wcag-wp' ) : __( 'No', 'wcag-wp' ); ?></li>
                    <?php if ( ! empty( $preview_config['default_value'] ) ) : ?>
                        <li><strong><?php _e( 'Valore di default:', 'wcag-wp' ); ?></strong> 
                            <?php echo esc_html( $preview_config['default_value'] ); ?></li>
                    <?php endif; ?>
                </ul>
                
                <h5><?php _e( 'Shortcode', 'wcag-wp' ); ?></h5>
                <div class="wcag-wp-shortcode-display">
                    <code>[wcag_radio_group id="<?php echo esc_attr( $post->ID ); ?>"]</code>
                    <button type="button" 
                            class="wcag-wp-button wcag-wp-button--small wcag-wp-copy-shortcode"
                            data-shortcode='[wcag_radio_group id="<?php echo esc_attr( $post->ID ); ?>"]'
                            aria-label="<?php esc_attr_e( 'Copia shortcode negli appunti', 'wcag-wp' ); ?>">
                        <?php _e( 'Copia', 'wcag-wp' ); ?>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="wcag-wp-preview-actions">
        <button type="button" 
                class="wcag-wp-button wcag-wp-button--secondary wcag-wp-refresh-preview"
                aria-label="<?php esc_attr_e( 'Aggiorna anteprima', 'wcag-wp' ); ?>">
            <?php _e( 'Aggiorna Anteprima', 'wcag-wp' ); ?>
        </button>
        
        <p class="wcag-wp-field-description">
            <?php _e( 'L\'anteprima mostra come apparirà il radio group sul frontend. Salva le modifiche per aggiornare l\'anteprima.', 'wcag-wp' ); ?>
        </p>
    </div>
</div>
