<?php
    session_start();
    if($_SESSION['rol'] != 1){
        header("location: ./");
    }
        include "../conexion.php";

    if(!empty($_POST)){

        if(empty($_POST['idproveedor'])){
            header("location: lista_proveedor.php");
        }
        $idproveedor = $_POST['idproveedor'];

        $query_delete = mysqli_query($conection, "UPDATE proveedor SET estatus = 0 WHERE codproveedor = $idproveedor");
        
        //$query_delete = mysqli_query($conection, "DELETE FROM usuario WHERE id_usuario = $id_usuario ");

        if($query_delete){
            header("location: lista_proveedor.php");

        }else{
            echo "Error al eliminar";
        }
    }

    if(empty($_REQUEST['id'])){
        header("location: lista_proveedor.php");
   
    }else{
        
        $idproveedor = $_REQUEST['id'];

        $query = mysqli_query($conection,"SELECT * FROM proveedor WHERE codproveedor = $idproveedor");
    
        $result = mysqli_num_rows($query);

        if($result>0){
            while($data  = mysqli_fetch_array($query)){
                $proveedor   = $data['proveedor'];
                $contacto = $data['contacto'];
            }
        }else{
            header("location: lista_proveedor.php");
        }
    }

?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Eliminar Proveedor</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

    <div class="data_delete">
        <h2>¿Esta seguro de eliminar este Proveedor?</h2>
        <br>
        <p>Proveedor: <span><?php echo $proveedor;?></span></p>
        <p>Contacto: <span><?php echo $contacto;?></span></p>

        <form method="post" action="">
            <input type="hidden" name="idproveedor" value="<?php echo $idproveedor?>">
            <a href="lista_proveedor.php" class="btn_cancel">Cancelar</a>
            <input type="submit" value="Eliminar" class="btn_ok">
        </form>
    </div>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>