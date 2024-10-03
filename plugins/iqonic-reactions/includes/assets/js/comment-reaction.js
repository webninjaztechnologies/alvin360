(function (jQuery) {
    "use strict";
    add_default_comment_reaction();
    remove_comment_reaction();
    add_comment_reaction();
    get_comment_reaction_box_data();
    socialv_reaction_wrapper();

    window.addEventListener('resize', function () {
        enableSliderNav();
    });

})(jQuery);

function socialv_reaction_wrapper() {
var userCommentReactions = document.querySelectorAll('.user-comment-reaction');
// Loop through each selected element and add a 'mouseover' event listener
userCommentReactions.forEach(function (element) {
    element.addEventListener('mouseover', function () {
        // Select all elements with the class 'reaction-wrapper' and change their CSS display property
        var reactionWrappers = document.querySelectorAll('.reaction-wrapper');
        reactionWrappers.forEach(function (wrapper) {
            wrapper.style.display = "flex";
        });
    });
});
}

function add_default_comment_reaction() {
    const $activityStream = jQuery('#activity-stream');

    function handleReactionClick() {
        const $this = jQuery(this);
        const $parent = $this.parent();
        const $superParent = $parent.closest('.comment-container-main').find('.ir-comment-box');

        const comment_user_id = $this.data("user_id");
        const activity_id = $this.data("activity_id");
        const reaction_id = $this.data("reaction_id");
        const comment_id = $this.data("comment_id");

        const data = {
            'action': "iqonic_add_comment_reaction",
            'activity_id': activity_id,
            'comment_user_id': comment_user_id,
            'reaction_id': reaction_id,
            'comment_id': comment_id,
        };

        jQuery.ajax({
            url: ir_reaction_ajax_params.ajaxUrl,
            data: data,
            type: 'POST',
            success: function (data) {
                const $parsedData = jQuery(jQuery.parseHTML(data));
                const reactions = $parsedData.filter(".reacted");
                const reactionsData = $parsedData.filter(".ir-comment-box");

                $parent.html(reactions);
                $superParent.replaceWith(reactionsData);

                jQuery('.reaction-wrapper').css("display", "none");
                jQuery('.user-comment-reaction').hover(function () {
                    jQuery('.reaction-wrapper').css("display", "flex");
                });
            },
        });
    }

    $activityStream.on("click", ".user-comment-reaction .reaction-button", _.debounce(handleReactionClick, 250));
}


function add_comment_reaction() {
    jQuery('#activity-stream').on("click", ".comment-reaction .reaction-option", function() {

        let $this = jQuery(this);
        let $parent = $this.parent();
        let $replace = $parent.closest('.comment-reaction').find('.user-comment-reaction');
        let $superParent = $parent.closest('.comment-container-main').find('.ir-comment-box');

        // Simplify data extraction
        let data = {
            'action': "iqonic_add_comment_reaction",
            'activity_id': $parent.data("activity_id"),
            'comment_user_id': $parent.data("user_id"),
            'reaction_id': $this.data("reaction_id"),
            'comment_id': $parent.data("comment_id"),
            'table_id': $replace.find('.reacted').data("table_id"),
        };

        jQuery.ajax({
            url: ir_reaction_ajax_params.ajaxUrl,
            data: data,
            type: 'POST',
            success: function (data) {
                // Use cached jQuery object to avoid multiple parsing
                var $parsedData = jQuery(jQuery.parseHTML(data));

                // Update both elements in one step
                $replace.html($parsedData.filter(".reacted"));
                $superParent.replaceWith($parsedData.filter(".ir-comment-box"));

                // Cache jQuery objects for better performance
                var $reactionWrapper = jQuery('.reaction-wrapper');
                var $userCommentReaction = jQuery('.user-comment-reaction');

                $reactionWrapper.css("display", "none");
                $userCommentReaction.hover(function () {
                    $reactionWrapper.css("display", "flex");
                });
            },
        });
    });
}


function remove_comment_reaction() {
    jQuery('#activity-stream').on("click", ".user-comment-reaction .reacted", function() {

        let $this = jQuery(this);
        let $parent = $this.parent();
        let $superParent = $parent.closest('.comment-container-main').find('.ir-comment-box');

        let comment_user_id = jQuery(this).data("user_id");
        let activity_id = jQuery(this).data("activity_id");
        let comment_id = jQuery(this).data("comment_id");
        
        let data = {
            'action' : "iqonic_delete_comment_reaction_activity",
            'activity_id': activity_id,
            'comment_user_id': comment_user_id,
            'comment_id': comment_id,
        };

        jQuery.ajax({
            url : ir_reaction_ajax_params.ajaxUrl,
            data : data,
            type: 'POST',
            success: function(data) {
                var reactions = jQuery(jQuery.parseHTML(data)).filter(".reaction-button");
                $parent.html(reactions);

                var reactionsData = jQuery(jQuery.parseHTML(data)).filter(".ir-comment-box");
                $superParent.replaceWith(reactionsData);

                jQuery('.user-comment-reaction').hover(function () {
                    jQuery('.reaction-wrapper').css("display", "flex");
                });

                touch_event('.user-comment-reaction .reaction-button');
            },
        });
    });
}

function get_comment_reaction_box_data() {
    // Use event delegation for better performance
    jQuery('#activity-stream').on("click", ".comment-container-main .emoji-reaction, .comment-container-main .other-content", function() {
        const $this = jQuery(this);
        const activity_id = $this.data("activity_id");
        const comment_id = $this.data("comment_id");
        const data = {
            action: "iqonic_comment_reaction_box",
            activity_id,
            comment_id,
        };

        jQuery.ajax({
            url: ir_reaction_ajax_params.ajaxUrl,
            data: data,
            type: 'POST',
            success: function (data) {
                // Cache the body element
                const $body = jQuery('body');
                
                // Append the data to the body once
                $body.append(data);
                
                // Cache the modal element
                const $reactionModal = jQuery('.ir-reaction-modal');
                
                // Add the "active" class
                $reactionModal.addClass("active");

                // Call other functions
                commet_reaction_box();
                enableSliderNav();
            },
        });
    });
}


function commet_reaction_box() {
    
    jQuery('.emoji-reaction').on("click", function() {
        jQuery('.ir-reaction-modal').addClass("active");
    });
    
    jQuery('.ir-modal-centered .popup_close-button').on("click", function() {
        jQuery('.ir-reaction-modal').removeClass("active");
        jQuery('.ir-reaction-modal').remove();
    });

    jQuery('.ir-option').click(function() {
        jQuery('.ir-option').removeClass('active');
        jQuery(this).addClass('active');
    });

    jQuery('.user-comment-reaction').hover(function () {
        jQuery('.reaction-wrapper').css("display", "flex");
    });

    jQuery('.ir-reaction-modal.ir-comment-box.active .ir-option').click(function() {
        let $this = jQuery(this);
        let $parent = $this.closest('.ir-options');

        let reaction_id = $this.data("reaction_id");
        let activity_id = $parent.data("activity_id");
        let comment_id = $parent.data("comment_id");

        let data = {
            'action' : "iqonic_comment_grouped_reaction",
            'activity_id': activity_id,
            'comment_id': comment_id,
            'reaction_id': reaction_id,
        };

        jQuery.ajax({
            url : ir_reaction_ajax_params.ajaxUrl,
            data : data,
            type: 'POST',
            success: function(data) {
                jQuery('.ir-reaction-card-wrapper').html(data);
            },
        });
    });

    jQuery('body').on("click", function(e) {
        if(!jQuery(e.target).closest('.ir-box').length) {
            jQuery('.ir-reaction-modal').remove();
        }
    });
}

document.addEventListener("DOMContentLoaded", function() {
    touch_event('.user-comment-reaction .reaction-button');
},false);

document.addEventListener("resize", function() {
    touch_event('.user-comment-reaction .reaction-button');
},false);