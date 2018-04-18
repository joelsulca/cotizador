$(document).ready(function () {

    function get_modelos(marca) {
        if(marca) {
            $.ajax({
                dataType: "json",
                url: '/cotizador/modelos/' + marca,
                success: function (response) {
                    $('#s-modelo').find('option').remove();

                    $('<option/>').val("").html('Seleccione').appendTo('#s-modelo');
                    $.each(response, function (index, item) {
                        $('<option/>').val(item.id).html(item.nombre).appendTo('#s-modelo');
                    });
                    get_annios(null);
                }
            });
        }
    }

    function get_annios(modelo) {
        if(modelo) {
            $.ajax({
                dataType: "json",
                url: '/cotizador/annios/' + modelo,
                success: function (response) {
                    $('#s-annio').find('option').remove();

                    $('<option/>').val("").html('Seleccione').appendTo('#s-annio');
                    $.each(response, function (index, item) {
                        $('<option/>').val(item.anio_fabricacion_id).html(item.anio_fabricacion_id).appendTo('#s-annio');
                    });
                }
            });
        }else{
            $('#s-annio').find('option').remove();
            $('<option/>').val("").html('Seleccione').appendTo('#s-annio');
        }
    }

    get_modelos($('#s-marca').val());

    $('#s-marca').on('change', function () {
       get_modelos(this.value);
    });

    $('#s-modelo').on('change', function () {
        get_annios(this.value);
    });
});