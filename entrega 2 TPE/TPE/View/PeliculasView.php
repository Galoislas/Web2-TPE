<?php 
class PeliculasView {
    public function showPelicula($pelicula, $categoria, $req){
        include "PeliculasDetalle.phtml";
    }

    function showDelete($pelicula, $categoria_id, $req){
        if(isset($req->user) && $req->user->rol == "admin"){ 
            ?>
                <form action="<?= BASE_URL ?>peliculas/delete/<?= $pelicula->id ?>" method="POST">
                    <input type="hidden" name="categoria_id" value="<?= $categoria_id ?>">
                    <button type="submit">Borrar</button>
                </form>
            <?php 
        } 
    }
   
    function showAdd($categoria, $req){
        if(isset($req->user) && $req->user->rol == "admin"){
            ?>
                <form method="POST" action="<?= BASE_URL ?>peliculas/add/">
                    <h4>Agregar</h4>

                    <label>Nombre:</label><br>
                    <input type="text" name="nombre" placeholder="ingrese nombre de la categoria"><br>

                    <label>Descripcion</label><br>
                    <input type="text" name="descripcion" placeholder="ingrese descripcion de la categoria"><br>

                    <label>Fecha:</label><br>
                    <input type="date" name="fecha" placeholder="ingrese la fecha en que se estreno la pelicula"><br>

                    <label>Imagen:</label><br>
                    <input type="text" name="imagen" placeholder="URL de imagen"><br>

                    <input type="hidden" name="categoria_id" value="<?= $categoria->id ?>"></input>

                    <button type="submit">Añadir</button>
                </form>
            <?php
        }
            
}
function showUpdate($peliculas, $categoria, $req){
    if(isset($req->user)){ ?> 
        <form method="POST" action="<?= BASE_URL ?>peliculas/update/">
                <h4>Reemplazar</h4>

                <label>Nombre:</label><br>
                <input type="text" name="nombre" placeholder="ingrese nombre de la pelicula"><br>

                <label>Descripcion</label><br>
                <input type="text" name="descripcion" placeholder="ingrese descripcion de la pelicula"><br>

                <label>Fecha:</label><br>
                <input type="date" name="fecha" placeholder="ingrese la fecha en que se estreno la pelicula"><br>
                
                <label>Imagen:</label><br>
                <input type="text" name="imagen" placeholder="URL de imagen"><br>

                <input type="hidden" name="categoria_id" value="<?= $categoria->id ?>"></input>

                <select name="id">
                <?php foreach($peliculas as $pelicula): ?>
                    <option value="<?= $pelicula->id ?>">
                    <?= $pelicula->nombre ?> 
                    </option>
                <?php endforeach; ?>
                </select>
                <button type="submit">Actualizar</button>
        </form>
    <?php    
    }
}  
function showError(){
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red;'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
}

}
?>