$(document).ready(function () {

    function get_modelos(marca) {
        if (marca) {
            $.ajax({
                dataType: "json",
                url: 'modelos/' + marca,
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
        if (modelo) {
            $.ajax({
                dataType: "json",
                url: 'annios/' + marca + '/' + modelo,
                success: function (response) {
                    $('#s-annio').find('option').remove();

                    $('<option/>').val("").html('Seleccione').appendTo('#s-annio');
                    $.each(response, function (index, item) {
                        $('<option/>').val(item.anio_fabricacion).data('costo', item.costo).html(item.anio_fabricacion).appendTo('#s-annio');
                    });
                }
            });
        } else {
            $('#s-annio').find('option').remove();
            $('<option/>').val("").html('Seleccione').appendTo('#s-annio');
        }
    }

    function set_valor_comercial(costo) {
        $('#r-valor-com').attr('value', costo);
        var porcentual_valor = Math.round(costo * 0.1);
        var valor_min = costo-porcentual_valor;
        var valor_max = costo+porcentual_valor;
        $('#r-valor-com').attr('min', valor_min);
        $('#r-valor-com').attr('max', valor_max);
        $('#r-valor-com').attr('value', costo);
        $('#r-valor-com').show();
        $('#valor-comercial-min').html('<b>$ '+ format_valor(valor_min) +'</b>');
        $('#valor-comercial-max').html('<b>$ '+ format_valor(valor_max) +'</b>');
        show_valor_comercial(costo);
    }

    function show_valor_comercial(valor){
        if( !isNaN(valor)) {
            var monto = format_valor(valor);
            $('#valor-comercial').html("<b>$ " + monto + "</b>");
        }
    }

    function format_valor(valor){
        if( !isNaN(valor)) {
            return valor.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    }

    get_modelos($('#s-marca').val());

    $('#s-marca').on('change', function () {
        get_modelos(this.value);
    });

    $('#s-modelo').on('change', function () {
        get_annios($('#s-marca').val(), this.value);
    });

    $('#s-annio').on('change', function () {
        set_valor_comercial( parseFloat($(this).find('option:selected').data('costo')) );
    });

    $('#r-valor-com').on('input', function () {
        show_valor_comercial(this.value);
    });
});