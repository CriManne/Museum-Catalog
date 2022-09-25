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
    ['GET','/artifact',Controller\Pages\Public\Artifact\ArtifactController::class],

    //ARTIFACTS 
    ['GET','/artifacts',Controller\Pages\Public\Artifact\ArtifactsController::class],

    // ______________________________________________________ //

    /* PRIVATE AREA */

    //PRIVATE HOME
    ['GET', '/private', [Controller\BasicAuthController::class, Controller\Pages\Private\HomeController::class]],

    //ADD ARTIFACT
    ['GET','/private/artifact/add_artifact',[Controller\BasicAuthController::class,Controller\Pages\Private\AddArtifactController::class]],

    // ______________________________________________________ //

    /* API */

    //ENDPOINT TO RETURN ALL THE ARTIFACT'S CATEGORIES
    ['GET', '/api/list/artifacts', Controller\Api\ArtifactsListController::class],

    //GET ALL THE IMAGES BY THE ID
    ['GET','/api/images',Controller\Api\ArtifactImagesController::class],

    /* USER */

    //GET USERS
    ['GET', '/api/private/user', [Controller\BasicAuthController::class, Controller\AdvancedAuthController::class, Controller\Api\User\GetController::class]],

    //POST USER
    ['POST', '/api/private/user', Controller\Api\User\PostController::class],
    //    ['POST','/private/user',[Controller\BasicAuthController::class,Controller\AdvancedAuthController::class,Controller\Api\User\PostController::class]],

    //DELETE USER
    ['DELETE', '/api/private/user', [Controller\BasicAuthController::class, Controller\AdvancedAuthController::class, Controller\Api\User\DeleteController::class]],

    /* /USER */

    /* ARTIFACT */

    //CREATE/UPDATE ARTIFACT 
    //['POST','/api/artifact',[Controller\BasicAuthController::class,Controller\Api\Artifact\PostController::class]],    
    [['POST','PUT'],'/api/artifacts',Controller\Api\Artifact\PostController::class],    

    //GET ARTIFACT
    //ex: /api/artifacts?id=ABC
    ['GET','/api/artifacts',Controller\Api\Artifact\GetController::class],
    //ex: /api/artifacts/search  | api/artifacts/search?category=ABC&q=abc
    ['GET','/api/artifacts/search',Controller\Api\Artifact\SearchArtifactController::class],
    //ex: /api/component/search  | api/component/search?category=ABC&q=abc
    ['GET','/api/component/search',Controller\Api\Artifact\SearchComponentController::class],

    

    //DELETE ARTIFACT
    ['DELETE','/api/artifacts',Controller\Api\Artifact\DeleteController::class],    

    //GET PRIVATE BASIC SCRIPTS
    ['GET','/api/scripts',[Controller\BasicAuthController::class,Controller\Api\Scripts\ScriptsController::class]],
    
    //GET PRIVATE ADVANCED SCRIPTS
    ['GET','/api/adv/scripts',[Controller\BasicAuthController::class, Controller\AdvancedAuthController::class,Controller\Api\Scripts\AdvScriptsController::class]]


];
