<?php
use Mupin\Controller;

return [
    [ 'GET', '/', Controller\Home::class ],    
    [ ['GET','POST','PUT','DELETE'], '/user', Controller\UserController::class]    
];
