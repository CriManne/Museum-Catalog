$(document).ready(function(){

    $("#add-software-form").on("submit",function(e){
        e.preventDefault();
        let formData =new FormData(this);

        let response = makeRequest(
            urlArtifacts,
            'POST',
            headers = {
                "mimeType": "multipart/form-data",
            },
            params = formData
        );

        createAlert(response);
    });

});