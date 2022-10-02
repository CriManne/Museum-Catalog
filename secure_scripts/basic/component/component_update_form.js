const urlSpecificComponent = '/api/specific/component';
const urlImageDelete = '/api/images/delete';

$(document).ready(function(){
    $("component-form").on("reset",function(e){
        e.preventDefault();        
    });
});

function fillUpdateForm(){
    const urlSearchParams = new URLSearchParams(window.location.search);
    const id = Object.fromEntries(urlSearchParams.entries())['id'];
    const category = Object.fromEntries(urlSearchParams.entries())['category'];

    const object = makeRequest(urlSpecificComponent + "?id=" + id+"&category="+category);
    if (String(object.status).includes("4")) {
        createStaticAlert(object);
        $("#component-form").remove();
    }else{               
        for(const property in object){
            if(object[property]!=null){
                $("#"+property).val(object[property]);
                $("#"+property).prop("defaultValue",object[property]);
            }else{
                $("#"+property).val("");
                $("#"+property).prop("defaultValue","");
            }
        }
    }
}