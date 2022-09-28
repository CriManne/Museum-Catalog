const urlHome = "/private";
const urlArtifacts = "/api/generic/artifacts";
const urlArtifactCreate = "/api/artifact/create";
const urlArtifactUpdate = "/api/artifact/update";
const urlArtifactDelete = "/api/artifact/delete";
const urlAddPages = "/private/artifact/add_artifact?category=";



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