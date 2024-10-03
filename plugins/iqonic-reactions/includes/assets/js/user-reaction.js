(function (jQuery) {
    "use strict";
    add_default_reaction();
    delete_reaction();
    add_reaction();
    get_reaction_box_data();

    window.addEventListener('resize', function () {
        enableSliderNav();
    });
})(jQuery);

function enableSliderNav() {
    const sliders = document.querySelectorAll('.custom-nav-slider');

    sliders.forEach(slider => {
        const parent = slider.parentElement;
        const left = parent.querySelector(".left");
        const right = parent.querySelector(".right");
        
        if (left && right) {
            const shouldDisplayNav = slider.scrollWidth > slider.clientWidth;

            left.style.display = shouldDisplayNav ? "block" : "none";
            right.style.display = shouldDisplayNav ? "block" : "none";
        }
    });
}


function add_default_reaction() {

    jQuery('#activity-stream').on("click", ".user-reaction .reaction-button", _.debounce(function () {

        let $this = jQuery(this);
        let $parent = $this.parent();
        let $superParent = $parent.closest('.activity-content').find('.iqonic-meta-details');

        let reaction_id = $this.data("reaction_id");

        if (reaction_id.length === 0) {
            return;
        }

        let user_id = $this.data("user_id");
        let activity_id = $this.data("activity_id");

        let data = {
            'action': "iqonic_add_reaction_meta",
            'activity_id': activity_id,
            'user_id': user_id,
            'reaction_id': reaction_id,
        };

        jQuery.ajax({
            url: ir_reaction_ajax_params.ajaxUrl,
            data: data,
            type: 'POST',
            success: function (data) {
                var $data = jQuery(data);
                var reactions = $data.filter(".reacted");
                var reactionsData = $data.filter(".iqonic-meta-details");
                $parent.html(reactions);
                $superParent.replaceWith(reactionsData);
            },
            error: function (xhr) {
                
            }
        });
    }, 250));
}


function add_reaction() {
    jQuery('#activity-stream').on("click", ".comment-activity .iqonic-reaction .reaction-option", function () {
        let $this = jQuery(this);
        let $parent = $this.closest('.iqonic-reaction').find('.user-reaction');
        let $superParent = $this.closest('.activity-content').find(".iqonic-meta-details");

        let $reactionWrapper = jQuery('.reaction-wrapper');
        let activityData = $this.parent().data();
        let reactionData = $this.data();

        let data = {
            'action': "iqonic_add_reaction_meta",
            ...activityData,
            ...reactionData,
        };

        jQuery.ajax({
            url: ir_reaction_ajax_params.ajaxUrl,
            data: data,
            type: 'POST',
            success: function (data) {
                var $data = jQuery(data);
                var reactions = $data.filter(".reacted");
                var reactionsData = $data.filter(".iqonic-meta-details");
                $parent.html(reactions);
                $superParent.replaceWith(reactionsData);

                $reactionWrapper.css("display", "none");
                $parent.hover(function () {
                    $reactionWrapper.css("display", "flex");
                });
            },
            error: function (xhr) {
                
            }
        });
    });
}


function delete_reaction() {
    jQuery('#activity-stream').on("click", ".user-reaction .reacted", function () {
        let $this = jQuery(this);
        let $parent = $this.parent();
        let $superParent = $this.closest('.activity-content').find('.iqonic-meta-details');

        let user_id = $this.data("user_id");
        let activity_id = $this.data("activity_id");

        let data = {
            'action': "iqonic_delete_reaction_activity",
            'activity_id': activity_id,
            'user_id': user_id,
        };

        jQuery.ajax({
            url: ir_reaction_ajax_params.ajaxUrl,
            data: data,
            type: 'POST',
            success: function (data) {
                var reactions = jQuery(jQuery.parseHTML(data)).filter(".reaction-button");
                $parent.html(reactions);

                var reactionsData = jQuery(jQuery.parseHTML(data)).filter(".iqonic-meta-details");
                $superParent.replaceWith(reactionsData);

                touch_event('.user-reaction .reaction-button');
            },
        });
    });
}

function get_reaction_box_data() {
    jQuery('#activity-stream').on("click", '.activity-content .emoji-reaction, .activity-content .other-content', function () {
        let activity_id = jQuery(this).data("activity_id");

        let data = {
            'action': "iqonic_get_reaction_box_data",
            'activity_id': activity_id,
        };

        jQuery.ajax({
            url: ir_reaction_ajax_params.ajaxUrl,
            data: data,
            type: 'POST',
            success: function (data) {
                if(jQuery('.ir-reaction-modal').length > 0) {
                    jQuery('.ir-reaction-modal').remove();
                }
                jQuery('body').append(data);
                jQuery('.ir-reaction-modal').addClass("active");

                reaction_box();
                enableSliderNav();

                jQuery('.ir-reaction-modal.active .ir-option').click(function () {
                    let reaction_id = jQuery(this).data("reaction_id");
                    let activity_id = jQuery(this).data("activity_id");

                    let data = {
                        'action': "iqonic_get_grouped_reaction",
                        'activity_id': activity_id,
                        'reaction_id': reaction_id,
                    };

                    jQuery.ajax({
                        url: ir_reaction_ajax_params.ajaxUrl,
                        data: data,
                        type: 'POST',
                        success: function (data) {
                            jQuery('.ir-reaction-card-wrapper').html(data);
                        },
                    });
                });
            },
        });
    });
}

function reaction_box() {

    jQuery('.emoji-reaction').on("click", function () {
        jQuery('.ir-reaction-modal').addClass("active");
    });

    jQuery('.ir-modal-centered .popup_close-button').on("click", function () {
        jQuery('.ir-reaction-modal').removeClass("active");
        jQuery('.ir-reaction-modal').remove();
    });

    jQuery('.ir-option').click(function () {
        jQuery('.ir-option').removeClass('active');
        jQuery(this).addClass('active');
    });

    jQuery('body').on("click", function (e) {
        if (!jQuery(e.target).closest('.ir-box').length) {
            jQuery('.ir-reaction-modal').remove();
        }
    });
}

//touch event for mobile devices
function touch_event(selector) {
    const el = document.querySelectorAll(selector);
    el.forEach(function(element) {
        element.addEventListener('touchstart', touchStartHandler);

        element.addEventListener('touchstart', function() {
            var touch_ele = jQuery(this);
            setTimeout( function() {
                longPress(touch_ele);
            },200);
        });
    });

    document.addEventListener('click',function() {
        const el = jQuery(document).find('.reaction-wrapper');
        el.css("display","none");
    });

    jQuery('.user-reaction').hover(function () {
        jQuery('.reaction-wrapper').css("display", "flex");
    });
}

function touchStartHandler(evt) {
    if(evt == undefined) return;
    evt.preventDefault();
    const el = jQuery(this).closest('.iqonic-reaction').find('.reaction-wrapper');
    el.css("display","none");

    timer = setTimeout(touchStartHandler, 100);
}

function longPress(touch_ele) {
    const el = touch_ele.closest('.iqonic-reaction').find('.reaction-wrapper');
    if(timer) {
        el.addClass("touch-active");
        el.css("display","flex");
    }
    touch_ele.on("touchmove", longpressTouchMoveHandler);
    touch_ele.on("touchend", longpressTouchEndHandler);
}

function longpressTouchMoveHandler(evt) {
    evt.preventDefault();
    evt.stopPropagation();
    var changedTouch = evt.changedTouches[0];
    var x = changedTouch.clientX;
    var y = changedTouch.clientY;
    var elem = document.elementFromPoint(x, y);

    $this = jQuery(elem).closest('.reaction-option');

    jQuery('.reaction-option').removeClass('touch-hover');
    $this.addClass('touch-hover');
}

function longpressTouchEndHandler(evt) {
    evt.preventDefault();
    evt.stopPropagation();
    var changedTouch = evt.changedTouches[0];
    var elem = document.elementFromPoint(changedTouch.clientX, changedTouch.clientY);

    var reaction_id = jQuery(elem).closest(".reaction-option").data("reaction_id");
    var activity_id = jQuery(elem).closest(".reaction-wrapper").data("activity_id");
    var user_id = jQuery(elem).closest(".reaction-wrapper").data("user_id");
    
    const el = jQuery(this).closest('.iqonic-reaction').find('.reaction-wrapper');
    if(timer) {
        el.remove("touch-active");
        el.css("display","none");
    }

    if(reaction_id === undefined || activity_id === undefined || user_id === undefined) return;
    
    let $this = jQuery(elem).closest(".reaction-option");
    let $parent = $this.closest('.iqonic-reaction').find('.user-reaction');
    let $superParent = $this.closest('.activity-content').find(".iqonic-meta-details");

    touchEndAjaxHandler(reaction_id,activity_id,user_id,$parent,$superParent);
}

function touchEndAjaxHandler(reaction_id,activity_id,user_id,$parent,$superParent) {
    let data = {
        'action': "iqonic_add_reaction_meta",
        'reaction_id': reaction_id,
        'activity_id': activity_id,
        'user_id': user_id,
    };

    jQuery.ajax({
        url: ir_reaction_ajax_params.ajaxUrl,
        data: data,
        type: 'POST',
        success: function (data) {
            var reactions = jQuery(jQuery.parseHTML(data)).filter(".reacted");
            $parent.html(reactions);

            var reactionsData = jQuery(jQuery.parseHTML(data)).filter(".iqonic-meta-details");
            $superParent.replaceWith(reactionsData);

            jQuery('.reaction-wrapper').css("display", "none");
            jQuery('.user-reaction').hover(function () {
                jQuery('.reaction-wrapper').css("display", "flex");
            });
        },
        error: function (xhr) {
            
        }
    });
    jQuery('.reaction-option').removeClass('touch-hover');
}

function touchEndHandler(evt) {
    evt.preventDefault();
    const el = jQuery(this).closest('.iqonic-reaction').find('.reaction-wrapper');
    if(timer) {
        clearTimeout(timer);
    }
    el.removeClass("touch-active");
    el.css("display","none");
}

document.addEventListener("DOMContentLoaded", function() {
    touch_event('.user-reaction .reaction-button');
},false);

document.addEventListener("resize", function() {
    touch_event('.user-reaction .reaction-button');
},false);

jQuery(window).resize(function() {
    var width = jQuery(document).width();
    if (width > 1199) {
        var touchClass = document.querySelectorAll('.reaction-wrapper');
        touchClass.forEach(function(e) {
            e.classList.remove("touch-active");
            e.style = '';
        });
    }
});