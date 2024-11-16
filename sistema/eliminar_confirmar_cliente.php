<?php
    session_start();
    if($_SESSION['rol'] != 1){
        header("location: ./");
    }
        include "../conexion.php";

    if(!empty($_POST)){

        if(empty($_POST['codcliente'])){
            header("location: lista_clientes.php");
        }

        $codcliente = $_POST['codcliente'];

        $query_delete = mysqli_query($conection, "UPDATE cliente SET estatus = 0 WHERE codcliente = $codcliente");
        
        //$query_delete = mysqli_query($conection, "DELETE FROM usuario WHERE id_usuario = $id_usuario ");

        if($query_delete){
            header("location: lista_clientes.php");

        }else{
            echo "Error al eliminar";
        }
    }

    if(empty($_REQUEST['id'])){
        header("location: lista_clientes.php");
   
    }else{
        
        $codcliente = $_REQUEST['id'];

        $query = mysqli_query($conection,"SELECT * FROM cliente WHERE codcliente = $codcliente");
    
        $result = mysqli_num_rows($query);

        if($result>0){
            while($data  = mysqli_fetch_array($query)){
                $nombre  = $data['nombre'];
                $cedula  = $data['cedula'];
            }
        }else{
            header("location: lista_clientes.php");
        }
    }

?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Eliminar Cliente</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

    <div class="data_delete">
        <h2>¿Esta seguro de eliminar este cliente?</h2>
        <br>
        <p>Nombre: <span><?php echo $nombre;?></span></p>
        <p>Cédula: <span><?php echo $cedula;?></span></p>

        <form method="post" action="">
            <input type="hidden" name="codcliente" value="<?php echo $codcliente ?>">
            <a href="lista_clientes.php" class="btn_cancel">Cancelar</a>
            <input type="submit" value="Eliminar" class="btn_ok">
        </form>
    </div>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>