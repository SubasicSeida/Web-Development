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
    view: "signup",
    load: "signup.html",
  });
  app.route({
    view: "login",
    load: "login.html",
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
  });

  app.run();

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

  /*$(".header-carousel").owlCarousel({
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
  });*/

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


// Property Image Modal
function openGalleryModal(imageSrc) {
  document.getElementById("modalImage").src = imageSrc;
}

/*** Sorting ***/


/***Pagination ***/


});