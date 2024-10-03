(function ($) {
    "use strict";
    $(window).ready(function () {
        CallAccordion();
    });
})(jQuery);

function CallAccordion(){
    var accordion = document.getElementsByClassName('socialv-accordion');
    if (jQuery(document).find(accordion).length > 0) {
        jQuery('.socialv-accordion .socialv-active .socialv-accordion-details').slideDown('slow');
        jQuery('.socialv-accordion .socialv-accordion-title').on("click", function () {
            let ele = jQuery(this).parent().hasClass('socialv-active');
            jQuery('.socialv-accordion .socialv-accordion-block').removeClass('socialv-active').children('div.socialv-accordion-details').slideUp('slow');
            if (ele) {
                jQuery(this).parent().removeClass('socialv-active');
            } else {
                jQuery(this).parent().addClass('socialv-active').children('div.socialv-accordion-details').slideDown('slow');
            }
        });
    }
}
