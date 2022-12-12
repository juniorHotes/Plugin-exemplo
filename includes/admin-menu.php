<?php 

class Admin_Menu {
    function __construct() {
        // https://developer.wordpress.org/reference/hooks/admin_menu/
        add_action( 'admin_menu', array( $this, 'add_menu' ) );
    }

     /**
     *  Menus da aba lateral do WP
     */
    public function add_menu() {

        // https://developer.wordpress.org/reference/functions/add_menu_page/
        add_menu_page(
            'Exemplo Plugin',
            'Exemplo Plugin',
            'manage_options',
            'exemplo_settings_admin',
            array( $this, 'exemplo_settings_page' ),
            'dashicons-building',
            5
        );

        // https://developer.wordpress.org/reference/functions/add_submenu_page/
        add_submenu_page(
            'exemplo_settings_admin',
            'Postagens',
            'Postagens',
            'manage_options',
            'edit.php?post_type=exempo_postagem',
            null,
            null
        );
    }

    /**
     * Página de Configurações
     */
    public function exemplo_settings_page() {
        // Se o usuário não tiver permissão 
        // https://developer.wordpress.org/reference/functions/current_user_can/
        if( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Peronalizar a mensagem após o savamento da configurações
        if( isset( $_GET['settings-updated'] ) ) {
            // https://developer.wordpress.org/reference/functions/add_settings_error/
            add_settings_error( 'exemplo_manage_options', 'exemplo_manage_message', 'Alterações salvas com sucesso!', 'success' );
        }
        // https://developer.wordpress.org/reference/functions/settings_errors/
        settings_errors( 'exemplo_manage_options' );

        require( EXEMPLO_PLUGIN_PATH . 'includes/views/settings-page.php' );
    }
}

if( class_exists( 'Admin_Menu' ) ) {
    $admin_menu = new Admin_Menu();
}