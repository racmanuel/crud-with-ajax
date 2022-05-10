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

    /**
     * Llamada de Ajax para Editar Registro
     */
    $('#formulario-edit').submit(function (e) {
        e.preventDefault();
        var id = $('#id').val();
        var nombre = $('#name_customer').val();
        var correo = $('#email_customer').val();
        var estados = $('#estados').val();
        //La llamada AJAX
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url, // Pon aquí tu URL
            data: {
                action: 'update_custom_form',
                id_customer: id,
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
                $('#alerts .message-header').html('Actualizado');
                $('#alerts .message-body').html('Tu datos se han actualizado correctamente en la Base de Datos.');
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

    /** DataTables */
    var table = $('#example').DataTable({
        rowId: [0],
        "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": "<button>Editar</button><button id='delete'>Eliminar</button>"
        }]
    });

    /**
     * Al Hacer click obtienes el ID de la columna para Editar
     */
    $('#example tbody').on( 'click', 'button', function () {
        var data = table.row( $(this).parents('tr') ).data();
        //alert("El ID es " + data[0]);
        window.location.href = '/edit?id='+data[0];
    } );

    /**
     * Al Hacer click obtienes el ID de la columna para Borrar
     */
     $('#example tbody').on( 'click', '#delete', function (e) {
        e.preventDefault();
        var data = table.row( $(this).parents('tr') ).data();
        //alert("El ID es " + data[0]);
        window.location.href = '?id='+data[0];
        var id = data[0];
        $.ajax({
            type: 'GET',
            url: ajax_object.ajax_url, // Pon aquí tu URL
            data: {
                action: 'delete_custom_form',
                id_customer: id,
            },
            beforeSend: function (response) {
               console.log('Cargando...');
            },
            success: function (response) {
                console.log('Eliminado.');
            },
            error: function (response) {
                console.log('Error...');
            }
        })
    } );
    
    /**
     * 
    $('#example').on( 'click', 'tr', function () {
        var id = table.row( this ).id();
        alert( 'Clicked row id '+id );
    } );
    */
});