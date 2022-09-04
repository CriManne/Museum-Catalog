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
    ['GET', '/', Controller\Public\Home::class],

    //UTIL ENDPOINT TO RETURN ALL THE ARTIFACT'S CATEGORIES
    ['GET','/categories',Controller\Public\CategoriesController::class],

    //SEARCH CONTROLLER
    ['GET','/search',Controller\Public\SearchController::class],    
    
    //LOGIN PAGE
    ['GET', '/login', Controller\Public\LoginController::class],

    // ______________________________________________________ //

    /* PRIVATE AREA */

    //GET USERS
    ['GET','/private/users',Controller\Private\UsersController::class],

    //VALIDATE LOGIN
    [['GET','POST'], '/private', Controller\Private\ValidateLoginController::class],

    //ADMIN PAGE
    ['GET','/private/admin',[Controller\Private\AuthorizationController::class,Controller\Private\AdminController::class]]
];
