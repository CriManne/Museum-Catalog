const urlSpecificArtifact = '/api/specific/artifact';
const urlImageDelete = '/api/images/delete';

$(document).ready(function(){
    $("artifact-form").on("reset",function(e){
        e.preventDefault();        
        fillUpdateForm();
    });

    $(".image-del").on("click",function(){
        let imgPath = $(this).attr("data-id");
        let imgName = imgPath.split("/").at(-1);       

        let response = makeRequest(urlImageDelete+"?image="+imgName,'DELETE');
        
        $(this).closest(".card").remove();
        createAlert(response);        

        if($("#images-container").html()==''){
            $("#images-outer-container").remove();
        }
    });
});

function fillUpdateForm(){
    const urlSearchParams = new URLSearchParams(window.location.search);
    const objectID = Object.fromEntries(urlSearchParams.entries())['id'];
    const category = Object.fromEntries(urlSearchParams.entries())['category'];

    const object = makeRequest(urlSpecificArtifact + "?id=" + objectID+"&category="+category);

    if (object.status_code == "404") {
        createAlert(object);
    }else{               
        for(const key in object){
            if(object[key]!=null && typeof object[key] == 'object'){
                let innerObject = object[key];                
                if(!innerObject.hasOwnProperty(length)){
                    innerObject = [innerObject];
                }                
                for(const item of innerObject){
                    console.log(item);
                    for(const key2 in item){     
                        if(key2.includes("ID")){           
                            // $("#"+key2).val(item[key2]); SEEMS TO WORK EVEN WITHOUT THIS
                            $("#"+key2+" option[value='"+item[key2]+"']").attr("selected",true);                        
                        }
                    }
                }
            }else if(object[key]!=null){
                $("#"+key).val(object[key]);
                $("#"+key).prop("defaultValue",object[key]);
            }else{
                $("#"+key).val("");
                $("#"+key).prop("defaultValue","");
            }
        }
        let images = makeRequest(urlImagesNames+"?id="+objectID);

        if(images.length>0){
            $("#images-outer-container").empty();
            $("#images-outer-container").append(
                '<label class="form-label">Immagini già presenti</label>'+
                '<div class="d-flex flex-row gap-2 p-0" id="images-container">'
            );

            images.forEach(function(img){
                $("#images-container").append(
                    '<div class="card" id="'+img+'" style="width: 18rem;">'+
                    '   <img src="'+img+'" class="card-img-top img-thumbnail" style="height:20vh;">'+
                    '   <div class="card-body">'+
                    '       <button type="button" role="button" class="btn btn-danger image-del" data-id="'+img+'">Elimina immagine</button>'+
                    '   </div>'+
                    '</div>'
                );
            });
            $("#images-outer-container").append(                
                '</div>'
            );
        }
    }
}