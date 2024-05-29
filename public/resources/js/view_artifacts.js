$(document).ready(function () {
    $("#loading-container").remove();
    $("#main-container").removeClass("d-none");
});

function loadHeader() {
    $("#result-container").append("<div class='row p-2 gy-5' id='tb-container'></div>");
}

function displayResult(result) {
    result.forEach(function (elem) {
        let description = [];

        for (const [key, value] of Object.entries(elem.descriptors)) {
            description.push("<b>" + key + "</b>: " + value);
        }

        $("#tb-container").append(
            '<div class="card artifact-card mx-1" role="button" data-id="' +
            elem.objectId +
            '">' +
            '  <div class="card-body">' +
            '        <h4 class="card-title">' +
            elem.title +
            "</h4>" +
            '        <h6 class="card-subtitle text-muted">' +
            elem.objectId +
            "</h6>" +
            '        <p class="card-text p-y-1">' +
            description.join("<br>") +
            "</p>" +
            '        <p class="card-text p-y-1">' +
            elem.category +
            "</p>" +
            "  </div>" +
            "</div>"
        );
    });

    $(".artifact-card")
        .unbind()
        .on("click", function () {
            window.location.href = "/view_artifact?id=" + $(this).attr("data-id");
        });
}
