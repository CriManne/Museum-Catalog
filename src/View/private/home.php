<?php

$this->layout('layouts::layout', ['title' => 'Login'])
?>

<?php $this->push('styles') ?>
<link rel="stylesheet" href="/resources/css/styles.css">
<script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
<?php $this->end() ?>
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
  <!-- Navbar Brand-->
  <a href="/private" class="navbar-brand ps-3"><img src="favicon.ico" width="30" height="30" class="d-inline-block align-top mx-2">Gestione museo</a>
  <!-- Sidebar Toggle-->
  <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
  <!-- Navbar-->
  <ul class="navbar-nav d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li><a class="dropdown-item" href="#!">
            <form action='/private' method='GET'>
              <input type='submit' class="dropdown-item" name='logout-btn' value='Logout'>
            </form>
          </a></li>
      </ul>
    </li>
  </ul>
</nav>
<div id="layoutSidenav">
  <div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
      <div class="sb-sidenav-menu">
        <div class="nav">
          <?php
          if ($this->e($user->Privilege) === "1") {
          ?>
            <div class="sb-sidenav-menu-heading">Gestione utenti</div>
            <div class="nav-link" role="button" id="viewUsers">
              <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
              Visualizza utenti
            </div>
            <div class="nav-link" role="button" id="addUser">
              <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
              Aggiungi utente
            </div>
          <?php } ?>
          <div class="sb-sidenav-menu-heading">Gestione reperti</div>
          <div class="nav-link" role="button">
            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
            Visualizza reperti
          </div>
          <div class="nav-link" role="button">
            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
            Aggiungi reperti
          </div>
        </div>
      </div>
    </nav>
  </div>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4" id="left-container">
        <h1 class="mt-4">Dashboard<?= " di " . $this->e($user->firstname) . "!"; ?></h1>
        <?php if ($this->e($user->Privilege) === "1" && isset($_GET['viewUsers'])) {
          $this->insert('p_admin::viewUsers');
        } else if ($this->e($user->Privilege) === "1" && isset($_GET['addUser'])) {
          $this->insert('p_admin::addUser');
        } else if (isset($_GET['viewArtifacts'])) {
          $this->insert('p_admin::viewUsers');
        } else if (isset($_GET['addArtifacts'])) {
          $this->insert('p_admin::viewUsers');
        } else {
          echo "<div class='w-100 text-lg-center'><h3>Seleziona una voce dal menu!</h3></div>";
        }
        ?>
      </div>
    </main>
  </div>
</div>



<?php $this->push('scripts') ?>
<script src="/resources/js/util.js"></script>
<script src="/api/adv/scripts?filename=home.js"></script>
<script src="/api/scripts?filename=menu-toggle.js"></script>
<?php if ($this->e($user->Privilege) === "1" && isset($_GET['viewUsers'])) { ?>
  <script src="/api/adv/scripts?filename=viewUsers.js"></script>
<?php } ?>
<?php if ($this->e($user->Privilege) === "1" && isset($_GET['addUser'])) { ?>
  <script src="/api/adv/scripts?filename=addUser.js"></script>
<?php } ?>

<?php $this->end() ?>