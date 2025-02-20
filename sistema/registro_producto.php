<?php
    session_start();
    if($_SESSION['rol'] != 1){
        header("location: ./");
    }

    include "../conexion.php";
    if(!empty($_POST)){
      
        $alert='';
        if(empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['cantidad']))
        {
            $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';

        }else{
            $proveedor  = $_POST['proveedor'];
            $producto   = $_POST['producto'];
            $precio     = $_POST['precio'];
            $cantidad   = $_POST['cantidad'];
            $usuario_id = $_SESSION['idUser'];

            $foto = $_FILES['foto'];
            $nombre_foto  = $foto['name'];
            $type = $foto['type'];
            $url_temp = $foto['tmp_name'];
            $imgProducto = 'img_producto.png';

                if($nombre_foto != ''){
                    $destino = 'img/uploads/';
                    $img_nombre  = 'img_'.md5(date('d-m-Y H:m:s'));
                    $imgProducto = $img_nombre.'.jpg';
                    $src         = $destino.$imgProducto;
                }
            
                $query_insert = mysqli_query($conection,"INSERT INTO producto (proveedor,descripcion,precio,existencia,usuario_id,foto)
                                                         VALUES ('$proveedor','$producto','$precio','$cantidad','$usuario_id','$imgProducto')");

            if($query_insert){
                if($nombre_foto != ''){
                    move_uploaded_file($url_temp,$src);
                }
                $alert='<p class="msg_save">Producto guardado.</p>';

            }else{
                $alert='<p class="msg_error">Error al guardar el proveedor.</p>';
            } 
        }
    }

?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro producto</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="form_register">
            <h1> Registro producto</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

            <form action="" method="post" enctype="multipart/form-data">
                <label for="proveedor">Proveedor</label>
                    <?php
                        $query_proveedor = mysqli_query($conection,"SELECT codproveedor,proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
                        $result_proveedor = mysqli_num_rows($query_proveedor);
                    ?>
                <select name="proveedor" id="proveedor">
                    <?php
                        if($result_proveedor > 0){
                            while($proveedor = mysqli_fetch_array($query_proveedor)){
                    ?>
                        <option value="<?php echo $proveedor['codproveedor'];?>"><?php echo $proveedor['proveedor']?></option>
                    <?php
                            }
                        }
                    ?>
                </select>
                <label for="producto">Producto</label>
                <input type="text" name="producto" id="producto" placeholder="Nombre del producto">
                <label for="precio">Precio del producto</label>
                <input type="number" name="precio" id="precio" placeholder="Precio de producto"  >
                <label for="cantidad">Cantidad</label>
                <input type="number" step="0.01" name="cantidad" id="cantidad" placeholder="Cantidad del producto">

                <div class="photo">
	                <label for="foto">Foto</label>
                    <div class="prevPhoto">
                    <span class="delPhoto notBlock">X</span>
                    <label for="foto"></label>
                    </div>
                    <div class="upimg">
                    <input type="file" name="foto" id="foto">
                    </div>
                    <div id="form_alert"></div>
                </div>
                <input type="submit"  value="Guardar producto" class="btn_save">   
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>