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

    public function displayWantToTestSection() {
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
    

}
