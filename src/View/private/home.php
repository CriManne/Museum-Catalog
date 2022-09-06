<?php

$this->layout('layouts::layout', ['title' => 'Login'])
?>

<?= "Welcome back " . $this->e($user->firstname) . "!"; ?>

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
    $this->insert('p_admin::admin');    
  ?>
  </div>
</div>
<?php } ?>

GESTIONE REPERTI!