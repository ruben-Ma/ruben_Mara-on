<?php
/**
 * Clase PersistentManager 
 *
 * gestiona la conexion con la base de datos.
 * lee credenciales json y crea un objeto PDO
 */
class PersistentManager {

    private static $instance = null;//
    
    private $connection = null;

    // credenciales de la BBDD
    private $dbhost;
    private $dbuser;
    private $dbpass;
    private $dbname;
    private $dbport; // Puerto (opcional, por defecto 3306 para MySQL)

    /**
     * consctructor privado para evitar que se instancie directamente.
     */
    private function __construct() {
        // Obtenemos la ruta absoluta al fichero de credenciales
        $configFile = __DIR__ . '/credentials.json';
        
        try {
            if (!file_exists($configFile)) {// si no existe el fichero lanzamos excepcion
                throw new Exception("ERROR: credentials.json no encontrado en " . $configFile);
            }

            $config = json_decode(file_get_contents($configFile), true);// leemos y decodificamos el json
            
            if (json_last_error() !== JSON_ERROR_NONE) {// si hay error lanzamos excepcion
                throw new Exception("ERROR: Fichero credentials.json mal formado.");
            }

            // asignamos las credenciales a las propiedades
            $this->dbhost = $config['dbhost'];
            $this->dbuser = $config['dbuser'];
            $this->dbpass = $config['dbpass'];
            $this->dbname = $config['dbname'];
            $this->dbport = $config['dbport'] ?? 3306; //  3306 si no esta definido el puerto de mysql

            $this->connect();
            
        } catch (Exception $e) {
            die("Error de configuración de BBDD: " . $e->getMessage());// manejo de error critico de bdbd
        }
    }

    //metodo para obtener la instancia unica 
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new PersistentManager();
        }
        return self::$instance;
    }

    //Crea la conexion PDO y la guarda.
    private function connect() {
        try {
            $dsn = "mysql:host={$this->dbhost};port={$this->dbport};dbname={$this->dbname};charset=utf8mb4";// establecemos el dsn d conexion
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en errores
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // Devuelve arrays asociativos
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->connection = new PDO($dsn, $this->dbuser, $this->dbpass, $options);
            
        } catch (PDOException $e) {// mensaje eerror
            die("Error al conectar con la BBDD: " . $e->getMessage());
        }
    }

    //devuelve la conexion pdo activa
    public function getConnection() {
        return $this->connection;
    }

    // Evita que la instancia sea clonada.
     
    private function __clone() {}

    // evita que la instancia sea deserializada.
    public function __wakeup() {}
}

?>