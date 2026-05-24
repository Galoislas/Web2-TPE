    <?php 

    include_once __DIR__ . "/../Model/CategoriaModel.php";
    include_once __DIR__ . "/../Model/PeliculasModel.php";
    include_once __DIR__ . "/../View/CategoriaView.php";

    class CategoriaController{
    private $model;
    private $modelPeliculas;
    private $view;

    public function __construct(){
        $this->model = new CategoriaModel();
        $this->modelPeliculas = new PeliculasModel();

        $this->view = new CategoriaView();

    }

    public function getCategorias(){
    $categorias = $this->model->getCategorias();

    if($categorias){
        http_response_code(200);
    } else {
        http_response_code(205);
    }

    return $categorias;
    } 

    public function getCategoriasById($id){
        $categorias = $this->model->getCategoriasById($id);
        
        if($categorias){
            http_response_code(200);
            return $categorias;
        }else{
            http_response_code(404);                                                                        
        }

    }

    public function addCategorias($nombre, $descripcion, $req){
        $this->isAdmin($req);
        $this->isEmpty([$nombre, $descripcion]);

        $categorias = $this->model->getCategorias();
        $isAvailable = true;
        
        foreach($categorias as $categoria){
            if($categoria->nombre == $nombre){
                $isAvailable = false;
            }
        }
        
        if($isAvailable){
            $this->model->addCategorias($nombre, $descripcion);
            header("Location:" . BASE_URL . "home");
        }else{
            http_response_code(400);
            exit();
        }
    }

    public function updateCategorias($id, $nombre, $descripcion, $req){
        if(!isset($req->user)){
            header("Location: " . BASE_URL . "login");
            exit();
        }
        
        $this->isEmpty([$nombre, $descripcion]);

        $categorias = $this->model->getCategorias();
        $isFound = false;

        foreach($categorias as $categoria){
            if($categoria->id == $id){
                $isFound = true;
            }
        }

        if($isFound){
            $this->model->updateCategorias($nombre, $descripcion, $id);

            http_response_code(200);
            header("Location:" . BASE_URL . "home");
        }else{
            http_response_code(404);
            header("Location:" . BASE_URL . "home");
            exit();
        }
    }

    public function deleteCategorias($id, $req){
        $this->isAdmin($req);
        $this->isEmpty([$id]);
        
        $categorias = $this->model->getCategorias();
        $isFound = false;
        
        foreach($categorias as $categoria){
            if($categoria->id == $id){
                $isFound = true;
            }
        }
        if($isFound){
            $this->model->deleteCategorias($id);

            http_response_code(200);
            header("Location:" . BASE_URL . "home");
        }else{
            http_response_code(404);
            exit();
        } 
    }

    public function showCategoria($id, $req){
        $peliculas = $this->modelPeliculas->getPeliculas();

        if($id !== null){
            $categoria = $this->model->getCategoriasById($id);
            $peliculasArr = [];
            foreach($peliculas as $pelicula){
                if($pelicula->categoria_id == $id){
                    $peliculasArr[] = $pelicula;
                }
            }
            $this->view->showCategoria($categoria, $peliculasArr, $req);
        }else{
            http_response_code(404);
            echo "Item not found";
        }
    }

    private function isAdmin($req){
        if(!isset($req->user) || $req->user->rol !== "admin"){
            header("Location: " . BASE_URL . "login");
            exit();
        }
    }

    private function isEmpty($items){
        $isEmpty = false;
        foreach($items as $item){
            if(empty($item)){
                $isEmpty = true;
            }
        }

        if($isEmpty){
            $_SESSION['error'] = "Faltan datos";
            header("Location: " . BASE_URL . "home");
            exit();
        }
    }
    }

    ?>