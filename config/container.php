<?php
use League\Plates\Engine;
use Psr\Container\ContainerInterface;

return [
    'view_path' => 'src/View',
    Engine::class => function(ContainerInterface $c) {
        return new Engine($c->get('view_path'));
    },
    'dns' => 'mysql:host=localhost;dbname=mupin',
    'username' => 'root',
    'psw' => '',
    'PDO' => function(ContainerInterface $c){
        return new PDO($c->get('dns'),$c->get('username'),$c->get('psw'),
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }   

];
