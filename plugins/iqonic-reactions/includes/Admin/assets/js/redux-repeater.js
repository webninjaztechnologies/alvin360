jQuery(document).ready(function () {
    "use strict";

    setTimeout(function () {
        jQuery(".redux-repeaters-remove").off("click");
    }, 1000);

    jQuery(document).on('click', '.redux-repeaters-remove', function (event) {
        var $this = jQuery(this);
        var $repeater = $this.closest('.redux-field');
        var reaction_name = $repeater.children().find('.bind_title').val();
        if (confirm('Are you sure you want to delete the reaction "' + reaction_name + '"? Deleting this reaction, will also delete it data from the database. The deleted data will not be recovered. Be careful while deleting')) {
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    "action": "iqonic_delete_reaction_notification",
                    "reaction_name": reaction_name
                },
                success: function (data) {
                   
                }
            });
            
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    "action": "iqonic_delete_reaction_from_database",
                    "reaction_name": reaction_name
                },
                success: function (data) {
                    
                    var mainParent = jQuery(event.target).closest('.redux-repeater-accordion-repeater');
                    mainParent.remove();

                    jQuery.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {
                            "action": "iqonic_delete_reaction_from_reaction_list",
                            "reaction_name": reaction_name
                        },
                        success: function (data) {
                           
                            alert('Successfully Deleted Reaction ' + reaction_name);
                            
                            if (jQuery('.redux-repeater-accordion-repeater').length < 7) {
                                jQuery('.redux-repeaters-add').removeClass('button-disabled');
                            }
        
                            jQuery('.redux-save-warn').css("display", "block");
                        }
                    });
                },
                error: function (xhr) {
                   
                    alert('There seems to be some problem deleting the reaction. Please try again after some time.');
                },
            });
        }
    });

    jQuery('#redux_top_save, #redux_bottom_save').click(function () {
        setTimeout(function () {
            let opt_name = jQuery('.redux-form-wrapper').data('opt-name');
            let redux_all_reaction_names = document.querySelectorAll('#ir_options-reactions_field .redux-field .bind_title');
            let redux_all_reaction_images = document.querySelectorAll('#ir_options-reactions_field .upload_button_div');
            
            const reaction_name_arr = [];
            const reaction_image_arr = [];

            redux_all_reaction_names.forEach(element => {
                var reaction_name = jQuery(element).val();
                reaction_name_arr.push(reaction_name);
            });

            redux_all_reaction_images.forEach(element => {
                var reaction_image = jQuery(element).find('.redux-option-image').attr('src');
                if(jQuery(element).find('.screenshot')[0].style.display != 'none' && reaction_image.length > 0) {
                    reaction_image_arr.push(reaction_image);
                } else {
                    reaction_image_arr.push('');
                }
            });

            if (opt_name == "ir_options") {
                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        "action": "iqonic_update_reaction_list",
                        "reaction_name": reaction_name_arr,
                        "reaction_image": reaction_image_arr,
                    },
                    success: function (data) {
                        
                    }
                });
            }
        }, 1000);
    });
});