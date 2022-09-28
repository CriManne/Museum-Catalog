$(document).ready(function(){
    loadSelect();

    $("#artifact-category-select").on('change', function() {
        let value = this.value;
        window.location.href= urlAddArtifactPages+value;
    })
});

function loadSelect() {
    let data = makeRequest(urlListArtifacts);
    if (data) {
        data.forEach(function(elem) {
            $("#artifact-category-select").append($('<option>', {
                value: elem,
                text: elem
            }));
        });
    }
}