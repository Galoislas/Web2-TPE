<?php 
class HomeView{
    public function showHome($categorias, $req){
        include "home.phtml";
    }
    
    function showDelete($categoria, $req){
        if(isset($req->user) && $req->user->rol == "admin"){ 
            ?>
                <a href="<?= BASE_URL ?>categorias/delete/<?= $categoria->id ?>">
                    <button type="button">Borrar</button>
                </a>
            <?php 
        } 
    }
   
    function showAdd($req){
        if(isset($req->user) && $req->user->rol == "admin"){
            ?>
                <form method="POST" action="<?= BASE_URL ?>categorias/add/">
                    <h4>Agregar</h4>

                    <label>Nombre:</label><br>
                    <input type="text" name="nombre" placeholder="ingrese nombre de la categoria"><br>

                    <label>Descripcion</label><br>
                    <input type="text" name="descripcion" placeholder="ingrese descripcion de la categoria"><br>

                    <button type="submit">añadir</button>
                </form>
            <?php
        }
    }

    function showUpdate($categorias,$req){
        if(isset($req->user)){ ?> 
            <form method="POST" action="<?= BASE_URL ?>categorias/update/">
                    <h4>Reemplazar</h4>
                    <label>Nombre:</label><br>
                    <input type="text" name="nombre" placeholder="Ingrese nombre de la categoria"><br>

                    <label>Descripcion</label><br>
                    <input type="text" name="descripcion" placeholder="Ingrese descripcion de la categoria"><br>
                    <select name="id">
                    <?php foreach($categorias as $categoria): ?>
                        <option value="<?= $categoria->id ?>">
                        <?= $categoria->nombre ?> 
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

