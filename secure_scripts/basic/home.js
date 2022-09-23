var urlHome = "/private";

$(document).ready(function() {

    $("#viewUsers").on('click',function(){
        window.location.href =urlHome+"?viewUsers";
    });

    $("#addUser").on('click',function(){
        window.location.href =urlHome+"?addUser";
    });

    $("#viewArtifacts").on('click',function(){
        window.location.href =urlHome+"?viewArtifacts";
    });

    $("#addUser").on('click',function(){
        window.location.href =urlHome+"?addUser";
    });

});