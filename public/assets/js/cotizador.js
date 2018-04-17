$(document).ready(function () {

    function get_modelos(marca) {
        $.get('cotizador/modelos', function () {
            $('s-modelo').find('option').remove();

        });
    }

    $('#s-marca').on('change', function () {
       get_modelos(this.value);
    });
});