<?php

$this->layout('layouts::layout', ['title' => 'Login'])
?>

<?php $this->push('styles') ?>
<link rel="stylesheet" href="/resources/css/styles.css">
<?php $this->end() ?>
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
  <!-- Navbar Brand-->
  <p class="navbar-brand ps-3">Gestione museo</p>
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
            <div class="nav-link text-light" role="button">
              <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
              <form action="" method="GET"><button type='submit' name='viewUsers'>Visualizza utenti</button></form>
            </div>
            <div class="nav-link" role="button">
              <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
              <form action="" method="GET"><button type='submit' name='addUser'>Aggiungi utente</button></form>
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
            <?php if($this->e($user->Privilege) === "1" && isset($_GET['viewUsers'])){$this->insert('p_admin::viewUsers');}?>
            <?php if($this->e($user->Privilege) === "1" && isset($_GET['addUser'])){$this->insert('p_admin::addUser');}?>
            <?php if(isset($_GET['viewArtifacts'])){$this->insert('p_admin::viewUsers');}?>
            <?php if(isset($_GET['addArtifacts'])){$this->insert('p_admin::viewUsers');}?>
      </div>
    </main>
  </div>
</div>



<?php $this->push('scripts') ?>
<script src="/resources/js/util.js"></script>
<script src="/api/scripts?filename=addUser.js"></script>
<script src="/api/scripts?filename=viewUsers.js"></script>
<?php $this->end() ?>