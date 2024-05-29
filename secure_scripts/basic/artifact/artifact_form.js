$(document).ready(function () {

    $("#artifact-form").on("submit", handleSubmit);
    $("#loading-container").remove();
    $("#main-container").removeClass("d-none");

});

function handleSubmit(e) {
    e.preventDefault();

    let formData = new FormData(this);

    let toBeRemoved = [];

    for (let [key, value] of formData) {
        if (isEmptyOrSpaces(value)) {
            toBeRemoved.push(key);
        }
    }

    for (const value of toBeRemoved) {
        formData.delete(value);
    }

    let response = makeRequest(
        urlForm,
        'POST',
        {
            "mimeType": "multipart/form-data",
        },
        formData
    );

    createAlert(response);
    if (response.status === 200) {
        fillUpdateForm();
    }
}

function isEmptyOrSpaces(str) {
    str = str.toString();
    return str === null ||
        str === undefined ||
        str === "" ||
        str.trim().length === 0;
}