<?php
define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');

include_once __DIR__ .  "/controller/CategoriaController.php";
include_once __DIR__ .  "/controller/PeliculasController.php";
include_once __DIR__ .  "/controller/UsuariosController.php";

include_once __DIR__ .  "/view/homeView.php";
include_once __DIR__ .  "/view/UsuariosView.php";


include_once __DIR__ . "/Middleware.php";

if(!isset($_GET['action']) || empty($_GET['action'])){
    $action = 'default';
}else{
    $action = $_GET['action'];
}
$req = new stdClass();    
$params = explode('/', $action);

$resource = $params[0];
$method = $params[1] ?? null;
$id = isset($params[2]) ? $params[2] : null;


switch($resource){
    case 'home':
        $homeView = new HomeView();
        $controllerCategorias = new CategoriaController();

        $categoriasValue = $controllerCategorias->getCategorias();

       if(session_status() === PHP_SESSION_NONE){
        session_start();
       }
       if(!empty($_SESSION["id"])){
        $req->user = new stdClass();
            $req->user->id = $_SESSION['id'];
            $req->user->nombre = $_SESSION['nombre'];
            $req->user->rol = $_SESSION['rol'];
       }
       $homeView->showHome($categoriasValue, $req); 
        
        break;
    case 'categorias':
        $controller = new CategoriaController();
        switch($method){
            case 'get':
                    if($id !== null){
                        $controller->getCategoriasById($id);
                    } else {
                        $controller->getCategorias();
                    }
                break;

                case 'add':
                    Middleware($req);

                    $nombre = $_POST['nombre']; 
                    $descripcion = $_POST['descripcion']; 

                    $controller->addCategorias($nombre, $descripcion, $req);
                break;

                case 'update':
                    Middleware($req);

                    $nombre = $_POST['nombre']; 
                    $descripcion = $_POST['descripcion'];
                    $categoria_id = $_POST['id'];  

                    $controller->updateCategorias($categoria_id, $nombre, $descripcion, $req);
                break;

                case 'delete':
                    Middleware($req);

                    if($id !== null){
                        $controller->deleteCategorias($id, $req);
                    }
                break;

                case 'verCategoria':
                    Middleware($req);
                        $controller->showCategoria($id, $req);
                break;
            default:
                    http_response_code(404);
                    echo 'Route not found';
                break;
            }
        break;

    case 'peliculas' : 
        $controller = new PeliculasController();
        switch($method){
            case 'get':
                    if($id !== null){
                        $controller->getPeliculasById($id);
                    } else {
                        $controller->getPeliculas();
                    }
                break;

                case 'add':
                    Middleware($req);
                    $nombre = $_POST['nombre']; 
                    $descripcion = $_POST['descripcion']; 
                    $fecha = $_POST['fecha']; 
                    $imagen = $_POST['imagen'];
                    $categoria_id = $_POST['categoria_id']; 

                        $controller->addPeliculas($nombre, $descripcion, $fecha, $imagen, $categoria_id, $req);
                    
                break;

                case 'update':
                    Middleware($req);

                    $nombre = $_POST['nombre']; 
                    $descripcion = $_POST['descripcion']; 
                    $fecha = $_POST['fecha']; 
                    $imagen = $_POST['imagen'];
                    $categoria_id = $_POST['categoria_id'];
                    $pelicula_id = $_POST['id'];  

                    $controller->updatePeliculas($nombre, $descripcion, $fecha, $imagen, $categoria_id, $pelicula_id, $req);
                break;

                case 'delete':
                    Middleware($req);
                    $categoria_id = $_POST['categoria_id'];
                    $controller->deletePeliculas($id, $categoria_id, $req);
                break;

                case 'verPelicula':
                        Middleware($req);

                        $controller->showPelicula($id, $req);
                break;
                
            default:
                    http_response_code(404);
                    echo 'Route not found';
                break;
            }
        break;

        case 'usuarios': 
            $controller = new UsuariosController();
            switch($method){
                case 'get':
                        if($id !== null){
                            $controller->getUsuariosById($id);
                        } else {
                            $controller->getUsuarios();
                        }
                    break;

                    case 'add':
                        $nombre = $_POST['nombre']; 
                        $contraseña = $_POST['contraseña']; 
                        $rol = $_POST['rol']; 

                        $controller->addUsuarios($nombre, $contraseña, $rol);
                    break;

                    case 'update':
                        Middleware($req);

                        $nombre = $_POST['nombre']; 
                        $contraseña = $_POST['contraseña']; 
                        $rol = $_POST['rol']; 

                        $controller->updateUsuarios($id, $nombre, $contraseña, $rol);
                    break;

                    case 'delete':
                        Middleware($req);

                        $controller->deleteUsuario($id, $req);
                    break;
                    

                default:
                        http_response_code(404);
                        echo 'Route not found';
                    break;
                }
            break;

            case 'login':
                $controller = new UsuariosController();
                $userView = new UsuariosView();

                switch($method){
                    case 'newAccount':
                        $userView->register();
                        break;

                    case 'verifyUser':
                        $nombre = $_POST['nombre'];
                        $contraseña = $_POST['contraseña'];

                        $controller->verifyUser($nombre, $contraseña);
                        break;

                    case 'registerUser':
                        $nombre = $_POST['nombre'];
                        $contraseña = $_POST['contraseña'];

                        $controller->registerUser($nombre, $contraseña);
                        break;
                    
                    case 'signOut': 
                        $controller->signOut();
                        break;
                default:
                    $userView->login();
                    break;
                }
            break;

            default:
                    http_response_code(404);
                    echo 'Route not found';
            break;
        }
    

?>


