<?php
$this->layout('layouts::layout', ['title' => 'Admin'])
?>

<?php

use App\Exception\ServiceException;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Util\DIC;

    $userService = new UserService(new UserRepository(DIC::getContainer()->get("PDO")));

    try{
        $users = $userService->selectAll();

        echo "<table><thead><tr><td>Nome</td><td>Cognome</td><td>Email</td><td>Privilegi</td></tr></thead><tbody>";


        foreach($users as $user){
            echo "<tr><td>$user->firstname</td><td>$user->lastname</td><td>$user->Email</td><td>$user->Privilege</td></tr>";
        }
        echo "</tbody></table>";
    }catch(ServiceException $e){
        echo $e->getMessage();
    }

?>