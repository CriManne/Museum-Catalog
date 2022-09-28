const urlObject = "/api/generic/artifact";

$(document).ready(function() {
    const urlSearchParams = new URLSearchParams(window.location.search);
    const objectID = Object.fromEntries(urlSearchParams.entries())['id'];

    const object = makeRequest(urlObject + "?id=" + objectID);

    if (object.status_code == "404") {
        $("#artifact").hide();
        $("#error-alert").removeClass("d-none");
        $("#error-alert").append(object.message);
    }else{
        $("#artifact").removeClass("d-none");

        let images = makeRequest(urlImagesNames+"?id="+objectID);

        if(images.length==1){
            $("#single-img").attr("src",images[0]);
        }

        if(images.length > 1){
            $("#single-img").remove();
            $("#carousel").removeClass("d-none");
            let active = false;
            images.forEach(function(img){
                $("#carousel-images").append(
                    '<div class="carousel-item '+(!active ? 'active' : '')+'">'+
                    '       <img src="'+img+'" class="d-block w-100" style="height:40vh;" alt="...">'+
                    '    </div>'
                );
                active = true;
            });
        }

    }

    // $("#debug-container").append(JSON.stringify(object));
    $("#object-id").append(object.ObjectID);
    $("#object-title").append(object.Title);

    for (const [key, value] of Object.entries(object.Descriptors)) {
        $("#object-description").append("<p><b>" + key + "</b>: " + value + "</p>");
    }

    if(object.Tag!==""){
        $("object-tags").append("Tags: "+object.Tag);
    }
    if(object.Url!==""){
        $("object-url").append("Url: "+object.Url);
    }
    if(object.Note!==""){
        $("object-note").append("Note: "+object.Note);
    }
});

function imageExists(image_url){

    var http = new XMLHttpRequest();

    http.open('HEAD', image_url, false);
    http.send();

    return http.status != 404;

}