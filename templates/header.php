<?php
    // Iniciamos la sesión en CADA página para que funcione la navegación
    //De esto tamopoco digo nada que es del antiguio index
    require_once('../utils/SessionHelper.php');
    SessionHelper::start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>4VGym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/octicons/3.5.0/octicons.min.css">
    
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>

<body>

    <nav class="navbar navbar-light navbar-fixed-top navbar-expand-md" role="navigation">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarToggler02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarToggler02">
            <ul class="navbar-nav ">
                <li class="nav-item ">
                    <a class="navbar-brand" href="listado.php"> 
                        <img class="img-fluid rounded d-inline-block align-top" src="../assets/img/small-logo_1.jpg" alt="" width="30" height="30">
                        4VGYM
                    </a>
                </li>
            </ul>
            <div class="ml-auto">
                <a type="button" class="btn btn-info " href="crear.php">
                    <span class="octicon octicon-cloud-upload"></span> Subir Actividad
                </a>
            </div>
        </div>
    </nav>