<?php
/**
 * Plugin Name: Exemplo base de Plugin WP
 * Plugin URI: https://www.workana.com/freelancer/7c0bfafb6aa251a230a2f96ccb389418
 * Description: Este plugin é uma abase de estrutura e funcionamento de um plugin Wordpress
 * Author URI: https://www.workana.com/freelancer/7c0bfafb6aa251a230a2f96ccb389418
 * Version: 1.0.0
 * Requires at least: 5.9
 * Requires PHP: 7.2
 * Author: Junior Hotes
 * Tested up to: 1.0
 * WC requires at least: 6.4
 * WC tested up to: 1.4
 * Text Domain: exemplo
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

class Exemplo {

    function __construct() {
        $this->define_constants();
        $this->instances();

        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );
    }

    public function instances() {
        require_once( EXEMPLO_PLUGIN_PATH . 'includes/admin-menu.php' );
        require_once( EXEMPLO_PLUGIN_PATH . 'includes/settings.php' );
        require_once( EXEMPLO_PLUGIN_PATH . 'includes/postagens-cpt.php' );
    }

    public function define_constants() {
        define( 'EXEMPLO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
        define( 'EXEMPLO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        define( 'EXEMPLO_PLUGIN_VERSION', '1.0.0' );
        define( 'EXEMPLO_MIN_PHP_VER', '5.6.0' );
        define( 'EXEMPLO_MIN_WC_VER', '3.0' );
    }

    /**
     * Ao ativar plugin
     */
    public static function activate() {
        update_option( 'rewrite_rules', '' );
    }

    /**
     * Ao desativar plugin
     */
    public static function deactivate() {
        /**
         * Suas ações aqio
         */
    }

    /**
     * Ao desinstalar plugin
     */
    public static function uninstall() {
        /**
         * Suas ações aqui
         */
    }

    /**
     * Pendurar scripts apenas no backend (Na página de administração do WP)
     */
    public function register_admin_scripts() {

        wp_register_script( 
            'exemplo_select2_js', 
            EXEMPLO_PLUGIN_URL . 'resources/select2/select2.min.js', 
            array('jquery'), 
            EXEMPLO_PLUGIN_VERSION, 
            true 
        );

        wp_register_script( 
            'exemplo_script_admin_js', 
            EXEMPLO_PLUGIN_URL . 'assets/js/admin/script-admin.js', 
            array('jquery'), 
            EXEMPLO_PLUGIN_VERSION, 
            true 
        );

        wp_enqueue_script('exemplo_select2_js');
        wp_enqueue_script('exemplo_script_admin_js');
    }

    /**
     * Pendurar estilos apenas no backend (Na página de administração do WP)
     */
    public function register_admin_styles() {

        wp_register_style(
            'exemplo_select2_css', 
            EXEMPLO_PLUGIN_URL . 'resources/select2/select2.min.css'
        );

        wp_enqueue_style('exemplo_select2_css');
    }
}

if( class_exists( 'Exemplo' ) ) {
    register_activation_hook( __FILE__, array( 'Exemplo', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'Exemplo', 'deactivate' ) );
    register_uninstall_hook( __FILE__, array( 'Exemplo', 'uninstall' ) );

    $exemplo = new Exemplo();
}