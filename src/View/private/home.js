//URL to fetch users
var urlUsers = "api/private/users";

$(document).ready(function() {


});

//Create alert
function createAlert(response, container = "#alert-container") {
    $(container).prepend(
        '<div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert">' +
        '<p><strong>' + (response.status_code == "200" ? "Success!" : "Error!") + '</strong></p>' + response.message +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
    );
    $("#alert").fadeTo(3000, 0).slideUp(500, function() {
        $(this).remove();
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