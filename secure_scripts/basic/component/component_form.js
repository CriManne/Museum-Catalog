$(document).ready(function () {

    $("#component-form").on("submit", handleSubmit);
    $("#loading-container").remove();
    $("#main-container").removeClass("d-none");
   
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
    
    if(response.status=="200"){
        fillUpdateForm();
    }
}

function isEmptyOrSpaces(str){
    str = str.toString();
    return str === null || 
    str === undefined || 
    str === "" ||
    str.trim().length === 0;
}