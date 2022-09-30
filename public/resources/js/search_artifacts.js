let category;

$(document).ready(function() {

    $("#tb-container").hide();
    $("#error-alert").hide();

    loadSelect();

    loadResult();

    $("#category-select").on('change', function() {
        let value = this.value;
        category = value;
        loadResult("?category=" + category+"&q="+$("#artifact-search").val());
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

function loadResult(search="") {
    var result = makeRequest(urlSearchArtifacts + search);

    $("#tb-container").empty();
    $("#error-alert").empty();

    if (result.status == "404") {
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

function loadSelect() {
    let data = makeRequest(urlListArtifacts);
    if (data) {
        data.forEach(function(elem) {
            $("#category-select").append($('<option>', {
                value: elem,
                text: elem
            }));
        });
    }
}