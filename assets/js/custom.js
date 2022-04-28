jQuery(document).ready(function ($) {
    $('#formulario').submit(function (e) {
        e.preventDefault();
        var nombre = $('#name_customer').val();
        var correo = $('#email_customer').val();
        var estados = $('#estados').val();
        //La llamada AJAX
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url, // Pon aquí tu URL
            data: {
                action: 'my_save_custom_form',
                name_customer: nombre,
                email_customer: correo,
                estados: estados
            },
            beforeSend: function (response) {
                $('#alert').html('Enviando ...');
            },
            success: function (response) {
                // Actualiza el mensaje con la respuesta
                $('#alert').html('Enviado');
                console.log(response);
            },
            error: function (response) {
                $('#alert').html('Ocurrio un problema con el envio:' + response);
            }
        })
    });
});