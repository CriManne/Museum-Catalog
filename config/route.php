<?php

declare(strict_types=1);

use App\Controller;

return [
    /* PUBLIC AREA */

    //HOME PAGE
    ['GET', '/', [Controller\Middlewares\EnforceDBConnectionMiddleware::class, Controller\Pages\Public\HomeBaseController::class]],

    //LOGIN PAGE
    [['GET', 'POST'], '/login', [Controller\Middlewares\EnforceDBConnectionMiddleware::class, Controller\Pages\Public\LoginBaseController::class]],

    //SINGLE ARTIFACT
    ['GET', '/view_artifact', [Controller\Middlewares\EnforceDBConnectionMiddleware::class, Controller\Pages\Public\Artifact\ArtifactBaseController::class]],

    //ALL THE ARTIFACTS 
    ['GET', '/catalog', [Controller\Middlewares\EnforceDBConnectionMiddleware::class, Controller\Pages\Public\Artifact\ArtifactsBaseController::class]],

    // ______________________________________________________ //

    /* PRIVATE AREA */

    //PRIVATE HOME
    ['GET', '/private', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Pages\Private\HomeBaseController::class]],

    //UPDATE PROFILE    
    ['GET', '/private/profile', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Pages\Private\User\ViewProfileBaseController::class]],

    //------------USERS--------------

    //VIEW USERS    
    ['GET', '/private/user/view_users', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Middlewares\AdvancedAuthMiddleware::class, Controller\Pages\Private\User\ViewUsersBaseController::class]],

    //ADD USER
    ['GET', '/private/user/add_user', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Middlewares\AdvancedAuthMiddleware::class, Controller\Pages\Private\User\AddBaseController::class]],

    //------------ARTIFACTS--------------

    //VIEW ARTIFACTS
    ['GET', '/private/artifact/view_artifacts', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Pages\Private\Artifact\ViewArtifactsBaseController::class]],

    //CHOOSE CATEGORY
    ['GET', '/private/artifact/choose_artifact_category', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Pages\Private\Artifact\ChooseCategoryBaseController::class]],

    //ADD ARTIFACT
    ['GET', '/private/artifact/add_artifact', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Pages\Private\Artifact\AddBaseController::class]],

    //UPDATE ARTIFACT
    ['GET', '/private/artifact/update_artifact', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Pages\Private\Artifact\UpdateBaseController::class]],

    //------------COMPONENTS--------------
    //VIEW COMPONENTS
    ['GET', '/private/component/view_components', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Pages\Private\Component\ViewComponentsBaseController::class]],

    //CHOOSE CATEGORY
    ['GET', '/private/component/choose_component_category', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Pages\Private\Component\ChooseCategoryBaseController::class]],

    //ADD COMPONENT
    ['GET', '/private/component/add_component', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Pages\Private\Component\AddBaseController::class]],

    //UPDATE COMPONENT
    ['GET', '/private/component/update_component', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Pages\Private\Component\UpdateBaseController::class]],

    // ______________________________________________________ //

    /* API */

    /* USER */

    //GET USERS
    ['GET', '/api/user', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Middlewares\AdvancedAuthMiddleware::class, Controller\Api\User\GetBaseController::class]],

    //POST USER
    ['POST', '/api/user/create', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Middlewares\AdvancedAuthMiddleware::class, Controller\Api\User\PostBaseController::class]],

    //UPDATE USER
    ['POST', '/api/user/update', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Api\User\UpdateBaseController::class]],

    //DELETE USER
    ['DELETE', '/api/user/delete', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Middlewares\AdvancedAuthMiddleware::class, Controller\Api\User\DeleteBaseController::class]],

    /* /USER */

    /* ARTIFACT */

    //CREATE ARTIFACT
    ['POST', '/api/artifact/create', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Api\Artifact\CreateBaseController::class]],

    //UPDATE ARTIFACT
    ['POST', '/api/artifact/update', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Api\Artifact\UpdateBaseController::class]],

    //DELETE ARTIFACT
    ['DELETE', '/api/artifact/delete', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Api\Artifact\DeleteBaseController::class]],

    //SELECT GENERIC ARTIFACT BY ID AND CATEGORY
    ['GET', '/api/generic/artifact', [Controller\Middlewares\EnforceDBConnectionMiddleware::class, Controller\Api\Artifact\GetGenericByIdBaseController::class]],

    //SELECT ALL GENERIC ARTIFACTS OR BY QUERY | BY CATEGORY
    ['GET', '/api/generic/artifacts', [Controller\Middlewares\EnforceDBConnectionMiddleware::class, Controller\Api\Artifact\GetGenericsBaseController::class]],

    //SELECT SPECIFIC ARTIFACT BY ID
    ['GET', '/api/specific/artifact', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Api\Artifact\GetSpecificByIdBaseController::class]],

    /* /ARTIFACT */

    /* COMPONENT */

    //CREATE COMPONENT
    ['POST', '/api/component/create', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Api\Component\CreateBaseController::class]],

    //UPDATE COMPONENT
    ['POST', '/api/component/update', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Api\Component\UpdateBaseController::class]],

    //DELETE COMPONENT
    ['DELETE', '/api/component/delete', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Api\Component\DeleteBaseController::class]],

    //SELECT ALL GENERIC COMPONENTS BY CATEGORY
    ['GET', '/api/generic/components', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Api\Component\GetGenericsBaseController::class]],

    //SELECT SPECIFIC COMPONENT BY ID AND CATEGORY
    ['GET', '/api/specific/component', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Api\Component\GetSpecificByIdBaseController::class]],

    /* /COMPONENT */

    /* IMAGES */

    //GET ALL THE IMAGES BY ID
    ['GET', '/api/images', Controller\Api\Images\GetBaseController::class],

    //DELETE ALL THE IMAGES BY ID
    ['DELETE', '/api/images/delete', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Api\Images\DeleteBaseController::class]],


    /* /IMAGES */


    /* MISC */

    //GET SECURE BASIC SCRIPTS
    ['GET', '/api/scripts', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Api\Scripts\ScriptsBaseController::class]],

    //GET SECURE ADVANCED SCRIPTS
    ['GET', '/api/adv/scripts', [Controller\Middlewares\BasicAuthMiddleware::class, Controller\Middlewares\AdvancedAuthMiddleware::class, Controller\Api\Scripts\AdvScriptsBaseController::class]],

    //GET ALL THE ARTIFACT'S CATEGORIES
    ['GET', '/api/list/artifacts', [Controller\Middlewares\EnforceDBConnectionMiddleware::class, Controller\Api\ArtifactsListController::class]],

    //GET ALL THE COMPONENTS'S CATEGORIES
    ['GET', '/api/list/components', [Controller\Middlewares\EnforceDBConnectionMiddleware::class, Controller\Api\ComponentsListController::class]],

    /* /MISC */

];
