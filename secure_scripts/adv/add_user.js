const urlCreateUser = "/api/user/create";

$(document).ready(function () {

    $("#loading-container").remove();
    $("#main-container").removeClass("d-none");

    $("#add-user-form").on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        let response = makeRequest(
            urlCreateUser,
            'POST',
            headers = {
                "mimeType": "multipart/form-data",
            },
            params = formData);
        createAlert(response);
    });

});