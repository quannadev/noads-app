jQuery(document).ready(function($) {
    $('#bootstrap-link-form').on('submit', function(event) {
        event.preventDefault();

        var linkUrl = $('#link_url').val();
        var resultContainer = $('#link-result');

        // Make an AJAX request to the WordPress admin
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'handle_bootstrap_link_request',
                link_url: linkUrl,
                nonce: $('#get_link_nonce').val()
            },
            success: function(response) {
                console.log("success");
                resultContainer.html(response);
                // window.open(response, '_blank');
            },
            error: function(errorThrown) {
                console.log(errorThrown);
            }
        });
    });
});
