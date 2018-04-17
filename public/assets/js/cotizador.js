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
                }
            });
        }
    }

    get_modelos($('#s-marca').val());

    $('#s-marca').on('change', function () {
       get_modelos(this.value);
    });
});