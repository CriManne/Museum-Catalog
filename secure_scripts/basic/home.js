var urlHome = "/private";

$(document).ready(function() {

    $("#view_users").on('click',function(){
        window.location.href =urlHome+"?page=view_users";
    });

    $("#add_user").on('click',function(){
        window.location.href =urlHome+"?page=add_user";
    });

    $("#view_artifacts").on('click',function(){
        window.location.href =urlHome+"?page=view_artifacts";
    });

    $("#add_artifact").on('click',function(){
        window.location.href =urlHome+"?page=add_artifact";
    });

    $("#view_components").on('click',function(){
        window.location.href =urlHome+"?page=view_components";
    });

    $("#add_component").on('click',function(){
        window.location.href =urlHome+"?page=add_component";
    });

});