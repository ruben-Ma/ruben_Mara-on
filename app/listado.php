<?php
require_once('../persistence/conf/PersistentManager.php');
require_once('../persistence/DAO/ActividadDAO.php');
require_once('../utils/SessionHelper.php');

SessionHelper::setLastPage('listado.php');// nos ayuda luego a redirigir a la ultima pagina vista

require_once('../templates/header.php');// incluimos el header


if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) { // procesamos la eliminacion de una actividad

    $idBorrar = (int)$_GET['id'];// recogemos el id a borrar

    $actividadDAO_logic = new ActividadDAO();// instanciamos el DAO para la logica


    $actividad_a_borrar = $actividadDAO_logic->selectById($idBorrar); // verificamos que exista la actividad


    if ($actividad_a_borrar) { // si existe procedemos a borrar
        $actividadDAO_logic->deleteActividad($idBorrar); // llamamos al metodo de borrado
    }


    header('Location: listado.php'); // redirigimos para NO ver esa card ya
    exit;
}


$actividadDAO = new ActividadDAO();// instanciamos el DAO para obtener las actividades

$isFiltering = false; // Para saber qué mensaje mostrar si no hay resultados
$filterDate = '';     // Para el "sticky form"

if (isset($_GET['activityDate']) && !empty($_GET['activityDate'])) {

    $isFiltering = true;
    $filterDate = $_GET['activityDate'];

    $listaActividades = $actividadDAO->selectByDate($filterDate);
} else {
    $listaActividades = $actividadDAO->selectAll();
}

?>

<div class="container-fluid">
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div class="row">
            <div class="col-md-5 ">
                <img class="img-fluid img-rounded" src="../assets/img/main-logo.png" alt="">
            </div>
            <div class="col-md-7 ">
                <h1 class="alert-heading">4VGym, GYM de 4V</h1>
                <p>Ponte en forma y ganaras vida</p>
                <hr />

                <form action="listado.php" method="get" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <input name="activityDate" id="activityDate" class="form-control" type="date"
                            value="<?php echo $filterDate; ?>" />
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filter</button>
                        <a href="listado.php" class="btn btn-outline-secondary my-2 my-sm-0">Limpiar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">

        <?php

        if (empty($listaActividades)) {

            if ($isFiltering) {
                echo '<div class="alert alert-info text-center">
                            <strong>Sin resultados:</strong> No se encontraron actividades para la fecha ' . htmlspecialchars($filterDate) . '.
                          </div>';
            } else {
                echo '<p class="text-center">No hay actividades programadas.</p>';
            }
        } else {
            foreach ($listaActividades as $act) {

                $tipo_actividad = strtolower(trim($act['tipo']));
                $imagen = 'default.png';

                switch ($tipo_actividad) {
                    case 'spinning':
                        $imagen = 'spinning2.png';
                        break;
                    case 'bodypump':
                        $imagen = 'bodypump.png';
                        break;
                    case 'pilates':
                        $imagen = 'pilates.png';
                        break;
                }
        ?>

                <div class=" col-sm-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">

                        <img class="card-img-top w-50 p-3 img-fluid mx-auto"
                            src='../assets/img/<?php echo $imagen; ?>'
                            alt="<?php echo $act['tipo']; ?>">

                        <div class="card-body">

                            <h2 class="card-title display-4"><?php echo $act['lugar']; ?></h2>

                            <p class="card-text lead"><?php echo date('d M Y H:i', strtotime($act['fecha'])); ?></p>

                            <p class="card-text lead"><?php echo $act['monitor']; ?></p>

                        </div>

                        <div class="card-footer d-flex justify-content-center">
                            <div class="btn-group">
                                <a type="button" class="btn btn-success"
                                    href="editar.php?id=<?php echo $act['id_actividad']; ?>">Modificar</a>

                                <a type="button" class="btn btn-danger"
                                    href="listado.php?action=delete&id=<?php echo $act['id_actividad']; ?>"
                                    onclick="return confirm('¿Seguro que quieres borrar?');">Borrar</a>
                            </div>
                        </div>
                    </div>
                </div>

        <?php
            } 
        } 
        ?>

    </div>
</div>

<?php
require_once('../templates/footer.php');
?>