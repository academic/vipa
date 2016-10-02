$(document).ready(function () {

    $.material.init();

    $('[data-toggle="tooltip"], [rel="tooltip"]').tooltip();

    if ($('.datepicker').length != 0) {
        $('.datepicker').datepicker({
            weekStart: 1
        });
    }

    $('[data-toggle="popover"]').popover();

    $('.carousel').carousel({
        interval: 400000
    });

});
