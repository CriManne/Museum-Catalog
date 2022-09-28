<?php

declare(strict_types=1);

use App\Controller;
use SimpleMVC\Controller\BasicAuth;

return [
    /* PUBLIC AREA */

    //HOME PAGE
    ['GET', '/', Controller\Pages\Public\HomeController::class],

    //LOGIN PAGE
    [['GET', 'POST'], '/login', Controller\Pages\Public\LoginController::class],

    //SINGLE ARTIFACT
    ['GET', '/artifact', Controller\Pages\Public\Artifact\ArtifactController::class],

    //ARTIFACTS 
    ['GET', '/artifacts', Controller\Pages\Public\Artifact\ArtifactsController::class],

    // ______________________________________________________ //

    /* PRIVATE AREA */

    //PRIVATE HOME
    ['GET', '/private', [Controller\BasicAuthController::class, Controller\Pages\Private\HomeController::class]],
    ['GET', '/private/users/', [Controller\BasicAuthController::class, Controller\Pages\Private\HomeController::class]],
    ['GET', '/private/artifact/', [Controller\BasicAuthController::class, Controller\Pages\Private\HomeController::class]],
    ['GET', '/private/component/', [Controller\BasicAuthController::class, Controller\Pages\Private\HomeController::class]],

    //ADD ARTIFACT
    ['GET', '/private/artifact/add_artifact', [Controller\BasicAuthController::class, Controller\Pages\Private\AddArtifactController::class]],

    //UPDATE ARTIFACT
    ['GET', '/private/artifact/update_artifact', [Controller\BasicAuthController::class, Controller\Pages\Private\UpdateArtifactController::class]],

    //ADD COMPONENT
    ['GET', '/private/component/add_component', [Controller\BasicAuthController::class, Controller\Pages\Private\AddComponentController::class]],

    //UPDATE COMPONENT
    ['GET', '/private/component/update_component', [Controller\BasicAuthController::class, Controller\Pages\Private\UpdateComponentController::class]],

    // ______________________________________________________ //

    /* API */

    /* USER */

    //GET USERS
    ['GET', '/api/private/user', [Controller\BasicAuthController::class, Controller\AdvancedAuthController::class, Controller\Api\User\GetController::class]],

    //POST USER
    ['POST', '/api/private/user/create', [Controller\BasicAuthController::class, Controller\AdvancedAuthController::class, Controller\Api\User\PostController::class]],

    //DELETE USER
    ['DELETE', '/api/private/user/delete', [Controller\BasicAuthController::class, Controller\AdvancedAuthController::class, Controller\Api\User\DeleteController::class]],

    /* /USER */

    /* ARTIFACT */

    //CREATE ARTIFACT
    ['POST', '/api/artifact/create', [Controller\BasicAuthController::class, Controller\Api\Artifact\CreateController::class]],

    //UPDATE ARTIFACT
    ['POST', '/api/artifact/update', [Controller\BasicAuthController::class, Controller\Api\Artifact\UpdateController::class]],

    //DELETE ARTIFACT
    ['DELETE', '/api/artifact/delete', [Controller\BasicAuthController::class, Controller\Api\Artifact\DeleteController::class]],

    //SELECT GENERIC ARTIFACT BY ID AND CATEGORY
    ['GET', '/api/generic/artifact', Controller\Api\Artifact\GetGenericByIdController::class],

    //SELECT ALL GENERIC ARTIFACTS OR BY QUERY | BY CATEGORY
    ['GET', '/api/generic/artifacts', Controller\Api\Artifact\GetGenericsController::class],

    //SELECT SPECIFIC ARTIFACT BY ID
    ['GET', '/api/specific/artifact', [Controller\BasicAuthController::class, Controller\Api\Artifact\GetSpecificByIdController::class]],

    /* /ARTIFACT */

    /* COMPONENT */

    //CREATE COMPONENT
    ['POST', '/api/component/create', [Controller\BasicAuthController::class, Controller\Api\Component\CreateController::class]],

    //UPDATE COMPONENT
    ['POST', '/api/component/update', [Controller\BasicAuthController::class, Controller\Api\Component\UpdateController::class]],

    //DELETE COMPONENT
    ['DELETE', '/api/component/delete', [Controller\BasicAuthController::class, Controller\Api\Component\DeleteController::class]],

    //SELECT ALL GENERIC COMPONENTS BY CATEGORY
    ['GET', '/api/generic/components',[Controller\BasicAuthController::class,Controller\Api\Component\GetGenericsController::class]],

    //SELECT SPECIFIC COMPONENT BY ID AND CATEGORY
    ['GET', '/api/specific/component', [Controller\BasicAuthController::class, Controller\Api\Component\GetSpecificByIdController::class]],

    /* /COMPONENT */

    /* IMAGES */

    //GET ALL THE IMAGES BY ID
    ['GET','/api/images',Controller\Api\Images\GetController::class],

    //DELETE ALL THE IMAGES BY ID
    ['DELETE','/api/images/delete',Controller\Api\Images\DeleteController::class],


    /* /IMAGES */


    /* MISC */

    //GET SECURE BASIC SCRIPTS
    ['GET', '/api/scripts', [Controller\BasicAuthController::class, Controller\Api\Scripts\ScriptsController::class]],

    //GET SECURE ADVANCED SCRIPTS
    ['GET', '/api/adv/scripts', [Controller\BasicAuthController::class, Controller\AdvancedAuthController::class, Controller\Api\Scripts\AdvScriptsController::class]],

    //GET ALL THE ARTIFACT'S CATEGORIES
    ['GET', '/api/list/artifacts', Controller\Api\ArtifactsListController::class],

    //GET ALL THE COMPONENTS'S CATEGORIES
    ['GET', '/api/list/components', Controller\Api\ComponentsListController::class],

    /* /MISC */

];
