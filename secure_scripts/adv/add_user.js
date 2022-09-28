const urlCreateUser = "/api/private/user/create";

$(document).ready(function() {

    $("#loading-container").remove();
    $("#main-container").removeClass("d-none");

    $("#add-user-form").on('submit', function(e) {
        e.preventDefault();
        let data = $("#add-user-form").serializeArray();
        let object = {};
        data.forEach(element => {
            object[element.name] = element.value;
        });

        let response = makeRequest(
            urlUsers,
            'POST',
            headers = {
                "mimeType": "multipart/form-data",
            },
            params = object);
        createAlert(response);

        if (response.status_code == "200") {
            initializePage();
        }
    });

});