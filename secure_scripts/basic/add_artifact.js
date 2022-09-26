$(document).ready(function () {

    $("#add-form").on("submit", handleSubmit);

});

function handleSubmit(e) {
    e.preventDefault();
    let formData = new FormData(this);

    let response = makeRequest(
        urlAdd,
        'POST',
        headers = {
            "mimeType": "multipart/form-data",
        },
        params = formData
    );

    createAlert(response);
}