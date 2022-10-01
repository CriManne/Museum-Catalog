<?php

$this->layout('layouts::homepage', ['title' => $title]);
?>
<div id="layoutAuthentication" class="p-5">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header">
                                <h3 class="text-center font-weight-light my-4">Login</h3>
                            </div>
                            <div class="card-body">
                                <form action="/login" method="POST">
                                    <div class="form-floating mb-3">
                                        <?php
                                        if (isset($error)) {
                                            echo "<h4>" . $error . "</h4>";
                                        }
                                        ?>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="email" name="email" type="text" placeholder="name@example.com" />
                                        <label for="email">Email</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="password" name="password" type="password" placeholder="Password" />
                                        <label for="password">Password</label>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                        <button type='submit' name="submitLogin" class="btn btn-primary">Login</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>


