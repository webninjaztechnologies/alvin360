jQuery(document).ready(function($){
    
    // Show loader
    function showLoader(){
        $('#page_loader').css('opacity', 1);
    }
    
    
    // Hide loader
    function hideLoader(){
        $('#page_loader').css('opacity', 0);
    }
    // showLoader();
   
    
        // Cache jQuery objects for performance
        const $fileInput = $('#profilePic');
        const $fileNameDisplay = $('#profilepicID');
        const $profilePicLabel = $('#profilePicLabel');

        const $uploadInput = $('#browserFile');
        const $uploadDisplay = $('#browserID');
        const $browserFileLabel = $('#browserFileLabel');

        // Event handler for profile picture file input change
        $fileInput.on('change', function() {
            const files = $fileInput.prop('files'); // Get the files property

            if (files.length > 0) {
                // Get the name of the first selected file
                const fileName = files[0].name;
                // Display the file name
                $fileNameDisplay.html(`${fileName}`);

                // Change the h3 text to 'UPLOAD SUCCESSFULLY'
                $profilePicLabel.text('UPLOAD SUCCESSFULLY');
             } else {
                // If no file selected, reset text
                $fileNameDisplay.text('');
                $profilePicLabel.text('UPLOAD PROFILE PICTURE');
             }
        });

        // Event handler for portfolio file input change
        $uploadInput.on('change', function() {
            const files = $uploadInput.prop('files'); // Get the files property

            if (files.length > 0) {
                // Get the name of the first selected file
                const fileName = files[0].name;
                // Display the file name
                $uploadDisplay.html(`${fileName}&nbsp;&nbsp;X`);

                // Change the h3 text to 'UPLOAD SUCCESSFULLY'
                $browserFileLabel.text('UPLOAD SUCCESSFULLY');
             } else {
                // If no file selected, reset text
                $uploadDisplay.text('');
                $browserFileLabel.text('UPLOAD YOUR PORTFOLIO');
             }
        });

        // Optional: Click the image to trigger file input
        $('#uploadImage1').on('click', function() {
            $fileInput.trigger('click');
        });

        $('#uploadImage2').on('click', function() {
            $uploadInput.trigger('click');
        });

// sjhdbfdhjf÷


// phone number vakidation

$('#phone_number').on('keypress', function (e) {
    var input = $(this).val();
    input = input.replace(/\D/g, ''); // Remove all non-numeric characters

    if (input.length >= 10) {
      e.preventDefault(); // Prevent further input if 10 digits are already entered
    }
  });

  $('#phone_number').on('keyup', function () {
    var input = $(this).val();
    input = input.replace(/\D/g, ''); // Remove all non-numeric characters

    var formattedInput = '';

    if (input.length > 0) {
      formattedInput += '(' + input.substring(0, 3);
    }
    if (input.length > 3) {
      formattedInput += ') ' + input.substring(3, 6);
    }
    if (input.length > 6) {
      formattedInput += ' ' + input.substring(6, 10);
    }

    $(this).val(formattedInput);
  });


// tooltip ==================================

$(document).ready(function() {
    $('#invitation-heading').hover(
    function() {
        console.log('inside');
        $('#tooltip-block-heading').show();  // Show the div on hover
    },
    function() {
                console.log('outside');

        $('#tooltip-block-heading').hide();  // Hide the div when no longer hovering
    }
);

    $('#hover-for-email').hover(
    function() {
        $('#tooltip-block-email').show();  // Show the div on hover
    },
    function() {
        $('#tooltip-block-email').hide();  // Hide the div when no longer hovering
    }
);

$('#hover-for-incitatincode').hover(
    function() {
        $('#tooltip-block-invitatincode').show();
    },
    function() {
        $('#tooltip-block-invitatincode').hide();
    }
);

// Code for 2nd step user information

$('#user-information-heading').hover(
    function() {
        $('#tooltip-user-heading').show();
    },
    function() {
        $('#tooltip-user-heading').hide();
    }
);

$('#hover-for-firstname').hover(
    function() {
        $('#tooltip-user-firstname').show();
    },
    function() {
        $('#tooltip-user-firstname').hide();
    }
);

$('#hover-for-lastname').hover(
    function() {
        $('#tooltip-user-lastname').show();
    },
    function() {
        $('#tooltip-user-lastname').hide();
    }
);

$('#hover-for-username').hover(
    function() {
        $('#tooltip-user-username').show();
    },
    function() {
        $('#tooltip-user-username').hide();
    }
);

$('#hover-for-phonenumber').hover(
    function() {
        $('#tooltip-user-phonenumber').show();
    },
    function() {
        $('#tooltip-user-phonenumber').hide();
    }
);

$('#hover-for-password').hover(
    function() {
        $('#tooltip-user-password').show();
    },
    function() {
        $('#tooltip-user-password').hide();
    }
);

$('#hover-for-confirm-pass').hover(
    function() {
        $('#tooltip-user-confirmpassword').show();
    },
    function() {
        $('#tooltip-user-confirmpassword').hide();
    }
);

});

//  ============================================

jQuery(document).ready(function($) {
    $('.user-carousel').slick({
        slidesToShow: 4,  
        slidesToScroll: 1,  
        autoplay: true,  
        autoplaySpeed: 2000,  
        dots: false,  
        arrows: false,  
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
});
// ==============================





// Select the file input and label
const fileInput = document.getElementById('file-upload');
const fileLabel = document.getElementById('file-upload-label');
const fileNameDisplay = document.getElementById('file-upload-name');

// Event listener for file input change
fileInput.addEventListener('change', function() {
    const files = fileInput.files;

    if (files.length > 0) {
        // Get the name of the first selected file
        const fileName = files[0].name;

        // Display the file name in the paragraph element
        fileNameDisplay.textContent = `${fileName}`;
        // $fileNameDisplay.html(`${fileName}&nbsp;&nbsp;X`);


        // Change the label text to 'UPLOADED'
        fileLabel.textContent = 'UPLOADED';
     } else {
        // If no file selected, reset the label and text
        fileNameDisplay.textContent = '';
        fileLabel.textContent = 'UPLOAD FILE';
     }
});



// Ensure that gamipress_ajax_object is properly defined
if (typeof gamipress_ajax_object === 'undefined') {
    console.error('gamipress_ajax_object is not defined');
}

// multistep form credit points 
let currentStep = 1;
let totalPoints = 0;

function nextStep(step) {
    // Increment step and points
    currentStep = step + 1;
    totalPoints += 20;
    
    // Update points display for the next step
    const pointsDisplayElement = document.getElementById(`pointsStep${currentStep}`);
    if (pointsDisplayElement) {
        pointsDisplayElement.innerText = totalPoints;
    }

    // Show alert with the points awarded
    if(step >= 1 && step < 4){
    alert(`You have earned ${totalPoints} points so far!`);
}
    
 
    // Send points to the server
    jQuery.ajax({
        url: gamipress_ajax_object.ajaxurl,  
        type: 'POST',
        data: {
            action: 'award_points',
            points: totalPoints,
            nonce: gamipress_ajax_object.nonce
        },
        success: function(response) {
            if (response.success) {
                console.log('Points awarded successfully');
            } else {
                console.log('Failed to award points');
            }
        },
            error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
}

// Adding event listeners to each button

// document.getElementById('validate_invitation_code').addEventListener('click', function(event) {
//     event.preventDefault();  
//     nextStep(1);
// });

// document.getElementById('submit_details-btn').addEventListener('click', function(event) {
//     event.preventDefault(); 
//     nextStep(2); 
// });

// document.getElementById('add_to_group_btn').addEventListener('click', function(event) {
//     event.preventDefault();  
//     nextStep(3);  
// });
 
console.log('hello page'); 
    
    
    // new js code 
$('#validate_invitation_code').on('click', function (event) {
    event.preventDefault(); // Prevent the default form submission behavior
    localStorage.clear();
    
    // Get input values
    let emailAddress = $('#user_email').val();
    let invitationCodeField = $('#invitation_code');
    let invitationCode = invitationCodeField.length ? invitationCodeField.val() : null; // Check if the field exists

    console.log('Email Address:', emailAddress);
    console.log('Invitation Code:', invitationCode);

    // Check the invitation code enable/disable status from PHP
    
    // let invitationCodeEnabled = <?php echo json_encode(get_option('invitation_code_enabled') === 'enable'); ?>;
        const invitationCodeEnabled = document.getElementById('invitation_code_enabled').value === 'enable';


    // Validate input fields
    if (!emailAddress || (invitationCodeEnabled && !invitationCode)) {
        alert("Please fill in all required fields.");
        return;
    }

    // Make AJAX request
    showLoader();
    $.ajax({
        url: ajax_object.ajax_url,
        type: 'post',
        data: {
            action: 'validate_invitation_code',
            emailAddress: emailAddress,
            invitationCode: invitationCodeEnabled ? invitationCode : null // Only send code if enabled
        },
        success: function (response) {
            hideLoader();
            try {
                response = JSON.parse(response);
                if (response.success === true) {
                    console.log('Success:', response.message);
                    localStorage.setItem('emailAddress', emailAddress);
                    $('#usr-email').html(emailAddress); // Update HTML with email address
                    $('#code-validation-container').hide(); // Hide code validation container
                    $("#personal-details-container").show(); // Show personal details container
                     nextStep(1);
                } else {
                    alert(response.message); // Show error message in an alert
                }
            } catch (e) {
                console.error('Error parsing JSON response:', e);
                alert('An unexpected error occurred. Please try again.');
            }
        },
        error: function (xhr, status, error) {
            hideLoader();
            console.error('AJAX Error:', status, error);
            alert('An error occurred while processing your request. Please try again.');
        }
    });
});

    
    // if code is validated
    $("#submit_details-btn").on('click', function(event){
        event.preventDefault();
        let emailAddress = localStorage.getItem('emailAddress');
        let usrName         =   $('#username').val();
        let phoneNumber     =   $('#phone_number').val();
        let password        =   $('#password').val();
        let confirmPassword =   $('#cnfrm_pswrd').val();
        let lastName        =   $('#last_name').val();
        let firstName       =   $('#first_name').val();
    
        
        if (!emailAddress || !usrName || !phoneNumber || !password || !confirmPassword || !lastName || !firstName) {
            alert("Please fill in all required fields");
            return;
        }

        
        if(password !== confirmPassword){
            alert('Password is not match.');
            return;
        }
        
        showLoader();
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'post',
            data: {
                action: 'register_new_artist',
                emailAddress: emailAddress,
                usrName: usrName,
                phoneNumber: phoneNumber,
                password: password,
                confirmPassword: confirmPassword,
                firstName: firstName,
                lastName: lastName, 
            }, 
            success:function(response){
                hideLoader();
                // response = JSON.parse(response);
                if(response.success === true){
                    var userId = response.data.user_id;
                    console.log('userId: ' , userId);
                    // console.log(userId);
                    $("#crnt_usr_id").val(userId);
                    $("#personal-details-container").hide();
                    $('#select-group-container').show();
                        nextStep(2); 
                } else{
                    let data = response.data;
                    alert(data.message);
                }
                console.log(response);
            },
            error:function(error){
                hideLoader();
                alert(error.message);
                console.log(error);
            }
        })
    });
    
    
    // Join buddypress group
    $('#add_to_group_btn').on('click', function(event){
        event.preventDefault();
        // let groupID = $('#select_bp_group option:selected').data('selectedgroup');
        // let groupID = getSelectedCheckboxes();
        let userID = $('#crnt_usr_id').val();
        let groupID;
        var selectedGroups = [];
        $('input[name="groups[]"]:checked').each(function() {
            selectedGroups.push($(this).val());
        });

        console.log(selectedGroups);
        if(!selectedGroups){
            alert("Please select a group alert1");
            return;
        }
        showLoader();
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'post', 
            data:{
                action: 'join_buddypress_groups',
                group_ids: selectedGroups,
                user_id : userID
            },
            success:function(response){
                hideLoader();
                if(response.success === true){
                    $('#select-group-container').hide();
                    $('#upload-file-container').show();
                        nextStep(3); 
                    // window.location.href = response.data.redirect_url;
                } else{
                    let data = response.data;
                    alert(data.message);  
                }
                console.log(response);
            },
            error:function(error){
                hideLoader();
                console.log(error);
            }
        })
    });
    
    
    // Submit portfolio 
   $('#create-story-form').on('submit', function(event) {
        event.preventDefault();  // Prevent the default form submission
    
        // Collect form data
        var formData = new FormData(this);
    
        // Include any additional data (e.g., action for WP AJAX handler)
        formData.append('action', 'handle_nightlife360_form_submission');
        
        let img = $('#file-upload').val();
        console.log(img);
    
        // Perform AJAX request
        showLoader();
        $.ajax({
            url: ajax_object.ajax_url,  // Ensure ajaxurl is defined in your theme or plugin
            type: 'POST',
            data: formData,
            contentType: false,  // Required for file upload
            processData: false,  // Required for file upload
            success: function(response) {
                hideLoader();
                
                console.log(response);
                if (response.success === true) {
                      nextStep(5);

                    // Show points earned in an alert
                    alert(`You have earned ${totalPoints} points so far!`);
                    $("#featured-story-container").hide();
                    $('#thankyou-page-container').show();
                    //  window.location.href = response.data.post_url;
                } else {
                    alert("Error occurred while creating story: " + response.data.message);
                }
            },
            error: function(xhr, status, error) {
                hideLoader();
                console.error('AJAX Error:', status, error);
                alert('An error occurred while submitting the form. Please try again.');
            }
        });
    });



    
    // Affiliate user 
    $("#affiliate_artist_btn").on('click', function(event){
        event.preventDefault();
        let artistName = $('#artist-name').val();
        let artistEmail = $('#artist-email').val();
        // let artistBio = $('#artist-bio').val();
        let affiliateID = $('#affiliate-id').val();
        // || !artistBio
        if(!artistName || !artistEmail){
            alert("Please fill required field.");
            return;
        }
        
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'post',
            data:{
                action: 'handle_affiliate_form',
                artistName: artistName,
                artistEmail: artistEmail,
                // artistBio: artistBio,
                affiliateID: affiliateID
            },
            success:function(response){
                console.log(response);
                alert(response);
            },
            error: function(error){
                console.log(error);
                alert(error);
            }
        })
    });
    
    // Add profile photo
    $('#add_profile_bio_form').on('submit', function(event){
        event.preventDefault();
        
        let formData = new FormData(this);
        formData.append('action', 'add_profile_image_bio_action');
        
        showLoader();
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'post',
            data: formData,
            contentType: false,  // Required for file upload
            processData: false,
            success:function(response){
                hideLoader();
                console.log("inside 4th");
                nextStep(4); 
                console.log("outside 4th");
                $('#upload-file-container').hide();
                $('#featured-story-container').show();
               alert(`You have earned ${totalPoints} points so far!`);
            },
            error:function(error){
                hideLoader();
                // console.log(error);
                alert("Unexpected error occured. Please try after some time.");
            }
        })
    })
    
    
    
    
    // remove autocomplete
    $('input').each(function() {
    $(this).on('focus', function() {
        $(this).attr('autocomplete', 'off');
    });
});

});




// tooltip js 



