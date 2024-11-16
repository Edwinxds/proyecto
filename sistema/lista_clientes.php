<?php
  session_start();

      include "../conexion.php";
?>

<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de clientes</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<h1>Lista de clientes</h1>
        <a href="registro_cliente.php" class="btn_new">Crear nuevo cliente</a>
        <form action="buscar_cliente.php" method="get" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <input type="submit" value="buscar" class="btn_search">
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Cédula</th> 
                <th>Telefono</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
            <?php
                //PAGINADOR
            $sql_register = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM cliente WHERE estatus = 1");
            $result_register = mysqli_fetch_array($sql_register);
            $total_registro = $result_register['total_registro'];

            $por_pagina = 5;

            if(empty($_GET['pagina'])){
                $pagina = 1;
            }else{
                $pagina = $_GET['pagina'];
            }

            $desde = ($pagina-1) * $por_pagina;
            $total_paginas = ceil($total_registro/$por_pagina);

            $query = mysqli_query($conection, "SELECT * FROM cliente 
                                                WHERE estatus = 1 
                                                ORDER BY codcliente ASC
                                                LIMIT $desde,$por_pagina");
            

            $result = mysqli_num_rows($query);
            if($result>0){
                while($data=mysqli_fetch_array($query)){
                    if($data["cedula"] == 0){
                        $cedula = 'C/F';
                    }else{
                        $cedula = $data["cedula"];
                    }
        ?>
                <tr>
                    <td><?php echo $data["codcliente"]?></td>
                    <td><?php echo $data["nombre"]?></td>
                    <td><?php echo $data["cedula"]?></td>
                    <td><?php echo $data["telefono"]?></td>
                    <td><?php echo $data["direccion"]?></td>
                    <td>
                        <a class="link_edit" href="editar_cliente.php?id=<?php echo $data["codcliente"]?>">Editar</a>
                        
                        <?php if($_SESSION['rol'] == 1){ ?>     
                        |   
                        <a class="link_delete" href="eliminar_confirmar_cliente.php?id=<?php echo $data["codcliente"]?>">Eliminar</a>
                         <?php } ?>
                    </td>
            </tr>
        <?php
                }
            }

        ?>
        </table>

        <div class="paginador">
            <ul>
            <?php
                if($pagina != 1){
            ?>
                <li> <a href="?pagina=<?php echo 1; ?>">|<</a></li>
                <li> <a href="?pagina=<?php echo $pagina-1;?>">|<<</a></li>
            <?php
               } 
                for ($i=1;$i<=$total_paginas;$i++){
                    if($i == $pagina){
                    echo '<li class="pageSelected">'.$i.'</li>';
                    }else{
                        echo '<li> <a href="?pagina='.$i.'">'.$i.'</a></li>';
                    }
                }
                if($pagina != $total_paginas){
                ?>
                <li> <a href="?pagina=<?php echo $pagina + 1; ?>">>>|</a></li>
                <li> <a href="?pagina=<?php echo $total_paginas; ?>">>|</a></li>
            <?php } ?>
            </ul>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>