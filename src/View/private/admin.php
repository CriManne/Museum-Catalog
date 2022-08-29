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

        echo "<table class='table'><thead><tr>
        <th scope='col'>Email</th>
        <th scope='col'>Nome</th>
        <th scope='col'>Cognome</th>
        <th scope='col'>Privilegi</th>
        <th scope='col'>Operazioni</th>
        </tr></thead><tbody>";


        foreach($users as $user){
            echo "<tr><th scope='row'>$user->Email</th><td>$user->firstname</td><td>$user->lastname</td><td>$user->Privilege</td></tr>";
        }
        echo "</tbody></table>";
    }catch(ServiceException $e){
        echo $e->getMessage();
    }

?>