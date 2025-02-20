<?php

use LDAP\Result;

    include "../conexion.php";
    session_start();

    //print_r($_POST); exit;

    if(!empty($_POST)){
            //Extraer datos del producto
        if($_POST['action'] == 'infoProducto'){
            $producto_id = $_POST['producto'];

            $query = mysqli_query($conection,"SELECT codproducto,descripcion,existencia,precio FROM producto
                                                WHERE codproducto = $producto_id AND estatus = 1");

            $result = mysqli_num_rows($query);
            if($result > 0){
                $data = mysqli_fetch_assoc($query);
                echo json_encode($data,JSON_UNESCAPED_UNICODE);
                exit;
            }
            echo 'error';
            exit;
        }


            //Agregar productos a entrada
    if($_POST['action'] == 'addProduct')
    {
            if(!empty($_POST['cantidad']) || !empty($_POST['precio']) || !empty($_POST['producto_id'])){
                $cantidad = $_POST['cantidad'];
                $precio = $_POST['precio'];
                $producto_id = $_POST['producto_id'];
                $usuario_id = $_SESSION['idUser'];

                $query_insert = mysqli_query($conection,"INSERT INTO entradas(codproducto,cantidad,precio,usuario_id)
                VALUES($producto_id,$cantidad,$precio,$usuario_id)");

                if($query_insert){
                    //Ejecutar procedimiento almacenado
                    $query_upd = mysqli_query($conection,"CALL actualizar_precio_producto($cantidad,$precio,$producto_id)");
                    $result_pro = mysqli_num_rows($query_upd);
                if($result_pro > 0){
                    $data = mysqli_fetch_assoc($query_upd);
                    $data['producto_id'] = $producto_id;
                      echo json_encode($data,JSON_UNESCAPED_UNICODE);
                    exit;
                }else{
                    echo 'error';
                }
            }
        }
        
    }
            //Eliminar producto
    if($_POST['action'] == 'delProduct')
    {
        if(empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])){
            echo "Error";
        }else{
            $idproducto = $_POST['producto_id'];
            $query_delete = mysqli_query($conection, "UPDATE producto SET estatus = 0 WHERE codproducto = $idproducto");
            //$query_delete = mysqli_query($conection, "DELETE FROM usuario WHERE id_usuario = $id_usuario ");
            if($query_delete){
                header("location: lista_proveedor.php");
            }else{
                echo "Error al eliminar";
            }
        }
        echo 'error';
        exit;
    }
            //Buscar cliente
    if($_POST['action'] == 'searchCliente'){
        if(!empty($_POST['cliente'])){
            $cedula = $_POST['cliente'];

            $query = mysqli_query($conection,"SELECT * FROM cliente WHERE cedula like '$cedula' AND estatus = 1");

            $result = mysqli_num_rows($query);

            $data = '';
            if($result > 0){
                $data = mysqli_fetch_assoc($query);
            }else{
                $data = 0;
            }
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
            //Registrar clientes-venta
    if($_POST['action'] == 'addCliente'){
       $cedula = $_POST['cedula_cliente'];
       $nombre = $_POST['nom_cliente'];
       $telefono = $_POST['tel_cliente'];
       $direccion = $_POST['dir_cliente'];
       $usuario_id = $_SESSION['idUser'];

       $query_insert = mysqli_query($conection,"INSERT INTO cliente (cedula,nombre,telefono,direccion,usuario_id)
                                                VALUES('$cedula','$nombre','$telefono','$direccion','$usuario_id')");

        if($query_insert){
            $codCliente = mysqli_insert_id($conection);
            $msg = $codCliente;
        }else{
            $msg = 'error';
        }
            echo $msg;
            exit;
    }
            //Agregar producto al detalle temporal
    if($_POST['action'] == 'addProductoDetalle'){
        if(empty($_POST['producto']) || empty($_POST['cantidad'])){
            echo 'error';
        }else{
            $codproducto = $_POST['producto'];
            $cantidad    = floatval($_POST['cantidad']);
            $token       = md5($_SESSION['idUser']);

            $query_iva = mysqli_query($conection,"SELECT iva  FROM configuracion");
            $result_iva = mysqli_num_rows($query_iva);

            $query_detalle_temp = mysqli_query($conection,"CALL add_detalle_temp($codproducto,$cantidad,'$token')");
            $result = mysqli_num_rows($query_detalle_temp);

            $detalleTabla = '';
            $sub_total    = 0;
            $iva          = 0;
            $total        = 0;
            $arrayData    = array();

            if($result > 0){
                if($result_iva > 0){
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva      = $info_iva['iva'];
                }
                while($data = mysqli_fetch_assoc($query_detalle_temp)){
                    $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
                    $sub_total   = round($sub_total + $precioTotal, 2);
                    $total       = round($total + $precioTotal, 2);

                    $detalleTabla .= '<tr>
                                        <td>'.$data['codproducto'].'</td>
                                        <td colspan="2">'.$data['descripcion'].'</td>
                                        <td class="textcenter" >'.number_format($data['cantidad'], 2).'</td>
                                        <td class="textright">'.$data['precio_venta'].' Bs '.'</td>
                                        <td class="textright">'.$precioTotal.' Bs '.'</td>
                                        <td class="">
                                            <a class="link_delete" href="#" onclick="event.preventDefault();
                                                del_product_detalle('.$data['correlativo'].');">Eliminar</a>
                                        </td>
                                    </tr>';
                }
                $impuesto = round($sub_total * ($iva / 100), 2);
                $tl_sniva = round($sub_total - $impuesto, 2);
                $total    = round($tl_sniva + $impuesto, 2);

                $detalleTotales = '<tr>
                                        <td colspan="5" class="textright">SUBTOTAL Bs.</td>
                                        <td class="textright">'.$tl_sniva.'</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="textright">IVA ('.$iva.'%)</td>
                                        <td class="textright">'.$impuesto.'</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="textright">TOTAL Bs.</td>
                                        <td class="textright">'.$total.'</td>
                                     </tr>';
                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
            }else{
                echo 'error';
            }
        }
        exit;
    }
            //Extraer datos de detalle temp
    if($_POST['action'] == 'searchForDetalle'){
                if(empty($_POST['user'])){
                    echo 'error';
                }else{
                    $token = md5($_SESSION['idUser']);

                    $query = mysqli_query($conection,"SELECT tmp.correlativo, tmp.token_user,tmp.cantidad,tmp.precio_venta,p.codproducto,p.descripcion
                                                        FROM detalle_temp tmp
                                                        INNER JOIN producto p
                                                        ON tmp.codproducto = p.codproducto
                                                        WHERE token_user = '$token'");
        
                    $result = mysqli_num_rows($query);

                    $query_iva = mysqli_query($conection,"SELECT iva  FROM configuracion");
                    $result_iva = mysqli_num_rows($query_iva);
        
                    $detalleTabla = '';
                    $sub_total    = 0;
                    $iva          = 0;
                    $total        = 0;
                    $arrayData    = array();
        
                    if($result > 0){
                        if($result_iva > 0){
                            $info_iva = mysqli_fetch_assoc($query_iva);
                            $iva      = $info_iva['iva'];
                        }
                        while($data = mysqli_fetch_assoc($query)){
                            $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
                            $sub_total   = round($sub_total + $precioTotal, 2);
                            $total       = round($total + $precioTotal, 2);
        
                            $detalleTabla .= '<tr>
                                                <td>'.$data['codproducto'].'</td>
                                                <td colspan="2">'.$data['descripcion'].'</td>
                                                <td class="textcenter" step="0.01">'.$data['cantidad'].'</td>
                                                <td class="textright">'.$data['precio_venta'].'</td>
                                                <td class="textright">'.$precioTotal.'</td>
                                                <td class="">
                                                    <a class="link_delete" href="#" onclick="event.preventDefault();
                                                        del_product_detalle('.$data['correlativo'].');">Eliminar</a>
                                                </td>
                                            </tr>';
                        }
                        $impuesto = round($sub_total * ($iva / 100), 2);
                        $tl_sniva = round($sub_total - $impuesto, 2);
                        $total    = round($tl_sniva + $impuesto, 2);
        
                        $detalleTotales = '<tr>
                                                <td colspan="5" class="textright">SUBTOTAL Bs.</td>
                                                <td class="textright">'.$tl_sniva.'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="textright">IVA ('.$iva.'%)</td>
                                                <td class="textright">'.$impuesto.'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="textright">TOTAL Bs.</td>
                                                <td class="textright">'.$total.'</td>
                                             </tr>';
                        $arrayData['detalle'] = $detalleTabla;
                        $arrayData['totales'] = $detalleTotales;
        
                        echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
                    }else{
                        echo 'error';
                    }
                }
                exit;
    }
            //Eliminar productos del detalle temp
    if($_POST['action'] == 'del_product_detalle'){
        if(empty($_POST['id_detalle'])){
            echo 'error';
        }else{
            $id_detalle = $_POST['id_detalle'];
            $token = md5($_SESSION['idUser']);

            $query_iva = mysqli_query($conection,"SELECT iva  FROM configuracion");
            $result_iva = mysqli_num_rows($query_iva);
            $query_detalle_temp = mysqli_query($conection,"CALL del_detalle_temp($id_detalle, '$token')");
            $result = mysqli_num_rows($query_detalle_temp);

            $detalleTabla = '';
            $sub_total    = 0;
            $iva          = 0;
            $total        = 0;
            $arrayData    = array();

        if($result > 0){
            if($result_iva > 0){
                $info_iva = mysqli_fetch_assoc($query_iva);
                $iva      = $info_iva['iva'];
            }
            while($data = mysqli_fetch_assoc($query_detalle_temp)){
                $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
                $sub_total   = round($sub_total + $precioTotal, 2);
                $total       = round($total + $precioTotal, 2);

                $detalleTabla .= '<tr>
                                    <td>'.$data['codproducto'].'</td>
                                    <td colspan="2">'.$data['descripcion'].'</td>
                                    <td class="textcenter" step="0.01">'.$data['cantidad'].'</td>
                                    <td class="textright">'.$data['precio_venta'].'</td>
                                    <td class="textright">'.$precioTotal.'</td>
                                    <td class="">
                                            <a class="link_delete" href="#" onclick="event.preventDefault();
                                                del_product_detalle('.$data['correlativo'].');">Eliminar</a>
                                        </td>
                                    </tr>';
                }
                $impuesto = round($sub_total * ($iva / 100), 2);
                $tl_sniva = round($sub_total - $impuesto, 2);
                $total    = round($tl_sniva + $impuesto, 2);

                $detalleTotales = '<tr>
                                        <td colspan="5" class="textright">SUBTOTAL Bs.</td>
                                        <td class="textright">'.$tl_sniva.'</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="textright">IVA ('.$iva.'%)</td>
                                        <td class="textright">'.$impuesto.'</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="textright">TOTAL Bs.</td>
                                        <td class="textright">'.$total.'</td>
                                    </tr>';
                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
        }else{
                echo 'error';
            }
        }
        exit;
    }
            //Anular venta
    if($_POST['action'] == 'anularVenta'){

        $token = md5($_SESSION['idUser']);
        $query_del = mysqli_query($conection,"DELETE FROM detalle_temp WHERE token_user = '$token'");
        if($query_del){
            echo 'ok';
        }else{
            echo 'error';
        }
    }
            //Procesar venta
     if($_POST['action'] == 'procesarVenta'){
        if(empty($_POST['codcliente'])){
            $codcliente = 1;
        }else{
            $codcliente = $_POST['codcliente'];
        }
        $token   = md5($_SESSION['idUser']);
        $usuario = $_SESSION['idUser'];

        $query = mysqli_query($conection,"SELECT * FROM detalle_temp WHERE token_user = '$token'");
        $result = mysqli_num_rows($query);

        if($result > 0){
            $query_procesar = mysqli_query($conection,"CALL procesar_venta($usuario,$codcliente,'$token')");
            $result_detalle = mysqli_num_rows($query_procesar);

            if($result_detalle > 0){
                $data = mysqli_fetch_assoc($query_procesar);
                echo json_encode($data,JSON_UNESCAPED_UNICODE);
            }else{
                echo "error";
            }
        }else{
            echo "error";
        }
        exit;
    }
            //Info factura
    if($_POST['action'] =='infoFactura'){
        if(!empty($_POST['nofactura'])){
            $nofactura = $_POST['nofactura'];

            $query =  mysqli_query($conection,"SELECT * FROM factura WHERE nofactura = '$nofactura'  AND estatus =  1");
            $result = mysqli_num_rows($query);
            if($result > 0){
                $data = mysqli_fetch_assoc($query);
                echo json_encode($data,JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        echo "error";
        exit;
    }
            //Anular factura
    if($_POST['action'] == 'anularFactura'){
        if(!empty($_POST['noFactura'])){
            $noFactura = $_POST['noFactura'];

            $query_anular = mysqli_query($conection,"CALL anular_factura($noFactura)");
            $result = mysqli_num_rows($query_anular);
            if($result > 0){
                $data = mysqli_fetch_assoc($query_anular);
                echo json_encode($data,JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        echo "error";
        exit;
    }
            //Cambiar contraseña
    if($_POST['action'] == 'cangePassword'){
        if(!empty($_POST['passActual']) && !empty($_POST['passNuevo'])){
            $password = md5($_POST['passActual']);
            $newPass  = md5($_POST['passNuevo']);
            $idUser   = $_SESSION['idUser'];

            $code    = '';
            $msg     = '';
            $arrData = array();

            $query_user = mysqli_query($conection,"SELECT * FROM usuario WHERE clave = '$password' AND id_usuario = $idUser");
            $result = mysqli_num_rows($query_user);
            if($result > 0){
                $query_update = mysqli_query($conection,"UPDATE usuario SET clave = '$newPass' WHERE id_usuario = $idUser");

                if($query_update){
                    $code = '00';
                    $msg  = "Su contraseña se ha actualizado con éxito.";
                }else{
                    $code = '2';
                    $msg  = "No es posible cambiar su contraseña.";
                }
            }else{
                $code = '1';
                $msg  = "La contraseña actual es incorrecta";
            }
            $arrData = array('cod' => $code, 'msg' => $msg);
            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);

        }else{
            echo "error";
        }
        exit;
    }
            //Actualizar datos de la empresa
    if($_POST['action'] == 'updateDataEmpresa'){
  
        if(empty($_POST['txtRIF']) || empty($_POST['txtNombre']) || empty($_POST['txtTelEmpresa']) || empty($_POST['txtEmailEmpresa']) || empty($_POST['txtDirEmpresa']) || empty($_POST['txtIva']))
        {
            $code = '1';
            $msg  = 'Todos los campos son obligatorios';
        }else{
            $intrif     = $_POST['txtRIF'];
            $strNombre  = $_POST['txtNombre'];
            $strRSocial = $_POST['txtRSocial'];
            $intTel     = $_POST['txtTelEmpresa'];
            $strEmail   = $_POST['txtEmailEmpresa'];
            $strDir     = $_POST['txtDirEmpresa'];
            $strIva     = $_POST['txtIva'];

            $queryUpd = mysqli_query($conection,"UPDATE configuracion SET RIF = '$intrif', nombre = '$strNombre', 
                                                                    razon_social = '$strRSocial', telefono = '$intTel', 
                                                                    email = '$strEmail', direccion = '$strDir', iva = $strIva 
                                                                    WHERE id = 1");
            if($queryUpd){
                $code = '00';
                $msg  = "Datos actualizados.";
            }else{
                $code = '2';
                $msg  = "Error al actualizar los datos.";
            }  
        }
        $arrData = array('cod' => $code, 'msg' => $msg);
        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
        exit;
    }

}    
    exit;
?>