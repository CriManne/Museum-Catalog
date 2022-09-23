var urlHome = "/private";

$(document).ready(function() {

    $("#view_users").on('click',function(){
        window.location.href =urlHome+"?view_users";
    });

    $("#add_user").on('click',function(){
        window.location.href =urlHome+"?add_user";
    });

    $("#view_artifacts").on('click',function(){
        window.location.href =urlHome+"?view_artifacts";
    });

    $("#add_user").on('click',function(){
        window.location.href =urlHome+"?add_user";
    });

});