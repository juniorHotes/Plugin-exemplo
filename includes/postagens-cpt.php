<?php
/**
 * Criar a postagem personalizada para listar e mostrar os dados dos postagem.
 * Att: Assim como é feito na tela de posts (Todos os posts)
 * CPT (Menu)
 */

class Postagens_cpt {

    function __construct() {
        // https://developer.wordpress.org/?s=init&post_type%5B%5D=wp-parser-hook
        add_action( 'init', array( $this, 'create_post_type' ), 0 );
        // https://developer.wordpress.org/reference/hooks/add_meta_boxes/
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        // https://developer.wordpress.org/reference/hooks/save_post/
        add_action( 'save_post', array( $this, 'save_post'  ), 10, 2 );
        // https://developer.wordpress.org/reference/hooks/manage_post-post_type_posts_custom_column/
        add_action( 'manage_exempo_postagem_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );
        // https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/
        add_filter( 'manage_exempo_postagem_posts_columns', array( $this, 'columns' ) );
        // https://developer.wordpress.org/reference/hooks/manage_this-screen-id_sortable_columns/
        add_filter( 'manage_exempo_postagem_sortable_columns', array( $this, 'sortable_columns' ) );
        // https://developer.wordpress.org/reference/hooks/post_row_actions/
        add_filter( 'post_row_actions', array( $this, 'remove_quick_edit_admin_tabs' ), 10,2 );
    }

    /**
     * Cria um post customizável para listar ou cadastrar
     * Registra também um menu na barra de menus WP
     */
    public function create_post_type() {

        $labels = array(
            'name'                => 'Postagens',
            'singular_name'       => 'Postagem',
            'menu_name'           => 'Exemplo - Postagens',
            'all_items'           => 'Todas as Postagens',
            'view_item'           => 'Ver Postagem',
            'add_new_item'        => 'Nova Postagem',
            'add_new'             => 'Nova Postagem',
            'edit_item'           => 'Editar Postagem',
            'update_item'         => 'Atualizar Postagem',
            'search_items'        => 'Pesquisar Postagem',
            'not_found'           => 'Postagem não encontrada',
            'not_found_in_trash'  => 'Postagem não encontrada no lixo'
        );

        $args = array(
            'label'               => 'Exemplo - Postagens',
            'description'         => 'Exemplo gestor de postagem',
            'labels'              => $labels,
            'supports'            => array( 'author' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest' => true,
            'delete_with_user' => false,
            'menu_icon' => 'dashicons-building',
        );

        // https://developer.wordpress.org/reference/functions/register_post_type/
        register_post_type( 'exempo_postagem', $args );
    }

    /**
     * Customizar colunas na listagem de posts.
     * Pode-se adicionar, remover e ordenar (a ordem de cada coluna é obedecida na mesma ordem que é construído o array $columns).
     * A variável $columns recebe um array com a key da coluna e o nome 
     * @param string[] $columns Array com os nomes das colunas
     */
    public function columns( $columns ) {
        // Ex: Removendo a coluna author 
        unset( $columns['author'] );

        // Ex: Customizar as colunas
        $columns = array(
            'cb'            => $columns['cb'], // Coluna de checkbox
            'title'         => $columns['title'], // Título do post
            'user_login'    => "Login de usuário",
            'user_email'    => "E-mail",
            'date'          => $columns['date'] // Coluna de data   
        );
        return $columns;
    }
    
    /**
     * Adicionando valores personalizados as novas colunas.
     * Aqui pode-se definir qual valor será incluso em cada coluna desejada.
     * @param string $column Armazena o nome da coluna
     * @param int $post_id Id do post
     */
    public function custom_columns( $column, $post_id ) {
    
        $user_login = get_post_meta( $post_id, 'user_login', true );
        $user_email = get_post_meta( $post_id, 'user_email', true );
    
        switch ( $column ) {
            case 'user_login':
                echo $user_login;
                break;
            case 'user_email':
                echo $user_email;
                break;
        }
    }
    
    /**
     * Define qual coluna deve conter a opção de ordenação
     * @param string[] $columns Array com os nomes das colunas
     */
    public function sortable_columns( $columns ) {
        $columns['user_login'] = 'user_login';
        return $columns;
    }

    /**
     * Remove as opções da coluna
     * @param string[] $actions Array de opções da coluna
     * @param WP_Post $post Objeto da postagem
     */
    function remove_quick_edit_admin_tabs( $actions, $post ) {
        global $post;

        if ($post->post_type=='exempo_postagem') {
            unset($actions['edit']);
            unset($actions['trash']);
            unset($actions['view']);
            unset($actions['inline hide-if-no-js']);
        }
                
        return $actions;
    }

    /**
     *  Adicionar um metabox dentro da tela de criação e edição do post
     */
    public function add_meta_boxes() {

        // https://developer.wordpress.org/reference/functions/add_meta_box/
        add_meta_box(
            'exempo_postagem_dados_metabox',
            'Dados da Postagem',
            array( $this, 'add_exempo_postagem_dados_metaboxes' ),
            'exempo_postagem',
            'normal',
            'high'
        );

        // https://developer.wordpress.org/reference/functions/remove_meta_box/
        remove_meta_box( 'slugdiv', 'exempo_postagem', 'normal' );
    }

    /**
     * Adiciona o view html
     * @param WP_Post $post Objeto da postagem
     */
    public function add_exempo_postagem_dados_metaboxes( $post ) {
        require_once( EXEMPLO_PLUGIN_PATH . 'includes/views/exempo_postagem_dados_metabox.php' );
    }

    /**
     * Ao salvar post
     * @param int $post_id Id do post
     */
    public function save_post( $post_id ) {
        
        // Verifica o noce
        if( isset( $_POST['exempo_postagem_nonce'] ) ){
            // https://developer.wordpress.org/reference/functions/wp_verify_nonce/
            if( ! wp_verify_nonce( $_POST['exempo_postagem_nonce'], 'exempo_postagem_nonce' ) ) {
                return;
            }
        } else {
            return;
        }

        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
            return;
        }

        // Permições de usuário
        if( isset( $_POST['post_type'] ) && $_POST['post_type'] === 'exempo_postagem' ) {
            // https://developer.wordpress.org/reference/functions/current_user_can/
            if( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }elseif( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }

        if( isset( $_POST['action'] ) && $_POST['action'] == 'editpost' ) {
            /**
             * Ações de salvamento de dados aqui,
             * Neste exemplo inserimos um novo usuário
             */

            $first_name = sanitize_text_field( $_POST['first_name'] );
            $last_name = sanitize_text_field( $_POST['last_name'] );
            $user_login = sanitize_text_field( $_POST['user_login'] );
            $user_email = sanitize_email( $_POST['user_email'] );
            $pass1 = $_POST['pass1'];

            $full_name = $first_name . ' ' . $last_name;

            $user_id_exits = username_exists( $user_login );

            // Inserir novo usuário
            if( empty( $user_id_exits ) && !empty( $user_login ) ) {

                $userdata = array(
                    'user_pass'             => $pass1, 
                    'user_login'            => $user_login,
                    'user_email'            => $user_email,
                    'first_name'            => $first_name, 
                    'last_name'             => $last_name,
                    'dispaly_name'          => $full_name,
                    'role'                  => 'customer',      
                );
                 
                // https://developer.wordpress.org/reference/functions/wp_insert_user/
                $user_id = wp_insert_user( $userdata ) ;
                 
                // https://developer.wordpress.org/reference/functions/is_wp_error/
                if ( is_wp_error( $user_id ) ) {
                    echo "Insert user error : ". $user_id;
                    return;
                } else {
                    add_post_meta( $post_id, 'user_login', $user_login );
                    add_post_meta( $post_id, 'user_email', $user_email );
                }
            } else {
                // https://developer.wordpress.org/reference/functions/wp_update_user/
                $user_id = wp_update_user( ['ID' => $user_id_exits, 
                    'user_nicename' => $first_name,
                    'user_email'    => $user_email,
                    'display_name'  => $full_name,
                    'first_name'    => $first_name,
                    'last_name'     => $last_name,
                    'user_pass'     => $pass1
                ] );

                // https://developer.wordpress.org/reference/functions/is_wp_error/
                if ( is_wp_error( $user_id ) ) {
                    echo "Update user error: ". $user_id;
                } else {
                    update_post_meta( $post_id, 'user_login', $user_login );
                    update_post_meta( $post_id, 'user_email', $user_email );
                }
            }

            // https://developer.wordpress.org/reference/classes/wpdb/
            global $wpdb;

            // Atualizar título e o conteúdo do post
            if(the_title() == '' && the_content() == '') {
                $where = array( 'ID' => $post_id );
                $wpdb->update( 
                    $wpdb->posts, 
                    array( 
                        'post_title' => $first_name,
                        'post_content' => json_encode( $userdata ),
                        'post_parent' => $user_id
                    ), 
                    $where 
                );
            }
        }
    }
}

if( class_exists( 'Postagens_cpt' ) ) {
    $postagem_cpt = new Postagens_cpt();
}