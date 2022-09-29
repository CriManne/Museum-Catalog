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
            $("#images-outer-container").hide();
        }
    });
});

function fillUpdateForm(){
    const urlSearchParams = new URLSearchParams(window.location.search);
    const objectID = Object.fromEntries(urlSearchParams.entries())['id'];
    const category = Object.fromEntries(urlSearchParams.entries())['category'];

    const object = makeRequest(urlSpecificArtifact + "?id=" + objectID+"&category="+category);

    if (object.status == "404") {
        createAlert(object);
    }else{               
        for(const property in object){
            if(object[property]!=null && typeof object[property] == 'object'){
                let innerObject = object[property]; 
                
                //IF IS NOT AN ARRAY THEN TURN IT INTO AN ARRAY
                if(!innerObject.hasOwnProperty(length)){
                    innerObject = [innerObject];
                }                
                let selectResetted = false;
                for(const item of innerObject){                                        
                    for(const property2 in item){                             
                        if(property2.includes("ID")){ 
                            if(!selectResetted){
                                $("#"+property2+" option").attr("selected",false);          
                                selectResetted = true;
                            }
                            $("#"+property2+" option[value='"+item[property2]+"']").attr("selected",true);                        
                        }
                    }
                }
            //IF THE VALUE OF THE PROPERTY IS NOT AN OBJECT AND IS NOT NULL
            }else if(object[property]!=null){
                $("#"+property).val(object[property]);
                $("#"+property).prop("defaultValue",object[property]);
            }else{
                $("#"+property).val("");
                $("#"+property).prop("defaultValue","");
            }
        }
        let images = makeRequest(urlImagesNames+"?id="+objectID);

        if(images.length>0){
            $("#images-outer-container").empty();
            $("#images-outer-container").show();
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