$(document).ready(function () {

    $("#loading-container").remove();
    $("#main-container").removeClass("d-none");
});


function loadHeader() {

    $("#tb-container").append(
        "<table class='m-auto table table-hover table-responsive w-100' id='table-result'>" +
        "<thead><tr>" +
        "<th scope='col'>Id</th>" +
        "<th scope='col'>Name</th>" +
        "<th scope='col'>Category</th>" +
        "<th scope='col' class='text-center'>Update</th>" +
        "<th scope='col' class='text-center'>Delete</th>" +
        "</tr></thead><tbody>"
    );

}

function displayResult(result) {

    result.forEach(function (elem) {

        $("#table-result").append(
            "<tr role='button' class='component-row' data-id='" + elem.id + "' data-category='" + elem.category + "'>" +
            "<th scope='row' class='text-decoration-underline' >" + elem.id +
            "</th><td>" + elem.name +
            "</td><td>" + elem.category +
            "</td><td class='text-center'><button class='btn btn-primary btn-upd' data-id='" + elem.id + "' data-category='" + elem.category + "'>Update</button>" +
            "</td><td class='text-center'><button class='btn btn-primary btn-del' data-id='" + elem.id + "' data-category='" + elem.category + "'>Delete</button>" +
            "</td>" +
            "</tr>"
        );
    });

    $(".component-row").unbind().on('click', function () {
        let id = $(this).attr('data-id');
        let category = $(this).attr('data-category');
        window.location.href = '/private/component/update_component?id=' + id + "&category=" + category;
    });

    $(".btn-del").unbind().on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        let id = $(this).attr('data-id');
        let category = $(this).attr('data-category');
        if (confirm("Sei sicuro di voler eliminare il componente {" + id + "}?")) {
            let response = makeRequest(urlComponentDelete + "?id=" + id + "&category=" + category, 'DELETE');
            if (response.status === "200") {
                loadResult("&q=" + $("#component-search").val());
            }
            createAlert(response);
        }
    });

    $(".btn-upd").unbind().on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        let id = $(this).attr('data-id');
        let category = $(this).attr('data-category');
        window.location.href = "/private/component/update_component?category=" + category + "&id=" + id;
    });

}