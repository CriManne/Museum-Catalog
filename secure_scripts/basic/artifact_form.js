$(document).ready(function () {

    $("#artifact-form").on("submit", handleSubmit);

});

function handleSubmit(e) {
    e.preventDefault();
    let formData = new FormData(this);

    let response = makeRequest(
        urlForm,
        'POST',
        headers = {
            "mimeType": "multipart/form-data",
        },
        params = formData
    );

    createAlert(response);
}