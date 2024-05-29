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
            {
                "mimeType": "multipart/form-data",
            },
            formData
        );
        createAlert(response);
    });

});