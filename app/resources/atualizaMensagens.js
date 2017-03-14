function getContent(id) {
    
    // cria script para scroll bottom
    $('#wrap-mensagens-conversa').animate({scrollTop: $('#wrap-mensagens-conversa').prop('scrollHeight')}, 500);
    
    var queryString = {'id': id};
    $.get('refreshMessage.php', queryString, function(data) {
        var mensagem = jQuery.parseJSON(data);
    
        // limpa área de mensagens
        $('#wrap-mensagens-conversa').html('');

        for (var i in mensagem) {
            var texto =  '<span class="box-mensagem '+mensagem[i].classeCSS+'">'; 
                texto +=     mensagem[i].texto;
                texto +=     '<p class="info-mensagem">'+ mensagem[i].remetente +': '+ mensagem[i].data_envio+ ' ' +mensagem[i].hora_envio +'</p>';
                texto += '</span>';

            var id = mensagem[i].id;
            $('#wrap-mensagens-conversa').append(texto);
        }
        
        // reconecta ao receber uma resposta do servidor
        getContent(id);
    });
}
 
$(document).ready(function() {
    getContent();
});