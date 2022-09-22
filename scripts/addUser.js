let urlUsers = "api/private/user";

$(document).ready(function() {

    $("#add-user-form").on('submit', function(e) {
        e.preventDefault();
        var data = $("#add-user-form").serializeArray();
        var object = {};
        data.forEach(element => {
            object[element.name] = element.value;
        });

        var response = makeRequest(
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