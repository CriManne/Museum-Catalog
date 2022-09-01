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
    /* PUBLIC AREA / */
    ['GET', '/', Controller\Home::class],

    ['GET','/categories',Controller\CategoriesController::class],
    //[ 'GET', '/hello[/{name}]', Controller\Hello::class ],
    //[ 'GET', '/secret', [ BasicAuth::class, Controller\Secret::class ]],
    /* EMPLOYEE AREA /private/ */
    ['GET', '/login', Controller\LoginController::class],

    //[ 'POST', '/login', [Controller\ValidateLoginController::class,Controller\LoggedHome::class]]    
    [['GET', 'POST'], '/private', Controller\ValidateLoginController::class],
    ['GET','/private/admin',Controller\AdminController::class]
];
