<?php

class Ajax {

    function __construct() {
        // https://developer.wordpress.org/reference/hooks/wp_ajax_action/
		add_action( 'wp_ajax_exemplo_postagem_pesquisar', array( $this, 'exemplo_postagem_pesquisar' ) );
		add_action( 'wp_ajax_exemplo_postagem_pesquisa_encontrada', array( $this, 'exemplo_postagem_pesquisa_encontrada' ) );

    }

    /** 
	 * Procurar usuário.
     * Enquando o usuário dígita esta função é executada
	 */
	public function exemplo_postagem_pesquisar() {
		$return = array();
	
		$query = $_GET['q'];
	
		$search_results = get_users( array( 
			'search' => '*' . $query . '*', 
			'search_columns' => array(
				'user_login',
				'user_email',
				'display_name',
			),
		));
		if( $search_results ) :
			foreach( $search_results as $result ):	
				$title = $result->display_name;
				$return[] = array( $result->ID, $title );
			endforeach;
		endif;
		
		echo json_encode( $return );
		wp_die();
	}

    /**
	 * Usuário encontrado.
     * Quando o usuário for encontrado na lista de usuário no input select2 
	 */
	public function exemplo_postagem_pesquisa_encontrada() {

		$user_id = $_POST['user_id'];

        $post = get_post( array( 'post_author' => $user_id ) );
	
        wp_send_json_success( [ 
            'post_id' =>  $post->ID, 
        ], 200 );

		wp_die();
	}

}

if( class_exists('Ajax') ) {
    $ajax = new Ajax();
}