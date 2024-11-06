
function ajax (metod = 'POST', url = 'api.php', data = {}){
    return new Promise((resolve, reject) => {
        $.ajax({
            type: metod,
            url: url,
            data: data,
            dataType: 'json',
            success: function(response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
}
