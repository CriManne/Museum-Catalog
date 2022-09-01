var urlCategories = "http://127.0.0.1:8080/categories";

$(document).ready(function() {

    fillSelect();

    $("#search-form").submit(function(e) {
        e.preventDefault();

    });


});

function fillSelect() {

    $.ajax({
        url: urlCategories,
        method: "GET",
        success: function(data) {
            array = JSON.parse(data);
            array.forEach(function(elem) {
                $("#category-select").append($('<option>', {
                    value: elem.toLowerCase(),
                    text: elem
                }));
            });
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert("Failed to connect to the server!\n" + xhr.status + " " + thrownError);
        }
    });

}