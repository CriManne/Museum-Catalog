$(document).ready(function() {
    
    $("#loading-container").remove();
    $("#main-container").removeClass("d-none");
});


function loadHeader() {

    $("#tb-container").append(
        "<table class='m-auto table table-hover table-responsive w-100' id='table-result'>" +
        "<thead><tr>" +
        "<th scope='col'>ID</th>" +
        "<th scope='col'>Title</th>" +
        "<th scope='col'>Description</th>" +
        "<th scope='col'>Category</th>" +
        "<th scope='col' class='text-center'>Update</th>" +
        "<th scope='col' class='text-center'>Delete</th>" +
        "</tr></thead><tbody>"
    );

}

function displayResult(result) {

    result.forEach(function(elem) {

        let description = [];

        for (const [key, value] of Object.entries(elem.Descriptors)) {
            description.push("<b>" + key + "</b>: " + value)
        }

        $("#table-result").append(
            "<tr role='button' class='artifact-row' data-id='" + elem.ObjectID + "' data-category='"+elem.Category+"'>"+
            "<th scope='row' class='text-decoration-underline' >" + elem.ObjectID +
            "</th><td>" + elem.Title +
            "</td><td>" + description.join(" | ") +
            "</td><td>" + elem.Category +
            "</td><td class='text-center'><button class='btn btn-primary btn-upd' data-id='"+elem.ObjectID+"' data-category='"+elem.Category+"'>Update</button>" +
            "</td><td class='text-center'><button class='btn btn-primary btn-del' data-id='"+elem.ObjectID+"' data-category='"+elem.Category+"'>Delete</button>" +
            "</td>" +
            "</tr>"
        );
    });

    $(".artifact-row").unbind().on('click', function() {
        let id = $(this).attr('data-id');
        let category = $(this).attr('data-category');
        window.location.href = '/private/artifact/update_artifact?id=' + id+"&category="+category;
    });

    $(".btn-del").unbind().on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        let id = $(this).attr('data-id');
        let category = $(this).attr('data-category');
        if (confirm("Sei sicuro di voler eliminare il reperto {" + id + "}?")) {
            let response = makeRequest(urlArtifactDelete + "?id=" + id+"&category="+category, 'DELETE');
            if(response.status=="200"){
                loadResult("?q="+$("#artifact-search").val());
            }
            createAlert(response);
        }
    });

    $(".btn-upd").unbind().on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        let id = $(this).attr('data-id');
        let category = $(this).attr('data-category');
        window.location.href = "/private/artifact/update_artifact?category="+category+"&id="+id;
    });

}