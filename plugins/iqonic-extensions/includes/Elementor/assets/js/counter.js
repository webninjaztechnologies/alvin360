(function ($) {
    "use strict";

    $(document).ready(function () {
        callCountTo();
    });

    function callCountTo() {
        // Cache the frequently used elements
        var $counters = $('.iq-counter[data-once=true]');
        var $timers = $('.timer');

        $(window).on('resize scroll', function () {
            $counters.each(function () {
                if ($timers.isInViewport()) {
                    $timers.countTo();
                }
            });
        });
    }

    // Cache the frequently used elements
    var $window = $(window);

    $.fn.isInViewport = function () {
        var $element = $(this);
        var elementTop = $element.offset().top;
        var elementBottom = elementTop + $element.outerHeight();
        var viewportTop = $window.scrollTop();
        var viewportBottom = viewportTop + $window.height();
        return elementBottom > viewportTop && elementTop < viewportBottom;
    };

})(jQuery);
