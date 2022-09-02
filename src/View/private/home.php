<?php
session_start();
$this->layout('layouts::layout', ['title' => 'Login'])
?>

<?= "Welcome back " . $this->e($user->firstname) . "!"; ?>

<?php

$_SESSION['user_email'] = $this->e($user->Email);
$_SESSION['privilege'] = $this->e($user->Privilege);

?>

<?php
if($this->e($user->Privilege)==="1"){
?>
<p>
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
    Gestione utenti
  </a>
</p>
<div class="collapse mx-2" id="collapseExample">
  <div class="card card-body">
    
  <?php    
    $this->insert('private::admin');    
  ?>
  </div>
</div>
<?php } ?>

GESTIONE REPERTI!