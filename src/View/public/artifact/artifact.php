<?php $this->layout('layouts::layout', ['title' => 'Artifact']) ?>
<script defer>
    <?php require('artifact.js'); ?>
</script>

<!-- Product section-->
<section class="py-5" id="artifact">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="..." /></div>
            <div class="col-md-6">
                <div class="small mb-1 d-flex flex-row gap-1"><b>ID:</b><div id="object-id"></div></div>
                <h1 class="display-5 fw-bolder" id="object-title"></h1>                
                <p class="lead" id="object-description"></p>                
            </div>
        </div>
    </div>
</section>
<div class="alert alert-danger" role="alert" id="error-alert"></div>

<div id="debug-container"></div>