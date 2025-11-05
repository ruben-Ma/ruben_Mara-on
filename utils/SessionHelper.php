<?php
/**
 * El que ayuda a  gestionar las sesiones de las diferentes paginas
 */
class SessionHelper {

    //inicia una sesion si no existia
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    //guarda la pagina actual en la variable que en el index luego busca
    public static function setLastPage($page) {
        self::start();
        $_SESSION['last_page'] = $page;
    }

    //aqui busca la ultima pagina guardadada, o listado o crear, editar evitamos que se quede en la variable ya que no tendria mucho sentido
    public static function getLastPage() {
        self::start();
        
        if (isset($_SESSION['last_page'])) {// si exite la variable en sesion
            return $_SESSION['last_page'];// devuelve esa pagina
        } else {
            return 'listado.php'; // sino a la predefinida que es sesion
        }
    }
}
?>