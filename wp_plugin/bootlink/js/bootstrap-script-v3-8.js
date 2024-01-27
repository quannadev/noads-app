jQuery(document).ready(function($) {
    console.log('Bootstrap script v3.6 loaded');
    $('#bootstrap-link-form').on('submit', function(event) {
        event.preventDefault();
        const linkUrl = $('#link_url').val();
        getLink(linkUrl);
    });

    function getLink(linkUrl) {
        const resultContainer = $('#link-result');
        resultContainer.html(''); // Clear any existing content in the result container
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
            },
            error: function(errorThrown) {
                console.log(errorThrown);
            }
        });
    }
});
