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
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    ourstoryz
 * @subpackage ourstoryz/includes
 * @author     Mediusware <zahid@mediusware.com>
 */

class Signup_Modal_Info
{

    public function signup_post()
    {
        $args = array(
            'post_type'      => 'signup', // Replace 'signup' with your custom post type
            'posts_per_page' => -1, // Number of posts to retrieve (-1 for all)
            'post_status'    => 'publish', // Only get published posts
            'orderby'       => 'date', // Order by date
            'order'         => 'DESC' // Latest first
        );

        $signup_query = new WP_Query($args);

        $count = 1; // Initialize count outside the loop
        if ($signup_query->have_posts()) {
            while ($signup_query->have_posts()) {
                $signup_query->the_post();
?>
                <div class="form-check mb-10">
                    <input type="radio" name="eventOption" class="form-check-input" id="check<?php echo $count; ?>" onclick="checkCheckboxes()">
                    <label class="form-check-label" for="check<?php echo $count; ?>"><?php the_title(); ?></label>
                </div>
<?php
                $count++; // Increment count after each post
            }
        } else {
            echo 'No signups found.';
        }

        wp_reset_postdata(); // Reset post data
    }

    public function displayWantToTestSection()
    {
        echo '
        <div id="want-to-test-section" class="want-to-test-section d-none">
            <div class="fs-20">What do you want to test?</div>
            <div class="mt-20 d-flex flex-column flex-sm-row gap-2">
                <div class="geospaced-event w-360 d-flex flex-column">
                    <img src="assets/images/geospaced-event.png" alt="geospaced" class="w-100">
                    <div class="p-3">
                        <div class="fs-16 fw-semibold mb-10">Free Trial with one geospaced event</div>
                        <div class="fs-14 text-center">
                            If you just want to explore geospaced events with a free account, choose the Locator option. You can upgrade to full event management later.
                        </div>
                    </div>
                    <div onclick="handleSetModal(\'welcome-to-location-section\')" class="btn btn-dark w-100 mt-auto">Select Locator for free (1 year)
                    </div>
                </div>
                <div class="geospaced-event w-360 d-flex flex-column">
                    <img src="assets/images/multiple-event.png" alt="multiple-event" class="w-100">
                    <div class="p-3">
                        <div class="fs-16 fw-semibold mb-10">Multiple events</div>
                        <div class="fs-14 text-center">
                            If you are setting up an event that has sub-events, you need DIY or Professional service. You will create a event (“Storyz”) which in turn can have many events.
                        </div>
                    </div>
                    <div class="btn btn-dark w-100 mt-auto">See options with multiple events</div>
                </div>
            </div>
            <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">cancel
            </div>
        </div>';
    }

    public function displayWelcomeToLocationSection()
    {
        echo '
        <div id="welcome-to-location-section" class="welcome-to-location d-none">
            <div class="fs-20">Welcome to Locator</div>
            <div class="mt-20 d-flex gap-2">
                <div class="geospaced-event w-360 d-flex flex-column">
                    <img src="assets/images/geospaced-event.png" alt="geospaced" class="w-100">
                    <div class="p-3">
                        <div class="fs-14 text-center mb-10">
                            If you just want to explore with a simple, one-time event free event, choose the Locator option. You can upgrade later if desired.
                        </div>
                        <div class="btn btn-gray w-100 mt-auto">You will only be able to create a single event
                        </div>
                    </div>
                    <div onclick="handleSetModal(\'give-your-event-name-section\')" class="btn btn-dark w-100 mt-auto">Select Locator for free
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'want-to-test-section\')">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.25 9H3.75M3.75 9L9 14.25M3.75 9L9 3.75" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Back
                </div>
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">cancel
                </div>
            </div>
        </div>';
    }


    public function displayGiveYourEventNameSection()
    {
        echo '
        <div id="give-your-event-name-section" class="w-600 d-none">
            <div class="fs-20 divider pb-4">Give your event a name</div>
            <div class="fs-14 mt-20 divider mb-20 pb-20">Let us know a little more about yourself so we can begin creating your basic Locator account.
            </div>
            <img src="./assets/images/give-your-event-name.png" alt="event" class="w-100 rounded-4">
            <div class="mt-20">
                <div class="fs-14 fw-500">Event name:</div>
                <input id="event-name" value="" type="text" placeholder="enter a name for your event" class="form-control mt-2">
            </div>
            <div class="mt-20">
                <div class="fs-14 fw-500">Greeting to your guests:</div>
                <input id="greeting-guest" value="" type="text" placeholder="enter 1-sentence greeting to your guests" class="form-control mt-2">
            </div>
            <div class="mt-20 fs-16 divider pb-20 mb-20">Your default role is ‘event organizer’ - you can add additional roles after completing the signup process.
            </div>
            <div onclick="handleSetModal(\'add-location-section\')" class="btn btn-dark w-100 mt-auto disabled" id="event-continue-btn">Continue
            </div>
            <div class="d-flex justify-content-between">
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'welcome-to-location-section\')">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.25 9H3.75M3.75 9L9 14.25M3.75 9L9 3.75" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Back
                </div>
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">cancel
                </div>
            </div>
        </div>';
    }

    public function displayAddLocationSection()
{
    echo '
    <div id="add-location-section" class="w-600 d-none">
        <div class="fs-20 divider pb-4">Add a location</div>
        <div class="fs-16 mt-20 divider mb-20 pb-20">
            Provide the location of your event (approximate is OK).
            You’ll need it to test the OurStoryz Geospace features. You can always add this later.
        </div>
        <img src="./assets/images/add-location.png" alt="event" class="w-100 rounded-4 mb-20">

        <div class="my-20">
            <div class="row">
                <div class="col-6">
                    <div id="add-location-change-section" class="h-100 d-flex flex-column justify-content-center">
                        <div class="fs-18 text-end mb-2">Your event location</div>
                        <div onclick="handleChangeLocation()" id="add-location-change-btn" class="btn-location-change">
                            Change
                        </div>
                    </div>
                    <div id="add-location-change-input" class="h-100 flex-column justify-content-center d-none">
                        <div class="fs-18 mb-2">Your event location</div>
                        <textarea name="location" class="form-control flex-grow-1"></textarea>
                    </div>
                </div>
                <div class="col-6">
                    <div class="map-area"></div>
                </div>
            </div>
        </div>

        <div class="divider mb-20 pb-20"></div>

        <div class="d-flex justify-content-between gap-2">
            <div onclick="handleSetModal(\'add-dates-section\')" class="btn btn-dark rounded-pill w-100">
                Continue
            </div>

            <div onclick="handleSetModal(\'add-dates-section\')" class="btn w-100 btn-dark rounded-pill border-dark border fw-semibold bg-transparent text-dark">
                Don’t know (skip)
            </div>
        </div>
        <div onclick="handleSetModal(\'why-list-location\')" class="text-center text-decoration-underline fs-14 fw-semibold mt-2" type="button">
            Why do I need to list a location?
        </div>

        <div class="d-flex justify-content-between">
            <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'give-your-event-name-section\')">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.25 9H3.75M3.75 9L9 14.25M3.75 9L9 3.75" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </div>
            <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">
                Cancel
            </div>
        </div>
    </div>';
}


    public function displayWhyListLocation()
    {
        echo '
        <div id="why-list-location" class="w-600 d-none">
            <div class="d-flex justify-content-center mb-3">
                <svg width="94" height="94" viewBox="0 0 94 94" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2.5" y="2.5" width="89" height="89" rx="44.5" stroke="#FC3158" stroke-width="5" />
                    <path d="M42.9597 56.7273V56.4716C42.9881 53.7585 43.2722 51.5994 43.812 49.9943C44.3517 48.3892 45.1188 47.0895 46.1131 46.0952C47.1074 45.1009 48.3006 44.1847 49.6926 43.3466C50.5307 42.8352 51.2836 42.2315 51.9512 41.5355C52.6188 40.8253 53.1444 40.0085 53.5279 39.0852C53.9256 38.1619 54.1245 37.1392 54.1245 36.017C54.1245 34.625 53.7978 33.4176 53.1444 32.3949C52.4909 31.3722 51.6174 30.5838 50.5236 30.0298C49.4299 29.4759 48.2154 29.1989 46.8801 29.1989C45.7154 29.1989 44.5932 29.4403 43.5137 29.9233C42.4341 30.4062 41.5321 31.1662 40.8077 32.2031C40.0833 33.2401 39.6642 34.5966 39.5506 36.2727H34.1813C34.2949 33.858 34.9199 31.7912 36.0563 30.0724C37.2069 28.3537 38.7196 27.0398 40.5946 26.1307C42.4838 25.2216 44.579 24.767 46.8801 24.767C49.3801 24.767 51.5534 25.2642 53.4 26.2585C55.2608 27.2528 56.6955 28.6165 57.704 30.3494C58.7267 32.0824 59.2381 34.0568 59.2381 36.2727C59.2381 37.8352 58.9966 39.2486 58.5137 40.5128C58.0449 41.777 57.3631 42.9062 56.4682 43.9006C55.5875 44.8949 54.5222 45.7756 53.2722 46.5426C52.0222 47.3239 51.0208 48.1477 50.2679 49.0142C49.5151 49.8665 48.9682 50.8821 48.6273 52.0611C48.2864 53.2401 48.1017 54.7102 48.0733 56.4716V56.7273H42.9597ZM45.687 69.3409C44.6358 69.3409 43.7338 68.9645 42.981 68.2116C42.2282 67.4588 41.8517 66.5568 41.8517 65.5057C41.8517 64.4545 42.2282 63.5526 42.981 62.7997C43.7338 62.0469 44.6358 61.6705 45.687 61.6705C46.7381 61.6705 47.6401 62.0469 48.3929 62.7997C49.1458 63.5526 49.5222 64.4545 49.5222 65.5057C49.5222 66.2017 49.3446 66.8409 48.9895 67.4233C48.6486 68.0057 48.187 68.4744 47.6046 68.8295C47.0364 69.1705 46.3972 69.3409 45.687 69.3409Z" fill="#FC3158" />
                </svg>
            </div>
            <div class="fs-20 divider pb-3 text-center">Why you need to list your location</div>
            <div class="fs-16 mt-20 divider mb-20 pb-20">
                OurStoryz is unique in providing geolocated events which are specific in space and time. You can
                plan an event and communicate with your guests at all times, but the geolocation and social
                sharing functions in the guest app require a location (and event date) to work.
                <br><br>
                You can always add this later.
            </div>
            <div onclick="handleSetModal(\'add-location-section\')" class="btn btn-dark rounded-pill w-100">
                Continue
            </div>
        </div>';
    }

    public function displayAddDatesSection()
    {
        echo '
        <div id="add-dates-section" class="w-600 d-none">
            <div class="fs-20 divider pb-4">Add dates</div>
            <div class="fs-16 mt-20 divider mb-20 pb-20">Provide the date and location of your event. You can
                always add this later, but you’ll need it to test the OurStoryz Geospace features.
            </div>
            <img src="./assets/images/add-dates.png" alt="event" class="w-100 rounded-4 mb-20">
            <div class="row">
                <div class="col-6">
                    <div>
                        <div class="fs-14 fw-500">Event begins</div>
                        <input type="datetime-local" class="form-control mt-2">
                        <div class="fs-12 mt-2 text-end">8/8/2022 3:30PM in your local timezone (PST)</div>
                    </div>
                </div>
                <div class="col-6">
                    <div>
                        <div class="fs-14 fw-500">Event ends</div>
                        <input type="datetime-local" class="form-control mt-2">
                        <div class="fs-12 mt-2 text-end">8/8/2022 3:30PM in your local timezone (PST)</div>
                    </div>
                </div>
            </div>
    
            <div class="divider mb-20 pb-20"></div>
    
            <div class="d-flex justify-content-between gap-2">
                <div onclick="handleSetModal(\'about-yourself-section\')" class="btn btn-dark rounded-pill w-100">Continue</div>
                <div onclick="handleSetModal(\'about-yourself-section\')" class="btn w-100 btn-dark rounded-pill border-dark border fw-semibold bg-transparent text-dark">
                    Don’t know (skip)
                </div>
            </div>
            <div onclick="handleSetModal(\'why-list-dates\')" class="text-center text-decoration-underline fs-14 fw-semibold mt-2" type="button">Why do I need to list a date?</div>
            <div class="d-flex justify-content-between">
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'add-location-section\')">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.25 9H3.75M3.75 9L9 14.25M3.75 9L9 3.75" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Back
                </div>
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">cancel</div>
            </div>
        </div>';
    }

    public function displayWhyListDatesSection()
    {
        echo '
        <div id="why-list-dates" class="w-600 d-none">
            <div class="d-flex justify-content-center mb-3">
                <svg width="94" height="94" viewBox="0 0 94 94" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2.5" y="2.5" width="89" height="89" rx="44.5" stroke="#FC3158" stroke-width="5" />
                    <path d="M42.9597 56.7273V56.4716C42.9881 53.7585 43.2722 51.5994 43.812 49.9943C44.3517 48.3892 45.1188 47.0895 46.1131 46.0952C47.1074 45.1009 48.3006 44.1847 49.6926 43.3466C50.5307 42.8352 51.2836 42.2315 51.9512 41.5355C52.6188 40.8253 53.1444 40.0085 53.5279 39.0852C53.9256 38.1619 54.1245 37.1392 54.1245 36.017C54.1245 34.625 53.7978 33.4176 53.1444 32.3949C52.4909 31.3722 51.6174 30.5838 50.5236 30.0298C49.4299 29.4759 48.2154 29.1989 46.8801 29.1989C45.7154 29.1989 44.5932 29.4403 43.5137 29.9233C42.4341 30.4062 41.5321 31.1662 40.8077 32.2031C40.0833 33.2401 39.6642 34.5966 39.5506 36.2727H34.1813C34.2949 33.858 34.9199 31.7912 36.0563 30.0724C37.2069 28.3537 38.7196 27.0398 40.5946 26.1307C42.4838 25.2216 44.579 24.767 46.8801 24.767C49.3801 24.767 51.5534 25.2642 53.4 26.2585C55.2608 27.2528 56.6955 28.6165 57.704 30.3494C58.7267 32.0824 59.2381 34.0568 59.2381 36.2727C59.2381 37.8352 58.9966 39.2486 58.5137 40.5128C58.0449 41.777 57.3631 42.9062 56.4682 43.9006C55.5875 44.8949 54.5222 45.7756 53.2722 46.5426C52.0222 47.3239 51.0208 48.1477 50.2679 49.0142C49.5151 49.8665 48.9682 50.8821 48.6273 52.0611C48.2864 53.2401 48.1017 54.7102 48.0733 56.4716V56.7273H42.9597ZM45.687 69.3409C44.6358 69.3409 43.7338 68.9645 42.981 68.2116C42.2282 67.4588 41.8517 66.5568 41.8517 65.5057C41.8517 64.4545 42.2282 63.5526 42.981 62.7997C43.7338 62.0469 44.6358 61.6705 45.687 61.6705C46.7381 61.6705 47.6401 62.0469 48.3929 62.7997C49.1458 63.5526 49.5222 64.4545 49.5222 65.5057C49.5222 66.2017 49.3446 66.8409 48.9895 67.4233C48.6486 68.0057 48.187 68.4744 47.6046 68.8295C47.0364 69.1705 46.3972 69.3409 45.687 69.3409Z" fill="#FC3158" />
                </svg>
            </div>
            <div class="fs-20 divider pb-3 text-center">Why you need to list your event dates</div>
    
            <div class="fs-16 mt-20 divider mb-20 pb-20">
                OurStoryz is unique in providing geolocated events which are specific in space and time. You can
                plan an event and communicate with your guests at all times, but the geolocation and social
                sharing functions in the guest app require event dates (and location) to work.
                <br><br>
                You can always add this later.
            </div>
            <div onclick="handleSetModal(\'add-dates-section\')" class="btn btn-dark rounded-pill w-100">Continue</div>
        </div>';
    }

    public function displayAboutYourselfSection()
    {
        echo '
        <div id="about-yourself-section" class="w-600 d-none">
            <div class="fs-20 divider pb-3">About yourself</div>
            <div class="fs-16 mt-20 divider mb-20 pb-20">
                Add your name and role in this event. If you have an image of yourself you want to use, you can
                add it now.
            </div>
            <div class="row">
                <div class="col-sm-5 mb-3 mb-sm-0">
                    <div class="h-100 border rounded-4 p-4">
                        <div class="w-200">
                            <img id="avatarYourself" src="./assets/images/demo.png" alt="demo" class="w-100">
                        </div>
                        <div class="mt-20 fs-16 mb-10 fw-semibold text-center text-dark">Personal image</div>
                        <div class="fs-12 text-center mb-10">
                            Upload an image of yourself. The image will appear in the guest list, so choose one
                            that you want everyone to see.
                        </div>
                        <div class="fs-12 fw-500 position-relative file-upload-btn">Upload
                            <input type="file" id="avatarInput" class="position-absolute file-input">
                        </div>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="h-100 d-flex flex-column justify-content-center">
                        <div>
                            <div class="fs-14 fw-500">First name:</div>
                            <input type="text" id="first-name" class="form-control mt-2" placeholder="enter first name">
                        </div>
                        <div class="mt-20">
                            <div class="fs-14 fw-500">Last name:</div>
                            <input type="text" id="last-name" class="form-control mt-2" placeholder="enter last name">
                        </div>
                        <div class="mt-20">
                            <div class="fs-14 fw-500">Enter email address:</div>
                            <div class="position-relative">
                                <input type="text" id="email" class="form-control mt-2" placeholder="enter email address" style="padding-right: 50px">
                                <svg class="position-absolute end-0 top-0" style="margin: 12px" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 7.5L10.1649 13.2154C10.8261 13.6783 11.1567 13.9097 11.5163 13.9993C11.8339 14.0785 12.1661 14.0785 12.4837 13.9993C12.8433 13.9097 13.1739 13.6783 13.8351 13.2154L22 7.5M6.8 20.5H17.2C18.8802 20.5 19.7202 20.5 20.362 20.173C20.9265 19.8854 21.3854 19.4265 21.673 18.862C22 18.2202 22 17.3802 22 15.7V9.3C22 7.61984 22 6.77976 21.673 6.13803C21.3854 5.57354 20.9265 5.1146 20.362 4.82698C19.7202 4.5 18.8802 4.5 17.2 4.5H6.8C5.11984 4.5 4.27976 4.5 3.63803 4.82698C3.07354 5.1146 2.6146 5.57354 2.32698 6.13803C2 6.77976 2 7.61984 2 9.3V15.7C2 17.3802 2 18.2202 2.32698 18.862C2.6146 19.4265 3.07354 19.8854 3.63803 20.173C4.27976 20.5 5.11984 20.5 6.8 20.5Z" stroke="#0067E1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="fs-16 mt-20">
                        (Optional) Your default role is Event Organizer. you can add an additional role if you
                        wish when you complete onboarding.
                    </div>
                </div>
            </div>
    
            <div class="divider mb-20 pb-20"></div>
    
            <div class="d-flex justify-content-between gap-2">
                <div id="about-continue-btn" onclick="handleSetModal(\'add-image-event-section\')" class="disabled btn btn-dark rounded-pill w-100">Continue</div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'add-dates-section\')">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.25 9H3.75M3.75 9L9 14.25M3.75 9L9 3.75" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Back
                </div>
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">cancel</div>
            </div>
        </div>';
    }

    public function displayAddImageEventSection()
    {
        echo '
        <div id="add-image-event-section" class="w-390 d-none">
            <div class="fs-20 divider pb-4">Add an image for your event</div>
            <div class="fs-16 mt-20 divider mb-20 pb-20">
                You can add an image that will be the default for your event, guest list, and invites. You can
                change this later.
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="border rounded-4">
                        <div class="row">
                            <div class="col-6">
                                <div class="h-100 p-4 pe-0">
                                    <div class="img">
                                        <img id="eventImage" src="./assets/images/demo.png" alt="demo" class="w-100">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="h-100 d-flex flex-column justify-content-center p-4 ps-0">
                                    <div class="mt-20 fs-16 mb-10 fw-semibold text-center text-dark">Event image</div>
                                    <div class="fs-12 text-center mb-10">
                                        Upload an image related to your event. It could be abstract, or a view
                                        of the event venue.
                                    </div>
                                    <div class="fs-12 fw-500 position-relative file-upload-btn">Upload
                                        <input type="file" id="eventAvatarInput" class="position-absolute file-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="divider mb-20 pb-20"></div>
    
            <div class="d-flex justify-content-between gap-2">
                <div data-bs-dismiss="modal" class="btn btn-dark rounded-pill w-100">Continue</div>
                <div onclick="handleSetModal(\'sorry-to-see-section\')" class="btn w-100 btn-dark rounded-pill border-dark border fw-semibold bg-transparent text-dark">
                    Skip
                </div>
            </div>
    
            <div class="d-flex justify-content-between">
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'about-yourself-section\')">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.25 9H3.75M3.75 9L9 14.25M3.75 9L9 3.75" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Back
                </div>
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">cancel</div>
            </div>
        </div>';
    }

    public function displayChooseLevelServiceSection()
    {
        echo '
        <div id="choose-level-service-section" class="d-none">
            <div class="fs-20 text-center divider pb-3">Choose a level of service</div>
            <div class="fs-16 text-center my-20">You can upgrade later if desired.</div>
            <div class="mt-20 d-flex flex-column flex-sm-row gap-2">
                <div class="geospaced-event w-360 d-flex flex-column border rounded-4 border-blue">
                    <img src="assets/images/planner-wedding-1.png" alt="geospaced" class="w-100">
                    <div class="p-3">
                        <div class="fs-16 fw-semibold mb-10 text-gray text-center">You are personally planning
                            your wedding
                        </div>
                        <div class="fs-14 text-center">
                            If you’re planning the big event yourself, this is the plan for you!
                        </div>
                    </div>
                    <div onclick="handleSetModal(\'add-dates-section\')" class="btn btn-dark w-100 mt-auto">Select DIY for $99/1 year</div>
                </div>
                <div class="geospaced-event w-360 d-flex flex-column border rounded-4 border-blue">
                    <img src="assets/images/planner-wedding-2.png" alt="multiple-event" class="w-100">
                    <div class="p-3">
                        <div class="fs-16 fw-semibold mb-10 text-gray text-center">You are a professional
                            wedding planner
                        </div>
                        <div class="fs-14 text-center">
                            Select Professional if you need to manage the wedding ceremony, rehearsal,
                            reception, or other events in the big day.
                        </div>
                    </div>
                    <div class="btn btn-dark bg-transparent text-black w-100 mt-auto">Select Professional for $40/month</div>
                </div>
            </div>
            <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">cancel</div>
        </div>';
    }

    public function displayEventsLargerWeddingSection()
    {
        echo '
        <div id="events-larger-wedding-section" class="w-600 d-none">
            <div class="fs-20 divider pb-3 mb-20">Add events within your larger wedding</div>
            <div class="d-flex flex-column flex-sm-row align-items-center gap-3 divider pb-20 mb-20">
                <img src="./assets/images/larger-wedding.png" alt="event" class="w-100 rounded-4" style="max-width: 126px">
                <div class="fs-16">You probably have several sub-events in your overall wedding. We have listed
                    a few common events that you can have built automatically for you.
                    <br> <br>Don’t worry - you can always add additional events later. Each event can also have
                    its own guest list.
                </div>
            </div>
            <div class="mt-20">
                <div class="fs-14 fw-500">Storyz name:</div>
                <input value="" type="text" placeholder="My Wedding" class="form-control mt-2">
            </div>
            <div class="my-20">
                <div class="fs-16 text-black fw-500 mb-20">Select additional events to auto-create</div>
                <div class="my-20 pb-20 check-list-section divider">
                    <div class="form-check mb-10">
                        <input type="checkbox" class="form-check-input" id="wedding-1">
                        <label class="form-check-label" for="wedding-1">Wedding rehearsal</label>
                    </div>
                    <div class="form-check mb-10">
                        <input type="checkbox" class="form-check-input" id="wedding-2">
                        <label class="form-check-label" for="wedding-2">Wedding ceremony</label>
                    </div>
                    <div class="form-check mb-10">
                        <input type="checkbox" class="form-check-input" id="wedding-3">
                        <label class="form-check-label" for="wedding-3">Wedding reception</label>
                    </div>
                </div>
            </div>
            <div onclick="handleSetModal(\'add-location-section\')" class="btn btn-dark rounded-pill w-100 mt-auto">Continue</div>
            <div class="d-flex justify-content-between">
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'welcome-to-location-section\')">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.25 9H3.75M3.75 9L9 14.25M3.75 9L9 3.75" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Back
                </div>
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">cancel</div>
            </div>
        </div>';
    }


    public function displayAddImageStoryzSection()
    {
        echo '
        <div id="add-image-storyz-section" class="w-390 d-none">
            <div class="fs-20 divider pb-4">Add an image for your Storyz</div>
            <div class="fs-16 mt-20 divider mb-20 pb-20">
                You can add an image that will be the default for your website, guest app, and invites. After
                onboarding, you can specify different images for each sub-event in your Storyz.
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="border border-blue rounded-4">
                        <div class="row p-3">
                            <div class="col-sm-5">
                                <div class="h-100 d-flex align-items-center">
                                    <div class="img w-140-circle">
                                        <img id="storyzImage" src="./assets/images/demo.png" alt="demo" class="w-100 h-100">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="h-100 d-flex flex-column justify-content-center">
                                    <div class="mt-20 fs-16 mb-10 fw-semibold text-center text-dark">Storyz image</div>
                                    <div class="fs-12 text-center mb-10">
                                        Upload an image which can evoke or symbolize your entire event,
                                        remembering that there may be multiple sub-events.
                                    </div>
                                    <div class="fs-12 fw-500 position-relative file-upload-btn">Upload
                                        <input type="file" id="storyzAvatarInput" class="position-absolute file-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider mb-20 pb-20"></div>
            <div class="d-flex justify-content-between gap-2">
                <div data-bs-dismiss="modal" class="btn btn-dark rounded-pill w-100">Continue</div>
                <div onclick="handleSetModal(\'sorry-to-see-section\')" class="btn w-100 btn-dark rounded-pill border-dark border fw-semibold bg-transparent text-dark">Skip</div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'about-yourself-section\')">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.25 9H3.75M3.75 9L9 14.25M3.75 9L9 3.75" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Back
                </div>
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">cancel</div>
            </div>
        </div>';
    }

    public function displayTellOrganizationSection()
    {
        echo '
        <div id="tell-organization-section" class="w-600 d-none">
            <div class="fs-20 divider pb-3 mb-20">Tell us about your organization</div>
            <img src="./assets/images/tell-org.png" alt="org" class="w-100">
            <div class="fs-14 mt-20 divider mb-20 pb-20">
                Let us know a little more about your organization. This information will be used for find
                everythingner and vendor services.
            </div>
            <div class="row">
                <div class="col-sm-5 mb-3 mb-sm-0">
                    <div class="h-100 border border-blue rounded-4 p-4">
                        <div class="w-200">
                            <img id="brandLogo" src="./assets/images/demo.png" alt="demo" class="w-100">
                        </div>
                        <div class="mt-20 fs-16 mb-10 fw-semibold text-center text-dark">Brand logo</div>
                        <div class="fs-12 text-center mb-10">
                            Upload your logo, if you have one. This image will appear on your vendor page. Need a
                            logo? Contact us for brand services.
                        </div>
                        <div class="fs-12 fw-500 position-relative file-upload-btn">Upload
                            <input type="file" id="brandLogoInput" class="position-absolute file-input">
                        </div>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="h-100 d-flex flex-column">
                        <div>
                            <div class="fs-14 fw-500">Organization name:</div>
                            <input type="text" id="org-name" class="form-control mt-2" placeholder="enter a name for company or organization">
                        </div>
                        <div class="mt-20">
                            <div class="fs-14 fw-500">Tagline:</div>
                            <input type="text" id="tagline" class="form-control mt-2" placeholder="enter 1-sentence motto describing your organization">
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider mb-20 pb-20"></div>
            <div class="d-flex justify-content-between gap-2">
                <div id="org-continue-btn" onclick="handleSetModal(\'add-image-event-section\')" class="disabled btn btn-dark rounded-pill w-100">Continue</div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'add-dates-section\')">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.25 9H3.75M3.75 9L9 14.25M3.75 9L9 3.75" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Back
                </div>
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">cancel</div>
            </div>
        </div>';
    }

    public function displayCreateStoryzSection()
    {
        echo '
        <div id="create-storyz-section" class="w-420 d-none">
            <div class="fs-20 divider pb-3">Create a Storyz</div>
            <div class="fs-16 mt-20 divider mb-20 pb-20">
                OurStoryz groups related events into an event group that we call a ‘Storyz’. Define your first
                Storyz below. Remember that each Storyz can have many sub-events within.
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="border border-blue rounded-4">
                        <div class="row p-3">
                            <div class="col-sm-5">
                                <div class="h-100 d-flex align-items-center">
                                    <div class="img w-140-circle">
                                        <img id="createStoryzImage" src="./assets/images/demo.png" alt="demo" class="w-100 h-100">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="h-100 d-flex flex-column justify-content-center">
                                    <div class="mt-20 fs-16 mb-10 fw-semibold text-center text-dark">Storyz
                                        image
                                    </div>
                                    <div class="fs-12 text-center mb-10">
                                        Upload an image which can evoke or symbolize your entire event,
                                        remembering that there may be multiple sub-events.
                                    </div>
                                    <div class="fs-12 fw-500 position-relative file-upload-btn">Upload
                                        <input type="file" id="CreateStoryzInput" class="position-absolute file-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-20">
                <div class="fs-14 fw-500">Storyz name:</div>
                <input type="text" id="storyz-name" class="form-control mt-2" placeholder="enter a Storyz name (describes all the sub-events)">
            </div>
            <div class="position-relative mt-20">
                <div id="storyz-search-list" class="position-absolute d-flex gap-2 flex-column w-100 bg-white storyz-search">
                    <div class="fs-16 divider px-2 py-2" data-value="Generic">Generic</div>
                    <div class="fs-16 divider px-2 py-2" data-value="Wedding">Wedding</div>
                    <div class="fs-16 divider px-2 py-2" data-value="Anniversary">Anniversary</div>
                    <div class="fs-16 divider px-2 py-2" data-value="Auction">Auction</div>
                    <div class="fs-16 divider px-2 py-2" data-value="Autograph/Signing">Autograph/Signing</div>
                    <div class="fs-16 divider px-2 py-2" data-value="Ballroom">Ballroom</div>
                </div>
                <input id="storyz-search" type="text" class="form-control" placeholder="Select option..." style="padding-left: 40px">
                <svg class="position-absolute start-0 top-0" style="margin: 12px" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.6588 19L13.7487 14M15.3853 9.83333C15.3853 13.055 12.8206 15.6667 9.65677 15.6667C6.49298 15.6667 3.92822 13.055 3.92822 9.83333C3.92822 6.61167 6.49298 4 9.65677 4C12.8206 4 15.3853 6.61167 15.3853 9.83333Z" stroke="#828282" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="divider mb-20 pb-20"></div>
            <div class="d-flex justify-content-between gap-2">
                <div class="btn btn-dark rounded-pill w-100 disabled" id="create-storyz-btn">Create Storyz</div>
                <div class="btn w-auto btn-dark rounded-pill px-4 border-dark border fw-semibold bg-transparent text-dark">
                    Skip
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'about-yourself-section\')">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.25 9H3.75M3.75 9L9 14.25M3.75 9L9 3.75" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Back
                </div>
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">cancel</div>
            </div>
        </div>';
    }

    public function displayInviteTeamMemberSection()
    {
        echo '
        <div id="invite-team-member-section" class="w-600 d-none">
            <div class="fs-20 divider pb-3 mb-20">Invite a team member</div>
            <img src="./assets/images/team-member.png" alt="team" class="w-100 rounded-4 mb-20">
            <div class="fs-16 divider mb-20 pb-20">
                Invite additional team members. You can invite anyone from
                your organization to create and edit events, guest lists, and invitations.
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="fs-14 fw-500 mb-2">Enter email address:</div>
                    <div class="position-relative">
                        <div class="tags-input-wrapper form-control">
                            <input id="email-input" type="text" class="form-control" placeholder="enter email address" style="padding-right: 50px">
                        </div>
                        <svg class="position-absolute end-0 top-0 d-flex align-items-center h-100" style="margin-inline: 12px" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 7.5L10.1649 13.2154C10.8261 13.6783 11.1567 13.9097 11.5163 13.9993C11.8339 14.0785 12.1661 14.0785 12.4837 13.9993C12.8433 13.9097 13.1739 13.6783 13.8351 13.2154L22 7.5M6.8 20.5H17.2C18.8802 20.5 19.7202 20.5 20.362 20.173C20.9265 19.8854 21.3854 19.4265 21.673 18.862C22 18.2202 22 17.3802 22 15.7V9.3C22 7.61984 22 6.77976 21.673 6.13803C21.3854 5.57354 20.9265 5.1146 20.362 4.82698C19.7202 4.5 18.8802 4.5 17.2 4.5H6.8C5.11984 4.5 4.27976 4.5 3.63803 4.82698C3.07354 5.1146 2.6146 5.57354 2.32698 6.13803C2 6.77976 2 7.61984 2 9.3V15.7C2 17.3802 2 18.2202 2.32698 18.862C2.6146 19.4265 3.07354 19.8854 3.63803 20.173C4.27976 20.5 5.11984 20.5 6.8 20.5Z" stroke="#0067E1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="divider mb-20 pb-20"></div>
            <div class="d-flex justify-content-between gap-2">
                <div class="btn btn-dark rounded-pill w-100">Continue</div>
                <div class="btn w-100 btn-dark rounded-pill border-dark border fw-semibold bg-transparent text-dark">
                    skip
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'add-location-section\')">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.25 9H3.75M3.75 9L9 14.25M3.75 9L9 3.75" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Back
                </div>
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">cancel</div>
            </div>
        </div>';
    }

    public function displaySetupOfferSection()
    {
        echo '
        <div id="setup-offer-section" class="w-600 d-none">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="fs-20 divider pb-20 mb-20">Setup offer</div>
                </div>
                <div class="col-8">
                    <div class="fs-32">
                        Need help setting up your event? <br>
                        We’ll build it for you.
                    </div>
                    <div class="fs-16 fw-500 mt-3">
                        For a one-time fee, we will set up your event, and provide 10 hours of technical
                        support.
                        <br><br>
                        After setup is complete, you can manage the event yourself, or you can continue to use
                        our professionals to provide maintenance and updates.
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex align-items-center justify-content-center flex-column">
                        <img src="./assets/images/logo.png" alt="logo">
                        <img src="./assets/images/sorry-to-see.png" alt="sorry" class="w-100 mt-20">
                    </div>
                </div>
                <div class="col-12">
                    <div class="divider my-20"></div>
                </div>
            </div>
    
            <div class="d-flex align-items-center justify-content-between gap-4">
                <div class="btn btn-dark rounded-pill w-100">Add this for</div>
                <div class="fs-24 fw-500" style="color:#286BEF">$250</div>
                <div data-bs-dismiss="modal" class="btn px-5 btn-dark rounded-pill border-dark border fw-semibold bg-transparent text-dark">
                    Skip
                </div>
            </div>
    
            <div class="d-flex justify-content-between">
                <div class="fs-16 text-end mt-4 text-black" type="button">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.25 9H3.75M3.75 9L9 14.25M3.75 9L9 3.75" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Back
                </div>
                <div class="fs-16 text-end mt-4 text-black" type="button" onclick="handleSetModal(\'sorry-to-see-section\')">cancel</div>
            </div>
        </div>';
    }

    public function displaySorryToSeeSection()
    {
        echo '
        <div id="sorry-to-see-section" class="w-600 d-none">
            <div class="row align-items-center">
                <div class="col-8">
                    <div class="fs-32">Sorry to see you go!</div>
                    <div class="fs-16 mt-3">
                        Let us know if you want to receive our free newsletter. Our newsletter details upcoming
                        events, new features and services, and tips for planning and running events.
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex align-items-center justify-content-center flex-column">
                        <img src="./assets/images/logo.png" alt="logo">
                        <img src="./assets/images/sorry-to-see.png" alt="sorry" class="w-100 mt-20">
                    </div>
                </div>
                <div class="col-12">
                    <div class="divider my-20"></div>
                    <div class="divider pb-20 my-20">
                        <div class="fs-14 fw-500">Let us know which email to use:</div>
                        <input value="" type="text" placeholder="enter your email address" class="form-control mt-2">
                    </div>
                </div>
            </div>
    
            <div class="d-flex justify-content-between gap-4">
                <div onclick="handleSetModal(\'newsletter-set-section\')" class="btn btn-dark rounded-pill w-100">
                    I like free, send newsletter
                </div>
                <div data-bs-dismiss="modal" class="btn px-5 btn-dark rounded-pill border-dark border fw-semibold bg-transparent text-dark">
                    Nope
                </div>
            </div>
        </div>';
    }

    public function displayNewsletterSetSection()
    {
        echo '
        <div id="newsletter-set-section" class="w-420 d-none">
            <div class="row">
                <div class="col-12 text-center">
                    <svg class="mx-auto" width="94" height="94" viewBox="0 0 94 94" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="94" height="94" rx="47" fill="#53D769" />
                        <path d="M70 30L38.375 64L24 48.5455" stroke="white" stroke-opacity="0.7" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="fs-20 mt-2 divider pb-3 mb-4">Newsletter set up!</div>
                    <div class="fs-18 rounded-pill p-2" style="background: rgba(255, 184, 68, 0.10)">
                        email_address@someemail.com
                    </div>
                    <div class="fs-16 my-4 divider pb-4">
                        You will be receiving our newsletter on an occasional (weekly to monthly) basis. You can
                        cancel at any time.
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between gap-4">
                <div data-bs-dismiss="modal" class="btn btn-dark rounded-pill w-100">Continue</div>
            </div>
        </div>';
    }
}
