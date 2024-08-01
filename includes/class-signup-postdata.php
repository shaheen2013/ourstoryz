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

 class Signup_Modal_Info {
     
    public function signup_post() {
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
                    <input type="checkbox" class="form-check-input" id="check<?php echo $count; ?>" onclick="checkCheckboxes()">
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
    
    

}
