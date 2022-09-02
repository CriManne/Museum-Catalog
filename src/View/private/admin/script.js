var urlUsers = "/private/users";
var countUsers = 0;
var limitPerPage = 5;
var users = [];
var currentPage = 0;

$(document).ready(function() {

    initializePage();

    $("#page-limit").on('change', function() {
        limitPerPage = this.value;
        currentPage = 0;
        initializePage();
    });

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

    // https://stackoverflow.com/questions/2870371/why-is-jquerys-ajax-method-not-sending-my-session-cookie
});

function initializePage() {

    countUsers = makeRequest(urlUsers + "?count=true", 'GET')["count"];

    var data = makeRequest(urlUsers + "?page=" + currentPage + "&limit=" + limitPerPage, 'GET');
    fillResult(data);

    createPagination();

}

function createPagination() {
    $("#paginations").empty();
    var pages = Math.ceil(countUsers / limitPerPage);

    for (var i = currentPage - 1; i <= currentPage + 1; i++) {
        if (i < 0 || i >= pages) {
            continue;
        }
        $('#paginations').append('<li class="page-item"><button class="page-link" id="change-page-' + i + '" data-page=' + i + '>' + (i + 1) + '</button></li>');

        $("#change-page-" + i).on("click", function() {
            currentPage = parseInt($(this).attr('data-page'));
            initializePage();
        });
    }





}

function fillResult(data) {
    $("#tb-container").empty();

    if (data.length < 1) {
        $("#tb-container").append("No users found");
        return;
    }

    $("#tb-container").append(
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
        xhrFields: {
            withCredentials: true
        },
        success: function(data) {
            returnData = JSON.parse(data);
        },
        error: function() {
            returnData = null;
        }
    });
    return returnData;
}