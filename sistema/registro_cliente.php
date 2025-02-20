<?php
    session_start();

    include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['cedula']) || empty($_POST['telefono']))
        {
            $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
        }else{
            $nombre        = $_POST['nombre'];
            $cedula        = $_POST['cedula'];
            $telefono      = $_POST['telefono'];
            $direccion      = $_POST['direccion'];
            $usuario_id    = $_SESSION['idUser'];

            $result = 0;
            if(is_numeric($cedula)){
                $query = mysqli_query($conection,"SELECT * FROM cliente WHERE cedula = '$cedula'");
                $result = mysqli_fetch_array($query);
            }

            if($result > 0){
                $alert='<p class="msg_error">El número de cedula ya existe.</p>';
            }else{
                $query_insert = mysqli_query($conection,"INSERT INTO cliente (nombre,cedula,telefono,direccion,usuario_id)
                                                         VALUES ('$nombre','$cedula','$telefono','$direccion','$usuario_id')");

                if($query_insert){
                    $alert='<p class="msg_save">Cliente guardado.</p>';
                }else{
                    $alert='<p class="msg_error">Error al guardar el cliente.</p>';
                }
            }          
        }
    }

?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro cliente</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="form_register">
            <h1> Registro cliente</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

            <form action="" method="post">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre completo" >
                <label for="cedula">Cédula</label>
                <input type="number" name="cedula" id="cedula" placeholder="Cédula">
                <label for="telefono">Número de telefono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Número de telefono"  >
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección"  >
          
                <input type="submit"  value="Guardar cliente" class="btn_save">
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>