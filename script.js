function ajax (metod = 'POST', url = 'api.php', data = {}){
    $.ajax({
        type: 'POST',
        url: 'api.php',
        data: { email: email, password: password },
        dataType: 'json',
        success: function(response) {
            return response;
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText); 
            console.info(xhr.responseText); 
            alert('Erro ao processar a requisição: ' + error);
        }
    });
}
