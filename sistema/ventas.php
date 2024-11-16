<?php
  session_start();

      include "../conexion.php";
?>

<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de ventas</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<h1>Lista de Ventas</h1>
        <a href="nueva_venta.php" class="btn_new">Nueva venta</a>
        <form action="buscar_venta.php" method="get" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="No. Factura">
            <input type="submit" value="buscar" class="btn_search">
        </form>
        <div>
            <h5>Buscar por Fecha</h5>
            <form action="buscar_venta.php" method="get" class="form_search_date">
                <label>De: </label>
                <input type="date" name="fecha_de" id="fecha_de" required>
                <label> A </label>
                <input type="date" name="fecha_a" id="fecha_a" required>
                <button type="submit" class="btn_view">Buscar</button>
            </form>
        </div>

        <table>
            <tr>
                <th>No.</th>
                <th>Fecha/Hora</th>
                <th>Cliente</th> 
                <th>Vendedor</th>
                <th>Estado</th>
                <th class="textright">Total Factura</th>
                <th class="textright">Acciones</th>
            </tr>
            <?php
                //PAGINADOR
            $sql_register = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM factura WHERE estatus != 10");
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

            $query = mysqli_query($conection, "SELECT f.nofactura,f.fecha,f.totalfactura,f.codcliente,f.estatus,
                                                u.nombre as vendedor, cl.nombre as cliente 
                                                FROM factura f
                                                INNER JOIN usuario u
                                                ON f.usuario = u.id_usuario
                                                INNER JOIN cliente cl
                                                ON f.codcliente = cl.codcliente
                                                WHERE f.estatus != 10
                                                ORDER BY f.fecha DESC LIMIT $desde,$por_pagina");
            

            $result = mysqli_num_rows($query);
            if($result>0){
                while($data=mysqli_fetch_array($query)){
                    if($data["estatus"] == 1){
                        $estado = '<span class="pagada">Pagada</span>';
                    }else{
                        $estado = '<span class="anulada">Anulada</span>';
                    }
        ?>
                <tr id="row_<?php echo $data["nofactura"];?>">
                    <td><?php echo $data["nofactura"]?></td>
                    <td><?php echo $data["fecha"]?></td>
                    <td><?php echo $data["cliente"]?></td>
                    <td><?php echo $data["vendedor"]?></td>
                    <td class="estado"><?php echo $estado; ?></td>
                    <td class="textright totalfactura"><span></span><?php echo $data["totalfactura"].' Bs.';?></td></td>
                    <td>
                        <div class="div_acciones">
                            <div>
                                <button class="btn_view view_factura" type="button" cl="<?php echo $data["codcliente"];?>" f="<?php echo $data['nofactura'];?>">Ver</button>
                            </div>
                        <?php if($_SESSION['rol'] == 1){
                            if($data["estatus"] == 1)
                            {
                        ?>
                            <div class="div_factura">
                                <button class="btn_anular anular_factura" fac="<?php echo $data["nofactura"]?>">Anular</button>
                            </div>
                        <?php }else{  ?>
                            <div class="div_factura">
                                <button type="button" class="btn_anular inactive">Anular</button>
                            </div>
                        <?php       }
                                }
                        ?>
                            </div>
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