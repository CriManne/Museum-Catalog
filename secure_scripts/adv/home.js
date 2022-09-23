var urlHome = "/private";

$(document).ready(function() {

    $("#viewUsers").on('click',function(){
        window.location.href =urlHome+"?viewUsers";
    });

    $("#addUser").on('click',function(){
        window.location.href =urlHome+"?addUser";
    });

});