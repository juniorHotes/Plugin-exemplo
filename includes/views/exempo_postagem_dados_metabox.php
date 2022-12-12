<?php
    global $post_id;

    // https://developer.wordpress.org/reference/functions/get_post/
    $post = get_post( $post_id );

    $user_id = $post->post_parent;
    
    $user = get_user_by( 'ID', $user_id );
    $first_name = get_user_meta( $user_id, 'first_name', true );
    $last_name = get_user_meta( $user_id, 'last_name', true );
?>

<input type="hidden" name="exempo_postagem_nonce" value="<?php echo wp_create_nonce( "exempo_postagem_nonce" ) ?>">

<table class="form-table" role="presentation" style="min-width:440px;max-width:680px;">
    
    <label style="display:block" for="exemplo_postagem_pesquisar">Procurar usuário</label>
    <select name="exemplo_postagem_pesquisar" id="exemplo_postagem_pesquisar"></select><br>
    
	<tbody>
        <p class="description">Todos os campos contendo * são obrigatórios</p>
        <tr class="form-field form-required">
            <th scope="row">
                <label for="first_name">Nome <span class="description">*</span></label>
            </th>
            <td>
                <input 
                    name="first_name" 
                    type="text" 
                    id="first_name" 
                    value="<?php echo sanitize_text_field( $first_name ); ?>" 
                    required
                >
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row">
                <label for="last_name">Sobrenome <span class="description">*</span></label>
            </th>
            <td>
                <input 
                    name="last_name" 
                    type="text" 
                    id="last_name" 
                    value="<?php echo sanitize_text_field( $last_name ); ?>"
                    required
                >
            </td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row">
                <label for="user_email">E-mail <span class="description">*</span></label>
            </th>
            <td>
                <input 
                    name="user_email" 
                    type="email" 
                    id="user_email" 
                    value="<?php echo sanitize_email( $user->data->user_email ); ?>"
                    required
                >
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row">
                <label for="user_login">Nome de usuário <span class="description">*</span></label>
            </th>
            <td>
                <p class="description">Será usado para fazer login, deve ser único e não poderá ser editado</p>
                <input 
                    name="user_login" 
                    type="text" 
                    id="user_login" 
                    value="<?php echo sanitize_text_field( $user->data->user_login ); ?>"
                    required
                    maxlength="60"
                >
            </td>
        </tr>

        <?php if( !empty( $phx_user_id ) ): ?>
            <tr>
                <th scope="row">
                    <label for="new_pass">Nova Senha <span class="description"></span></label>
                </th>
                <td>
                    <button type="button" class="button button-large">Definir nova senha</button>
                </td>
            </tr>
        <?php else: ?>
            <!-- Password -->
            <tr class="form-field form-required user-pass1-wrap">
                <th scope="row">
                    <label for="pass1">Senha <span class="description">*</span></label>
                </th>
                <td>
                    <input 
                        type="password" 
                        name="pass1" 
                        id="pass1" 
                        value="<?php echo ''; ?>" 
                        autocomplete="off" 
                    >
                </td>
            </tr>
            <tr class="form-field form-required user-pass2-wrap">
                <th scope="row">
                    <label for="pass2">Repetir senha <span class="description">*</span></label>
                </th>
                <td>
                    <p class="description" id="pass2-desc">Digite a senha novamente.</p>
                    <input 
                        name="pass2" 
                        type="password" 
                        id="pass2" 
                        value="<?php echo ''; ?>" 
                        autocomplete="off" 
                    >
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>