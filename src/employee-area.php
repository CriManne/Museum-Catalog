<?php
    declare(strict_types=1);
    namespace App;    

    session_start();

    require('../vendor/autoload.php');

    use App\Util\UserRepository;
    use League\Plates\Engine;

    $template = new Engine("templates");
    $template->addFolder("layouts","templates/layouts");

    //IF THE LOGIN BUTTON ISN'T SET THEN LOAD THE LOGIN FORM
    if(!isset($_POST['btnLoginSubmit'])){
        echo $template->render('login-page',['title'=>'Login']);
        exit();
    }


    if(!isset($_POST['username']) || !isset($_POST['password'])){
        echo $template->render('login-page',['title'=>'Login','error'=>'Empty credentials']);
        exit();
    }

    $email = $_POST['username'];
    $psw = $_POST['password'];

    $userRepository = new UserRepository();

    $user = $userRepository->getUser($email,$psw);

    if($user==null){
        echo $template->render('login-page',['title'=>'Login','error'=>'Invalid credentials']);
        exit();
    }
    $_SESSION['userLogged'] = json_encode($user);
?>

<form action ='insert-object.php' method ='post'>
    <input type='submit' name='insertObjectBtn' value='Inserisci un nuovo reperto'>
</form>
<form action ='update-object.php' method ='post'>
    <input type='submit' name='updateObjectBtn' value='Modifica un reperto'>
</form>
<form action ='delete-object.php' method ='post'>
    <input type='submit' name='deleteObjectBtn' value='Elimina un reperto'>
</form>