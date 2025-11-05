<?php
// Incluimos la base (DAO, Session, etc.)
require_once('../persistence/conf/PersistentManager.php');
require_once('../persistence/DAO/ActividadDAO.php');
require_once('../utils/SessionHelper.php');

// no guardamos esta pagina para que nunca se quede en editar
SessionHelper::start();


$errors = []; //  para guardar los errores de validación
$actividadDAO = new ActividadDAO(); // instanciamos dao una vez


if ($_SERVER['REQUEST_METHOD'] === 'GET') {// cargar lso datos de la actividad a editar
    
    if (!isset($_GET['id'])) {// si no viene id redirigimos
        header('Location: listado.php');// redirigimos
        exit;
    }
    
    $id_editar = (int)$_GET['id'];// recogemos el id a editar
    
    $actividad = $actividadDAO->selectById($id_editar);// obtenemos la actividad a editar
    
    if (!$actividad) {// si no existe redirigimos
        header('Location: listado.php');// redirigimos
        exit;
    }

    // si existe preparamos las variables
    $sticky_id = $actividad['id_actividad'];
    $sticky_tipo = $actividad['tipo'];
    $sticky_monitor = $actividad['monitor'];
    $sticky_lugar = $actividad['lugar'];
    
    try {// formateamos la fecha para el input datetime-local
        $sticky_fecha = date('Y-m-d\TH:i', strtotime($actividad['fecha']));
    } catch (Exception $e) {
        $sticky_fecha = ''; // valor por defecto si hay error
    }
    
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {// procesar el formulario de edicion

    $id_actualizar = (int)$_POST['id_actividad']; // ID del campo oculto
    $tipo = $_POST['type'] ?? '';// recogemos los datos del formulario
    $monitor = trim($_POST['monitor'] ?? '');// usamos trim para eliminar espacios en blanco al inicio y final
    $lugar = trim($_POST['place'] ?? '');// recogemos el lugar
    $fecha = $_POST['date'] ?? '';// recogemos la fecha

    $sticky_id = $id_actualizar;
    $sticky_tipo = $tipo;
    $sticky_monitor = $monitor;
    $sticky_lugar = $lugar;
    $sticky_fecha = $fecha;

    //toodos los campos son obligatorios
    if (empty($tipo)) $errors[] = "El campo 'Tipo' es obligatorio.";
    if (empty($monitor)) $errors[] = "El campo 'Monitor' es obligatorio.";
    if (empty($lugar)) $errors[] = "El campo 'Lugar' es obligatorio.";
    if (empty($fecha)) $errors[] = "El campo 'Fecha' es obligatorio.";

    $tipos_validos = ['spinning', 'bodypump', 'pilates'];// tipos validos
    if (!empty($tipo) && !in_array($tipo, $tipos_validos)) {// si el tipo no es valido
        $errors[] = "El 'Tipo' de actividad no es válido. Solo: Spinning, BodyPump o Pilates.";//solo 3 tipos permitidos
    }
    
    if (!empty($fecha)) {// la fecha tiene que ser posterior a la actual
        try {
            $fecha_obj = new DateTime($fecha);// convierte el string del form a objeto DateTime
            $ahora = new DateTime();// fecha y hora actual
            if ($fecha_obj <= $ahora) {//comparamos las fechas
                
                $errors[] = "La 'Fecha' debe ser posterior a la fecha y hora actual.";
            }
        } catch (Exception $e) {
            $errors[] = "El formato de la 'Fecha' no es válido.";
        }
    }
    
    if (empty($errors)) {// si no hay errores actualizamos la actividad
        $actividadDAO->updateActividad($id_actualizar, $tipo, $monitor, $lugar, $fecha);  // actualizamos la actividad
        
        header('Location: listado.php'); // redirigimos al listado
        exit;
    }
}

if (!isset($sticky_id)) { // si no se ha seteado el id redirigimos
    header('Location: listado.php'); // redirigimos
    exit;
}



//igual que crear pero con los datos cargados
require_once('../templates/header.php');
?>

    <div class="container">
    
        <h2 class="mt-4">Editar Actividad (ID: <?php echo $sticky_id; ?>)</h2>
        <hr>

        <?php
        if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <strong>Error de validación:</strong>
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="form-horizontal" method="POST" action="editar.php">
        
            <input type="hidden" name="id_actividad" value="<?php echo $sticky_id; ?>">

            <div class="form-group mb-3">
                <label for="type" class="col-sm-2 control-label">Tipo (*)</label>
                <div class="col-sm-10">
                    <select id="type" class="form-control" name="type">
                        <option value="">-- Selecciona un tipo --</option>
                        <option value="spinning" <?php if ($sticky_tipo == 'spinning') echo 'selected'; ?>>Spinning</option>
                        <option value="bodypump" <?php if ($sticky_tipo == 'bodypump') echo 'selected'; ?>>BodyPump</option>
                        <option value="pilates" <?php if ($sticky_tipo == 'pilates') echo 'selected'; ?>>Pilates</option>
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
                    <input type="text" class="form-control" name="place" id="place" placeholder="Ej: Aula15" 
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
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <a href="listado.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>

<?php
require_once('../templates/footer.php');