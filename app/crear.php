<?php
require_once('../persistence/conf/PersistentManager.php');
require_once('../persistence/DAO/ActividadDAO.php');
require_once('../utils/SessionHelper.php');

SessionHelper::setLastPage('crear.php'); // esste nos ayuda luego a redirigir a la ultima pagina vista



$errors = []; // en este array guardaremos los errores de validación

$sticky_tipo = '';
$sticky_monitor = '';
$sticky_lugar = '';
$sticky_fecha = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // comprobamos que la petición sea POST

    $tipo = $_POST['type'] ?? ''; // recogemos los datos del formulario
    $monitor = trim($_POST['monitor'] ?? ''); // usamos trim para eliminar espacios en blanco al inicio y final
    $lugar = trim($_POST['place'] ?? ''); // recogemos el lugar
    $fecha = $_POST['date'] ?? ''; // El input tiene que sere de le formato dado

    // guardamos los valores
    $sticky_tipo = $tipo;
    $sticky_monitor = $monitor;
    $sticky_lugar = $lugar;
    $sticky_fecha = $fecha;

    //los campos son obligatorios para poder crear la actividad susodicha
    if (empty($tipo)) {
        $errors[] = "El campo 'Tipo' es obligatorio.";
    }
    if (empty($monitor)) {
        $errors[] = "El campo 'Monitor' es obligatorio.";
    }
    if (empty($lugar)) {
        $errors[] = "El campo 'Lugar' es obligatorio.";
    }
    if (empty($fecha)) {
        $errors[] = "El campo 'Fecha' es obligatorio.";
    }


    //solo ecxisten tripod  de actividades en 4gym
    $tipos_validos = ['spinning', 'bodypump', 'pilates'];
    if (!empty($tipo) && !in_array($tipo, $tipos_validos)) {
        $errors[] = "El 'Tipo' de actividad no es válido. Solo se permite: Spinning, BodyPump o Pilates.";
    }


    //la fecha tiene que ser posterior a la actual
    if (!empty($fecha)) {
        try {
            $fecha_obj = new DateTime($fecha); // convierte el string del form a objeto DateTime
            $ahora = new DateTime(); // fecha y hora de ahora

            if ($fecha_obj <= $ahora) { //comparamos las fechas
                $errors[] = "La 'Fecha' debe ser posterior a la fecha y hora actual.";
            }
        } catch (Exception $e) { // esto salta si el formato no es correcto 
            $errors[] = "El formato de la 'Fecha' no es válido.";
        }
    }

    // validaciones errores
    if (empty($errors)) {

        $actividadDAO = new ActividadDAO(); // si esta sin ningun error creamos el dao
        $actividadDAO->insertActividad($tipo, $monitor, $lugar, $fecha); // insertamos la actividad

        header('Location: listado.php'); // y nos derigir al listado
        exit;
    }
}



require_once('../templates/header.php');
?>

<div class="container">

    <h2 class="mt-4">Subir Nueva Actividad</h2>
    <hr>

    <?php
    // muestra si tiene errores
    if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <strong>Error de validación:</strong>
            <ul>
                <?php foreach ($errors as $err): ?> <!-- muestra los errores -->
                    <li><?php echo $err; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="form-horizontal" method="POST" action="crear.php">

        <div class="form-group mb-3">
            <label for="type" class="col-sm-2 control-label">Tipo (*)</label>
            <div class="col-sm-10">
                <select id="type" class="form-control" name="type">
                    <option value="">-- Selecciona un tipo --</option>

                    <option value="spinning" <?php if ($sticky_tipo == 'spinning') echo 'selected'; ?>>
                        Spinning
                    </option>
                    <option value="bodypump" <?php if ($sticky_tipo == 'bodypump') echo 'selected'; ?>>
                        BodyPump
                    </option>
                    <option value="pilates" <?php if ($sticky_tipo == 'pilates') echo 'selected'; ?>>
                        Pilates
                    </option>
                </select>
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="monitor" class="col-sm-2 control-label">Monitor (*)</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="monitor" id="monitor" placeholder="Nombre del monitor"
                    value="<?php echo $sticky_monitor; ?>">
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="place" class="col-sm-2 control-label">Lugar (*)</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="place" id="place" placeholder="¿Dónde se realiza la actividad?"
                    value="<?php echo $sticky_lugar; ?>">
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="date" class="col-sm-2 control-label">Fecha (*)</label>
            <div class="col-sm-10">
                <input type="datetime-local" class="form-control" name="date" id="date"
                    value="<?php echo $sticky_fecha; ?>">
            </div>
        </div>

        <div class="form-group mb-3">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Insertar Actividad</button>
                <a href="listado.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<?php
// metemos el footer 
require_once('../templates/footer.php');
?>