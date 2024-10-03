// increate comment box height amd submit comment form on enter
function changeHeight(event) {
    var enteredValue = event.target.value;

    // Adjust textarea height dynamically
    event.target.style.height = "auto";
    event.target.style.height =(event.target.scrollHeight / 16) + "em";


    // Handle Enter key press (without Shift)
    if (event.keyCode == 13 && !event.shiftKey) {
        // Check if the current line index is at the end of entered content
        if (lineIndex === countLines(enteredValue) + 1) {
            // Handle form submission or other actions on Enter press
            if (enteredValue.trim() !== "") {
                event.target.closest("form.socialv-comment-form").querySelector('button.ac_form_submit').click();
            }
            event.stopPropagation();
        }
    }
}



/*------------------------
  Init activity swiper
--------------------------*/
function swiperSlider(swiperElements) {
	swiperElements.forEach(function (element) {
		var config = {
			spaceBetween: 16,
			pagination: {
				el: ".swiper-pagination",
				dynamicBullets: true,
				clickable: true
			},
		}
		var swiper = new Swiper(element, config);
		document.addEventListener('theme_scheme_direction', (e) => {
			swiper.destroy(true, true)
			setTimeout(() => {
				swiper = new Swiper(element, config);
			}, 500);
		})
	});

}

/*------------------
 hide more comment
-------------------*/
function socialv_legacy_theme_hide_comments() {
	// Select all div elements with the class 'activity-comments-list'
	var comments_divs = jQuery('div.activity-comments-list'),
		parent_li, comment_lis, comment_count, comments_div;

	// Check if there are no comments_divs
	if (!comments_divs.length) {
		return false; // Exit the function
	}

	// Iterate over each comments_div
	comments_divs.each(function () {
		// If the number of direct child li elements is less than 2, skip to the next comments_div
		if (jQuery(this).children('ul').children('li').length < 2) {
			return; // Skip to the next iteration
		}

		// Cache the current comments_div, parent_li, comment_lis, and comment_count
		comments_div = jQuery(this);
		parent_li = comments_div.parents('#activity-stream > li');
		comment_lis = jQuery(this).children('ul').children('li');
		comment_count = '';

		// Count the number of comments with a specific pattern in the parent_li
		if (jQuery('#' + parent_li.attr('id') + ' li[id*="acomment-"]').length) {
			comment_count = jQuery('#' + parent_li.attr('id') + ' li[id*="acomment-"]').length;
		}

		// Variable to keep track of visible parent comments
		var visibleParentCount = 0;

		// Iterate over each comment_li
		comment_lis.each(function (index) {
			// If visibleParentCount reaches 3, hide the current comment
			if (visibleParentCount >= 3) {
				jQuery(this).hide();
			} else {
				jQuery(this).show();
				visibleParentCount++;
			}

			// Add a button to show all comments if this is the first comment
			if (!index) {
				jQuery(this).closest(".activity-comments-list").prev().before('<a class="comment-info show-activity-comments" data-bs-toggle="modal" data-bs-target="#activityPopup" href="#' + parent_li.attr('id') + '/show-all/">' + BP_DTheme.show_x_comments.replace('%d', comment_count) + '</a>');
			}
		});
	});
}


/*------------------------
 execurt on dom loaded completely
--------------------------*/
function domLoadingComplete() {
	// activity-swiper
	var swiperElements = document.querySelectorAll('.socialv-swiper-slider:not(.swiper-initialized)');

	if (swiperElements.length > 0) {
		swiperSlider(swiperElements);
	}

	if (document.getElementById("activity-stream")) {
		document.getElementById("activity-stream").addEventListener("DOMNodeInserted", function (e) {
			if (e.target.tagName == "LI" && e.target.querySelector(".activity-content .swiper") != null) {
				var swiperElements = e.target.querySelectorAll(".activity-content .swiper");
				if (swiperElements.length > 0) {
					swiperSlider(swiperElements);
				}
			}
		});
	}

	//comment count
	if (document.querySelector(".activity-comments-list ul.activity-comments")) {
		document.querySelector(".activity-comments-list ul.activity-comments").addEventListener("DOMNodeRemoved", function (e) {
			if (e.target.tagName == "LI") {
				if (e.target.closest(".activity-comments-list").parentElement.querySelector("div.show-all-comments a")) {
					var comment_count = e.target.parentElement.querySelectorAll("li").length - 1;
					var showAllComments = e.target.closest(".activity-comments-list").parentElement.querySelector("div.show-all-comments a")
					if (comment_count < 3) {
						showAllComments.remove();
						[].forEach.call(e.target.parentElement.querySelectorAll("li"), function (a) {
							a.removeAttribute('style');
						});
						return;
					}
					showAllComments.textContent = BP_DTheme.show_x_comments.replace('%d', comment_count);
				}
			}
		});
	}

	document.addEventListener("click", function (e) {
		// scroll on new activity
		if (e.target.hash && e.target.hash == "#newest" && document.getElementById('buddypress')) {
			document.body.scrollTop = document.getElementById('buddypress').offsetTop;
			document.documentElement.scrollTop = document.getElementById('buddypress').offsetTop;
		}

		// show-all hidden comments
		const show_comment = (document.querySelector('.show-all-comments')) ? document.querySelector('.show-all-comments') : null;
		if (show_comment != null) {
			if (e.target.parentElement.classList.contains("show-all-comments")) {
				e.target.parentElement.style.display = "none";
				[].forEach.call(e.target.closest(".show-all-comments").parentElement.querySelectorAll("ul.activity-comments li"), function (a) {
					a.removeAttribute('style');
				});
			}
		}
	});

}

// check document ready state
document.addEventListener("readystatechange", function (e) {
	if (document.readyState == "complete") {
		domLoadingComplete();
	}
});

(function ($) {
	"use strict";
	$(document).ready(function () {
		/*------------------------
		comment reply form
	   --------------------------*/
		$(document).on("click", ".socialv-acomment-reply", function (e) {
			e.preventDefault();
			var formClone = '';

			var $this = $(this);
			var formID = $this.attr("comment-id");
			formClone = $this.closest('.socialv-activity-parent').find('form#' + formID).clone();

			// Remove the original form from its current position
			$this.closest('.socialv-activity-parent').find('form#' + formID).remove();

			if (!$this.hasClass("active")) {
				formClone.hide();
			} else {
				$this.removeClass("active");
				if (!formClone.is(":hidden")) {
					formClone.hide();
				}
			}
			if ($this.hasClass("main-comment")) {
				$this.closest('.socialv-activity-parent').find('.activity-comments.socialv-form').prepend(formClone);
			} else {
				$this.closest('.acomment-options').after(formClone);
			}

			$this.addClass("active");

			formClone.slideDown();

			var body = $("html, body");
			body.stop().animate({
				scrollTop: $(formClone).offset().top - 100
			}, 500);

			// formClone.find("textarea.ac-input").on("keyup", changeHeight);

			/* Add Gif icon in activity comment section*/
			if (jQuery('body').hasClass('buddypress-giphy-active')) {
				var activity_id = $(this).attr('id').split('-');
				if ($('#activity-' + activity_id[2] + ' .activity-comments #ac-form-' + activity_id[2] + ' .ac-reply-content .bp-giphy-comment-html-container').length === 0) {
					$('#activity-' + activity_id[2] + ' .activity-comments #ac-form-' + activity_id[2] + ' .ac-textarea').after('<div class="bp-giphy-comment-html-container bp-giphy-html-container"><div class="bp-giphy-media-search"><div class="bp-giphy-icon" title="' + bpgp_data.add_gif_text + '"><i class="wb-icons wb-icon-gif"></i></div><div class="bp-giphy-media-search-dropdown"></div></div></div>');
				}
			}
		});

		/* Add Gif icon in activity comment section on legacy template */
		if ($('body').hasClass('bp-legacy buddypress-giphy-active')) {
			$('div.activity').on('click', function (event) {
				var target = $(event.target);
				if (target.hasClass('socialv-acomment-reply') || target.parent().hasClass('socialv-acomment-reply')) {
					if (target.parent().hasClass('socialv-acomment-reply')) {
						target = target.parent();
					}
					var activity_id = target.attr('id').split('-');

					if ($('#activity-' + activity_id[2] + ' .activity-comments #ac-form-' + activity_id[2] + ' .ac-reply-content .bp-giphy-comment-html-container').length === 0) {
						$('#activity-' + activity_id[2] + ' .activity-comments #ac-form-' + activity_id[2] + ' .ac-textarea').after('<div class="bp-giphy-comment-html-container bp-giphy-html-container"><div class="bp-giphy-media-search"><div class="bp-giphy-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="' + bpgp_data.add_gif_text + '"><i class="wb-icons wb-icon-gif"></i></div><div class="bp-giphy-media-search-dropdown"></div></div></div>');
					}

				}
			});
		}

		// get users who liked activity
		$(document).on("click", ".socialv-get-liked-users", function (e) {
			e.preventDefault();
			var dataId = $(this).data("id");
			$.ajax({
				type: "GET",
				url: ajaxurl,
				data: {
					action: "socialv_activity_liked_users",
					id: dataId,
				},
				success: function (data) {
					if (data) {
						$('#liked-users-modal .modal-body').html(data);
						$('#liked-users-modal').modal('show');
					}
				}
			});
			return false;
		});

		// media select , delete
		$('.select-media-checkbox.multi').on('change', function (e) {
			var $inputs = $('.select-media-checkbox.single');
			if (e.originalEvent === undefined) {
				var allChecked = true;
				$inputs.each(function () {
					if (this.checked) {
						$(this).closest(".socialv-media-container").addClass("socialv-selected");
					} else {
						$(this).closest(".socialv-media-container").removeClass("socialv-selected");
					}
					allChecked = allChecked && this.checked;
				});
				this.checked = allChecked;
			} else {
				$inputs.prop('checked', this.checked);
				$inputs.closest(".socialv-media-container").removeClass("socialv-selected");
				if (this.checked) {
					$inputs.closest(".socialv-media-container").addClass("socialv-selected");
				}
			}
		});

		$('.select-media-checkbox.single').on('change', function () {
			$('.select-media-checkbox.multi').trigger('change');
		});

		$(".socialv-delete-media").stop().click(function (e) {
			e.preventDefault();
			if (!confirm(socialv_global_script.alert_media)) {
				return;
			}
			var $this = $(this);
			var hasType = "media";
			var dataIds = "";
			var is_multi = $this.hasClass("multi-delete") ? true : false;
			if (is_multi) {
				$('.select-media-checkbox.single[type=checkbox]:checked').each(function () {
					dataIds += (dataIds == "") ? $(this).val() : "," + $(this).val();
				});
			} else {
				dataIds = $this.data("id");
				hasType = "gallery";
			}

			$.ajax({
				type: "GET",
				dataType: "json",
				url: ajaxurl,
				data: {
					action: "user_delete_media",
					id: dataIds,
					type: hasType
				},
				success: function (data) {

					if (is_multi && data.status === true) {
						var ids = dataIds.split(",");
						$.each(ids, function (i) {
							$('.select-media-checkbox.single[value=' + ids[i] + ']').closest(".mpp-media").remove();
						});
					} else {
						$this.closest(".mpp-item").remove();
					}

				}
			});
			return false;
		});

		// comment
		// if (jQuery('div.activity-comments-list').length > 0) {	
		// 	 socialv_legacy_theme_hide_comments();
		// }

		// infinite scroll start
		var $window = $(window);
		// Check the window scroll event.
		$window.scroll(function () {
			ScrollGallery();
			var $load_more_btn = $('.load-more:visible');
			if (!$load_more_btn.get(0) || $load_more_btn.data('bpaa-autoloaded')) {
				return;
			}

			// Find the offset of the button.
			var pos = $load_more_btn.offset();
			var offset = pos.top - 3000; // 3000 px before we reach the button.

			// we have scrolled to the button's position. Let us load more activity.
			if ($window.scrollTop() + $window.height() > offset) {
				$load_more_btn.data('bpaa-autoloaded', 1);
				$load_more_btn.find('a').trigger('click');
			}

		});
		var target = $(target);
		if (target.parent().hasClass('load-more')) {
			if (bp_ajax_request) {
				bp_ajax_request.abort();
			}

			$('#buddypress li.load-more').addClass('loading');

			oldest_page = activity_oldestpage + 1;
			just_posted = [];

			$('.activity-list li.just-posted').each(function () {
				just_posted.push($(this).attr('id').replace('activity-', ''));
			});

			load_more_args = {
				action: 'activity_get_older_updates',
				'cookie': bp_get_cookies(),
				'page': oldest_page,
				'exclude_just_posted': just_posted.join(',')
			};

			load_more_search = bp_get_querystring('s');

			if (load_more_search) {
				load_more_args.search_terms = load_more_search;
			}
			bp_ajax_request = jq.post(ajaxurl, load_more_args,
				function (response) {
					$('#buddypress li.load-more').removeClass('loading');
					activity_oldestpage = oldest_page;
					var new_activities = $(response.contents);
					$('#buddypress ul.activity-list').append(new_activities);

					target.parent().hide();
					bp_legacy_theme_hide_comments(new_activities);
					new_activities = undefined;
					if (window.instgrm) {
						window.instgrm.Embeds.process();
					}
				}, 'json');

			return false;
		}
		// infinite scroll end

		// Gif Plugin 
		LoadbpGiphy();

		// Delete Post 
		$('div.activity').on('click', function (event) {
			var target = $(event.target);

			/* Delete activity stream items */
			if (target.hasClass('socialv_delete-activity')) {
				var li = target.parents('div.activity ul li');
				var id = li.attr('id').substr(9, li.attr('id').length);
				var link_href = target.attr('href');
				var nonce = link_href.split('_wpnonce=');
				var timestamp = li.prop('class').match(/date-recorded-([0-9]+)/);
				nonce = nonce[1];

				target.addClass('loading');

				// Display confirmation dialog
				var confirmDelete = confirm(socialv_global_script.alert_media);
				if (confirmDelete) {
					$.post(ajaxurl, {
						action: 'delete_activity',
						'cookie': bp_get_cookies(),
						'id': id,
						'_wpnonce': nonce
					},
						function (response) {

							if (response[0] + response[1] === '-1') {
								li.prepend(response.substr(2, response.length));
								li.children('#message').hide().fadeIn(300);
							} else {
								li.slideUp(300);
								var activityDiv = li.closest('.activity-item');
								var successMessage = target.data('success'); // Updated line
								// Create the success message HTML
								var successHTML = jQuery('<div id="template-notices" role="alert" aria-atomic="true">' +
									'<div id="message" class="bp-template-notice updated">' +
									'<p>' + successMessage + '</p>' +
									'</div>' +
									'</div>');

								// Insert the success message HTML after the activity div
								activityDiv.after(successHTML);

								// Remove the success message after 5 seconds
								setTimeout(function () {
									var messageDiv = jQuery('#message');
									if (messageDiv.length > 0) {
										messageDiv.parent().remove();
									}
								}, 5000);
								// reset vars to get newest activities
								if (timestamp && activity_last_recorded === timestamp[1]) {
									newest_activities = '';
									activity_last_recorded = 0;
								}
							}
						});
				}

				return false;
			}
		});
	});
}(jQuery));

/*------------------------
 like / pin activityheader height
--------------------------*/
document.addEventListener("click", function (event) {

	if (event.target.classList.contains("socialv-user-activity-btn")) {
		event.preventDefault();
		var dataId = event.target.getAttribute("data-id");
		var dataUnpin = event.target.getAttribute("data-unpin");
		var dataPin = event.target.getAttribute("data-pin");
		var thisElement = event.target;
		var key = "_socialv_activity_liked_users";
		var isPinnedActivity = thisElement.classList.contains("has-socialv-pin");
		var isPostActivity = thisElement.classList.contains("has-socialv-post");
		var dataType = event.target.getAttribute("data-type");
		var groupId = '';
		thisElement.classList.add("adding");
		thisElement.innerHTML = '<i class="icon-loader-circle"></i>';

		if (isPostActivity) {
			key = "_socialv_posts_liked_users";
		}

		if (isPinnedActivity) {
			key = event.target.getAttribute("component");
			if (dataType == "groups") {
				groupId = event.target.getAttribute("group-id");
			}
		}

		var xhr = new XMLHttpRequest();
		xhr.open("GET", ajaxurl + "?action=socialv_user_activity_callback&id=" + dataId + "&meta_key=" + key + "&data_type=" + dataType + "&group_id=" + groupId, true);
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.onload = function () {
			if (xhr.status === 200) {
				var response = JSON.parse(xhr.responseText);
				var data = response.data;
				if (!isPinnedActivity) {
					if (data) {
						thisElement.classList.remove("adding");
						var likeCount = '<span>' + data + '</span>';
						var existingContent = thisElement.innerHTML;
						thisElement.innerHTML = '<i class="iconly-Heart icbo"></i>' + existingContent + likeCount;
						thisElement.classList.add("liked");

						if (thisElement.classList.contains("added")) {
							thisElement.innerHTML = '<i class="iconly-Heart icli"></i>' + existingContent + likeCount;
							thisElement.classList.remove("added");
						} else {
							thisElement.innerHTML = '<i class="iconly-Heart icbo"></i>' + existingContent + likeCount;
							thisElement.classList.add("added");
						}
					} else {
						thisElement.classList.remove("adding");
						thisElement.classList.remove("liked");
						thisElement.innerHTML = '<i class="iconly-Heart icli"></i>' + thisElement.innerHTML;
					}
				} else {
					var iconElement = document.createElement("i");
					iconElement.className = "icon-pin pinicon me-3 mt-2";
					var liElement = event.target.parentNode.closest('ul');
					var aElement = liElement.previousElementSibling;
					if (data && data.status === true) {
						thisElement.classList.add("adding");
						thisElement.innerHTML = dataUnpin;
						thisElement.setAttribute("data-unpin", dataUnpin);
						thisElement.classList.add("added");
						aElement.parentNode.insertBefore(iconElement, aElement);
					} else {
						thisElement.innerHTML = dataPin;
						thisElement.setAttribute("data-unpin", dataUnpin);
						thisElement.classList.remove("added");
						var ulParentDiv = event.target.parentNode.closest('ul').parentNode;
						var iTagInsideDiv = ulParentDiv.querySelector('i.pinicon');
						if (iTagInsideDiv) {
							iTagInsideDiv.remove();
						}
					}
				}
			}
			thisElement.innerHTML = thisElement.innerHTML.replace('<i class="icon-loader-circle"></i>', ''); // Remove loading spinner HTML
		};
		xhr.send();
		return false;
	}
});

/*------------------------
  Add the AJAX script for the "Hide Post" button
--------------------------*/
document.addEventListener('DOMContentLoaded', function () {
	document.querySelector('body').addEventListener('click', function (event) {
		if (event.target.matches('.hide-post-btn')) {
			event.preventDefault();
			var activityItem = event.target.closest('.activity-item');
			var activity_id = event.target.dataset.activity_id;
			var user_id = event.target.dataset.id;
			var data_type = event.target.dataset.type;
			var data = {
				action: 'hide_activity_post',
				activity_id: activity_id,
				user_id: user_id,
				data_type: data_type
			};
			fetch(ajaxurl, {
				method: 'POST',
				body: new URLSearchParams(data)
			}).then(function (response) {
				return response.json();
			}).then(function (response) {
				if (data_type === 'hide') {
					activityItem.querySelector('.socialv_activity_inner').style.display = 'none';
					activityItem.querySelector('.undo_activity_post').style.display = 'block';
					if (response.success === true) {
						activityItem.style.display = 'block';
					}
				} else if (data_type === 'undo') {
					activityItem.style.display = 'block';
					activityItem.querySelector('.socialv_activity_inner').style.display = 'block';
					activityItem.querySelector('.undo_activity_post').style.display = 'none';
				}
			});
		}
	});
});


function LoadbpGiphy() {
	if (jQuery('.bp-giphy-icon').length > 0) {
		jQuery(document).on('click', '.bp-giphy-icon', function (e) {
			let dropDown = jQuery(this).next();
			jQuery(dropDown).addClass('show');
			if (jQuery('.bp-giphy-media-search-dropdown').not(dropDown).length != 0) {
				jQuery('.bp-giphy-media-search-dropdown').not(dropDown).each(function (key, el) {
					jQuery(el).removeClass('show');
				})
			}
		});
	}
}


//popup activity comment method
document.addEventListener("click", function (e) {
	if (e.target.getAttribute('name') === 'socialv_ac_form_submit') {
		e.preventDefault();

		let form = jq(e.target).closest('form');
		let form_id = form.attr('id').split('-');
		let comment_id;
		let form_parent = false;

		if (!form.parent().hasClass('activity-comments')) {
			let tmp_id = form.parent().attr('id').split('-');
			comment_id = tmp_id[1];
		} else {
			form_parent = true;
			comment_id = form_id[2];
		}

		let content = form.find('textarea').val();

		/* Hide any error messages */
		form.find('div.error').hide();
		jq(e.target).addClass('loading').prop('disabled', true);
		form.find('textarea').addClass('loading').prop('disabled', true);

		let ajaxdata = {
			action: 'new_activity_comment',
			'cookie': bp_get_cookies(),
			'_wpnonce_new_activity_comment': jq('#_wpnonce_new_activity_comment_' + form_id[2]).val(),
			'comment_id': comment_id,
			'form_id': form_id[2],
			'content': content
		};

		// Akismet
		let ak_nonce = jq('#_bp_as_nonce_' + comment_id).val();
		if (ak_nonce) {
			ajaxdata['_bp_as_nonce_' + comment_id] = ak_nonce;
		}

		jq.post(ajaxurl, ajaxdata, function (response) {
			jq(e.target).removeClass('loading');
			form.find('textarea').removeClass('loading');

			/* Check for errors and append if found. */
			if (response[0] + response[1] === '-1') {
				form.append(jq(response.substr(2, response.length)).hide().fadeIn(200));
			} else {

				let activity_comments = form.parent();

				if (form_parent == true) {
					let modal_footer = form.closest('.modal-footer');
					let modal_body = modal_footer.prev('.modal-body');
					activity_comments = modal_body.find('.activity-comments-list');
				}
				if (activity_comments.children('ul').length === 0) {
					activity_comments.append('<ul></ul>');
				}

				/* Preceding whitespace breaks output with jQuery 1.9.0 */
				let the_comment = jq.trim(response);
				activity_comments.children('ul').append(jq(the_comment).hide().fadeIn(200));
				activity_comments.parent().addClass('has-comments');

				// Scroll to the bottom of the container
				activity_comments.scrollTop(activity_comments.prop("scrollHeight"));

				// Clear textarea and re-enable form elements
				form.find('textarea').val('');
				jq(e.target).prop('disabled', false);
				form.find('textarea').prop('disabled', false);

				// Hide form if form_parent is false
				if (!form_parent) {
					form.hide();
				}

			}
		});

		return false;
	}
});



