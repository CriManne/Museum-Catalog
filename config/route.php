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
    [ 'GET', '/', Controller\Home::class ],
    //[ 'GET', '/hello[/{name}]', Controller\Hello::class ],
    //[ 'GET', '/secret', [ BasicAuth::class, Controller\Secret::class ]],
    /* EMPLOYEE AREA /private/ */
    [ 'GET', '/private', Controller\LoginPageController::class],
    [ 'POST', '/login', [Controller\ValidateLoginController::class,Controller\LoggedHome::class]]    
];
