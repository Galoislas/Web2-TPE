<?php 

include_once __DIR__  . '/../config.php';

class PeliculasModel{
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
            die("Error to conect" . $e->getMessage());
        }
    }

private function _deploy(){

    $sql = "
    CREATE TABLE IF NOT EXISTS peliculas (
        id int(11) NOT NULL AUTO_INCREMENT,
        nombre varchar(100) NOT NULL,
        descripcion text NOT NULL,
        fecha date NOT NULL,
        imagen VARCHAR(255) DEFAULT NULL,
        categoria_id int(11) NOT NULL,
        PRIMARY KEY (id),
        KEY fk_categoria (categoria_id),
        CONSTRAINT fk_categoria
        FOREIGN KEY (categoria_id)
        REFERENCES categorias (id)
        ON DELETE CASCADE
    );
    ";

    $this->db->exec($sql);

    $query = $this->db->query("SELECT COUNT(*) FROM peliculas");
    $count = $query->fetchColumn();

    if($count == 0){

        $queryCategoria = $this->db->query("
            SELECT id FROM categorias LIMIT 1
        ");

        $categoria = $queryCategoria->fetch(PDO::FETCH_OBJ);

        if($categoria){
            $query = $this->db->prepare("
            INSERT INTO peliculas
            (nombre, descripcion, fecha, categoria_id, imagen)
            VALUES (?, ?, ?, ?, ?)
            ");

            $query->execute([
            'La Morgue',
            'Cuando el cuerpo golpeado de una mujer es descubierto...',
            '2016-04-16',
            $categoria->id,
            "https://placehold.co/300x400/png?text=La Morgue"
            ]);
        }
    }
}

    public function getPeliculas(){
        $query = $this->db->prepare("SELECT * FROM peliculas");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getPeliculasById($id){
    $query = $this->db->prepare("SELECT * FROM peliculas WHERE id = ?");
    $query->execute([$id]);

    return $query->fetch(PDO::FETCH_OBJ);
}

public function addPeliculas($nombre, $descripcion, $fecha, $imagen, $categoria_id){
    $query = $this->db->prepare("INSERT INTO peliculas (nombre, descripcion, fecha, imagen, categoria_id) VALUES (?, ?, ?, ?, ?)");
    $query->execute([$nombre, $descripcion, $fecha, $imagen, $categoria_id]);

    return $this->db->lastInsertId();
}

public function deletePeliculas($id){
    $query = $this->db->prepare("DELETE FROM peliculas WHERE id = ?");
    $query->execute([$id]);
    
    return $query->rowCount();
}

public function updatePeliculas($nombre, $descripcion, $fecha, $imagen, $categoria_id, $id){
    $query = $this->db->prepare("UPDATE peliculas SET nombre = ?, descripcion = ?, fecha = ?, imagen = ?, categoria_id = ? WHERE id = ?");
    $query->execute([$nombre, $descripcion, $fecha, $imagen, $categoria_id, $id]);

    return $query->rowCount();
}
}