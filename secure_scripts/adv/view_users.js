//Limit of records per page
let limitPerPage = 5;

//Current page index
let currentPage = 0;

//Amount of pages displayable
let pages = 0;

//All the users fetched, since there will be not many users the app will fetch all the users in the db and it will store them locally
let users = [];

let searchTerm = "";

//URL to fetch users
const urlFetchUsers = "/api/user";

const urlDeleteUser = "/api/user/delete";

$(document).ready(function() {
    
    $("#loading-container").remove();
    $("#main-container").removeClass("d-none");
    

    loadPage();

    //When the per page limit select is changed
    $("#page-limit").unbind().on('change', function() {
        limitPerPage = parseInt(this.value);
        currentPage = 0;

        //Reload the page with the new per page limit
        loadPage();
    });

    //When the user type in the search bar
    $("#user-search").unbind().on('input copy paste cut', function() {
        searchTerm = this.value.toLowerCase();
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
        if (currentPage < pages - 1) {
            currentPage++;
            loadPage();
        }
    });

});

function loadPage() {
    let url = urlFetchUsers + "?page=" + currentPage + "&itemsPerPage=" + limitPerPage + "&query=" + searchTerm;

    let response = makeRequest(
        url
    );

    if(response.status === "401"){
        window.location.reload();
    }

    users = response.data;

    //If there's no user in the subset
    if (users.length < 1) {
        $("#tb-container").empty();
        $("#tb-container").append("No users found");
        $(".pagination").hide();
        $("#page-limit").hide();
        return;
    }

    //Calculate the pages
    pages = response.totalPages;

    //Fill the table with the data
    fillTable(users);

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
            loadPage();
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
            loadPage();
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
            loadPage();
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

        let email = elem.email;
        let firstname = elem.firstname;
        let lastname = elem.lastname;
        let privilege = elem.privilege;

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
            let response = makeRequest(urlDeleteUser + "?id=" + email, 'DELETE');
            createAlert(response);
            loadPage();
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