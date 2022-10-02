const urlUpdateUser = "/api/user/update";

$(document).ready(function() {

    $("#loading-container").remove();
    $("#main-container").removeClass("d-none");

    $("#add-user-form").on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);       

        if($("#Password").val() !== $("#Confirm_Password").val()){
            alert("The two passwords aren't equal!");
            return;
        }

        let response = makeRequest(
            urlUpdateUser,
            'POST',
            headers = {
                "mimeType": "multipart/form-data",
            },
            params = formData);
        createAlert(response);
    });

});