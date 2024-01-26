document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('bootstrap-link-form').addEventListener('submit', function (event) {
        event.preventDefault();
        var linkUrl = document.getElementById('link_url').value;
        var resultContainer = document.getElementById('link-result');

        // Make an AJAX request to the WordPress admin
        jQuery.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'handle_bootstrap_link_request',
                link_url: linkUrl,
                nonce: $('#get_link_nonce').val()
            },
            success: function (response) {
                console.log(response)
                window.open(response, "_blank");
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
    });

    // Function to display the episode list
    function showEpisodeList(episodes) {
        var episodeListContainer = document.getElementById('episode-list-container');

        // Create a list of media links
        var mediaList = '<ul>';
        for (var i = 0; i < mediaLinks.length; i++) {
            var mediaLink = mediaLinks[i];
            mediaList += '<li><a href="' + mediaLink + '" target="_blank">' + mediaLink + '</a></li>';
        }
        mediaList += '</ul>';

        // Display the media list
        episodeListContainer.innerHTML = mediaList;
    }

    function checkLiveLink(link, itemId) {
        fetch(link, {method: 'HEAD'})
            .then(response => {
                if (response.ok) {
                    document.getElementById(itemId).classList.add('text-success');
                } else {
                    document.getElementById(itemId).classList.add('text-danger');
                }
            })
            .catch(() => {
                document.getElementById(itemId).classList.add('dead-link');
            });
    }
});
