<?php 

include_once __DIR__ . '/../config.php';

class UsuariosModel{
    private $db;
    private $config;
    
    public function __construct(){
       $this->config = new config();
       $host = $this->config->getHost(); 
       $dbName = $this->config->getDbName();
       $user = $this->config->getUser();  
       $password = $this->config->getPassword();
       
        try{
            $this->db = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $user, $password);
            
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->_deploy();
        }catch(Exception $e){
                die("Error al conectar" .$e->getMessage());
        }
    }

    private function _deploy(){

        $query = $this->db->query("SHOW TABLES LIKE 'usuarios'");
        $table = $query->fetch();

        if(!$table){

            $hash = password_hash("admin", PASSWORD_BCRYPT);

            $sql = <<<END

            CREATE TABLE IF NOT EXISTS usuarios (
                id int(11) NOT NULL AUTO_INCREMENT,
                nombre varchar(100) NOT NULL UNIQUE,
                contraseña varchar(255) NOT NULL,
                rol varchar(50) NOT NULL DEFAULT 'usuario',
                PRIMARY KEY (id)
            );

            INSERT INTO usuarios (nombre, contraseña, rol)
            VALUES (
                'webadmin',
                '$hash',
                'admin'
            );

            END;

            $this->db->exec($sql);
        }
    }

       public function getUsuarios(){
            $query = $this->db->prepare("SELECT * FROM usuarios");
            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);
       }

       public function getUsuariosById($id){
            $query = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
            $query->execute([$id]);
            return $query->fetch(PDO::FETCH_OBJ);
       }
       
       public function addUsuarios($nombre, $contraseña, $rol){
            $query = $this->db->prepare("INSERT INTO usuarios (nombre, contraseña, rol) VALUES(?, ?, ?)");
            $query->execute([$nombre, $contraseña, $rol]);
            return $this->db->lastInsertId();

       }

        public function updateUsuarios($id, $nombre, $contraseña, $rol){
            $query = $this->db->prepare("UPDATE usuarios SET nombre = ?, contraseña = ?, rol = ? WHERE id = ?");
            $query->execute([$nombre, $contraseña, $rol, $id]);
            return $this->db->lastInsertId();
        }

        public function deleteUsuario($id){
            $query = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
            $query->execute([$id]);
            return $query->rowCount();
        }
}

?>