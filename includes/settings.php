<?php
// ===========================
// = Configurações do plugin =
// ===========================
class Settings {
    public static $options;

    public function __construct() {
        self::$options = get_option( 'exemplo_admin_options' );
        add_action( 'admin_init', array( $this, 'admin_init' ) );
    }

    public function admin_init() {
        // https://developer.wordpress.org/reference/functions/register_setting/
        register_setting( 'exemplo_settings_group', 'exemplo_admin_options', array( $this, 'validate' ) );
        
        // https://developer.wordpress.org/reference/functions/add_settings_section/
        add_settings_section(
            'exemplo_admin_option_section',
            'Conf. 01',
            null,
            'exemplo_admin_options_page_01'
        );

        // https://developer.wordpress.org/reference/functions/add_settings_field/
        add_settings_field(
            'exemplo_conf_01_field_options',
            'Opções de Configurações 01',
            array( $this, 'conf_01_fields_callback' ),
            'exemplo_admin_options_page_01',
            'exemplo_admin_option_section'
        );

        add_settings_section(
            'exemplo_admin_option_section',
            'Conf. 02',
            null,
            'exemplo_admin_options_page_02'
        );

        add_settings_field(
            'exemplo_admin_option_auth',
            'Opções de Configurações 02',
            array( $this, 'conf_02_fields_callback' ),
            'exemplo_admin_options_page_02',
            'exemplo_admin_option_section'
        );

    }

    /**
     * Configurações da Aba "Conf. 01"
     * @param string[] $args Argumentos da seção
     */
    public function conf_01_fields_callback( $args ) {
        ?>
            <fieldset>
                <p class="description">Exemplo de opção do tipo radio</p>
                <label>
                    <input 
                    type="radio" 
                    name="exemplo_admin_options[exemplo_conf_01_field_options]" 
                    value="1" 
                    <?php
                        if( isset( self::$options['exemplo_conf_01_field_options'] ) ) {
                            // "checked" Verifica se o valor armazenado é igual ou diferente 
                            checked( "1", self::$options['exemplo_conf_01_field_options'], true );
                        }
                    ?>
                    > 
                    <span>Option 01</span>
                </label><br>
                <label>
                    <input 
                    type="radio" 
                    name="exemplo_admin_options[exemplo_conf_01_field_options]" 
                    value="2"
                    <?php
                        if( isset( self::$options['exemplo_conf_01_field_options'] ) ) {
                            // Verifica se o valor armazenado é igual ou diferente 
                            checked( "2", self::$options['exemplo_conf_01_field_options'], true );
                        }
                    ?>
                    > 
                    <span>Option 02</span>
                </label><br>
            </fieldset>
        <?php
    }

    /**
     * Configurações da Aba "Conf. 02"
     * @param string[] $args Argumentos da seção
     */
    public function conf_02_fields_callback( $args ) {
        ?>
            <fieldset style="max-width: 400px">
                <label for="exemplo_admin_options[exemplo_admin_option_auth][]">Escolha quais páginas o usuário não pode acessar caso não tenha feito login</label>

                <select class="select2" name="exemplo_admin_options[exemplo_admin_option_auth][]" multiple>
                    <?php

                        $opts = self::$options['exemplo_admin_option_auth'];

                        $args = array(
                            'post_type' => 'page',
                            'post_status' => 'publish',
                            'numberposts' => 5000,
                        );

                        $posts = get_posts($args);
                        $pages = array_map(function($a) {
                            $permalink = post_permalink($a->ID);
                            return [ 
                                'post_title' => $a->post_title, 
                                'permalink' => $permalink
                            ];
                        }, $posts);

                        if( ! $opts ) {
                            $opts = ['1','2','3'];
                        }

                        if ( $pages ) {
                            foreach( $pages as $page ) {
                                $selected = ( in_array( $page['permalink'], $opts ) )
                                    ?'selected'
                                    :'';
                                printf('<option %1$s value="%2$s">%3$s</option>',
                                    $selected,
                                    $page['permalink'],
                                    $page['post_title']
                                );
                            }
                        }
                    ?>
                </select>
            </fieldset>
        <?php
    }  
    
    /**
     * Validar valores
     */
    public function validate( $input ) {
        $new_input = array();

        foreach( $input as $key => $value ) {
            $new_input[$key] = $value;
        }
        return $new_input;
    }
}

if( class_exists( 'Settings' ) ) {
    $settings = new Settings();
}