<?php
/**
 * punto de entrada donde se gestiona las app.
 */

require_once('utils/SessionHelper.php');

$lastPage = SessionHelper::getLastPage();// obtenemos la ultima pagina vista, es algo diferente al futbol al principio me he liado con eso

header('Location: app/' . $lastPage);// redirigimos a esa pagina, que es la ulltima visitada
exit;
?>