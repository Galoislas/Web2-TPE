<?php

require_once __DIR__ . "/../Model/PeliculasModel.php";
require_once __DIR__ . "/../Model/CategoriaModel.php";
require_once __DIR__ . "/../View/PeliculasView.php";

class PeliculasController{
    private $model;
    private $categoriaModel;
    private $view;

    public function __construct(){
        $this->model = new PeliculasModel();
        $this->categoriaModel = new CategoriaModel();
        $this->view = new PeliculasView();
    }

    public function getPeliculas(){
        $peliculas = $this->model->getPeliculas();

        if($peliculas){
            http_response_code(200);
        }else{
            http_response_code(205);
        }

        return $peliculas;

    }

    public function getPeliculasById($id){
        $pelicula = $this->model->getPeliculasById($id);

        if($pelicula){
            http_response_code(200);
        }else{
            http_response_code(400);
        }
        return $pelicula;
    }

    public function addPeliculas($nombre, $descripcion, $fecha, $imagen, $categoria_id, $req){
        $this->isAdmin($req);
        $this->isEmpty([$nombre, $descripcion, $fecha], $categoria_id);

        $imagen = !empty($imagen) ? $imagen : "https://placehold.co/300x400/png?text=" . urlencode($nombre);

        $categoria = $this->categoriaModel->getCategoriasById($categoria_id);

        if($categoria){
            $this->model->addPeliculas($nombre, $descripcion, $fecha, $imagen, $categoria_id);
            
            header("Location: " . BASE_URL . "categorias/verCategoria/". $categoria_id);
            die();
        } else {
            header("Location: " . BASE_URL . "categorias/verCategoria/". $categoria_id);
        }
    }

    public function updatePeliculas($nombre, $descripcion, $fecha, $imagen, $categoria_id, $id, $req){
        if(!isset($req->user)){
            header("Location: " . BASE_URL . "login");
            exit();
        }

        $imagen = !empty($imagen) ? $imagen : "https://placehold.co/300x400/png?text=" . urlencode($nombre);

        $this->isEmpty([$nombre, $descripcion, $fecha], $categoria_id);

        $pelicula = $this->model->getPeliculasById($id);
        $categoria = $this->categoriaModel->getCategoriasById($categoria_id);

        if(!$pelicula || !$categoria){
            http_response_code(404);
            echo "Item not found";
            exit();
        }

        $this->model->updatePeliculas($nombre, $descripcion, $fecha, $imagen, $categoria_id, $id);

        header("Location:" . BASE_URL . "categorias/verCategoria/" . $categoria_id);
        exit();
    }

    public function deletePeliculas($id, $categoria_id, $req){
        $this->isAdmin($req);
        $this->isEmpty([$id], $categoria_id);

        $peliculas = $this->model->getPeliculas();
        $isFound = false;

        foreach($peliculas as $pelicula){
            if($pelicula->id == $id){
                $isFound = true;
            }
        }

        if($isFound){
            $this->model->deletePeliculas($id);
            header("Location:". BASE_URL .  "categorias/verCategoria/". $categoria_id);
            exit();
        }else{
            http_response_code(404);
            echo "Item not found";
        }
    }

    public function showPelicula($id, $req){
        if($id !== null){
            $pelicula = $this->model->getPeliculasById($id);
            $categoria = $this->categoriaModel->getCategoriasById($pelicula->categoria_id);
            $this->view->showPelicula($pelicula, $categoria, $req);
            }
    }

    private function isAdmin($req){
        if(!isset($req->user) || $req->user->rol !== "admin"){
            header("Location: " . BASE_URL . "login");
            exit();
        }
    }

    private function isEmpty($items, $categoria_id){
        $isEmpty = false;
        foreach($items as $item){
            if(empty($item)){
                $isEmpty = true;
            }
        }

        if($isEmpty){
            $_SESSION['error'] = "Faltan datos";
            header("Location: " . BASE_URL . "categorias/verCategoria/". $categoria_id);
            exit();
        }
    }

}



?>