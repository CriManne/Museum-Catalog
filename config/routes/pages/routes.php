<?php

declare(strict_types=1);

use App\Controller;

return [
    /* PAGES */

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
];
