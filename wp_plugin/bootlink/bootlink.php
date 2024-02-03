<?php
/*
Plugin Name: Bootstrap Link Plugin
Description: Adds a form with Bootstrap styling to submit a link.
Version: 4.0
Author: Quan Nguyen
*/

// Enqueue scripts and styles
function bootstrap_link_enqueue_scripts()
{
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap-link-script', plugins_url('js/bootstrap-script-v3-8.js', __FILE__), array('jquery'), null, true);

    // Pass AJAX parameters to script.js
    wp_localize_script('bootstrap-link-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}

add_action('wp_enqueue_scripts', 'bootstrap_link_enqueue_scripts');

//Get link Function
function GetLink($url)
{
    //trim space url
    $url = trim($url);
    $docker_endpoint = 'https://noads-api.quanna.dev';
    //get env api endpoint
    if (getenv('API_ENDPOINT')) {
        $docker_endpoint = getenv('API_ENDPOINT');
    }
    // API endpoint
    $api_url = $docker_endpoint . '/api/view?url=' .$url;

    // Make the API request
    $args = array(
        'timeout'     => 20
    );
    $response = wp_remote_get($api_url, $args);

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
   <div class="col">
	    <div class="row justify-content-center">
            <div class="col-md-12">
                <form id="bootstrap-link-form" action="#" method="post">
                    <div class="form-group">
                        <label for="link_url">Keyword or url:</label>
                        <input type="text" class="form-control" name="link_url" id="link_url" placeholder="Link or text search" required>
                        <?php wp_nonce_field('get_link_nonce', 'get_link_nonce'); ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
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
    $link_url = isset($_POST['link_url']) ? esc_url($_POST['link_url']) : '';

    $res = GetLink($link_url);

    $titles = explode('|', $res['name']);
    $subtitle = $titles[1];
    $title = $titles[0];
    $episodes = $res['episodes'];
    $medias = $res['media'];
    $thumbnail = $res['thumbnail'];

    //filter m3u8 in medias filter
    $m3u8 = "";
    foreach ($medias as $media) {
        if (strpos($media, 'm3u8') !== false) {
            $m3u8 = esc_url($media);
            //remove m3u8 from medias
            $medias = array_diff($medias, array($media));
            break;
        }
        if (strpos($media, 'mp4') !== false) {
            $m3u8 = esc_url($media);
            //remove m3u8 from medias
            $medias = array_diff($medias, array($media));
            break;
        }
    }
    // Create the unordered list of media links
	$content = "";
    if ($m3u8 != "") {
        $video_player = '[bradmax_video url="' .$m3u8 . '" autoplay="true"]';
        $content = do_shortcode($video_player);
    }
    if($content != "")
    {
       $content = '<div class="card-img-top">' . $content . '</div>';
    }elseif($thumbnail != ""){
        $content = '<img src="' . $thumbnail . '" class="card-img-top" alt="'.$title.'">';
    }


    //check link_url contains youtube
    if (strpos($link_url, 'youtube') !== false) {
         $content = '';
         $medias[] = $m3u8;
    }
    $thumbnail = '<img src="' . $thumbnail . '" class="card-img-top" alt="'.$title.'">';
    // Echo the unordered list of media links
    ?>
    <div class="col">
		<div class="card">
		<?php if ($content !== ""): ?>
             <?= $content; ?>
        <?php endif; ?>

        <div class="card-body">
            <h4 class="card-title"><?php echo esc_html($title); ?></h4>
            <h6 class="card-subtitle mb-2 text-muted"><?php echo esc_html($subtitle); ?></h6>
            <?php foreach ($medias as $index => $mediaLink) : ?>
               <a href="<?php echo esc_url($mediaLink); ?>" target="_blank" class="view-link card-link" data-media-link="<?php echo esc_url($mediaLink); ?>">
                    View Link <?php echo esc_html($index + 1); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="card-footer">
            <?php foreach ($episodes as $index => $es) : ?>
               <li>
                    <a href="#" class="episode-link card-link" data-es-link="<?php echo esc_url($episodes[$index]); ?>">
                       <?php echo esc_html($index); ?>
                   </a>
                </li>
            <?php endforeach; ?>
        </div>
    </div>
    </div>
    <script>
    jQuery(document).ready(function ($) {
        $('.view-link').on('click', function(event) {
            event.preventDefault();
            const mediaLink = $(this).data('media-link');
            window.open(mediaLink, '_blank');
        });
        $('.episode-link').on('click', function(event) {
            event.preventDefault();
            const esLink = $(this).data('es-link');
            //check is m3u8
            if (esLink.indexOf('.m3u8') !== -1) {
                window.open(esLink, '_blank');
                return;
            }

            getLink(esLink);
        });
        function getLink(esLink) {
            const resultContainer = $('#link-result');
            resultContainer.html(''); // Clear any existing content in the result container
            $.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: {
                    action: 'handle_bootstrap_link_request',
                    link_url: esLink,
                    nonce: $('#get_link_nonce').val()
                },
                success: function(response) {
                    console.log("success");
                    resultContainer.html(response);
                },
                error: function(errorThrown) {
                    console.log(errorThrown);
                }
            });
        }
    });
    </script>
    <?php

    wp_die(); // This is required to terminate immediately and return a proper response
}


add_action('wp_ajax_nopriv_handle_bootstrap_link_request', 'handle_bootstrap_link_request');
add_action('wp_ajax_handle_bootstrap_link_request', 'handle_bootstrap_link_request');
