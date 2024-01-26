<?php
/*
Plugin Name: Bootstrap Link Plugin
Description: Adds a form with Bootstrap styling to submit a link.
Version: 1.0
Author: Your Name
*/

// Enqueue scripts and styles
function bootstrap_link_enqueue_scripts()
{
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap-link-script', plugins_url('js/bootstrap-link-script.js', __FILE__), array('jquery'), null, true);

    // Pass AJAX parameters to script.js
    wp_localize_script('bootstrap-link-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('bootstrap_link_nonce') // Create nonce for security
    ));
}

add_action('wp_enqueue_scripts', 'bootstrap_link_enqueue_scripts');

//Getlink Function

function getLink($url)
{
    // API endpoint
    $api_url = 'http://docker.for.mac.host.internal:8080/api/view?url=' . urlencode($url);

    // Make the API request
    $response = wp_remote_get($api_url);

    // Check for errors
    if (is_wp_error($response)) {
        return json_encode(array('error' => 'Error: ' . $response->get_error_message()));
    }

    // Retrieve the API response body
    $body = wp_remote_retrieve_body($response);

    // Decode the JSON response
    $decoded_body = json_decode($body, true);

    // Check if decoding was successful
    if ($decoded_body === null) {
        return json_encode(array('error' => 'Error decoding JSON response'));
    }

    return $decoded_body;
}


// Shortcode callback function
function bootstrap_link_form_shortcode()
{
    ob_start(); // Start output buffering
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form id="bootstrap-link-form" action="#" method="post">
                    <div class="form-group">
                        <label for="link_url">Enter your link URL:</label>
                        <input type="url" class="form-control" name="link_url" id="link_url"
                               placeholder="https://example.com" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Get Link</button>
                </form>
                <div id="link-result" class="mt-3"></div>
            </div>
        </div>
    </div>

    <?php
    return ob_get_clean(); // Return buffered content
}

add_shortcode('bootstrap_link_form', 'bootstrap_link_form_shortcode');

// AJAX callback function
function handle_bootstrap_link_request()
{
    check_ajax_referer('bootstrap_link_nonce', 'nonce');

    $link_url = isset($_POST['link_url']) ? esc_url($_POST['link_url']) : '';

    $res = getLink($link_url);

    // Echo the unordered list of media links
    ?>
    <ul class="list-group" id="liveLinkList">
        <?php
        foreach ($res['media'] as $index => $mediaLink) {
            // Use JavaScript to check if the link is live
            ?>
            <li class="list-group-item" id="mediaLinkItem_<?php echo $index; ?>">
                <a href="<?php echo esc_url($mediaLink); ?>" target="_blank">View Link</a>
            </li>
            <?php
        }
        ?>
    </ul>
    <?php

    wp_die(); // This is required to terminate immediately and return a proper response
}


add_action('wp_ajax_nopriv_handle_bootstrap_link_request', 'handle_bootstrap_link_request');
add_action('wp_ajax_handle_bootstrap_link_request', 'handle_bootstrap_link_request');
