/**
 * File ajax-custom.js.
 */

/*------------------------
 Ajax Search
 --------------------------*/
if (typeof iqSearchInputs == "undefined") {
	let iqSearchInputs = document.getElementsByClassName('ajax_search_input');
	for (let ind = 0; ind < iqSearchInputs.length; ind++) {
		let element = iqSearchInputs[ind];

		element.addEventListener('keyup', function (event) {
			getAjaxSearch(event);
		});

	}
}
var getAjaxSearch = _.debounce(function (event) {
	let _this = event.target;
	var search = _this.value;
	if (search.length > 3) {
		_this.closest('.header-search').querySelector('.socialv-search-result').classList.remove("search-result-dislogbox");
		var formData = new FormData();
		//activity data
		formData.append("action", "ajax_search_content");
		formData.append("keyword", search);
		let request_act = new XMLHttpRequest();
		request_act.open('POST', socialv_loadmore_params.ajaxurl, true);
		request_act.onload = function () {
			if (this.status >= 200 && this.status < 400) {
				// Success!
				var resp = JSON.parse(this.response)['data']['content'];
				var details = JSON.parse(this.response)['data']['details'];

				_this.closest('.header-search').querySelector('.socialv-search-activity-content').innerHTML = resp;
				if (typeof details !== "undefined") {
					_this.closest('.header-search').querySelector('.item-footer').innerHTML = details;
				} else {
					_this.closest('.header-search').querySelector('.item-footer').innerHTML = "";
				}
			} else {
				_this.closest('.header-search').querySelector('.socialv-search-activity-content').innerHTML = "";
			}
		};

		request_act.onerror = function () {
			_this.closest('.header-search').querySelector('.socialv-search-activity-content').innerHTML = "";

		};

		request_act.onprogress = function () {
			var resp = '<li><span class="socialv-loader"></span></li>';
			_this.closest('.header-search').querySelector('.socialv-search-activity-content').innerHTML = resp;
		};

		request_act.send(formData);

	} else {
		_this.closest('.header-search').querySelector('.socialv-search-result').classList.add("search-result-dislogbox");
	}
}, 500);

document.addEventListener('DOMContentLoaded', function () {
	const searchResult = document.querySelector('.socialv-search-result');
	// Remove search results when the user clicks outside the container
	if (searchResult !== (null && undefined)) {
		document.addEventListener('click', function (event) {
			const target = event.target;
			if (!searchResult.contains(target)) {
				searchResult.classList.add('search-result-dislogbox');
			}
		});
	}
});

/*------------------------
 accept/reject friend request
 --------------------------*/
document.addEventListener("DOMContentLoaded", function () {
	var friendshipButtons = document.querySelectorAll(".socialv-friendship-btn");
	friendshipButtons.forEach(function (button) {
		button.addEventListener("click", function (e) {
			e.preventDefault();
			e.stopPropagation();
			var $this = this,
				friendshipId = $this.getAttribute("data-friendship-id"),
				dataAction = $this.classList.contains("accept") ? "friends_accept_friendship" : "friends_reject_friendship";
			var xhr = new XMLHttpRequest();
			xhr.open("POST", ajaxurl);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.onreadystatechange = function () {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					if (xhr.status === 200) {
						var data = JSON.parse(xhr.responseText);
						var response = data.data.feedback;
						$this.closest(".socialv-friend-request").querySelector(".response").innerHTML = response;
						if (data.success)
							$this.closest(".request-button").remove();
						updateConfirmCount(); // Update the confirm count
					} else {
						console.error("An error occurred during the AJAX request.");
					}
				}
			};
			xhr.send("action=socialv_ajax_addremove_friend&friendship_id=" + friendshipId + "&data_action=" + dataAction);
			return false;
		});
	});
	// Function to update the confirm count
	function updateConfirmCount() {
		var countElement = document.getElementById("notify-count");
		if (countElement) {
			var currentCount = parseInt(countElement.textContent);
			if (currentCount > 1) {
				countElement.textContent = currentCount - 1;
			} else {
				countElement.textContent = "";
				countElement.classList.remove("notify-count");
			}
		}
	}
});

/*------------------------
 Toggle Remove On Click
 --------------------------*/
document.addEventListener("click", function (e) {
	var btnDropdown = e.target.closest(".btn-dropdown");
	if (btnDropdown) {
		var sharingOptions = document.querySelectorAll('.sharing-options');
		if (sharingOptions.length !== 0) {
			sharingOptions.forEach(function (option) {
				option.classList.remove('open');
			});
		} else {
			var serviceButtonsList = document.querySelectorAll('.service-buttons');
			serviceButtonsList.forEach(function (option) {
				option.style.display = 'none';
			});
		}
	}

	var dropdownToggle = e.target.closest(".socialv-header-right .dropdown-toggle");
	if (dropdownToggle) {
		var searchResult = document.querySelector('.socialv-search-result');
		searchResult.classList.add('search-result-dislogbox');
	}
});

/*------------------------
 Share activity Post
 --------------------------*/
document.addEventListener('click', function (e) {
	var target = e.target;

	while (target) {
		if (target.classList.contains('share-btn')) {

			e.preventDefault();
			var option = target.parentElement.querySelector('.sharing-options');
			if (option) {
				if (option.classList.contains('open')) {
					document.querySelectorAll('.sharing-options').forEach(function (elem) {
						elem.classList.remove('open');
					});
				} else {
					document.querySelectorAll('.sharing-options').forEach(function (elem) {
						elem.classList.remove('open');
					});
					option.classList.add('open');
				}
			}

			break; // Stop searching once a 'share-btn' element is found
		}


		if (target.classList.contains('bp-share-btn') && target.classList.contains('generic-button')) {

			e.preventDefault();
			var serviceButtonsList = document.querySelectorAll('.service-buttons');
			serviceButtonsList.forEach(function (serviceButtons) {
				serviceButtons.style.display = 'none';
			});

			// Show the nearest "service-buttons" element to the clicked "generic-button"
			var serviceButtonssss = target.closest('.socialv-activity_comment').nextElementSibling;
			if (serviceButtonssss) {
				serviceButtonssss.style.display = 'block';
			}
			break;
		}
		target = target.parentElement;
	}
});


/*------------------------------
 Mark All Notification To Read
 ------------------------------*/
jQuery(document).ready(function ($) {
	$('#read_all_notification').on('click', function () {
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'socialv_read_all_notification',
			},
			success: function (response) {
				location.reload();
			},
			error: function (error) {
				location.reload();
			},
		});
	});
});

/*------------------------------
 Copy activity URl
------------------------------*/

document.addEventListener("click", function (e) {
	var htmlContent;
	if (e.target.classList.contains("socialv_copy_url")) {
		var hrefValue = e.target.getAttribute("data_url");
		htmlContent = e.target.outerHTML;
		var elemetns = e.target;
	}
	if (e.target.classList.contains("icon-copying")) {
		var parentElement = e.target.parentElement;
		var hrefValue = parentElement.getAttribute("data_url");
		htmlContent = parentElement.outerHTML;
		var elemetns = e.target.parentElement
	}
	if (hrefValue) {
		var parent_element = elemetns.parentElement;
		var loading_icons = '<i class="icon-loader-circle"></i>';
		parent_element.innerHTML = loading_icons;

		// Use Clipboard API if supported
		var textarea = document.createElement("textarea");
		textarea.value = hrefValue;
		document.body.appendChild(textarea);
		textarea.select();
		document.execCommand('copy');
		document.body.removeChild(textarea);

		setTimeout(function () {
			var ok_icon = '<i class="icon-circle-check"></i>';
			parent_element.innerHTML = ok_icon;
		}, 2000);

		setTimeout(function () {
			parent_element.innerHTML = htmlContent;
		}, 3000);
	}
});


/*-----------------------------------
 Activity popup on view more comment
-------------------------------------*/
document.addEventListener("click", function (e) {
	if (e.target.classList.contains("show-activity-comments")) {
		e.preventDefault();
		var activityId = e.target.getAttribute("activity_id");
		let outerpopup = document.querySelector('.activitypopup .modal-dialog .modal-content');
		let popup = document.querySelector('.activitypopup .modal-dialog .modal-content .modal-body');
		let popupHeaderbox = document.querySelector('.activitypopup .modal-dialog .modal-content .modal-header');
		let popupHeader = document.querySelector('.activitypopup .modal-dialog .modal-content .modal-header .modal-user-name');
		let popupFooter = document.querySelector('.activitypopup .modal-dialog .modal-content .modal-footer');

		outerpopup.classList.add('loading-popup');
		

		// Clear the popup content before appending the new activity
		while (popup.firstChild) {
			popup.removeChild(popup.firstChild);
		}
		popupHeader.innerHTML = '';
		popupFooter.innerHTML = '';


		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'socialv_get_popup_activity',
				activity_id: activityId,
			},
			dataType: 'json',
			success: function (response) {
				let activityHtml = response.data.activity_html;

				let activityHtmlObj = jQuery(activityHtml);

				activityHtmlObj.find('[name="ac_form_submit"]').attr('name', 'socialv_ac_form_submit');

				// Find the footer element
				let activityFooterElement = activityHtmlObj.find('.socialv-form');

				// Store the outerHTML of the footer content
				let activityFooter = activityFooterElement.prop('outerHTML');


				// Now activityHtmlObj only contains the content without the footer
				let activityHeader = activityHtmlObj.find('.activity-header p').prop('outerHTML');

				// Get the outerHTML of the remaining content
				let remainingActivityHtml = activityHtmlObj.prop('outerHTML');
				outerpopup.classList.remove('loading-popup');

				popupHeader.insertAdjacentHTML('beforeend', activityHeader);


				popup.insertAdjacentHTML('beforeend', remainingActivityHtml);

				// Append activityFooter to popupFooter
				popupFooter.innerHTML = activityFooter;

				// Find the form in the footer and remove display: none, add display: block
				let formInFooter = popupFooter.querySelector('form');
				if (formInFooter) {
					formInFooter.style.display = 'block';
				}

			},
			error: function (error) {
				console.error('Error occurred:', error.responseText);
			},
		});
	}
});


/*----------------------------------------
 share on activity click open popup card 
------------------------------------------*/
jQuery(document).ready(function ($) {
	$(document).on('click', 'a.share_activity-share, i.icon-share-box', function (e) {
		e.preventDefault();

		var target = e.target;
		var post_id = target.getAttribute('data-post-id');

		let popup = $('.shareactivitypopup .modal-body .share_activity-content');
		let popupHeader = $('.shareactivitypopup .modal-header .modal-user-name');
		let popupFooter = $('.shareactivitypopup .modal-footer');
		$('#shareactivitypopup').modal('show');

		// Clear the popup content before appending the new activity
		popup.empty();
		popupHeader.text('');
		// popupFooter.empty();

		popup.addClass('loading-popup');
		// popupHeader.addClass('loading-popup');
		// popupFooter.classList.add('skeleton');

		// AJAX request to fetch activity details
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'socialv_share_on_activity_click',
				post_id: post_id
			},
			success: function (response) {

				if (response.success) {

					let activityHtml = response.data.activity_html;
					let activityHtmlObj = $(activityHtml);

					// Form in header
					let activityHeaderElement = activityHtmlObj.find('.socialv-form');
					activityHeaderElement.find('.send-comment-btn .ac_form_submit').remove();

					let activityHeader = activityHeaderElement.prop('outerHTML');

					// Header of the post
					activityHtmlObj.find('.socialv-activity-header-right').remove();
					let postHeader = activityHtmlObj.find('.socialv-activity-header').prop('outerHTML');
					// Append the header and content of the post to the popup body
					// popupHeader.removeClass('loading-popup');
					popupHeader.html(postHeader + activityHeader);

					// Content of the post
					let postContent = activityHtmlObj.find('.activity-content').clone(); // Clone to preserve original
					postContent.find('.socialv-meta-details, .socialv-comment-main').remove();

					// Append the content of the post to the popup body
					popup.removeClass('loading-popup');

					popup.append(postContent);

					// Append the text field for comment-input
					// let textFieldHtml = '<br><div class="comment-textfield"><input type="text" id="comment-input" placeholder="Add a comment"></div>';
					// popup.append(textFieldHtml);

					// // Show the share button in the footer
					// popupFooter.html('<button id="share-btn" class="btn btn-primary repost-share-btn">Share</button>');

					// Show the popup
					$('#share-btn').data('post-id', post_id);

					// Check if media content is already appended
					if (popup.find('.activity-media').length === 0) {
						// Append the media content to the popup if it exists
						let mediaContent = response.data.media_meta;
						if (mediaContent) {
							popup.append(mediaContent);
						}
					}
				} else {
					console.log('Error: ' + response.data.message);
				}
			},
			error: function (xhr, status, error) {
				console.log("Error fetching activity details: " + error);
			}
		});
	});

	$(document).on('click', '.socialv-reshare-post', function (e) {
		e.preventDefault();

		var post_id = $(this).data('post-id');
		var commentText = $('#comment-input').val(); // Get the comment text

		// AJAX request to handle share button click
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'socialv_post_share_activity',
				activity_id: post_id,
				commentText: commentText
			},
			success: function (response) {
				$('#shareactivitypopup').modal('hide');
			},
			error: function (xhr, status, error) {
				console.error("Error reposting activity: " + error);
				$('#shareactivitypopup').modal('hide');
			}

		});
	});
});


