<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title><?= $this->e($title) ?></title>

    <link rel="stylesheet" href="/resources/css/bootstrap/bootstrap.min.css">
    <script src="/resources/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/resources/js/jquery/jquery.min.js"></script>
    <?= $this->section('styles') ?>
    <style>
        html,
        body{
            margin:0;
            padding:0;
            overflow-x: hidden;
        }

        *{
            box-sizing: border-box;
        }
        html {
            height: 100vh !important;
        }

        body {
            min-height: 100% !important;
            display: flex;
            flex-direction: column;
        }

        footer {
            margin-top: auto;
        }
    </style>
</head>

<body>
    <?= $this->section('content') ?>

    <?= $this->section('scripts') ?>
</body>

</html>