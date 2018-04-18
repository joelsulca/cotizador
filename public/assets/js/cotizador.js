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

    function get_annios(marca, modelo) {
        if(modelo) {
            $.ajax({
                dataType: "json",
                url: '/cotizador/annios/' + marca + '/' + modelo,
                success: function (response) {
                    $('#s-annio').find('option').remove();

                    $('<option/>').val("").html('Seleccione').appendTo('#s-annio');
                    $.each(response, function (index, item) {
                        $('<option/>').val(item.anio_fabricacion).data('costo', item.costo).html(item.anio_fabricacion).appendTo('#s-annio');
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
        get_annios($('#s-marca').val(), this.value);
    });
});