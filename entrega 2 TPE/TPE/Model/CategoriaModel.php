<?php

include_once __DIR__ . '/../config.php';

class CategoriaModel{
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
       }catch(PDOException $e){
            die("Error to conect".$e->getMessage());
       }
    }

private function _deploy(){

    $sql = "
    CREATE TABLE IF NOT EXISTS categorias (
        id int(11) NOT NULL AUTO_INCREMENT,
        nombre varchar(100) NOT NULL,
        descripcion text NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY nombre (nombre)
    );
    ";

    $this->db->exec($sql);

    $query = $this->db->query("SELECT COUNT(*) FROM categorias");
    $count = $query->fetchColumn();

    if($count == 0){

        $this->db->exec("
            INSERT INTO categorias (nombre, descripcion)
            VALUES (
                'Terror',
                'Todas las peliculas de terror y suspenso esta aca'
            )
        ");
    }
}

    public function getCategorias(){
        $query = $this->db->prepare("SELECT * FROM categorias");
        $query->execute();
        return $query -> fetchAll(PDO::FETCH_OBJ);
    }

    public function getCategoriasById($id){
        $query = $this->db->prepare("SELECT * FROM categorias WHERE id = ?");
        $query->execute([$id]);
        return $query ->fetch(PDO::FETCH_OBJ);
    }

    public function addCategorias($nombre, $descripcion){
        $query = $this->db->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)");
        $query->execute([$nombre, $descripcion]);
        return $this->db->lastInsertId();            
    }

    public function deleteCategorias($id){
        $query = $this->db->prepare("DELETE FROM categorias WHERE id = ?");
        $query -> execute([$id]);
        return $query->rowCount();
    }
    
    public function updateCategorias($nombre, $descripcion, $id){
        $query = $this->db->prepare("UPDATE categorias SET nombre = ?, descripcion = ? WHERE id = ?");
        $query->execute([$nombre, $descripcion, $id]); 
        return $query->rowCount();
    }
    }
?>