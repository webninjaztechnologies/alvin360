(function ($) {
    "use strict";
    $(document).ready(function () {
        callTimer();
    });

})(jQuery);
function callTimer(){
    jQuery('.iq-data-countdown-timer').each(function () {
        var future_date = jQuery(this).attr('data-date');
        var label = jQuery(this).attr('data-labels');
        var displayFormat = jQuery(this).attr('data-format');
        var l = true;
        if (label == "true") {
            l = true;
        } else {
            l = false;
        }
        jQuery(this).countdowntimer({
            dateAndTime: future_date,
            labelsFormat: l,
            displayFormat: displayFormat,
        });
    });
}