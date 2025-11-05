<?php
// Importamos el gestor de conexión
require_once('../persistence/conf/PersistentManager.php');

/**
 * Clase GenericDAO
 *
 * cogido de artean .
 */
abstract class GenericDAO {

    //  guardar el gestor de persistencia
    private $manager;

    /**
     *  instancia del PersistentManager.
     */
    public function __construct() {
        $this->manager = PersistentManager::getInstance();
    }

    //para que los hisjos puuedan obtener la conexion pdo
    protected function getManager() {
        return $this->manager;
    }

}
?>