const urlHome = "/private";

//ARTIFACTS API
const urlArtifactCreate = "/api/artifact/create";
const urlArtifactUpdate = "/api/artifact/update";
const urlArtifactDelete = "/api/artifact/delete";

//COMPONENTS API
const urlComponentCreate = "/api/component/create";
const urlComponentUpdate = "/api/component/update";
const urlComponentDelete = "/api/component/delete";

//USERS
const urlViewUsers = urlHome + "/user/view_users";
const urlAddUser = urlHome + "/user/add_user";


//ARTIFACTS
const urlViewArtifacts = urlHome + "/artifact/view_artifacts";
const urlChooseArtifactCategory = urlHome + "/artifact/choose_artifact_category";
const urlAddArtifactPage = urlHome + "/artifact/add_artifact";

//COMPONENTS
const urlChooseComponentsCategory = urlHome + "/component/choose_component_category";
const urlViewComponents = urlHome + "/component/view_components";
const urlAddComponentPages = urlHome + "/component/add_component";

$(document).ready(function () {

    $("#view_users").on('click', function () {
        window.location.href = urlViewUsers;
    });

    $("#add_user").on('click', function () {
        window.location.href = urlAddUser
    });

    $("#view_artifacts").on('click', function () {
        window.location.href = urlViewArtifacts;
    });

    $("#add_artifact").on('click', function () {
        window.location.href = urlChooseArtifactCategory;
    });

    $("#view_components").on('click', function () {
        window.location.href = urlChooseComponentsCategory + "?next=view";
    });

    $("#add_component").on('click', function () {
        window.location.href = urlChooseComponentsCategory + "?next=add";
    });

});