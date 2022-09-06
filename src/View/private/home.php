<?php

$this->layout('layouts::layout', ['title' => 'Login'])
?>

<?= "Welcome back " . $this->e($user->firstname) . "!"; ?>

<?php
if($this->e($user->Privilege)==="1"){
?>
<p>
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#viewUsers" role="button" aria-expanded="false" aria-controls="viewUsers">
    Visualizza utenti
  </a>
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#addUser" role="button" aria-expanded="false" aria-controls="addUser">
    Aggiungi utente
  </a>
</p>
<div id="alert-container"></div>
<div class="collapse mx-2" id="viewUsers">
  <div class="card card-body">    
  <?php    
    $this->insert('p_admin::viewUsers');    
  ?>
  </div>
</div>
<div class="collapse mx-2" id="addUser">
  <div class="card card-body">    
  <?php    
    $this->insert('p_admin::addUser');    
  ?>
  </div>
</div>
<?php } ?>

GESTIONE REPERTI!


<script>
<?php require('home.js'); ?>
</script>