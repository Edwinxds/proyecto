<?php
    session_start();
    if($_SESSION['rol'] != 1){
        header("location: ./");
    }

    include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST["usuario"]) || empty($_POST['rol']))
        {
            $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
        }else{
            
            $idUsuario = $_POST['idUsuario'];
            $nombre = $_POST['nombre'];
            $email = $_POST['correo'];
            $user = $_POST['usuario'];
            $clave = md5($_POST['clave']);
            $rol = $_POST['rol'];

            $query = mysqli_query($conection,"SELECT * FROM usuario 
                                                        WHERE (usuario = '$user' AND id_usuario != $idUsuario) 
                                                        OR (correo = '$email' AND id_usuario != $idUsuario)");
            
            $result = mysqli_fetch_array($query);
            

            if($result>0){
                $alert='<p class="msg_error">El correo o el usuario ya existe.</p>';
            }else{
                if(empty($_POST['clave']))
                {
                    $sql_update = mysqli_query($conection,"UPDATE usuario
                                                             SET nombre = '$nombre', correo = '$email', usuario = '$user', rol = '$rol' 
                                                             WHERE id_usuario = '$idUsuario'");

                }else{
                    $sql_update = mysqli_query($conection,"UPDATE usuario 
                                                            SET nombre = '$nombre', correo = '$email', usuario = '$user', clave = '$clave', rol = '$rol' 
                                                            WHERE id_usuario = '$idUsuario'");
                }

                if($sql_update){
                    $alert='<p class="msg_save">Usuario actualizado.</p>';
                }else{
                    $alert='<p class="msg_error">Error al actualizar el usuario.</p>';
                }
            }
        }
    }
    //MOSTRANDO DATOS DE USUARIO

    if(empty($_GET['id']))
    {
        header('Location: lista_usuarios.php');
      
    }
    $iduser = $_GET['id'];

    $sql = mysqli_query($conection,"SELECT u.id_usuario, u.nombre, u.correo, u.usuario, (u.rol) AS id_rol, (r.rol) AS rol
                                    FROM usuario u
                                    INNER JOIN rol r
                                    ON u.rol = r.id_rol
                                    WHERE id_usuario = $iduser AND estatus = 1");

    $result_sql = mysqli_num_rows($sql);
    if($result_sql == 0){
        header('Location: lista_usuarios.php');
        
    }else{
        $option = '';
        while($data = mysqli_fetch_array($sql)){
            $iduser  = $data['id_usuario'];
            $nombre  = $data['nombre'];
            $correo  = $data['correo'];
            $usuario = $data['usuario'];
            $idrol   = $data['id_rol'];
            $rol     = $data['rol'];
            
            if($idrol == 1){
                $option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
            
            }elseif($idrol == 2){
                $option = '<option value="'.$idrol.'" select>'.$rol.'</option>';

            }
        }
    }
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Usuario</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="form_register">
            <h1> Actualizar usuario</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

            <form action="" method="post">
                <input type="hidden" name="idUsuario" value="<?php echo $iduser;?>">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre completo" value="<?php echo $nombre?>">
                <label for="correo">Correo electrónico</label>
                <input type="email" name="correo" id="correo" placeholder="Correo electrónico" value="<?php echo $correo?>">
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario" value="<?php echo $usuario?>">
                <label for="clave">Clave</label>
                <input type="password" name="clave" id="clave" placeholder="Clave"  >
                <label for="rol">Tipo de usuario</label>

                <?php
                    include "../conexion.php";

                    $query_rol= mysqli_query($conection,"SELECT * FROM rol");
                    
                    $resul_rol = mysqli_num_rows($query_rol);
                ?>
                <select name="rol" id="rol" class="notItemOne">
                    <?php
                    echo $option;
                        if($resul_rol>0){
                            while($rol = mysqli_fetch_array($query_rol)){
                    ?>
                            <option value="<?php echo $rol["id_rol"] ?>"><?php echo $rol["rol"] ?></option>
                    <?php            
                            }
                        }
                    ?>
                </select>
                <input type="submit"  value="Actualizar usuario" class="btn_save">
            </form>
        </div>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>