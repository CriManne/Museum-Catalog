<?php $this->layout('layouts::layout', ['title' => "MuPIn - Museo Piemontese dell'Informatica"]) ?>

<?php $this->start("styles"); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
<?php $this->end(); ?>

<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a href="/" class="navbar-brand ps-3"><img src="/favicon.ico" width="30" height="30" class="d-inline-block align-top mx-2">Mupin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/catalog">Catalogo</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/#contacts">Contatti</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/login">Area dipendenti<i class="fa fa-user mx-2" aria-hidden="true"></i></a></li>
                    
                </ul>
            </div>
        </div>
    </nav>
    <?= $this->section('content') ?>
    <footer>
        <div class="py-3 bg-dark">
            <div class="container">
                <p class="m-0 text-center text-white">Copyright &copy; <a class="link-light" href="mailto:mannellacristian@gmail.com">Mannella Cristian</a> 2022</p>
            </div>
        </div>
    </footer>