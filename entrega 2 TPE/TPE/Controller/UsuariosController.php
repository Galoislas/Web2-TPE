<?php 
require_once __DIR__ . "/../Model/UsuariosModel.php";

class UsuariosController{
    private $model;

    public function __construct(){
        $this->model = new UsuariosModel();
    }

    public function getUsuarios(){
        $usuarios = $this->model->getUsuarios();
        if($usuarios){
            http_response_code(200);
        }else{
            http_response_code(205);
        }

        return $usuarios;
    }

    public function getUsuariosById($id){
        $usuario = $this->model->getUsuariosById($id);

        if($usuario){
            http_response_code(200);
        }else{
            http_response_code(400);
        }
        
        return $usuario;
    }    
    
    public function updateUsuarios($id, $nombre, $contraseña, $rol){
        $usuarios = $this->model->getUsuarios();
        $isId = false;
        foreach($usuarios as $usuario){
            if($usuario->id == $id){
                $isId = true;
            }
        }

        if($isId && !empty($nombre) && !empty($contraseña) && !empty($rol)){
            $this->model->updateUsuarios($id, $nombre, $contraseña, $rol);
            http_response_code(201);
            echo "Successfull request";
        }else{
            http_response_code(204);
            echo "Item not found";
        }

    }

    public function addUsuarios($nombre, $contraseña, $rol="usuario"){
        $this->isEmpty([$nombre, $contraseña], "login");
           
        $usuarios = $this->model->getUsuarios();
        $isAvailable = true;
        
        foreach($usuarios as $usuario){
            if($usuario->nombre == $nombre){
                $isAvailable = false;
            }
        }
        
        if($isAvailable){
            $this->model->addUsuarios($nombre, $contraseña, $rol);
            http_response_code(200);    
            return $isAvailable;
        }else{
            http_response_code(401);
            return $isAvailable;
        }
    }

    public function deleteUsuario($id, $req){
        $this->isAdmin($req);

        $usuarios = $this->model->getUsuarios();
        $isFound = false;
        forEach($usuarios as $usuario){
            if($usuario->id == $id){
                $isFound = true;
            }
        }
        if($isFound){
            http_response_code(200); 
            $this->model->deleteUsuario($id);
            echo "Successfull Request";
        }else{
            http_response_code(204);
            echo "Item not found";
        }
    }

    public function registerUser($nombre, $contraseña){
        $this->isEmpty([$nombre, $contraseña], "login/newAccount/");

        $hashContraseña = password_hash($contraseña, PASSWORD_BCRYPT);
        $isSuccessfull = $this->addUsuarios($nombre, $hashContraseña);
        if($isSuccessfull){
            http_response_code(200);
            header("Location:" . BASE_URL . "login");
            exit();

        }else{
            http_response_code(400);
            header("Location:" . BASE_URL . "login/newAccount/");
            exit();

        }
    }
    
    public function signOut(){
        session_start();

        $_SESSION = [];       
        session_destroy();      

        header("Location: " . BASE_URL . "login");
        exit();
    }

    public function verifyUser($nombre, $contraseña){
        session_start();
        $usuarios = $this->model->getUsuarios();
        $isFound = false;
        $id = -1;
        $rol = "";

        foreach($usuarios as $usuario){

            if($usuario->nombre == $nombre && password_verify($contraseña, $usuario->contraseña)){
                $isFound = true;
                $id = $usuario->id;
                $rol = $usuario->rol;
                }
        }

        if($isFound){
            $_SESSION["logged"] = true;
            $_SESSION["nombre"] = $nombre;
            $_SESSION["id"] = $id;
            $_SESSION["rol"] = $rol;
            var_dump($_SESSION);

            header("Location:" . BASE_URL . "home");
        }else{
            $_SESSION["logged"] = false;
            var_dump($_SESSION);
            header("Location:" . BASE_URL . "login");
            }
        return $isFound;
    }

    private function isAdmin($req){
        if($req->user->rol !== "admin"){
            header("Location: " . BASE_URL . "login");
            exit();
        }
    }
    
    private function isEmpty($items, $url){
        $isEmpty = false;
        foreach($items as $item){
            if(empty($item)){
                $isEmpty = true;
            }
        }

        if($isEmpty){
            header("Location:" . BASE_URL . $url);
            exit();
        }
    }
}

?>