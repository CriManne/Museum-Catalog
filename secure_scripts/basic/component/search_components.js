const urlSearchComponents = '/api/generic/components';
const urlSearchParams = new URLSearchParams(window.location.search);
let category = Object.fromEntries(urlSearchParams.entries())['category'];

$(document).ready(function() {

    $("#tb-container").hide();
    $("#error-alert").hide();

    loadSelect();

    loadResult("");

    $("#category-select").on('change', function() {
        let value = this.value;
        category = value;
        loadResult("&q="+$("#component-search").val());
    })

    $("#component-search-form").on('submit', function(e) {
        e.preventDefault();
        let q = $("#component-search").val();

        let search = "";

        if (q != "") {
            search = "&q=" + q;
        }

        loadResult(search);

    });
});

function loadResult(search) {
    var result = makeRequest(urlSearchComponents + "?category="+category+search);

    $("#tb-container").empty();
    $("#error-alert").empty();

    if (result.status == "404") {
        $("#tb-container").hide();
        createAlert(result);
    } else {
        $("#tb-container").show();
        loadHeader();
        displayResult(result);
        $("#tb-container").append("</tbody></table>");
    }
}

function loadSelect() {
    let data = makeRequest(urlListComponents);
    if (data) {
        data.forEach(function(elem) {
            $("#category-select").append($('<option>', {
                value: elem,
                text: elem,
                selected: elem === category
            }));
        });
    }
}