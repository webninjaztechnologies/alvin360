(function () {
    "use strict";
    document.addEventListener('DOMContentLoaded', function () {
        var productSingleSliders = document.querySelectorAll('.product-single-slider');
        if (productSingleSliders.length > 0) {
            productSingleSliders.forEach(function (slider) {
                var config;
                if (slider.classList.contains("image-slider")) {
                    config = {
                        slidesPerView: 1,
                        paginationClickable: true,
                        pagination: '.swiper-pagination',
                        paginationType: "bullets",
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev'
                        },
                        loop: true,
                        spaceBetween: 0
                    };
                }

                if (slider.classList.contains("related-slider") || slider.classList.contains("upsells-slider")) {
                    var sliderAutoplay = slider.getAttribute('data-autoplay');
                    if (sliderAutoplay) {
                        sliderAutoplay = {
                            delay: parseInt(slider.getAttribute('data-autoplay'))
                        };
                    }
                    config = {
                        loop: slider.getAttribute('data-loop'),
                        speed: slider.getAttribute('data-speed'),
                        spaceBetween: 0,
                        slidesPerView: slider.getAttribute('data-slide'),
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev'
                        },
                        autoplay: sliderAutoplay,
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true
                        },
                        grabCursor: true,
                        breakpoints: {
                            0: {
                                slidesPerView: slider.getAttribute('data-mobile'),
                            },
                            768: {
                                slidesPerView: slider.getAttribute('data-tab'),
                            },
                            999: {
                                slidesPerView: slider.getAttribute('data-laptop'),
                            },
                            1400: {
                                slidesPerView: slider.getAttribute('data-slide'),
                            }
                        },
                    };
                }

                var swiper = new Swiper(slider, config);
                document.addEventListener('theme_scheme_direction', function (e) {
                    swiper.destroy(true, true);
                    setTimeout(function () {
                        swiper = new Swiper('.product-single-slider', config);
                    }, 500);
                });
            });
            /* Resize window on load */
            window.dispatchEvent(new Event('resize'));
        }
    });
})();
