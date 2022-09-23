//Limit of records per page
let limitPerPage = 5;

//Current page index
let currentPage = 0;

//Amount of pages displayable
let pages = 0;

//All the users fetched, since there will be not many users the app will fetch all the users in the db and it will store them locally
let users = [];

//The filtered users which is a subset of the users array
let filteredUsers = [];

//URL to fetch users
var urlUsers = "api/private/user";

$(document).ready(function() {
    
    $("#loading-container").remove();
    $("#main-container").removeClass("d-none");
    

    initializePage();

    //When the per page limit select is changed
    $("#page-limit").unbind().on('change', function() {
        limitPerPage = parseInt(this.value);
        currentPage = 0;

        //Reload the page with the new per page limit
        loadPage();
    });

    //When the user type in the search bar
    $("#user-search").unbind().on('input copy paste cut', function() {
        let key = this.value.toLowerCase();
        //Create the subset array filtered by the key
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
        loadPage();
    });

    //When the user go to the prev page
    $("#navigation-prev").unbind().on('click', function() {
        if (currentPage > 0) {
            currentPage--;
            loadPage();
        }
    });

    //When the user go to the next page
    $("#navigation-next").unbind().on('click', function() {
        if (currentPage < Math.ceil(filteredUsers.length / limitPerPage) - 1) {
            currentPage++;
            loadPage();
        }
    });

});


function initializePage() {
    users = filteredUsers = makeRequest(urlUsers);

    loadPage();

}

function loadPage() {
    //If there's no user in the subset
    if (filteredUsers.length < 1) {
        $("#tb-container").empty();
        $("#tb-container").append("No users found");
        $(".pagination").hide();
        $("#page-limit").hide();
        return;
    }

    //Calculate the pages
    pages = Math.ceil(filteredUsers.length / limitPerPage);

    //Reload page until the current page exists
    if (currentPage + 1 > pages) {
        currentPage--;
        loadPage();
    }

    //Calculate the offset of the subset for pagination
    let offset = currentPage * limitPerPage;

    //Fill the table with the data
    fillTable(filteredUsers.slice(offset, offset + limitPerPage));

    //Create the pagination buttons
    createPaginationButtons();
}

function createPaginationButtons() {
    //Reset the paginations container
    $("#paginations").empty();

    $(".pagination").show();
    $("#page-limit").show();

    //If the current page is the 4th or more create a button with the first page link
    if (currentPage >= 3) {
        $('#paginations').append('<li class="page-item"><button class="page-link" id="change-page-' + 0 + '" data-page=' + 0 + '>' + 1 + '</button></li>');
        $("#change-page-0").unbind().on("click", function() {
            currentPage = 0;
            loadPage(filteredUsers);
        });
    }

    //Add the three dots in the pagination
    if (currentPage >= 2) {
        $('#paginations').append('<li class="page-item"><div class="page-link">...</div></li >');
    }

    //Create all the pagination links
    for (let i = currentPage - 1; i <= currentPage + 1; i++) {
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

    //Add the three dots in the pagination
    if (currentPage <= pages - 3) {
        $('#paginations').append('<li class="page-item"><button class="page-link">...</button></li >');
    }

    //Add a button with the last page link
    if (currentPage < pages - 3) {
        $('#paginations').append('<li class="page-item"><button class="page-link" id="change-page-' + (pages - 1) + '" data-page=' + (pages - 1) + '>' + pages + '</button></li>');
        $("#change-page-" + (pages - 1)).unbind().on("click", function() {
            currentPage = pages - 1;
            loadPage(filteredUsers);
        });
    }
}

function fillTable(data) {
    $("#tb-container").empty();

    $("#tb-container").append(
        "<table class='m-auto table table-hover table-responsive w-100' id='table-result'>"
    );
    $('#table-result').append(
        "<thead><tr>" +
        "<th scope='col'>Email</th>" +
        "<th scope='col'>Nome</th>" +
        "<th scope='col'>Cognome</th>" +
        "<th scope='col'>Privilegi</th>" +
        "<th scope='col'>Elimina</th>" +
        "</tr></thead><tbody>"
    );


    data.forEach(function(elem) {

        let email = elem.Email;
        let firstname = elem.firstname;
        let lastname = elem.lastname;
        let privilege = elem.Privilege;

        $("#table-result").append(
            '<tr><th scope="row">' + email +
            '</th><td>' + firstname +
            "</td><td>" + lastname +
            "</td><td>" + (privilege == "1" ? "Amministratore" : "Dipendente") +
            "</td><td>" +
            "<button class='btn btn-primary delete-user' data-id='" + email + "'>Elimina</button>" +
            "</td>" +
            "</tr>"
        );

    });

    //Delete user button handler
    $(".delete-user").unbind().on('click', function() {
        let email = $(this).data("id");
        if (confirm("Sei sicuro di voler eliminare l'utente {" + email + "}?")) {
            let response = makeRequest(urlUsers + "?id=" + email, 'DELETE');
            createAlert(response);
            initializePage();
        }
    });

}

//Custom compare function 
function compare(property, direction) {
    return (a, b) => {
        if (direction == 1) {
            return a[property] > b[property] ? 1 : -1;
        }
        return a[property] < b[property] ? 1 : -1;
    };
}