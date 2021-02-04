(function ($) {
    'use strict';

    // Preloader js
    $(window).on('load', function () {
        $('.preloader').fadeOut(100);
    });

    // Accordions
    $('.collapse').on('shown.bs.collapse', function () {
        $(this).parent().find('.ti-angle-right').removeClass('ti-angle-right').addClass('ti-angle-down');
    }).on('hidden.bs.collapse', function () {
        $(this).parent().find('.ti-angle-down').removeClass('ti-angle-down').addClass('ti-angle-right');
    });


    //slider
    $('.slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        dots: true,
        arrows: false
    });

    //Contact form submission
    var form = $('#contact-us');
    form.submit(function (event) {

        $('.alert').remove();

        var visa = $('#visa').val();

        if(visa && visa !== '') {
            window.location.replace("/");
        }

        var formData = {
            'name': $('input[name=name]').val(),
            'email': $('#email').val(),
            'subject': $('input[name=subject]').val(),
            'message': $('#message').val()
        };

        // process the form
        $.ajax({
            type: 'POST',
            url: '/forms/request.php',
            data: formData,
            dataType: 'json',
            encode: true
        })
            .done(function (data) {
                if (data.success !== true) {
                    form.before(data.errors_html);
                } else if (data.success === true) {
                    window.location.replace("/thank-you/");
                }
            });
        event.preventDefault();
    });

})(jQuery);