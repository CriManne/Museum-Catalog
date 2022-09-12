<?php

/**
 * Skeleton application for SimpleMVC
 * 
 * @link      http://github.com/simplemvc/skeleton
 * @copyright Copyright (c) Enrico Zimuel (https://www.zimuel.it)
 * @license   https://opensource.org/licenses/MIT MIT License
 */

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


    // ______________________________________________________ //

    /* API */

    //UTIL ENDPOINT TO RETURN ALL THE ARTIFACT'S CATEGORIES
    ['GET', '/api/categories', Controller\Api\CategoriesController::class],

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

    //CREATE ARTIFACT
    ['POST','/api/artifact',[Controller\BasicAuthController::class,Controller\Api\Artifact\PostController::class]],    

    //GET ARTIFACT
    ['GET','/api/artifact',Controller\Api\Artifact\GetController::class],
    ['GET','/api/artifact/search',Controller\Api\Artifact\SearchController::class],

    //UPDATE ARTIFACT
    ['PUT','/api/artifact/update',[Controller\BasicAuthController::class,Controller\Api\Artifact\UpdateController::class]],    

    //DELETE ARTIFACT
    ['DELETE','/api/artifact/delete',[Controller\BasicAuthController::class,Controller\Api\Artifact\DeleteController::class]],    


];
