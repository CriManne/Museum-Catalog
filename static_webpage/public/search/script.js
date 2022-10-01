var urlCategories = "http://127.0.0.1:8080/categories";
var urlSearch = "http://127.0.0.1:8080/search";

$(document).ready(function() {

    var data = makeRequest(urlCategories, 'GET');
    fillSelect(data);

    $("#search-form").submit(function(e) {
        e.preventDefault();

        var queryString = "?key=" + $("#artifact-search").val();

        var category = $("#category-select").val();
        if (category != "every") {
            queryString += "&category=" + category;
        }

        var data = makeRequest(urlSearch + queryString, 'GET');

        fillResult(data);

    });


});

function fillResult(data) {
    data.forEach(function(elem) {

        var title = elem.ModelName ? elem.ModelName : elem.Title;
        var elemUrl = "http://127.0.0.1/object/" + elem.ObjectID;

        $("#result-container").append(
            '<div class="card" style="width:18rem;">' +
            '<img src="..." class="card-img-top" alt="...">' +
            '<div class="card-body">' +
            '    <h5 class="card-title">' + title + '</h5>' +
            '    <p class="card-text">Breve descrizione</p>' +
            '    <a href="' + elemUrl + '" class="btn btn-primary">Go to the item</a>' +
            '</div>'
        );
    });
}

function makeRequest(url, method, headers = [], params = []) {
    var returnData;
    $.ajax({
        url: url,
        method: method,
        async: false,
        headers: headers,
        data: params,
        success: function(data) {
            returnData = JSON.parse(data);
        },
        error: function() {
            returnData = null;
        }
    });
    return returnData;
}

function fillSelect(data) {

    if (!data) {
        alert("ERROR");
    } else {
        data.forEach(function(elem) {
            $("#category-select").append($('<option>', {
                value: elem.toLowerCase(),
                text: elem
            }));
        });
    }
}