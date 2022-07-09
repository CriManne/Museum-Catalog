<?php
    declare(strict_types=1);

    namespace App;

    require('vendor/autoload.php');

    use League\Plates\Engine;

    $template = new Engine("src/templates");
    $template->addFolder("layouts","src/templates/layouts");

    //IF THE LOGIN BUTTON IS SET THEN RENDER THE LOGIN FORM
    if(isset($_POST['loginPageBtn'])){
        echo $template->render("login-page",['title'=>'LOGIN']);    
        exit();
    }

    echo $template->render("main-page",['title'=>'CATALOGO MUPIN']);
?>