var urlUsers = "/private/users";
var countUsers = 0;
var limitPerPage = 5;
var users = [];
var filteredUsers = [];
var currentPage = 0;

$(document).ready(function() {

    // for (var i = 0; i < 20; i++) {
    //     $("#result-container").append(
    //         "INSERT INTO User(Email,Password,firstname,lastname,Privilege) VALUES('test" + i + "','admin','test" + i + "','test" + i + "',0);"
    //     );
    // }

    initializePage();

    $("#page-limit").unbind().on('change', function() {
        limitPerPage = parseInt(this.value);
        currentPage = 0;
        loadPage(filteredUsers);
    });

    $("#user-search").unbind().on('input change keyup copy paste cut', function() {
        var key = this.value.toLowerCase();
        filteredUsers = users.filter(function(elem) {
            if (elem.firstname.toLowerCase().includes(key) ||
                elem.lastname.toLowerCase().includes(key) ||
                elem.Email.toLowerCase().includes(key) ||
                key.length < 1) {
                return true;
            }
            return false;
        });
        currentPage = 0;
        loadPage(filteredUsers);
    });

    $("#navigation-prev").unbind().on('click', function() {
        if (currentPage > 0) {
            currentPage--;
            loadPage(filteredUsers);
        }
    });

    $("#navigation-next").unbind().on('click', function() {
        if (currentPage < Math.ceil(filteredUsers.length / limitPerPage) - 1) {
            currentPage++;
            loadPage(filteredUsers);
        }
    });

});

function initializePage() {

    users = makeRequest(urlUsers);
    filteredUsers = users;
    loadPage(users);

}

function loadPage(data) {
    var offset = currentPage * limitPerPage;

    fillResult(data.slice(offset, offset + limitPerPage));

    createPagination();
}

function createPagination() {
    $("#paginations").empty();

    if (filteredUsers.length < 1) {
        $(".pagination").hide();
        $("#page-limit").hide();
    } else {
        $(".pagination").show();
        $("#page-limit").show();
    }

    var pages = Math.ceil(filteredUsers.length / limitPerPage);

    if (currentPage >= 3) {
        $('#paginations').append('<li class="page-item"><button class="page-link" id="change-page-' + 0 + '" data-page=' + 0 + '>' + 1 + '</button></li>');
        $("#change-page-0").unbind().on("click", function() {
            currentPage = 0;
            loadPage(filteredUsers);
        });
    }

    if (currentPage >= 2) {
        $('#paginations').append('<li class="page-item"><button class="page-link">...</button></li >');
    }

    for (var i = currentPage - 1; i <= currentPage + 1; i++) {
        if (i < 0 || i >= pages) {
            continue;
        }
        $('#paginations').append('<li class="page-item"><button class="page-link' + (currentPage == i ? " bg-info" : "") + '" id="change-page-' + i + '" data-page=' + i + '>' + (i + 1) + '</button></li>');

        $("#change-page-" + i).unbind().on("click", function() {
            if (currentPage == parseInt($(this).data('page'))) {
                return;
            }
            currentPage = parseInt($(this).data('page'));
            loadPage(filteredUsers);
        });
    }

    if (currentPage <= pages - 3) {
        $('#paginations').append('<li class="page-item"><button class="page-link">...</button></li >');
    }

    if (currentPage < pages - 3) {
        $('#paginations').append('<li class="page-item"><button class="page-link" id="change-page-' + (pages - 1) + '" data-page=' + (pages - 1) + '>' + pages + '</button></li>');
        $("#change-page-" + (pages - 1)).unbind().on("click", function() {
            currentPage = pages - 1;
            loadPage(filteredUsers);
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
            "</td><td>" + (privilege == "1" ? "ADMIN" : "EMPLOYEE") +
            "</td><td>" +
            "<button class='button update-user' data-id='" + email + "'>Aggiorna</button>" +
            "<button class='button delete-user' data-id='" + email + "'>Elimina</button>" +
            "</td>" +
            "</tr>"
        );

        $(".update-user").unbind().on('click', function() {
            confirm("Sei sicuro di voler aggiornare l'utente {" + $(this).data("id") + "}?");
        });

        $(".delete-user").unbind().on('click', function() {
            var email = $(this).data("id");
            if (confirm("Sei sicuro di voler eliminare l'utente {" + email + "}?")) {
                var result = makeRequest(urlUsers + "?id=" + email, 'DELETE');
                initializePage();
                createSuccessAlert(result.message);
            }
        });
    });
}

function createSuccessAlert(message) {
    $("#result-container").prepend(
        '<div class="alert alert-warning alert-dismissible fade show" role="alert" id="success-alert">' +
        '<p><strong>Success!</strong></p>' + message +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
    );
    $("#success-alert").fadeTo(3000, 0).slideUp(500, function() {
        $(this).remove();
    });
}

function makeRequest(url, method = 'GET', headers = [], params = []) {
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