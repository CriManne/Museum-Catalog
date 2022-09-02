var urlUsers = "http://127.0.0.1:8080/private/users";

$(document).ready(function() {

    var data = makeRequest(urlUsers, 'GET');
    fillResult(data);

    // $("#search-form").submit(function(e) {
    //     e.preventDefault();

    //     var queryString = "?key=" + $("#artifact-search").val();

    //     var category = $("#category-select").val();
    //     if (category != "every") {
    //         queryString += "&category=" + category;
    //     }

    //     var data = makeRequest(urlSearch + queryString, 'GET');

    //     fillResult(data);

    // });


});

function fillResult(data) {

    if (data.length < 1) {
        $("#result-container").append("No users found");
        return;
    }

    $("#result-container").append(
        "<table class='table' id='table-result'>"
    );
    $('#table-result').append(
        "<thead><tr><th scope='col'>Email</th><th scope='col'>Nome</th><th scope='col'>Cognome</th><th scope='col'>Privilegi</th><th scope='col'>Operazioni</th></tr></thead><tbody>"
    );

    data.forEach(function(elem) {

        var email = elem.Email;
        var firstname = elem.firstname;
        var lastname = elem.lastname;
        var privilege = elem.Privilege;

        $("#table-result").append(
            '<tr><th scope="row">' + email +
            '</th><td>' + firstname +
            "</td><td>" + lastname +
            "</td><td>" + privilege +
            "</td><td></td>" +
            "</tr>"
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