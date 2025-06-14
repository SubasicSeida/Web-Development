$(document).ready(function () {
    "use strict";

    $("main#spapp > section").height($(document).height() - 60);
    var app = $.spapp({ pageNotFound: "error_404" });

    app.route({
        view: "home",
        load: "home.html",
        onReady: function () {
            initOwlCarousel();
        },
    });
    app.route({
        view: "properties",
        load: "properties.html",
    });
    app.route({
        view: "agents",
        load: "agents.html",
    });
    app.route({
        view: "account",
        load: "account.html",
    });
    app.route({
        view: "create-listing",
        load: "create-listing.html",
    });
    app.route({
        view: "view-listing",
        load: "view-listing.html",
        onReady: function () {
            initLightbox();
        }
    });

    app.run();

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "2000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($("#spinner").length > 0) {
                $("#spinner").removeClass("show");
            }
        }, 1);
    };
    spinner();


    // Initiate the wowjs
    new WOW().init();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 45) {
            $(".nav-bar").addClass("sticky-top");
        } else {
            $(".nav-bar").removeClass("sticky-top");
        }
    });


    // Navbar toggler
    $(".navbar-nav .nav-link, .navbar-collapse .btn").on("click", function () {
        if ($("#navbarCollapse").hasClass("show")) {
            $("#navbarToggler").click();
        }
    });


    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $(".back-to-top").fadeIn("slow");
        } else {
            $(".back-to-top").fadeOut("slow");
        }
    });

    $(document).on("click", ".back-to-top", function (event) {
        event.preventDefault();

        $("html, body").animate({ scrollTop: 0 }, 500, "easeInOutExpo");
    });


    // Header carousel
    function initializeOwlCarousel() {
        let $carousel = $(".header-carousel");

        if ($carousel.hasClass("owl-loaded")) {
            $carousel.trigger("destroy.owl.carousel");
            $carousel.removeClass("owl-loaded");
            $carousel.find(".owl-stage-outer").children().unwrap();
        }

        $carousel.owlCarousel({
            autoplay: true,
            smartSpeed: 1500,
            items: 1,
            dots: true,
            loop: true,
            nav: true,
            navText: [
                '<i class="bi bi-chevron-left"></i>',
                '<i class="bi bi-chevron-right"></i>',
            ],
        });
    }

    if ($(".header-carousel").length) {
        initializeOwlCarousel();
    }

    setInterval(function () {
        if (
            $(".header-carousel").length &&
            !$(".header-carousel").hasClass("owl-loaded")
        ) {
            initializeOwlCarousel();
        }
    }, 500);


    // Testimonials carousel
    function initOwlCarousel() {
        $(".testimonial-carousel").owlCarousel({
            autoplay: true,
            smartSpeed: 1000,
            margin: 24,
            dots: false,
            loop: true,
            nav: true,
            navText: [
                '<i class="bi bi-arrow-left"></i>',
                '<i class="bi bi-arrow-right"></i>',
            ],
            responsive: {
                0: {
                    items: 1,
                },
                992: {
                    items: 2,
                },
            },
        });
    }


    $(document).on('click', '.star-toggle', function () {
        PropertyService.toggleStar(this);
    });


    /*** Profile picture preview ***/
    $(document).on('change', '#editProfilePicture', function (e) {
        const file = e.target.files[0];
        if (file) {
            const imageURL = URL.createObjectURL(file);
            $('#profilePreview').attr('src', imageURL);
        }
    });


    /*** Property Image Lightbox ***/
    let galleryImages = [];
    let currentIndex = 0;

    function updateModalImage() {
        $('#modalImage').attr('src', galleryImages[currentIndex]);
    }

    function initLightbox() {
        galleryImages = [];
        $('.gallery-image').each(function () {
            galleryImages.push($(this).attr('src'));
        });

        $('.gallery-image').off('click').on('click', function () {
            currentIndex = $('.gallery-image').index(this);
            updateModalImage();
        });

        $('#prevImage').off('click').on('click', function () {
            currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
            updateModalImage();
        });

        $('#nextImage').off('click').on('click', function () {
            currentIndex = (currentIndex + 1) % galleryImages.length;
            updateModalImage();
        });
    }


    /*** Star rating ***/
    let selectedRating = 0;

    function highlightStars(rating) {
        $('.star-rating').each(function () {
            const starValue = $(this).data('value');
            if (starValue <= rating) {
                $(this).removeClass('far').addClass('fas');
            } else {
                $(this).removeClass('fas').addClass('far');
            }
        });
    }

    $(document).on('mouseenter', '.star-rating', function () {
        const hoverValue = $(this).data('value');
        highlightStars(hoverValue);
    });

    $(document).on('mouseleave', '.star-rating', function () {
        highlightStars(selectedRating);
    });

    $(document).on('click', '.star-rating', function () {
        if (selectedRating === $(this).data('value')) {
            selectedRating = 0;
        } else selectedRating = $(this).data('value');
        highlightStars(selectedRating);
    });

    $("#search-form").on("submit", function (e) {
        e.preventDefault();

        const filters = $(this).serialize();
        window.location.hash = "properties?" + filters;
    });

    /*** Sorting ***/


    /***Pagination ***/


});