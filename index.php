<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test libreria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
    .dbg { 
        color: #ffffff; 
        background: black;
        padding: 0 4px;
    }
    </style>
</head>
<body>
<?php
ini_set('display_errors','on');
require 'lib/autoloader.php';
class Person
{
    private $name;
    private $surname;

    public function __construct(string $surname,string $name)
    {
        $this->name=$name;
        $this->surname=$surname;
    }
    public function getFullName(): string
    {
        return $this->name.' '.mb_strtoupper($this->surname);
    }
    public function __toString()
    {
        return $this->getFullName();
    }
}

$form=new \MyClasses\Form($_REQUEST,$_POST,$_GET);
$debug=new \MyClasses\Debug(class: 'dbg');
$db=new \MyClasses\DB('sqlite:magistrati.sqlite');
?>
<div class="container">
    <form method="post">
        <p>Cognome: <?= $form->input('cognome','text',['class'=>'form-control']) ?></p>
        <p>Nome: <?= $form->input('nome','text',['class'=>'form-control']) ?></p>
        <p>Sesso: <?= $form->radio('sesso',['m'=>'maschio','f'=>'femmina'],['class'=>'form-check-input']) ?></p>
        <p>Data di nascita: <?= $form->input('nascita','date',['class'=>'form-control']) ?></p>
        <p>VIP? <?= $form->input('vip','checkbox',['class'=>'form-check-input']) ?></p>
        <p><button name="doIt" type="submit" class="btn btn-primary">Engage!</button></p>
    </form>
<?php
$debug->log($_REQUEST);
$now=new DateTimeImmutable();
$debug->log($debug->inspect($now,false));
$per=new Person($_POST['cognome'],$_POST['nome']);
$debug->log($per);
$debug->inspect($per);
$debug->dump($now,$per);
$sel=$db->query("SELECT cod,cognome||' '||nome FROM pri");
$debug->dump($sel->toAssoc());
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>