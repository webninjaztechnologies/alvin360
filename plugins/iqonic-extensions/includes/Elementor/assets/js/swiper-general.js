(function ($) {
    "use strict";
    $(window).ready(function () {
        Widget_swiperSlider();
    });
})(jQuery);

function Widget_swiperSlider() {
    if (jQuery('.socialv-widget-swiper').length > 0) {
        jQuery('.socialv-widget-swiper').each(function (e) {

            let slider = jQuery(this);
            var navNext = (slider.data('navnext')) ? "#" + slider.data('navnext') : "";
            var navPrev = (slider.data('navprev')) ? "#" + slider.data('navprev') : "";
            var pagination = (slider.data('pagination')) ? "#" + slider.data('pagination') : "";

            var sliderAutoplay = slider.data('autoplay');
            if (sliderAutoplay) {
                sliderAutoplay = {
                    delay: slider.data('autoplay')
                };
            }
            var iqonicPagination = {
                el: pagination,
                dynamicBullets: true,
                clickable: true
            };

            var breakpoint = {
                0: {
                    slidesPerView: slider.data('mobile'),
                },
                // when window width is >= 767px
                768: {
                    slidesPerView: slider.data('tab'),
                },
                // when window width is >= 1024px
                999: {
                    slidesPerView: slider.data('laptop'),
                },
                // when window width is >= 1200px
                1400: {
                    slidesPerView: slider.data('slide'),
                }
            }
            var sw_config = {
                loop: slider.data('loop'),
                speed: slider.data('speed'),
                spaceBetween: slider.data('spacebtslide'),
                slidesPerView: slider.data('slide'),
                navigation: {
                    nextEl: navNext,
                    prevEl: navPrev
                },
                autoplay: sliderAutoplay,
                pagination: (slider.data('pagination')) ? iqonicPagination : "",
                breakpoints: breakpoint,
            };
            var swiper = new Swiper(slider[0], sw_config);
            document.addEventListener('theme_scheme_direction', (e) => {
                swiper.destroy(true, true)
                setTimeout(() => {
                    swiper = new Swiper('.socialv-widget-swiper', sw_config);
                }, 500);
            })
        });

        /* Resize window on load */
        setTimeout(function () {
            window.dispatchEvent(new Event('resize'));
        }, 500);

    }
}
