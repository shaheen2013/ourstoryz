<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       https://mediusware.com
 * @since      1.0.0
 *
 * @package    ourstoryz
 * @subpackage ourstoryz/includes
 */

/**
 * Shortcode handler function
 *
 * @return string Content to display
 */

require_once plugin_dir_path(__FILE__) . 'class-signup-postdata.php';

function ourstoryz_shortcode_function()
{
    ob_start(); // Start output buffering
?>



    <div class="ar-main-wrapper py-4 py-md-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 order-1 order-lg-0">
                    <div class="p-30">
                        <div class="fs-20 divider pb-3 mb-20">What Type of Event?</div>
                        <div class="fs-16 divider pb-20">
                            Choose the broad category of your event. If you are a professional event planner, you’ll have
                            the option of creating a Professional account supporting multiple clients.
                        </div>
                        <div class="my-20 pb-20 check-list-section divider">



                            <?php
                            $signup_info = new Signup_Modal_Info();
                            $signup_info->signup_post();

                            ?>




                        </div>
                        <div class="btn-border">
                            <div id="continue-btn" class="continue-btn btn disabled" data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="handleSetModal('google-captcha-section')">
                                Continue Signup
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-0 order-lg-1">
                    <div class="row g-3 ar-hero-img">
                        <div class="col-6">

                            <img src="/assets/images/img1.png" alt="">
                        </div>
                        <div class="col-6">
                            <img src="./assets/images/img2.png" alt="">
                        </div>
                        <div class="col-6">
                            <img src="./assets/images/img3.png" alt="">
                        </div>
                        <div class="col-6">
                            <img src="./assets/images/img4.png" alt="">
                        </div>
                        <div class="col-12">
                            <div class="fs-16 text-end">
                                Are you a vendor? <a class="fs-16" href="#">click here.</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- MODAL SECTION -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" id="signin-modal">

                    <!--GOOGLE-CAPTCHA-SECTION-->
                    <!-- old  -->
                    <div id="google-captcha-section" class="google-captcha-section d-none">
                        <div class="divider pb-3 d-flex align-items-center gap-2">
                            <img src="./assets/images/logo.png" alt="logo" width="w-100">
                            <div>
                                <div class="fs-24 fw-semibold">OurStoryz</div>
                                <div class="fs-16 fw-500">Login</div>
                            </div>
                        </div>
                        <div class="fs-24 my-20">Let’s get started! (confirm you’re human)</div>
                        <div class="captcha-img">
                            <img src="./assets/images/captcha.png" alt="captcha" class="w-100">
                            <button onclick="handleSetModal('want-to-test-section')" type="button"
                                class="btn btn-sm btn-primary mt-20">NEXT
                            </button>
                        </div>
                    </div>

                    <!-- end old -->
                    <!-- working  -->
                    <!-- Add this script in the <head> section of your HTML -->
                    <!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->

                    <!-- Your HTML structure -->

                    <!-- <div id="google-captcha-section" class="google-captcha-section d-none">
                        <div class="divider pb-3 d-flex align-items-center gap-2">
                            <img src="./assets/images/logo.png" alt="logo" width="w-100">
                            <div>
                                <div class="fs-24 fw-semibold">OurStoryz</div>
                                <div class="fs-16 fw-500">Login</div>
                            </div>
                        </div>
                        <div class="fs-24 my-20">Let’s get started! (confirm you’re human)</div>

                 
                        <div class="captcha-img">
                           
                            <input type="hidden" name="recaptcha_token" id="recaptcha_token">

                             
                            <button onclick="handleCaptchaVerification()" type="button" class="btn btn-sm btn-primary mt-20">NEXT</button>
                        </div>
                    </div> -->







                    <!-- end working -->







                    <!--WANT-TO-TEXT-SECTION-->
                    <?php
                    $signup_info->displayWantToTestSection();

                    ?>

                    <!--WELCOME-TO-LOCATION-SECTION-->
                    <?php $signup_info->displayWelcomeToLocationSection();  ?>

                    <!--GIVE-YOUR-EVENT-NAME-SECTION-->
                    <?php
                    $signup_info->displayGiveYourEventNameSection();
                    ?>

                    <!--ADD-LOCATION-SECTION-->
                    <?php
                    $signup_info->displayAddLocationSection();
                    ?>
                    <!--WHY-LIST-LOCATION-ALERT-->
                    <?php $signup_info->displayWhyListLocation(); ?>

                    <!--ADD-DATES-SECTION big event-->
                    <?php $signup_info->displayAddDatesSection(); ?>

                    <!--WHY-LIST-DATES-ALERT-->
                    <?php $signup_info->displayWhyListDatesSection(); ?>

                    <!--ABOUT-YOURSELF-SECTION-->
                    <?php $signup_info->displayAboutYourselfSection(); ?>

                    <!--ADD-IMAGE-EVENT-SECTION-->
                    <?php $signup_info->displayAddImageEventSection(); ?>

                    <!--CHOOSE-LEVEL-SERVICE-SECTION-->
                    <?php $signup_info->displayChooseLevelServiceSection(); ?>

                    <!--EVENTS-LARGER-WEDDING-SECTION-->
                    <?php $signup_info->displayEventsLargerWeddingSection(); ?>

                    <!--ADD-IMAGE-STORYZ-SECTION-->
                    <?php $signup_info->displayAddImageStoryzSection(); ?>

                    <!--TELL-ORGANIZATION-SECTION-->
                    <?php $signup_info->displayTellOrganizationSection();  ?>

                    <!--ADD-IMAGE-STORYZ-SECTION-->
                    <?php $signup_info->displayCreateStoryzSection(); ?>

                    <!--INVITE-TEAM-MEMBER-SECTION-->
                    <?php $signup_info->displayInviteTeamMemberSection(); ?>

                    <!--SETUP-OFFER-SECTION-->
                    <?php $signup_info->displaySetupOfferSection(); ?>

                    <!--SORRY-TO-SEE-SECTION-->
                    <?php $signup_info->displaySorryToSeeSection(); ?>

                    <!--NEWSLETTER-SET-SECTION-->
                    <?php $signup_info->displayNewsletterSetSection(); ?>

                </div>
            </div>
        </div>


    </div>
<?php
    return ob_get_clean(); // Return the buffered content
}

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    ourstoryz
 * @subpackage ourstoryz/includes
 */
function ourstoryz_register_shortcodes()
{
    add_shortcode('signupmodal_shortcode', 'ourstoryz_shortcode_function');
}

// Hook into the 'init' action to register the shortcode
add_action('init', 'ourstoryz_register_shortcodes');

// function verify_recaptcha()
// {
//     $recaptcha_secret = '6LfZ0BwqAAAAAFjPUyQaCOG8gDbK4bI9qqsQXH4Q'; // Your reCAPTCHA secret key
//     $response = sanitize_text_field($_POST['token']); // Sanitize the received token

//     // Send a request to Google to verify the token
//     $verify_response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$response}");
//     $response_body = wp_remote_retrieve_body($verify_response); // Retrieve the response body
//     $result = json_decode($response_body, true); // Decode the JSON response

//     // Check if the verification was successful
//     if ($result['success']) {
//         wp_send_json_success(); // Send a JSON success response
//     } else {
//         wp_send_json_error(); // Send a JSON error response
//     }
// }
// add_action('wp_ajax_verify_recaptcha', 'verify_recaptcha');
// add_action('wp_ajax_nopriv_verify_recaptcha', 'verify_recaptcha');

function verify_recaptcha_ajax()
{
    // Verify the nonce for security
    check_ajax_referer('recaptcha_nonce', 'nonce');

    $recaptcha_response = sanitize_text_field($_POST['recaptcha_response']);

    // Send a request to Google to verify the reCAPTCHA response
    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
        'body' => array(
            'secret' => '6LfZ0BwqAAAAAFjPUyQaCOG8gDbK4bI9qqsQXH4Q', // Replace with your secret key
            'response' => $recaptcha_response
        )
    ));

    $response_body = wp_remote_retrieve_body($response);
    $result = json_decode($response_body, true);

    if (isset($result['success']) && $result['success'] == true) {
        // Success: The user is verified
        wp_send_json_success('Verified');
    } else {
        // Failure: The user is not verified
        wp_send_json_error('Verification failed');
    }
}
add_action('wp_ajax_verify_recaptcha', 'verify_recaptcha_ajax');
add_action('wp_ajax_nopriv_verify_recaptcha', 'verify_recaptcha_ajax');

?>