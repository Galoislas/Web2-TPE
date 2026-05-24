<?php

function middleware($req){
    session_start();

    if(empty($_SESSION['id'])){
        header("Location:" . BASE_URL . "/login");
        die();
    }
    $req->user = new stdClass();
    $req->user->id = $_SESSION['id'];
    $req->user->nombre = $_SESSION['nombre'];
    $req->user->rol = $_SESSION['rol']; 
    
}


?>