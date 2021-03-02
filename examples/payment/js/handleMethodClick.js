$(function() {
    // Variable to hold request
    var request;
    // Bind to the submit event of our form
    $("#methods").submit(function(event) {
        // Prevent default posting of form - put here to work in case of errors
        event.preventDefault();

        // Abort any pending request
        if (request) {
            request.abort();
        }

        // setup some local variables
        var $input = $(this).find("input");
        var url = $input.data("url");
        var data = $input.data("data");

        request = $.ajax({
            type: "POST",
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                // request.setRequestHeader("Access-Control-Allow-Origin", "*");
            },
            url: url,
            // data: "json=" + escape(JSON.stringify(data)),
            data: data,
            // dataType: "jsonp",
            // crossDomain: true
        });

        // Callback handler that will be called on success
        request.done(function (response, textStatus, jqXHR){
            // Log a message to the console
            console.log("Hooray, it worked!");
        });

        // Callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown){
            // Log the error to the console
            console.error(
                "The following error occurred: "+
                textStatus, errorThrown
            );
        });

        // Callback handler that will be called regardless
        // if the request failed or succeeded
        request.always(function () {
            // Reenable the inputs
            // $inputs.prop("disabled", false);
        });
    });
});