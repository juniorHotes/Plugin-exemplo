
/**
 * Quando o usuário for selecionado no input Select2
 * @param {number} userID 
 */
function getUserFound(userID) {

    $.ajax({
        url: ajaxurl,
        type: 'post',
        data: {
            action: 'exemplo_postagem_pesquisa_encontrada',
            user_id: parseInt(userID)
        },
        beforeSend: function () {
            console.log('Pesquisando...');
        },
        success: function (response) {
            const { success, data } = response;

            if(success) {
                const { origin, pathname } = window.location;
                window.location = `${origin}${pathname}?post=${data.post_id}&action=edit`;
            }   
        },
        error: function (error) {
            console.error('error', error);
        },
    });
}

jQuery(function ($) {  
    /**
     * Habilita a funcionalidade de pesquisar um usuário usando o pacote Select2.js  
     */ 
    $("#exemplo_postagem_pesquisar").select2({
        width: '350px',
        ajax: {
            url: ajaxurl, // AJAX URL is predefined in WordPress admin
            dataType: 'json',
            delay: 250, // delay in ms while typing when to perform a AJAX search
            data: function (params) {
                return {
                    q: params.term, // search query
                    action: 'exemplo_postagem_pesquisar' // AJAX action for admin-ajax.php
                };
            },
            processResults: function (data) {
                var options = [];
                if (data) {

                    // data is the array of arrays, and each of them contains ID and the Label of the option
                    $.each(data, function (index, text) { // do not forget that "index" is just auto incremented value
                        options.push({ id: text[0], text: text[1] });
                    });

                }
                return {
                    results: options
                };
            },
            cache: true
        },
        minimumInputLength: 3,
        language: {
            errorLoading: function () { return 'Os resultados não podem ser carregados.'; },
            inputTooShort: function (e) {
                return 'Digite ' + (e.minimum - e.input.length) + ' ou mais caracteres';
            },
            noResults: function () { return 'Nenhum resultado encontrado'; },
            searching: function () { return 'Procurando...'; },
            removeAllItems: function () { return 'Remova todos os objetos'; },
        }
    });

    jQuery(document.body).on("change", "#exemplo_postagem_pesquisar", function (e) {
        let dataObject = jQuery('#exemplo_postagem_pesquisar').select2('data');;
        if (dataObject) {
            getUserFound(dataObject[0]['id']);
        }
    });

    /**
     * Habilita qualquer elemento html do tipo select à ter o modelo do pacote Select2.js   
     */
    $("select.select2").select2({ width: '100%' });
});