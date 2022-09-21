const urlSearch = '/api/artifacts/search';
const urlCategories = "/api/categories";
let category = "";

$(document).ready(function() {

    $("#tb-container").hide();
    $("#error-alert").hide();

    loadSelect();

    loadResult("");

    $("#category-select").on('change', function() {
        let value = this.value;
        category = value;
        loadResult("?category=" + category);
    })

    $("#artifact-search-form").on('submit', function(e) {
        e.preventDefault();
        let q = $("#artifact-search").val();

        let search = "";

        if (q != "") {
            search = "?q=" + q;
        }

        if (category != "") {
            if (q != "") {
                search += "&";
            } else {
                search += "?";
            }
            search += "category=" + category;
        }

        loadResult(search);

    });
});

function loadResult(search) {
    var result = makeRequest(urlSearch + search);

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
}

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

        let description = [];

        for (const [key, value] of Object.entries(elem.Descriptors)) {
            description.push("<b>" + key + "</b>: " + value)
        }

        $("#table-result").append(
            "<tr role='button' class='artifact-row' data-id='" + elem.ObjectID + "'><th scope='row' class='text-decoration-underline'>" + elem.ObjectID +
            "</th><td>" + elem.Title +
            "</td><td>" + description.join(" | ") +
            "</td>" +
            "</tr>"
        );
    });

    $(".artifact-row").unbind().on('click', function() {
        window.location.href = '/artifact?id=' + $(this).attr('data-id');
    });

}

function loadSelect() {
    let data = makeRequest(urlCategories);
    if (data) {
        data.forEach(function(elem) {
            $("#category-select").append($('<option>', {
                value: elem,
                text: elem
            }));
        });
    }
}