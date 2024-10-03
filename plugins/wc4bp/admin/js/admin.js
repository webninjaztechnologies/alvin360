function wc4bpAdministration() {

	function wc4bp_update_user() {
		wc4bp_this_user_pages++;
		jQuery.ajax({
			async: false,
			type: 'POST',
			url: ajaxurl,
			data: {'action': 'wc4bp_shop_profile_sync_ajax', 'visibility_level': visibility_level, 'update_type': update_type, 'wc4bp_page': wc4bp_this_user_pages,'nonce':wc4bp_admin_js.nonce},
			success: function(data) {
				jQuery('#result').html(data);
			},
			error: function() {
				alert('Something went wrong.. ;-(sorry)');
			},
		});
		if (wc4bp_total_user_pages > wc4bp_this_user_pages) {
			window.setTimeout(function() {
				wc4bp_update_user();
			}, 0);
		}
		if (wc4bp_total_user_pages == wc4bp_this_user_pages) {
			jQuery('#result').html('<h2 style="color: green;">All Donne! Update Complete ;)</h2>');
		}
	}

	function continue_update_paged() {
		wc4bp_total_user_pages = jQuery('#wc4bp_total_user_pages').val();
		wc4bp_this_user_pages = jQuery('#continue_update_paged').val();
		wc4bp_update_user();
	}

	function wc_bp_sync_all_user_data() {
		jQuery('#result').html('');
		update_type = jQuery(this).attr('id');
		visibility_level = jQuery('#' + update_type + '_options').val();

		wc4bp_total_user_pages = jQuery('#wc4bp_total_user_pages').val();
		wc4bp_this_user_pages = 0;
		wc4bp_update_user();
	}

	

	return {
		init: function() {
			jQuery(function() {
				jQuery('#tabs').tabs();
			});

			var continueUpdatePaged = jQuery('#continue_update_paged');
			if (continueUpdatePaged.length > 0) {
				continueUpdatePaged.click(continue_update_paged);
			}

			var syncAllUserData = jQuery('.wc_bp_sync_all_user_data');
			if (syncAllUserData.length > 0) {
				syncAllUserData.click(wc_bp_sync_all_user_data);
			}

			
		},
	};
}

var wc4bpImplementation = wc4bpAdministration();
jQuery(document).ready(function() {
	wc4bpImplementation.init();
});

