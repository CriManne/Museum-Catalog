const urlHome = "/private";
const urlArtifacts = "/api/generic/artifacts";
const urlArtifactCreate = "/api/artifact/create";
const urlArtifactUpdate = "/api/artifact/update";
const urlArtifactDelete = "/api/artifact/delete";

const urlComponentCreate = "/api/component/create";
const urlComponentUpdate = "/api/component/update";
const urlComponentDelete = "/api/component/delete";

const urlAddArtifactPages = "/private/artifact/add_artifact?category=";
const urlViewComponents = "/private/component?page=view_components&category=";
const urlAddComponentPages = "/private/component/add_component?category=";



$(document).ready(function() {

    $("#view_users").on('click',function(){
        window.location.href =urlHome+"/user?page=view_users";
    });

    $("#add_user").on('click',function(){
        window.location.href =urlHome+"/user?page=add_user";
    });

    $("#view_artifacts").on('click',function(){
        window.location.href =urlHome+"/artifact?page=view_artifacts";
    });

    $("#add_artifact").on('click',function(){
        window.location.href =urlHome+"/artifact?page=choose_artifact_category";
    });

    $("#view_components").on('click',function(){
        window.location.href =urlHome+"/component?page=choose_component_category&next=view";
    });

    $("#add_component").on('click',function(){
        window.location.href =urlHome+"/component?page=choose_component_category&next=add";
    });

});