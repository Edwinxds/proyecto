<?php
    session_start();
    include "../conexion.php";
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Nueva Venta</title>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="title_page">
            <h1>Nueva Venta</h1>
        </div>
        <div class="datos_cliente">
            <div class="action_cliente">
                <h4>Datos del cliente</h4>
                <a href="" class="btn_new btn_new_cliente">Nuevo cliente</a>
            </div>
            <form name="form_new_cliente_venta" id="form_new_cliente_venta" class="datos">
                <input type="hidden" name="action" value="addCliente">
                <input type="hidden" id="codcliente" name="codcliente" value="" required>
                <div class="wd30">
                    <label>Cedula</label>
                    <input type="text" name="cedula_cliente" id="cedula_cliente">
                </div>
                <div class="wd30">
                    <label>Nombre</label>
                    <input type="text" name="nom_cliente" id="nom_cliente" disabled required>
                </div>
                <div class="wd30">
                    <label>Teléfono</label>
                    <input type="number" name="tel_cliente" id="tel_cliente" disabled required>
                </div>
                <div class="wd100">
                    <label>Dirección</label>
                    <input type="text" name="dir_cliente" id="dir_cliente" disabled required>
                </div>
                <div id="div_registro_cliente" class="wd100">
                    <button type="submit" class="btn_save">Guardar</button>
                </div>
            </form>
        </div>
        <div class="datos_venta">
            <h4>Datos de Venta</h4>
            <div class="datos">
                <div class="wd50">
                    <label>Vendedor</label>
                    <p><?php echo $_SESSION['nombre']; ?></p>
                </div>
                <div class="wd50">
                    <label>Acciones</label>
                    <div id="aciones_venta">
                        <a href="" class="btn_anular textcenter" id="btn_anular_venta">Limpiar</a>
                        <a href="" class="btn_new textcenter" id="btn_facturar_venta" style="display:none;">Procesar</a>
                    </div>
                </div>
            </div>
        </div>

        <table class="tbl_venta">
            <thead>
                <tr>
                    <th width="100px">Código</th>
                    <th>Descripción</th>
                    <th>Existencia</th>
                    <th width="100px">Cantidad</th>
                    <th class="textright">Precio</th>
                    <th class="textright">Precio Total</th>
                    <th> Acción</th>
                </tr>
                <tr>
                    <td><input type="text" name="txt_cod_producto" id="txt_cod_producto"></td>
                    <td id="txt_descripcion">-</td>
                    <td id="txt_existencia">-</td>
                    <td><input type="text" step="0.01" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
                    <td id="txt_precio" class="textright">0.00</td>
                    <td id="txt_precio_total" class="textright">0.00</td>
                    <td> <a href="#" id="add_product_venta" class="link_add">Agregar</a></td>
                </tr>
                <tr>
                    <th>Código</th>
                    <th colspan="2">Descripción</th>
                    <th>Cantidad</th>
                    <th class="textright">Precio</th>
                    <th class="textright">Precio Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="detalle_venta">
            <!-- Contenido Ajax -->
            </tbody>
            <tfoot id="detalle_totales">
                <!-- Contenido ajax -->
            </tfoot>
        </table>

    </section>
    <?php include "includes/footer.php"; ?>

    <script type="text/javascript">
        $(document).ready(function(){
            var usuarioid = '<?php echo $_SESSION['idUser']; ?>';
            searchForDetalle(usuarioid)

        });
    </script>

</body>
</html>