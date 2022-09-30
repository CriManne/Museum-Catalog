$(document).ready(function(){
    loadSelect();

    $("#component-category-select").unbind().on('change', function() {
        const urlSearchParams = new URLSearchParams(window.location.search);
        const next = Object.fromEntries(urlSearchParams.entries())['next'];

        if(next !== undefined && (next === "add" || next === "view")){
            let value = this.value;
            if(next === "add"){
                window.location.href= urlAddComponentPages+"?category="+value;
            }else{
                window.location.href= urlViewComponents+"?category="+value;
            }
            return;
        }

        alert("Wrong url! Please go back!");                   
    });
});

function loadSelect() {
    let data = makeRequest(urlListComponents);
    if (data) {
        data.forEach(function(elem) {
            $("#component-category-select").append($('<option>', {
                value: elem,
                text: elem
            }));
        });
    }
}