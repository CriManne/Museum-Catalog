<?php

declare(strict_types=1);

use App\Controller;

return [
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
