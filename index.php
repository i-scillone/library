<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test libreria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
<?php
require 'form.php';
require 'debug.php';

$form=new Form($_REQUEST);
$debug=new Debug();
?>
<div class="container">
    <form method="post">
        <p>Cognome: <?= $form->input('cognome','text',['class'=>'form-control']) ?></p>
        <p>Nome: <?= $form->input('nome','text',['class'=>'form-control']) ?></p>
        <p>Sesso: <?= $form->radio('sesso',['m'=>'maschio','f'=>'femmina'],['class'=>'form-check-input']) ?></p>
        <p>VIP? <?= $form->input('vip','checkbox',['class'=>'form-check-input']) ?></p>
        <p><button name="doIt" type="submit" class="btn btn-secondary">Engage!</button></p>
    </form>
<?php
echo '<pre>Dati inviati → '.json_encode($_REQUEST,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT)."</pre>\n";
$debug->log($_POST);
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>