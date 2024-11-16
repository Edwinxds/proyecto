<?php
$alert= '';
session_start();
if(!empty($_SESSION['active']))
{
    header('location: sistema/'); 
}else{

if (!empty($_POST))
    {
        if(empty($_POST['usuario']) || empty($_POST['clave']))
        {
            $alert = "Ingrese su usuario y su clave";
        }else{
            require_once "conexion.php";
            $user = mysqli_real_escape_string($conection,$_POST['usuario']);
            $pass = md5(mysqli_real_escape_string($conection,$_POST['clave']));

            $query = mysqli_query($conection,"SELECT u.id_usuario,u.nombre,u.correo,u.usuario,r.id_rol,r.rol 
                                                FROM usuario u
                                                INNER JOIN rol r
                                                ON u.rol = r.id_rol
                                                WHERE u.usuario = '$user' AND u.clave = '$pass'");

            $result = mysqli_num_rows($query);

            if($result > 0)
            {
                $data = mysqli_fetch_array($query);
                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $data['id_usuario'];
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['email'] = $data['correo'];
                $_SESSION['user'] = $data['usuario'];
                $_SESSION['rol'] = $data['id_rol'];
                $_SESSION['rol_name'] = $data['rol'];

                header('location: sistema/');
            }else{
                $alert = "El usuario o la clave son incorrectos";
                session_destroy();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login | Sistema de facturación</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    
    

</head>
<body>
    <section id="container">
    
        <form action="" method="post">
            <h3>Iniciar Sesión</h3>
            <img src="img/login.svg" alt="Login">

            <input type="text" name="usuario" placeholder="Usuario">
            <input type="password" name="clave" placeholder="Contraseña">
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
            <input type="submit" value="Ingresar">

            <input type="button" value="Regresar" class="back-button" onclick="goBack()">

<script>
    function goBack() {
        if (history.length > 1) {
            // Si hay historial, regresa a la página anterior
            history.back();
        } else {
            // Si no hay historial, redirige a la página de inicio
            window.location.href = '/home/index.html';
        }
    }
</script>
</a>


        </form>
    </section>


</body>
</html>