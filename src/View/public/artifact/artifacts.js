const urlSearch = '/api/artifact/search';

$(document).ready(function() {

    $("#tb-container").hide();
    $("#error-alert").hide();

    $("#artifact-search-form").on('submit', function(e) {
        e.preventDefault();
        var search = $("#artifact-search").val();

        var result = makeRequest(urlSearch + "?q=" + search);

        $("#tb-container").empty();
        $("#error-alert").empty();

        if (result.status_code == "404") {
            $("#error-alert").show();
            $("#tb-container").hide();
            $("#error-alert").append(result.message);
        } else {
            $("#tb-container").show();
            $("#error-alert").hide();
            loadHeader();
            displayResult(result);
            $("#tb-container").append("</tbody></table>");
        }

    });
});

function loadHeader() {

    $("#tb-container").append(
        "<table class='m-auto table table-hover table-responsive w-100' id='table-result'>" +
        "<thead><tr>" +
        "<th scope='col'>ID</th>" +
        "<th scope='col'>Title</th>" +
        "<th scope='col'>Description</th>" +
        "</tr></thead><tbody>"
    );

}

function displayResult(result) {

    result.forEach(function(elem) {
        $("#table-result").append(
            "<tr role='button' class='artifact-row' data-id='" + elem.ObjectID + "'><th scope='row' class='text-decoration-underline'>" + elem.ObjectID +
            "</th><td>" + elem.Title +
            "</td><td>" + JSON.stringify(elem.Descriptors) +
            "</td>" +
            "</tr>"
        );
    });

    $(".artifact-row").unbind().on('click', function() {
        window.location.href = '/artifact?id=' + $(this).attr('data-id');
    });

}


//Make a request and return a json response
function makeRequest(url, method = 'GET', headers = [], params = []) {
    var returnData = {};
    $.ajax({
        url: url,
        method: method,
        async: false,
        headers: headers,
        data: params,
        xhrFields: {
            withCredentials: true
        },
        success: function(data, textStatus, xhr) {
            returnData = JSON.parse(data);
            returnData.status_code = xhr.status;
        },
        error: function(xhr, status, error) {
            returnData.message = JSON.parse(xhr.responseText).message;
            returnData.status_code = xhr.status;
        }
    });
    return returnData;
}