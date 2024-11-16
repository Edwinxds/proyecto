<?php
    session_start();
    if($_SESSION['rol'] != 1){
        header("location: ./");
    }
        include "../conexion.php";

    if(!empty($_POST)){

        if($_POST['id_usuario'] == 1){
            header("location: lista_usuarios.php");
            exit;
        }
        $id_usuario = $_POST['id_usuario'];

        $query_delete = mysqli_query($conection, "UPDATE usuario SET estatus = 0 WHERE id_usuario = $id_usuario");
    
        //$query_delete = mysqli_query($conection, "DELETE FROM usuario WHERE id_usuario = $id_usuario ");

        if($query_delete){
            header("location: lista_usuarios.php");

        }else{
            echo "Error al eliminar";
        }
    }

    if(empty($_REQUEST['id']) ||$_REQUEST['id'] == 1){
        header("location: lista_usuarios.php");
    }else{
        
        $id_usuario = $_REQUEST['id'];

        $query = mysqli_query($conection,"SELECT u.nombre,u.cedula, u.usuario, r.rol 
                                            FROM usuario = u
                                            INNER JOIN rol = r
                                            ON u.rol = r.id_rol
                                            WHERE u.id_usuario = $id_usuario");
    
        $result = mysqli_num_rows($query);

        if($result>0){
            while($data  = mysqli_fetch_array($query)){
                $nombre  = $data['nombre'];
                $cedula  = $data['cedula'];
                $usuario = $data['usuario'];
                $rol     = $data['rol'];
            }
        }else{
            header("location: lista_usuarios.php");
        }
    }

?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Eliminar usuario</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

    <div class="data_delete">
        <h2>¿Esta seguro de eliminar este usuario?</h2>
        <br>
        <p>Nombre: <span><?php echo $nombre;?></span></p>
        <p>Cédula: <span><?php echo $cedula;?></span></p>
        <p>Usuario: <span><?php echo $usuario;?></span></p>
        <p>Tipo de usuario: <span><?php echo $rol;?></span></p>

        <form method="post" action="">
            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario ?>">
            <a href="lista_usuarios.php" class="btn_cancel">Cancelar</a>
            <input type="submit" value="Aceptar" class="btn_ok">
        </form>
    </div>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>