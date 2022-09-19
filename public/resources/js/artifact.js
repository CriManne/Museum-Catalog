const urlObject = "/api/artifact";

$(document).ready(function() {
    const urlSearchParams = new URLSearchParams(window.location.search);
    const objectID = Object.fromEntries(urlSearchParams.entries())['id'];

    const object = makeRequest(urlObject + "?id=" + objectID);

    if (object.status_code == "404") {
        $("#artifact").hide();
        $("#error-alert").append(object.message);
    } else {
        $("#error-alert").hide();
    }

    // $("#debug-container").append(JSON.stringify(object));
    $("#object-id").append(object.ObjectID);
    $("#object-title").append(object.Title);

    for (const [key, value] of Object.entries(object.Descriptors)) {
        $("#object-description").append("<p><b>" + key + "</b>: " + value + "</p>");
    }
});