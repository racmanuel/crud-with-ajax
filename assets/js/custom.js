jQuery(document).ready(function ($) {
    $('#alerts').hide();
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
                $('#alerts').addClass('is-info');
                $('#alerts').show();
                $('#alerts .message-header').html('Enviando datos...');
                $('#alerts .message-body').html('Tomara unos segundos...');
            },
            success: function (response) {
                // Actualiza el mensaje con la respuesta
                $('#alerts').addClass('is-success');
                $('#alerts').show();
                $('#alerts .message-header').html('Enviado');
                $('#alerts .message-body').html('Tu datos se han almacenado correctamente en la Base de Datos.');
                console.log(response);
            },
            error: function (response) {
                 // Actualiza el mensaje con la respuesta
                 $('#alerts').addClass('is-danger');
                 $('#alerts').show();
                 $('#alerts .message-header').html('Error');
                 $('#alerts .message-body').html('Ha ocuriido un error al enviar los datos a la Base de Datos, intentelo nuevamente.');
            }
        })
    });
});