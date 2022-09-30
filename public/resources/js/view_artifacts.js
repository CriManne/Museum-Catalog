$(document).ready(function() {
    
    $("#loading-container").remove();
    $("#main-container").removeClass("d-none");
});

function loadHeader() {

    $("#tb-container").append(
        "<table class='m-auto table table-hover table-responsive w-100' id='table-result'>" +
        "<thead><tr>" +
        "<th scope='col'>ID</th>" +
        "<th scope='col'>Title</th>" +
        "<th scope='col'>Description</th>" +
        "<th scope='col'>Category</th>" +
        "</tr></thead><tbody>"
    );

}

function displayResult(result) {

    result.forEach(function(elem) {

        let description = [];

        for (const [key, value] of Object.entries(elem.Descriptors)) {
            description.push("<b>" + key + "</b>: " + value)
        }

        $("#table-result").append(
            "<tr role='button' class='artifact-row' data-id='" + elem.ObjectID + "'><th scope='row' class='text-decoration-underline'>" + elem.ObjectID +
            "</th><td>" + elem.Title +
            "</td><td>" + description.join(" | ") +
            "</td><td>" + elem.Category +
            "</td>" +
            "</tr>"
        );
    });

    $(".artifact-row").unbind().on('click', function() {
        window.location.href = '/view_artifact?id=' + $(this).attr('data-id');
    });

}