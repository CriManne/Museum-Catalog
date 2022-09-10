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
    ['GET', '/', Controller\Public\HomeController::class],

    //LOGIN PAGE
    [['GET', 'POST'], '/login', Controller\Public\LoginController::class],

    // ______________________________________________________ //

    /* PRIVATE AREA */

    //PRIVATE HOME
    ['GET', '/private', [Controller\Private\BasicAuthController::class, Controller\Private\HomeController::class]],


    // ______________________________________________________ //

    //UTIL ENDPOINT TO RETURN ALL THE ARTIFACT'S CATEGORIES
    ['GET', '/api/categories', Controller\Api\CategoriesController::class],

    //GET USERS
    ['GET', '/api/private/users', [Controller\Private\BasicAuthController::class, Controller\Private\AdvancedAuthController::class, Controller\Api\User\GetController::class]],

    //POST USER
    ['POST', '/api/private/users', Controller\Api\User\PostController::class],
    //    ['POST','/private/users',[Controller\Private\BasicAuthController::class,Controller\Private\AdvancedAuthController::class,Controller\Api\User\PostController::class]],

    //DELETE USERS
    ['DELETE', '/api/private/users', [Controller\Private\BasicAuthController::class, Controller\Private\AdvancedAuthController::class, Controller\Api\User\DeleteController::class]],
];
