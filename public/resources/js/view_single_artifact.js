const urlObject = "/api/generic/artifact";

$(document).ready(function() {
    const urlSearchParams = new URLSearchParams(window.location.search);
    const objectID = Object.fromEntries(urlSearchParams.entries())['id'];

    const response = makeRequest(urlObject + "?id=" + objectID);

    if (response.status == "404") {
        $("#artifact").hide();
        createAlert(response);
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
    
    $("#object-id").append(response.ObjectID);
    $("#object-title").append(response.Title);

    for (const [key, value] of Object.entries(response.Descriptors)) {
        $("#object-description").append("<p><b>" + key + "</b>: " + value + "</p>");
    }

    if(response.Tag!==""){
        $("#object-tags").append("<b>Tags:</b> "+response.Tag);
    }
    if(response.Url!==""){
        const conditions = ["http://","https://"];
        if(!conditions.some(el => response.Url.includes(el))){
            response.Url = "https://"+response.Url;
        }
        $("#object-url").append("<b>Url:</b> <a href='"+response.Url+"'>Segui il link</a>");
    }
    if(response.Note!==""){
        $("#object-note").append("<b>Note:</b> "+response.Note);
    }
});