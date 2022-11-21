(function ($) {
    "use strict";

    ///////////////////////////
    // Preloader
    $(window).on('load', function () {
        $("#preloader").delay(600).fadeOut();
    });

    ///////////////////////////
    // Scrollspy
    $('body').scrollspy({
        target: '#nav',
        offset: $(window).height() / 2
    });

    ///////////////////////////
    // Smooth scroll
    $("#nav .main-nav a[href^='#']").on('click', function (e) {
        e.preventDefault();
        var hash = this.hash;
        $('html, body').animate({
            scrollTop: $(this.hash).offset()
        }, 600);
    });

    $('#back-to-top').on('click', function () {
        $('body,html').animate({
            scrollTop: 0
        }, 600);
    });

    ///////////////////////////
    // Btn nav collapse
    $('#nav .nav-collapse').on('click', function () {
        $('#nav').toggleClass('open');
    });

    ///////////////////////////
    // Mobile dropdown
    $('.has-dropdown a').on('click', function () {
        $(this).parent().toggleClass('open-drop');
    });

    ///////////////////////////
    // On Scroll
    if ($('#home').length) {
        $(window).on('scroll', function () {
            var wScroll = $(this).scrollTop();

            // Fixed nav
            wScroll > 4 ? $('#nav').addClass('fixed-nav') : $('#nav').removeClass('fixed-nav');

            // Back To Top Appear
            wScroll > 400 ? $('#back-to-top').fadeIn() : $('#back-to-top').fadeOut();
        });
    } else {
        $('#nav').removeClass('fixed-nav');
    }

    ///////////////////////////
    // magnificPopup
    $('.work').magnificPopup({
        delegate: '.lightbox',
        type: 'image'
    });


    ///////////////////////////
    // Disable Register Button
    $("#terms").click(function () {
        var checked_status = this.checked;
        if (checked_status == true) {
            $("#signup").removeAttr("disabled");
        } else {
            $("#signup").attr("disabled", "disabled");
        }
    });

    ///////////////////////////
    // Owl Carousel
    $('#about-slider').owlCarousel({
        items: 1,
        loop: true,
        margin: 15,
        nav: true,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        dots: true,
        autoplay: true,
        animateOut: 'fadeOut'
    });

    $('#testimonial-slider').owlCarousel({
        loop: true,
        margin: 15,
        dots: true,
        nav: false,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            992: {
                items: 2
            }
        }
    });

    //////////////////////////
    // Show Nav Bar
    $('document').ready(function () {
        if ($('#blog').length) {
            $('#nav').addClass('fixed-nav');
        } else if ($('#pricing').length) {
            $('#nav').addClass('fixed-nav');
        } else if ($('#contact').length) {
            $('#nav').addClass('fixed-nav');
        } else if ($('#terms').length) {
            $('#nav').addClass('fixed-nav');
        } else if ($('#fixedNavBar').length) {
            $('#nav').addClass('fixed-nav');
            $(window).on('scroll', function () {
                var wScroll = $(this).scrollTop();
                // Back To Top Appear
                wScroll > 400 ? $('#back-to-top').fadeIn() : $('#back-to-top').fadeOut();
            });
        }
        if ($('#blog').length) {
            $(window).on('scroll', function () {
                var wScroll = $(this).scrollTop();
                // Back To Top Appear
                wScroll > 400 ? $('#back-to-top').fadeIn() : $('#back-to-top').fadeOut();
            });
        }
    });

    $('#logout-link').click(function () {
        window.localStorage.removeItem('navDropdownToggle');
    });

    function toggleIcon(e) {
        $(e.target)
            .prev('.panel-heading')
            .find(".more-less")
            .toggleClass('glyphicon-plus glyphicon-minus');
    }

    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);

})(jQuery);
