jQuery(document).ready(function ($) {

    // Add a click event listener to your button
    $(document.body).on('click', '.skip_shipment', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        // Show the popup when the button is clicked
        MicroModal.init();
        MicroModal.show('wc_sub_box_skip_shipment_popup');
    });

    // Add click event listener for the Confirm button in the popup
    $(document.body).on('click', '#wc_sub_box_skip_shipment_popup .confirm-skip-shipment', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        // Show loader before making the AJAX call
        show_loader();

        // Make an AJAX call to handle the confirmation
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                'action': 'handle_skip_shipment_confirmation',
                'nonce': ajax_object.nonce,
                'subscription_id': $('#wc_sub_box_skip_shipment_popup').attr('data-subscription_id'),
            },
            beforeSend: function () {
                // This function will be called before the AJAX request is sent
                show_loader();
            },
            success: function (response) {
                // Reload the page on success
                location.reload();
            },
            error: function (error) {
                // Hide loader on error
                hide_loader();
                console.error('Error:', error);
            },
        });
    });

    function show_loader() {
        // Show the loader by setting its display property to 'block'
        $('.wc-sub-box-extra-action-loader-wrapper').show();
    }


    // Function to hide the loader
    function hide_loader() {
        // Hide the loader by setting its display property to 'none'
        $('.wc-sub-box-extra-action-loader-wrapper').hide();
    }
});

