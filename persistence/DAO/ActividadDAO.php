<?php
// Importamos el GenericDAO que contiene la conexión (PersistentManager)
require_once('../persistence/DAO/GenericDAO.php');

class ActividadDAO extends GenericDAO {

    public function __construct() {
        // Llama al constructor del padre (GenericDAO)
        // que a su vez inicializa el PersistentManager
        parent::__construct();
    }

    //selecciona todas las actividades de la BBDD
    public function selectAll() {
        $sql = "SELECT * FROM actividades ORDER BY fecha DESC";// ordemamos las acvtividades por fecha 
        $conn = $this->getManager()->getConnection();// obtenemos la conexion
        
        $stmt = $conn->prepare($sql);// preparamos la consulta
        $stmt->execute();// ejecutamos la consulta
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);// trae todas las actividades con array asociativo
    }

    

    //muy importante para poder editar y eliminas actiivdades
    public function selectById($id) {//selecciona una actividad por su ID
        $sql = "SELECT * FROM actividades WHERE id_actividad = :id";// consulta preparada
        $conn = $this->getManager()->getConnection();// obtenemos la conexion
        
        $stmt = $conn->prepare($sql);// preparamos la consulta
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);// vinculamos el parametro
        $stmt->execute();// ejecutamos la consulta
        
        return $stmt->fetch(PDO::FETCH_ASSOC);// trae una sola actividad como array asociativo
    }

   
    public function insertActividad($tipo, $monitor, $lugar, $fecha) {// funcion para añadir una actividad a la BBDD
        //consulta preparada para insertar una nueva actividad
        $sql = "INSERT INTO actividades (tipo, monitor, lugar, fecha) 
                VALUES (:tipo, :monitor, :lugar, :fecha)";
        
        $conn = $this->getManager()->getConnection();// obtenemos la conexion
        $stmt = $conn->prepare($sql);// preparamos la consulta
        

        //vinculamos los parametros
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':monitor', $monitor, PDO::PARAM_STR);
        $stmt->bindParam(':lugar', $lugar, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        
        $stmt->execute();// ejecutamos la consulta
    }

        public function deleteActividad($id) {// funcion que nos permite eliminar la actividad por su ID
            //consulta sql para eliminar por id
        $sql = "DELETE FROM actividades WHERE id_actividad = :id";
        $conn = $this->getManager()->getConnection();//obtenemos la conexion
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        $stmt->execute();
    }

   // actualiza una actividad existente
    public function updateActividad($id, $tipo, $monitor, $lugar, $fecha) {// funcion para actualizar una actividad existente
        $sql = "UPDATE actividades 
                SET tipo = :tipo, monitor = :monitor, lugar = :lugar, fecha = :fecha
                WHERE id_actividad = :id";
        
        $conn = $this->getManager()->getConnection();
        $stmt = $conn->prepare($sql);
        // vinculamos todos los parametros
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':monitor', $monitor, PDO::PARAM_STR);
        $stmt->bindParam(':lugar', $lugar, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        
        $stmt->execute();
    }

    public function selectByDate($fecha) {// selecciona actividades por fecha (YYYY-MM-DD)
        
        //  DATE() de MySQL para que 
        // ignore la hora (DATETIME) y compare solo la fecha (DATE)
        $sql = "SELECT * FROM actividades WHERE DATE(fecha) = :fecha ORDER BY fecha ASC";
        
        $conn = $this->getManager()->getConnection();// obtenemos la conexion
        $stmt = $conn->prepare($sql);// preparamos la consulta
        
        // vinculamos la fecha (que viene como 'YYYY-MM-DD')
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        
        $stmt->execute();// ejecutamos la consulta
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);// Retorna un array asociativo
    }
}
?>