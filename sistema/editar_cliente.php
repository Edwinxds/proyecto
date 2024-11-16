<?php
    session_start();
    
    include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST["telefono"]))
        {
            $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
        }else{
            
            $idCliente = $_POST['id'];
            $nombre = $_POST['nombre'];
            $cedula = $_POST['cedula'];
            $telefono = $_POST['telefono'];
            $direccion      = $_POST['direccion'];

            $result = 0;
        if(is_numeric($cedula) and $cedula != 0){
            $query = mysqli_query($conection,"SELECT * FROM cliente 
                                                WHERE (cedula = '$cedula' 
                                                AND codcliente != $idCliente)");
        $result = mysqli_fetch_array($query);
        }
            if($result>0){
                $alert='<p class="msg_error">El número de cedula ya existe.</p>';
            }else{

            if($cedula == ''){
                $cedula = 0;
            }  
                    $sql_update = mysqli_query($conection,"UPDATE cliente
                                                             SET nombre = '$nombre', cedula = $cedula, telefono = '$telefono', direccion = '$direccion' 
                                                             WHERE codcliente = '$idCliente'");

                
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
        header('Location: lista_clientes.php');
    }
    $idcliente = $_GET['id'];

    $sql = mysqli_query($conection,"SELECT * FROM cliente WHERE codcliente = $idcliente AND estatus = 1");
  
    $result_sql = mysqli_num_rows($sql);
    if($result_sql == 0){
        header('Location: lista_clientes.php');
        
    }else{
        while($data = mysqli_fetch_array($sql)){
            $idcliente  = $data['codcliente'];
            $nombre     = $data['nombre'];
            $cedula     = $data['cedula'];
            $telefono   = $data['telefono'];
            $direccion  = $data['direccion'];
        }
    }

?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar cliente</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="form_register">
            <h1> Actualizar cliente</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo $idcliente;?>">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre completo" value="<?php echo $nombre; ?>">
                <label for="cedula">Cédula</label>
                <input type="number" name="cedula" id="cedula" placeholder="Cédula" value="<?php echo $cedula; ?>">
                <label for="telefono">Número de telefono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Número de telefono" value="<?php echo $telefono; ?>">
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección" value="<?php echo $direccion; ?>">
          
                <input type="submit"  value="Actualizar cliente" class="btn_save">
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>